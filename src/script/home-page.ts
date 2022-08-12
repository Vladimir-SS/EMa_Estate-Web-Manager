import Options from "./filter/Options";
import Item from "./Item";
import DocumentHandler from "./shared/DocumentHandler";

const ParallaxController = {
  ticking: false,
  images: document.getElementById("parallax-container")?.children,
  parallaxSpeed: [1.3, 0.8, 1.1, 1],

  scrollHandler: (scrollPos: number) => {
    const { images } = ParallaxController;

    if (images == undefined) return;

    for (let index = 0; index < images.length; ++index) {
      (images[index] as HTMLImageElement).style.marginTop = `${Math.floor(
        ParallaxController.parallaxSpeed[index] * scrollPos
      )}px`;
    }
  },
};

document.addEventListener("scroll", (__e) => {
  if (!ParallaxController.ticking) {
    window.requestAnimationFrame(() => {
      ParallaxController.scrollHandler(window.scrollY);
      ParallaxController.ticking = false;
    });

    ParallaxController.ticking = true;
  }
});

DocumentHandler.whenReady(() => {
  //TODO: do it with fetch :)
  // var xmlHttpRequest = new XMLHttpRequest();
  // let obj: ItemData[];
  // xmlHttpRequest.open("GET", "/api/items?count=10&index=0", true);
  // xmlHttpRequest.onreadystatechange = () => {
  //   if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
  //     obj = JSON.parse(xmlHttpRequest.responseText);
  //     let items = document.getElementById("items");
  //     if (items == null) return;
  //     for (const key in obj) {
  //       if (Object.prototype.hasOwnProperty.call(obj, key)) {
  //         items.appendChild(Item.create(obj[key]));
  //       }
  //     }
  //   }
  // };
  // xmlHttpRequest.send();
});

DocumentHandler.whenReady(Options.createFilter);
