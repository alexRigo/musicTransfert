<?php
require '../model/CurlServer.php';

/* require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load(); */
$deezer_app_client_id = $_ENV['DEEZER_APP_CLIENT_ID'];
$deezer_redirect_uri = $_ENV['DEEZER_REDIRECT_URI'];
$deezer_app_secret = $_ENV['DEEZER_APP_SECRET'];
$spotify_app_secret = $_ENV['SPOTIFY_APP_SECRET'];
$spotify_app_client_id = $_ENV['SPOTIFY_APP_CLIENT_ID'];
$spotify_redirect_uri = $_ENV['SPOTIFY_REDIRECT_URI']; 
$spotify_uri_connect = $_ENV['SPOTIFY_URI_CONNECT'];

if($_SESSION['source'] == "spotify") {

    /* require '../_private/spotifyGlobal.inc.php'; */
    require '../model/Spotify.php';

    $curlServer = new CurlServer("spotify");
    $spotify = new Spotify($spotify_app_client_id, $spotify_app_secret, $curlServer);
   
    $playlistsAndTracks = [];

    try {
        $playlists = $spotify->getUserPlaylists();
    } catch (Exception $e){
        echo $e->getMessage();
        die;
    }

    if($playlists == null) 
        $_SESSION["message"] = "Vous n'avez aucune playlists";
 
    foreach($playlists as $playlist) {
        $playlistTracksUrl = $playlist["tracks"]["href"];

        try {
            $playlistTracks = $spotify->getPlaylistTracks($playlistTracksUrl);
        } catch (Exception $e){
            echo $e->getMessage();
            die;
        } 

        $playlistWithTracks = $spotify->associateTracksToPlaylist($playlist, $playlistTracks['items']);
        $playlistsAndTracks[] = $playlistWithTracks;
    } 

   
} elseif($_SESSION["source"] == "deezer") {
    /* require '../_private/deezerGlobal.inc.php'; */
    require '../model/Deezer.php'; 
    
    $curlServer = new CurlServer("deezer");
    $deezer = new Deezer($deezer_app_client_id, $deezer_app_secret, $curlServer);

    $playlistsAndTracks = [];

    try {
        $playlists = $deezer->getUserPlaylists();
    } catch (Exception $e) {
        echo $e->getMessage();
        die;
    }
    
    foreach($playlists as $playlist) {
        if($playlist['title'] !== "Loved Tracks") {
            $playlistTracksUrl = $playlist["tracklist"];
        
            try {
                $playlistTracks = $deezer->getPlaylistTracks($playlistTracksUrl);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
         
            $playlistWithTracks = $deezer->associateTracksToPlaylist($playlist, $playlistTracks);
            $playlistsAndTracks[] = $playlistWithTracks;
        }
    } 
}

include '../vue/dashboard.php';