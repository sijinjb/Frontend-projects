<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice App | Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #6777cd;
            height: 100vh;
        }

        #login .container #login-row #login-column #login-box {
            margin-top: 120px;
            max-width: 600px;
            height: 320px;
            border: 1px solid #9C9C9C;
            background-color: #EAEAEA;
        }

        #login .container #login-row #login-column #login-box #login-form {
            padding: 20px;
        }

        #login .container #login-row #login-column #login-box #login-form #register-link {
            margin-top: -85px;
        }
    </style>
</head>

<body>
    <div id="login">
        <h3 class="text-center text-white pt-5">&nbsp;</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (session('continue'))
                            <div class="alert alert-info">
                                {!! session('continue') !!}
                            </div>
                            @endif
                            @if (session('success'))
                            <div class="alert alert-success">
                                {!! session('success') !!}
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {!! session('error') !!}
                            </div>
                            @endif
                            
                            @if($resetPass == 'show-reset')
                            <form id="password-form" class="form" action="{{ route('auth.password') }}" method="post">
                                <h3 class="text-center">Update Password</h3>
                                @csrf
                                <div class="form-group mt-2">
                                    <label for="password">Password:</label><br>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="cnf-password">Confirm Password:</label><br>
                                    <input type="password" name="cnf-password" id="cnf-password" class="form-control" required>
                                </div>

                                <input type="hidden" name="user" value="{{$user ?? ''}}">
                                <div class="form-group mt-3">
                                    <input type="submit" name="submit" id="update-user" class="btn btn-primary w-100" value="Update Password">
                                </div>
                            </form>
                            @elseif($resetPass == false)
                            <!-- FORGOT EMAIL CONFIRM -->
                            <form id="forgot-email" class="form" action="{{ route('auth.password') }}" method="post">
                                <h4 class="text-center">Enter Registered email</h4>
                                @csrf
                                <div class="form-group mt-2">
                                    <label for="email">Email</label><br>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group mt-3">
                                    <input type="submit" name="submit" id="update-user" class="btn btn-primary w-100" value="Get Reset Link">
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $('#update-user').on('click', (e) => {
        if ($('#password').val() != $('#cnf-password').val()) {
            alert('Passwords does not match');
            return false;
        }
    });
</script>

</html>