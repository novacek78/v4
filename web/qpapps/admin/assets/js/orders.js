
// defaultne radenie datatables - podla cisla objednavky DESC
$(document).ready(function() {
    $('#ordersTable').DataTable( {
        "autoWidth": true,
        "order": [[ 1, "desc" ]],
        "pageLength": 25
    } );
} );
