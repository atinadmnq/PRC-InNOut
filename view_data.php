
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
    body {
        background-color: #EDEEEB; 
        color: #31393C; 
        font-family: 'Century Gothic';
    }

    .modal-header {
        background-color: #3E96F4;
        color: #FFFFFF;
    }

    .modal-footer {
        background-color: #FFFFFF; 
    }

    .modal-title {
        font-weight: bold;
    }

    .btn-incoming {
        background-color: #CCC7BF; 
        color: #31393C; 
        font-weight: 600;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.4rem;
    }

    .btn-outgoing {
        background-color: #31393C; 
        color: #FFFFFF; 
        font-weight: 600;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.4rem;
    }

    .btn-incoming:hover,
    .btn-outgoing:hover {
        opacity: 0.9;
        cursor: pointer;
    }
</style>

</head>
<body onload="showChoiceModal()">

<div class="modal fade" id="choiceModal" tabindex="-1" aria-labelledby="choiceModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="choiceModalLabel">SELECT RECORD TO VIEW</h5>
      </div>
      <div class="modal-body text-center">
        <p class="mb-4">What type of records would you like to view?</p>
        <div class="d-flex justify-content-around">
            <a href="incoming_table.php" class="btn btn-incoming px-4 py-2">Incoming Records</a>
            <a href="outgoing_table.php" class="btn btn-outgoing px-4 py-2">Outgoing Records</a>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showChoiceModal() {
        const modal = new bootstrap.Modal(document.getElementById('choiceModal'));
        modal.show();
    }
</script>

</body>
</html>
