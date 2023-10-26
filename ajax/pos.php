<?php
require_once "../modelos/PosModelo.php";
$posmodelo = new PosModelo();
//Primeros productos
$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idfamilia = isset($_POST["idfamilia"]) ? limpiarCadena($_POST["idfamilia"]) : "";
$codigo_proveedor = isset($_POST["codigo_proveedor"]) ? limpiarCadena($_POST["codigo_proveedor"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$familia = isset($_POST["familia"]) ? limpiarCadena($_POST["familia"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
$precio = isset($_POST["precio"]) ? limpiarCadena($_POST["precio"]) : "";
$costo_compra = isset($_POST["costo_compra"]) ? limpiarCadena($_POST["costo_compra"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$precio_final_kardex = isset($_POST["precio_final_kardex"]) ? limpiarCadena($_POST["precio_final_kardex"]) : "";
$unidad_medida = isset($_POST["unidad_medida"]) ? limpiarCadena($_POST["unidad_medida"]) : "";
$ccontable = isset($_POST["ccontable"]) ? limpiarCadena($_POST["ccontable"]) : "";
$nombreum = isset($_POST["nombreum"]) ? limpiarCadena($_POST["nombreum"]) : "";
$fechavencimiento = isset($_POST["fechavencimiento"]) ? limpiarCadena($_POST["fechavencimiento"]) : "";
$nombreal = isset($_POST["nombreal"]) ? limpiarCadena($_POST["nombreal"]) : "";

//comprobantes:
$fechaDesde = isset($_POST["fechaDesde"]) ? limpiarCadena($_POST["fechaDesde"]) : "";
$fechaHasta = isset($_POST["fechaHasta"]) ? limpiarCadena($_POST["fechaHasta"]) : "";
$tipoComprobante = isset($_POST["tipoComprobante"]) ? limpiarCadena($_POST["tipoComprobante"]) : "";

//Limpiar Familia 
$idfamilia = isset($_POST["idfamilia"]) ? limpiarcadena($_POST["idfamilia"]) : null;
$idfamilia = isset($_GET["idfamilia"]) ? limpiarcadena($_GET["idfamilia"]) : null;
$busqueda = isset($_GET["busqueda"]) ? limpiarcadena($_GET["busqueda"]) : null;

require_once "../modelos/Rutas.php";
$rutas = new Rutas();
$Rrutas = $rutas->mostrar2("1");
$Prutas = $Rrutas->fetch_object();
$rutaimagen = $Prutas->rutaarticulos; // ruta de la imagen


if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action = '';
}

if ($action == 'listarProducto') {
  $rspta = $posmodelo->listarProducto(1, $idfamilia, $busqueda);
  $data = array();

  // Obtiene la URL base
  $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  $currentDir = dirname($_SERVER['REQUEST_URI']); // Obtiene el directorio actual sin el script
  $baseURL = $baseURL . $currentDir; // Concatena el host con el directorio
  $baseURL = preg_replace('#/ajax$#', '', $baseURL); // Elimina la parte "/ajax" si existe

  while ($reg = $rspta->fetch_object()) {
    $imagenURL = $baseURL . '/files/articulos/' . (empty($reg->imagen) ? 'simagen.png' : $reg->imagen);

    $data[] = array(
      'idarticulo'    => $reg->idarticulo,
      'idfamilia'     => $reg->idfamilia,
      'codigo_proveedor' => $reg->codigo_proveedor,
      'codigo'        => $reg->codigo,
      'familia'       => $reg->familia,
      'nombre'        => $reg->nombre,
      'stock'         => $reg->stock ,
      'precio'        => empty($reg->precio) ? 0 : floatval($reg->precio),
      'costo_compra'  => empty($reg->costo_compra) ? 0 : floatval($reg->costo_compra),
      'precio_unitario' => empty($reg->precio_unitario) ? 0 : floatval($reg->precio_unitario),
      'cicbper'       => $reg->cicbper,
      'mticbperu'     => $reg->mticbperu,
      // 'factorconversion' => $reg->factorconversion,
      //(a.factorc * a.stock) as factorconversion,
      'factorc'       => $reg->factorc,
      'descrip'       => $reg->descrip,
      'tipoitem'      => $reg->tipoitem,
      'imagen'        => $imagenURL,
      // Utilizar la URL completa de la imagen
      'precio_final_kardex' => $reg->precio_final_kardex,
      'unidad_medida' => $reg->unidad_medida,
      'ccontable'     => $reg->ccontable,
      'st2'           => empty($reg->st2) ? 0 : floatval($reg->st2),
      'nombreum'      => $reg->nombreum,
      'abre'          => $reg->abre,
      'fechavencimiento' => $reg->fechavencimiento,
      'nombreal'      => $reg->nombreal
    );
  }
  $results = array(
    "ListaProductos" => $data
  );

  header('Content-type: application/json');
  echo json_encode($results);
}


//Listar Categorias : 

if ($action == 'listarCategorias') {
  $rspta = $posmodelo->listarcategorias();  
  $results = array(
    "ListaCategorias" => $rspta
  );

  header('Content-type: application/json');
  echo json_encode($results);
}



//Comprobantes boleta, factura y nota de venta

$data = json_decode(file_get_contents("php://input"), true);
if ($data) { // Verificamos si se ha enviado algo en formato JSON
  $idempresa = isset($data['idempresa']) ? $data['idempresa'] : "";
  $fechainicio = isset($data['fechainicio']) ? $data['fechainicio'] : "";
  $fechafinal = isset($data['fechafinal']) ? $data['fechafinal'] : "";
  $tipocomprobante = isset($data['tipocomprobante']) ? $data['tipocomprobante'] : "";
}

if ($action == 'listarComprobantesVarios') {
  $rspta = $posmodelo->listarComprobantesVarios($idempresa, $fechainicio, $fechafinal, $tipocomprobante);
  $data = array();

  while ($reg = $rspta->fetch_object()) {
    $data[] = array(
      'id' => $reg->id,
      'fecha' => $reg->fecha,
      'cliente' => $reg->cliente,
      'estado' => $reg->estado,
      'tipo_comprobante' => $reg->tipo_comprobante,
      'total' => $reg->total
    );
  }

  $results = array(
    "ListaComprobantes" => $data
  );

  header('Content-type: application/json');
  echo json_encode($results);
}


//insertar personas  - clientes : 

$data = json_decode(file_get_contents("php://input"), true);

if ($data) { // Verificamos si se ha enviado algo en formato JSON
  $tipo_persona = isset($data['tipo_persona']) ? $data['tipo_persona'] : "";
  $nombres = isset($data['nombres']) ? $data['nombres'] : "";
  $apellidos = isset($data['apellidos']) ? $data['apellidos'] : "";
  $tipo_documento = isset($data['tipo_documento']) ? $data['tipo_documento'] : "";
  $numero_documento = isset($data['numero_documento']) ? $data['numero_documento'] : "";
  $razon_social = isset($data['razon_social']) ? $data['razon_social'] : "";
  $domicilio_fiscal = isset($data['domicilio_fiscal']) ? $data['domicilio_fiscal'] : "";
  $telefono1 = isset($data['telefono1']) ? $data['telefono1'] : "";
  //$estado = isset($data['estado']) ? $data['estado'] : 1; // Supongo un valor por defecto de 1 para estado si no se especifica.
}

if ($action == 'insertarCliente') {
  $result = $modelo->insertarClientePOS($tipo_persona, $nombres, $apellidos, $tipo_documento, $numero_documento, $razon_social, $domicilio_fiscal, $telefono1);

  $response = array();

  if ($result) {
    $response = array(
      "status" => "success",
      "message" => "Cliente insertado correctamente."
    );
  } else {
    $response = array(
      "status" => "error",
      "message" => "Error al insertar el cliente."
    );
  }

  header('Content-type: application/json');
  echo json_encode($response);
}
