<?php
require_once __DIR__ . "/../database/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Government Office</title>

<link rel="stylesheet" href="../css/style.css">

<style>

```css
/* =========================================================
   Awi Zone Administrative Office Direction Indicator
   Government Office Search Page
   Professional Green + Gold Design
   ========================================================= */

:root {
    --dark-green: #003d20;
    --deep-green: #00552b;
    --main-green: #006400;
    --bright-green: #078b45;
    --light-green: #eaf7ef;
    --gold: #d4af37;
    --gold-light: #f5d76e;
    --white: #ffffff;
    --text-dark: #173525;
    --text-light: #f4fff8;
    --danger: #c62828;
    --shadow: 0 15px 45px rgba(0, 35, 18, 0.25);
}


/* =========================================================
   PAGE BACKGROUND
   ========================================================= */

body {
    font-family: "Segoe UI", Arial, sans-serif;
    margin: 0;
    padding: 45px 20px 60px;

    min-height: 100vh;

    color: var(--text-dark);

    background:
        radial-gradient(
            circle at 15% 15%,
            rgba(212, 175, 55, 0.18),
            transparent 25%
        ),
        radial-gradient(
            circle at 85% 80%,
            rgba(0, 255, 120, 0.12),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #002d18 0%,
            #00552b 45%,
            #003d20 100%
        );

    background-attachment: fixed;

    position: relative;
    overflow-x: hidden;
}


/* Animated background light */

body::before {
    content: "";
    position: fixed;

    width: 450px;
    height: 450px;

    top: -180px;
    left: -180px;

    background: radial-gradient(
        circle,
        rgba(212, 175, 55, 0.22),
        transparent 70%
    );

    border-radius: 50%;

    animation: floatingGlow 8s ease-in-out infinite;

    pointer-events: none;
}


/* Second background light */

body::after {
    content: "";

    position: fixed;

    width: 500px;
    height: 500px;

    right: -220px;
    bottom: -220px;

    background: radial-gradient(
        circle,
        rgba(0, 255, 128, 0.16),
        transparent 70%
    );

    border-radius: 50%;

    animation: floatingGlow2 10s ease-in-out infinite;

    pointer-events: none;
}


/* =========================================================
   PAGE TITLE
   ========================================================= */

h2 {
    position: relative;

    text-align: center;

    margin: 10px auto 35px;

    color: var(--white);

    font-size: clamp(26px, 4vw, 38px);

    font-weight: 700;

    letter-spacing: 0.5px;

    text-shadow:
        0 3px 15px rgba(0, 0, 0, 0.35);

    animation: titleAppear 0.8s ease forwards;
}


/* Gold line under title */

h2::after {
    content: "";

    display: block;

    width: 90px;
    height: 4px;

    margin: 13px auto 0;

    background: linear-gradient(
        90deg,
        transparent,
        var(--gold),
        var(--gold-light),
        var(--gold),
        transparent
    );

    border-radius: 50px;

    animation: lineGrow 1s ease forwards;
}


/* =========================================================
   SEARCH FORM CONTAINER
   ========================================================= */

form {
    position: relative;

    width: min(850px, 95%);

    margin: 0 auto 45px;

    padding: 28px;

    display: flex;

    align-items: center;

    gap: 12px;

    box-sizing: border-box;

    background: rgba(255, 255, 255, 0.96);

    border: 1px solid rgba(212, 175, 55, 0.45);

    border-radius: 18px;

    box-shadow: var(--shadow);

    animation: formAppear 0.9s ease forwards;

    overflow: hidden;
}


/* Gold top decoration */

form::before {
    content: "";

    position: absolute;

    top: 0;
    left: 0;

    width: 100%;
    height: 5px;

    background: linear-gradient(
        90deg,
        var(--main-green),
        var(--gold),
        var(--gold-light),
        var(--gold),
        var(--main-green)
    );

    background-size: 250% 100%;

    animation: goldMove 4s linear infinite;
}


/* =========================================================
   SEARCH INPUT
   ========================================================= */

input[type="text"] {

    flex: 1;

    min-width: 0;

    height: 58px;

    box-sizing: border-box;

    padding: 0 20px;

    font-family: inherit;

    font-size: 16px;

    font-weight: 500;

    color: var(--text-dark);

    background:
        linear-gradient(
            135deg,
            #f1fff6,
            #e2f6e9
        );

    border: 2px solid #b7dbc4;

    border-radius: 12px;

    outline: none;

    box-shadow:
        inset 0 2px 7px rgba(0, 70, 35, 0.08);

    transition:
        border-color 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease,
        transform 0.3s ease;
}


/* Placeholder */

input[type="text"]::placeholder {
    color: #64806f;
    font-weight: 400;
}


/* Input hover */

input[type="text"]:hover {
    border-color: var(--bright-green);

    background:
        linear-gradient(
            135deg,
            #ffffff,
            #eafaf0
        );
}


/* Input focus */

input[type="text"]:focus {

    border-color: var(--gold);

    background: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 0 0 4px rgba(212, 175, 55, 0.16),
        0 8px 22px rgba(0, 100, 0, 0.14);
}


/* =========================================================
   SEARCH BUTTON
   ========================================================= */

input[type="submit"] {

    position: relative;

    height: 58px;

    padding: 0 28px;

    border: none;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #004d25,
            #00833e
        );

    color: white;

    font-family: inherit;

    font-size: 16px;

    font-weight: 700;

    letter-spacing: 0.3px;

    cursor: pointer;

    box-shadow:
        0 8px 20px rgba(0, 80, 35, 0.25);

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease;

    overflow: hidden;
}


/* Button shine */

input[type="submit"]::before {
    content: "";

    position: absolute;

    top: 0;
    left: -100%;

    width: 70%;
    height: 100%;

    background: linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,0.25),
        transparent
    );

    transform: skewX(-20deg);

    transition: left 0.6s ease;
}


/* Button hover */

input[type="submit"]:hover {

    background:
        linear-gradient(
            135deg,
            #006b32,
            #00a34d
        );

    transform: translateY(-3px);

    box-shadow:
        0 12px 28px rgba(0, 100, 45, 0.35);
}


input[type="submit"]:hover::before {
    left: 130%;
}


/* Button active */

input[type="submit"]:active {
    transform: translateY(0);
}


/* =========================================================
   RESULT CARD
   ========================================================= */

.card {

    position: relative;

    width: min(850px, 92%);

    margin: 0 auto 28px;

    padding: 28px 30px;

    box-sizing: border-box;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,0.98),
            rgba(244,251,247,0.98)
        );

    border-radius: 18px;

    border-left: 6px solid var(--main-green);

    box-shadow: var(--shadow);

    overflow: hidden;

    animation: cardAppear 0.7s ease forwards;

    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease;
}


/* Gold decorative corner */

.card::before {
    content: "";

    position: absolute;

    top: 0;
    right: 0;

    width: 90px;
    height: 90px;

    background:
        linear-gradient(
            135deg,
            transparent 50%,
            rgba(212,175,55,0.20) 50%
        );

    pointer-events: none;
}


/* Animated green line */

.card::after {

    content: "";

    position: absolute;

    left: 0;
    bottom: 0;

    width: 0;
    height: 3px;

    background: linear-gradient(
        90deg,
        var(--main-green),
        var(--gold)
    );

    transition: width 0.5s ease;
}


.card:hover {

    transform: translateY(-7px);

    box-shadow:
        0 22px 50px rgba(0, 50, 25, 0.30);
}


.card:hover::after {
    width: 100%;
}


/* =========================================================
   OFFICE NAME
   ========================================================= */

.card h3 {

    margin: 0 0 20px;

    padding-bottom: 12px;

    color: var(--main-green);

    font-size: 24px;

    font-weight: 700;

    border-bottom: 2px solid #d9ebdf;

    position: relative;
}


.card h3::after {

    content: "";

    position: absolute;

    bottom: -2px;
    left: 0;

    width: 70px;
    height: 3px;

    background: var(--gold);

    border-radius: 5px;
}


/* =========================================================
   INFORMATION TEXT
   ========================================================= */

.card p {

    margin: 12px 0;

    padding: 11px 14px;

    color: #365343;

    line-height: 1.6;

    background: rgba(231, 246, 237, 0.65);

    border-radius: 8px;

    border-left: 3px solid #b9dec7;

    transition:
        background 0.3s ease,
        transform 0.3s ease;
}


.card p:hover {

    background: #e2f5e9;

    transform: translateX(4px);
}


.card p strong {

    color: var(--dark-green);

    font-weight: 700;
}


/* =========================================================
   GOOGLE MAPS BUTTON
   ========================================================= */

.card a {

    display: inline-flex !important;

    align-items: center;

    justify-content: center;

    gap: 8px;

    margin: 8px 0 15px;

    padding: 12px 20px !important;

    background:
        linear-gradient(
            135deg,
            #006400,
            #078b45
        ) !important;

    color: white !important;

    text-decoration: none !important;

    font-weight: 600;

    border-radius: 10px !important;

    box-shadow:
        0 6px 16px rgba(0, 100, 0, 0.20);

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease;
}


.card a:hover {

    background:
        linear-gradient(
            135deg,
            #00833e,
            #00a852
        ) !important;

    transform: translateY(-3px);

    box-shadow:
        0 10px 22px rgba(0, 100, 45, 0.30);
}


/* =========================================================
   NO RESULT MESSAGE
   ========================================================= */

h3[style*="red"] {

    width: min(700px, 90%);

    box-sizing: border-box;

    margin: 30px auto;

    padding: 20px;

    color: #a51d1d !important;

    background: rgba(255, 235, 235, 0.97);

    border: 1px solid #efb2b2;

    border-left: 5px solid #c62828;

    border-radius: 12px;

    box-shadow: 0 8px 25px rgba(100, 0, 0, 0.15);

    animation: shake 0.5s ease;
}


/* =========================================================
   BACK TO HOME
   ========================================================= */

.back {

    text-align: center;

    margin: 40px auto 10px;

    animation: fadeUp 1s ease;
}


.back a {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 13px 26px;

    text-decoration: none;

    color: white;

    background:
        linear-gradient(
            135deg,
            #003d20,
            #007a39
        );

    border: 2px solid rgba(212,175,55,0.65);

    border-radius: 10px;

    font-weight: 600;

    letter-spacing: 0.2px;

    box-shadow:
        0 8px 20px rgba(0, 40, 20, 0.25);

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        background 0.3s ease;
}


.back a:hover {

    background:
        linear-gradient(
            135deg,
            #006b32,
            #00a34d
        );

    transform: translateY(-4px);

    box-shadow:
        0 12px 28px rgba(0, 60, 30, 0.35);
}


/* =========================================================
   ANIMATIONS
   ========================================================= */

@keyframes floatingGlow {

    0%, 100% {
        transform: translate(0, 0);
    }

    50% {
        transform: translate(80px, 60px);
    }
}


@keyframes floatingGlow2 {

    0%, 100% {
        transform: translate(0, 0);
    }

    50% {
        transform: translate(-70px, -50px);
    }
}


@keyframes goldMove {

    0% {
        background-position: 0% 50%;
    }

    100% {
        background-position: 250% 50%;
    }
}


@keyframes titleAppear {

    from {
        opacity: 0;
        transform: translateY(-20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@keyframes lineGrow {

    from {
        width: 0;
        opacity: 0;
    }

    to {
        width: 90px;
        opacity: 1;
    }
}


@keyframes formAppear {

    from {
        opacity: 0;
        transform: translateY(25px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}


@keyframes cardAppear {

    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@keyframes fadeUp {

    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@keyframes shake {

    0%, 100% {
        transform: translateX(0);
    }

    25% {
        transform: translateX(-7px);
    }

    75% {
        transform: translateX(7px);
    }
}


/* =========================================================
   TABLET
   ========================================================= */

@media (max-width: 700px) {

    body {
        padding: 30px 12px 45px;
    }

    h2 {
        font-size: 27px;
        margin-bottom: 25px;
    }

    form {
        width: 95%;
        padding: 20px;

        flex-direction: column;

        gap: 12px;
    }

    input[type="text"],
    input[type="submit"] {
        width: 100%;
    }

    .card {
        width: 95%;
        padding: 22px;
    }

    .card h3 {
        font-size: 21px;
    }
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 450px) {

    body {
        padding: 22px 8px 35px;
    }

    h2 {
        font-size: 23px;
    }

    form {
        width: 100%;
        padding: 17px;

        border-radius: 14px;
    }

    input[type="text"],
    input[type="submit"] {
        height: 52px;

        font-size: 15px;
    }

    .card {
        width: 100%;

        padding: 18px;

        border-left-width: 4px;

        border-radius: 14px;
    }

    .card h3 {
        font-size: 19px;
    }

    .card p {
        font-size: 14px;

        padding: 10px;
    }

    .card a {
        width: 100%;

        box-sizing: border-box;

        text-align: center;
    }

    .back a {
        width: 90%;

        box-sizing: border-box;
    }
}


/* =========================================================
   ACCESSIBILITY
   ========================================================= */

@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {

        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```


</style>

</head>

<body>

<h2>Search Government Office</h2>

<form method="GET">

<input
type="text"
name="keyword"
placeholder="Enter Office or Service Name"
required>

<input
type="submit"
value="Search">

</form>

<?php

if(isset($_GET['keyword']))
{

$keyword=mysqli_real_escape_string($conn,$_GET['keyword']);

$sql="SELECT * FROM offices
WHERE office_name LIKE '%$keyword%'
OR service_name LIKE '%$keyword%'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<div class="card">

<h3><?php echo $row['office_name']; ?></h3>

<p><strong>Service:</strong> <?php echo $row['service_name']; ?></p>

<p><strong>Address:</strong> <?php echo $row['address']; ?></p>

<a href="<?php echo $row['map_link']; ?>"
   target="_blank"
   style="display:inline-block;
          padding:10px 15px;
          background:green;
          color:white;
          text-decoration:none;
          border-radius:5px;">
    View Location on Google Maps
</a>

<p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>

<p><strong>Email:</strong> <?php echo $row['email']; ?></p>

<p><strong>Floor Number:</strong> <?php echo $row['floor_number']; ?></p>

<p><strong>Office Number:</strong> <?php echo $row['office_number']; ?></p>

<p><strong>Description:</strong> <?php echo $row['description']; ?></p>

</div>

<?php

}

}
else
{

echo "<h3 style='text-align:center;color:red;'>No office found.</h3>";

}

}

?>

<div class="back">
<a href="../index.php">← Back to Home</a>
</div>

</body>
</html>