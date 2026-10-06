<?php
// ======================================================
//  	COMPONENTE      : 	tabla_ot_component.php
//  	MÓDULO			: 	Órdenes de Trabajo (OT)
//  	RESPONSABILIDAD : 	Tabla corporativa del listado OT
//  	GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// 		ESTADO 			: 	ACTIVO
// 		NO ELIMINAR
// ======================================================
?>

<section class="ot-tabla card-corp shadow-sm"
         data-componente="tabla_ot"
         data-version="4.1">

    <div class="card-corp-body p-3">

        <div class="table-responsive">

            <table id="ot-tabla-listado"
                   class="table table-striped table-bordered table-hover w-100 tabla-corp">

                <thead class="thead-corp">
                    <tr>
                        <th><i class="fa-solid fa-hashtag fa-fw"></i></th>
                        <th><i class="fa-solid fa-file-lines fa-fw me-1"></i> Número OT</th>
                        <th><i class="fa-solid fa-calendar-day fa-fw me-1"></i> Fecha</th>
                        <th><i class="fa-solid fa-user fa-fw me-1"></i> Cliente</th>
                        <th><i class="fa-solid fa-file-signature fa-fw me-1"></i> OC Cliente</th>
                        <th><i class="fa-solid fa-tags fa-fw me-1"></i> Tipo OT</th>
                        <th><i class="fa-solid fa-building fa-fw me-1"></i> Empresa</th>
                        <th><i class="fa-solid fa-route fa-fw me-1"></i> N° Viajes</th>
                        <th><i class="fa-solid fa-circle-info fa-fw me-1"></i> Estado</th>
                        <th><i class="fa-solid fa-gear fa-fw me-1"></i> Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- El contenido se carga vía DataTables + AJAX -->
                </tbody>

            </table>

        </div>

    </div>

</section>
