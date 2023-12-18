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
