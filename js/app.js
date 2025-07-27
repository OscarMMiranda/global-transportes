/**
 * app.js
 * Core JavaScript for Global Transportes
 * - Navbar toggle
 * - Modal open/close
 * - Safe JSON parsing (avoids “[object Object]” errors)
 * - Chrome storage change listener (optional)
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
  // Navbar toggle for mobile
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
  // Modal handlers: open, close, overlay click
  // ────────────────────────────────────────────────────────────────
  function setupModalHandlers() {
    // Open buttons: <button data-modal-target="modalId">
    document.querySelectorAll('[data-modal-target]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var targetId = btn.getAttribute('data-modal-target');
        var modal    = document.getElementById(targetId);
        openModal(modal);
      });
    });

    // Close buttons: <button class="modal-close">
    document.querySelectorAll('.modal-close').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var modal = btn.closest('.modal');
        closeModal(modal);
      });
    });

    // Click outside content to close
    document.querySelectorAll('.modal').forEach(function(modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeModal(modal);
        }
      });
    });
  }

  // ────────────────────────────────────────────────────────────────
  // Chrome.storage listener with safe JSON.parse
  // ────────────────────────────────────────────────────────────────
  function setupStorageListener() {
    if (window.chrome && chrome.storage && chrome.storage.onChanged) {
      chrome.storage.onChanged.addListener(function(changes, areaName) {
        for (var key in changes) {
          var newVal = changes[key].newValue;
          var data   = safeParse(newVal);
          handleStorageUpdate(key, data, areaName);
        }
      });
    }
  }

  // Default handler—override or extend as needed
  function handleStorageUpdate(key, data, area) {
    console.log('Storage changed:', area, key, data);
    // Aquí tu lógica para reaccionar a cambios en el storage
  }

  // ────────────────────────────────────────────────────────────────
  // Safe JSON parse: solo parsea si es string válida
  // ────────────────────────────────────────────────────────────────
  function safeParse(input) {
    if (typeof input === 'string') {
      try {
        return JSON.parse(input);
      } catch (e) {
        console.warn('safeParse: no es JSON válido, devolviendo entrada cruda:', input);
        return input;
      }
    }
    return input;
  }

  // ────────────────────────────────────────────────────────────────
  // Global helpers para modales
  // ────────────────────────────────────────────────────────────────
  window.openModal = function(modal) {
    if (modal) modal.classList.add('show');
  };

  window.closeModal = function(modal) {
    if (modal) modal.classList.remove('show');
  };
  
})();
