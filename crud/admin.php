<?php
include 'connect.php';

session_start();


// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Admin credentials (replace these with your actual admin credentials)
    $admin_username = 'admin';
    $admin_password = 'admin123';

    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate admin credentials
    if($username === $admin_username && $password === $admin_password) {
        // Store admin login status in session
        $_SESSION['admin_logged_in'] = true;
        
        // Redirect to admin dashboard
        header('Location: user.php');
        exit;
    } else {
        $error_message = "Invalid username or password";
    }
}

// If the user is already logged in, redirect to user.php
// if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
//     header('Location: admin.php');
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="password"] {
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            border-radius: 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if(isset($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
