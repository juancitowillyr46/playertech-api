# Player Profile Evolution under SDD

## Contexto

La ficha del jugador está creciendo, pero no todo atributo pertenece al mismo nivel de dominio.

Bajo SDD, la recomendación es separar:

- lo que es identidad base del jugador
- lo que es evolución deportiva
- lo que depende de una asignación, una temporada o una compra

## Regla de decisión

Antes de agregar un campo nuevo al jugador, hay que responder:

1. ¿El dato identifica al jugador de forma estable?
2. ¿El dato cambia por equipo, temporada o campaña?
3. ¿El dato pertenece a una transacción o a una relación?
4. ¿Ya existe una historia o epic que lo describa?

Si la respuesta apunta a cambio temporal o transaccional, no debe vivir en `Player`.

## Player base

Campos que sí encajan como identidad base:

- Tipo de documento
- Número de documento
- Nombres
- Apellidos
- Fecha de nacimiento

Campos que pueden entrar pronto, pero deben tratarse como evolución documentada:

- Nacionalidad
- Género
- Identificador de federación
- Pie dominante
- Posición preferida

Campos de contacto opcional que pueden existir sin ser obligatorios:

- Correo electrónico
- Teléfono celular

## Campos fuera de `Player`

Estos no describen al jugador como identidad estable:

- Número de camiseta
- Nombre impreso en camiseta

Se recomienda modelarlos en una entidad o relación aparte, porque dependen de:

- equipo
- temporada
- disponibilidad de número
- compra de uniforme
- concepto facturado

## Impacto en SSD

### Ventajas

- Evita mezclar identidad con operación.
- Facilita documentar una feature antes de codificarla.
- Hace más claro qué debe ir a `specs/` y qué debe ir a `docs/architecture/`.
- Reduce cambios innecesarios en `Player` cuando el crecimiento real está en otra capa.

### Riesgos

- Si se abren demasiados campos opcionales, la ficha se vuelve difusa.
- Si no se define una historia por cada atributo, la documentación pierde valor.
- Si no se separa evolución deportiva de identidad, el modelo se sobrecarga.

## Criterio recomendado

Para el proyecto actual:

- Mantener `Player` como identidad base.
- Documentar cualquier atributo nuevo antes de implementarlo.
- Llevar número y nombre de camiseta fuera de `Player`.
- Tratar posición como dato deportivo evolutivo, no como dato administrativo.
- Mantener `email` y `phone` como datos de contacto opcionales, no como identidad base.

## Estado

Documento de referencia para la siguiente evolución del dominio de jugadores.
