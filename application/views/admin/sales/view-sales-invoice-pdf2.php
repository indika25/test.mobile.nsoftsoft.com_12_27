<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>
       /*@page { margin: 100px 25px; }*/
    header { position: fixed; top: 45px; left: 10px; right: 0px;  height: 10px; }
    /*footer { position: fixed; bottom: -20px; left: 20px; right: 0px; height: 10px;float: right; }*/
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    .pagenum:before { content: counter(page); }
    .footer { position: fixed; bottom: 0px; }
    .main{
      margin-bottom: 10px;
      margin-top: -25px;}
      body{margin-top: 5px;
        margin-bottom: 5px;
        margin-left: 20px;
        height: auto;}

        #tbl_est_data tbody tr td{
        padding: 3px;
    }
    #tbl_est_data2 tbody tr td{
        padding: 13px;
    }
    .text-right{
      text-align: right;
    }
</style></head><body><div class="main"><div class="row" id="printAreaPos" align="center" style='margin:0px;'><?php $this->load->view('admin/_templates/company_header_mini_pdf.php',true); ?><table style="border-collapse:collapse;width:290px;font-family: Arial, Helvetica, sans-serif;" border="0"  align="center"><tr style="text-align:center;font-size:13px;"><td colspan="4" style="border-bottom: #000 solid 1px;text-align:center;font-size:15px;font-weight: bold;"><b>&nbsp;Cash Invoice</b></td></tr><tr style="text-align:left;font-size:13px;"><td style="padding-top:0px;font-size:13px;text-align:left;">Cash Invoice No </td><td style="text-align: left;">:</td><td colspan="2" style="font-size:13px;text-align:left;"><?php echo $invHed->SalesInvNo ?></td></tr><tr style="text-align:left;font-size:13px;"><td style="text-align:left;">Invoice Date</td><td style="text-align: left;">:</td><td colspan="2" style="text-align:left;"><?php echo date('Y-m-d',strtotime($invHed->SalesDate));?>&nbsp;</td></tr><tr style="text-align:left;font-size:13px;"><td style="padding-top:0px;font-size:13px;text-align:left;">Customer </td><td style="text-align: left;">:</td><td colspan="2" style="font-size:13px;text-align:left;"><?php echo $invCus->DisplayName; ?></td></tr></div></div>
<style type="text/css" media="screen">
                  #tbl_po_data2 tbody tr td{
                      padding: 5px  !important;
                      border-bottom:1px solid #fff !important;
                  }
</style><table id="tbl_po_data" style="border-collapse:collapse;width:290px;padding:0px;font-size:15px;" border="0"><?php if($invHed->SalesInvType==2 || $invHed->SalesInvType==3){?>
<thead style="display: table-row-group;" id="taxHead"><tr><td colspan="5" style="border-top:1px solid #fff;border-left:1px solid #fff;border-right:1px solid #fff;text-align: right;"></td></tr><tr style="background-color:#5d5858 !important;color:#fff !important;line-height:20px; border-bottom:1px solid #000000; border-top:1px solid #000000; "><th style='padding: 3px;color:#060404; text-align:center;'>Item</th><th style='padding: 3px;color:#060404; text-align:center;'>Qty</th><th style='padding: 3px;color:#060404; text-align:center;' >Price</th><th style='padding: 3px;color:#060404; text-align:center;'>Dis</th><th style='padding: 3px;color:#060404; text-align:center;'>Amount</th></tr></thead><?php }elseif($invHed->SalesInvType==1){?>
<thead style="display: table-row-group;" id="taxHead"><tr><td colspan="5" style="border-top:1px solid #fff;border-left:1px solid #fff;border-right:1px solid #fff;text-align: right;"></td></tr><tr style="background-color:#5d5858 !important;color:#fff !important;line-height:20px; border-bottom:1px solid #000000; border-top:1px solid #000000; "><th style='padding: 3px;color:#060404; text-align:center;'>Item</th><th style='padding: 3px;color:#060404; text-align:center;'>Qty</th><th style='padding: 3px;color:#060404; text-align:center;' >Price</th><th style='padding: 3px;color:#060404; text-align:center;'>Dis</th><th style='padding: 3px;color:#060404; text-align:center;'>Amount</th></tr></thead> <?php } ?>
<tbody><?php $i=1; $noOfItem = count($invDtl); foreach ($invDtl AS $invdata) { if($invHed->SalesInvType==1 || $invHed->SalesInvType==3){ ?>
<tr style="line-height:15px;">
    <td colspan="5" style="text-align: left"><?php echo $invdata->SalesProductName."<br>".$invdata->SalesSerialNo;?></td>
    </tr><tr style="line-height:5px;">
        <td style="text-align:center;"><?php echo $invdata->type; ?></td>
        <td style="text-align:center;"><?php echo number_format(($invdata->SalesQty),0)?></td>
        <td style="text-align:right;"><?php echo number_format(($invdata->SalesUnitPrice),2)?></td>
        <td style="text-align:right;"><?php echo number_format(($invdata->SalesDisValue),2)?></td>
        <td style="text-align:right;"><?php echo number_format(($invdata->SalesInvNetAmount),2)?></td>
        </tr><?php $i++; } elseif($invHed->SalesInvType==2){ ?>  <tr style="line-height:15px;">
                              <td colspan="5" style=""><?php echo $invdata->SalesProductName."<br>".$invdata->SalesSerialNo;?></td></tr><tr style="line-height:5px;">
                              <td style="text-align:center;"><?php echo $invdata->type; ?></td>
                              <td style="text-align:center;"><?php echo number_format(($invdata->SalesQty),0)?></td>
                              <td style="text-align:right;"><?php echo number_format(($invdata->SalesUnitPrice),2)?></td>
                              <td style="text-align:right;"><?php echo number_format(($invdata->SalesDisValue),2)?></td>
                              <td style="text-align:right;"><?php echo number_format(($invdata->SalesTotalAmount),2)?></td>
                          </tr><?php $i++; } } ?></tbody>
<tfoot><?php $payment_term =''; if($invHed->SalesInvType==2){ ?><tr style="line-height:25px;border-top: 1px #060404 solid;" id="rowTotal"><td colspan="3" style="text-align:left;padding: 3px;border-top: 1px #060404 solid;">Total Amount</td><td style="border-top: 1px #060404 solid;">:</td><td id="lbltotalPOAmount"   style='text-align:right;padding: 3px;border-top: 1px #060404 solid;'><?php echo number_format($invHed->SalesInvAmount,2);?></td></tr><?php } else { ?><tr style="line-height:25px;border-top: 1px #060404 solid;" id="rowTotal"><td colspan="3" style="text-align:left;padding: 3px;border-top: 1px #060404 solid;">Total Amount </td><td style="border-top: 1px #060404 solid;">:</td><td id="lbltotalPOAmount"   style='font-weight:bold;text-align:right;padding: 3px;border-top: 1px #060404 solid;'><?php echo number_format($invHed->SalesInvAmount,2);?></td></tr><?php } ?><tr style="line-height:25px;" id="rowDiscount">
                      <td colspan="3" style="text-align:left">Total Dis Amount  </td>
                      <td style="">:</td>
                      <td id="lbltotalDicount"   style='font-weight:bold;text-align:right'><?php echo number_format($invHed->SalesDisAmount,2);?>&nbsp;</td>
                  </tr><?php if($invHed->SalesVatAmount>0 && $invHed->SalesInvType==2){?>
                      <tr style="line-height:25px;" id="rowVAT">
                      <td colspan="3" style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></td><td style="text-align:right">VAT Amount  </td><td id="lbltotalVat"   style='text-align:right'><?php echo number_format($invHed->SalesVatAmount,2);?></td>
                      </tr><?php } ?><?php if($invHed->SalesNbtAmount>0 && $invHed->SalesInvType==2){?><tr style="line-height:25px;" id="rowNBT"><td colspan="3" style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></td><td style="text-align:right">NBT Amount  </td><td id="lbltotalNbt"   style='text-align:right'><?php echo number_format($invHed->SalesNbtAmount,2);?></td></tr><?php } ?><?php if($invHed->SalesShipping>0){?>
                      <tr style="line-height:25px;" id="rowDiscount">
                          <td colspan="3" style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></td><td style="text-align:right"><?php echo $invHed->SalesShippingLabel; ?>  </td><td id="lbltotalDicount"   style='text-align:right'><?php echo number_format($invHed->SalesShipping,2);?></td>
                      </tr>
                      <tr>
                          <td colspan="3">&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                  <?php }?><tr style="line-height:25px;" id="rowNET">
                      <td colspan="3" style="font-weight:bold;text-align:left">Total Net Amount</td>
                      <td style="">:</td>
                      <td id="lbltotalNet"   style='font-weight:bold;text-align:right'><?php echo number_format($invHed->SalesNetAmount,2);?>&nbsp;&nbsp;</td>
                  </tr><?php if($invHed->SalesReturnAmount>0){
                      ?>
                      <tr style="line-height:25px;" id="rowNBT">
                          <td colspan="3" style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></td><td style="text-align:right">Return Amount  </td><td id="lbltotalNbt"   style='text-align:right'><?php echo number_format($invHed->SalesReturnAmount,2);?></td>
                      </tr>
                      <tr><td colspan="6">
                      Return Items
                      <p>
                      <?php  if($returnDtlArr){ foreach ($returnDtlArr AS $rtinvdata) { ?>
                          <?php echo $rtinvdata->SalesProductName ?>-<?php echo $rtinvdata->SalesReturnQty ?>, &nbsp;
                      <?php } ?></p>
                          </td></tr>
                      <?php } } ?><tr>
                      <td colspan="6">
                          <table style="width:290px; font-size:14px;" border="0">
                              <tr><td colspan="5" style="text-align:left;font-size:12px;">Numbers of Items&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $noOfItem; ?></td></tr>
                              <tr><td colspan="5" style="font-size:15px;text-align:center;">Thank You come again</td></tr>
                              <tr><td colspan="5" style="text-align:center;font-size: 9px">Any inquiries should be forwarded together with the invoice.
                                      <br>Software By Nsoft Solutions 071-6232944
                                  </td></tr>
                              <tr><td colspan="5" style="text-align:center;font-size: 9px">&nbsp;</td></tr>
                              <tr><td colspan="5" style="text-align:center;font-size: 9px">&nbsp;</td></tr>
                          </table>
                      </td>
                  </tr></tfoot>
</table><style type="text/css" media="screen">
                  #tbl_po_data tbody tr td{
                      padding:5px;
                  }
              </style>
</body></html>