<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <style>
    /* Existing CSS styles */
    </style>
    <script>
    function sendNotification() {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        var actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'send_notifications';
        form.appendChild(actionInput);

        document.body.appendChild(form);
        form.submit();
    }
    </script>
</head>

<body>
    <h1>Send Notifications</h1>
    <button class="notification-button" onclick="sendNotification()">Send Notifications</button>
</body>

</html>