class FilterOptionHandler {
    private static lastFilterOption: FilterOption = null;
    private static filterElement = document.getElementById("filter");
    private static filterOptions: FilterOption[] = [];

    public static closeLastElement = () => {
        if (FilterOptionHandler.lastFilterOption == null)
            return;

        FilterOptionHandler.lastFilterOption.element.classList.remove("show");
        FilterOptionHandler.lastFilterOption = null;
    }

    public static labelOnClickEventHandler = (option: FilterOption) => {
        if (FilterOptionHandler.lastFilterOption) {
            let isSameObject = FilterOptionHandler.lastFilterOption === option;

            FilterOptionHandler.closeLastElement();

            if (isSameObject)
                return;

        }
        option.element.classList.add("show");
        FilterOptionHandler.lastFilterOption = option;
    }

    public static add = (option: FilterOption) => {

        FilterOptionHandler.filterOptions.push(option);
        if (FilterOptionHandler.filterElement != null)
            FilterOptionHandler.filterElement.appendChild(option.element);

        return FilterOptionHandler;
    }
}