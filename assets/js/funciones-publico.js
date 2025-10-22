/* funciones-publico.js */

// Validación simple para el formulario de contacto
document.getElementById("contact-form").addEventListener("submit", function(event) {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    
    if (name === "" || email === "") {
        event.preventDefault();
        alert("Por favor, complete todos los campos.");
    }
});