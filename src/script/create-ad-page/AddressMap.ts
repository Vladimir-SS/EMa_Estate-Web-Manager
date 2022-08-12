import "ol/ol.css";
import * as ol from "ol";
import * as olProj from "ol/proj";
import * as olGeom from "ol/geom";
import * as olSource from "ol/source";
import * as olLayer from "ol/layer";
import * as olStyle from "ol/style";
import CreateAd from "./CreateAd";

class AddressMap {
  public static coordinates: { lat: number; lon: number } | null = null;

  private static view = new ol.View({
    center: olProj.fromLonLat([27.568014, 47.178197]),
    zoom: 17,
  });

  private static marker = new ol.Feature({});

  private static changeCenter(lon: number, lat: number) {
    AddressMap.view.setCenter(olProj.fromLonLat([lon, lat]));
    AddressMap.marker.setGeometry(
      new olGeom.Point(olProj.fromLonLat([lon, lat]))
    );
    AddressMap.coordinates = { lat, lon };
  }

  private static searchingNow: boolean = false;
  public static lastSearch: string | null = null;

  public static searchAddress(address: string) {
    if (address === "" || AddressMap.searchingNow) return;

    AddressMap.searchingNow = true;
    AddressMap.lastSearch = address;

    const url =
      "https://nominatim.openstreetmap.org?format=json&limit=1&q=" + address;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data[0] != null) {
          const { lat, lon } = data[0];
          if (lat != null && lon != null) AddressMap.changeCenter(lon, lat);
        }
        setTimeout(() => (AddressMap.searchingNow = false), 3000);
      })
      .catch((__reason) => (AddressMap.coordinates = null));
  }

  public static map = new ol.Map({
    target: "map",
    layers: [
      new olLayer.Tile({
        source: new olSource.OSM(),
      }),
      new olLayer.Vector({
        source: new olSource.Vector({
          features: [AddressMap.marker],
        }),
        style: new olStyle.Style({
          image: new olStyle.Icon({
            anchor: [0.5, 46],
            anchorXUnits: "fraction",
            anchorYUnits: "pixels",
            src: "https://openlayers.org/en/latest/examples/data/icon.png",
          }),
        }),
      }),
    ],
    view: AddressMap.view,
  });

  public static init() {
    navigator.geolocation.getCurrentPosition((pos) => {
      AddressMap.changeCenter(pos.coords.longitude, pos.coords.latitude);
    });

    AddressMap.map.on("click", (event) => {
      const [lon, lat] = olProj.toLonLat((event as any).coordinate);
      const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;

      fetch(url)
        .then((response) => response.json())
        .then((data) => {
          const { display_name } = data;
          if (display_name == null) {
            AddressMap.coordinates = null;
            return;
          }

          AddressMap.lastSearch = display_name;
          AddressMap.marker.setGeometry(
            new olGeom.Point(olProj.fromLonLat([lon, lat]))
          );
          CreateAd.addressElement.value = display_name;
          AddressMap.coordinates = { lon, lat };
        })
        .catch((__reason) => (AddressMap.coordinates = null));
    });
  }
}

export default AddressMap;
