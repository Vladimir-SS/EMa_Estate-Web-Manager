type DropdownMap = { [key in keyof typeof Options.dropdown]: string[] };

class SearchHandler {

    public static itemsElement = document.getElementById('items');

    private static dropdownMapToString: DropdownMap = {
        apType: ["", "detached", "semi-detached", "non-detached", "circular", "open-space"],
        type: ["apartment", "house", "office", "land"],
        by: ["", "individual", "company"],
        transaction: ["rent", "sell"]
    };

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

    public static getItems() {
        var xmlHttpRequest = new XMLHttpRequest();
        let obj: ItemData[];
        xmlHttpRequest.open('GET', '/api/items/filter' + SearchHandler.toApiParams(), true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                obj = JSON.parse(xmlHttpRequest.responseText);
                let items = document.getElementById('items');
                for (const key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        items.appendChild(Item.create(obj[key]));
                    }
                }
            }
        }
        xmlHttpRequest.send();
    }

}

Options.onSubmit = () => {
    window.history.replaceState(null, null, Options.toGetParams());

    const items = document.getElementById('items');
    items.innerHTML = "";
    SearchHandler.getItems();
};

DocumentHandler.whenReady(() => {
    SearchHandler.resizeHandler();
    SearchHandler.getItems();
})

window.addEventListener('resize', SearchHandler.resizeHandler);
