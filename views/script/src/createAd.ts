var insertedFiles = 0;
var icon = document.getElementById('plus-icon');

const createInput = (): Element => {

    const input = document.createElement('input');
    input.type = "file";
    input.accept = "image/*";
    input.name = "images[]";
    input.addEventListener("change", handleFiles, false);

    const labelInput = createSimpleElement('label', 'images__add-img image-container');
    labelInput.setAttribute('onclick', '');
    labelInput.append(icon, input);

    return labelInput;
}

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

        // let labelImg = document.createElement('label');
        // labelImg.setAttribute('class', 'images__add-img image-container');
        // labelImg.appendChild(divImg);

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
