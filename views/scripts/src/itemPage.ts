DocumentHandler.whenReady(() => {

    const profileElement = document.getElementsByClassName("content__right")[0];
    const decriptionContainer = document.getElementsByClassName("content__box description")[0];

    var xmlHttpRequest = new XMLHttpRequest();

    let item: ItemData;
    const searchParams = new URLSearchParams(window.location.search);

    xmlHttpRequest.open('GET', `/api/item?id=${searchParams.get('id')}`, true);
    xmlHttpRequest.onreadystatechange = () => {
        if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
            item = JSON.parse(xmlHttpRequest.responseText);
            profileElement.appendChild(ItemInfo.create(item));
            const descriptionElement = createSimpleElement('p', '');
            descriptionElement.textContent = item.description;
            decriptionContainer.appendChild(descriptionElement);
            Slideshow.create(item.imagesURLs);

            let profile: ProfileData;

            xmlHttpRequest.open('GET', `/api/profile?id=${item.accountID}`, true);
            xmlHttpRequest.onreadystatechange = () => {
                if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                    profile = JSON.parse(xmlHttpRequest.responseText);
                    profileElement.appendChild(ProfileContainer.create(profile));
                }
            }
            xmlHttpRequest.send();
        }
    }
    xmlHttpRequest.send();

})