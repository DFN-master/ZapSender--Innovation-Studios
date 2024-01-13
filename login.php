<?php
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Campanha ZapSend</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Innovation Chat - Login</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="login.html">Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Login ou Registro</h2>
                </div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="username">Nome de Usuário:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                        <button type="button" class="btn btn-success" onclick="register()">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $("#loginForm").submit(function(e) {
        e.preventDefault();

        const username = $('#username').val();
        const password = $('#password').val();

        $.ajax({
            url: 'auth/auth_acess.php',
            type: 'POST',
            dataType: 'text',
            data: { action: 'login', username: username, password: password },
            success: function (response) {
                response = response.trim();

                if (response && response.includes("success")) {
                    try {
    const jsonResponse = JSON.parse(response);

    if (jsonResponse.success) {
        Swal.fire({
            icon: 'success',
            title: 'Login bem-sucedido!',
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Credenciais inválidas.',
            text: 'Por favor, verifique seu nome de usuário e senha.'
        });
    }
} catch (error) {
    console.error(error);
    Swal.fire({
        icon: 'error',
        title: 'Erro na análise do JSON.',
        text: `Detalhes do erro: ${error.message}`
    });
}

                }
            },
            error: function (error) {
                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Erro na comunicação com o servidor.',
                    text: 'Por favor, tente novamente mais tarde.'
                });
            }
        });
    });
});
</script>

</body>
</html>
