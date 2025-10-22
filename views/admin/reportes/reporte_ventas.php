<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../database/conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ===============================================
// 🧩 CONSULTA DE VENTAS (solo pedidos completados)
// ===============================================
global $pdo;
$sql = "
    SELECT 
        p.nombre AS producto,
        pe.unidad,
        SUM(pe.cantidad) AS cantidad_total,
        SUM(pe.total) AS total_ventas
    FROM pedidos pe
    INNER JOIN productos p ON pe.id_producto = p.id
    WHERE pe.estado = 'completado'
    GROUP BY p.id, pe.unidad
    ORDER BY p.nombre ASC, pe.unidad ASC
";
$stmt = $pdo->query($sql);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estructuramos los datos por producto y tipo de venta
$resumen = [];
foreach ($ventas as $v) {
    $producto = $v['producto'];
    if (!isset($resumen[$producto])) {
        $resumen[$producto] = [
            'producto' => $producto,
            'metros' => ['cantidad' => 0, 'total' => 0],
            'rollos' => ['cantidad' => 0, 'total' => 0],
        ];
    }
    if ($v['unidad'] === 'metro') {
        $resumen[$producto]['metros']['cantidad'] += $v['cantidad_total'];
        $resumen[$producto]['metros']['total'] += $v['total_ventas'];
    } else {
        $resumen[$producto]['rollos']['cantidad'] += $v['cantidad_total'];
        $resumen[$producto]['rollos']['total'] += $v['total_ventas'];
    }
}

// ===============================================
// 🧾 CONFIGURAR DOMPDF
// ===============================================
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ===============================================
// 🧱 HTML DEL REPORTE
// ===============================================
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - Camila Textil</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 30px;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        p.sub {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: right;
        }
        th {
            background-color: #f1f5fa;
            text-align: center;
        }
        td:first-child, th:first-child {
            text-align: left;
        }
        .total-row {
            background: #eaf1ff;
            font-weight: bold;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Reporte de Ventas</h1>
    <p class="sub">Resumen consolidado de productos vendidos por tipo de unidad (solo pedidos completados)</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant. por Metro</th>
                <th>Total por Metro (Bs)</th>
                <th>Cant. por Rollo</th>
                <th>Total por Rollo (Bs)</th>
                <th>Total General (Bs)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $granTotal = 0;
            foreach ($resumen as $r):
                $totalProd = $r['metros']['total'] + $r['rollos']['total'];
                $granTotal += $totalProd;
            ?>
                <tr>
                    <td><?= htmlspecialchars($r['producto']); ?></td>
                    <td><?= number_format($r['metros']['cantidad'], 2); ?></td>
                    <td><?= number_format($r['metros']['total'], 2); ?></td>
                    <td><?= number_format($r['rollos']['cantidad'], 2); ?></td>
                    <td><?= number_format($r['rollos']['total'], 2); ?></td>
                    <td><?= number_format($totalProd, 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="5">TOTAL GENERAL</td>
                <td><?= number_format($granTotal, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <footer>
        <hr>
        Camila Textil &copy; <?= date('Y'); ?> — Reporte de ventas generado automáticamente
    </footer>
</body>
</html>
<?php
$html = ob_get_clean();

// ===============================================
// 🖨️ GENERAR PDF
// ===============================================
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_ventas.pdf", ["Attachment" => false]);
exit;
