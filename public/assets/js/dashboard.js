
$(document).ready(function () {
    $('.sidebar-item').removeClass('active');
    $('#dashboard').addClass('active');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize validation on the holidayForm
    $("#holidayForm").validate({
        errorClass: "invalid-feedback",
        validClass: "valid-feedback",
        errorElement: "div",
        highlight: function (element, errorClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") === "radio" || element.prop("type") === "checkbox") {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            holidayName: "required",
            holidayDate: "required",
            holidayDescription: "required"
        },
        messages: {
            holidayName: "Please enter the holiday name.",
            holidayDate: "Please select the holiday date.",
            holidayDescription: "Please provide a brief description."
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);  // Place error after the input field
        }
    });

    // Pie chart attendance
    new Chart(document.getElementById("chartjs-dashboard-pie"), {
        type: "pie",
        data: {
            labels: ["adsent", "present"],
            datasets: [{
                data: [todayPresentCount, absentCount],
                backgroundColor: [
                    window.theme.danger,
                    window.theme.primary
                ],
                borderWidth: 5
            }]
        },
        options: {
            responsive: !window.MSInputMethodContext,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            cutoutPercentage: 75
        }
    });

    // Pie chart salary
    new Chart(document.getElementById("salary-chart"), {
        type: "pie",
        data: {
            labels: ["paid", "pending"],
            datasets: [{
                data: [salaryCount.paid_count, salaryCount.pending_count],
                backgroundColor: [
                    window.theme.success,
                    window.theme.warning
                ],
                borderWidth: 5
            }]
        },
        options: {
            responsive: !window.MSInputMethodContext,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            cutoutPercentage: 75
        }
    });

    const holidayDates = holidays.map(date => new Date(date));

    function getWeekends(year) {
        const weekends = [];
        let date = new Date(year, 0, 1); 

        while (date.getFullYear() === year) {
            if (date.getDay() === 0 || date.getDay() === 6) { // Sunday or Saturday
                weekends.push(new Date(date));
            }
            date.setDate(date.getDate() + 1);
        }

        return weekends;
    }
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    const currentYear = new Date().getFullYear();
    const weekendDates = getWeekends(currentYear);

    const fp = document.getElementById("datetimepicker-dashboard").flatpickr({
        inline: true,
        prevArrow: "<span title=\"Previous month\">&laquo;</span>",
        nextArrow: "<span title=\"Next month\">&raquo;</span>",
        defaultDate: new Date(),
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dateStr = formatDate(dayElem.dateObj);

            // Check if the day is a holiday or a weekend
            const isHoliday = holidayDates.some(holiday => formatDate(holiday) === dateStr);
            const isWeekend = weekendDates.some(weekend => formatDate(weekend) === dateStr);

            // Apply custom styling for holidays or weekends
            if (isHoliday || isWeekend) {
                dayElem.style.color = 'red';
            }
        }
    });

    const today = new Date();
    fp.setDate(today, true);


    $('#holidayForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '/create-holiday',
            data: formData,
            processData: false, 
            contentType: false,
            success: function (data) {
              
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $('#holidayForm')[0].reset();

                        window.location.reload();
                    }
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred: ' + xhr.responseText, 
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    let date = new Date();
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0'); 
    let year = date.getFullYear();
    let minDate = year + '-' + month + '-' + day;
    $('#holidayDate').attr('min', minDate);
});