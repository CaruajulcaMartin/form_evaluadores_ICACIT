$(document).ready(function() {
    $('#add_button').click(function() {
        $('#user_form')[0].reset();
        $('.modal-title').text("Agregar Registro");
        $('#action').val("Guardar");
        $('#operation').val("Add");
        $('#user_uploaded_image').html('');
    });
    $(document).on('submit', '#user_form', function(event) {
        event.preventDefault();
        var nom_ape = $('#nom_ape').val();
        var cargo = $('#cargo').val();
        var institucion = $('#institucion').val();
        var validar = $('#file').val()
        var operation = $('#operation').val()
        if (validar != '') {
            var extension = $('#file').val().split('.').pop().toLowerCase();
            var peso = $('#file')[0].files[0];
            if (extension != '') {
                if (jQuery.inArray(extension, ['png']) == -1) {
                    alert("Solo se permite imagen PNG");
                    $('#file').val('');
                    return false;
                }
            }
            if (peso.size > 512000) { // 512000 bytes = 512 Kb
                alert("La imagen supera el límite de peso permitido.");
                $('#file').val('');
                return false;
            }
        }
        if (operation == 'Add' && validar == '') {
            alert('Seleccionar imagen');
        } else {
            if (nom_ape != '' && cargo != '' && institucion != '') {
                $.ajax({
                    url: url + "/Home/add_edit",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        $('#user_form')[0].reset();
                        $('#modal-lg').modal('hide');
                        $('#example1').DataTable().ajax.reload();
                    }
                });
            } else {
                alert("Ambos campos son obligatorios");
            }
        }
    });
    $(document).on('click', '.update', function() {
        var user_id = $(this).attr("id");
        var params = {
            "user_id": user_id
        };
        $.ajax({
            "url": url + "/Home/update_modal",
            //"dataType": 'html',
            "dataType": "json",
            "method": "POST",
            "data": params,
            success: function(data) {
                //alert(data.ape_per);
                $('#modal-lg').modal('show');
                $('#nom_ape').val(data.nom_ape);
                $('#cargo').val(data.cargo);
                $('#institucion').val(data.institucion);
                $('.modal-title').text("Editar Registros");
                $('#user_id').val(user_id);
                $('#user_uploaded_image').html(data.file);
                $('#action').val("Guardar");
                $('#operation').val("Edit");
            }
        });
    });
    /* */
    $(document).on('click', '.delete', function() {
        var user_id = $(this).attr("id");
        if (confirm("¿Estás seguro de eliminar registro?")) {
            $.ajax({
                "url": url + "/Home/delete_grid",
                "method": "POST",
                "data": {
                    user_id: user_id
                },
                success: function(data) {
                    alert(data);
                    $('#example1').DataTable().ajax.reload();
                }
            });
        } else {
            return false;
        }
    });
});