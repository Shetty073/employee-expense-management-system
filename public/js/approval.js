// Edit an expense
$(document).on('click', '.editExpenseBtn', function () {
    // get the data from clicked row
    let data = [];
    $(this).children('.data').each(function (i) {
        data.push($(this).text().trim());
    });

    let id = data[0];
    let date = `${data[1].slice(6, 10)}-${data[1].slice(3, 5)}-${data[1].slice(0, 2)}`;
    let category = data[2];
    let description = data[4];
    let amount = data[5].replace('₹ ', '');

    let updateUrl = $('#url').val().split('/');
    updateUrl.pop();
    updateUrl = `${updateUrl.join('/')}/updateExpense/${id}`;

    // hide insert form
    $('#insertForm').hide();
    $('#updateForm').show();
    $('#updateForm').prop('action', updateUrl);

    $('#updateDate').val(date);
    $('#updateCategory').val(category);
    $('#updateDescription').val(description);
    $('#updateAmount').val(amount);

});


// send 'apply for approval' request
$(document).on('click', '#applyForApprovalBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#url').val();

    fetch(
        url,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': csrft,
            },
            body: JSON.stringify({
                'voucher_id': voucherId,
            }),
        },
    ).then(function (response) {
        if(response.status === 200) {
            return response.json();
        } else {
            return {
                'process' : 'failed',
            };
        }
        // window.location.reload();
    }).then(function (response) {
        if(response.process === 'success') {
            Swal.fire(
                'Successfully applied for approval!',
                '',
                'Success'
            );
            // request succeeded
            window.location.reload();
        } else {
            // request failed
        }
    });
});
