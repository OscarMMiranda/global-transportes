/**
 * app.js
 * Core JavaScript for Global Transportes
 * - Navbar toggle
 * - Modal open/close
 * - Safe JSON parsing (evita “[object Object]”)
 * - Chrome storage change listener blindado
 */

;(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', init);

  function init() {
    setupNavbarToggle();
    setupModalHandlers();
    setupStorageListener();
  }

  // ────────────────────────────────────────────────────────────────
  // Navbar toggle para móviles
  // ────────────────────────────────────────────────────────────────
  function setupNavbarToggle() {
    var toggleBtn = document.querySelector('.nav-toggle');
    var navMenu   = document.getElementById('navMenu');
    if (!toggleBtn || !navMenu) return;

    toggleBtn.addEventListener('click', function() {
      navMenu.classList.toggle('open');
    });
  }

  // ────────────────────────────────────────────────────────────────
  // Modal: abrir, cerrar, clic en overlay
  // ────────────────────────────────────────────────────────────────
  function setupModalHandlers() {
    document.querySelectorAll('[data-modal-target]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var targetId = btn.getAttribute('data-modal-target');
        var modal    = document.getElementById(targetId);
        openModal(modal);
      });
    });

    document.querySelectorAll('.modal-close').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var modal = btn.closest('.modal');
        closeModal(modal);
      });
    });

    document.querySelectorAll('.modal').forEach(function(modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeModal(modal);
        }
      });
    });
  }

  // ────────────────────────────────────────────────────────────────
  // Listener de chrome.storage con trazabilidad y blindaje
  // ────────────────────────────────────────────────────────────────
  function setupStorageListener() {
    // Evitar ejecución en login
    if (window.location.pathname.includes('login')) {
      console.log('🔒 Storage listener desactivado en login');
      return;
    }

    if (window.chrome && chrome.storage && chrome.storage.onChanged) {
      chrome.storage.onChanged.addListener(function(changes, areaName) {
        for (var key in changes) {
          var newVal = changes[key].newValue;

          // 🧪 Trazabilidad visual
          console.log(`📦 Cambio en ${areaName}.${key}`);
          console.log('🧠 Valor recibido:', newVal);

          var data = safeParse(newVal);
          handleStorageUpdate(key, data, areaName);
        }
      });
    }
  }

  // Handler por defecto — puedes extenderlo
  function handleStorageUpdate(key, data, area) {
    console.log('🔄 Storage actualizado:', area, key, data);
    // Aquí tu lógica para reaccionar a cambios
  }

  // ────────────────────────────────────────────────────────────────
  // safeParse: evita errores de JSON.parse sobre objetos
  // ────────────────────────────────────────────────────────────────
  function safeParse(input) {
    if (typeof input === 'string') {
      try {
        return JSON.parse(input);
      } catch (e) {
        console.warn('❌ safeParse: cadena no válida, devolviendo crudo:', input);
        return input;
      }
    }

    if (typeof input === 'object') {
      console.warn('⚠️ safeParse: ya es objeto, no se parsea:', input);
    }

    return input;
  }

  // ────────────────────────────────────────────────────────────────
  // Helpers globales para modales
  // ────────────────────────────────────────────────────────────────
  window.openModal = function(modal) {
    if (modal) modal.classList.add('show');
  };

  window.closeModal = function(modal) {
    if (modal) modal.classList.remove('show');
  };

})();