
<?php
include 'connect.php';
// Fetch states from the database
$sql = "SELECT name FROM states";
$result = mysqli_query($con, $sql);
$states = mysqli_fetch_all($result, MYSQLI_ASSOC);


$id=$_GET['updateid'];
$sql="select * from crud where id=$id";
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($result);
$name=$row['Name'];
$email=$row['Email'];
$place=$row['Place'];
if(isset($_POST['submit'])){
 
  
  $name=$_POST['name'];
  $email=$_POST['email'];
  $place=$_POST['place'];

  $sql="update crud set id='$id',name='$name',email='$email',place='$place' where id=$id";
  $result=mysqli_query($con,$sql);
  if($result){
    // echo "Data Updated Successfully!!!";
    header('location:display.php');
  }else{
    die(mysqli_error($con));

  }
}
?>


<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet">

    <title>Hello, world!</title>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 50px;
        }

        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        .form-select {
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        button[type="submit"] {
            border-radius: 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center fw-lighter">Update User</h1>

    <form name="myForm" method="post" onsubmit="return validateForm()">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter your name" name="name" autocomplete="off" value="<?php echo $name; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter your Email" name="email" autocomplete="off" value="<?php echo $email; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Place</label>
            <select class="form-select" name="place">
                <option value="" disabled selected>Select your place</option>
                <?php foreach ($states as $state): ?>
                    <option value="<?php echo $state['name']; ?>" <?php if ($state['name'] == $place) echo 'selected'; ?>><?php echo $state['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
</div>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Select2 JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

<script>
    function validateForm() {
        var name = document.forms["myForm"]["name"].value;
        var email = document.forms["myForm"]["email"].value;
        var place = document.forms["myForm"]["place"].value;

        if (name == "" || email == "" || place == "") {
            alert("All fields must be filled out");
            return false;
        }
        return true;
    }
</script>

<script>
    $(document).ready(function() {
        $('.form-select').select2();
    });
</script>
</body>
</html>
