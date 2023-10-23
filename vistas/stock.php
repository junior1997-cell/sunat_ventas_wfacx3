<?php
session_start();
//Activamos el almacenamiento del Buffer
ob_start();


if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['almacen'] == 1) {

    ?>

    
        <div class="content-header">
          <h1>Stock y Precios de Artículos</h1>
        </div>

        <div class="row">

          <div class="col-md-12">
            <div class="card">
              <div class="card-body">

                <div class="table-responsive">
                  <table id="tbllistado" class="table table-striped" style="width: 100% !important;">
                    <thead>
                      <tr>
                        <th hidden scope="col">id</th>
                        <th scope="col">Codigo</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Estado</th>
                        <th style="background:red;" scope="col">Stock</th>
                        <th scope="col">C. Compra</th>
                        <th scope="col">P. Venta</th>
                        <th scope="col">P. Mayor</th>
                        <th scope="col">P. Distribuidor</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">T. U. vendidas</th>
                        <th scope="col">T. ventas</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>

                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>



        </div><!-- /.row -->


  


    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/scriptstock.js"></script>
  <?php
}
ob_end_flush();
?>