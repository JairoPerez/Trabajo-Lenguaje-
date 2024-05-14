<?php 
session_start();

if(!isset($_SESSION['nia'])){
    header("Location: login.php");
    session_destroy();
}

?>