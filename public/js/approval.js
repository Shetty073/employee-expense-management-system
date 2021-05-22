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

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger mr-2',
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, apply!',
        cancelButtonText: 'Cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // perform fetch API call here
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
                    // request succeeded
                    swalWithBootstrapButtons.fire(
                        'Requested for approval!',
                        'This voucher is now waiting for approval from admin.',
                        'success'
                    ).then(function (result) {
                        window.location.reload();
                    })
                } else {
                    // request failed
                    swalWithBootstrapButtons.fire(
                        'Failed!',
                        'Request was not unsuccessful! Please contact the system administrator.',
                        'error'
                    )
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'You can request for approval again at any time.',
                'error'
            )
        }
    });
});
