<?php

session_start();

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
| This is your existing administrator protection.
*/

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    strtolower(trim($_SESSION['role'])) !== 'admin'
) {
    session_unset();
    session_destroy();

    header("Location: ../login.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

include("../database/db.php");


/*
|--------------------------------------------------------------------------
| OFFICE SEARCH
|--------------------------------------------------------------------------
*/

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';


$sql = "SELECT * FROM offices";


if (!empty($search)) {

    $search_safe = mysqli_real_escape_string(
        $conn,
        $search
    );

    $sql .= "
        WHERE office_name LIKE '%$search_safe%'
        OR service_name LIKE '%$search_safe%'
        OR description LIKE '%$search_safe%'
        OR address LIKE '%$search_safe%'
        OR email LIKE '%$search_safe%'
        OR phone LIKE '%$search_safe%'
    ";
}


$sql .= " ORDER BY id ASC";


$result = mysqli_query($conn, $sql);


/*
|--------------------------------------------------------------------------
| OFFICE STATISTICS
|--------------------------------------------------------------------------
*/

$totalResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM offices"
);

$totalOffices = mysqli_fetch_assoc(
    $totalResult
)['total'];


$activeResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM offices
     WHERE status='active'"
);

$activeOffices = mysqli_fetch_assoc(
    $activeResult
)['total'];


$inactiveResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM offices
     WHERE status='inactive'"
);

$inactiveOffices = mysqli_fetch_assoc(
    $inactiveResult
)['total'];


/*
|--------------------------------------------------------------------------
| USER STATISTICS
|--------------------------------------------------------------------------
*/

$totalUsersResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

$totalUsers = mysqli_fetch_assoc(
    $totalUsersResult
)['total'];


$totalAdminsResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE LOWER(role) = 'admin'"
);

$totalAdmins = mysqli_fetch_assoc(
    $totalAdminsResult
)['total'];


$totalNormalUsersResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE LOWER(role) = 'user'"
);

$totalNormalUsers = mysqli_fetch_assoc(
    $totalNormalUsersResult
)['total'];


/*
|--------------------------------------------------------------------------
| GET REGISTERED USERS
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We do NOT select the password.
|
| Expected users table columns:
|
| id
| fullname
| username
| email
| phone
| password
| role
| created_at
|
*/

$usersSql = "
    SELECT
        id,
        fullname,
        username,
        email,
        phone,
        role,
        created_at
    FROM users
    ORDER BY id DESC
";


$usersResult = mysqli_query(
    $conn,
    $usersSql
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Admin Dashboard | Awi Zone Administrative Office
    </title>


    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f4f7f4;

            color: #26352b;

            min-height: 100vh;
        }


        /* =====================================================
           TOP NAVIGATION / HEADER
           KEEPING YOUR ADMIN NAVIGATION FUNCTIONALITY
        ===================================================== */

        .top-header {

            background: #006400;

            color: white;

            min-height: 72px;

            padding: 12px 5%;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            position: sticky;

            top: 0;

            z-index: 1000;

            box-shadow:
                0 3px 15px
                rgba(0, 0, 0, 0.15);
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .brand-icon {

            width: 44px;

            height: 44px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                rgba(255,255,255,0.14);

            border:
                1px solid
                rgba(255,255,255,0.3);

            font-size: 22px;
        }


        .brand-text h1 {

            font-size: 17px;

            margin-bottom: 3px;
        }


        .brand-text span {

            font-size: 10px;

            opacity: 0.8;

            letter-spacing: 0.4px;
        }


        .admin-area {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .admin-info {

            text-align: right;
        }


        .admin-info strong {

            display: block;

            font-size: 13px;
        }


        .admin-info small {

            font-size: 10px;

            opacity: 0.75;

            text-transform: uppercase;
        }


        .admin-avatar {

            width: 39px;

            height: 39px;

            border-radius: 50%;

            background: white;

            color: #006400;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: bold;
        }


        /* =====================================================
           MAIN CONTAINER
        ===================================================== */

        .main-container {

            width: 90%;

            max-width: 1250px;

            margin: auto;

            padding: 35px 0 60px;
        }


        /* =====================================================
           WELCOME
        ===================================================== */

        .welcome {

            background:
                linear-gradient(
                    120deg,
                    #006400,
                    #087a2f
                );

            color: white;

            border-radius: 17px;

            padding: 30px 35px;

            margin-bottom: 25px;

            box-shadow:
                0 10px 28px
                rgba(0,80,0,0.15);
        }


        .welcome small {

            text-transform: uppercase;

            letter-spacing: 1.5px;

            opacity: 0.75;
        }


        .welcome h2 {

            margin-top: 7px;

            font-size: 28px;
        }


        .welcome p {

            margin-top: 8px;

            font-size: 13px;

            opacity: 0.85;

            line-height: 1.6;
        }


        /* =====================================================
           STATISTICS
        ===================================================== */

        .stats-grid {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 17px;

            margin-bottom: 32px;
        }


        .stat-card {

            background: white;

            border-radius: 13px;

            padding: 20px;

            border:
                1px solid
                #e2e9e3;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.05);

            transition:
                transform 0.2s ease;
        }


        .stat-card:hover {

            transform: translateY(-3px);
        }


        .stat-top {

            display: flex;

            align-items: center;

            justify-content: space-between;
        }


        .stat-icon {

            width: 43px;

            height: 43px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #edf7ef;

            color: #006400;

            font-size: 20px;
        }


        .stat-number {

            margin-top: 15px;

            font-size: 28px;

            font-weight: bold;

            color: #26382c;
        }


        .stat-label {

            margin-top: 4px;

            color: #7a857d;

            font-size: 12px;
        }


        /* =====================================================
           SECTION HEADER
        ===================================================== */

        .section-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin: 32px 0 17px;

            flex-wrap: wrap;
        }


        .section-header h3 {

            color: #20352a;

            font-size: 19px;
        }


        .section-header p {

            margin-top: 4px;

            font-size: 12px;

            color: #78837b;
        }


        /* =====================================================
           BUTTONS
        ===================================================== */

        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            padding: 9px 14px;

            border-radius: 6px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            color: white;

            font-size: 12px;

            font-weight: bold;

            transition: 0.2s;
        }


        .btn-add {

            background: #006400;
        }


        .btn-add:hover {

            background: #004d00;
        }


        .btn-logout {

            background: #b52b2b;
        }


        .btn-logout:hover {

            background: #922020;
        }


        .btn-edit {

            background: #1671b8;
        }


        .btn-edit:hover {

            background: #105b96;
        }


        .btn-delete {

            background: #c0392b;
        }


        .btn-delete:hover {

            background: #a93226;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .search-area {

            background: white;

            padding: 18px;

            border-radius: 12px;

            border:
                1px solid
                #e3e9e4;

            margin-bottom: 18px;
        }


        .search-form {

            display: flex;

            gap: 8px;

            flex-wrap: wrap;
        }


        .search-form input {

            flex: 1;

            min-width: 220px;

            height: 42px;

            padding: 10px 12px;

            border:
                1px solid
                #ccd5ce;

            border-radius: 6px;

            outline: none;
        }


        .search-form input:focus {

            border-color: #006400;

            box-shadow:
                0 0 0 3px
                rgba(0,100,0,0.07);
        }


        .search-button {

            height: 42px;

            padding: 0 18px;

            border: none;

            border-radius: 6px;

            background: #34495e;

            color: white;

            font-weight: bold;

            cursor: pointer;
        }


        .search-button:hover {

            background: #263746;
        }


        .clear-link {

            display: inline-flex;

            align-items: center;

            color: #b52b2b;

            text-decoration: none;

            font-size: 12px;

            padding: 0 5px;
        }


        /* =====================================================
           REGISTERED USERS SECTION
        ===================================================== */

        .user-section {

            margin-top: 30px;
        }


        .user-section-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 15px;

            margin-bottom: 16px;

            flex-wrap: wrap;
        }


        .user-section-title {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .user-section-icon {

            width: 44px;

            height: 44px;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eaf5ed;

            color: #006400;

            font-size: 20px;
        }


        .user-section-title h3 {

            margin: 0;

            color: #20352a;

            font-size: 20px;
        }


        .user-section-title p {

            margin-top: 4px;

            color: #78837b;

            font-size: 12px;
        }


        .user-count {

            background: #006400;

            color: white;

            padding: 8px 14px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

            white-space: nowrap;
        }


        /* =====================================================
           USER TABLE
        ===================================================== */

        .user-table-container {

            width: 100%;

            background: white;

            border-radius: 15px;

            border:
                1px solid
                #e1e8e2;

            box-shadow:
                0 6px 22px
                rgba(0,0,0,0.06);

            overflow: hidden;
        }


        .user-table-scroll {

            width: 100%;

            overflow-x: auto;
        }


        .user-table {

            width: 100%;

            min-width: 900px;

            border-collapse: collapse;
        }


        .user-table thead th {

            background: #244332;

            color: white;

            padding: 14px 12px;

            text-align: center;

            font-size: 11px;

            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            white-space: nowrap;
        }


        .user-table tbody td {

            padding: 13px 12px;

            border-bottom:
                1px solid
                #edf1ee;

            text-align: center;

            vertical-align: middle;

            font-size: 12px;

            color: #4a584e;

            white-space: nowrap;
        }


        .user-table tbody tr {

            transition:
                background 0.2s ease;
        }


        .user-table tbody tr:hover {

            background: #f5faf6;
        }


        .user-table tbody tr:last-child td {

            border-bottom: none;
        }


        .user-id {

            color: #78837b !important;

            font-weight: bold;
        }


        /* =====================================================
           USER PROFILE
        ===================================================== */

        .user-profile {

            display: flex;

            align-items: center;

            gap: 10px;

            text-align: left;

            min-width: 190px;
        }


        .user-avatar-small {

            width: 38px;

            height: 38px;

            min-width: 38px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #006400,
                    #159447
                );

            color: white;

            font-size: 13px;

            font-weight: bold;

            box-shadow:
                0 3px 8px
                rgba(0,100,0,0.15);
        }


        .user-name {

            color: #26382c;

            font-weight: bold;

            font-size: 12px;
        }


        .username-cell {

            color: #006400 !important;

            font-weight: bold;
        }


        .email-cell {

            color: #56645b !important;
        }


        .phone-cell {

            color: #56645b !important;
        }


        .registered-date {

            color: #69766e !important;

            font-size: 11px !important;
        }


        /* =====================================================
           ROLE BADGES
        ===================================================== */

        .role-badge {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 72px;

            padding: 6px 11px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: bold;

            text-transform: capitalize;
        }


        .role-admin {

            background: #fff3d9;

            color: #956000;

            border:
                1px solid
                #f2d89b;
        }


        .role-user {

            background: #eaf6ed;

            color: #176b32;

            border:
                1px solid
                #c9e6d0;
        }


        /* =====================================================
           SECURITY NOTE
        ===================================================== */

        .security-note {

            display: flex;

            align-items: flex-start;

            gap: 9px;

            margin-top: 13px;

            padding: 13px 16px;

            background: #f3f8f4;

            border-left:
                4px solid
                #006400;

            border-radius: 7px;

            color: #68756d;

            font-size: 11px;

            line-height: 1.6;
        }


        /* =====================================================
           EMPTY USER TABLE
        ===================================================== */

        .user-empty {

            padding: 45px 20px !important;

            text-align: center !important;

            color: #8a948d !important;

            font-style: italic;
        }


        /* =====================================================
           OFFICE TABLE
        ===================================================== */

        .table-container {

            width: 100%;

            overflow-x: auto;

            background: white;

            border-radius: 12px;

            border:
                1px solid
                #e1e7e2;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.05);
        }


        table {

            width: 100%;

            min-width: 1050px;

            border-collapse: collapse;
        }


        table th {

            background: #244332;

            color: white;

            padding: 13px 10px;

            font-size: 12px;

            text-align: center;

            white-space: nowrap;
        }


        table td {

            padding: 12px 10px;

            border-bottom:
                1px solid
                #edf0ed;

            text-align: center;

            vertical-align: middle;

            font-size: 12px;

            color: #435148;
        }


        table tbody tr:hover {

            background: #f5faf6;
        }


        table tbody tr:last-child td {

            border-bottom: none;
        }


        .office-name {

            color: #174f29;

            font-weight: bold;
        }


        .description {

            max-width: 220px;

            line-height: 1.5;
        }


        .action-links {

            display: flex;

            justify-content: center;

            gap: 5px;

            flex-wrap: wrap;
        }


        .action-links .btn {

            padding: 6px 10px;

            font-size: 11px;
        }


        .no-data {

            padding: 30px;

            text-align: center;

            color: #888;

            font-style: italic;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {

            background: white;

            border-top:
                1px solid
                #e1e7e2;

            text-align: center;

            padding: 22px 15px;

            color: #7c867f;

            font-size: 11px;

            line-height: 1.7;
        }


        footer strong {

            color: #006400;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 950px) {

            .stats-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 700px) {

            .top-header {

                align-items: flex-start;

                padding: 12px 4%;
            }


            .admin-info {

                display: none;
            }


            .brand-text h1 {

                font-size: 14px;
            }


            .brand-text span {

                font-size: 9px;
            }


            .main-container {

                width: 92%;

                padding-top: 25px;
            }


            .welcome {

                padding: 25px;
            }


            .welcome h2 {

                font-size: 23px;
            }


            .stats-grid {

                grid-template-columns:
                    1fr 1fr;

                gap: 10px;
            }


            .stat-card {

                padding: 15px;
            }


            .stat-number {

                font-size: 23px;
            }


            .search-form {

                flex-direction: column;
            }


            .search-form input {

                width: 100%;

                min-width: 0;
            }


            .search-button {

                width: 100%;
            }


            .user-section-title h3 {

                font-size: 17px;
            }


            .user-table {

                min-width: 900px;
            }

        }


        @media (max-width: 430px) {

            .stats-grid {

                grid-template-columns: 1fr;
            }


            .brand-icon {

                width: 37px;

                height: 37px;
            }


            .brand-text h1 {

                font-size: 12px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="top-header">


    <div class="brand">

        <div class="brand-icon">
            🏛️
        </div>


        <div class="brand-text">

            <h1>
                Awi Zone Administrative Office
            </h1>

            <span>
                Office Direction & Information System
            </span>

        </div>

    </div>


    <div class="admin-area">

        <div class="admin-info">

            <strong>
                <?= htmlspecialchars(
                    $_SESSION['username'] ?? 'Admin'
                ) ?>
            </strong>

            <small>
                Administrator
            </small>

        </div>


        <div class="admin-avatar">

            <?= strtoupper(
                htmlspecialchars(
                    substr(
                        $_SESSION['username'] ?? 'A',
                        0,
                        1
                    )
                )
            ) ?>

        </div>

    </div>

</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main-container">


    <!-- =====================================================
         WELCOME ADMIN
    ===================================================== -->

    <section class="welcome">

        <small>
            Administration Panel
        </small>

        <h2>
            Welcome, Admin
        </h2>

        <p>
            Manage government offices, monitor registered users,
            and maintain the Awi Zone Administrative Office
            Direction and Information System.
        </p>

    </section>



    <!-- =====================================================
         OFFICE STATISTICS
    ===================================================== -->

    <div class="stats-grid">


        <!-- TOTAL OFFICES -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Offices
                </strong>

                <div class="stat-icon">
                    🏢
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($totalOffices) ?>
            </div>

            <div class="stat-label">
                Total registered offices
            </div>

        </div>


        <!-- ACTIVE OFFICES -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Active
                </strong>

                <div class="stat-icon">
                    ✓
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($activeOffices) ?>
            </div>

            <div class="stat-label">
                Active offices
            </div>

        </div>


        <!-- INACTIVE OFFICES -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Inactive
                </strong>

                <div
                    class="stat-icon"
                    style="
                        background:#fff0f0;
                        color:#b52b2b;
                    "
                >
                    !
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($inactiveOffices) ?>
            </div>

            <div class="stat-label">
                Inactive offices
            </div>

        </div>


        <!-- TOTAL USERS -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Users
                </strong>

                <div class="stat-icon">
                    👥
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($totalUsers) ?>
            </div>

            <div class="stat-label">
                Registered system users
            </div>

        </div>


    </div>



    <!-- =====================================================
         USER OVERVIEW
    ===================================================== -->

    <div class="section-header">

        <div>

            <h3>
                User Overview
            </h3>

            <p>
                Summary of registered accounts
            </p>

        </div>


        <a
            class="btn btn-add"
            href="register.php"
        >
            ➕ Register User
        </a>

    </div>


    <div class="stats-grid">


        <!-- ALL USERS -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    All Users
                </strong>

                <div class="stat-icon">
                    👥
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($totalUsers) ?>
            </div>

            <div class="stat-label">
                All registered accounts
            </div>

        </div>


        <!-- ADMINISTRATORS -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Administrators
                </strong>

                <div
                    class="stat-icon"
                    style="
                        background:#fff5df;
                        color:#9a6100;
                    "
                >
                    🛡️
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($totalAdmins) ?>
            </div>

            <div class="stat-label">
                Administrator accounts
            </div>

        </div>


        <!-- NORMAL USERS -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Normal Users
                </strong>

                <div class="stat-icon">
                    👤
                </div>

            </div>

            <div class="stat-number">
                <?= htmlspecialchars($totalNormalUsers) ?>
            </div>

            <div class="stat-label">
                Standard user accounts
            </div>

        </div>


        <!-- ACCOUNT MANAGEMENT -->

        <div class="stat-card">

            <div class="stat-top">

                <strong>
                    Account Management
                </strong>

                <div class="stat-icon">
                    ⚙️
                </div>

            </div>

            <div
                style="
                    margin-top:15px;
                    color:#78837b;
                    font-size:12px;
                    line-height:1.6;
                "
            >
                Register a new system user or
                administrator account.
            </div>

            <div style="margin-top:15px;">

                <a
                    class="btn btn-add"
                    href="register.php"
                >
                    Add User
                </a>

            </div>

        </div>


    </div>



    <!-- =====================================================
         REGISTERED USERS
         THIS IS THE UPDATED SECTION
    ===================================================== -->

    <section class="user-section">


        <!-- USER SECTION HEADER -->

        <div class="user-section-header">


            <div class="user-section-title">


                <div class="user-section-icon">
                    👥
                </div>


                <div>

                    <h3>
                        Registered Users
                    </h3>

                    <p>
                        Essential account information for system users
                    </p>

                </div>


            </div>


            <!-- TOTAL USERS BADGE -->

            <div class="user-count">

                👤 <?= htmlspecialchars($totalUsers) ?>

                <?= $totalUsers == 1
                    ? 'User'
                    : 'Users'
                ?>

            </div>


        </div>



        <!-- USER TABLE -->

        <div class="user-table-container">

            <div class="user-table-scroll">

                <table class="user-table">


                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Username
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Registered
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if (
                            $usersResult &&
                            mysqli_num_rows($usersResult) > 0
                        ): ?>


                            <?php while (
                                $user = mysqli_fetch_assoc(
                                    $usersResult
                                )
                            ): ?>


                                <?php

                                /*
                                ----------------------------------------
                                USER ROLE
                                ----------------------------------------
                                */

                                $userRole = strtolower(
                                    trim(
                                        $user['role'] ?? ''
                                    )
                                );


                                /*
                                ----------------------------------------
                                USER DISPLAY NAME
                                ----------------------------------------
                                */

                                $displayName =
                                    !empty($user['fullname'])
                                    ? $user['fullname']
                                    : $user['username'];


                                /*
                                ----------------------------------------
                                FIRST LETTER FOR AVATAR
                                ----------------------------------------
                                */

                                $firstLetter =
                                    strtoupper(
                                        substr(
                                            $displayName,
                                            0,
                                            1
                                        )
                                    );

                                ?>


                                <tr>


                                    <!-- ==============================
                                         ID
                                    =============================== -->

                                    <td class="user-id">

                                        #<?= htmlspecialchars(
                                            $user['id']
                                        ) ?>

                                    </td>



                                    <!-- ==============================
                                         USER / FULL NAME
                                    =============================== -->

                                    <td>

                                        <div class="user-profile">


                                            <div
                                                class="user-avatar-small"
                                            >

                                                <?= htmlspecialchars(
                                                    $firstLetter
                                                ) ?>

                                            </div>


                                            <div class="user-name">

                                                <?= htmlspecialchars(
                                                    $user['fullname']
                                                ) ?>

                                            </div>


                                        </div>

                                    </td>



                                    <!-- ==============================
                                         USERNAME
                                    =============================== -->

                                    <td class="username-cell">

                                        @<?= htmlspecialchars(
                                            $user['username']
                                        ) ?>

                                    </td>



                                    <!-- ==============================
                                         EMAIL
                                    =============================== -->

                                    <td class="email-cell">

                                        <?= htmlspecialchars(
                                            $user['email']
                                        ) ?>

                                    </td>



                                    <!-- ==============================
                                         PHONE
                                    =============================== -->

                                    <td class="phone-cell">

                                        <?php if (
                                            !empty($user['phone'])
                                        ): ?>

                                            <?= htmlspecialchars(
                                                $user['phone']
                                            ) ?>

                                        <?php else: ?>

                                            —

                                        <?php endif; ?>

                                    </td>



                                    <!-- ==============================
                                         ROLE
                                    =============================== -->

                                    <td>

                                        <span
                                            class="
                                                role-badge
                                                <?= $userRole === 'admin'
                                                    ? 'role-admin'
                                                    : 'role-user'
                                                ?>
                                            "
                                        >

                                            <?= $userRole === 'admin'
                                                ? 'Admin'
                                                : 'User'
                                            ?>

                                        </span>

                                    </td>



                                    <!-- ==============================
                                         REGISTERED DATE
                                    =============================== -->

                                    <td class="registered-date">

                                        <?= htmlspecialchars(
                                            $user['created_at']
                                        ) ?>

                                    </td>


                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td
                                    colspan="7"
                                    class="user-empty"
                                >

                                    👥

                                    <br><br>

                                    No registered users found.

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>



        <!-- SECURITY MESSAGE -->

        <div class="security-note">

            <span>
                🔒
            </span>

            <span>

                <strong>
                    Security:
                </strong>

                User passwords are not displayed in the
                administrator dashboard. Passwords remain
                stored as secure hashes in the database.

            </span>

        </div>


    </section>



    <!-- =====================================================
         OFFICE MANAGEMENT
    ===================================================== -->

    <div class="section-header">

        <div>

            <h3>
                Office Management
            </h3>

            <p>
                Search, view, edit and manage registered offices
            </p>

        </div>


        <a
            class="btn btn-add"
            href="add_office.php"
        >
            ➕ Add Office
        </a>

    </div>



    <!-- =====================================================
         OFFICE SEARCH
    ===================================================== -->

    <div class="search-area">

        <form
            method="GET"
            action=""
            class="search-form"
        >


            <input
                type="text"
                name="search"
                placeholder="Search office, service, address, email or phone..."
                value="<?= htmlspecialchars($search) ?>"
            >


            <button
                type="submit"
                class="search-button"
            >
                🔍 Search
            </button>


            <?php if (!empty($search)): ?>

                <a
                    href="dashboard.php"
                    class="clear-link"
                >
                    ✖ Clear
                </a>

            <?php endif; ?>


        </form>

    </div>



    <!-- =====================================================
         OFFICE TABLE
    ===================================================== -->

    <div class="table-container">

        <table>


            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Office
                    </th>

                    <th>
                        Service
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Address
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Phone
                    </th>

                    <th>
                        Floor
                    </th>

                    <th>
                        Office No
                    </th>

                    <th>
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ): ?>


                    <?php while (
                        $row = mysqli_fetch_assoc($result)
                    ): ?>


                        <tr>


                            <!-- ID -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['id']
                                ) ?>

                            </td>


                            <!-- OFFICE -->

                            <td class="office-name">

                                <?= htmlspecialchars(
                                    $row['office_name']
                                ) ?>

                            </td>


                            <!-- SERVICE -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['service_name']
                                ) ?>

                            </td>


                            <!-- DESCRIPTION -->

                            <td class="description">

                                <?= htmlspecialchars(
                                    $row['description'] ?? ''
                                ) ?>

                            </td>


                            <!-- ADDRESS -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['address'] ?? ''
                                ) ?>

                            </td>


                            <!-- EMAIL -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['email'] ?? ''
                                ) ?>

                            </td>


                            <!-- PHONE -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['phone'] ?? ''
                                ) ?>

                            </td>


                            <!-- FLOOR -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['floor_number'] ?? ''
                                ) ?>

                            </td>


                            <!-- OFFICE NUMBER -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['office_number'] ?? ''
                                ) ?>

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div
                                    class="action-links"
                                >


                                    <a
                                        class="btn btn-edit"
                                        href="edit_office.php?id=<?= urlencode(
                                            $row['id']
                                        ) ?>"
                                    >
                                        ✏️ Edit
                                    </a>


                                    <a
                                        class="btn btn-delete"
                                        href="delete_office.php?id=<?= urlencode(
                                            $row['id']
                                        ) ?>"
                                        onclick="
                                            return confirm(
                                                'Are you sure you want to delete this office?'
                                            );
                                        "
                                    >
                                        🗑️ Delete
                                    </a>


                                </div>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="10"
                            class="no-data"
                        >

                            No offices found.

                            <br>

                            <a
                                href="add_office.php"
                                style="
                                    color:#006400;
                                    font-weight:bold;
                                    text-decoration:none;
                                "
                            >
                                Add an office now
                            </a>

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>

        </table>

    </div>



    <!-- =====================================================
         LOGOUT
    ===================================================== -->

    <div
        style="
            text-align:right;
            margin-top:25px;
        "
    >

        <a
            class="btn btn-logout"
            href="../logout.php"
        >
            🚪 Logout
        </a>

    </div>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <strong>
        Awi Zone Administrative Office
    </strong>

    <br>

    Injibara, Awi Zone, Amhara Region, Ethiopia

    <br>

    © 2026 Awi Zone Administrative Office Direction
    Indicator. All Rights Reserved.

</footer>


</body>

</html>