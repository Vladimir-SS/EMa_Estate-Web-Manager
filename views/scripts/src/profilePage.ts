class ProfileItemsHandler {

    public static itemsElement = document.getElementById('items');

    public static resizeHandler() {
        if (window.innerWidth <= 1220)
            ProfileItemsHandler.itemsElement.classList.add('chubby-items');
        else
            ProfileItemsHandler.itemsElement.classList.remove('chubby-items');
    }

    public static deleteHandler(announcementID: number) {
        var xmlHttpRequest = new XMLHttpRequest();
        xmlHttpRequest.open('DELETE', '/profile', true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                const obj = JSON.parse(xmlHttpRequest.responseText);
                if (obj) {
                    const item = document.getElementById('item' + announcementID);
                    item.remove();
                }
            }
        }
        xmlHttpRequest.send(`announcement_id=${announcementID}`);
    }

    public static getProfileItems() {
        var xmlHttpRequest = new XMLHttpRequest();
        let obj: ItemData[];
        xmlHttpRequest.open('GET', '/api/profile/items', true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                obj = JSON.parse(xmlHttpRequest.responseText);
                for (const key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        ProfileItemsHandler.itemsElement.appendChild(Item.createDeletable(obj[key], () => { ProfileItemsHandler.deleteHandler(obj[key].id) }));
                    }
                }
            }
        }
        xmlHttpRequest.send();
    }

}

DocumentHandler.whenReady(() => {
    ProfileItemsHandler.resizeHandler();
    ProfileItemsHandler.getProfileItems();
})

window.addEventListener('resize', ProfileItemsHandler.resizeHandler);