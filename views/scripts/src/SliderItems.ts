class SliderItems {
    private buttons: HTMLButtonElement[];
    private pagesContor;
    private itemsContainer: HTMLElement;
    public count = 10;

    private static items: Item[] = [];
    private itemsCount = 0;

    public create(pagesContor: number) {
        this.pagesContor = pagesContor;
        this.itemsContainer = document.getElementById('items');
        const slider = document.getElementById('slider');
        const sliderButtons = createSimpleElement('div', 'slider-buttons');

        slider.appendChild(sliderButtons);

        for (let index = 1; index <= this.pagesContor / this.count + 1; index++) {
            sliderButtons.append(this.createButton(index.toString()));
        }
    }

    public createPage(data: ItemData[]) {
        for (const key in data) {
            if (Object.prototype.hasOwnProperty.call(data, key)) {
                const item = new Item(data[key]);
                SliderItems.items.push(item);
                this.itemsContainer.appendChild(item.create());
                this.itemsCount++;
            }
        }
    }
    private createButton(no: string) {
        const btn = createSimpleElement('button', '');
        btn.innerText = no;
        btn.onclick = () => { this.getPage(btn.innerHTML) };
        return btn;
    }

    public getPage(no: string) {
        console.log("works " + no);

        var xmlHttpRequest = new XMLHttpRequest();
        let obj: ItemData[];
        let url = `/api/items?count=${this.count}&index=${this.count * (parseInt(no) - 1)}`;
        xmlHttpRequest.open('GET', url, true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                obj = JSON.parse(xmlHttpRequest.responseText);
                delete obj['COUNT'];
                let i = 0;
                for (const key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        SliderItems.items[i].changeData(obj[key]);
                        if (i >= this.itemsCount)
                            this.itemsContainer.appendChild(SliderItems.items[i].getContainer());
                        i++;
                    }
                }
                while (i < SliderItems.items.length) {
                    SliderItems.items[i].getContainer().remove();
                    this.itemsCount--;
                    i++;
                }
            }
        }
        xmlHttpRequest.send();
    }
}