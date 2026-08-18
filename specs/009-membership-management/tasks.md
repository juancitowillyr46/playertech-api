# Tasks: Membership

**Entrada**: Design documents from `./`

**Prerequisites**: plan.md (required), spec.md (required), research.md, data-model.md, contracts/

## Phase 1: Setup

- [ ] T001 Validate membership documentation against `specs/14-current-state.md`
- [ ] T002 Align membership backlog stories with the feature scope in `spec.md`
- [ ] T003 Confirm the current memberships table and migration state in the real database

## Phase 2: Foundational

- [ ] T004 Update `CreateMembershipRequest` to require `playerId`, `responsibleGuardianId`, and `categoryId`
- [ ] T005 Update `CreateMembershipCommand` and `CreateMembershipHandler` to persist the new contract
- [ ] T006 Update `Membership` entity and Doctrine XML mapping to store `responsibleGuardianId` and `categoryId`
- [ ] T007 Update `MembershipResponse` and `MembershipHistoryItemResponse` to expose the new contract fields
- [ ] T008 Add or update the migration for the `memberships` table if the schema needs to change
- [ ] T009 Map current membership endpoints to the canonical membership feature
- [ ] T010 Consolidate duplicated membership stories and references

## Phase 3: User Story 1 - Create active membership

- [ ] T011 Add unit tests for membership creation with `responsibleGuardianId` and `categoryId`
- [ ] T012 Add unit tests for duplicate active membership rejection with the new contract

## Phase 4: User Story 2 - Membership history y status transitions

- [ ] T013 Add or update unit tests for membership history responses
- [ ] T014 Add or update unit tests for suspend and withdraw flows under the new contract

## Phase 5: User Story 3 - Financial boundary and references

- [ ] T015 Document the financial boundary in spec, backlog, and contracts
- [ ] T016 Keep the payment and charge references explicit without moving them into Membership runtime

## Phase 6: User Story 4 - Membership enrollment contract update

- [ ] T017 Update the HTTP contract examples in `contracts/`
- [ ] T018 Update backlog stories to match the canonical enrollment contract
- [ ] T019 Validate the complete flow in containerized tests

## Phase 7: Homologation

- [ ] T020 Align `spec.md`, `plan.md`, `tasks.md`, `contracts/README.md`, and the backlog stories
