<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('assets/icons/icon-48x48.png') }}" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>Leave Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: none;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-body h6 {
            font-size: 1rem;
            margin-bottom: 0.75rem;
            color: #495057;
        }

        .modal-body p {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: #6c757d;
        }

        .modal-footer {
            border-top: none;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        @include('component.sidebar')

        <div class="main">

            @include('component.navbar')

            <main class="content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0"><strong>Salary</strong> Details</h1>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#payslipModal" id="payslip">
                        generate payslip
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">Monthly Leaves</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                Taken : {{$leaveCount ?? 0}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">This Month's Deduction</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                $ {{$salary->deductions ?? 0}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">This Month's Allowance</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                $ {{$salary->allowances ?? 0}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-9">
                        {{-- Salary Chart --}}
                        <div class="card flex-fill w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Salary Chart</h5>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-150" type="button"
                                        id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="selectedFilter">Full Year</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end mt-5"
                                        aria-labelledby="filterDropdown">
                                        <li><a class="dropdown-item" href="#"
                                                data-duration="last_6_months">Last 6 Months</a></li>
                                        <li><a class="dropdown-item" href="#"
                                                data-duration="full_year">Full Year</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                <div class="chart chart-sm">
                                    <div id="reportsChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            @include('component.footer')
        </div>
    </div>

    <!-- Payslip Modal -->
    <div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-0 rounded-top">
                    <h5 class="modal-title text-dark fw-bold" id="payslipModalLabel">
                        <i class="bi bi-receipt me-2"></i> Payslip for <span class="name"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-primary fw-semibold">Employee Details</h6>
                            <p><strong>Name:</strong> <span id="name"></span></p>
                            <p><strong>Designation:</strong> <span id="designation"></span></p>
                            <p><strong>Department:</strong> <span id="department"></span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary fw-semibold">Salary Details</h6>
                            <div class="p-3 bg-light rounded-3">
                                <p><strong>Basic Salary:</strong> $<span id="basicSalary"></span></p>
                                <p><strong>Allowances:</strong> $<span id="allowances"></span></p>
                                <p><strong>Deductions:</strong> $<span id="deductions"></span></p>
                                <p><strong>Net Salary:</strong> $<span id="netSalary"></span></p>
                                <p><strong>Payment Date:</strong> <span id="paymentDate"></span></p>
                                <p><strong>Payment Status:</strong> <span
                                        class="badge bg-warning text-dark" id="paymentStatus"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" id="download">Download</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        employeeId = @json($employeeId)
    </script>
    <script src="{{ asset('assets/js/salary.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
