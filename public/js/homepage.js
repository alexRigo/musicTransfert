let deezerSource = document.querySelector('.platform-source.deezer');
let spotifySource= document.querySelector('.platform-source.spotify');
let deezerCheckbox = document.querySelector('#deezer');
let spotifyCheckbox = document.querySelector('#spotify');
let submit = document.querySelector('#submit');

function validate() {
    if ((deezerCheckbox.checked === true) || (spotifyCheckbox.checked === true)) {
        return true;
    } else {
        return false;
    }
}

deezerSource.addEventListener('click', () => {
    spotifySource.classList.remove("enabled");
    deezerSource.classList.add("enabled");
    deezerCheckbox.checked = true;
    submit.classList.remove("disabled");
})
spotifySource.addEventListener('click', () => {
    deezerSource.classList.remove("enabled");
    spotifySource.classList.add("enabled");
    spotifyCheckbox.checked = true;
    submit.classList.remove("disabled");
})



     