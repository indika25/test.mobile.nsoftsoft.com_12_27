<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <?php echo $pagetitle; ?>
        <?php echo $breadcrumb; ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <form id="filterform">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="productsearch" id="productsearch">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <!--                                    <div class="form-group">-->
                                    <!--                                        <label for="isall" class="control-label">-->
                                    <!--                                            <input class="rpt_icheck" type="checkbox" name="isall">-->
                                    <!--                                            All-->
                                    <!--                                        </label>-->
                                    <!--                                    </div>-->
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" name="route" id="route" multiple>
                                            <option value="">--select location--</option>
                                            <?php foreach ($locations AS $loc) { ?>
                                            <option value="<?php echo $loc->location_id ?>"><?php echo $loc->location ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="route_ar" id="route_ar">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-flat btn-success">Show</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button onclick="printdiv()" class="btn btn-flat btn-default">Print</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <!--                                <div class="col-md-3">-->
                                <!--                                    <div class="form-group">-->
                                <!--                                        <select class="form-control" name="department" id="department" multiple>-->
                                <!--                                            <option value="">--select a department--</option>-->
                                <!--                                        </select>-->
                                <!--                                        <input type="hidden" name="dep_ar" id="dep_ar">-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="col-md-3">-->
                                <!--                                    <div class="form-group">-->
                                <!--                                        <select class="form-control" name="subdepartment" id="subdepartment" multiple>-->
                                <!--                                            <option value="">--select a sub department--</option>-->
                                <!--                                        </select>-->
                                <!--                                        <input type="hidden" name="subdep_ar" id="subdep_ar">-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="col-md-3">-->
                                <!--                                    <div class="form-group">-->
                                <!--                                        <select class="form-control" name="subcategory" id="subcategory" multiple>-->
                                <!--                                            <option value="">--select a sub category--</option>-->
                                <!--                                        </select>-->
                                <!--                                        <input type="hidden" name="subcategory_ar" id="subcategory_ar">-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="col-md-3" >-->
                                <!--                                    <select class="form-control" style="display:none;" name="supplier" id="supplier">-->
                                <!--                                        <option value="">--select a supplier--</option>-->
                                <!--                                    </select>-->
                                <!--<button type="reset" class="btn btn-flat btn-danger">Reset</button>-->
                                <!--                                </div>-->

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body  table-responsive">
                        <table id="saletable" class="table table-bordered  table-hover table-fixed">
                            <thead>
                                <tr style="font-size: large">
                                    <td>id</td>
                                    <td>Product Code</td>
                                    <td>Product Name</td>
                                    <td>Location</td>
                                    <td>Emei No/ Serial No</td>
                                    <td>Stock</td>
                                    <td>ROL</td>
                                    <td>ROQ</td>
                                    <td>Cost Price</td>
                                    <td>Selling Price</td>
                                    <td>Supplier</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--        </form>-->
        <!--print view modal-->
        <div id="salesbydateprint" class="modal fade bs-add-category-modal-lg" tabindex="-1" role="dialog"
            aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- load data -->
                </div>
            </div>
        </div>
    </section>
</div>
<script>
$("#subcategory").select2({
    placeholder: "Select a model"
});

$("#department").select2({
    placeholder: "Select a department"
});
$("#subdepartment").select2({
    placeholder: "Select a sub department"
});

$("#route").select2({
    placeholder: "Select a location"
});

var loc = [];
$("#route").change(function() {
    loc.length = 0;

    $("#route :selected").each(function() {
        loc.push($(this).val());
    });
    $("#route_ar").val(JSON.stringify(loc));
});


var sub = [];
var depArr = [];
var subdepArr = [];
$("#subcategory").change(function() {
    sub.length = 0;

    $("#subcategory :selected").each(function() {
        sub.push($(this).val());
    });
    $("#subcategory_ar").val(JSON.stringify(sub));
});

$('.rpt_icheck').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '50%'
});
var dep = 0;
var subdep = 0;
$("#productsearch").select2({
    placeholder: "Select a product",
    allowClear: true,
    ajax: {
        url: baseUrl + '/report/productjson',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});

$("#supplier").select2({
    placeholder: "Select a supplier",
    allowClear: true,
    ajax: {
        url: baseUrl + '/report/supplierjson',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});

$("#department").select2({
    placeholder: "Select a Department",
    ajax: {
        url: baseUrl + '/report/departmentjson',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});
$("#department").change(function() {
    dep = $("#department option:selected").val();
    $("#subdepartment").select2('val', '');
    depArr.length = 0;

    $("#department :selected").each(function() {
        depArr.push($(this).val());
    });
    if (depArr.length == 0) {
        $("#dep_ar").val('');
    } else {
        $("#dep_ar").val(JSON.stringify(depArr));
    }

});

$("#subdepartment").change(function() {
    subdep = $("#subdepartment option:selected").val();
    $("#subcategory").select2('val', '');
    subdepArr.length = 0;

    $("#subdepartment :selected").each(function() {
        subdepArr.push($(this).val());
    });

    if (subdepArr.length == 0) {
        $("#subdep_ar").val('');
    } else {
        $("#subdep_ar").val(JSON.stringify(subdepArr));
    }
});




$("#subdepartment").select2({
    placeholder: "Select a Sub Department",
    ajax: {
        url: baseUrl + '/report/subdepartmentjson',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term,
                dep: dep
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});

$("#subcategory").select2({
    placeholder: "Select a sub Category",
    allowClear: true,
    ajax: {
        url: baseUrl + '/report/subcategoryjson',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term,
                dep: dep,
                subdep: subdep
            };
        },
        processResults: function(data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});


$('#filterform').submit(function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: baseUrl + '/grn/loadstockAdjustment',
        data: $(this).serialize(),
        success: function(data) {
            console.log(data);
            $('#saletable tbody').empty();
            drawTable(JSON.parse(data));



        }
    });
});

$("#saletable").on('click', '.edit', function() {
    var id = $(this).attr("value");
    var stockChange = parseFloat($("#stockChange_" + id).val());
    var $row = $(this).closest("tr");
    var invoiceNo = $row.find("td:eq(4)").text().trim();
    var price = $("#priceChange_" + id).val();
    var unitCost = $("#unitCostChange_" + id).val();



    if (invoiceNo != '--') {
        if (selectedEmeiNo !== undefined && selectedEmeiNo !== null &&
            selectedEmeiNo !== '' && selectedEmeiNo != 0) {
            var qty = parseFloat(stockChange);
            if (isNaN(qty) || qty < 0 || qty > 1) {
                $.notify("Stock must be between 0 and 1", "warning");
                return;
            }

        } else {
            $.notify("Please select a valid EMEI", "warning");
            return;
        }
    }

    if (confirm(
            "Are you want to Update Stock ? Please Check Is correct Product Code"
        )) {

      

        $.ajax({
            type: "post",
            url: baseUrl + '/grn/updateStock',
            data: {
                id: id,
                stockChange: stockChange,
                emeiNo: selectedEmeiNo,
                price:price
            },
             dataType: 'json', 
            success: function(json) {
                location.reload();
            }
        });
    } else {

    }
});

var totalCostValue = 0;
var totalValue = 0;
var totalProfit = 0;

function drawTable(data) {
    totalCostValue = 0;
    totalValue = 0;
    totalProfit = 0;
    totalStock = 0;
    $("#totalcost").html(0);
    $("#totalvalue").html(0);
    $("#totalprofit").html(0);
    $("#totalstock").html(0);

    $.each(data, function(key, value) {
        $("#saletable").append("<tr style='background-color:#00a678;color:#fff;'><td colspan='13'><b>" + key +
            "</b></td></tr>");

        for (var i = 0; i < value.length; i++) {
            drawRow(value, i);
            totalStock += (parseFloat(value[i].Stock));
            totalCostValue += ((value[i].Prd_CostPrice) * (value[i].Stock));
            totalValue += ((value[i].ProductPrice) * (value[i].Stock));
            totalProfit += ((value[i].ProductPrice) * (value[i].Stock)) - ((value[i].Prd_CostPrice) * (value[i]
                .Stock));
            $("#totalcost").html(accounting.formatMoney(totalCostValue));
            $("#totalvalue").html(accounting.formatMoney(totalValue));
            $("#totalprofit").html(accounting.formatMoney(totalProfit));
            $("#totalstock").html(accounting.formatMoney(totalStock));
        }
    });
}






var selectedEmeiNo;

function drawRow(rowData, index) {

    if (parseFloat(rowData[index].Stock) < rowData[index].Prd_ROL) {
        var row = $("<tr class='stockout'>");
    } else {
        var row = $("<tr>");
    }

    $("#saletable").append(row);
    row.append($("<td>" + (index + 1) + "</td>"));
    row.append($("<td>" + rowData[index].ProductCode + "</td>"));
    row.append($("<td>" + rowData[index].Prd_Description + "</td>"));
    row.append($("<td>" + rowData[index].location + "</td>"));

    if (rowData[index].emei_list && rowData[index].emei_list.length > 0) {

        var select = $("<select></select>")
            .attr("name", "serialEmi_" + rowData[index].ProductCode)
            .attr("id", "serialEmi_" + rowData[index].ProductCode)
            .attr("data-product", rowData[index].ProductCode)
            .addClass("form-control");

        select.append($("<option></option>").val("").text("Select"));

        rowData[index].emei_list.forEach(function(emei) {
            select.append($("<option></option>").val(emei).text(emei));
        });

        select.on("change", function() {
            var selected = $(this).val();


            var productCode = $(this).attr("data-product");
            selectedEmeiNo = selected;

            if (selected !== "") {
                $.ajax({
                    url: baseUrl + "/grn/getEmeiStocktoProduct",
                    type: "POST",
                    data: {
                        emei: selected.trim(),
                        product: productCode.trim()
                    },
                    dataType: "json",
                    success: function(res) {
                      
                        if (res.status) {
                            var qty = res.quantity;
                            var price = res.price;
                            var unitCost = res.unitCost;

                              var input = $("#stockChange_" + productCode);
                            var td = input.parent();


                            td.contents().filter(function() {
                                return this.nodeType === 3;
                            }).remove();


                            input.val(qty);
                            input.after(document.createTextNode(qty));

                            var priceInput = $("#priceChange_" + productCode);
                            priceInput.val(price);


                            var priceInput = $("#priceChange_" + productCode);
                            priceInput.val(price);
                            var unitCostInput = $("#unitCostChange_" + productCode);
                            unitCostInput.val(unitCost);


                        } else {
                            alert("No stock for this EMEI");
                        }
                    },
                    error: function() {
                        alert("Error fetching stock.");
                    }
                });
            } else {
                alert("Select valid EMEI/Serial.");
            }
        });


        row.append($("<td></td>").append(select));

    }

    else if(rowData[index].serial_list && rowData[index].serial_list.length > 0){
        row.append($("<td>--</td>"));
    row.append($("<td><input class='form-control' type='number' step='any' value='" + rowData[index].Stock +
        "' name='stockChange_" + rowData[index].ProductCode + "' id='stockChange_" + rowData[index]
        .ProductCode + "'>" + rowData[index].Stock + "</td>"));

    row.append($("<td>" + rowData[index].Prd_ROL + "</td>"));
    row.append($("<td>" + rowData[index].Prd_ROQ + "</td>"));
    row.append(
    $("<td></td>").append(
        "<input disabled  class='form-control' type='number' step='any' " +
        "value='" + rowData[index].Prd_CostPrice + "' " +
        "name='unitCostChange_" + rowData[index].ProductCode + "' " +
        "id='unitCostChange_" + rowData[index].ProductCode + "'>"
    )
);


   row.append(
    $("<td></td>").append(
        "<input disabled class='form-control' type='number' step='any' " +
        "value='" + rowData[index].Prd_SetAPrice + "' " +
        "name='priceChange_" + rowData[index].ProductCode + "' " +
        "id='priceChange_" + rowData[index].ProductCode + "'>"
    )
);


    row.append($("<td>" + rowData[index].SupName + "</td>"));
    row.append($("<td><button class='btn btn-primary edit' id='saveStock' name='saveStock' value='" + rowData[index]
        .ProductCode + "'disabled >Save</button></td>"));

        return;
    } else {

        row.append($("<td>--</td>"));
    }


    row.append($("<td><input class='form-control' type='number' step='any' value='" + rowData[index].Stock +
        "' name='stockChange_" + rowData[index].ProductCode + "' id='stockChange_" + rowData[index]
        .ProductCode + "'>" + rowData[index].Stock + "</td>"));
    row.append($("<td>" + rowData[index].Prd_ROL + "</td>"));
    row.append($("<td>" + rowData[index].Prd_ROQ + "</td>"));
    row.append(
    $("<td></td>").append(
        "<input disabled  class='form-control' type='number' step='any' " +
        "value='" + rowData[index].Prd_CostPrice + "' " +
        "name='unitCostChange_" + rowData[index].ProductCode + "' " +
        "id='unitCostChange_" + rowData[index].ProductCode + "'>"
    )
);

    row.append(
    $("<td></td>").append(
        "<input disabled class='form-control' type='number' step='any' " +
        "value='" + rowData[index].Prd_SetAPrice + "' " +
        "name='priceChange_" + rowData[index].ProductCode + "' " +
        "id='priceChange_" + rowData[index].ProductCode + "'>"
    )
);

    row.append($("<td>" + rowData[index].SupName + "</td>"));
    row.append($("<td><button class='btn btn-primary edit' id='saveStock' name='saveStock' value='" + rowData[index]
        .ProductCode + "'>Save</button></td>"));


        
}



function printdiv() {
    $("#saletable").print({
        prepend: "<h3 style='text-align:center'>Product Detail Report</h3><hr/>",
        title: 'Date vise Sales Report'
    });
}

// $("#saletable").freezeHeader({'height': '600px'});


// $(document).ready(function() {

//     function savestocktest() {
//         alert('asasa')
//     }


// $(document).on('click', '.delete', function () {
//     var id = $(this).attr("id");
//     if (confirm("Are you want to Delete Customer ? Please Check this customer have Invoices or Not")){
//         $.ajax({
//             type: "post",
//             url: "../Customer/cancel_syn_customer",
//             data: {id: id},
//             success: function (json) {
//                 location.reload();
//             }
//         });
//     }else {
//
//     }
// });

// });
</script>