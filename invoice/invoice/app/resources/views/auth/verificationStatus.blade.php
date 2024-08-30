<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice App | Verify User</title>
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
            min-height: 320px;
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
                <div id="login-column" class="col-md-8">
                    <div class="col-md-12 d-flex flex-column justify-content-center">
                        @if($valid == -1)
                        <div class="alert alert-danger">
                            <h4>Oops! Something went wrong</h4>
                            <p>Something went wrong while verifying</p>
                        </div>
                        @elseif(!$valid)
                        <div class="alert alert-danger">
                            <h4>Link Expired</h4>
                            <p>The verification link has expired. Please try again</p>
                        </div>
                        @elseif($status > 1)
                        <div class="alert alert-warning">
                            <h4>Needs action!</h4>
                            <p>Your account is being restricted. Please connect with support to identify the cause and solutions.</p>
                        </div>
                        @elseif($status == 1)
                        <div class="alert alert-success">
                            <h4>Yayy!! You're email id is verified</h4>
                            <p>Your account is verified. Please <a href="{{ route('login') }}">click here</a> to login</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</html>