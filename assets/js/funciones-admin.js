/* funciones-admin.js */

// Función para eliminar un producto
function eliminarProducto(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        window.location.href = `/eliminar-producto/${id}`;
    }
}

// Función para cambiar el estado de un pedido
function cambiarEstadoPedido(id, estado) {
    if (confirm("¿Estás seguro de que deseas cambiar el estado del pedido?")) {
        window.location.href = `/cambiar-estado-pedido/${id}/${estado}`;
    }
}
