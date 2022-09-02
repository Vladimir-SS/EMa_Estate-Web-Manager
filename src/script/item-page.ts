import "./shared/page";
import DocumentHandler from "./shared/DocumentHandler";
import ProfileContainer from "./item-page/ProfileContainer";
import { createSimpleElement } from "./shared/functions";
import ItemInfo from "./Item/ItemInfo";
import Slideshow from "./item-page/Slideshow";
import ProximityMapHandler from "./item-page/ProximityMapHandler";

interface ComponentWeatherData {
  co: number;
  no: number;
  no2: number;
  o3: number;
  so2: number;
  pm2_5: number;
  pm10: number;
  nh3: number;
}

interface ListWeatherData {
  main: MainWeatherData;
  component: ComponentWeatherData[];
}

interface MainWeatherData {
  aqi: number;
}

interface CoordWeatherData {
  lat: number;
  lon: number;
}

declare global {
  interface WeatherData {
    coord: CoordWeatherData;
    list: ListWeatherData[];
  }
}

class ItemPage {
  public static getProfileData(profileElement: Element, accountID: number) {
    fetch(`/api/profile?id=${accountID}`)
      .then((rep) => rep.json())
      .then((profile: ProfileData) =>
        profileElement.appendChild(ProfileContainer.create(profile))
      );

    //;
  }

  public static getWeatherData(
    itemInfo: HTMLDivElement,
    lon: number,
    lat: number
  ) {
    var xmlHttpRequest = new XMLHttpRequest();
    xmlHttpRequest.open("GET", `/api/weather?lon=${lon}&lat=${lat}`, true);
    xmlHttpRequest.onreadystatechange = () => {
      if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
        const weatherData: WeatherData = JSON.parse(
          xmlHttpRequest.responseText
        );
        itemInfo.appendChild(ItemInfo.addAditionalInfo(weatherData));
      }
    };
    xmlHttpRequest.send();
  }
}

DocumentHandler.whenReady(() => {
  const profileElement = document.getElementsByClassName("content__right")[0];
  const decriptionContainer = document.getElementsByClassName(
    "content__box description"
  )[0];

  const searchParams = new URLSearchParams(window.location.search);

  fetch(`/api/item?id=${searchParams.get("id")}`)
    .then((rep) => rep.json())
    .then((item: ItemData) => {
      const itemInfo = ItemInfo.create(item);
      profileElement.appendChild(itemInfo);
      const descriptionElement = createSimpleElement("p", "pre");
      descriptionElement.innerHTML = item.description;
      decriptionContainer.appendChild(descriptionElement);
      Slideshow.create(item.imagesURLs);
      ItemPage.getProfileData(profileElement, item.accountID);
      ItemPage.getWeatherData(itemInfo, item.lon, item.lat);
      ProximityMapHandler.setTo(item.lon, item.lat);
    });
});
