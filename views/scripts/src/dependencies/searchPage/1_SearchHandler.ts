type DropdownMap = { [key in keyof typeof Options.dropdown]: string[] };

class SearchHandler {

    public static itemsElement = document.getElementById('items');

    private static dropdownMapToString: DropdownMap = {
        apType: ["", "detached", "semi-detached", "non-detached", "circular", "open-space"],
        type: ["apartment", "house", "office", "land"],
        by: ["", "individual", "company"],
        transaction: ["rent", "sell"]
    };
    static curency = " Ron";

    public static resizeHandler() {
        if (window.innerWidth <= 1220)
            SearchHandler.itemsElement.classList.add('chubby-items');
        else
            SearchHandler.itemsElement.classList.remove('chubby-items');
    }

    public static toApiParams(): string {

        const dropdownParams = Object.entries(Options.dropdown)
            .filter(([__key, op]) => op.element.style.display != "none" && op.isSelected())
            .map(([key, op]) => `${key}=${SearchHandler.dropdownMapToString[key][op.getOption().index]}`);


        const sliderParams = Object.entries(Options.slider)
            .filter(([__key, op]) => op.element.style.display != "none" && op.isSelected())
            .map(([key, op]) => Object.entries(op.getOption()).map(([minmax, value]) => `${key}${minmax}=${value}`).join("&")
            );

        return "?" + [...dropdownParams, ...sliderParams].join("&");
    }

    private static addItem(itemData: ItemData) {
        const item = Item.create(itemData)

        const toIcon = createIcon("next");
        item.appendChild(toIcon);
        toIcon.setAttribute("onclick", "");
        SearchHandler.itemsElement.appendChild(item);

        toIcon.onclick = () => MapHandler.setTo(itemData.lon, itemData.lat);
    }

    public static getItems() {
        const url = '/api/items/filter' + SearchHandler.toApiParams();

        fetch(url)
            .then(response => response.json())
            .then(data =>
                data.forEach(val => SearchHandler.addItem(val))
            )
    }
}


