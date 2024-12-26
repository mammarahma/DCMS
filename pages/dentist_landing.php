<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    header('Location: ../index.php');
    exit();
}

// Fetch dentist information
$username = $_SESSION['username'];
$stmt = $dbconn->prepare("SELECT employee_id FROM user_account WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $employee_id = $user['employee_id'];

    // Fetch dentist details (assuming you have a table for employees)
    $stmt = $dbconn->prepare("SELECT * FROM employee_info WHERE employee_id = :employee_id");
    $stmt->execute(['employee_id' => $employee_id]);
    $dentist_info = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>DCMS - Dentist Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Welcome, Dr. <?php echo htmlspecialchars($dentist_info['name']); ?>!</h1>
    <h2>Your Information</h2>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($dentist_info['name']); ?></p>
    <p><strong>Specialization:</strong> <?php echo htmlspecialchars($dentist_info['specialization']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($dentist_info['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($dentist_info['phone']); ?></p>

    <h2>Your Appointments</h2>
    <?php
    // Fetch appointments for the dentist
    $stmt = $dbconn->prepare("SELECT * FROM appointment WHERE dentist_id = :dentist_id");
    $stmt->execute(['dentist_id' => $employee_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($appointments) {
        echo "<ul>";
        foreach ($appointments as $appointment) {
            echo "<li>";
            echo "Appointment ID: " . htmlspecialchars($appointment['appointment_id']) . "<br>";
            echo "Patient ID: " . htmlspecialchars($appointment['patient_id']) . "<br>";
            echo "Date: " . htmlspecialchars($appointment['date_of_appointment']) . "<br>";
            echo "Time: " . htmlspecialchars($appointment['start_time']) . " - " . htmlspecialchars($appointment['end_time']) . "<br>";
            echo "Status: " . htmlspecialchars($appointment['appointment_status']);
            echo "</li><br>";
        }
        echo "</ul>";
    } else {
        echo "<p>No appointments found.</p>";
    }
    ?>

    <h2>Manage Appointments</h2>
    <a href="view_appointments.php">View All Appointments</a><br>
    <a href="add_appointment.php">Add New Appointment</a><br>

    <h2>Patient Records</h2>
    <a href="view_patients.php">View All Patients</a><br>

    <a href="../index.php">Logout</a>

    <script src="../js/script.js"></script>

</body>
</html>