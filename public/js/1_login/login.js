$(document).ready(function () {
    //Refresh captcha image
    $(".change-captcha").click(function(){
        var rnd = new Date().getTime();
        var src = $("img.captcha-img").attr("src");

        if (src.indexOf("?")!=-1) {
            var ind = src.indexOf("?");
            src = src.substr(0, ind);
        }

        src += "?"+rnd;
        $("img.captcha-img").attr("src", src);
        $("#verify").val("");
    });

    // Iniciar sesión
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        var email = jQuery("#email").val();
        var password = jQuery("#password").val();
        var verify = jQuery("#verify").val();

        // Limpiar mensajes anteriores y mostrar carga
        $("#message").empty().html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Procesando...</div>');

        var params = {
            "email": email,
            "password": password,
            "verify": verify
        };

        if (!email || !password || !verify) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
            });
            return;
        }

        $.ajax({
            url: url + "Persona/login",
            dataType: 'json',
            method: 'POST',
            data: params
        }).done(function (response, textStatus, xhr) {
            // Verificar si la respuesta es realmente JSON
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    console.error('La respuesta no es JSON válido:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Respuesta del servidor no válida.',
                    });
                    return;
                }
            }

            if (response.status === 'success') {
                //alert(response.message);
                Swal.fire({
                    icon: 'success',
                    title: '¡Inicio de sesión exitoso!',
                    text: response.message || 'Bienvenido al sistema.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    location.href = url + 'admin/HomeFormulario';
                });
            } else if (response.status === 'error' && response.message === 'Cuenta inactiva') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verificación de cuenta requerida',
                    text: 'Para poder iniciar sesión, primero debes validar tu cuenta. Por favor, revisa tu correo electrónico y sigue el enlace de verificación que te enviamos.',
                });
            }else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error desconocido',
                });
            }
        }).fail(function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error, xhr.responseText);

            // Intentar extraer mensaje de error si viene como HTML
            let errorMsg = 'Ocurrió un error al iniciar sesión.';
            try {
                const doc = new DOMParser().parseFromString(xhr.responseText, 'text/html');
                const bodyText = doc.body.textContent.trim();
                if (bodyText) errorMsg = bodyText;
            } catch (e) {}

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg,
            });
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

        // alert('nombre: ' + name + ' apellido: ' + lastName + ' apellido materno: ' + motherLastName + ' email: ' + email + ' password: ' +  password + ' confirmar password: ' + confirmPassword);

        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Las contraseñas no coinciden. Por favor, verifica.',
            });
            return;
        }

        if (!name || !lastName || !motherLastName || !email || !password || !confirmPassword) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, completa todos los campos.',
            });
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
                Swal.fire({
                    icon: 'success',
                    title: '¡Confirme y active su cuenta!',
                    html: 'Consulte en su correo electrónico el link de verificación. <br> Si no encuentra el mensaje en su bandeja de entrada, revise su carpeta de spam o correos no deseados.',
                    confirmButtonText: 'Entendido'
                }).then(function() {
                    location.href = url + 'home/index';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Ocurrió un error durante el registro.',
                });
            }
        }).fail(function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al registrar. Inténtalo de nuevo.',
            });
        });
    });


    //!restablecer contraseña

    $('.recuperar').on('click', function() {
        $('#modal-recover').modal('show');
    });

    // Enviar formulario de recuperación
    $('#form_recover').on('submit', function(e) {
        e.preventDefault();
        var email = $('#e_recuperar').val();

        if (!email) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor ingresa tu correo electrónico.',
            });
            return;
        }

        $.ajax({
            url: url + "Persona/recuperarPassword", //! Asegúrate de tener este método en tu controlador
            method: 'POST',
            data: {email: email},
            dataType: 'json'
        }).done(function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Correo Enviado!',
                    text: response.message || 'Se ha enviado un correo con las instrucciones para restablecer tu contraseña.',
                }).then(() => {
                    $('#modal-recover').modal('hide');
                    $('#e_recuperar').val(''); // Limpiar el campo de correo
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Ocurrió un error al solicitar el restablecimiento de contraseña.',
                });
            }
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la solicitud.',
            });
        });
    });

});
