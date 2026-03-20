// Lista de códigos que se cargará desde la base de datos
let codigosRegistrados = [];

// Cargar datos iniciales (Bodegas, Monedas y Códigos Existentes)
const cargarDatosIniciales = () => {
    console.log("Cargando datos iniciales...");
    const bodegaSelect = document.getElementById('bodega');
    const monedaSelect = document.getElementById('moneda');

    // Cargar Bodegas
    if (bodegaSelect) {
        bodegaSelect.innerHTML = '<option value="">Seleccione...</option>';
        fetch('obtenerBodegas.php')
            .then(res => res.json())
            .then(bodegas => {
                if (Array.isArray(bodegas)) {
                    bodegas.forEach(b => bodegaSelect.innerHTML += `<option value="${b.id}">${b.nombre}</option>`);
                }
            })
            .catch(err => console.error('Error al cargar bodegas:', err));
    }

    // Cargar Monedas
    if (monedaSelect) {
        monedaSelect.innerHTML = '<option value="">Seleccione...</option>';
        fetch('obtenerMonedas.php')
            .then(res => res.json())
            .then(monedas => {
                if (Array.isArray(monedas)) {
                    monedas.forEach(m => monedaSelect.innerHTML += `<option value="${m.nombre}">${m.nombre}</option>`);
                }
            })
            .catch(err => console.error('Error al cargar monedas:', err));
    }

    // Cargar Códigos Existentes para validación de unicidad
    fetch('obtenerCodigosExistentes.php')
        .then(res => res.json())
        .then(codigos => {
            if (Array.isArray(codigos)) {
                codigosRegistrados = codigos;
                console.log("Códigos registrados cargados:", codigosRegistrados.length);
            }
        })
        .catch(err => console.error('Error al cargar códigos existentes:', err));
};

// Carga dinámica de sucursales según bodega seleccionada
const bodegaElement = document.getElementById('bodega');
if (bodegaElement) {
    bodegaElement.addEventListener('change', (e) => {
        const sucursalSelect = document.getElementById('sucursal');
        const bodegaId = e.target.value;
        sucursalSelect.innerHTML = '<option value="">Seleccione...</option>';
        
        if (bodegaId) {
            fetch(`obtenerSucursales.php?bodega_id=${bodegaId}`)
                .then(res => res.json())
                .then(sucursales => {
                    if (Array.isArray(sucursales)) {
                        sucursales.forEach(s => sucursalSelect.innerHTML += `<option value="${s.id}">${s.nombre}</option>`);
                    }
                })
                .catch(err => console.error('Error al cargar sucursales:', err));
        }
    });
}

// Función de validación
const validarFormulario = () => {
    const obtenerValor = id => document.getElementById(id).value.trim();
    const alertar = msg => { alert(msg); return null; };

    // Obtenemos todos los checkboxes para mapear sus IDs por orden
    const checkboxesMaterial = Array.from(document.querySelectorAll('input[name="material"]'));
    const materialesSeleccionados = checkboxesMaterial
        .map((cb, index) => cb.checked ? index + 1 : null)
        .filter(id => id !== null);

    const datos = {
        codigo: obtenerValor('codigoProducto'),
        nombre: obtenerValor('nombreProducto'),
        bodega: obtenerValor('bodega'),
        sucursal: obtenerValor('sucursal'),
        moneda: obtenerValor('moneda'),
        precio: obtenerValor('precio'),
        descripcion: obtenerValor('descripcion'),
        materiales: materialesSeleccionados
    };

    // Validación de Código (UNICIDAD REAL)
    if (!datos.codigo) return alertar("El código del producto no puede estar en blanco.");
    if (datos.codigo.length < 5 || datos.codigo.length > 15) return alertar("El código del producto debe tener entre 5 y 15 caracteres.");
    if (!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/.test(datos.codigo)) return alertar("El código del producto debe contener letras y números");
    
    // Comparar contra los códigos traídos de la BD
    if (codigosRegistrados.includes(datos.codigo)) {
        return alertar("El código del producto ya está registrado.");
    }

    if (!datos.nombre) return alertar("El nombre del producto no puede estar en blanco.");
    if (datos.nombre.length < 2 || datos.nombre.length > 50) return alertar("El nombre del producto debe tener entre 2 y 50 caracteres.");

    if (!datos.bodega) return alertar("Debe seleccionar una bodega.");
    if (!datos.sucursal) return alertar("Debe seleccionar una sucursal para la bodega seleccionada.");
    if (!datos.moneda) return alertar("Debe seleccionar una moneda para el producto.");

    if (!datos.precio) return alertar("El precio del producto no puede estar en blanco.");
    if (!/^\d+(\.\d{1,2})?$/.test(datos.precio) || parseFloat(datos.precio) <= 0) 
        return alertar("El precio del producto debe ser un número positivo con hasta dos decimales.");

    if (datos.materiales.length < 2) return alertar("Debe seleccionar al menos dos materiales para el producto.");

    if (!datos.descripcion) return alertar("La descripción del producto no puede estar en blanco.");
    if (datos.descripcion.length < 10 || datos.descripcion.length > 1000) 
        return alertar("La descripción del producto debe tener entre 10 y 1000 caracteres.");

    return datos;
};

// Iniciar al cargar la ventana
window.onload = cargarDatosIniciales;
