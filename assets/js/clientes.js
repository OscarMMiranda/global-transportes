/**
 * archivo: /assets/js/clientes.js
 */

document.addEventListener('DOMContentLoaded', function () {

    // Si NO existe nada del módulo clientes, no ejecutar este archivo
    const esVistaClientes =
        document.getElementById('tablaClientes') ||
        document.getElementById('modalVerCliente') ||
        document.querySelector('.btn-ver') ||
        document.getElementById('departamento_id');

    if (!esVistaClientes) {
        return; // ← evita que este JS rompa otros módulos
    }

    console.log('clientes.js cargado');
    console.log('API URL:', window.CLIENTES_API_URL);

    // ============================
    // MODAL VER CLIENTE
    // ============================

    const modalEl   = document.getElementById('modalVerCliente');
    const modalBody = modalEl && modalEl.querySelector('.modal-body');
    const modal     = modalEl && new bootstrap.Modal(modalEl);

    const verButtons = document.querySelectorAll('.btn-ver');
    console.log('Botones VER encontrados:', verButtons.length);

    verButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            console.log('Click VER id:', id);

            if (!modal || !modalBody) {
                return console.error('No se encontró el modal o su body');
            }

            modalBody.innerHTML = '<p>Cargando…</p>';

            fetch(`${window.CLIENTES_API_URL}?method=view&id=${encodeURIComponent(id)}`)
                .then(resp => {
                    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                    return resp.text();
                })
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.show();
                })
                .catch(err => {
                    console.error('Error cargando detalle:', err);
                    modalBody.innerHTML = `<p class="text-danger">Error: ${err.message}</p>`;
                    modal.show();
                });
        });
    });

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        });
    }

    // ============================
    // SELECTS DINÁMICOS
    // ============================

    const depSelect  = document.getElementById('departamento_id');
    const provSelect = document.getElementById('provincia_id');
    const distSelect = document.getElementById('distrito_id');

    if (depSelect) {
        depSelect.addEventListener('change', function () {
            const depId = this.value;
            provSelect.innerHTML = '<option>Cargando…</option>';
            distSelect.innerHTML = '<option value="">Selecciona...</option>';

            fetch(`${window.CLIENTES_API_URL}?method=provincias&departamento_id=${depId}`)
                .then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(list => {
                    let html = '<option value="">Selecciona...</option>';
                    list.forEach(p => {
                        html += `<option value="${p.id}">${p.nombre}</option>`;
                    });
                    provSelect.innerHTML = html;
                })
                .catch(err => {
                    console.error('Error cargando provincias:', err);
                    provSelect.innerHTML = '<option value="">Error</option>';
                });
        });
    }

    if (provSelect) {
        provSelect.addEventListener('change', function () {
            const provId = this.value;
            distSelect.innerHTML = '<option>Cargando…</option>';

            fetch(`${window.CLIENTES_API_URL}?method=distritos&provincia_id=${provId}`)
                .then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(list => {
                    let html = '<option value="">Selecciona...</option>';
                    list.forEach(d => {
                        html += `<option value="${d.id}">${d.nombre}</option>`;
                    });
                    distSelect.innerHTML = html;
                })
                .catch(err => {
                    console.error('Error cargando distritos:', err);
                    distSelect.innerHTML = '<option value="">Error</option>';
                });
        });
    }

    // ============================
    // DATATABLES
    // ============================

    if (typeof $.fn.DataTable === "function" && document.getElementById('tablaClientes')) {
        if (!$.fn.DataTable.isDataTable('#tablaClientes')) {
            $('#tablaClientes').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 5 }
                ]
            });
        }
    }

    // ============================
    // TRANSFORMACIONES
    // ============================

    document.querySelectorAll('.uppercase').forEach(input => {
        input.style.textTransform = 'uppercase';
        input.addEventListener('input', () => {
            input.value = input.value.toUpperCase();
        });
    });

    document.querySelectorAll('.lowercase').forEach(input => {
        input.style.textTransform = 'lowercase';
        input.addEventListener('input', () => {
            input.value = input.value.toLowerCase();
        });
    });

});




