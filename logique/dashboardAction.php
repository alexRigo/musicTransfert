<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if(isset($_GET["playlist"]) && !empty($_GET["playlist"])) {
  
    $_SESSION["playlist"] = $_GET["playlist"];

    if ($_SESSION["source"] == "deezer") {
        $_SESSION["source"] = null;
        $_SESSION["destination"] = "spotify";
    
        /* include './_private/spotifyGlobal.inc.php'; */
    } else if ($_SESSION["source"] == "spotify") {
        $_SESSION["source"] = null;
        $_SESSION["destination"] = "deezer";

       /*  include './_private/deezerGlobal.inc.php'; */
    } 

    include './connexion.php'; 
} else {
    header("Location: ../index.php");
    exit;
}

