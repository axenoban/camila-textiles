<?php
// sucursal.php

require_once __DIR__ . '/../database/conexion.php';

class Sucursal {

    // Método para obtener todas las sucursales
    public function obtenerSucursales() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM sucursales");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener una sucursal por ID
    public function obtenerSucursalPorId($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM sucursales WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para agregar una nueva sucursal
    public function agregarSucursal($nombre, $direccion, $telefono, $horario_apertura) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO sucursales (nombre, direccion, telefono, horario_apertura) VALUES (:nombre, :direccion, :telefono, :horario_apertura)");
        return $stmt->execute(['nombre' => $nombre, 'direccion' => $direccion, 'telefono' => $telefono, 'horario_apertura' => $horario_apertura]);
    }

    // Método para editar una sucursal
    public function editarSucursal($id, $nombre, $direccion, $telefono, $horario_apertura) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE sucursales SET nombre = :nombre, direccion = :direccion, telefono = :telefono, horario_apertura = :horario_apertura WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nombre' => $nombre, 'direccion' => $direccion, 'telefono' => $telefono, 'horario_apertura' => $horario_apertura]);
    }

    // Método para eliminar una sucursal
    public function eliminarSucursal($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM sucursales WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
