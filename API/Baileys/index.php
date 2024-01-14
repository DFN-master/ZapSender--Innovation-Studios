<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', 'senha123', 'send');


function getAllContacts($userId, $conn) {
    $result = $conn->query("SELECT * FROM contacts WHERE user_id = '$userId'");
    $contacts = [];

    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row['number'];
    }

    return $contacts;
}

$userId = isset($_POST['userId']) ? $_POST['userId'] : null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $sendTo = isset($_POST['sendTo']) ? $_POST['sendTo'] : null;
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null; 
    $message = isset($_POST['message']) ? $_POST['message'] : null;

   
    $validOptions = ['individual', 'allContacts'];
    if ($sendTo !== null && in_array($sendTo, $validOptions) && $message && $userId) {
       
        $apiUrl = "https://api.innovationstudios.online/api/messages/send";
        $tokenAPI = "106040";

        if ($sendTo === 'individual') {
            $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;

            if ($phoneNumber) {
                $postData = [
                    'number' => $phoneNumber,
                    'body' => $message,
                ];

                $headers = [
                    'X_TOKEN: ' . $tokenAPI,
                    'Content-Type: application/json',
                ];

                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    echo "Mensagem enviada para {$phoneNumber}\n";
                } else {
                    echo "Erro ao enviar mensagem: {$response}\n";
                }
            } else {
                echo "Erro: Número de telefone ausente.\n";
            }
        } elseif ($sendTo === 'allContacts') {
            $contacts = getAllContacts($userId, $conn);

            if (empty($contacts)) {
                echo "Erro: Não há contatos para enviar mensagem.\n";
            } else {
                foreach ($contacts as $contact) {
                    $postData = [
                        'number' => $contact,
                        'body' => $message,
                    ];

                    $headers = [
                        'X_TOKEN: ' . $tokenAPI,
                        'Content-Type: application/json',
                    ];

                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode === 200) {
                        echo "Mensagem enviada para {$contact}\n";
                    } else {
                        echo "Erro ao enviar mensagem para {$contact}: {$response}\n";
                    }
                }

                echo "Mensagens enviadas para todos os contatos.\n";
            }
        }
    } else {
        echo "Erro: Selecione uma opção válida, preencha a mensagem e forneça o ID do usuário.\n";
    }
} else {
    echo "Erro: Este script aceita apenas solicitações POST.\n";
}
?>
