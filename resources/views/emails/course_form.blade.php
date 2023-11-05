<!-- resources/views/emails/your_mail_template.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>Contact Form Submission</h1>
    <p><strong>Name:</strong> {{ $emailData['name'] }} {{ $emailData['surname'] }}</p>
    <p><strong>Email:</strong> {{ $emailData['email'] }}</p>
    <p><strong>Phone Number:</strong> {{ $emailData['phoneNumber'] }}</p>
    <p><strong>Message:</strong> {{ $emailData['message'] }}</p>
</body>
</html>
