<?php

require '../model/CurlServer.php';

if($_SESSION['source'] == "spotify") {

    require '../_private/spotifyGlobal.inc.php';
    require '../model/Spotify.php';

    $curlServer = new CurlServer("spotify");
    $spotify = new Spotify($__app_client_id, $__app_secret, $curlServer);
   
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
    require '../_private/deezerGlobal.inc.php';
    require '../model/Deezer.php'; 
    
    $curlServer = new CurlServer("deezer");
    $deezer = new Deezer($__app_client_id, $__app_secret, $curlServer);

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


