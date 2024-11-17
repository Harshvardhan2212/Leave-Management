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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="assets/css/app.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>

    <style>
        /* Modal styles */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background-color: #343a40;
            color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-close {
            background-color: #f8f9fa;
        }

        .modal-body {
            background-color: #f8f9fa;
        }

        .btn-save {
            background-color: #343a40;
            color: #fff;
        }
        .error{
            color: #eb6868;
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
                    <h1 class="h3 mb-0"><strong>Employee</strong> Table</h1>
                    <i class="bi bi-person-add fs-1 me-2" id="addEmployeeButton" data-bs-toggle="modal"
                        data-bs-target="#editEmployeeModal" data-mode="add"></i>
                </div>

                <!-- Container for Search Bar and Table -->
                <div class="table-container">

                    <!-- Search Bar with Filter -->
                    <div class="search-bar mb-4">
                        <form action="{{ route('employee.index') }}" method="GET">
                            <div class="input-group">
                                <!-- Filter Dropdown -->
                                <select class="form-select" name="filter" aria-label="Filter by">
                                    <option value="name" {{ request('filter') == 'name' ? 'selected' : '' }}>Name
                                    </option>
                                    <option value="department"
                                        {{ request('filter') == 'department' ? 'selected' : '' }}>Department</option>
                                    <option value="designation"
                                        {{ request('filter') == 'designation' ? 'selected' : '' }}>Designation</option>
                                </select>
                                <!-- Search Input -->
                                <input type="text" class="form-control" name="search" placeholder="Search..."
                                    value="{{ request('search') }}">
                                <!-- Search Button -->
                                <button class="btn btn-secondary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>

                    <!-- Employee Table -->
                    <table class="table table-hover my-0 text-center">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Joining Date</th>
                                <th>Current Salary</th>
                                <th colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="alert alert-light" role="alert">
                                            No records found.
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->userDetails->first_name }} {{ $item->userDetails->last_name }}
                                        </td>
                                        <td>{{ $item->userDetails->email }}</td>
                                        <td>{{ $item->userDetails->phone_number }}</td>
                                        <td>{{ $item->designation }}</td>
                                        <td>{{ $item->departmentDetails->department_name }}</td>
                                        <td>{{ $item->joining_date }}</td>
                                        <td>{{ number_format($item->current_salary, 2) }}</td>
                                        <td>
                                            <i class="bi bi-pencil-square edit-icon" data-bs-toggle="modal"
                                                data-bs-target="#editEmployeeModal" data-mode="edit"
                                                data-id="{{ $item->id }}"></i>
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.show', ['employee' => $item->id]) }}">
                                                <i class="bi bi-eye text-secondary view-icon"
                                                    data-id="{{ $item->id }}"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="bi bi-trash3 delete-icon" id="delete" data-id="{{ $item->id }}"></i>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            {{ $data->links('vendor.pagination.custom-pagination') }}
                        </div>
                        <div>
                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }}
                            entries
                        </div>
                    </div>
                </div>
            </main>
            @include('component.footer')
        </div>
    </div>

    {{-- <select name="department" id="department" class="form-select">
        </select> --}}



   <!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content"
            style="border-radius: 10px; border: none; box-shadow: 0px 5px 15px rgba(0,0,0,0.3);">
            <div class="modal-header"
                style="background-color: #343a40; color: #fff; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title text-white" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="background-color: #f8f9fa; border: none; box-shadow: none;"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <form id="editEmployeeForm" action="javascript:;" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="_method" name="_method" value="post">

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="editPhoneNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="editDepartment" class="form-label">Department</label>
                            <select name="department" id="department" class="form-select" required>
                                
                            </select>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="editDesignation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="editJoiningDate" class="form-label">Joining Date</label>
                            <input type="datetime-local" class="form-control" id="joining_date" name="joining_date" required>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="editCurrentSalary" class="form-label">Current Salary</label>
                            <input type="number" class="form-control" id="current_salary" name="current_salary" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="editProfilePhoto" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3 col-6" id="inputPassword">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-secondary mt-2">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>







    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}
    <script src="assets/js/employee.js"></script>
    <script src="assets/js/app.js"></script>


</body>

</html>
