<?php

session_start();
header('Content-Type: application/json');


include("../install/addons/config.php");


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['success' => true, 'user_id' => $user['id']]);
        } else {
            echo json_encode(['error' => 'Credenciais inválidas']);
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'register') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($query) === TRUE) {
            $userId = $conn->insert_id;
            $_SESSION['user_id'] = $userId;
            echo json_encode(['success' => true, 'user_id' => $userId]);
        } else {
            echo json_encode(['error' => 'Erro ao registrar o usuário']);
        }
    }
}

$conn->close();
?>
