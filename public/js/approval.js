let selectedExpenseId;
// Edit an expense
$(document).on('click', '.editExpenseBtn', function (e) {
    let data = [];
    $(this).children('.data').each(function (i) {
        data.push($(this).text().trim());
    });
    let id = data[0];
    selectedExpenseId = id;

    // check if bills download cell was clicked
    if ($(e.target).closest('td').is(':nth-child(7)')) {
        // download bills
        let urls = [];
        $(`.billurl.${selectedExpenseId}`).each(function () {
            urls.push($(this).text().trim());
        });
        downloadBills(urls);

        return;
    }

    // edit the row
    // get the data from clicked row
    let date = `${data[1].slice(6, 10)}-${data[1].slice(3, 5)}-${data[1].slice(0, 2)}`;
    let category = data[2];
    let amount = data[4].replace('₹ ', '');
    let description = data[5];

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

// delete an expense
$(document).on('click', '#deleteExpenseBtn', function () {
    let url = $('#deleteExpenseUrl').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');

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
        confirmButtonText: 'Yes, delete!',
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
                        'expense_id': selectedExpenseId,
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
                        'Expense deleted successfully!',
                        'The selected expense was deleted.',
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
                    );
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Expense not deleted',
                'Your expense was saved and was not deleted.',
                'info'
            );
        }
    });
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
        cancelButtonText: 'Save draft!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the four additional files form
            $('#extraFilesForm').submit();

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
                    );
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Draft saved!',
                'Voucher has been saved! You can request for approval again at any time.',
                'info'
            );
        }
    });
});
