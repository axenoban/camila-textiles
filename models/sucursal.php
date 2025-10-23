<?php
// sucursal.php
// sucursal.php
require_once __DIR__ . '/../database/conexion.php';

class Sucursal
{
    // Obtener todas las sucursales
    public function obtenerSucursales()
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM sucursales");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener sucursales: ' . $e->getMessage());
            return [];
        }
    }

    // Obtener sucursal por ID
    public function obtenerSucursalPorId($id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM sucursales WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener sucursal por ID: ' . $e->getMessage());
            return null;
        }
    }

    // Agregar sucursal con latitud y longitud
    public function agregarSucursal($nombre, $direccion, $telefono, $horario_apertura, $latitud, $longitud, $visible)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO sucursales (nombre, direccion, telefono, horario_apertura, latitud, longitud, visible) 
            VALUES (:nombre, :direccion, :telefono, :horario_apertura, :latitud, :longitud, :visible)");
            return $stmt->execute([
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'horario_apertura' => $horario_apertura,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'visible' => $visible
            ]);
        } catch (PDOException $e) {
            error_log('Error al agregar sucursal: ' . $e->getMessage());
            return false;
        }
    }

    // Actualizar sucursal
    public function editarSucursal($id, $nombre, $direccion, $telefono, $horario_apertura, $latitud, $longitud, $visible)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("UPDATE sucursales SET nombre = :nombre, direccion = :direccion, telefono = :telefono, horario_apertura = :horario_apertura, latitud = :latitud, longitud = :longitud, visible = :visible WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'nombre' => $nombre,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'horario_apertura' => $horario_apertura,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'visible' => $visible
            ]);
        } catch (PDOException $e) {
            error_log('Error al editar sucursal: ' . $e->getMessage());
            return false;
        }
    }

    // Eliminar sucursal
    public function eliminarSucursal($id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("DELETE FROM sucursales WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log('Error al eliminar sucursal: ' . $e->getMessage());
            return false;
        }
    }

    // Cambiar visibilidad de una sucursal
    public function toggleVisibilidad($id)
    {
        global $pdo;
        try {
            $sql = "SELECT visible FROM sucursales WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $sucursal = $stmt->fetch(PDO::FETCH_ASSOC);

            $nuevoEstado = $sucursal['visible'] ? 0 : 1;

            $sql = "UPDATE sucursales SET visible = :visible WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute(['visible' => $nuevoEstado, 'id' => $id]);
        } catch (PDOException $e) {
            error_log('Error al cambiar visibilidad de sucursal: ' . $e->getMessage());
            return false;
        }
    }

    // Obtener solo sucursales visibles
    public function obtenerSucursalesVisibles()
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM sucursales WHERE visible = 1");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener sucursales visibles: ' . $e->getMessage());
            return [];
        }
    }
}
