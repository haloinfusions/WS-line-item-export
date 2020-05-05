<?php
    session_start();
    include("config.php");
    if($_SERVER["REQUEST_METHOD"] == "POST") {
       
        // username and password sent from form
        $myusername = mysqli_real_escape_string($db,$_POST['username']);
        $mypassword = mysqli_real_escape_string($db,$_POST['password']);
        
        $mypassword = md5($mypassword);
        
        $sql = "SELECT id FROM user WHERE `username` = '".$_POST['username']."' and `password` = '$mypassword'";
        
        $result = mysqli_query($db,$sql);
        
        
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        
        $active = $row['active'];
        $user_id = $row['id'];
       
        $count = mysqli_num_rows($result);
        
        // If result matched $myusername and $mypassword, table row must be 1 row
        
        if($count == 1) {
            //echo "<center>Logging in as $myusername</center>";
            $_SESSION['user_id'] = $user_id;
            echo "Session id".$_SESSION['user_id'];
            $seshsion = $_SESSION['user_id'];
            echo "<center>Logging in as ".$seshsion."</center>";
            //sleep(5);
            header("location: order_export_secure.php");
        }else {
            $error = "Your Login Name or Password is invalid";
        }
    }
  
    ?>
<html>

<head>
<title>Login Page</title>

<style type = "text/css">
body {
    font-family:Arial, Helvetica, sans-serif;
    font-size:14px;
}
label {
    font-weight:bold;
width:100px;
    font-size:14px;
}
.box {
border:#666666 solid 1px;
}
</style>

</head>

<body bgcolor = "#FFFFFF">

<div align = "center">
<img src="img/cropped-pnghaloi_e-2-1.png" width="25%">
<div style = "width:300px; border: solid 1px #333333; " align = "left">
<div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>

<div style = "margin:30px">

<form action = "" method = "post">
<label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
<label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
<input type = "submit" value = " Submit "/><br />
</form>

<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

</div>

</div>

</div>

</body>
</html>
