<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link href="<?= ASSETS ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/css/animate.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="<?= ASSETS ?>/assets/js/jquery-2.1.1.js"></script>

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .35rem;
            font-size: 12px;
            color: #dc3545;
        }
    </style>
    <script src="https://kit.fontawesome.com/e216f95262.js" crossorigin="anonymous"></script>
</head>

<body class="fixed-navigation">

    <div class="row">
        <div class="flash-data" data-flashdata="<?= Flasher::flash(true); ?>"></div>
    </div>

    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation" style="position:fixed">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span style="width:10px">
                                    <img alt="image" class="img-circle" style="width:64px; height:64px;" src="<?= ASSETS ?>/assets/img/admin.png" />
                                </span>
                                <span class="clear" style="width:100px">
                                    <span class="block m-t-xs"> <strong class="font-bold"><?= $_SESSION['session_login']; ?></strong></span>
                                    <span class="text-muted text-xs block">
                                        <?= $_SESSION['session_login_grade'] ?></span>
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">
                            <img src="<?= ASSETS ?>/assets/img/admin.png" style="width:64px; height:64px;">
                        </div>
                    </li>
                    <?php if ($_SESSION['session_login_grade'] !== "syahbandar") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard"><i class="fa fa-th-large"></i> <text class="nav-label">Dashboard</text> </a>
                        </li>
                    <?php endif ?>
                    <?php if ($_SESSION['session_login_grade'] !== "administrator") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard/profile') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/profile"><i class="fa fa-user"></i> <text class="nav-label">Profile</text> </a>
                        </li>
                    <?php endif ?>
                    <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard/user') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/user"><i class="fa fa-user"></i> <text class="nav-label">Account User</text></a>
                        </li>

                        <li class="<?= (Url::checkAll() == 'dashboard/agent') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/agent"><i class="fa fa-users"></i> <text class="nav-label">Agent List</text></a>
                        </li>
                    <?php endif ?>
                    <?php if ($_SESSION['session_login_grade'] !== "accounting" && $_SESSION['session_login_grade'] !== "syahbandar") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard/book') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/book"><i class="fas fa-money-bill-wave"></i> <text class="nav-label">Transaction</text></a>
                        </li>
                    <?php endif ?>
                    <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard/boat') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/boat"><i class="fas fa-ship"></i> <text class="nav-label">Boat</text></a>
                        </li>
                        <li class="<?= (Url::checkAll() == 'dashboard/schedule') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/schedule"><i class="fas fa-calendar-week"></i> <text class="nav-label">Schedule</text></a>
                        </li>
                        <li class="<?= (Url::checkAll() == 'dashboard/transport') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/transport"><i class="fas fa-car"></i> <text class="nav-label">Transport</text></a>
                        </li>
                    <?php endif ?>

                    <li class="<?= (Url::checkAll() == 'dashboard/report') ? 'active' : '' ?>">
                        <a href="#"><i class="fa fa-area-chart"></i> <text class="nav-label">Report</text> <span><i class="fa arrow"></i></span></a>
                        <ul class="nav nav-second-level">
                            <?php if ($_SESSION['session_login_grade'] !== "accounting" && $_SESSION['session_login_grade'] !== "syahbandar") : ?>
                                <li><a href="<?= BASEURL ?>dashboard/report/booking">Booking Report</a></li>
                            <?php endif ?>
                            <?php if ($_SESSION['session_login_grade'] == "administrator" || $_SESSION['session_login_grade'] == "syahbandar" || $_SESSION['session_login_grade'] == "staff") : ?>
                                <li><a href="<?= BASEURL ?>dashboard/report/mitra">Passenger Report</a></li>
                            <?php endif ?>
                            <?php if ($_SESSION['session_login_grade'] == "accounting" || $_SESSION['session_login_grade'] == "administrator") : ?>
                                <li><a href="<?= BASEURL ?>dashboard/report/agent">Agent Report</a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                    <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                        <li class="<?= (Url::checkAll() == 'dashboard/setting') ? 'active' : '' ?>">
                            <a href="<?= BASEURL ?>dashboard/setting"><i class="fas fa-users-cog"></i> <text class="nav-label">Setting</text></a>
                        </li>
                    <?php endif ?>

                    <li>
                        <a href="<?= BASEURL ?>login/logout"><i class="fa fa-sign-out"></i> <text class="nav-label">Logout</text></a>
                    </li>
                </ul>

            </div>
        </nav>


        <div id="page-wrapper" class="gray-bg sidebar-content">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Welcome to Dashboard" class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="<?= BASEURL ?>login/logout">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>