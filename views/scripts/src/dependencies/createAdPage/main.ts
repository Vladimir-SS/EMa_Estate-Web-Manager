

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

    const typeLinked = [
        [],
        [residentialSpecific, buildingSpecific, CreateAd.dropdownOptions.apType.element],
        [buildingSpecific, houseSpecific],
        [buildingSpecific],
        []
    ]

    typeLinked.forEach(list => list.forEach(disableElement));
    let lastIndex = 0;
    CreateAd.dropdownOptions.type.onChange((index, __text) => {
        typeLinked[lastIndex].forEach(disableElement);
        typeLinked[index].forEach(enableElement);
        lastIndex = index;
    });
    Object.values(CreateAd.dropdownOptions).forEach(op => FilterOptionHandler.add(op))

    //vreau o ordine
    dropDownContainer.append(
        CreateAd.dropdownOptions.type.element,
        CreateAd.dropdownOptions.apType.element,
        CreateAd.dropdownOptions.transaction.element
    );

    CreateAd.addImageInputElement = document.getElementById("add-image-input");
    CreateAd.imagesElement = document.getElementById("images");
    CreateAd.addImageInputElement.addEventListener("change", CreateAd.addImageHandler, true);

    const formElement = document.getElementsByTagName("form")[0];
    formElement.addEventListener("formdata", CreateAd.formDataHandler);
})


DocumentHandler.whenReady(() => {
    AddressMap.init();

    const { addressElement } = CreateAd;

    addressElement.oninput = () => AddressMap.searchAddress(addressElement.value);

    setInterval(() => {
        const currentAddress = addressElement.value;
        if (currentAddress != AddressMap.lastSearch)
            AddressMap.searchAddress(currentAddress);
    }, 1000);
})

