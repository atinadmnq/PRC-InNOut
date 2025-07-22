<?php
session_start();
include 'db_connect.php';

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $loginError = "PLEASE TRY AGAIN!";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: incoming.php");
                exit();
            } else {
                $loginError = "Incorrect password.";
            }
        } else {
            $loginError = "Username does not exist.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>In-n-Out Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
        background-color: #EDEEEB; 
        color: #31393C; 
        font-family: 'Century Gothic';
    }

    .login-container {
        background-color: #FFFFFF; 
        padding: 2rem;
        border-radius: 1rem;
        max-width: 400px;
        margin: 6vh auto;
        box-shadow: 0 0 10px rgba(49, 57, 60, 0.1); 
    }

    .form-label {
        font-weight: 600;
        color: #31393C;
    }

    .form-control {
        border: 1px solid #CCC7BF; 
        border-radius: 0.5rem;
    }

    .btn-login {
        background-color: #3E96F4; 
        color: #FFFFFF;
        font-weight: 600;
        border: none;
        width: 100%;
        border-radius: 0.5rem;
        padding: 0.6rem;
    }

    .btn-login:hover {
        background-color: #3379C7; 
    }
</style>

</head>
<body>

<div class="login-container">
  <h4 class="text-center mb-4 fw-bold">PLEASE LOGIN TO YOUR ACCOUNT</h4>

  <?php if (!empty($loginError)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($loginError) ?></div>
  <?php endif; ?>

  <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    <div class="alert alert-success text-center">You are now registered! Please log in.</div>
  <?php endif; ?>

  <form method="POST" action="index.php">
    <div class="mb-3">
      <label for="user" class="form-label">Username</label>
      <input type="text" id="user" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="pass" class="form-label">Password</label>
      <input type="password" id="pass" name="password" class="form-control" required>
    </div>
  
    <button type="submit" class="btn btn-login">Login</button>
  </form>
</div>

</body>
</html>
