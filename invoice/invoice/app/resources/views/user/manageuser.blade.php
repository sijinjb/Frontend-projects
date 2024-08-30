@extends('layouts.app')

@section('content')
<div class="row">

    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <div class="col-12">
        <table class="table table-striped">
            <thead>
                <!-- <th scope="col">UUID</th> -->
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Status</th>
                <!-- Add more fields as needed -->
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td><a href="{{ route('users.edit', ['uuid' => $user->id]) }}">{{ $user->email }}</a></td>
                    <td>{{ $user->role }}</td>
                    <td> {{ $user->active }} </td>
                    <!-- <td>{{ $user->active ? 'Active' : 'Inactive' }}</td> -->
                    <!-- Add more fields as needed -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
