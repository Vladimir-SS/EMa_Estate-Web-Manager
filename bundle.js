var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
var FilterOption = /** @class */ (function () {
    function FilterOption(name) {
        var _this = this;
        this.setLabelTextIcon = function (text, iconName) {
            _this.textNode = document.createElement("span");
            _this.textNode.innerText = text;
            _this.labelElement.append(_this.textNode);
            _this.labelElement.appendChild(createIcon(iconName));
        };
        this.getParameters = function () {
            return null;
        };
        this.name = name;
        var element = document.createElement("div");
        element.className = "filter-option";
        var labelElement = FilterOption.createLabelElement();
        labelElement.onclick = function () { FilterOptionHandler.labelOnClickEventHandler(_this); };
        var contentBoxElement = FilterOption.createContentBoxElement();
        element.appendChild(labelElement);
        element.appendChild(contentBoxElement);
        this.labelElement = labelElement;
        this.contentBoxElement = contentBoxElement;
        this.element = element;
    }
    FilterOption.createLabelElement = function () {
        var labelElement = document.createElement("div");
        labelElement.className = "label label--flex";
        labelElement.setAttribute("onclick", "");
        return labelElement;
    };
    FilterOption.createContentBoxElement = function () {
        var contentBoxElement = document.createElement("div");
        contentBoxElement.className = "content__box";
        return contentBoxElement;
    };
    return FilterOption;
}());
var DropdownFilterOption = /** @class */ (function (_super) {
    __extends(DropdownFilterOption, _super);
    function DropdownFilterOption() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.chosenOptionItem = null;
        _this.lastLinked = [];
        _this.createOptionItem = function (option) {
            var text;
            var linked;
            if (typeof (option) === 'string') {
                text = option;
                linked = [];
            }
            else {
                (text = option.text, linked = option.linked);
            }
            var element = document.createElement("li");
            element.innerHTML = text;
            element.setAttribute("onclick", "");
            element.onclick = function () { return _this.optionItemHandler(element, linked); };
            return element;
        };
        _this.createOptionList = function (optionList) {
            var listElement = document.createElement("ul");
            optionList
                .map(_this.createOptionItem)
                .forEach(function (e) { return listElement.append(e); });
            return listElement;
        };
        _this.optionItemHandler = function (optionItem, linked) {
            _this.textNode.textContent = optionItem.innerHTML;
            _this.chosenOptionItem = optionItem;
            _this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
            _this.lastLinked.forEach(function (el) { return el.style.display = "none"; });
            linked.forEach(function (el) { return el.style.removeProperty("display"); });
            _this.lastLinked = linked;
        };
        _this.getParameters = function () {
            return _this.chosenOptionItem ? "".concat(_this.name, "=").concat(_this.chosenOptionItem.innerHTML) : null;
        };
        return _this;
    }
    DropdownFilterOption.create = function (name, optionList) {
        var instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon("", "down-arrow");
        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown");
        return instance;
    };
    DropdownFilterOption.createWithIndex = function (name, optionList) {
        var instance = this.create(name, optionList);
        var child = instance.contentBoxElement.firstElementChild.firstChild;
        ;
        instance.optionItemHandler(child, []);
        return instance;
    };
    DropdownFilterOption.createWithDefault = function (name, optionList, defaultPlaceholder) {
        var instance = this.create(name, __spreadArray([defaultPlaceholder], optionList, true));
        var child = instance.contentBoxElement.firstChild.firstChild;
        child.onclick = function () {
            instance.optionItemHandler(child, []);
            instance.labelElement.classList.remove("label--important");
            instance.chosenOptionItem = null;
        };
        return instance;
    };
    return DropdownFilterOption;
}(FilterOption));
/// <reference path="./filters/FilterOption.ts" />
/// <reference path="./filters/DropdownFilterOption.ts" />
var SubmitFilterOption = /** @class */ (function (_super) {
    __extends(SubmitFilterOption, _super);
    function SubmitFilterOption() {
        var _this = _super.call(this, "submit") || this;
        _this.setLabelTextIcon("Caută acum anunțuri", "magnifying-glass");
        _this.labelElement.classList.add("label--important");
        _this.element.classList.add("filter-option--submit");
        _this.labelElement.onclick = FilterOptionHandler.submit;
        return _this;
    }
    return SubmitFilterOption;
}(FilterOption));
