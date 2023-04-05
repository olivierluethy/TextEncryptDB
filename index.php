<?php
// Set up encryption key and initialization vector
$encryption_key = 'my-secret-key-123';
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
  // Get text from form submission
  $text = $_POST['text'];
  
  // Encrypt the text using AES-256-CBC
  $encrypted_text = openssl_encrypt($text, 'aes-256-cbc', $encryption_key, 0, $iv);
  
  // Save the encrypted text and initialization vector to the database
  $servername = 'localhost';
  $username = 'root';
  $password = '';
  $dbname = 'encrypt';
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
  }

  // Encode encrypted text and initialization vector as base64
  $encrypted_text = base64_encode($encrypted_text);
  $iv = base64_encode(str_pad($iv, 16, "\0"));

  // Insert encrypted text and initialization vector into database
  $sql = "INSERT INTO encrypted_text (encrypted_data, iv) VALUES ('$encrypted_text', '$iv')";
  $result = $conn->query($sql);

  // Check if insert was successful
  if (!$result) {
    die('Error inserting encrypted text into database: ' . $conn->error);
  } else {
    echo 'Encrypted text added to database.';
  }

  // Close connection
  $conn->close();
  
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
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'encrypt';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Select all encrypted data and initialization vectors from database
$sql = "SELECT encrypted_data, iv FROM encrypted_text";
$result = $conn->query($sql);

// Check if any encrypted data was found
if ($result->num_rows > 0) {
  // Decrypt each row and display it
  while($row = $result->fetch_assoc()) {
    // Decode base64 encoded encrypted data and initialization vector
    $encrypted_text = base64_decode($row['encrypted_data']);
    $iv = base64_decode($row['iv']);

    // Pad initialization vector with null bytes to be 16 bytes long
    $iv = str_pad($iv, 16, "\0");

    // Decrypt the encrypted data using AES-256-CBC
    $decrypted_text = openssl_decrypt($encrypted_text, 'aes-256-cbc', $encryption_key, 0, $iv);
    
    // Display decrypted text
    echo "<p>$decrypted_text</p>";
  }
} else {
  echo "No encrypted text found in database.";
}

// Close connection
$conn->close();
?>