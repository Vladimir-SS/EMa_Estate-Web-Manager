DocumentHandler.whenReady(() => {
    var xmlHttpRequest = new XMLHttpRequest();
    let obj: ItemData;
    xmlHttpRequest.open('GET', '/api/items?count=10&index=0', true);
    xmlHttpRequest.onreadystatechange = () => {
        if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
            obj = JSON.parse(xmlHttpRequest.responseText);
            let items = document.getElementById('items');
            for (const key in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, key)) {
                    items.appendChild(new Item(obj[key]).getContainer());
                }
            }
        }
    }
    xmlHttpRequest.send();
})