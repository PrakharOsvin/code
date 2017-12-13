<?php
class CouponXXL_Stripe{
    
    public $slug = 'stripe';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
		add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'append_scripts' ) );
        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );

        add_action( 'wp_ajax_pay_with_stripe', array( $this, 'process_payment' ) );
        add_action( 'wp_ajax_nopriv_pay_with_stripe', array( $this, 'process_payment' ) );

        add_action( 'couponxxl_process_payout', array( $this, 'process_payout' ) );
        add_action( 'couponxxl_process_refund', array( $this, 'process_refund' ) );

        add_action( 'couponxxl_payout_fields_'.$this->slug, array( $this, 'payout_field' ), 10, 2 );
        add_action( 'couponxxl_deregister_payout_account', array( $this, 'deregister_payout_field' ), 10, 2 );
        add_action( 'init', array( $this, 'register_payout_field' ) );
	}

    public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $pk_client_id = couponxxl_get_option( 'pk_client_id' );
        if( !empty( $pk_client_id ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'Stripe', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => true,
                'has_payout' => true
            );
        }
    }

	public function theme_options( &$sections ){
        // Stripe API //
        $sections[] = array(
            'title' => esc_html__('Stripe API', 'couponxxl'),
            'desc' => esc_html__('Important Stripe Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
            'fields' => array(
                array(
                    'id' => 'pk_client_id',
                    'type' => 'text',
                    'title' => esc_html__('Public Client ID', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your stripe public client ID', 'couponxxl')
                ),
                array(
                    'id' => 'sk_client_id',
                    'type' => 'text',
                    'title' => esc_html__('Secret Client ID', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your stripe secret client ID', 'couponxxl')
                ),
                array(
                    'id' => 'ap_client_id',
                    'type' => 'text',
                    'title' => esc_html__('Application Client ID', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your stripe secret application client ID', 'couponxxl')
                ),

            )
        );
	}


    public function append_scripts( ){
        wp_enqueue_script( 'couponxxl-stripe', 'https://checkout.stripe.com/checkout.js', false, false, true );
        wp_enqueue_script( 'couponxxl-stripe-handle', get_template_directory_uri() . '/js/gateways/stripe.js', false, false, true );
    }

    public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway ){
        if( $gateway == $this->slug ){
            $pk_client_id = couponxxl_get_option( 'pk_client_id' );
            $site_logo = couponxxl_get_option( 'site_logo' );
            $amount = $amount * 100;
            echo '<div class="stripe-payment" data-genearting_string="'.esc_html__( 'Processing payment...', 'couponxxl' ).'" data-pk="'.esc_attr( $pk_client_id ).'" data-transient="'.esc_attr( $transient ).'" data-name="'.esc_attr( $title ).'" data-description="'.esc_attr( $desc ).'" data-amount="'.esc_attr( $amount ).'" data-currency="'.esc_attr( $main_unit_abbr ).'"></div>
                  <div class="stripe-response"></div>';
        }
    }

    public function process_payment(){
        $transient = $_POST['transient'];
        $token = $_POST['token'];
        $transient_data = maybe_unserialize( get_transient( $transient ) );
        if( !empty( $transient_data ) ){

            $response = $this->process_stripe( $transient_data['amount'], $token );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo '<div class="alert alert-danger">'.esc_html__( 'Something went wrong: ', 'couponxxl' ).$error_message.'</div>';
            } 
            else{           
                $data = json_decode( $response['body'], true );
                if( empty( $data['error'] ) ){
                    echo couponxxl_process_payment_details( $transient, $transient_data, array( 'transaction_id' => $data['id'] ) );
                }
                else{
                    echo '<div class="alert alert-danger">'.$data['error_description'].'</div>';
                }
            }
            die();
        }
    }

    public function process_stripe( $amount, $token ){
        $response = wp_remote_post( 'https://api.stripe.com/v1/charges', array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'Authorization' => 'Bearer '.couponxxl_get_option( 'sk_client_id' )
            ),
            'body' => array(
                'amount' => $amount*100,
                'currency' => strtolower( couponxxl_get_option( 'main_unit_abbr' ) ),
                'card' => $token['id'],
                'receipt_email' => $token['email'],
            ),
            'cookies' => array()
        ));

        return $response;
    }

    public function process_payout( $sellers ){
        global $wpdb;
        $response = '';
        $ids = array();
        if( !empty( $sellers['stripe'] ) ){
            foreach( $sellers['stripe'] as $payment_data ){
                $post_response = wp_remote_post( 'https://api.stripe.com/v1/transfers', array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => 'Bearer '.couponxxl_get_option( 'sk_client_id' )
                    ),
                    'body' => array(
                        'amount' => $payment_data['amount']*100,
                        'destination' => $payment_data['seller_payout_account'],
                        'currency' => strtolower( couponxxl_get_option( 'main_unit_abbr' ) )
                    ),
                    'cookies' => array()
                ));

                if ( is_wp_error( $post_response ) ) {
                    $error_message = $post_response->get_error_message();
                    $response .= couponxxl_wrap_message( esc_html__( 'Something went wrong:', 'couponxxl' ).$error_message, 'error' );
                }
                else{
                    $data = json_decode( $post_response['body'], true );
                    if( empty( $data['error'] ) ){  
                        $ids += $payment_data['order_item_ids'];
                        $response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Payment Sent', 'couponxxl' ), 'success' );
                    }
                    else{
                        $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data['error']['message'], 'error' );
                    }               
                }
            } 
            if( !empty( $ids ) ){
                $wpdb->query( "UPDATE {$wpdb->prefix}order_items SET status = 'sent' WHERE item_id IN ( ".esc_sql( join( ',', $ids ) )." )" );
            }
        }       
    }

    public function process_refund( $buyers ){
        global $wpdb;
        $response = '';
        $data = array();
        if( !empty( $buyers['stripe'] ) ){
            foreach( $buyers['stripe'] as $payment_data ){
                $payment_data['transaction_details']['transaction_details'] = maybe_unserialize( $payment_data['transaction_details']['transaction_details'][0] );
                $post_response = wp_remote_post( 'https://api.stripe.com/v1/refunds', array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => 'Bearer '.couponxxl_get_option( 'sk_client_id' )
                    ),
                    'body' => array(
                        'amount' => $payment_data['amount']*100,
                        'charge' => $payment_data['transaction_details']['transaction_details']['transaction_id'],
                        'reason' => esc_html__( 'Buyer refund from the offer', 'couponxxl' ).$payment_data['order_id']
                    ),
                    'cookies' => array()
                ));

                if ( is_wp_error( $post_response ) ) {
                    $error_message = $post_response->get_error_message();
                    $response .= couponxxl_wrap_message( esc_html__( 'Something went wrong:', 'couponxxl' ).$error_message, 'error' );
                }
                else{
                    $data = json_decode( $post_response['body'], true );
                    if( empty( $data['error'] ) ){
                        $data[$payment_data['order_id']] = array(
                            'ids' => $payment_data['order_item_ids'],
                            'amount' => $payment_data['amount']
                        );
                        $response .= couponxxl_wrap_message( $payment_data['user'].' - '.esc_html__( 'Refund Sent', 'couponxxl' ), 'updated' );
                    }
                    else{
                        $response .= couponxxl_wrap_message( $payment_data['user'].' - '.$data['error']['message'], 'error' );
                    }               
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
            $class = 'hidden';
        }        
        $ap_client_id = couponxxl_get_option('ap_client_id');
        ?>
        <div class="payout-connect payout-<?php echo esc_attr( $this->slug ) ?> <?php echo esc_attr( $class ) ?>">
            <div class="input-group">
                <?php if( empty( $seller_payout_account ) ): ?>
                    <a href="https://connect.stripe.com/oauth/authorize?response_type=code&amp;client_id=<?php echo esc_attr( $ap_client_id ); ?>&amp;scope=read_write&amp;redirect_uri=<?php echo urlencode( add_query_arg( array( 'subpage' => 'profile' ), couponxxl_get_permalink_by_tpl( 'page-tpl_profile' ) ) ); ?>" class="btn">
                        <?php esc_html_e( 'Connect Account', 'couponxxl' ); ?>
                    </a>
                    <p class="description"><?php esc_html_e( 'Click on the button and follow the instructions to connect your Stripe account.', 'couponxxl' ); ?></p>
                <?php else: ?>
                    <?php esc_html_e( 'Account Is Connected', 'couponxxl' ); ?>
                <?php endif; ?>            
                <input type="hidden" name="seller_payout_account_<?php echo esc_attr( $this->slug ) ?>" value="<?php echo esc_attr( $seller_payout_account ) ?>">
            </div>
        </div>
        <?php
    }

    public function register_payout_field(){
        if( isset( $_GET['scope'] ) && isset( $_GET['code'] ) ){
            global $current_user;
            $response = wp_remote_post( 'https://connect.stripe.com/oauth/token', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array(
                    'client_secret' => couponxxl_get_option( 'sk_client_id' ),
                    'code' => $_GET['code'],
                    'grant_type' => 'authorization_code'
                ),
                'cookies' => array()
            ));

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                return array( 'error' => "Something went wrong: $error_message" );
            } 
            else{           
                $data = json_decode( $response['body'], true );
                if( empty( $data['error'] ) ){
                    update_user_meta( $current_user->ID, 'seller_payout_method', $this->slug );
                    update_user_meta( $current_user->ID, 'seller_payout_account', $data['stripe_user_id'] );
                    $message = '<div class="alert alert-success">'.esc_html__( 'Your account is connected with Stripe', 'couponxxl' ).'</div>';
                }
                else{
                    $message = '<div class="alert alert-danger">'.$data['error_description'].'</div>';
                }

                set_transient( 'couponxxl_account_connect_message', $message );
            }    
        }
    }

    public function deregister_payout_field( $seller_payout_account, $seller_payout_method ){
        if( $seller_payout_method == $this->slug ){
            $response = wp_remote_post( 'https://connect.stripe.com/oauth/deauthorize', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array(
                    'client_secret' => couponxxl_get_option( 'sk_client_id' ),
                    'client_id' => couponxxl_get_option( 'ap_client_id' ),
                    'stripe_user_id' => $seller_payout_account
                ),
                'cookies' => array()
            ));
        }
    }

}
$cxxl_stripe = new CouponXXL_Stripe();
?>