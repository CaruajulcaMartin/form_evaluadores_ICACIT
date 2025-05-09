<!-- Contenido de la sección login -->
<section class="py-5 home-section">
  <div class="text-center mb-4 home-header">
    <h3 class="fw-bold home-title">Bienvenidos al Programa de Formación de Evaluadores ICACIT</h3>
    <h5 class="text-muted home-subtitle">Inicia sesión en tu cuenta o crea una nueva</h5>
  </div>

  <div class="tabs-container">
    <div class="tabs-list">
      <button class="tabs-trigger active" data-value="login">Iniciar sesión</button>
      <button class="tabs-trigger" data-value="register">Registrarse</button>
    </div>
  </div>

  <!-- Formulario de inicio de sesión -->
  <div class="row justify-content-center login-section">
    <div class="col-md-6 col-lg-5 home-form-container">
      <form class="needs-validation home-form" id="loginForm" method="post" novalidate>
        <div class="mb-3">
          <label for="email" class="form-label fw-bold home-label">Email</label>
          <input id="email" type="email" class="form-control home-input" placeholder="nombre@ejemplo.com" required>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <label for="password" class="form-label fw-bold home-label">Contraseña</label>
            <button type="button" name="recuperar" class="btn btn-link recuperar" data-toggle="modal" data-container="body" style="text-decoration: none;">¿Olvido su contraseña?</button>
          </div>
          <input id="password" type="password" class="form-control home-input" required>
        </div>

        <!-- campo de captcha -->
        <div>
          <center>
            <div class="captcha">
              <img alt="Verification" class="captcha-img" height="68" src="<?php echo URL; ?>tools/captcha/captcha.php" width="200">
              <i class="change-captcha btn btn-info btn-sm" title="Cambiar imagen">
                <span class="fa fa-fw fa-refresh">
                </span>
              </i>
            </div>
          </center>
        </div>

        <div class="mb-3">
          <label for="captcha" class="form-label fw-bold home-label">Captcha</label>
          <input class="form-control autocomplete-off" id="verify" name="verify" placeholder="Código Captcha" required type="txt">
        </div>
        <div id="message">
        </div>

        <button type="submit" class="btn-continue">Continuar</button>
      </form>
    </div>
  </div>

  <!-- Formulario de registro -->
  <div class="row justify-content-center register-section d-none">
    <div class="col-md-6 col-lg-5 home-form-container">
      <form class="needs-validation home-form" id="registerForm" method="post" novalidate>
        <!-- Campo de Nombre -->
        <div class="mb-3">
          <label for="name" class="form-label fw-bold home-label">Nombre</label>
          <input id="name" type="text" class="form-control home-input" placeholder="Juan" required>
        </div>

        <!-- Campos de Apellido Paterno y Materno en una sola fila -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="lastName" class="form-label fw-bold home-label">Apellido Paterno</label>
            <input id="lastName" type="text" class="form-control home-input" placeholder="Perez" required>
          </div>
          <div class="col-md-6">
            <label for="motherLastName" class="form-label fw-bold home-label">Apellido Materno</label>
            <input id="motherLastName" type="text" class="form-control home-input" placeholder="Lopez" required>
          </div>
        </div>

        <!-- Campo de Correo Electrónico -->
        <div class="mb-3">
          <label for="registerEmail" class="form-label fw-bold home-label">Correo Electrónico</label>
          <input id="registerEmail" type="email" class="form-control home-input" placeholder="nombre@ejemplo.com" required>
        </div>

        <!-- Campos de Contraseña y Confirmar Contraseña en una sola fila -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="registerPassword" class="form-label fw-bold home-label">Contraseña</label>
            <input id="registerPassword" type="password" class="form-control home-input" required>
          </div>
          <div class="col-md-6">
            <label for="confirmPassword" class="form-label fw-bold home-label">Confirmar Contraseña</label>
            <input id="confirmPassword" type="password" class="form-control home-input" required>
          </div>
        </div>

        <!-- Botón de Registro -->
        <button type="submit" class="btn-register">Registrarse</button>
      </form>
    </div>
  </div>
</section>

<!-- modal de recuperar contraseña -->
<div class="modal fade" id="modal-recover" tabindex="-1" aria-labelledby="modal-recover-label" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" id="form_recover" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-recover-label">Recuperar Contraseña</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <p class="login-box-msg">
              ¿Olvidaste tu contraseña? Aquí puedes recuperarla fácilmente ingresando tu correo electrónico registrado.
            </p>
            <div class="input-group">
              <input type="email" id="e_recuperar" name="e_recuperar" class="form-control" placeholder="Email" required>
              <span class="input-group-text">
                <i class="fa fa-envelope"></i>
              </span>
            </div>
          </div>
          <div class="d-grid">
            <button type="submit" name="action" id="action" class="btn btn-success">Enviar Solicitud</button>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <input type="hidden" name="operation" id="operation">
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Alternar entre las secciones de login y registro
  document.querySelectorAll('.tabs-trigger').forEach(button => {
    button.addEventListener('click', function() {
      document.querySelectorAll('.tabs-trigger').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      if (this.dataset.value === 'login') {
        document.querySelector('.login-section').classList.remove('d-none');
        document.querySelector('.register-section').classList.add('d-none');
      } else {
        document.querySelector('.login-section').classList.add('d-none');
        document.querySelector('.register-section').classList.remove('d-none');
      }
    });
  });
</script>

<script src="<?php echo URL; ?>js/1_login/login.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>