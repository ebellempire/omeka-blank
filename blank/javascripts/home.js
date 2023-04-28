// SIMPLE SLIDESHOW
function nextSlide(slides, dots) {
  let active = document.querySelector("[data-active='1']");
  let next = active.nextSibling;
  let active_dot = document.querySelector("[data-dot-active='1']");
  let next_dot = active_dot.nextSibling;
  if (next) {
    next.setAttribute("data-active", 1);
    active.setAttribute("data-active", 0);

    next_dot.setAttribute("data-dot-active", 1);
    active_dot.setAttribute("data-dot-active", 0);
  } else {
    slides[0].setAttribute("data-active", 1);
    active.setAttribute("data-active", 0);

    dots[0].setAttribute("data-dot-active", 1);
    active_dot.setAttribute("data-dot-active", 0);
  }
  return null;
}

function toSlide(id, slides, dots) {
  slides.forEach((slide) => {
    if (slide.getAttribute("data-slide-id") == id) {
      slide.setAttribute("data-active", 1);
    } else {
      slide.setAttribute("data-active", 0);
    }
  });
  dots.forEach((dot) => {
    if (dot.getAttribute("data-dot-id") == id) {
      dot.setAttribute("data-dot-active", 1);
    } else {
      dot.setAttribute("data-dot-active", 0);
    }
  });
  return null;
}

document.addEventListener("DOMContentLoaded", (event) => {
  const gallery = document.querySelector("#homepage-gallery");
  if(gallery){
    const autoplay = gallery.getAttribute("data-autoplay");
    const timing = gallery.getAttribute("data-timing");
    const slides = document.querySelectorAll(
      "#homepage-gallery .homepage-gallery-image-container"
    );
    const dots = document.querySelectorAll("#slide-dots .dot");
    var interval;
    
    if (slides) {
      // autoplay
      if (autoplay) {
        interval = setInterval(() => {
          nextSlide(slides, dots);
        }, Math.max(3000, timing)); // ignore < 3 sec
      }
      // dots
      dots.forEach((dot) => {
        dot.addEventListener("click", (e) => {
          e.preventDefault();
          if (autoplay) clearInterval(interval);
          let id = e.target.getAttribute("data-dot-id");
          if (id) {
            toSlide(id, slides, dots);
          }
        });
      });
    }
  }
});
