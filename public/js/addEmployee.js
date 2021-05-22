
$(document).on('input', '#wallet_balance', function () {
    let amount = $(this).val();
    if(parseFloat(amount) > 0) {
        $('#paymentModeDiv').prop('hidden', false);
        $('#dateDiv').prop('hidden', false);
        $('#remarkDiv').prop('hidden', false);
    } else {
        $('#paymentModeDiv').prop('hidden', true);
        $('#dateDiv').prop('hidden', true);
        $('#remarkDiv').prop('hidden', true);
    }
});
