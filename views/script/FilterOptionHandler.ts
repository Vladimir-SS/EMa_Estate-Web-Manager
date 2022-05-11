class FilterOptionHandler {
    private static lastFilterOption: FilterOption;
    private static filterElement = document.getElementById("filter");
    private static filterOptions: FilterOption[] = [];

    public static closeLastElement = () => {
        this.lastFilterOption.element.classList.remove("show");
        this.lastFilterOption = null;
    }

    public static labelOnClickEventHandler = (option: FilterOption) => {
        if (this.lastFilterOption) {
            let isSameObject = this.lastFilterOption === option;

            this.closeLastElement();

            if (isSameObject)
                return;

        }
        option.element.classList.add("show");
        this.lastFilterOption = option;
    }

    public static add = (option: FilterOption) => {
        this.filterOptions.push(option);
        this.filterElement.appendChild(option.element);

        return FilterOptionHandler;
    }

    static submit = () => {
        let params = "?" + this.filterOptions
            .map(o => o.getParameters())
            .filter(o => o)
            .join("&");

        console.log(params);
    }
}