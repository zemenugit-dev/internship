<?php

/*
|--------------------------------------------------------------------------
| ERROR REPORTING
|--------------------------------------------------------------------------
| Keep this while testing.
| Remove or disable it after the system is working.
|--------------------------------------------------------------------------
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../database/db.php';


/*
|--------------------------------------------------------------------------
| MESSAGE VARIABLES
|--------------------------------------------------------------------------
*/

$message = "";
$message_type = "";


/*
|--------------------------------------------------------------------------
| CHECK DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

if (!isset($conn) || !$conn) {

    $message = "Database connection failed.";

    $message_type = "error";

}


/*
|--------------------------------------------------------------------------
| REGISTRATION
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | GET FORM DATA
    |--------------------------------------------------------------------------
    */

    $fullname = trim($_POST["fullname"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $phone    = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role     = strtolower(trim($_POST["role"] ?? ""));


    /*
    |--------------------------------------------------------------------------
    | VALIDATE REQUIRED FIELDS
    |--------------------------------------------------------------------------
    */

    if (
        $fullname === "" ||
        $username === "" ||
        $email === "" ||
        $password === "" ||
        $role === ""
    ) {

        $message =
            "Please fill in all required fields.";

        $message_type = "error";

    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE EMAIL
    |--------------------------------------------------------------------------
    */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message =
            "Please enter a valid email address.";

        $message_type = "error";

    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE PASSWORD
    |--------------------------------------------------------------------------
    */

    elseif (strlen($password) < 8) {

        $message =
            "Password must contain at least 8 characters.";

        $message_type = "error";

    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE ROLE
    |--------------------------------------------------------------------------
    */

    elseif ($role !== "user" && $role !== "admin") {

        $message =
            "Invalid role selected.";

        $message_type = "error";

    }

    else {

        /*
        |--------------------------------------------------------------------------
        | CHECK USERNAME OR EMAIL
        |--------------------------------------------------------------------------
        */

        $check_sql = "
            SELECT id
            FROM users
            WHERE username = ?
               OR email = ?
            LIMIT 1
        ";

        $check_stmt = $conn->prepare($check_sql);


        if (!$check_stmt) {

            $message =
                "Database error while checking user: "
                . $conn->error;

            $message_type = "error";

        }

        else {

            $check_stmt->bind_param(
                "ss",
                $username,
                $email
            );

            $check_stmt->execute();

            $check_result =
                $check_stmt->get_result();


            /*
            |--------------------------------------------------------------------------
            | USER ALREADY EXISTS
            |--------------------------------------------------------------------------
            */

            if ($check_result->num_rows > 0) {

                $message =
                    "Username or email is already registered.";

                $message_type = "error";

                $check_stmt->close();

            }

            else {

                $check_stmt->close();


                /*
                |--------------------------------------------------------------------------
                | HASH PASSWORD
                |--------------------------------------------------------------------------
                */

                $hashed_password =
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );


                /*
                |--------------------------------------------------------------------------
                | INSERT USER
                |--------------------------------------------------------------------------
                |
                | EXACTLY MATCHES YOUR TABLE:
                |
                | fullname
                | username
                | email
                | phone
                | password
                | role
                |
                | id and created_at are NOT included.
                |--------------------------------------------------------------------------
                */

                $insert_sql = "
                    INSERT INTO users
                    (
                        fullname,
                        username,
                        email,
                        phone,
                        password,
                        role
                    )
                    VALUES
                    (?, ?, ?, ?, ?, ?)
                ";


                $stmt =
                    $conn->prepare($insert_sql);


                /*
                |--------------------------------------------------------------------------
                | CHECK PREPARED STATEMENT
                |--------------------------------------------------------------------------
                */

                if (!$stmt) {

                    $message =
                        "Database INSERT preparation failed: "
                        . $conn->error;

                    $message_type = "error";

                }

                else {

                    /*
                    |--------------------------------------------------------------------------
                    | BIND VALUES
                    |--------------------------------------------------------------------------
                    */

                    $stmt->bind_param(
                        "ssssss",
                        $fullname,
                        $username,
                        $email,
                        $phone,
                        $hashed_password,
                        $role
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | EXECUTE INSERT
                    |--------------------------------------------------------------------------
                    */

                    if ($stmt->execute()) {

                        /*
                        |--------------------------------------------------------------------------
                        | GET NEW USER ID
                        |--------------------------------------------------------------------------
                        */

                        $new_user_id =
                            $conn->insert_id;


                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS MESSAGE
                        |--------------------------------------------------------------------------
                        */

                        $message =
                            "Registration successful! "
                            . "User account has been created successfully. "
                            . "User ID: "
                            . $new_user_id;

                        $message_type = "success";


                        /*
                        |--------------------------------------------------------------------------
                        | CLEAR FORM VALUES
                        |--------------------------------------------------------------------------
                        */

                        $fullname = "";
                        $username = "";
                        $email = "";
                        $phone = "";
                        $role = "user";

                    }

                    else {

                        /*
                        |--------------------------------------------------------------------------
                        | SHOW ACTUAL MYSQL ERROR
                        |--------------------------------------------------------------------------
                        */

                        $message =
                            "Registration failed. "
                            . "MySQL Error: "
                            . $stmt->error;

                        $message_type = "error";

                    }


                    $stmt->close();

                }

            }

        }

    }

}

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
        Register User - Awi Zone System
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            min-height: 100vh;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f4f6f4;

            display: flex;

            justify-content: center;

            align-items: flex-start;

            padding: 40px 15px;
        }


        .register-container {

            width: 100%;

            max-width: 480px;

            background: #ffffff;

            padding: 30px;

            border-radius: 12px;

            border-top: 5px solid #006400;

            box-shadow:
                0 8px 25px
                rgba(0, 0, 0, 0.10);
        }


        .header {

            text-align: center;

            margin-bottom: 25px;
        }


        .header h2 {

            margin: 0;

            color: #006400;

            font-size: 26px;
        }


        .header p {

            margin-top: 8px;

            color: #777;

            font-size: 14px;
        }


        /*
        |--------------------------------------------------------------------------
        | MESSAGE
        |--------------------------------------------------------------------------
        */

        .message {

            padding: 14px;

            margin-bottom: 20px;

            border-radius: 7px;

            font-size: 14px;

            font-weight: bold;

            line-height: 1.5;
        }


        .message.success {

            color: #176b35;

            background: #e8f7ed;

            border: 1px solid #a8ddb8;
        }


        .message.error {

            color: #a52222;

            background: #fdecec;

            border: 1px solid #efb1b1;
        }


        /*
        |--------------------------------------------------------------------------
        | FORM GROUP
        |--------------------------------------------------------------------------
        */

        .form-group {

            margin-bottom: 17px;
        }


        label {

            display: block;

            margin-bottom: 7px;

            color: #333;

            font-size: 14px;

            font-weight: bold;
        }


        .required {

            color: #c00000;
        }


        input,
        select {

            width: 100%;

            height: 44px;

            padding: 10px 12px;

            border: 1px solid #cfd5cf;

            border-radius: 6px;

            background: white;

            color: #333;

            font-size: 14px;

            outline: none;
        }


        input:focus,
        select:focus {

            border-color: #006400;

            box-shadow:
                0 0 0 3px
                rgba(0, 100, 0, 0.08);
        }


        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        .password-wrapper {

            position: relative;
        }


        .password-wrapper input {

            padding-right: 70px;
        }


        .show-password {

            position: absolute;

            right: 10px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            color: #006400;

            cursor: pointer;

            font-size: 12px;

            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | REGISTER BUTTON
        |--------------------------------------------------------------------------
        */

        .register-button {

            width: 100%;

            height: 46px;

            margin-top: 5px;

            border: none;

            border-radius: 6px;

            background: #006400;

            color: white;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;
        }


        .register-button:hover {

            background: #004d00;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN LINK
        |--------------------------------------------------------------------------
        */

        .login-link {

            text-align: center;

            margin-top: 20px;
        }


        .login-link a {

            color: #006400;

            font-weight: bold;

            text-decoration: none;

            font-size: 14px;
        }


        .login-link a:hover {

            text-decoration: underline;
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS ACTION
        |--------------------------------------------------------------------------
        */

        .success-action {

            margin-top: 15px;

            text-align: center;
        }


        .success-action a {

            display: inline-block;

            padding: 10px 18px;

            background: #006400;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            font-weight: bold;

            font-size: 14px;
        }


        @media (max-width: 500px) {

            body {

                padding: 20px 10px;
            }


            .register-container {

                padding: 22px 18px;
            }


            .header h2 {

                font-size: 22px;
            }

        }

    </style>

</head>


<body>


<div class="register-container">


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <div class="header">

        <h2>
            Register User
        </h2>

        <p>
            Awi Zone Administrative Office System
        </p>

    </div>


    <!-- =====================================================
         PHP RESPONSE MESSAGE
         ===================================================== -->

    <?php if ($message !== ""): ?>

        <div
            class="message <?= $message_type ?>"
        >

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         REGISTRATION FORM
         ===================================================== -->

    <form
        method="POST"
        action=""
    >


        <!-- FULL NAME -->

        <div class="form-group">

            <label for="fullname">

                Full Name

                <span class="required">
                    *
                </span>

            </label>

            <input
                type="text"
                id="fullname"
                name="fullname"
                value="<?= htmlspecialchars($fullname ?? '') ?>"
                placeholder="Enter full name"
                required
            >

        </div>


        <!-- USERNAME -->

        <div class="form-group">

            <label for="username">

                Username

                <span class="required">
                    *
                </span>

            </label>

            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars($username ?? '') ?>"
                placeholder="Enter username"
                required
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">

                Email

                <span class="required">
                    *
                </span>

            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email ?? '') ?>"
                placeholder="example@gmail.com"
                required
            >

        </div>


        <!-- PHONE -->

        <div class="form-group">

            <label for="phone">

                Phone

            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="<?= htmlspecialchars($phone ?? '') ?>"
                placeholder="0912345678"
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label for="password">

                Password

                <span class="required">
                    *
                </span>

            </label>


            <div class="password-wrapper">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                >

                <button
                    type="button"
                    class="show-password"
                    onclick="togglePassword()"
                >
                    Show
                </button>

            </div>

        </div>


        <!-- CONFIRM PASSWORD -->

        <div class="form-group">

            <label for="confirm_password">

                Confirm Password

                <span class="required">
                    *
                </span>

            </label>


            <div class="password-wrapper">

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm password"
                    required
                >

                <button
                    type="button"
                    class="show-password"
                    onclick="toggleConfirmPassword()"
                >
                    Show
                </button>

            </div>

        </div>


        <!-- ROLE -->

        <div class="form-group">

            <label for="role">

                Role

                <span class="required">
                    *
                </span>

            </label>


            <select
                id="role"
                name="role"
                required
            >

                <option
                    value="user"
                    <?= (($role ?? 'user') === 'user')
                        ? 'selected'
                        : '' ?>
                >
                    User
                </option>


                <option
                    value="admin"
                    <?= (($role ?? '') === 'admin')
                        ? 'selected'
                        : '' ?>
                >
                    admin
                </option>

            </select>

        </div>


        <!-- REGISTER BUTTON -->

        <button
            type="submit"
            name="submit"
            class="register-button"
        >

            Register Account

        </button>


    </form>


    <!-- =====================================================
         SUCCESS DASHBOARD LINK
         ===================================================== -->

    <?php if ($message_type === "success"): ?>

        <div class="success-action">

            <?php if (($role ?? '') === 'admin'): ?>

                <a href="dashboard.php">
                    Go to Admin Dashboard
                </a>

            <?php else: ?>

                <a href="../user/dashboard.php">
                    Go to User Dashboard
                </a>

            <?php endif; ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         LOGIN LINK
         ===================================================== -->

    <div class="login-link">

        <a href="../login.php">

            Already have an account?
            Login

        </a>

    </div>


</div>


<script>

/*
|--------------------------------------------------------------------------
| SHOW / HIDE PASSWORD
|--------------------------------------------------------------------------
*/

function togglePassword() {

    const password =
        document.getElementById("password");

    const button =
        event.target;


    if (password.type === "password") {

        password.type = "text";

        button.textContent = "Hide";

    } else {

        password.type = "password";

        button.textContent = "Show";
    }

}


function toggleConfirmPassword() {

    const password =
        document.getElementById("confirm_password");

    const button =
        event.target;


    if (password.type === "password") {

        password.type = "text";

        button.textContent = "Hide";

    } else {

        password.type = "password";

        button.textContent = "Show";
    }

}


/*
|--------------------------------------------------------------------------
| CONFIRM PASSWORD CHECK
|--------------------------------------------------------------------------
|
| IMPORTANT:
| This does NOT stop form submission.
| It only gives the user a warning.
|--------------------------------------------------------------------------
*/

document
    .querySelector("form")
    .addEventListener("submit", function(event) {

        const password =
            document.getElementById("password").value;

        const confirmPassword =
            document.getElementById("confirm_password").value;


        if (password !== confirmPassword) {

            event.preventDefault();

            alert(
                "Password and Confirm Password do not match."
            );

            return false;
        }

    });

</script>


</body>

</html>