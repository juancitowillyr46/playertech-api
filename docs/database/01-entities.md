# 01-entities.md

# Entidades del Dominio

Este documento describe las principales entidades que conforman el dominio de PlayerTech.

La plataforma sigue un modelo **Player-Centric**, donde el jugador es la entidad principal y alrededor de él se construyen los procesos administrativos, formativos y competitivos.

---

# Academy

Representa una academia registrada dentro de PlayerTech.

### Tipo

Aggregate Root

### Responsabilidades

- Configuración general de la academia.
- Aislamiento Multi-Tenant.
- Administración de sedes.
- Administración de usuarios.
- Propietaria de toda la información del negocio.

### Estado

- ACTIVE
- SUSPENDED
- INACTIVE

---

# Venue

Representa una sede física donde la academia desarrolla sus actividades.

### Tipo

Entidad

### Responsabilidades

- Identificar ubicaciones físicas.
- Soportar entrenamientos y actividades deportivas.
- Ser utilizada por futuras sesiones de entrenamiento.

### Estado

- ACTIVE
- INACTIVE

---

# User

Representa una persona autorizada para acceder a la plataforma.

### Tipo

Aggregate Root

### Responsabilidades

- Autenticación.
- Autorización.
- Operación administrativa.

### Estado

- ACTIVE
- INACTIVE

---

# Role

Representa un conjunto de permisos asignables a usuarios.

### Tipo

Aggregate Root

### Responsabilidades

- Agrupar permisos.
- Simplificar la administración de accesos.

---

# Permission

Representa una capacidad específica dentro del sistema.

### Tipo

Entidad de Referencia

### Responsabilidades

- Control granular de acceso.

---

# Category

Representa la clasificación administrativa y deportiva de los jugadores según su rango de edad.

### Tipo

Aggregate Root

### Responsabilidades

- Clasificar jugadores.
- Definir rangos de edad.
- Servir como referencia para equipos competitivos.
- Base para futuros procesos de formación.

### Estado

- ACTIVE
- INACTIVE

---

# Player

Representa un jugador registrado en la academia.

Es la entidad central del dominio.

### Tipo

Aggregate Root

### Responsabilidades

- Información personal y deportiva.
- Clasificación dentro de una categoría.
- Participación en equipos competitivos.
- Relación con acudientes.
- Gestión de matrículas.
- Historial administrativo y deportivo.

### Estado

- ACTIVE
- INACTIVE

---

# Team

Representa un equipo deportivo con fines competitivos.

No representa un grupo permanente de entrenamiento.

### Tipo

Aggregate Root

### Responsabilidades

- Organizar jugadores para competencias.
- Participar en torneos.
- Agrupar jugadores de una misma categoría.

### Estado

- ACTIVE
- INACTIVE

---

# LegalGuardian

Representa un acudiente o tutor legal.

### Tipo

Aggregate Root

### Responsabilidades

- Contacto administrativo.
- Responsable financiero.
- Responsable de autorizaciones.
- Contacto de emergencia.

### Estado

- ACTIVE
- INACTIVE

---

# PlayerGuardian

Representa la relación entre jugadores y acudientes.

### Tipo

Entidad Relacional

### Responsabilidades

- Gestionar la relación N:M.
- Identificar el acudiente principal.
- Definir responsabilidades administrativas.

---

# Membership

Representa la matrícula administrativa de un jugador.

### Tipo

Aggregate Root

### Responsabilidades

- Controlar la permanencia del jugador en la academia.
- Base para la gestión financiera.
- Asociar pagos.
- Mantener el historial de matrículas.

### Estado

- ACTIVE
- SUSPENDED
- WITHDRAWN
- GRADUATED

---

# TeamAssignment

Representa la participación deportiva de un jugador dentro de un equipo.

### Tipo

Entidad Relacional

### Responsabilidades

- Gestionar la relación N:M entre jugadores y equipos.
- Mantener el historial de participación deportiva.
- Permitir la participación simultánea en múltiples equipos.

---

# PaymentConcept

Representa el motivo o concepto de un pago.

### Tipo

Aggregate Root

### Responsabilidades

- Clasificar los pagos realizados por la academia.

### Estado

- ACTIVE
- INACTIVE

---

# Payment

Representa una transacción financiera asociada a una matrícula.

### Tipo

Aggregate Root

### Responsabilidades

- Registrar pagos.
- Controlar la información financiera.
- Asociar conceptos de pago.
- Mantener el historial de transacciones.

### Estado

- REGISTERED
- VOIDED

---

# PaymentEvidence

Representa una evidencia asociada a un pago.

### Tipo

Entidad

### Responsabilidades

- Almacenar soportes documentales.
- Permitir múltiples evidencias por pago.