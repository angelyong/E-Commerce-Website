document.addEventListener("DOMContentLoaded", function () {
  let slider = document.getElementById("containerSlider");
  if (!slider) return;

  let slides = slider.querySelectorAll(".slide");
  let dotsContainer = document.getElementById("sliderDots");
  let current = 0;
  let autoplayDelay = 1500;
  let timer;
  let left = document.getElementById("left");
  let right = document.getElementById("right");

  slides.forEach(function (slide, index) {
    let dot = document.createElement("span");
    dot.className = "dot" + (index === 0 ? " active" : "");
    dot.addEventListener("click", function () {
		stopAutoplay();
		startAutoplay();
		goToSlide(index)
	  ;
    });
    dotsContainer.appendChild(dot);
  });

  let dots = dotsContainer.querySelectorAll(".dot");

  function goToSlide(index) {
    slides[current].classList.remove("active");
    dots[current].classList.remove("active");
    current = index;
    slides[current].classList.add("active");
    dots[current].classList.add("active");
  }

  function nextSlide() {
    goToSlide((current + 1) % slides.length);
  }
  
  function previousSlide() {
	let target = current - 1;
	if (target < 0) {
		target = slides.length - 1;
	}
	else {
		target = target;
	}
	goToSlide(target);
  }

  function startAutoplay() {
    timer = setInterval(nextSlide, autoplayDelay);
  }

  function stopAutoplay() {
    clearInterval(timer);
  }

  slider.addEventListener("mouseenter", stopAutoplay);
  slider.addEventListener("mouseleave", startAutoplay);
  left.addEventListener("click", previousSlide);
  right.addEventListener("click", nextSlide);
  
  

  startAutoplay();
});
