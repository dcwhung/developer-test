<?php
    /**
    * @Author: Dennis L.
    * @Test: 4
    * @TimeLimit: 30 minutes
    * @Testing: Input Sanitation
    */

    // Fix this so there are no SQL Injection attacks, no chance for a man-in-the-middle attack (e.g., use something to determine if the input was changed), and limit the chances of
    // brute-forcing this credential system to gain entry. Feel free to change any part of this code.
    
    /**
     * Test cases
     * http://localhost:8000/test4.php?username=root&password=secret
     * http://localhost:8000/test4.php?username=<script>123</script>&password=<?=echo '123';?> 
     */

    $username = @$_GET['username'] ? $_GET['username'] : '';
    $password = @$_GET['password'] ? $_GET['password'] : '';

    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_ENCODED);
    $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_ENCODED);

    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("CREATE TABLE users (username VARCHAR(255), password VARCHAR(255))");
    
    $rootPassword = md5("secret");

    $pdo->prepare("INSERT INTO users (username, password) 
                    VALUES (:username, :password);")
        ->execute(['username' => 'root', 'password' => $rootPassword]);
    
    $password = md5($password);
    $statement = $pdo->prepare("SELECT * 
                                FROM users 
                                WHERE username = :username 
                                AND password = :password");
    $statement->execute(['username' => $username, 'password' => $password]);
    
    if (count($statement->fetchAll()) > 0)
        echo "Access granted to $username!<br>\n";
    else
        echo "Access denied for $username!<br>\n";
