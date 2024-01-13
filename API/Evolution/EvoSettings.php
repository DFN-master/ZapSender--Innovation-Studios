<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli('localhost', 'root', 'senha123', 'send');


function saveEvolutionApiConfig($userId, $evolutionApiEndpoint,$apikey,$conn) {
   
    $sql = "UPDATE users 
    SET evolutionApiEndpoint='$evolutionApiEndpoint', apikeyevo='$apikey' 
    WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        return "Configurações salvas com sucesso!";
    } else {
        return "Erro ao salvar configurações: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    $evolutionApiEndpoint = isset($_POST['evolutionApiEndpoint']) ? $_POST['evolutionApiEndpoint'] : null;
    $apikey = isset($_POST['apikey']) ? $_POST['apikey'] : null;


    
    if ($userId && $evolutionApiEndpoint) {
        
        $result = saveEvolutionApiConfig($userId, $evolutionApiEndpoint,$apikey, $conn);
        echo $result;
    } else {
        echo "Erro: Preencha todas as informações necessárias.";
    }
} else {
    echo "Erro: Este script aceita apenas solicitações POST.";
}

?>
