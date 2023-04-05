<?php
// Connect to the database
$db = new mysqli('localhost', 'root', '', 'encrypt');

// Check for errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Set the encryption method
$encryption_method = "AES-256-CBC";

// Set the secret key and iv
$secret_key = 'my_secret_key';
$secret_iv = 'my_secret_iv';

// Hash the secret key and iv
$key = hash('sha256', $secret_key);
$iv = substr(hash('sha256', $secret_iv), 0, 16);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Get the value from the input field
    $value = $_POST['value'];

    // Encrypt the value
    $encrypted_value = openssl_encrypt($value, $encryption_method, $key, 0, $iv);
    $encrypted_value = base64_encode($encrypted_value);

    // Store the encrypted value in the database
    $stmt = $db->prepare("INSERT INTO my_table (value) VALUES (?)");
    $stmt->bind_param("s", $encrypted_value);

    if ($stmt->execute()) {
        // Redirect the user to the same page to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        die("Error storing value in database.");
    }
}

// Get the values from the database
$result = $db->query("SELECT * FROM my_table");

// Display the values
while ($row = $result->fetch_assoc()) {
    // Get the encrypted value from the database
    $encrypted_value = base64_decode($row['value']);

    // Decrypt the value
    $decrypted_value = openssl_decrypt($encrypted_value, $encryption_method, $key, 0, $iv);

    // Display the decrypted value
    echo "<p>" . htmlspecialchars($decrypted_value) . "</p>";
}
?>

<!-- The HTML form -->
<form method="post">
    <input type="text" name="value">
    <input type="submit" name="submit" value="Submit">
</form>