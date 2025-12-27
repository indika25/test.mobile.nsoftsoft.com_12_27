<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content-wrapper">
    <section class="content-header">
        <?php echo $pagetitle; ?>
        <?php echo $breadcrumb; ?>
    </section>
    <section class="content">
        <style type="text/css">
        .icheckbox_square-blue,
        .iradio_square-blue {
            background-color: #fff;
        }
        </style>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success container-fluid">
                    <div class="row" style="background: #5fbfba;"><br>
                        <style>
                        .form-group {
                            margin-bottom: 5px;
                        }
                        </style>
                        <div class="col-md-4">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="customer" class="col-sm-4 control-label">Tranfer No<span
                                            class="required">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" tabindex="1" class="form-control" value="<?php echo $inv; ?>"
                                            name="tranfer_no" id="tranfer_no" placeholder="Auto Generate">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tranfer_Date" class="col-sm-4 control-label">Tranfer Date <span
                                            class="required">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="date" tabindex="1" class="form-control"
                                            required="required" name="tranfer_Date" id="tranfer_Date" placeholder="">
                                        <input type="hidden" class="form-control" required="required" name="location"
                                            id="invlocation" value="<?php echo $_SESSION['location'] ?>">
                                        <input type="hidden" class="form-control" required="required" name="invUser"
                                            id="invUser" value="<?php echo $_SESSION['user_id'] ?>">
                                        <input type="hidden" class="form-control" required="required" name="maxSerial"
                                            id="maxSerial">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Price Level</label>
                                    <div class="col-sm-7">
                                        <select tabindex="7" class="form-control" id="priceLevel">
                                            <?php foreach ($pricelevels as $pl) { ?>
                                            <option value="<?php echo $pl->PL_No; ?>"
                                                <?php if ($pl->PL_No == 1) {echo 'selected';}?>>
                                                <?php echo $pl->PriceLevel; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>



                            </form>
                        </div>
                        <div class="col-md-5">
                            <row class="form-horizontal">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">From</label>
                                    <div class="col-sm-7">
                                        <select tabindex="7" class="form-control" id="from">
                                            <?php foreach ($locations as $location) { ?>
                                            <option value="<?php echo $location->location_id; ?>"
                                                <?php ?>>
                                                <?php echo $location->location; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
 
                                    <label class="col-sm-4 control-label">To</label>
                                    <div class="col-sm-7">
                                        <select tabindex="7" class="form-control" id="to">
                                            <?php foreach ($locations as $location) { ?>
                                            <option value="<?php echo $location->location_id; ?>"
                                                <?php ?>>
                                                <?php echo $location->location; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                            </row>

                        </div>

                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-4">
                            <div class="box-body">
                                <form class="form-horizontal" id="formProduct">
                                    
                                   <div class="form-group">
                                        <div>
                                            <label for="itemCode" class="col-sm-4 control-label"><span class="required"></span></label>
                                            <div class="col-sm-8"><span id="productName" style="font-size: 11px; color: black;"><b></b></span>&nbsp;
                                                <span id="productStock" style="font-size: 11px; color: black;"><b></b></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div>
                                            <label for="itemCode" class="col-sm-4 control-label"><span class="required"></span></label>
                                            <div class="col-sm-8"><span id="productName" style="font-size: 11px; color: black;"><b></b></span>&nbsp;
                                                <span id="priceStock" style="font-size: 11px; color: black;"><b></b></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div id="lbl_refCode">
                                            <label for="itemCode" class="col-sm-4 control-label">Product <span
                                                    class="required"></span></label>
                                            <div class="col-sm-8 input-group">
                                                
                                                <input type="text" tabindex="9" name="itemCode" id="itemCode"
                                                    class="form-control" onfocus="this.select();">
                                         
                                                <span class="input-group-btn">
                                                    <button class="btn btn-warning" id="addpro"><i
                                                            class="fa fa-plus"></i></button>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div id="productLable">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Unit Or Case</label>
                                            <div class="col-sm-4">
                                                <select required="required" tabindex="9" class="form-control"
                                                    name="mUnit" id="mUnit">
                                                    <option value="UNIT">Unit</option>
                                                    <option value="CASE">Case</option>
                                                </select>
                                            </div>
                                           
                                        </div>
                                        <div class="form-group">
                                            <label for="product" class="col-sm-4 control-label">Qty <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="number" tabindex="10" min="0" step="1" class="form-control"
                                                    required="required" name="qty" id="qty" placeholder="Enter Qty"
                                                    value="0" onfocus="this.select();">
                                            </div>
                                            
                                       
                                        </div>
                                        
                            
                                        <div class="form-group">
                                            <label for="sellingPrice" class="col-sm-4 control-label">Selling Price <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="number" tabindex="13" min="0" step="1" class="form-control"
                                                    required="required" name="sellingPrice" id="sellingPrice"
                                                    placeholder="Enter Selling Price" onfocus="this.select();">

                                                <input type="hidden" disabled class="form-control" required="required"
                                                    name="prdName" id="prdName" placeholder="Enter product Code">
                                                <input type="hidden" class="form-control" required="required"
                                                    name="batchCode" id="batchCode">
                                                <input type="hidden" class="form-control" required="required" name="upc"
                                                    id="upc">
                                                <input type="hidden" class="form-control" required="required"
                                                    name="isSerial" id="isSerial">
                                                <input type="hidden" class="form-control" required="required"  name="isEmi" id="isEmi">
                                                <input type="hidden" class="form-control" required="required"
                                                    name="orgSellPrice" id="orgSellPrice">
                                            </div>
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="unitcost" class="col-sm-4 control-label">Cost Price <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="number" tabindex="13" min="0" step="1" class="form-control"
                                                    required="required" name="unitcost" id="unitcost" readonly
                                                     onfocus="this.select();">

                                            </div>
                                           
                                        </div>
                                        
                                        <div class="form-group" id="dv_SN">
                                            <label for="product" class="col-sm-4 control-label">Serial No <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-6">
                                                <input type="text" tabindex="14" class="form-control"
                                                    required="required" name="serialNo" id="serialNo"
                                                    placeholder="Enter Serial No" value="" onfocus="this.select();">
                                                <input type="hidden" tabindex="14" class="form-control"
                                                    required="required" name="serialQty" id="serialQty" value="0">
                                                <input type="hidden" tabindex="14" class="form-control"
                                                    name="serialNoCheck" id="serialNoCheck">
                                            </div>
                                        </div>

                                         <div class="form-group" id="emiDiv"> 
                                             <label for="sellingPrice"class="col-sm-4 control-label">Emi No <span class="required">*</span></label>
                                             <div class="col-sm-6">
                                                 <input type="text"  tabindex="13"  min="0" step="1" class="form-control" name="emiNo" id="emiNo" readonly>
                                                 
                                             </div>
                                         </div>
                                        <div class="form-group">
                                            <input type="hidden" name="salesperson" id="salesperson" value="0">
                                        
                                        </div>
                                        
                                        
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="rank" class="col-sm-4 control-label">&nbsp;</label>
                                        <button tabindex="18" type="button" id="addItem" class="btn btn-primary ">Add
                                            Item</button>
                                    </div>
                                </form>
                            </div><!-- /.box-body -->
                        </div>
                        <div class="col-md-8">
                            <h5 class="text-center"><b>Invoice Item List</b></h5>

                            <div class="table-responsives">
                                <table id="tbl_item" class="table table-bordered table-striped table-responsives">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Product</th>
                                            <th>Cost</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            
                                            <th>Total Amount</th>
                                            <th>Serial</th>
                                            <th>IEMI No</th>
                                            <!--<th>Sale person</th>-->
                                            <th>##</th>
                                            <th>##</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>

                                </table>
                                <div class="form-group">
                                    <label> Remarks</label>
                                    <textarea class="form-control" name="remark" id="remark"></textarea>
                                </div>
                            </div>
                            <div class="text-right">
                                <span id="errData"></span>
                                 
                                <button tabindex="19" id="onlySaveItems" class="btn btn-primary"><i
                                        class="fa fa-save"></i> Save</button>&nbsp;
                
                                 <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- customer modal -->
    <div id="customermodal" class="modal fade bs-addcustomer-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg" style="width: 95%;">
            <div class="modal-content">
                <!-- load data -->
            </div>
        </div>
    </div>
    <!-- customer modal -->
    <div id="vehiclemodal" class="modal fade bs-addcustomer-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg" style="width: 95%;">
            <div class="modal-content">
                <!-- load data -->
            </div>
        </div>
    </div>
    <!--invoice print-->
    <div class="modal fade bs-print-modal-lg" id="modelInvoice" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <?php //invoice print 
                    $this->load->view('admin/sales/sales_invoice_print.php',true); ?>
                </div>
            </div>
        </div>
    </div>
    <!--payment model-->
    <div class="modal fade bs-addpayment-modal-lg" id="modelPayment" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <form role="form" id="addDep" data-parsley-validate method="post" action="#">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true"><i class="fa fa-remove"></i></span></button>
                        <h4 class="modal-title" id="myModalLabel2">Payment Details <span id="errPayment"></span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <form class="form-horizontal">
                                <div class="col-md-4">
                                    <b><span id="casAmount"></span></b>

                                    <!--<div class="form-group">-->
                                    <!--<h4 class="text-center">Payment Type : <span class="discount_type">: <span class="label label-primary">percentage</span></span></h4>-->
                                    <div class="input-group">
                                        <span class="input-group-addon label-success">Cash Amount</span>
                                        <input type="number" name="cash_amount" id="cash_amount" min='0' value="0"
                                            step="50" class="form-control" placeholder="cash amount"
                                            onfocus="this.select();">
                                    </div><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Card Amount</span>
                                        <input type="number" disabled name="card_amount" id="card_amount" min='0'
                                            value="0" class="form-control" placeholder="card amount">
                                    </div><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Return Amount</span>
                                        <input type="number" disabled name="return_amount" id="return_amount" min='0'
                                            value="0" class="form-control" placeholder="Return Amount">
                                    </div><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Advance Amount</span>
                                        <input type="number" disabled name="advance_amount" id="advance_amount" min='0'
                                            value="0" class="form-control" placeholder="Advance Amount">
                                        <input type="hidden" name="before_advance_amount" id="before_advance_amount"
                                            value="0" class="form-control">
                                    </div><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Bank Amount</span>
                                        <input type="number" name="bank_amount" id="bank_amount" min='0' value="0"
                                            class="form-control" placeholder="Bank Amount">
                                    </div><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Cheque Amount</span>
                                        <input type="number" name="cheque_amount" id="cheque_amount" min='0' value="0"
                                            class="form-control" placeholder="cheque amount" onfocus="this.select();">
                                    </div><br>
                                    <!-- <div class="input-group">
                                        <span class="input-group-addon">Credit Amount</span>
                                        <input type="number" name="credit_amount" id="credit_amount" min='0'  value="0"  step="50"  class="form-control" placeholder="credit amount">
                                    </div> -->
                                    <div class="input-group">
                                        <span class="input-group-addon">Credit Amount</span>
                                        <input type="number" name="credit_amount" id="credit_amount" min='0' value="0"
                                            step="50" class="form-control" placeholder="credit amount"
                                            onfocus="this.select();">
                                        <div class="input-group-btn">
                                            <button class="btn btn-warning" id="addCredit"><i
                                                    class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Cash</td>
                                                <td>:</td>
                                                <td id='mcash' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Card</td>
                                                <td>:</td>
                                                <td id='mcard' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Advance</td>
                                                <td>:</td>
                                                <td id='madvance' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Return</td>
                                                <td>:</td>
                                                <td id='mareturn' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Bank</td>
                                                <td>:</td>
                                                <td id='mbank' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Cheque</td>
                                                <td>:</td>
                                                <td id='mcheque' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Credit</td>
                                                <td>:</td>
                                                <td id='mcredit' class='text-right'>0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--</div>-->
                                </div>
                                <div class="col-md-4">
                                    <b><span id="advanceAmountTotal"></span></b>
                                    <div class="form-group">
                                        <span class="label">Advance Payment</span>
                                        <input type="text" name="advance_payment_no" id="advance_payment_no"
                                            class="form-control" placeholder="Advace Payment No">
                                    </div>
                                    <div class="form-group" style="display:;">
                                        <span class="label">Return Payment</span>
                                        <input type="text" name="return_payment_no" id="return_payment_no"
                                            class="form-control" placeholder="Return Payment No">
                                    </div>
                                    <hr>
                                    <div class="input-group">
                                        <span class="input-group-addon">Card type</span>
                                        <select name="card_type" id="card_type" class="form-control">
                                            <option value="0">Select a type</option>
                                            <option value="1">Visa</option>
                                            <option value="2">Master</option>
                                            <option value="3">Amex</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Reference</span>
                                        <input type="text" name="card_ref" id="card_ref" class="form-control"
                                            placeholder="Reference">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Amount</span>
                                        <input type="number" name="ccard_amount" id="ccard_amount" min='0' step="50"
                                            value="0" class="form-control" placeholder="card amount">
                                        <span class="input-group-btn"><button class="btn btn-primary" id='addCard'
                                                type="button">Add</button></span>
                                    </div>
                                    <h4>Card details</h4>
                                    <label id="errCard"></label>
                                    <table class="table table-hover" id='tblCard'>
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Ref</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div id='bankData'>
                                        <hr>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="bank" class="control-label">Bank Account<span
                                                        class="required">*</span></label>
                                                <select class="form-control" required="required" name="bank_acc"
                                                    id="bank_acc">
                                                    <option value="">Select a Bank Account</option>
                                                    <?php foreach($bank_acc as $banks){?>
                                                    <option value="<?php echo $banks->acc_id; ?>">
                                                        <?php echo $banks->acc_name." / ".$banks->acc_no." - ".$banks->BankName; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Total</td>
                                                <td>:</td>
                                                <td id='mtotal' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>VAT</td>
                                                <td>:</td>
                                                <td id='mvat' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>NBT</td>
                                                <td>:</td>
                                                <td id='mnbt' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Discount</td>
                                                <td>:</td>
                                                <td id='mdiscount' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td id="">Shipping</td>
                                                <td>:</td>
                                                <td id='mshipping' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td>Net Payable</td>
                                                <td>:</td>
                                                <td id='mnetpay' class='text-right'>0.00</td>
                                            </tr>
                                            <tr>
                                                <td id='changeLable'>Change/Refund</td>
                                                <td>:</td>
                                                <td id='mchange' class='text-right'>0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <input type="hidden" name="mchange1" id="mchange1">
                                        <div class="col-md-11">
                                            <div class="input-group">
                                                <span class="input-group-addon">Comission</span>
                                                <input type="text" name="com_amount" id="com_amount"
                                                    class="form-control" placeholder="Commission Amount"
                                                    onfocus="this.select();">
                                            </div> <br>
                                            <div class="input-group">
                                                <span class="input-group-addon">Pay to</span>
                                                <input type="text" name="compayto" id="compayto" class="form-control"
                                                    placeholder="Commission Pay to" onfocus="this.select();">
                                                <input type="hidden" name="compaytoid" id="compaytoid"
                                                    class="form-control" placeholder="Commission Pay to"
                                                    onfocus="this.select();">
                                            </div><br>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="input-group">If item receiver different from customer</div>
                                            <div class="input-group">
                                                <span class="input-group-addon">Name</span>
                                                <input type="text" name="receiver_name" id="receiver_name"
                                                    class="form-control" placeholder="Receiver Name"
                                                    onfocus="this.select();">
                                            </div> <br>
                                            <div class="input-group">
                                                <span class="input-group-addon">NIC</span>
                                                <input type="text" name="receiver_nic" id="receiver_nic"
                                                    class="form-control" placeholder="Receiver Nic"
                                                    onfocus="this.select();" minlength="10" maxlength="12">
                                            </div><br>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id='chequeData'>
                                    <hr>
                                    <h4>Cheque Details</h4>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bank" class="control-label">Bank <span
                                                    class="required">*</span></label>
                                            <select class="form-control" name="bank" id="bank">
                                                <option value="">Select a Bank</option>
                                                <?php foreach($bank as $banks){?>
                                                <option value="<?php echo $banks->BankCode; ?>">
                                                    <?php echo $banks->BankName; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chequeNo" class="control-label">Cheque No <span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="chequeNo" id="chequeNo">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chequeReciveDate" class="control-label">Cheque Received date
                                                <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="chequeReciveDate"
                                                id="chequeReciveDate">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chequeDate" class="control-label">Date of Cheque<span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="chequeDate" id="chequeDate">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chequeReference" class="control-label">Cheque Reference<span
                                                    class="required"></span></label>
                                            <textarea class="form-control" name="chequeReference" id="chequeReference">

                                    </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                        <button type="button" id="saveItems" class="btn btn-success btn-lg">Confirm Payment</button>
                    </div>

                </div>
            </form>
        </div>
    </div>


</div>
<script>
$('.prd_icheck').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '50%'
});
$("#supplier").select2({
    minimumInputLength: 1
});
</script>
<style type="text/css">
ul.ui-autocomplete {
    z-index: 1100;
}
</style>