// Agregar el modal al documento si aún no está presente
document.addEventListener('DOMContentLoaded', (event) => {
    if (!document.getElementById("alertModal")) {
        const modalHTML = `
            <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="alertModalLabel">Alerta</h4>
                        </div>
                        <div class="modal-body" id="alertModalBody">
                            <!-- Mensaje de alerta irá aquí -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
});

// Función para mostrar el modal de alerta
function mostrarAlerta(mensaje) {
    document.getElementById("alertModalBody").textContent = mensaje;
    $('#alertModal').modal('show');
}

async function showPreviewInModal() {
    if (!validateSection8()) {
        mostrarAlerta("Por favor, marcar todas las casillas y proporcionar una firma antes de continuar.");
        return;
    }

    const content = generatePreviewContent();
    document.getElementById("previewModalBody").innerHTML = content;

    const modalElement = document.getElementById('previewModal');
    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
}

function generatePreviewContent() {
    const styleContent = `
        <style>
            .header { text-align: center; margin-bottom: 20px; }
            .profile-picture { max-width: 100px; border-radius: 10px; }
            .section { margin-bottom: 30px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
            .subsection { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
            .subsection h5 { width: 100%; color: #006699; margin-bottom: 10px; }
            .subsection p { flex: 1 1 45%; margin: 5px 0; }
            h4 { color: #003366; }
            h5 { color: #006699; margin-bottom: 5px; }
            h6 { color: #003366; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f4f4f4; }
            .signature { max-width: 200px; border: 1px solid #000; margin-top: 10px; }
            a { color: blue; text-decoration: underline; }
            .anexo-label { background-color: #007bff; color: white; padding: 2px 5px; border-radius: 3px; font-size: 0.9em; }
            .checkbox-label { display: block; margin: 5px 0; }
            .archivo-adjunto { color: #007bff; font-style: italic; }
        </style>
    `;

    let content = styleContent;

    const fotoPerfilInput = document.querySelector('input[name="fotoPerfil"]');
    if (fotoPerfilInput && fotoPerfilInput.files.length > 0) {
        const fotoPerfil = URL.createObjectURL(fotoPerfilInput.files[0]);
        content += `
            <div class="section">
                <h4>Foto de Perfil</h4>
                <img src="${fotoPerfil}" alt="Foto de Perfil" class="profile-picture">
            </div>
        `;
    }

    const sections = document.querySelectorAll('.form-section');
    sections.forEach((section) => {
        let sectionContent = `<div class="section">`;

        const sectionTitleElem = section.querySelector('h2');
        if (sectionTitleElem) {
            sectionContent += `<h4>${sectionTitleElem.innerText.replace("*", "")}</h4>`;
        }

        sectionContent += processSubsections(section, section.id);

        sectionContent += `</div>`;
        content += sectionContent;
    });

    const canvas = document.getElementById('firmaCanvas');
    if (canvas) {
        const firmaURL = canvas.toDataURL();
        if (firmaURL && firmaURL.length > 100) {
            content += `
                <div class="section">
                    <h4>Firma</h4>
                    <img src="${firmaURL}" alt="Firma del usuario" class="signature">
                </div>
            `;
        }
    }

    return content;
}

function processSubsections(section, sectionId) {
    let content = '';

    const subsections = section.querySelectorAll('.section-title, .subsection-title');
    if (subsections.length > 0) {
        subsections.forEach(subsection => {
            content += processSubsection(subsection, sectionId);
        });
    } else {
        content += processFields(section.querySelectorAll('input, select, textarea'), sectionId);
    }

    return content;
}

function processSubsection(subsection, sectionId) {
    let content = `<div class="subsection">`;

    const subsectionTitle = subsection.querySelector('h4, h5');
    if (subsectionTitle) {
        content += `<h5>${subsectionTitle.innerText.replace("*", "")}</h5>`;
    }

    const fieldsContainer = subsection.nextElementSibling;
    if (fieldsContainer) {
        content += processFields(fieldsContainer.querySelectorAll('input, select, textarea'), sectionId);
    }

    const tableSection3 = subsection.querySelector('.row.g-3');
    if (tableSection3) {
        content += processFields(tableSection3.querySelectorAll('input, select, textarea'), sectionId);
    }

    let table = fieldsContainer?.nextElementSibling || tableSection3?.nextElementSibling;
    if (table && table.tagName === 'TABLE') {
        content += processTable(table);
    }

    // Se agrega el procesamiento de las tablas en la sección 3
    if (sectionId === 'section3') {
        const tables = subsection.querySelectorAll('table');
        tables.forEach(table => {
            content += processTable(table);
        });
    }

    content += `</div>`;
    return content;
}

function processFields(fields, sectionId) {
    let content = '';

    fields.forEach(field => {
        if (['LABEL', 'button', 'submit', 'hidden', 'file'].includes(field.tagName) || field.name === 'firma' || field.type === 'file') {
            return;
        }

        let labelText = '';
        const parentLabel = field.parentElement.querySelector('label');
        if (parentLabel) {
            labelText = parentLabel.innerText.replace(":", "").replace("*", "").trim();
        } else if (field.previousElementSibling && field.previousElementSibling.tagName === "LABEL") {
            labelText = field.previousElementSibling.innerText.replace(":", "").replace("*", "").trim();
        }

        let value = '';
        if (field.type === 'checkbox') {
            value = field.checked ? "Sí" : "No";
        } else if (field.tagName === "SELECT") {
            const selectedOption = field.options[field.selectedIndex];
            value = selectedOption ? selectedOption.text : '';
            if (value.includes("--Seleccionar") || value.includes("Selecciona")) {
                return;
            }
        } else {
            value = field.value.trim();
        }

        if (field.closest('.input-group') && field.id === "basic-url" && value === "https://example.com/") {
            return;
        }

        if (field.closest('.input-group') && field.id === "phoneNumber") {
            const phoneCode = document.getElementById('phoneCode').value;
            value = `${phoneCode} ${value}`;
            labelText = "Número de Celular";
        }

        if (field.closest('.input-group') && field.id === "basic-url") {
            labelText = "Red Profesional";
        }

        if (value) {
            content += `<p><strong>${labelText}:</strong> ${value}</p>`;
        }
    });

    return content;
}

function processTable(table) {
    const clonedTable = table.cloneNode(true);

    const headers = clonedTable.querySelectorAll('th');
    let actionIndex = -1;
    headers.forEach((header, index) => {
        if (header.textContent.includes("Acción")) {
            actionIndex = index;
        }
    });

    if (actionIndex !== -1) {
        const rows = clonedTable.querySelectorAll('tr');
        rows.forEach(row => {
            const actionCell = row.children[actionIndex];
            if (actionCell) {
                actionCell.remove();
            }
        });
    }

    const rows = clonedTable.querySelectorAll('tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            if (cell.querySelector('img')) {
                cell.innerHTML = 'Adjunto';
            }
        });
    });

    return `<table>${clonedTable.innerHTML}</table>`;
}


async function readFileAsArrayBuffer(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
        reader.readAsArrayBuffer(file);
    });
}

async function downloadPDF() {
    const { PDFDocument } = PDFLib;
    const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
    const previewContent = document.getElementById('previewModalBody');

    let margin = 15; // Margen general
    let lineHeight = 10;
    let currentY = margin;
    const pageWidth = pdf.internal.pageSize.getWidth() - margin * 2;

    const logoImg = new Image();
    logoImg.src = '/assets/ICACIT_2025.jpg';

    logoImg.onload = async function () {
        const logoHeight = (logoImg.height * 25) / logoImg.width;

        const addHeader = () => {
            pdf.addImage(logoImg, 'PNG', margin, margin, 25, logoHeight);
            pdf.setFontSize(10);
            pdf.setTextColor(100); // Color gris para el texto secundario
            pdf.text(`Fecha de Registro: ${new Date().toLocaleDateString()}`, pageWidth + margin, margin + logoHeight / 2, { align: 'right' }); // Mover la fecha más a la derecha
        };

        addHeader();

        pdf.setFontSize(36); // Tamaño más grande para el título principal
        pdf.setFont('helvetica', 'bold');
        pdf.setTextColor(0, 0, 0); // Color negro para el título (RGB)
        const titleText = 'Formulario de Inscripción\nICACIT 2025'; // Título en dos líneas
        const titleX = pdf.internal.pageSize.getWidth() / 2;
        const titleY = pdf.internal.pageSize.getHeight() / 2;
        pdf.text(titleText, titleX, titleY, { align: 'center' }); // Centrar el título en la página
        currentY = titleY + 25; // Ajustar el espacio después del título

        // Agregar una nueva página para el contenido
        pdf.addPage();
        addHeader();
        currentY = margin + logoHeight + 10;

        const addText = (text, fontSize = 12, isBold = false, align = 'left', colWidth = pageWidth, color = [0, 0, 0], justify = true) => {
            if (currentY + lineHeight > pdf.internal.pageSize.getHeight() - margin) {
                pdf.addPage();
                addHeader();
                currentY = margin + logoHeight + 10;
            }
            pdf.setFontSize(fontSize);
            pdf.setFont('helvetica', isBold ? 'bold' : 'normal');
            pdf.setTextColor(...color); // Pasar el color como argumentos separados

            const textLines = pdf.splitTextToSize(text, colWidth);
            if (justify) {
                // Justificar el texto
                textLines.forEach((line, index) => {
                    pdf.text(line, margin, currentY + (index * lineHeight), { align: 'justify' });
                });
                currentY += lineHeight * textLines.length;
            } else {
                pdf.text(textLines, margin, currentY, { align });
                currentY += lineHeight * textLines.length;
            }
        };

        const addImage = (imgSrc, width, height, align = 'center') => {
            if (currentY + height + lineHeight > pdf.internal.pageSize.getHeight() - margin) {
                pdf.addPage();
                addHeader();
                currentY = margin + logoHeight + 10;
            }
            const x = align === 'center' ? (pageWidth - width) / 2 : margin;
            pdf.addImage(imgSrc, 'PNG', x, currentY, width, height);
            currentY += height + lineHeight;
        };

        const addTable = (table) => {
            const rows = table.querySelectorAll('tr');
            const data = [];

            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('th, td');
                cells.forEach(cell => {
                    if (cell.querySelector('.pdf-icon')) {
                        rowData.push("Adjunto");
                    } else {
                        rowData.push(cell.textContent.trim());
                    }
                });
                data.push(rowData);
            });

            pdf.autoTable({
                startY: currentY - 10,
                head: [data[0]],
                body: data.slice(1),
                margin: { left: margin },
                headStyles: { fillColor: [44, 62, 80] }, // Color de fondo para el encabezado de la tabla
                alternateRowStyles: { fillColor: [245, 245, 245] }, // Color alterno para las filas
            });

            currentY = pdf.autoTable.previous.finalY + lineHeight;
        };

        // Agregar el contenido de la previsualización
        previewContent.querySelectorAll('h4, h5, p, img, table').forEach(element => {
            if (element.tagName === 'H4') {
                currentY += 8; // Reducir espacio antes del título
                addText(element.textContent, 18, true, 'left', pageWidth, [0, 51, 102]); // Color azul oscuro para títulos
            } else if (element.tagName === 'H5') {
                addText(element.textContent, 16, true, 'left', pageWidth, [0, 102, 153]); // Color azul claro para subtítulos
                currentY += 5; // Reducir espacio después del subtítulo
            } else if (element.tagName === 'P') {
                const isSection7 = element.closest('.form-section')?.id === 'section7'; // Verificar si es la sección 7
                addText(element.textContent.replace(/<br\s*\/?>/gi, '\n'), 12, false, 'left', pageWidth, [0, 0, 0], isSection7); // Justificar solo la sección 7
                if (isSection7) {
                    currentY -= 20; // Reducir espacio después de la sección 7
                }
            } else if (element.tagName === 'IMG') {
                const imgSrc = element.src;
                let imgWidth, imgHeight;
        
                if (element.classList.contains('profile-picture')) {
                    imgWidth = 30; // Ajustar el tamaño de la foto de perfil
                    imgHeight = (element.naturalHeight * imgWidth) / element.naturalWidth;
                } else if (element.classList.contains('signature')) {
                    imgWidth = 100; // Tamaño más grande para la firma
                    imgHeight = (element.naturalHeight * imgWidth) / element.naturalWidth;
                } else {
                    imgWidth = 60; // Tamaño más grande para otras imágenes
                    imgHeight = (element.naturalHeight * imgWidth) / element.naturalWidth;
                }
        
                addImage(imgSrc, imgWidth, imgHeight);
            } else if (element.tagName === 'TABLE') {
                addTable(element);
            }
        });

        
        // Guardar el PDF generado por jsPDF como un ArrayBuffer
        const jsPDFBuffer = pdf.output('arraybuffer');

        // Crear un nuevo PDF con pdf-lib y combinar los archivos adjuntos
        const finalPdf = await PDFDocument.create();

        // Agregar el contenido del PDF generado por jsPDF
        const jsPDFDoc = await PDFDocument.load(jsPDFBuffer);
        const jsPDFPages = await finalPdf.copyPages(jsPDFDoc, jsPDFDoc.getPageIndices());
        jsPDFPages.forEach(page => finalPdf.addPage(page));

        // Función para agregar un PDF adjunto al documento final
        const addPDF = async (fileInput, titulo) => {
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const arrayBuffer = await readFileAsArrayBuffer(file);
                const pdfDoc = await PDFDocument.load(arrayBuffer);
        
                if (titulo) {
                    const tituloPagina = await finalPdf.addPage();
                    const helveticaBoldFont = await finalPdf.embedFont(PDFLib.StandardFonts.HelveticaBold);
                    const fontSize = 28;
                    const pageWidth = tituloPagina.getWidth();
                    const pageHeight = tituloPagina.getHeight();
                    const lines = titulo.split('\n');
                    const lineHeight = fontSize * 1.2;
                    const totalTextHeight = lineHeight * lines.length;
                    const yStart = (pageHeight + totalTextHeight) / 2 - lineHeight;
        
                    lines.forEach((line, index) => {
                        const textWidth = helveticaBoldFont.widthOfTextAtSize(line, fontSize);
                        const x = (pageWidth - textWidth) / 2;
                        const y = yStart - (index * lineHeight);
        
                        tituloPagina.drawText(line, {
                            x: x,
                            y: y,
                            size: fontSize,
                            font: helveticaBoldFont,
                        });
                    });
                }
        
                const pages = await finalPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                pages.forEach(page => finalPdf.addPage(page));
            }
        };

        // Agregar los PDF adjuntos en el orden de las secciones
        await addPDF(document.getElementById('pdfDocumentoIdentidad'), 'Anexo: Documento de Identidad');

        // Agregar los PDF de las tablas de formacion academica
        let seccionesUnicasFormacion = [...new Set(anexosTablasFormacionAcademica.map(anexo => anexo.seccion))]; // Obtener secciones únicas
        for (let seccion of seccionesUnicasFormacion) {
            // Agregar un título general para la sección
            const titleAnexo = await finalPdf.addPage();
            const helveticaBoldFont = await finalPdf.embedFont(PDFLib.StandardFonts.HelveticaBold);
            const titleTextFormacion = 'Anexo: Evidencias de formación\nacadémica';
            const lines = titleTextFormacion.split('\n');
            const fontSize = 28;
            const lineHeight = fontSize * 1.2; // Ajustar el espaciado entre líneas
            const pageWidth = titleAnexo.getWidth();
            const pageHeight = titleAnexo.getHeight();
            const totalTextHeight = lineHeight * lines.length;
            const yStart = (pageHeight + totalTextHeight) / 2 - lineHeight;

            lines.forEach((line, index) => {
                const textWidth = helveticaBoldFont.widthOfTextAtSize(line, fontSize);
                const x = (pageWidth - textWidth) / 2;
                const y = yStart - (index * lineHeight);

                titleAnexo.drawText(line, {
                    x: x,
                    y: y,
                    size: fontSize,
                    font: helveticaBoldFont,
                });
            });

            // Agregar los PDFs de la sección actual
            const anexosSeccion = anexosTablasFormacionAcademica.filter(anexo => anexo.seccion === seccion);
            for (let anexo of anexosSeccion) {
                const arrayBuffer = await readFileAsArrayBuffer(anexo.file);
                const pdfDoc = await PDFDocument.load(arrayBuffer);
                const pages = await finalPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                pages.forEach(page => finalPdf.addPage(page));
            }
        }

        // Agregar los PDF de las tablas de experiencia laboral
        let seccionesUnicasExperiencia = [...new Set(anexosTablasExperienciaLaboral.map(anexo => anexo.seccion))]; // Obtener secciones únicas
        for (let seccion of seccionesUnicasExperiencia) {
            // Agregar un título general para la sección
            const titleAnexo = await finalPdf.addPage();
            const helveticaBoldFont = await finalPdf.embedFont(PDFLib.StandardFonts.HelveticaBold);
            const titleText = 'Anexo: Evidencias de 10 años de\nexperiencia profesional';
            const lines = titleText.split('\n');
            const fontSize = 28;
            const lineHeight = fontSize * 1.2; // Ajustar el espaciado entre líneas
            const pageWidth = titleAnexo.getWidth();
            const pageHeight = titleAnexo.getHeight();
            const totalTextHeight = lineHeight * lines.length;
            const yStart = (pageHeight + totalTextHeight) / 2 - lineHeight;

            lines.forEach((line, index) => {
                const textWidth = helveticaBoldFont.widthOfTextAtSize(line, fontSize);
                const x = (pageWidth - textWidth) / 2;
                const y = yStart - (index * lineHeight);

                titleAnexo.drawText(line, {
                    x: x,
                    y: y,
                    size: fontSize,
                    font: helveticaBoldFont,
                });
            });

            // Agregar los PDFs de la sección actual
            const anexosSeccion = anexosTablasExperienciaLaboral.filter(anexo => anexo.seccion === seccion);
            for (let anexo of anexosSeccion) {
                const arrayBuffer = await readFileAsArrayBuffer(anexo.file);
                const pdfDoc = await PDFDocument.load(arrayBuffer);
                const pages = await finalPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                pages.forEach(page => finalPdf.addPage(page));
            }
        }

        // Guardar el PDF final
        const finalPdfBytes = await finalPdf.save();
        const blob = new Blob([finalPdfBytes], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Formulario_Inscripcion_ICACIT_2025.pdf';
        link.click();
    };
}
