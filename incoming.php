<?php
include 'db_connect.php';
include 'navbar.php';

function logActivity($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ctrlNum     = $_POST['ctrlNum'];
        $source      = $_POST['source'];
        $dateRecd    = $_POST['dateRecd'];
        $timeRe      = $_POST['timeRe'];
        $subj        = $_POST['subj'];
        $attachment  = $_POST['attachment'];
        $stat        = $_POST['stat'];
        $actionUnit  = $_POST['actionUnit'];
        $dateRel     = $_POST['dateRel'];
        $recipient   = $_POST['recipient'];
        $intial      = $_POST['intial'];
        $trackingNum = $_POST['trackingNum'];

        $stmt = $conn->prepare("
            INSERT INTO incoming 
            (ctrlNum, source, dateRecd, timeRe, subj, attachment, stat, actionUnit, dateRel, recipient, intial, trackingNum) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssssss", 
            $ctrlNum, $source, $dateRecd, $timeRe, $subj, $attachment,
            $stat, $actionUnit, $dateRel, $recipient, $intial, $trackingNum
        );

        if ($stmt->execute()) {
            echo "<script>alert('Data inserted successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

logActivity($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incoming Mails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #EDEEEB; 
        color: #31393C;
        font-family: 'Century Gothic';
        margin-left: 250px; 
    }

    .form-container {
        background-color: #FFFFFF;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 0 10px rgba(49, 57, 60, 0.1);
        margin-top: 3rem;
        margin-bottom: 3rem;
    }

    .form-label {
        font-weight: 600;
        color: #31393C; 
    }

    .form-control {
        border: 1px solid #CCC7BF; 
        border-radius: 0.5rem;

    }

    .submit-btn {
        background-color: #3E96F4; 
        color: #FFFFFF; 
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .submit-btn:hover {
        background-color: #3379C7; 
    }
</style>

</head>
<body>
    <div class="container">
        <div class="form-container mx-auto col-md-8 shadow border">
            <h3 class="mb-4 fw-bold text-center">INCOMING MAILS</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="ctrlNum" class="form-label">Control Number</label>
                    <input type="text" class="form-control" id="ctrlNum" name="ctrlNum" required>
                </div>

                <div class="mb-3">
                    <label for="source" class="form-label">Source</label>
                    <input type="text" class="form-control" id="source" name="source" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dateRecd" class="form-label">Date Received</label>
                        <input type="date" class="form-control" id="dateRecd" name="dateRecd" required>
                    </div>
                    <div class="col-md-6">
                        <label for="timeRe" class="form-label">Time Received</label>
                        <input type="time" class="form-control" id="timeRe" name="timeRe" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="subj" class="form-label">Subject Matter</label>
                    <input type="text" class="form-control" id="subj" name="subj" required>
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment:</label>
                    <input type="text" class="form-control" id="attachment" name="attachment" required>
                </div>

                <div class="mb-3">
                    <label for="stat" class="form-label">Status</label>
                    <select class="form-control" id="stat" name="stat" required>
                        <option value="">-- Select Status --</option>
                        <option value="Pending">Pending</option>
                        <option value="Released">Released</option>
                        <option value="In Progress">In Progress</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="actionUnit" class="form-label">Action Unit</label>
                    <input type="text" class="form-control" id="actionUnit" name="actionUnit" required>
                </div>

                <div class="mb-3">
                    <label for="dateRel" class="form-label">Date Released</label>
                    <input type="date" class="form-control" id="dateRel" name="dateRel" required>
                </div>

                <div class="mb-3">
                    <label for="recipient" class="form-label">Recipient</label>
                    <input type="text" class="form-control" id="recipient" name="recipient" required>
                </div>
                
                <div class="mb-3">
                    <label for="intial" class="form-label">Receiver Initial:</label>
                    <input type="text" class="form-control" id="intial" name="intial" required>
                </div>
            
                <div class="mb-4">
                    <label for="trackingNum" class="form-label">Tracking Number</label>
                    <input type="text" class="form-control" id="trackingNum" name="trackingNum" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
