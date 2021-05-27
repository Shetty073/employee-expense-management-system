// filter the table rows based on input
$("#searchBox").on("keyup", function () {
    let term = $(this).val().toLowerCase();
    $("table tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(term) > -1);
    });
});
