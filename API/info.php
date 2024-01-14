<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../install/addons/config.php");
// Função para salvar as configurações do Twilio na tabela de usuários
function saveTwilioConfig($userId, $accountSid, $authToken, $twilioNumber, $conn) {
    // Query SQL para atualizar as informações do Twilio para o usuário
    $sql = "UPDATE users 
            SET accountSid='$accountSid', authToken='$authToken', twilioNumber='$twilioNumber' 
            WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        return "Configurações salvas com sucesso!";
    } else {
        return "Erro ao salvar configurações: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    $accountSid = isset($_POST['accountSid']) ? $_POST['accountSid'] : null;
    $authToken = isset($_POST['authToken']) ? $_POST['authToken'] : null;
    $twilioNumber = isset($_POST['twilioNumber']) ? $_POST['twilioNumber'] : null;

    // Verifica se todos os campos estão preenchidos
    if ($userId && $accountSid && $authToken && $twilioNumber) {
        // Tenta salvar as configurações no banco de dados
        $result = saveTwilioConfig($userId, $accountSid, $authToken, $twilioNumber, $conn);
        echo $result;
    } else {
        echo "Erro: Preencha todas as informações necessárias.";
    }
} else {
    echo "Erro: Este script aceita apenas solicitações POST.";
}

?>
