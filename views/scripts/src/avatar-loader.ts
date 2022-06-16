
const avatarElement = document.getElementById("avatar");
const el = document.getElementById('avatarImage') as HTMLImageElement;
avatarElement.appendChild(el);
let file: File;

const addImageHandler = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;
    file = files[0];
    if (file != undefined) {
        const imgURL = URL.createObjectURL(files[0]);
        el.src = imgURL;
        el.alt = files[0].name;
    } else {
        el.src = "";
        el.alt = "avatar";
    }

}


const formDataHandler = (event: FormDataEvent) => {
    if (file != undefined) {
        event.formData.set('image', file);
    }
}

DocumentHandler.whenReady(() => {
    let addImageInputElement = document.getElementById("add-image-input");
    addImageInputElement.addEventListener("change", addImageHandler, true);
    const formElement = document.getElementsByTagName("form")[0];
    formElement.addEventListener("formdata", formDataHandler);
});