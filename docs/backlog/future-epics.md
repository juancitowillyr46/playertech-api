# PlayerTech - Future Epics

## Objetivo

Este documento contiene las épicas identificadas durante el descubrimiento del producto que no forman parte del alcance del MVP V1.

Estas funcionalidades serán evaluadas y priorizadas en futuras versiones del producto según retroalimentación de las academias usuarias.

---

# EP-014 Gestión de Asistencia

## Objetivo

Permitir registrar y consultar la asistencia de los jugadores a entrenamientos y actividades deportivas.

## Origen

Identificada durante la entrevista con la directora de academia Doña Ángela.

Actualmente la asistencia se controla mediante comunicación verbal entre profesores y administración.

## Problema que Resuelve

No existe un registro formal que permita:

* Conocer asistencia histórica.
* Detectar ausencias recurrentes.
* Identificar jugadores inactivos.
* Compartir información entre profesores y administración.

## Valor de Negocio

Reduce procesos manuales y mejora el seguimiento de los deportistas.

## Actores

* Profesor
* Academic Administrator

## Dominios Futuros

* Attendance
* TrainingSession

## Historias Candidatas

* Registrar asistencia.
* Consultar asistencia.
* Registrar ausencia.
* Consultar historial de asistencia.
* Detectar ausencias recurrentes.

## Prioridad

Alta

---

# EP-015 Seguimiento de Jugadores

## Objetivo

Permitir realizar seguimiento administrativo y deportivo a los jugadores.

## Origen

Identificada durante la entrevista con Doña Ángela.

Actualmente los seguimientos se realizan mediante mensajes de WhatsApp y consultas manuales.

## Problema que Resuelve

Dificulta conocer:

* Motivos de ausencia.
* Situaciones particulares.
* Historial de seguimiento.

## Valor de Negocio

Mejora la comunicación entre academia y acudientes.

## Actores

* Profesor
* Academic Administrator

## Historias Candidatas

* Registrar observación.
* Registrar contacto con acudiente.
* Consultar historial de seguimiento.
* Registrar novedad deportiva.

## Prioridad

Alta

---

# EP-016 Gestión de Convocatorias

## Objetivo

Permitir organizar convocatorias para partidos, festivales y competencias.

## Origen

Identificada durante la entrevista con Doña Ángela.

Actualmente existe incertidumbre sobre la disponibilidad de jugadores para eventos deportivos.

## Problema que Resuelve

Los acudientes suelen informar tarde la asistencia a competencias.

Esto dificulta:

* Planificación de equipos.
* Organización logística.
* Confirmación de participantes.

## Valor de Negocio

Mejora la planificación deportiva.

## Actores

* Academic Administrator

## Historias Candidatas

* Crear convocatoria.
* Convocar jugadores.
* Confirmar asistencia.
* Rechazar asistencia.
* Consultar disponibilidad.

## Prioridad

Alta

---

# EP-017 Gestión de Actividades Especiales

## Objetivo

Permitir administrar actividades extraordinarias organizadas por la academia.

## Ejemplos

* Viajes.
* Festivales.
* Integraciones.
* Eventos deportivos.

## Problema que Resuelve

Actualmente los pagos y participantes se controlan manualmente.

## Valor de Negocio

Centraliza actividades complementarias.

## Actores

* Academic Administrator

## Historias Candidatas

* Crear actividad.
* Registrar participantes.
* Registrar pagos asociados.
* Consultar estado financiero de la actividad.

## Prioridad

Alta

---

# EP-018 Portal para Acudientes

## Objetivo

Permitir que los acudientes consulten información relacionada con sus jugadores.

## Problema que Resuelve

Reduce consultas manuales realizadas por WhatsApp.

## Valor de Negocio

Mejora la experiencia de los acudientes.

## Actores

* Acudiente

## Historias Candidatas

* Consultar pagos.
* Consultar matrícula.
* Consultar asistencia.
* Consultar actividades.
* Consultar convocatorias.

## Prioridad

Media

---

# EP-019 Aplicación Móvil

## Objetivo

Permitir utilizar PlayerTech desde dispositivos móviles.

## Origen

Solicitud explícita identificada durante la entrevista con Doña Ángela.

## Problema que Resuelve

Los administradores y profesores realizan gran parte de su trabajo fuera de una oficina.

## Valor de Negocio

Facilita la operación diaria.

## Actores

* Academic Administrator
* Profesor
* Acudiente

## Historias Candidatas

* Registrar asistencia desde móvil.
* Consultar jugadores.
* Consultar pagos.
* Gestionar convocatorias.

## Prioridad

Media

---

# EP-020 Integraciones Externas

## Objetivo

Permitir la integración de PlayerTech con servicios externos.

## Problema que Resuelve

Las academias utilizan múltiples herramientas desconectadas.

## Integraciones Potenciales

### Comunicación

* WhatsApp

### Financiero

* Facturación electrónica
* Pasarelas de pago

### Notificaciones

* Correo electrónico
* Mensajes SMS

## Valor de Negocio

Automatización de procesos operativos.

## Actores

* Academic Administrator

## Historias Candidatas

* Enviar recordatorios de pago.
* Notificar convocatorias.
* Generar facturación electrónica.
* Confirmar pagos automáticos.

## Prioridad

Media

---

# EP-022 Configuración de Modalidad Deportiva de la Academia

## Objetivo

Permitir que una academia defina su modalidad deportiva principal para que el sistema adapte reglas operativas como tamaños de equipo, validaciones de categorías y futura configuración deportiva.

## Origen

Identificada a partir de la necesidad de soportar academias que operan con fútbol, fútbol sala, baloncesto, vóley u otras disciplinas con reglas distintas.

## Problema que Resuelve

El sistema no distingue hoy entre academias deportivas según la disciplina principal, lo que dificulta parametrizar reglas como el número de jugadores por equipo o las categorías permitidas.

## Valor de Negocio

Mejora la parametrización del SaaS y prepara la plataforma para escenarios de multi deporte.

## Actores

* Academic Administrator
* Super Admin

## Historias Candidatas

* Registrar modalidad deportiva de la academia.
* Consultar modalidad deportiva de la academia.
* Actualizar modalidad deportiva de la academia.
* Ajustar reglas de equipo según modalidad deportiva.
* Ajustar reglas de categorías según modalidad deportiva.

## Prioridad

Media

---

# Notas de Evolución

Las siguientes necesidades fueron identificadas durante el descubrimiento del producto y deberán reevaluarse después de validar el MVP:

* Gestión de entrenadores.
* Horarios de entrenamiento.
* Programación de sesiones.
* Torneos.
* Partidos.
* Estadísticas deportivas.
* Portal para jugadores.
* Multi deporte.
* Inteligencia artificial aplicada al seguimiento deportivo.

Estas funcionalidades permanecen fuera del alcance actual.

---

# EP-023 Información Tributaria de Academias y Comprobantes DIAN

## Objetivo
Permitir que las academias registren su información tributaria y, en una etapa posterior, emitan comprobantes de pago con integración a DIAN.

## Problema que Resuelve
La plataforma necesita prepararse para escenarios de facturación fiscal real, donde los pagos requieran datos tributarios del emisor, numeración formal y sincronización con entidades externas.

## Valor de Negocio
Habilita cumplimiento fiscal, trazabilidad documental y una futura capa de facturación electrónica para academias que la requieran.

## Actores

* Super Admin
* Academy Admin
* Acudiente

## Dominios Involucrados

* Academy
* Billing
* Payment
* FiscalDocument
* DIAN Integration

## Historias Candidatas

* Registrar información tributaria de la academia.
* Consultar información tributaria de la academia.
* Actualizar información tributaria de la academia.
* Generar comprobante de pago.
* Consultar comprobante de pago.
* Enviar comprobante a DIAN.
* Consultar estado DIAN del comprobante.
* Anular comprobante emitido.
* Reintentar envío a DIAN ante error técnico.

## Prioridad

Media
