<h2>Your Profile</h2>
First Name: <input type="text" name="firstname" id="firstname">
<br />
Middle Name: <input type="text" name="middlename" id="middlename">
<br />
Last Name: <input type="text" name="lastname" id="lastname">
<br />
Email: <input type="text" name="email">
<br />
Business: <input type="text" name="business">
<br />

<h2>Your Billing Address</h2>
street: <input type="text" name="street" id="street">
<br />street2: <input type="text" name="street2" id="street2">
<br />city: <input type="text" name="city" id="city">
<br />state: <input type="text" name="state" id="state">
<br />Country: 	<select name="countrycode" id="countrycode">
		<?php foreach ($pp->Countries as $key => $value) { ?>
			<option value="<?php echo $value?>"><?php echo $key;?></option>
		<?php } ?>
		</select>
<br />zip: <input type="text" name="zip" id="zip">
<br />phonenum: <input type="text" name="phonenum" id="phonenum">
<br />

<h2>Your Shipping Address</h2>
<input type="checkbox" id="copyshipp">Same as billing address
shiptoname: <input type="text" name="shiptoname" id="shiptoname">
<br />shiptostreet: <input type="text" name="shiptostreet" id="shiptostreet">
<br />shiptostreet2: <input type="text" name="shiptostreet2" id="shiptostreet2">
<br />shiptocity: <input type="text" name="shiptocity" id="shiptocity">
<br />shiptostate: <input type="text" name="shiptostate" id="shiptostate">
<br />shiptozip: <input type="text" name="shiptozip" id="shiptozip">
<br />shiptocountrycode: <select name="shiptocountrycode" id="shiptocountrycode">
		<?php foreach ($pp->Countries as $key => $value) { ?>
			<option value="<?php echo $value?>"><?php echo $key;?></option>
		<?php } ?>
		</select>
<br />shiptophonenum: <input type="text" name="shiptophonenum" id="shiptophonenum">


<h2>Your Credit Card Info</h2>
creditcardtype: <select name="creditcardtype">
		<option value="Visa">Visa</option>
		<option value="MasterCard">MasterCard</option>
		<option value="Discover">Discover</option>
		<option value="Amex">Amex</option>
		</select>
<br />Card or Account Number: <input type="text" name="acct">
<br />Expiration month:: 
	<select name="expdate_month">
		<?php for($m=1;$m<13;$m++){?>
		<option value="<?php echo $m;?>"><?php echo $m;?></option>
		<?php } ?>
	</select>
	Expiration year:
	<select name="expdate_year">
		<?php for($m=date('Y');$m<date('Y')+10;$m++){?>
		<option value="<?php echo $m;?>"><?php echo $m;?></option>
		<?php } ?>
	</select>
<br />Card Verification Number:: <input type="text" name="cvv2">
<br />
<input type="submit" name="finish_carrito" value="Pay now">
</form>
<br style="clear:both;" />
<script type="text/javascript">
$('#copyshipp').change(function() {
	if($('#copyshipp').attr('checked')==true){
		$('#shiptoname').val($('#firstname').val()+' '+ $('#middlename').val() + ' ' + $('#lastname').val());
		$('#shiptostreet').val($('#street').val());
		$('#shiptostreet2').val($('#street2').val());
		$('#shiptocity').val($('#city').val());
		$('#shiptozip').val($('#zip').val());
		$('#shiptostate').val($('#state').val());
		$('#shiptocountrycode').val($('#countrycode').val());
		$('#shiptophonenum').val($('#phonenum').val());
		$('input[id^=shipto]').attr('disabled','disabled');
	}
	else{
		$('input[id^=shipto]').attr('disabled','');
	}
});

$('form').submit(function() {
	var rq=$('.required').;
	//return false;	
});
</script>
