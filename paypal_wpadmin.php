<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<style type="text/css">
label{ display:block; width: 150px; float:left; }
br { clear:both; }
</style>
<div class="wrap">
	<h2><?php _e('Paypal API Information'); ?></h2>
	<div class="narrow">
		<form action="" method="post" style="">
			<label for="username_paypal">Paypal Username:</label> <input type="text" name="username_paypal" value="<?php echo get_option('carrito_paypal_username');?>">
			<br />
			<label for="password_paypal">Paypal Password:</label> <input type="text" name="password_paypal" value="<?php echo get_option('carrito_paypal_password');?>">
			<br />
			<label for="signature_paypal">Paypal Signature:</label> <input type="text" name="signature_paypal" value="<?php echo get_option('carrito_paypal_signature');?>">
			<br />
			<label for="sandbox_paypal">Sandbox Mode:</label> <input type="checkbox" name="sandbox_paypal" <?php if(get_option('carrito_paypal_sandbox')=='active'){ echo 'checked'; }?> value="active">
			<br />
            <label for="currency_paypal">Currency:</label>
                                    <?php
                                    $CurrencyCodes = array(
										'AUD' => 'Austrailian Dollar',
										'CAD' => 'Canadian Dollar',
										'CHF' => 'Swiss Franc',
										'CZK' => 'Czech Koruna',
										'DKK' => 'Danish Krone',
										'EUR' => 'Euro',
										'GBP' => 'Pound Sterling',
										'HKD' => 'Hong Kong Dollar',
										'HUF' => 'Hungarian Forint',
										'JPY' => 'Japanese Yen',
										'NOK' => 'Norwegian Krone',
										'NZD' => 'New Zealand Dollar',
										'PLN' => 'Polish Zloty',
										'SEK' => 'Swedish Krona',
										'SGD' => 'Singapore Dollar',
										'USD' => 'U.S. Dollar'
                                    );
                                    ?>
                <select name="currency_paypal">
                    <?php
                    foreach ($CurrencyCodes as $k => $v) {
                        ?>
                        <option value="<?php echo $k;?>" <?php if(get_option('carrito_paypal_currency')==$k){ echo 'selected'; } ?>><?php echo $k;?> - <?php echo $v;?></option>
                        <?php
                    }
                    ?>
                </select>
			<br />
			<label for="button_paypal">Buy button image:</label>
		    <input type="radio" name="button_paypal" <?php if(get_option('carrito_paypal_button')=='default'){ echo 'checked'; }?> value="default"> Default
		    <input type="radio" name="button_paypal" <?php if(get_option('carrito_paypal_button')=='mine'){ echo 'checked'; }?> value="mine"> Personalized
		    <br />
		    <label for="carrito_paypal_button_url">Button image URL:</label>
		    <input type="text" name="carrito_paypal_button_url" value="<?php echo get_option('carrito_paypal_button_url'); ?>">
		    <br />
		    <label for="paypal_image_button">&nbsp;</label>
            <?php if(get_option('carrito_paypal_button')=='mine'){ ?>
                <input type="image" name="paypal_image_button" src="<?php echo get_option('carrito_paypal_button_url'); ?>" onclick="return false;">
            <?php }
            else{ ?>
                <input type="image" name="paypal_image_button" src="<?php echo get_bloginfo('url');?>/wp-content/plugins/Wordpress-PaypalPro-Plugin/images/Shopping%20Cart.png" onclick="return false;">
            <?php }
            ?>
			<br />
			<input type="submit" name="carrito_config" value="Save Configuration">
		</form>

	</div>
	<h2><?php _e('Flat Shipping options'); ?></h2>
	<?php $activate_flat_shipping_paypal=get_option('activate_flat_shipping_paypal'); ?>
	<div class="narrow">
    	<form action="" method="post" style="">
            Activate Flat Shipping: <input type="checkbox" name="activate_flat_shipping_paypal" <?php if($activate_flat_shipping_paypal=='active'){ echo 'checked'; }?> value="active">
            <input type="submit" name="save_flat_shipping_paypal" value="Save Shipping Options">
        </form>
    	<?php if($activate_flat_shipping_paypal=='active') { ?>
            <h3>Add New</h3>
            <form action="" method="post" style="">
            	<label for="carrito_shipping_name">Shipping Name:</label> <input name="carrito_shipping_name" value="">
            	<br />
            	<label for="carrito_shipping_price">Shipping Price:</label> <input name="carrito_shipping_price" value="">
            	<br />
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
        <?php } ?>
	</div>

	<h2><?php _e('Coupon codes'); ?></h2>
	<div class="narrow">
		<h3>Add New</h3>
		<form action="" method="post" style="">
			<label for="carrito_discount_name">Code:</label> <input name="carrito_discount_name" value="">(ex: ABC1234)
			<br />
			<label for="carrito_discount_price">Discount:</label> <input name="carrito_discount_price" value="">(ex: 10% or 15$)
			<br />
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
icons by  <a href="http://www.webiconset.com/payment-icon-set/">webiconset</a>

