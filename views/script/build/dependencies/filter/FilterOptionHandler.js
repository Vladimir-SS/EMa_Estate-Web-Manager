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
    return _a.filterOptions
        .filter(op => op.element.style.display != "none")
        .map(op => op.getParameters())
        .reduce((previus, current) => {
        return Object.assign(Object.assign({}, previus), current);
    }, {});
};
