<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="
0">
<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>"
     style="background-color: #f5f5f5; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%">
	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
		<tbody>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container"
				       style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #dcdcdc; border-radius: 3px !important">
					<tbody>
					<tr>
						<td align="center" valign="top">
							<!-- node type 8 -->
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header"
							       style="background-color: #557da1; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif">
								<tbody>
								<tr>
									<td id="header_wrapper" style="padding: 36px 48px; display: block">
										<h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #7797b4; -webkit-font-smoothing: antialiased">
											<?php echo $email_title ?>
										</h1>
									</td>
								</tr>
								</tbody>
							</table>
							<!-- node type 8 -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
							<!-- node type 8 -->
							<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
								<tbody>
								<tr>
									<td valign="top" id="body_content" style="background-color: #fdfdfd">
										<!-- node type 8 -->
										<table border="0" cellpadding="20" cellspacing="0" width="100%">
											<tbody>
											<tr>
												<td valign="top" style="padding: 48px">
													<div id="body_content_inner"
													     style="color: #737373; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left">
														<p style="margin: 0 0 16px">
															<?php echo $email_subtitle ?>
														</p>
														<h2 style="color: #557da1; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 16px 0 8px; text-align: left">
															<?php echo esc_html__( 'Order ', 'couponxxl' ) . '#' . $order_id ?>
														</h2>
														<table class="td" cellspacing="0" cellpadding="6"
														       style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4"
														       border="1">
															<thead>
															<tr>
																<th class="td"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px">
																	<?php esc_html_e( 'Product', 'couponxxl' ) ?>
																</th>
																<th class="td"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px">
																	<?php esc_html_e( 'Quantity', 'couponxxl' ) ?>
																</th>
																<th class="td"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px">
																	<?php esc_html_e( 'Price', 'couponxxl' ) ?>
																</th>
															</tr>
															</thead>
															<tbody>
															<?php echo $items_list; ?>
															</tbody>
															<tfoot>
															<tr>
																<th class="td" colspan="2"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px"><?php esc_html_e( 'Payment Method:', 'couponxxl' ) ?></th>
																<td class="td"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px"><?php echo $gateway ?></td>
															</tr>
															<tr>
																<th class="td" colspan="2"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px"><?php esc_html_e( 'Total:', 'couponxxl' ) ?></th>
																<td class="td"
																    style="text-align: left; color: #737373; border: 1px solid #e4e4e4; padding: 12px">
																	<span class="amount"><?php echo $total ?></span>
																</td>
															</tr>
															</tfoot>
														</table>
													</div>
												</td>
											</tr>
											</tbody>
										</table>
										<!-- node type 8 -->
									</td>
								</tr>
								</tbody>
							</table>
							<!-- node type 8 -->
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
		</tbody>
	</table>
</div>
</body>
</html>