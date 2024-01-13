<?php require "API/componentes.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Enviar Mensagem</title>
    <!-- Inclua o Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Inclua o Bootstrap JS e o jQuery (versão completa) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="sendMessageForm">
                <div class="form-group">
                    <label for="apiSelection">Escolha a API:</label>
                    <select class="form-control" id="apiSelection" name="apiSelection" required>
                        <option value="twilio">Twilio</option>
                        <option value="evolution">Evolution API</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sendTo">Enviar para:</label>
                    <select class="form-control" id="sendTo" name="sendTo" required>
                        <option value="individual">Número Individual</option>
                        <option value="allContacts">Todos os Contatos</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phoneNumber">Números de WhatsApp (separados por vírgula):</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
                </div>

                <div class="form-group">
                    <label for="message">Mensagem:</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="interval">Intervalo (em minutos):</label>
                    <input type="number" class="form-control" id="interval" name="interval" required>
                </div>

                <button type="button" class="btn btn-primary btn-block" onclick="sendMessage()">Enviar Mensagem</button>
            </form>
        </div>
    </div>
</div>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="sendMessageForm" onsubmit="sendMessage(); return false;">
                <!-- Seu formulário aqui -->

                <div class="form-group">
                    <label for="log">Log de Mensagens:</label>
                    <div id="log" class="border p-3" style="max-height: 200px; overflow-y: auto;"></div>
                </div>

            
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    function sendMessage() {
        var selectedApi = document.getElementById('apiSelection').value;
        var sendTo = document.getElementById('sendTo').value;
        var phoneNumber = document.getElementById('phoneNumber').value;
        var message = document.getElementById('message').value;
        var userId = <?php echo $userId; ?>;
        var apiUrl = '';

        if (selectedApi === 'twilio') {
            apiUrl = 'API/sendAPI.php';
        } else if (selectedApi === 'evolution') {
            apiUrl = 'API/Evolution/apiSend.php';
        } else {
            alert('Selecione uma API válida.');
            return;
        }

        $.post(apiUrl, { sendTo: sendTo, phoneNumber: phoneNumber, message: message, userId: userId })
            .done(function (response) {
                // Exemplo de mensagem de sucesso
                if (selectedApi === 'twilio') {
                    displayMessage("Sucesso, mensagem enviada via Twilio para", phoneNumber, "success");
                } else if (selectedApi === 'evolution') {
                    displayMessage("Sucesso, mensagem enviada via Evolution para", phoneNumber, "success");
                }
            })
            .fail(function (error) {
                // Exemplo de mensagem de erro
                displayMessage("Erro", 'Erro ao enviar mensagem (' + selectedApi + '): ' + error.responseText, "error");
            });
    }

    function displayMessage(title, message, messageType) {
        var logContainer = $("#log");

        // Crie uma div para a mensagem
        var messageDiv = $("<div class='alert'></div>").addClass("alert-" + messageType).append("<strong>" + title + ":</strong> " + message);

        // Adicione a div ao log
        logContainer.append(messageDiv);

        // Rolar para o final do log para exibir a última mensagem
        logContainer.scrollTop(logContainer[0].scrollHeight);
    }

    var intervalId;

    function scheduleMessages() {
        var interval = document.getElementById('interval').value;

        // Validar se o intervalo é um número positivo
        if (isNaN(interval) || interval <= 0) {
            alert('Por favor, insira um intervalo válido em minutos.');
            return;
        }

        // Limpar qualquer intervalo existente antes de agendar um novo
        clearInterval(intervalId);

        // Agendar o envio de mensagens a cada intervalo definido pelo usuário
        intervalId = setInterval(function () {
            sendMessage();
        }, interval * 60 * 1000); // Converter minutos para milissegundos
    }
</script>


</body>
</html>
