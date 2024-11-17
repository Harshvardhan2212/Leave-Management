$(document).ready(function() {
    $('#logout').on('click', function(e) {
        e.preventDefault(); // Prevent the default action

        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, proceed with logout
                window.location.href = "/logout";
            }
        });
    });
});