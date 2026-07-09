# HU-009 Consultar usuarios administrativos

## Epic
EP-003 Gestión de Usuarios y Accesos

## Prioridad
Media

## MVP
Sí

## Estado
Draft

## Actor Principal
Academy Admin

## Objetivo
Listar los usuarios administrativos de la academia actual.

## Reglas de Negocio
* La consulta debe respetar el contexto `academy_id`.
* Solo deben mostrarse usuarios del tenant actual.

## Criterios de Aceptación
* Dado un tenant con usuarios administrativos, cuando se consulta la lista, entonces se muestran solo sus usuarios.

