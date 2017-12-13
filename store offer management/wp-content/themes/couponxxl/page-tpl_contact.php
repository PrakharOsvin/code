<?php
/*
	Template Name: Contact Page
*/
get_header();
the_post();
get_template_part( 'includes/title' );
?>
<section>
    <div class="container">
        <div class="row">
<!--
            <div class="col-md-8">
                <?php
                $contact_map = couponxxl_get_option( 'contact_map' );
                if( !empty( $contact_map[0] ) ){
                    echo '<div class="contact_map">';
                        foreach( $contact_map as $long_lat ){
                            echo '<input type="hidden" value="'.esc_attr( $long_lat ).'" class="contact_map_marker">';
                        }
                        $contact_map_scroll_zoom = couponxxl_get_option( 'contact_map_scroll_zoom' );
                        if( $contact_map_scroll_zoom == 'yes' ){
                            echo '<input type="hidden" value="1" class="contact_map_scroll_zoom">';
                        }
                        ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <div id="map" class="embed-responsive-item"></div>
                        </div>                        
                        <?php
                    echo '</div>';
                }
                ?>    
            </div>
-->
            <div class="col-md-12">
                <div class="widget white-block">
                    <div class="contact-widget-wrap">
                        <h6><?php esc_html_e( 'Additional Info', 'couponxxl' ) ?></h6>

                        <?php the_content() ?>
                        
                        <?php
                        $contact_address = couponxxl_get_option( 'contact_address' );
                        if( !empty( $contact_address ) ){
                            echo '<p><i class="pline-compass"></i> '.$contact_address.'</p>';
                        }
                        ?>

                        <?php
                        $contact_phone = couponxxl_get_option( 'contact_phone' );
                        if( !empty( $contact_phone ) ){
                            echo '<p><i class="pline-phone-hang"></i> '.$contact_phone.'</p>';
                        }
                        ?>

                        <?php
                        $contact_link = couponxxl_get_option( 'contact_link' );
                        if( !empty( $contact_link ) ){
                            echo '<p><i class="pline-chain-alt"></i> <a href="'.esc_url( $contact_link ).'" target="_blank">'.$contact_link.'</a></p>';
                        }
                        ?>

                        <?php
                        $contact_email = couponxxl_get_option( 'contact_email' );
                        if( !empty( $contact_email ) ){
                            echo '<p><i class="pline-envelope"></i> <a href="mailto:'.esc_attr( $contact_email ).'" target="_blank">'.$contact_email.'</a></p>';
                        }
                        ?>
                    </div>

                    <div class="contact-widget-wrap">
                        <?php
                        $contact_facebook = couponxxl_get_option( 'contact_facebook' );
                        $contact_google = couponxxl_get_option( 'contact_google' );
                        $contact_twitter = couponxxl_get_option( 'contact_twitter' );
                        $contact_rss = couponxxl_get_option( 'contact_rss' );
                        if( !empty( $contact_facebook ) || !empty( $contact_google ) || !empty( $contact_twitter ) || !empty( $contact_rss ) ){
                            ?>
                            <h6><?php esc_html_e( 'Follow Us', 'couponxxl' ) ?></h6>
                            <ul class="list-inline list-unstyled store-social-networks">
                                <?php
                                if( !empty( $contact_facebook ) ){
                                    echo '<li><a href="'.esc_url( $contact_facebook ).'" target="_blank"><i class="fa fa-facebook-square"></i></a></li>';
                                }
                                ?>
                                <?php
                                if( !empty( $contact_google ) ){
                                    echo '<li><a href="'.esc_url( $contact_google ).'" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>';
                                }
                                ?>
                                <?php
                                if( !empty( $contact_twitter ) ){
                                    echo '<li><a href="'.esc_url( $contact_twitter ).'" target="_blank"><i class="fa fa-twitter-square"></i></a></li>';
                                }
                                ?>
                                <?php
                                if( !empty( $contact_rss ) ){
                                    echo '<li><a href="'.esc_url( $contact_rss ).'" target="_blank"><i class="fa fa-rss-square"></i></a></li>';
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>

                </div>
            </div>
            <div class="col-sm-12">
                <div class="white-block">
                    <div class="white-block-content">
                        <h5><?php esc_html_e( 'Send a message', 'couponxxl' ) ?></h5>
                        <form>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <label for="name"><?php esc_html_e( 'Name', 'couponxxl' ) ?><span class="required">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name">
                                        <i class="pline-user"></i>
                                    </div>
                                    <div class="input-group">
                                        <label for="contact-email"><?php esc_html_e( 'Email', 'couponxxl' ) ?><span class="required">*</span></label>
                                        <input type="text" class="form-control" name="email" id="contact-email">
                                        <i class="pline-envelope"></i>
                                    </div>
                                    <div class="input-group">
                                        <label for="subject"><?php esc_html_e( 'Subject', 'couponxxl' ) ?><span class="required">*</span></label>
                                        <input type="text" class="form-control" name="subject" id="subject">
                                        <i class="pline-chain"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <label for="message"><?php esc_html_e( 'Message', 'couponxxl' ) ?><span class="required">*</span></label>
                                        <textarea class="form-control" name="message" id="message"></textarea>
                                        <i class="pline-notes"></i>
                                    </div>
                                    <input type="checkbox" name="captcha" id="captcha" class="hidden">
                                    <input type="hidden" name="action" value="contact">
                                    <a class="btn submit-form-contact" href="javascript:;"><?php esc_html_e( 'Send Message', 'couponxxl' ); ?></a>
                                    <div class="send_result"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>