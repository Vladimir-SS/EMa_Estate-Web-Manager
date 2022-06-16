const itemsElement = document.getElementById('items');

const resizeHandler = () => {
    if (window.innerWidth <= 1220)
        itemsElement.classList.add('chubby-items');
    else
        itemsElement.classList.remove('chubby-items');

}

DocumentHandler.whenReady(() => {
    resizeHandler();
    var xmlHttpRequest = new XMLHttpRequest();
    let obj: ItemData[];
    xmlHttpRequest.open('GET', '/api/items', true);
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
})

window.addEventListener('resize', resizeHandler);