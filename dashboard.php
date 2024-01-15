<?php
require "API/componentes.php";

// Função para consultar a quantidade de contatos salvos
function getTotalContatos($conn) {
    $sqlContatos = "SELECT COUNT(*) as totalContatos FROM contacts";
    $resultContatos = $conn->query($sqlContatos);

    if ($resultContatos) {
        $rowContatos = $resultContatos->fetch_assoc();
        return $rowContatos['totalContatos'];
    } else {
        return "Erro na consulta de contatos: " . $conn->error;
    }
}

// Função para obter informações do usuário
function getUsuarioInfo($conn) {
    $sqlUsuario = "SELECT created_at, username, twilioNumber FROM users";
    $resultUsuario = $conn->query($sqlUsuario);

    if ($resultUsuario) {
        return $resultUsuario;
    } else {
        return "Erro na consulta de usuário: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... (mesmo código) -->
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <?php
            $resultUsuario = getUsuarioInfo($conn);

            if ($resultUsuario instanceof mysqli_result) {
                while ($row = $resultUsuario->fetch_assoc()) {
            ?>
                    <div class="col-md-6">
                        <!-- ... (mesmo código) -->
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-danger'>$resultUsuario</p>";
            }
            ?>

            <div class="col-md-6">
                <div class="card">
                    <!-- ... (mesmo código) -->
                </div>
            </div>
        </div>
    </div>

    <!-- ... (mesmo código) -->

</body>

</html>

<?php
// Fechar a conexão
$conn->close();
?>
