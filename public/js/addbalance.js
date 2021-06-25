
let addBalanceForm = document.querySelector('#addBalanceForm');
let addBalanceBtn = document.querySelector('#addBalanceBtn');

addBalanceBtn.addEventListener('click', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, add it!'
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            addBalanceForm.submit();
        } else {
            Swal.fire('Balance not added', '', 'info')
        }
    });

});
