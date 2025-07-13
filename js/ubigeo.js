
// document.addEventListener('DOMContentLoaded', () => {
//   const depSel  = document.getElementById('departamento');
//   const provSel = document.getElementById('provincia');
//   const distSel = document.getElementById('distrito');

//   const provUrl = `${window.BASE_URL}ubicaciones/ajax_provincias.php`;
//   const distUrl = `${window.BASE_URL}ubicaciones/ajax_distritos.php`;

//   function poblar(selectEl, items, placeholder) {
//     let html = `<option value="">${placeholder}</option>`;
//     items.forEach(o => {
//       html += `<option value="${o.id}">${o.nombre}</option>`;
//     });
//     selectEl.innerHTML = html;
//   }

//   depSel.addEventListener('change', () => {
//     // 1) Limpiar y mostrar “cargando”
//     poblar(provSel, [], 'Cargando provincias…');
//     poblar(distSel, [], 'Primero selecciona provincia');

//     if (!depSel.value) {
//       return poblar(provSel, [], 'Seleccione departamento primero');
//     }

//     // 2) Traer provincias del servidor
//     fetch(`${provUrl}?departamento_id=${depSel.value}`)
//       .then(r => r.ok ? r.json() : Promise.reject(r.status))
//       .then(data => poblar(provSel, data, 'Selecciona una provincia'))
//       .catch(() => poblar(provSel, [], 'Error al cargar provincias'));
//   });

//   provSel.addEventListener('change', () => {
//     poblar(distSel, [], 'Cargando distritos…');

//     if (!provSel.value) {
//       return poblar(distSel, [], 'Seleccione provincia primero');
//     }

//     fetch(`${distUrl}?provincia_id=${provSel.value}`)
//       .then(r => r.ok ? r.json() : Promise.reject(r.status))
//       .then(data => poblar(distSel, data, 'Selecciona un distrito'))
//       .catch(() => poblar(distSel, [], 'Error al cargar distritos'));
//   });
// });



document.addEventListener('DOMContentLoaded', () => {
  const depSel  = document.getElementById('departamento');
  const provSel = document.getElementById('provincia');
  const distSel = document.getElementById('distrito');

  const provUrl = `${window.BASE_URL}ubicaciones/ajax_provincias.php`;
  const distUrl = `${window.BASE_URL}ubicaciones/ajax_distritos.php`;

  // Helper para rellenar un <select>
  function poblar(selectEl, items, placeholder) {
    let html = `<option value="">${placeholder}</option>`;
    items.forEach(o => {
      html += `<option value="${o.id}">${o.nombre}</option>`;
    });
    selectEl.innerHTML = html;
  }

  // 1) Cuando cambie Departamento ⇒ cargar Provincias
  depSel.addEventListener('change', () => {
    poblar(provSel, [], 'Cargando provincias…');
    poblar(distSel, [], 'Primero selecciona provincia');

    if (!depSel.value) {
      return poblar(provSel, [], 'Seleccione departamento primero');
    }

    fetch(`${provUrl}?departamento_id=${depSel.value}`)
      .then(r => r.ok ? r.json() : Promise.reject(r.status))
      .then(data => {
        poblar(provSel, data, 'Selecciona una provincia');

        // Si estamos en modo editar, preselecciona la que venía de PHP
        const selProv = provSel.dataset.selected;
        if (selProv) provSel.value = selProv;
      })
      .catch(() => {
        poblar(provSel, [], 'Error al cargar provincias');
      });
  });

  // 2) Cuando cambie Provincia ⇒ cargar Distritos
  provSel.addEventListener('change', () => {
    poblar(distSel, [], 'Cargando distritos…');

    if (!provSel.value) {
      return poblar(distSel, [], 'Seleccione provincia primero');
    }

    fetch(`${distUrl}?provincia_id=${provSel.value}`)
      .then(r => r.ok ? r.json() : Promise.reject(r.status))
      .then(data => {
        poblar(distSel, data, 'Selecciona un distrito');

        // Preselecciona el distrito que viene de PHP
        const selDist = distSel.dataset.selected;
        if (selDist) distSel.value = selDist;
      })
      .catch(() => {
        poblar(distSel, [], 'Error al cargar distritos');
      });
  });

  // 3) No dispares nada al cargar: mantiene los valores precargados
});

