<?php
session_start();
require_once __DIR__ . "/../config/db.php";

$msg = "";
$msgType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($email === "" || $password === "") {
    $msg = "Email and password are required.";
    $msgType = "err";
  } else {
    $stmt = $conn->prepare("SELECT id, full_name, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
      if (password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["full_name"] = $user["full_name"];
        header("Location: ../dashboard.php");
        exit;
      } else {
        $msg = "Invalid password.";
        $msgType = "err";
      }
    } else {
      $msg = "No account found with this email.";
      $msgType = "err";
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body>
  <div class="card">
    <h1>Welcome Back</h1>
    <p class="sub">Login to continue.</p>

    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" placeholder="you@example.com" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Your password" required>

      <button class="btn" type="submit">Login</button>
    </form>

    <?php if ($msg !== ""): ?>
      <div class="msg <?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="small">
      New here? <a href="register.php">Create account</a>
    </div>
  </div>
</body>
</html>
