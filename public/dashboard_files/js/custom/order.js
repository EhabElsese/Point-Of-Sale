$(document).ready(function () {

    //add product btn
    $('.add-product-btn').on('click', function (e) {

        e.preventDefault();
        var name = $(this).data('name');
        var id = $(this).data('id');
        var price = $.number($(this).data('price'), 2);

        $(this).removeClass('btn-success').addClass('btn-default disabled');

        var html =
            `<tr>
                <td>${name}</td>
                <td><input type="number" name="products[${id}][quantity]" data-price="${price}" class="form-control input-sm product-quantity" min="1" value="1"></td>
                <td class="product-price">${price}</td>
                <td><button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}"><span class="fa fa-trash"></span></button></td>
            </tr>`;

        $('.order-list').append(html);

        //to calculate total price
        calculateTotal();
    });

    //disabled btn
    $('body').on('click', '.disabled', function(e) {

        e.preventDefault();

    });//end of disabled

    //remove product btn
    $('body').on('click', '.remove-product-btn', function(e) {

        e.preventDefault();
        var id = $(this).data('id');

        $(this).closest('tr').remove();
        $('#product-' + id).removeClass('btn-default disabled').addClass('btn-success');

        //to calculate total price
        calculateTotal();

    });//end of remove product btn

    //change product quantity
    $('body').on('keyup change', '.product-quantity', function() {

        var quantity = Number($(this).val()); //2
        var unitPrice = parseFloat($(this).data('price').replace(/,/g, '')); //150
        console.log(unitPrice);
        $(this).closest('tr').find('.product-price').html($.number(quantity * unitPrice, 2));
        calculateTotal();

    });//end of product quantity change

    //list all order products
    $('.order-products').on('click', function(e) {

        e.preventDefault();

        $('#loading').css('display', 'flex');

        var url = $(this).data('url');
        var method = $(this).data('method');
        $.ajax({
            url: url,
            method: method,
            success: function(data) {

                $('#loading').css('display', 'none');
                $('#order-product-list').empty();
                $('#order-product-list').append(data);

            }
        })

    });//end of order products click

    //print order
    $(document).on('click', '.print-btn', function() {

        $('#print-area').printThis();

    });//end of click function

    // order status
    $(document).on('click', '.order-status-btn', function(e) {
        e.preventDefault();


            var that = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: that.data('url'),
            type: "post",
            data: {

                id : that.data('id')
            },

            success: function(response) {

                new Noty({
                    type: 'success',
                    layout: 'topRight',
                    text :response.trans,
                    timeout: 2000,
                    killer: true
                }).show();

                that.removeClass('btn-warning ').addClass('btn-success').prop('disabled',true).text(response.trans);

            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('Error:', errorThrown);
                console.log('Status:', textStatus);
                console.log('XHR:', xhr);
            }
        });
    }); // end of function order status

});//end of document ready

//calculate the total
function calculateTotal() {

    var price = 0;

    $('.order-list .product-price').each(function(index) {

        price += parseFloat($(this).html().replace(/,/g, ''));

    });//end of product price

    $('.total-price').html($.number(price, 2));

    //check if price > 0
    if (price > 0) {

        $('#add-order-form-btn').removeClass('disabled')

    } else {

        $('#add-order-form-btn').addClass('disabled')

    }//end of else

}//end of calculate total

// export to excel sheet
function exportToExcel() {
    // Get the div content
    var divContent = document.getElementById("div-to-export").innerHTML;
  
    // Convert the div content to an Excel sheet using SheetJS
    var workbook = XLSX.utils.table_to_book(document.getElementById("table-to-export"));
  
    // Download the Excel sheet as a file
    XLSX.writeFile(workbook, 'exported-data.xlsx');
  }
// change Order Status
/* // end or change status
*/
