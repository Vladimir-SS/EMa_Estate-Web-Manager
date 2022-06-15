class CreateAd {
    public static addImageInputElement: HTMLElement;
    public static imagesElement: HTMLElement;
    private static uploadedFiles: File[] = [];

    public static addImage = (alt: string, url: string) => {
        const el = document.createElement('img');
        el.src = url;
        el.alt = alt;

        CreateAd.imagesElement.append(el);
    }

    public static addImageHandler = (event: Event) => {
        const target = event.target as HTMLInputElement;
        const files = target.files;

        for (let i = 0; i < files.length; ++i) {
            const file = files[i];
            const imgURL = URL.createObjectURL(file);
            this.uploadedFiles.push(file);

            console.log(this.uploadedFiles);

            CreateAd.addImage(file.name, imgURL);

            if (this.uploadedFiles.length === 10) {
                this.addImageInputElement.parentElement.style.display = "none";
                console.log("remove");
                return;
            }
        }
    }

    public static submitHandler = (event: SubmitEvent) => {
        event.preventDefault();
        const formData = new FormData(event.target as HTMLFormElement);

        for (var [key, value] of formData.entries()) {
            console.log(key, value);
        }

        var xmlHttpRequest = new XMLHttpRequest();
        let url = '/create-ad';
        xmlHttpRequest.open('POST', url, true);
        xmlHttpRequest.send(formData);

        xmlHttpRequest.onreadystatechange = function () {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                alert(xmlHttpRequest.responseText);
            }
        }

        console.log("submit")
    }

    public static formDataHandler = (event: FormDataEvent) => {
        console.log(event);

        event.formData.delete('images');
        CreateAd.uploadedFiles.forEach((file, index) => event.formData.set(`image${index}`, file));

        console.log("formData");

    }
}

const disableElement = (el: HTMLElement) => {
    el.style.display = "none";
    if (el.tagName === 'FIELDSET')
        el.setAttribute('disabled', '');
}

const enableElement = (el: HTMLElement) => {
    el.style.removeProperty("display");
    if (el.tagName === 'FIELDSET')
        el.removeAttribute('disabled');
}

DocumentHandler.whenReady(() => {
    const residentialSpecific = document.getElementById("residential-specific");
    const houseSpecific = document.getElementById("house-specific");
    const buildingSpecific = document.getElementById("building-specific");
    const dropDownContainer = document.getElementById("dropdown-container");

    const apartamentTypeDropDown = DropdownFilterOption.createWithDefault(
        "type",
        ["Decomandat", "Nedecomandat", "Semidecomandat", "Circular"],
        "Alege tipul apartamentului"
    );

    const typeLinked = [
        [],
        [residentialSpecific, buildingSpecific, apartamentTypeDropDown.element],
        [buildingSpecific, houseSpecific],
        [buildingSpecific],
        []
    ]

    const typeDropDown = DropdownFilterOption.createWithDefault(
        "type",
        ["Apartament", "Casă", "Office", "Teren"],
        "Alege tipul proprietății");

    let lastIndex = 0;
    typeDropDown.onChange = (index, __text) => {
        typeLinked[lastIndex].forEach(disableElement);
        typeLinked[index].forEach(enableElement);
        lastIndex = index;
    }
    typeLinked.forEach(list => list.forEach(disableElement));
    FilterOptionHandler.add(typeDropDown);

    dropDownContainer.append(typeDropDown.element, apartamentTypeDropDown.element);


    CreateAd.addImageInputElement = document.getElementById("add-image-input");
    CreateAd.imagesElement = document.getElementById("images");
    CreateAd.addImageInputElement.addEventListener("change", CreateAd.addImageHandler, true);

    const formElement = document.getElementsByTagName("form")[0];
    formElement.addEventListener("submit", CreateAd.submitHandler);
    formElement.addEventListener("formdata", CreateAd.formDataHandler);
})