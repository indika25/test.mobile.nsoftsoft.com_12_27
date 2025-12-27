<div class="row"  id="printArea" align="center" style='margin:5px;'>
    <!-- load comapny common header -->
    <?php $this->load->view('admin/_templates/company_header_mini.php',true); ?>

    <table style="border-collapse:collapse;width:290px;margin:0px;font-family: Arial, Helvetica, sans-serif;" border="0">
        <tr style="text-align:center;font-size:13px;">
            <!--        <td></td>-->
            <td colspan="5" style="border-bottom: #000 solid 1px;text-align:center;font-size:20px;font-weight: bold;">Invoice </td>
            <!--        <td > &nbsp;</td>-->
            <!--        <td> &nbsp;</td>-->
            <!--        <td colspan="3" style="text-align:center;font-size:20px;font-weight: bold;" >Invoice</td>-->
            <!--        <td colspan="3" style="text-align:center;font-size:20px;font-weight: bold;" ><span id="lblInvType"></span> </td>-->
        </tr>
        <!--    <tr style="text-align:left;font-size:13px;">-->
        <!--    <td colspan="2">Bill to :</td>-->
        <!--<td> &nbsp;</td>-->
        <!--        <td></td>-->
        <!--        <td></td>-->
        <!--        <td colspan="2"> &nbsp;</td>    -->
        <!--    </tr>-->
        <tr style="text-align:left;font-size:13px;">
            <!--    <td style="font-size:14px;">-->
            <!--            <span id="lblcusName"></span><br>-->
            <!--            <span id="lbladdress1"></span>-->
            <!-- <span id="lbladdress2"></span><br> -->
            <!--        </td>-->
            <!--        <td> &nbsp;</td>-->
            <td colspan="2">Cash Invoice No</td>
            <td>:</td>
            <td colspan="2" id="lblPoNo"></td>
        </tr>
        <tr style="text-align:left;font-size:13px;">
            <!--        <td> &nbsp;</td>-->
            <td colspan="2">Invoice Date</td>
            <td>:</td>
            <td colspan="2" id="lblinvDate"></td>
        </tr>
        <tr style="text-align:left;font-size:13px;">
            <td colspan="2">Customer</td>
            <td>:</td>
            <td style="padding-bottom:1px;" colspan="2" id="customerName"></td>
        </tr>
        <!--    <tr style="text-align:left;font-size:13px;">-->
        <!--    <td> &nbsp;</td>-->
        <!--        <td>Remark</td>-->
        <!--        <td>:</td>-->
        <!--        <td colspan="2" style="border-bottom:1px dashed #000;"> &nbsp;</td>-->
        <!--    </tr>-->
        <!--    <tr style="text-align:center;font-size:13px;">-->
        <!--    <td> &nbsp;</td>-->
        <!--        <td colspan="4" style="border-bottom:1px solid #000;"> &nbsp;</td>-->
        <!---->
        <!--    </tr>-->
        <!--    <tr style="text-align:right;font-size:13px;">-->
        <!--        <td colspan="2" ></td><td colspan="5" style="text-align: right;padding-top: 13px;" id="vatno">VAT Reg. No : --><?php //echo $company['Email02'] ?><!--</td>-->
        <!--    </tr>-->
    </table>
    <style type="text/css" media="screen">
        #tbl_est_data tbody tr td{
            padding: 13px;
        }

    </style><br>
    <table id="tbl_po_data" style="border-collapse:collapse;width:290px;padding:0px;font-size:13px;" border="0">
        <!--    <thead id="taxHead">-->
        <!--        <tr style="border-bottom:1px solid #000;border-top:1px solid #000;">-->
        <!--            <th style='padding: 0px;width:230px;text-align:center;'>Qty</th>-->
        <!--            <th style='padding: 0px;width:30px;text-align:center;'>Price</th>-->
        <!--            <!-- <th style='padding: 3px;'></th> -->
        <!--            <!--            <th style='padding: 3px;width:60px;'>Warranty</th>-->
        <!--            <th style='padding: 0px;width:80px;text-align:center;'>Dis</th>-->
        <!--            <th style='padding: 0px;width:80px;text-align:center;'>Amount</th>-->
        <!--        </tr>-->
        <!--    </thead>-->
        <thead  id="invHead">
        <tr style="border-bottom:1px solid #000;border-top:1px solid #000;">
            <th style='padding: 3px;color:#060404; text-align:center;'>Itm/Wrnty</th>
            <th style='padding: 0px;width:230px;text-align:center;'>Qty</th>
            <th style='padding: 0px;width:30px;text-align:center;'>Price</th>
            <!-- <th style='padding: 3px;'></th> -->
            <!--            <th style='padding: 3px;width:60px;'>Warranty</th>-->
            <th style='padding: 0px;width:80px;text-align:center;'>Dis</th>
            <th style='padding: 0px;width:80px;text-align:center;'>Amount</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot style="border-top: #000 solid 1px;">
        <tr id="rowTotal">
            <th colspan="3" style="text-align:left;padding: 3px;">Total Amount </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalPOAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowDiscount">
            <th colspan="3" style="text-align:left;padding: 3px;">Total Dis Amount  </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalDiscount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowVat" ><th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></th><th colspan="2" style="text-align:right;padding: 3px;">VAT Amount  </th><th id="lbltotalVatAmount"   style='text-align:right;padding: 3px;'></th></tr>
        <tr id="rowNbt"><th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></th><th colspan="2" style="text-align:right;padding: 3px;">NBT Amount  </th><th id="lbltotalNbtAmount"   style='text-align:right;padding: 3px;'></th></tr>
        <tr id="rowNet">
            <th colspan="3"  style="text-align:left;padding: 3px;">Total Net Amount  </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalPONetAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowAdvance">
            <th colspan="3" style="text-align:left;padding: 3px;">Advance Amount  </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalPOAdvanceAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowCash">
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></th>
            <th colspan="3" style="text-align:right;padding: 3px;">Cash Amount  </th>
            <th id="lbltotalCashAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowCredit">
            <th colspan="3" style="text-align:left;padding: 3px;">Credit Amount  </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalCreditAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowCheque"><th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></th><th colspan="2" style="text-align:right;padding: 3px;">Cheque Amount  </th><th id="lbltotalChequeAmount"   style='text-align:right;padding: 3px;'></th></tr>
        <tr id="rowCard"><th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;"></th><th colspan="2" style="text-align:right;padding: 3px;">Card Amount  </th><th id="lbltotalCardAmount"   style='text-align:right;padding: 3px;'></th></tr>
         <tr id="rowTotal">
            <th colspan="3" style="text-align:left;padding: 3px;">Total Paid Amount  </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalPaidAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        <tr id="rowTotal">
            <th colspan="3" style="text-align:left;padding: 3px;">Blance Amount </th>
            <th style="border-left: 1px #fff solid;border-bottom: 1px #fff solid;">:</th>
            <th id="lbltotalBlanceAmount"   style='text-align:right;padding: 3px;'></th>
        </tr>
        </tfoot>
    </table>
    <table style="width:290px;" border="0">
        <tr>
            <td colspan="3" style="text-align:left;">Numbers of Items :</td>
            <td colspan="4" style="text-align:left;" id="noOfItem"></td>
        </tr>
        <tr><td colspan="6" style="text-align:center;">&nbsp;</td></tr>
        <tr><td colspan="6" style="text-align:center;font-size:18px;">Thank You come again</td></tr>
        <tr><td colspan="6" style="text-align:center;font-size:10px;"><i>Any inquiries should be forwarded together with the invoice.</i></td></tr>
        <tr><td colspan="6" style="text-align:center;font-size:10px;"><i>විකුණුම් සම්බන්ධ විමසුමකදී මෙම බිල්පත් රැගෙන ඒම අනිවාර්ය වේ. </i></td></tr>
        <tr><td colspan="6" style="text-align:center;font-size:9px;">Software By Nsoft Solutions www.nsoft.lk</td></tr>
        <tr><td colspan="6" style="text-align:right;">&nbsp;</td></tr>
        <!--           <tr><td colspan="5" style="text-align:right;">&nbsp;</td></tr> <tr>-->
        <!--            <td style="border-bottom:1px dashed #000;width:100px" >&nbsp;</td>-->
        <!--            <td style="">&nbsp;</td>-->
        <!--            <td style="border-bottom:1px dashed #000;width:200px">&nbsp;</td>-->
        <!--            <td style="">&nbsp;</td>-->
        <!--           <td style="border-bottom:1px dashed #000;width:200px">&nbsp;</td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td style="width:100px;text-align: center">Prepared By</td>-->
        <!--            <td style="">&nbsp;</td>-->
        <!--            <td style="width:200px;text-align: center">Approved By</td>-->
        <!--            <td style="">&nbsp;</td>-->
        <!--            <td style="width:200px;text-align: center">Customer Signature</td>            -->
        <!--        </tr>-->
    </table>
</div>