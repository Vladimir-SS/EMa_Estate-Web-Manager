function avatarLoader() {
    var avatarElement = document.getElementById('avatar');
    avatarElement.style.backgroundImage = 'url( "data: image/jpeg; base64, ' + avatarElement.innerText + '" ) ';
    avatarElement.innerText = "";
}
DocumentHandler.whenReady(avatarLoader);
