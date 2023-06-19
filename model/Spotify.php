<?php

class Spotify 
{
    protected $token;
    private $curlServer;

    public function __construct($__app_client_id, $__app_secret, $curlServer, $spotify_redirect_uri)
    {
        $this->token = $this->getToken($__app_client_id, $__app_secret, $spotify_redirect_uri);
        $this->curlServer = $curlServer;
    }

    public function getToken($__app_client_id, $__app_secret, $spotify_redirect_uri) 
    {
        $_SESSION["code"] = $_GET["code"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code='. $_SESSION['code'] . '&redirect_uri=' . $spotify_redirect_uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . 'Basic ' . base64_encode("$__app_client_id:$__app_secret"), 'Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $server_output = json_decode($server_output, true);
        curl_close($ch);

        return $server_output['access_token'];
    }

    /**
     * Find user playlists
     */
    public function getUserPlaylists(): array
    {
        /* $curl = new CurlServer(); */

        $requestUrl = "https://api.spotify.com/v1/me/playlists";
        $errorMessage = "Erreur lors de la recherche des playlists de l'utilisateur";
       
        $response = $this->curlServer->getRequest($requestUrl, $this->token, $errorMessage, "spotify");
        $userPlaylists = json_decode($response, true);

        return $userPlaylists["items"];
    }

    /**
     * Find the tracks of a playlist
     */
    public function getPlaylistTracks(string $requestUrl): array
    {
        $errorMessage = "Erreur lors de la recherche des morceaux d'une playlist";

        $response = $this->curlServer->getRequest($requestUrl, $this->token, $errorMessage, "spotify");

        $playlistTracks = json_decode($response, true);

        return $playlistTracks;
    }

    /**
     * Generate an array with playlist infos and its tracks
     */
    public function associateTracksToPlaylist(array $playlist, array $playlistTracks): array
    {
        $picture = !empty($playlist["images"]) ? $playlist["images"][0]["url"] : "public/images/logo.png";
        $playlistName = $playlist["name"];

        $tracks = [];
        foreach($playlistTracks as $item) {
            $trackName = $item["track"]["name"];
            $trackAlbum = $item['track']['album']['name'];
            $artist = $item['track']['artists'][0]['name'];
            $tracks[] = [
                'artist' => $artist,
                'album' => $trackAlbum,
                'song' => $trackName,
            ];
        }
        
        $playlistWithTracks = [
            'name' => $playlistName,
            'picture' => $picture,
            'tracks' => $tracks,
        ];

        return $playlistWithTracks;
    }

    /**
     * Create a playlist with a name
     */
    public function createPlaylist(string $playlistName): string
    {
        $requestUrl = "https://api.spotify.com/v1/me/playlists";
        $playlistName = array('name' => $playlistName);
        $httpHeader = array('Authorization: Bearer ' . $this->token);
        $errorMessage = "Erreur lors de la création d'une playlist";

        $response = $this->curlServer->postRequest($requestUrl, json_encode($playlistName), $httpHeader, $errorMessage, "spotify");

        $response = json_decode($response, true);
       
        return $response["id"];
    }

    /**
     * Find the id of a track in terms of title, artist and album
     */
    public function findIdTrack(string $trackName, string $artist, string $album, $playlist): string
    {
        $encodedTrack = urlencode($trackName); 
        $encodedArtist = urlencode($artist);
        $encodedAlbum = urlencode($album);
 
        $requestUrl = "https://api.spotify.com/v1/search?q=track:$encodedTrack%20artist:$encodedArtist%20&type=track"; 

        $errorMessage = "Erreur lors de la recherche d'un morceaux";
     
        $response = $this->curlServer->getRequest($requestUrl, $this->token, $errorMessage, "spotify");
        $response = json_decode($response, true);

        $trackUri = '';

        if (empty($response["tracks"]["items"])) {
            $_SESSION["error"][] = [
                "track" => $trackName,
                "artist" => $artist,
                "album" => $album,
                "playlist" => $playlist,
            ];
        } else {
            foreach($response["tracks"]["items"] as $track) {
                if ((mb_strtolower($track["artists"][0]["name"]) == mb_strtolower($artist)) && (mb_strtolower($track["album"]["name"]) == mb_strtolower($album)) && (mb_strtolower($track["name"] == $trackName))) {
                    $trackUri = $track["uri"];

                    return $trackUri;
                } else {
                    $_SESSION["error"][] = [
                        "track" => $trackName,
                        "artist" => $artist,
                        "album" => $album,
                        "playlist" => $playlist,
                    ];
                }  
            }             
        }    
    
       /*  if (empty($trackUri)) {
            throw new Exception('Impossible de trouver le morceau: <br> ' . $artist . ' - ' . $trackName . ' de l\'album: ' . $album . '<br>' . $requestUrl); 
        }
     */
        return $trackUri; 
    }

    /**
     * Add a track to a playlist
     */
    public function addTrackToPlaylist(string $playlistId, string $spotifyTrackUri): string
    {   
        $requestUrl = "https://api.spotify.com/v1/playlists/$playlistId/tracks?uris=$spotifyTrackUri";
        $httpHeader = array('Authorization: Bearer ' . $this->token);
        $errorMessage = "Erreur lors de l'ajout d'un morceau à la playlist";

        $response = $this->curlServer->postRequest($requestUrl, null, $httpHeader, $errorMessage, "spotify");
        
        return $response;
    }
}