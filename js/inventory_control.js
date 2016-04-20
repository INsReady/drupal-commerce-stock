$ = jQuery;

$('.stock-inventory-control-form').keypress (
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  }
);

$(function(){
  $('input#edit-sku').focus(); // focus to add input upon loading


  $("#edit-sku").bind('keypress', function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if(e.keyCode==13 && ($(this).val() != '')) {
      typingDone();
      calculateTotalQuantity();
    }
  });

  $("#button-add-item").click(function(){
    if ($("#edit-sku").val() != '') {
      typingDone();
      calculateTotalQuantity();
    }
  });

  $("#edit-values").on('click', '.delete-item-button', function() {
    $(this).closest('tr').remove();
    calculateTotalQuantity();
  });

  $( "#edit-values" ).on('change', '.quantity input', function() {
    calculateTotalQuantity();
  });

  function typingDone() {
    $("#edit-sku").blur();
    var SKU = $("#edit-sku").val();
    addProduct(SKU);
    $("#edit-sku").val("");
    $("#edit-sku").focus();
  }

  function addProduct(sku) {
    var p = getItemPosition(sku);
    if(p > -1) {
      var q = parseInt($(".quantity input.form-number").eq(p).val());
      $(".quantity input.form-number").eq(p).val(q+1);
    }
    else {
      var rowCount = $( "#edit-values .sku input").length;
      var preHTML = '<tr>';
      var postHTML = '<td><button class="button btn-danger delete-item-button" type="button">Remove</button></td>';
      $("#edit-values").append(
        preHTML +
        '<td><div class="sku"><div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-values-'+ rowCount +'-sku form-item-values-'+ rowCount +'-sku form-no-label">' +
        '<input readonly="readonly" data-drupal-selector="edit-values-'+ rowCount +'-sku" type="text" id="edit-values-'+ rowCount +'-sku" name="values['+ rowCount +'][sku]" value="' + sku + '" size="60" maxlength="128" class="form-text"></div></div></td>' +
        '<td><div class="quantity"><div class="js-form-item form-item js-form-type-number form-type-number js-form-item-values-'+ rowCount +'-quantity form-item-values-'+ rowCount +'-quantity form-no-label">' +
        '<input data-drupal-selector="edit-values-'+ rowCount +'-quantity" type="number" id="edit-values-'+ rowCount +'-quantity" name="values['+ rowCount +'][quantity]" value="1" step="1" class="form-number required" required="required" aria-required="true"></div></div></td>' +
        postHTML);
    }
  }

  function getItemPosition(sku) {
    var x = -1;
    $( "#edit-values .sku input" ).each(function( index ) {
      if(sku == $(this).val())
        x = index;
    });
    return x;
  }

  function calculateTotalQuantity() {
    var totalItems = 0;
    $( "#edit-values .quantity input" ).each(function( index ) {
      totalItems  += parseInt($(this).val());
    });
    $('.table-transfers-total').html(totalItems);
  }

});
