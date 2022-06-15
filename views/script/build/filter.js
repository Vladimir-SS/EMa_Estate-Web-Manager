class FilterOption {
    constructor(name) {
        this.setLabelTextIcon = (text, iconName) => {
            this.textNode = document.createElement("span");
            this.textNode.innerText = text;
            this.labelElement.append(this.textNode);
            this.labelElement.appendChild(createIcon(iconName));
        };
        this.getParameters = () => {
            return {};
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
    constructor(name, optionList) {
        super(name);
        this.chosenOptionIndex = 0;
        this.onChange = (__index, __text) => { };
        this.createOptionList = () => {
            let listElement = document.createElement("ul");
            this.options.forEach((text, index) => {
                const element = DropdownFilterOption.createOptionItem(text);
                element.onclick = () => this.optionItemHandler(index);
                listElement.append(element);
            });
            return listElement;
        };
        this.optionItemHandler = (index) => {
            this.chosenOptionIndex = index;
            this.textNode.textContent = this.options[index];
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
            const op = this.getCurrentOption();
            this.onChange(op.index, op.text);
        };
        this.getParameters = () => {
            if (this.labelElement.classList.contains("label--important"))
                return this.getCurrentOption().text;
            return {};
        };
        this.options = optionList;
        this.setLabelTextIcon("", "down-arrow");
        this.contentBoxElement.appendChild(this.createOptionList());
        this.element.classList.add("filter-option--dropdown");
        this.optionItemHandler(0);
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        const instance = new DropdownFilterOption(name, [defaultPlaceholder, ...optionList]);
        const child = instance.element.getElementsByTagName("ul")[0].firstChild;
        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };
        child.onclick(null);
        return instance;
    }
    getCurrentOption() {
        return {
            index: this.chosenOptionIndex,
            text: this.options[this.chosenOptionIndex]
        };
    }
}
DropdownFilterOption.createOptionItem = (text) => {
    let element = document.createElement("li");
    element.innerHTML = text;
    element.setAttribute("onclick", "");
    return element;
};

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

class SliderFilterOption extends FilterOption {
    constructor(name, labelText, unit) {
        super(name);
        this.resetSliders = () => {
            this.slider1.max = this.slider2.max = (this.values.length - 1).toString();
            this.slider2.value = this.slider2.max;
            this.slider1.value = '0';
            this.sliderOnInputEventHandler(null);
        };
        this.set = (min, max) => {
            this.values = SliderFilterOption.calcValues(min, max);
            this.resetSliders();
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
        this.getParameters = () => {
            const { min, max } = this.getMinMax();
            const minValue = this.values[min];
            const maxValue = this.values[max];
            if (min !== 0 && max !== this.values.length - 1)
                return {
                    [`${this.name}Max`]: maxValue,
                    [`${this.name}Min`]: minValue
                };
            else if (min !== 0)
                return {
                    [`${this.name}Min`]: minValue
                };
            else if (max !== this.values.length - 1)
                return {
                    [`${this.name}Max`]: maxValue,
                    [`${this.name}Min`]: minValue
                };
            else
                return {};
        };
        this.defaultLabelText = labelText;
        this.setLabelTextIcon(labelText, "left-right-arrow");
        this.element.classList.add("filter-option--slider");
        this.element.style.setProperty("--unit", `" ${unit}"`);
        this.contentBoxElement.append(this.createShowContainer(), this.createSlideContainer());
    }
    openRightDomain() {
        this.values.push(this.values[this.values.length - 1] + "+");
        this.resetSliders();
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
        this.labelElement.onclick = () => {
            console.log(FilterOptionHandler.submit());
        };
    }
}
