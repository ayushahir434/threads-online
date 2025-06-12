<?php 
session_start();
include_once('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if (!isset($_SESSION["aid"]) || strlen($_SESSION["aid"]) == 0) {
    header('location:logout.php');
    exit();
}

// For Adding Categories
if (isset($_POST['submit'])) {
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $createdby = $_SESSION['aid'];

    // Use prepared statements to avoid SQL injection
    $stmt = $con->prepare("INSERT INTO category (categoryName, categoryDescription, createdBy) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $category, $description, $createdby);

    if ($stmt->execute()) {
        echo "<script>alert('Category added successfully');</script>";
        echo "<script>window.location.href='manage-categories.php'</script>";
    } else {
        echo "<script>alert('Error adding category');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Threads Online | Add Category</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="js/all.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include_once('includes/header.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Add Category</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Category</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="post">
                                <div class="row mb-3">
                                    <div class="col-2">Category Name</div>
                                    <div class="col-4">
                                        <input type="text" name="category" class="form-control" placeholder="Enter category name" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Category Description</div>
                                    <div class="col-4">
                                        <textarea name="description" class="form-control" placeholder="Enter category description" required></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
