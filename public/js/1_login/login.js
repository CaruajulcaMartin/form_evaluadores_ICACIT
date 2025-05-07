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
            $("#message").html(`
                <div class='alert alert-danger alert-dismissable'>
                    <strong>¡Error!</strong> Por favor, completa todos los campos.
                </div>
            `);
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
                    throw new Error('Respuesta del servidor no válida');
                }
            }
            
            if (response.status === 'success') {
                //alert(response.message);
                location.href = url + 'admin/HomeFormulario';
            } else {
                $("#message").html(`
                    <div class='alert alert-danger alert-dismissable'>
                        <strong>¡Error!</strong> ${response.message || 'Error desconocido'}
                    </div>
                `);
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
            
            $("#message").html(`
                <div class='alert alert-danger alert-dismissable'>
                    <strong>¡Error!</strong> ${errorMsg}
                </div>
            `);
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

    /*
    //restablecer contraseña
    $('.recuperar').on('click', function() {
        $('#modal-recover').modal('show');
    });

    // Enviar formulario de recuperación
    $('#form_recover').on('submit', function(e) {
        e.preventDefault();
        var email = $('#e_recuperar').val();
        
        if (!email) {
            alert('Por favor ingresa tu correo electrónico');
            return;
        }
        
        $.ajax({
            url: url + "Persona/recuperarPassword", //! Asegúrate de tener este método en tu controlador
            method: 'POST',
            data: {email: email},
            dataType: 'json'
        }).done(function(response) {
            if (response.status === 'success') {
                alert(response.message);
                $('#modal-recover').modal('hide');
            } else {
                alert(response.message);
            }
        }).fail(function() {
            alert('Error al procesar la solicitud');
        });
    });
    */
});