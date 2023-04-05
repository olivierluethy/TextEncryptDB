<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "encrypt";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
  $text = $_POST['text'];
  $encrypted_text = base64_encode(openssl_encrypt($text, "AES-128-ECB", "encryption_key"));
  $sql = "INSERT INTO encrypted_text (text) VALUES ('$encrypted_text')";
  $result = $conn->query($sql);
  
  // Redirect to prevent form resubmission
  header('Location: ' . $_SERVER['REQUEST_URI']);
  exit;
}

// Display input form
echo '<form method="post">';
echo '<input type="text" name="text" placeholder="Enter text to encrypt">';
echo '<input type="submit" value="Encrypt and Add to Database">';
echo '</form>';

// Retrieve encrypted text from database and decrypt
$sql = "SELECT text FROM encrypted_text";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $encrypted_text = $row["text"];
    $decrypted_text = openssl_decrypt(base64_decode($encrypted_text), "AES-128-ECB", "encryption_key");
    echo "<p>$decrypted_text</p>";
  }
} else {
  echo "No encrypted text found in database.";
}

$conn->close();
?>
