<?php
session_start();
include ("./conexion.php");
include ("./tools.php");
require_once('./FRAMEWORK/fpdf/fpdf.php');
$system_date = date("Y-m-d");
extract($_GET);
$db = new MySQL();

$rec = $db->fetch_array($db->consulta("
    SELECT 
        f.id,
        f.tipo_documento,
        f.n_documento,
        DATE_FORMAT(f.fecha_documento,'%d/%m/%Y') fecha_documento,
        DAY(f.fecha_documento) dia_documento,
        MONTH(f.fecha_documento) mes_documento,
        YEAR(f.fecha_documento) anio_documento,
        f.id_cliente, 
        f.n_notaremi,
        IF (f.fecha_notaremi!='0000-00-00',DATE_FORMAT(f.fecha_notaremi,'%d/%m/%Y'),'') fecha_notaremi,
        cp.condicion_pago, 
        f.venta_a_cta, 
        f.n_pedido, 
        f.hecho_por,
		f.comentario,
        c.nombre AS nombre_cliente,
		c.nombre_comercial,
        c.nit,
        c.dir,
		c.dir2,
        c.nrc,
        c.giro,
		c.codigo_proveedor,
        d.departamento,
        m.municipio,
        f.n_notaremi
    FROM facturacion f
        LEFT JOIN clientes c ON c.id = f.id_cliente
        LEFT JOIN departamento d ON c.dep=d.id
        LEFT JOIN municipio m ON c.mun=m.id
        LEFT JOIN condicion_pago cp ON cp.id = f.condicion_pago
    WHERE f.id='$id_documento'"));


 $totales = $db->fetch_array($db->consulta("SELECT 
                sum(fd.ventas_gravadas) AS ventas_gravadas,
                sum(fd.ventas_gravadas)*.13 AS iva,
               (sum(fd.ventas_gravadas)+sum(fd.ventas_gravadas)*.13) AS subtotal,
			   IF(sum(fd.ventas_gravadas)>=100,sum(fd.ventas_gravadas)*c.iva_retenido,0) AS iva_retenido,
			   (sum(fd.ventas_gravadas)+sum(fd.ventas_gravadas)*.13)-IF(sum(fd.ventas_gravadas)>=100,sum(fd.ventas_gravadas)*c.iva_retenido,0) AS venta_total
            FROM facturas_detalle fd
				LEFT JOIN facturacion f ON fd.id_documento = f.id
				LEFT JOIN clientes c ON f.id_cliente = c.id
            WHERE fd.id_documento='$id_documento'
            GROUP BY id_documento
            "));

if ($rec['tipo_documento']==1){ //F A C T U R A
    
    $pdf = new FPDF('P','cm','Letter');

    
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    }

    $pdf = new PDF();
    
    $tab1=30;
    $tab2=140;
    
//    detalle
    $tab3=8;
    $tab4=18;
    $tab5=115;
    $tab6=140;
    $tab7=160;
    $tab8=180;
    
    $br = 3.5;
    
    
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    $pdf->SetFont('Courier','B',10);
    $pdf->Ln(32);
    $pdf->Cell($tab2-20);
    $pdf->Cell(30,10,$rec['dia_documento'].'        '.mes_en_letras($rec['mes_documento']));
	$pdf->Cell(30,10,'             '.$rec['anio_documento']);

    
    $pdf->Ln($br);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['nombre_cliente']);
    
    $pdf->Ln($br);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,'     '.$rec['condicion_pago']);
    
    $pdf->Ln($br);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,'    '.$rec['nombre_comercial']);

    $pdf->Ln($br);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['hecho_por']);
    
    
    $pdf->Ln($br);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['dir']);
    
    $pdf->Ln($br);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['venta_a_cta']);

    $pdf->Ln(0.5);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['dir2']);

	
    $pdf->Ln($br);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['departamento']);
    
    $pdf->Ln($br);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['n_pedido']);
    
    
    $pdf->Ln($br);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['n_notaremi']);
    
    $consulta = $db->consulta("SELECT 
                p.nombre,
                cantidad, 
                precio_unit, 
                ventas_no_sujetas, 
                ventas_exentas, 
                ventas_gravadas 
            FROM facturas_detalle fd
                LEFT JOIN producto p ON p.id = fd.id_producto
            WHERE id_documento='$id_documento'");
    
//    detalle
    $pdf->Ln($br);$pdf->Ln($br);$pdf->Ln($br);    
    while ($row = $db->fetch_array($consulta)){
        $pdf->Ln($br);
        $pdf->Cell($tab3);
        $pdf->Cell(0,10,$row['cantidad']);
        $pdf->Ln(0);
        $pdf->Cell($tab4);
        $pdf->Cell(0,10,$row['nombre']);
        $pdf->Ln(0);
        $pdf->Cell($tab5);
        $pdf->Cell(0,10,$row['precio_unit']);
        $pdf->Ln(0);
        $pdf->Cell($tab8);
        $pdf->Cell(0,10,$row['ventas_gravadas'],0,0,'R');
        $pdf->Ln($br);
    }
    
	$pdf->Ln($br);
	$pdf->Cell($tab3);
	$pdf->Cell(0,10,$rec['comentario']);


    
    $pdf->SetXY(170, 203);
    $pdf->Ln(0);
    $pdf->Cell($tab4);
    $pdf->Cell(0,10,num2letras($totales['ventas_gravadas'],0,0,'R'));
    
    $pdf->Ln(0);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,$totales['ventas_gravadas'],0,0,'R');
    $pdf->Ln($br*10);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,$totales['ventas_gravadas'],0,0,'R');
    
    
    $pdf->Output();
}



if ($rec['tipo_documento']==2){ //  C R E D I T O  F I S C A L
    $pdf = new FPDF('P','cm','Letter');
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    }

    $pdf = new PDF();
    
    $tab1=20;
    $tab2=130;
    
//    detalle
    $tab3=5;
    $tab4=16;
    $tab5=118;
    $tab6=140;
    $tab7=160;
    $tab8=180;
    
    $br = 3.7;
    
    
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    $pdf->SetFont('Courier','B',10);

    $pdf->Ln(34);
    
	$pdf->Cell($tab2-15);
    $pdf->Cell(40,10, $rec['dia_documento'].'        '.mes_en_letras($rec['mes_documento']));
	$pdf->Cell(30,10,'            '.$rec['anio_documento']);
	
    $pdf->Ln(0);
	$pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['nombre_cliente']);

    $pdf->Ln($br+2);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['nombre_comercial']);
	
    $pdf->Ln(0);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['nrc']);
    
    $pdf->Ln($br+3);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['dir']);

    $pdf->Ln(-2);
    $pdf->Cell($tab2-15);
    $pdf->Cell(40,10,'     '.$rec['giro']);
    
    $pdf->Ln(7);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['dir2']);

    $pdf->Ln(-2);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,'      '.$rec['condicion_pago']);

    $pdf->Ln($br+2);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,$rec['departamento']);

    $pdf->Ln(0);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,'     '.$rec['venta_a_cta']);
	
    $pdf->Ln($br+2);
    $pdf->Cell($tab1);
    $pdf->Cell(40,10,'        '.$rec['n_notaremi']);
    
    $pdf->Ln(0);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['fecha_notaremi']);

	
    $pdf->Ln($br*1);
    $pdf->Cell($tab1+15);
    $pdf->Cell(40,10,$rec['codigo_proveedor']);
    
    $pdf->Ln(0);
    $pdf->Cell($tab1+50);
    $pdf->Cell(40,10,$rec['n_pedido']);
    
    $pdf->Ln(0);
    $pdf->Cell($tab2);
    $pdf->Cell(40,10,$rec['nit']);
    
    $consulta = $db->consulta("SELECT 
                CONCAT(p.nombre,' ',p.codigo) nombre,
                cantidad, 
                precio_unit, 
                ventas_no_sujetas, 
                ventas_exentas, 
                ventas_gravadas 
            FROM facturas_detalle fd
                LEFT JOIN producto p ON p.id = fd.id_producto
            WHERE id_documento='$id_documento'");
    
//    detalle
	$br = 3.00; // ajustar el espaciado entre lineas
    $pdf->Ln($br*5);
    while ($row = $db->fetch_array($consulta)){
        $pdf->Ln($br);
        $pdf->Cell($tab3);
        $pdf->Cell(0,10,$row['cantidad']);
        $pdf->Ln(0);
        $pdf->Cell($tab4);
        $pdf->Cell(0,10,$row['nombre']);
        $pdf->Ln(0);
        $pdf->Cell($tab5);
        $pdf->Cell(0,10,$row['precio_unit']);
        $pdf->Ln(0);
        $pdf->Cell($tab8);
        $pdf->Cell(0,10,$row['ventas_gravadas'],0,0,'R');
        $pdf->Ln($br);
    }
	
	$pdf->Ln($br);
	$pdf->Cell($tab3);
	$pdf->Cell(0,10,$rec['comentario']);
	
    $br=5.20;
    $pdf->SetXY(170, 180);
    
    $pdf->Ln(0);
    $pdf->Cell($tab4);
    $pdf->Cell(0,10,num2letras($totales['venta_total']));

    $pdf->Ln(0);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,' '.$totales['ventas_gravadas'],0,0,'R');
    
    $pdf->Ln($br);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,' '.$totales['iva'],0,0,'R');
    $pdf->Ln($br);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,' '.$totales['subtotal'],0,0,'R');
    $pdf->Ln($br);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,' '.$totales['iva_retenido'],0,0,'R');
     $pdf->Ln($br*3);
    $pdf->Cell($tab8);
    $pdf->Cell(0,10,' '.$totales['venta_total'],0,0,'R');
    
    $pdf->Output();
}


?>
