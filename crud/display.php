<?php include 'connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            padding-top: 20px;
        }

        .btn-add-user {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-add-user:hover {
            background-color: #0056b3;
        }

        .btn-update, .btn-delete {
            padding: 5px 10px;
            margin-right: 5px;
        }

        .btn-update a, .btn-delete a {
            text-decoration: none;
            color: #fff;
        }

        .btn-update {
            background-color: green;
            
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px; /* Added margin at the bottom */
        }

        .pagination a {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #007bff;
            border-radius: 3px;
            text-decoration: none;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="btn btn-add-user my-3"><a href="user.php" class="text-light">Add User</a></button>

        <table class="table">
    <thead>
        <tr>
            <th scope="col">Sl no.</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Place</th>
            <th scope="col">Image</th> 
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // Pagination variables
            $records_per_page = 5;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $records_per_page;

            // Fetch records with pagination
            $sql = "SELECT * FROM crud LIMIT $offset, $records_per_page";
            $result = mysqli_query($con, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['ID'];
                    $name = $row['Name'];
                    $email = $row['Email'];
                    $place = $row['Place'];
                    $imageFileName = $row['image']; // Retrieve image filename from database

                    echo '<tr>
                            <th scope="row">' . $id . '</th>
                            <td>' . $name . '</td>
                            <td>' . $email . '</td>
                            <td>' . $place . '</td>
                            <td><img src="uploads/' . $imageFileName . '" alt="" style="max-width: 100px;"></td> <!-- Display image -->
                            <td>
                                <button class="btn btn-update"><a href="update.php?updateid=' . $id . '">Update</a></button>
                                <button class="btn btn-delete"><a href="delete.php?deleteid=' . $id . '">Delete</a></button>
                            </td>
                        </tr>';
                }
            }
        ?>
    </tbody>
</table>


        <!-- Pagination links -->
        <div class="pagination">
            <?php
                // Pagination links
                $sql_total = "SELECT COUNT(*) AS total FROM crud";
                $result_total = mysqli_query($con, $sql_total);
                $row_total = mysqli_fetch_assoc($result_total);
                $total_records = $row_total['total'];
                $total_pages = ceil($total_records / $records_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            ?>
        </div>
    </div>  
</body>
</html>
