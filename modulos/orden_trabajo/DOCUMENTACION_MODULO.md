

LISTAR OT

Vista Principal
--------------
views/listado_ot_view.php
LISTAR OT
─────────────────────────────────

VISTA PRINCIPAL
│
└── views/listado_ot_view.php
    │
    ├── componentes/botones_superiores_component.php
    │
    ├── componentes/filtros_panel_component.php
    │       │
    │       ├── componentes/filtro_semana_component.php
    │       │
    │       └── componentes/tabs_estado_component.php
    │
    └── componentes/tabla_ot_component.php
            │
            └── <table id="ot-tabla-listado">


LISTAR OT
│
├── listado_ot_view.php
	│
	├── componentes/botones_superiores_component.php
	│
	├── componentes/filtros_panel_component.php
	│   		├── filtro_semana_component.php
	│   		└── tabs_estado_component.php
	│
	├── componentes/tabla_ot_component.php
	│
	└── componentes/scripts_ot_component.php
	│			├── ot_tabla_actions.js
	│			├── ot_filtro_estado_actions.js
	│			├── ot_componentes.js
	│			└── ot_modals_nueva.js
	│

Componentes
-----------
components/filtro_estado_component.php
components/filtro_semana_component.php
components/tabla_ot_component.php

Javascript
----------
js/ot_tabla_actions.js
js/ot_filtro_estado_actions.js
js/ot_componentes.js

API
---
api/ot_listado_api.php

Modelo
------
models/OrdenTrabajoModel.php

Estado
------
ACTIVO