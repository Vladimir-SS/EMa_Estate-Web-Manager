DocumentHandler.whenReady(() => {
    FilterOptionHandler
        .add(DropdownFilterOption.createWithIndex("type", ["Apartament", "Casă"], 1))
        .add(DropdownFilterOption.createWithDefault("by", ["Proprietar", "Firmă", "Dezvoltator"], "Oricine"));
});
