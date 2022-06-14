DocumentHandler.whenReady(() => {
    var xmlHttpRequest = new XMLHttpRequest();
    let obj: ItemData;
    xmlHttpRequest.open('GET', '/api/items', true);
    xmlHttpRequest.onreadystatechange = () => {
        if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
            //console.log(xmlHttpRequest.responseText);
            obj = JSON.parse(xmlHttpRequest.responseText);
            console.log(obj);
            let items = document.getElementById('items');
            for (const key in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, key)) {
                    items.appendChild(Item.create(obj[key]));
                }
            }
        }
    }

    xmlHttpRequest.onload = () => {
        if (xmlHttpRequest.status == 200) {
            console.log('?3?');
        }
    }
    xmlHttpRequest.send();
})