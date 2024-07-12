<?php 
session_start();
if(isset($_GET["nasabh"])){
    $_SESSION["nasabh"] = $_GET["nasabh"];
    header('Location: index.php');
    exit();
} else {
    echo "Parameter tidak ditemukan";
    }
    ?>