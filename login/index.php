<?php 
session_start();
require_once("../database/db-conf.php");
$jp = new Jasper();
?>
<html>
<head>
  <title>'lumənɛks | Project Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Jost'>
  <link rel='icon' href='../designs/lumenbulb.png'>
  <link rel='stylesheet' href='../designs/style.css'>
</head>
<body>
  <div class='center'>
    <div class='login-box'>
      <img src='../designs/lumenbanner.png' class='bannerImg'>
      <form method='post' class='login-elements'>
        <input type='text' name='un' class='loginInp' placeholder="Username">
        <input type='password' name='pw' class='loginInp' placeholder="Password">
        <button name='logn' class='loginBtn'>Login</button>
      </form>
    </div>
    <?php 
if(isset($_POST['logn'])){
  $usern = $_POST['un'];
  $psw = hash('sha256', $_POST['pw']);
  $data = $jp->get_row("../database/db/users", ["name" => $usern, "password" => $psw]);
  if(count($data) > 0){
    $detailsArray = [
      "name" => $data[0]['name'],
      "uid" => $data[0]['uid'],
      "role" => $data[0]['role']
    ];
    $_SESSION['user'] = $detailsArray;
    redirect("/");
  

  }else{
    echo "<center><p style='color:red;'>Invalid username or password</p></center>";
  }
}
?>
  </div>
</body>
</html>