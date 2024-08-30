@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Create New User</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <form action="{{ route('users.store') }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="u-name">Name</label>
                                <input type="text" name="name" id="u-name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-email">Email</label>
                                <input type="email" name="email" id="u-email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="u-role">Role</label>
                                <select name="role" id="u-role" class="form-control" required>
                                    <option value="">Select user role</option>
                                    <option value="Cashier" {{ old('role') == 'Cashier' ? 'selected':'' }}>Cashier</option>
                                    <option value="Manager" {{ old('role') == 'Manager' ? 'selected':'' }}>Manager</option>
                                </select>
                            </div>

                            <!-- Add more fields as needed -->

                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-user-plus"></i>
                                </span>
                                <span class="text">Create New User</span>
                            </button>

                        </form>
                    </div>
                    <div class="col-5">
                        <!-- Display success message if it exists in the session -->
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <!-- Display validation errors if any -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
