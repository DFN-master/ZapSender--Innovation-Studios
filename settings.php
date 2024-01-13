<?php require "API/componentes.php"; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Configurações Twilio e Evolution API</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>



<div class="container mt-5">
    <!-- Card de Aviso de Segurança -->
    <div class="card text-white bg-danger mb-3">
        <div class="card-body">
            <p class="card-text">Por razões de segurança, as informações nesta página não são exibidas</p>

        </div>
    </div>

    <div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Configurações Twilio</h5>
                </div>
                <div class="card-body">
                    <form id="twilioConfigForm">
                        <div class="form-group">
                            <label for="accountSid">Account SID:</label>
                            <input type="text" class="form-control" id="accountSid" name="accountSid" required>
                        </div>
                        <div class="form-group">
                            <label for="authToken">Auth Token:</label>
                            <input type="text" class="form-control" id="authToken" name="authToken" required>
                        </div>
                        <div class="form-group">
                            <label for="twilioNumber">Twilio Number:</label>
                            <input type="text" class="form-control" id="twilioNumber" name="twilioNumber" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="saveTwilioConfig()">Salvar Configurações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Configurações Evolution API</h5>
                </div>
                <div class="card-body">
                    <form id="evolutionApiConfigForm">
                        <div class="form-group">
                            <label for="evolutionApiEndpoint">Evolution API Endpoint:</label>

                
                            <input type="text" class="form-control" id="apikey" name="apikey" value="<?php echo $apikeyevo; ?>" required>
                       

                            </div>
                        <!-- Adicione um novo campo para a apikey -->
                        <div class="form-group">
    <label for="apikey">API Key:</label>
    <input type="text" class="form-control" id="apikey" name="apikey" value="<?php echo isset($_SESSION['apikeyevo']) ? $_SESSION['apikeyevo'] : ''; ?>" required>
</div>


                        <!-- Adicione um campo oculto para armazenar a sessão apikey -->
                        <input type="hidden" id="session_apikey" name="session_apikey" value="<?php echo $_SESSION['apikey']; ?>">

                        <button type="button" class="btn btn-primary" onclick="saveEvolutionApiConfig()">Salvar Configurações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function saveEvolutionApiConfig() {
    var userId = <?php echo $userId; ?>;
    var evolutionApiEndpoint = document.getElementById('evolutionApiEndpoint').value;
    var apikey = document.getElementById('apikey').value;

    console.log("User ID:", userId);
    console.log("Evolution API Endpoint:", evolutionApiEndpoint);
    console.log("API Key:", apikey);

    var apiUrl = 'API/Evolution/EvoSettings.php';

    // Envie tanto evolutionApiEndpoint quanto apikey para o PHP
    $.post(apiUrl, { userId: userId, evolutionApiEndpoint: evolutionApiEndpoint, apikey: apikey })
        .done(function (response) {
            alert(response);
        })
        .fail(function (error) {
            alert('Erro ao salvar configurações: ' + error.responseText);
        });
}

function saveTwilioConfig() {
    var userId = <?php echo $userId; ?>;
    var accountSid = document.getElementById('accountSid').value;
    var authToken = document.getElementById('authToken').value;
    var twilioNumber = document.getElementById('twilioNumber').value;

    console.log("User ID:", userId);
    console.log("Account SID:", accountSid);
    console.log("Auth Token:", authToken);
    console.log("Twilio Number:", twilioNumber);

    var apiUrl = 'API/info.php';

    $.post(apiUrl, { userId: userId, accountSid: accountSid, authToken: authToken, twilioNumber: twilioNumber })
        .done(function (response) {
            alert(response);
        })
        .fail(function (error) {
            alert('Erro ao salvar configurações: ' + error.responseText);
        });
}
</script>

</body>
</html>
