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
	@session_start();
	add_action('admin_menu', 'carrito_config_page');
	add_action('admin_menu', 'carrito_stats_page');
}
add_action('init', 'carrito_init');



function carrito_config_page() {
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('PayPal Shop'), __('PayPal Shop'), 'manage_options', 'carrito-config', 'carrito_conf');
	}
}

function carrito_stats_page() {
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('PayPal Shop Stats'), __('PayPal Shop Stats'), 'manage_options', 'carrito-stats', 'carrito_stats');
	}
}

function carrito_conf() {

	if ( isset($_POST['carrito_config']) ) {
		delete_option('carrito_paypal_signature');
		add_option('carrito_paypal_signature', $_POST['signature_paypal']);
		
		delete_option('carrito_paypal_username');
		add_option('carrito_paypal_username', $_POST['username_paypal']);
		
		delete_option('carrito_paypal_password');
		add_option('carrito_paypal_password', $_POST['password_paypal']);
		
		delete_option('carrito_paypal_sandbox');
		add_option('carrito_paypal_sandbox', $_POST['sandbox_paypal']);
	} 
	
	if ( isset($_POST['carrito_shipping_new']) ) {
		$str=get_option('carrito_shipping');
		$str.='|'.$_POST['carrito_shipping_name'].'|'.$_POST['carrito_shipping_price'];
		update_option('carrito_shipping', $str);
	}
	
	if ( isset($_POST['carrito_shippings']) ) {
		//$str=get_option('carrito_shipping');
		for($i=0;$i<(count($_POST['carrito_shipping_names']));$i++){
			$str.='|'.$_POST['carrito_shipping_names'][$i].'|'.$_POST['carrito_shipping_prices'][$i];
		}
		update_option('carrito_shipping', $str);
	} 
	
	if ( isset($_POST['carrito_discount_new']) ) {
		$str=get_option('carrito_discounts');
		$str.='|'.$_POST['carrito_discount_name'].'|'.$_POST['carrito_discount_price'];
		update_option('carrito_discounts', $str);
	}
	
	if ( isset($_POST['carrito_discounts']) ) {
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
<?php endif; ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<div class="wrap">
	<h2><?php _e('Paypal API Information'); ?></h2>
	<div class="narrow">
		<form action="" method="post" style="">
			Paypal Username: <input type="text" name="username_paypal" value="<?php echo get_option('carrito_paypal_username');?>">
			<br>
			Paypal Password: <input type="text" name="password_paypal" value="<?php echo get_option('carrito_paypal_password');?>">
			<br>
			Paypal Signature: <input type="text" name="signature_paypal" value="<?php echo get_option('carrito_paypal_signature');?>">
			<br>
			Sandbox Mode: <input type="checkbox" name="sandbox_paypal" <?php if(get_option('carrito_paypal_sandbox')=='active'){ echo 'checked'; }?> value="active">
			<input type="submit" name="carrito_config" value="Save Configuration">
		</form>

	</div>
	<h2><?php _e('Shipping options'); ?></h2>
	<div class="narrow">
		<h3>Add New</h3>
		<form action="" method="post" style="">
			Shipping Name: <input name="carrito_shipping_name" value="">
			<br>
			Shipping Price: <input name="carrito_shipping_price" value="">
			<br>
			<input type="submit" name="carrito_shipping_new" value="Add New Shipping Option">
		</form>

		<form action="" method="post" style="padding:25px 0px 0px 25px">
		<?php
		$sopt=explode('|',get_option('carrito_shipping')); 
		for($i=1; $i<(count($sopt));$i++){
			?>
			<div>
			<input type="button" onclick="$(this).parent().html('');" value="Remove">
			<input name="carrito_shipping_names[]" value="<?php echo $sopt[$i];?>">
			<?php $i++;?>
			<input name="carrito_shipping_prices[]" value="<?php echo $sopt[$i];?>">
			</div>
			<?php
		}
		?>
		<?php if(count($sopt)>1) { ?><input type="submit" name="carrito_shippings" value="Update Shipping Options"> <?php } ?>
		</form>
	</div>
	
	<h2><?php _e('Coupon codes'); ?></h2>
	<div class="narrow">
		<h3>Add New</h3>
		<form action="" method="post" style="">
			Code: <input name="carrito_discount_name" value="">(ex: ABC1234)
			<br>
			Discount: <input name="carrito_discount_price" value="">(ex: 10% or 15$)
			<br>
			<input type="submit" name="carrito_discount_new" value="Add New Coupon Code">
		</form>

		<form action="" method="post" style="padding:25px 0px 0px 25px">
		<?php
		$copt=explode('|',get_option('carrito_discounts')); 
		for($i=1; $i<(count($copt));$i++){
			?>
			<div>
			<input type="button" onclick="$(this).parent().html('');" value="Remove">
			<input name="carrito_discount_names[]" value="<?php echo $copt[$i];?>">
			<?php $i++;?>
			<input name="carrito_discount_prices[]" value="<?php echo $copt[$i];?>">
			</div>
			<?php
		}
		?>
		<?php if(count($copt)>1) { ?><input type="submit" name="carrito_discounts" values="Update Coupon Codes"><?php } ?>
		</form>
	</div>
</div>
<?php
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
