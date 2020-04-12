<?php
/*
Plugin Name: Jambopay WooCommerce Payment Gateway
Description: Extends WooCommerce with Jambopay gateway.
Version: 2.6.1.0
Author: Jeremy Murimi
Author URI:https://www.facebook.com/wordpressexpertskenya


Jambopay WooCommerce Payment Gateway
Copyright (C) 2013-2014  

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

add_action('plugins_loaded', 'woocommerce_jambopay_init', 0);

function woocommerce_jambopay_init() {

    if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

    /**
     * Localisation
     */
    load_plugin_textdomain('wc-jambo-pay', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

    /**
     * Gateway class
     */
    class WC_Jambopay extends WC_Payment_Gateway {
	protected $msg = array();
        public function __construct(){
            // Go wild in here
			 $this->return_url = str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'WC_Jambopay', home_url( '/' ) ) );
            $this -> id = 'jambopay';
           $this -> method_title = __('Jambopay', 'jambopay');
            $this -> icon = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/images/jambopay.jpg';
            $this -> has_fields = false;
            $this -> init_form_fields();
            $this -> init_settings();
            $this -> title = $this -> settings['title'];
            $this -> description = $this -> settings['description'];
            $this -> merchant_id = $this -> settings['merchant_id'];
			 $this -> merchant_name = $this -> settings['merchant_name'];
            $this -> working_key = $this -> settings['working_key'];
            $this -> redirect_page_id = $this -> settings['redirect_page_id'];
            $this -> msg['message'] = "";
            $this -> msg['class'] = "";
            add_action('woocommerce_api_wc_jambopay', array($this, 'check_jambopay_response'));
                      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
             } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
            }
            add_action('woocommerce_receipt_jambopay', array($this , 'receipt_page'));
			
           
        }

        function init_form_fields(){

            $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'jambopay'),
                    'type' => 'checkbox',
                    'label' => __('Enable Jambopay Payment Module.', 'jambopay'),
                    'default' => 'yes'),
                'title' => array(
                    'title' => __('Title:', 'jambopay'),
                    'type'=> 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'jambopay'),
                    'default' => __('Jambopay', 'jambopay')),
                'description' => array(
                    'title' => __('Description:', 'jambopay'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'jambopay'),
                    'default' => __('Pay securely by Credit,Debit card, MPESA,Yucash,Kenswitch through Jambopay Secure Servers.', 'jambopay')),
                'merchant_id' => array(
                    'title' => __('Merchant ID', 'email@yourshop.com'),
                    'type' => 'text',
                    'description' => __('This id [jp_business] at Jambopay')),
				'merchant_name' => array(
                    'title' => __('Merchant Name', 'yourshop'),
                    'type' => 'text',
                    'description' => __('This is Shopname[jp_item_name]')),
                'working_key' => array(
                    'title' => __('Shared Key', 'jambopay'),
                    'type' => 'text',
                    'description' =>  __('Given to Merchant by Jambopay', 'jambopay'),
                ),
                'redirect_page_id' => array(
                    'title' => __('Return Page'),
                    'type' => 'select',
                    'options' => $this -> get_pages('Select Page'),
                    'description' => "URL of success page"
                )
            );


        }
        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         **/
        public function admin_options(){
            echo '<h3>'.__('Jambopay Payment Gateway', 'jambopay').'</h3>';
            echo '<p>'.__('Jambopay is most popular payment gateway for online shopping in Kenya').'</p>';
            echo '<table class="form-table">';
            $this -> generate_settings_html();
            echo '</table>';

        }
        /**
         *  There are no payment fields for Jambopay, but we want to show the description if set.
         **/
        function payment_fields(){
            if($this -> description) echo wpautop(wptexturize($this -> description));
        }
        /**
         * Receipt Page
         **/
        function receipt_page($order){
            echo '<p>'.__('Thank you for your order, please click the button below to pay with Jambopay.', 'jambopay').'</p>';
            echo $this -> generate_jambopay_form($order);
        }
        /**
         * Process the payment and return the result
         **/
        function process_payment($order_id){
            $order =  new WC_Order($order_id);
            return array('result' => 'success', 'redirect' => add_query_arg('order',
                $order->id, add_query_arg('key', $order->order_key, get_permalink(get_option('woocommerce_pay_page_id'))))
            );
        }
        /**
         * Check for valid Jambopay server callback
         **/
        function check_jambopay_response(){
            global $woocommerce;
			//************************ CHECK IF VALUES HAVE BEEN SET *****************
        if (isset($_POST['JP_PASSWORD'])&&isset($_POST['JP_MERCHANT_ORDERID'])) {

            $JP_TRANID = $_POST['JP_TRANID'];
            $JP_MERCHANT_ORDERID = $_POST['JP_MERCHANT_ORDERID'];
            $JP_ITEM_NAME = $_POST['JP_ITEM_NAME'];
            $JP_AMOUNT = $_POST['JP_AMOUNT'];
            $JP_CURRENCY = $_POST['JP_CURRENCY'];
            $JP_TIMESTAMP = $_POST['JP_TIMESTAMP'];
            $JP_PASSWORD = $_POST['JP_PASSWORD'];
            $JP_CHANNEL = $_POST['JP_CHANNEL'];

//$sharedkey, IS ONLY SHARED BETWEEN THE MERCHANT AND JAMBOPAY. THE KEY SHOULD BE SECRET ********************

						$order_id=$_POST['JP_MERCHANT_ORDERID'];
                        $order = new WC_Order((int)$order_id);

//Make sure you get the key from JamboPay Support team
            $sharedkey = $this->working_key ;

            $str = $JP_MERCHANT_ORDERID . $JP_AMOUNT . $JP_CURRENCY . $sharedkey . $JP_TIMESTAMP;


//**************** VERIFY *************************
            if (md5(utf8_encode($str)) == $JP_PASSWORD) {

                //VALID
                //if valid, mark order as paid
						$order->add_order_note( __( 'Jambopay txID:'. $JP_TRANID.' Channel:'.$JP_CHANNEL, 'woocommerce' ) );
						$order->payment_complete();
						$woocommerce->cart->empty_cart();
				        

            } else{
			
					$order->add_order_note( __( 'Jambopay payment failed txID:'.$JP_TRANID, 'woocommerce' ) );
                	$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce' ), strtolower( $posted['payment_status'] ) ) );
        }
			 
					wp_safe_redirect(add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks')))));
					 
			}
				}
		



        /**
         * Generate Jambopay button link
         **/
        public function generate_jambopay_form($order_id){
             global $woocommerce;
            $order = new  WC_Order($order_id);
        
           return '<form action="https://www.jambopay.com/JPExpress.aspx" method="post" id="ccavenue_payment_form">
           
                <input type="submit" class="button-alt" id="submit_ccavenue_payment_form" value="'.__('Pay via Jambopay', 'jambopay').'" /> <a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Cancel order &amp; restore cart', 'jambopay').'</a>
				<input type="hidden" name="jp_item_type" value="cart"/>
<input type="hidden" name="jp_item_name" value="'.$this -> merchant_name.'"/>
<input type="hidden" name="order_id" value="'.$order_id.'"/>
<input type="hidden" name="jp_business" value="'.$this -> merchant_id.'"/>
<input type="hidden" name="jp_amount_1" value="'.$order->order_total.'"/>
<input type="hidden" name="jp_amount_2" value="0"/>
<input type="hidden" name="jp_amount_5" value="0"/>
<input type="hidden" name="jp_payee" value="'.$order->billing_email.'"/>
<input type="hidden" name="jp_shipping" value="company name"/>
<input type="hidden" name="jp_rurl" value="'.$this->return_url .'"/>
<input type="hidden" name="jp_furl" value="'.$this->return_url .'"/>
<input type="hidden" name="jp_curl" value="'.$order->get_cancel_order_url().'"/>
<input type="image" src="https://www.jambopay.com/jambohelp/jambo/rsc/checkout.png"/>
                <script type="text/javascript">
jQuery(function(){
    jQuery("body").block(
            {
                message: "<img src=\"'.$woocommerce->plugin_url().'/assets/images/ajax-loader.gif\" alt=\"Redirecting…\" style=\"float:left; margin-right: 10px;\" />'.__('Thank you for your order. We are now redirecting you to Jambopay to make payment.', 'jambopay').'",
                    overlayCSS:
            {
                background: "#fff",
                    opacity: 0.6
        },
        css: {
            padding:        20,
                textAlign:      "center",
                color:          "#555",
                border:         "3px solid #aaa",
                backgroundColor:"#fff",
                cursor:         "wait",
                lineHeight:"32px"
        }
        });
        jQuery("#submit_ccavenue_payment_form").click();

        });
                    </script>
                </form>';

        }


        /**
         *  Jambopay Essential Functions
         **/
       
        /*
         * End Jambopay Essential Functions
         **/
        // get all pages
        function get_pages($title = false, $indent = true) {
            $wp_pages = get_pages('sort_column=menu_order');
            $page_list = array();
            if ($title) $page_list[] = $title;
            foreach ($wp_pages as $page) {
                $prefix = '';
                // show indented child pages?
                if ($indent) {
                    $has_parent = $page->post_parent;
                    while($has_parent) {
                        $prefix .=  ' - ';
                        $next_page = get_page($has_parent);
                        $has_parent = $next_page->post_parent;
                    }
                }
                // add to page list array array
                $page_list[$page->ID] = $prefix . $page->post_title;
            }
            return $page_list;
        }

    }
	

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_jambopay_gateway($methods) {
        $methods[] = 'WC_Jambopay';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_jambopay_gateway' );
}

?>
