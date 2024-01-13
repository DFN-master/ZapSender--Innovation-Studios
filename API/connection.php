<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "senha123";
$dbname = "send";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addContact') {
    $user_id = $_SESSION['user_id']; 
    $name = $_POST['name']; 
    $number = formatPhoneNumber($_POST['number']);

    $sql = "INSERT INTO contacts (user_id, name, number) VALUES ($user_id, '$name', '$number')";

    if ($conn->query($sql) === TRUE) {
        echo "Contato adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar contato: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'importContacts') {
    $user_id = $_SESSION['user_id']; 
    $file = $_FILES['file'];


    if ($file['type'] === 'text/csv') {
        $csvData = file_get_contents($file['tmp_name']);
        $csvArray = array_map("str_getcsv", explode("\n", $csvData));

        
        $header = array_shift($csvArray);

     
        foreach ($csvArray as $row) {
            $name = '';
            $number = '';

            foreach ($row as $value) {
                $value = trim($value);

               
                if (preg_match("/[a-zA-Z]/", $value)) {
                    $name .= $value . ' ';
                }

                
                if (preg_match("/[0-9]/", $value)) {
                    $number .= $value;
                }
            }

    
            $name = trim(preg_replace("/[^a-zA-Z0-9À-ú\s]/", '', $name));
            $number = trim(preg_replace("/[^0-9]/", '', $number));

            
            $number = formatPhoneNumber($number);

          
            $sql = "INSERT INTO contacts (user_id, name, number) VALUES ($user_id, '$name', '$number')";

            if ($conn->query($sql) !== TRUE) {
                echo "Erro ao adicionar contato: " . $conn->error;
                exit; 
            }
        }

        echo "Contatos importados com sucesso!";
    } else {
        echo "Formato de arquivo inválido. Por favor, envie um arquivo CSV.";
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getContacts') {
    $user_id = $_SESSION['user_id']; 
    $result = $conn->query("SELECT * FROM contacts WHERE user_id = $user_id");

    $contacts = [];

    while ($row = $result->fetch_assoc()) {
      
        $contacts[] = ['name' => $row['name'], 'number' => formatPhoneNumber($row['number'])];
    }

    echo json_encode($contacts);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'removeContact') {
    $contact_id = isset($_POST['contactId']) ? intval($_POST['contactId']) : 0;

    if ($contact_id > 0) {
        $sql = $conn->prepare("DELETE FROM contacts WHERE id = '$user_id'");
        $sql->bind_param("i", $contact_id);

        if ($sql->execute()) {
            echo "Contato removido com sucesso!";
        } else {
            echo "Erro ao remover contato: " . $conn->error;
        }

        $sql->close();
    } else {
        echo "ID de contato inválido.";
    }
}




function formatPhoneNumber($number) {
  
    if (strpos($number, 'whatsapp:') === 0) {
        return $number;
    } else {
        return 'whatsapp:' . $number;
    }
}

$conn->close();
?>