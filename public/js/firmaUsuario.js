// firmaUsuario.js
function initializeSignatureCanvas() {
    const canvas = document.getElementById("firmaCanvas");
    const ctx = canvas.getContext("2d");
    const limpiador = document.getElementById("limpiarFirma");
    const inputFirma = document.getElementById("firmaInput");

    // Verificar si los elementos existen
    if (!canvas || !ctx || !limpiador || !inputFirma) {
        console.error("Elementos de firma no encontrados.");
        return;
    }

    // Ajustar tamaño del canvas
    function ajustarCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
        ctx.lineWidth = 2; // Grosor de la línea
        ctx.strokeStyle = "#000"; // Color de la firma
    }

    // Inicializar el canvas
    ajustarCanvas();
    window.addEventListener("resize", ajustarCanvas);

    let dibujando = false;

    // Función para obtener las coordenadas del evento (mouse o toque)
    function getCoordenadas(event) {
        const rect = canvas.getBoundingClientRect();
        if (event.touches) {
            return {
                x: event.touches[0].clientX - rect.left,
                y: event.touches[0].clientY - rect.top
            };
        } else {
            return {
                x: event.offsetX,
                y: event.offsetY
            };
        }
    }

    // Función para iniciar el dibujo
    function iniciarDibujo(event) {
        dibujando = true;
        const { x, y } = getCoordenadas(event);
        ctx.beginPath();
        ctx.moveTo(x, y);
        event.preventDefault(); // Evitar el comportamiento predeterminado en dispositivos táctiles
    }

    // Función para dibujar
    function dibujar(event) {
        if (!dibujando) return;
        const { x, y } = getCoordenadas(event);
        ctx.lineTo(x, y);
        ctx.stroke();
        event.preventDefault(); // Evitar el comportamiento predeterminado en dispositivos táctiles
    }

    // Función para finalizar el dibujo
    function finalizarDibujo() {
        dibujando = false;
        guardarFirma();
    }

    // Función para guardar la firma en el input
    function guardarFirma() {
        if (isCanvasEmpty(canvas)) {
            inputFirma.value = ""; // Si el canvas está vacío, no guardar la firma
        } else {
            inputFirma.value = canvas.toDataURL("image/png"); // Convierte la firma en base64
        }
    }

    // Función para verificar si el canvas está vacío
    function isCanvasEmpty(canvas) {
        const context = canvas.getContext("2d");
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;

        for (let i = 0; i < data.length; i += 4) {
            if (data[i + 3] !== 0) { // Si algún píxel no es transparente
                return false;
            }
        }
        return true;
    }

    // Limpiar la firma
    limpiador.addEventListener("click", function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        inputFirma.value = "";
    });

    // Eventos del canvas (mouse y toque)
    canvas.addEventListener("mousedown", iniciarDibujo);
    canvas.addEventListener("mousemove", dibujar);
    canvas.addEventListener("mouseup", finalizarDibujo);
    canvas.addEventListener("mouseleave", finalizarDibujo);

    canvas.addEventListener("touchstart", iniciarDibujo);
    canvas.addEventListener("touchmove", dibujar);
    canvas.addEventListener("touchend", finalizarDibujo);
}

// Observar cambios en el DOM para inicializar el canvas cuando esté visible
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        // Verificar si el canvas está visible
        const canvas = document.getElementById("firmaCanvas");
        if (canvas && canvas.style.display !== "none") {
            // Inicializar el canvas de la firma
            initializeSignatureCanvas();
            // Dejar de observar después de inicializar (opcional)
            observer.disconnect();
        }
    });
});

// Configurar el observer para observar cambios en el DOM
observer.observe(document.body, {
    childList: true, // Observar cambios en los hijos del nodo
    subtree: true, // Observar en todo el subárbol del nodo
    attributes: true, // Observar cambios en atributos (como style)
});