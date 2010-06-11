<?php

if($_GET['DPID']){
	$_SESSION['cart'][$_GET['DPID']]['amount']=0;
}

if($_GET['PID'] && get_post($_GET['PID'])) {
	$p=get_post($_GET['PID']);
	$_SESSION['cart'][$p->ID]['name']=$p->post_title;
	$_SESSION['cart'][$p->ID]['amount']=$_SESSION['cart'][$p->ID]['amount']+1;
	$_SESSION['cart'][$p->ID]['price']=substr(get_post_meta( $p->ID, 'price', true ),1);
}
?>
<?php echo $error;?>
<form method="post" action="?checkout=finish">
	<table width="600">
		<tr>
			<td>Product</td>
			<td>Amount</td>
			<td>Price</td>
			<td>Total</td>
			<td>Remove</td>
		</tr>
	<?php foreach ($_SESSION['cart'] as $key => $value) {?>
		<?php if($_SESSION['cart'][$key]['amount']>0){?>
			<tr id="pid<?php echo $key;?>">
				<td><?php echo $_SESSION['cart'][$key]['name'];?></td>
				<td>
					<select name="amount[<?php echo $key;?>]">
					<?php for($i=1;$i<15;$i++){?>
						<option value="<?php echo $i;?>" <?php if($_SESSION['cart'][$key]['amount']==$i){ echo 'selected';}?>><?php echo $i;?></option>
					<?php } ?>
					</select>
				</td>
				<td><?php echo $_SESSION['cart'][$key]['price'];?></td>
				<td><?php echo $_SESSION['cart'][$key]['price']*$_SESSION['cart'][$key]['amount']; $TOTAL+=$_SESSION['cart'][$key]['price']*$_SESSION['cart'][$key]['amount'];?></td>
				<td>
					<input type="button" value="Remove" name="remove" onclick='window.location.replace(location.protocol + "://" + location.host + "/" + location.pathname + "?DPID=<?php echo $key;?>");'>
				</td>
			</tr>
		<?php } ?>
	<?php } ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>Discount Coupon</td>
			<td><input type="text" name="coupon" <?php echo $_SESSION['coupon'];?>></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>Shipping </td>
			<td>
				<select name="shipping_carrito">
				<?php
				$sopt=explode('|',get_option('carrito_shipping')); 
				for($i=1; $i<(count($sopt));$i++){
					?>
					<option value="<?php echo $sopt[$i+1];?>"><?php echo $sopt[$i];?> - U$S<?php echo $sopt[$i+1];?></option>
					<?php
					$i++;
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<?php echo $TOTAL;?>
				<?php if($_SESSION['coupon_total']){
					if(substr($_SESSION['coupon_total'],-1)=='$'){
						$TOTAL2=$TOTAL-substr($_SESSION['coupon_total'],0,-1);
						$_SESSION['coupon_amount']=substr($_SESSION['coupon_total'],0,-1);
					}
					if(substr($_SESSION['coupon_total'],-1)=='%'){
						$TOTAL2=$TOTAL-($TOTAL/substr($_SESSION['coupon_total'],0,-1));
						$_SESSION['coupon_amount']=$TOTAL/substr($_SESSION['coupon_total'],0,-1);
					}
					echo "<br>-".$_SESSION['coupon_total'];
					echo "<br>$TOTAL2";
				}?>
			</td>
			<td>
				<!-- <input type="submit" name="save" value="Update">
				<input type="button" value="Checkout" onclick="$('#frm_paypal').attr('action','?checkout=address');$('#frm_paypal').submit();"> -->
			</td>
		</tr>
	</table>

<br style="clear:both">

