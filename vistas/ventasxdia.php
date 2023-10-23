<?php

//Activamos el almacenamiento del Buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: ../vistas/login.php");
} else {
  require 'header.php';

  if ($_SESSION['ventas'] == 1) {
    ?>
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
        <!--Contenido-->
              <!-- Content Wrapper. Contains page content -->
              <div class="content-start transition">        
                <!-- Main content -->
                <section class="container-fluid dashboard">
                <div class="content-header">
                  <h1>REGISTRO DE VENTAS POR DÍA DE PRODUCTOS Y SERVICIOS</h1>
                </div>
                    <div class="row">
                      <div class="col-md-12">
                      <div class="card">
                      <div class="card-body">


 
                    
        <!-- <div class="panel-body"  id="formularioregistros">
  <form name="formulario" id="formulario" method="POST"> -->

        <form name="formulario" id="formulario" method="POST" target="_blank">
        <input type="hidden" name="idempresa" id="idempresa" value="<?php echo $_SESSION['idempresa']; ?>">
        <div class="row">
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <label> Año: </label>
            <select class="form-control" name="ano" id="ano" onchange="regventasmes()">

              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
              <option value="2025">2025</option>
              <option value="2026">2026</option>
              <option value="2027">2027</option>
              <option value="2028">2028</option>
              <option value="2029">2029</option>
            </select>
            <input type="hidden" name="ano_1" id="ano_1"> 
          </div>

  

         <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <label> Mes: </label>
            <select class="form-control" name="mes" id="mes" onchange="regventasmes()">
              <option value="00">todos</option>
              <option value="1">Enero</option>
              <option value="2">Febrero</option>
              <option value="3">Marzo</option>
              <option value="4">Abril</option>
              <option value="5">Mayo</option>
              <option value="6">Junio</option>
              <option value="7">Julio</option>
              <option value="8">Agosto</option>
              <option value="9">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Diciembre</option>
            </select>
            <input type="hidden" name="mes_1" id="mes_1">
          </div> 


          <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <label> Día: </label>
            <select class="form-control" name="dia" id="dia" onchange="regventas()">
              <option value="1">01</option>
              <option value="2">02</option>
              <option value="3">03</option>
              <option value="4">04</option>
              <option value="5">05</option>
              <option value="6">06</option>
              <option value="7">07</option>
              <option value="8">08</option>
              <option value="9">09</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
      
            </select>
            <input type="hidden" name="mes_1" id="mes_1">
          </div> 



          <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <label> Moneda: </label>
            <select class="form-control" name="tmonedaa" id="tmonedaa" onchange="regventas()">
              <option value="PEN">PEN</option>
              <option value="USD">USD</option>
           </select>
          </div> 
          </div> 
  
      
        <div class="form-group col-lg-12 justify-content-center text-center mt-3 mb-3">      
                <button class="btn btn-success btn-sm " type="submit" id="btnconsultapdf"  data-toggle="tooltip" title="Consultar" 
                onclick="this.form.action='../reportes/ventasmesdiaExcel.php'" >Reporte excel mes <i class="fa fa-excel" ></i>
                </button>


                <button class="btn btn-success btn-sm " type="submit" id="btnconsultapdf"  data-toggle="tooltip" title="Consultar" 
                onclick="this.form.action='../reportes/ventasmesdiaExcelxdia.php'" >Reporte excel día <i class="fa fa-excel" ></i>
                </button>


                <button class="btn btn-primary btn-sm" type="submit" id="btnconsultaexcel"  data-toggle="tooltip" title="Consultar" 
                onclick="this.form.action='../reportes/RegistroVentas.php'" >Reporte pdf mes 
                </button>


        
        </div>

        <!-- <div class="form-group col-lg-12 col-md-6 col-sm-4 col-xs-12">
    <div class="panel-body table-responsive" id="listadoregistros">
        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>FECHA</th>
                            <th>DOCUMENTO</th>
                            
                            <th>OP. GRAVADA</th>
                            <th>IGV</th>
                            <th>TOTAL</th>
                            <th>TIPO</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                      <tfoot>
                            <th>TOTALES</th>
                            <th></th>
                            
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                      </tfoot> 
                        </table>
                    </div>

                    </div> -->

         <div class="form-group col-lg-12 col-md-6 col-sm-4 col-xs-12">
            <div class="panel-body table-responsive" id="listadoventaxdia" >
                <table id="tbllistadoventaxdia" class="table table-condensed">
                    <tbody>                            
                      </tbody>
                      
                                </table>
                            </div>
            </div>


             <div class="form-group col-lg-12 col-md-6 col-sm-4 col-xs-12">
            <div class="panel-body table-responsive" id="listadoventaxdianp">
                <table id="tbllistadoventaxdianotapedido" class="table table-condensed">
                    <tbody>                            
                      </tbody>
                      
                                </table>
                            </div>
                 </div>





        </div>




        </form>
               
                            <!--Fin centro -->
                          </div><!-- /.box -->
                      </div><!-- /.col -->
                  </div><!-- /.row -->
              </section><!-- /.content -->

            </div>




        <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
    <script type="text/javascript" src="scripts/inventario.js"></script>



    <?php
}
ob_end_flush();
?>