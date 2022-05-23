var insertedFiles = 0;
const image_input = document.getElementById("image-input");
image_input.addEventListener("change", handleFiles, false);
function handleFiles() {
    const fileList = this.files;
    const file = fileList[0];
    if (insertedFiles == 4) {
        document.getElementById("add_file").remove();
    }
    let divImg = document.createElement('div');
    divImg.setAttribute('class', 'image');
    let imgURL = URL.createObjectURL(file);
    divImg.style.backgroundImage = 'url( ' + imgURL + ' )';
    let labelImg = document.createElement('label');
    labelImg.setAttribute('class', 'images__add-img image-container');
    labelImg.appendChild(divImg);
    var image = document.getElementById('images');
    image.prepend(labelImg);
    insertedFiles += 1;
}
