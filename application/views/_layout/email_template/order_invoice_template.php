<style type="text/css">
	.coupon {
		color: #80cc93;
		font-size: 12px;
		font-weight: bold;
		margin: 0;
		padding: 0;
	}

	.body {
		font-family: "Raleway", sans-serif;
		font-size: 14px;
		font-weight: 400;
		line-height: 18px;
		color: #000;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>

<body class="body">
	<?php
	if ($data['test_mode'])
		echo '<center><h3>TEST MODE</h3></center>';
	?>

	<table width="622" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#f3f4f8; border:#d8d8d8 1px solid;">
		<tr>
			<td>
				<table width="622" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr> </tr>
				</table>
			</td>
		</tr>
		<tr align="center" style="font-size:18px; font-family:Tahoma, Geneva, sans-serif;"> </tr>
		<tr>
			<td>
				<center>
					<img src="<?= $logo ?>" style='padding: 15px;width:40%;' />
				</center>
			</td>
		</tr>
		<tr>
			<td height="1"></td>
		</tr>
		<tr>
			<td bgcolor="#f5f9f6" style="font-family:Arial, Helvetica, sans-serif;">
				<table width="622" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding:8px 15px;">
							<p><strong>Dear <?= $data['name']; ?></strong></p>
						</td>
					</tr>
					<tr>
						<td style="font-size:13px; line-height:22px; padding:0 15px; margin-bottom:15px;">
							<?php
							if ($data['type'] == 'USER') {
								$abc = 'This email is to let you know that we have received your order.
											Thank you for subscribing. Subscription details are given below: ';
							} else {
								$abc = 'A user has successfully placed an order. Order details are shown below for your reference:';
							}

							echo $abc;
							?>
						</td>
					</tr>
					<tr bgcolor="<?= $color_theme ?>" style="margin:20px 0; float:left;height:86px; ">
						<td width="622">
							<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px;">
								<tr style="color:#fff;  ">
									<td width="251" align="left ">Order number : <b><?= $data['order_no'] ?></td>
									<td width="50">&nbsp;</td>
									<td width="251" align="right">Order date : <?= date('d-M-Y') ?></td>
								</tr>
								<tr style="color:#fff">

									<td width="451" align="left">Payment status :
										<span style='font-size:13px;font-weight:bold'>
											<?= $data['payment_status'] ?>
										</span>
									</td>

									<td width="25">&nbsp;</td>
									<td width="50" align="right" style="font-size:30px;"><?= price($total_order_amount) ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="font-size:13px; line-height:22px; padding:0 15px; margin-bottom:15px;">
							<?php
							$your = ($data['type'] == 'USER' ? 'Your' : 'User');
							?>
							<?= $your ?> email ID: <?= $data['user_email'] ?> <br />
						</td>
					</tr>

					<tr bgcolor="#f6f5f5" style="margin:0px 0; float:left; height:50px;">
						<td width="622">
							<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:15px;">
								<tr style="color:<?= $color_theme ?>;  ">
									<td width="251" align="left " style="font-size:28px;">Payment details</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr bgcolor="#f6f5f5" style="float:left; padding:0 0 10px;border-bottom: 1px solid #cbcaca; margin-bottom:15px; ">

						<td width="622">
							<table width="580" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px;">
								<?php
								$i = 1;
								if (isset($data['product']) && is_array($data['product']) && count($data['product']) > 0) {
									foreach ($data['product'] as $value) {
								?>
										<tr style="color:<?= $color_theme ?>; height:30px;  ">
											<td width="251" align="left" colspan='3'>
												Product Name : <?= $value['product_name'] ?>
												<?php
												if (isset($value['coupon_name'])) {
													$coupon_name = $value['coupon_name'];

													echo "<p class='coupon'>Avail Coupon : $coupon_name</p>";
												}
												?>
											</td>

										</tr>

										<tr style="color:<?= $color_theme ?>; height:30px;  ">
											<td width="251" align="left ">
												Sub total (Qty <?= $value['product_qty'] ?> @ <?= $value['product_rate'] ?>)
											</td>
											<td width="50">&nbsp;</td>
											<td width="251" align="right"><?= price($value['product_total']) ?></td>
										</tr>
										<?php
										if (!empty($value['item_content'])) {
										?>
											<tr>
												<td colspan='3' style="background: #f5f9f6 none repeat scroll 0 0;color: #000;font-size: 11px;padding: 8px;">
													<strong>Comments : </strong> <?= $value['item_content'] ?>
												</td>
											</tr>
										<?php
										}
										?>
										<tr>
											<td colspan='3' style="color:<?= $color_theme ?>;font-weight:bold">
												-------------------------------------------------------------------------------------------------------------
											</td>
										</tr>

								<?php
									}
								}
								?>

								<!--<tr style="color:<?= $color_theme ?>;height:30px;">-->
								<!--	<td width="251" align="left">Add : Total shipping charges</td>-->
								<!--	<td width="50">&nbsp;</td>-->
								<!--	<td width="251" align="right">-->
								<?php /*
												if($data['shipping_amount'] == 0.00)
													echo 'Free shipping';
												else
													echo price($data['shipping_amount']); */
								?>
								<!--	</td>-->
								<!--</tr>-->

								<?php
								if (!empty($data['tax_amount']) && $data['tax_amount'] > 0) {
								?>
									<tr style="color:<?= $color_theme ?>;height:30px;">
										<td width="251" align="left">Add : Tax Amount</td>
										<td width="50">&nbsp;</td>
										<td width="251" align="right">
											<?= price($data['tax_amount']) ?>
										</td>
									</tr>
								<?php
								}
								if (!empty($data['discount_amount']) && $data['discount_amount'] > 0) {
								?>
									<tr style="color:<?= $color_theme ?>;height:30px;">
										<td width="251" align="left">Less : Discount Amount</td>
										<td width="50">&nbsp;</td>
										<td width="251" align="right">
											- <?= price($data['discount_amount']) ?>
										</td>
									</tr>
								<?php
								}
								?>

								<tr style="color:<?= $color_theme ?>;height:30px;">
									<td width="251" align="left"><strong>Total cost</strong></td>
									<td width="50">&nbsp;</td>
									<td width="251" align="right"><strong><?= price($total_order_amount) ?></strong></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="font-size:13px; line-height:22px; padding:0 15px; margin-bottom:15px; padding-bottom:10px;">
							To make sure our emails reach your inbox, please add <a href='mailto:<?= $data['inquiry_email'] ?>'><?= $data['inquiry_email'] ?></a> to your safe list or address book.<br />
							<!-- Please note that there will be a delivery charge for re-sending returned items if an incorrect address has been provided. <br /> -->
							<!-- Refund Policy - Due to health and hygiene reasons, we are unable to exchange or provide a refund on products once they have been dispatched. If the items have been damaged in transit, please contact us by email within 24 hours of receiving delivery. -->
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>