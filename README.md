# End-to-End Encrypted Data Storage and Retrieval
This code demonstrates how to store and retrieve encrypted data in a MySQL database using PHP.

## Getting Started
1. Create a MySQL database with a table named "my_table" that has a single column called "value".
2. Copy the code into a PHP file and save it in a web server directory.
3. Update the database connection settings in the code to match your own database.
4. Set your own secret key and initialization vector (IV) in the code.

## Usage
The code provides an HTML form that accepts input data to be encrypted and stored in the database. Once submitted, the data is encrypted using the AES-256-CBC encryption method and then stored in the database.

The encrypted data is retrieved from the database, decrypted, and displayed on the web page.

## Encryption
The encryption key and IV are created using a SHA-256 hash of a secret key and IV, respectively.

The encryption method used is AES-256-CBC, which is a secure encryption algorithm.

## Security
This code provides end-to-end encryption, which means that the data is encrypted before it is stored in the database and decrypted when it is retrieved. As a result, even if the database is compromised, the data remains encrypted and cannot be read without the encryption key and IV.

However, this code has some potential security weaknesses that need to be addressed to ensure maximum security:

1. The secret key and IV are hardcoded in the code, which is a potential security risk. It is recommended to use a secure key management system to generate and store these values.
2. The code does not include any form of user authentication, which means that anyone with access to the page can submit and retrieve encrypted data. It is recommended to implement user authentication and access control to ensure that only authorized users can access and modify data.

## Credits
This code was written by a programmer and should be used at your own risk. The code is provided as-is and without any warranty or support.
