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
    if(e.keyCode==13) {
      typingDone();
      calculateTotalQuantity();
    }
  });

  $("#button-add-item").click(function(){
    typingDone();
    calculateTotalQuantity();
  });

  $("#item-table").on('click', '.delete-item-button', function() {
    $(this).closest('tr').remove();
    calculateTotalQuantity();
  });

  $( "#item-table" ).on('change', '.quantity input', function() {
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
    console.log('Pos:' + p);
    if(p > -1) {
      var q = parseInt($( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val());
      $( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val(q+1);
    }
    else {
      var rowCount = $( "#item-table .sku input").length;
      var preHTML = '<tr>';
      var postHTML = '<td><button class="button btn-danger delete-item-button" type="button">Remove</button></td>';
      $("#item-table").append(
        preHTML +
        '<td class="sku">' + '<input type="text" class="form-text required" name="sku[' + rowCount + ']" value="' + sku + '" READONLY />' + '</th>' +
        '<td class="quantity"><input type="number" class="form-number required" name="qty['+ rowCount + ']" " value="' + '1' + '" /></th>' +
        postHTML);
    }
  }

  function getItemPosition(sku) {
    var x = -1;
    $( "#item-table .sku input" ).each(function( index ) {
      if(sku == $(this).val())
        x = index;
    });
    return x;
  }

  function calculateTotalQuantity() {
    var totalItems = 0;
    $( "#item-table .quantity input" ).each(function( index ) {
      totalItems  += parseInt($(this).val());
    });
    $('.table-transfers-total').html(totalItems);
  }

});
