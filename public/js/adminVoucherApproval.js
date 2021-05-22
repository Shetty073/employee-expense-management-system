$(document).on('click', '#approveVoucherBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#url').val();
    let date = $('#date').val();
    let payment_mode = $('#paymentMode').val();
    let amount = $('#totalAmountPaid').val();
    let remark = $('#remark').val();

    // approve this voucher
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
                    )
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'This voucher is neither approved nor rejected',
                'error'
            )
        }
    });
});

$(document).on('click', '#rejectVoucherBtn', function () {
    let voucherId = $('#voucherId').val();
    let csrft = $('meta[name="csrf-token"]').attr('content');
    let url = $('#url').val();

    // reject this voucher
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
                    )
                }
            });

        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'This voucher is neither approved nor rejected',
                'error'
            )
        }
    });
});
