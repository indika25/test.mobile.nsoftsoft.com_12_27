<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// echo $prnNo;die;
?>

<div class="content-wrapper">
    <section class="content-header">
        <?php echo $pagetitle; ?>
        <?php echo $breadcrumb; ?>
    </section>

    <section class="content">
        <div class="box collapse cart-options" id="collapseExample">
            <div class="box-header">Filter Categories</div>
            <div class="box-body categories_dom_wrapper">
            </div>
            <div class="box-footer">
                <button class="btn btn-primary close-item-options pull-right">Hide options</button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table">

                                    <tr>
                                        <td>Trans No</td>
                                        <td>:</td>
                                        <td class="text-right"><?php echo $stockhead->TrnsNo;?></td>
                                    </tr>
                                    <tr>
                                        <td>Trans Date</td>
                                        <td>:</td>
                                        <td class="text-right"><?php echo $stockhead->TrnsDate;?></td>
                                    </tr>
                                    <tr>
                                        <td>From Location</td>
                                        <td>:</td>
                                        <td class="text-right"><?php echo $stockhead->from_location_name;?></td>
                                    </tr>
                                    <tr>
                                        <td>To Location</td>
                                        <td>:</td>
                                        <td class="text-right"><?php echo $stockhead->to_location_name;?></td>
                                    </tr>
                                    

                                </table>

                            </div>
                            <!-- <div class="col-md-4">

                                <table class="table">
                                    <tr><td></td><td></td><td class="text-right"></td></tr>
                                    <tr><td>CostAmount</td><td>:</td><td class="text-right"><?php echo number_format($stockhead->CostAmount,2);?></td></tr>
                                </table>
                            </div> -->
                            <div class="col-md-4">
                                <div class="pull-right">
                                    <button type="button" id="btnPrint"
                                        class="btn btn-default btn-lg btn-block">Print</button>
                                </div>
                            </div>
                        </div>





                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">


                            <table id="tbl_payment" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Serial No</th>
                                        <th>Emi No</th>
                                        <th>Quantity</th>
                                        <th>Unit/Case</th>
                                        <th>Unit Cost</th>
                                        <th>Selling Price</th>
                                        <!-- <th>Amount</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1;
                                 foreach ($stockheadDtls AS $stockheadDtl) { ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $stockheadDtl->ProductCode ?></td>
                                        <td><?php echo $stockheadDtl->Prd_Description ?></td>
                                        <td><?php echo $stockheadDtl->Serial ?></td>
                                        <td><?php echo $stockheadDtl->EmiNo ?></td>
                                        <td><?php echo number_format($stockheadDtl->TransQty,2) ?></td>
                                        <td><?php echo ($stockheadDtl->CaseOrUnit) ?></td>
                                        <td><?php echo number_format($stockheadDtl->CostPrice,2) ?></td>
                                        <td><?php echo number_format($stockheadDtl->SellingPrice,2) ?></td>
                                        <!-- <td><?php echo number_format($stockheadDtl->TransAmount,2) ?></td> -->
                                    </tr>
                                    <?php $i++; } ?>
                                </tbody>
                                <thead>

                                </thead>

                            </table>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
    <div id="customermodal" class="modal fade bs-add-category-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="cusModal">
                <!-- load data -->
            </div>
        </div>
    </div>
    <!--invoice print-->
    <div class="modal fade bs-payment-modal-lg" id="modelInvoice" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="row" id="printArea" align="center" style='margin:5px;'>
                <!-- load comapny common header -->
                <?php $this->load->view('admin/_templates/company_header.php',true); ?>

                <table
                    style="border-collapse:collapse;width:700px;margin:5px;font-family: Arial, Helvetica, sans-serif;"
                    border="0">


                    <tr>
                        <td>Trans No</td>
                        <td>:</td>
                        <td class="text-right"><?php echo $stockhead->TrnsNo;?></td>
                    </tr>
                    <tr>
                        <td>Trans Date</td>
                        <td>:</td>
                        <td class="text-right"><?php echo $stockhead->TrnsDate;?></td>
                    </tr>
                    <tr>
                        <td>From Location</td>
                        <td>:</td>
                        <td class="text-right"><?php echo $stockhead->from_location_name;?></td>
                    </tr>
                    <tr>
                        <td>To Location</td>
                        <td>:</td>
                        <td class="text-right"><?php echo $stockhead->to_location_name;?></td>
                    </tr>

                </table>
                <style type="text/css" media="screen">
                #tbl_est_data tbody tr td {
                    padding: 13px;
                }
                </style>
                <table id="tbl_est_data" style="border-collapse:collapse;width:700px;padding:5px;font-size:13px;"
                    border="1">
                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit/Case</th>
                            <th>Unit Cost</th>
                            <th>Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;
                                 foreach ($stockheadDtls AS $stockheadDtl) { ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $stockheadDtl->ProductCode ?></td>
                            <td><?php echo $stockheadDtl->Prd_Description ?></td>
                            <td><?php echo number_format($stockheadDtl->TransQty,2) ?></td>
                            <td><?php echo ($stockheadDtl->CaseOrUnit) ?></td>
                            <td><?php echo number_format($stockheadDtl->CostPrice,2) ?></td>
                            <td><?php echo number_format($stockheadDtl->SellingPrice,2) ?></td>
                            <!-- <td><?php echo number_format($stockheadDtl->TransAmount,2) ?></td> -->
                        </tr>
                        <?php $i++; } ?>
                    </tbody>

                </table>
                <table style="width:700px;" border="0">
                    <tr>
                        <td colspan="5" style="text-align:left;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:right;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px dashed #000;width:100px">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="border-bottom:0px dashed #000;width:200px">&nbsp;</td>
                        <td style="">&nbsp;</td>
                        <td style="border-bottom:1px dashed #000;width:200px">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width:100px;text-align: center">Approved By</td>
                        <td style="">&nbsp;</td>
                        <td style="width:200px;text-align: center"></td>
                        <td style="">&nbsp;</td>
                        <td style="width:200px;text-align: center">Signature</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>




</div>
<style>
.shop-items:hover {
    background-color: #00ca6d;
    color: #fff;
}
</style>
<script type="text/javascript">
$("#btnPrint").click(function() {
    $('#printArea').focus().print();
});
</script>