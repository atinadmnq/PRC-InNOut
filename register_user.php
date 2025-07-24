<?php
include 'db_connect.php'; 
include 'sidebar.php';

$username = $email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed_password, $email);
            if ($insert->execute()) {
                header("Location: register_user.php?registered=1");
                exit();
            } else {
                $errors[] = "Registration failed. Try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
  body {
    background-color: #EDEEEB; 
    font-family: 'Century Gothic';
    margin-left: 250px;
  }

  .register-container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #FFFFFF; 
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(49, 57, 60, 0.1); 
  }

  .form-label {
    color: #31393C; 
  }

  .form-control:focus {
    border-color: #CCC7BF; 
    box-shadow: 0 0 5px rgba(204, 199, 191, 0.5); 
  }

  .btn-register {
    background-color: #3E96F4; 
    color: #FFFFFF; 
    width: 100%;
    font-weight: 600;
    border: none;
    border-radius: 0.5rem;
    padding: 0.6rem;
  }

  .btn-register:hover {
    background-color: rgba(51, 121, 199, 0.8); 
    transition: 0.4s;
    color: #FFFFFF; 
  }
</style>

</head>
<body>

<div class="register-container shadow border">
  <h3 class="text-center mb-4" style="color: #292F36; font-weight: bold;">REGISTER USER</h3>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form id="registerForm" method="post" action="register_user.php" novalidate>
    <div class="mb-3">
      <label class="form-label">Username:</label>
      <input type="text" name="username" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Email:</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Password:</label>
      <input type="password" name="password" class="form-control" required minlength="5" />
    </div>
    <button type="submit" class="btn btn-register">Register</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function (e) {
  const form = e.target;
  if (!form.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  form.classList.add('was-validated');
});
</script>

</body>
</html>
