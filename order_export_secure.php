<?php session_start();
if (!(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '')) {
    header ("Location: login.php");
}
?>

<html>

<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
<center>
<form method="post" action="order_export.php">
Start: <input type="date" name="start" id="start">
End: <input type="date" name="end" id="end">
<input type="submit">
</form>
</center>
</body>
