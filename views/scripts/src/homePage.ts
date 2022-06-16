// PLACEHOLDER
const landItemExample: LandData = {
    type: 3,
    id: 0,
    address: "",
    title: "",
    transactionType: "",
    description: "",
    price: "",
    surface: 0,
    imageURL: ""
}

const apartmentItemExample: ApartmentData = {
    type: 0,
    transactionType: "",
    apartmentType: 0,
    rooms: 0,
    floor: 0,
    bathrooms: 0,
    parkingLots: 0,
    builtIn: 0,
    id: 0,
    address: "",
    title: "",
    description: "",
    price: "",
    surface: 0,
    imageURL: ""
}


// ACTUAL DOCUMENT
DocumentHandler.whenReady(() => {

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
})