<?php
require "db.php";

$message = "";

// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['email'], $_POST['password'])) {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered.";
        } else {
            $stmt->close();

            // Insert user
            $stmt = $conn->prepare(
                "INSERT INTO users (email, password) VALUES (?, ?)"
            );
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                $message = "Registration successful. <a href='login.php'>Login</a>";
            } else {
                $message = "Something went wrong.";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
</head>
<body>

<h2>Register</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form action="register.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.html">Login</a></p>

</body>
</html>