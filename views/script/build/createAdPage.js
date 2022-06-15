var _a;
class CreateAd {
}
_a = CreateAd;
CreateAd.uploadedFiles = [];
CreateAd.addImage = (alt, url) => {
    const el = document.createElement('img');
    el.src = url;
    el.alt = alt;
    CreateAd.imagesElement.append(el);
};
CreateAd.addImageHandler = (event) => {
    const target = event.target;
    const files = target.files;
    for (let i = 0; i < files.length; ++i) {
        const file = files[i];
        const imgURL = URL.createObjectURL(file);
        _a.uploadedFiles.push(file);
        console.log(_a.uploadedFiles);
        CreateAd.addImage(file.name, imgURL);
        if (_a.uploadedFiles.length === 10) {
            _a.addImageInputElement.parentElement.style.display = "none";
            console.log("remove");
            return;
        }
    }
};
CreateAd.formDataHandler = (event) => {
    event.formData.delete('images');
    CreateAd.uploadedFiles.forEach((file, index) => event.formData.set(`image${index}`, file));
    const type = CreateAd.typeDropDown.getCurrentOption();
    event.formData.set('TYPE', type.index.toString());
    if (type.index == 1)
        event.formData.set('AP_TYPE', CreateAd.apartamentTypeDropDown.getCurrentOption().index.toString());
};
const disableElement = (el) => {
    el.style.display = "none";
    if (el.tagName === 'FIELDSET')
        el.setAttribute('disabled', '');
};
const enableElement = (el) => {
    el.style.removeProperty("display");
    if (el.tagName === 'FIELDSET')
        el.removeAttribute('disabled');
};
DocumentHandler.whenReady(() => {
    const residentialSpecific = document.getElementById("residential-specific");
    const houseSpecific = document.getElementById("house-specific");
    const buildingSpecific = document.getElementById("building-specific");
    const dropDownContainer = document.getElementById("dropdown-container");
    CreateAd.apartamentTypeDropDown = DropdownFilterOption.createWithDefault("type", ["Decomandat", "Nedecomandat", "Semidecomandat", "Circular"], "Alege tipul apartamentului");
    const typeLinked = [
        [],
        [residentialSpecific, buildingSpecific, CreateAd.apartamentTypeDropDown.element],
        [buildingSpecific, houseSpecific],
        [buildingSpecific],
        []
    ];
    CreateAd.typeDropDown = DropdownFilterOption.createWithDefault("type", ["Apartament", "Casă", "Office", "Teren"], "Alege tipul proprietății");
    let lastIndex = 0;
    CreateAd.typeDropDown.onChange = (index, __text) => {
        typeLinked[lastIndex].forEach(disableElement);
        typeLinked[index].forEach(enableElement);
        lastIndex = index;
    };
    typeLinked.forEach(list => list.forEach(disableElement));
    FilterOptionHandler.add(CreateAd.typeDropDown);
    dropDownContainer.append(CreateAd.typeDropDown.element, CreateAd.apartamentTypeDropDown.element);
    CreateAd.addImageInputElement = document.getElementById("add-image-input");
    CreateAd.imagesElement = document.getElementById("images");
    CreateAd.addImageInputElement.addEventListener("change", CreateAd.addImageHandler, true);
    const formElement = document.getElementsByTagName("form")[0];
    formElement.addEventListener("formdata", CreateAd.formDataHandler);
});
