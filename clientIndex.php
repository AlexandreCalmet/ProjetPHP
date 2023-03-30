

<?php
session_start();

include("clientFunctions.php");
include("jwt_utils.php");

if (isset($_SESSION['jwt'])) {
    $sessionJWT = $_SESSION['jwt'] ;
    $jwt = $sessionJWT.get_bearer_token() ;
    $payload = base64_decode(split_jwt($jwt)['payload']);
    $login = json_decode($payload, true)['username'];
    $exp = json_decode($payload, true)['exp'];
    $role = json_decode($payload, true)['role'];
} else {
    $jwt = null;
    $role = "non authentifie";
    $exp = time() + 300;
    $login = "non authentifie";
}
$remaining_time = $exp - time();
$hours = floor($remaining_time / 3600);
$minutes = floor(($remaining_time % 3600) / 60);
$seconds = $remaining_time % 60;



// Check if the session has expired
if ($exp <= time()) {
    session_unset();
    session_destroy();
  }

  
if (isset($_POST['end_session'])) {
    session_destroy();
    header("Location: clientIndex.php");
}

?>

<style>
.insertform {
    margin: auto;
    text-align: center;
}
</style>

<div class="insertform">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" id="name" name="contenu" required minlength="3" size="50">
        <input type="submit" id="envoyer" name="insert" >
    </form>
</div>
<div style="position: fixed; top: 0; right: 0;">
  <table>
    <tbody>
      <tr>
        <td>
          <?php echo $login . '<br/>' . $role . '<br/>' ?>
          <span id="time-container"><?php echo gmdate("H:i:s", mktime($hours, $minutes, $seconds, 1, 1, 1970)); ?></span>
          <form method="post">
            <input type="submit" name="end_session" value="Disconnect">
          </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<script>
  // Initialize the time value
  var hours = <?php echo $hours; ?>;
  var minutes = <?php echo $minutes; ?>;
  var seconds = <?php echo $seconds; ?>;

  // Update the time value every second
  setInterval(function() {
    if (seconds > 0) {
      seconds--;
    } else {
      if (minutes > 0) {
        minutes--;
        seconds = 59;
      } else {
        if (hours > 0) {
          hours--;
          minutes = 59;
          seconds = 59;
        }
      }
    }
    document.getElementById("time-container").innerHTML = hours.toString().padStart(2, "0") + ":" + minutes.toString().padStart(2, "0") + ":" + seconds.toString().padStart(2, "0");
  }, 1000);
  
</script>

<?php

    echo "<table border='1'  align='center' >";
    echo "<tr>
            <td>Date</td>
            <td>Content</td>
            <td>Likes</td>
            <td>Total</td>
            <td>Author</td>
    </tr>";

    $result = get();

    $article = json_decode($result, true);
    
    if(isset($article)&&(!empty($article))){
        foreach($article['data'] as $values) {
            if(isset($values['id_article'])){
                echo "<tr>";
                echo "<td>" . $values['publication'] . "</td>";
                echo "<td>" . $values['contenu'] ; # . ' <a href="upVote.php?id=' . $values['id_article'] . '&vote=' . $values['likes'] .' " >upVote</a></td>';
                echo "<td>" . 0 . "</td>";
                echo "<td>" . 0 . "</td>";
                echo "<td>" . $values['login'] . "</td>";
                echo "</tr>";
            }
        }
    }

    if (isset($login)) {
        if(!empty($_POST['contenu'])) {
            if(isset($_POST['insert'])) {
                insertArticle($_POST['contenu'], $login, $jwt);
            }
        }
}

?>
