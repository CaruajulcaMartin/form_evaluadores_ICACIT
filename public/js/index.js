let currentSection = 0;
const sections = document.querySelectorAll(".form-section");
const progressBar = document.getElementById("progressBar");
const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));

// Mostrar la sección actual
function showSection(index) {
    sections.forEach((section, i) => {
        section.style.display = i === index ? "block" : "none";
        section.classList.toggle("active", i === index);
    });
    updateProgress(index);

    // Desplazarse al inicio de la sección visible
    sections[index].scrollIntoView({ behavior: 'smooth' });

    // Si la sección actual es la que contiene el canvas de la firma, inicialízalo
    if (index === sections.length - 1) {
        initializeSignatureCanvas();
    }
}

// Avanzar a la siguiente sección
function nextSection() {
    if (!validateSection(sections[currentSection])) return;

    // Si estamos en la sección 4, validar experiencia laboral con el script externo
    if (currentSection === 3 && !validarExperienciaLaboral()) return;

    if (currentSection < sections.length - 1) {
        currentSection++;
        showSection(currentSection);
    }
}

// Retroceder a la sección anterior
function prevSection() {
    if (currentSection > 0) {
        currentSection--;
        showSection(currentSection);
    }
}

// Actualizar la barra de progreso
function updateProgress(index) {
    const stepPercentage = ((index + 1) / sections.length) * 100;
    progressBar.style.width = stepPercentage + "%";
    progressBar.innerText = `Paso ${index + 1} de ${sections.length}`;
}

// Validar la sección actual
function validateSection(section) {
    return validateRequiredFields(section) && validateTables(section);
}

// Validar campos requeridos (solo marca en rojo, sin modal)
function validateRequiredFields(section) {
    let isValid = true;
    const requiredInputs = section.querySelectorAll('input[required], select[required], textarea[required]');

    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            showError(input);
        } else {
            hideError(input);
        }
    });

    return isValid;
}

// Validar solo las tablas obligatorias (muestra modal solo para tablas)
function validateTables(section) {
    let isValid = true;
    const requiredTables = section.querySelectorAll('table.required');

    requiredTables.forEach(table => {
        if (table.querySelectorAll('tbody tr').length === 0) {
            isValid = false;
            const tableTitle = table.dataset.tableName || 
                             table.previousElementSibling?.textContent?.trim() || 
                             'Tabla requerida';
            showTableAlert(tableTitle);
        }
    });

    return isValid;
}

// Mostrar error en campo (solo borde rojo)
function showError(input) {
    input.classList.add('is-invalid');
    // Enfocar el primer campo con error
    if (!document.querySelector('.is-invalid:focus')) {
        input.focus();
    }
}

// Ocultar error en campo
function hideError(input) {
    input.classList.remove('is-invalid');
}

// Mostrar alerta para tablas requeridas (usa modal)
function showTableAlert(tableTitle) {
    document.getElementById('alertModalLabel').textContent = 'Tabla requerida';
    document.getElementById('alertModalBody').textContent = `Debe agregar al menos un registro en la tabla: "${tableTitle}"`;
    alertModal.show();
}

// Inicializar la primera sección visible
showSection(currentSection);