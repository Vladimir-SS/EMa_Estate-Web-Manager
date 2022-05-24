var insertedFiles = 0;
var icon = document.getElementById('plus-icon');
const createInput = () => {
    let input = document.createElement('input');
    input.type = "file";
    input.accept = "image/*";
    input.name = "images[]";
    input.addEventListener("change", handleFiles, false);
    let labelInput = document.createElement('label');
    labelInput.setAttribute('class', 'images__add-img image-container');
    labelInput.id = 'add_file';
    labelInput.setAttribute('onclick', '');
    labelInput.appendChild(icon);
    labelInput.appendChild(input);
    return labelInput;
};
const rm = function remove(el) {
    var element = el;
    element.remove();
};
const image_input = document.getElementById("image-input");
image_input.addEventListener("change", handleFiles, false);
function handleFiles() {
    const fileList = this.files;
    const file = fileList[0];
    let el = document.getElementById('add_file');
    if (el != null) {
        let divImg = document.createElement('div');
        divImg.setAttribute('class', 'image');
        let imgURL = URL.createObjectURL(file);
        divImg.style.backgroundImage = 'url( ' + imgURL + ' )';
        el.appendChild(divImg);
        el.removeAttribute('id');
        this.removeEventListener;
        this.removeAttribute('id');
        document.getElementById('plus-icon').remove();
        if (insertedFiles < 4) {
            var image = document.getElementById('images');
            image.appendChild(createInput());
        }
        insertedFiles += 1;
    }
}
