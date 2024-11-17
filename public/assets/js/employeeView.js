$(document).ready(function () {
    // Remove active class from all sidebar items
    $('.sidebar-item').removeClass('active');

    // Get the current URL path and extract the employee ID
    let pathArray = window.location.pathname.split('/');
    let employeeId = pathArray[pathArray.length - 1];

    // Create a gradient for the attendance chart background
    let ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
    let gradient = ctx.createLinearGradient(0, 0, 0, 225);
    gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
    gradient.addColorStop(1, "rgba(215, 227, 244, 0)");

    // Initialize the attendance chart with default empty data
    let attendanceChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: [], // Empty labels initially
            datasets: [{
                label: "Present",
                fill: true,
                backgroundColor: gradient,
                borderColor: window.theme ? window.theme.primary : 'rgba(0, 123, 255, 1)', // Fallback color
                data: [] // Empty data initially
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: true
            },
            tooltips: {
                intersect: false
            },
            hover: {
                intersect: true
            },
            plugins: {
                filler: {
                    propagate: false
                }
            },
            scales: {
                x: {
                    grid: {
                        color: "rgba(0,0,0,0.0)"
                    }
                },
                y: {
                    ticks: {
                        stepSize: 1
                    },
                    display: true,
                    borderDash: [3, 3],
                    grid: {
                        color: "rgba(0,0,0,0.0)"
                    }
                }
            }
        }
    });

    // Fetch the attendance data from the server
    $.ajax({
        url: `/get-attendance/${employeeId}`,
        method: 'GET',
        success: function (response) {
            // Check if response is valid
            if (response.error) {
                console.error(response.error);
                return;
            }

            // Update attendance chart data
            attendanceChart.data.labels = response.labels; // Update labels
            attendanceChart.data.datasets[0].data = response.present; // Update present data
            // If you want to add 'absent' data as another dataset
            attendanceChart.data.datasets.push({
                label: "Absent",
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                data: response.absent
            });

            attendanceChart.update(); // Re-render the chart with new data
        },
        error: function (xhr) {
            console.error('Error fetching attendance data:', xhr.responseText);
        }
    });

    // Variable to store the salary chart instance
    let salaryChart;

    // Function to fetch and render salary data
    function fetchSalaryData(duration) {
        $.ajax({
            url: '/get-salary-details/' + employeeId,
            type: 'GET',
            data: { duration: duration },
            success: function (data) {
                if (!data.error) {
                    // Destroy existing salary chart instance before rendering new data
                    if (salaryChart) salaryChart.destroy();

                    // Reinitialize the salary chart with the new data
                    salaryChart = new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                            name: 'Base Salary',
                            data: data.basic_salary
                        }, {
                            name: 'Allowances',
                            data: data.allowances
                        }, {
                            name: 'Deductions',
                            data: data.deductions
                        }],
                        chart: {
                            height: 350,
                            type: 'area',
                            toolbar: {
                                show: false
                            }
                        },
                        markers: {
                            size: 4
                        },
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
                        fill: {
                            type: "gradient",
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.3,
                                opacityTo: 0.4,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            categories: data.payment_date.map(date => new Date(date).toLocaleString('default', { month: 'short' })),
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yy'
                            }
                        }
                    });

                    salaryChart.render();
                } else {
                    console.error(data.error);
                }
            },
            error: function (xhr) {
                console.error('Error fetching salary details: ' + xhr.responseText);
            }
        });
    }

    // Set default filter value and fetch initial data
    fetchSalaryData('full_year');

    // Handle dropdown item clicks
    $('#filterDropdown + .dropdown-menu .dropdown-item').on('click', function (e) {
        e.preventDefault();

        // Update the selected filter text
        const selectedText = $(this).text();
        $('#selectedFilter').text(selectedText);

        // Extract duration value
        const duration = $(this).data('duration');

        // Fetch data for the selected duration and reload the salary chart
        fetchSalaryData(duration);
    });

    // Function to fetch leave data
    function fetchLeaveData(id) {
        $.ajax({
            url: '/get-leave-data/' + id,
            method: 'GET',
            success: function (response) {
                let tbody = $('#leave-details-body');
                tbody.empty(); // Clear existing rows

                // Loop through each leave record and append to table
                $.each(response, function (index, leave) {
                    let leaveType = leave.get_leave_type.leave_type_name;
                    let leaveStatus = leave.leave_status.charAt(0).toUpperCase() + leave.leave_status.slice(1); // Capitalize first letter

                    let row = `<tr>
                        <td><span class="badge bg-success">${leaveType}</span></td>
                        <td><span class="text-info">${leaveStatus}</span></td>
                        <td class="d-none d-xl-table-cell">${formatDate(leave.start_date)}</td>
                        <td class="d-none d-xl-table-cell">${leave.leave_days}</td>
                    </tr>`;

                    tbody.append(row);
                });
            },
            error: function (xhr, status, error) {
                console.error("Error", 'An error occurred: ' + error, "error");
            }
        });
    }

    // Function to format date from timestamp
    function formatDate(timestamp) {
        var date = new Date(timestamp);
        var day = String(date.getDate()).padStart(2, '0');
        var month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        var year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Fetch leave data when document is ready
    fetchLeaveData(employeeId);

    $('#salaryFormButton').on('click',function (){
        $.ajax({
            type:"GET",
            url:'/get-current-month-salary',
            data:{'employeeId':employeeId},
            success:function(response){
                $('#basicSalary').val(response.basic_salary);
                $('#allowances').val(response.allowances);
                $('#deduction').val(response.deductions);
                $('#paymentDate').val(response.payment_date ? response.payment_date:"");
            }
        });
    })

    //salary model
    $('#salaryForm').validate({
        rules: {
            basicSalary: {
                required: true,
                number: true,
                min: 0
            },
            allowances: {
                required: true,
                number: true,
                min: 0
            },
            deduction: {
                required: true,
                number: true,
                min: 0
            },
            paymentDate: {
                required: true,
                date: true
            }
        },
        messages: {
            basicSalary: {
                required: "Please enter the basic salary",
                number: "Please enter a valid number",
                min: "Basic salary cannot be negative"
            },
            allowances: {
                required: "Please enter allowances",
                number: "Please enter a valid number",
                min: "Allowances cannot be negative"
            },
            deduction: {
                required: "Please enter the deduction amount",
                number: "Please enter a valid number",
                min: "Deduction cannot be negative"
            },
            paymentDate: {
                required: "Please select a payment date",
                date: "Please enter a valid date"
            }
        },
        errorClass: 'is-invalid',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            // Submit form via AJAX or any other custom functionality
            $.ajax({
                url: '/your-salary-edit-endpoint/'.employeeId,
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    // Handle the successful submission
                    swal("Success", "Salary details updated successfully!", "success");
                },
                error: function(xhr) {
                    // Handle errors
                    swal("Error", "There was an error updating the salary.", "error");
                }
            });
            return false; // Prevent default form submission
        }
    });
});
