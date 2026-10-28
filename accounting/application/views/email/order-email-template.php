<div style="background-color:#fff;width:650px;font-family:Open-sans,sans-serif;color:#555454;font-size:13px;line-height:18px;margin:auto">
    <table style="width:100%;margin-top:10px">
        <tbody><tr>
            <td style="width:20px;padding:7px 0">&nbsp;</td>
            <td align="center" style="padding:7px 0">
                <table bgcolor="#ffffff" style="width:100%">
                    <tbody>
                    <tr>
                        <td align="center" style="border-bottom:4px solid #333333;padding:7px 0">
                            <?php
                            $CI =& get_instance();
                            $CI->load->model('Company_model', 'email_company_model');
                            $company = $CI->email_company_model->get_company();
                            $logo_url = base_url("media/images/logo.png");
                            if (!empty($company) && !empty($company->Logo) && file_exists(FCPATH . 'media/img/' . $company->Logo)) {
                                $logo_url = base_url('media/img/' . $company->Logo);
                            }
                            ?>
                            <a  href="<?=base_url()?>" style="color:#337ff1" target="_blank">
                                <img src="<?= $logo_url ?>"  class="CToWUd" style="max-height: 80px; object-fit: contain;">
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:7px 0">
                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                <span style="font-weight:500;font-size:28px;text-transform:capitalize;line-height:33px">Hi <?= $one_customer->FirstName ?> <?= $one_customer->LastName ?>,</span><br>
                                <span style="font-weight:500;font-size:16px;text-transform:uppercase;line-height:25px">Thank you for shopping with <?= !empty($company->CompanyName) ? htmlspecialchars($company->CompanyName) : TITLE ?>!</span>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0!important">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #d6d4d4;background-color:#f8f8f8;padding:7px 0">
                            <table style="width:100%">
                                <tbody><tr>
                                    <td width="10" style="padding:7px 0">&nbsp;</td>
                                    <td style="padding:7px 0">
                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                            <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
                                                Order details						</p>
						<span style="color:#777">
							<span style="color:#333"><strong>Order:</strong></span> <?= $one_receipt->ReceiptNo ?> Placed on <?= date("d/m/Y H:i:s", strtotime($one_receipt->OrderDate) ) ?><br>
							<span style="color:#333"><strong>Receipt No:</strong></span> <?= $one_receipt->ReceiptNo ?> <br>
							<span style="color:#333"><strong>Card Type:</strong></span> <?= $one_receipt->CreditCardType  ?><br>
							<span style="color:#333"><strong>Card Holder Name:</strong></span>  <?= $one_receipt->CreditCardHolderName  ?>
						</span>
                                        </font>
                                    </td>
                                    <td width="10" style="padding:7px 0">&nbsp;</td>
                                </tr>
                                </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:7px 0">
                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                <table bgcolor="#ffffff" style="width:100%;border-collapse:collapse">
                                    <tbody><tr>
                                        <th bgcolor="#f8f8f8" style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px">#</th>
                                        <th bgcolor="#f8f8f8" style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px">Product</th>
                                        <th bgcolor="#f8f8f8" style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%">Unit Price (LKR)</th>
                                        <th bgcolor="#f8f8f8" style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px">Quantity</th>
                                        <th bgcolor="#f8f8f8" style="border:1px solid #d6d4d4;background-color:#fbfbfb;color:#333;font-family:Arial;font-size:13px;padding:10px" width="17%">Total Price</th>
                                    </tr>
                                    <?php $cart = json_decode($one_receipt->Cart);  ?>
                                    <tr>
                                        <td colspan="5" style="border:1px solid #d6d4d4;text-align:center;color:#777;padding:7px 0">
                                            &nbsp;&nbsp;</td></tr>
                                    <?php  $x=0; foreach ($cart as $items): $x++;?>
                                        <tr>
                                            <td style="border:1px solid #d6d4d4">
                                                <table>
                                                    <tbody><tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td>
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                <?= $x; ?>
                                                            </font>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                            <td style="border:1px solid #d6d4d4">
                                                <table>
                                                    <tbody><tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td>
                                                            <img src="<?= UP.$items->image ?>" width="75" ><br/>
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                <strong> <?= $items->pro_ori_name; ?>  </strong>
                                                            </font>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                            <td style="border:1px solid #d6d4d4">
                                                <table>
                                                    <tbody><tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td align="right">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">



                                                                <span class="amounte">


                                                                    <?php

                                                                    $price = '';
                                                                    if($items->options->DsiscountExpireDate!=0000-00-00){

                                                                        if($items->options->DsiscountExpireDate >= $today){

                                                                            if ($items->options->DiscountValue == 0) {
                                                                                $price = $items->options->Price;
                                                                                echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                            } else {
                                                                                if ($items->options->DiscountType == 2) {
                                                                                    $price = $items->options->Price - $items->options->DiscountValue;
                                                                                    echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                                } else {
                                                                                    $price = $items->options->Price - ($items->options->Price * $items->options->DiscountValue / 100);
                                                                                    echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                                }
                                                                                echo'<br/>';
                                                                                echo ' <span class="old-price" style="color: #a0a0a0;"> <del>LKR ' . number_format($items->options->Price, 2) . '</del> </span>';
                                                                            }
                                                                        }else{

                                                                            $price = $items->options->Price;
                                                                            echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                        }
                                                                    }else{
                                                                        if ($items->options->DiscountValue == 0) {
                                                                            $price = $items->options->Price;
                                                                            echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                        } else {
                                                                            if ($items->options->DiscountType == 2) {
                                                                                $price = $items->options->Price - $items->options->DiscountValue;
                                                                                echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                            } else {
                                                                                $price = $items->options->Price - ($items->options->Price * $items->options->DiscountValue / 100);
                                                                                echo '<span class="special-price">LKR ' . number_format($price, 2) . '</span>';
                                                                            }
                                                                            echo'<br/>';
                                                                            echo ' <span class="old-price" style="color: #a0a0a0;"> <del>LKR ' . number_format($items->options->Price, 2) . '</del> </span>';
                                                                        }
                                                                    }

                                                                    ?>
                                        </span>

                                                            </font>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                            <td style="border:1px solid #d6d4d4">
                                                <table>
                                                    <tbody><tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td align="right">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                <?= $items->qty; ?>
                                                            </font>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                            <td style="border:1px solid #d6d4d4">
                                                <table>
                                                    <tbody><tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td align="right">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                LKR <?= number_format($items->subtotal, 2) ?>
                                                            </font>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr>
                                        <td colspan="5" style="border:1px solid #d6d4d4;text-align:center;color:#777;padding:7px 0">
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <strong>Products</strong>
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                        <td bgcolor="#f8f8f8" align="right" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                             LKR <?= number_format($one_receipt->CartTotal,2) ?>
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>

                                    <?php
                                    if($one_receipt->CouponDiscountType AND $one_receipt->CouponValue){
                                        if($one_receipt->CouponDiscountType==1) {
                                            $new_coupon_price = ($one_receipt->CartTotal * $one_receipt->CouponValue / 100);
                                        }else{
                                            $new_coupon_price=  $one_receipt->CouponValue;
                                        }
                                        ?>
                                        <tr>
                                            <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                                <table style="width:100%;border-collapse:collapse">
                                                    <tbody><tr>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                        <td align="right" style="color:#333;padding:0">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                <strong>Coupon Discount</strong>
                                                            </font>
                                                        </td>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                            <td bgcolor="#f8f8f8" align="right" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                                <table style="width:100%;border-collapse:collapse">
                                                    <tbody><tr>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                        <td align="right" style="color:#333;padding:0">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                LKR <?= $this->cart->format_number($new_coupon_price) ?> (-)
                                                            </font>
                                                        </td>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if($one_receipt->VoucherCode AND $one_receipt->VoucherNumber AND $one_receipt->VoucherValue){
                                        $new_voucher_price= $one_receipt->CartTotal- $one_receipt->VoucherValue;
                                        ?>
                                        <tr>
                                            <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                                <table style="width:100%;border-collapse:collapse">
                                                    <tbody><tr>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                        <td align="right" style="color:#333;padding:0">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                <strong>Voucher Value</strong>
                                                            </font>
                                                        </td>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>

                                            <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                                <table style="width:100%;border-collapse:collapse">
                                                    <tbody><tr>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                        <td align="right" style="color:#333;padding:0">
                                                            <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                                LKR
                                                                <?= $this->cart->format_number($one_receipt->VoucherValue); ?> (-)
                                                            </font>
                                                        </td>
                                                        <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>


                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <tr>
                                        <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <strong>Shipping</strong>
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                        <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            LKR <?= number_format($one_receipt->DeliveryCost,2) ?> (+)
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <strong>Total paid</strong>
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                        <td bgcolor="#f8f8f8" colspan="3" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody><tr>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                    <td align="right" style="color:#333;padding:0">
                                                        <font size="4" face="Open-sans, sans-serif" color="#555454">
                                                            <?php
                                                            $new_voucher_total=0;
                                                            $new_coupon_total=0;
                                                            if($one_receipt->CouponDiscountType AND $one_receipt->CouponValue){
                                                                if($one_receipt->CouponDiscountType==1) {
                                                                    $new_coupon_total = ($one_receipt->CartTotal * $one_receipt->CouponValue / 100);
                                                                }else{
                                                                    $new_coupon_total=  $one_receipt->CouponValue;
                                                                }
                                                            }

                                                            if($one_receipt->VoucherCode AND $one_receipt->VoucherNumber AND $one_receipt->VoucherValue){
                                                                $new_voucher_total= $one_receipt->VoucherValue;
                                                            }


                                                            ?>

                                                            LKR
                                                            <?php
                                                            //                                    $this->cart->format_number($this->cart->total()-($new_coupon_total+$new_voucher_total))
                                                            if($this->cart->format_number($one_receipt->CartTotal-($new_coupon_total+$new_voucher_total)+$one_receipt->DeliveryCost) > 0){
                                                                echo number_format($one_receipt->CartTotal-($new_coupon_total+$new_voucher_total)+$one_receipt->DeliveryCost,2);
                                                            }else{
                                                                echo '0.00';
                                                            }
                                                            ?>
                                                        </font>
                                                    </td>
                                                    <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                </tr>
                                                </tbody></table>
                                        </td>
                                    </tr>

                                    </tbody></table>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #d6d4d4;background-color:#f8f8f8;padding:7px 0">
                            <table style="width:100%">
                                <tbody><tr>
                                    <td width="10" style="padding:7px 0">&nbsp;</td>
                                    <td style="padding:7px 0">
                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                            <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
                                                Shipping						</p>
						<span style="color:#777">
							<span style="color:#333"><strong>Carrier:</strong></span> <?= $delivery->MediaComTitle ?><br>
							<span style="color:#333"><strong>Shipping Country:</strong></span> <?= $delivery->CountryTitle ?><br>
							<span style="color:#333"><strong>Shipping City/Town:</strong></span> <?= $delivery->CityTitle ?><br>
							<span style="color:#333"><strong>Payment:</strong></span> Credit Card [Type : <?= $one_receipt->CreditCardType ?>]<br>
							<span style="color:#333"><strong>Estimated Delivery:</strong></span>  Delivery 2-4 working days
						</span>
                                        </font>
                                    </td>
                                    <td width="10" style="padding:7px 0">&nbsp;</td>
                                </tr>
                                </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0!important">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 0">
                            <table style="width:100%">
                                <tbody>
                                <tr>
                                    <td width="310" style="border:1px solid #d6d4d4;background-color:#f8f8f8;padding:7px 0">
                                        <table style="width:100%">
                                            <tbody><tr>
                                                <td width="10" style="padding:7px 0">&nbsp;</td>
                                                <td style="padding:7px 0">
                                                    <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                        <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
                                                            Delivery address									</p>
									<span style="color:#777">

										<span style="font-weight:bold"><?= $one_receipt->FirstName;  ?> </span>
                                        <span style="font-weight:bold"><?= $one_receipt->LastName;  ?></span><br>
                                        <br>
                                        <?= $one_receipt->Address1;  ?><br>
                                        <?= $one_receipt->Address2;  ?><br>
                                        <?= $one_customer_city->CityTitle ?>, <?= $one_customer_country->CountryTitle ?><br>
                                        Mobile No:- <?= $one_receipt->MobileNo ?>, <br>
                                        Telephone No:- <?= $one_receipt->TelephoneNo ?><br>

									</span>

                                                    </font>
                                                </td>
                                                <td width="10" style="padding:7px 0">&nbsp;</td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                    <td width="20" style="padding:7px 0">&nbsp;</td>
                                    <td width="310" style="border:1px solid #d6d4d4;background-color:#f8f8f8;padding:7px 0">
                                        <table style="width:100%">
                                            <tbody><tr>
                                                <td width="10" style="padding:7px 0">&nbsp;</td>
                                                <td style="padding:7px 0">
                                                    <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                        <p style="border-bottom:1px solid #d6d4d4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
                                                            Billing address									</p>
									<span style="color:#777">

										<span style="font-weight:bold"><?= $one_customer->FirstName;  ?> </span>
                                        <span style="font-weight:bold"><?= $one_customer->LastName;  ?></span><br>
                                        <br>
                                        <?= $one_customer->Address1;  ?><br>
                                        <?= $one_customer->Address2;  ?><br>
                                        <?= $one_customer_ori_city->CityTitle ?>, <?= $one_customer_ori_country->CountryTitle ?><br>
                                         Mobile No:- <?= $one_receipt->MobileNumber ?>, <br>
                                        Telephone No:- <?= $one_receipt->LandPhoneNumber ?><br>

									</span>

                                                    </font>
                                                </td>
                                                <td width="10" style="padding:7px 0">&nbsp;</td>
                                            </tr>
                                            </tbody>
                                            </table>
                                    </td>
                                </tr>
                                
                                </tbody></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0!important">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 0">
                            <font size="2" face="Open-sans, sans-serif" color="#555454">
			<span>
				You can review your order and download your invoice from the <a href="<?= base_url() ?>Order-History/<?= $one_receipt->ReceiptNo ?>" style="color:#337ff1" target="_blank">"Order history"</a> section of your customer account by clicking <a href="<?= base_url() ?>User/My-Account" style="color:#337ff1" target="_blank">"My account"</a> on our shop.			</span>
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0!important">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border-top:4px solid #333333;padding:7px 0">
                            <span><a href="<?= base_url() ?>" style="color:#337ff1" target="_blank"><?= TITLE ?></a> powered by <a href="http://itmart.lk/" style="color:#337ff1" target="_blank">ITMARTX</a></span>
                        </td>
                    </tr>
                    </tbody></table>
            </td>
            <td style="width:20px;padding:7px 0">&nbsp;</td>
        </tr>
        </tbody></table><div class="yj6qo"></div><div class="adL">
    </div></div>