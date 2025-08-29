<?php
require 'db.php';

$fields = ['first_name','last_name','email','interest','message'];
foreach ($fields as $f) { $$f = trim($_POST[$f] ?? ''); }

if ($first_name === '' || $last_name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: contact.php?error=1');
  exit;
}

$stmt = $pdo->prepare("INSERT INTO contact(first_name,last_name,email,interest,message) VALUES(?,?,?,?,?)");
$stmt->execute([$first_name,$last_name,$email,$interest,$message]);

header('Location: contact.php?success=1');
exit;
?>
