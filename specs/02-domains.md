# 02-domains.md

# Visión General del Dominio

PlayerTech es una plataforma SaaS enfocada en la gestión operativa y administrativa de academias de fútbol.

La plataforma sigue una arquitectura Multi-Tenant donde todas las entidades de negocio pertenecen a una única academia mediante el campo:

- academy_id

El dominio se divide intencionalmente en dos contextos de negocio complementarios:

- Formación
- Competición

Aunque ambos están relacionados, representan procesos de negocio diferentes y evolucionan de forma independiente.

El **Jugador** es la entidad central del dominio.

---

# Principios del Dominio

## Modelo centrado en el Jugador

El jugador es el núcleo del dominio.

Los procesos administrativos, formativos y competitivos giran alrededor del jugador y no del equipo.

## Separación de responsabilidades

El dominio distingue claramente tres procesos:

- Gestión administrativa
- Formación deportiva
- Participación competitiva

Cada proceso posee sus propias reglas de negocio.

## Contexto de Formación

Representa el ciclo de vida administrativo y formativo del jugador dentro de la academia.

Conceptos principales:

- Jugador
- Matrícula
- Acudiente
- Pagos
- Categorías
- Sesiones de entrenamiento (Futuro)

## Contexto de Competición

Representa la participación deportiva de los jugadores en equipos y torneos.

Conceptos principales:

- Equipo
- Asignación de jugadores a equipos
- Torneos (Futuro)

Un jugador puede participar en múltiples equipos competitivos sin afectar su información administrativa.

---

# Academia

Representa una academia registrada en la plataforma.

## Responsabilidades

- Aislamiento Multi-Tenant.
- Configuración general de la academia.
- Administración de toda la información operativa.

## Estados

- ACTIVE
- SUSPENDED
- INACTIVE

---

# Sede

Representa una sede física donde la academia desarrolla sus actividades.

## Responsabilidades

- Lugar de entrenamiento.
- Lugar para partidos (Futuro).
- Punto operativo de la academia.

## Reglas

- Pertenece a una única academia.
- Puede albergar múltiples sesiones de entrenamiento.
- No representa una asignación permanente de jugadores ni de equipos.

## Estados

- ACTIVE
- INACTIVE

---

# Categoría

Representa la clasificación administrativa y deportiva de los jugadores según su rango de edad.

## Responsabilidades

- Organizar jugadores por edad.
- Definir rangos de edad.
- Servir como base para la organización de entrenamientos.
- Servir como referencia para equipos competitivos.

## Atributos

- Nombre
- Edad mínima
- Edad máxima

## Reglas

- La edad mínima debe ser menor que la edad máxima.
- No pueden existir categorías duplicadas dentro de una misma academia.

## Ejemplos

- Sub-6
- Sub-8
- Sub-10
- Sub-12

## Estados

- ACTIVE
- INACTIVE

---

# Jugador

Representa un jugador registrado en la academia.

El jugador es la entidad principal del dominio deportivo.

## Reglas

- Pertenece a una academia.
- Pertenece a una categoría administrativa.
- Puede tener múltiples acudientes.
- Puede tener múltiples matrículas a lo largo de su permanencia.
- Puede participar simultáneamente en múltiples equipos competitivos.

## Atributos MVP

- Nombres
- Apellidos
- Fecha de nacimiento
- Número de documento

## Atributos futuros

- Fotografía
- Correo electrónico
- Teléfono
- Nacionalidad
- Posición preferida
- Perfil dominante
- Identificador de federación

## Estados

- ACTIVE
- INACTIVE

---

# Acudiente

Representa un tutor legal o responsable del jugador.

## Reglas

- Puede existir independientemente de los jugadores.
- Puede estar relacionado con múltiples jugadores.
- Debe contener información de contacto.

## Estados

- ACTIVE
- INACTIVE

---

# Relación Jugador - Acudiente

Representa la relación entre jugadores y acudientes.

## Reglas

- Relación muchos a muchos.
- Todo jugador activo debe tener exactamente un acudiente principal.

## Responsabilidades

- Acudiente principal.
- Responsable de pagos.
- Responsable de autorizaciones.
- Contacto de emergencia.

## Atributos

- player_id
- guardian_id
- is_primary

---

# Matrícula

Representa la inscripción administrativa de un jugador dentro de la academia.

Controla la permanencia del jugador en la academia.

## Reglas

- Pertenece a un único jugador.
- Un jugador puede tener múltiples matrículas históricas.
- Solo puede existir una matrícula activa por jugador dentro de una academia.
- Los pagos pertenecen a una matrícula.
- La participación en equipos no genera matrículas.

## Estados

- ACTIVE
- SUSPENDED
- WITHDRAWN
- GRADUATED

---

# Equipo

Representa un equipo deportivo con fines competitivos.

Un equipo se crea para participar en competencias o torneos.

No representa la pertenencia administrativa del jugador.

## Responsabilidades

- Organizar jugadores para competir.
- Participar en torneos.
- Definir plantillas deportivas.

## Reglas

- Pertenece a una academia.
- Hace referencia a una categoría.
- Contiene múltiples asignaciones de jugadores.
- Un jugador puede pertenecer simultáneamente a múltiples equipos.

## Ejemplos

- Sub-12 Liga
- Sub-12 Mixto
- Sub-14 Competitivo

## Estados

- ACTIVE
- INACTIVE

---

# Asignación de Jugadores a Equipos

Representa la participación deportiva de un jugador dentro de un equipo.

Esta entidad separa la inscripción administrativa de la participación deportiva.

## Reglas

- Relación muchos a muchos.
- Un jugador puede pertenecer a múltiples equipos.
- Tiene fecha de inicio.
- Puede tener fecha de finalización.
- No genera obligaciones financieras.
- No reemplaza una matrícula.

## Atributos

- player_id
- team_id
- start_date
- end_date

---

# Concepto de Pago

Representa el motivo de un pago.

## Conceptos iniciales

- REGISTRATION
- MONTHLY_FEE
- OTHER

## Conceptos futuros

- TOURNAMENT_REGISTRATION
- UNIFORM
- TRANSPORT
- REFEREE_FEE
- EVENT

---

# Pago

Representa un pago realizado por un acudiente.

## Reglas

- Pertenece a una matrícula.
- Pertenece a un jugador.
- Pertenece a un acudiente.
- Pertenece a un concepto de pago.
- Debe registrar fecha y valor.

## Estados

- REGISTERED
- VOIDED

---

# Evidencia de Pago

Representa los soportes asociados a un pago.

## Tipos soportados

- Imagen
- PDF

## Reglas

- Pertenece a un pago.
- Un pago puede tener múltiples evidencias.

---

# Límites de Agregados

## Agregado Academia

Raíz del contexto Multi-Tenant.

## Agregado Jugador

Raíz del contexto deportivo y administrativo del jugador.

## Agregado Acudiente

Raíz del contexto administrativo del acudiente.

## Agregado Matrícula

Raíz del contexto de permanencia del jugador.

## Agregado Pago

Raíz del contexto financiero.

## Entidades de Relación

Las siguientes entidades no son Aggregate Roots:

- PlayerGuardian
- TeamAssignment

---

# Módulos Futuros

Fuera del alcance del MVP.

## Formación

- Entrenadores
- Sesiones de entrenamiento
- Asistencia a entrenamientos
- Planificación de entrenamientos

## Competición

- Torneos
- Inscripciones a torneos
- Partidos
- Estadísticas deportivas

## Administración

- Portal para acudientes
- Aplicación móvil
- Notificaciones
- Reportes

---

# Notas de Evolución del Dominio

## Evolución de Equipos

Los equipos representan agrupaciones competitivas.

La organización de los entrenamientos deberá evolucionar de manera independiente.

En futuras versiones podrán incorporarse:

- Grupos de entrenamiento
- Sesiones de entrenamiento
- Calendarios de entrenamiento

sin modificar el modelo de Equipos.

---

## Matrícula vs Asignación a Equipos

La matrícula representa la relación administrativa entre un jugador y la academia.

La asignación a equipos representa la participación deportiva del jugador.

Ambos conceptos deben permanecer independientes.

---

## Evolución del Jugador

El jugador puede evolucionar durante toda su permanencia en la academia.

Su evolución típica es:

Categoría → Matrícula → Equipos Competitivos → Torneos

sin cambiar su identidad dentro de la academia.

---

# Preguntas Abiertas

Los siguientes conceptos deberán validarse con academias reales antes de su implementación.

- Equipo principal.
- Grupos de entrenamiento.
- Ascenso automático de categoría.
- Equipos específicos por torneo.
- Participación de jugadores en categorías superiores.