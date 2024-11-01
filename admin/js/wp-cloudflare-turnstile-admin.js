jQuery( function( $ ) {
	$( '.idomit-buy-now' ).on( 'click', function( e ) {
		var $button = $( this ),
			plugin_id = $button.data( 'plugin-id' ),
			plan_id = $button.data( 'plan-id' ),
			public_key = $button.data( 'public-key' ),
			type = $button.data( 'type' ),
			coupon = $button.data( 'coupon' ),
			licenses = $button.data( 'licenses' ),
			title = $button.data( 'title' ),
			subtitle = $button.data( 'title' );

		var handler = FS.Checkout.configure( {
			plugin_id: plugin_id,
			plan_id: plan_id,
			public_key: public_key,
		} );

		handler.open( {
			title: title,
			subtitle: subtitle,
			licenses: licenses,
			coupon: coupon,
			hide_coupon: true,
			// You can consume the response for after purchase logic.
			purchaseCompleted: function( response ) {
				// The logic here will be executed immediately after the purchase confirmation.                                // alert(response.user.email);
			},
			success: function( response ) {
				// The logic here will be executed after the customer closes the checkout, after a successful purchase.                                // alert(response.user.email);
			}
		} );

		e.preventDefault();
	} );
	
});
(function( $, document ) {
	
	var wtcc = {
		
		wtcccache: function() {
			wtcc.els = {};
			wtcc.vars = {};

			wtcc.els.tab_links = $('.wtcc-nav__item-link');
			wtcc.els.submit_button = $( '.wtcc-button-submit' );
		},

		on_ready: function() {

			// on ready stuff here
			wtcc.wtcccache();
			wtcc.tabs.watch();
			// $( document.body ).on( 'change', wtcc.control_groups );
		},

		/**
		 * Setup the main tabs for the settings page
		 */
		tabs: {
			/**
			 * Watch for tab clicks.
			 */
			watch: function() {
				var tab_id = wtcc.tabs.get_tab_id();

				if ( tab_id ) {
					wtcc.tabs.set_active_tab( tab_id );
				}

				wtcc.els.tab_links.on( 'click', function( e ) {
					// Show tab
					var tab_id = $( this ).attr( 'href' );

					wtcc.tabs.set_active_tab( tab_id );

					e.preventDefault();
				} );
			},

			/**
			 * Is storage available.
			 */
			has_storage: 'undefined' !== typeof (Storage),

			/**
			 * Store tab ID.
			 *
			 * @param tab_id
			 */
			set_tab_id: function( tab_id ) {
				if ( !wtcc.tabs.has_storage ) {
					return;
				}

				localStorage.setItem( wtcc.tabs.get_option_page() + '_wtcc_tab_id', tab_id );
			},

			/**
			 * Get tab ID.
			 *
			 * @returns {boolean}
			 */
			get_tab_id: function() {
				// If the tab id is specified in the URL hash, use that.
				if ( window.location.hash ) {
					// Check if hash is a tab.
					if ( $( `.wtcc-nav a[href="${window.location.hash}"]` ).length ) {
						return window.location.hash;
					}
				}

				if ( !wtcc.tabs.has_storage ) {
					return false;
				}

				return localStorage.getItem( wtcc.tabs.get_option_page() + '_wtcc_tab_id' );
			},

			/**
			 * Set active tab.
			 *
			 * @param tab_id
			 */
			set_active_tab: function( tab_id ) {
				
				var $tab = $( tab_id ),
					$tab_link = $( '.wtcc-nav__item-link[href="' + tab_id + '"]' );
					console.log($tab);
				if ( $tab.length <= 0 || $tab_link.length <= 0 ) {
					// Reset to first available tab.
					$tab_link = $( '.wtcc-nav__item-link' ).first();
					tab_id = $tab_link.attr( 'href' );
					$tab = $( tab_id );
				}
				console.log(wtcc.els.tab_links.parent());
				// Set tab link active class
				wtcc.els.tab_links.parent().removeClass( 'wtcc-nav__item--active' );
				$( 'a[href="' + tab_id + '"]' ).parent().addClass( 'wtcc-nav__item--active' );

				// Show tab
				$( '.wtcc-tab' ).removeClass( 'wtcc-tab--active' );
				$tab.addClass( 'wtcc-tab--active' );

				wtcc.tabs.set_tab_id( tab_id );
			},

			/**
			 * Get unique option page name.
			 *
			 * @returns {jQuery|string|undefined}
			 */
			get_option_page: function() {
				return $( 'input[name="option_page"]' ).val();
			}
		},
	};
	$( document ).ready( wtcc.on_ready );
	
}( jQuery, document ));