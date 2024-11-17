$(document).ready(function () {
    $('.sidebar-item').removeClass('active');
    $('#leave-approval').addClass('active');
    // Attach a click event handler to elements with the ID 'view'
    $(document).on('click', '#view', function () {
        // Get the leave ID from the data-id attribute
        let leaveId = $(this).attr('data-id');

        // Make an AJAX request to fetch leave details
        $.ajax({
            url: '/view-leave/' + leaveId,
            method: 'GET',
            success: function (response) {
                // Check if the response contains error
                if (response.error) {
                    alert(response.error);
                    return;
                }
                console.log(response);

                // Populate the modal with the leave details
                $('#leaveModal #name').text(response.get_employee.user_details.first_name); // Adjust based on actual data structure
                $('#leaveModal #department').text(response.get_employee.department_details.department_name); // Adjust based on actual data structure
                $('#leaveModal #leave-type').text(response.get_leave_type.leave_type_name); // Adjust based on actual data structure
                $('#leaveModal #applied-date').text(new Date(response.applied_date).toLocaleDateString()); // Adjust formatting if needed
                $('#leaveModal #start-date').text(new Date(response.start_date).toLocaleDateString()); // Adjust formatting if needed
                $('#leaveModal #end-date').text(new Date(response.end_date).toLocaleDateString()); // Adjust formatting if needed
                $('#leaveModal #description').text(response.reason); // Adjust based on actual data structure
                $('#approve').attr('data-id', response.id);
                $('#reject').attr('data-id', response.id);
                if(response.leave_status == 'approved'){
                    $('#approve').prop('disabled', true);
                    $('#reject').prop('disabled', true);
                }else{
                    $('#approve').prop('disabled', false);
                    $('#reject').prop('disabled', false);
                }
                // Show the modal
                $('#leaveModal').modal('show');
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Failed to fetch leave details.');
            }
        });
    });


    // Handle approve button click
    $('#approve').click(function () {
        handleLeaveStatus('approve');
    });

    // Handle reject button click
    $('#reject').click(function () {
        handleLeaveStatus('reject');
    });

    function handleLeaveStatus(status) {
        leaveId = $('#approve').attr('data-id');
        Swal.fire({
            title: `Are you sure you want to ${status} this leave?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'No, cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/leave-status/${leaveId}?status=${status}`,
                    method: 'GET',
                    success: function (response) {
                        Swal.fire('Success!', response.message, 'success');
                        $('#leaveModal').modal('hide'); // Hide the modal
                        location.reload(); // Example: reload the page
                    },
                    error: function (xhr, status, error) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Failed to update leave status.';
                        Swal.fire('Error!', errorMsg, 'error');
                    }
                });
            }
        });
    }
});