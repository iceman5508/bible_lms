 /*---------------------------
 DataTable page
 ---------------------------*/
 //customers table
 $('#customers-table').DataTable({
    "paging": false,
    "info": false,
    //searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});

//project list table
$('#project-list-table').DataTable({
    "paging": false,
    "info": false,
    //searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});


//project list table
$('#package-list-table').DataTable({
    "paging": false,
    "info": false,
    //searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});

//promotion list table
$('#promotion-courses-table').DataTable({
    //"paging": false,
    "info": false,
    //searching: false,
    lengthMenu: [
        [5, 10, 50, -1],
        [10, 25, 50, 'All'],
    ],
    language: {
        searchPlaceholder: "Type..."
    }
});

//filter table
$('#filter-table').DataTable({
    "paging": false,
    "info": false,
    searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});

//filter table
$('#all-request-table').DataTable({
    "paging": true,
    "info": true,
    searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});
//filter table
$('#pending-request-table').DataTable({
    "paging": true,
    "info": true,
    searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});
//filter table
$('#reject-request-table').DataTable({
    "paging": true,
    "info": true,
    searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});//filter table
$('#approved-request-table').DataTable({
    "paging": true,
    "info": true,
    searching: false,
    language: {
        searchPlaceholder: "Type..."
    }
});
