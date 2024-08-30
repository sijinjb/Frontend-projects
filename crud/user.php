<?php
include 'connect.php';

// Check if the "uploads" directory exists, if not, create it
$uploadsDirectory = 'uploads/';
if (!file_exists($uploadsDirectory)) {
    mkdir($uploadsDirectory, 0777, true); // Create the directory with full permissions
}

// Fetch states from the database
$sql = "SELECT name FROM states";
$result = mysqli_query($con, $sql);
$states = mysqli_fetch_all($result, MYSQLI_ASSOC);

$errors = array();

if(isset($_POST['submit'])){
  
  // Server-side validation
  if(empty($_POST['name'])) {
    $errors['name'] = "Name is required";
  } else {
    $name=$_POST['name'];
  }

  if(empty($_POST['email'])) {
    $errors['email'] = "Email is required";
  } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format";
  } else {
    $email=$_POST['email'];
  }

  if(empty($_POST['place'])) {
    $errors['place'] = "Place is required";
  } else {
    $place=$_POST['place'];
  }

  // Image upload
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      $errors['image'] = "File is not an image.";
      $uploadOk = 0;
    }
  }
  
  // Check file size
  if ($_FILES["image"]["size"] > 5000000) {
    $errors['image'] = "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    $errors['image'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  
  // If there are no errors, proceed with inserting data into database
  if(empty($errors) && $uploadOk) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $imageFileName = basename($_FILES["image"]["name"]);
      $sql="INSERT INTO crud (name,email,place,image,upload_time) VALUES ('$name','$email','$place','$imageFileName',NOW())";
      $result=mysqli_query($con,$sql);
      if($result){
        header('location:display.php');
      } else {
        die(mysqli_error($con));
      }
    } else {
      $errors['image'] = "Sorry, there was an error uploading your file.";
    }
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
    
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet">
    
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
      .form-select,
      input[type="file"] {
        border-radius: 5px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        padding: 10px;
        width: 100%;
        box-sizing: border-box;
      }

      .error {
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
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

    <title>Add User</title>
  </head>
  <body>
    <div class="container my-5">
      <h1 class="text-center fw-lighter">Add user</h1>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" placeholder="Enter your name" name="name" autocomplete="off">
          <?php if(isset($errors['name'])) { echo '<div class="error">'.$errors['name'].'</div>'; } ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="Enter your Email" name="email" autocomplete="off">
          <?php if(isset($errors['email'])) { echo '<div class="error">'.$errors['email'].'</div>'; } ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Place</label>
          <select class="form-select" name="place">
            <option value="" disabled selected>Select your place</option>
            <?php foreach ($states as $state): ?>
              <option value="<?php echo $state['name']; ?>"><?php echo $state['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <?php if(isset($errors['place'])) { echo '<div class="error">'.$errors['place'].'</div>'; } ?>
        </div> 
        
        <div class="mb-3">
          <label class="form-label">Upload Image</label>
          <input type="file" class="form-control" name="image">
          <?php if(isset($errors['image'])) { echo '<div class="error">'.$errors['image'].'</div>'; } ?>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </form>
    </div>  

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

    <script>
      $(document).ready(function() {
        $('.form-select').select2();
      });
    </script>
  </body>
</html>
