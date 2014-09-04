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
 * @package   WC-Intuit-QBMS/Gateway
 * @author    SkyVerge
 * @copyright Copyright (c) 2013-2014, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Intuit QBMS eCheck Payment Gateway
 *
 * Handles all purchases, displaying saved accounts, etc
 *
 * This is a direct check gateway that supports tokenization, subscriptions and pre-orders.
 *
 * @since 1.0
 */
class WC_Gateway_Intuit_QBMS_eCheck extends WC_Gateway_Intuit_QBMS {


	/**
	 * Initialize the gateway
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// parent plugin
		global $wc_intuit_qbms;

		parent::__construct(
			WC_Intuit_QBMS::ECHECK_GATEWAY_ID,
			$wc_intuit_qbms,
			WC_Intuit_QBMS::TEXT_DOMAIN,
			array(
				'method_title'       => __( 'Intuit QBMS eCheck', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'method_description' => __( 'Allow customers to securely pay using their checking accounts with Intuit QBMS.', WC_Intuit_QBMS::TEXT_DOMAIN ),
				'supports'           => array(
					'products',
					'tokenization',
				 ),
				'payment_type'       => 'echeck',
				'echeck_fields'      => array( 'check_number', 'account_type' ),
				'environments'       => array( 'production' => __( 'Production', WC_Intuit_QBMS::TEXT_DOMAIN ), 'test' => __( 'Test', WC_Intuit_QBMS::TEXT_DOMAIN ) ),
				'shared_settings'    => $this->shared_settings_names,
			)
		);

		// add subscriptions support if tokenization is enabled
		if ( $this->tokenization_enabled() ) {

			$this->add_support(
				array(
					'subscriptions',
					'subscription_suspension',
					'subscription_cancellation',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes',
					'subscription_payment_method_change',
				)
			);

		}

		// add pre-orders support if tokenization is enabled
		if ( $this->tokenization_enabled() ) {
			$this->add_support(
				array(
					'pre-orders',
				)
			);
		}
	}


	/**
	 * Display the payment fields on the checkout page
	 *
	 * @since 1.0
	 * @see WC_Payment_Gateway::payment_fields()
	 */
	public function payment_fields() {

		woocommerce_intuit_qbms_echeck_payment_fields( $this );

	}


	/**
	 * Render the "My Payment Methods" template
	 *
	 * @since 1.0
	 * @see SV_WC_Payment_Gateway::show_my_payment_methods_load_template()
	 */
	protected function show_my_payment_methods_load_template() {

		woocommerce_intuit_qbms_echeck_show_my_payment_methods( $this );

	}


	/**
	 * Adds any gateway-specific transaction data to the order
	 *
	 * @since 1.0
	 * @see SV_WC_Payment_Gateway::add_payment_gateway_transaction_data()
	 * @param WC_Order $order the order object
	 * @param WC_Intuit_QBMS_API_Response $response the transaction response
	 */
	protected function add_payment_gateway_transaction_data( $order, $response ) {

		// transaction results
		$this->update_order_meta( $order->id, 'authorization_code', $response->get_check_authorization_code() );
		$this->update_order_meta( $order->id, 'client_trans_id',    $response->get_client_trans_id() );

	}


	/** Subscriptions ******************************************************/


	/**
	 * Returns the query fragment to remove the given subscription renewal
	 * order meta, plus the Intuit QBMS specific meta
	 *
	 * @since 1.0
	 * @see SV_WC_Payment_Gateway::get_remove_subscription_renewal_order_meta_fragment()
	 * @see SV_WC_Payment_Gateway::remove_subscription_renewal_order_meta()
	 * @param array $meta_names array of string meta names to remove
	 * @return string query fragment
	 */
	protected function get_remove_subscription_renewal_order_meta_fragment( $meta_names ) {

		$meta_names[] = $this->get_order_meta_prefix() . 'authorization_code';
		$meta_names[] = $this->get_order_meta_prefix() . 'client_trans_id';

		return parent::get_remove_subscription_renewal_order_meta_fragment( $meta_names );

	}


}
