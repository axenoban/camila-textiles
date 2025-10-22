<?php
// models/producto.php
require_once __DIR__ . '/../database/conexion.php';

class Producto
{
    /* =======================================================
       📦 PRODUCTOS
    ======================================================= */

    // Obtener productos visibles (para el catálogo público)
    public function obtenerProductosVisibles()
    {
        global $pdo;

        $sql = "
            SELECT 
                p.*,
                COALESCE(SUM(pc.stock_metros + (pc.stock_rollos * p.metros_por_rollo)), 0) AS stock_total
            FROM productos p
            LEFT JOIN producto_colores pc ON p.id = pc.id_producto
            WHERE p.visible = 1
            GROUP BY p.id
            ORDER BY p.fecha_creacion DESC
        ";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodosLosProductos()
    {
        global $pdo;

        // Solo obtener productos activos
        $sql = "
        SELECT 
            p.*,
            COUNT(DISTINCT pc.id) AS total_colores,
            COALESCE(SUM(pc.stock_metros + (pc.stock_rollos * p.metros_por_rollo)), 0) AS stock_total
        FROM productos p
        LEFT JOIN producto_colores pc ON p.id = pc.id_producto
        WHERE p.estado = 'activo'  -- Solo productos activos
        GROUP BY p.id
        ORDER BY p.fecha_creacion DESC
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Contar productos visibles
    public function contarProductosVisibles()
    {
        global $pdo;
        return (int) $pdo->query("SELECT COUNT(*) FROM productos WHERE visible = 1")->fetchColumn();
    }

    // Obtener productos destacados (por fecha o visibilidad)
    public function obtenerProductosDestacados($limite = 6)
    {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT * FROM productos
            WHERE visible = 1
            ORDER BY fecha_creacion DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por ID
    public function obtenerProductoPorId($id)
    {
        global $pdo;

        $sql = "
            SELECT 
                p.*,
                COALESCE(SUM(pc.stock_metros + (pc.stock_rollos * p.metros_por_rollo)), 0) AS stock_total
            FROM productos p
            LEFT JOIN producto_colores pc ON p.id = pc.id_producto
            WHERE p.id = :id
            GROUP BY p.id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================================================
       🎨 COLORES Y VARIANTES
    ======================================================= */

    // 🟣 Obtener todos los colores de un producto
    public function obtenerColoresPorProducto($idProducto)
    {
        global $pdo;
        $sql = "SELECT * FROM producto_colores WHERE id_producto = :id_producto ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_producto' => $idProducto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un color específico por ID
    public function obtenerColorPorId($idColor)
    {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM producto_colores WHERE id = :id");
        $stmt->execute(['id' => (int)$idColor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener el stock total de un color (en metros y rollos)
    public function obtenerStockColor($idColor)
    {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT stock_metros, stock_rollos 
            FROM producto_colores 
            WHERE id = :id
        ");
        $stmt->execute(['id' => (int)$idColor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Disminuir stock tras un pedido (por tipo de unidad)
    public function disminuirStockColor($idColor, $unidad, $cantidad)
    {
        global $pdo;

        if ($unidad === 'metro') {
            $sql = "UPDATE producto_colores 
                    SET stock_metros = GREATEST(stock_metros - :cantidad, 0)
                    WHERE id = :id_color";
        } else {
            $sql = "UPDATE producto_colores 
                    SET stock_rollos = GREATEST(stock_rollos - :cantidad, 0)
                    WHERE id = :id_color";
        }

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'cantidad' => (float)$cantidad,
            'id_color' => (int)$idColor
        ]);
    }

    /* =======================================================
       🧱 CRUD BÁSICO DE PRODUCTOS (VERSIONES ANTIGUAS)
    ======================================================= */

    public function agregarProducto($nombre, $descripcion, $precioMetro, $precioRollo, $imagen, $tipoTela = null, $composicion = null)
    {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO productos 
            (nombre, descripcion, precio_metro, precio_rollo, imagen_principal, tipo_tela, composicion) 
            VALUES 
            (:nombre, :descripcion, :precio_metro, :precio_rollo, :imagen, :tipo_tela, :composicion)
        ");

        return $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio_metro' => $precioMetro,
            'precio_rollo' => $precioRollo,
            'imagen' => $imagen,
            'tipo_tela' => $tipoTela,
            'composicion' => $composicion
        ]);
    }

    public function editarProducto($id, $nombre, $descripcion, $precioMetro, $precioRollo, $imagen, $tipoTela = null, $composicion = null)
    {
        global $pdo;

        try {
            // Iniciar la transacción
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                UPDATE productos 
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio_metro = :precio_metro,
                    precio_rollo = :precio_rollo,
                    imagen_principal = :imagen,
                    tipo_tela = :tipo_tela,
                    composicion = :composicion
                WHERE id = :id
            ");

            $stmt->execute([
                'id' => $id,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio_metro' => $precioMetro,
                'precio_rollo' => $precioRollo,
                'imagen' => $imagen,
                'tipo_tela' => $tipoTela,
                'composicion' => $composicion
            ]);

            // Si todo sale bien, commit
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            // En caso de error, hacer rollback
            $pdo->rollBack();
            throw $e;
        }
    }

    // Modelo Producto.php

    public function eliminarProducto($idProducto)
    {
        global $pdo;

        // Verificar si el producto tiene relaciones
        if ($this->tieneRelaciones($idProducto)) {
            // Cambiar el estado a 'inactivo' en lugar de eliminar
            $sql = "UPDATE productos SET estado = 'inactivo', visible = 0 WHERE id = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_producto' => $idProducto]);
            return true; // Producto desactivado correctamente
        } else {
            // Si no tiene relaciones, se puede eliminar
            $sql = "DELETE FROM productos WHERE id = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_producto' => $idProducto]);
            return true; // Producto eliminado correctamente
        }
    }

    // Método para verificar si el producto tiene relaciones en otras tablas
    public function tieneRelaciones($idProducto)
    {
        global $pdo;

        // Verificar en la tabla `producto_colores`
        $sql = "SELECT COUNT(*) FROM producto_colores WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_producto' => $idProducto]);
        $countColores = $stmt->fetchColumn();

        // Verificar en la tabla `pedidos`
        $sql = "SELECT COUNT(*) FROM pedidos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_producto' => $idProducto]);
        $countPedidos = $stmt->fetchColumn();

        // Si hay registros en las tablas relacionadas, devolver true
        return ($countColores > 0 || $countPedidos > 0);
    }






    /* =======================================================
       🧩 CRUD COMPLETO (NUEVAS VERSIONES - 2025)
    ======================================================= */

    public function crearProducto(array $data)
    {
        global $pdo;

        $sql = "INSERT INTO productos (
                    nombre, descripcion, ancho_metros, composicion, tipo_tela, gramaje, elasticidad,
                    precio_metro, precio_rollo, metros_por_rollo, imagen_principal, visible
                ) VALUES (
                    :nombre, :descripcion, :ancho_metros, :composicion, :tipo_tela, :gramaje, :elasticidad,
                    :precio_metro, :precio_rollo, :metros_por_rollo, :imagen_principal, :visible
                )";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'nombre'           => $data['nombre'],
            'descripcion'      => $data['descripcion'],
            'ancho_metros'     => $data['ancho_metros'],
            'composicion'      => $data['composicion'],
            'tipo_tela'        => $data['tipo_tela'],
            'gramaje'          => $data['gramaje'],
            'elasticidad'      => $data['elasticidad'],
            'precio_metro'     => $data['precio_metro'],
            'precio_rollo'     => $data['precio_rollo'],
            'metros_por_rollo' => $data['metros_por_rollo'],
            'imagen_principal' => $data['imagen_principal'],
            'visible'          => $data['visible']
        ]);
    }

    public function actualizarProducto($id, array $data)
    {
        global $pdo;

        $sql = "UPDATE productos SET 
                nombre = :nombre,
                descripcion = :descripcion,
                ancho_metros = :ancho_metros,
                composicion = :composicion,
                tipo_tela = :tipo_tela,
                gramaje = :gramaje,
                elasticidad = :elasticidad,
                precio_metro = :precio_metro,
                precio_rollo = :precio_rollo,
                metros_por_rollo = :metros_por_rollo,
                imagen_principal = :imagen_principal,
                visible = :visible
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'id'               => $id,
            'nombre'           => $data['nombre'],
            'descripcion'      => $data['descripcion'],
            'ancho_metros'     => $data['ancho_metros'],
            'composicion'      => $data['composicion'],
            'tipo_tela'        => $data['tipo_tela'],
            'gramaje'          => $data['gramaje'],
            'elasticidad'      => $data['elasticidad'],
            'precio_metro'     => $data['precio_metro'],
            'precio_rollo'     => $data['precio_rollo'],
            'metros_por_rollo' => $data['metros_por_rollo'],
            'imagen_principal' => $data['imagen_principal'],
            'visible'          => $data['visible']
        ]);
    }

    public function actualizarVisibilidad($id, $visible)
    {
        global $pdo;

        $sql = "UPDATE productos SET visible = :visible WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // Verificar si la actualización fue exitosa
        if ($stmt->execute([
            'visible' => (int)$visible,
            'id' => (int)$id
        ])) {
            return true;
        } else {
            return false;
        }
    }
    public function obtenerProductosPorEstado($estado)
    {
        global $pdo;

        $sql = "
        SELECT 
            p.*,
            COUNT(DISTINCT pc.id) AS total_colores,
            COALESCE(SUM(pc.stock_metros + (pc.stock_rollos * p.metros_por_rollo)), 0) AS stock_total
        FROM productos p
        LEFT JOIN producto_colores pc ON p.id = pc.id_producto
        WHERE p.estado = :estado
        GROUP BY p.id
        ORDER BY p.fecha_creacion DESC
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['estado' => $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoProducto($id, $estado)
    {
        global $pdo;

        $sql = "UPDATE productos SET estado = :estado WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['estado' => $estado, 'id' => (int)$id]);
    }
}
