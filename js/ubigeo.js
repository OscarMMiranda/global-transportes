document.addEventListener('DOMContentLoaded', function () {
    const departamentoSelect = document.getElementById('departamento');
    const provinciaSelect = document.getElementById('provincia');
    const distritoSelect = document.getElementById('distrito');

    departamentoSelect.addEventListener('change', function () {
        const departamentoId = this.value;
        if (departamentoId) {
            fetch(`../../ubicaciones/ajax_provincias.php?departamento_id=${departamentoId}`)
                .then(res => res.json())
                .then(data => {
                    provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
                    distritoSelect.innerHTML = '<option value="">Primero selecciona una provincia</option>';
                    data.forEach(provincia => {
                        provinciaSelect.innerHTML += `<option value="${provincia.id}">${provincia.nombre}</option>`;
                    });
                });
        } else {
            provinciaSelect.innerHTML = '<option value="">Primero selecciona un departamento</option>';
            distritoSelect.innerHTML = '<option value="">Primero selecciona una provincia</option>';
        }
    });

    provinciaSelect.addEventListener('change', function () {
        const provinciaId = this.value;
        if (provinciaId) {
            fetch(`../../ubicaciones/ajax_distritos.php?provincia_id=${provinciaId}`)
                .then(res => res.json())
                .then(data => {
                    distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
                    data.forEach(distrito => {
                        distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nombre}</option>`;
                    });
                });
        } else {
            distritoSelect.innerHTML = '<option value="">Primero selecciona una provincia</option>';
        }
    });
});
