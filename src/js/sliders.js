import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
// Swiper.use([Navigation, Pagination, Grid, FreeMode, EffectFade, Autoplay, Controller]);
Swiper.use(Navigation);
// Swiper.use([Navigation, Grid, Autoplay, Controller]);

/*---------------------------------------
	Init
---------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
	sliderExpertsInit();
});

/*---------------------------------------
	Experts slider
---------------------------------------*/
function sliderExpertsInit() {
	const sliderExpertsElem = document.querySelector('.js-slider-experts');
	new Swiper(sliderExpertsElem, {
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
