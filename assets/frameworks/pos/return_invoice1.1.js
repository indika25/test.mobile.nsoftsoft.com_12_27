/*
 * return invoice java script goes here
 * author esanka
 */
$(document).ready(function () {
  var customProCode = "100001";
  var paymentId = 0;
  var paymentNo = 0;
  var invNo = 0;
  var cusCode = 0;
  var ItemCodeArr = [];
  var TotalAmount = 0;
  var TotalNetAmount = 0;
  var proDiscount = 0;
  var location = $("#invlocation").val();
  var dueAmount = 0;
  var creditAmount = 0;
  var cashAmount = 0;
  var isNonRt = 0;
  var price_level = 1;
  var supcode = 0;
  var itemCode = 0;
  var itemcode = [];
  var i = 0;
  var minQty = 0;
  var total_amount = 0;
  var total_amount2 = 0;
  var totalCost = 0;
  var cashAmount = 0;
  var chequeAmount = 0;
  var creditAmount = 0;
  var dueAmount = 0;
  var lotPrice = 0;
  var returnAmount = 0;
  var refundAmount = 0;
  var returnPayment = 0;
  var totalExtraChrages = 0;
  var downPayment = 0;
  var totalIterest = 0;
  var discount_precent = 0;
  var discount_amount = 0;
  var product_discount = 0;
  var totalNet2 = 0;
  var totalNet = 0;
  var totalNetAmount = 0;
  var total_discount = 0;
  var total_item_discount = 0;
  var discount = 0;
  var discount_type = 0;
  var total_dwn_interest = 0;
  var total_qur_interest = 0;
  var totalExtraAmount = 0;
  var totalProWiseDiscount = 0;
  var totalGrnDiscount = 0;

  var settleAmount = 0;
  var total_due = 0;
  var pay_amount = 0;
  var due_amount = 0;
  var credit_amount = 0;
  var settle_amount = 0;
  var invoiceNo = 0;
  var rid = 0;
  var total_settle = 0;
  var change_amount = 0;
  var over_pay_amount = 0;
  var over_pay_inv = 0;
  var isInvoiceColse = 0;
  var serialBatch = 1;
  var totalProVAT = 0;
  var totalProNBT = 0;
  var finalVat = 0;
  var finalNbt = 0;
  $("#dv_SN").hide();

  //======= set price levels ==========
  $("#priceLevel").change(function () {
    price_level = $("#priceLevel option:selected").val();
  });
  //customer autoload

  $(".prd_icheck").iCheck({
    checkboxClass: "icheckbox_square-blue",
    radioClass: "iradio_square-blue",
    increaseArea: "50%",
  });

  $("input[name='nonInv']").on("ifChanged", function () {
    isNonRt = $("input[name='nonInv']:checked").val();
    if (!isNonRt) {
      isNonRt = 0;
    }
    invNo = 0;
    $("#invoice").val("");
    if ((cusCode != "" || cusCode != 0) && isNonRt == 1) {
      loadCustomerCreditInvoice(cusCode);
    } else {
      $("#tbl_payment tbody").html("");
    }
  });

  var loc = $("#invlocation").val();

  //customer autoload
  $("#customer").autocomplete({
    source: function (request, response) {
      cusType = $("#CustType option:selected").val();

      $.ajax({
        url: "loadcustomersjson",
        dataType: "json",
        data: {
          q: request.term,
        },
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.label,
                value: item.value,
                data: item,
              };
            })
          );
        },
      });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
      $("input[name='nonInv']").iCheck("uncheck");
      $("#invoice").val("");
      invNo = 0;
      cusCode = ui.item.value;
      $("#tbl_payment tbody").html("");

      $.ajax({
        type: "POST",
        url: "../../admin/Payment/getCustomersDataById",
        data: { cusCode: cusCode },
        success: function (data) {
          var resultData = JSON.parse(data);

          cusCode = resultData.cus_data.CusCode;
          outstanding = resultData.cus_data.CusOustandingAmount;
          available_balance =
            parseFloat(resultData.cus_data.CreditLimit) -
            parseFloat(outstanding);
          customer_name = resultData.cus_data.CusName;
          $("#cusCode").html(resultData.cus_data.CusName);
          $("#customer").val(resultData.cus_data.CusCode);
          $("#creditLimit").html(
            accounting.formatMoney(resultData.cus_data.CreditLimit)
          );
          $("#creditPeriod").html(resultData.cus_data.CreditPeriod);
          $("#cusOutstand").html(accounting.formatMoney(outstanding));
          $("#availableCreditLimit").html(
            accounting.formatMoney(available_balance)
          );
          $("#city").html(resultData.cus_data.MobileNo);
        },
      });
    },
  });
  var invoiceType = 1;

  $("#invType").change(function () {
    invoiceType = $("#invType option:selected").val();
  });

  //invoice autoload
  $("#invoice").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "loadinvoicejsonbytype",
        dataType: "json",
        data: {
          q: request.term,
          location: location,
          cusCode: cusCode,
          invoiceType: invoiceType,
        },
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.text,
                value: item.id,
                data: item,
              };
            })
          );
        },
      });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
      paymentId = ui.item.value;
      invNo = ui.item.value;
      $("#cusCode").html();
      $("#itemCode").val("");
      $("#qty").val(0);
      $("#sellingPrice").val(0);
      $("#tbl_payment tbody").html("");
      $("#tblData tbody").html("");

      if (invoiceType == 1) {
        loadSAlesInvoice(invNo);
      } else if (invoiceType == 2) {
        loadJobInvoice(invNo);
      }
    },
  });

  function loadSAlesInvoice(po_no) {
    $.ajax({
      type: "POST",
      url: "../Salesinvoice/getSalesInvoiceById",
      data: { saleInvoiceNo: po_no },
      success: function (data) {
        var resultData = JSON.parse(data);
        loadSalesInvoiceDatatoGrid(resultData);
      },
    });
  }

  function loadJobInvoice(po_no) {
    $.ajax({
      type: "POST",
      url: "../Salesinvoice/getInvoiceDataByInvoiceNo",
      data: { invoiceNo: po_no },
      success: function (data) {
        var resultData = JSON.parse(data);
        loadJobInvoiceDatatoGrid(resultData);
      },
    });
  }

  // load grid data
  function loadSalesInvoiceDatatoGrid(resultData) {
    total_discount = 0;
    total_amount = 0;
    total_amount2 = 0;
    totalNetAmount = 0;
    totalProWiseDiscount = 0;
    itemcode.length = 0;

    $("#cart-pay-button").prop("disabled", true);
    $("#tbl_job tbody").html("");

    if (resultData.si_hed) {
      invNo = resultData.si_hed.SalesInvNo;
      cusCode = resultData.si_hed.SalesCustomer;

      $("#customer").val(resultData.si_hed.SalesCustomer);
      $("#salesorder").val(resultData.si_hed.SalesOrderNo);
      $("#cusPhone").val(resultData.cus_data.MobileNo);

      for (var i = 0; i < resultData.si_dtl.length; i++) {
        if (resultData.si_dtl[i].ProductCode != customProCode) {
          ItemCodeArr.push(resultData.si_dtl[i].ProductCode);
        }
        // ItemCodeArr.push(resultData.si_dtl[i].ProductCode);
        // itemcode.push(resultData.si_dtl[i].ProductCode);
        // serialnoarr.push(resultData.si_dtl[i].ProductCode);
        totalProWiseDiscount += parseFloat(resultData.si_dtl[i].SalesDisAmount);
      }

      // $("#netgrnamount").html(accounting.formatMoney(resultData.si_hed.SalesNetAmount));
      // $("#grndiscount").html(accounting.formatMoney(resultData.si_hed.SalesDisAmount));
      // $("#totalgrn").html(accounting.formatMoney(resultData.si_hed.SalesInvAmount));
      // $("#totalVat").html(accounting.formatMoney(resultData.si_hed.SalesVatAmount));
      // $("#totalNbt").html(accounting.formatMoney(resultData.si_hed.SalesNbtAmount));
      // // $("#totalprodiscount").html(accounting.formatMoney(totalProWiseDiscount));

      // total_discount = parseFloat(resultData.si_hed.SalesDisAmount);
      // total_amount = parseFloat(resultData.si_hed.SalesInvAmount);
      // total_amount2 = parseFloat(resultData.si_hed.SalesInvAmount);
      // totalNetAmount = parseFloat(resultData.si_hed.SalesNetAmount);
    } else {
      estimateNo = 0;
    }
  }

  // load grid data
  function loadJobInvoiceDatatoGrid(resultData) {
    total_discount = 0;
    total_amount = 0;
    total_amount2 = 0;
    totalNetAmount = 0;
    totalProWiseDiscount = 0;
    itemcode.length = 0;

    $("#cart-pay-button").prop("disabled", true);
    $("#tbl_job tbody").html("");

    if (resultData.inv_hed) {
      poNo = resultData.inv_hed.JobCardNo;
      cusCode = resultData.inv_hed.JCustomer;
      invNo = resultData.inv_hed.JobInvNo;

      $("#customer").val(resultData.inv_hed.JCustomer);
      $("#salesorder").val(resultData.inv_hed.SalesOrderNo);
      $("#cusPhone").val(resultData.cus_data.MobileNo);

      for (var i = 0; i < resultData.inv_dtl.length; i++) {
        if (resultData.inv_dtl[i].JobType == 2) {
          if (resultData.inv_dtl[i].JobCode != customProCode) {
            ItemCodeArr.push(resultData.inv_dtl[i].JobCode);
          }
          // ItemCodeArr.push(resultData.inv_dtl[i].JobCode);
          // itemcode.push(resultData.inv_dtl[i].JobCode);
          // serialnoarr.push(resultData.inv_dtl[i].JobCode);
          totalProWiseDiscount += parseFloat(0);
        }
      }

      // $("#netgrnamount").html(accounting.formatMoney(resultData.inv_hed.JobNetAmount));
      // $("#grndiscount").html(accounting.formatMoney(resultData.inv_hed.JobTotalDiscount));
      // $("#totalgrn").html(accounting.formatMoney(resultData.inv_hed.JobTotalAmount));
      // $("#totalVat").html(accounting.formatMoney(resultData.inv_hed.JobVatAmount));
      // $("#totalNbt").html(accounting.formatMoney(resultData.inv_hed.JobNbtAmount));

      // total_discount = parseFloat(resultData.inv_hed.JobTotalDiscount);
      // total_amount = parseFloat(resultData.inv_hed.JobTotalAmount);
      // total_amount2 = parseFloat(resultData.inv_hed.JobTotalAmount);
      // totalNetAmount = parseFloat(resultData.inv_hed.JobNetAmount);
    } else {
      poNo = resultData.job_data.JobCardNo;
      cusCode = resultData.job_data.JCustomer;
      invNo = "";

      $("#customer").val(resultData.job_data.JCustomer);
      $("#salesorder").val("");
      $("#cusPhone").val(resultData.cus_data.MobileNo);

      for (var i = 0; i < resultData.job_desc.length; i++) {
        if (resultData.job_desc[i].ProductCode != customProCode) {
          itemcode.push(resultData.job_desc[i].ProductCode);
        }

        // itemcode.push(resultData.job_desc[i].ProductCode);
        serialnoarr.push(resultData.job_desc[i].SerialNo);

        $("#tbl_item tbody").append(
          "<tr ri=" +
            i +
            " id=" +
            i +
            " proCode='" +
            resultData.job_desc[i].ProductCode +
            "' uc='" +
            0 +
            "' qty='" +
            1 +
            "' unit_price='" +
            0 +
            "' upc='" +
            0 +
            "' caseCost='" +
            0 +
            "' isSerial='" +
            0 +
            "' serial='" +
            resultData.job_desc[i].SerialNo +
            "' discount_percent='" +
            0 +
            "' cPrice='" +
            0 +
            "' pL='1' fQ='0' nonDisTotalNet='" +
            0 +
            "' netAmount='" +
            0 +
            "' proDiscount='" +
            0 +
            "' proName='" +
            resultData.job_desc[i].ProductName +
            "'><td class='text-center'>" +
            (i + 1) +
            "</td><td class='text-left'>" +
            resultData.job_desc[i].ProductCode +
            "</td><td>" +
            resultData.job_desc[i].ProductName +
            "</td><td></td><td class='qty" +
            i +
            "'>" +
            accounting.formatNumber(1) +
            "</td><td class='text-right'>" +
            accounting.formatNumber(0) +
            "</td><td class='text-center'>" +
            0 +
            "</td><td class='text-right' >" +
            accounting.formatMoney(0) +
            "</td><td class='text-right' >" +
            resultData.job_desc[i].SerialNo +
            "</td><td></td><td class='rem" +
            i +
            "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td></tr>"
        );
        totalProWiseDiscount += parseFloat(0);
      }

      // $("#netgrnamount").html(accounting.formatMoney(0));
      // $("#grndiscount").html(accounting.formatMoney(0));
      // $("#totalgrn").html(accounting.formatMoney(0));
      // $("#totalVat").html(accounting.formatMoney(0));
      // $("#totalNbt").html(accounting.formatMoney(0));

      // total_discount = parseFloat(0);
      // total_amount = parseFloat(0);
      // total_amount2 = parseFloat(0);
      // totalNetAmount = parseFloat(0);
      // estimateNo = 0;
    }
  }

  $("#invDate,#chequeReciveDate,#chequeDate").datepicker({
    dateFormat: "yy-mm-dd",
    startDate: "-3d",
  });

  $("#invDate,#chequeReciveDate")
    .datepicker()
    .datepicker("setDate", new Date());

  function clearPaymentDetails() {
    $("#remark").val("");
    ItemCodeArr.length = 0;
    $("#invoice").val("");
    invNo = 0;
    TotalAmount = 0;
    TotalNetAmount = 0;
    proDiscount = 0;
    paymentId = 0;
    paymentNo = 0;
    cusCode = 0;
    $("#tbl_payment tbody").empty();
    $("#totalAmount").html(accounting.formatMoney(0));
    $("#totalDis").html(accounting.formatMoney(0));
    $("#totalNet").html(accounting.formatMoney(0));
  }
  var invPrice = 0;
  //==============load products========================
  $("#itemCode").autocomplete({
    source: function (request, response) {
      $.ajax({
        url: "loadproductjson",
        dataType: "json",
        data: {
          q: request.term,
          nonRt: isNonRt,
          invNo: invNo,
          invoiceType: invoiceType,
          price_level: price_level,
        },
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.label,
                value: item.value,
                qty: item.qty,
                price: item.price,
                name: item.name,
                serial: item.serial,
                data: item,
              };
            })
          );
        },
      });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
      itemCode = ui.item.value;
      var proname = ui.item.name;
      var serial = ui.item.serial;
      minQty = ui.item.qty;
      invPrice = ui.item.price;

      if (isNonRt == 1) {
        $.ajax({
          type: "post",
          url: "../../admin/Product/getProductByIdforGrn",
          data: { proCode: itemCode, prlevel: price_level, location: loc },
          success: function (json) {
            var resultData = JSON.parse(json);
            if (resultData) {
              $.each(resultData.serial, function (key, value) {
                var serialNoArrIndex1 = $.inArray(value, serialnoarr);
                if (serialNoArrIndex1 < 0) {
                  serialnoarr.push(value);
                }
              });

              loadProModal(
                resultData.product.Prd_Description,
                resultData.product.ProductCode,
                resultData.product.ProductPrice,
                resultData.product.Prd_CostPrice,
                0,
                resultData.product.IsSerial,
                resultData.product.IsFreeIssue,
                resultData.product.IsOpenPrice,
                resultData.product.IsMultiPrice,
                resultData.product.Prd_UPC,
                resultData.product.WarrantyPeriod
              );
            } else {
              $.notify("Product not found.", "warning");
              $("#itemCode").val("");
              $("#itemCode").focus();
              return false;
            }
          },
          error: function () {
            alert("Error while request..");
          },
        });
      } else {
        if (invoiceType == 1) {
          $.ajax({
            type: "POST",
            url: "../Salesinvoice/getSalesInvoiceProductById",
            data: {
              proCode: itemCode,
              invNo: invNo,
              name: proname,
              serial: serial,
            },
            success: function (data) {
              var resultData = JSON.parse(data);
              console.log(resultData);
              if (resultData) {
                loadProModal(
                  resultData.product.SalesProductName, // mname
                  resultData.product.ProductCode, // mcode
                  resultData.product.SalesUnitPrice, // msellPrice ✔
                  resultData.product.SalesQty, // getQty
                  resultData.product.Prd_CostPrice, // mcostPrice
                  resultData.product.SalesSerialNo, // mserial
                  resultData.product.IsSerial, // misSerial
                  resultData.product.IsFreeIssue, // misFree
                  resultData.product.IsOpenPrice, // isOP
                  resultData.product.IsMultiPrice, // isMP
                  resultData.product.Prd_UPC, // upc
                  resultData.product.WarrantyMonth, // waranty
                  resultData.product.IsEmi, // isEmiNo
                  resultData.product.SalesUnitPerCase, // upm ✔ correct
                  resultData.product.SalesVatAmount, // vatSell ✔ correct
                  resultData.product.EmiNo,
                  resultData.product.SalesDisValue
                );
              } else {
                $.notify("Product not found.", "warning");
                $("#itemCode").val("");
                $("#itemCode").focus();
                return false;
              }
            },
          });
        } else if (invoiceType == 2) {
          $.ajax({
            type: "POST",
            url: "../Salesinvoice/getJobInvoiceProductById",
            data: { proCode: itemCode, invNo: invNo },
            success: function (data) {
              var resultData = JSON.parse(data);
              if (resultData) {
                loadProModal(
                  resultData.product.JobDescription,
                  resultData.product.ProductCode,
                  resultData.product.JobNetAmount / resultData.product.JobQty,
                  resultData.product.Prd_CostPrice,
                  0,
                  resultData.product.IsSerial,
                  resultData.product.IsFreeIssue,
                  resultData.product.IsOpenPrice,
                  resultData.product.IsMultiPrice,
                  resultData.product.Prd_UPC,
                  resultData.product.WarrantyMonth
                );
              } else {
                $.notify("Product not found.", "warning");
                $("#itemCode").val("");
                $("#itemCode").focus();
                return false;
              }
            },
          });
        }
      }
    },
  });

  $("#addpro").click(function (e) {
    itemCode = customProCode;
    setCustomProduct();
    e.preventDefault();
  });

  function setCustomProduct() {
    var mname = $("#itemCode").val();
    $("#prdName").val(mname);
    $("#productName").html(mname);

    $("#qty").val(1);
    $("#qty").focus();
    $("#itemCode").val(itemCode);
    // $("#sellingPrice").val(0);
    // $("#orgSellPrice").val(0);
    // $("#proVatPrice").val(0);

    $("#unitcost").val(1);
    $("#isSerial").val(0);
    $("#upc").val(1);
    $("#upm").html(1);

    $("#dv_SN").hide();

    $("#dv_FreeQty").hide();
  }

  //load model
  function loadProModal(
    mname,
    mcode,
    msellPrice,
    getQty,
    mcostPrice,
    mserial,
    misSerial,
    misFree,
    isOP,
    isMP,
    upc,
    waranty,
    isEmiNo,
    upm,
    vatSell,
    EmiNo,disAmo
  ) {

    $("#productName").html("");
    $("#qty").focus();
    $("#getqty").val(getQty);
    if (misSerial == 1 && isEmiNo == 0) {
      $("#serialNo").val(mserial);
      $("#serialNoCheck").val(mserial);
      $("#qty").val(1);
      $("#qty").attr("disabled", true);
      $("#dv_SN").show();
      $("#emiDiv").hide();

      $("#qty").focus();
      $("#qty").focus();
    } else if (misSerial == 0 && isEmiNo == 1) {
      $("#qty").val(1);
      $("#qty").attr("disabled", true);
      $("#mSerial").val("");
      $("#emiDiv").show();

      $("#qty").focus();
      $("#qty").focus();
    } else if (misSerial == 1 && isEmiNo == 1) {
      $("#serialNo").val(mserial);
      $("#serialNoCheck").val(mserial);
      $("#qty").val(1);
      $("#qty").attr("disabled", true);
      $("#dv_SN").show();
      $("#emiDiv").show();

      $("#qty").focus();
      $("#qty").focus();
    } else {
      $("#mSerial").val("");
      $("#qty").attr("disabled", false);
      $("#dv_SN").hide();
      $("#emiDiv").hide();
    }
    $("#qty").val(1);
    //        $("#mLProCode").html(mcode);
    $("#prdName").val(mname);
    $("#productName").html(mname);
    $("#itemCode").val(mcode);
    itemCode = $("#itemCode").val();

    $("#sellingPrice").val(msellPrice-disAmo);
    $("#orgSellPrice").val(msellPrice);

    $("#unitcost").val(mcostPrice);
    $("#isSerial").val(misSerial);
    $("#upc").val(upc);
    $("#upm").html(upm);
    $("#emiNo").val(EmiNo);
    $("#isEmi").val(isEmiNo);

    if (vatSell == 0 || vatSell == null) {
      $("#proVatPrice").val(msellPrice);
    } else {
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

  discount_precent = parseFloat($("#disPercent").val());
  discount_amount = parseFloat($("#disAmount").val());
  discount = $("input[name='discount']:checked").val();
  discount_type = $("input[name='discount_type']:checked").val();

  //===========discount types===========================
  $("input[name='discount']").on("ifChanged", function () {
    var check = $(this).val();

    if (check == 1) {
      $("#disAmount").val(0);
    } else if (check == 2) {
      $("#disPercent").val(0);
    }
  });

  $("input[name='discount_type']").on("ifChanged", function () {
    var check = $(this).val();

    if (check == 1) {
      $("#disAmount").val(0);
    } else if (check == 2) {
      $("#disPercent").val(0);
    }
  });

  var sellingPrice = 0;
  var costPrice = 0;
  var casecost = 0;
  var isEmiNo = 0;
  $("#sellingPrice").blur(function () {
    sellingPrice = parseFloat($(this).val());
    costPrice = parseFloat($("#unitcost").val());

    if (costPrice > sellingPrice) {
      $.notify("Selling price can not be less than cost price.", "warning");
      return false;
    }
  });

  $("#unitcost").blur(function () {
    costPrice = parseFloat($(this).val());
    sellingPrice = parseFloat($("#sellingPrice").val());

    if (costPrice > sellingPrice) {
      $.notify("Selling price can not be less than cost price.", "warning");
      return false;
    }
  });

  $("#qty").blur(function () {
    $("#serialQty").val($(this).val());
    if ($(this).val() > minQty) {
      $.notify("Qty must be less than or equal" + minQty, "warning");
      return false;
    }
  });


$("#resetItems").on("click", function () {
    window.location.reload();
});




  var serialQty = 0;
  var newSerialQty = 0;
  var serialnoarr = [];

  var stockSerialnoArr = [];
  //=========Add products===============================
  $("#addItem").click(function () {
    add_products();
  });

  $("#serialNo").on("keydown", function (e) {
    if (e.which == 13) {
      add_products();
      e.preventDefault();
    }
  });
  $("#serialNo").autocomplete({
    source: function (request, response) {
      let invoice = $("#invoice").val();
      $.ajax({
        url: baseUrl + "/Invoice/loadproductSerial",
        dataType: "json",
        data: {
          q: request.term,
          location: loc,
          row_num: 1,
          proCode: itemCode,
          InvNo: invoice,
        },
        success: function (data) {
          console.log("here");
          response(
            $.map(data, function (item) {
              return {
                label: item.label,
                value: item.value,
                data: item,
                emiNo: item.emiNo,
              };
            })
          );
        },
      });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
      serialNo = ui.item.value;
      emiNo = ui.item.emiNo;
      $("#serialNo").val(serialNo);
      $("#emiNo").val(emiNo);
      $("#serialNoCheck").val(serialNo);
    },
  });
  $("#sellingPrice").on("keydown", function (e) {
    if (e.which == 13) {
      add_products();
      e.preventDefault();
    }
  });
  var isSellZero = 0;
  var vatSellingPrice = 0;
  var EmeiPriceArr = [];
  function add_products() {
    var serialQty = 0;
    sellingPrice = parseFloat($("#sellingPrice").val());
    orgSellingPrice = parseFloat($("#orgSellPrice").val());
    var unit = $("#mUnit option:selected").val();
    var prdName = $("#prdName").val();
    var itemCode = $("#itemCode").val();
    var serialNo = $("#serialNo").val();
    var emiNo = $("#emiNo").val();
    var is_serail = $("#isSerial").val();
    var isEmi = $("#isEmi").val();

    priceLevel = $("#priceLevel option:selected").val();
    var qty = parseFloat($("#qty").val());
    var getqty = parseFloat($("#getqty").val());

    var upc = parseFloat($("#upc").val());
    costPrice = parseFloat($("#unitcost").val());

    var freeQty = parseFloat($("#freeqty").val());
    var case1 = $("#mUnit option:selected").val();

    var salesperson = 0;
    var salespname = $("#salesperson option:selected").html();
    var warranty = $("#warrantytype option:selected").val();
    var warrantytype = $("#warrantytype option:selected").html();

    var serialNoCheck = $("#serialNoCheck").val();
    var priceStock = parseFloat($("#priceStock").html());

    if (salesperson == "") {
      salespname = "";
    } else {
      salespname = salespname;
    }

    if (warranty == "") {
      warrantytype = "";
    } else {
      warrantytype = warrantytype;
    }
    newSerialQty = parseFloat($("#serialQty").val());

    var isNewVat = $("input[name='isProVat']:checked").val();
    var isNewNbt = $("input[name='isProNbt']:checked").val();
    var newNbtRatio = parseFloat($("#proNbtRatio").val());

    isSellZero = $("input[name='isZero']:checked").val();

    if (isNewVat) {
      isNewVat = 1;
    } else {
      isNewVat = 0;
    }

    if (isNewNbt) {
      isNewNbt = 1;
    } else {
      isNewNbt = 0;
    }

    if (isSellZero) {
      isSellZero = 1;
    } else {
      isSellZero = 0;
    }

    maxSerialQty = qty;
    maxSerialQty2 = qty;

    if (is_serail == 1 && isEmiNo == 0) {
      serialQty = newSerialQty;
      qty = qty;
    } else {
      qty = qty;
    }

    if (case1 == "Unit" || case1 == "UNIT") {
      qty = qty;
    } else if (case1 == "Case" || case1 == "CASE") {
      qty = upc * qty;
      casecost = costPrice * qty;
    }

    var itemCodeArrIndex = $.inArray(itemCode, itemcode);
    var itemCodeSellingPrice = itemCode + "_" + sellingPrice;

    var itemCodesellArrIndex = $.inArray(itemCodeSellingPrice, itemcode);
    isEmiNo = 0;

    if (itemCode == "" || itemCode == 0) {
      $.notify("Please select a item.", "warning");
      return false;
    } else if (
      (sellingPrice == "" ||
        sellingPrice == 0 ||
        isNaN(sellingPrice) == true) &&
      isSellZero == 0
    ) {
      $.notify("Selling price can not be 0.", "warning");
      return false;
    } else if (costPrice == "" || costPrice == 0 || isNaN(costPrice) == true) {
      $.notify("Cost price can not be 0.", "warning");
      return false;
    } else if (qty == "" || qty == 0 || isNaN(qty) == true) {
      $.notify("Please enter a qty.", "warning");
      return false;
    } else if (costPrice > sellingPrice && isSellZero == 0) {
      $.notify("Selling price can not be less than cost price.", "warning");
      return false;
    }else if(qty>getqty){
     $.notify("Entered Quantity More that Invoice..", "warning");
     $("#qty").focus().select();
      return false;
    }
    //  else if (parseFloat(priceStock) < qty || parseFloat(priceStock) <= 0) {
    //   $.notify("Stock not available.", "warning");
    //   return false;
    // }
    else if (is_serail == 1 && serialNoCheck == "") {
      $.notify("Please Enter valid Serial No", "warning");
      return false;
    } else if (qty == "" || qty == 0 || isNaN(qty) == true) {
      $.notify("Please enter a qty.", "warning");
      return false;
    } else {
      if (is_serail == 0 && isEmi == 0) {
        if (itemCodesellArrIndex < 0) {
          totalNet2 = sellingPrice * qty;

          if (itemCode != customProCode) {
            itemcode.push(itemCode);
          }

          total_amount2 += totalNet2;
          totalCost += costPrice * qty;

          itemcode.push(itemCodeSellingPrice);

          $("#totalWithOutDiscount").val(total_amount2);

          calculateProductWiseDiscount(
            totalNet2,
            discount,
            discount_type,
            discount_precent,
            discount_amount,
            total_amount2,
            sellingPrice
          );

          proVat = addProductVat(totalNet, isNewVat, isNewNbt, newNbtRatio);
          proNbt = addProductNbt(totalNet, isNewVat, isNewNbt, newNbtRatio);

          totalNet += proVat;
          totalNet += proNbt;
          totalProVAT += parseFloat(proVat);
          totalProNBT += parseFloat(proNbt);
          cal_total(
            total_amount2,
            total_discount,
            totalExtraChrages,
            downPayment,
            total_dwn_interest,
            total_qur_interest,
            totalIterest,
            totalExtraAmount
          );

          i++;
          if (is_serail == 1) {
            serialQty--;
            $("#serialQty").val(serialQty);
          }
          var finalPrice = 0;
          var product_discount = 0;
          if (discount == 1) {
            // Discount by percentage
            product_discount = totalNet2 * (discount_precent / 100);
            finalPrice = totalNet2 - product_discount;
          } else {
            // Discount by amount
            product_discount = discount_amount;
            finalPrice = totalNet2 - product_discount * qty;
          }

          $("#tbl_item tbody").append(
            "<tr serial_batch='0'  ri=" +
              i +
              " id=" +
              i +
              " isZero='" +
              isSellZero +
              "' proCode='" +
              itemCode +
              "' uc='" +
              unit +
              "' qty='" +
              qty +
              "' unit_price='" +
              sellingPrice +
              "'  vatunit_price='" +
              vatSellingPrice +
              "'  org_unit_price='" +
              orgSellingPrice +
              "' upc='" +
              upc +
              "' caseCost='" +
              casecost +
              "' isSerial='" +
              is_serail +
              "' isEmi='" +
              isEmi +
              "' serial='" +
              serialNo +
              "' emiNo='" +
              emiNo +
              "' discount_percent='" +
              discount_precent +
              "' cPrice='" +
              costPrice +
              "' pL='" +
              priceLevel +
              "' fQ='" +
              freeQty +
              "' nonDisTotalNet='" +
              totalNet2 +
              "' netAmount='" +
              totalNet2 +
              "' proDiscount='" +
              product_discount +
              "' proName='" +
              prdName +
              "'  isvat='" +
              isNewVat +
              "' isnbt='" +
              isNewNbt +
              "' nbtRatio='" +
              newNbtRatio +
              "' proVat='" +
              proVat +
              "' proNbt='" +
              proNbt +
              "'  salesPerson='" +
              salesperson +
              "'  isbatchSerial='0' warranty='" +
              warranty +
              "'>" +
              "<td class='text-center'>" +
              i +
              "</td>" +
              "<td class='text-left'>" +
              itemCode +
              "</td>" +
              "<td>" +
              prdName +
              "</td><td>" +
              "UNIT" +
              "</td>" +
              "<td class='qty" +
              i +
              "'>" +
              accounting.formatNumber(qty) +
              "</td>" +
              "<td class='text-right'>" +
              accounting.formatNumber(freeQty) +
              "</td>" +
              "<td class='text-right'>" +
              accounting.formatNumber(sellingPrice) +
              "</td>" +
              "<td class='text-center'>" +
              discount_precent +
              "</td>" +
              "<td class='text-right' >" +
              accounting.formatMoney(finalPrice) +
              "</td>" +
              "<td>" +
              serialNo +
              "</td>" +
              "<td>" +
              warrantytype +
              "</td>" +
              "<td>" +
              emiNo +
              "</td>" +
              "<td style='display:none'>" +
              is_serail +
              "</td>" +
              "<td style='display:none'>" +
              isEmi +
              "</td>" +
              // "<td>" + salespname + "</td>" +
              "<td><i class='glyphicon glyphicon-edit edit btn btn-info btn-xs'></i></td>" +
              "<td class='rem" +
              i +
              "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td>" +
              "</tr>"
          );
          clear_gem_data();

          if (is_serail != 1) {
            clear_gem_data();
          } else {
            if (serialQty == 0) {
              clear_gem_data();
            } else {
              $("#serialNo").val("");
              $("#serialNo").focus();
              clear_gem_data();
            }
          }
          setProductTable();
        } else {
          $.notify("Item already exists.", "warning");
          return false;
        }

        // here only serial items...
      } else if (is_serail == 1) {
        var serialNoArrIndex = $.inArray(serialNo, serialnoarr);
        var StockserialNoArrIndex = $.inArray(serialNo, stockSerialnoArr);
        var sellingPrice = parseFloat($("#sellingPrice").val()) || 0;

        if (typeof serialPriceArr === "undefined") {
          var serialPriceArr = [];
        }

        if (serialNo === "" || serialNo == 0) {
          $.notify("Serial Number cannot be empty.", "warning");
          $("#serialNo").focus();
          return false;
        } else if (is_serail == 1) {
          var duplicateSerial = serialPriceArr.find(
            (item) => item.serial === serialNo && item.price === sellingPrice
          );

          var sameSerialDiffPrice = serialPriceArr.find(
            (item) => item.serial === serialNo && item.price !== sellingPrice
          );

          if (duplicateSerial) {
            $.notify(
              "Serial Number already exists with the same price.",
              "warning"
            );
            $("#serialNo").val("");
            return false;
          }

          serialPriceArr.push({ serial: serialNo, price: sellingPrice });
        }

        if (
          (itemCodesellArrIndex >= 0 && is_serail == 1) ||
          (itemCodesellArrIndex < 0 && is_serail == 1)
        ) {
          if (serialnoarr.includes(serialNo)) {
            $.notify("Serial Number already added.", "warning");
            $("#serialNo").val("");
            return false;
          }
          totalNet2 = sellingPrice * qty;

          serialnoarr.push(serialNo);
          total_amount2 += totalNet2;
          totalCost += costPrice * qty;
          itemcode.push(itemCodeSellingPrice);
        }

        $("#totalWithOutDiscount").val(total_amount2);

        calculateProductWiseDiscount(
          totalNet2,
          discount,
          discount_type,
          discount_precent,
          discount_amount,
          total_amount2,
          sellingPrice
        );

        proVat = addProductVat(totalNet, isNewVat, isNewNbt, newNbtRatio);
        proNbt = addProductNbt(totalNet, isNewVat, isNewNbt, newNbtRatio);

        totalNet += proVat;
        totalNet += proNbt;
        totalProVAT += parseFloat(proVat);
        totalProNBT += parseFloat(proNbt);

        cal_total(
          total_amount2,
          total_discount,
          totalExtraChrages,
          downPayment,
          total_dwn_interest,
          total_qur_interest,
          totalIterest,
          totalExtraAmount
        );
        i++;

        if (is_serail == 1) {
          serialQty--;
          $("#serialQty").val(serialQty);
        }
        var finalPrice = 0;
        var product_discount = 0;
        if (discount == 1) {
          // Discount by percentage
          product_discount = totalNet2 * (discount_precent / 100);
          finalPrice = totalNet2 - product_discount;
        } else {
          // Discount by amount
          product_discount = discount_amount;
          finalPrice = totalNet2 - product_discount * qty;
        }

        $("#tbl_item tbody").append(
          "<tr serial_batch='" +
            serialBatch +
            "' ri=" +
            i +
            " id=" +
            i +
            "  isZero='" +
            isSellZero +
            "'  proCode='" +
            itemCode +
            "' uc='" +
            unit +
            "' qty='" +
            qty +
            "' unit_price='" +
            sellingPrice +
            "'  vatunit_price='" +
            vatSellingPrice +
            "'   org_unit_price='" +
            orgSellingPrice +
            "'  upc='" +
            upc +
            "' caseCost='" +
            casecost +
            "' isSerial='" +
            is_serail +
            "' isEmi='" +
            isEmi +
            "' serial='" +
            serialNo +
            "' emiNo='" +
            emiNo +
            "' discount_percent='" +
            discount_precent +
            "' cPrice='" +
            costPrice +
            "' pL='" +
            priceLevel +
            "' fQ='" +
            freeQty +
            "' nonDisTotalNet='" +
            totalNet2 +
            "' netAmount='" +
            totalNet2 +
            "' proDiscount='" +
            product_discount +
            "' proName='" +
            prdName +
            "'  isvat='" +
            isNewVat +
            "' isnbt='" +
            isNewNbt +
            "' nbtRatio='" +
            newNbtRatio +
            "' proVat='" +
            proVat +
            "' proNbt='" +
            proNbt +
            "' salesPerson='" +
            salesperson +
            "' isbatchSerial='1' warranty='" +
            warranty +
            "'>" +
            "<td class='text-center'>" +
            i +
            "</td>" +
            "<td class='text-left'>" +
            itemCode +
            "</td>" +
            "<td>" +
            prdName +
            "</td><td>" +
            "UNIT" +
            "</td>" +
            "<td class='qty" +
            i +
            "'>" +
            accounting.formatNumber(qty) +
            "</td>" +
            "<td class='text-right'>" +
            accounting.formatNumber(freeQty) +
            "</td>" +
            "<td class='text-right'>" +
            accounting.formatNumber(sellingPrice) +
            "</td>" +
            "<td class='text-center'>" +
            discount_precent +
            "</td>" +
            "<td class='text-right' >" +
            accounting.formatMoney(finalPrice) +
            "</td>" +
            "<td>" +
            serialNo +
            "</td>" +
            "<td>" +
            warrantytype +
            "</td>" +
            "<td>" +
            emiNo +
            "</td>" +
            "<td style='display:none'>" +
            is_serail +
            "</td>" +
            "<td style='display:none'>" +
            isEmi +
            "</td>" +
            // "<td>"+salespname+"</td>" +

            "<td class='rem" +
            i +
            "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td>" +
            "</tr>"
        );

        if (is_serail != 1) {
          clear_gem_data();
        } else {
          if (serialQty == 0) {
            clear_gem_data();

            serialBatch++;
          } else {
            $("#serialNo").val("");
            $("#serialNo").focus();
            clear_gem_data();
          }
        }
        setProductTable();
      } else if (isEmi == 1) {
        var sellingPrice = parseFloat($("#sellingPrice").val()) || 0;

        if (emiNo === "" || emiNo == 0) {
          $.notify("Serial Number cannot be empty.", "warning");
          $("#serialNo").focus();
          return false;
        } else if (isEmi == 1) {
          var emiExists = EmeiPriceArr.find((item) => item.emi === emiNo);

          if (emiExists) {
            $.notify("This EMI Number already added.", "warning");
            $("#serialNo").val("");
            return false;
          }

          EmeiPriceArr.push({ emi: emiNo, price: sellingPrice });
        }

        // if (
        //     (itemCodesellArrIndex >= 0 && isEmi == 1) ||
        //     (itemCodesellArrIndex < 0 && isEmi == 1)
        // ) {
        //     if (serialnoarr.includes(emiNo)) {
        //         $.notify("Serial Number already added.", "warning");
        //         $("#serialNo").val("");
        //         return false;
        //     }
        //     totalNet2 = sellingPrice * qty;

        //     serialnoarr.push(emiNo);
        //     total_amount2 += totalNet2;
        //     totalCost += costPrice * qty;
        //     itemcode.push(itemCodeSellingPrice);
        // }

        totalNet2 = sellingPrice * qty;
        total_amount2 += totalNet2;
        totalCost += costPrice * qty;

        $("#totalWithOutDiscount").val(total_amount2);

        calculateProductWiseDiscount(
          totalNet2,
          discount,
          discount_type,
          discount_precent,
          discount_amount,
          total_amount2,
          sellingPrice
        );

        proVat = addProductVat(totalNet, isNewVat, isNewNbt, newNbtRatio);
        proNbt = addProductNbt(totalNet, isNewVat, isNewNbt, newNbtRatio);

        totalNet += proVat;
        totalNet += proNbt;
        totalProVAT += parseFloat(proVat);
        totalProNBT += parseFloat(proNbt);

        cal_total(
          total_amount2,
          total_discount,
          totalExtraChrages,
          downPayment,
          total_dwn_interest,
          total_qur_interest,
          totalIterest,
          totalExtraAmount
        );
        i++;

        if (isEmi == 1) {
          serialQty--;
          $("#serialQty").val(serialQty);
        }

        var finalPrice = 0;
        var product_discount = 0;
        if (discount == 1) {
          // Discount by percentage
          product_discount = totalNet2 * (discount_precent / 100);
          finalPrice = totalNet2 - product_discount;
        } else {
          // Discount by amount
          product_discount = discount_amount;
          finalPrice = totalNet2 - product_discount;
        }

        $("#tbl_item tbody").append(
          "<tr serial_batch='" +
            serialBatch +
            "' ri=" +
            i +
            " id=" +
            i +
            "  isZero='" +
            isSellZero +
            "'  proCode='" +
            itemCode +
            "' uc='" +
            unit +
            "' qty='" +
            qty +
            "' unit_price='" +
            sellingPrice +
            "'  vatunit_price='" +
            vatSellingPrice +
            "'   org_unit_price='" +
            orgSellingPrice +
            "'  upc='" +
            upc +
            "' caseCost='" +
            casecost +
            "' isSerial='" +
            is_serail +
            "' isEmi='" +
            isEmi +
            "' serial='" +
            serialNo +
            "' emiNo='" +
            emiNo +
            "' discount_percent='" +
            discount_precent +
            "' cPrice='" +
            costPrice +
            "' pL='" +
            priceLevel +
            "' fQ='" +
            freeQty +
            "' nonDisTotalNet='" +
            totalNet2 +
            "' netAmount='" +
            totalNet2 +
            "' proDiscount='" +
            product_discount +
            "' proName='" +
            prdName +
            "'  isvat='" +
            isNewVat +
            "' isnbt='" +
            isNewNbt +
            "' nbtRatio='" +
            newNbtRatio +
            "' proVat='" +
            proVat +
            "' proNbt='" +
            proNbt +
            "' salesPerson='" +
            salesperson +
            "' isbatchSerial='1' warranty='" +
            warranty +
            "'>" +
            "<td class='text-center'>" +
            i +
            "</td>" +
            "<td class='text-left'>" +
            itemCode +
            "</td>" +
            "<td>" +
            prdName +
            "</td><td>" +
            "UNIT" +
            "</td>" +
            "<td class='qty" +
            i +
            "'>" +
            accounting.formatNumber(qty) +
            "</td>" +
            "<td class='text-right'>" +
            accounting.formatNumber(freeQty) +
            "</td>" +
            "<td class='text-right'>" +
            accounting.formatNumber(sellingPrice) +
            "</td>" +
            "<td class='text-center'>" +
            discount_precent +
            "</td>" +
            "<td class='text-right' >" +
            accounting.formatMoney(finalPrice) +
            "</td>" +
            "<td>" +
            serialNo +
            "</td>" +
            "<td>" +
            warrantytype +
            "</td>" +
            "<td>" +
            emiNo +
            "</td>" +
            "<td style='display:none'>" +
            is_serail +
            "</td>" +
            "<td style='display:none'>" +
            isEmi +
            "</td>" +
            "<td class='rem" +
            i +
            "'><a href='#' class='remove btn btn-xs btn-danger'><i class='fa fa-remove'></i></a></td>" +
            "</tr>"
        );

        if (isEmi != 1) {
          clear_gem_data();
        } else {
          if (serialQty == 0) {
            clear_gem_data();

            serialBatch++;
          } else {
            $("#emiNo").val("");
            $("#emiNo").focus();

            clear_gem_data();
          }
        }
        setProductTable();
      } else {
        $.notify("Item already exists.", "warning");
        $("#serialNo").val("");
        return false;
      }
    }
  }

  $("#emiNo").autocomplete({
    source: function (request, response) {
      let sendData = {
        q: request.term,
        location: loc,
        row_num: 1,
        proCode: itemCode,
      };

      console.log("Sending Data:", sendData); // <<< HERE
      var invoice = $("#invoice").val();
      $.ajax({
        url: baseUrl + "/Invoice/loadproductEmei",
        dataType: "json",
        data: {
          q: request.term,
          location: loc,
          row_num: 1,
          proCode: itemCode,
          inv: invoice,
        },
        success: function (data) {
          response(
            $.map(data, function (item) {
              return {
                label: item.label,
                value: item.value,
                data: item,
                emiNo: item.emiNo,
              };
            })
          );
        },
      });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
      serialNo = ui.item.value;
      emiNo = ui.item.emiNo;
      $("#serialNo").val(serialNo);
      $("#emiNo").val(emiNo);
      $("#serialNoCheck").val(serialNo);
    },
  });

  function addProductVat(amount, vat, nbt, nratio) {
    if (vat == 1 && isTotalVat != 1 && isTotalNbt != 1) {
      proVat = parseFloat((amount * vatRate) / 100);
    } else {
      proVat = 0;
    }
    return proVat;
  }
  function addProductNbt(amount, vat, nbt, nratio) {
    if (nbt == 1 && isTotalVat != 1 && isTotalNbt != 1) {
      proNbt = ((amount * nbtRate) / 100) * nratio;
    } else {
      proNbt = 0;
    }
    return proNbt;
  }

  function addTotalVat(amount, vat, nbt, nratio) {
    if (vat == 1 && isProVat != 1 && isProNbt != 1) {
      totalVat = (amount * vatRate) / 100;
    } else {
      totalVat = 0;
    }
    return totalVat;
  }

  function addTotalNbt(amount, vat, nbt, nratio) {
    if (nbt == 1 && isProVat != 1 && isProNbt != 1) {
      totalNbt = ((amount * nbtRate) / 100) * nbtRatio;
    } else {
      totalNbt = 0;
    }
    return totalNbt;
  }
  //================remove row from grid subtraction total amount and discount==============================================
  $("#tbl_item").on("click", ".remove", function () {
    var rid = $(this).parent().parent().attr("ri");

    var r = confirm("Do you want to remove row no " + rid + " ?");
    if (r === true) {
      var totalNets = parseFloat(
        $(this).parent().parent().attr("nonDisTotalNet")
      );
      var proDiscount = parseFloat(
        $(this).parent().parent().attr("proDiscount")
      );
      var proCost = parseFloat($(this).parent().parent().attr("cPrice"));
      var setlAmount = parseFloat($(this).parent().parent().attr("setlAmount"));
      total_amount -= totalNets;
      total_amount2 -= totalNets;
      totalCost -= proCost;
      totalProWiseDiscount -= proDiscount;

      total_discount = totalProWiseDiscount + totalGrnDiscount;
      $("#totalWithOutDiscount").val(total_amount2);
      $("#totalAmount").html(accounting.formatMoney(total_amount2));
      $("#totalprodiscount").html(accounting.formatMoney(totalProWiseDiscount));

      if (setlAmount > 0) {
        pay_amount = setlAmount;
        removeSettleCreditAmount();
      }

      cal_total(
        total_amount2,
        total_discount,
        totalExtraChrages,
        downPayment,
        total_dwn_interest,
        total_qur_interest,
        totalIterest,
        totalExtraAmount
      );
      //remove product code from array
      var removeItem = $(this).parent().parent().attr("proCode");
      var removeSerial = $(this).parent().parent().attr("serial");

      itemcode = jQuery.grep(itemcode, function (value) {
        return value != removeItem;
      });

      serialnoarr = jQuery.grep(serialnoarr, function (value) {
        return value != removeSerial;
      });

      $(this).parent().parent().remove();
      setProductTable();
      return false;
    } else {
      return false;
    }
  });

  //================Common Functions====================//

  //===========calculate total summery======================================
  function cal_total(
    total,
    discount,
    extra,
    downPay,
    downPayInt,
    qurPayInt,
    totalInt,
    totalExtra
  ) {
    var total_net2 = parseFloat(total) - parseFloat(discount);
    finalAmount = parseFloat(total_net2);
    //        alert(total); alert(discount);
    $("#netgrnamount").html(accounting.formatMoney(total_net2));
    $("#grndiscount").html(accounting.formatMoney(discount));
    $("#totalgrn").html(accounting.formatMoney(total));
    total_discount = discount;
    total_amount = total;
    total_amount2 = total;
    totalNetAmount = total_net2;
  }

  //===============calculate product wise discount================================
  function calculateProductWiseDiscount(
    totalNet3,
    discount,
    discount_type,
    disPercent,
    disAmount,
    total_amount4
  ) {
    //product wise discount
    if (discount_type == 1) {
      if (discount == 1) {
        //discount by percent
        product_discount = totalNet3 * (disPercent / 100);
        disAmount = 0;
      } else if (discount == 2) {
        //discount by amount
        product_discount = disAmount;
        disPercent = (product_discount * 100) / totalNet3;
      }
      total_item_discount = 0;
    } else if (discount_type == 2) {
      //total item wise discount
      total_discount = 0;
      product_discount = 0;
      disPercent = 0;
    }
    totalProWiseDiscount += product_discount;
    total_discount = totalProWiseDiscount + totalGrnDiscount;
    total_amount += totalNet3;
    totalNet = totalNet3 - parseFloat(product_discount);
    discount_precent = accounting.formatNumber(disPercent);
    totalNetAmount = total_amount - parseFloat(total_discount);
    cal_total(
      total_amount,
      total_discount,
      totalExtraChrages,
      downPayment,
      total_dwn_interest,
      total_qur_interest
    );
    $("#discountAmount").html(accounting.formatMoney(total_discount));
    $("#totalprodiscount").html(accounting.formatMoney(totalProWiseDiscount));
  }

  function clear_data_after_save() {
    $("input[name=isLot][value='0']").prop("checked", true);
    getLastBatchNo();
    $("#tbl_item tbody").html("");
    $("#table_expenses tbody").html("");
    $("#cusCode").html("");
    $("#customer").val("");
    $("#creditLimit").html("0.00");
    $("#creditPeriod").html("0");
    $("#cusOutstand").html("0.00");
    $("#availableCreditLimit").html("0.00");
    $("#city").html("");
    $("#cusImage").hide();
    expensesArr.length = 0;
    pcode.length = 0;
    total_expenses = 0;
    $("#totalExpenses").html(0);
  }

  function setProductTable() {
    $("#tbl_item tbody tr").each(function (rowIndex, element) {
      var row = rowIndex + 1;
      $(this).find("[class]").eq(0).html(row);
      $(this).find("[class]").eq(0).parent().attr("ri", row);
      $(this).find("[class]").eq(0).parent().attr("id", row);
    });
  }

  function clear_gem_data() {
    $("#sellingPrice").val("");
    $("#serialNo").val("");
    $("#dv_SN").hide();
    $("#itemCode").val("");
    $("#itemCode").focus();
    $("#batchCode").val("");
    $("#unitcost").val(0);
    $("#remark").val("");
    $("#guessAmount").val(0);
    $("#qty").val(0);
    $("#cutWeight").val(0);
    $("#polishWeight").val(0);
    $("#totalNet").val(0);
    $("#buyAmount").val(0);
    $("#isCut").val(1);
    $("#isPolish").val(1);
    $("#isBuy").val(1);
    $(".gemoption").prop("checked", false);
    $(".rank").val(0);
    $("#disPercent").val(0);
    $("#disAmount").val(0);

    $("#totalAmount").html(accounting.formatMoney(total_amount));
    $("#netAmount").html(accounting.formatMoney(totalNetAmount));

    $("input[name=isCut][value='1']").prop("checked", false);
    $("input[name=isPolish][value='1']").prop("checked", false);
    $("input[name=isBuy][value='1']").prop("checked", false);
    totalNet2 = 0;
    discount = 0;
    discount_type = 0;
    discount_precent = 0;
    discount_amount = 0;
    product_discount = 0;
    itemCode = 0;
    casecost = 0;
    costPrice = 0;
    sellingPrice = 0;
  }

  function loadCustomerCreditInvoice(cusCode) {
    $("#tbl_payment tbody").html("");
    total_due_amount = 0;
    total_over_payment = 0;

    $.ajax({
      type: "POST",
      url: "../../admin/Payment/getCustomersDataById",
      data: { cusCode: cusCode },
      success: function (data) {
        var resultData = JSON.parse(data);
        var creditAmount = 0;
        var settleAmount = 0;
        $.each(resultData.credit_data, function (key, value) {
          var paymentNo = value.InvoiceNo;
          var invDate = value.InvoiceDate;
          var creditAmount = value.CreditAmount;
          var settleAmount = value.SettledAmount;
          var customerPayment = value.payAmount;
          var dueAmount = 0;
          total_due_amount += creditAmount - settleAmount;

          $("#tbl_payment tbody").append(
            "<tr id='" +
              (key + 1) +
              "'><td>" +
              (key + 1) +
              "</td><td  class='invoiceNo'>" +
              paymentNo +
              "</td><td>" +
              invDate +
              "</td><td class='text-right creditAmount'>" +
              accounting.formatMoney(creditAmount - settleAmount) +
              "</td><td class='text-right settleAmount' invPay='0'>" +
              accounting.formatMoney(0) +
              "</td><td class='text-right dueAmount' isColse='0'>" +
              accounting.formatMoney(creditAmount - settleAmount) +
              "</td></tr>"
          );
        });
        $("#tbl_payment").dataTable().fnDestroy();
      },
    });
  }
  var settleArry = [];
  function settleCreditAmount() {
    for (var i = 1; i <= $("#tbl_payment tbody tr").length; i++) {
      due_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".dueAmount")
            .html()
        )
      );
      credit_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".creditAmount")
            .html()
        )
      );
      settle_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".settleAmount")
            .html()
        )
      );

      //            if (credit_amount == settle_amount) {
      //                return  false;
      //            } else {
      if (due_amount <= pay_amount) {
        var due_amount3 = due_amount;
        var pay_amount3 = pay_amount;

        total_settle += due_amount;
        pay_amount -= due_amount;
        settle_amount = settle_amount + due_amount;

        change_amount = 0;
        due_amount = change_amount;
        //over payment for last invoice
        if (i == $("#tbl_payment tbody tr").length) {
          if (due_amount3 < pay_amount3) {
            total_settle = due_amount;
            settle_amount = settle_amount + due_amount;
            change_amount = 0;
            due_amount = change_amount;
          }
        }
      } else if (due_amount > pay_amount) {
        $("#" + i + " .settleAmount").attr("invPay", pay_amount);
        change_amount = due_amount - pay_amount;
        settle_amount = settle_amount + pay_amount;
        total_settle += pay_amount;
        due_amount = change_amount;
        pay_amount -= due_amount;

        if (total_settle > pay_amount) {
          pay_amount = 0;
        }
      }

      if (settle_amount >= credit_amount) {
        isInvoiceColse = 1;
      } else {
        isInvoiceColse = 0;
      }

      if ($("#" + i + " .settleAmount").attr("invPay") > 0) {
        $("#" + i)
          .addClass("rowselected")
          .siblings();
      }
      $("#" + i + " .settleAmount").attr("invPay", settle_amount);
      $("#tbl_payment tbody")
        .find("[id='" + i + "']")
        .children(".dueAmount")
        .html(accounting.formatMoney(change_amount));
      $("#tbl_payment tbody")
        .find("[id='" + i + "']")
        .children(".settleAmount")
        .html(accounting.formatMoney(settle_amount));
      settleArry.push(settle_amount);
    }

    //        }
  }

  function removeSettleCreditAmount() {
    for (var i = 1; i <= $("#tbl_payment tbody tr").length; i++) {
      due_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".creditAmount")
            .html()
        )
      );
      credit_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".creditAmount")
            .html()
        )
      );
      settle_amount = parseFloat(
        accounting.unformat(
          $("#tbl_payment tbody")
            .find("[id='" + i + "']")
            .children(".settleAmount")
            .html()
        )
      );

      if (due_amount <= pay_amount) {
        var due_amount3 = due_amount;
        var pay_amount3 = pay_amount;

        $("#" + i + " .settleAmount").attr("invPay", due_amount);
        total_settle -= due_amount;
        pay_amount += due_amount;
        settle_amount = settle_amount - due_amount;

        change_amount = 0;
        due_amount = change_amount;
        //over payment for last invoice
        if (i == $("#tbl_payment tbody tr").length) {
          if (due_amount3 < pay_amount3) {
            total_settle = due_amount;
            settle_amount = settle_amount - due_amount;
            change_amount = 0;
            due_amount = change_amount;
          }
        }
      } else if (due_amount > pay_amount) {
        $("#" + i + " .settleAmount").attr("invPay", pay_amount);
        change_amount = due_amount + pay_amount;
        settle_amount = settle_amount - pay_amount;
        total_settle -= pay_amount;
        due_amount = change_amount;
        pay_amount += due_amount;

        if (total_settle > pay_amount) {
          pay_amount = 0;
        }
      }

      if (settle_amount >= credit_amount) {
        isInvoiceColse = 1;
      } else {
        isInvoiceColse = 0;
      }

      if ($("#" + i + " .settleAmount").attr("invPay") > 0) {
        $("#" + i)
          .addClass("rowselected")
          .siblings();
      }

      $("#tbl_payment tbody")
        .find("[id='" + i + "']")
        .children(".dueAmount")
        .html(accounting.formatMoney(change_amount));
      $("#tbl_payment tbody")
        .find("[id='" + i + "']")
        .children(".settleAmount")
        .html(accounting.formatMoney(settle_amount));
    }
  }

  $("#saveItems").click(function () {
    setProductTable();
    var rowCount = $("#tbl_item tr").length;
    var product_code = new Array();
    var emi_no = new Array();
    var item_code = new Array();
    var serial_no = new Array();
    var pro_name = new Array();
    var qty = new Array();
    var unit_price = new Array();
    var unit_type = new Array();
    var unitPC = new Array();
    var caseCost = new Array();
    var discount_precent = new Array();
    var pro_discount = new Array();
    var total_net = new Array();
    var isSerial = new Array();
    var price_level = new Array();
    var fee_qty = new Array();
    var cost_price = new Array();
    var pro_total = new Array();

    var invDate = $("#invDate").val();
    var invUser = $("#invUser").val();
    var returnlocation = $("#invlocation").val();
    var invType = $("#invType option:selected").val();
    //        var chequeReference = $("#chequeReference").val();
    //        var chequeRecivedDate = $("#chequeReciveDate").val();
    //        var chequeDate = $("#chequeDate").val();
    //        var cash_amount = parseFloat($("#cashAmount").val());
    //        var cheque_amount = parseFloat($("#chequeAmount").val());
    //        var credit_amount = parseFloat($("#creditAmount").val());
    //        var return_amount = parseFloat($("#returnPayment").val());
    //        var refund_amount = parseFloat($("#refundAmount").val());
    //
    //        var dwn_payment = parseFloat(accounting.unformat($("#downPayment").val()));

    var invoicenumber = $("#invoicenumber").val();
    var additional = $("#additional").val();
    var remark = $("#grnremark").val();

    //        customer_payment = cash_amount + cheque_amount + credit_amount + return_amount;

    $("#tbl_item tbody tr").each(function (rowIndex, element) {
      product_code.push($(this).attr("proCode"));
      serial_no.push($(this).attr("serial"));
      qty.push($(this).attr("qty"));
      unit_price.push($(this).attr("unit_price"));
      discount_precent.push($(this).attr("discount_percent"));
      pro_discount.push($(this).attr("proDiscount"));
      total_net.push($(this).attr("netAmount"));
      price_level.push($(this).attr("pL"));
      unit_type.push($(this).attr("uc"));
      fee_qty.push($(this).attr("fQ"));
      cost_price.push($(this).attr("cPrice"));
      console.log(cost_price);
      unitPC.push($(this).attr("upc"));
      caseCost.push($(this).attr("caseCost"));
      pro_total.push($(this).attr("nonDisTotalNet"));
      isSerial.push($(this).attr("isSerial"));
      pro_name.push($(this).attr("proName"));
      emi_no.push($(this).attr("emiNo"));
    });

    var sendProduct_code = JSON.stringify(product_code);
    var sendPro_name = JSON.stringify(pro_name);
    var sendSerial_no = JSON.stringify(serial_no);
    var sendQty = JSON.stringify(qty);
    var sendUnit_price = JSON.stringify(unit_price);
    var sendDiscount_precent = JSON.stringify(discount_precent);
    var sendPro_discount = JSON.stringify(pro_discount);
    var sendTotal_net = JSON.stringify(total_net);
    var sendPrice_level = JSON.stringify(price_level);
    var sendUnit_type = JSON.stringify(unit_type);
    var sendFree_qty = JSON.stringify(fee_qty);
    var sendCost_price = JSON.stringify(cost_price);
    var sendUpc = JSON.stringify(unitPC);
    var sendCaseCost = JSON.stringify(caseCost);
    var sendPro_total = JSON.stringify(pro_total);
    var sendIsSerial = JSON.stringify(isSerial);
    var sendEmiNo = JSON.stringify(emi_no);

    var r = confirm("Do you want to save this return invoice.?");
    if (r == true) {
      if (rowCount - 1 == "0" || rowCount - 1 == "") {
        $.notify("Please add products.", "warning");
        $("html, body").animate(
          { scrollTop: $("#location").offset().top },
          "slow"
        );
        return false;
      } else {
        console.log({
          invoicenumber: invNo,
          invType: invType,
          remark: remark,
          product_code: sendProduct_code,
          serial_no: sendSerial_no,
          qty: sendQty,
          unit_price: sendUnit_price,
          discount_precent: sendDiscount_precent,
          pro_discount: sendPro_discount,
          total_net: sendTotal_net,
          unit_type: sendUnit_type,
          price_level: sendPrice_level,
          upc: sendUpc,
          case_cost: sendCaseCost,
          freeQty: sendFree_qty,
          cost_price: sendCost_price,
          pro_total: sendPro_total,
          isSerial: sendIsSerial,
          proName: sendPro_name,
          total_cost: totalCost,
          totalProDiscount: totalProWiseDiscount,
          totalGrnDiscount: totalGrnDiscount,
          invDate: invDate,
          invUser: invUser,
          total_amount: total_amount,
          total_discount: total_discount,
          total_net_amount: totalNetAmount,
          location: returnlocation,
          cuscode: cusCode,
          emeiNo: sendEmiNo,
        });

        $.ajax({
          type: "post",
          url: "saveReturn",
          data: {
            invoicenumber: invNo,
            invType: invType,
            remark: remark,
            product_code: sendProduct_code,
            serial_no: sendSerial_no,
            qty: sendQty,
            unit_price: sendUnit_price,
            discount_precent: sendDiscount_precent,
            pro_discount: sendPro_discount,
            total_net: sendTotal_net,
            unit_type: sendUnit_type,
            price_level: sendPrice_level,
            upc: sendUpc,
            case_cost: sendCaseCost,
            freeQty: sendFree_qty,
            cost_price: sendCost_price,
            pro_total: sendPro_total,
            isSerial: sendIsSerial,
            proName: sendPro_name,
            total_cost: totalCost,
            totalProDiscount: totalProWiseDiscount,
            totalGrnDiscount: totalGrnDiscount,
            invDate: invDate,
            invUser: invUser,
            total_amount: total_amount,
            total_discount: total_discount,
            total_net_amount: totalNetAmount,
            location: returnlocation,
            cuscode: cusCode,
            emeiNo: sendEmiNo,
          },
          success: function (data) {
            var resultData = JSON.parse(data);
            var feedback = resultData["fb"];
            var invNumber = resultData["InvNo"];
            if (feedback != 1) {
              $.notify("Return not saved successfully.", "warning");
              $("#loadBarCode").hide();
              $("#dwnLink").hide();
              return false;
            } else {
              $.notify("Return saved successfully.", "success");
              $("input[name=suppliercheck][value='1']").prop("checked", false);
              //                        $('#tbl_item tbody').html("");
              $("#invoicenumber").val("");
              $("#supplier").val("");
              $("#totalgrn").html("0.00");
              $("#grndiscount").html("0.00");
              $("#netgrnamount").html("0.00");
              $("#grnremark").val("");
              invNo = "";
              total_amount = 0;
              total_discount = 0;
              totalNetAmount = 0;
              supcode = 0;
              creditAmount = 0;
              dueAmount = 0;
              totalProWiseDiscount = 0;
              totalGrnDiscount = 0;
              $("#cashAmount").val(0);
              $("#chequeAmount").val(0);
              $("#creditAmount").val(0);

              $("#totalExpenses").html(0);
              //                        getLastBatchNo();
              $("#itemTable").show();
              $("#costTable").hide();
              $("#totalAmount").html("0.00");
              $("#totalgrndiscount").html("0.00");
              $("#totalprodiscount").html("0.00");
              $("#dueAmount2").html("0.00");
              $("#loadBarCode").hide();
              $("#dwnLink").show();
              $("#saveItems").attr("disabled", true);
            }
          },
        });
      }
    } else {
      return false;
    }
  });
});
