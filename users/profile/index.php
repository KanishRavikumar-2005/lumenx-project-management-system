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
      <a href='/mail' class='item'>
        <icon><i class="uil uil-envelope-alt"></i></icon>
        <itext>In-Mail</itext>
      </a>
      <a href='/users' class='item'>
        <icon><i class="uil uil-users-alt"></i></icon>
        <itext>Users</itext>
      </a>
      <a href='/tasks' class='item'>
        <icon><i class="uil uil-clipboard-notes"></i></icon>
        <itext>Tasks</itext>
      </a>
      <a href='/users/profile?id=<?php echo $_SESSION['user']['uid'];?>' class='item <?php if($_SESSION['user']['uid'] == $_GET['id']){ echo "current";}?>'>
        <icon><i class="uil uil-user-circle"></i></icon>
        <itext>Profile</itext>
      </a>
    </navigator>
  </leftbar>
  <mainbox>
    <style>

      .changp{
        border-style: solid;
        border-width: 2px;
        border-color: var(--blue);
        border-radius: 3px;
        padding: 10px;
      }
    </style>
 <div class='add'>
  <?php 
if(isset($_GET['id'])){
  if($_GET['id'] == ""){
    redirect("/users");
  }
  else{
    $user = $jp->get_row("../../database/db/users", ["uid" => $_GET['id']]);
    if(count($user)<1){
      redirect("/users");
    }else{
      $user = $user[0];
      $tasks = $jp->get_row("../../database/db/tasks", ["assigned" => $_GET['id']]);
      $name = ucfirst($user['name']);
      echo "<label class='heads h1'>{$name}</label><br>";    
      echo "<label class='heads'>@{$user['name']}</label><br>";
      echo "<label class=''>Role: {$user['role']}</label><br>";
      if($user['uid'] == $_SESSION['user']['uid']){
      echo "
      <div class='changp'>
        <form method='POST'>
          <label for='old-password'>Old Password:</label>
          <input type='password' class='mainInp' id='old-password' name='old_password' placeholder='Enter Old Password'><br>
      
          <label for='new-password'>New Password:</label>
          <input type='password' class='mainInp' id='new-password' name='new_password' placeholder='Enter New Password'><br>
      
          <button type='submit' class='mainBtn' name='change_password'>Change Password</button>
        </form>
        </div>
      ";
      
      if(isset($_POST['change_password'])){
        $userid = $_SESSION['user']['uid'];
        $oldpassword = hash('sha256', $_POST['old_password']);
        $newpassword = hash('sha256', $_POST['new_password']);
      
        changePassword($userid, $oldpassword, $newpassword);
      }
      echo "<br><form method='post'>
      <button name='lout' class='delete' style='font-size: 17px;'><i class='uil uil-signout'></i> Logout</button>
      </form>";
      }
    }
  }
}else{
  redirect("/users");
}

if(isset($_POST['lout'])){
  $_SESSION['user']['name'] = "";
  $_SESSION['user']['uid'] = "";
  $_SESSION['user']['role'] = "";
  reload();
}
function changePassword($userid, $oldpassword, $newpassword){
  $jsp = new Jasper();
  $user = $jsp->get_row("../../database/db/users", ["uid" => $userid]);
  if(count($user)<1){
    echo "<label style='color:red;'>User not found</label>";
  }else{
    $user = $user[0];
    if($oldpassword == $user['password']){
      $jsp->update_row("../../database/db/users", ["uid" => $userid], ["password" => $newpassword]);
      echo "<label style='color:green;'>Password Changed Successfully</label>";
    }else{
      echo "<label style='color:red;'>Old Password Doesn't Match </label>";
    }
  }
}
?>
 </div>
<style>
  .styled-table {
      border-collapse: collapse;
      margin: 25px 0;
      font-size: 0.9em;
      font-family: jost;
      min-width: 60%;
      table-layout: fixed;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
  }
  .styled-table thead tr {
      background-color: #009879;
      color: #ffffff;
      text-align: left;
  }
  .styled-table th,
  .styled-table td {
      padding: 12px 15px;
      overflow-x: scroll;
  }
  .styled-table tbody tr {
      border-bottom: 1px solid #dddddd;
  }

  .styled-table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
  }

  .styled-table tbody tr:last-of-type {
      border-bottom: 2px solid #009879;
  }
  .styled-table tbody tr.active-row {
      font-weight: bold;
      color: #009879;
  }
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
  @media screen and (max-width: 700px){
    .styled-table{
      width: 100%;
    }
    ltext{
      display: none;
    }
  }
</style>
    <label class='heads h1'>All User Tasks</label>

    <table class='styled-table'>
      <thead>
      <tr>
        <th>ID</th>
        <th>Task</th> 
        <th>Status</th>
      </tr>
      </thead> 
          <tbody>
    <?php
    $tasks = $jp->get_row("../../database/db/tasks", ["assigned" => $user['uid']]);
    foreach ($tasks as $task) {
        echo "<tr>";
        echo "<td><a class='openurl' href='/tasks/view?id={$task['taskid']}'><i class='uil uil-external-link-alt'></i> {$task['taskid']}</a></td>";
        echo "<td>" . $task['title'] . "</td>";
        $color='green';
        $logo = 'check-circle';
        $code = "| <button name='mUnfin' value='{$task['taskid']}' class='assign'><span class='uil uil-times-circle' style='color: red;'> Mark Unfinished</span></button>";
        if($task['status'] == 'unfinished'){
          $color='red';
          $logo = 'times-circle ';
          $code = "| <button name='mfin' value='{$task['taskid']}' class='assign'><span class='uil uil-check-circle' style='color: green;'> Mark Finished</span></button>";
        }
        if($_SESSION['user']['uid'] != $user['uid']){
          $code = "";
        }
        echo "<td><span class='uil uil-$logo' style='color: $color;'> {$task['status']}</span> <form method='post' style='display: inline;'>$code</form></td>";
        echo "</tr>";

    }

if(isset($_POST['mUnfin'])){
  $taskId= $_POST['mUnfin'];
  $jp->update_row("../../database/db/tasks", ['taskid' => $taskId], ['status' => 'unfinished']);
  reload();
}
  if(isset($_POST['mfin'])){
    $taskId= $_POST['mfin'];
    $jp->update_row("../../database/db/tasks", ['taskid' => $taskId], ['status' => 'finished']);
    reload();
  }
    ?>
          </tbody>
    </table>

    <label class='heads h1'> All User Announcements</label>
     <div class='announcements'>
          <?php 
    $ann = $jp->get_row("../../database/db/announcements", ['uid' => $user['uid']], "reverse");
    if(count($ann) > 0){

        foreach($ann as $announcement){
          $by = "";
          if($announcement['role'] == "admin"){
            $by = "<span class='admin'>@{$announcement['username']}</span>";
          }else{
            $by = "<span class='user'>@{$announcement['username']}</span>";
          }
          echo "<div class='announcement'>";
          echo $by;
          echo "<label class='title'>" . $announcement['title'] . "</label>";
          echo "<label class='desc'>" . $announcement['body'] . "</label>";
          echo "<label class='datetime'>" . $announcement['date'] . " UTC " . $announcement['time']. "</label>";
          echo "</div>";
        }

    }else{
      echo "<center><label class='heads h1'><i class='uil uil-frown'></i></label><br><label class='heads'> No Announcements Yet</label></center>";
    }
    ?>
        </div>
  </mainbox>
</body>
</html>