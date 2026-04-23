// js/reservacion.js
(function () {
    const selectHab   = document.getElementById('habitacion_id');
    const inputEntrada = document.getElementById('fecha_entrada');
    const inputSalida  = document.getElementById('fecha_salida');
    const inputPersonas = document.getElementById('personas');
    const preview      = document.getElementById('preview-precio');
    const textoPreview = document.getElementById('texto-precio');

    function actualizarPreview() {
        const opcion = selectHab.options[selectHab.selectedIndex];
        const entrada = inputEntrada.value;
        const salida  = inputSalida.value;

        if (!opcion.value || !entrada || !salida) {
            preview.style.display = 'none';
            return;
        }

        const precio = parseFloat(opcion.dataset.precio);
        const capacidad = parseInt(opcion.dataset.capacidad);
        const ms = new Date(salida) - new Date(entrada);
        const noches = ms / 86400000;

        if (noches <= 0) {
            textoPreview.textContent = '⚠️ La fecha de salida debe ser posterior a la de entrada.';
            preview.style.display = 'flex';
            preview.className = 'alerta alerta-error';
            return;
        }

        // Ajustar max de personas
        inputPersonas.max = capacidad;
        if (parseInt(inputPersonas.value) > capacidad) {
            inputPersonas.value = capacidad;
        }

        const total = noches * precio;
        const totalFormateado = total.toLocaleString('es-CR', { minimumFractionDigits: 0 });

        textoPreview.innerHTML = `
            <strong>${noches} noche${noches > 1 ? 's' : ''}</strong> × 
            ₡${precio.toLocaleString('es-CR')} = 
            <strong>₡${totalFormateado} total</strong>
        `;
        preview.style.display = 'flex';
        preview.className = 'alerta alerta-info';
    }

    // Validar que salida > entrada al cambiar
    inputEntrada.addEventListener('change', function () {
        if (inputSalida.value && inputSalida.value <= this.value) {
            const next = new Date(this.value);
            next.setDate(next.getDate() + 1);
            inputSalida.value = next.toISOString().split('T')[0];
        }
        inputSalida.min = this.value;
        actualizarPreview();
    });

    selectHab.addEventListener('change', actualizarPreview);
    inputSalida.addEventListener('change', actualizarPreview);
    inputPersonas.addEventListener('change', actualizarPreview);

    // Si viene con habitación preseleccionada
    if (selectHab.value) actualizarPreview();
})();
