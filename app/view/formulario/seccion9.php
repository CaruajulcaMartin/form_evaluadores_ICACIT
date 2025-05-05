<header class="header-section">
    <a class="img-header" href="<?php echo URL; ?>Admin/HomeFormulario">
        <img src="<?php echo URL; ?>assets/logo ICACIT.png" alt="logo de ICACIT">
    </a>

    <div class="usuario-header">
        <?php if (isset($data['user'])): ?>
            <h3 class="user-name">
                <?php echo htmlspecialchars($data['user']['nombre'] . ' ' . $data['user']['apellido_paterno']); ?>
            </h3>
            <span><?php echo htmlspecialchars($data['user']['email']); ?></span>
            <input type="hidden" id="userId" value="<?php echo htmlspecialchars($data['user']['id']); ?>">
        <?php else: ?>
            <p>Error: No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</header>

<section class="container">
    <h2 class="text-center my-4">Resumen de Información Registrada</h2>

    <!-- Sección 1 -->
    <div class="section">
        <h3>Sección 1: Información Personal</h3>
        <p><strong>Primer Apellido:</strong> <?php echo htmlspecialchars($data['seccion1']['primer_apellido'] ?? ''); ?></p>
    </div>

    <!-- Sección 2 -->
    <div class="section">
        <h3>Sección 2: Información Académica</h3>
        <p><strong>Campo Ejemplo:</strong> <?php echo htmlspecialchars($data['seccion2']['campo_ejemplo'] ?? ''); ?></p>
    </div>

    <!-- Repite para las demás secciones -->
    <div class="section">
        <h3>Sección 3: Información Laboral</h3>
        <p><strong>Campo Ejemplo:</strong> <?php echo htmlspecialchars($data['seccion3']['campo_ejemplo'] ?? ''); ?></p>
    </div>

    <div class="section">
        <h3>Sección 4: Información Adicional</h3>
        <p><strong>Campo Ejemplo:</strong> <?php echo htmlspecialchars($data['seccion4']['campo_ejemplo'] ?? ''); ?></p>
    </div>

    <!-- Botones -->
    <div class="text-center my-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmarEnvioModal">
            Confirmar Envío
        </button>
        <a href="<?php echo URL; ?>Formulario/descargarPDF" class="btn btn-primary">
            Descargar PDF
        </a>
    </div>
</section>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarEnvioModal" tabindex="-1" aria-labelledby="confirmarEnvioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEnvioModalLabel">Confirmar Envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas confirmar el envío de toda la información registrada?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?php echo URL; ?>Formulario/confirmarEnvio" class="btn btn-success">Confirmar</a>
            </div>
        </div>
    </div>
</div>
