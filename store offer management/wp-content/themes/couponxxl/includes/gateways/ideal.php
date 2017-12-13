<?php
class CouponXXL_iDEAL{
    
    public $slug = 'ideal';

	public function __construct(){
        add_action( 'init', array( $this, 'check_gateway' ), 0 );
        add_action( 'couponxxl_add_options', array( $this, 'theme_options' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'append_scripts' ) );
        add_action( 'couponxxl_generate_payments', array( $this, 'html' ), 10, 7 );
        add_action( 'wp_ajax_ideal_bank_link', array( $this, 'fetch_bank_url' ) );
        add_action( 'wp_ajax_nopriv_ideal_bank_link', array( $this, 'fetch_bank_url' ) );

        add_action( 'couponxxl_process_response', array( $this, 'process_response' ) );
        add_action( 'couponxxl_process_verify', array( $this, 'verify_ideal' ) );
	}

    public function check_gateway(){
        global $COUPONXXL_GATEWAYS;
        $mollie_id = couponxxl_get_option( 'mollie_id' );
        if( !empty( $mollie_id ) ){
            $COUPONXXL_GATEWAYS[$this->slug] = array(
                'name' => esc_html__( 'iDEAL', 'couponxxl' ),
                'slug' => $this->slug,
                'has_refund' => false,
                'has_payout' => false
            );
        }
    }

	public function theme_options( &$sections ){
        $sections[] = array(
            'title' => esc_html__('iDEAL API', 'couponxxl'),
            'desc' => esc_html__('Important iDEAL Settings.', 'couponxxl'),
            'icon' => '',
            'subsection' => true,
            'fields' => array(
                array(
                    'id' => 'mollie_id',
                    'type' => 'text',
                    'title' => esc_html__('Mollie ID', 'couponxxl'),
                    'compiler' => 'true',
                    'desc' => esc_html__('Input your mollie ID to connect to iDEAL', 'couponxxl')
                ),
                array(
                    'id' => 'ideal_mode',
                    'type' => 'select',
                    'title' => esc_html__('iDEAL Model', 'couponxxl'),
                    'compiler' => 'true',
                    'options' => array(
                        'live' => esc_html__( 'Live Mode', 'couponxxl' ),
                        'test' => esc_html__( 'Test Mode', 'couponxxl' )
                    ),
                    'desc' => esc_html__('Select iDEAL mode', 'couponxxl'),
                    'default' => 'live'
                ),
            )
        );
	}

    public function append_scripts(){
        wp_enqueue_script( 'couponxxl-ideal', get_template_directory_uri() . '/js/gateways/ideal.js', false, false, true );
    }     

    public function html( $amount, $title, $desc, $main_unit_abbr, $permalink, $transient, $gateway ){
        if( $gateway == $this->slug ){
            $return_url = add_query_arg( array( 'gateway' => $this->slug, 'return' => 'yes', 'transient' => $transient ), $permalink );
            $status_url = add_query_arg( array( 'gateway' => $this->slug, 'status' => 'yes', 'transient' => $transient ), home_url('/') );

            $mollie_id = couponxxl_get_option( 'mollie_id' );
            $iDEAL = new Couponxxl_Mollie_iDEAL_Payment ( $mollie_id );
            if( couponxxl_get_option( 'ideal_mode' ) == 'test' ){
                $iDEAL->setTestmode(true);
            }
            $form = '';
            set_transient( 'cxxl_ideal_transient_'.get_current_user_id(), array(
                'amount' => $amount,
                'return_url' => $return_url,
                'status_url' => $status_url,
                'title' => $title,
            ));
            $bank_array = $iDEAL->getBanks();
            if( $bank_array ){
                $form = '<form method="post" class="ideal-payment">
                            <select name="bank_id">
                                <option value="">'.esc_html__( 'Select Your Bank', 'couponxxl' ).'</option>';
                                foreach( $bank_array as $bank_id => $bank_name ){
                                    $form .= '<option value="'.esc_attr( $bank_id ).'">'.$bank_name.'</option>';
                                }
                $form .= '  </select>
                            <input type="hidden" name="action" value="ideal_bank_link"/>
                            <a href="javascript:;" class="ideal_bank btn">'.esc_html__( 'Proceed', 'couponxxl' ).'</a>
                            <div class="ajax-response"></div>
                        </form>';
            }

            echo  $form;
        }
    }

    public function fetch_bank_url(){
        $bank_id = $_POST['bank_id'];

        $transient_data = maybe_unserialize( get_transient( 'cxxl_ideal_transient_'.get_current_user_id() ) );

        if( !empty( $transient_data ) ){
            $mollie_id = couponxxl_get_option( 'mollie_id' );
            $iDEAL = new Couponxxl_Mollie_iDEAL_Payment ( $mollie_id );
            if( couponxxl_get_option( 'ideal_mode' ) == 'test' ){
                $iDEAL->setTestmode(true);
            }               

            $payment = $iDEAL->createPayment( $bank_id, $transient_data['amount']*100,  $transient_data['title'], $transient_data['return_url'], $transient_data['status_url'] );

            if( $payment ){
                echo  $iDEAL->getBankURL();
            }
            else{
                echo '<div class="alert alert-danger no-margin">'.esc_html__( 'Could not retrive bank URL', 'couponxxl' ).' '.$iDEAL->getErrorMessage().'</div>';
            }

        }
        else{
            echo '<div class="alert alert-danger no-margin">'.esc_html__( 'Order expired', 'couponxxl' ).'</div>';
        }
        die();
    } 

    public function process_response( $gateway ){
        if( $gateway == $this->slug ){
            if( !empty( $_GET['return'] ) ){
                $transient = $_GET['transient'];
                $transient_data = maybe_unserialize( get_transient( $transient ) );
                if( !empty( $transient_data ) ){
                    if( $transient_data['purchase'] == 'order' ){
                        $order_status = get_post_meta( $transient_data['order_id'], 'order_status', true );
                        if( $order_status == 'not_paid' ){
                            update_post_meta( $transient_data['order_id'], 'order_status', 'pending_payment' );
                        }
                    }
                    else if( $transient_data['purchase'] == 'credits' ){
                        echo '<div class="alert alert-info">'.esc_html__( 'Thank you for your purchase.<br />Once iDEAL confirms payment you will receive your credits to your account ', 'couponxxl').'</div>';
                    }
                }
            }
            echo '<div class="alert alert-info">'.esc_html__( 'Thank you for your purchase.<br />Once iDEAL confirms payment your order will be processed', 'couponxxl').'</div>';
            delete_transient( 'cxxl_ideal_transient_'.get_current_user_id() );
        }
    }

    public function verify_ideal( $gateway ){
        if( $gateway == $this->slug && !empty( $_GET['status'] ) ){
            $transient = $_GET['transient'];
            $transient_data = maybe_unserialize( get_transient( $transient ) );
            if( !empty( $transient_data ) ){
                $mollie_id = couponxxl_get_option( 'mollie_id' );
                $iDEAL = new Couponxxl_Mollie_iDEAL_Payment( $mollie_id );
                if( couponxxl_get_option( 'ideal_mode' ) == 'test' ){
                    $iDEAL->setTestmode(true);
                }
                $iDEAL->checkPayment($_GET['transaction_id']);
                if ( $iDEAL->getPaidStatus() ){
                    couponxxl_process_payment_details( $transient, $transient_data );
                }
            }
        }
    }
}
$cxxl_ideal = new CouponXXL_iDEAL();
?>