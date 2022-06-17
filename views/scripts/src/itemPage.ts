DocumentHandler.whenReady(() => {

    const profileElement = document.getElementsByClassName("content__right")[0];

    var xmlHttpRequest = new XMLHttpRequest();

    let item: ItemData;
    const searchParams = new URLSearchParams(window.location.search);

    xmlHttpRequest.open('GET', `/api/item?id=${searchParams.get('id')}`, true);
    xmlHttpRequest.onreadystatechange = () => {
        if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
            item = JSON.parse(xmlHttpRequest.responseText);
            console.log(item);
            profileElement.appendChild(ItemInfo.create(item));

            let profile: ProfileData;

            xmlHttpRequest.open('GET', `/api/profile?id=${item.accountID}`, true);
            xmlHttpRequest.onreadystatechange = () => {
                if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                    profile = JSON.parse(xmlHttpRequest.responseText);
                    console.log(profile);
                    profileElement.appendChild(ProfileContainer.create(profile));
                }
            }
            xmlHttpRequest.send();
        }
    }
    xmlHttpRequest.send();

})