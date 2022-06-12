DocumentHandler.whenReady(() => {
    let submitButton = new SubmitFilterOption();

    let apartamentType = DropdownFilterOption.createWithDefault(
        "ap_type",
        ["Decomandat", "Semidecomandat", "Nedecomandat", "Circular", "Open-Space"],
        "Tip"
    );

    let rooms = new SliderFilterOption("rooms", "Camere", "camere").set(1, 5).openRightDomain();

    FilterOptionHandler
        .add(DropdownFilterOption.create("type", [
            {
                text: "Apartament",
                linked: [apartamentType.element, rooms.element],
            },
            {
                text: "Casă",
                linked: [rooms.element]
            },
            "Teren"
        ]))
        .add(DropdownFilterOption.createWithDefault("by", ["Proprietar", "Firmă", "Dezvoltator"], "Oricine"))
        .add(new SliderFilterOption("price", "Preț", "Lei").set(120, 40000))
        .add(rooms)
        .add(apartamentType)
        .add(submitButton);
});

// --> /search?type=wefa&by
//