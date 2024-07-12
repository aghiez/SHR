<?php
session_start();
if(isset($_GET["nasabah"]) && isset($_GET["minggu"])){
    $_SESSION['nasabah'] = $_GET['nasabah'];
    $_SESSION['minggu'] = $_GET['minggu'];

    header('Location: input.php');
    exit();
} else{
    echo "Parameter tidak ditemukan";
}
?>