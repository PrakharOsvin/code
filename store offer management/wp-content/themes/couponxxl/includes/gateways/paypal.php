<?php
class CouponXXL_PayPal{
	
	public $slug = 'paypal';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
		add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );

        add_action( 'couponxxl_process_response', array( $this, 'process_response' ) );

        add_action( 'couponxxl_process_payout', array( $this, 'process_payout' ) );
        add_action( 'couponxxl_process_refund', array( $this, 'process_refund' ) );

        add_action( 'couponxxl_payout_fields_'.$this->slug, array( $this, 'payout_field' ), 10, 2 );
	}

	public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $paypal_username = couponxxl_get_option( 'paypal_username' );
        if( !empty( $paypal_username ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'PayPal', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => true,
                'has_payout' => true
            );
        }
	}

	public function theme_options( &$sections ){
        // PayPal API //
        $sections[] = array(
            'title' => esc_html__('PayPal API', 'couponxxl'),
            'desc' => esc_html__('Important PayPal Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
            'fields' => array(
                array(
                    'id' => 'paypal_mode',
                    'type' => 'select',
                    'title' => esc_html__('PayPal Mode', 'couponxxl'),
                    'compiler' => 'true',
                    'options' => array(
                        '' => esc_html__('Live mode', 'couponxxl'),
                        '.sandbox' => esc_html__('Testing mode', 'couponxxl')
                    )
                ),                    
                array(
                    'id' => 'paypal_username',
                    'type' => 'text',
                    'title' => esc_html__('Paypal API Username', 'couponxxl'),
                    'desc' => esc_html__('Input paypal API username here.', 'couponxxl')
                ),
                array(
                    'id' => 'paypal_password',
                    'type' => 'text',
                    'title' => esc_html__('Paypal API Password', 'couponxxl'),
                    'desc' => esc_html__('Input paypal API password here.', 'couponxxl')
                ),
                array(
                    'id' => 'paypal_signature',
                    'type' => 'text',
                    'title' => esc_html__('Paypal API Signature', 'couponxxl'),
                    'desc' => esc_html__('Input paypal API signature here.', 'couponxxl')
                )

            )
        );
	}

	public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway ){
		if( $gateway == $this->slug ){

			$cancelUrl = add_query_arg( array('gateway' => $this->slug, 'cancel' => 'yes', 'transient' => $transient ), $permalink );
			$returnUrl = add_query_arg( array('gateway' => $this->slug, 'confirm' => 'yes', 'transient' => $transient ), $permalink );

			$paypal = new PayPal( $this->get_credentials( $cancelUrl, $returnUrl ) );

			$pdata = array(
				'PAYMENTREQUEST_0_PAYMENTACTION' => "SALE",
				'L_PAYMENTREQUEST_0_NAME0' => $title,
				'L_PAYMENTREQUEST_0_NUMBER0' => uniqid( true ),
				'L_PAYMENTREQUEST_0_DESC0' => $desc,
				'L_PAYMENTREQUEST_0_AMT0' => $amount,
				'L_PAYMENTREQUEST_0_QTY0' => 1,
				'NOSHIPPING' => 1,
				'PAYMENTREQUEST_0_CURRENCYCODE' => $main_unit_abbr,
				'PAYMENTREQUEST_0_AMT' => $amount,
				'cancelUrl' => $cancelUrl,
				'returnUrl' => $returnUrl
			);

			set_transient('cxxl_paypal_express_'.get_current_user_id(), $pdata );

			$response = $paypal->SetExpressCheckout( $pdata );
			if( !isset( $response['error'] ) ){
				wp_redirect( $response['url'] );
			}
			else{
				echo '<div class="alert alert-danger">'.$response['error'].'</div>';
			}
		}
	}

	private function get_credentials( $cancelUrl = '', $returnUrl = '' ){
		return  array(
			'username' => couponxxl_get_option( 'paypal_username' ),
			'password' => couponxxl_get_option( 'paypal_password' ),
			'signature' => couponxxl_get_option( 'paypal_signature' ),
			'cancelUrl' => $cancelUrl,
			'returnUrl' => $returnUrl,
		);
	}

	public function do_express_checkout(){

		$pdata = maybe_unserialize( get_transient( 'cxxl_paypal_express_'.get_current_user_id() ) );
		if( !empty( $pdata ) ){
			$paypal = new PayPal( $this->get_credentials( $pdata['cancelUrl'], $pdata['returnUrl'] ) );

			$pdata = array_merge(
				array(
					'TOKEN' => $_GET['token'],
					'PAYERID' => $_GET['PayerID'],
				),
				$pdata
			);

			$response = $paypal->DoExpressCheckoutPayment( $pdata );
		}
		else{
			$response['error'] = esc_html__( 'This transaction is already processed', 'couponxxl' );
		}

		return $response;
	}

	public function process_response( $gateway ){
		if( $gateway == $this->slug ){
			$transient = $_GET['transient'];
			$transient_data = maybe_unserialize( get_transient( $transient ) );
			if( !empty( $_GET['confirm'] ) ){
	            if( !empty( $transient_data ) ){
	            	$response = $this->do_express_checkout();

					if( !isset( $response['error'] ) && !isset( $response['L_ERRORCODE0'] ) ){
						delete_transient( 'cxxl_paypal_express_'.get_current_user_id() );
						echo couponxxl_process_payment_details( $transient, $transient_data, array( 'transaction_id' => $response['PAYMENTINFO_0_TRANSACTIONID'], 'PayerID' => $_GET['PayerID'] ) );
					}
					else if( isset( $response['L_ERRORCODE0'] ) && $response['L_ERRORCODE0'] === '11607' ){
						echo '<div class="alert alert-danger">'.esc_html__( 'This transaction is already processed.', 'couponxxl' ).'</div>';
					}
					else{
						echo '<div class="alert alert-danger">'.esc_html__( 'There was an error processing yor request: <strong>', 'couponxxl' ).$response['error'].'</strong></div>';
					}
	            }
	        }
	        else{
	        	couponxxl_cancel_order( $transient, $transient_data );
	        }
		}
	}

	public function process_payout( $sellers ){
        global $wpdb;
        $response = '';		
		if( !empty( $sellers['paypal'] ) ){
			$paypal = new PayPal(array(
				'username' => couponxxl_get_option( 'paypal_username' ),
				'password' => couponxxl_get_option( 'paypal_password' ),
				'signature' => couponxxl_get_option( 'paypal_signature' ),
				'cancelUrl' => '',
				'returnUrl' => '',
			));

			$pdata = array(
				'RECEIVERTYPE' => 'EmailAddress',				
				'CURRENCYCODE' => couponxxl_get_option( 'main_unit_abbr' ),
			);
			$counter = 0;
			foreach( $sellers['paypal'] as $payment_data ){
				$pdata['L_EMAIL'.$counter] = $payment_data['seller_payout_account'];
				$pdata['L_AMT'.$counter] = $payment_data['amount'];
				$counter++;
			}

			$details = $paypal->MassPay( $pdata );
			if( !empty( $details['error'] ) ){
				$response .= couponxxl_wrap_message( $payment_data['user'].' - '.$details['error'], 'error' );
			}
			else{
				$ids = array();
				foreach( $sellers['paypal'] as $payment_data ){
					$ids += $payment_data['order_item_ids'];
					$response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Payment Sent', 'couponxxl' ), 'success' );
				}
                $wpdb->query( "UPDATE {$wpdb->prefix}order_items SET status = 'sent' WHERE item_id IN ( ".esc_sql( join( ',', $ids ) )." )" );
			}
		}
	}

	public function process_refund( $buyers ){
        global $wpdb;
        $response = '';
        $data = array();
		if( !empty( $buyers['paypal'] ) ){
			$paypal = new PayPal(array(
				'username' => couponxxl_get_option( 'paypal_username' ),
				'password' => couponxxl_get_option( 'paypal_password' ),
				'signature' => couponxxl_get_option( 'paypal_signature' ),
				'cancelUrl' => '',
				'returnUrl' => '',
			));

			foreach( $buyers['paypal'] as $payment_data ){
				$payment_data['transaction_details']['transaction_details'] = maybe_unserialize( $payment_data['transaction_details']['transaction_details'][0] );
				$pdata = array(
					'REFUNDTYPE' => 'Full',
					'TRANSACTIONID' => $payment_data['transaction_details']['transaction_details']['transaction_id'],
					'CURRENCYCODE' => couponxxl_get_option( 'main_unit_abbr' )
				);
				$details = $paypal->Refund( $pdata );

				if( !empty( $details['error'] ) ){
					$response .= couponxxl_wrap_message( $payment_data['user'].' - '.$details['error'], 'error' );
				}
				else{
                    $data[$payment_data['order_id']] = array(
                        'ids' => $payment_data['order_item_ids'],
                        'amount' => $payment_data['amount']
                    );
                    $response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Refund Sent', 'couponxxl' ), 'updated' );
				}				
			}
			set_transient( 'couponxxl_process_refund', $response );
			if( !empty( $data ) ){
				couponxxl_process_refund( $data );
			}
		}
	}

	public function payout_field( $seller_payout_account, $seller_payout_method ){
		$class = '';
		if( $seller_payout_method != $this->slug ){
			$seller_payout_account = '';
			$class = "hidden";
		}
		?>
		<div class="payout-connect payout-<?php echo esc_attr( $this->slug ) ?> <?php echo esc_attr( $class ) ?>">
			<div class="input-group">
			    <label for="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>"><?php esc_html_e( 'PayPal Email', 'couponxxl' )?></label>
			    <input type="text" name="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>" id="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>" class="form-control" value="<?php echo esc_attr( $seller_payout_account ) ?>">
			    <i class="pline-lock-locked"></i>
			    <p class="description"><?php esc_html_e( 'Input your PayPal email address.', 'couponxxl' ); ?></p>
			</div>
		</div>
		<?php
	}
}
$cxxl_paypal = new CouponXXL_PayPal();
?>