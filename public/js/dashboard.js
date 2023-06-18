let playlists = document.querySelectorAll(".playlist-name");
let tracks = document.querySelectorAll(".track-name"); 
let tracksInfos = document.querySelectorAll(".track-infos");

playlists.forEach(playlist => {

    let playlistData = playlist.getAttribute('data-playlist');

    playlist.addEventListener("click", () => {
        tracks.forEach(track => {
            if(track.getAttribute('data-playlist') == playlistData){
               
                    track.checked = playlist.checked
                    let trackArtist = track.nextElementSibling;
                    let trackAlbum = track.nextElementSibling.nextElementSibling;
                    trackArtist.checked = track.checked;
                    trackAlbum.checked = track.checked; 
            }
        })
    })
});

tracks.forEach(track => {
    track.addEventListener('click', () => {
        let trackArtist = track.nextElementSibling;
        let trackAlbum = track.nextElementSibling.nextElementSibling;
        trackArtist.checked = track.checked;
        trackAlbum.checked = track.checked;
    })
});

//////

let tracksButton = document.querySelectorAll('.display-tracks-button');

let listTracks = document.querySelectorAll('.tracks');

tracksButton.forEach(trackButton => {
    trackButton.addEventListener('click', () =>{
      
        trackButton.firstElementChild.nextElementSibling.classList.toggle("rotate")
        trackButton.classList.add("expanded")
  
        listTracks.forEach(listTrack => {
        
            if((listTrack.getAttribute('data-list')) === (trackButton.getAttribute('data-list'))){
                listTrack.classList.toggle("show");
            }
        })
    })
});

