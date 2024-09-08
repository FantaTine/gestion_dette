<!-- resources/views/emails/fidelity_card.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Your New Fidelity Card</title>
</head>
<body>
    <h1>Welcome, {{ $client->user->nom }} {{ $client->user->prenom }}!</h1>
    <p>Thank you for joining our fidelity program. Your new fidelity card is attached to this email.</p>
    <p>Your client ID is: {{ $client->id }}</p>
    <p>If you have any questions, please don't hesitate to contact us.</p>
</body>
</html>
