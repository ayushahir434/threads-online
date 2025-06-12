<?php
session_start();
include_once 'includes/config.php';
$oid=intval($_GET['oid']);
 ?>
<script language="javascript" type="text/javascript">
function f2()
{
window.close();
}ser
function f3()
{
window.print(); 
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Order Tracking Details</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="anuj.css" rel="stylesheet" type="text/css">
</head>
<style>
  /* style.css */

  /* General Body Styling */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
  }

  /* Wrapper Div */
  div {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  /* Title Section */
  .fontpink2 {
    font-size: 1.5em;
    color: #4CAF50;
    text-align: center;
    margin-bottom: 20px;
  }

  /* Table Styling */
  table {
    width: 100%;
    border-collapse: collapse;
  }

  td {
    padding: 10px;
    vertical-align: top;
  }

  td.fontkink1 {
    font-weight: bold;
    color: #555;
    width: 30%;
  }

  td.fontkink {
    color: #333;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  tr:nth-child(even) td.fontkink {
    background-color: #f0f0f5;
  }

  /* Horizontal Rule */
  hr {
    border: 0;
    height: 1px;
    background-color: #ddd;
    margin: 20px 0;
  }

  /* Button Styling */
  button {
    padding: 10px 15px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
  }

  button:hover {
    background-color: #45a049;
  }

  /* Media query for tablets */
@media screen and (min-width: 600px) and (max-width: 1024px) {
    /* Adjust the container width */
    div {
        margin: 20px;
        padding: 15px;
        width: auto;
    }

    /* Table adjustments for smaller screens */
    table {
        font-size: 14px;
    }

    td {
        padding: 8px;
    }

    td.fontkink1 {
        width: 40%; /* Increase label width for better readability */
    }

    /* Font sizes for better readability */
    .fontpink2 {
        font-size: 1.2em;
    }

    /* Adjust button size */
    button {
        padding: 8px 12px;
        font-size: 13px;
    }
}

</style>
<body>

<div style="margin-left:50px;">
 <form name="updateticket" id="updateticket" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">

    <tr height="50">
      <td colspan="2" class="fontkink2" style="padding-left:0px;"><div class="fontpink2"> <b>Order Tracking Details !</b></div></td>
      
    </tr>
    <tr height="30">
      <td  class="fontkink1"><b>order Id:</b></td>
      <td  class="fontkink"><?php echo $oid;?></td>
    </tr>
    <?php 
$ret = mysqli_query($con,"SELECT * FROM ordertrackhistory WHERE orderId='$oid'");
$num=mysqli_num_rows($ret);
if($num>0)
{
while($row=mysqli_fetch_array($ret))
      {
     ?>
		
    
    
      <tr height="20">
      <td class="fontkink1" ><b>At Date:</b></td>
      <td  class="fontkink"><?php echo $row['postingDate'];?></td>
    </tr>
     <tr height="20">
      <td  class="fontkink1"><b>Status:</b></td>
      <td  class="fontkink"><?php echo $row['status'];?></td>
    </tr>
     <tr height="20">
      <td  class="fontkink1"><b>Remark:</b></td>
      <td  class="fontkink"><?php echo $row['remark'];?></td>
    </tr>

   
    <tr>
      <td colspan="2"><hr /></td>
    </tr>
   <?php } }
else{
   ?>
   <tr>
   <td colspan="2">Order Not Process Yet</td>
   </tr>
   <?php  }
$st='Delivered';
   $rt = mysqli_query($con,"SELECT * FROM orders WHERE id='$oid'");
     while($num=mysqli_fetch_array($rt))
     {
     $currrentSt=$num['orderStatus'];
   }
     if($st==$currrentSt)
     { ?>
   <tr><td colspan="2"><b>
      Product Delivered successfully </b></td>
   <?php } 
 
  ?>
</table>
 </form>
</div>

</body>
</html>

     