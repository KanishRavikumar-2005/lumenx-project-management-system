<?php 
session_start();
require_once("../../database/db-conf.php");
$jp = new Jasper();
if(isset($_SESSION['user'])){
  if(count($jp->get_row("../../database/db/users", ["uid" => $_SESSION['user']['uid']])) < 1){
     redirect("/login");
  }

}else{
  redirect("/login");
}
?>
<html>
<head>
  <title>'lumənɛks | Project Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Jost'>
  <link rel='icon' href='../../designs/lumenbulb.png'>
  <link rel='stylesheet' href='../../designs/style.css'>
</head>
<body>
  <leftbar>
    <imagebox>
      <img  class='titleImg' id='titleImage'>
    </imagebox>
    <navigator>
      <a href='/' class='item'>
        <icon><i class="uil uil-estate"></i></icon>
        <itext>Home</itext>
      </a>
      <a href='/mail' class='item current'>
        <icon><i class="uil uil-envelope-alt"></i></icon>
        <itext>In-Mail</itext>
      </a>
      <a href='/users' class='item '>
        <icon><i class="uil uil-users-alt"></i></icon>
        <itext>Users</itext>
      </a>
      <a href='/tasks' class='item '>
        <icon><i class="uil uil-clipboard-notes"></i></icon>
        <itext>Tasks</itext>
      </a>
      <a href='/users/profile?id=<?php echo $_SESSION['user']['uid'];?>' class='item'>
        <icon><i class="uil uil-user-circle"></i></icon>
        <itext>Profile</itext>
      </a>
    </navigator>
  </leftbar>
  <mainbox>
<style>
  .delete{
    background: none;
    color: red;
    border: none;
    cursor: pointer;
    padding: 0px;
  }
  .assign{
    background: none;
    color: green;
    border: none;
    cursor: pointer;
    padding: 0px;
  }

  ltext{
    font-family: jost;
    font-size: 15px;
  }
  .selec{
    display: inline;
    border: none;
    background: none;
    border-bottom-width: 0px;
    border: solid 1px green;
    border-radius: 3px;
    /* color: green; */
    outline: none;
  }
  .openurl{
    color: var(--blue);
    text-decoration: none;
  }
  .openurl:hover{
    text-decoration: underline;
  }
</style>


<div class='add' style='text-align: justify;'>
<?php 
if(isset($_GET['id'])){
  if($_GET['id'] == ""){
    redirect("/tasks");
  }
  else{
    $mail = $jp->get_row("../../database/db/mail", ["mailId" => $_GET['id']]);
    if(count($mail) < 1){
      redirect("/tasks");

    }else{
      $mail = $mail[0];
      $sent = "";
      $recieved = "";
      $outer = true;
      if($mail['sender'] == $_SESSION['user']['uid']){
        $sent = "<label class='heads'>(you)</label>";
        $outer = false;
      }else if($mail['recipient'] == $_SESSION['user']['uid']){
        $recieved = "<label class='heads'>(you)</label>";
        $outer = false;
        }

      if($outer){
        redirect("/mail");
      }
      echo "<label class='heads h1'>{$mail['subject']}</label><br>";
      echo "<label class='heads'>{$mail['body']}</label><br>";
      
      echo "<label>Sender: <a class='openurl' href='/users/profile?id={$mail['sender']}'>@{$mail['sendername']}</a> $sent</label><br>";
      $recipient = $jp->get_row("../../database/db/users", ["uid" => $mail['recipient']]);
      if(count($recipient) < 1){
        redirect("/mail");
      }else{
$recipientName = $recipient[0]['name'];

echo "<label>Recipient: <a class='openurl' href='/users/profile?id={$mail['recipient']}'>@{$recipientName}</a> $recieved</label><br>";
      echo "<br>";
      }
      echo "<label>Date : <label class='heads'>{$mail['date']}</label></label><br>";
      echo "<label>Time : <label class='heads'>UST {$mail['date']}</label></label>";

    }
  }
}else{
  redirect("/tasks");
}

if(isset($_POST['assignto'])){
  $user = $_POST['assid'];  
  $mail = $_POST['assignto'];
  $jp->update_row(
  "../../database/db/tasks", ["taskid" => $mail], ["assigned" => $user]);
  reload();
}
if(isset($_POST['self'])){
  $data = json_decode($_POST['self']);
  $user = $data[0];
  $mail = $data[1];
  $jp->update_row(
    "../../database/db/tasks", ["taskid" => $mail], ["assigned" => $user]);
  reload();
}
if(isset($_POST['del'])){
  $tid = $_POST['del'];
  $jp->remove_row("../../database/db/tasks", ["taskid" => $tid]);
  reload();
}
?>
</div>





  </mainbox>
</body>
</html>