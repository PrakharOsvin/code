<?php
class CouponXXL_Skrill{
    
    public $slug = 'skrill';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
        add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'append_scripts' ) );
        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );

        add_action( 'couponxxl_process_response', array( $this, 'process_response' ) );
        add_action( 'couponxxl_process_verify', array( $this, 'verify_skrill' ) );

        add_action( 'couponxxl_process_payout', array( $this, 'process_payout' ) );

        add_action( 'couponxxl_process_refund', array( $this, 'process_refund' ) );

        add_action( 'couponxxl_payout_fields_'.$this->slug, array( $this, 'payout_field' ), 10, 2 );
	}

    public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $skrill_owner_mail = couponxxl_get_option( 'skrill_owner_mail' );
        if( !empty( $skrill_owner_mail ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'Skrill', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => true,
                'has_payout' => true
            );
        }
    }

    public function theme_options( &$sections ){
        // Skrill API //
        $sections[] = array(
            'title' => esc_html__('Skrill API', 'couponxxl'),
            'desc' => esc_html__('Important Skrill Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
            'fields' => array(
                array(
                    'id' => 'skrill_owner_mail',
                    'type' => 'text',
                    'title' => esc_html__('You skrill mail', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your email which is connected with your skrill account.', 'couponxxl')
                ),
                array(
                    'id' => 'skrill_secret_word',
                    'type' => 'text',
                    'title' => esc_html__('You skrill secret word', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your scrill secret word.', 'couponxxl')
                ),
                array(
                    'id' => 'skrill_api_mqi_password',
                    'type' => 'text',
                    'title' => esc_html__('Your API/MQI password.', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your API/MQI password.', 'couponxxl')
                ),                    
            )
        );
    }    

    public function append_scripts(){
        wp_enqueue_script( 'couponxxl-stripe', 'https://checkout.stripe.com/checkout.js', false, false, true );
        wp_enqueue_script( 'couponxxl-skrill', get_template_directory_uri() . '/js/gateways/skrill.js', false, false, true );
    }  
    public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway ){
        if( $gateway == $this->slug ){
            $status_url = add_query_arg( array( 'gateway' => $this->slug, 'status' => 'yes', 'transient' => $transient ), home_url('/').'index.php' );
            $return_url = add_query_arg( array( 'gateway' => $this->slug, 'return' => 'yes', 'transient' => $transient ) );
            echo '
            <form class="hidden skrill-payment" action="https://www.moneybookers.com/app/payment.pl" method="post">
                <input type="hidden" name="pay_to_email" value="'.esc_attr( couponxxl_get_option( 'skrill_owner_mail' ) ).'"/>
                <input type="hidden" name="status_url" value="'.esc_url( $status_url ).'"/> 
                <input type="hidden" name="language" value="EN"/>
                <input type="hidden" name="return_url" value="'.esc_url( $return_url ).'"/>
                <input type="hidden" name="amount" value="'.esc_attr( $amount ).'"/>
                <input type="hidden" name="currency" value="'.esc_attr( $main_unit_abbr ).'"/>    
                <input type="hidden" name="detail1_text " value="'.esc_attr( $title ).'"/>    
            </form>
            ';
        }
    }

    public function process_response( $gateway ){
        if( $gateway == $this->slug && !empty( $_GET['return'] ) ){
            $transient = $_GET['transient'];
            $transient_data = maybe_unserialize( get_transient( $transient ) );
            if( !empty( $transient_data ) ){
                if( $transient_data['purchase'] == 'order' ){
                    $order_status = get_post_meta( $transient_data['order_id'], 'order_status', true );
                    if( $order_status == 'not_paid' ){
                        update_post_meta( $transient_data['order_id'], 'order_status', 'pending_payment' );
                    }
                }
            }
            echo '<div class="alert alert-info">'.esc_html__( 'Thank you for your purchase.<br />Once Skrill confirms payment your order will be processed', 'couponxxl').'</div>';            
        }        
    }

    public function verify_skrill( $gateway ){
        if( $gateway == $this->slug && !empty( $_GET['status'] ) ){
            $transient = $_GET['transient'];
            $transient_data = maybe_unserialize( get_transient( $transient ) );
            if( !empty( $transient_data ) ){
                if( isset( $_POST['merchant_id'] ) ){   
                    $skrill_secret_word = couponxxl_get_option( 'skrill_secret_word' );
                    $concatFields = $_POST['merchant_id']
                        .$_POST['transaction_id']
                        .strtoupper(md5($skrill_secret_word))
                        .$_POST['mb_amount']
                        .$_POST['mb_currency']
                        .$_POST['status'];

                    $MBEmail = couponxxl_get_option( 'skrill_owner_mail' );

                    if ( strtoupper( md5($concatFields) ) == $_POST['md5sig'] && $_POST['status'] == 2 && $_POST['pay_to_email'] == $MBEmail ){
                        couponxxl_process_payment_details( $transient, $transient_data, array( 'transaction_id' => $_POST['transaction_id'] ) );
                    }
                }
            }
        }
    }

    public function process_payout( $sellers ){
        global $wpdb;
        $response = '';
        $ids = array();
        if( !empty( $sellers['skrill'] ) ){
            $base_url = 'https://www.moneybookers.com/app/pay.pl';
            $skrill_api_mqi_password = couponxxl_get_option( 'skrill_api_mqi_password' );
            $skrill_owner_mail = couponxxl_get_option( 'skrill_owner_mail' );
            if( !empty( $skrill_api_mqi_password ) && !empty( $skrill_owner_mail ) ){
                foreach( $sellers['skrill'] as $payment_data ){
                    $query = http_build_query(array(
                        'action' => 'prepare',
                        'email' => $skrill_owner_mail,
                        'password' => md5( $skrill_api_mqi_password ),
                        'amount' => $payment_data['amount'],
                        'currency' => strtolower( couponxxl_get_option( 'main_unit_abbr' ) ),
                        'bnf_email' => $payment_data['seller_payout_account'],
                        'subject' => esc_html__( 'Seller Share', 'couponxxl' ),
                        'note' => esc_html__( 'Seller share payout', 'couponxxl' )
                    ));

                    $post_response = wp_remote_get( $base_url.'?'.$query );
                    if ( is_wp_error( $post_response ) ) {
                        $error_message = $post_response->get_error_message();
                        $response .= couponxxl_wrap_message( esc_html__( 'Something went wrong:', 'couponxxl' ).' - '.$error_message, 'error' );
                    }
                    else{
                        $data = simplexml_load_string( $post_response['body'] );
                        if( empty( $data->error ) ){
                            $post_response = wp_remote_get( $base_url.'?action=transfer&sid='.$data->sid );
                            $data = simplexml_load_string( $post_response['body'] );
                            if( empty( $data->error ) ){
                                $ids += $payment_data['order_item_ids'];
                                $response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Payment Sent', 'couponxxl' ), 'success' );
                            }
                            else{
                                $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data->error->error_msg, 'error' );
                            }
                        }
                        else{
                            $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data->error->error_msg, 'error' );
                        }               
                    }               
                }
                if( !empty( $ids ) ){
                    $wpdb->query( "UPDATE {$wpdb->prefix}order_items SET status = 'sent' WHERE item_id IN ( ".esc_sql( join( ',', $ids ) )." )" );
                }
            }
        }

        echo  $response;
    }

    public function process_refund( $buyers ){
        global $wpdb;
        $response = '';
        $data = array();
        if( !empty( $buyers['skrill'] ) ){
            $base_url = 'https://www.skrill.com/app/refund.pl';
            $skrill_api_mqi_password = couponxxl_get_option( 'skrill_api_mqi_password' );
            $skrill_owner_mail = couponxxl_get_option( 'skrill_owner_mail' );
            if( !empty( $skrill_api_mqi_password ) && !empty( $skrill_owner_mail ) ){
                $skrill_refund_uniq = uniqid( true );
                foreach( $buyers['skrill'] as $payment_data ){
                    $payment_data['transaction_details']['transaction_details'] = maybe_unserialize( $payment_data['transaction_details']['transaction_details'][0] );
                    $query = http_build_query(array(
                        'action' => 'prepare',
                        'email' => $skrill_owner_mail,
                        'password' => md5( $skrill_api_mqi_password ),
                        'amount' => $payment_data['amount'],
                        'transaction_id' => $payment_data['transaction_details']['transaction_details']['transaction_id'],
                        'merchant_fields' => 'transition',
                        'transition' => 'couponxxl_skrill_refund_'.$skrill_refund_uniq,
                        'refund_note' => esc_html__( 'Buyer refund from the offer ', 'couponxxl' ).$payment_data['order_id'],
                        'refund_status_url' => home_url('/').'index.php'
                    ));

                    $post_response = wp_remote_get( $base_url.'?'.$query );
                    if ( is_wp_error( $post_response ) ) {
                        $error_message = $post_response->get_error_message();
                        $response .= couponxxl_wrap_message( esc_html__( 'Something went wrong:', 'couponxxl' ).' - '.$error_message, 'error' );
                    }
                    else{
                        $data = simplexml_load_string( $post_response['body'] );
                        if( empty( $data->error ) ){
                            $post_response = wp_remote_get( $base_url.'?action=refund&sid='.$data->sid );
                            $data = simplexml_load_string( $post_response['body'] );
                            if( empty( $data->error ) ){
                                $data[$payment_data['order_id']] = array(
                                    'ids' => $payment_data['order_item_ids'],
                                    'amount' => $payment_data['amount']
                                );
                                $response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Refund Sent', 'couponxxl' ), 'updated' );
                            }
                            else{
                                $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data->error->error_msg, 'error' );
                            }
                        }
                        else{
                            $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data->error->error_msg, 'error' );
                        }               
                    }               
                }
                set_transient( 'couponxxl_process_refund', $response );
                if( !empty( $data ) ){
                    couponxxl_process_refund( $data );
                }
            }
        }
        echo  $response;
    }

    public function payout_field( $seller_payout_account, $seller_payout_method ){
        $class = '';
        if( $seller_payout_method != $this->slug ){
            $seller_payout_account = '';
            $class = 'hidden';
        }
        ?>
        <div class="payout-connect payout-<?php echo esc_attr( $this->slug ) ?> <?php echo esc_attr( $class ) ?>">
            <div class="input-group">
                <label for="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>"><?php esc_html_e( 'Skrill Email', 'couponxxl' )?></label>
                <input type="text" name="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>" id="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>" class="form-control" value="<?php echo esc_attr( $seller_payout_account ) ?>">
                <i class="pline-lock-locked"></i>
                <p class="description"><?php esc_html_e( 'Input your Skrill email address.', 'couponxxl' ); ?></p>
            </div>
        </div>
        <?php
    }    
}
$cxxl_skrill = new CouponXXL_Skrill();
?>