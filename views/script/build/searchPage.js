const itemsElement = document.getElementById('items');
const resizeHandler = () => {
    if (window.innerWidth <= 1220)
        itemsElement.classList.add('chubby-items');
    else
        itemsElement.classList.remove('chubby-items');
};
DocumentHandler.whenReady(() => {
    resizeHandler();
    var xmlHttpRequest = new XMLHttpRequest();
    let obj;
    const slider = new SliderItems();
    let url = `/api/items?count=${slider.count}&index=0`;
    xmlHttpRequest.open('GET', url, true);
    xmlHttpRequest.onreadystatechange = () => {
        if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
            obj = JSON.parse(xmlHttpRequest.responseText);
            slider.create(obj['COUNT']);
            delete obj['COUNT'];
            slider.createPage(obj);
        }
    };
    xmlHttpRequest.send();
});
window.addEventListener('resize', resizeHandler);
