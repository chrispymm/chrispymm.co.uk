const imgs = document.querySelectorAll('img[loading="lazy"]');

const imageObserverCallback = (images, elObserver) => {
  images.forEach((image) => {
    if (image.isIntersecting) {
      image.target.classList.add("visible");
      // Stop watching element when in viewport
      elObserver.unobserve(image.target);
    }
  });
};

const imageObserverOptions = {
  // Fade in when 25% of element is in view
  threshold: 0.25,
};

const imageObserver = new IntersectionObserver(
  imageObserverCallback,
  imageObserverOptions,
);

imgs.forEach((img) => {
  img.addEventListener("load", () => {
    img.classList.add("loaded");
  });
  imageObserver.observe(img);
});
