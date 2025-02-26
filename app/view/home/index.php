   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Administrar Firmante</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Sistema de Certificado Digital - SCD</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <button type="button" id="add_button" data-toggle="modal" data-target="#modal-lg" class="btn btn-info btn-lg"><span class="fas fa-plus"></span> <strong>Agregar Firmante</strong></button>
              </div>
            </div>
          </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
       <div class="col-lg-12">
      <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                          <th>ID</th>
                          <th>Nombres y Apellidos</th>
                          <th>Cargo</th>
                          <th>Institución</th>
                          <th>Firma</th>
                          <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                         <th>ID</th>
                          <th>Nombres y Apellidos</th>
                          <th>Cargo</th>
                          <th>Institución</th>
                          <th>Firma</th>
                          <th>Acciones</th>
                      </tr>
                    </tfoot>
                </table>
            </div>
          </div>
       </div>
      </div>
    </section>
  </div>
  

  
    <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <form method="post" id="user_form" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Datos del Firmante
                                        </h3>
                                    </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!--label>
                                                Enter Last Name:
                                            </label-->
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
                                              </div>
                                              <input required type="text" name="nom_ape" id="nom_ape" class="form-control" placeholder="Nombres y Apellidos" />
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                              </div>
                                              <input required type="text" name="cargo" id="cargo" class="form-control" placeholder="Cargo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                              </div>
                                              <input required type="text" name="institucion" id="institucion" class="form-control" placeholder="Institución" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label> Recomendación: <br /> 
                                          Imagen .PNG sin fondo y peso < 512 kb </label>
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" id="file" name="file" >
                                          <label class="custom-file-label" for="customFile">Ninguna imagen de firma</label>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <div class="custom-file" id="user_uploaded_image">
                                        </div>
                                      </div>
                                        
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                    <!-- /.container-fluid -->
                </section>
            </div>
            <div class="modal-footer justify-content-between">
          <input type="hidden" name="user_id" id="user_id" />
          <input type="hidden" name="operation" id="operation" />
          <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </form>
    </div>
</div>