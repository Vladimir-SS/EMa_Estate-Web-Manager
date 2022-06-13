

class SliderFilterOption extends FilterOption {
    private values: string[];
    private defaultLabelText: string;
    private openRight: boolean = false;

    private showBigger: HTMLParagraphElement;
    private showSmaller: HTMLParagraphElement;
    private slider1: HTMLInputElement;
    private slider2: HTMLInputElement;
    private tracker: HTMLDivElement;


    private static calcValues = (min: number, max: number): string[] => {
        let
            delta = max - min,
            z = Math.floor(Math.log10(delta)) - 1,
            ten2z = Math.pow(10, z),
            c = delta / ten2z,
            g;

        if (c < 25)
            g = 10;
        else if (c < 50)
            g = 25;
        else
            g = 50;

        let step = Math.max(ten2z / 10 * g, 1),
            vStart = min + (step - min % step),
            vEnd = max - max % step;

        let rv: number[] = [min, ...Array((vEnd - vStart) / step).fill(null).map((_, i) => vStart + step * i)];
        if (rv[length - 1] != max)
            rv.push(max);
        return rv.map(val => val.toString());
    }

    public set = (min: number, max: number) => {

        this.values = SliderFilterOption.calcValues(min, max);

        this.slider1.max = this.slider2.max = (this.values.length - 1).toString();
        this.slider2.value = this.slider2.max;
        this.slider1.value = '0';
        this.sliderOnInputEventHandler(null);

        return this;
    }

    private getMinMax = () => {
        const val1: number = Number(this.slider1.value);
        const val2: number = Number(this.slider2.value);
        return {
            min: Math.min(val1, val2),
            max: Math.max(val1, val2)
        };
    }

    private sliderOnChangeEventHandler = (__e: Event): void => {
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
                labelText = `${minValue}`
            else if (min !== 0 && max !== maxIndex)
                labelText = `${minValue} - ${maxValue}`;
            else if (min !== 0)
                labelText = `> ${minValue}`;
            else
                labelText = `< ${maxValue}`;
            this.labelElement.classList.add("label--important");
        }
        this.textNode.textContent = labelText;
    }


    private asPercent = (v: number) => v * 100 / (this.values.length - 1);

    private sliderOnInputEventHandler = (__e: Event): void => {
        const { min, max } = this.getMinMax();

        this.tracker.style.left = `${this.asPercent(min)}%`;
        this.tracker.style.width = `${this.asPercent(max - min)}%`;

        this.showSmaller.textContent = this.values[min];
        this.showBigger.textContent = this.values[max];
    }

    private createSlideContainer = () => {
        let slideContainer: HTMLDivElement = document.createElement("div");
        slideContainer.className = "slider-container";

        let slider1 = document.createElement("input");
        slider1.type = "range";
        let slider2 = slider1.cloneNode() as HTMLInputElement;

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
    }


    private createShowContainer = () => {
        let showContainerElement = document.createElement("div");
        showContainerElement.className = "show-container";

        this.showSmaller = document.createElement("p");
        this.showBigger = this.showSmaller.cloneNode() as HTMLParagraphElement;
        showContainerElement.append(this.showSmaller, this.showBigger);

        return showContainerElement;
    }

    public openRightDomain() {
        this.openRight = true;

        return this;
    }

    constructor(name: string, labelText: string, unit: string) {
        super(name);
        this.defaultLabelText = labelText;

        this.setLabelTextIcon(labelText, "left-right-arrow");
        this.element.classList.add("filter-option--slider");
        this.element.style.setProperty("--unit", `" ${unit}"`);

        this.contentBoxElement.append(this.createShowContainer(), this.createSlideContainer());
    }
}