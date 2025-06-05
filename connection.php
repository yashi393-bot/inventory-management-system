<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
   echo "connection not found";
}
// else {
// echo "connection success";
// }
?>


