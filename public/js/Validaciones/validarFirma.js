function validateSection8() {
    let valid = true;

    // Validar checkboxes
    const checkboxes = document.querySelectorAll("#section8 input[type='checkbox']");
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            valid = false;
            showError(checkbox, `Debe marcar todas las opciones.`);
        } else {
            hideError(checkbox);
        }
    });

    // Validar el canvas de la firma
    const firmaCanvas = document.getElementById("firmaCanvas");
    const firmaInput = document.getElementById("firmaInput");

    if (!firmaCanvas || !firmaInput) {
        console.error("Elementos de firma no encontrados.");
        valid = false;
        return valid;
    }

    // Obtener el valor de la firma
    const firmaValue = firmaInput.value.trim();

    // Validar si la firma está vacía o no es válida
    if (!firmaValue || firmaValue === "data:,") {
        valid = false;
        showError(firmaCanvas, "Por favor, proporciona tu firma para continuar.");
    } else {
        hideError(firmaCanvas);
    }

    return valid;
}