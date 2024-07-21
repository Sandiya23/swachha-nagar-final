<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departures</title>
    <style>
    /* Existing CSS styles */
    .departure-button {
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
    }

    .departure-button.red {
        background-color: red;
    }

    .departure-button.green {
        background-color: green;
    }
    </style>
    <script>
    function toggleDeparture(button) {
        var row = button.closest('tr');
        var isDeparted = button.classList.contains('green');

        button.classList.toggle('green', !isDeparted);
        button.classList.toggle('red', isDeparted);
        button.innerText = isDeparted ? 'Departed?' : 'Departed';

        var departureInput = row.querySelector('.departure-value');
        departureInput.value = isDeparted ? '0' : '1';
    }
    </script>
</head>

<body>
    <h1>Manage Departures</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Departure</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM schedules");
        while ($row = $result->fetch_assoc()) :
            $isDeparted = $row['departure'] == 1;
        ?>
        <tr id="row-<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td>
                <button class="departure-button <?php echo $isDeparted ? 'green' : 'red'; ?>"
                    onclick="toggleDeparture(this)">
                    <?php echo $isDeparted ? 'Departed' : 'Departed?'; ?>
                </button>
                <input type="hidden" class="departure-value" name="departure" value="<?php echo $row['departure']; ?>">
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>