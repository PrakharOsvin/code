<?php
class CouponXXL_Bank{

    public $slug = 'bank';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
		add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );

        add_action( 'couponxxl_process_response', array( $this, 'process_response' ) );

        add_action('wp_ajax_process-credits', array( $this, 'process_bank_credits_manually' ));
	}

	public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $bank_name = couponxxl_get_option( 'bank_name' );
        if( !empty( $bank_name ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'Bank', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => false,
                'has_payout' => false
            );
        }
	}

	public function theme_options( &$sections ){
        // PayPal API //
        $sections[] = array(
            'title' => esc_html__('Bank Data', 'couponxxl'),
            'desc' => esc_html__('Important Bank Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'bank_account_name',
                        'type' => 'text',
                        'title' => esc_html__('Bank Account Name', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your bank account name.', 'couponxxl')
                    ),                    
                    array(
                        'id' => 'bank_name',
                        'type' => 'text',
                        'title' => esc_html__('Bank Name', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your bank name.', 'couponxxl')
                    ),
                    array(
                        'id' => 'bank_account_number',
                        'type' => 'text',
                        'title' => esc_html__('Bank Account Number', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your bank account number.', 'couponxxl')
                    ),
                    array(
                        'id' => 'bank_sort_number',
                        'type' => 'text',
                        'title' => esc_html__('Sort Number', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your sort number.', 'couponxxl')
                    ),
                    array(
                        'id' => 'bank_iban_number',
                        'type' => 'text',
                        'title' => esc_html__('IBAN Code', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your IBAN code.', 'couponxxl')
                    ),
                    array(
                        'id' => 'bank_bic_swift_number',
                        'type' => 'text',
                        'title' => esc_html__('BIC / Swift Code', 'couponxxl'),
                        'compiler' => 'true',
                        'desc' => esc_html__('Input your BIC / Swift code.', 'couponxxl')
                    ),                    
                )
        );
	}

    public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway ){
        if( $gateway == $this->slug ){
            wp_redirect( add_query_arg( array( 'gateway' => $this->slug, 'transient' => $transient ), $permalink ) );
        }
    }

    public function process_response( $gateway ){
        if( $gateway == 'bank' ){
            $transient = $_GET['transient'];
            $transient_value = maybe_unserialize( get_transient( $transient ) );
            if( !empty( $transient_value ) ){
                $bank_account_name = couponxxl_get_option( 'bank_account_name' );
                $bank_name = couponxxl_get_option( 'bank_name' );
                $bank_account_number = couponxxl_get_option( 'bank_account_number' );
                $bank_sort_number = couponxxl_get_option( 'bank_sort_number' );
                $bank_iban_number = couponxxl_get_option( 'bank_iban_number' );
                $bank_bic_swift_number = couponxxl_get_option( 'bank_bic_swift_number' );
                echo '<div class="white-block">
                    <div class="white-block-content">
                        '.esc_html__( 'Make your payment directly into our bank account. Please use \'Coupon And Deal Credits\' or Purchase ID as the payment reference. Your order won’t be processed until the funds have cleared in our account.', 'couponxxl' ).'
                        <h4>'.esc_html__( 'Our Bank Details', 'couponxxl' ).'</h4>
                        '.$bank_account_name.' - '.$bank_name.'
                        <ul class="list-unstyled list-inline">
                            <li>
                                '.esc_html__( 'ACCOUNT NUMBER', 'couponxxl' ).':
                                '.$bank_account_number.'
                            </li>
                            <li>
                                '.esc_html__( 'SORT CODE', 'couponxxl' ).':
                                '.$bank_sort_number.'
                            </li>
                            <li>
                                '.esc_html__( 'IBAN', 'couponxxl' ).':
                                '.$bank_iban_number.'
                            </li>
                            <li>
                                '.esc_html__( 'BIC', 'couponxxl' ).':
                                '.$bank_bic_swift_number.'
                            </li>
                        </ul>
                    </div>
                </div>';

                if( $transient_value['purchase'] == 'credits' ){
                    update_user_meta( $transient_value['buyer_id'], 'cxxl_credits_manual', $transient_value );
                }
                else{
                    echo couponxxl_process_payment_details( $transient, $transient_value, array(), 'pending_payment' );
                }
            }          
        }
    }

    public function process_bank_credits_manually(){
        $user_id = $_POST['user_id'];
        $transient_value = get_user_meta( $user_id, 'cxxl_credits_manual', true );

        couponxxl_process_payment_details( '', $transient_value );
    }    
}
$cxxl_bank = new CouponXXL_Bank();
?>