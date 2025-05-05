$(document).ready(function () {

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        if (!email || !password) {
            alert('Por favor, completa todos los campos.');
            return;
        }

        $.ajax({
            url: url + "Persona/login",
            dataType: 'json',
            method: 'POST',
            data: {
                email: email,
                password: password
            }
        }).done(function (response) {
            // alert(response);
            if (response.status === 'success') {
                location.href = url + 'admin/HomeFormulario';
            } else {
                alert(response.message);
            }
        }).fail(function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            alert('Ocurrió un error al iniciar sesión. Inténtalo de nuevo.');
        });
    });

    //registrar nuevo usuario
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
    
        var name = $('#name').val();
        var lastName = $('#lastName').val();
        var motherLastName = $('#motherLastName').val();
        var email = $('#registerEmail').val();
        var password = $('#registerPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        // alert('nombre: ' + name + ' apellido: ' + lastName + ' apellido materno: ' + motherLastName + ' email: ' + email + ' password: ' +  password + ' confirmar password: ' + confirmPassword);

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden. Por favor, verifica.');
            return;
        }
    
        if (!name || !lastName || !motherLastName || !email || !password || !confirmPassword) {
            alert('Por favor, completa todos los campos.');
            return;
        }
    
        $.ajax({
            url: url + "Persona/register",
            dataType: 'json',
            method: 'POST',
            data: {
                name: name,
                lastName: lastName,
                motherLastName: motherLastName,
                email: email,
                password: password
            }
        }).done(function (response) {
            // alert(response);
            // console.log("Respuesta cruda:", response);
            if (response.status === 'success') {
                location.href = url + 'home/index';
            } else {
                alert(response.message);
            }
        }).fail(function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Ocurrió un error al registrar. Inténtalo de nuevo.');
        });
    });
});