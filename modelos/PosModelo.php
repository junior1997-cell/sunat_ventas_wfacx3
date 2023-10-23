<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
class PosModelo
{
    //Implementamos nuestro constructor
    public function __construct()
    {

    }
    //Listar los articulos
    public function listarProducto($idempresa, $idfamilia = null, $busqueda = null)
    {

        $filtro = "";
        if (!is_null($idfamilia)) {
            $filtro = " AND a.idfamilia = '$idfamilia'";
        }

        if (!is_null($busqueda) && $busqueda != "") {
            $filtro .= " AND (a.codigo LIKE '%$busqueda%' OR a.nombre LIKE '%$busqueda%')";
        }

        $sql = "select 
        a.idarticulo, 
        f.idfamilia, 
        a.codigo_proveedor, 
        a.codigo, 
        f.descripcion as familia, 
        left(a.nombre, 50) as nombre, 
        format(a.stock,2) as stock, 
        a.precio_venta as precio, 
        a.costo_compra,
        (a.precio_venta * 0.18) as precio_unitario,
        a.cicbper,
        format(a.mticbperu,2) as mticbperu,
        
        a.factorc,
        a.descrip,
        a.tipoitem,
        a.imagen, 
        a.estado, 
        a.precio_final_kardex,
        a.unidad_medida,
        a.ccontable,
        a.stock as st2,
        um.nombreum,
        um.abre,
        date_format(a.fechavencimiento, '%d/%m/%Y') as fechavencimiento,
        al.nombre as nombreal

        from 

        articulo a inner join familia f on a.idfamilia=f.idfamilia inner join almacen al on a.idalmacen=al.idalmacen inner join empresa e on al.idempresa=e.idempresa inner join umedida um on a.umedidacompra=um.idunidad and a.tipoitem='productos'
         where 
         not a.nombre='1000ncdg' and e.idempresa='$idempresa' and al.estado='1' $filtro";

        return ejecutarConsulta($sql);

    }

    //listar las categorias : 

    public function listarCategorias()
    {
        $sql = "select
                    f.idfamilia,
                    f.descripcion as familia,
                    f.estado
                from
                    familia f
                where
                    f.estado = '1'"; // elimina esta línea si también quieres categorías inactivas

        return ejecutarconsulta($sql);
    }


    //listar todas las BOLETAS
    public function listarBoletas($idempresa)
    {

        $sql = "select 
        b.idboleta,
        date_format(b.fecha_emision_01, '%d/%m/%y') as fecha,
        b.idcliente,
        left(p.razon_social, 20) as cliente,
        b.vendedorsitio,
        u.nombre as usuario,
        b.tipo_documento_06,
        b.numeracion_07,
        format(b.importe_total_23, 2) as importe_total_23, 
        b.estado, 
        p.nombres, 
        p.apellidos,
        e.numero_ruc,
        p.email,
        b.CodigoRptaSunat,
        b.DetalleSunat,
        b.tarjetadc,
        b.montotarjetadc,
        b.transferencia,
        b.montotransferencia,
        b.tipo_moneda_24 as moneda,
        b.tcambio,
        (b.tcambio * importe_total_23) as valordolsol,
        b.formapago,
        group_concat(a.nombre) as nombre_articulo
    from 
        boleta b 
        inner join persona p on b.idcliente = p.idpersona 
        inner join usuario u on b.idusuario = u.idusuario 
        inner join empresa e on b.idempresa = e.idempresa
        left join detalle_boleta_producto db on b.idboleta = db.idboleta 
        left join articulo a on db.idarticulo = a.idarticulo
    where
        date(b.fecha_emision_01) = current_date and e.idempresa = '$idempresa'
    group by
        b.idboleta
    order by
        b.idboleta desc;
    ";

        return ejecutarConsulta($sql);

    }



    public function listarComprobantesVarios($idempresa, $fechainicio, $fechafinal, $tipocomprobante)
    {

        $sql = "";

        // Agregar consulta para Boletas si se requiere
        if ($tipocomprobante == "Boleta" || $tipocomprobante == "Todos") {
            $sql .= "
    select 
    b.idboleta as id,
    date_format(b.fecha_emision_01, '%d/%m/%y') as fecha,
    p.razon_social as cliente,
    b.estado,
    'Boleta' as tipo_comprobante,
    b.importe_total_23 as total
from 
    boleta b 
    inner join persona p on b.idcliente = p.idpersona 
where
    date(b.fecha_emision_01) between '$fechainicio' and '$fechafinal' and b.idempresa = '$idempresa'
    ";
        }

        // Agregar consulta para Facturas si se requiere
        if ($tipocomprobante == "Factura" || $tipocomprobante == "Todos") {
            if ($sql != "") { // Verificar si ya hay una consulta
                $sql .= " union ";
            }
            $sql .= "
    select
            f.idfactura as id,
            date_format(f.fecha_emision_01, '%d/%m/%y') as fecha,
            p.razon_social as cliente,
            f.estado,
            'Factura' as tipo_comprobante,
            f.importe_total_venta_27 as total
        from
            factura f 
            inner join persona p on f.idcliente = p.idpersona
        where
            date(f.fecha_emision_01) between '$fechainicio' and '$fechafinal' and f.idempresa = '$idempresa'
    ";
        }

        // Agregar consulta para NotaPedido si se requiere
        if ($tipocomprobante == "NotaPedido" || $tipocomprobante == "Todos") {
            if ($sql != "") { // Verificar si ya hay una consulta
                $sql .= " union ";
            }
            $sql .= "
    select
            b.idboleta as id,
            date_format(b.fecha_emision_01,'%d/%m/%y') as fecha,
            p.razon_social as cliente,
            b.estado,
            'NotaPedido' as tipo_comprobante,
            b.importe_total_23 as total
            from
            notapedido b inner join persona p on b.idcliente=p.idpersona
        where
            date(b.fecha_emision_01) between '$fechainicio' and '$fechafinal' and b.idempresa = '$idempresa'
    ";
        }
        return ejecutarConsulta($sql);


    }




    public function insertarClientePOS($tipo_persona, $nombres, $apellidos, $tipo_documento, $numero_documento, $razon_social, $domicilio_fiscal, $telefono1)
    {
        $sql = "insert into persona (tipo_persona, nombres, apellidos, tipo_documento, numero_documento, razon_social, domicilio_fiscal, telefono1, estado) 
        values ('$tipo_persona', '$nombres', '$apellidos', '$tipo_documento', '$numero_documento', '$razon_social', '$domicilio_fiscal', '$telefono1', 1)
        ";
        return ejecutarConsulta($sql);
    }





}