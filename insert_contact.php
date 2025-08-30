<?php
require 'db.php';

// Added 'phone' field
$fields = ['first_name', 'last_name', 'email', 'phone', 'interest', 'message'];
foreach ($fields as $f) {
    $$f = trim($_POST[$f] ?? '');
}

//  Basic validation
if (
    $first_name === '' ||
    $last_name === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone) // Phone must be 10 digits
) {
    header('Location: contact.php?error=1');
    exit;
}

//  Insert into database
$stmt = $pdo->prepare("INSERT INTO contact(first_name, last_name, email, phone, interest, message) VALUES(?,?,?,?,?,?)");
if ($stmt->execute([$first_name, $last_name, $email, $phone, $interest, $message])) {
    header('Location: contact.php?status=success');
} else {
    header('Location: contact.php?status=failed');
}
exit;
?>
