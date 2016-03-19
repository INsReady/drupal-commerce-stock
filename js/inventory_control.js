$ = jQuery;

$(function(){
  $('label.tree-toggler').click(function () {
    $(this).parent().children('ul.tree').toggle(300);
  });

  $('input#input-barcode').focus(); // focus to add input upon loading


  $("#input-barcode").bind('keypress', function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if(e.keyCode==13) {
      typingDone();
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
    $("#input-barcode").blur();
    var SKU = $("#input-barcode").val();
    addProduct(SKU);
    $("#input-barcode").val("");
    $("#input-barcode").focus();
  }

  function addProduct(sku) {
    var p = getItemPosition(sku);
    if(p > -1) {
      var q = parseInt($( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val());
      $( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val(q+1);
    }
    else {
      var preHTML = '<tr>';
      var postHTML = '<td><button class="btn btn-mini btn-danger delete-item-button" type="button"><i class="icon-remove-sign icon-white"></i> Delete</button></td>';
      $("#item-table").append(
        preHTML +
        '<td class="sku">' + sku + '</th>' +
        '<td class="quantity"><input type="number" class="span12" value="' + '1' + '" /></th>' +
        postHTML);
    }
  }

  function getItemPosition(sku) {
    var x = -1;
    $( "#item-table .sku" ).each(function( index ) {
      if(sku == $(this).text())
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

  $('button.submit-form').click(function() {
    var data = [];
    var row;
    $('table#item-table tbody tr').each(function(){
      row = [];
      row.push($(this).children('td.sku').text());
      row.push($(this).children('td.quantity').children('input').val());
      data.push(row);
    });
    var url = $(this).attr('id') === "in" ? "add" : "subtract";
    var $form = $("form#form-move-inventory");
    $form.attr('action','http://ics.feinesh.com/transfers/fill/1');
    for(var i = 0; i < data.length; i++)
    {
      $form.append("<input type='hidden' name='values["+i+"]' value='"+data[i][0]+","+data[i][1]+"'/>");
      $form.append($("#description"));
    }
    $form.submit();
  });

});
