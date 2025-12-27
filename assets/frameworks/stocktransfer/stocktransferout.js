
$(document).ready(function() {

    var itemCode = '';
    var i = 0;
     var customProCode='100001';
    var itemcode = [];
    var serialBatch=1;
//==============load products========================

// this function connected to addsale invoice/ estimate / job invoice // stock tranfer out
    $("#itemCode").autocomplete({
        source: function(request, response) {
                var priceLevel = $('#priceLevel').val();

                if(priceLevel == 1){
                        $.ajax({
                            url: baseUrl+'/Salesinvoice/loadproductjson',
                            dataType: "json",
                            data: {
                                q: request.term,
                                type: 'getActiveProductCodes',
                                row_num: 1,
                                action: "getActiveProductCodes",
                                price_level: priceLevel,
                                isGrn:0
                            },
                            success: function(data) {
                       
                                response($.map(data, function(item) {
                                    return {
                                        label: item.label,
                                        value: item.value,
                                        price: item.price
                                    }
                                }));
                            }
                        });
                    } else{
                        $.ajax({
                            url: baseUrl+'/Salesinvoice/loadwholesalepriceproductjson',
                            dataType: "json",
                            data: {
                                q: request.term,
                                type: 'getActiveProductCodes',
                            
                            
                                row_num: 1,
                                action: "getActiveProductCodes",
                                price_level: priceLevel,
                                isGrn:0
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.label,
                                        value: item.value,
                                        price: item.price
                                    }
                                }));
                            }
                        });
                    }
            },
        autoFocus: true,
        minLength: 0,
        select: function(event, ui) {
            itemCode = ui.item.value;
            price = ui.item.price;
            let fromloc = $('#from').val();
             let priceLevel = $('#priceLevel').val();
            $.ajax({
                type: "post",
                url: baseUrl+"/stocktransfer/getProductByIdforSTO",
                data: {proCode: itemCode,fromloc:fromloc,costPrice:price,prlevel:priceLevel},
                success: function(json) {
                  
                    var resultData = JSON.parse(json);

                   console.log('result',resultData);

                    if (resultData) {
                      
                        if (resultData.serial) {
                            let SerialNoRaw = resultData.serial.SerialNo;

                            if (Array.isArray(SerialNoRaw)) {
                                // If it's an array, iterate and push
                                SerialNoRaw.forEach(function(value) {
                                    if ($.inArray(value, stockSerialnoArr) < 0) {
                                        stockSerialnoArr.push(value);
                                    }
                                });
                            } else if (SerialNoRaw) {
                                // If it's a single value, push it directly
                                if ($.inArray(SerialNoRaw, stockSerialnoArr) < 0) {
                                    stockSerialnoArr.push(SerialNoRaw);
                                }
                            }
                        }

                        let SerialNoRaw = resultData.serial?.SerialNo ?? 0;
                            let SerialNo = 0;

                            if (SerialNoRaw) {
                                if (Array.isArray(SerialNoRaw)) {
                                    
                                    SerialNo = 0;
                                } else {
                                  
                                    SerialNo = SerialNoRaw;
                                }
                            }

                            

                        let EmiNo = resultData.serial?.EmiNo ?? 0;
                         if(resultData.product){
                         isEmiNo = resultData.product.IsRawMaterial;
                         $("#productStock").html('/ Available Main Stock = ' + resultData.productstock.Stock);
                         $("#priceStock").html('/ Available Price Stock = ' + resultData.pricestock.Stock);
                         stockavalibal = resultData.productstock.Stock;

                        //loadVATNBT(resultData.product.IsTax,resultData.product.IsNbt,resultData.product.NbtRatio);
                       // loadProModal(resultData.product.Prd_Description, resultData.product.ProductCode, resultData.pricestock.Price, resultData.pricestock.UnitCost, 0, resultData.product.IsSerial);
                       loadProModal(resultData.product.Prd_Description, resultData.product.ProductCode, resultData.pricestock.Price, resultData.pricestock.UnitCost,
                             SerialNo, resultData.product.IsSerial, resultData.product.IsFreeIssue, resultData.product.IsOpenPrice,
                              resultData.product.IsMultiPrice, resultData.product.Prd_UPC, resultData.product.WarrantyPeriod,
                               resultData.product.IsRawMaterial,resultData.product.UOM_Name, resultData.product.ProductVatPrice,EmiNo);
                    }

                    $("#proStock").html('');

                    if(resultData.pro_stock){
                        $("#proStock").html(resultData.pro_stock);
                     }else{
                        $("#proStock").html(0);
                     }

                    } else {

                        $.notify("Product not found.", "warning");
                        $("#itemCode").val('');
                        $("#itemCode").focus();
                        return false;
                    }
                },
                error: function() {
                    $.notify("Error while request.", "warning");
                 }
            });
        }
    });


//

   $('#itemCode').on('keydown', function(e) {
       
        if (e.which == 13) {
            $("#errGrid").hide();
            var barCode = $(this).val();
            price_level = $('#priceLevel').val();
            let fromloc = $('#from').val();
            //let toloc = $('#to').val();

            $.ajax({
                type: "post",
                url: baseUrl+"/stocktransfer/getProductByBarCodeforSTO",
                data: {proCode: barCode, prlevel: price_level, location: fromloc},
                success: function(json) {

                    var resultData = JSON.parse(json);
                    if (resultData) {
                         if (resultData.serial) {
                            let SerialNoRaw = resultData.serial.SerialNo;

                            if (Array.isArray(SerialNoRaw)) {
                                // If it's an array, iterate and push
                                SerialNoRaw.forEach(function(value) {
                                    if ($.inArray(value, stockSerialnoArr) < 0) {
                                        stockSerialnoArr.push(value);
                                    }
                                });
                            } else if (SerialNoRaw) {
                                // If it's a single value, push it directly
                                if ($.inArray(SerialNoRaw, stockSerialnoArr) < 0) {
                                    stockSerialnoArr.push(SerialNoRaw);
                                }
                            }
                        }

                        let SerialNoRaw = resultData.serial?.SerialNo ?? 0;
                            let SerialNo = 0;

                            if (SerialNoRaw) {
                                if (Array.isArray(SerialNoRaw)) {
                                    
                                    SerialNo = 0;
                                } else {
                                  
                                    SerialNo = SerialNoRaw;
                                }
                            }

                            

                        let EmiNo = resultData.serial?.EmiNo ?? 0;

                        if(resultData.product){
                            itemCode = resultData.product.ProductCode;
                            isEmiNo = resultData.product.IsRawMaterial;
                            // autoSerial = resultData.product.IsRawMaterial;
                            // loadVATNBT(isJobVat,isJobNbt,isJobNbtRatio);
                            
                            $("#productStock").html('/ Available Stock = ' + resultData.productstock.Stock);
                            stockavalibal = resultData.productstock.Stock;
                            $("#priceStock").html('/ Available Price Stock = ' + resultData.pricestock.Stock);
                            //loadVATNBT(resultData.product.IsTax,resultData.product.IsNbt,resultData.product.NbtRatio);
                           //loadProModal(resultData.product.Prd_Description, resultData.product.ProductCode, resultData.pricestock.Price, resultData.pricestock.UnitCost, 0, resultData.product.IsSerial);
                           loadProModal(resultData.product.Prd_Description, resultData.product.ProductCode, resultData.pricestock.Price, resultData.product.Prd_CostPrice,
                             SerialNo, resultData.product.IsSerial, resultData.product.IsFreeIssue, resultData.product.IsOpenPrice,
                              resultData.product.IsMultiPrice, resultData.product.Prd_UPC, resultData.product.WarrantyPeriod,
                               resultData.product.IsRawMaterial,resultData.product.UOM_Name, resultData.product.ProductVatPrice,EmiNo);
                        }

                        $("#proStock").html('');

                         if(resultData.pro_stock){
                            $("#proStock").html(resultData.pro_stock);
                         }else{
                            $("#proStock").html(0);
                         }
                    } else {
                        $.notify("Product not found.", "warning");
                        $("#itemCode").val('');
                        $("#itemCode").focus();
                        return false;
                    }
                },
                error: function() {
                    $.notify("Error while request.", "warning");
                }
            });
           e.preventDefault();
        }
    });


    function setProductTable() {
        $('#tbl_item tbody tr').each(function(rowIndex, element) {
            var row = rowIndex + 1;

            $(this).find("[class]").eq(0).html(row);
            $(this).find("[class]").eq(0).parent().attr("ri", row);
            $(this).find("[class]").eq(0).parent().attr("id", row);
        });
    }

    function clear_gem_data() {
        $("#sellingPrice").val('');
        $("#proVatPrice").val('');
        $("#serialNo").val('');
        $("#serialNoCheck").val('');
        $("#dv_SN").hide();
        $("#itemCode").val('');
        $("#batchCode").val('');
        $("#unitcost").val(0);
        // $("#remark").val('');
        $("#guessAmount").val(0);
        $("#qty").val(0);
        $("#cutWeight").val(0);
        $("#polishWeight").val(0);
        // $("#totalNet").val(0);
        $("#buyAmount").val(0);
        $("#isCut").val(1);
        $("#isPolish").val(1);
        $("#isBuy").val(1);
        $(".gemoption").prop('checked', false);

        $('.rank').val(0);
        $("#disPercent").val(0);
        $("#disAmount").val(0);
        $("#salesperson").val('');
        $("#warrantytype").val('');
        $("#upm").html('');
        $("#proStock").html('');
         $("#productStock").html('');
        $("#productName").html('');
        $("#priceStock").html('');
        //$("#totalAmount").html(accounting.formatMoney(total_amount));
       // $("#netAmount").html(accounting.formatMoney(totalNetAmount));

        $("input[name=isCut][value='1']").prop('checked', false);
        $("input[name=isPolish][value='1']").prop('checked', false);
        $("input[name=isBuy][value='1']").prop('checked', false);
        $("#isZero").iCheck('uncheck');

        discount = 0;
        discount_type = 0;
        discount_amount = 0;
        product_discount = 0;
        itemCode = 0;
        casecost = 0;
        costPrice = 0;
        sellingPrice = 0;
        orgSellingPrice=0;
        vatSellingPrice=0;
        isSellZero=0;
        $("#itemCode").focus();
        
    }

    //load model
      function loadProModal(mname, mcode, msellPrice, mcostPrice, mserial, misSerial, misFree, isOP, isMP, upc, waranty, isEmiNo,upm,vatSell,EmiNo) {

    //     $("#productName").html('');
    //     $("#qty").focus();
       
    //     if (misSerial == 1) {
    //        $("#serialNo").val(mserial);
    //        $("#serialNoCheck").val(mserial);
    //        $("#qty").val(1);
    //        $("#qty").attr('disabled', true);
    //         $("#dv_SN").show();
    //         $("#qty").focus();
    //     } else {
    //        $("#mSerial").val('');
    //         $("#qty").attr('disabled', false);
    //         $("#dv_SN").hide();
    //     }
    //     $("#qty").val(1);
    //     $("#prdName").val(mname);
    //     $("#productName").html(mname);
    //     $("#itemCode").val(mcode);
    //     $("#sellingPrice").val(msellPrice);
    //     $("#orgSellPrice").val(msellPrice);
    //     $("#unitcost").val(mcostPrice);
    //     $("#isSerial").val(misSerial);
     

      

    //     if (misSerial == 1) {
    //         $("#dv_SN").show();
    //     } else {
    //         $("#dv_SN").hide();
    //     }

    //     // if (misFree == 1) {
    //     //     $("#dv_FreeQty").show();
    //     // } else {
    //     //     $("#dv_FreeQty").hide();
    //     // }
    // }



    $("#productName").html('');
        $("#qty").focus();
      console.log(misSerial,isEmiNo,mserial);
        if (misSerial == 1 && isEmiNo == 0) {
            $("#serialNo").val(mserial);
            $("#serialNoCheck").val(mserial);
            $("#qty").val(1);
            $("#qty").attr('disabled', true);
            $("#dv_SN").show();
            $("#emiDiv").hide();
           
            $("#qty").focus();
            $("#qty").focus();
        }else if(misSerial == 0 && isEmiNo == 1){
            $("#qty").val(1);
            $("#qty").attr('disabled', true);
             $("#mSerial").val('');
            $("#emiDiv").show();
           
            $("#qty").focus();
            $("#qty").focus();
        }else if (misSerial == 1 && isEmiNo == 1){
            $("#serialNo").val(mserial);
            $("#serialNoCheck").val(mserial);
            $("#qty").val(1);
            $("#qty").attr('disabled', true);
            $("#dv_SN").show();
            $("#emiDiv").show();
           
            $("#qty").focus();
            $("#qty").focus();
        } else {
           $("#mSerial").val('');
            $("#qty").attr('disabled', false);
            $("#dv_SN").hide();
            $("#emiDiv").hide();

        }
        $("#qty").val(1);
//        $("#mLProCode").html(mcode);
        $("#prdName").val(mname);
        $("#productName").html(mname);
        $("#itemCode").val(mcode);
        $("#sellingPrice").val(msellPrice);
        $("#orgSellPrice").val(msellPrice);
        $("#unitcost").val(mcostPrice);
        $("#isSerial").val(misSerial);
        $("#upc").val(upc);
        $("#upm").html(upm);
        $("#emiNo").val(EmiNo);
        $("#isEmi").val(isEmiNo);

        if(vatSell==0 || vatSell==null){
            $("#proVatPrice").val(msellPrice);
        }else{
            $("#proVatPrice").val(vatSell);
        }

        if (misSerial == 1) {
            $("#dv_SN").show();
        } else {
            $("#dv_SN").hide();
        }
        

        if (misFree == 1) {
            $("#dv_FreeQty").show();
        } else {
            $("#dv_FreeQty").hide();
        }
    }


    $("#addItem").click(function() {
        add_products();
    });

      $('#serialNo').on('keydown', function(e) {
        if (e.which == 13) {
            add_products();
        }
    });

    $('#sellingPrice').on('keydown', function(e) {
        if (e.which == 13) {
            add_products();
        }
    });

    $('#itemCode').on('keydown', function(e) {
        if (e.which == 107) {
            itemCode=customProCode;
            setCustomProduct();
            e.preventDefault();
        }
    });

    var stockSerialnoArr = [];
    var serialnoarr = [];

      $("#serialNo").autocomplete({
        source: function(request, response) {
            let fromloc = $('#from').val();
            $.ajax({
                url: baseUrl+"/stocktransfer/loadproductSerial",
                dataType: "json",
                data: {
                    q: request.term,
                    location: fromloc,
                    row_num: 1,
                    proCode: itemCode
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.label,
                            value: item.value,
                            data: item,
                            emiNo:item.emiNo
                        }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function(event, ui) {
            serialNo = ui.item.value;
            emiNo = ui.item.emiNo;
            $('#serialNo').val(serialNo);
            $('#emiNo').val(emiNo);
            $('#serialNoCheck').val(serialNo);
        }
    });
    function add_products() {
        var serialQty = 0;
        sellingPrice = parseFloat($("#sellingPrice").val());
        orgSellingPrice= parseFloat($("#orgSellPrice").val());
        var unit = $("#mUnit option:selected").val();
        var prdName = $("#prdName").val();
        var serialNo = $("#serialNo").val();
        var is_serail = $("#isSerial").val();
         var emiNo = $("#emiNo").val();
         var isEmi = $("#isEmi").val();
        priceLevel = $("#priceLevel option:selected").val();
        var qty = parseFloat($("#qty").val());
        var upc = parseFloat($("#upc").val());
        costPrice = parseFloat($("#unitcost").val());
        var freeQty = parseFloat($("#freeqty").val());
        var case1 = $("#mUnit option:selected").val();
        
        var serialNoCheck = $("#serialNoCheck").val();
        vatSellingPrice = sellingPrice;

        

     
        newSerialQty = parseFloat($("#serialQty").val());
        maxSerialQty = qty;
        maxSerialQty2 = qty;

        if (is_serail == 1 && isEmiNo  == 0) {
            serialQty = newSerialQty;
            qty = qty;
        } else {
            qty = qty;
        }

        if (case1 == 'Unit' || case1 == 'UNIT') {
            qty = qty;
        } else if (case1 == 'Case' || case1 == 'CASE') {
            qty = upc * qty;
            casecost = costPrice * qty;
        }

        var itemCodeArrIndex = $.inArray(itemCode, itemcode);
        var itemCodeSellingPrice = itemCode + '_' + sellingPrice;
        var itemCodesellArrIndex = $.inArray(itemCodeSellingPrice, itemcode);
        isEmiNo=0;

        if (itemCode == '' || itemCode == 0) {
            $.notify("Please select a item.", "warning");
            return false;
        } else if ((sellingPrice == '' || sellingPrice == 0 || isNaN(sellingPrice) == true)) {
            $.notify("Selling price can not be 0.", "warning");
           return false;
        } else if (costPrice == '' || costPrice == 0 || isNaN(costPrice) == true) {
            $.notify("Cost price can not be 0.", "warning");
            return false;
        } else if (qty == '' || qty == 0 || isNaN(qty) == true) {
            $.notify("Please enter a qty.", "warning");
            return false;
        } else if (costPrice > sellingPrice) {
            $.notify("Selling price can not be less than cost price.", "warning");
            return false;
        } else if (parseFloat(stockavalibal) < qty || parseFloat(stockavalibal) <= 0) {
            $.notify("Stock not available.", "warning");
            return false;
        } else if (is_serail == 1 && serialNoCheck == '') {
            $.notify("Please Enter valid Serial No", "warning");
            return false;
        }  else {
            if (is_serail == 0) {
                if ((itemCodeArrIndex < 0)) {
                    totalNet = (sellingPrice * qty);
                   
                    
                    // if(itemCode!=customProCode){
                    //     itemcode.push(itemCode);
                    // }

                  
                    i++;
                    if (is_serail == 1) {
                        serialQty--;
                        $("#serialQty").val(serialQty);
                    }

                    $("#tbl_item tbody").append("<tr serial_batch='0'  ri=" + i + 
                        " id=" + i + "' proCode='" + itemCode + 
                        "' uc='" + unit + 
                        "' qty='" + qty + 
                        "' unit_price='" + sellingPrice + 
                        "'  org_unit_price='" + orgSellingPrice + 
                        "' upc='" + upc + 
                        "' isSerial='" + is_serail + 
                        "' isEmi='" + isEmi + 
                        "' emiNo='" + emiNo + 
                        "' serial='" + serialNo +
                        "' cPrice='" + costPrice + 
                        "' totalNet='" + totalNet +
                        "' pL='" + priceLevel + 
                        "' fQ='" + freeQty + 
                        "' proName='" + prdName + "'>" +
                        "<td class='text-center'>" + i + "</td>" +
                        "<td class='text-left'>" + itemCode + "</td>" +
                        "<td>" + prdName + "</td><td>" + unit + "</td>" +
                        "<td class='qty" + i + "'>" + accounting.formatNumber(qty) + "</td>" +
                        "<td class=''>" + accounting.formatNumber(sellingPrice) + "</td>" +
                        "<td class='' >" + accounting.formatMoney(totalNet) + "</td>" +
                        "<td>" + serialNo + "</td>" +
                        "<td>" + emiNo + "</td>" +
                        "<td style='display:none'>" + is_serail + "</td>" +
                        "<td style='display:none'>" + isEmi + "</td>" +
                        // "<td>" + salespname + "</td>" +
                        "<td><i class='glyphicon glyphicon-edit edit btn btn-info btn-xs'></i></td>" +
                        "<td class='rem" + i + "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td>" +
                        "</tr>");
                    clear_gem_data();
                    if (is_serail != 1) {
                        clear_gem_data();
                    } else {
                        if (serialQty == 0) {
                            clear_gem_data();
                        } else {
                            $("#serialNo").val('');
                           $("#serialNo").focus();
                        }
                    }
                    setProductTable();
                } else {
                    $.notify("Item already exists.", "warning");
                    return false;
                }
            }
            else if (is_serail == 1) {

                console.log('Serial',serialNo,stockSerialnoArr);
                var serialNoArrIndex = $.inArray(serialNo, serialnoarr);
                var StockserialNoArrIndex = $.inArray(serialNo, stockSerialnoArr);
                 totalNet = (sellingPrice * qty);
                    if (serialNo == '' || serialNo == 0) {
                        $.notify("Serial Number can not be empty.", "warning");
                        $("#serialNo").focus();
                        return false;
                    }
                    else if (((serialNoArrIndex >= 0 && is_serail == 1))) {
                        $.notify("Serial Number already exists.", "warning");
                        $("#serialNo").val('');
                        return false;
                    } 
                    // else if (((StockserialNoArrIndex < 0 && is_serail == 1))) {
                    //     $.notify("Serial Number product not in  stock..", "warning");
                    //     $("#serialNo").val('');
                    //     return false;
                    // }
                    else if (((itemCodeArrIndex >= 0 && is_serail == 1) || (itemCodeArrIndex < 0 && is_serail == 1))) {

                        
                        itemcode.push(itemCodeSellingPrice);
                        serialnoarr.push(serialNo);
                    
                       

                        i++;
                        if (is_serail == 1) {
                            serialQty--;
                            $("#serialQty").val(serialQty);
                        }

                        $("#tbl_item tbody").append("<tr serial_batch='"+serialBatch+"' ri=" + i + 
                            " id=" + i + 
                            "'  proCode='" + itemCode + 
                            "' uc='" + unit + 
                            "' qty='" + qty + 
                            "' unit_price='" + sellingPrice + 
                            "' org_unit_price='" + orgSellingPrice +
                            "' isSerial='" + is_serail + 
                            "' isEmi='" + isEmi + 
                            "' emiNo='" + emiNo + 
                            "' serial='" + serialNo + 
                            "' cPrice='" + costPrice + 
                            "' pL='" + priceLevel +  
                            "' totalNet='" + totalNet + 
                            "' fQ='" + freeQty +
                            "' proName='" + prdName +"'>" +
                            "<td class='text-center'>" + i + 
                            "</td>" +
                            "<td class='text-left'>" + itemCode + "</td>" +
                            "<td>" + prdName + "</td><td>" + unit + "</td>" +
                            "<td class='qty" + i + "'>" + accounting.formatNumber(qty) + "</td>" +
                            "<td class=''>" + accounting.formatNumber(sellingPrice) + "</td>" +
                            "<td class='totalNet' >" + accounting.formatMoney(totalNet) + "</td>" +
                            "<td>" + serialNo + "</td>" +
                                 "<td>" + emiNo + "</td>" +
                            "<td style='display:none'>" + is_serail + "</td>" +
                            "<td style='display:none'>" + isEmi + "</td>" +
                            
                            "<td class='rem" + i + "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td>" +
                            "</tr>");
                            clear_gem_data()
                        if (is_serail != 1) {
                            clear_gem_data();
                        } else {
                            if (serialQty == 0) {
                                clear_gem_data();
                                serialBatch++;
                            } else {
                                $("#serialNo").val('');
                                $("#serialNo").focus();
                            }
                        }
                        setProductTable();
                    } else {
                        $.notify("Item already exists.", "warning");
                        $("#serialNo").val('');
                        return false;
                    }
            }
        }
    }

    // edit grid
     $("#tbl_item tbody").on('click', '.edit', function() {
        var proname = $(this).parent().parent().attr('proName')
        var proCode = $(this).parent().parent().attr('proCode');
        //var isZero = $(this).parent().parent().attr('isZero');
        // var jobdesc = $(this).parent().parent().attr('job');
        var qty = parseFloat($(this).parent().parent().attr('qty'));
        var freeqty = parseFloat($(this).parent().parent().attr('fQ'));
        var selprice = parseFloat($(this).parent().parent().attr('unit_price'));
        var costprice = parseFloat($(this).parent().parent().attr('cPrice'));
        //var netprice = parseFloat($(this).parent().parent().attr('netAmount'));
        var uc = $(this).parent().parent().attr('uc');
        var upc = parseFloat($(this).parent().parent().attr('upc'));
        var isSerial = $(this).parent().parent().attr('isSerial');
        var serialNo = $(this).parent().parent().attr('serialNo');
        var salesPerson = $(this).parent().parent().attr('salesPerson');
        var warranty = $(this).parent().parent().attr('warranty');
        //var pricelevel = $(this).parent().parent().attr('pL');
        
       

   

        var r = confirm('Do you want to edit this row ?');
        if (r === true) {
          
            itemCode=customProCode;
            $("#prdName").val(proname);
            $("#productName").html(proname);
            $("#upc").val(upc);
            $("#qty").val(qty);
            $("#mUnit").val(uc);
            $("#sellingPrice,#proVatPrice,#orgSellPrice").val(selprice);
            $("#itemCode").val(proname);
            $("#freeqty").val(freeqty);
            $("#isSerial").val(isSerial);
            $("#serialNo").val(serialNo);
            //$("#proNbtRatio").val(nbtratio);
            // $("#estPrice").val(estPrice);
            $("#unitcost").val(costprice);
            $("#salesperson").val(salesPerson);
            $("#warrantytype").val(warranty);
            // $("#disPercent").val(disPrecent);
            
            $("input[name='isZero']:checked").val();

            if(itemCode!=proCode){
                itemcode.splice($.inArray(itemCode, itemcode), 1);
            }

           

            $(this).parent().parent().remove();

            setProductTable();
            return false;
        }
    });


    $("#tbl_item").on('click', '.remove', function() {
        var rid = $(this).parent().parent().attr('ri');

        var r = confirm('Do you want to remove row no ' + rid + ' ?');
        if (r === true) {

            //remove product code from array
            var removeItem = $(this).parent().parent().attr("proCode");
            var removeSerial = $(this).parent().parent().attr("serial");

            if(itemcode!=customProCode){
               itemcode = jQuery.grep(itemcode, function(value) {
                    return value != removeItem;
                });
            }
            
            serialnoarr = jQuery.grep(serialnoarr, function(value) {
                return value != removeSerial;
            });

            $(this).parent().parent().remove();

            setProductTable();
            return false;
        } else {
            return false;
        }
    });


    $("#onlySaveItems").click(function() {
        setProductTable();
        var rowCount = $('#tbl_item tr').length;
        var product_code = new Array();
        var item_code = new Array();
        var serial_no = new Array();
        var pro_name = new Array();
        var qty = new Array();
        var unit_price = new Array();
        var org_unit_price = new Array();
        var unit_type = new Array();
        var unitPC = new Array();
        var caseCost = new Array();
   
        var total_net = new Array();
        var isSerial = new Array();
        var price_level = new Array();
        var fee_qty = new Array();
        var cost_price = new Array();
        var emi_no = new Array();
        var isEmi = new Array();
        
        let tranferDate = $('#tranfer_Date').val();
       
        let fromloc = $('#from').val();
        let toloc = $('#to').val();
        var invUser    = $("#invUser").val();
        var remark = $("#remark").val();
        action = $("#action").val();
      
       
    
        

        $('#tbl_item tbody tr').each(function(rowIndex, element) {
            product_code.push($(this).attr('proCode'));
            serial_no.push($(this).attr('serial'));
            qty.push(($(this).attr('qty')));
            unit_price.push(($(this).attr('unit_price')));
            org_unit_price.push(($(this).attr('org_unit_price')));
          
            total_net.push(($(this).attr('totalNet')));
            price_level.push($(this).attr("pL"));
            unit_type.push($(this).attr("uc"));
            fee_qty.push($(this).attr("fQ"));
            cost_price.push(($(this).attr("cPrice")));
            unitPC.push(($(this).attr("upc")));
            caseCost.push(($(this).attr("caseCost")));
            isSerial.push($(this).attr("isSerial"));
            pro_name.push($(this).attr("proName"));
            emi_no.push($(this).attr('emiNo'));
            isEmi.push($(this).attr('isEmi'));
           
        });

        var sendProduct_code = JSON.stringify(product_code);
        var sendPro_name = JSON.stringify(pro_name);
        var sendSerial_no = JSON.stringify(serial_no);
        var sendQty = JSON.stringify(qty);
        var sendUnit_price = JSON.stringify(unit_price);
        var sendOrgUnit_price = JSON.stringify(org_unit_price);
        var sendTotal_net = JSON.stringify(totalNet);
        var sendPrice_level = JSON.stringify(price_level);
        var sendUnit_type = JSON.stringify(unit_type);
        var sendFree_qty = JSON.stringify(fee_qty);
        var sendCost_price = JSON.stringify(cost_price);
        var sendUpc = JSON.stringify(unitPC);
        var sendCaseCost = JSON.stringify(caseCost);
        //var sendPro_total = JSON.stringify(pro_total);
        var sendIsSerial = JSON.stringify(isSerial);
         var emi_noArr = JSON.stringify(emi_no);
        var sendIsEmi = JSON.stringify(isEmi);

        // var r = confirm("Do you want to save this invoice.?");
        // if (r == true) {
        if(tranferDate=="" || tranferDate=="null" ){
              $.notify("Please add Transfer Date.", "warning");
                return false;
        }
         if ((rowCount - 1) == '0' || (rowCount - 1) == '') {
                $.notify("Please add products.", "warning");
                return false;

            }else {
                maxSerialQty += parseFloat($("#maxSerial").val());
                $("#saveItems").attr('disabled', true);
                $.ajax({
                    type: "post",
                    url: baseUrl+"/stocktransfer/saveNewstocktransfer",
                    data: {remark:remark,action:action,product_code: sendProduct_code, serial_no: sendSerial_no, qty: sendQty, unit_price: sendUnit_price,org_unit_price:sendOrgUnit_price,
                        total_net: sendTotal_net, unit_type: sendUnit_type, price_level: sendPrice_level,
                        case_cost: sendCaseCost, freeQty: sendFree_qty, cost_price: sendCost_price, isSerial: sendIsSerial, proName: sendPro_name,
                        tranferDate: tranferDate, invUser: invUser, maxSerialQty: maxSerialQty,fromloc:fromloc,toloc:toloc, emi_no:emi_noArr,isEmi: sendIsEmi},
                    success: function(data) {
                        var resultData = JSON.parse(data);
                        var feedback = resultData['fb'];
                        var invNumber = resultData['InvNo'];

                        if (feedback != 1) {
                            $.notify("Stock Transfer not saved successfully.", "warning");
                            $("#saveItems").attr('disabled', true);
                           
                           // loadSAlesInvoice(invNumber);
                            return false;
                        } else {
                            
                            $("#cart-pay-button").prop('disabled',true);
                            $.notify("Stock Transfer saved successfully.", "sucess");
                            $("#tbl_item tbody").html('');
                             //loadSAlesInvoice(invNumber);
                            $("input[name=suppliercheck][value='1']").prop('checked', false);
                            $("#invoicenumber").val("");
                            $("#supplier").val("");
                            $("#totalgrn").html('0.00');
                            $("#grndiscount").html('0.00');
                            $("#netgrnamount").html('0.00');
                            // $("#grnremark").val('');
                            $("#saveItems").attr('disabled', true);
                            $("#modelPayment").modal('hide');
                            
                            $("#cart-pay-button").prop('disabled',true);
                            serialAutoGen = 0;
                            total_amount = 0;
                            total_discount = 0;
                            totalNetAmount = 0;
                            supcode = 0;
                            creditAmount = 0;
                            dueAmount = 0;
                            advance_amount=0;
                            return_amount=0;
                            advance_payment_no=0;
                            totalProWiseDiscount = 0;
                            totalGrnDiscount = 0;
                            shipping = 0;
                             cashAmount=0;
                            chequeAmount=0;cardAmount=0;
                            return_payment_no=0;returnAmount=0;
                            bank_amount=0;

                            $("#cash_amount").val(0);
                            $("#cheque_amount").val(0);
                            $("#credit_amount").val(0);
                            $("#advance_amount").val(0);
                            $("#before_advance_amount").val(0);
                            $("#return_amount").val(0);
                            $("#bank_amount").val(0);
                            $("#card_amount").val(0);
                            $("#totalExpenses").html(0);
                            $('#itemTable').show();
                            $('#costTable').hide();
                            $('#totalAmount').html('0.00');
                            $('#totalgrndiscount').html('0.00');
                            $('#totalprodiscount').html('0.00');
                            $('#dueAmount2').html('0.00');
                            $("#loadBarCode").hide();
                            
                        }
                    }
                });
            }
        // } else {
        //     return false;
        // }
    });


   


});