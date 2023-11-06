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
      <a href='/mail' class='item current'>
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
    <label class='heads h1'>Send In-Mail</label>
 <div class='add'>
<?php
  $users = $jp->get("../database/db/users"); 
?>

<form method="POST">
  <label for="user">Reciever:</label>
  <select id="user" class='mainInp' name="user" required>
    <option value='' style='display:none;'>Select</option>
    <?php foreach($users as $user): ?>
  <?php if($user['uid'] != $_SESSION['user']['uid']):?>
      <option value="<?php echo $user['uid']; ?>"><?php echo $user['name']; ?></option>
    <?php endif; ?>
    <?php endforeach; ?>
  </select><br>

  <label for="subject">Subject:</label>
  <input type="text" class='mainInp' id="subject" name="subject" required><br>

  <label for="body">Body:</label><br>
  <textarea id="body" class='mainInp textarea' name="body" required></textarea><br>

  <button type="submit"  class='mainBtn' name="send_mail" required>Send In-Mail</button>
</form>

<?php
if(isset($_POST['send_mail'])){
  $recipient = $_POST['user'];
  $subject = $_POST['subject'];
  $body = $_POST['body'];
  $sender = $_SESSION['user']['uid'];
  $sendername = $_SESSION['user']['name'];
  $date = date("Y-m-d");
  $time = date("h:i A");
  $mailInf = [
    'mailId' => $jp->idgen(12),
    'sender' => $sender,
    'sendername' => $sendername,
    'recipient' => $recipient,
    'subject' => $subject,
    'body' => $body,
    'date' => $date,
    'time' => $time
  ];
  $jp->add_row("../database/db/mail", $mailInf);
  reload();
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
    <label class='heads h1'>All Inbox</label>

    <table class='styled-table'>
      <thead>
      <tr>
        <th>ID</th>
        <th>Subject</th> 
        <th>Sender</th>
        <th>Date</th>
      </tr>
      </thead> 
          <tbody>

  <tbody>
    <?php
      $mails = $jp->get_row("../database/db/mail", ['recipient' => $_SESSION['user']['uid']]);
      foreach($mails as $mail) {
        echo "<tr>";
        echo "<td><a class='openurl' href='/mail/read?id={$mail['mailId']}'><i class='uil uil-external-link-alt'></i> {$mail['mailId']}</a></td>";
        echo "<td>{$mail['subject']}</td>";
        echo "<td><a class='openurl' href='/users/profile?id={$mail['sender']}'>@{$mail['sendername']}</a></td>";
        echo "<td>{$mail['date']}</td>";
        echo "</tr>";
      }
    ?>


          </tbody>
    </table>

      <label class='heads h1'>All Sent</label>

      <table class='styled-table'>
        <thead>
        <tr>
          <th>ID</th>
          <th>Subject</th> 
          <th>Recepient</th>
          <th>Date</th>
        </tr>
        </thead> 
            <tbody>

    <tbody>
      <?php
        $mails = $jp->get_row("../database/db/mail", ['sender' => $_SESSION['user']['uid']]);
        foreach($mails as $mail) {
          $usr = $jp->get_row("../database/db/users", ['uid' => $mail['recipient']]);
          if(count($usr) > 0){
            echo "<tr>";
            echo "<td><a class='openurl' href='/mail/read?id={$mail['mailId']}'><i class='uil uil-external-link-alt'></i> {$mail['mailId']}</a></td>";
            echo "<td>{$mail['subject']}</td>";
            echo "<td><a class='openurl' href='/users/profile?id={$usr[0]['uid']}'>@{$usr[0]['name']}</a></td>";
            echo "<td>{$mail['date']}</td>";
            echo "</tr>";
          }
        }
      ?>


            </tbody>
      </table>
  </mainbox>
</body>
</html>