<?php
require "API/componentes.php";
$conn = new mysqli('localhost', 'root', 'senha123', 'send');

// Consulta para contar a quantidade de contatos salvos
$sqlContatos = "SELECT COUNT(*) as totalContatos FROM contacts";
$resultContatos = $conn->query($sqlContatos);
$rowContatos = $resultContatos->fetch_assoc();
$totalContatos = $rowContatos['totalContatos'];

// Consulta para obter informações do usuário
$sqlUsuario = "SELECT created_at, username, twilioNumber FROM users";
$resultUsuario = $conn->query($sqlUsuario);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard do Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .card-title {
            margin-bottom: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-text {
            color: #495057;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <?php
            while ($row = $resultUsuario->fetch_assoc()) {
            ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Informações do Usuário</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>Data de Criação:</strong> <?php echo $row['created_at']; ?></p>
                            <p class="card-text"><strong>Número Twilio:</strong> <?php echo $row['twilioNumber']; ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Estatísticas</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Quantidade de Contatos Salvos:</strong> <?php echo $totalContatos; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php
// Fechar a conexão
$conn->close();
?>
