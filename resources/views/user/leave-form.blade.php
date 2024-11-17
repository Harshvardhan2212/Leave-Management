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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body>
    <div class="wrapper">

        @include('component.sidebar')

        <div class="main">

            @include('component.navbar')

            <main class="content">
                <div class="row">
                    <!-- Employee view page -->
                    <div class="col-xl-12 col-md-12 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body pb-0 mx-25">
                                    <!-- header section -->
                                    <div class="row mx-0">
                                        <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                                            <h4 class="mr-75">Add Leave</h4>
                                        </div>
                                    </div>
                                    <hr>
                                    <form class="form" method="POST" action="{{route('leave-submit')}}" id="create_leave">
                                        @csrf
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-12 my-2">
                                                            <label for="leave_type">Leave Type</label><span class="required text-danger">*</span>
                                                            <div class="form-group mt-2">
                                                                <div class="controls">
                                                                    <select name="leave_type" id="leave_type" class="js-example-placeholder-single js-states form-control" data-validation- text-danger-message="Leave Type field is  text-danger">
                                                                        <option value="">Select leave</option>
                                                                       @foreach ($leaveTypes as $leaveType)
                                                                        <option value="{{$leaveType->id}}">{{$leaveType->leave_type_name}}</option>
                                                                       @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                    
                                                        <!-- Updated Leave Start Date and Leave End Date Fields -->
                                                        <div class="col-12 col-sm-6 my-2">
                                                            <label for="leave_start_date">Leave Start Date</label><span class="required text-danger">*</span>
                                                            <div class="form-group position-relative has-icon-left mt-2">
                                                                <input id="leave_start_date" class="form-control" placeholder="Select Start Date" name="start_date" type="date">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 my-2">
                                                            <label for="leave_end_date">Leave End Date</label><span class="required text-danger">*</span>
                                                            <div class="form-group position-relative has-icon-left mt-2">
                                                                <input id="leave_end_date" class="form-control" placeholder="Select End Date" name="end_date" type="date">
                                                            </div>
                                                        </div>
                                                    </div>
                                    
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label for="reason">Leave Reason</label><span class="required text-danger">*</span>
                                                            <div class="form-group form-label-group mt-2">
                                                                <fieldset class="form-label-group">
                                                                    <textarea id="reason" rows="4" class="form-control" data-validation-required-message="Leave reason field is required" placeholder="Add Leave Reason" name="reason" cols="50"></textarea>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                    
                                            <div class="col-12 d-flex justify-content-end my-2">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="check-for-validation">
                                                    <span class="d-none d-sm-block">Submit</span>
                                                </button>
                                                <button type="reset" class="btn btn-light ms-1 mb-1" onclick="window.location.href=window.location.href">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </main>

            @include('component.footer')
        </div>
    </div>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/leaveForm.js') }}"></script>

</body>

</html>
