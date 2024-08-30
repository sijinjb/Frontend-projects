<?php

namespace App\Http\Controllers;

use App\Models\Client as ModelsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Client extends Controller
{
    /**
     * Create a new client
     **/
    public function create(Request $request)
    {
        $data['user'] = Auth::user();

        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'name' => 'required',
                    'type' => 'required|in:Customer,Supplier',
                    'mobile' => 'required|digits:10|numeric',
                    'gstno' => 'required|size:15',
                    'pan' => 'required|size:10',
                    'billing_address' => 'required',
                    'billing_state' => 'required',
                ]);

                $client = new ModelsClient();
                $client->name = $request->name;
                $client->type = $request->type;
                $client->mobile = $request->mobile;
                $client->gstno = $request->gstno;
                $client->pan = $request->pan;
                $client->billing_address = $request->billing_address;
                $client->billing_state = $request->billing_state;
                $client->created_by = $data['user']->id;

                if ($client->save()) {
                    return redirect()->route('app.client.list')->with('success', 'Client created successfully');
                }
                $data['error'] = "Failed to save client, please try again";
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            $data['error'] = "Failed to save client." . $th->getMessage();
        }

        return view('clients.create', $data);
    }

    /**
     * Show All clients
     **/
    public function list(Request $request)
    {
        $data['user'] = Auth::user();
        $data['clients'] = ModelsClient::join('users', 'clients.created_by', '=', 'users.id')->select([
            'clients.*',
            'users.name as user_name'
        ])->get();

        return view('clients.list', $data);
    }

    /**
     * Edit Client
     *
     * @param Request $request
     * @param String $uuid unique uuid
     **/
    public function edit(Request $request, string $uuid)
    {
        $data['user'] = Auth::user();
        $data['client'] = ModelsClient::where('uuid', '=', $uuid)->first();
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
                'type' => 'required|in:Customer,Supplier',
                'mobile' => 'required|digits:10|numeric',
                'gstno' => 'required|size:15',
                'pan' => 'required|size:10',
                'billing_address' => 'required',
                'billing_state' => 'required',
            ]);

            $data['client']->name = $request->name;
            $data['client']->type = $request->type;
            $data['client']->mobile = $request->mobile;
            $data['client']->gstno = $request->gstno;
            $data['client']->pan = $request->pan;
            $data['client']->billing_address = $request->billing_address;
            $data['client']->billing_state = $request->billing_state;

            if ($data['client']->save()) {
                return redirect()->route('app.client.list')->with('success', 'Client updated successfully');
            }
            $data['error'] = "Failed to update client, please try again";
        }

        return view('clients.edit', $data);
    }

    /**
     * Delete Client
     * 
     * @param String $uuid unique uuid
     **/
    public function delete(Request $request ,string $uuid)
    {
        if ($uuid) {
            try {
                $client = ModelsClient::where('uuid', '=', $uuid)->first();
                if ($client) {
                    $client->delete();
                     return redirect()->route('app.client.list')->with('success', 'Client deleted successfully');
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            }
            return redirect()->route('app.client.list')->with('error', 'Failed to delete client');
        }

        abort(404, 'Unknown Delete Request');
    }
}
