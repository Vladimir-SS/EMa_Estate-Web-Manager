class FilterOptionHandler {
}
FilterOptionHandler.lastFilterOption = null;
FilterOptionHandler.filterElement = document.getElementById("filter");
FilterOptionHandler.filterOptions = [];
FilterOptionHandler.closeLastElement = () => {
    if (FilterOptionHandler.lastFilterOption == null)
        return;
    FilterOptionHandler.lastFilterOption.element.classList.remove("show");
    FilterOptionHandler.lastFilterOption = null;
};
FilterOptionHandler.labelOnClickEventHandler = (option) => {
    if (FilterOptionHandler.lastFilterOption) {
        let isSameObject = FilterOptionHandler.lastFilterOption === option;
        FilterOptionHandler.closeLastElement();
        if (isSameObject)
            return;
    }
    option.element.classList.add("show");
    FilterOptionHandler.lastFilterOption = option;
};
FilterOptionHandler.add = (option) => {
    FilterOptionHandler.filterOptions.push(option);
    if (FilterOptionHandler.filterElement != null)
        FilterOptionHandler.filterElement.appendChild(option.element);
    return FilterOptionHandler;
};
FilterOptionHandler.submit = () => {
    return FilterOptionHandler.filterOptions
        .filter(op => op.element.style.display != "none")
        .map(op => op.getParameters())
        .reduce((previus, current) => {
        return Object.assign(Object.assign({}, previus), current);
    }, {});
};
