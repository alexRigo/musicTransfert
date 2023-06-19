<?php 
ob_start();
 ?>

<div class="dashboard container">
    <h2 class="big-text">Your playlists</h2>
        <form action="../logique/dashboardAction.php" method="get">
            <div class="playlists">
                <?php foreach ($playlistsAndTracks as $keyX=>$playlist) : ?>
                <div class="playlist">
                    <div class="playlist-datas">
                        <input type="checkbox" class="playlist-name" data-playlist=<?= $keyX ?> name=<?="playlist[$keyX][playlistName]"?> value="<?=$playlist["name"]?>" checked>
                        <img src="<?= $playlist["picture"]?>" alt="playlist image" class="playlist-image">
                        <div class="playlist-name">
                            <span><?= $playlist["name"] ?></span>
                        </div>
                        <span class="tracks-count"> &nbsp;(<?= count($playlist["tracks"]) ?> morceaux)</span>
                        <div class="display-tracks-button" data-list = <?= $keyX ?>>
                            <p>Afficher les morceaux</p>
                            <img src="../public/images/simple-arrow.png" alt="arrow" class="">
                        </div>
                    </div>
                    <div class="tracks" data-list = <?= $keyX ?>>
                        <?php foreach ($playlist["tracks"] as $keyY=>$track): ?>
                        <div class="track">
                            <input type="checkbox" class="track-name" id= <?= "playlist[$keyX][tracks][$keyY][name]"?> data-playlist=<?= $keyX ?> data-track=<?= $keyY ?>  name=<?= "playlist[$keyX][tracks][$keyY][name]"?> value="<?= $track["song"] ?>" checked>
                            <input type="checkbox" class="track-infos" data-playlist=<?= $keyX ?> data-track=<?= $keyY ?> name=<?= "playlist[$keyX][tracks][$keyY][artist]"?> value="<?= $track['artist']?>" checked style="display:none;">
                            <input type="checkbox" class="track-infos" data-playlist=<?= $keyX ?> data-track=<?= $keyY ?> name=<?= "playlist[$keyX][tracks][$keyY][album]"?> value="<?= $track['album'] ?>" checked style="display:none;">
                            <label for=<?= "playlist[$keyX][tracks][$keyY][name]"?>>
                                <span><?php echo $track["artist"] ?></span>
                                <span> - </span>
                                <span><?php echo $track["song"] ?></span>
                            </label>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <?php endforeach ?>
                <input type="hidden" name="code" value=<?php echo $_GET["code"] ?>>  
               

      
               
                        </div> 
                        <div class="submit-button">
   <button type="submit" class="button">Transfer your playlists</button>
   </div>
           
        </form>
    </div>
    </div> 

<script src="../public/js/dashboard.js"></script> 

<?php

$content = ob_get_clean(); 
$cssFiles = array("../public/style/dashboard.css");

include "template.php";

?>