# 13-user-story-rebuild-guide.md

# User Story Rebuild Guide

Este documento define cómo reconstruir historias de usuario a partir del negocio sin contaminar la base técnica.

---

# Objective

Convertir cada historia en una unidad clara de implementación que incluya:

* dominio
* actor
* intención
* regla
* contrato API
* persistencia
* pruebas

---

# Rebuild Process

## Step 1 - Identify Domain

Determinar a qué dominio pertenece la historia.

## Step 2 - Identify Actor

Definir quién ejecuta la acción.

## Step 3 - Identify Business Outcome

Establecer qué problema resuelve.

## Step 4 - Extract Invariants

Listar reglas que no pueden romperse.

## Step 5 - Define Command or Query

Clasificar la historia como operación de escritura o lectura.

## Step 6 - Define API Contract

Especificar request, response, status codes y errores.

## Step 7 - Define Persistence Impact

Identificar tablas, relaciones e índices afectados.

## Step 8 - Define Tests

Definir pruebas mínimas para validar la historia.

---

# Story Template

Cada historia reconstruida deberá dejar claro:

```text
ID
Title
Actor
Goal
Domain
Invariants
API Contract
Validation Rules
Persistence Impact
Tests
Acceptance Criteria
Dependencies
```

---

# Rebuild Rules

* Si una historia contradice la base técnica, primero se corrige la base técnica.
* Si la historia requiere una nueva entidad o relación, se documenta antes de implementar.
* Si la historia toca tenant, seguridad o auditoría, debe revisar estos documentos primero.
* Si la historia es ambigua, se detiene y se aclara antes de codificar.

