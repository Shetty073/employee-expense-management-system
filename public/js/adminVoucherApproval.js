// download all bills of a particular expense
$(document).on('click', '.downloadExpenseBillsBtn', function () {
    let id = $(this).prop('id');
    let urls = [];
    $(`.billurl.${id}`).each(function () {
        urls.push($(this).text().trim());
    });

    downloadBills(urls)
});

// download all bills of the voucher at once
$(document).on('click', '#downloadAllBillsBtn', function () {
    let urls = [];
    $('.billurl').each(function () {
        urls.push($(this).text().trim());
    });
    downloadBills(urls);
});

// download extra files attached at bottom
$(document).on('click', '#siteCompletionDocBtn', function () {
    let urls = [];
    $('.site_completion_doc_url').each(function () {
        urls.push($(this).text().trim());
    });
    downloadBills(urls);
});

$(document).on('click', '#receivedDocsBtn', function () {
    let urls = [];
    $('.received_doc_url').each(function () {
        urls.push($(this).text().trim());
    });
    downloadBills(urls);
});

$(document).on('click', '#returnableListBtn', function () {
    let urls = [];
    $('.returnable_list_doc_url').each(function () {
        urls.push($(this).text().trim());
    });
    downloadBills(urls);
});

$(document).on('click', '#submittedDocsBtn', function () {
    let urls = [];
    $('.submitted_doc_url').each(function () {
        urls.push($(this).text().trim());
    });
    downloadBills(urls);
});

// total amount logic
$(document).on('input', '.expenseamount', function () {
    let total = 0;
    $('.expenseamount').each(function () {
        total += parseFloat($(this).val());
    });

    $('#totalAmountPaid').val(total);
});

// add more bills to expense
$(document).on('click', '.addExpenseBillsBtn', function () {
    let expenseId = $(this).prop('id');
    $(document).on('change', `#billupload${expenseId}`, function () {
        $(`#addExpenseBillsForm${expenseId}`).submit();
    });
    $(`#billupload${expenseId}`).click();

});

// save this voucher as draft with approved amounts etc.
$(document).on('click', '#saveVoucherDraftBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#voucherSaveDraftUrl').val();
    let remark = $('#remark').val();
    let special_remark = $('#special_remark').val();
    let amount = $('#totalAmountPaid').val();
    let payment_mode = $('#paymentMode').val();
    let date = $('#date').val();
    let payment_remark = $('#remark').val();

    let expense_remarks = {};
    let expense_amounts = {};

    $('.expenseremark').each(function () {
        let expenseRemark = $(this).val();
        let expenseRemarkId = $(this).prop('id');

        if(expenseRemark.trim() !== '') {
            expense_remarks[expenseRemarkId] = expenseRemark;
        }
    });

    $('.expenseamount').each(function () {
        let expenseAmount = $(this).val();
        let expenseAmountId = $(this).prop('id');
        expense_amounts[expenseAmountId] = expenseAmount;
    });


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
        confirmButtonText: 'Yes, save it!',
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
                        'amount': amount,
                        'date': date,
                        'payment_mode': payment_mode,
                        'payment_remark': payment_remark,
                        'remark': remark,
                        'special_remark': special_remark,
                        'expense_remarks': expense_remarks,
                        'expense_amounts': expense_amounts,
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
                        'Saved!',
                        'This draft has been saved.',
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
                'No action taken',
                'The changes are not saved.',
                'info'
            );
        }
    });
});

// approve this voucher
$(document).on('click', '#approveVoucherBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#url').val();
    let date = $('#date').val();
    let payment_mode = $('#paymentMode').val();
    let amount = $('#totalAmountPaid').val();
    let remark = $('#remark').val();
    let special_remark = $('#special_remark').val();

    let expense_remarks = {};
    let expense_amounts = {};

    $('.expenseremark').each(function () {
        let expenseRemark = $(this).val();
        let expenseRemarkId = $(this).prop('id');

        if(expenseRemark.trim() !== '') {
            expense_remarks[expenseRemarkId] = expenseRemark;
        }
    });

    $('.expenseamount').each(function () {
        let expenseAmount = $(this).val();
        let expenseAmountId = $(this).prop('id');
        expense_amounts[expenseAmountId] = expenseAmount;
    });


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
        confirmButtonText: 'Yes, approve it!',
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
                        'status': 2,
                        'date': date,
                        'payment_mode': payment_mode,
                        'amount': amount,
                        'remark': remark,
                        'special_remark': special_remark,
                        'expense_remarks': expense_remarks,
                        'expense_amounts': expense_amounts,
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
                        'Approved!',
                        'This voucher has been approved.',
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
                'No action taken',
                'This voucher is neither approved nor rejected',
                'info'
            );
        }
    });
});

// reject this voucher
$(document).on('click', '#rejectVoucherBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#url').val();
    let special_remark = $('#special_remark').val();

    let expense_remarks = {};
    let expense_amounts = {};

    $('.expenseremark').each(function () {
        let expenseRemark = $(this).val();
        let expenseRemarkId = $(this).prop('id');

        if(expenseRemark.trim() !== '') {
            expense_remarks[expenseRemarkId] = expenseRemark;
        }
    });

    $('.expenseamount').each(function () {
        let expenseAmount = $(this).val();
        let expenseAmountId = $(this).prop('id');
        expense_amounts[expenseAmountId] = expenseAmount;
    });


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
        confirmButtonText: 'Yes, reject it!',
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
                        'status': 3,
                        'special_remark': special_remark,
                        'expense_remarks': expense_remarks,
                        'expense_amounts': expense_amounts,
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
                        'Rejected!',
                        'This voucher has been rejected.',
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
                'No action taken',
                'This voucher is neither approved nor rejected',
                'info'
            );
        }
    });
});
