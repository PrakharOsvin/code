<?php
class CouponXXL_PayUMoney{
    
    public $slug = 'payumoney';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
        add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'append_scripts' ) );
        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );

        add_action( 'couponxxl_process_response', array( $this, 'process_response' ) );

        add_action('wp_ajax_payumoney_additional', array( $this, 'process_additional' ) );
        add_action('wp_ajax_nopriv_payumoney_additional', array( $this, 'process_additional' ) );        
	}

    public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $payumoney_merchant_key = couponxxl_get_option( 'payumoney_merchant_key' );
        if( !empty( $payumoney_merchant_key ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'PayUMoney', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => false,
                'has_payout' => false
            );
        }
    }

    public function theme_options( &$sections ){
        // iDEAL API //
        $sections[] = array(
            'title' => esc_html__('PayUMoney API', 'couponxxl'),
            'desc' => esc_html__('Important PayUMoney Money Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
            'fields' => array(
                array(
                    'id' => 'payumoney_merchant_key',
                    'type' => 'text',
                    'title' => esc_html__('Merchant Key', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your merchant key to connect to PayUMoney', 'couponxxl')
                ),
                array(
                    'id' => 'payumoney_merchant_salt',
                    'type' => 'text',
                    'title' => esc_html__('Merchant Salt', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your merchant salt to connect to PayUMoney', 'couponxxl')
                ),
                array(
                    'id' => 'payumoney_mode',
                    'type' => 'select',
                    'title' => esc_html__('PayUMoney Mode', 'couponxxl'),
                    'compiler' => 'true',
                    'options' => array(
                        'secure' => esc_html__( 'Live Mode', 'couponxxl' ),
                        'test' => esc_html__( 'Test Mode', 'couponxxl' )
                    ),
                    'desc' => esc_html__('Select PayUMoney mode', 'couponxxl'),
                    'default' => 'secure'
                ),                    
            )
        );
    }    

    public function process_additional(){
	    $buyer_id = get_current_user_id();
        $payumoney_transient = $_POST['payumoney_transient'];
        $transient_data = maybe_unserialize( get_transient( $payumoney_transient ) );
        if( !empty( $transient_data ) ){   
            extract( $transient_data );

            if( isset( $_POST['payumoney_name'] ) ){
                $first_name = $_POST['payumoney_name'];
                update_user_meta( $buyer_id, 'first_name', $first_name );
            }
            if( isset( $_POST['payumoney_phone'] ) ){
                $phone_number = $_POST['payumoney_phone'];
                update_user_meta( $buyer_id, 'phone_number', $phone_number );
            }

            $this->html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $this->slug, $first_name, $phone_number );
        }
    }

    public function append_scripts(){
        wp_enqueue_script( 'couponxxl-payumoney', get_template_directory_uri() . '/js/gateways/payumoney.js', false, false, true );
    }

    public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway, $first_name = '', $phone_number = '' ){
        if( $gateway == $this->slug ){

            if( empty( $first_name ) ){
                $first_name = get_user_meta( $buyer_id, 'first_name', true );
            }
            if( empty( $phone_number ) ){
                $phone_number = get_user_meta( $buyer_id, 'phone_number', true );
            }
          
            if( empty( $first_name ) || empty( $phone_number ) ){

                $payumoney_transient = 'payumoney_add_'.$buyer_id;

                set_transient( $payumoney_transient, array(
                    'amount' => number_format_i18n($amount, 2),
                    'title' => $title,
                    'desc' => $desc,
                    'main_unit_abbr' => $main_unit_abbr,
                    'uniq' => uniqid( true ),
                    'permalink' => $permalink,
                    'transient' => $transient,
                    'first_name' => $first_name,
                    'phone_number' => $phone_number,
                    'buyer_id' => $buyer_id
                )); 

                $return = '
                    <div class="payumoney-wrap">
                        <form class="payu-form payu-additional">
                            '.( empty( $first_name ) ? '
                                <div class="input-group">
                                    <label for="payumoney_name">'.esc_html__( 'FIRST NAME', 'couponxxl' ).' <span class="required">*</span></label>
                                    <input type="text" id="payumoney_name" name="payumoney_name" class="form-control">
                                </div>' 
                                : '' 
                            ).'
                            '.( empty( $phone_number ) ? '
                                <div class="input-group">
                                    <label for="payumoney_phone">'.esc_html__( 'PHONE NUMBER', 'couponxxl' ).' <span class="required">*</span></label>
                                    <input type="text" id="payumoney_phone" name="payumoney_phone" class="form-control">
                                </div>' 
                                : '' 
                            ).'
                            <input type="hidden" name="payumoney_transient" value="'.esc_attr( $payumoney_transient ).'">
                            <input type="hidden" name="action" value="payumoney_additional" />
                            <a href="javascript:;" class="payu-additional-info btn">'.esc_html__( 'Process', 'couponxxl' ).'</a>
                        </form>
                    </div>
                ';
            }
            else{
                $current_user = wp_get_current_user();
                $payumoney_merchant_key = couponxxl_get_option( 'payumoney_merchant_key' );
                $payumoney_merchant_salt = couponxxl_get_option( 'payumoney_merchant_salt' );

                $surl = add_query_arg( array( 'gateway' => $this->slug, 'return' => 'yes', 'transient' => $transient ), $permalink );
                $furl = add_query_arg( array( 'gateway' => $this->slug, 'failure' => 'yes', 'transient' => $transient ), $permalink );

                $values = array(
                    'key' => $payumoney_merchant_key,
                    'txnid' => substr(hash('sha256', mt_rand() . microtime()), 0, 20),
                    'amount' => $amount,
                    'productinfo' => $title,
                    'firstname' => $first_name,
                    'email' => $current_user->user_email
                );

                $values['productinfo'] = str_replace( '%', '', $values['productinfo'] );

                $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';  
                foreach($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($values[$hash_var]) ? $values[$hash_var] : '';
                    $hash_string .= '|';
                }
                $hash_string .= $payumoney_merchant_salt;

                $hash = strtolower(hash('sha512', $hash_string));

                $return = '
                    <div class="payumoney-wrap">
                        <form class="payu-form payu-submit-click" action="https://'.( couponxxl_get_option( 'payumoney_mode' ) ).'.payu.in/_payment" method="post">
                            <input type="hidden" name="amount" value="'.esc_attr( $values['amount'] ).'" />
                            <input type="hidden" name="productinfo" value="'.$values['productinfo'].'" />
                            <input type="hidden" name="firstname" value="'.esc_attr( $values['firstname'] ).'" />
                            <input type="hidden" name="email" value="'.esc_attr( $values['email'] ).'" />
                            <input type="hidden" name="surl" value="'.esc_url( $surl ).'" />
                            <input type="hidden" name="furl" value="'.esc_url( $furl ).'" />  
                            <input type="hidden" name="service_provider" value="payu_paisa" />  
                            <input type="hidden" name="key" value="'.esc_attr( $payumoney_merchant_key ).'" />
                            <input type="hidden" name="hash" value="'.$hash.'"/>
                            <input type="hidden" name="txnid" value="'.esc_attr( $values['txnid'] ).'" />
                            <input type="hidden" name="phone" value="'.esc_attr( $phone_number ).'" />
                        </form>
                    </div>
                ';
            }

            echo  $return;
        }
    }

    public function process_response( $gateway ){
        if( $gateway == $this->slug ){
            $status = $_POST["status"];
            $firstname = $_POST["firstname"];
            $amount = $_POST["amount"];
            $txnid = $_POST["txnid"];
            $posted_hash = $_POST["hash"];
            $key = $_POST["key"];
            $productinfo = $_POST["productinfo"];
            $email = $_POST["email"];
            $salt = couponxxl_get_option( 'payumoney_merchant_salt' );
            $transient = $_GET["transient"];

            $transient_data = maybe_unserialize( get_transient( $transient ) );

            if( !empty( $transient_data ) ){

                $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
                $hash = hash( "sha512", $retHashSeq );

                if ( $hash != $posted_hash ) {
                    echo '<div class="alert alert-danger">'.esc_html__( 'Invalid Transaction. Please try again', 'couponxxl' ).'</strong></div>';
                }
                else {
                    delete_transient( 'payumoney_add_'.get_current_user_id() );
                    echo couponxxl_process_payment_details( $transient, $transient_data );
                }
            }
            else{
                echo '<div class="alert alert-danger">'.esc_html__( 'Offer is no longer available', 'couponxxl' ).'</strong></div>';
            }
        }      
    }
}
$cxxl_payumoney = new CouponXXL_PayUMoney();
?>