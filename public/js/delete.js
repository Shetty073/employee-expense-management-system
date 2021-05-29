
$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();

    let url = $(this).prop('href');
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
        text: "You can revert this anytime!",
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
                        'Entry deleted successfully!',
                        'The selected data has been deleted successfully.',
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
                'Selected entry is safe',
                'The selected data is safe.',
                'info'
            );
        }
    });
});
