<?php require "API/componentes.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gerenciamento de Contatos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title">Adicionar Contato</h5>
                </div>
                <div class="card-body">
                    <form id="addContactForm">
                        <div class="form-group">
                            <label for="contactName">Nome:</label>
                            <input type="text" class="form-control" id="contactName" name="contactName" required>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Número de WhatsApp:</label>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addContact()">Adicionar Contato</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Importar Contatos</h5>
                </div>
                <div class="card-body">
                    <input type="file" id="fileInput" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    <button type="button" class="btn btn-info mt-2" onclick="importContacts()">Importar Contatos</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title">Contatos Cadastrados</h5>
                </div>
                <div class="card-body" id="contactList">
                  
                    <ul class="list-group">
                        <?php foreach ($contacts as $contact): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
                                <div>
                                    <strong>Nome:</strong> <?php echo $contact['name']; ?><br>
                                    <strong>Número:</strong> <?php echo $contact['number']; ?>
                                </div>
                               
                                <button class="btn btn-danger btn-sm" onclick="removeContact(<?php echo $contact['id']; ?>)">Remover</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        getContacts();
    });

    function addContact() {
        const name = $('#contactName').val();
        const number = $('#contactNumber').val();

        $.ajax({
            url: 'API/connection.php',
            type: 'POST',
            dataType: 'text',
            data: { action: 'addContact', name: name, number: number },
            success: function (response) {
                console.log(response);
                getContacts();
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    function getContacts() {
        $.ajax({
            url: 'API/connection.php',
            type: 'GET',
            dataType: 'json',
            data: { action: 'getContacts' },
            success: function (response) {
                console.log(response);
                displayContacts(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    function importContacts() {
        var fileInput = document.getElementById('fileInput');
        var file = fileInput.files[0];

        if (file) {
            var formData = new FormData();
            formData.append('action', 'importContacts');
            formData.append('file', file);

            $.ajax({
                url: 'API/connection.php',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    console.log(response);
                    getContacts();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        } else {
            alert('Selecione um arquivo para importar.');
        }
    }

    function displayContacts(contacts) {
        $('#contactList').empty();

        contacts.forEach(function (contact) {
            var listItem = $('<li>').addClass('list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn');
            listItem.attr('data-contact-id', contact.id);

            var contactInfo = $('<div>').html(
                '<strong>Nome:</strong> ' + contact.name + '<br>' +
                '<strong>Número:</strong> ' + contact.number
            );

            var removeButton = $('<button>').addClass('btn btn-danger btn-sm remove-contact').text('Remover');
            removeButton.click(function () {
                removeContact(contact.id);
            });

            listItem.append(contactInfo, removeButton);
            $('#contactList').append(listItem);
        });
    }

    function removeContact(contactId) {
        console.log('Removendo contato com ID:', contactId);

        $.ajax({
            url: 'API/connection.php',
            type: 'POST',
            dataType: 'text',
            data: { action: 'removeContact', contactId: contactId },
            success: function (response) {
                console.log(response);
                getContacts();
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
</script>

</body>
</html>
