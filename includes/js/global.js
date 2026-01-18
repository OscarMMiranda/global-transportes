/* archivo: includes/js/global.js */

/* ============================================================
   GLOBAL.JS — Funciones universales del ERP
   Compatible con PHP 5.6 — Sin sintaxis moderna
   ============================================================ */

/* ------------------------------
   Loader universal
--------------------------------*/
function mostrarLoader() {
    var loader = document.getElementById('loader_global');
    if (loader) {
        loader.style.display = 'block';
    }
}

function ocultarLoader() {
    var loader = document.getElementById('loader_global');
    if (loader) {
        loader.style.display = 'none';
    }
}

/* ------------------------------
   AJAX universal (POST)
--------------------------------*/
function ajaxPost(url, data, callback) {
    mostrarLoader();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            ocultarLoader();
            if (xhr.status === 200) {
                callback(xhr.responseText);
            } else {
                console.log("Error AJAX POST → " + url);
            }
        }
    };

    xhr.send(data);
}

/* ------------------------------
   AJAX universal (GET)
--------------------------------*/
function ajaxGet(url, callback) {
    mostrarLoader();

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            ocultarLoader();
            if (xhr.status === 200) {
                callback(xhr.responseText);
            } else {
                console.log("Error AJAX GET → " + url);
            }
        }
    };

    xhr.send();
}

/* ------------------------------
   Helpers universales
--------------------------------*/
function formatearFecha(fecha) {
    if (!fecha) return "";
    var partes = fecha.split("-");
    return partes[2] + "/" + partes[1] + "/" + partes[0];
}

function soloNumeros(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode < 48 || charCode > 57) {
        evt.preventDefault();
    }
}