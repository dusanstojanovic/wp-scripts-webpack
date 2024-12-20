import Lenis from 'lenis';
import SplitType from 'split-type';

/*---------------------------------------
	Vars
---------------------------------------*/
const { body } = document;

/*---------------------------------------
	Lenis Smooth scroll (use <div data-lenis-prevent> for popups etc)
---------------------------------------*/
const lenis = new Lenis({
	duration: 1.2,
	smoothWheel: true,
	orientation: 'vertical',
	// easing: t => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t)), // https://easings.net
});
function raf(time) {
	lenis.raf(time);
	requestAnimationFrame(raf);
}
requestAnimationFrame(raf);
/**
 * Adds a click event listener to all anchor tags with an href starting with "#" to smoothly scroll to the target element.
 * The scroll offset is set to -64 pixels to account for any fixed header or navigation.
 */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
	anchor.addEventListener('click', function (e) {
		e.preventDefault();
		lenis.scrollTo(this.getAttribute('href'), { offset: -64 });
	});
});

/*---------------------------------------
    Responsive Embeds (from twentytwentyone)
---------------------------------------*/
function twentytwentyoneResponsiveEmbeds() {
	let proportion, parentWidth;
	document.querySelectorAll('iframe').forEach(function (iframe) {
		if (iframe.width && iframe.height) {
			proportion = parseFloat(iframe.width) / parseFloat(iframe.height);
			parentWidth = parseFloat(window.getComputedStyle(iframe.parentElement, null).width.replace('px', ''));
			iframe.style.maxWidth = '100%';
			iframe.style.maxHeight = Math.round(parentWidth / proportion).toString() + 'px';
		}
	});
}
twentytwentyoneResponsiveEmbeds();
window.onresize = twentytwentyoneResponsiveEmbeds;

/*---------------------------------------
	Splitting texts
---------------------------------------*/
const heroTextSplit = new SplitType('.c-txt--herotitle', { types: 'words, chars' });
const servicesTextSplit = new SplitType('.c-services-tiles__text', { types: 'lines' });
