<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../database/conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ================================================
// 📊 CONSULTA DE INVENTARIO AGRUPADO POR PRODUCTO
// ================================================
global $pdo;
$sql = "
    SELECT 
        p.id AS id_producto,
        p.nombre AS producto,
        c.nombre_color,
        c.codigo_color,
        c.codigo_hex,
        c.stock_metros,
        c.stock_rollos
    FROM producto_colores c
    INNER JOIN productos p ON c.id_producto = p.id
    ORDER BY p.nombre ASC, c.nombre_color ASC
";
$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================================================
// 🧩 AGRUPAR POR PRODUCTO
// ================================================
$inventario = [];
foreach ($registros as $r) {
    $prod = $r['producto'];
    if (!isset($inventario[$prod])) {
        $inventario[$prod] = [
            'producto' => $prod,
            'total_metros' => 0,
            'total_rollos' => 0,
            'colores' => []
        ];
    }
    $inventario[$prod]['colores'][] = $r;
    $inventario[$prod]['total_metros'] += $r['stock_metros'];
    $inventario[$prod]['total_rollos'] += $r['stock_rollos'];
}

// ================================================
// ⚙️ CONFIGURAR DOMPDF
// ================================================
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ================================================
// 🧱 HTML DEL REPORTE
// ================================================
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario - Camila Textil</title>
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
        h2 {
            margin-top: 25px;
            font-size: 14px;
            color: #0d6efd;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 11.5px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
        }
        th {
            background-color: #f1f5fa;
            text-align: center;
        }
        td {
            vertical-align: middle;
        }
        .color-dot {
            display:inline-block;
            width:14px;
            height:14px;
            border-radius:50%;
            border:1px solid #666;
            margin-right:4px;
        }
        .low-stock {
            background: #fff3cd;
        }
        .no-stock {
            background: #f8d7da;
            color: #842029;
            font-weight: bold;
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
    <h1>Reporte de Inventario</h1>
    <p class="sub">Stock disponible agrupado por producto y color</p>

    <?php 
    $granTotalM = 0;
    $granTotalR = 0;

    foreach ($inventario as $prod):
        $granTotalM += $prod['total_metros'];
        $granTotalR += $prod['total_rollos'];
    ?>
        <h2><?= htmlspecialchars($prod['producto']); ?></h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Color</th>
                    <th style="width: 10%;">Código</th>
                    <th style="width: 15%;">Stock (m)</th>
                    <th style="width: 15%;">Stock (rollos)</th>
                    <th style="width: 30%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prod['colores'] as $c): 
                    $estado = 'Normal';
                    $clase = '';
                    if ($c['stock_metros'] == 0 && $c['stock_rollos'] == 0) {
                        $estado = 'Sin stock';
                        $clase = 'no-stock';
                    } elseif ($c['stock_metros'] < 10 || $c['stock_rollos'] < 2) {
                        $estado = 'Bajo stock';
                        $clase = 'low-stock';
                    }
                ?>
                    <tr class="<?= $clase; ?>">
                        <td>
                            <span class="color-dot" style="background-color:<?= htmlspecialchars($c['codigo_hex']); ?>;"></span>
                            <?= htmlspecialchars($c['nombre_color']); ?>
                        </td>
                        <td style="text-align:center;"><?= htmlspecialchars($c['codigo_color']); ?></td>
                        <td style="text-align:right;"><?= number_format($c['stock_metros'], 2); ?></td>
                        <td style="text-align:right;"><?= number_format($c['stock_rollos'], 2); ?></td>
                        <td><?= $estado; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="2">Totales del producto</td>
                    <td style="text-align:right;"><?= number_format($prod['total_metros'], 2); ?></td>
                    <td style="text-align:right;"><?= number_format($prod['total_rollos'], 2); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <h2 style="margin-top:25px;">Totales Generales</h2>
    <table>
        <tr class="total-row">
            <td style="width:70%;">TOTAL GENERAL DE INVENTARIO</td>
            <td style="text-align:right;">Metros: <?= number_format($granTotalM, 2); ?></td>
            <td style="text-align:right;">Rollos: <?= number_format($granTotalR, 2); ?></td>
        </tr>
    </table>

    <footer>
        <hr>
        Camila Textil &copy; <?= date('Y'); ?> — Reporte generado automáticamente
    </footer>
</body>
</html>
<?php
$html = ob_get_clean();

// ================================================
// 🖨️ GENERAR PDF
// ================================================
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_inventario.pdf", ["Attachment" => false]);
exit;
