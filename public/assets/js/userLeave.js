$(document).ready(function () {
    $('.sidebar-item').removeClass('active');
    $('#leaves').addClass('active');
});
$(document).ready(function () {
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

    // Function to fetch leave data
    function fetchLeaveData(id) {
        $.ajax({
            url: '/get-leave-data/' + id,
            method: 'GET',
            success: function (response) {
                var tbody = $('#leave-details-body');
                tbody.empty(); // Clear existing rows

                // Loop through each leave record and append to table
                $.each(response, function (index, leave) {
                    var leaveType = leave.get_leave_type.leave_type_name;
                    var leaveStatus = leave.leave_status.charAt(0).toUpperCase() + leave.leave_status.slice(1); // Capitalize first letter

                    var row = `<tr>
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
});