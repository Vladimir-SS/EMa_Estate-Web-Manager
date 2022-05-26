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
        this.chosenOptionItem = null;
        this.createOptionItem = (text) => {
            let element = document.createElement("li");
            element.innerHTML = text;
            element.setAttribute("onclick", "");
            element.onclick = () => this.optionItemHandler(element);
            return element;
        };
        this.createOptionList = (optionList) => {
            let listElement = document.createElement("ul");
            optionList
                .map(this.createOptionItem)
                .forEach(e => listElement.append(e));
            return listElement;
        };
        this.optionItemHandler = (optionItem) => {
            this.textNode.textContent = optionItem.innerHTML;
            this.chosenOptionItem = optionItem;
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
        };
        this.getParameters = () => {
            return this.chosenOptionItem ? `${this.name}=${this.chosenOptionItem.innerHTML}` : null;
        };
    }
    static create(name, optionList, labelText) {
        let instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon(labelText, "down-arrow");
        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown");
        return instance;
    }
    static createWithIndex(name, optionList, index) {
        let instance = this.create(name, optionList, optionList[index]);
        let child = instance.contentBoxElement.firstElementChild.children[index];
        ;
        instance.optionItemHandler(child);
        return instance;
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        let instance = this.create(name, [defaultPlaceholder, ...optionList], defaultPlaceholder);
        let child = instance.contentBoxElement.firstChild.firstChild;
        child.onclick = () => {
            instance.optionItemHandler(child);
            instance.labelElement.classList.remove("label--important");
            instance.chosenOptionItem = null;
        };
        return instance;
    }
}
class SliderFilterOption extends FilterOption {
    constructor(name, labelText, unit) {
        super(name);
        this.set = (min, max) => {
            this.min = min;
            this.delta = max - min;
            let step = Math.ceil(this.delta / 20);
            if (step < 5)
                step = 1;
            else if (step < 10)
                step = 5;
            else
                step = step - step % 10;
            this.step = step;
            this.updateShowContainer(0, 100);
            return this;
        };
        this.getMinMax = () => {
            let val1 = parseInt(this.slider1.value);
            let val2 = parseInt(this.slider2.value);
            let minVal = Math.min(val1, val2);
            let maxVal = Math.max(val1, val2);
            return [minVal, maxVal];
        };
        this.sliderOnChangeEventHandler = (e) => {
            let [min, max] = this.getMinMax();
            let minValue = this.percentToValue(min);
            let maxValue = this.percentToValue(max);
            let labelText = this.defaultLabelText;
            if (min === 0 && max === 100) {
                this.labelElement.classList.remove("label--important");
            }
            else {
                if (min !== 0 && max !== 100)
                    labelText = `${minValue} - ${maxValue}`;
                else if (min !== 0)
                    labelText = `> ${minValue}`;
                else
                    labelText = `< ${maxValue}`;
                this.labelElement.classList.add("label--important");
            }
            this.textNode.textContent = labelText;
        };
        this.sliderOnInputEventHandler = (e) => {
            let [min, max] = this.getMinMax();
            this.tracker.style.left = `${min}%`;
            this.tracker.style.width = `${Math.abs(max - min)}%`;
            this.updateShowContainer(min, max);
        };
        this.createSlideContainer = () => {
            let slideContainer = document.createElement("div");
            slideContainer.className = "slider-container";
            this.slider1 = document.createElement("input");
            this.slider1.type = "range";
            this.slider1.min = "0";
            this.slider1.max = "100";
            this.slider1.value = "0";
            this.slider2 = this.slider1.cloneNode();
            this.slider2.value = "100";
            this.slider1.oninput = this.slider2.oninput = this.sliderOnInputEventHandler;
            this.slider1.onchange = this.slider2.onchange = this.sliderOnChangeEventHandler;
            this.tracker = document.createElement("div");
            this.tracker.className = "slider-container__track";
            slideContainer.append(this.tracker, this.slider1, this.slider2);
            return slideContainer;
        };
        this.percentToValue = (percent) => {
            let simple = Math.ceil(percent * this.delta / 100) + this.min;
            if (percent === 0 || percent === 100)
                return simple;
            return simple - simple % this.step + this.step;
        };
        this.createShowContainer = () => {
            let showContainerElement = document.createElement("div");
            showContainerElement.className = "show-container";
            this.showSmaller = document.createElement("p");
            this.showBigger = this.showSmaller.cloneNode();
            this.updateShowContainer(0, 100);
            showContainerElement.append(this.showSmaller, this.showBigger);
            return showContainerElement;
        };
        this.getParameters = () => {
            const [min, max] = this.getMinMax();
            if (min === 0 && max === 100)
                return null;
            let minValue = `${this.name}_min=${this.percentToValue(min)}`;
            let maxValue = `${this.name}_max=${this.percentToValue(max)}`;
            if (min !== 0 && max !== 100)
                return `${minValue}&${maxValue}`;
            if (min !== 0)
                return minValue;
            return maxValue;
        };
        this.defaultLabelText = labelText;
        this.setLabelTextIcon(labelText, "left-right-arrow");
        this.element.classList.add("filter-option--slider");
        this.element.style.setProperty("--unit", `" ${unit}"`);
        this.contentBoxElement.append(this.createShowContainer(), this.createSlideContainer());
    }
    updateShowContainer(min, max) {
        this.showSmaller.innerText = this.percentToValue(min).toString();
        this.showBigger.innerText = this.percentToValue(max).toString();
    }
}
class SubmitFilterOption extends FilterOption {
    constructor() {
        super("submit");
        this.setLabelTextIcon("Caută acum anunțuri", "magnifying-glass");
        this.labelElement.classList.add("label--important");
        this.element.classList.add("filter-option--submit");
        this.labelElement.onclick = FilterOptionHandler.submit;
    }
}
