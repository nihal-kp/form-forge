<!DOCTYPE html>
<html>
<head>
    <title>Form Created</title>
</head>
<body>
    <h2>New Form Created</h2>
    <p><strong>Label:</strong> {{ $form->label }}</p>
    <p><strong>Status:</strong> {{ $form->status->label() }}</p>
    <p><strong>Created At:</strong> {{ $form->created_at->format('Y-m-d H:i:s') }}</p>
</body>
</html>
