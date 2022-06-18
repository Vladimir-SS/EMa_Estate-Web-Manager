class CreateAd {
    public static addressElement = document.getElementsByName("ADDRESS")[0] as HTMLInputElement;
    public static addImageInputElement: HTMLElement;
    public static imagesElement: HTMLElement;
    private static uploadedFiles: File[] = [];

    public static dropdownOptions: { [key: string]: DropdownFilterOption } = {
        apType: DropdownFilterOption.createWithDefault(
            ["Decomandat", "Semidecomandat", "Nedecomandat", "Circular", "Open-Space"],
            "Alege tipul apartamentului"
        ),
        type: DropdownFilterOption.createWithDefault(
            ["Apartament", "Casă", "Office", "Teren"],
            "Alege tipul proprietății"
        ),
        transaction: DropdownFilterOption.createWithDefault(["Închiriere", "Cumpărare"], "Alege tipul anunțului")
    }

    private static dropdownMapToString: DropdownMap = {
        apType: ["", "detached", "semi-detached", "non-detached", "circular", "open-space"],
        type: ["", "apartment", "house", "office", "land"],
        transaction: ["", "rent", "sell"]
    };

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

    public static formDataHandler = (event: FormDataEvent) => {
        event.formData.delete('images');
        CreateAd.uploadedFiles.forEach((file, index) => event.formData.set(`image${index}`, file));
        const type = CreateAd.dropdownOptions.type.getOption();
        event.formData.set('TYPE', type.index.toString());
        if (type.index == 1) // type: Apartment
            event.formData.set('AP_TYPE', CreateAd.dropdownOptions.apType.getOption().index.toString());
        event.formData.set('TRANSACTION_TYPE', CreateAd.dropdownOptions.transaction.getOption().index.toString());
        if (AddressMap.coordinates != null) {
            event.formData.set('LAT', AddressMap.coordinates.lat.toString());
            event.formData.set('LON', AddressMap.coordinates.lon.toString());
        }
    }
}