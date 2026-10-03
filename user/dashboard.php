<?php
session_start();

// Verify the user is logged in
if (!isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}

include("../database/db.php");

// Get current user information from session
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'user';
$user_id = $_SESSION['user_id'];
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
        User Dashboard | Awi Zone Administrative Office
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

            background:
                linear-gradient(
                    135deg,
                    #f4f7f4 0%,
                    #eef4ef 100%
                );

            color: #26352b;

            min-height: 100vh;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .top-header {

            background: #006400;

            color: white;

            height: 74px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 6%;

            box-shadow:
                0 3px 15px
                rgba(0, 0, 0, 0.15);

            position: sticky;

            top: 0;

            z-index: 1000;
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 13px;
        }


        .brand-icon {

            width: 44px;

            height: 44px;

            border-radius: 50%;

            background: rgba(255,255,255,0.15);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 23px;

            border:
                1px solid
                rgba(255,255,255,0.35);
        }


        .brand-text h1 {

            font-size: 17px;

            font-weight: 700;

            letter-spacing: 0.2px;
        }


        .brand-text span {

            display: block;

            margin-top: 3px;

            font-size: 11px;

            opacity: 0.82;

            letter-spacing: 0.4px;
        }


        .user-mini {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .user-avatar {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            background: white;

            color: #006400;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 16px;

            font-weight: bold;
        }


        .user-mini-info {

            text-align: right;
        }


        .user-mini-info strong {

            display: block;

            font-size: 13px;
        }


        .user-mini-info small {

            font-size: 10px;

            opacity: 0.75;

            text-transform: uppercase;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .main-content {

            width: 90%;

            max-width: 1150px;

            margin: 0 auto;

            padding: 42px 0 60px;
        }


        /* =====================================================
           WELCOME SECTION
        ===================================================== */

        .welcome-card {

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(
                    120deg,
                    #006400,
                    #087a2f
                );

            color: white;

            border-radius: 18px;

            padding: 38px 42px;

            margin-bottom: 28px;

            box-shadow:
                0 12px 30px
                rgba(0, 80, 0, 0.18);

            animation: fadeUp 0.6s ease;
        }


        .welcome-card::before {

            content: "";

            position: absolute;

            width: 210px;

            height: 210px;

            border-radius: 50%;

            background:
                rgba(255,255,255,0.06);

            right: -70px;

            top: -80px;
        }


        .welcome-card::after {

            content: "";

            position: absolute;

            width: 140px;

            height: 140px;

            border-radius: 50%;

            background:
                rgba(255,255,255,0.04);

            right: 100px;

            bottom: -80px;
        }


        .welcome-content {

            position: relative;

            z-index: 2;
        }


        .welcome-label {

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 1.5px;

            opacity: 0.78;

            margin-bottom: 9px;
        }


        .welcome-card h2 {

            font-size: 31px;

            margin-bottom: 10px;

            line-height: 1.2;
        }


        .welcome-card p {

            max-width: 650px;

            font-size: 14px;

            line-height: 1.7;

            opacity: 0.88;
        }


        .welcome-badge {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            margin-top: 18px;

            padding: 7px 13px;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.13);

            border:
                1px solid
                rgba(255,255,255,0.2);

            font-size: 12px;
        }


        .online-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #8ff5a8;

            box-shadow:
                0 0 8px
                rgba(143,245,168,0.8);
        }


        /* =====================================================
           SECTION TITLE
        ===================================================== */

        .section-title {

            margin: 32px 0 17px;
        }


        .section-title h3 {

            font-size: 19px;

            color: #20352a;
        }


        .section-title p {

            font-size: 12px;

            color: #758178;

            margin-top: 4px;
        }


        /* =====================================================
           QUICK ACTIONS
        ===================================================== */

        .action-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;
        }


        .action-card {

            position: relative;

            background: white;

            border-radius: 15px;

            padding: 25px;

            text-decoration: none;

            color: inherit;

            border:
                1px solid
                #e3e9e4;

            box-shadow:
                0 5px 18px
                rgba(0, 0, 0, 0.05);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;

            animation: fadeUp 0.7s ease;
        }


        .action-card:hover {

            transform: translateY(-5px);

            border-color: #a8c9ae;

            box-shadow:
                0 12px 25px
                rgba(0, 70, 0, 0.10);
        }


        .action-icon {

            width: 52px;

            height: 52px;

            border-radius: 13px;

            background: #edf7ef;

            color: #006400;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;

            margin-bottom: 18px;
        }


        .action-card h4 {

            font-size: 17px;

            margin-bottom: 7px;

            color: #26382c;
        }


        .action-card p {

            font-size: 13px;

            color: #78837b;

            line-height: 1.6;

            margin-bottom: 17px;
        }


        .action-link {

            color: #006400;

            font-size: 12px;

            font-weight: bold;

            display: inline-flex;

            align-items: center;

            gap: 5px;
        }


        /* =====================================================
           ACCOUNT INFORMATION
        ===================================================== */

        .account-card {

            background: white;

            border-radius: 15px;

            border:
                1px solid
                #e3e9e4;

            box-shadow:
                0 5px 18px
                rgba(0,0,0,0.05);

            overflow: hidden;

            animation: fadeUp 0.8s ease;
        }


        .account-header {

            padding: 18px 22px;

            border-bottom:
                1px solid
                #edf0ed;

            display: flex;

            align-items: center;

            gap: 10px;
        }


        .account-header-icon {

            color: #006400;

            font-size: 18px;
        }


        .account-header h4 {

            font-size: 15px;

            color: #26382c;
        }


        .account-body {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);
        }


        .account-item {

            padding: 20px 22px;

            border-right:
                1px solid
                #edf0ed;
        }


        .account-item:last-child {

            border-right: none;
        }


        .account-label {

            display: block;

            color: #8a938c;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 0.6px;

            margin-bottom: 7px;
        }


        .account-value {

            color: #26382c;

            font-size: 14px;

            font-weight: bold;
        }


        .role-badge {

            display: inline-block;

            padding: 5px 10px;

            border-radius: 15px;

            background: #edf7ef;

            color: #006400;

            font-size: 11px;

            font-weight: bold;

            text-transform: capitalize;
        }


        /* =====================================================
           INFORMATION BOX
        ===================================================== */

        .info-box {

            margin-top: 22px;

            padding: 20px 22px;

            background: #f0f7f1;

            border-left:
                4px solid
                #006400;

            border-radius: 8px;

            display: flex;

            align-items: flex-start;

            gap: 13px;
        }


        .info-icon {

            font-size: 19px;

            color: #006400;
        }


        .info-box h4 {

            font-size: 14px;

            color: #24502f;

            margin-bottom: 5px;
        }


        .info-box p {

            color: #68766d;

            font-size: 12px;

            line-height: 1.6;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {

            text-align: center;

            padding: 22px 15px;

            border-top:
                1px solid
                #e0e6e1;

            background: white;

            color: #7b857e;

            font-size: 11px;

            line-height: 1.7;
        }


        footer strong {

            color: #006400;
        }


        /* =====================================================
           ANIMATION
        ===================================================== */

        @keyframes fadeUp {

            from {

                opacity: 0;

                transform:
                    translateY(15px);
            }

            to {

                opacity: 1;

                transform:
                    translateY(0);
            }
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 700px) {

            .top-header {

                height: auto;

                min-height: 68px;

                padding: 12px 5%;
            }


            .brand-text h1 {

                font-size: 14px;
            }


            .brand-text span {

                font-size: 9px;
            }


            .user-mini-info {

                display: none;
            }


            .welcome-card {

                padding: 30px 25px;
            }


            .welcome-card h2 {

                font-size: 25px;
            }


            .action-grid {

                grid-template-columns: 1fr;
            }


            .account-body {

                grid-template-columns: 1fr;
            }


            .account-item {

                border-right: none;

                border-bottom:
                    1px solid
                    #edf0ed;
            }


            .account-item:last-child {

                border-bottom: none;
            }
        }


        @media (max-width: 430px) {

            .main-content {

                width: 92%;

                padding-top: 25px;
            }


            .brand-icon {

                width: 36px;

                height: 36px;

                font-size: 18px;
            }


            .brand-text h1 {

                font-size: 12px;
            }


            .welcome-card h2 {

                font-size: 22px;
            }


            .welcome-card p {

                font-size: 12px;
            }


            .action-card {

                padding: 21px;
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


    <div class="user-mini">

        <div class="user-mini-info">

            <strong>
                <?= htmlspecialchars($username) ?>
            </strong>

            <small>
                <?= htmlspecialchars($role) ?>
            </small>

        </div>


        <div class="user-avatar">

            <?= strtoupper(
                htmlspecialchars(
                    substr($username, 0, 1)
                )
            ) ?>

        </div>

    </div>


</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main-content">


    <!-- =====================================================
         WELCOME CARD
    ===================================================== -->

    <section class="welcome-card">

        <div class="welcome-content">

            <div class="welcome-label">
                User Dashboard
            </div>


            <h2>
                Welcome, <?= htmlspecialchars($username) ?>!
            </h2>


            <p>
                Welcome to the Awi Zone Administrative Office
                Direction and Information System. You can search
                for government offices and easily find information
                about their services and locations.
            </p>


            <div class="welcome-badge">

                <span class="online-dot"></span>

                Account Active

            </div>

        </div>

    </section>



    <!-- =====================================================
         QUICK ACTIONS
    ===================================================== -->

    <div class="section-title">

        <h3>
            Quick Actions
        </h3>

        <p>
            Access the main services available to you
        </p>

    </div>


    <div class="action-grid">


        <!-- SEARCH OFFICE -->

        <a
            href="search.php"
            class="action-card"
        >

            <div class="action-icon">
                🔍
            </div>


            <h4>
                Search Office
            </h4>


            <p>
                Find a government office by service,
                office name, or other available information.
            </p>


            <span class="action-link">
                Search offices →
            </span>

        </a>



        <!-- LOGOUT -->

        <a
            href="../logout.php"
            class="action-card"
        >

            <div
                class="action-icon"
                style="
                    background:#fff0f0;
                    color:#b52b2b;
                "
            >
                🚪
            </div>


            <h4>
                Logout
            </h4>


            <p>
                Securely sign out of your account when
                you have finished using the system.
            </p>


            <span
                class="action-link"
                style="color:#b52b2b;"
            >
                Sign out →
            </span>

        </a>


    </div>



    <!-- =====================================================
         ACCOUNT INFORMATION
    ===================================================== -->

    <div class="section-title">

        <h3>
            Account Information
        </h3>

        <p>
            Basic information about your current session
        </p>

    </div>


    <div class="account-card">


        <div class="account-header">

            <span class="account-header-icon">
                👤
            </span>

            <h4>
                Current Account
            </h4>

        </div>


        <div class="account-body">


            <!-- USER ID -->

            <div class="account-item">

                <span class="account-label">
                    User ID
                </span>

                <span class="account-value">

                    #
                    <?= htmlspecialchars($user_id) ?>

                </span>

            </div>


            <!-- USERNAME -->

            <div class="account-item">

                <span class="account-label">
                    Username
                </span>

                <span class="account-value">

                    <?= htmlspecialchars($username) ?>

                </span>

            </div>


            <!-- ROLE -->

            <div class="account-item">

                <span class="account-label">
                    Account Role
                </span>

                <span class="role-badge">

                    <?= htmlspecialchars($role) ?>

                </span>

            </div>


        </div>

    </div>



    <!-- =====================================================
         INFORMATION
    ===================================================== -->

    <div class="info-box">

        <div class="info-icon">
            ℹ️
        </div>


        <div>

            <h4>
                About this system
            </h4>


            <p>
                This system helps users identify the appropriate
                Awi Zone government office for a requested service,
                view office information, and access its location.
            </p>

        </div>

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