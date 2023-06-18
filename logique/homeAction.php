<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['source']) && !empty($_GET['source'])) {
    if ($_GET['source'] !== "deezer" && $_GET['source'] !== "spotify") {
        header("Location: ../index.php");
        exit;
    } 
    $_SESSION['source'] = $_GET['source'];
    require('../logique/connexion.php');
} else {
    header("Location: ../index.php");
    exit;
}