/*!
 * WooCommerce Intuit QBMS
 * Version 1.0
 *
 * Copyright (c) 2013-2014, SkyVerge, Inc.
 * Licensed under the GNU General Public License v3.0
 * http://www.gnu.org/licenses/gpl-3.0.html
 */
jQuery( document ).ready( function ( $ ) {

	'use strict';

	/* global intuit_qbms_params */

	// checkout page
	if ( $( 'form.checkout' ).length ) {

		// handle payment methods, note this is bound to the updated_checkout trigger so it fires even when other parts
		// of the checkout are changed
		$( 'body' ).bind( 'updated_checkout', function() { handleSavedPaymentMethods(); } );

		// validate payment data before order is submitted
		$( 'form.checkout' ).bind( 'checkout_place_order_intuit_qbms', function() { return validateCardData( $( this ) ); } );

	// checkout->pay page
	} else {

		// handle payment methods on checkout->pay page
		handleSavedPaymentMethods();

		// validate card data before order is submitted when the payment gateway is selected
		$( 'form#order_review' ).submit( function () {

			if ( 'intuit_qbms' === $( '#order_review input[name=payment_method]:checked' ).val() ) {
				return validateCardData( $( this ) );
			}

		} );
	}


	// Perform validation on the card info entered
	function validateCardData( $form ) {

		if ( $form.is( '.processing' ) ) { return false; }

		var $paymentFields = $( '.payment_method_intuit_qbms' );

		var tokenizedPaymentMethodSelected = $paymentFields.find( '.js-wc-payment-gateway-payment-token:checked' ).val();

		// don't validate fields if a saved payment method is being used
		if ( tokenizedPaymentMethodSelected ) {
			return true;
		}

		var errors = [];

		var accountNumber = $paymentFields.find( '.js-wc-payment-gateway-account-number' ).val();
		var csc           = $paymentFields.find( '.js-wc-payment-gateway-csc' ).val();  // optional element
		var expMonth      = $paymentFields.find( '.js-wc-payment-gateway-card-exp-month' ).val();
		var expYear       = $paymentFields.find( '.js-wc-payment-gateway-card-exp-year' ).val();

		// replace any dashes or spaces in the card number
		accountNumber = accountNumber.replace( /-|\s/g, '' );

		// validate card number
		if ( ! accountNumber ) {

			errors.push( intuit_qbms_params.card_number_missing );

		} else {

			if ( accountNumber.length < 12 || accountNumber.length > 19 ) {
				errors.push( intuit_qbms_params.card_number_length_invalid );
			}

			if ( /\D/.test( accountNumber ) ) {
				errors.push( intuit_qbms_params.card_number_digits_invalid );
			}

			if ( ! luhnCheck( accountNumber ) ) {
				errors.push( intuit_qbms_params.card_number_invalid );
			}

		}

		// validate expiration date
		var currentYear = new Date().getFullYear();

		if ( /\D/.test( expMonth ) || /\D/.test( expYear ) ||
				expMonth > 12 ||
				expMonth < 1 ||
				expYear < currentYear ||
				expYear > currentYear + 20 ) {
			errors.push( intuit_qbms_params.card_exp_date_invalid );
		}

		// validate CSC if present
		if ( 'undefined' !== typeof csc ) {

			if ( ! csc ) {
				errors.push( intuit_qbms_params.cvv_missing );
			} else {

				if (/\D/.test( csc ) ) {
					errors.push( intuit_qbms_params.cvv_digits_invalid );
				}

				if ( csc.length < 3 || csc.length > 4 ) {
					errors.push( intuit_qbms_params.cvv_length_invalid );
				}

			}

		}

		if ( errors.length > 0 ) {

			renderErrors( $form, errors );

			return false;

		} else {

			// get rid of any space/dash characters
			$paymentFields.find( '.js-wc-payment-gateway-account-number' ).val( accountNumber );

			return true;
		}
	}


	// luhn check
	function luhnCheck( accountNumber ) {
		var i, ix, weight, sum = 0;
		for ( i = 0, ix = accountNumber.length; i < ix - 1; i++ ) {
			weight = parseInt( accountNumber.substr( ix - ( i + 2 ), 1 ) * ( 2 - ( i % 2 ) ), 10 );
			sum += weight < 10 ? weight : weight - 9;
		}

		return parseInt( accountNumber.substr( ix - 1 ), 10 ) === ( ( 10 - sum % 10 ) % 10 );
	}


	// render any new errors and bring them into the viewport
	function renderErrors( $form, errors ) {

		// hide and remove any previous errors
		$( '.woocommerce-error, .woocommerce-message' ).remove();

		// add errors
		$form.prepend( '<ul class="woocommerce-error"><li>' + errors.join( '</li><li>' ) + '</li></ul>' );

		// unblock UI
		$form.removeClass( 'processing' ).unblock();

		$form.find( '.input-text, select' ).blur();

		// scroll to top
		$( 'html, body' ).animate( {
			scrollTop: ( $form.offset().top - 100 )
		}, 1000 );

	}


	// show/hide the saved payment methods when a saved payment method is de-selected/selected
	function handleSavedPaymentMethods() {

		$( 'input.js-wc-intuit-qbms-payment-token' ).change( function() {

				var tokenizedPaymentMethodSelected = $( 'input.js-wc-intuit-qbms-payment-token:checked' ).val(),
					$newPaymentMethodSection = $( 'div.js-wc-intuit-qbms-new-payment-method-form' );

				if ( tokenizedPaymentMethodSelected ) {

					// using an existing tokenized payment method, hide the 'new method' fields
					$newPaymentMethodSection.slideUp( 200 );

					if ( intuit_qbms_params.require_csc ) {
						// move the CSC field out of the 'new method' fields so it can be used with the tokenized transaction
						$( '.js-wc-intuit-qbms-new-payment-method-form' ).after( $( '.js-wc-intuit-qbms-csc' ).parent() );
					}

				} else {

					// use new payment method, display the 'new method' fields
					$newPaymentMethodSection.slideDown( 200 );

					if ( intuit_qbms_params.require_csc ) {
						// move the CSC field back into its regular spot
						$( '.js-wc-intuit-qbms-csc-clear' ).before( $( '.js-wc-intuit-qbms-csc' ).parent() );
					}

				}
		} ).change();

		// display the 'save payment method' option for guest checkouts if the 'create account' option is checked
		//  but only hide the input if there is a 'create account' checkbox (some themes just display the password)
		$( 'input#createaccount' ).change( function() {

			var $parentRow = $( 'input.js-wc-intuit-qbms-tokenize-payment-method' ).closest( 'p.form-row' );

			if ( $( this ).is( ':checked' ) ) {
				$parentRow.slideDown();
				$parentRow.next().show();
			} else {
				$parentRow.hide();
				$parentRow.next().hide();
			}

		} ).change();
	}

} );
