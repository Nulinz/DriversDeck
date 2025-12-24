<div class="main">
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
        </a>

        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-icon pe-md-0 dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-fw fa-user-circle"></i>
                            <span class="fs-4 ms-1">{{ Auth::user()->name }}</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a data-bs-toggle="modal" class="dropdown-item" data-bs-target="#centeredModal"><i
                                class="align-middle me-1" data-feather="help-circle"></i> Change Password</a>
                        <a class="dropdown-item" href="{{ route('admin.auth.logout') }}"><i
                                class="align-middle me-2 fa fa-fw fa-sign-out-alt"></i> Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- BEGIN primary modal -->
    <div class="modal fade" id="centeredModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fs-4 fw-bold modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3 col-md-12">
                            <label class="form-label fw-bold">Old Password</label>
                            <input type="text" name="" id="" class="form-control" minlength="6">
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label fw-bold">New Password</label>
                            <input type="text" name="" id="" class="form-control" minlength="6"
                                data-toggle="tooltip" data-placement="top"
                                title="Password needs to be at least 6 characters long">
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="text" name="" id="" class="form-control" minlength="6">
                        </div>
                        <div class="modal-footer border-0 p-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="button" class="btn btn-primary w-25" name="" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END primary modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

        });
    </script>
