$(document).ready(function(){
    $('.sidebar-item').removeClass('active');
    $('#salary').addClass('active');   

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
                            categories: data.payment_date.map(date => {
                                const dateObj = new Date(date);
                                return dateObj.toLocaleString('default', { month: 'short', year: 'numeric' });
                            }),
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
      
      $('#payslip').on('click',function(){
        $.ajax({
            type:"GET",
            data:{
                'employeeId':employeeId
            },
            url:'/get-payslip-data',
            success:function(response){
                $('.name').text(`${response.employee.user_details.first_name} ${response.employee.user_details.last_name}`);
                $('#name').text(`${response.employee.user_details.first_name} ${response.employee.user_details.last_name}`);
                $('#department').text(response.employee.department_details.department_name);
                $('#designation').text(response.employee.designation);
                $('#basicSalary').text(`${response.basic_salary}`);
                $('#allowances').text(`${response.allowances}`);
                $('#deductions').text(`${response.deductions}`);
                $('#netSalary').text(`${response.net_salary}`);
                $('#paymentDate').text(`${response.payment_date ? response.payment_date : ""}`);
                $('#paymentStatus').text(`${response.payment_status}`);
            }
        });
      });
      $('#download').on('click', function() {
        // AJAX request to get the PDF URL
        $.ajax({
            url: '/download-payslip',
            method: 'GET',
            data:{'employeeId':employeeId},
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data, status, xhr) {
                // Create a URL for the blob data
                var blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
                var url = window.URL.createObjectURL(blob);

                // Create a link element
                var a = document.createElement('a');
                a.href = url;
                a.download = 'payslip.pdf'; // File name
                document.body.appendChild(a);
                a.click(); // Trigger the download
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url); // Clean up
            },
            // error: function(xhr, status, error) {
            //     alert(error);
            // }
        });
    });
});

 