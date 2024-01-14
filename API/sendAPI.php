<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../install/addons/config.php");

// Função para obter todos os contatos do usuário
function getAllContacts($userId, $conn) {
    $result = $conn->query("SELECT * FROM contacts WHERE user_id = '$userId'");
    $contacts = [];

    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row['number'];
    }

    return $contacts;
}

function getTwilioInfo($userId, $conn) {
    $result1 = $conn->query("SELECT accountSid, authToken, twilioNumber FROM users WHERE id = '$userId'");

    if ($result1->num_rows > 0) {
        return $result1->fetch_assoc();
    } else {
        return null;
    }
}

$userId = isset($_POST['userId']) ? $_POST['userId'] : null; // Certifique-se de obter o ID do usuário

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $sendTo = isset($_POST['sendTo']) ? $_POST['sendTo'] : null;
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null; // Adicione esta linha para obter o ID do usuário
    $message = isset($_POST['message']) ? $_POST['message'] : null;

    // Verifique se a opção sendTo é válida
    $validOptions = ['individual', 'allContacts'];
    if ($sendTo !== null && in_array($sendTo, $validOptions) && $message && $userId) {
        // Obter as informações do Twilio para o usuário
        $twilioInfo = getTwilioInfo($userId, $conn);

        if ($twilioInfo) {
            // Utilize as informações do Twilio para enviar mensagens

            $accountSid = $twilioInfo['accountSid'];
            $authToken = $twilioInfo['authToken'];
            $twilioNumber = $twilioInfo['twilioNumber'];

            //echo "Informações do Twilio lidas com sucesso:\n";
           // echo "Account SID: $accountSid\n";
           // echo "Auth Token: $authToken\n";
          //  echo "Twilio Number: $twilioNumber\n";
            $apiUrl = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

            // Lógica para enviar mensagem usando cURL
            if ($sendTo === 'individual') {
                $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;

                if ($phoneNumber) {
                    $postData = [
                        'From' => $twilioNumber,
                        'To' => $phoneNumber,
                        'Body' => $message,
                    ];

                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                    curl_setopt($ch, CURLOPT_USERPWD, "{$accountSid}:{$authToken}");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilita a verificação SSL
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode === 201) {
                        $responseData = json_decode($response, true);
                        echo "Mensagem enviada para {$phoneNumber}: SID {$responseData['sid']}\n";
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
                            'From' => $twilioNumber,
                            'To' => $contact,
                            'Body' => $message,
                        ];

                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                        curl_setopt($ch, CURLOPT_USERPWD, "{$accountSid}:{$authToken}");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilita a verificação SSL
                        $response = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        if ($httpCode === 201) {
                            $responseData = json_decode($response, true);
                            echo "Mensagem enviada para {$contact}: SID {$responseData['sid']}\n";
                        } else {
                            echo "Erro ao enviar mensagem para {$contact}: {$response}\n";
                        }
                    }

                    echo "Mensagens enviadas para todos os contatos.\n";
                }
            }
        } else {
            echo "Erro: Informações do Twilio não encontradas para o usuário.\n";
        }
    } else {
        echo "Erro: Selecione uma opção válida, preencha a mensagem e forneça o ID do usuário.\n";
    }
} else {
    echo "Erro: Este script aceita apenas solicitações POST.\n";
}
?>
