# 19-observability-local.md

# Observability Local Strategy

Este documento define una estrategia minima de observabilidad local para PlayerTech.

Su objetivo es permitir analisis de uso, consumo, errores y comportamiento operativo sin depender de una plataforma externa como Datadog desde el MVP.

---

# Purpose

La observabilidad local busca responder preguntas como:

* Que endpoint se usa mas.
* Que tenant consume mas recursos.
* Que operaciones fallan con mayor frecuencia.
* Cuanto tarda cada flujo critico.
* Que evento de negocio disparo una operacion.

La estrategia no reemplaza la auditoria de negocio ni la seguridad.

---

# Scope

Esta estrategia cubre:

* Logs estructurados.
* Correlacion de request.
* Metricas basicas de consumo.
* Visualizacion local.

No cubre:

* APM completo.
* Trazas distribuidas.
* Plataforma SaaS externa.
* Alerting avanzado.

---

# Design Principles

* Local-first.
* Incremental.
* Low overhead.
* Tenant-aware.
* Compatible con la arquitectura modular existente.
* Sin contaminar el dominio con preocupaciones de infraestructura.

---

# Core Concepts

## Logging

Los logs deben servir para diagnostico y trazabilidad tecnica.

Cada evento relevante debe incluir, cuando aplique:

* `request_id`
* `academy_id`
* `user_id`
* `role`
* `method`
* `path`
* `status_code`
* `duration_ms`
* `module`
* `event_name`

## Metrics

Las metricas deben servir para entender consumo y tendencia.

Metricas basicas sugeridas:

* Requests por ruta.
* Requests por academia.
* Latencia por endpoint.
* Conteo de errores por tipo.
* Operaciones por usuario.
* Eventos de negocio por modulo.

## Correlation

Cada request debe poder rastrearse de punta a punta con un identificador unico.

El identificador de correlacion debe:

* Leerse desde `X-Request-Id` si ya existe.
* Generarse si no viene en la peticion.
* Propagarse en la respuesta.
* Usarse en logs y errores HTTP.

---

# Local Architecture

## Request Flow

1. Presentation recibe la request.
2. Un subscriber o middleware resuelve el `request_id`.
3. El contexto de tenant y usuario se agrega al log.
4. Application ejecuta el caso de uso.
5. Infrastructure persiste el log o emite la metrica.
6. La respuesta devuelve el `request_id` al cliente.

## Suggested Layers

### Shared

Debe alojar:

* Contexto de observabilidad.
* Contratos de evento de consumo.
* Utilidades comunes de correlacion.

### Presentation

Debe alojar:

* Subscriber HTTP para request correlation.
* Hooks de entrada y salida de request.

### Application

Debe alojar:

* Emision de eventos de negocio observables.
* Casos de uso que registren actividad importante.

### Infrastructure

Debe alojar:

* Configuracion de Monolog.
* Persistencia o exportacion de metricas.
* Adaptadores para visor local si se agregan.

---

# Logging Strategy

## Current Base

La base actual ya incluye Monolog y un canal dedicado de `application`.

Eso permite separar:

* Logs generales del kernel.
* Logs de aplicacion.
* Errores tecnicos.

## Recommended Format

La salida ideal para observabilidad local es estructurada.

Preferencia:

* JSON logs para facilitar filtrado.
* Campos consistentes entre requests.
* Mensajes legibles para busqueda manual.

## Log Levels

Uso sugerido:

* `debug` para diagnostico local.
* `info` para eventos de negocio relevantes.
* `notice` para reglas de dominio esperadas.
* `warning` para validaciones y situaciones recuperables.
* `error` para fallos inesperados.

## Event Examples

* Usuario autenticado.
* Tenant resuelto.
* Jugador registrado.
* Pago registrado.
* Error de validacion.
* Error tecnico no controlado.

---

# Consumption Metrics

## Minimum Viable Metrics

Las metricas iniciales deben ser simples y utiles:

* Contador de requests por ruta.
* Latencia promedio por ruta.
* Conteo de respuestas 4xx y 5xx.
* Consumo por `academy_id`.
* Consumo por `user_id`.

## Business Metrics

Cuando el sistema necesite mas visibilidad, se pueden agregar:

* Jugadores creados por dia.
* Pagos registrados por periodo.
* Matriculas activas por academia.
* Equipos creados por academia.

## Storage Decision

Para el MVP local se prioriza:

1. Persistir logs.
2. Agregar metricas simples.
3. Exponer visualizacion local.

No se define aun una base de datos de metricas obligatoria.

---

# Local Visualization

## Goal

Permitir inspeccion rapida desde desarrollo local.

## Desired Views

* Log stream.
* Filtro por tenant.
* Filtro por request_id.
* Filtro por ruta.
* Top endpoints.
* Top errores.
* Latencia por flujo.

## Candidate Tools

Las herramientas concretas se decidiran mas adelante.

Opciones posibles:

* Visor simple basado en archivos.
* Panel local de metricas.
* Stack local con contenedores dedicados.

La eleccion dependera de la relacion costo/beneficio al momento de implementarlo.

---

# Security And Tenancy

## Rules

* Todo log sensible debe evitar contraseñas, tokens y datos personales innecesarios.
* Todo evento observable debe respetar `academy_id`.
* `ROLE_ROOT` debe quedar diferenciado de un usuario tenant.
* La observabilidad no puede saltarse el aislamiento por tenant.

## Audit Separation

La observabilidad tecnica no reemplaza la auditoria de negocio.

* Auditoria: quien ejecuto la operacion.
* Observabilidad: que paso, cuanto tardo y con que frecuencia.

---

# Implementation Criteria

Esta estrategia se considerara lista para implementar cuando:

* Exista una decision explicita de formato de log.
* Se defina el mecanismo de correlation id.
* Se acuerde el set minimo de metricas.
* Se elija la herramienta local de visualizacion.
* Se documente la trazabilidad en `specs/14-current-state.md`.

---

# Suggested Delivery Order

1. Correlation id.
2. Logs estructurados.
3. Contexto tenant y usuario.
4. Metricas basicas.
5. Visualizacion local.

