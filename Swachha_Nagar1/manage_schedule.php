<?php
include 'db.php';
session_start(); // Ensure session handling is started

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit();
}

// Logout logic
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); // Clear session variables
    session_destroy(); // Destroy the session
    header('Location: login.php'); // Redirect to the login page
    exit();
}

// Initialize variables
$status = $message = "";

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submissions for adding, updating, and deleting schedules

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'] ?? '';
    
        if ($action == 'add') {
            // Add new schedule
            $city = $_POST['city'];
            $ward_number = $_POST['ward_number'];
            $section = $_POST['section'];
            $waste_type = $_POST['waste_type'];
            $day = $_POST['day'];
            $departure = isset($_POST['departure']) ? 1 : 0;
    
            $stmt = $conn->prepare("INSERT INTO schedules (city, ward_number, section, waste_type, day, departure) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssssi", $city, $ward_number, $section, $waste_type, $day, $departure);
                if ($stmt->execute()) {
                    $status = 'success';
                    $message = 'Schedule added successfully.';
                } else {
                    $status = 'error';
                    $message = 'Error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $status = 'error';
                $message = 'Prepare failed: ' . $conn->error;
            }
        } elseif ($action == 'edit') {
            // Edit schedule
            $id = $_POST['id'];
            $city = $_POST['city'];
            $ward_number = $_POST['ward_number'];
            $section = $_POST['section'];
            $waste_type = $_POST['waste_type'];
            $day = $_POST['day'];
            $departure = isset($_POST['departure']) ? 1 : 0;
    
            $stmt = $conn->prepare("UPDATE schedules SET city = ?, ward_number = ?, section = ?, waste_type = ?, day = ?, departure = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssssssi", $city, $ward_number, $section, $waste_type, $day, $departure, $id);
                if ($stmt->execute()) {
                    $status = 'success';
                    $message = 'Schedule updated successfully.';
                } else {
                    $status = 'error';
                    $message = 'Error: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $status = 'error';
                $message = 'Prepare failed: ' . $conn->error;
            }
        }
   elseif ($action == 'delete') {
        // Delete schedule
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM schedules WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $status = 'success';
                $message = 'Schedule deleted successfully.';
            } else {
                $status = 'error';
                $message = 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $status = 'error';
            $message = 'Prepare failed: ' . $conn->error;
        }
    }
}

    
    

// Fetch all schedules
$sql = "SELECT * FROM schedules";
$result = $conn->query($sql);

if (!$result) {
    die('Query failed: ' . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedules</title>
    <style>
    .day-select body {
        font-family: courier, monospace;
        width: 80%;
        margin: auto;
    }

    body h1 {
        color: #009600;
        margin-top:
            50px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #dcf5ee;
    }

    .form-container {
        margin-bottom: 20px;
        display: none;
    }

    .form-container form {
        margin: 0;
    }

    .form-container input,
    .form-container select {
        margin-bottom: 10px;
        display: block;
        width: 100%;
        box-sizing: border-box;
    }

    .form-container button {
        display:
            inline-block;
        margin-top: 10px;
    }

    .message {
        font-size: 1.2em;
        font-weight: bold;
    }

    .success {
        color: green;
    }

    .error {
        color: red;
    }

    .edit-mode td {
        cursor: text;
        background-color: #f9f9f9;
    }

    .waste-type-select,
    .ward-number-select,
    .section-select,
    .day-select {
        width: 100%;
        box-sizing: border-box;
    }

    .hidden {
        display:
            none;
    }

    .submit-button {
        font-size: 16px;
        background-color: #009600;
        color: white;
        cursor: pointer;
        border: 1px solid #009600;
        border-radius: 5px;
        margin-top: 10px;
        padding: 5px 10px;
    }

    .submit-button:hover {
        background-color: white;
        color: black;
    }

    .insert-button,
    .logout-button {
        text-decoration: none;
        margin: 20px 0 20px 0;
        padding: 5px 20px;
        font-size: 18px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        border:
            none;
        border-radius: 4px;
        font-family: courier, monospace;
    }

    .insert-button:hover,
    .logout-button:hover {
        background-color: #45a049;
    }

    label {
        font-weight: bold;
    }

    input {
        margin-top: 5px;
        max-width: 500px;
        padding:
            2px 10px;
        border-radius: 5px;
    }

    select {
        margin-top: 5px;
        max-width: 500px;
        border-radius: 5px;
        padding: 2px 10px;
    }
    </style>
    <script>
    // Removed redundant code

    function toggleDeparture(button) {
        var row = button.closest('tr');
        var departureValue = row.querySelector('.departure-value');
        var currentStatus = parseInt(departureValue.value); // Get current status as integer

        // Toggle the departure status
        var newStatus = currentStatus === 1 ? 0 : 1;
        departureValue.value = newStatus;

        // Update button text and color based on new status
        if (newStatus === 1) {
            button.textContent = 'Departed';
            button.style.backgroundColor = 'green'; // Green for departed
        } else {
            button.textContent = 'Departed?';
            buttstyleyle.backgroundColor = 'red'; // Red for not departed
        }
    }




    // Toggle edit mode and replace the departure button with a checkbox
    function toggleEditMode(row) {
        var isEditing = row.classList.toggle('edit-mode');

        row.querySelectorAll('.editable').forEach(cell => {
                cell.contentEditable = isEditing;

                if (isEditing) {
                    cell.focus();
                }
            }

        );

        row.querySelectorAll('.dropdown-select').forEach(select => {
                select.classList.toggle('hidden');
            }

        );
        var departureCell = row.querySelector('.departure-cell');
        var departureButton = row.querySelector('.departure-button');
        var departureCheckbox = row.querySelector('.departure-checkbox');

        if (isEditing) {
            // Show checkbox and hide button in edit mode
            departureButton.style.display = 'none';
            departureCheckbox.style.display = 'inline';
            departureCheckbox.checked = departureButton.textContent === 'Departed';
        } else {
            // Show button and hide checkbox in view mode
            departureButton.style.display = 'inline';
            departureCheckbox.style.display = 'none';
        }

        row.querySelector('.save-button').style.display = isEditing ? 'inline' : 'none';

        row.querySelectorAll('.display-value').forEach(span => {
                span.style.display = isEditing ? 'none' : 'inline';
            }

        );
    }

    // Save changes including departure status
    function saveChanges(id) {
        var row = document.getElementById('row-' + id);
        var city = row.querySelector('.editable[data-field="city"]').innerText;
        var wardNumber = row.querySelector('.ward-number-select').value;
        var section = row.querySelector('.section-select').value;
        var wasteType = row.querySelector('.waste-type-select').value;
        var day = row.querySelector('.day-select').value;
        var departure = row.querySelector('.departure-checkbox').checked ? 1 : 0; // Get value from checkbox

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        var actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'edit';
        form.appendChild(actionInput);

        var idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        form.appendChild(idInput);

        var cityInput = document.createElement('input');
        cityInput.type = 'hidden';
        cityInput.name = 'city';
        cityInput.value = city;
        form.appendChild(cityInput);

        var wardNumberInput = document.createElement('input');
        wardNumberInput.type = 'hidden';
        wardNumberInput.name = 'ward_number';
        wardNumberInput.value = wardNumber;
        form.appendChild(wardNumberInput);

        var sectionInput = document.createElement('input');
        sectionInput.type = 'hidden';
        sectionInput.name = 'section';
        sectionInput.value = section;
        form.appendChild(sectionInput);

        var wasteTypeInput = document.createElement('input');
        wasteTypeInput.type = 'hidden';
        wasteTypeInput.name = 'waste_type';
        wasteTypeInput.value = wasteType;
        form.appendChild(wasteTypeInput);

        var dayInput = document.createElement('input');
        dayInput.type = 'hidden';
        dayInput.name = 'day';
        dayInput.value = day;
        form.appendChild(dayInput);

        var departureInput = document.createElement('input');
        departureInput.type = 'hidden';
        departureInput.name = 'departure';
        departureInput.value = departure; // Updated to use checkbox value
        form.appendChild(departureInput);

        document.body.appendChild(form);
        form.submit();
    }



    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this schedule?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            var actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'delete';
            form.appendChild(actionInput);

            var idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            form.appendChild(idInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function showForm() {
        document.querySelector('.form-container').style.display = 'block';
    }
    </script>
</head>

<body>
    <h1>Manage Schedules</h1><button class="insert-button" onclick="showForm()">Add Schedule</button><a
        href="?action=logout" class="logout-button">Logout</a>
    <div class="form-container">
        <form method="POST" action=""><input type="hidden" name="action" value="add"><label
                for="city">City:</label><input type="text" id="city" name="city" required><label for="ward_number">Ward
                Number:</label><select id="ward_number" name="ward_number" required>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
            </select><label for="section">Section:</label><select id="section" name="section" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
            </select><label for="waste_type">Waste Type:</label><select id="waste_type" name="waste_type" required>
                <option value="Decomposable">Decomposable</option>
                <option value="Non-decomposable">Non-decomposable</option>
            </select><label for="day">Day:</label><select id="day" name="day" required>
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select><button type="submit" class="submit-button">Add Schedule</button></form>
    </div><?php if ($status): ?><div class="message <?php echo $status; ?>"><?php echo $message;
    ?></div><?php endif;

    ?><h2>Existing Schedules</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>City</th>
            <th>Ward Number</th>
            <th>Section</th>
            <th>Type of Waste</th>
            <th>Day</th>
            <th>Departure</th>
            <th>Actions</th>
        </tr><?php while ($row=$result->fetch_assoc()) {
        ?><tr id="row-<?php echo $row['id']; ?>">
            <td><?php echo htmlspecialchars($row['id']);
        ?></td>
            <td class="editable" data-field="city"><?php echo htmlspecialchars($row['city']);
        ?></td>
            <td><span class="display-value"><?php echo htmlspecialchars($row['ward_number']);
        ?></span><select class="dropdown-select ward-number-select hidden">
                    <option value="14" <?php if ($row['ward_number']=='14') echo 'selected';
        ?>>14</option>
                    <option value="15" <?php if ($row['ward_number']=='15') echo 'selected';
        ?>>15</option>
                    <option value="16" <?php if ($row['ward_number']=='16') echo 'selected';
        ?>>16</option>
                </select></td>
            <td><span class="display-value"><?php echo htmlspecialchars($row['section']);
        ?></span><select class="dropdown-select section-select hidden">
                    <option value="A" <?php if ($row['section']=='A') echo 'selected';
        ?>>A</option>
                    <option value="B" <?php if ($row['section']=='B') echo 'selected';
        ?>>B</option>
                    <option value="C" <?php if ($row['section']=='C') echo 'selected';
        ?>>C</option>
                    <option value="D" <?php if ($row['section']=='D') echo 'selected';
        ?>>D</option>
                    <option value="E" <?php if ($row['section']=='E') echo 'selected';
        ?>>E</option>
                </select></td>
            <td><span class="display-value"><?php echo htmlspecialchars($row['waste_type']);
        ?></span><select class="dropdown-select waste-type-select hidden">
                    <option value="Decomposable" <?php if ($row['waste_type']=='Decomposable') echo 'selected';
        ?>>Decomposable </option>
                    <option value="Non-decomposable" <?php if ($row['waste_type']=='Non-decomposable') echo 'selected';
        ?>>Non-decomposable </option>
                </select></td>
            <td><span class="display-value"><?php echo htmlspecialchars($row['day']);
        ?></span><select class="dropdown-select day-select hidden">
                    <option value="Sunday" <?php if ($row['day']=='Sunday') echo 'selected';
        ?>>Sunday</option>
                    <option value="Monday" <?php if ($row['day']=='Monday') echo 'selected';
        ?>>Monday</option>
                    <option value="Tuesday" <?php if ($row['day']=='Tuesday') echo 'selected';
        ?>>Tuesday</option>
                    <option value="Wednesday" <?php if ($row['day']=='Wednesday') echo 'selected';
        ?>>Wednesday </option>
                    <option value="Thursday" <?php if ($row['day']=='Thursday') echo 'selected';
        ?>>Thursday</option>
                    <option value="Friday" <?php if ($row['day']=='Friday') echo 'selected';
        ?>>Friday</option>
                    <option value="Saturday" <?php if ($row['day']=='Saturday') echo 'selected';
        ?>>Saturday</option>
                </select></td>
            <td class="departure-cell"><button type="button" class="departure-button" onclick="toggleDeparture(this)"
                    style="background-color: <?php echo $row['departure'] ? 'green' : 'red'; ?>; color: white; border: none; padding: 5px 10px; cursor: pointer;"><?php echo $row['departure'] ? 'Departed': 'Departed?';
        ?></button><input type="checkbox" class="departure-checkbox hidden" <?php echo $row['departure'] ? 'checked': '';
        ?>><input type="hidden" class="departure-value" value="<?php echo $row['departure']; ?>"></td>
            <td><button type="button" onclick="toggleEditMode(this.closest('tr'));">Edit</button><button type="button"
                    class="save-button hidden" onclick="saveChanges(<?php echo $row['id']; ?>);">Save</button><button
                    type="button" onclick="confirmDelete(<?php echo $row['id']; ?>);">Delete</button></td>
        </tr><?php
    }

    ?>
    </table>
</body>

</html><?php $conn->close();
    ?>