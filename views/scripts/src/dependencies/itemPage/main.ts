interface ComponentWeatherData {
    co: number
    no: number
    no2: number
    o3: number
    so2: number
    pm2_5: number
    pm10: number
    nh3: number
}

interface ListWeatherData {
    main: MainWeatherData
    component: ComponentWeatherData[]
}

interface MainWeatherData {
    aqi: number
}

interface CoordWeatherData {
    lat: number
    lon: number
}


interface WeatherData {
    coord: CoordWeatherData
    list: ListWeatherData[]
}

class ItemPage {
    public static getProfileData(profileElement: Element, accountID: number) {
        let profile: ProfileData;
        var xmlHttpRequest = new XMLHttpRequest();

        xmlHttpRequest.open('GET', `/api/profile?id=${accountID}`, true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                profile = JSON.parse(xmlHttpRequest.responseText);
                profileElement.appendChild(ProfileContainer.create(profile));
            }
        }
        xmlHttpRequest.send();
    }

    public static getWeatherData(itemInfo: HTMLDivElement, lon: number, lat: number) {
        var xmlHttpRequest = new XMLHttpRequest();
        xmlHttpRequest.open('GET', `/api/weather?lon=${lon}&lat=${lat}`, true);
        xmlHttpRequest.onreadystatechange = () => {
            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                const weatherData: WeatherData = JSON.parse(xmlHttpRequest.responseText);
                itemInfo.appendChild(ItemInfo.addAditionalInfo(weatherData));
            }
        }
        xmlHttpRequest.send();
    }
}

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
            const itemInfo = ItemInfo.create(item);
            profileElement.appendChild(itemInfo);
            const descriptionElement = createSimpleElement('p', '');
            descriptionElement.textContent = item.description;
            decriptionContainer.appendChild(descriptionElement);
            Slideshow.create(item.imagesURLs);
            ItemPage.getProfileData(profileElement, item.accountID);
            ItemPage.getWeatherData(itemInfo, item.lon, item.lat);
            ProximityMapHandler.setTo(item.lon, item.lat);
        }
    }
    xmlHttpRequest.send();

})

