$ = jQuery;

$('.stock-inventory-control-form').keypress (
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  }
);

$(function(){
  $('label.tree-toggler').click(function () {
    $(this).parent().children('ul.tree').toggle(300);
  });

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
    if(p > -1) {
      var q = parseInt($( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val());
      $( "#item-table .sku" ).siblings(".quantity").children('input').eq(p).val(q+1);
    }
    else {
      var preHTML = '<tr>';
      var postHTML = '<td><button class="button btn-danger delete-item-button" type="button"><i class="icon-remove-sign icon-white"></i> Remove</button></td>';
      $("#item-table").append(
        preHTML +
        '<td class="sku">' + '<input type="text" class="form-text required" value="' + sku + '" READONLY />' + '</th>' +
        '<td class="quantity"><input type="number" class="form-number required" value="' + '1' + '" /></th>' +
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
