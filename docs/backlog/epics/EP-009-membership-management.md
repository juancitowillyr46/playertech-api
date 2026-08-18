# EP-009-membership-management Gestión de Matrículas

## Objetivo
Controlar la vinculación administrativa del jugador con la academia mediante una matrícula activa, su historial y sus transiciones de estado.

## Problema que Resuelve
Permite saber quién pertenece actualmente a la academia, quién fue su acudiente principal responsable y cómo evolucionó su matrícula a lo largo del tiempo.

## Dominio Involucrado
* Membership

## Reglas de Negocio Relacionadas
* Solo puede existir una matrícula activa por jugador dentro de una academia.
* Un jugador puede tener múltiples matrículas históricas.
* Cada matrícula debe tener un acudiente principal responsable.
* La matrícula debe conservar historial de estado.
* La suspensión y el retiro no deben borrar trazabilidad.

## Historias de Usuario
* Crear matrícula con acudiente principal.
* Consultar matrícula activa.
* Consultar historial de matrícula.
* Suspender matrícula.
* Retirar matrícula.

## Alcance Relacionado pero Separado
Las capacidades financieras asociadas al ingreso del jugador no forman parte del núcleo de esta épica y deben documentarse como dependencias o extensiones del bloque financiero:

* Generar cargos iniciales de matrícula y primera mensualidad.
* Registrar pagos sobre matrícula.
* Consultar saldo o deuda pendiente.
* Evidencia y conciliación de pagos.

## MVP
Sí.

## Estado
Implementado en runtime y alineado en `specs/14-current-state.md`.
