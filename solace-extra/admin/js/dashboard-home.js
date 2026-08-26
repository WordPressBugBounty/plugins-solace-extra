( function () {
	'use strict';

	function initHeroSlider( slider ) {
		var track = slider.querySelector( '.solace-home__hero-track' );
		var slides = slider.querySelectorAll( '.solace-home__hero-slide' );
		var dots = slider.querySelectorAll( '.solace-home__hero-dots button' );
		var prevButton = slider.querySelector( '.solace-home__hero-arrow--prev' );
		var nextButton = slider.querySelector( '.solace-home__hero-arrow--next' );
		var autoplayDelay = parseInt( slider.getAttribute( 'data-autoplay' ), 10 ) || 6000;
		var currentIndex = 0;
		var timer = null;
		var autoplayLocked = false;

		if ( ! track || slides.length < 2 ) {
			return;
		}

		function setTrackHeight() {
			var activeSlide = slides[ currentIndex ];

			if ( ! activeSlide ) {
				return;
			}

			track.style.minHeight = activeSlide.offsetHeight + 'px';
		}

		function goToSlide( index ) {
			currentIndex = ( index + slides.length ) % slides.length;

			slides.forEach( function ( slide, slideIndex ) {
				var isActive = slideIndex === currentIndex;

				slide.classList.toggle( 'is-active', isActive );
				slide.setAttribute( 'aria-hidden', isActive ? 'false' : 'true' );
			} );

			dots.forEach( function ( dot, dotIndex ) {
				var isActive = dotIndex === currentIndex;

				dot.classList.toggle( 'is-active', isActive );
				dot.setAttribute( 'aria-current', isActive ? 'true' : 'false' );
			} );

			setTrackHeight();
		}

		function nextSlide() {
			goToSlide( currentIndex + 1 );
		}

		function prevSlide() {
			goToSlide( currentIndex - 1 );
		}

		function startAutoplay() {
			if ( autoplayLocked ) {
				return;
			}

			stopAutoplay();
			timer = window.setInterval( nextSlide, autoplayDelay );
		}

		function stopAutoplay() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				var targetIndex = parseInt( dot.getAttribute( 'data-slide' ), 10 );

				if ( Number.isNaN( targetIndex ) ) {
					return;
				}

				goToSlide( targetIndex );
				startAutoplay();
			} );
		} );

		if ( prevButton ) {
			prevButton.addEventListener( 'click', function () {
				prevSlide();
				startAutoplay();
			} );
		}

		if ( nextButton ) {
			nextButton.addEventListener( 'click', function () {
				nextSlide();
				startAutoplay();
			} );
		}

		slider.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				event.preventDefault();
				prevSlide();
				startAutoplay();
			}

			if ( 'ArrowRight' === event.key ) {
				event.preventDefault();
				nextSlide();
				startAutoplay();
			}
		} );

		slider.addEventListener( 'mouseenter', stopAutoplay );
		slider.addEventListener( 'mouseleave', startAutoplay );
		slider.addEventListener( 'focusin', stopAutoplay );
		slider.addEventListener( 'focusout', startAutoplay );
		slider.addEventListener( 'solace-pagespeed-testing', function ( event ) {
			autoplayLocked = !!( event.detail && event.detail.testing );

			if ( autoplayLocked ) {
				stopAutoplay();
				return;
			}

			startAutoplay();
		} );

		window.addEventListener( 'resize', setTrackHeight );

		goToSlide( 0 );
		startAutoplay();
	}

	function initPageSpeedTest( card ) {
		var button = card.querySelector( '[data-pagespeed-test]' );
		var value = card.querySelector( '[data-pagespeed-value]' );
		var status = card.querySelector( '[data-pagespeed-status]' );

		if ( ! button || ! value || ! status || 'undefined' === typeof window.solaceDashboard ) {
			return;
		}

		button.addEventListener( 'click', function () {
			var originalLabel = button.textContent;
			var requestData = new window.URLSearchParams();
			var slider = card.closest( '.solace-home__hero-slider' );

			requestData.set( 'action', 'solace_run_pagespeed_test' );
			requestData.set( 'nonce', window.solaceDashboard.nonce );

			button.disabled = true;
			button.setAttribute( 'aria-busy', 'true' );
			button.textContent = window.solaceDashboard.strings.testing;
			card.classList.add( 'is-testing' );
			status.classList.remove( 'is-error' );
			status.textContent = window.solaceDashboard.strings.testing;

			if ( slider ) {
				slider.dispatchEvent( new CustomEvent( 'solace-pagespeed-testing', {
					detail: { testing: true },
				} ) );
			}

			window.fetch( window.solaceDashboard.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: requestData.toString(),
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( response ) {
					if ( ! response.success ) {
						throw new Error(
							response.data && response.data.message
								? response.data.message
								: window.solaceDashboard.strings.requestFailed
						);
					}

					value.classList.remove( 'is-good', 'is-warning', 'is-critical' );
					value.classList.add( response.data.scoreClass );
					value.textContent = response.data.score + ' / 100';
					status.textContent = response.data.statusText;
					button.textContent = window.solaceDashboard.strings.retest;
					window.dispatchEvent( new Event( 'resize' ) );
				} )
				.catch( function ( error ) {
					status.classList.add( 'is-error' );
					status.textContent = error.message || window.solaceDashboard.strings.requestFailed;
					button.textContent = originalLabel;
					window.dispatchEvent( new Event( 'resize' ) );
				} )
				.finally( function () {
					button.disabled = false;
					button.removeAttribute( 'aria-busy' );
					card.classList.remove( 'is-testing' );

					if ( slider ) {
						slider.dispatchEvent( new CustomEvent( 'solace-pagespeed-testing', {
							detail: { testing: false },
						} ) );
					}
				} );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.solace-home__hero-slider' ).forEach( initHeroSlider );
		document.querySelectorAll( '[data-pagespeed-card]' ).forEach( initPageSpeedTest );
	} );
}() );
