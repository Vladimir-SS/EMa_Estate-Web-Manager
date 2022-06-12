class FilterOption {
    constructor(name) {
        this.setLabelTextIcon = (text, iconName) => {
            this.textNode = document.createElement("span");
            this.textNode.innerText = text;
            this.labelElement.append(this.textNode);
            this.labelElement.appendChild(createIcon(iconName));
        };
        this.getParameters = () => {
            return null;
        };
        this.name = name;
        let element = document.createElement("div");
        element.className = "filter-option";
        let labelElement = FilterOption.createLabelElement();
        labelElement.onclick = () => { FilterOptionHandler.labelOnClickEventHandler(this); };
        let contentBoxElement = FilterOption.createContentBoxElement();
        element.appendChild(labelElement);
        element.appendChild(contentBoxElement);
        this.labelElement = labelElement;
        this.contentBoxElement = contentBoxElement;
        this.element = element;
    }
}
FilterOption.createLabelElement = () => {
    let labelElement = document.createElement("div");
    labelElement.className = `label label--flex`;
    labelElement.setAttribute("onclick", "");
    return labelElement;
};
FilterOption.createContentBoxElement = () => {
    let contentBoxElement = document.createElement("div");
    contentBoxElement.className = "content__box";
    return contentBoxElement;
};

class DropdownFilterOption extends FilterOption {
    constructor() {
        super(...arguments);
        this.chosenOptionIndex = 0;
        this.createOptionItem = (text) => {
            let element = document.createElement("li");
            element.innerHTML = text;
            element.setAttribute("onclick", "");
            return element;
        };
        this.createOptionList = (optionList) => {
            let listElement = document.createElement("ul");
            this.options = optionList.map(op => {
                let text, linked;
                if (typeof (op) === 'string') {
                    text = op;
                    linked = [];
                }
                else {
                    text = op.text;
                    linked = op.linked;
                }
                const element = this.createOptionItem(text);
                return { element, linked };
            });
            this.options.forEach((op, index) => {
                op.element.onclick = () => this.optionItemHandler(index);
                listElement.append(op.element);
                op.linked.forEach(el => el.style.display = "none");
            });
            return listElement;
        };
        this.optionItemHandler = (index) => {
            this.options[this.chosenOptionIndex]
                .linked.forEach(el => el.style.display = "none");
            this.chosenOptionIndex = index;
            const option = this.options[index];
            const { element, linked } = option;
            this.textNode.textContent = element.innerHTML;
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
            linked.forEach(el => el.style.removeProperty("display"));
        };
        this.getParameters = () => {
            return "";
        };
    }
    static create(name, optionList) {
        const instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon("", "down-arrow");
        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown");
        instance.optionItemHandler(0);
        return instance;
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        const instance = this.create(name, [defaultPlaceholder, ...optionList]);
        let child = instance.options[0].element;
        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };
        child.onclick(null);
        return instance;
    }
}

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

class SliderFilterOption extends FilterOption {
    constructor(name, labelText, unit) {
        super(name);
        this.openRight = false;
        this.set = (min, max) => {
            this.values = SliderFilterOption.calcValues(min, max);
            this.slider1.max = this.slider2.max = (this.values.length - 1).toString();
            this.slider2.value = this.slider2.max;
            this.slider1.value = '0';
            this.sliderOnInputEventHandler(null);
            return this;
        };
        this.getMinMax = () => {
            const val1 = Number(this.slider1.value);
            const val2 = Number(this.slider2.value);
            return {
                min: Math.min(val1, val2),
                max: Math.max(val1, val2)
            };
        };
        this.sliderOnChangeEventHandler = (__e) => {
            const { min, max } = this.getMinMax();
            const minValue = this.values[min];
            const maxValue = this.values[max];
            let labelText = this.defaultLabelText;
            const maxIndex = this.values.length - 1;
            if (min === 0 && max === maxIndex) {
                this.labelElement.classList.remove("label--important");
            }
            else {
                if (min === max)
                    labelText = `${minValue}`;
                else if (min !== 0 && max !== maxIndex)
                    labelText = `${minValue} - ${maxValue}`;
                else if (min !== 0)
                    labelText = `> ${minValue}`;
                else
                    labelText = `< ${maxValue}`;
                this.labelElement.classList.add("label--important");
            }
            this.textNode.textContent = labelText;
        };
        this.asPercent = (v) => v * 100 / (this.values.length - 1);
        this.sliderOnInputEventHandler = (__e) => {
            const { min, max } = this.getMinMax();
            this.tracker.style.left = `${this.asPercent(min)}%`;
            this.tracker.style.width = `${this.asPercent(max - min)}%`;
            this.showSmaller.textContent = this.values[min];
            this.showBigger.textContent = this.values[max];
        };
        this.createSlideContainer = () => {
            let slideContainer = document.createElement("div");
            slideContainer.className = "slider-container";
            let slider1 = document.createElement("input");
            slider1.type = "range";
            let slider2 = slider1.cloneNode();
            slider1.oninput = slider2.oninput = this.sliderOnInputEventHandler;
            slider1.onchange = slider2.onchange = this.sliderOnChangeEventHandler;
            let tracker = document.createElement("div");
            tracker.className = "slider-container__track";
            slideContainer.append(tracker, slider1, slider2);
            this.slider1 = slider1;
            this.slider2 = slider2;
            this.tracker = tracker;
            this.set(0, 100);
            return slideContainer;
        };
        this.createShowContainer = () => {
            let showContainerElement = document.createElement("div");
            showContainerElement.className = "show-container";
            this.showSmaller = document.createElement("p");
            this.showBigger = this.showSmaller.cloneNode();
            showContainerElement.append(this.showSmaller, this.showBigger);
            return showContainerElement;
        };
        this.defaultLabelText = labelText;
        this.setLabelTextIcon(labelText, "left-right-arrow");
        this.element.classList.add("filter-option--slider");
        this.element.style.setProperty("--unit", `" ${unit}"`);
        this.contentBoxElement.append(this.createShowContainer(), this.createSlideContainer());
    }
    openRightDomain() {
        this.openRight = true;
        return this;
    }
}
SliderFilterOption.calcValues = (min, max) => {
    let delta = max - min, z = Math.floor(Math.log10(delta)) - 1, ten2z = Math.pow(10, z), c = delta / ten2z, g;
    if (c < 25)
        g = 10;
    else if (c < 50)
        g = 25;
    else
        g = 50;
    let step = Math.max(ten2z / 10 * g, 1), vStart = min + (step - min % step), vEnd = max - max % step;
    let rv = [min, ...Array((vEnd - vStart) / step).fill(null).map((_, i) => vStart + step * i)];
    if (rv[length - 1] != max)
        rv.push(max);
    return rv.map(val => val.toString());
};

class SubmitFilterOption extends FilterOption {
    constructor() {
        super("submit");
        this.setLabelTextIcon("Caută acum anunțuri", "magnifying-glass");
        this.labelElement.classList.add("label--important");
        this.element.classList.add("filter-option--submit");
        this.labelElement.onclick = FilterOptionHandler.submit;
    }
}
