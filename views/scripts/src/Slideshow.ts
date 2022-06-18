let current = 0;

class Slideshow {

    public static create(data: string[]) {
        const carouselContainer = document.getElementsByClassName("content__box carousel")[0];

        const slideshowContainer = createSimpleElement('div', 'slideshow-container');
        const dotsContainer = createSimpleElement('div', 'dots');

        carouselContainer.append(slideshowContainer, dotsContainer);

        const prevElement = createSimpleElement('span', 'prev icon icon-prev');
        prevElement.onclick = Slideshow.prevSlide;
        const nextElement = createSimpleElement('span', 'next icon icon-next');
        nextElement.onclick = Slideshow.nextSlide;

        slideshowContainer.append(prevElement, nextElement);

        for (let index = 0; index < data.length; index++) {
            const imageContainer = createSimpleElement('div', 'slide fade image-container image-container--animated');
            const image = createSimpleElement('div', 'image');
            // image.src = data[index];
            image.style.backgroundImage = `url("${data[index]}")`;
            imageContainer.appendChild(image);
            imageContainer.style.display = (index == 0) ? 'flex' : 'none';

            slideshowContainer.appendChild(imageContainer);

            const dotElement = createSimpleElement('span', 'dot');
            dotElement.onclick = () => { this.currentSlide(index) };
            dotsContainer.appendChild(dotElement);

        }
    }

    public static currentSlide(no: number) {
        current = no;
        Slideshow.showSlide(no);
    }

    public static prevSlide() {
        Slideshow.showSlide(current - 1);
        console.log("clicked");
    }

    public static nextSlide() {
        Slideshow.showSlide(current + 1);
        console.log("clicked");
        console.log(current);
    }

    public static showSlide(no: number) {
        const slides = document.getElementsByClassName("slide");
        const dots = document.getElementsByClassName("dot");
        console.log(slides);
        console.log(dots);
        current = no;
        if (no >= slides.length) { current = 0 }
        if (no < 0) { current = slides.length - 1; }
        for (let i = 0; i < slides.length; i++) {
            const el = slides[i] as HTMLElement;
            console.log(el);
            el.style.display = "none";
        }
        for (let i = 0; i < dots.length; i++) {
            console.log(dots[i]);
            dots[i].className = dots[i].className.replace(" active", "");
        }
        console.log(current);
        const el = slides[current] as HTMLElement;
        el.style.display = "flex";
        dots[current].className += " active";
    }
}