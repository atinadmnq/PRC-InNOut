<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>In-n-Out</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      background-color: #EDEEEB; 
      font-family: 'Century Gothic';
    }

    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #31393C;
      padding-top: 20px;
      font-family: 'Century Gothic';
    }

    .sidebar a {
      padding: 15px 20px;
      text-decoration: none;
      font-size: 18px;
      color: #FFFFFF; 
      display: block;
      transition: 0.3s;
    }

    .sidebar a:hover {
      background-color: #CCC7BF; 
      color: #31393C; 
    }

    .sidebar .active {
      background-color: #FFFFFF; 
      color: #31393C; 
    }

    .content {
      margin-left: 250px;
      padding: 20px;
      font-family: 'Century Gothic';
      color: #31393C; 
    }

    .btn-logout {
      background-color: #3E96F4; 
      color: #FFFFFF; 
      padding: 7px 0px;
      border: none;
      margin-top: 250px;
      width: 100%;
      font-family: 'Century Gothic';
      box-shadow: -5px 0px 7px rgba(0, 0, 0, 0.1);
    }

    .btn-logout:hover {
      background-color: #3379C7; 
      transition: 0.7s;
    }

    .logo {
      width: 150px;
      height: auto;
      display: block;
      margin: 0 auto 20px;
    }

    .brand-text {
      font-size: 20px;
      text-align: center;
      color: #FFFFFF;
      margin-bottom: 1rem;
    }

    .modal-content {
      border-radius: 20px;
      background-color: #FFFFFF; 
      font-family: 'Century Gothic';
    }

    .modal-title,
    .modal-body {
      color: #31393C; 
    }

    .modal-footer .btn-cancel {
      background-color: #CCC7BF; 
      color: #31393C; 
    }

    .modal-footer .btn-confirm {
      background-color: #3E96F4; 
      color: #FFFFFF; 
    }

    .btn-close {
      filter: invert(1);
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <img src="prcLogo.png" alt="Logo" class="logo" />
    <div class="brand-text">IN-n-OUT</div>

    <a href="incoming.php">Incoming</a>
    <a href="outgoing.php">Outgoing</a>
    <a href="register_user.php">Register User</a>
    <a href="view_data.php">View Data</a>

    <form id="logoutForm" action="logout.php" method="post" class="mt-auto">
      <button type="button" class="btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
    </form>
  </div>

  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-confirm" id="confirmLogoutBtn">Yes, Logout</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.getElementById('confirmLogoutBtn').addEventListener('click', function () {
      document.getElementById('logoutForm').submit();
    });
  </script>

</body>
</html>
