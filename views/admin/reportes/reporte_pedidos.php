<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../database/conexion.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ==================================================
// 📦 CONSULTA: pedidos agrupados (confirmados + completados)
// ==================================================
global $pdo;
$sql = "
    SELECT 
        u.nombre AS cliente,
        p.nombre AS producto,
        pe.unidad,
        GROUP_CONCAT(DISTINCT c.nombre_color ORDER BY c.nombre_color SEPARATOR ', ') AS colores,
        GROUP_CONCAT(DISTINCT c.codigo_color ORDER BY c.codigo_color SEPARATOR ',') AS codigos_color,
        GROUP_CONCAT(DISTINCT c.codigo_hex ORDER BY c.nombre_color SEPARATOR ',') AS codigos_hex,
        SUM(pe.cantidad) AS cantidad_total,
        SUM(pe.total) AS total_pedido,
        pe.estado,
        MAX(pe.fecha_creacion) AS fecha_creacion
    FROM pedidos pe
    INNER JOIN usuarios u ON pe.id_usuario = u.id
    INNER JOIN productos p ON pe.id_producto = p.id
    INNER JOIN producto_colores c ON pe.id_color = c.id
    WHERE pe.estado IN ('confirmado', 'completado')
    GROUP BY u.id, p.id, pe.unidad, pe.estado
    ORDER BY u.nombre ASC, p.nombre ASC
";
$stmt = $pdo->query($sql);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================================================
// 🧾 CONFIGURAR DOMPDF
// ==================================================
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ==================================================
// 🧱 HTML DEL REPORTE
// ==================================================
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pedidos - Camila Textil</title>
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
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
            vertical-align: top;
        }
        .color-dot {
            display:inline-block;
            width:12px;
            height:12px;
            border-radius:50%;
            border:1px solid #666;
            margin-right:4px;
        }
        .estado {
            font-weight: bold;
            text-transform: capitalize;
            text-align: center;
        }
        .pendiente { color: #b58900; }
        .confirmado { color: #0d6efd; }
        .completado { color: #198754; }
        .cancelado { color: #6c757d; }
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
    <h1>Reporte de Pedidos</h1>
    <p class="sub">Resumen de pedidos confirmados y completados</p>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Colores</th>
                <th>Unidad</th>
                <th>Cantidad Total</th>
                <th>Total (Bs)</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalGeneral = 0;
            foreach ($pedidos as $p): 
                $colores = explode(', ', $p['colores']);
                $hex = explode(',', $p['codigos_hex']);
                $codigos = explode(',', $p['codigos_color'] ?? '');
                $totalGeneral += $p['total_pedido'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($p['cliente']); ?></td>
                    <td><?= htmlspecialchars($p['producto']); ?></td>
                    <td>
                        <?php foreach ($colores as $i => $color): ?>
                            <div style="margin-bottom:2px;">
                                <span class="color-dot" style="background-color:<?= htmlspecialchars($hex[$i] ?? '#ccc'); ?>;"></span>
                                <strong><?= htmlspecialchars($codigos[$i] ?? '-'); ?></strong>
                                <small><?= htmlspecialchars($color); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td style="text-align:center;"><?= ucfirst($p['unidad']); ?></td>
                    <td style="text-align:right;"><?= number_format($p['cantidad_total'], 2); ?></td>
                    <td style="text-align:right;"><?= number_format($p['total_pedido'], 2); ?></td>
                    <td class="estado <?= htmlspecialchars($p['estado']); ?>"><?= ucfirst($p['estado']); ?></td>
                    <td style="text-align:center;"><?= date('d/m/Y', strtotime($p['fecha_creacion'])); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="5">TOTAL GENERAL</td>
                <td colspan="3" style="text-align:right;"><?= number_format($totalGeneral, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <footer>
        <hr>
        Camila Textil &copy; <?= date('Y'); ?> — Reporte generado automáticamente
    </footer>
</body>
</html>
<?php
$html = ob_get_clean();

// ==================================================
// 🖨️ GENERAR PDF
// ==================================================
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_pedidos.pdf", ["Attachment" => false]);
exit;
