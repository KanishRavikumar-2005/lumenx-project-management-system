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
      <a href='/users' class='item current'>
        <icon><i class="uil uil-users-alt"></i></icon>
        <itext>Users</itext>
      </a>
      <a href='/tasks' class='item'>
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
    <label class='heads h1'>Create New User</label>

<div>
  <form method="post" class='add'>
    <input type="text" class='mainInp' name="new_username" placeholder="Username">
    <input type="text"  class='mainInp' name="new_password" placeholder="Password">
    <select class='mainInp' name="new_role">
      <option value="" style='display:none;'>Select User</option>
      <option value="user">User</option>
      <option value="manager">Manager</option>
    </select>
    <button type="submit" class='mainBtn' name="create_user">Create User</button>
  </form>
</div>
<?php endif; ?>
    <?php
    if(isset($_POST['create_user'])){
      $username = $_POST['new_username'];
      $password = $_POST['new_password'];
      $role = $_POST['new_role'];
      create_user($username, $password, $role);
    }
function create_user($username, $password, $role) {
  global $jp;
  $newUser = array(
    "uid" => $jp->uuid(17),
    "name" => $username,
    "password" => hash('sha256', $password),
    "role" => $role
  );
  $jp->add_row("../database/db/users", $newUser);
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
    .openurl{
      color: var(--blue);
      text-decoration: none;
    }
    .openurl:hover{
      text-decoration: underline;
    }
  ltext{
    font-family: jost;
    font-size: 15px;
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
    <label class='heads h1'>All Users</label>

<table class='styled-table'>
  <thead>
  <tr>
    <th>Name</th>
    <th>ID</th>
    <th>Role</th>
    <th>Delete</th>
  </tr>
  </thead> 
      <tbody>
<?php
$current_user = $_SESSION['user']['name'];
$users = $jp->get("../database/db/users");
foreach ($users as $user) {
  if($user['name'] != $current_user){
    echo "<tr>";
    echo "<td><a class='openurl' href='/users/profile?id={$user['uid']}'> @" . $user['name'] . "</a></td>";
    echo "<td>" . $user['uid'] . "</td>";
    echo "<td>" . $user['role'] . "</td>";
    if(($_SESSION['user']['role'] == "manager" and $user['role'] == "user") or $_SESSION['user']['role'] == "admin"){
    echo "<td><form method='post' style='display:inline;'><button name='del' class='delete' value='{$user['uid']}'><span style='color: red;' class='uil uil-trash-alt' title='Deleting User is Permanent, as User Id will be removed'> <ltext>Delete User</ltext></span> </button></form></td>";
    }else{
      echo "<td><span title='Not Allowed to Delete User' style='color: red;' class='uil uil-info-circle'> Not Allowed</span></td>";
    }
    echo "</tr>";
  }
}

if(isset($_POST['del'])){
  $uid = $_POST['del'];
  $jp->remove_row("../database/db/users", ["uid" => $uid]);
  reload();
}
?>
      </tbody>
</table>

  </mainbox>
</body>
</html>