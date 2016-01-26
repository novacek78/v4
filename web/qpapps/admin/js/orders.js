
// defaultne radenie datatables - podla cisla objednavky DESC
$(document).ready(function() {
    $('#ordersTable').DataTable( {
        "order": [[ 1, "desc" ]]
    } );
} );
