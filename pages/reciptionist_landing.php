<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    header('Location: ../index.php');
    exit();
}

// Fetch receptionist information
$username = $_SESSION['username'];
$stmt = $dbconn->prepare("SELECT employee_id FROM user_account WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $employee_id = $user['employee_id'];

    // Fetch receptionist details (assuming you have a table for employees)
    $stmt = $dbconn->prepare("SELECT * FROM employee_info WHERE employee_id = :employee_id");
    $stmt->execute(['employee_id' => $employee_id]);
    $employee_info = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Handle case where user is not found
    echo "User  not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DCMS - Receptionist Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($employee_info['name']); ?>!</h1>
    <h2>Your Information</h2>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($employee_info['name']); ?></p>
    <p><strong>Position:</strong> <?php echo htmlspecialchars($employee_info['position']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($employee_info['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($employee_info['phone']); ?></p>

    <h2>Manage Appointments</h2>
    <a href="view_appointments.php">View All Appointments</a><br>
    <a href="add_appointment.php">Add New Appointment</a><br>

    <h2>Manage Patients</h2>
    <a href="view_patients.php">View All Patients</a><br>
    <a href="add_patient.php">Add New Patient</a><br>

    <a href="../index.php">Logout</a>
    <script src="../js/script.js"></script>

</body>
</html>