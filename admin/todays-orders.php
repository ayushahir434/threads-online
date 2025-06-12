<?php
session_start();
include('include/config.php');
if (empty($_SESSION['alogin'])) {
    header('location:index.php');
    exit();
}

date_default_timezone_set('Asia/Kolkata'); // Set timezone
$currentTime = date('d-m-Y h:i:s A', time());

// Fetch today's orders
$f1 = "00:00:00";
$from = date('Y-m-d') . " " . $f1;
$t1 = "23:59:59";
$to = date('Y-m-d') . " " . $t1;

$query = mysqli_query($con, "SELECT users.name as username, users.email as useremail, users.contactno as usercontact, products.productName as productname, orders.orderDate as orderdate, orders.id as id FROM orders JOIN users ON orders.userId = users.id JOIN products ON products.id = orders.productId WHERE orders.orderDate BETWEEN '$from' AND '$to'");
if (!$query) {
    die("SQL Error: " . mysqli_error($con));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Today's Orders</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="images/icons/css/font-awesome.css">
    <script src="scripts/jquery-1.9.1.min.js"></script>
    <script src="scripts/datatables/jquery.dataTables.js"></script>
</head>
<body>
<?php include('include/header.php'); ?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <?php include('include/sidebar.php'); ?>
            <div class="span9">
                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>Today's Orders</h3>
                        </div>
                        <div class="module-body">
                            <table class="datatable-1 table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email / Contact No</th>
                                        <th>Product</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr>
                                            <td><?= htmlentities($cnt); ?></td>
                                            <td><?= htmlentities($row['username']); ?></td>
                                            <td><?= htmlentities($row['useremail']) . " / " . htmlentities($row['usercontact']); ?></td>
                                            <td><?= htmlentities($row['productname']); ?></td>
                                            <td><?= htmlentities($row['orderdate']); ?></td>
                                            <td>
                                                <a href="order-details.php?oid=<?= htmlentities($row['id']); ?>" class="btn btn-info">Details</a>
                                            </td>
                                        </tr>
                                        <?php $cnt++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.content -->
            </div><!-- /.span9 -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.datatable-1').DataTable();
    });
</script>
</body>
</html>
