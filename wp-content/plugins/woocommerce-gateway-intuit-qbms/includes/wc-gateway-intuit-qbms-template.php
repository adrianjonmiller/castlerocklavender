<?php
/**
 * WooCommerce Intuit QBMS
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Intuit QBMS to newer
 * versions in the future. If you wish to customize WooCommerce Intuit QBMS for your
 * needs please refer to http://docs.woothemes.com/document/intuit-qbms/
 *
 * @package   WC-Intuit-QBMS/Templates
 * @author    SkyVerge
 * @copyright Copyright (c) 2013-2014, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * Template Function Overrides
 *
 * @since 1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! function_exists( 'woocommerce_intuit_qbms_payment_fields' ) ) {

	/**
	 * Pluggable function to render the checkout page payment fields form
	 *
	 * @since 1.0
	 * @param WC_Gateway_Intuit_QBMS_Credit_Card $gateway gateway object
	 */
	function woocommerce_intuit_qbms_payment_fields( $gateway ) {

		// safely display the description, if there is one
		if ( $gateway->get_description() )
			echo '<p>' . wp_kses_post( $gateway->get_description() ) . '</p>';

		$payment_method_defaults = array(
			'account-number' => '',
			'exp-month'      => '',
			'exp-year'       => '',
			'csc'            => '',
		);

		// for the demo environment, display a notice and supply a default test payment method
		if ( $gateway->is_environment( 'test' ) ) {
			echo '<p>' . __( 'TEST MODE ENABLED', WC_Intuit_QBMS::TEXT_DOMAIN ) . '</p>';

			$payment_method_defaults = array(
				'account-number' => '4111111111111111',
				'exp-month'      => '1',
				'exp-year'       => date( 'Y' ) + 1,
				'csc'            => '123',
			);

			// convenience for testing error conditions
			$test_conditions = array(
				'10200_comm'        => __( 'CC Processing Gateway comm error', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10201_login'       => __( 'Processing Gateway login error', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10301_ccinvalid'   => __( 'Invalid CC account number', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10400_insufffunds' => __( 'Insufficient funds', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10401_decline'     => __( 'Transaction declined', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10403_acctinvalid' => __( 'Invalid merchant account', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10404_referral'    => __( 'Declined pending voice auth', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10406_capture'     => __( 'Capture error', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10500_general'     => __( 'General error', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'10000_avscvdfail'  => __( 'AVS Failure', WC_Intuit_QBMS::TEXT_DOMAIN ),
			);

			echo '<select name="wc-intuit-qbms-test-condition">';
			echo '<option value="">' . __( 'Test an Error Condition:', WC_Intuit_QBMS::TEXT_DOMAIN ) . '</option>';
			foreach ( $test_conditions as $key => $value )
				echo '<option value="' . $key . '">' . $value . '</option>';
			echo '</select>';
		}

		// tokenization is allowed if tokenization is enabled on the gateway
		$tokenization_allowed = $gateway->tokenization_enabled();

		// on the pay page there is no way of creating an account, so disallow tokenization for guest customers
		if ( $tokenization_allowed && SV_WC_Plugin_Compatibility::is_checkout_pay_page() && ! is_user_logged_in() ) {
			$tokenization_allowed = false;
		}

		$tokens = array();
		$default_new_card = true;
		if ( $tokenization_allowed && is_user_logged_in() ) {
			$tokens = $gateway->get_payment_tokens( get_current_user_id() );

			foreach ( $tokens as $token ) {
				if ( $token->is_default() ) {
					$default_new_card = false;
					break;
				}
			}
		}

		// load the payment fields template file
		woocommerce_get_template(
			'checkout/intuit-qbms-payment-fields.php',
			array(
				'payment_method_defaults' => $payment_method_defaults,
				'enable_csc'              => $gateway->csc_enabled(),
				'tokens'                  => $tokens,
				'tokenization_allowed'    => $tokenization_allowed,
				'tokenization_forced'     => $gateway->tokenization_forced(),
				'default_new_card'        => $default_new_card,
			),
			'',
			$gateway->get_plugin()->get_plugin_path() . '/templates/'
		);

	}
}


if ( ! function_exists( 'woocommerce_intuit_qbms_show_my_payment_methods' ) ) {

	/**
	 * Pluggable function to render the gateway tokenized credit card payment methods on
	 * the My Account page
	 *
	 * @since 1.0
	 * @param WC_Gateway_Intuit_QBMS_Credit_Card $gateway gateway class
	 */
	function woocommerce_intuit_qbms_show_my_payment_methods( $gateway ) {

		$user_id = get_current_user_id();

		// get available saved payment methods
		$tokens = $gateway->get_payment_tokens( $user_id );

		// load the My Account - My Cards template file
		woocommerce_get_template(
			'myaccount/intuit-qbms-my-cards.php',
			array(
				'gateway' => $gateway,
				'tokens'  => $tokens,
			),
			'',
			$gateway->get_plugin()->get_plugin_path() . '/templates/'
		);

		// Add confirm javascript when deleting payment methods
		ob_start();
		?>
			$( 'a.js-wc-intuit-qbms-delete-payment-method' ).click( function( e ) {
				if ( ! confirm( '<?php _e( 'Are you sure you want to delete this payment method?', WC_Intuit_QBMS::TEXT_DOMAIN ); ?>') ) {
					e.preventDefault();
				}
			} );
		<?php
		SV_WC_Plugin_Compatibility::wc_enqueue_js( ob_get_clean() );
	}

}


if ( ! function_exists( 'woocommerce_intuit_qbms_echeck_payment_fields' ) ) {

	/**
	 * Pluggable function to render the checkout page payment fields form
	 *
	 * @since 1.0
	 * @param WC_Gateway_Intuit_QBMS $gateway gateway object
	 */
	function woocommerce_intuit_qbms_echeck_payment_fields( $gateway ) {

		// safely display the description, if there is one
		if ( $gateway->get_description() )
			echo '<p>' . wp_kses_post( $gateway->get_description() ) . '</p>';

		$payment_method_defaults = array(
			'account-number'         => '',
			'routing-number'         => '',
			'drivers-license-number' => '',
			'drivers-license-state'  => '',
			'account-type'           => '',
			'check-number'           => '',
		);

		// for the demo environment, display a notice and supply a default test payment method
		if ( $gateway->is_environment( 'test' ) ) {
			echo '<p>' . __( 'TEST MODE ENABLED', WC_Intuit_QBMS::TEXT_DOMAIN ) . '</p>';

			$payment_method_defaults = array(
				'account-number'         => '',
				'routing-number'         => '',
				'drivers-license-number' => '1234',
				'drivers-license-state'  => 'MA',
				'account-type'           => 'checking',
				'check-number'           => '1',
			);

		}

		// tokenization is allowed if tokenization is enabled on the gateway
		$tokenization_allowed = $gateway->tokenization_enabled();

		$tokens = array();
		$default_new_account = true;
		if ( $tokenization_allowed && is_user_logged_in() ) {
			$tokens = $gateway->get_payment_tokens( get_current_user_id() );

			foreach ( $tokens as $token ) {
				if ( $token->is_default() ) {
					$default_new_account = false;
					break;
				}
			}
		}

		// load the payment fields template file
		woocommerce_get_template(
			'checkout/intuit-qbms-echeck-payment-fields.php',
			array(
				'payment_method_defaults' => $payment_method_defaults,
				'sample_check_image_url'  => SV_WC_Plugin_Compatibility::force_https_url( $gateway->get_plugin()->get_plugin_url() ) . '/' . $gateway->get_plugin()->get_framework_image_path() . 'example-check.png',
				'states'                  => SV_WC_Plugin_Compatibility::WC()->countries->get_states( 'US' ),
				'tokens'                  => $tokens,
				'tokenization_allowed'    => $tokenization_allowed,
				'tokenization_forced'     => $gateway->tokenization_forced(),
				'default_new_account'     => $default_new_account,
			),
			'',
			$gateway->get_plugin()->get_plugin_path() . '/templates/'
		);

	}
}



if ( ! function_exists( 'woocommerce_intuit_qbms_echeck_show_my_accounts' ) ) {

	/**
	 * Pluggable function to render the gateway tokenized checking account payment methods on
	 * the My Account page
	 *
	 * @since 1.0
	 * @param WC_Gateway_Intuit_QBMS $gateway gateway class
	 */
	function woocommerce_intuit_qbms_echeck_show_my_accounts( $gateway ) {

		$user_id = get_current_user_id();

		// get available saved payment methods
		$tokens = $gateway->get_payment_tokens( $user_id );

		// load the My Account - My Accounts template file
		woocommerce_get_template(
			'myaccount/intuit-qbms-echeck-my-accounts.php',
			array(
				'gateway' => $gateway,
				'tokens'  => $tokens,
			),
			'',
			$gateway->get_plugin()->get_plugin_path() . '/templates/'
		);

		// Add confirm javascript when deleting payment methods
		ob_start();
		?>
			$( 'a.js-wc-intuit-qbms-echeck-delete-payment-method' ).click( function( e ) {
				if ( ! confirm( '<?php _e( 'Are you sure you want to delete this payment method?', WC_Intuit_QBMS::TEXT_DOMAIN ); ?>') ) {
					e.preventDefault();
				}
			} );
		<?php
		SV_WC_Plugin_Compatibility::wc_enqueue_js( ob_get_clean() );
	}

}
