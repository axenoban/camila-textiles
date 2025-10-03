/* funciones-cliente.js */

// Función para confirmar el pedido
function confirmarPedido(id) {
    if (confirm("¿Estás seguro de que deseas confirmar este pedido?")) {
        window.location.href = `/confirmar-pedido/${id}`;
    }
}
