function avatarLoader() {
    var avatarElement = document.getElementById('avatar');
    let imgURL = URL.createObjectURL(new Blob([atob(avatarElement.innerText)])); // ???????????
    avatarElement.style.backgroundImage = 'url( ' + imgURL + ' )';
    avatarElement.innerText = "";
}

DocumentHandler.whenReady(avatarLoader);