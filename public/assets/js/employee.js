function getDepartment(employeeData = null) {
    $.ajax({
        type: 'GET',
        url: 'fetch-department',
        success: function (data) {
            if (data) {
                $('#department').empty();
                $('#department').append(`<option val="" style="display:none">select</option>`);
                data.forEach(element => {
                    if (employeeData && employeeData.department_details.id == element.id) {
                        $('#department').append(
                            `<option value=${element.id} selected>${element.department_name}</option>`
                        );
                    } else {
                        $('#department').append(
                            `<option value=${element.id}>${element.department_name}</option>`
                        );
                    }

                });
            }
        }
    });
}
$(document).ready(function() {
    $('.sidebar-item').removeClass('active');
    $('#employee').addClass('active');  
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#editEmployeeForm').validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            phone_number: {
                required: true,
                digits: true
            },
            department: {
                required: true,
            },
            designation: {
                required: true,
            },
            joining_date: {
                required: true,
            },
            current_salary: {
                required: true,
                number: true,
                min: 0
            },
            password: {
                minlength: 6
            }
        },
        messages: {
            first_name: "First name is required.",
            last_name: "Last name is required.",
            email: {
                required: "A valid email address is required.",
                email: "Please enter a valid email address."
            },
            phone_number: "Phone number is required.",
            department: "Department is required.",
            designation: "Designation is required.",
            joining_date: "Joining date is required.",
            current_salary: {
                required: "Current salary is required.",
                number: "Please enter a valid number.",
                min: "Salary must be a positive number."
            },
            password: {
                minlength: "Password should be at least 6 characters long."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element); 
        }
    });

    $('#editEmployeeModal').on('hidden.bs.modal', function () {
        let form = $('#editEmployeeForm');
        form.validate().resetForm(); // Reset validation messages
        form[0].reset(); // Reset form data
        form.find('.form-control').removeClass('is-invalid'); 
        $("input,select").removeClass('error');// Remove validation classes
    });

    $('#editEmployeeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var mode = button.data('mode'); // Extract info from data-* attributes

        setupModal(mode);

        if (mode === 'add') {
            $('#editEmployeeForm').trigger('reset');
            $('#editEmployeeForm').find('.form-control').removeClass('is-invalid'); // Ensure classes are removed
            $('#_method').val('post');
        }
    });

    // Setup modal function
    function setupModal(mode) {
        var modalTitle = $('#editEmployeeModalLabel');
        var saveButton = $('#editEmployeeForm button[type="submit"]');
        var passwordField = $('#inputPassword');

        if (mode === 'edit') {
            modalTitle.text('Edit Employee');
            saveButton.text('Save Changes');
            passwordField.hide(); // Hide password field in edit mode
        } else if (mode === 'add') {
            modalTitle.text('Add New Employee');
            saveButton.text('Add Employee');
            passwordField.show(); // Show password field in add mode
        }
    }

    // Handle form submission
    $('#editEmployeeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        if ($(this).valid()) {
            let url = ($('#_method').val() == 'post') ? '/employee' : `/employee/${$('#id').val()}`;

            $.ajax({
                url: url,
                method: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'The form has been submitted successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#editEmployeeModal').modal('hide'); // Hide modal after success
                        location.reload(); // Reload the page
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });

    // Trigger the modal for "add" mode
    $('#addEmployeeButton').on('click', function() {
        $('#editEmployeeForm').trigger('reset');
        $('#editEmployeeForm').find('.form-control').removeClass('is-invalid');
        setupModal('add');
        getDepartment();
        $('#editEmployeeModal').modal('show');
    });

    // Trigger the modal for "edit" mode
    $(document).on('click', '.edit-icon', function() {
        $('#editEmployeeForm').trigger('reset');
        $('#editEmployeeForm').find('.form-control').removeClass('is-invalid');

        let employeeId = $(this).data('id');

        // Load employee data into the form
        $.ajax({
            url: '/employee/' + employeeId + '/edit',
            method: 'GET',
            success: function(data) {
                $('#_method').val('put');
                $('#id').val(data.id);

                $('#editEmployeeForm').find('input[name="first_name"]').val(data.user_details.first_name);
                $('#editEmployeeForm').find('input[name="last_name"]').val(data.user_details.last_name);
                $('#editEmployeeForm').find('input[name="email"]').val(data.user_details.email);
                $('#editEmployeeForm').find('input[name="phone_number"]').val(data.user_details.phone_number);
                $('#editEmployeeForm').find('select[name="department"]').val(data.department_id);
                $('#editEmployeeForm').find('input[name="designation"]').val(data.designation);
                $('#editEmployeeForm').find('input[name="joining_date"]').val(data.joining_date);
                $('#editEmployeeForm').find('input[name="current_salary"]').val(data.current_salary);
                $('#editEmployeeForm').find('input[name="image"]').val(''); // Reset file input
                $('#editEmployeeForm').find('input[name="password"]').val(''); // Reset password input
                $('#editEmployeeForm').attr('action', '/employee/' + employeeId); // Set form action URL
                getDepartment(data);
                $('#editEmployeeModal').modal('show');
                setupModal('edit');
            }
        });
    });

    $(document).on('click', '#delete', function() {
        var employeeId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/employee/' + employeeId,
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'The employee record has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page or remove the row from the table
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the record. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
