<?php 
session_start();
require_once("../database/db-conf.php");
$jp = new Jasper();
if(isset($_SESSION['user'])){
  if(count($jp->get_row("../database/db/users", ["uid" => $_SESSION['user']['uid']])) < 1){
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
  <link rel='icon' href='../designs/lumenbulb.png'>
  <link rel='stylesheet' href='../designs/style.css'>
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
    <?php if($_SESSION['user']['role'] == 'admin' or $_SESSION['user']['role'] == 'manager'): ?>
    <label class='heads h1'>Create New Task</label>

<div>
  <form method="post" class='add'>
    <input type="text" class='mainInp' name="title" placeholder="title">
    <textarea  class='mainInp  textarea' name="desc" placeholder="Descreption"></textarea>
    <button type="submit" class='mainBtn' name="create_task">Create Task</button>
  </form>
</div>
<?php endif; ?>
    <?php
    if(isset($_POST['create_task'])){
  $taskid = $jp->uuid(6);
  $tasktitle = $_POST['title'];
  $taskdesc = $_POST['desc'];
  $assigned = "unassigned";

  create_task($taskid, $tasktitle, $taskdesc, $assigned);
}

function create_task($taskid, $tasktitle, $taskdesc, $assigned) {
  global $jp;
  $newTask = array(
    "taskid" => $taskid,
    "title" => $tasktitle,
    "desc" => $taskdesc,
    "assigned" => $assigned,
    "status" => "unfinished"
  );
  $jp->add_row("../database/db/tasks", $newTask);
}
?>
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
    <label class='heads h1'>All Tasks</label>

<table class='styled-table'>
  <thead>
  <tr>
    <th>ID</th>
    <th>Task</th>
    <th>Assigned</th>
    <th>Delete</th>   
    <th>Status</th>

  </tr>
  </thead> 
      <tbody>
<?php
$current_user = $_SESSION['user']['name'];
$tasks = $jp->get("../database/db/tasks");
foreach ($tasks as $task) {
    echo "<tr>";
    echo "<td><a class='openurl' href='/tasks/view?id={$task['taskid']}'><i class='uil uil-external-link-alt'></i> {$task['taskid']}</a></td>";
    echo "<td>" . $task['title'] . "</td>";
    echo "<td>";

    if ($task['assigned'] == 'unassigned') {
      if($_SESSION['user']['role'] == 'admin'){
        echo "<form method='post' style='display: inline;'>";
        echo "<select name='assid' class='selec'>";
        echo "<option style='display:none;' required>Select User</option>";
        foreach($jp->get("../database/db/users") as $user){
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
        foreach($jp->get_row("../database/db/users", ['role'=>'user']) as $user){
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
    } else {
      $assigned_user = $jp->get_row("../database/db/users", ["uid" => $task['assigned']]);
      if(count($assigned_user) > 0){
      echo "<a class='openurl' href='/users/profile?id={$assigned_user[0]['uid']}'>@{$assigned_user[0]['name']}</a>";
      }else{
        if($_SESSION['user']['role'] == 'admin'){
            echo "<form method='post' style='display: inline;'>";
            echo "<select name='assid' class='selec'>";
            echo "<option style='display:none;' required>Select User</option>";
            foreach($jp->get("../database/db/users") as $user){
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
            foreach($jp->get_row("../database/db/users", ['role'=>'user']) as $user){
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
    }

    echo "</td>";
  if($_SESSION['user']['role'] == "admin" or $_SESSION['user']['role'] == "manager"){
    echo "<td><form method='post' style='display:inline;'><button name='del' class='delete' value='{$task['taskid']}'><span style='color: red;' class='uil uil-trash-alt' title='Deleting Task is Permanent, as Task Id will be removed'> <ltext>Delete Task</ltext></span> </button></form></td>";
  } else{
    echo "<td><span title='Not Allowed to Delete Task' style='color: red;' class='uil uil-info-circle'> Not Allowed</span></td>";
  }
    $color='green';
    $logo = 'check-circle';
    if($task['status'] == 'unfinished'){
      $color='red';
      $logo = 'times-circle ';
    }
    echo "<td><span class='uil uil-$logo' style='color: $color;'> {$task['status']}</span></td>";
    echo "</tr>";
  
}
if(isset($_POST['assignto'])){
  $user = $_POST['assid'];  
  $task = $_POST['assignto'];
  $jp->update_row(
  "../database/db/tasks", ["taskid" => $task], ["assigned" => $user]);
  reload();
}
if(isset($_POST['self'])){
  $data = json_decode($_POST['self']);
  $user = $data[0];
  $task = $data[1];
  $jp->update_row(
    "../database/db/tasks", ["taskid" => $task], ["assigned" => $user]);
  reload();
}
if(isset($_POST['del'])){
  $tid = $_POST['del'];
  $jp->remove_row("../database/db/tasks", ["taskid" => $tid]);
  reload();
}
?>
      </tbody>
</table>

  </mainbox>
</body>
</html>