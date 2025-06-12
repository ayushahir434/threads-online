<?php
define('DB_SERVER','127.0.0.1');
define('DB_USER','u884847578_ayush');
define('DB_PASS' ,'pvNw4XpXS#CGe');
define('DB_NAME', 'u884847578_ayush');
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>