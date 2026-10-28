

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
                                <span style="font-weight:500;font-size:28px;text-transform:uppercase;line-height:33px">Hi <?= $cus_data->FirstName ?> <?= $cus_data->LastName ?>,</span><br>
                               <!-- <span style="font-weight:500;font-size:16px;text-transform:uppercase;line-height:25px">YOUR E-MAIL AND PASSWORD</span>-->
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
                                                Your email and password				</p>
						<span style="color:#777">
							<span style="color:#333"><strong>Email:</strong></span> <?= $cus_data->Email ?><br>
							<span style="color:#333"><strong>Password:</strong> </span> <strong><?= $cus_data->Password ?></strong> <br>
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