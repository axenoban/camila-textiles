<?php
// empleado.php

require_once __DIR__ . '/../database/conexion.php';

class Empleado {

    // Método para obtener todos los empleados
    public function obtenerEmpleados() {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM empleados");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un empleado por ID
    public function obtenerEmpleadoPorId($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo empleado
    public function agregarEmpleado($nombre, $puesto, $salario) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO empleados (nombre, puesto, salario) VALUES (:nombre, :puesto, :salario)");
        return $stmt->execute(['nombre' => $nombre, 'puesto' => $puesto, 'salario' => $salario]);
    }

    // Método para editar un empleado
    public function editarEmpleado($id, $nombre, $puesto, $salario) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE empleados SET nombre = :nombre, puesto = :puesto, salario = :salario WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nombre' => $nombre, 'puesto' => $puesto, 'salario' => $salario]);
    }

    // Método para eliminar un empleado
    public function eliminarEmpleado($id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
