<?php

namespace App\Http\Controllers;

use App\Mail\VerifyUser;
use App\Models\User as ModelsUser;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class User extends Controller
{
    const USER_STATUS = [
        "inactive" => 0,
        "active" => 1,
        "fraud" => 2 ,   
        "banned" => 3,
       
    ];

    /**
     * Register User
     * 
     **/
    public function register(Request $request)
    {
        try {
            if ($request->has(['name', 'email', 'password'])) {

                /* Check if already exist */
                $existingUser = ModelsUser::where('email', '=', $request->email)->first();
                if ($existingUser) {
                    if ($existingUser->active == 1) {
                        return back()->withErrors(['failed' => 'User Already exist and is active!']);
                    } else if ($existingUser->active == 0) {
                        $route = route('user.update', ['u' => Crypt::encrypt(['email' => $existingUser->email]), "s" => true]);
                        return back()->with('continue', "User already exists! <a href='$route'>Activate User</a>?");
                    }
                }

                $user = new ModelsUser();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role = $request->has('role') ? $request->role : 'cashier';
                $user->active = 0;

                if ($user->save()) {
                    $this->sendVerificationEmail($user, ['subject' => 'Verify your Email ID']);
                } else {
                    return back()->withErrors(['failed' => 'Failed to Register']);
                }

                return back()->with('success', "Verification email sent. Please click on the link and verify.");
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['failed' => 'Operation failed', 'error' => $th->getMessage()]);
        }
    }

    /**
     * Authenticate user
     *
     * @param Request $request Request
     * @return View|Redirect
     **/
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return redirect()->intended('/dashboard'); // Redirect to the intended URL or any other URL
            }

            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    /**
     * Logout user
     *
     * @throws Redirect
     **/
    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }

    /**
     * User Dashboard
     *
     * @param Request $request Request
     * @return View|Redirect
     **/
    public function dashboard(Request $request)
    {
        $data['meta'] = [
            'title' => 'Dashboard'
        ];
        $data['user'] = Auth::user();
        return view('dashboard', $data);
    }

    /**
     * forgot password
     **/
    public function forgotPassword(Request $request, string $u = "")
    {
        $data = ['resetPass' => false];
        try {
            /* If has reset link */
            if ($u != "") {
                $decrypted = Crypt::decrypt($u);
                if ($decrypted['valid'] >= strtotime('now')) {
                    $user = ModelsUser::where('email', '=', $decrypted['email'])->first();
                    if ($user) {
                        $data['resetPass'] = 'show-reset';
                        $data['user'] = $u;
                    }
                } else {
                    return view('auth.forgotPassword', $data)->with('error', 'Link expired. Try again');
                }
            }

            /* Form post for sending reset email and reset password */
            if ($request->isMethod('POST')) {
                if ($request->has('email')) {
                    $user = ModelsUser::where('email', '=', $request->email)->first();
                    // dd($user);
                    if ($user) {
                        if ($user->active > 1) {
                            return back()->with('error', "Your account is being restricted. Please connect with support to identify the cause and solutions before reset.");
                        }
                        $this->sendVerificationEmail($user, [
                            'subject' => 'Password reset',
                            'content' => "Please click on the below button to reset your account password. Please note, link is valid only for 15 minutes.",
                            'validity' => "+15 minutes",
                            'route' => "auth.password",
                        ]);
                        return back()->with('success', "Password reset link has been sent to email successfully");
                    }
                    return back()->withErrors(['email' => 'Email not found']);
                }

                if ($request->has(['password', 'user'])) {
                    $decrypted = Crypt::decrypt($request->user);
                    $user = ModelsUser::where('email', '=', $decrypted['email'])->first();
                    if ($user) {
                        $user->password = Hash::make($request->password);
                        if ($user->save()) {
                            return back()->with('success', "Password reset success. Please <a href='" . route('login') . "'>login</a>");
                        }
                    }
                    return back()->with('error', "Password reset failed. Please try again or contact support if this persists.");
                }
                return back()->withErrors(['email' => 'Action not authorized']);
            }

            return view('auth.forgotPassword', $data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            return view('auth.forgotPassword', $data)->with('error', 'Something went wrong. Please try again');
        }
    }

    /**
     * Update user data.
     **/
    public function updateUser(Request $request, string $u = "")
    {
        try {
            $decrypted = Crypt::decrypt($u);
            $user = ModelsUser::where('email', '=', $decrypted['email'])->first();

            if ($request->has('s')) {
                $this->sendVerificationEmail($user, ['subject' => 'Verify your Email ID']);
                return back()->with('success', "Verification email sent. Please click on the link and verify.");
            }

            $linkValid = strtotime('now') <= $decrypted['valid'];
            if (!$linkValid) {
                return view('auth.verificationStatus', [
                    'valid' => $linkValid,
                    'status' => 0
                ]);
            }

            if ($user) {
                if ($request->has('v')) {
                    if ($user->active == self::USER_STATUS['inactive']) {
                        $user->active = self::USER_STATUS['active'];
                        $user->save();
                        Cache::add("user_settings_{$user->id}", false);
                    }
                }
            }

            return view('auth.verificationStatus', [
                'valid' => $linkValid,
                'status' => $user->active
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            return view('auth.verificationStatus', [
                'valid' => -1,
                'status' => 0
            ]);
        }
    }

    /**
     * Add/Update user details
     **/
    public function details(Request $request)
    {
        try {
            $data['user'] = Auth::user();
            $key = "user_details_{$data['user']->id}";
            $userDetails = UserDetails::where("user_id", "=", $data['user']->id)->first();
            if ($request->isMethod("POST")) {
                $rules = [
                    'address' => 'required',
                    'company_name' => 'required',
                    'billing_address' => 'required',
                    'gstno' => 'required|size:15',
                    'pan' => 'required|size:10',
                    'phone_number' => 'required|numeric|digits:10',
                    'billing_state' => 'required',
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }

                if (!$userDetails) {
                    $userDetails = new UserDetails();
                }

                $userDetails->company_name = $request->company_name;
                $userDetails->address = $request->address;
                $userDetails->billing_address = $request->billing_address;
                $userDetails->gstno = $request->gstno;
                $userDetails->pan = $request->pan;
                $userDetails->phone_number = $request->phone_number;
                $userDetails->billing_state = $request->billing_state;
                $userDetails->user_id = $data['user']->id;
                $logs = [
                    "message" => "added user details",
                    "request" => $request->all(),
                    "by" => $data['user']->name,
                    "ts" => date('d-m-Y h:i a')
                ];
                if (isset($userDetails->logs) || $userDetails->logs) {
                    $existLogs = json_decode($userDetails->logs, true);
                    $logs = array_merge($existLogs, $logs);
                }

                $userDetails->logs = json_encode($logs);
                if ($userDetails->save()) {
                    Cache::put($key, json_encode($userDetails->toArray()));
                    return back()->with("success", "User details updated successfully");
                }
                return back()->with("error", "Failed to update user details");
            }
            $data['userDetails'] = $userDetails;
            return view("user.details", $data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            return back()->with("error", "Something went wrong!");
        }
    }

    /**
     * user list
     *
     * @param Request $request
     **/
    public function list(Request $request)
    {
        $user = Auth::user();
        if ($user->role == "admin" || $user->role == "super") {
            $users = ModelsUser::select(['user.name','user.email','user.role','us.phone_number','us.updated_at','us.company_name'])->join("user_details as us", "user.id", "=", "us.user_id")->get();
            return view("user.list", ["user" => $user, "users" => $users]);
        }
        abort(401, 'You do not have access to this page');
        // try {
        // } catch (\Throwable $th) {
        //     Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
        //     return back()->with("error", "Something went wrong!");
        // }
    }

    private function sendVerificationEmail(ModelsUser $user, array $content = [])
    {
        Mail::to($user->email)->send(new VerifyUser([
            'name' => $user->name,
            'role' => $user->role,
            'link' => route($content['route'] ?? 'user.update', ['u' => Crypt::encrypt(['email' => $user->email, 'valid' => strtotime($content['validity'] ?? '+1 hour')]), "v" => true]),
            'subject' => $content['subject'] ?? 'Verify your email ID',
            'content' => $content['data'] ?? "Please click on the below button to verify your email id. Once verified only you'll be able to login. Please note, link is valid only for 1 hour.",
        ]));
    }
}
