import Swiper from 'swiper';
// import SwiperCore, { Navigation, Pagination, EffectFade, Autoplay, Controller } from 'swiper/core';
// SwiperCore.use([Navigation, Pagination, EffectFade, Autoplay, Controller]);
import SwiperCore, { Navigation } from 'swiper/core';
SwiperCore.use(Navigation);

/*---------------------------------------
	Experts slider
---------------------------------------*/
function sliderExpertsInit() {
	const sliderExpertsElem = document.querySelector('.js-slider-experts');
	const sliderExperts = new Swiper(sliderExpertsElem, {
		// slidesPerView: 'auto',
		slidesPerView: 'auto',
		spaceBetween: 28,
		lazy: false,
		navigation: {
			prevEl: '.c-slider-experts__prev',
			nextEl: '.c-slider-experts__next',
		},
	});
}
sliderExpertsInit();
