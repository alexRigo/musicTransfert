<?php ob_start(); ?>

<div class="homepage container">
    <p class="big-text">Transfer you playlists from a platform to an other</p>
    <div class="platform-source deezer">
        <img src="../public/images/deezer.png" alt="logo deezer" class="logo-platform">
        <img src="../public/images/arrowLeft.png" alt="fleche droite" class="arrow move">
        <img src="../public/images/spotify.png" alt="logo spotify" class="logo-platform">
        <p>Deezer to Spotify</p>
    </div>
    <div class="platform-source spotify">
        <img src="../public/images/spotify.png" alt="spotify logo" class="logo-platform">
        <img src="../public/images/arrowLeft.png" alt="fleche droite" class="arrow move">
        <img src="../public/images/deezer.png" alt="deezer logo" class="logo-platform">
        <p>Spotify to Deezer</p>
    </div>

    <form action="../logique/homeAction.php" method="GET" onsubmit="return validate()">
        <input type="radio" name="source" id="deezer" value="deezer">
        <input type="radio" name="source" id="spotify" value="spotify">
        
        <button type="submit" id="submit" class="button disabled">Next</button>
    </form>
</div>

<script src="../public/js/homepage.js"></script>

<?php

$content = ob_get_clean(); 
$cssFiles = array("../public/style/home.css");

include('template.php');

?>
