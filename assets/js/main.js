document.addEventListener("DOMContentLoaded", function () {
  const slider = document.getElementById("containerSlider");
  if (!slider) return;

  const slides = Array.from(slider.querySelectorAll(".hero-slide"));
  const dotsContainer = document.getElementById("sliderDots");
  const previous = slider.querySelector(".previous");
  const next = slider.querySelector(".next");
  let current = 0;
  let timer;

  slides.forEach(function (_, index) {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.className = "dot" + (index === 0 ? " active" : "");
    dot.setAttribute("aria-label", "Go to slide " + (index + 1));
    dot.addEventListener("click", function () { show(index); restart(); });
    dotsContainer.appendChild(dot);
  });

  const dots = Array.from(dotsContainer.querySelectorAll(".dot"));

  function show(index) {
    slides[current].classList.remove("active");
    dots[current].classList.remove("active");
    current = (index + slides.length) % slides.length;
    slides[current].classList.add("active");
    dots[current].classList.add("active");
  }
  function start() { timer = window.setInterval(function () { show(current + 1); }, 6000); }
  function stop() { window.clearInterval(timer); }
  function restart() { stop(); start(); }

  previous.addEventListener("click", function () { show(current - 1); restart(); });
  next.addEventListener("click", function () { show(current + 1); restart(); });
  slider.addEventListener("mouseenter", stop);
  slider.addEventListener("mouseleave", start);
  slider.addEventListener("focusin", stop);
  slider.addEventListener("focusout", start);
  slider.addEventListener("keydown", function (event) {
    if (event.key === "ArrowLeft") {
      show(current - 1);
      restart();
    }
    if (event.key === "ArrowRight") {
      show(current + 1);
      restart();
    }
  });
  document.addEventListener("visibilitychange", function () {
    if (document.hidden) stop(); else restart();
  });
  start();
});
