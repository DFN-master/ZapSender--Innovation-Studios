<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', 'senha123', 'send');


// Função para obter todos os contatos do usuário
function getAllContacts($userId, $conn) {
    $result = $conn->query("SELECT * FROM contacts WHERE user_id = '$userId'");
    $contacts = [];

    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row['number'];
    }

    return $contacts;
}

function getEvolutionApiInfo($userId, $conn) {
    $result = $conn->query("SELECT evolutionApiEndpoint FROM users WHERE id = '$userId'");

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function getEvolutionApiKey($userId, $conn) {
    $result = $conn->query("SELECT apikeyevo FROM users WHERE id = '$userId'");

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
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
        // Obter as informações da Evolution API para o usuário
        $evolutionApiInfo = getEvolutionApiInfo($userId, $conn);
        $api = getEvolutionApiKey($userId, $conn);

        if ($evolutionApiInfo) {
            // Utilize as informações da Evolution API para enviar mensagens

            $evolutionApiEndpoint = $evolutionApiInfo['evolutionApiEndpoint'];
            $apikey = $api['apikeyevo'];

            $apiUrl = "$evolutionApiEndpoint";
            
            // Lógica para enviar mensagem usando cURL
            if ($sendTo === 'individual') {
                $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;

                if ($phoneNumber) {
                    $postData = [
                        'number' => $phoneNumber,
                        'options' => [
                            'delay' => 1200,
                            'presence' => 'composing',
                            'linkPreview' => false
                            
                        ],
                        'textMessage' => [
                            'text' => $message,
                        ],
                    ];

                    $headers = [
                        'Content-Type: application/json',
                        'apikey:' . $apikey,
                    ];
        

                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Adicione o cabeçalho aqui
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
                            'options' => [
                                'delay' => 1200,
                                'presence' => 'composing',
                                'linkPreview' => false
                            ],
                            'textMessage' => [
                                'text' => $message,
                            ],
                        ];

                        $headers = [
                            'Content-Type: application/json',
                            'apikey:' . $apikey,
                        ];

                        $ch = curl_init($apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Adicione o cabeçalho aqui
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
            echo "Erro: Informações da Evolution API não encontradas para o usuário.\n";
        }
    } else {
        echo "Erro: Selecione uma opção válida, preencha a mensagem e forneça o ID do usuário.\n";
    }
} else {
    echo "Erro: Este script aceita apenas solicitações POST.\n";
}

//echo $apikey();
?>
