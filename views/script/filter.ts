

DocumentHandler.whenReady(() => {
    let submitButton = new SubmitFilterOption();

    FilterOptionHandler
        .add(DropdownFilterOption.createWithIndex("type", ["Apartament", "Casă"], 1))
        .add(DropdownFilterOption.createWithDefault("by", ["Proprietar", "Firmă", "Dezvoltator"], "Oricine"))
        .add(new SliderFilterOption("price", "Preț", "Lei").set(120, 4000))
        .add(submitButton);
});

