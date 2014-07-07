<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

    <p>This is a reminder about your Video Conference scheduled for {{ date('F j, Y \a\t g:i a', strtotime($conference['scheduled_date'])) }}. </p>
      
    <p>{{ $globalAdmin['name'] }} will be the Content Launch professional who will be addending with you.  If you have any issues with the schedule, please contact {{ $globalAdmin['name'] }} at {{ $globalAdmin['email'] }} before the scheduled time.</p>
     
    <p>Thank you and we look forward to meeting with you.</p>
     
    <p>Content Launch Team</p>

</body>
</html>