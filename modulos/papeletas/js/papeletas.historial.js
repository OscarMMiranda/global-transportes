// archivo: /modulos/papeletas/js/papeletas.historial.js
// Requisitos opcionales: Bootstrap 5, Bootstrap Icons, SweetAlert2 (Swal)
// Funcionalidad: abrir modal, cargar historial por AJAX, paginación ligera y filtros.

(() => {
  'use strict';

  const spinnerHtml = '<div class="text-center text-muted py-4"><div class="spinner-border text-primary" role="status" aria-hidden="true"></div> Cargando historial...</div>';

  // Helpers
  const $ = window.jQuery;
  function qs(sel, ctx = document) { return ctx.querySelector(sel); }
  function qsa(sel, ctx = document) { return Array.from(ctx.querySelectorAll(sel)); }
  function showError(msg) {
    if (window.Swal) Swal.fire({ icon: 'error', title: 'Error', text: msg });
    else console.error(msg);
  }

  // Estado local por modal (permite paginar)
  const state = {
    papeleta_id: null,
    page: 1,
    per_page: 6,
    total_pages: 1,
    tipo: ''
  };

  // Construye HTML de cada item (ajusta campos según tu API)
  function renderItem(item) {
    const tipoClass = {
      pago: 'type-pago',
      descuento: 'type-descuento',
      edicion: 'type-edicion',
      archivo: 'type-archivo'
    }[item.tipo] || 'type-edicion';

    const titulo = item.titulo || item.accion || (item.tipo ? item.tipo.toUpperCase() : 'Actividad');
    const detalle = item.detalle || item.descripcion || '';
    const usuario = item.usuario || 'Sistema';
    const fecha = item.fecha_hora || item.fecha || '';

    return `
      <div class="list-group-item ${tipoClass}">
        <div class="d-flex justify-content-between">
          <div>
            <div class="fw-semibold">${titulo}</div>
            <div class="small text-muted">${detalle}</div>
          </div>
          <div class="text-end small text-muted">
            <div>${usuario}</div>
            <div>${fecha}</div>
          </div>
        </div>
      </div>
    `;
  }

  // Render lista y paginación
  function renderList(data) {
    const container = qs('#historial_contenido');
    const pagEl = qs('#historial_paginacion');
    const lastUpdate = qs('#historial_last_update');

    if (!container) return;

    container.innerHTML = '';

    if (!data.items || data.items.length === 0) {
      container.innerHTML = '<div class="text-center text-muted py-4">No hay registros en el historial.</div>';
      if (pagEl) pagEl.innerHTML = '';
      if (lastUpdate) lastUpdate.textContent = data.last_update || '—';
      return;
    }

    const frag = document.createDocumentFragment();
    data.items.forEach(it => {
      const div = document.createElement('div');
      div.innerHTML = renderItem(it);
      frag.appendChild(div.firstElementChild);
    });
    container.appendChild(frag);

    // Paginación
    const total = data.total || data.items.length;
    state.total_pages = Math.max(1, Math.ceil(total / state.per_page));
    if (pagEl) renderPagination(pagEl);
    if (lastUpdate) lastUpdate.textContent = data.last_update || new Date().toLocaleString();
  }

  function renderPagination(pagEl) {
    pagEl.innerHTML = '';
    const current = state.page;
    const max = state.total_pages;

    function makeLi(page, label = null, disabled = false, active = false) {
      const li = document.createElement('li');
      li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
      const a = document.createElement('a');
      a.className = 'page-link';
      a.href = '#';
      a.dataset.page = page;
      a.textContent = label || page;
      li.appendChild(a);
      return li;
    }

    // Prev
    pagEl.appendChild(makeLi(Math.max(1, current - 1), '«', current <= 1));

    // Range (limit to 7)
    const start = Math.max(1, current - 3);
    const end = Math.min(max, current + 3);
    for (let p = start; p <= end; p++) {
      pagEl.appendChild(makeLi(p, p, false, p === current));
    }

    // Next
    pagEl.appendChild(makeLi(Math.min(max, current + 1), '»', current >= max));
  }

  // Cargar historial desde servidor
  async function loadHistorial() {
    if (!state.papeleta_id) return;
    const container = qs('#historial_contenido');
    const cargando = qs('#historial_cargando');
    const pagEl = qs('#historial_paginacion');

    if (cargando) cargando.style.display = 'block';
    if (container) container.innerHTML = '';

    const params = new URLSearchParams({
      id: state.papeleta_id,
      page: state.page,
      per_page: state.per_page,
      tipo: state.tipo || ''
    });

    try {
      const resp = await fetch('/modulos/papeletas/acciones/ver_historial.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
      });
      const text = await resp.text();
      if (cargando) cargando.style.display = 'none';

      let json;
      try { json = JSON.parse(text); } catch (e) {
        console.error('Historial parse error', e, text);
        if (container) container.innerHTML = '<div class="text-danger text-center py-3">Respuesta inválida del servidor.</div>';
        return;
      }

      if (json && json.success && json.data) {
        renderList(json.data);
      } else {
        if (container) container.innerHTML = '<div class="text-center text-muted py-3">' + (json && json.msg ? json.msg : 'No se pudo cargar el historial') + '</div>';
        if (pagEl) pagEl.innerHTML = '';
      }
    } catch (err) {
      console.error('Error cargar historial', err);
      if (cargando) cargando.style.display = 'none';
      if (container) container.innerHTML = '<div class="text-danger text-center py-3">Error de conexión al cargar historial.</div>';
    }
  }

  // Eventos y delegación
  function bindEvents() {
    // Abrir modal (jQuery compatible)
    if ($) {
      $(document).on('click', '.btnVerHistorial', function () {
        const id = $(this).data('id');
        state.papeleta_id = id;
        state.page = 1;
        state.tipo = qs('#historial_tipo_filtro') ? qs('#historial_tipo_filtro').value : '';
        // placeholder while carga
        const cont = qs('#historial_contenido');
        if (cont) cont.innerHTML = spinnerHtml;
        $('#modalVerHistorial').modal('show');
        loadHistorial();
      });
    } else {
      document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btnVerHistorial');
        if (!btn) return;
        state.papeleta_id = btn.dataset.id;
        state.page = 1;
        state.tipo = qs('#historial_tipo_filtro') ? qs('#historial_tipo_filtro').value : '';
        const cont = qs('#historial_contenido');
        if (cont) cont.innerHTML = spinnerHtml;
        const modalEl = qs('#modalVerHistorial');
        if (modalEl) new bootstrap.Modal(modalEl).show();
        loadHistorial();
      });
    }

    // Paginación (delegada)
    const pagEl = qs('#historial_paginacion');
    if (pagEl) {
      pagEl.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;
        e.preventDefault();
        const p = parseInt(a.dataset.page, 10);
        if (isNaN(p) || p < 1 || p > state.total_pages) return;
        state.page = p;
        loadHistorial();
      });
    }

    // Filtro y refrescar
    const filtro = qs('#historial_tipo_filtro');
    if (filtro) {
      filtro.addEventListener('change', () => {
        state.tipo = filtro.value;
        state.page = 1;
        loadHistorial();
      });
    }
    const btnRef = qs('#historial_refrescar');
    if (btnRef) btnRef.addEventListener('click', () => { loadHistorial(); });
  }

  // Inicializar al cargar DOM
  document.addEventListener('DOMContentLoaded', () => {
    bindEvents();
  });

  // Export para pruebas manuales
  window.PapeletasHistorial = { loadHistorial, state };

})();
