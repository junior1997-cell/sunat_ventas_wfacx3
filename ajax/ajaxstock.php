<?php
require_once "../modelos/ModeloStock.php";

$modelostock = new ModeloStock();

$id_del_articulo = isset($_POST["idarticulo"]) ? filter_var($_POST["idarticulo"], FILTER_SANITIZE_NUMBER_INT) : null;
$nuevo_valor_stock = isset($_POST["stock"]) ? filter_var($_POST["stock"], FILTER_SANITIZE_NUMBER_INT) : "";
$id_del_articulo = isset($_POST["idarticulo"]) ? filter_var($_POST["idarticulo"], FILTER_SANITIZE_NUMBER_INT) : "";
$nuevo_valor_costo_compra = isset($_POST["costo_compra"]) ? filter_var($_POST["costo_compra"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : "";
$nuevo_valor_precio_venta = isset($_POST["precio_venta"]) ? filter_var($_POST["precio_venta"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : "";
$nuevo_valor_precio2 = isset($_POST["precio2"]) ? filter_var($_POST["precio2"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : "";
$nuevo_valor_precio3 = isset($_POST["precio3"]) ? filter_var($_POST["precio3"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : "";
$nueva_descripcion = isset($_POST["descripcion"]) ? filter_var($_POST["descripcion"], FILTER_SANITIZE_STRING) : "";
;


switch ($_GET["op"]) {


    case 'listarStockProductos':
        $rspta = $modelostock->listarStockProductos();

        // Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $imagenUrl = (empty($reg->imagen) || !file_exists("../files/articulos/" . $reg->imagen)) ? '../files/articulos/simagen.png' : '../files/articulos/' . $reg->imagen;

            $data[] = array(
                "0" => $reg->idarticulo,
                "1" => $reg->codigo,
                "2" => $reg->nombre,
                "3" => '<img src="' . $imagenUrl . '" height="50" width="50" alt="Imagen"/>',
                "4" => ($reg->estado == 1) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desactivado</span>',
                "5" => '<input type="number" style="width:90px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="stock" value="' . $reg->stock . '">',
                "6" => '<input type="number" style="width:90px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="costo_compra" value="' . $reg->costo_compra . '">',
                "7" => '<input type="number" style="width:90px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="precio_venta" value="' . $reg->precio_venta . '">',
                "8" => '<input type="number" style="width:90px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="precio2" value="' . $reg->precio2 . '">',
                "9" => '<input type="number" style="width:90px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="precio3" value="' . $reg->precio3 . '">',
                "10" => '<input type="text" style="width:120px;" class="editable" data-idarticulo="' . $reg->idarticulo . '" data-field="descrip" value="' . $reg->descrip . '">',
                "11" => $reg->total_unidades_vendidas,
                "12" => $reg->total_ventas
            );
            

        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);

        break;

        case 'ActualizarStockProductos':
            $fields = ["stock", "costo_compra", "precio_venta", "precio2", "precio3", "descrip"];
            $dataToUpdate = array();
            
            foreach($fields as $field) {
                if(isset($_POST[$field])) {
                    $dataToUpdate[$field] = $_POST[$field];
                }
            }
        
            if(empty($dataToUpdate)) {
                echo "No hay datos para actualizar.";
                return;
            }
        
            $rspta = $modelostock->ActualizarStockProductos($dataToUpdate, $id_del_articulo);
            echo $rspta ? "Datos actualizados" : "El artículo no se pudo actualizar";
            break;
        


}


?>