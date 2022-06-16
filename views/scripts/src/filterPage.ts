DocumentHandler.whenReady(() => {
    const apartamentTypeDropdown = DropdownFilterOption.createWithDefault(
        "ap_type",
        ["Decomandat", "Semidecomandat", "Nedecomandat", "Circular", "Open-Space"],
        "Tip"
    );
    const typeDropdown = new DropdownFilterOption("type", ["Apartament", "Casă", "Office", "Teren"]);
    const sellerDropdown = DropdownFilterOption.createWithDefault("by", ["Proprietar", "Firmă", "Dezvoltator"], "Oricine")
    const transactionDropdown = new DropdownFilterOption("transaction_type", ["Închiriere", "Cumpărare"])

    const roomsSlider = new SliderFilterOption("rooms", "Camere", "camere").set(1, 5).openRightDomain();
    const bathroomsSlider = new SliderFilterOption("bathrooms", "Băi", "băi").set(1, 3).openRightDomain();
    const priceSlider = new SliderFilterOption("price", "Preț", "Lei").set(120, 40000);
    const builtInSlider = new SliderFilterOption("builtIn", "Anul Construcției", "").set(1950, 2022).openRightDomain().openLeftDomain();


    const submitButton = new SubmitFilterOption();


    const typeDependent = [
        [apartamentTypeDropdown, roomsSlider, bathroomsSlider, builtInSlider], //Apartment
        [roomsSlider, bathroomsSlider, builtInSlider], //House
        [bathroomsSlider, builtInSlider], //Office
        [], //Land
    ];

    typeDependent.forEach(list => list.forEach(op => op.element.style.display = "none"));
    let lastIndex = 0;
    typeDropdown.onChange((index, __text) => {
        typeDependent[lastIndex].forEach(op => op.element.style.display = "none");
        typeDependent[index].forEach(op => op.element.style.removeProperty("display"));
        lastIndex = index;
    });

    const options: FilterOption[] = [
        typeDropdown,
        transactionDropdown,
        apartamentTypeDropdown,
        priceSlider,
        sellerDropdown,
        roomsSlider,
        bathroomsSlider,
        builtInSlider,
        submitButton
    ];

    //overengineering :)) i changed that .add.add.add.add with this
    options.reduce((handler, current) => handler.add(current), FilterOptionHandler);

});