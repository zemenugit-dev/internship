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
    <title>Edit Office</title>
    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/*==================== ROOT COLORS ====================*/

:root {

    --primary: #006400;
    --primary-dark: #004d00;
    --primary-light: #008000;

    --gold: #d4af37;
    --gold-light: #f2d675;

    --background: #f3f6f4;
    --card: #ffffff;

    --text: #26332a;
    --muted: #68736b;

    --border: #d9e1db;

    --danger: #b3261e;

    --shadow:
        0 12px 35px rgba(0, 0, 0, 0.09);
}


/*==================== BODY ====================*/

body {

    min-height: 100vh;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    color: var(--text);

    background:
        linear-gradient(
            135deg,
            #eef4ef 0%,
            #f7f9f7 50%,
            #edf3ee 100%
        );

    padding: 45px 20px;

    position: relative;
}


/*==================== BACKGROUND DECORATION ====================*/

body::before {

    content: "";

    position: fixed;

    width: 380px;
    height: 380px;

    top: -190px;
    right: -150px;

    border-radius: 50%;

    background:
        rgba(0, 100, 0, 0.045);

    pointer-events: none;
}

body::after {

    content: "";

    position: fixed;

    width: 300px;
    height: 300px;

    bottom: -150px;
    left: -120px;

    border-radius: 50%;

    background:
        rgba(212, 175, 55, 0.055);

    pointer-events: none;
}


/*==================================================
  MAIN CONTAINER
==================================================*/

.container {

    width: 100%;

    max-width: 850px;

    margin: 0 auto;

    background: var(--card);

    border-radius: 16px;

    overflow: hidden;

    box-shadow: var(--shadow);

    border: 1px solid rgba(0, 100, 0, 0.08);

    animation:
        containerAppear 0.65s ease forwards;

    position: relative;

    z-index: 2;
}


/*==================================================
  TOP ACCENT
==================================================*/

.container::before {

    content: "";

    display: block;

    height: 5px;

    background:
        linear-gradient(
            90deg,
            var(--primary),
            var(--gold),
            var(--primary)
        );
}


/*==================================================
  HEADER
==================================================*/

.container h2 {

    position: relative;

    margin: 0;

    padding: 30px 38px 24px;

    color: var(--primary);

    font-size: 28px;

    font-weight: 700;

    border-bottom: 1px solid #e8ece9;
}


/* Small decorative line */

.container h2::after {

    content: "";

    display: block;

    width: 55px;

    height: 3px;

    margin-top: 10px;

    border-radius: 3px;

    background: var(--gold);
}


/*==================================================
  FORM
==================================================*/

form {

    padding: 32px 38px 38px;

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 22px 24px;
}


/*==================================================
  LABEL
==================================================*/

label {

    display: block;

    margin-bottom: 8px;

    color: #344139;

    font-size: 14px;

    font-weight: 700;

    letter-spacing: 0.1px;
}


/*==================================================
  INPUT + TEXTAREA
==================================================*/

input,
textarea {

    width: 100%;

    border: 1px solid var(--border);

    border-radius: 8px;

    background: #fbfcfb;

    color: #273229;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size: 15px;

    outline: none;

    transition:
        border-color 0.25s ease,
        box-shadow 0.25s ease,
        background 0.25s ease,
        transform 0.2s ease;
}


/* Input */

input {

    height: 48px;

    padding: 0 14px;
}


/* Textarea */

textarea {

    min-height: 125px;

    padding: 13px 14px;

    resize: vertical;

    line-height: 1.6;
}


/* Placeholder */

input::placeholder,
textarea::placeholder {

    color: #9aa49d;
}


/* Hover */

input:hover,
textarea:hover {

    border-color: #aebbb1;

    background: #ffffff;
}


/* Focus */

input:focus,
textarea:focus {

    background: #ffffff;

    border-color: var(--primary);

    box-shadow:
        0 0 0 3px
        rgba(0, 100, 0, 0.09);

    transform: translateY(-1px);
}


/*==================================================
  DESCRIPTION FULL WIDTH
==================================================*/

textarea[name="description"] {

    grid-column: 1 / -1;
}


/*==================================================
  ADDRESS FULL WIDTH
==================================================*/

input[name="address"] {

    grid-column: 1 / -1;
}


/*==================================================
  BUTTON
==================================================*/

button {

    grid-column: 1 / -1;

    width: 100%;

    min-height: 50px;

    margin-top: 8px;

    border: none;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            var(--primary),
            var(--primary-light)
        );

    color: #ffffff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size: 16px;

    font-weight: 700;

    letter-spacing: 0.2px;

    cursor: pointer;

    box-shadow:
        0 5px 14px
        rgba(0, 100, 0, 0.18);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        background 0.25s ease;
}


/* Button hover */

button:hover {

    background:
        linear-gradient(
            135deg,
            #008000,
            #009900
        );

    transform:
        translateY(-2px);

    box-shadow:
        0 8px 20px
        rgba(0, 100, 0, 0.25);
}


/* Button click */

button:active {

    transform:
        translateY(0);

    box-shadow:
        0 3px 8px
        rgba(0, 100, 0, 0.18);
}


/*==================================================
  FORM GROUP VISUAL SEPARATION
==================================================*/

form > label {

    position: relative;
}


/*==================================================
  ANIMATION
==================================================*/

@keyframes containerAppear {

    from {

        opacity: 0;

        transform:
            translateY(25px)
            scale(0.985);
    }

    to {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);
    }
}


/*==================================================
  TABLET
==================================================*/

@media (max-width: 750px) {

    body {

        padding:
            25px 15px;
    }

    .container {

        max-width: 650px;

        border-radius: 13px;
    }

    .container h2 {

        padding:
            25px 25px 20px;

        font-size: 25px;
    }

    form {

        padding:
            25px;

        grid-template-columns: 1fr;

        gap: 18px;
    }

    textarea[name="description"],
    input[name="address"] {

        grid-column: auto;
    }

    button {

        grid-column: auto;
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

        border-radius: 11px;
    }

    .container h2 {

        padding:
            22px 20px 18px;

        font-size: 22px;
    }

    form {

        padding:
            20px;

        gap: 16px;
    }

    label {

        font-size: 13px;
    }

    input {

        height: 46px;

        font-size: 14px;
    }

    textarea {

        min-height: 110px;

        font-size: 14px;
    }

    button {

        min-height: 48px;

        font-size: 15px;
    }
}


/*==================================================
  ACCESSIBILITY
==================================================*/

button:focus-visible,
input:focus-visible,
textarea:focus-visible {

    outline:
        3px solid
        rgba(212, 175, 55, 0.45);

    outline-offset: 2px;
}


/*==================================================
  REDUCED MOTION
==================================================*/

@media (prefers-reduced-motion: reduce) {

    * {

        animation-duration: 0.01ms !important;

        animation-iteration-count: 1 !important;

        transition-duration: 0.01ms !important;

        scroll-behavior: auto !important;
    }
}
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Office</h2>
    <form method="POST">
        <label>Office Name</label>
        <input type="text" name="office_name" value="<?= htmlspecialchars($row['office_name'] ?? '') ?>">

        <label>Service Name</label>
        <input type="text" name="service_name" value="<?= htmlspecialchars($row['service_name'] ?? '') ?>">

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($row['description'] ?? '') ?></textarea>

        <label>Location (Address)</label>
        <input type="text" name="address" value="<?= htmlspecialchars($row['address'] ?? '') ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email'] ?? '') ?>">

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($row['phone'] ?? '') ?>">

        <label>Floor Number</label>
        <input type="text" name="floor_number" value="<?= htmlspecialchars($row['floor_number'] ?? '') ?>">

        <label>Office Number</label>
        <input type="text" name="office_number" value="<?= htmlspecialchars($row['office_number'] ?? '') ?>">

        <button type="submit" name="update">Update Office</button>
    </form>
</div>
    <a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>