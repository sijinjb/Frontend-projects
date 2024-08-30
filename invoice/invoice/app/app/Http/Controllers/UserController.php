<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLog; 
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Method to list all users
    public function list()
    {
        $users = User::all();
        return view('user.manageuser', compact('users'));
    }

    // Method to show the user creation form
    public function create()
    {
        return view('user.adduser');
    }

    // Method to store a newly created user
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'role' => 'required|string|in:Cashier,Manager', // Validation rule for the role field
        ]);

        // Create a new user instance
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make('123456'); // You may want to generate a random password instead
        // Add more fields as needed

        // Save the user
        $user->save();

        // Create a new log entry
   
        // Redirect back to the manageuser page with success message
        return redirect()->route('users.list')->with('success', 'User created successfully.');
    }

    // Method to show the user edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edituser', compact('user'));
    }
    

    // Method to update an existing user
public function update(Request $request, $id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,'.$id, // Exclude current user's email from unique check
        'role' => 'required|string|in:Cashier,Manager', // Validation rule for the role field
        'active' => 'required|string|in:inactive,active,fraud,banned', // Validation rule for the active field
    ]);

    // Find the user by ID
    $user = User::findOrFail($id);

    // Update user details
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;
    $user->active = $request->active; // Update the active attribute

    // Save the user
    $user->save();

    UserLog::create([
        'user_id' => $user->id,
        'activity' => 'user_update',
        'log' => 'User updated',
        'updated_by' => auth()->id(), // Assuming you are using authentication and want to track who updated the user
    ]);


    // Redirect back to the manage user list page with success message
    return redirect()->route('users.list')->with('success', 'User updated successfully.');
}
}