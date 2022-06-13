var _a;
class FilterOptionHandler {
}
_a = FilterOptionHandler;
FilterOptionHandler.lastFilterOption = null;
FilterOptionHandler.filterElement = document.getElementById("filter");
FilterOptionHandler.filterOptions = [];
FilterOptionHandler.closeLastElement = () => {
    if (_a.lastFilterOption === null)
        return;
    _a.lastFilterOption.element.classList.remove("show");
    _a.lastFilterOption = null;
};
FilterOptionHandler.labelOnClickEventHandler = (option) => {
    if (_a.lastFilterOption) {
        let isSameObject = _a.lastFilterOption === option;
        _a.closeLastElement();
        if (isSameObject)
            return;
    }
    option.element.classList.add("show");
    _a.lastFilterOption = option;
};
FilterOptionHandler.add = (option) => {
    _a.filterOptions.push(option);
    _a.filterElement.appendChild(option.element);
    return FilterOptionHandler;
};
FilterOptionHandler.submit = () => {
    let params = "?" + _a.filterOptions
        .map(o => o.getParameters())
        .filter(o => o)
        .join("&");
    console.log(params);
};
