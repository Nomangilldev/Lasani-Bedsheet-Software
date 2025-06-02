$(document).ready(function () {
  document.onkeyup = function (e) {
    if (e.altKey && e.which == 13) {
      //enter press
      $("#addProductPurchase").trigger("click");
      // subAmount();
    }
    if (e.altKey && e.which == 83) {
      //s press
      $("#sale_order_btn").click();
    }
    if (e.altKey && e.which == 80) {
      //s press
      Swal.clickConfirm();
    }
  };
  $("#formData").on("submit", function (e) {
    //console.log('click');
    e.preventDefault();
    var form = $("#formData");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#formData_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#formData").each(function () {
            this.reset();
          });
          $("#tableData").load(location.href + " #tableData > *");
          $(".modal").modal("hide");
        }
        $("#formData_btn").prop("disabled", false);
        console.log(response.sts);
        sweeetalert(response.msg, response.sts, 1500);
      },
    }); //ajax call
  }); //main
  $("#formData1").on("submit", function (e) {
    e.stopPropagation();
    e.preventDefault();
    var form = $("#formData1");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#formData1_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#formData1").each(function () {
            this.reset();
          });
          //$("#tableData").load(location.href+" #tableData");
          $(".modal").modal("hide");
        }
        $("#formData1_btn").prop("disabled", false);

        $("#tableData1").load(location.href + " #tableData1 > *");

        sweeetalert(response.msg, response.sts, 1500);
      },
    }); //ajax call
  }); //main
  $("#formData2").on("submit", function (e) {
    e.stopPropagation();
    e.preventDefault();
    var form = $("#formData2");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#formData2_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#formData2").each(function () {
            this.reset();
          });
          //$("#tableData").load(location.href+" #tableData");
          $(".modal").modal("hide");
        }
        $("#formData2_btn").prop("disabled", false);

        $("#tableData2").load(location.href + " #tableData2 > *");

        sweeetalert(response.msg, response.sts, 1500);
      },
    }); //ajax call
  }); //main
  $("#sale_order_fm").on("submit", function (e) {
    e.preventDefault();
    var form = $("#sale_order_fm");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#sale_order_print").prop("disabled", true);
        $("#sale_order_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#sale_order_fm").each(function () {
            this.reset();
          });
          $("#purchase_product_tb").html("");
          $("#product_grand_total_amount").html("");
          $("#product_total_amount").html("");

          // 	window.location.assign('print_order.php?order_id='+response.order_id);

          //$("#tableData").load(location.href+" #tableData");
          Swal.fire({
            title: response.msg,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Print`,
            denyButtonText: `Add New`,
          }).then((result) => {
            if (result.isConfirmed) {
              // Open the invoice in a new tab
              window.open(
                response.print_url +
                "?id=" +
                response.order_id +
                "&type=" +
                response.type
              );
              setTimeout(() => {
                location.reload();
              }, 2000); // Adjust delay as needed
            } else if (result.isDenied) {
              // Reload the page for "Add New"
              location.reload();
            }
          });
        }
        if (response.sts == "error") {
          sweeetalert(response.msg, response.sts, 1500);
        }
        $("#sale_order_btn").prop("disabled", false);
        //
      },
    }); //ajax call
  }); //main
  $("#credit_order_client_name").on("change", function () {
    var value = $("#credit_order_client_name :selected").data("id");
    var contact = $("#credit_order_client_name :selected").data("contact");
    $("#customer_account").val(value);
    $("#client_contact").val(contact);
  });

  $("#add_product_fm").on("submit", function (e) {
    e.preventDefault();
    var form = $(this);
    var fd = new FormData(this);

    $.ajax({
      url: form.attr("action"),
      type: form.attr("method"),
      data: fd,
      dataType: "json",
      contentType: false,
      processData: false,
      beforeSend: function () {
        $("#add_product_btn").prop("disabled", true);
      },
      success: function (response) {
        console.log("click");
        sweeetalert(response.msg, response.sts, 1500);
        $("#add_product_btn").prop("disabled", false);
        var product_add_from = $("#product_add_from").val();
        if (product_add_from == "modal") {
          $("#get_product_name").load(location.href + " #get_product_name > *");
          $("#add_product_modal").modal("hide");
        }

        console.log(response.sts);
        if (response.sts == "success") {
          $("#add_product_fm").each(function () {
            this.reset();
            location.reload();
          });
        }
      },
    }); //ajax call
  }); //main

  $("#voucher_general_fm").on("submit", function (e) {
    e.preventDefault();
    var form = $("#voucher_general_fm");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#voucher_general_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#voucher_general_fm").each(function () {
            this.reset();
          });
          $("#tableData").load(location.href + " #tableData");
        }
        $("#voucher_general_btn").prop("disabled", false);

        Swal.fire({
          title: response.msg,
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: `Print`,
          denyButtonText: `Add New`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            Swal.fire({
              title: "Which type of do you Print?",
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText: `Debit`,
              denyButtonText: `Credit`,
              cancelButtonText: "Both",
            }).then((result) => {
              if (result.isConfirmed) {
                window.open(
                  "print_voucher.php?type=debit&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              } else if (result.isDenied) {
                window.open(
                  "print_voucher.php?type=credit&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              } else {
                window.open(
                  "print_voucher.php?type=both&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              }
            });
            //
          } else if (result.isDenied) {
            location.reload();
          }
        });
      },
    }); //ajax call
  }); //main
  $("#voucher_expense_fm").on("submit", function (e) {
    e.preventDefault();
    var form = $("#voucher_expense_fm");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#voucher_expense_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#voucher_expense_fm").each(function () {
            this.reset();
          });
          $("#tableData").load(location.href + " #tableData");
        }
        $("#voucher_expense_btn").prop("disabled", false);
        //    sweeetalert(response.msg,response.sts,1500);
        Swal.fire({
          title: response.msg,
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: `Print`,
          denyButtonText: `Add New`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            Swal.fire({
              title: "Which type of do you Print?",
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText: `Debit`,
              denyButtonText: `Credit`,
              cancelButtonText: "Both",
            }).then((result) => {
              if (result.isConfirmed) {
                window.open(
                  "print_voucher.php?type=debit&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              } else if (result.isDenied) {
                window.open(
                  "print_voucher.php?type=credit&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              } else {
                window.open(
                  "print_voucher.php?type=both&voucher_id=" +
                  response.voucher_id,
                  "_blank"
                );
              }
            });
          } else if (result.isDenied) {
            location.reload();
          }
        });
      },
    }); //ajax call
  }); //main
  $("#voucher_single_fm").on("submit", function (e) {
    e.preventDefault();
    var form = $("#voucher_single_fm");
    $.ajax({
      type: "POST",
      url: form.attr("action"),
      data: form.serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#voucher_single_btn").prop("disabled", true);
      },
      success: function (response) {
        if (response.sts == "success") {
          $("#voucher_single_fm").each(function () {
            this.reset();
          });
          $("#tableData").load(location.href + " #tableData");
        }
        $("#voucher_single_btn").prop("disabled", false);
        sweeetalert(response.msg, response.sts, 1500);
        Swal.fire({
          title: response.msg,
          showDenyButton: true,
          showCancelButton: true,
          confirmButtonText: `Print`,
          denyButtonText: `Add New`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            window.open(
              "print_voucher.php?type=debit&voucher_id=" + response.voucher_id,
              "_blank"
            );
          } else if (result.isDenied) {
            location.reload();
          }
        });
      },
    }); //ajax call
  }); //main
  $("#get_product_code").on("keyup", function () {
    var code = $("#get_product_code").val();
    var credit_sale_type = $("#credit_sale_type").val();
    var payment_type = $("#payment_type").val();
    //   var podid=  $('#get_product_name :selected').val();

    $.ajax({
      type: "POST",
      url: "php_action/custom_action.php",
      data: {
        get_products_list: code,
        type: "code"
      },
      dataType: "text",
      success: function (msg) {
        var res = msg.trim();
        $("#get_product_name").empty().html(res);
      },
    }); //ajax call }
    $.ajax({
      type: "POST",
      url: "php_action/custom_action.php",
      data: {
        getPrice: code,
        type: "code",
        credit_sale_type: credit_sale_type,
        payment_type: payment_type,
      },
      dataType: "json",
      success: function (response) {
        $("#get_product_price").val(response.price);
        $("#instockQty").html("instock :" + response.qty);
        console.log(response.qty);
        if (payment_type == "cash_in_hand" || payment_type == "credit_sale") {
          $("#get_product_quantity").attr("max", response.qty);
          if (response.qty > 0) {
            $("#addProductPurchase").prop("disabled", false);
          } else {
            $("#addProductPurchase").prop("disabled", true);
          }
        }
      },
    }); //ajax call }
  });
}); /*--------------end of-------------------------------------------------------*/
function pending_bills(value) {
  $.ajax({
    url: "php_action/custom_action.php",
    type: "post",
    data: {
      pending_bills_detils: value
    },
    dataType: "json",
    success: function (response) {
      $("#bill_customer_name").empty().val(response.client_name);
      $("#order_id").empty().val(response.order_id);
      $("#bill_grand_total").empty().val(response.grand_total);
      $("#bill_paid_ammount").empty().val(response.paid);
      $("#bill_remaining").empty().val(response.due);
      $("#bill_paid").attr("max", response.due);
      $("#bill_paid").empty().val(0);
    },
  });
}

function getCustomer_name(value) {
  $.ajax({
    url: "php_action/custom_action.php",
    type: "post",
    data: {
      getCustomer_name: value
    },
    dataType: "text",
    success: function (response) {
      var data = response.trim();
      $("#sale_order_client_name").empty().val(data);
    },
  });
}

function getRemaingAmount() {
  var paid_ammount = $("#paid_ammount").val();
  var product_grand_total_amount = $("#product_grand_total_amount").html();
  var total = parseInt(product_grand_total_amount) - parseInt(paid_ammount);
  $("#remaining_ammount").val(total);
}

function loadProducts(id) {
  $.ajax({
    url: "php_action/custom_action.php",
    type: "post",
    data: {
      getProductPills: id
    },
    dataType: "text",
    success: function (response) {
      var data = response.trim();
      $("#products_list").empty().html(data);
    },
  });
}

$("#get_product_name").on("change", function () {
  //var code=  $('#get_product_code').val();
  var code = $("#get_product_name :selected").val();
  var payment_type = $("#payment_type").val();
  var credit_sale_type = $("#credit_sale_type").val();

  $.ajax({
    type: "POST",
    url: "php_action/custom_action.php",
    data: {
      get_products_list: code,
      type: "product"
    },
    dataType: "text",
    success: function (msg) {
      var res = msg.trim();
      $("#get_product_code").val(res);
    },
  }); //ajax call }
  $.ajax({
    type: "POST",
    url: "php_action/custom_action.php",
    data: {
      getPrice: code,
      type: "product",
      credit_sale_type: credit_sale_type,
      payment_type: payment_type,
    },
    dataType: "json",
    success: function (response) {
      setTimeout(function () {
        $("#get_product_price").val(response.price);
        // $("#get_product_sale_price").val(response.price);
        $("#sale_product_price").val(response.sale_price);
        $("#instockQty").html("instock :" + response.qty);
      }, 500);
      console.log(response.qty);
      if (payment_type == "cash_in_hand" || payment_type == "credit_sale") {
        $("#get_product_quantity").attr("max", response.qty);
        if (response.qty > 0) {
          $("#addProductPurchase").prop("disabled", false);
        } else {
          $("#addProductPurchase").prop("disabled", true);
        }
      }
    },
  }); //ajax call }
});
$("#product_code").on("change", function () {
  //var code=  $('#get_product_code').val();
  var code = $("#product_code").val();
  if (/^[A-Za-z0-9]+$/.test(code)) {
    $.ajax({
      type: "POST",
      url: "php_action/custom_action.php",
      data: {
        get_products_code: code
      },
      dataType: "json",
      success: function (response) {
        if (response.sts == "error") {
          $("#add_product_btn").prop("disabled", true);
          $("#product_code").val("");
          Swal.fire({
            position: "center",
            icon: "error",
            title: response.msg,
            showConfirmButton: true,
          });
        } else {
          $("#add_product_btn").prop("disabled", false);
        }
      },
    });
  } else {
    Swal.fire({
      position: "center",
      icon: "error",
      title: "Please Enter Only Alphabets and Number Without Space and Characters",
      showConfirmButton: true,
    });
  }
});
$("#full_payment_check").on("click", function () {
  var checked = $("#full_payment_check").is(":checked");
  var grand = $("#product_grand_total_amount").html();

  if (checked) {
    $("#paid_ammount").val(grand);
  } else {
    $("#paid_ammount").val(0);
  }
  $("#paid_ammount").trigger("keyup");
});
//function addProductPurchase() {

function getRandomInt(max) {
  return Math.floor(Math.random() * max);
}


// ============= add product sale
$("#addProductPurchase").on("click", function () {
  var total_price = 0;
  var payment_type = $("#payment_type").val();

  var name = $("#get_product_name :selected").text();
  var pro_details = $("#get_product_detail").val();
  var price = $("#get_product_price").val();
  var sale_price = $("#get_product_sale_price").val();
  var id = $("#get_product_name :selected").val();
  var code = $("#get_product_code").val();
  var product_quantity = parseInt($("#get_product_quantity").val());
  var pro_type = $("#add_pro_type").val();
  var max_qty = parseInt($("#get_product_quantity").attr("max"));

  if (payment_type == "cash_purchase" || payment_type == "credit_purchase") {
    max_qty = getRandomInt(99999999999);
  }

  var GrandTotalAva = $("#remaining_ammount").val();
  var ThisTotal = price * product_quantity + Number(GrandTotalAva);
  var RThisPersonLIMIT = $("#R_LimitInput").val();

  if (
    id !== "" &&
    product_quantity !== "" &&
    max_qty >= product_quantity &&
    code !== ""
  ) {
    // Reset input fields
    $("#get_product_name").prop("selectedIndex", 0);
    $("#add_pro_type").val("add");
    $("#get_product_code").val("");
    $("#get_product_price").val("");
    $("#get_product_sale_price").val("");
    $("#get_product_quantity").val("1");
    $("#get_product_code").focus();

    if ($("#product_idN_" + id).length) {
      // Product already exists in list
      $(".product_ids").each(function () {
        var quantity = $(this).data("quantity");
        total_price = 0;
        var val = $(this).val();

        if (val == id) {
          var Currentquantity = (pro_type === "add") ?
            parseInt(quantity) + parseInt(product_quantity) :
            parseInt(product_quantity);

          total_price = parseFloat(price) * parseFloat(Currentquantity);

          if (Currentquantity <= max_qty) {
            $("#product_idN_" + id).replaceWith(`
                          <tr id="product_idN_${id}">
                              <input type="hidden" data-price="${price}" data-quantity="${Currentquantity}"
                                     id="product_ids_${id}" class="product_ids" name="product_ids[]" value="${id}">
                              <input type="hidden" id="product_quantites_${id}" name="product_quantites[]" value="${product_quantity}">
                              <input type="hidden" id="product_rate_${id}" name="product_rates[]" value="${price}">
                              <input type="hidden" id="product_totalrate_${id}" name="product_totalrates[]" value="${total_price}">
                              <input type="hidden" id="product_salerate_${id}" name="product_salerates[]" value="${sale_price}">
                              <td>${code}</td>
                              <td>${name}</td>
                              <td>${price}</td>
                              ${sale_price ? `<td>${sale_price}</td>` : ""}
                              <td>${Currentquantity}</td>
                              <td>${total_price}</td>
                              <td>
                                  <button type="button" onclick="removeByid('#product_idN_${id}')" class="fa fa-trash text-danger"></button>
                                  <button type="button" onclick="editByid(${id}, '${code}', '${price}', '${product_quantity}')" class="fa fa-edit text-success"></button>
                              </td>
                          </tr>
                      `);
          } else {
            sweeetalert("Cannot Add Quantity more than stock", "error", 1500);
          }
        }
        getOrderTotal();
      });
    } else {
      // New product row
      total_price = parseFloat(price) * parseFloat(product_quantity);

      $("#purchase_product_tb").append(`
              <tr id="product_idN_${id}">
                  <input type="hidden" data-price="${price}" data-quantity="${product_quantity}"
                         id="product_ids_${id}" class="product_ids" name="product_ids[]" value="${id}">
                  <input type="hidden" id="product_quantites_${id}" name="product_quantites[]" value="${product_quantity}">
                  <input type="hidden" id="product_rate_${id}" name="product_rates[]" value="${price}">
                  <input type="hidden" id="product_totalrate_${id}" name="product_totalrates[]" value="${total_price}">
                  <input type="hidden" id="product_salerate_${id}" name="product_salerates[]" value="${sale_price}">
                  <td>${code}</td>
                  <td>${name}</td>
                  <td>${price}</td>
                  ${sale_price ? `<td>${sale_price}</td>` : ""}
                  <td>${product_quantity}</td>
                  <td>${total_price}</td>
                  <td>
                      <button type="button" onclick="removeByid('#product_idN_${id}')" class="fa fa-trash text-danger"></button>
                      <button type="button" onclick="editByid(${id}, '${code}', '${price}', '${product_quantity}')" class="fa fa-edit text-success"></button>
                  </td>
              </tr>
          `);

      getOrderTotal();
    }
  } else {
    if (max_qty < product_quantity) {
      sweeetalert("Cannot Add Quantity more than stock", "error", 1500);
    } else if (code === "") {
      sweeetalert("Select The Product first", "error", 1500);
    }
  }
});
// =============================================================================================================================

// ============= add product sale
$("#addProductSale").on("click", function () {
  var total_price = 0;
  var payment_type = $("#payment_type").val();

  var name = $("#get_product_name :selected").text();
  var pro_details = $("#get_product_detail").val();
  var price = $("#sale_product_price").val();
  var sale_price = $("#get_product_sale_price").val();
  var id = $("#get_product_name :selected").val();
  var code = $("#get_product_code").val();
  var product_quantity = parseInt($("#get_product_quantity").val());
  var pro_type = $("#add_pro_type").val();
  var max_qty = parseInt($("#get_product_quantity").attr("max"));

  if (payment_type == "cash_purchase" || payment_type == "credit_purchase") {
    max_qty = getRandomInt(99999999999);
  }

  var GrandTotalAva = $("#remaining_ammount").val();
  var ThisTotal = price * product_quantity + Number(GrandTotalAva);
  var RThisPersonLIMIT = $("#R_LimitInput").val();

  if (
    id !== "" &&
    product_quantity !== "" &&
    max_qty >= product_quantity &&
    code !== ""
  ) {
    // Reset input fields
    $("#get_product_name").prop("selectedIndex", 0);
    $("#add_pro_type").val("add");
    $("#get_product_code").val("");
    $("#sale_product_price").val("");
    $("#get_product_sale_price").val("");
    $("#get_product_quantity").val("1");
    $("#get_product_code").focus();

    if ($("#product_idN_" + id).length) {
      // Product already exists in list
      $(".product_ids").each(function () {
        var quantity = $(this).data("quantity");
        total_price = 0;
        var val = $(this).val();

        if (val == id) {
          var Currentquantity = (pro_type === "add") ?
            parseInt(quantity) + parseInt(product_quantity) :
            parseInt(product_quantity);

          total_price = parseFloat(price) * parseFloat(Currentquantity);

          if (Currentquantity <= max_qty) {
            $("#product_idN_" + id).replaceWith(`
                          <tr id="product_idN_${id}">
                              <input type="hidden" data-price="${price}" data-quantity="${Currentquantity}"
                                     id="product_ids_${id}" class="product_ids" name="product_ids[]" value="${id}">
                              <input type="hidden" id="product_quantites_${id}" name="product_quantites[]" value="${product_quantity}">
                              <input type="hidden" id="product_rate_${id}" name="product_rates[]" value="${price}">
                              <input type="hidden" id="product_totalrate_${id}" name="product_totalrates[]" value="${total_price}">
                              <input type="hidden" id="product_salerate_${id}" name="product_salerates[]" value="${sale_price}">
                              <td>${code}</td>
                              <td>${name}</td>
                              <td>${price}</td>
                              ${sale_price ? `<td>${sale_price}</td>` : ""}
                              <td>${Currentquantity}</td>
                              <td>${total_price}</td>
                              <td>
                                  <button type="button" onclick="removeByid('#product_idN_${id}')" class="fa fa-trash text-danger"></button>
                                  <button type="button" onclick="editByid(${id}, '${code}', '${price}', '${product_quantity}')" class="fa fa-edit text-success"></button>
                              </td>
                          </tr>
                      `);
          } else {
            sweeetalert("Cannot Add Quantity more than stock", "error", 1500);
          }
        }
        getOrderTotal();
      });
    } else {
      // New product row
      total_price = parseFloat(price) * parseFloat(product_quantity);

      $("#purchase_product_tb").append(`
              <tr id="product_idN_${id}">
                  <input type="hidden" data-price="${price}" data-quantity="${product_quantity}"
                         id="product_ids_${id}" class="product_ids" name="product_ids[]" value="${id}">
                  <input type="hidden" id="product_quantites_${id}" name="product_quantites[]" value="${product_quantity}">
                  <input type="hidden" id="product_rate_${id}" name="product_rates[]" value="${price}">
                  <input type="hidden" id="product_totalrate_${id}" name="product_totalrates[]" value="${total_price}">
                  <input type="hidden" id="product_salerate_${id}" name="product_salerates[]" value="${sale_price}">
                  <td>${code}</td>
                  <td>${name}</td>
                  <td>${price}</td>
                  ${sale_price ? `<td>${sale_price}</td>` : ""}
                  <td>${product_quantity}</td>
                  <td>${total_price}</td>
                  <td>
                      <button type="button" onclick="removeByid('#product_idN_${id}')" class="fa fa-trash text-danger"></button>
                      <button type="button" onclick="editByid(${id}, '${code}', '${price}', '${product_quantity}')" class="fa fa-edit text-success"></button>
                  </td>
              </tr>
          `);

      getOrderTotal();
    }
  } else {
    if (max_qty < product_quantity) {
      sweeetalert("Cannot Add Quantity more than stock", "error", 1500);
    } else if (code === "") {
      sweeetalert("Select The Product first", "error", 1500);
    }
  }
});
// =============================================================================================================================



function removeByid(id) {
  $(id).remove();
  getOrderTotal();
}

function getOrderTotal() {
  var payment_type = $("#payment_type").val();
  var total_bill = 0;

  $(".product_ids").each(function () {
    var quantity = parseFloat($(this).data("quantity"));
    var rates = parseFloat($(this).data("price"));

    // Validate and fallback to 0 if NaN
    quantity = isNaN(quantity) ? 0 : quantity;
    rates = isNaN(rates) ? 0 : rates;

    total_bill += rates * quantity;
  });

  // Parse discount and freight values safely
  var discount = parseFloat($("#ordered_discount").val());
  discount = isNaN(discount) ? 0 : discount;

  var discountAmount = total_bill * (discount / 100);

  var freight = parseFloat($("#freight").val());
  freight = isNaN(freight) ? 0 : freight;

  // Set freight to 0 for other payment types
  if (!(payment_type == "cash_in_hand" || payment_type == "credit_sale")) {
    freight = 0;
  }

  // Calculate grand total using percentage discount
  var grand_total = freight + total_bill - discountAmount;

  // Display totals in HTML
  $("#product_total_amount").html(total_bill.toFixed(2));
  $("#product_grand_total_amount").html(grand_total.toFixed(2));

  // Handle paid amount logic
  if (payment_type == "cash_in_hand" || payment_type == "cash_purchase") {
    $("#paid_ammount").val(grand_total.toFixed(2));
    $("#paid_ammount").attr("max", grand_total.toFixed(2));
    $("#paid_ammount").prop("required", true);

    if (payment_type == "cash_in_hand") {
      $("#full_payment_check").prop("checked", true);
    }
  } else {
    $("#paid_ammount").val("0");
    $("#paid_ammount").prop("required", false);
  }

  // Make payment account required only if total is greater than 0
  if (grand_total > 0) {
    $("input[name='payment_account']").prop("required", true);
  } else {
    $("input[name='payment_account']").prop("required", false);
  }

  // Optional: update remaining balance (if needed)
  getRemaingAmount();
}

function editByid(id, code, price, qty) {
  // alert(price);
  $(".searchableSelect").val(id);

  $("#get_product_code").val(code);
  $("#get_product_quantity").val(qty);
  $("#add_pro_type").val("update");

  var effect = function () {
    return $(".searchableSelect").select2().trigger("change");
  };

  $.when(effect()).done(function () {
    setTimeout(function () {
      $("#sale_product_price").val(price);
    }, 500);
  });
}

function getBalance(val, id) {
  setTimeout(function () {
    if (id == "customer_account_exp") {
      var value = $("#customer_account").val();
    } else {
      var value = val;
    }
    $.ajax({
      type: "POST",
      url: "php_action/custom_action.php",
      data: {
        getBalance: value
      },
      dataType: "json",
      success: function (response) {
        //alert(response.blnc);
        //var res=msg.trim();

        $("#" + id).html(response.blnc);
        $("#customer_Limit").html(response.custLimit);
        var RLIMIT = Math.abs(response.custLimit - response.blnc);
        //alert(RLIMIT);
        $("#R_Limit").html(RLIMIT);
        $("#R_LimitInput").val(RLIMIT);
      },
    }); //ajax call }
  }, 1000);
}
// ---------------------------order gui---------------------------------------

// var input = document.getElementById("barcode_product");
$("#barcode_product").on("keyup", function (event) {
  // input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
    var value = input.value;
    event.preventDefault();
    addbarcode_product(value, "plus");
    //   console.log(value);
    input.value = "";
  }
});

function addbarcode_product(code, action_value) {
  //$("#ordered_products").append(dbarcode_productata);

  $.ajax({
    url: "php_action/custom_action.php",
    type: "post",
    data: {
      getProductDetailsBycode: code
    },
    dataType: "json",
    success: function (res) {
      console.log(action_value);

      if ($("#product_idN_" + res.product_id).length) {
        var jsonObj = [];
        $(".product_ids").each(function (index) {
          var quantity = $(this).data("quantity");
          var val = $(this).val();

          if (val == res.product_id) {
            //$("#product_idN_"+id).remove();
            alert(res.product_id);
            if (action_value == "plus") {
              var Currentquantity = 1 + parseInt(quantity);
            }
            if (action_value == "minus") {
              var Currentquantity = parseInt(quantity) - 1;
            }

            $("#product_idN_" + res.product_id).replaceWith(
              '<tr id="product_idN_' +
              res.product_id +
              '">\
					<input type="hidden" data-price="' +
              res.rate +
              '" data-quantity="' +
              Currentquantity +
              '" id="product_ids_' +
              res.product_id +
              '" class="product_ids" name="product_ids[]" value="' +
              res.product_id +
              '">\
					<input type="hidden" id="product_quantites_' +
              res.product_id +
              '" name="product_quantites[]" value="' +
              Currentquantity +
              '">\
					<input type="hidden" id="product_rates_' +
              res.product_id +
              '" name="product_rates[]" value="' +
              res.rate +
              '">\
					<td>' +
              res.product_code +
              "  </td>\
          <td>" +
              res.product_name +
              ' (<span class="text-success">' +
              res.brand_name +
              "</span>) </td>\
					<td>" +
              res.rate +
              " </td>\
					<td>" +
              Currentquantity +
              " </td>\
					<td>" +
              res.current_rate * Currentquantity +
              ' </td>\
					<td> <button type="button" onclick="addbarcode_product(`' +
              res.product_code +
              '`,`plus`)" class="fa fa-plus text-success" href="#" ></button>\
						<button type="button" onclick="addbarcode_product(`' +
              res.product_code +
              '`,`minus`)" class="fa fa-minus text-warning" href="#" ></button>\
						<button type="button" onclick="removeByid(`#product_idN_' +
              res.product_id +
              '`)" class="fa fa-trash text-danger" href="#" ></button>\
						</td>\
					</tr>'
            );
          }
          getOrderTotal();
        });
      } else {
        $("#purchase_product_tb").append(
          '<tr id="product_idN_' +
          res.product_id +
          '">\
			          <input type="hidden" data-price="' +
          res.current_rate +
          '"  data-quantity="1" id="product_ids_' +
          res.product_id +
          '" class="product_ids" name="product_ids[]" value="' +
          res.product_id +
          '">\
			          <input type="hidden" id="product_quantites_' +
          res.product_id +
          '" name="product_quantites[]" value="1">\
			          <input type="hidden" id="product_rate_' +
          res.product_id +
          '" name="product_rates[]" value="' +
          res.current_rate +
          '">\
			          <input type="hidden" id="product_totalrate_' +
          res.product_id +
          '" name="product_totalrates[]" value="' +
          res.current_rate +
          '">\
			          <td>' +
          res.product_code +
          "  </td>\
                <td>" +
          res.product_name +
          ' (<span class="text-success">' +
          res.brand_name +
          "</span>)</td>\
			           <td>" +
          res.current_rate +
          "</td>\
			           <td>1</td>\
			          <td>" +
          res.current_rate +
          '</td>\
			          <td>\
			            <button type="button" onclick="addbarcode_product(`' +
          res.product_code +
          '`,`plus`)" class="fa fa-plus text-success" href="#" ></button>\
						<button type="button" onclick="addbarcode_product(`' +
          res.product_code +
          '`,`minus`)" class="fa fa-minus text-warning" href="#" ></button>\
						<button type="button" onclick="removeByid(`#product_idN_' +
          res.product_id +
          '`)" class="fa fa-trash text-danger" href="#" ></button>\
						</td>\
			          </tr>'
        );

        getOrderTotal();
      }
      //console.log(jsonObj);
    },
  });
}

// ---------------------------order gui---------------------------------------
function addProductOrder(id, max = 100, action_value) {
  //$("#ordered_products").append(data);

  $.ajax({
    url: "php_action/custom_action.php",
    type: "post",
    data: {
      getProductDetails: id
    },
    dataType: "json",
    success: function (res) {
      console.log(action_value);

      if ($("#product_idN_" + id).length) {
        var jsonObj = [];
        $(".product_ids").each(function (index) {
          var quantity = $(this).data("quantity");
          var val = $(this).val();

          if (val == id) {
            //$("#product_idN_"+id).remove();

            if (action_value == "plus") {
              var Currentquantity = 1 + parseInt(quantity);
            }
            if (action_value == "minus") {
              var Currentquantity = parseInt(quantity) - 1;
            }

            $("#product_idN_" + id).replaceWith(
              '<tr id="product_idN_' +
              id +
              '">\
          <input type="hidden" data-price="' +
              res.current_rate +
              '" data-quantity="' +
              Currentquantity +
              '" id="product_ids_' +
              id +
              '" class="product_ids" name="product_ids[]" value="' +
              res.product_id +
              '">\
          <input type="hidden" id="product_quantites_' +
              id +
              '" name="product_quantites[]" value="' +
              Currentquantity +
              '">\
          <input type="hidden" id="product_rates_' +
              id +
              '" name="product_rates[]" value="' +
              res.current_rate +
              '">\
          <td>' +
              res.product_name +
              ' (<span class="text-success">' +
              res.brand_name +
              "</span>) </td>\
          <td>" +
              res.current_rate +
              " </td>\
          <td>" +
              Currentquantity +
              " </td>\
          <td>" +
              res.current_rate * Currentquantity +
              ' </td>\
          <td> <button type="button" onclick="addProductOrder(' +
              id +
              "," +
              res.quantity +
              ',`plus`)" class="fa fa-plus text-success" href="#" ></button>\
            <button type="button" onclick="addProductOrder(' +
              id +
              "," +
              res.quantity +
              ',`minus`)" class="fa fa-minus text-warning" href="#" ></button>\
            <button type="button" onclick="removeByid(`#product_idN_' +
              id +
              '`)" class="fa fa-trash text-danger" href="#" ></button>\
            </td>\
          </tr>'
            );
          }
          getOrderTotal();
        });
      } else {
        $("#purchase_product_tb").append(
          '<tr id="product_idN_' +
          id +
          '">\
                <input type="hidden" data-price="' +
          res.current_rate +
          '"  data-quantity="1" id="product_ids_' +
          id +
          '" class="product_ids" name="product_ids[]" value="' +
          id +
          '">\
                <input type="hidden" id="product_quantites_' +
          id +
          '" name="product_quantites[]" value="1">\
                <input type="hidden" id="product_rate_' +
          id +
          '" name="product_rates[]" value="' +
          res.current_rate +
          '">\
                <input type="hidden" id="product_totalrate_' +
          id +
          '" name="product_totalrates[]" value="' +
          res.current_rate +
          '">\
                <td>' +
          res.product_name +
          ' (<span class="text-success">' +
          res.brand_name +
          "</span>)</td>\
                 <td>" +
          res.current_rate +
          "</td>\
                 <td>1</td>\
                <td>" +
          res.current_rate +
          '</td>\
                <td>\
                  <button type="button" onclick="addProductOrder(' +
          id +
          "," +
          res.quantity +
          ',`plus`)" class="fa fa-plus text-success" href="#" ></button>\
            <button type="button" onclick="addProductOrder(' +
          id +
          "," +
          res.quantity +
          ',`minus`)" class="fa fa-minus text-warning" href="#" ></button>\
            <button type="button" onclick="removeByid(`#product_idN_' +
          id +
          '`)" class="fa fa-trash text-danger" href="#" ></button>\
            </td>\
                </tr>'
        );

        getOrderTotal();
      }
      //console.log(jsonObj);
    },
  });
}

function readonlyIt(value, read_it_id) {
  if (value == "") {
    $("#" + read_it_id).prop("readonly", false);
  } else {
    $("#" + read_it_id).prop("readonly", true);
  }
}

$("#product_mm,#product_inch,#product_meter").on("keyup", function () {
  getTotal_price();
});

$("#tableData1").on("change", function () {
  getTotal_price();
});

function getTotal_price() {
  var total = (total1 = total2 = fif_rate = current_cat = thir_rate = 0);
  var cat = $("#tableData1 :selected").data("price");
  var product_mm = $("#product_mm").val();
  var product_inch = $("#product_inch").val();
  var product_meter = $("#product_meter").val();
  var product_mm = product_mm == "" ? (product_mm = 0) : parseFloat(product_mm);
  var product_inch =
    product_inch == "" ? (product_inch = 0) : parseFloat(product_inch);
  var product_meter =
    product_meter == "" ? (product_meter = 0) : parseFloat(product_meter);
  total = product_mm * product_inch * product_meter;
  total1 = (total * parseFloat(cat)) / 54;
  total2 = Math.round(total1);
  $("#current_rate").val(total2);

  current_cat = parseFloat(cat) + 0.05;
  fif_rate = (total * current_cat) / 54;
  fif_rate = Math.round(fif_rate);
  $("#f_days").val(fif_rate);

  current_cat = parseFloat(cat) + 0.1;
  thir_rate = (total * current_cat) / 54;
  thir_rate = Math.round(thir_rate);
  $("#t_days").val(thir_rate);

  console.log(total);
}

function getVoucherPrint(voucher_id) {
  Swal.fire({
    title: "Which type of do you Print?",
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Debit`,
    denyButtonText: `Credit`,
    cancelButtonText: "Both",
  }).then((result) => {
    if (result.isConfirmed) {
      window.open(
        "print_voucher.php?type=debit&voucher_id=" + voucher_id,
        "_blank"
      );
    } else if (result.isDenied) {
      window.open(
        "print_voucher.php?type=credit&voucher_id=" + voucher_id,
        "_blank"
      );
    } else {
      window.open(
        "print_voucher.php?type=both&voucher_id=" + voucher_id,
        "_blank"
      );
    }
  });
}

function setAmountPaid(id, paid) {
  Swal.fire({
    title: "Did the Customer Paid All Amount?",
    showCancelButton: true,
    confirmButtonText: `Yes`,
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "php_action/custom_action.php",
        type: "post",
        data: {
          setAmountPaid: id,
          paid: paid
        },
        dataType: "json",
        success: function (res) {
          sweeetalert(res.msg, res.sts, 1500);
          if (res.sts == "success") {
            //$("#view_orders_tb").load(location.href+" #view_orders_tb > *");
            location.reload();
          }
        },
      });
    } else {}
  });
}