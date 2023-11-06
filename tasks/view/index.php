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
      <a href='/users' class='item '>
        <icon><i class="uil uil-users-alt"></i></icon>
        <itext>Users</itext>
      </a>
      <a href='/tasks' class='item current'>
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
    $task = $jp->get_row("../../database/db/tasks", ["taskid" => $_GET['id']]);
    if(count($task) < 1){
      redirect("/tasks");

    }else{
      $task = $task[0];
      echo "<label class='heads h1'>{$task['title']}</label><br>";
      echo "<label class='heads'>{$task['desc']}</label><br>";
      $color='green';
      $logo = 'check-circle';
      if($task['status'] == 'unfinished'){
        $color='red';
        $logo = 'times-circle ';
      }
      echo "<label>Task Status: <span class='uil uil-$logo' style='color: $color;'> {$task['status']}</span></label><br>";
      if($task['assigned'] == "unassigned"){
        /*TASK ASSIGNING*/
        if($_SESSION['user']['role'] == 'admin'){
          echo "<form method='post' style='display: inline;'>";
          echo "<select name='assid' class='selec'>";
          echo "<option style='display:none;' required>Select User</option>";
          foreach($jp->get("../../database/db/users") as $user){
            echo "<option value='" . $user['uid'] . "'>" . $user['name'] . "</option>";
          }
          echo "</select>";
          echo "<button class='assign' name='assignto' value='{$task['taskid']}'><i class='uil uil-check'></i> Assign</button>";
          echo "</form>";
        }
        else if($_SESSION['user']['role'] == 'manager'){
          echo "<form method='post' style='display: inline;'>";
          echo "<select name='assid' class='selec'>";
          echo "<option style='display:none;' required>Select User</option>";
          foreach($jp->get_row("../../database/db/users", ['role'=>'user']) as $user){
            echo "<option value='" . $user['uid'] . "'>" . $user['name'] . "</option>";
          } 
          echo "</select>";
          echo "<button class='assign' name='assignto' value='{$task['taskid']}'><i class='uil uil-check'></i> Assign</button>";
          echo "</form>";
        }
        else{
          echo "<form method='post' style='display: inline;'>";
          echo "<button class='assign' name='self' value='".json_encode(array($_SESSION['user']['uid'], $task['taskid']))."'><i class='uil uil-check'></i> Take Up Task</button>";
          echo "</form>";
        }
      }else{
        $user = $jp->get_row("../../database/db/users", ["uid" => $task['assigned']]);
        if(count($user) > 0){
        echo "<label>Assigned To: <a class='openurl' href='/users/profile?id={$user[0]['uid']}'>@{$user[0]['name']}</a></label>";
        }else{
          if($_SESSION['user']['role'] == 'admin'){
              echo "<form method='post' style='display: inline;'>";
              echo "<select name='assid' class='selec'>";
              echo "<option style='display:none;' required>Select User</option>";
              foreach($jp->get("../../database/db/users") as $user){
                echo "<option value='" . $user['uid'] . "'>" . $user['name'] . "</option>";
              }
              echo "</select>";
              echo "<button class='assign' name='assignto' value='{$task['taskid']}'><i class='uil uil-check'></i> Assign</button>";
              echo "</form>";
            }
            else if($_SESSION['user']['role'] == 'manager'){
              echo "<form method='post' style='display: inline;'>";
              echo "<select name='assid' class='selec'>";
              echo "<option style='display:none;' required>Select User</option>";
              foreach($jp->get_row("../../database/db/users", ['role'=>'user']) as $user){
                echo "<option value='" . $user['uid'] . "'>" . $user['name'] . "</option>";
              } 
              echo "</select>";
              echo "<button class='assign' name='assignto' value='{$task['taskid']}'><i class='uil uil-check'></i> Assign</button>";
              echo "</form>";
            }
            else{
              echo "<form method='post' style='display: inline;'>";
              echo "<button class='assign' name='self' value='".json_encode(array($_SESSION['user']['uid'], $task['taskid']))."'><i class='uil uil-check'></i> Take Up Task</button>";
              echo "</form>";
          }
      }
      echo "<br>";
      if($_SESSION['user']['role'] == "admin" or $_SESSION['user']['role'] == "manager"){
        echo "<label>Delete Task: <form method='post' style='display:inline;'><button name='del' class='delete' value='{$task['taskid']}'><span style='color: red;' class='uil uil-trash-alt' title='Deleting Task is Permanent, as Task Id will be removed'> <ltext>Delete Task</ltext></span> </button></form></label>";
      } else{
        echo "<label>Delete Task: <span title='Not Allowed to Delete Task' style='color: red;' class='uil uil-info-circle'> Not Allowed</span></label>";
      }
    }
  }
}
}else{
  redirect("/tasks");
}

if(isset($_POST['assignto'])){
  $user = $_POST['assid'];  
  $task = $_POST['assignto'];
  $jp->update_row(
  "../../database/db/tasks", ["taskid" => $task], ["assigned" => $user]);
  reload();
}
if(isset($_POST['self'])){
  $data = json_decode($_POST['self']);
  $user = $data[0];
  $task = $data[1];
  $jp->update_row(
    "../../database/db/tasks", ["taskid" => $task], ["assigned" => $user]);
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