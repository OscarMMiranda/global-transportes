// ======================================================
//  JS: ot_modal_restaurar_actions.js
//  RESPONSABILIDAD: Acciones del modal de restauración OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

document.getElementById('ot-btn-confirmar-restaurar').addEventListener('click', function () {

    var id = document.getElementById('restaurar_id_ot').value;

    document.getElementById('loaderRestaurarOT').style.display = 'block';

    fetch('/modulos/orden_trabajo/api/ot_restaurar_api.php', {
        method: 'POST',
        body: new URLSearchParams({ id: id })
    })
    .then(function(r){ return r.json(); })
    .then(function(res){

        document.getElementById('loaderRestaurarOT').style.display = 'none';

        if (res.success) {
            alert('Orden restaurada correctamente');
            location.reload();
        } else {
            alert(res.message || 'Error al restaurar la orden');
        }

    });

});
