$(document).ready(function() {
    $('.sidebar-item').removeClass('active');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Initialize form validation
    $('#changePasswordForm').validate({
        rules: {
            currentPassword: {
                required: true,
                minlength: 6
            },
            newPassword: {
                required: true,
                minlength: 6
            },
            confirmPassword: {
                required: true,
                minlength: 6,
                equalTo: "#newPassword" // Ensures confirmPassword matches newPassword
            }
        },
        messages: {
            currentPassword: {
                required: "Please enter your current password",
                minlength: "Password must be at least 6 characters long"
            },
            newPassword: {
                required: "Please enter a new password",
                minlength: "Password must be at least 6 characters long"
            },
            confirmPassword: {
                required: "Please confirm your new password",
                minlength: "Password must be at least 6 characters long",
                equalTo: "Passwords do not match"
            }
        },
        submitHandler: function(form) {
            // Perform AJAX request to change password
            $.ajax({
                url: '/change-password', // Adjust this to your route
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    // Handle success response
                    if(response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Changed',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message // Display error message from server
                    });
                }
            });
        }
    });
     // Initialize the validation
     $("#profileForm").validate({
        errorClass: 'is-invalid',
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            joiningDate: {
                required: true,
                date: true
            },
            phone_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            department: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
                minlength: "First name must be at least 2 characters long"
            },
            last_name: {
                required: "Please enter your last name",
                minlength: "Last name must be at least 2 characters long"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            joiningDate: {
                required: "Please select your joining date"
            },
            phone_number: {
                required: "Please enter your phone number",
                digits: "Please enter only digits",
                minlength: "Phone number must be at least 10 digits",
                maxlength: "Phone number cannot exceed 15 digits"
            },
            department: {
                required: "Please select your department"
            }
        },
        submitHandler: function (form) {
            // AJAX request to submit the form
            $.ajax({
                url: '/update-profile',
                type: 'POST',
                data: $(form).serialize(),
                success: function (response) {
                    // Handle success response
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Changed',
                        text: response.message
                    });
                },
                error: function (xhr) {
                    // Handle error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message // Display error message from server
                    });
                }
            });
        }
    });
    let $imageUpload = $('#imageUpload');
    let $imagePreview = $('#imagePreview');
    let cropper;

    // Handle image upload
    $imageUpload.on('change', function(e) {
        let files = e.target.files;

        if (files && files.length > 0) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let url = reader.result;
                $imagePreview.attr('src', url).removeClass('d-none');

                // Initialize or reinitialize Cropper.js
                if (cropper) {
                    cropper.destroy(); // Destroy the old cropper instance if it exists
                }
                cropper = new Cropper($imagePreview[0], {
                    aspectRatio: 1, // Set aspect ratio for square crop
                    viewMode: 1
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // Save cropped image
    $('#saveCroppedImage').on('click', function() {
        if (cropper) {
            let canvas = cropper.getCroppedCanvas({
                width: 500,  // Increase the cropped image width (e.g., 300px)
                height: 800  // Increase the cropped image height (e.g., 300px)
            });

            // Convert the canvas to a Blob
            canvas.toBlob(function(blob) {
                let formData = new FormData();
                formData.append('croppedImage', blob);

                // Add CSRF token to the form data
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Send the cropped image to the server via AJAX
                $.ajax({
                    url: '/upload-profile-image', // Your image upload endpoint
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success (update profile image, etc.)
                        console.log('Upload successful:', response);
                        location.reload(); // Reload the page to reflect the new profile image
                    },
                    error: function(error) {
                        // Handle error
                        console.error('Upload failed:', error);
                    }
                });
            });
        }
    });
    
});
