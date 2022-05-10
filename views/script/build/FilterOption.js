var __classPrivateFieldGet = (this && this.__classPrivateFieldGet) || function (receiver, state, kind, f) {
    if (kind === "a" && !f) throw new TypeError("Private accessor was defined without a getter");
    if (typeof state === "function" ? receiver !== state || !f : !state.has(receiver)) throw new TypeError("Cannot read private member from an object whose class did not declare it");
    return kind === "m" ? f : kind === "a" ? f.call(receiver) : f ? f.value : state.get(receiver);
};
var _a, _FilterOption_createLabel;
class FilterOption {
    constructor(labelContent, className, content) {
        this.element = document.createElement("div");
        this.element.className = `filter-option ${className}`;
        this.element.appendChild(__classPrivateFieldGet(FilterOption, _a, "f", _FilterOption_createLabel).call(FilterOption, labelContent));
    }
}
_a = FilterOption;
_FilterOption_createLabel = { value: (content) => {
        let lebelElement = document.createElement("div");
        lebelElement.className = `label label--flex`;
        lebelElement.append(content);
        return lebelElement;
    } };
