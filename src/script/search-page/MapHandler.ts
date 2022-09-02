import * as ol from "ol";
import * as olProj from "ol/proj";
import * as olSource from "ol/source";
import * as olLayer from "ol/layer";
import { createSimpleElement, parseMoney } from "../shared/functions";
import SearchHandler from "./SearchHandler";

interface PinData {
  id: number;
  imageURL: string;
  lat: number;
  lon: number;
  price: number;
  type: string;
}

class MapHandler {
  private static overlayList: ol.Overlay[] = [];

  private static view = new ol.View({
    center: olProj.fromLonLat([27.568014, 47.178197]),
    zoom: 17,
    maxZoom: 18,
    minZoom: 16,
  });

  /*
    <div class="pin-container">
                            <div class="pin">
                                123 000
                            </div>
                        </div>
    */

  private static createPinElement(data: PinData) {
    const pinElement = createSimpleElement("div", "pin");
    pinElement.textContent = parseMoney(data.price) + SearchHandler.curency;

    const title = createSimpleElement("p", "title");
    const extraiInfoElement = createSimpleElement("div", "extraInfo");

    const containerElement = createSimpleElement("div", "pin-container");
    containerElement.appendChild(pinElement);

    return containerElement;
  }

  private static createOverlay = (data: PinData) =>
    new ol.Overlay({
      position: olProj.fromLonLat([data.lon, data.lat]),
      positioning: "bottom-center",
      element: MapHandler.createPinElement(data),
      stopEvent: false,
    });

  private static map = new ol.Map({
    target: "map",
    layers: [
      new olLayer.Tile({
        source: new olSource.OSM(),
      }),
    ],
    view: MapHandler.view,
  });

  public static setTo(lon: number, lat: number) {
    fetch(`/api/items/near?lon=${lon}&lat=${lat}`)
      .then((response) => response.json())
      .then((data: PinData[]) => {
        MapHandler.view.setCenter(olProj.fromLonLat([lon, lat]));
        MapHandler.overlayList.forEach((ov) =>
          MapHandler.map.removeOverlay(ov)
        );
        MapHandler.overlayList = data.map((p) => MapHandler.createOverlay(p));
        MapHandler.overlayList.forEach((ov) => MapHandler.map.addOverlay(ov));
      });
  }
}

export default MapHandler;
