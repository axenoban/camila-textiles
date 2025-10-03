<?php
// comentarios.php

require_once 'models/comentario.php';

class ComentariosController {

    public function listarComentarios() {
        $comentarioModel = new Comentario();
        $comentarios = $comentarioModel->obtenerComentarios();
        include('views/admin/comentarios.php');
    }

    public function eliminarComentario($id) {
        $comentarioModel = new Comentario();
        $comentarioModel->eliminarComentario($id);
        header('Location: /admin/comentarios');
    }
}
?>
