<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

include '../model/CurlServer.php';
include '../model/Deezer.php';
include '../model/Spotify.php';

if ($_SESSION['destination'] == "deezer") {
    require '../_private/deezerGlobal.inc.php';

    $curlServer = new CurlServer("deezer");
    $deezer = new Deezer($deezer_app_client_id, $deezer_app_secret, $curlServer);

    foreach($_SESSION["playlist"] as $playlist) {
    	$playlistName = $playlist["playlistName"];

		try {
			$playlistId = $deezer->createPlaylist($playlistName);
		} catch (Exception $e) {
			echo $e->getMessage();
			die;
		}
       
        $playlistId = substr($playlistId, 6, -1);
    
        if(isset($playlist["tracks"])) {
           
        	foreach($playlist["tracks"] as $track) {
				try {
				    $deezerTrackId = $deezer->findIdTrack($track["name"], $track["artist"], $track['album'], $playlistName);
				} catch (Exception $e) {
				    echo $e->getMessage();
                    die;
				}
			 	
                if(!empty($deezerTrackId)) {
                    try {
                        $response = $deezer->addTrackToPlaylist($playlistId, $deezerTrackId); 
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        die;
                    }   
                }
        	} 
        }     
    }

} elseif ($_SESSION["destination"] == "spotify") {
   
    require '../_private/spotifyGlobal.inc.php';

    $curlServer = new CurlServer("spotify");
    $spotify = new Spotify($spotify_app_client_id, $spotify_app_secret, $curlServer);

    foreach($_SESSION["playlist"] as $playlist) {
        $playlistName = $playlist["playlistName"];

		try {
			$playlistId = $spotify->createPlaylist($playlistName); 
		} catch (Exception $e) {
			echo $e->getMessage();
			die;
		}

        if(isset($playlist["tracks"])) {
            foreach($playlist["tracks"] as $track) {
                try {
                    $spotifyTrackUri = $spotify->findIdTrack($track["name"], $track["artist"], $track["album"], $playlistName);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    die;
                }

                if (!empty($spotifyTrackUri)) {
                    try {
                        $response = $spotify->addTrackToPlaylist($playlistId, $spotifyTrackUri);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        die;
                    }  
                }  
            } 
        }
    }
}

include "../vue/result.php";



