<?php
session_start();

unset($_SESSION['banco']);
header("location: index.php");
exit;
?>