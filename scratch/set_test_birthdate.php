<?php
$conn = new mysqli('localhost', 'root', '', 'multimedia_club');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$stmt = $conn->prepare("UPDATE users SET birth_date = ?, address = ?, social_instagram = ?, social_github = ? WHERE id = 4");
$addr = 'Jl. Raya Ciapus No. 45, Tamansari, Bogor';
$ig   = 'rizkiagung_id';
$gh   = 'rizkiagungid';
$stmt->bind_param('ssss', $today, $addr, $ig, $gh);
$stmt->execute();

echo "Success! User 4 birth_date set to {$today}\n";
$conn->close();
