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
    <link rel="shortcut icon" href="{{asset('assets/icons/icon-48x48.png')}}" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>Leave Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
</head>

<body>
    <div class="wrapper">

        @include('component.sidebar')

        <div class="main">

            @include('component.navbar')

            <main class="content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 d-inline align-middle">Leave</h1>
                </div>
                {{-- cards --}}
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">Monthly Casual Leaves</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                Taken : {{$leave['casual_leave']->month_leave_days}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">Yearly Casual Leaves</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                Taken : {{$leave['casual_leave']->year_leave_days}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">Monthly Medical Leaves</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                Taken : {{$leave['medical_leave']->month_leave_days}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-center mt-3">
                                <h4 class="">Yearly Medical Leaves</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="registration-content mr-xl-2">
                                            <h4 class="mb-0">
                                                Taken : {{$leave['medical_leave']->year_leave_days}}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Attedance Chart --}}
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attedance Chart</h5>
                    </div>
                    <div class="card-body py-3">
                        <div class="chart chart-sm">
                            <canvas id="chartjs-dashboard-line"></canvas>
                        </div>
                    </div>
                </div>
                
                    {{-- Leave Details --}}
                    <div class="card mb-3">
                        <div class="d-flex justify-content-between align-items-center m-4 mb-0">
                            <h5 class="h3 d-inline align-middle">Leave</h5>
                            <a href="/leave-form">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#saralyEdit">
                                Add Leave
                            </button>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover my-0 text-center">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th class="d-none d-md-table-cell">Status</th>
                                        <th class="d-none d-xl-table-cell">Start Date</th>
                                        <th class="d-none d-xl-table-cell">Total Leave Days</th>
                                    </tr>
                                </thead>
                                <tbody id="leave-details-body">
                                    <!-- Leave details will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
            </main>

            @include('component.footer')
        </div>
    </div>
    <script>
        employeeId = @json($employeeId)
    </script>
    <script src="{{asset("assets/js/app.js")}}"></script>
    <script src="{{ asset('assets/js/userLeave.js') }}"></script>

</body>

</html>
