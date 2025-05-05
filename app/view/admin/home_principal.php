<header class="header-section">
    <div class="img-header">
        <img src="<?php echo URL; ?>assets/logo ICACIT.png" alt="logo de ICACIT">
    </div>

    <div class="usuario-header">
        <?php if (isset($data['user'])): ?>
            <h3 class="user-name">
                <?php echo htmlspecialchars($data['user']['nombre'] ?? ''); ?>
                <?php echo htmlspecialchars($data['user']['apellido_paterno'] ?? ''); ?>
            </h3>
            <span><?php echo htmlspecialchars($data['user']['email'] ?? ''); ?></span>
        <?php else: ?>
            <p>Error: No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</header>

<section class="container main-section">
    <div class="main-titles">
        <h2 class="title">!Hola, <span><?php echo htmlspecialchars($data['user']['nombre'] ?? ''); ?></span>! ¿Listo para comenzar?</h2>
        <p class="subtitle">Te guiaremos a través de todo el proceso paso a paso</p>
    </div>

    <div class="container-formulario">
        <h3 class="title-formulario">Formulario de Inscripción</h3>
        <div class="container-information">
            <div class="information-formulario">
                <div class="container-icono">
                    <i class="fa-solid fa-file-lines icono"></i>
                </div>
                <div class="information">
                    <h4>Información importante</h4>
                    <span>Este formulario cuenta con 8 secciones que debes completar para finalizar la inscripción</span>
                    <ul class="list-information">
                        <li><i class="fa-regular fa-circle-check"></i> Puedes guardar tu progreso en cualquier momento</li>
                        <li><i class="fa-regular fa-circle-check"></i> Debes completar todas las secciones para enviar</li>
                        <li><i class="fa-regular fa-circle-check"></i> Necesitarás tener todos tus documentos listos</li>
                    </ul>
                </div>
            </div>
            <button class="btn btn-form" type="button" onclick="location.href='<?php echo URL; ?>Admin/HomeFormulario'">Iniciar Formulario</button>
        </div>
    </div>
</section>

