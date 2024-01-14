<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Instalação do Banco de Dados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .logo-container {
            position: fixed;
            top: 0;
            left: 0;
            padding: 10px;
            background: linear-gradient(to right, #343a40, #007bff);
            color: #ffffff;
            text-align: center;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .logo {
            font-size: 24px;
            font-weight: 500;
        }

        .card {
            margin-top: 80px; /* Ajuste conforme necessário */
        }

        .footer {
            background: linear-gradient(to right, #343a40, #007bff);
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="logo-container">
    <span class="logo-text">Innovation Studios - Ravocxx</span>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Configuração do Banco de Dados</h5>
                </div>
                <div class="card-body">
                    <form id="installForm">
                        <div class="mb-3">
                            <label for="dbHost" class="form-label">Host do Banco de Dados</label>
                            <input type="text" class="form-control" id="dbHost" name="dbHost" required>
                        </div>
                        <div class="mb-3">
                            <label for="dbUser" class="form-label">Usuário do Banco de Dados</label>
                            <input type="text" class="form-control" id="dbUser" name="dbUser" required>
                        </div>
                        <div class="mb-3">
                            <label for="dbPassword" class="form-label">Senha do Banco de Dados</label>
                            <input type="password" class="form-control" id="dbPassword" name="dbPassword">
                        </div>
                        <div class="mb-3">
                            <label for="dbName" class="form-label">Nome do Banco de Dados</label>
                            <input type="text" class="form-control" id="dbName" name="dbName" required>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" onclick="installDatabase()">Instalar Banco de Dados</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 Innovation Studios - Ravocxx. Todos os direitos reservados.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function installDatabase() {
    var dbHost = document.getElementById('dbHost').value;
    var dbUser = document.getElementById('dbUser').value;
    var dbPassword = document.getElementById('dbPassword').value;
    var dbName = document.getElementById('dbName').value;

    var apiUrl = 'addons/database.php';
    var formData = new FormData();
    formData.append('dbHost', dbHost);
    formData.append('dbUser', dbUser);
    formData.append('dbPassword', dbPassword);
    formData.append('dbName', dbName);

    fetch(apiUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Ocorreu um erro durante a instalação do banco de dados. Por favor, verifique suas configurações.',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Banco de Dados Instalado!',
                text: 'A configuração do banco de dados foi concluída com sucesso.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Erro durante a requisição:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Ocorreu um erro durante a instalação do banco de dados. Por favor, verifique suas configurações.',
            confirmButtonText: 'OK'
        });
    });
}
</script>

</body>
</html>
