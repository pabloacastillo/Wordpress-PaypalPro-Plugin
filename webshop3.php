<?php
if($_POST['finish_carrito']){

	if($_POST['coupon']){
		$coupons=get_option('carrito_discounts');
		$error.='Coupon doents exists';
		if(strstr(strtolower($coupons),strtolower($_POST['coupon']))){
			$copt=explode('|',$coupons); 
			for($i=1; $i<(count($copt));$i++){
				if($copt[$i]==$_POST['coupon']){
					$_SESSION['coupon_total']=$copt[$i+1];
					$_SESSION['coupon']=$_POST['coupon'];
				}
				$i++;
			}
			$error='';
		}
	
	}
	echo "<h1>$error </h1>";
	foreach ($_SESSION['cart'] as $key => $value) {
		$_SESSION['cart'][$key]['amount']=$_POST['amount'][$key]+0;
	}
	

	$DPFields = array(
			'paymentaction' => 'Sale',
			'ipaddress' => $_SERVER['REMOTE_ADDR'],
			'returnfmfdetails' => '1'
		);

	$CCDetails = array(
			'creditcardtype' => $_POST['creditcardtype'],
			'acct' => $_POST['acct'],
			'expdate' => $_POST['expdate_month'].$_POST['expdate_year'],
			'cvv2' => $_POST['cvv2'],
			'startdate' => ''
		);

	$PayerInfo = array(
			'email' => $_POST['email'],
			'business' => $_POST['business']
		);

	$PayerName = array(
			'salutation' => '',
			'firstname' => $_POST['firstname'],
			'middlename' => $_POST['middlename'],
			'lastname' => $_POST['lastname'],
			'suffix' => ''
		);

	$BillingAddress = array(
				'street' => $_POST['street'],
				'street2' => $_POST['street2'],
				'city' => $_POST['city'],
				'state' => $_POST['state'],
				'countrycode' => $_POST['countrycode'],
				'zip' => $_POST['zip'],
				'phonenum' => $_POST['phonenum']
			);

	$ShippingAddress = array(
				'shiptoname' => $_POST['shiptoname'],
				'shiptostreet' => $_POST['shiptostreet'],
				'shiptostreet2' => $_POST['shiptostreet2'],
				'shiptocity' => $_POST['shiptocity'],
				'shiptostate' => $_POST['shiptostate'],
				'shiptozip' => $_POST['shiptozip'],
				'shiptocountrycode' => $_POST['shiptocountrycode'],
				'shiptophonenum' => $_POST['shiptophonenum']
				);
	
	$OrderItems = array();		

	foreach ($_SESSION['cart'] as $key => $value) {
		if($_SESSION['cart'][$key]['amount']>0){
			$Item	 = array(
				'l_name' => $_SESSION['cart'][$key]['name'],			// Item Name.  127 char max.
				'l_amt' => $_SESSION['cart'][$key]['price'],			// Cost of individual item.
				'l_number' => $key, 						// Item Number.  127 char max.
				'l_qty' => $_SESSION['cart'][$key]['amount'],			// Item quantity.  Must be any positive integer.  
				'l_taxamt' => $_SESSION['cart'][$key]['tax']+0,			// Item's sales tax amount.
				'l_ebayitemnumber' => '',
				'l_ebayitemauctiontxnid' => '',
				'l_ebayitemorderid' => ''
			);

			array_push($OrderItems, $Item);
		}
		$TOTAL+=$_SESSION['cart'][$key]['price']*$_SESSION['cart'][$key]['amount'];
	}
	
	
	if($_SESSION['coupon_total']){
	
		if(substr($_SESSION['coupon_total'],-1)=='%'){
			$_SESSION['coupon_amount']=($TOTAL/100)*substr($_SESSION['coupon_total'],0,-1);				
		}
		if(substr($_SESSION['coupon_total'],-1)=='$'){
			$_SESSION['coupon_amount']=substr($_SESSION['coupon_total'],0,-1);				
		}
		

		$Item	 = array(
			'l_name' => 'Discount Coupon '.$_SESSION['coupon_total'],	// Item Name.  127 char max.
			'l_amt' => ($_SESSION['coupon_amount'])*-1,			// Cost of individual item.
			'l_number' => 'Discount Coupon '.$_POST['coupon'], 		// Item Number.  127 char max.
			'l_qty' => 1,							// Item quantity.  Must be any positive integer.  
			'l_taxamt' => '',						// Item's sales tax amount.
			'l_ebayitemnumber' => '',
			'l_ebayitemauctiontxnid' => '',
			'l_ebayitemorderid' => ''
		);

		$OrderItems[]= $Item;
		$TOTAL-=$_SESSION['coupon_amount'];
	}
	
	
	$PaymentDetails = array(
				'amt' => $TOTAL+$_POST['shipping_carrito'],
				'currencycode' => 'USD',
				'itemamt' => $TOTAL,
				'shippingamt' => $_POST['shipping_carrito'],
				'handlingamt' => '',
				'taxamt' => '',
				'desc' => 'Probando carrito Finaplix.',
				'custom' => '',
				'invnum' => 'FINXPLX-'.(time()),
				'buttonsource' => '',
				'notifyurl' => ''
			);

	# Now combine your data arrays into a single nested array to pass into the class.
	$DPData = array(
		'DPFields' => $DPFields,
		'CCDetails' => $CCDetails,
		'PayerInfo' => $PayerInfo,
		'PayerName' => $PayerName,
		'BillingAddress' => $BillingAddress,
		'ShippingAddress' => $ShippingAddress,
		'OrderItems' => $OrderItems,
		'PaymentDetails' => $PaymentDetails);
	/*echo '<pre />';
	print_r($_POST);
	echo '<hr>';
	echo '<pre />';
	print_r($_SESSION);
	echo '<hr>';
	 */
	# Now we pass the nested array of all our data into the class.
	$DPResult = $pp -> DoDirectPayment($DPData);

	/*echo '<pre />';
	print_r($DPData);
	echo '<hr>';
	# Now lets study the result array
	echo '<pre />';
	print_r($DPResult); */
	if($DPResult['ACK']=='Success'){
	?>
		Thanks for your order.
	<?php
	}
	
	if($DPResult['ACK']=='Failure'){
	?>
		There was a problem with your order: <br />
		<ul>
		<?php for($e=0; $e<count($DPResult['ERRORS']);$e++){
		?>
			<li><?php echo $DPResult['ERRORS'][$e]['L_LONGMESSAGE'];?></li>
		<?php
		}?>
		</ul>
	<?php
	}

}
?>
