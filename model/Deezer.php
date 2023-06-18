<?php

class Deezer 
{
    protected $token;
    private $curlServer;

    public function __construct($__app_client_id, $__app_secret, $curlServer)
    {
        $this->token = $this->getToken($__app_client_id, $__app_secret);
        $this->curlServer = $curlServer;
    }

    public function getToken($__app_client_id, $__app_secret) 
    {
        $_SESSION['code'] = $_GET['code'];

        $req_url = 'https://connect.deezer.com/oauth/access_token.php?app_id=' . $__app_client_id . '&secret=' . $__app_secret . '&code=' . $_SESSION['code'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $req_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($curl);
        parse_str($output, $params);
        
        curl_close($curl);

        return $params['access_token'];
    }

    /**
     * Find user playlists
     */
    public function getUserPlaylists(): array
    {
       /*  $curl = new CurlServer(); */
        $requestUrl = "https://api.deezer.com/user/me/playlists?access_token=".$this->token;
        $errorMessage = "Erreur lors de la recherche des playlists de l'utilisateur";

        $response = $this->curlServer->getRequest($requestUrl, null, $errorMessage, "deezer");
        $userPlaylists = json_decode($response, true);

        return $userPlaylists["data"];
    }

    /**
     * Find the tracks of a playlist
     */
    public function getPlaylistTracks(string $requestUrl): array
    {
       /*  $curl = new CurlServer(); */

        $errorMessage = "Erreur lors de la recherche des morceaux d'une playlist";

        $response = $this->curlServer->getRequest($requestUrl, null, $errorMessage, "deezer");
        $playlistTracks = json_decode($response, true);

        return $playlistTracks;
    }

    /**
     * Generate an array with playlist infos and its tracks
     */
    public function associateTracksToPlaylist(array $playlist, array $playlistTracks): array
    {
        $picture = str_contains($playlist['picture_medium'], 'cover//') ? "public/images/logo.png" : $playlist["picture_medium"];
        $playlistName = $playlist['title'];

        $tracks = [];
        foreach($playlistTracks["data"]  as $item) {
            $trackName = $item['title'];
            $trackAlbum = $item['album']['title']; 
            $artist = $item["artist"]["name"];
            
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
        $requestUrl = "https://api.deezer.com/user/me/playlists?access_token=" . $this->token;
        $errorMessage = "Erreur lors de la création d'une playlist";
        $playlistName = array(
            "title" => $playlistName,
            "public" => true
        );

        $playlistId = $this->curlServer->postRequest($requestUrl, $playlistName, [], $errorMessage, "deezer");

        return $playlistId;
    }
    
    /**
     * Find the id of a track in terms of title, artist and album
     */
    public function findIdTrack(string $trackName, string $artist, string $album, string $playlist): string
    {
        /* $search = urlencode('aaez' . ' ' . 'test' . ' ' . 'double');  */
        $search = urlencode($trackName . ' ' . $artist . ' ' . $album);
        $requestUrl = "https://api.deezer.com/search?q='$search'";
        $errorMessage = "Erreur lors de la recherche d'un morceaux";

        $response = $this->curlServer->getRequest($requestUrl, null, $errorMessage, "deezer");
        $tracks = json_decode($response, true);
       
        $trackId = '';

        if (empty($tracks["data"])) {
            $_SESSION["error"][] = [
                "track" => $trackName,
                "artist" => $artist,
                "album" => $album,
                "playlist" => $playlist,
            ];
        } else {
            foreach($tracks["data"] as $track) {
                if ((mb_strtolower($track["artist"]["name"]) == mb_strtolower($artist)) && (mb_strtolower($track["album"]["title"]) == mb_strtolower($album)) && (mb_strtolower($track["title"] == $trackName)) ) {
                    $trackId = $track["id"];
                  
                    return $trackId;
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
        

        if (empty($trackId)) {
            throw new Exception('Impossible de trouver le morceau: <br> ' . $artist . ' - ' . $trackName . ' de l\'album: ' . $album); 
        } 
   
        return $trackId;
    }

    public function addTrackToPlaylist(string $playlistId, string $trackId): bool
    {
        $requestUrl = "https://api.deezer.com/playlist/$playlistId/tracks?access_token=" . $this->token;
        $errorMessage = "Erreur lors de l'ajout d'un morceau à la playlist";
        $trackId = array('songs' => $trackId);

        $response = $this->curlServer->postRequest($requestUrl, $trackId, [], $errorMessage, "deezer");

        return $response;
    }
}