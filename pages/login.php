<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Sanitize and check input
    $username = check_empty_input($_POST["username"]);
    $password = check_empty_input($_POST["password"]);

    if ($username != -1 && $password != -1) {
        // Prepare and execute the SQL statement
        $stmt = $dbconn->prepare("SELECT password, patient_id, employee_id FROM user_account WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['valid'] = true;
            $_SESSION['username'] = $username;

            // Redirect based on user type
            if ($user['patient_id']) {
                header('Location: pages/patient_landing.php');
            } else {
                header('Location: pages/receptionist_landing.php');
            }
            exit();
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DCMS - Login</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Dental Clinic Management System</h1>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="error"><?php echo $error; ?></p>

    <script src="../js/script.js"></script>

</body>
</html>