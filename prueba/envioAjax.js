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
            if (datos.estado === 'exito' || datos.mensaje.toLowerCase().includes('correctamente')) {
                alert('Producto procesado correctamente.');
                // Recargar la página para limpiar todos los campos automáticamente
                limpiarFormulario();
            } else {
                alert('Error al procesar el producto: ' + (datos.mensaje || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert('Error de conexión con el servidor.');
        });
    }

    // Función para limpiar todos los campos del formulario
    const limpiarFormulario = () => {
        // Limpiar inputs de texto
        document.getElementById('codigoProducto').value = '';
        document.getElementById('nombreProducto').value = '';
        document.getElementById('precio').value = '';
    
        // Resetear selects a su opción por defecto
        document.getElementById('bodega').selectedIndex = 0;
        document.getElementById('sucursal').innerHTML = '<option value="">Seleccione...</option>';
        document.getElementById('moneda').selectedIndex = 0;
    
        // Desmarcar todos los checkboxes
        const checkboxes = document.querySelectorAll('input[name="material"]');
        checkboxes.forEach(cb => cb.checked = false);
    
        // Limpiar textarea
        document.getElementById('descripcion').value = '';
    
        // Resetear banderas de validación de código
        codigoUnicoVerificado = false;
        ultimoCodigoVerificado = '';
    };
});
