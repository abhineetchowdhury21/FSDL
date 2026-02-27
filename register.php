<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$msg = "";
$msgType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $full_name = trim($_POST["full_name"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";
  $confirm = $_POST["confirm"] ?? "";

  if ($full_name === "" || $email === "" || $password === "" || $confirm === "") {
    $msg = "All fields are required.";
    $msgType = "err";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $msg = "Enter a valid email.";
    $msgType = "err";
  } elseif (strlen($password) < 6) {
    $msg = "Password must be at least 6 characters.";
    $msgType = "err";
  } elseif ($password !== $confirm) {
    $msg = "Passwords do not match.";
    $msgType = "err";
  } else {
    // check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $msg = "Email already registered. Please login.";
      $msgType = "err";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $full_name, $email, $hash);

      if ($stmt->execute()) {
        $msg = "Registration successful! You can login now.";
        $msgType = "ok";
      } else {
        $msg = "Something went wrong. Try again.";
        $msgType = "err";
      }
      $stmt->close();
    }
    $check->close();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <div class="card">
    <h1>Create Account</h1>
    <p class="sub">Register once, then login anytime.</p>

    <form method="POST">
      <label>Full Name</label>
      <input type="text" name="full_name" placeholder="Your name" required>

      <label>Email</label>
      <input type="email" name="email" placeholder="you@example.com" required>

      <div class="row">
        <div style="flex:1">
          <label>Password</label>
          <input type="password" name="password" placeholder="Min 6 chars" required>
        </div>
        <div style="flex:1">
          <label>Confirm</label>
          <input type="password" name="confirm" placeholder="Repeat" required>
        </div>
      </div>

      <button class="btn" type="submit">Register</button>
    </form>

    <?php if ($msg !== ""): ?>
      <div class="msg <?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="small">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>
</body>
</html>
