document.getElementById('btnGuardar').addEventListener('click', () => {
    // 1. Validar formulario usando la función de validaciones.js
    const datosProducto = validarFormulario();

    // 2. Si la validación es exitosa (no es null), enviar por AJAX
    if (datosProducto) {
        console.log("Enviando datos al servidor:", datosProducto);
        fetch('procesarProducto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosProducto)
        })
        .then(respuesta => respuesta.json())
        .then(datos => {
            console.log('Respuesta del Servidor:', datos);
            if (datos.estado === 'exito') {
                alert('Producto procesado correctamente. Revisa la consola.');
            } else {
                alert('Error al procesar el producto.');
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert('Error de conexión con el servidor.');
        });
    }
});
