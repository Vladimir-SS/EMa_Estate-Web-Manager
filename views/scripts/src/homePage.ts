


class Home {

    public static ticking: boolean = false;

    private static parallaxImages = [...document.getElementById("parallax-container").children] as [HTMLElement];
    public static parallaxSpeed = [1.3, 0.8, 1.1, 1];


    public static scrollHandler(scrollPos: number) {
        Home.parallaxImages.forEach((image, index) => image.style.marginTop = `${Math.floor(Home.parallaxSpeed[index] * scrollPos)}px`);

        console.log(Home.parallaxImages);
    }

    public static addItems() {
        var xmlHttpRequest = new XMLHttpRequest();
        let obj: ItemData[];
        xmlHttpRequest.open('GET', '/api/items?count=10&index=0', true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                obj = JSON.parse(xmlHttpRequest.responseText);
                let items = document.getElementById('items');
                for (const key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        items.appendChild(Item.create(obj[key]));
                    }
                }
            }
        }
        xmlHttpRequest.send();
    }
}



document.addEventListener('scroll', (__e) => {


    if (!Home.ticking) {
        window.requestAnimationFrame(() => {
            Home.scrollHandler(window.scrollY);
            Home.ticking = false;
        });

        Home.ticking = true;
    }
});

DocumentHandler.whenReady(() => {
    Home.addItems();
})