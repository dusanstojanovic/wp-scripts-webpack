const { body } = document;

/*---------------------------------------
    Sticky header body class
---------------------------------------*/
function stickyHeaderInit() {
	window.addEventListener(
		'scroll',
		function () {
			if (this.scrollY > 128) {
				body.classList.add('is-headerstuck');
			} else {
				body.classList.remove('is-headerstuck');
			}
		},
		false,
	);
}

/*---------------------------------------
    Sticky header GSAP
---------------------------------------*/
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

function stickyHeaderInit() {
	const showAnim = gsap
		.from('.c-header', {
			yPercent: -100,
			paused: true,
			duration: 0.5,
			ease: 'power1.inOut',
		})
		.progress(1);
	ScrollTrigger.create({
		start: '72px top',
		end: 99999,
		onUpdate: self => {
			self.direction === -1 ? showAnim.play() : showAnim.reverse();
		},
	});
}
stickyHeaderInit();

/*---------------------------------------
    GSAP menu
---------------------------------------*/
function menuInit() {
	const toggleMenu = document.querySelector('.home .js-open-menu');
	if (toggleMenu) {
		const tl = gsap.timeline({ defaults: { ease: 'power1.out', duration: 0.2 } });
		tl.set('.home .c-nav-main > li', { opacity: 0, y: -15 });
		let isOpen = false;

		function animateIn() {
			const tl = gsap.timeline({ defaults: { ease: 'power1.out', duration: 0.2 } });
			tl.to('.home .c-nav-main > li', {
				opacity: 1,
				y: 0,
				stagger: 0.04,
			});
			body.classList.add('is-menu-open');
			toggleMenu.classList.add('is-active');
			isOpen = true;
		}
		function animateOut() {
			const tl = gsap.timeline({ defaults: { ease: 'power1.out', duration: 0.2 } });
			tl.to('.home .c-nav-main > li', {
				opacity: 0,
				y: -15,
				stagger: 0.04,
			});
			body.classList.remove('is-menu-open');
			toggleMenu.classList.remove('is-active');
			isOpen = false;
		}
		toggleMenu.addEventListener('click', () => {
			if (!isOpen) {
				animateIn();
			} else {
				animateOut();
			}
		});

		const navMain = document.querySelector('.home .c-header__nav');
		document.addEventListener('mouseup', function (e) {
			if (!navMain.contains(e.target)) {
				animateOut();
			}
		});
	}
}
menuInit();

/*---------------------------------------
    toggle menu
---------------------------------------*/
function toggleMenuInit() {
	const toggleMenu = document.querySelector('.js-toggle-menu');
	if (toggleMenu) {
		toggleMenu.addEventListener('click', function () {
			body.classList.toggle('is-menuopen');
			toggleMenu.classList.toggle('is-active');
		});
		window.addEventListener('resize', closeMenu);
		function closeMenu() {
			const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
			if (viewportWidth > 1024) {
				body.classList.remove('is-menuopen');
				toggleMenu.classList.remove('is-active');
			}
		}
	}
}

/*---------------------------------------
    Accordion
---------------------------------------*/
function accordion() {
	if (document.querySelector('.c-accordion__title')) {
		const accordionBtns = document.querySelectorAll('.c-accordion__title');
		// const first = document.getElementsByClassName('c-accordion__title')[0];
		// const content = first.nextElementSibling;
		// setTimeout(() => {
		//     first.classList.add('is-open');
		//     content.style.maxHeight = content.scrollHeight + 'px';
		// }, '400');
		accordionBtns.forEach(accordion => {
			accordion.onclick = function () {
				this.classList.toggle('is-open');
				// For ony one open at the time
				//
				// let otherContents = document.querySelectorAll('.c-accordion__content');
				// otherContents.forEach(otherContent => {
				//     otherContent.style.maxHeight = null;
				//     otherContent.previousElementSibling.classList.remove('is-open');
				// });
				//
				let content = this.nextElementSibling;
				if (content.style.maxHeight) {
					content.style.maxHeight = null;
					content.previousElementSibling.classList.remove('is-open');
				} else {
					content.style.maxHeight = content.scrollHeight + 'px';
					content.previousElementSibling.classList.add('is-open');
				}
			};
		});
	}
}
accordion();
