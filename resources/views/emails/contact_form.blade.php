<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
</head>
<body>
    <h1>Contact Form Submission</h1>
    <p><strong>Name:</strong> {{ $emailData['firstName'] }} {{ $emailData['lastName'] }}</p>
    <p><strong>Email:</strong> {{ $emailData['email'] }}</p>
    <p><strong>Phone Number:</strong> {{ $emailData['phoneNumber'] }}</p>
    <p><strong>City:</strong> {{ $emailData['city'] }}</p>
    <p><strong>State:</strong> {{ $emailData['state'] }}</p>
    <p><strong>Course:</strong> {{ $emailData['course'] }}</p>
    <p><strong>Course Type:</strong> {{ $emailData['courseType'] }}</p>
    <p><strong>Course Type:</strong> {{ $emailData['message'] }}</p>
</body>
</html>
