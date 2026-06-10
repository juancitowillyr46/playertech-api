# 00-product.md

# Product Name

PlayerTech

---

# Product Vision

PlayerTech es una plataforma SaaS multi-tenant para academias de fútbol orientada a la gestión operativa y administrativa de jugadores, acudientes, categorías, equipos, matrículas y pagos.

Su objetivo es centralizar la información de la academia y reemplazar procesos manuales basados en hojas de cálculo, grupos de mensajería y registros físicos.

---

# Problem Statement

Las academias de fútbol suelen administrar su operación mediante múltiples herramientas dispersas, dificultando:

* El control de jugadores activos.
* La gestión de acudientes.
* La organización de categorías y equipos.
* El seguimiento de matrículas.
* El control de pagos.
* La consulta de información histórica.

Esto genera errores administrativos, pérdida de información y dificultades para el crecimiento de la academia.

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
* Soporte administrativo.

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

# Multi-Tenant Strategy

La plataforma utilizará un modelo:

* Shared Database
* Shared Schema

Cada academia representa un tenant independiente.

Todas las entidades de negocio deberán incluir:

```text
academy_id
```

La aplicación será responsable de garantizar el aislamiento de datos entre academias.

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

# Future Financial Vision

La plataforma deberá evolucionar para soportar distintos conceptos de cobro:

* Matrícula.
* Mensualidades.
* Inscripciones a torneos.
* Arbitrajes.
* Uniformes.
* Transporte.
* Eventos especiales.

El modelo financiero deberá permitir incorporar nuevos conceptos sin rediseñar la arquitectura principal.
