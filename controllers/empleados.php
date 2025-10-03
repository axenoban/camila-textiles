<?php
// empleados.php

require_once 'models/empleado.php';

class EmpleadosController {

    public function listarEmpleados() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->obtenerEmpleados();
        include('views/admin/empleados.php');
    }

    public function agregarEmpleado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $puesto = $_POST['puesto'];
            $salario = $_POST['salario'];

            $empleadoModel = new Empleado();
            $empleadoModel->agregarEmpleado($nombre, $puesto, $salario);
            header('Location: /admin/empleados');
        }
        include('views/admin/agregar_empleado.php');
    }

    public function editarEmpleado($id) {
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->obtenerEmpleadoPorId($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $puesto = $_POST['puesto'];
            $salario = $_POST['salario'];

            $empleadoModel->editarEmpleado($id, $nombre, $puesto, $salario);
            header('Location: /admin/empleados');
        }

        include('views/admin/editar_empleado.php');
    }

    public function eliminarEmpleado($id) {
        $empleadoModel = new Empleado();
        $empleadoModel->eliminarEmpleado($id);
        header('Location: /admin/empleados');
    }
}
?>
