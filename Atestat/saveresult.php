<?php
if (isset($_POST['score'])) {
  $score = $_POST['score'];
  $user=$_POST['user'];
  file_put_contents($user.'.txt', $score . PHP_EOL, FILE_APPEND);
}
?>