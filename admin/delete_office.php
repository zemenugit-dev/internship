<?php
session_start();

// 1. Fixed session check to match login session parameters
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../database/db.php");

// 2. Validate and execute deletion
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $id = intval($_GET['id']); // Sanitize ID to prevent SQL injection

    // Use prepared statement for secure deletion
    $stmt = mysqli_prepare($conn, "DELETE FROM offices WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // 3. Fixed redirect header syntax
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

} else {
    // If no valid ID is passed, redirect back to dashboard
    header("Location: dashboard.php");
    exit();
}
?>