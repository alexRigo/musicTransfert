<?php ob_start(); ?>
<div class="container result">
    <div class="message-container">
        <p class="big-text">Vos playlists ont été transférées avec succès</p>
        <img class="image-success" src="../public/images/success.png" alt="image succès">
        <?php if(isset($_SESSION["error"]) && !empty($_SESSION["error"])):?>
        <div class="error-message">
            <p>Cependant, suite à un problème, les morceaux suivants n'ont pas pu être trouvés et donc ajoutés:</p>
            <br>
            <ul>
                <?php foreach($_SESSION["error"] as $error): ?>
                    <li>
                        <?= $error["track"] . ' - ' . $error["artist"] . ' de l\'album ' . $error["album"] . ' (playlist: ' . $error["playlist"] . ')' ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div> 
        <?php endif ?>
    </div>
    <a class="button home-link" href="/index.php">Transférer d'autres playlists</a>
</div>
    
<?php 

$content = ob_get_clean(); 
$cssFiles = array("../public/style/result.css");

include('template.php');

?>