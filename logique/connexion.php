<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 

if (isset($_SESSION["source"]) || isset($_SESSION["destination"])) {
    if($_SESSION["source"] == "deezer" || $_SESSION["destination"] == "deezer"){
        include '../_private/deezerGlobal.inc.php'; 
    ?>
        <script>
    
            let logInUri = 'https://connect.deezer.com/oauth/auth.php?app_id=<?php echo $__deezer_app_client_id; ?>' +
                '&response_type=code' +
                '&redirect_uri=<?php echo $__deezer_redirect_uri; ?>' +
                '&perms=manage_library, basic_access, delete_library, email'
    
            window.open(logInUri, '_self');
    
        </script>
    
        <?php
        } else if ($_SESSION["source"] == "spotify" || $_SESSION["destination"] == "spotify"){
            include '../_private/spotifyGlobal.inc.php';
        ?>
    
        <script>
     
            let logInUri = '<?php echo $__spotify_uri_connect; ?>' +
                '?client_id=<?php echo $__spotify_app_client_id; ?>' +
                '&response_type=code' +
                '&redirect_uri=<?php echo $__spotify_redirect_uri; ?>' +
                '&scope=app-remote-control playlist-modify-public user-library-read user-library-modify user-top-read user-read-currently-playing user-read-recently-played streaming app-remote-control user-read-playback-state user-modify-playback-state' +
                '&show_dialog=true';
        
        
            window.open(logInUri, '_self'); 
       
        </script>
        
        <?php     
        }
} else {
    header("Location: ../index.php");
    exit;
}
?>
