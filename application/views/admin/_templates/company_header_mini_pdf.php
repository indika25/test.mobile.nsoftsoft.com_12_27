<table style="text-align:center;border-collapse:collapse;width:320px;margin:0px;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;" border="0">
    <tr style="text-align:center;font-size:19px;font-family: Arial, Helvetica, sans-serif;">
        <td colspan="5" style="font-size:19px;font-family: Arial, Helvetica, sans-serif;text-align: center;"><b> <?php echo $company['CompanyName'] ?> <?php echo $company['CompanyName2'] ?></b>


        </td>
        <td rowspan="3">
            <?php $logo =  $company['Logo']; ?>
            <!-- <img style="width: 100px;" src="<?php echo base_url($avatar_dir . '/'.$logo); ?>"  alt="logo"> -->
        </td>
    </tr>
    <tr style="text-align:center;font-size:12px;font-family: Arial, Helvetica, sans-serif;">
        <td colspan="5"><?php echo $company['AddressLine01'] ?><br><?php echo $company['AddressLine02'] ?><?php echo $company['AddressLine03'] ?></td>
    </tr>
    <tr style="text-align:center;font-size:12px;font-family: Arial, Helvetica, sans-serif;">
        <td style="padding-bottom:1px;" colspan="5"><?php echo $company['LanLineNo'] ?>, <?php echo $company['Fax'] ?> <?php echo $company['MobileNo'] ?></td>
    </tr>
    <!--    <tr style="text-align:center;font-size:14px;padding-bottom:1px;">-->
    <!--        <td colspan="6">&nbsp;</td>-->
    <!--    </tr>-->
</table>