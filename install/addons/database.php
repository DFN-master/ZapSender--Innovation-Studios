<?php
header('Content-Type: application/json');

$response = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $dbHost = $_POST['dbHost'];
    $dbUser = $_POST['dbUser'];
    $dbPassword = $_POST['dbPassword'];
    $dbName = $_POST['dbName'];

    $conn = new mysqli($dbHost, $dbUser, $dbPassword);

    if ($conn->connect_error) {
        $response['error'] = "Erro na conexão com o banco de dados: " . $conn->connect_error;
    } else {
        $createDBQuery = "CREATE DATABASE IF NOT EXISTS $dbName";
        if ($conn->query($createDBQuery) === TRUE) {
            $conn->select_db($dbName);

            // Crie a tabela users
            $createUsersTableQuery = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                accountSid VARCHAR(255),
                authToken VARCHAR(255),
                twilioNumber VARCHAR(255),
                evolutionApiEndpoint VARCHAR(255),
                apikeyevo VARCHAR(255)
            )";
            if ($conn->query($createUsersTableQuery) === TRUE) {
               
                $username = 'admin@innovationstudios.online';
                $password = 'senha@123';
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertUserQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
                if ($conn->query($insertUserQuery) === TRUE) {
                    $response['success'][] = "Usuário inserido com sucesso!";
                } else {
                    $response['error'][] = "Erro ao inserir usuário: " . $conn->error;
                }
            } else {
                $response['error'][] = "Erro ao criar tabela 'users': " . $conn->error;
            }

          
            $createContactsTableQuery = "CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                number VARCHAR(255) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )";
            if ($conn->query($createContactsTableQuery) === TRUE) {
               
                $insertContactsQuery = "INSERT INTO contacts (user_id, number) VALUES
                    (1, '5551234567'),
                    (1, '5559876543')";
                if ($conn->query($insertContactsQuery) === TRUE) {
                    $response['success'][] = "Contatos inseridos com sucesso!";
                } else {
                    $response['error'][] = "Erro ao inserir contatos: " . $conn->error;
                }
            } else {
                $response['error'][] = "Erro ao criar tabela 'contacts': " . $conn->error;
            }

            
            $configContent = "<?php\n\n";
            $configContent .= "\$servername = '$dbHost';\n";
            $configContent .= "\$username = '$dbUser';\n";
            $configContent .= "\$password = '$dbPassword';\n";
            $configContent .= "\$dbname = '$dbName';\n\n";
            $configContent .= "\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);\n\n";
            $configContent .= "if (\$conn->connect_error) {\n";
            $configContent .= "    die(\"Erro na conexão com o banco de dados: \" . \$conn->connect_error);\n";
            $configContent .= "}\n\n";
            $configContent .= "?>";

            $configFilePath = 'config.php';

            if (file_put_contents($configFilePath, $configContent) !== false) {
                $response['success'][] = "Arquivo de configuração criado com sucesso!";
            } else {
                $response['error'][] = "Erro ao criar o arquivo de configuração.";
            }
        } else {
            $response['error'][] = "Erro ao criar banco de dados: " . $conn->error;
        }

       
        $conn->close();
    }
} else {
    $response['error'][] = "Este script aceita apenas solicitações POST.";
}

echo json_encode($response);
?>
