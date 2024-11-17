<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin & Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('assets/icons/icon-48x48.png') }}" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <title>User Profile - Leave Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>

<body>
    <div class="wrapper">
        @include('component.sidebar')
        <div class="main">
            @include('component.navbar')

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3 d-inline align-middle">Profile</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xl-3">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Profile Details</h5>
                                </div>
                                <div class="card-body text-center position-relative">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ asset('UserProfile/' . Auth::user()->image) ?? asset('UserProfile/userLogo.png')}}" alt="Profile Image"
                                            class="img-fluid rounded-circle mb-2" width="128" height="128" />
                                        <!-- Edit icon in the bottom-right corner -->
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editImageModal"
                                            class="position-absolute bottom-0 end-0">
                                            <i class="bi bi-pencil-square fs-5 text-primary"></i>
                                        </a>
                                    </div>
                                    <h5 class="card-title mb-0">{{ Auth::user()->first_name }}
                                        {{ Auth::user()->last_name }}</h5>
                                    <div class="text-muted mb-2">{{ $Employee->designation }}</div>

                                    <div>
                                        <a class="btn btn-primary btn-sm" href="#">Follow</a>
                                        <a class="btn btn-primary btn-sm" href="#"><span
                                                data-feather="message-square"></span> Message</a>
                                    </div>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body">
                                    <h5 class="h6 card-title">Skills</h5>
                                    <a href="#" class="badge bg-primary me-1 my-1">HTML</a>
                                    <a href="#" class="badge bg-primary me-1 my-1">JavaScript</a>
                                    <a href="#" class="badge bg-primary me-1 my-1">Sass</a>
                                    <a href="#" class="badge bg-primary me-1 my-1">Angular</a>
                                    <a href="#" class="badge bg-primary me-1 my-1">Vue</a>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body">
                                    <h5 class="h6 card-title">About</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span>
                                            Lives in <a href="#">San Francisco, SA</a></li>

                                        <li class="mb-1"><span data-feather="briefcase"
                                                class="feather-sm me-1"></span> Works at <a href="#">GitHub</a>
                                        </li>
                                        <li class="mb-1"><span data-feather="map-pin" class="feather-sm me-1"></span>
                                            From <a href="#">Boston</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-8 col-lg-9">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Personal Information</h5>
                                </div>
                                <div class="card-body">
                                    <form id="profileForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="firstName" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="firstName"
                                                        name="first_name" value="{{ Auth::user()->first_name }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="lastName" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="lastName"
                                                        name="last_name" value="{{ Auth::user()->last_name }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" value="{{ Auth::user()->email }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="joiningDate" class="form-label">Joining Date</label>
                                                    <input type="date" class="form-control" id="joiningDate"
                                                        name="joiningDate"
                                                        value="{{ date('Y-m-d', strtotime($Employee->joining_date)) }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control" id="phoneNumber"
                                                        name="phone_number" value="{{ Auth::user()->phone_number }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="department" class="form-label">Department</label>
                                                    <select name="department" id="department" class="form-select"
                                                        required>
                                                        <option value="" style="display:none">Select</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                {{ $Employee->department_id == $department->id ? 'selected' : '' }}>
                                                                {{ $department->department_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-secondary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Change Password</h5>
                                </div>
                                <div class="card-body">
                                    <form id="changePasswordForm" action="javascript:;">
                                        <div class="mb-3">
                                            <label for="currentPassword" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="currentPassword"
                                                name="currentPassword" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="newPassword" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="newPassword"
                                                name="newPassword" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirmPassword" class="form-label">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control" id="confirmPassword"
                                                name="confirmPassword" required>
                                        </div>
                                        <button type="submit" class="btn btn-secondary">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Modal for image crop and edit -->
                <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editImageModalLabel">Edit Profile Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="file" id="imageUpload" accept="image/*" class="form-control mb-3">
                                <!-- Cropping container -->
                                <div class="img-container">
                                    <img id="imagePreview" src="#" alt="Image to crop"
                                        class="img-fluid d-none">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveCroppedImage">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>


            </main>

            @include('component.footer')
        </div>
    </div>


    <script src="{{ asset('assets/js/profile.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
