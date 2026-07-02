/**
 * Keep the two Solace logo controls in sync inside the Customizer panel.
 *
 * Changing the header logo (logo_logo) or the footer logo (logo-footer_logo)
 * mirrors the value into the other control, so both controls (and both saved
 * theme mods) always represent the same site logo.
 *
 * The logo controls are React components that read their displayed image from
 * internal state initialised once on mount; they do not re-render when their
 * setting is changed from the outside. So, on top of mirroring the setting
 * value, we also refresh the other control's <img> directly so the new logo
 * shows immediately without reloading the Customizer.
 *
 * @package Solace_Extra
 */
( function( api ) {
	'use strict';

	if ( ! api ) {
		return;
	}

	var LOGO_SETTINGS = [ 'logo_logo', 'logo-footer_logo' ];
	var syncing = false;

	/**
	 * Normalise a logo setting value (stored as a JSON string) into an object.
	 *
	 * @param {string|Object} value Raw setting value.
	 * @return {Object} Parsed value, or an empty object on failure.
	 */
	function parseValue( value ) {
		if ( ! value ) {
			return {};
		}
		if ( 'object' === typeof value ) {
			return value;
		}
		try {
			return JSON.parse( value ) || {};
		} catch ( e ) {
			return {};
		}
	}

	/**
	 * Paint (or clear) the light-logo image inside a control's UI.
	 *
	 * @param {string} key Setting/control id (e.g. logo-footer_logo).
	 * @param {string} url Image URL, or an empty string to clear it.
	 * @return {void}
	 */
	function renderPreview( key, url ) {
		var li = document.getElementById( 'customize-control-' + key );
		if ( ! li ) {
			return;
		}

		var select = li.querySelector( '.logo-select:not(.dark)' );
		var wrap = li.querySelector( '.logo-wrap' );
		if ( ! select ) {
			return;
		}

		var img = select.querySelector( 'img' );

		if ( url ) {
			if ( ! img ) {
				img = document.createElement( 'img' );
				img.setAttribute( 'aria-hidden', 'true' );
				img.setAttribute( 'alt', '' );
				select.appendChild( img );
			}
			img.setAttribute( 'src', url );
			select.classList.add( 'active' );
			if ( wrap ) {
				wrap.classList.add( 'active' );
			}
		} else if ( img ) {
			img.parentNode.removeChild( img );
			select.classList.remove( 'active' );
			if ( wrap ) {
				wrap.classList.remove( 'active' );
			}
		}
	}

	/**
	 * Resolve the attachment URL for a logo value and refresh the control's UI.
	 *
	 * @param {string} key   Setting/control id to refresh.
	 * @param {string|Object} value Logo setting value.
	 * @return {void}
	 */
	function previewFromValue( key, value ) {
		var parsed = parseValue( value );
		var id = parsed.light ? parseInt( parsed.light, 10 ) : 0;

		if ( ! id ) {
			renderPreview( key, '' );
			return;
		}

		if ( ! window.wp || ! window.wp.media ) {
			return;
		}

		var attachment = window.wp.media.attachment( id );
		var url = attachment.get( 'url' );

		if ( url ) {
			renderPreview( key, url );
		} else {
			attachment.fetch().then( function() {
				renderPreview( key, attachment.get( 'url' ) );
			} );
		}
	}

	function mirror( fromKey, value ) {
		if ( syncing ) {
			return;
		}

		syncing = true;

		LOGO_SETTINGS.forEach( function( otherKey ) {
			if ( otherKey === fromKey ) {
				return;
			}

			api( otherKey, function( otherSetting ) {
				if ( otherSetting.get() !== value ) {
					otherSetting.set( value );
				}

				// The other control's React component will not re-render on an
				// external setting change, so reflect the new logo in its UI.
				previewFromValue( otherKey, value );
			} );
		} );

		syncing = false;
	}

	api.bind( 'ready', function() {
		LOGO_SETTINGS.forEach( function( key ) {
			api( key, function( setting ) {
				setting.bind( function( value ) {
					mirror( key, value );
				} );
			} );
		} );
	} );
} )( window.wp && window.wp.customize );
