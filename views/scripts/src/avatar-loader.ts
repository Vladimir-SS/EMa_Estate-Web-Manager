
const avatarElement = document.getElementById("avatar");
const el = document.getElementById('avatarImage') as HTMLImageElement;
avatarElement.appendChild(el);

const addImageHandler = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;
    const file = files[0];
    const imgURL = URL.createObjectURL(file);

    el.src = imgURL;
    el.alt = file.name;

}

function avatarLoader() {
    let addImageInputElement = document.getElementById("add-image-input");
    addImageInputElement.addEventListener("change", addImageHandler, true);
}

DocumentHandler.whenReady(avatarLoader);