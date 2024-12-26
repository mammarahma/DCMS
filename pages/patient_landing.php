<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    header('Location: ../index.php');
    exit();
}

// Fetch patient information
$username = $_SESSION['username'];
$stmt = $dbconn->prepare("SELECT patient_id FROM user_account WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $patient_id = $user['patient_id'];

    // Fetch patient details
    $stmt = $dbconn->prepare("SELECT * FROM patient_info WHERE patient_id = :patient_id");
    $stmt->execute(['patient_id' => $patient_id]);
    $patient_info = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>DCMS - Patient Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($patient_info['name']); ?>!</h1>
    <h2>Your Information</h2>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($patient_info['name']); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient_info['gender']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($patient_info['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient_info['phone']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($patient_info['address']); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient_info['date_of_birth']); ?></p>
    <p><strong>Insurance:</strong> <?php echo htmlspecialchars($patient_info['insurance']); ?></p>

    <h2>Your Appointments</h2>
    <?php
    // Fetch appointments for the patient
    $stmt = $dbconn->prepare("SELECT * FROM appointment WHERE patient_id = :patient_id");
    $stmt->execute(['patient_id' => $patient_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($appointments) {
        echo "<ul>";
        foreach ($appointments as $appointment) {
            echo "<li>";
            echo "Appointment ID: " . htmlspecialchars($appointment['appointment_id']) . "<br>";
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

    <a href="../index.php">Logout</a>

    <script src="../js/script.js"></script>

</body>
</html>