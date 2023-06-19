<?php
/* if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 
if (isset($_SESSION['source'])) {
    include '../logique/request.php'; 
} elseif (isset($_SESSION['destination'])) {
    include '../logique/transfert.php';
} */
include '../logique/transfert.php';