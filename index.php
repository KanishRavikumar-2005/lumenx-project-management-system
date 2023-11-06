<?php 
session_start();
require_once("database/db-conf.php");
$jp = new Jasper();
/*$adminUser = array(
  "uid"=>$jp->uuid(17),
  "name"=>"admin",
  "password"=>"8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92",
  "role"=>"admin"
);
$jp->add_row("database/db/users", $adminUser);*/
if(isset($_SESSION['user'])){
  if(count($jp->get_row("database/db/users", ["uid" => $_SESSION['user']['uid']])) < 1){
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
    <link rel='icon' href='designs/lumenbulb.png'>

    <link rel='stylesheet' href='designs/style.css'>
  </head>
  <body>
  <leftbar>
    <imagebox>
      <img  class='titleImg' id='titleImage'>
    </imagebox>
    <navigator>
      <a href='/' class='item current'>
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
      <a href='/users/profile?id=<?php echo $_SESSION['user']['uid'];?>' class='item'>
        <icon><i class="uil uil-user-circle"></i></icon>
        <itext>Profile</itext>
      </a>
    </navigator>
  </leftbar>
    <center>
  <mainbox>
    <label class='heads h1'>Make An Announcement</label>
    <br>
    <div class='add'>
      <form method='post'>
  <input type="text" name="title" class='mainInp' placeholder="Title">
  <textarea name="body" class='mainInp textarea' placeholder="Body"></textarea>
  <button name="add" class='mainBtn' type="submit">Add</button>
      </form>
      <style>
        .openurl{
          color: var(--blue);
          text-decoration: none;
        }
        .openurl:hover{
          text-decoration: underline;
        }
      </style>
    </div>
    <?php
    if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];
  
    $username = $_SESSION['user']['name'];
    $uid = $_SESSION['user']['uid'];    
    $role = $_SESSION['user']['role'];

  
    $date = date('d/m/Y');
    $time = date('h:i A');
    $annid = $jp->idgen(14);
  
    $toAdd = array(
        "annid" => $annid,
        "username" => $username,
        "uid" => $uid,
        "date" => $date,
        "time" => $time,
        "title" => $title,
        "body" => $body,
        "role" => $role
    );
  
    $jp->add_row("database/db/announcements", $toAdd);
}
      ?>
    <label class='heads h1'>All Announcements</label>

    <div class='announcements'>
      <?php 
$ann = $jp->get("database/db/announcements", "reverse");
if(count($ann) > 0){
    
    foreach($ann as $announcement){
      $by = "";
      if($announcement['role'] == "admin"){
        $by = "<span class='admin'><a class='openurl' style='color: black;' href='/users/profile?id={$announcement['uid']}'>@{$announcement['username']}</a></span>";
      }else{
        $by = "<span class='user'><a class='openurl' href='/users/profile?id={$announcement['uid']}'>@{$announcement['username']}</a></span>";
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
      </center>
  </body>
</html>
<script>
  function changeImageSource() {
      const image = document.getElementById("titleImage");
      if (window.innerWidth <= 600) {
          image.src = "designs/lumenbulb.png"; // Change to the new image source
      } else {
          image.src = "designs/lumenextitle.png"; // Change back to the original image source
      }
  }

  // Initial check when the page loads
  changeImageSource();

  // Event listener to check on window resize
  window.addEventListener("resize", changeImageSource);
</script>