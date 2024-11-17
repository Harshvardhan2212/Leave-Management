$(document).ready(function () {
    $('.sidebar-item').removeClass('active');
    // Initialize form validation on the leave form
    $("#create_leave").validate({
        rules: {
            leave_type: {
                required: true
            },
            start_date: {
                required: true,
                date: true
            },
            end_date: {
                required: true,
                date: true
            },
            reason: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            leave_type: {
                required: "Please select a leave type."
            },
            start_date: {
                required: "Please select a start date.",
                date: "Please enter a valid date."
            },
            end_date: {
                required: "Please select an end date.",
                date: "Please enter a valid date."
            },
            reason: {
                required: "Please provide a reason for the leave.",
                minlength: "Your reason must be at least 10 characters long."
            }
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            error.addClass('text-danger');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
