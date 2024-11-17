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
    <link rel="shortcut icon" href="assets/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>Leave Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="assets/css/app.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Custom Modal Styling */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .form-label {
            font-size: 0.95rem;
            color: #495057;
        }

        .text-primary {
            color: #007bff;
        }

        .text-secondary {
            color: #6c757d;
        }

        .btn-success,
        .btn-danger {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-close {
            background: none;
            border: none;
            opacity: 0.5;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-footer {
            border-top: none;
            border-radius: 0 0 10px 10px;
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
                    <h1 class="h3 mb-0"><strong>Leave-Approval</strong> Table</h1>
                </div>
                <!-- Search Bar with Filter -->
                <div class="search-bar mb-4">
                    <form id="filter-form" class="d-flex" action="{{route('leave-approval')}}" method="GET">
                        <div class="input-group">
                            <!-- Filter Dropdown -->
                            <select class="form-select" name="filter" aria-label="Filter by">
                                <option value="name" {{ request('filter') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="department" {{ request('filter') == 'department' ? 'selected' : '' }}>
                                    Department</option>
                                <option value="leave_type" {{ request('filter') == 'leave_type' ? 'selected' : '' }}>
                                    Leave Type</option>
                            </select>
                            <!-- Search Input -->
                            <input type="text" class="form-control" name="search" placeholder="Search..."
                                value="{{ request('search') }}">
                            <!-- Search Button -->
                            <button class="btn btn-secondary" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-hover my-0 text-center" id="leaveTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Leave Type</th>
                            <th class="d-none d-xl-table-cell">Start Date</th>
                            <th class="d-none d-xl-table-cell">End Date</th>
                            <th class="d-none d-md-table-cell">View</th>
                            <th class="d-none d-md-table-cell">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveList as $leave)
                            <tr>
                                <td>{{ $leave->getEmployee->userDetails->first_name ?? 0 }}
                                    {{ $leave->getEmployee->userDetails->last_name ?? 0 }}</td>
                                <td>{{ $leave->getEmployee->departmentDetails->department_name ?? 0 }}</td>
                                <td><span class="text">{{ $leave->getLeaveType->leave_type_name ?? 0 }}</span></td>
                                <td class="d-none d-xl-table-cell">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d F Y') ?? 0 }}</td>
                                <td class="d-none d-xl-table-cell">
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d F Y') ?? 0 }}</td>
                                <td class="d-none d-md-table-cell fs-4 text-secondary"><i class="bi bi-eye"
                                        id='view' data-id={{ $leave->id ?? 0 }} data-bs-toggle="modal"
                                        data-bs-target="#leaveModal"></i></td>
                                @if ($leave->leave_status == 'approved')
                                    <td><span class="badge bg-success">{{ $leave->leave_status }}</span></td>
                                @else
                                    <td><span class="badge bg-info">{{ $leave->leave_status }}</span></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination Controls -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        {{ $leaveList->links('vendor.pagination.custom-pagination') }}
                    </div>
                    <div>
                        Showing {{ $leaveList->firstItem() }} to {{ $leaveList->lastItem() }} of
                        {{ $leaveList->total() }}
                        entries
                    </div>
                </div>
            </main>

            @include('component.footer')
        </div>
    </div>


    <!-- Leave Details Modal -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-0 rounded-top">
                    <h5 class="modal-title text-dark fw-bold" id="leaveModalLabel">
                        <i class="bi bi-person-lines-fill me-2"></i> Leave Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong class="text-primary">Name:</strong>
                        <span id="name" class="text-secondary">John Doe</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Department:</strong>
                        <span id="department" class="text-secondary">Laravel Development</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Leave Type:</strong>
                        <span id="leave-type" class="text-secondary">Casual Leave</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Applied Date:</strong>
                        <span id="applied-date" class="text-secondary">01/02/2025</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Start Date:</strong>
                        <span id="start-date" class="text-secondary">02/02/2025</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">End Date:</strong>
                        <span id="end-date" class="text-secondary">10/02/2025</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Description:</strong>
                        <p id="description" class="text-secondary">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis cupiditate, minima a
                            asperiores at magnam autem aliquam sit reprehenderit accusamus.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-success w-100 mb-2" id="approve">Approve</button>
                    <button type="button" class="btn btn-danger w-100" id="reject">Reject</button>
                </div>
            </div>
        </div>
    </div>





    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}
    <script src="assets/js/app.js"></script>
    <script src="assets/js/leaveapproval.js"></script>

</body>

</html>
