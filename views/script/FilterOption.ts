class FilterOption {
    public element: HTMLDivElement;
    protected labelElement: HTMLDivElement;
    protected contentBoxElement: HTMLDivElement;
    protected textNode: HTMLSpanElement;
    protected name: String;

    private static createLabelElement = () => {
        let labelElement = document.createElement("div");
        labelElement.className = `label label--flex`;
        labelElement.setAttribute("onclick", "");

        return labelElement;
    }

    private static createContentBoxElement = () => {
        let contentBoxElement = document.createElement("div");
        contentBoxElement.className = "content-box";

        return contentBoxElement;
    }

    protected setLabelTextIcon = (text: string, iconName: string) => {
        this.textNode = document.createElement("span");
        this.textNode.innerText = text;
        this.labelElement.append(this.textNode);
        this.labelElement.appendChild(createIcon(iconName));
    }

    public getParameters = (): string => {
        return null;
    }

    constructor(name: string) {
        this.name = name;
        let element = document.createElement("div");
        element.className = "filter-option";

        let labelElement = FilterOption.createLabelElement();
        labelElement.onclick = () => { FilterOptionHandler.labelOnClickEventHandler(this) };

        let contentBoxElement = FilterOption.createContentBoxElement();

        element.appendChild(labelElement);
        element.appendChild(contentBoxElement);

        this.labelElement = labelElement;
        this.contentBoxElement = contentBoxElement;
        this.element = element;
    }
}

/**
 * DropdownFilterOption
 */

class DropdownFilterOption extends FilterOption {
    private chosenOptionItem: HTMLLIElement = null;

    private createOptionItem = (text: string) => {
        let element = document.createElement("li");
        element.innerHTML = text;
        element.setAttribute("onclick", "");
        element.onclick = () => this.optionItemHandler(element);

        return element;
    }

    private createOptionList = (optionList: string[]) => {
        let listElement = document.createElement("ul");

        optionList
            .map(this.createOptionItem)
            .forEach(e => listElement.append(e));

        return listElement;
    }

    private optionItemHandler = (optionItem: HTMLLIElement) => {
        this.textNode.textContent = optionItem.innerHTML;
        this.chosenOptionItem = optionItem;
        this.labelElement.classList.add("label--important")
        FilterOptionHandler.closeLastElement();
    }

    private static create(name: string, optionList: string[], labelText: string) {
        let instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon(labelText, "down-arrow")

        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown")

        return instance;
    }

    static createWithIndex(name: string, optionList: string[], index: number) {
        let instance = this.create(name, optionList, optionList[index]);
        let child = instance.contentBoxElement.firstElementChild.children[index] as HTMLLIElement;;

        instance.optionItemHandler(child);
        return instance;
    }

    static createWithDefault(name: string, optionList: string[], defaultPlaceholder: string) {
        let instance = this.create(name, [defaultPlaceholder, ...optionList], defaultPlaceholder);
        let child = instance.contentBoxElement.firstChild.firstChild as HTMLLIElement;

        child.onclick = () => {
            instance.optionItemHandler(child);
            instance.labelElement.classList.remove("label--important")
            instance.chosenOptionItem = null;
        };

        return instance;
    }

    override  getParameters = () => {
        return this.chosenOptionItem ? `${this.name}=${this.chosenOptionItem.innerHTML}` : null;
    }
}

/**
 * SliderFilterOption
 */

class SliderFilterOption extends FilterOption {
    private min: number;
    private delta: number;
    private defaultLabelText: string;
    private step: number;

    private slider1: HTMLInputElement;
    private slider2: HTMLInputElement;
    private tracker: HTMLDivElement;
    private showSmaller: HTMLParagraphElement;
    private showBigger: HTMLParagraphElement;

    public set = (min: number, max: number) => {
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
    }

    private getMinMax = () => {
        let val1 = parseInt(this.slider1.value);
        let val2 = parseInt(this.slider2.value);
        let minVal = Math.min(val1, val2);
        let maxVal = Math.max(val1, val2);

        return [minVal, maxVal];
    }

    private sliderOnChangeEventHandler = (e: Event): void => {
        let [min, max] = this.getMinMax();
        let minValue = this.percentToValue(min);
        let maxValue = this.percentToValue(max);

        let labelText: string = this.defaultLabelText;

        if (min === 0 && max === 100) {
            this.labelElement.classList.remove("label--important");
        }
        else {
            if (min !== 0 && max !== 100)
                labelText = `${minValue} - ${maxValue}`;
            else if (min !== 0)
                labelText = `> ${minValue}`
            else
                labelText = `< ${maxValue}`

            this.labelElement.classList.add("label--important");
        }

        this.textNode.textContent = labelText;
    }


    private sliderOnInputEventHandler = (e: Event): void => {
        let [min, max] = this.getMinMax();

        this.tracker.style.left = `${min}%`;
        this.tracker.style.width = `${Math.abs(max - min)}%`;

        this.updateShowContainer(min, max);
    }

    private createSlideContainer = () => {
        let slideContainer: HTMLDivElement = document.createElement("div");
        slideContainer.className = "slider-container";

        this.slider1 = document.createElement("input");
        this.slider1.type = "range";
        this.slider1.min = "0";
        this.slider1.max = "100";
        this.slider1.value = "0";


        this.slider2 = this.slider1.cloneNode() as HTMLInputElement;
        this.slider2.value = "100";
        this.slider1.oninput = this.slider2.oninput = this.sliderOnInputEventHandler;
        this.slider1.onchange = this.slider2.onchange = this.sliderOnChangeEventHandler;

        this.tracker = document.createElement("div");
        this.tracker.className = "slider-container__track";


        slideContainer.append(this.tracker, this.slider1, this.slider2);

        return slideContainer;
    }

    private percentToValue = (percent: number) => {
        let simple = Math.ceil(percent * this.delta / 100) + this.min;

        if (percent === 0 || percent === 100)
            return simple;

        return simple - simple % this.step + this.step;
    }

    private updateShowContainer(min: number, max: number) {
        this.showSmaller.innerText = this.percentToValue(min).toString();
        this.showBigger.innerText = this.percentToValue(max).toString();
    }


    private createShowContainer = () => {
        let showContainerElement = document.createElement("div");
        showContainerElement.className = "show-container";

        this.showSmaller = document.createElement("p");

        this.showBigger = this.showSmaller.cloneNode() as HTMLParagraphElement;

        this.updateShowContainer(0, 100);
        showContainerElement.append(this.showSmaller, this.showBigger);

        return showContainerElement;
    }

    constructor(name: string, labelText: string, unit: string) {
        super(name);
        this.defaultLabelText = labelText;

        this.setLabelTextIcon(labelText, "left-right-arrow");
        this.element.classList.add("filter-option--slider");
        this.element.style.setProperty("--unit", `" ${unit}"`);

        this.contentBoxElement.append(this.createShowContainer(), this.createSlideContainer());
    }

    override getParameters = () => {
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