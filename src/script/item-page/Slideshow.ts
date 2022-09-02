import { createSimpleElement } from "../shared/functions";

let current = 0;

class Slideshow {
  public static create(data: string[]) {
    const carouselContainer = document.getElementsByClassName(
      "content__box carousel"
    )[0];

    const slideshowContainer = createSimpleElement(
      "div",
      "slideshow-container"
    );
    const dotsContainer = createSimpleElement("div", "dots");

    carouselContainer.append(slideshowContainer, dotsContainer);

    const prevElement = createSimpleElement("span", "prev icon icon-prev");
    prevElement.onclick = Slideshow.prevSlide;
    const nextElement = createSimpleElement("span", "next icon icon-next");
    nextElement.onclick = Slideshow.nextSlide;

    slideshowContainer.append(prevElement, nextElement);

    for (let index = 0; index < data.length; index++) {
      const imageContainer = createSimpleElement(
        "div",
        "slide fade image-container"
      );
      const image = createSimpleElement("div", "image");
      image.style.backgroundImage = `url("${data[index]}")`;
      imageContainer.appendChild(image);
      imageContainer.style.display = index == 0 ? "flex" : "none";

      slideshowContainer.appendChild(imageContainer);

      const dotElement = createSimpleElement("span", "dot");
      dotElement.onclick = () => {
        this.currentSlide(index);
      };
      dotsContainer.appendChild(dotElement);
    }
  }

  public static currentSlide(no: number) {
    current = no;
    Slideshow.showSlide(no);
  }

  public static prevSlide() {
    Slideshow.showSlide(current - 1);
  }

  public static nextSlide() {
    Slideshow.showSlide(current + 1);
  }

  public static showSlide(no: number) {
    const slides = document.getElementsByClassName("slide");
    const dots = document.getElementsByClassName("dot");
    current = no;
    if (no >= slides.length) {
      current = 0;
    }
    if (no < 0) {
      current = slides.length - 1;
    }
    for (let i = 0; i < slides.length; i++) {
      const el = slides[i] as HTMLElement;
      el.style.display = "none";
    }
    for (let i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    const el = slides[current] as HTMLElement;
    el.style.display = "flex";
    dots[current].className += " active";
  }
}

export default Slideshow;
