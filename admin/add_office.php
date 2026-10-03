<?php
session_start();

// Verify the user is logged in AND holds administrator privileges
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../database/db.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $office_name   = trim($_POST['office_name']);
    $service_name  = trim($_POST['service_name']);
    $description   = trim($_POST['description']);
    $address       = trim($_POST['address']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);
    $floor_number  = trim($_POST['floor_number']);
    $office_number = trim($_POST['office_number']);

    $stmt = $conn->prepare("INSERT INTO offices (office_name, service_name, description, address, email, phone, floor_number, office_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $office_name, $service_name, $description, $address, $email, $phone, $floor_number, $office_number);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Error adding office: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Office</title>
    <style>
     ```css
/*==================================================
  AWI ZONE ADMINISTRATIVE OFFICE
  ADD GOVERNMENT OFFICE
  ADVANCED PROFESSIONAL UI
==================================================*/


/*==================================================
  RESET
==================================================*/

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/*==================================================
  ROOT COLORS
==================================================*/

:root {

    --green-dark: #003d00;
    --green: #006400;
    --green-light: #008000;

    --gold: #d4af37;
    --gold-light: #f4d76a;

    --white: #ffffff;

    --text: #26332a;
    --muted: #69746c;

    --border: rgba(255, 255, 255, 0.45);

    --shadow:
        0 25px 60px rgba(0, 0, 0, 0.25);
}


/*==================================================
  BODY
==================================================*/

body {

    min-height: 100vh;

    display: flex;

    justify-content: center;
    align-items: center;

    padding: 50px 20px;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    color: var(--text);

    overflow-x: hidden;

    position: relative;

    /*
      Professional animated green background
    */

    background:
        radial-gradient(
            circle at 15% 20%,
            rgba(212, 175, 55, 0.20),
            transparent 25%
        ),
        radial-gradient(
            circle at 85% 80%,
            rgba(0, 255, 120, 0.14),
            transparent 28%
        ),
        linear-gradient(
            135deg,
            #002d00,
            #004d00,
            #006400,
            #003d00
        );

    background-size:
        auto,
        auto,
        400% 400%;

    animation:
        backgroundMove 15s ease infinite;
}


/*==================================================
  ANIMATED BACKGROUND GLOW
==================================================*/

body::before {

    content: "";

    position: fixed;

    width: 500px;
    height: 500px;

    top: -230px;
    left: -180px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(255, 215, 0, 0.18),
            transparent 68%
        );

    filter: blur(4px);

    animation:
        glowMoveOne 10s ease-in-out infinite;

    pointer-events: none;

    z-index: 0;
}


body::after {

    content: "";

    position: fixed;

    width: 600px;
    height: 600px;

    right: -280px;
    bottom: -300px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(0, 255, 100, 0.14),
            transparent 65%
        );

    animation:
        glowMoveTwo 13s ease-in-out infinite;

    pointer-events: none;

    z-index: 0;
}


/*==================================================
  FLOATING BACKGROUND PATTERN
==================================================*/

.container {

    position: relative;

    z-index: 5;

    width: 100%;

    max-width: 850px;

    background:
        rgba(255, 255, 255, 0.96);

    border-radius: 20px;

    overflow: hidden;

    box-shadow:
        0 30px 80px rgba(0, 0, 0, 0.32);

    border:
        1px solid
        rgba(255, 255, 255, 0.7);

    backdrop-filter:
        blur(15px);

    -webkit-backdrop-filter:
        blur(15px);

    animation:
        containerEntrance 0.9s
        cubic-bezier(.2,.8,.2,1)
        forwards;
}


/*==================================================
  TOP GOLD LINE
==================================================*/

.container::before {

    content: "";

    display: block;

    height: 6px;

    background:
        linear-gradient(
            90deg,
            #004d00,
            #d4af37,
            #008000,
            #d4af37,
            #004d00
        );

    background-size: 300% 100%;

    animation:
        goldFlow 5s linear infinite;
}


/*==================================================
  HEADER
==================================================*/

.container h2 {

    position: relative;

    padding:
        30px
        38px
        24px;

    color: var(--green);

    font-size: 29px;

    font-weight: 700;

    letter-spacing: 0.2px;

    border-bottom:
        1px solid #e7ebe8;

    background:
        linear-gradient(
            to right,
            #ffffff,
            #f8fbf8
        );
}


/* Decorative line */

.container h2::after {

    content: "";

    display: block;

    width: 65px;

    height: 4px;

    margin-top: 11px;

    border-radius: 5px;

    background:
        linear-gradient(
            90deg,
            var(--green),
            var(--gold)
        );

    animation:
        lineGrow 1s ease forwards;
}


/*==================================================
  MESSAGE
==================================================*/

.error,
.success {

    margin:
        22px
        38px
        0;

    padding:
        13px
        16px;

    border-radius: 8px;

    font-size: 14px;

    font-weight: 600;

    animation:
        messageAppear 0.5s ease;
}


.error {

    color: #a30000;

    background:
        #fff1f1;

    border-left:
        4px solid
        #c62828;
}


.success {

    color: #075f22;

    background:
        #edf9f0;

    border-left:
        4px solid
        #16833a;
}


/*==================================================
  FORM
==================================================*/

form {

    padding:
        32px 38px 30px;

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap:
        21px 25px;
}


/*==================================================
  LABEL
==================================================*/

label {

    display: block;

    margin-bottom: 8px;

    color: #334038;

    font-size: 14px;

    font-weight: 700;
}


/*==================================================
  INPUT + TEXTAREA
==================================================*/

input,
textarea {

    width: 100%;

    border:
        1px solid
        #d2dbd4;

    border-radius: 9px;

    background:
        #fafd fa;

    color: #26332a;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size: 15px;

    outline: none;

    transition:
        border-color 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease,
        transform 0.25s ease;
}


/* Fix background */

input,
textarea {
    background: #fbfdfb;
}


/*==================================================
  INPUT
==================================================*/

input {

    height: 50px;

    padding:
        0 15px;
}


/*==================================================
  TEXTAREA
==================================================*/

textarea {

    min-height: 125px;

    padding:
        13px 15px;

    resize: vertical;

    line-height: 1.6;
}


/*==================================================
  PLACEHOLDER
==================================================*/

input::placeholder,
textarea::placeholder {

    color: #9aa49d;
}


/*==================================================
  HOVER
==================================================*/

input:hover,
textarea:hover {

    border-color:
        #a4b3a8;

    background:
        #ffffff;
}


/*==================================================
  FOCUS
==================================================*/

input:focus,
textarea:focus {

    border-color:
        var(--green);

    background:
        #ffffff;

    box-shadow:
        0 0 0 4px
        rgba(0, 100, 0, 0.09),
        0 5px 15px
        rgba(0, 100, 0, 0.06);

    transform:
        translateY(-2px);
}


/*==================================================
  FULL WIDTH FIELDS
==================================================*/

textarea[name="description"] {

    grid-column:
        1 / -1;
}


input[name="address"] {

    grid-column:
        1 / -1;
}


/*==================================================
  ADD OFFICE BUTTON
==================================================*/

button {

    grid-column:
        1 / -1;

    position: relative;

    width: 100%;

    height: 53px;

    margin-top: 5px;

    overflow: hidden;

    border: none;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #004d00,
            #006400,
            #008000
        );

    background-size:
        200% 200%;

    color: #ffffff;

    font-size: 16px;

    font-weight: 700;

    letter-spacing: 0.3px;

    cursor: pointer;

    box-shadow:
        0 7px 18px
        rgba(0, 100, 0, 0.25);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        background-position 0.5s ease;
}


/* Animated button background */

button:hover {

    background-position:
        100% 0;

    transform:
        translateY(-3px);

    box-shadow:
        0 11px 25px
        rgba(0, 100, 0, 0.30);
}


/* Button press */

button:active {

    transform:
        translateY(0);

    box-shadow:
        0 4px 10px
        rgba(0, 100, 0, 0.20);
}


/*==================================================
  BUTTON SHINE
==================================================*/

button::before {

    content: "";

    position: absolute;

    top: 0;

    left: -120%;

    width: 80%;

    height: 100%;

    background:
        linear-gradient(
            100deg,
            transparent,
            rgba(255, 255, 255, 0.25),
            transparent
        );

    transform:
        skewX(-20deg);

    transition:
        left 0.7s ease;
}


button:hover::before {

    left: 130%;
}


/*==================================================
  BACK LINK
==================================================*/

.container > a {

    display: block;

    margin:
        0 38px
        32px;

    color:
        var(--green);

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition:
        color 0.25s ease,
        transform 0.25s ease;
}


.container > a:hover {

    color:
        var(--green-light);

    transform:
        translateX(-4px);
}


/*==================================================
  ANIMATIONS
==================================================*/


@keyframes backgroundMove {

    0% {
        background-position:
            0% 50%;
    }

    50% {
        background-position:
            100% 50%;
    }

    100% {
        background-position:
            0% 50%;
    }
}


@keyframes glowMoveOne {

    0%,
    100% {
        transform:
            translate(0, 0)
            scale(1);
    }

    50% {
        transform:
            translate(100px, 70px)
            scale(1.2);
    }
}


@keyframes glowMoveTwo {

    0%,
    100% {
        transform:
            translate(0, 0)
            scale(1);
    }

    50% {
        transform:
            translate(-100px, -80px)
            scale(1.15);
    }
}


@keyframes goldFlow {

    0% {
        background-position:
            0% 50%;
    }

    100% {
        background-position:
            300% 50%;
    }
}


@keyframes containerEntrance {

    0% {

        opacity: 0;

        transform:
            translateY(45px)
            scale(0.96);
    }

    100% {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);
    }
}


@keyframes lineGrow {

    from {
        width: 0;
    }

    to {
        width: 65px;
    }
}


@keyframes messageAppear {

    from {

        opacity: 0;

        transform:
            translateY(-8px);
    }

    to {

        opacity: 1;

        transform:
            translateY(0);
    }
}


/*==================================================
  TABLET
==================================================*/

@media (max-width: 750px) {

    body {

        padding:
            30px 15px;
    }


    .container {

        max-width:
            650px;

        border-radius:
            15px;
    }


    .container h2 {

        padding:
            25px;

        font-size:
            25px;
    }


    form {

        padding:
            25px;

        grid-template-columns:
            1fr;

        gap:
            18px;
    }


    textarea[name="description"],
    input[name="address"],
    button {

        grid-column:
            auto;
    }


    .error,
    .success {

        margin:
            20px 25px 0;
    }


    .container > a {

        margin:
            0 25px 25px;
    }
}


/*==================================================
  MOBILE
==================================================*/

@media (max-width: 480px) {

    body {

        padding:
            15px 10px;
    }


    .container {

        border-radius:
            13px;
    }


    .container h2 {

        padding:
            22px 20px 18px;

        font-size:
            22px;
    }


    form {

        padding:
            20px;

        gap:
            16px;
    }


    .error,
    .success {

        margin:
            17px 20px 0;

        font-size:
            13px;
    }


    label {

        font-size:
            13px;
    }


    input {

        height:
            47px;

        font-size:
            14px;
    }


    textarea {

        min-height:
            110px;

        font-size:
            14px;
    }


    button {

        height:
            49px;

        font-size:
            15px;
    }


    .container > a {

        margin:
            0 20px 22px;

        font-size:
            13px;
    }
}


/*==================================================
  ACCESSIBILITY
==================================================*/

input:focus-visible,
textarea:focus-visible,
button:focus-visible,
a:focus-visible {

    outline:
        3px solid
        rgba(212, 175, 55, 0.5);

    outline-offset:
        3px;
}


/*==================================================
  REDUCED MOTION
==================================================*/

@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {

        animation-duration:
            0.01ms !important;

        animation-iteration-count:
            1 !important;

        transition-duration:
            0.01ms !important;
    }
}
```

    </style>
</head>
<body>
<div class="container">
    <h2>Add Government Office</h2>
    <?php if ($message): ?>
        <p class="<?= strpos($message, 'Error') === false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Office Name</label>
        <input type="text" name="office_name" required>

        <label>Service Name</label>
        <input type="text" name="service_name" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Location (Address)</label>
        <input type="text" name="address">

        <label>Email</label>
        <input type="email" name="email">

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Floor Number</label>
        <input type="text" name="floor_number">

        <label>Office Number</label>
        <input type="text" name="office_number">

        <button type="submit" name="add">Add Office</button>
    </form>
    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>