<?php
/*
Plugin Name: PayPalPro Direct Payments
Plugin URI: http://pablo.lnxsoluciones.com/
Description: This plugin alow you to do payments from your website to you PaypalPro account directly without leaving your site. HTTPS recommended.
Version: 1.0
Author: Pablo Castillo
Author URI: http://pablo.lnxsoluciones.com/
*/

define('CARRITO_VERSION', '1.0');


function carrito_init() {
	@session_start(); // WE ACTIVATE SESSION SINCE THERE WE WILL SAVE THE PRODUCTS SELECTED BY THE BUYER
	add_action('admin_menu', 'carrito_config_page');
	add_action('admin_menu', 'carrito_stats_page');
}
add_action('init', 'carrito_init'); // WE START THE PLUGIN



function carrito_config_page() { // ADD A NEW ITEM TO THE PLUGINS LIST ON THE LEFT. FROM HERE WE WILL ACCCESS THE CONFIGURATION AND OPTIONS
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('PayPal Shop'), __('PayPal Shop'), 'manage_options', 'carrito-config', 'carrito_conf');
	}
}

function carrito_stats_page() { // ADD A NEW ITEM TO THE PLUGINS LIST ON THE LEFT. NOT CODED YET
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('PayPal Shop Stats'), __('PayPal Shop Stats'), 'manage_options', 'carrito-stats', 'carrito_stats');
	}
}

function carrito_conf() {

    /**
        HERE WE WILL SAVE THE PAYPAL USER OPTIONS TO CONNECT AND AUTENTICATE AGAINST PAYPAL
        YOU MUST GET THE SIGNATURE KEY, THE USERNAME AND PASSWORD FROM THE PAYPAL PANEL
    */
	if ( isset($_POST['carrito_config']) ) { // SAVE CONFIGURATATION OPTIONS
        // PAYPAL PRO SIGNATURE
		delete_option('carrito_paypal_signature');
		add_option('carrito_paypal_signature', $_POST['signature_paypal']);

        // PAYPAL PRO USERNAME
		delete_option('carrito_paypal_username');
		add_option('carrito_paypal_username', $_POST['username_paypal']);

        // PAYPAL PRO PASSWORD
		delete_option('carrito_paypal_password');
		add_option('carrito_paypal_password', $_POST['password_paypal']);

        // ACTIVATE OR DEACTIVATE PAYPAL SANDBOX FOR TESTING
		delete_option('carrito_paypal_sandbox');
		add_option('carrito_paypal_sandbox', $_POST['sandbox_paypal']);


        // SELECT THE CURRENCY YOU WOULD BE USING
		delete_option('carrito_paypal_currency');
		add_option('carrito_paypal_currency', $_POST['currency_paypal']);

        // SELECT THE IMAGE FOR THE BUY BUTTON YOU WOULD BE USING
		delete_option('carrito_paypal_button');
		add_option('carrito_paypal_button', $_POST['button_paypal']);
		if($_POST['carrito_paypal_button_url']){
            delete_option('carrito_paypal_button_url');
            add_option('carrito_paypal_button_url', $_POST['carrito_paypal_button_url']);
		}
	}


    /**
        IF YOU WANT TO USER MORE ADVANCED OPTIONS
        FOR YOUR SHIPPINGS YOU CAN USE THE SHIPPING CONFIGURATION INSIDE PAYPAL PRO.
        THE VALUES WILL BE SAVED SEPARATED BY PIPES (|)
    */
    if(isset($_POST['save_flat_shipping_paypal'])){ // ACTIVATE OR DEACTIVATE FLAT SHIPPING OPTIONS
        delete_option('activate_flat_shipping_paypal');
		add_option('activate_flat_shipping_paypal', $_POST['activate_flat_shipping_paypal']);
    }

	if ( isset($_POST['carrito_shipping_new']) ) { // ADD A NEW FLAT SHIPPING OPTION
		$str=get_option('carrito_shipping');
		$str.='|'.$_POST['carrito_shipping_name'].'|'.$_POST['carrito_shipping_price'];
		update_option('carrito_shipping', $str);
	}

	if ( isset($_POST['carrito_shippings']) ) { // UPDATE FLAT SHIPPING VALUES FOR CREATED OPTIONS
		//$str=get_option('carrito_shipping');
		for($i=0;$i<(count($_POST['carrito_shipping_names']));$i++){
			$str.='|'.$_POST['carrito_shipping_names'][$i].'|'.$_POST['carrito_shipping_prices'][$i];
		}
		update_option('carrito_shipping', $str);
	}



    /**
        PAYPAL PRO HAVE DISCOUNT COUPONS BUT ONLY FOR E-BAY. HERE WE WILL CREATE A PRODUCT WITH
        NEGATIVE VALUE TO MAKE THE DISCOUNT.
        THE VALUES WILL BE SAVED SEPARATED BY PIPES (|)
    */
	if ( isset($_POST['carrito_discount_new']) ) { // ADD A NEW COUPON CODE
		$str=get_option('carrito_discounts');
		$str.='|'.$_POST['carrito_discount_name'].'|'.$_POST['carrito_discount_price'];
		update_option('carrito_discounts', $str);
	}

	if ( isset($_POST['carrito_discounts']) ) { // UPDATE COUPON CODES VALUES
		//$str=get_option('carrito_shipping');
		for($i=0;$i<(count($_POST['carrito_discount_names']));$i++){
			$str.='|'.$_POST['carrito_discount_names'][$i].'|'.$_POST['carrito_discount_prices'][$i];
		}
		update_option('carrito_discounts', $str);
	}

	$messages = array();
    ?>
    <?php if ( !empty($_POST ) ) : ?>
        <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
    <?php endif;

    include_once 'paypal_wpadmin.php';

}


function carrito($string){
	echo $string;
	if(strstr($_SERVER['REQUEST_URI'],'webshop')){
		include_once 'paypal.nvp.class.php';

		$sandbox=false;
		if(get_option('carrito_paypal_sandbox')=='active') { $sandbox=true; }
		$ppConfig  = array('Sandbox' => $sandbox, 'APIUsername'=>get_option('carrito_paypal_username'),'APIPassword'=>get_option('carrito_paypal_password'),'APISignature'=>get_option('carrito_paypal_signature'));
		$pp = new PayPal($ppConfig);

		if(count($_SESSION['cart'])>0){
			if($_GET['PID'] || !$_GET || $_GET['DPID']){
				include_once 'webshop1.php';
				include_once 'webshop2.php';
			}

			/*if($_GET['checkout']=='address'){
				include_once 'webshop2.php';
			}*/

			if($_GET['checkout']=='finish'){
				include_once 'webshop3.php';
			}
		}
	}
}
add_filter('the_content','carrito');

function carrito_stats(){
	echo 'Stats';
}


/*
function carrito_editor_button() {
// Only add the javascript to post.php, post-new.php, page-new.php, or
// bookmarklet.php pages
	if (strpos($_SERVER['REQUEST_URI'], 'post.php') ||
		strpos($_SERVER['REQUEST_URI'], 'post-new.php') ||
		strpos($_SERVER['REQUEST_URI'], 'page-new.php') ||
		strpos($_SERVER['REQUEST_URI'], 'bookmarklet.php')
	) {
	    $mce_buttons = apply_filters('mce_buttons', array('separator','PayPalPro'));
		// Print out the HTML/Javascript to add the button
		?>
		<script type="text/javascript">
		//<![CDATA[
			var carrito_toolbar = document.getElementById("ed_toolbar");

			function carrito_button(querystr) {
				var precio=prompt("Enter the price for your product:");
			  	myField = document.getElementById('content');
			  	edInsertContent(myField, '[paypalpro_product_price='+precio+']');
				return false;
			}



			if (carrito_toolbar) {
				var theButton = document.createElement('input');
				theButton.type = 'button';
				theButton.value = 'PayPalPro';
				theButton.onclick = carrito_button;
				theButton.className = 'ed_button';
				theButton.title = 'PayPalPro!';
				theButton.id = 'ed_Carrito';
				carrito_toolbar.appendChild(theButton);

				edButtons[edButtons.length]=new edButton()
			}
		//]]>
		</script>
		<?php
	}
}

add_filter('admin_footer', 'carrito_editor_button');*/
?>

