# Product Name

PlayerTech

---

# Product Vision

PlayerTech es una plataforma SaaS multi-tenant para academias de fútbol orientada a la gestión operativa y administrativa de jugadores, acudientes, categorías, equipos, matrículas y pagos.

Su objetivo es centralizar la información de la academia y reemplazar procesos manuales basados en cuadernos, hojas de cálculo, grupos de WhatsApp y registros físicos.

La plataforma busca reducir la dependencia operativa de una única persona dentro de la academia, facilitando la colaboración entre administradores, coordinadores y profesores.

---

# Product Mission

Ayudar a las academias de fútbol a administrar su operación diaria desde un único lugar, permitiendo un mejor control de jugadores, acudientes, pagos y estructura deportiva.

---

# Problem Statement

Las academias de fútbol suelen administrar su operación mediante múltiples herramientas dispersas, dificultando:

* El control de jugadores activos.
* La gestión de acudientes.
* La organización de categorías y equipos.
* El seguimiento de matrículas.
* El control de pagos.
* La consulta de información histórica.
* El seguimiento de asistencia.
* La coordinación entre administradores y profesores.

Esto genera:

* Errores administrativos.
* Dependencia de registros manuales.
* Pérdida de información.
* Dificultad para conocer el estado financiero de los jugadores.
* Retrasos en la toma de decisiones.
* Sobrecarga operativa en los administradores.

---

# Market Validation

Las entrevistas realizadas con academias de fútbol permitieron identificar patrones comunes:

## Operación Centralizada

En muchas academias la operación depende de una sola persona que administra:

* Jugadores.
* Acudientes.
* Pagos.
* Categorías.
* Equipos.
* Comunicaciones.

Esta dependencia dificulta el crecimiento de la academia.

## Control de Asistencia Deficiente

La asistencia suele registrarse de manera informal mediante:

* Comunicación verbal entre profesores y coordinadores.
* Mensajes de WhatsApp.
* Registros físicos.

No existe trazabilidad ni historial centralizado.

## Dificultades con Pagos

Los pagos generalmente se controlan mediante:

* Cuadernos.
* Hojas de cálculo.
* Facturación electrónica separada.

Las academias presentan dificultades para responder preguntas como:

* ¿Quién está al día?
* ¿Quién tiene pagos pendientes?
* ¿Cuántos meses adeuda un jugador?

## Falta de Confirmación para Competencias

Los responsables de las academias necesitan conocer oportunamente:

* Qué jugadores participarán en competencias.
* Cuántos jugadores estarán disponibles.
* Qué familias han confirmado asistencia.

Actualmente esta información suele gestionarse mediante grupos de mensajería.

## Necesidad de Participación de Profesores

Las academias requieren que los profesores puedan registrar información operativa directamente en el sistema, evitando depender exclusivamente del administrador.

---

# Target Customers

## Primary

* Academias de fútbol pequeñas.
* Academias de fútbol medianas.
* Escuelas de formación deportiva.

## Secondary

* Clubes deportivos.
* Academias multisede.

---

# Product Scope (MVP V1)

## Academy Management

* Configuración de academia.
* Gestión de sedes.

## Sports Structure

* Gestión de categorías.
* Gestión de equipos.

## Player Management

* Registro de jugadores.
* Gestión de acudientes.
* Asociación jugador-acudiente.

## Membership Management

* Matrícula de jugadores.
* Consulta de jugadores activos.
* Historial de matrículas.

## Team Management

* Asignación de jugadores a equipos.
* Consulta de jugadores por equipo.

## Payment Management

* Registro manual de pagos.
* Asociación de pagos a un acudiente.
* Asociación de pagos a una matrícula.
* Carga de evidencias.
* Consulta histórica de pagos.

## Administration

* Gestión de usuarios administrativos.
* Control de acceso.

---

# User Roles

## Super Admin

Responsable de la administración global de la plataforma.

Capacidades:

* Crear academias.
* Activar academias.
* Suspender academias.
* Gestionar planes futuros.
* Brindar soporte administrativo.

## Academic Administrator

Responsable de la operación diaria de una academia.

Capacidades:

* Gestionar sedes.
* Gestionar categorías.
* Gestionar equipos.
* Gestionar jugadores.
* Gestionar acudientes.
* Gestionar matrículas.
* Gestionar asignaciones deportivas.
* Gestionar pagos.

---

# Core Business Flow

1. Crear academia.
2. Crear sedes.
3. Crear categorías.
4. Crear equipos.
5. Registrar acudientes.
6. Registrar jugadores.
7. Crear matrícula.
8. Asignar jugador a equipos.
9. Registrar pagos.
10. Consultar estado operativo.

---

# Out Of Scope (MVP V1)

* Control de asistencia.
* Convocatorias.
* Torneos.
* Inscripciones a torneos.
* Arbitrajes.
* Programación de partidos.
* Estadísticas deportivas.
* Portal para padres.
* Portal para jugadores.
* Aplicación móvil.
* Facturación electrónica.
* Pasarelas de pago.
* Integraciones WhatsApp.
* Inventario.
* Nómina.

---

# Future Vision

A partir de las entrevistas realizadas, se identificaron funcionalidades de alta relevancia para futuras versiones:

## Attendance Management

* Registro de asistencia por profesores.
* Historial de asistencia.
* Detección de ausencias recurrentes.
* Seguimiento a jugadores inactivos.

## Competition Management

* Convocatorias deportivas.
* Confirmación de participación.
* Gestión de disponibilidad de jugadores.

## Parent Communication

* Seguimiento a acudientes.
* Registro de observaciones.
* Historial de contacto.

## Financial Evolution

La plataforma deberá evolucionar para soportar distintos conceptos de cobro:

* Matrícula.
* Mensualidades.
* Inscripciones a torneos.
* Arbitrajes.
* Uniformes.
* Transporte.
* Eventos especiales.

El modelo financiero deberá permitir incorporar nuevos conceptos sin rediseñar la arquitectura principal.
