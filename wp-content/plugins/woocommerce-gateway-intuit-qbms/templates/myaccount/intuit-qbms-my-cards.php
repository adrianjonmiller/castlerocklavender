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
 * The My Account - My Cards
 *
 * @param SV_WC_Payment_Gateway $gateway the payment gateway
 * @param array $tokens optional array of payment token string to SV_WC_Payment_Gateway_Payment_Token object
 *
 * @version 1.2
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?> <h2 id="wc-intuit-qbms-my-payment-methods" style="margin-top:40px;"><?php _e( 'My Saved Cards', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></h2><?php

if ( ! empty( $tokens ) ) :
	?>
	<table class="shop_table wc-intuit-qbms-payment-methods-my-account">

		<thead>
		<tr>
			<th class="wc-intuit-qbms-payment-method-type"><span class="nobr"><?php _e( 'Card Type', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></span></th>
			<th class="wc-intuit-qbms-payment-method-account"><span class="nobr"><?php _e( 'Last Four', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></span></th>
			<th class="wc-intuit-qbms-payment-method-exp-date"><span class="nobr"><?php _e( 'Expires', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></span></th>
			<th class="wc-intuit-qbms-payment-method-status"><span class="nobr"><?php _e( 'Status', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></span></th>
			<th class="wc-intuit-qbms-payment-method-actions"><span class="nobr"><?php _e( 'Actions', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></span></th>
		</tr>
		</thead>

		<tbody>
			<?php foreach ( $tokens as $token ) :
				$delete_url       = wp_nonce_url( add_query_arg( array( 'wc-intuit-qbms-token' => $token->get_token(), 'wc-intuit-qbms-action' => 'delete' ) ), 'wc-intuit-qbms-token-action' );
				$make_default_url = wp_nonce_url( add_query_arg( array( 'wc-intuit-qbms-token' => $token->get_token(), 'wc-intuit-qbms-action' => 'make-default' ) ), 'wc-intuit-qbms-token-action' );
				$payment_method_image_url  = $token->get_image_url();
				?>
				<tr class="wc-intuit-qbms-payment-method-label">
					<td class="wc-intuit-qbms-payment-method-type">
						<?php if ( $payment_method_image_url ) : ?>
							<img src="<?php echo esc_url( $payment_method_image_url ); ?>" alt="<?php esc_attr_e( $token->get_type_full(), WC_Intuit_QBMS::TEXT_DOMAIN ); ?>" title="<?php esc_attr_e( $token->get_type_full(), WC_Intuit_QBMS::TEXT_DOMAIN ); ?>" style="vertical-align:middle;" />
						<?php else: ?>
							<?php echo esc_html_e( $token->get_type_full(), WC_Intuit_QBMS::TEXT_DOMAIN ); ?>
						<?php endif; ?>
					</td>
					<td class="wc-intuit-qbms-payment-method-account-number">
						<?php echo esc_html( $token->get_last_four() ); ?>
					</td>
					<td class="wc-intuit-qbms-payment-method-exp-date">
						<?php echo esc_html( $token->get_exp_month() . '/' . $token->get_exp_year() ); ?>
					</td>
					<td class="wc-intuit-qbms-payment-method-status">
						<?php echo ( $token->is_default() ) ? __( 'Default', WC_Intuit_QBMS::TEXT_DOMAIN ) : '<a href="' . esc_url( $make_default_url ) . '">' . __( 'Make Default', WC_Intuit_QBMS::TEXT_DOMAIN ) . '</a>'; ?>
					</td>
					<td class="wc-intuit-qbms-payment-method-actions" style="width: 1%; text-align: center;">
						<a href="<?php echo esc_url( $delete_url ); ?>" title="<?php esc_attr_e( 'Delete payment method', WC_Intuit_QBMS::TEXT_DOMAIN ); ?>" class="wc-intuit-qbms-delete-payment-method js-wc-intuit-qbms-delete-payment-method"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>

	</table>
<?php

else :

	?><p><?php _e( 'You do not have any saved payment methods.', WC_Intuit_QBMS::TEXT_DOMAIN ); ?></p><?php

endif;
