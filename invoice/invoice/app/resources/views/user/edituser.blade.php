@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', ['id' => $user->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="Cashier" {{ $user->role == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                            <option value="Manager" {{ $user->role == 'Manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="active">Status</label>
                        <select name="active" id="active" class="form-control" required>
                            <option value="inactive" {{ $user->active == 'inactive' ? 'selected' : '' }}>inactive</option>
                            <option value="active" {{ $user->active == 'active' ? 'selected' : '' }}>active</option>
                            <option value="fraud" {{ $user->active == 'fraud' ? 'selected' : '' }}>fraud</option>
                            <option value="banned" {{ $user->active == 'banned' ? 'selected' : '' }}>banned</option>
                        </select>


                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
