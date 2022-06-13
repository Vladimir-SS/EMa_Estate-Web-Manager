class FilterOptionHandler {
    private static lastFilterOption: FilterOption = null;
    private static filterElement = document.getElementById("filter");
    private static filterOptions: FilterOption[] = [];

    public static closeLastElement = () => {
        if (this.lastFilterOption === null)
            return;
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

        return this.filterOptions
            .filter(op => op.element.style.display != "none")
            .map(op => op.getParameters())
            .reduce((previus, current) => {
                return { ...previus, ...current }
            }, {});
    }
}