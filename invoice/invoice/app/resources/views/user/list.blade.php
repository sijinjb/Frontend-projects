@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Manage User</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Last Updated</th>
                            </thead>
                            <tbody>
                                @foreach($users as $regUser)
                                    <tr>
                                        <td>{{ ucwords($regUser->name) }}</td>
                                        <td>{{ $regUser->email }}</td>
                                        <td>{{ $regUser->phone_number }}</td>
                                        <td>{{ ucwords($regUser->company_name) }}</td>
                                        <td>{{ ucwords($regUser->role) }}</td>
                                        <td>{{ date('d-m-Y h:i a' , strtotime($regUser->updated_at . "+5 hours 30 minutes")) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection