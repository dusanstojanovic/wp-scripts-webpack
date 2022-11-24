import Lenis from '@studio-freight/lenis';
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
	easing: t => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t)), // https://easings.net
	smooth: true,
	direction: 'vertical',
});
function raf(time) {
	lenis.raf(time);
	requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

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
