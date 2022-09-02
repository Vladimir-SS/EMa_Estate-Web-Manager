import "./shared/page";
import Options from "./filter/Options";
import DocumentHandler from "./shared/DocumentHandler";
import Item from "./Item/Item";

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
  fetch("/api/items?count=10&index=0")
    .then((rep) => rep.json())
    .then((obj: ItemData[]) => {
      const itemsElement = document.getElementById("items");
      if (itemsElement == null) return;
      for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
          itemsElement.appendChild(Item.create(obj[key]));
        }
      }
    });
});

DocumentHandler.whenReady(Options.createFilter);
