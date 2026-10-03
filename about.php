
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us - Awi Zone Administrative Office Direction Indicator</title>

    <link rel="stylesheet" href="css/style.css">


    <style>

        /* =====================================================
           DEVELOPERS SECTION
           ===================================================== */

        .developers-section {

            width: 90%;
            max-width: 1150px;

            margin: 55px auto;

            padding: 50px 35px;

            box-sizing: border-box;

            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,0.98),
                    rgba(238,249,243,0.98)
                );

            border-radius: 24px;

            border-top: 5px solid #006400;

            box-shadow:
                0 20px 50px rgba(0, 55, 28, 0.20);

            position: relative;

            overflow: hidden;
        }


        /* =====================================================
           ANIMATED TOP GOLD LINE
           ===================================================== */

        .developers-section::before {

            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 5px;

            background:
                linear-gradient(
                    90deg,
                    #006400,
                    #d4af37,
                    #ffe27a,
                    #d4af37,
                    #006400
                );

            background-size: 300% 100%;

            animation:
                developerGoldLine
                5s
                linear
                infinite;
        }


        /* =====================================================
           DECORATIVE BACKGROUND GLOW
           ===================================================== */

        .developers-section::after {

            content: "";

            position: absolute;

            width: 260px;
            height: 260px;

            right: -120px;
            bottom: -120px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(212,175,55,0.18),
                    transparent 70%
                );

            animation:
                developerGlow
                7s
                ease-in-out
                infinite;

            pointer-events: none;
        }


        /* =====================================================
           TITLE
           ===================================================== */

        .developers-section h2 {

            text-align: center;

            color: #006400;

            font-size: 31px;

            margin: 0 0 10px;

            position: relative;

            z-index: 2;
        }


        .developers-section h2::after {

            content: "";

            display: block;

            width: 85px;
            height: 4px;

            margin: 13px auto 28px;

            background:
                linear-gradient(
                    90deg,
                    #006400,
                    #d4af37
                );

            border-radius: 20px;
        }


        /* =====================================================
           INTRODUCTION
           ===================================================== */

        .developers-intro {

            max-width: 780px;

            margin: 0 auto 42px;

            text-align: center;

            color: #53695c;

            font-size: 16px;

            line-height: 1.8;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           DEVELOPERS CONTAINER
           
           IMPORTANT:
           Each developer is an independent card.
           ===================================================== */

        .developers-container {

            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(260px, 1fr)
                );

            gap: 28px;

            width: 100%;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           EACH DEVELOPER = SEPARATE CARD
           ===================================================== */

        .developer-card {

            width: 100%;

            min-height: 245px;

            padding: 30px 22px;

            box-sizing: border-box;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            text-align: center;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #eef9f2
                );

            border-radius: 18px;

            border: 1px solid #c7dfcf;

            border-bottom: 4px solid #006400;

            box-shadow:
                0 10px 28px rgba(0, 70, 35, 0.13);

            position: relative;

            overflow: hidden;

            /* Start from right side */

            opacity: 0;

            transform:
                translateX(150px)
                scale(0.94);

            animation:
                developerEnter
                1s
                cubic-bezier(0.22, 1, 0.36, 1)
                forwards;

            transition:
                transform 0.4s ease,
                box-shadow 0.4s ease,
                border-color 0.4s ease;
        }


        /* =====================================================
           STAGGERED RIGHT → LEFT ANIMATION
           ===================================================== */

        .developer-card:nth-child(1) {

            animation-delay: 0.15s;
        }


        .developer-card:nth-child(2) {

            animation-delay: 0.45s;
        }


        .developer-card:nth-child(3) {

            animation-delay: 0.75s;
        }


        .developer-card:nth-child(4) {

            animation-delay: 1.05s;
        }


        .developer-card:nth-child(5) {

            animation-delay: 1.35s;
        }


        .developer-card:nth-child(6) {

            animation-delay: 1.65s;
        }


        /* =====================================================
           CARD LIGHT EFFECT
           ===================================================== */

        .developer-card::before {

            content: "";

            position: absolute;

            width: 150px;
            height: 150px;

            top: -80px;
            right: -80px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(212,175,55,0.25),
                    transparent 70%
                );

            transition:
                transform 0.6s ease;
        }


        /* =====================================================
           CARD BOTTOM ANIMATED LINE
           ===================================================== */

        .developer-card::after {

            content: "";

            position: absolute;

            left: 0;
            bottom: 0;

            width: 0;
            height: 4px;

            background:
                linear-gradient(
                    90deg,
                    #006400,
                    #d4af37,
                    #f5d76e
                );

            transition:
                width 0.5s ease;
        }


        /* =====================================================
           CARD HOVER
           ===================================================== */

        .developer-card:hover {

            transform:
                translateY(-10px)
                scale(1.025);

            border-color: #d4af37;

            box-shadow:
                0 22px 45px rgba(0, 70, 35, 0.25);
        }


        .developer-card:hover::before {

            transform:
                scale(2.2);
        }


        .developer-card:hover::after {

            width: 100%;
        }


        /* =====================================================
           DEVELOPER ICON
           ===================================================== */

        .developer-icon {

            width: 72px;
            height: 72px;

            margin-bottom: 17px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #003d20,
                    #008b45
                );

            color: #ffffff;

            font-size: 29px;

            font-weight: 700;

            border: 3px solid #d4af37;

            box-shadow:
                0 8px 20px rgba(0, 90, 40, 0.28);

            position: relative;

            z-index: 2;

            transition:
                transform 0.6s ease,
                box-shadow 0.4s ease;
        }


        .developer-card:hover .developer-icon {

            transform:
                rotateY(360deg)
                scale(1.08);

            box-shadow:
                0 12px 28px rgba(0, 90, 40, 0.38);
        }


        /* =====================================================
           DEVELOPER NAME
           ===================================================== */

        .developer-card h3 {

            margin: 0 0 10px;

            color: #00552b;

            font-size: 20px;

            font-weight: 700;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           DEVELOPER ID
           ===================================================== */

        .developer-id {

            display: inline-block;

            padding: 7px 16px;

            margin-top: 3px;

            color: #705b00;

            background:
                linear-gradient(
                    135deg,
                    #fff9df,
                    #fff3b8
                );

            border: 1px solid #dfc65e;

            border-radius: 30px;

            font-size: 14px;

            font-weight: 700;

            position: relative;

            z-index: 2;

            box-shadow:
                0 4px 10px rgba(150,120,0,0.10);
        }


        /* =====================================================
           DEVELOPER ROLE
           ===================================================== */

        .developer-role {

            margin-top: 15px;

            color: #61756a;

            font-size: 14px;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           RIGHT → LEFT ENTRANCE
           ===================================================== */

        @keyframes developerEnter {

            0% {

                opacity: 0;

                transform:
                    translateX(150px)
                    scale(0.94);
            }

            65% {

                opacity: 1;

                transform:
                    translateX(-10px)
                    scale(1.01);
            }

            100% {

                opacity: 1;

                transform:
                    translateX(0)
                    scale(1);
            }
        }


        /* =====================================================
           GOLD LINE ANIMATION
           ===================================================== */

        @keyframes developerGoldLine {

            0% {

                background-position:
                    0% 50%;
            }

            100% {

                background-position:
                    300% 50%;
            }
        }


        /* =====================================================
           BACKGROUND GLOW ANIMATION
           ===================================================== */

        @keyframes developerGlow {

            0%,
            100% {

                transform:
                    translate(0,0)
                    scale(1);
            }

            50% {

                transform:
                    translate(-40px,-30px)
                    scale(1.2);
            }
        }


        /* =====================================================
           TABLET
           ===================================================== */

        @media (max-width: 800px) {

            .developers-section {

                width: 92%;

                padding:
                    42px 25px;
            }


            .developers-container {

                grid-template-columns:
                    repeat(
                        2,
                        minmax(0,1fr)
                    );

                gap: 22px;
            }
        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media (max-width: 560px) {

            .developers-section {

                width: 94%;

                padding:
                    38px 18px;

                border-radius: 18px;
            }


            .developers-section h2 {

                font-size: 25px;
            }


            .developers-intro {

                font-size: 14px;

                margin-bottom: 30px;
            }


            .developers-container {

                grid-template-columns: 1fr;

                gap: 22px;
            }


            .developer-card {

                min-height: 225px;

                max-width: 380px;

                margin: 0 auto;
            }
        }


        /* =====================================================
           REDUCE MOTION
           ===================================================== */

        @media (prefers-reduced-motion: reduce) {

            .developer-card {

                animation: none;

                opacity: 1;

                transform: none;
            }


            .developers-section::before,
            .developers-section::after {

                animation: none;
            }
        }

    </style>

</head>


<body>


<!-- ================= HEADER ================= -->

<header>

    <div class="logo">

        <h1>
            Awi Zone Administrative Office Direction Indicator
        </h1>

    </div>


    <nav>

        <ul>

            <li>
                <a href="index.php">
                    Home
                </a>
            </li>

            <li>
                <a href="login.php">
                     Login
                </a>
            </li>

        </ul>

    </nav>

</header>


<!-- ================= ABOUT ================= -->

<section class="about">

    <h2>About the Website</h2>

    <p>

        The <strong>Awi Zone Administrative Office Direction Indicator</strong>
        is a web-based information system developed to help citizens quickly
        identify the correct government office for the public service they need.

    </p>


    <p>

        The system allows users to search government offices, view the services
        provided, obtain contact information, and locate offices within
        Awi Zone Administration.

    </p>

</section>


<!-- ================= VISION ================= -->

<section class="about">

    <h2>Vision</h2>

    <p>

        To improve access to government services by providing accurate,
        fast, and user-friendly office information for all citizens of
        Awi Zone.

    </p>

</section>


<!-- ================= MISSION ================= -->

<section class="about">

    <h2>Mission</h2>

    <p>

        To help citizens find the correct government office efficiently,
        reduce unnecessary travel, improve service delivery,
        and promote the use of information technology in public administration.

    </p>

</section>


<!-- ================= OBJECTIVES ================= -->

<section class="about">

    <h2>Objectives</h2>

    <ul>

        <li>
            Help citizens locate the correct government office.
        </li>

        <li>
            Provide office contact information.
        </li>

        <li>
            Provide office email addresses.
        </li>

        <li>
            Provide office locations.
        </li>

        <li>
            Improve access to government services.
        </li>

        <li>
            Reduce time spent searching for offices.
        </li>

    </ul>

</section>


<!-- ================= AVAILABLE OFFICES ================= -->

<section class="about">

    <h2>Government Offices Included</h2>

    <ul>

        <li>
            Education Office (ትምህርት መምሪያ)
        </li>

        <li>
            Health Office (ጤና መምሪያ)
        </li>

        <li>
            Trade and Industry Office (ንግድ እና ኢንዱስትሪ መምሪያ)
        </li>

        <li>
            Justice Office (ፍትህ መምሪያ)
        </li>

        <li>
            Women and Social Affairs Office (ሴቶች እና ማህበራዊ ጉዳይ)
        </li>

        <li>
            Civil Service Office (ሲቪል ሰርቪስ)
        </li>

        <li>
            Culture and Tourism Office (ባህል እና ቱሪዝም)
        </li>

        <li>
            Land Administration Office
        </li>

        <li>
            Revenue Office
        </li>

        <li>
            Innovation and Technology Team
        </li>

    </ul>

</section>


<!-- ================= CONTACT ================= -->

<section class="about">

    <h2>Contact Information</h2>

    <table>

        <tr>

            <td>
                <strong>Office</strong>
            </td>

            <td>
                Awi Zone Administration Innovation and Technology Team
            </td>

        </tr>


        <tr>

            <td>
                <strong>Address</strong>
            </td>

            <td>
                Injibara, Awi Zone, Amhara Region, Ethiopia
            </td>

        </tr>


        <tr>

            <td>
                <strong>Email</strong>
            </td>

            <td>
                innovation@awizone.gov.et
            </td>

        </tr>


        <tr>

            <td>
                <strong>Phone</strong>
            </td>

            <td>
                0582270785
            </td>

        </tr>


        <tr>

            <td>
                <strong>Working Days</strong>
            </td>

            <td>
                Monday - Friday
            </td>

        </tr>


        <tr>

            <td>
                <strong>Working Hours</strong>
            </td>

            <td>
                8:30 AM - 5:30 PM
            </td>

        </tr>

    </table>

</section>


<!-- =====================================================
     DEVELOPERS
     ===================================================== -->

<section class="developers-section">


    <h2>
        System Developers
    </h2>


    <p class="developers-intro">

        This system was developed as an information system project
        to improve access to government office information and
        support efficient public service delivery in Awi Zone.

    </p>


    <div class="developers-container">


        <!-- =================================================
             DEVELOPER 1
             ================================================= -->

        <div class="developer-card">

            <div class="developer-icon">
                Z
            </div>

            <h3>
                Zemenu Yehzbalem
            </h3>

            <div class="developer-id">
                ID: 1501227
            </div>

            <div class="developer-role">
                Information Systems Developer
            </div>

        </div>


        <!-- =================================================
             DEVELOPER 2
             ================================================= -->

        <div class="developer-card">

            <div class="developer-icon">
                Y
            </div>

            <h3>
                Yihunie Genetu
            </h3>

            <div class="developer-id">
                ID: 1501538
            </div>

            <div class="developer-role">
                Information Systems Developer
            </div>

        </div>


        <!-- =================================================
             ADD DEVELOPER 3 HERE
             
             Copy ONE complete developer-card when
             you want to add another developer.
             ================================================= -->


        <!--

        <div class="developer-card">

            <div class="developer-icon">
                A
            </div>

            <h3>
                Agerie Tafere
            </h3>

            <div class="developer-id">
                ID: 1500565
            </div>

            <div class="developer-role">
                System Designer
            </div>

        </div>

                -->
 <div class="developer-card">

            <div class="developer-icon">
                A
            </div>

            <h3>
                Agerie Tafere
            </h3>

            <div class="developer-id">
                ID: 1500565
            </div>

            <div class="developer-role">
                System Designer
            </div>

        </div>

    </div>

</section>


<!-- ================= FOOTER ================= -->

<footer>

    <p>

        © 2026 Awi Zone Administrative Office Direction Indicator System

    </p>

</footer>


</body>

</html>

