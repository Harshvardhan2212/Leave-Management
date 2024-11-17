<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="assets/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>Leave Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="assets/css/app.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    
</head>

<body>
    <div class="wrapper">

        @include('component.sidebar')

        <div class="main">

            @include('component.navbar')


            <main class="content">
                <div class="container-fluid p-0">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3 mb-0"><strong>Analytics</strong> Dashboard</h1>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#holidayModal">
                            Add Holiday
                        </button>
                    </div>

                    <div class="row">
                        <div class="w-100">
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Approved Leaves</h5>
                                                </div>

                                                <div class="col-auto">
                                                    <div class="stat text-primary">
                                                        <i class="bi bi-clipboard-data align-middle fs-3"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1 mb-3">{{$leavesCount->approved_count}}</h1>
                                            <div class="mb-0">
                                                <span class="text-danger">{{$leavesCount->pending_count}}</span>
                                                <span class="text-muted">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Employees</h5>
                                                </div>

                                                <div class="col-auto">
                                                    <div class="stat text-primary">
                                                        <i class="align-middle" data-feather="users"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1 mb-3">{{$EmployeeCount}}</h1>
                                            <div class="mb-0">
                                                <span class="text-success">{{$todayPresentCount}}</span>
                                                <span class="text-muted">Present</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Employees with Paid Salaries</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="stat text-primary">
                                                        <i class="align-middle" data-feather="dollar-sign"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1 mb-3">{{$salaryCount->paid_count ?? 0}}</h1>
                                            <div class="mb-0">
                                                <span class="text-danger">{{$salaryCount->pending_count ?? 0}}</span>
                                                <span class="text-muted">pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Upcoming Holidays This Year</h5>
                                                </div>

                                                <div class="col-auto">
                                                    <div class="stat text-primary">
                                                        <i class="bi bi-calendar-check-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1 mb-3">{{$holidayCounts->upcoming_count}}</h1>
                                            <div class="mb-0">
                                                <span class="text-danger">{{$holidayCounts->passed_count}}</span>
                                                <span class="text-muted">holidays have passed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        
                        {{-- attendence chart --}}
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
                            <div class="card flex-fill w-100">
                                <div class="card-header">

                                    <h5 class="card-title mb-0">Employees attendance</h5>
                                </div>
                                <div class="card-body d-flex">
                                    <div class="align-self-center w-100">
                                        <div class="py-3">
                                            <div class="chart chart-xs">
                                                <canvas id="chartjs-dashboard-pie"></canvas>
                                            </div>
                                        </div>

                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Present</td>
                                                    <td class="text-end">{{$EmployeeCount-$todayPresentCount}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Adsent</td>
                                                    <td class="text-end">{{$todayPresentCount}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Salary chart --}}
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
                            <div class="card flex-fill w-100">
                                <div class="card-header">

                                    <h5 class="card-title mb-0">Salary</h5>
                                </div>
                                <div class="card-body d-flex">
                                    <div class="align-self-center w-100">
                                        <div class="py-3">
                                            <div class="chart chart-xs">
                                                <canvas id="salary-chart"></canvas>
                                            </div>
                                        </div>

                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Paid</td>
                                                    <td class="text-end">{{$salaryCount->paid_count}}</td>
                                                </tr>
                                                <tr>
                                                    <td>pending</td>
                                                    <td class="text-end">{{$salaryCount->pending_count}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- holiday table  --}}
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
                            <div class="card flex-fill w-100">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Holidays</h5>
                                </div>
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="align-self-center w-100">
                                        <table class="table table-hover ">
                                            <thead>
                                                <tr>
                                                    <th>Holiday Name</th>
                                                    <th class="d-none d-xl-table-cell">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($holidayTable as $holiday)
                                                <tr>
                                                    <td>{{$holiday->holiday_name}}</td>
                                                    <td class="d-none d-xl-table-cell">{{$holiday->holiday_date}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Calender --}}
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
                            <div class="card flex-fill">
                                <div class="card-header">

                                    <h5 class="card-title mb-0">Calendar</h5>
                                </div>
                                <div class="card-body d-flex">
                                    <div class="align-self-center w-100">
                                        <div class="chart">
                                            <div id="datetimepicker-dashboard"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            @include('component.footer')

        </div>
    </div>
    <!-- Holiday Modal -->
    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 10px; border: none; box-shadow: 0px 5px 15px rgba(0,0,0,0.3);">
                <div class="modal-header"
                    style="background-color: #343a40; color: #fff; border-bottom: 1px solid #dee2e6;">
                    <h5 class="modal-title" id="holidayModalLabel" style="color: #fff;">
                        <i class="bi bi-calendar-plus me-2"></i> Create Holiday
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="background-color: #f8f9fa; border: none; box-shadow: none;"></button>
                </div>
                <div class="modal-body" style="background-color: #f8f9fa;">
                    <form id="holidayForm" action="javascript:;" method="POST">
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="holidayName" class="form-label">Holiday Name</label>
                                <input type="text" class="form-control" id="holidayName" name="holidayName"
                                    placeholder="Enter holiday name" required>
                                <div class="invalid-feedback">Holiday name is required.</div>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="holidayDate" class="form-label">Holiday Date</label>
                                <input type="date" class="form-control" id="holiday_date" name="holidayDate"
                                    required>
                                <div class="invalid-feedback">Holiday date is required.</div>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="holidayDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="holidayDescription" name="holidayDescription" rows="3"
                                    placeholder="Enter a brief description" required></textarea>
                                <div class="invalid-feedback">Description is required.</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn" style="background-color: #343a40; color: #fff;">Save Holiday</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let todayPresentCount = @json($todayPresentCount);
        let absentCount = @json($EmployeeCount-$todayPresentCount);
        let salaryCount = @json($salaryCount);
        let holidays = @json($holydayDate);
        
        
    </script>
    <script src="assets/js/app.js"></script>
    <script src="{{asset('assets/js/dashboard.js')}}"></script>

</body>

</html>
