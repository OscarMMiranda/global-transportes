<!-- archivo: /modulos/documentos_vehiculos/vistas/vista_documentos.php -->

<div id="panelDocumentosVehiculo">

  <table id="tablaDocumentosVehiculo" class="tabla-documentos">
    <thead>
      <tr>
        <th>Estado</th>
        <th>Descripción</th>
        <th>Fecha Vigente</th>
        <th>Adjuntar</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <!-- Modal simple para adjuntar -->
  <div id="modalAdjuntarDocumento" class="modal-doc" style="display:none;">
    <div class="modal-doc-contenido">
      <h3 id="tituloModalDoc">Adjuntar documento</h3>
      <input type="file" id="archivoDocumento"><br><br>
      <label>Fecha de vencimiento:</label>
      <input type="date" id="fechaVencimiento"><br><br>
      <button id="btnGuardarDoc">Guardar</button>
      <button onclick="cerrarModalAdjuntar()">Cancelar</button>
    </div>
  </div>

</div>
