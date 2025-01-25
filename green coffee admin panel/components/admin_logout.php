<?php
  include 'connection.php';

  session_status();
  session_unset();
  session_destroy();
  header('location: ../admin/login.php')
?>