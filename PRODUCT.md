# Product: WMSU Research Ethics Oversight (REO) Portal

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

1. **Academic Researchers, Faculty, & Graduate Students:**
   - Individuals submitting health, clinical, social science, and educational research proposals requiring ethical clearance.
   - Core jobs: proposal registration, uploading multi-file compliance dossiers (protocols, consent forms, study instruments, investigator CVs), tracking ethical review milestones, responding to evaluator remarks, and downloading clearance certificates.

2. **Ethics Evaluators / Primary Reviewers:**
   - University faculty and domain experts assigned to evaluate research proposals according to National Ethical Guidelines for Health and Life Sciences Research.
   - Core jobs: examining uploaded documents through the integrated viewer, verifying protocols against ethical criteria, submitting structured review remarks, and recommending protocol classifications (Exempt, Expedited, Full Board).

3. **Institutional Review Board (IRB) / Committee Secretaries & Admins:**
   - Operational backbone coordinating the committee workflow.
   - Core jobs: intake triage of incoming submissions, tracking physical/hardcopy document receipt, conducting initial completeness triage, dispatching protocols to qualified reviewers, managing revision cycles, scheduling review meetings, and generating official clearance and endorsement documentation.

4. **University Super-Administrators:**
   - Senior oversight executives managing system integrity.
   - Core jobs: managing user privileges, managing reviewer pools, overseeing review fee structures, auditing institutional proposal analytics, and monitoring machine learning decision support services.

## Product Purpose

The WMSU REO Portal provides a unified, transparent, and auditable digital infrastructure for the Research Ethics Oversight Committee at Western Mindanao State University. It replaces fragmented paper submissions and ad-hoc email communications with a centralized workflow engine that guarantees statutory compliance with Philippine Health Research Ethics Board (PHREB) standards, reduces administrative turnaround time, and maintains an immutable institutional review ledger.

## Positioning

Unlike generic academic submission portals or off-the-shelf document management systems, the WMSU REO Portal integrates dual-evidence verification (digital document processing paired with physical hardcopy audit milestones), domain-specific Philippine ethics committee protocol classifications (Exempt, Expedited, Full Board), and AI-assisted preliminary ethical risk triage calibrated for Philippine state university research.

## Operating Context

- **Academic & Regulatory Rhythm:** Driven by university semesters, thesis deadlines, faculty grant cycles, and institutional committee meeting schedules.
- **Statutory PHREB Governance:** Proposals must strictly follow multi-stage ethics approval protocols before data collection or research execution may begin.
- **Physical-Digital Dual Workflow:** Submissions require verified physical signatures and official university receipts alongside digital PDF/Word document uploads.
- **High Cognitive Load Review Sessions:** Administrators and evaluators process dense, complex documentation across prolonged working sessions, necessitating ergonomic, scannable data layouts.

## Capabilities and Constraints

- **Multi-Role Role-Based Access Control (RBAC):** Strict separation between Researcher, Reviewer, Admin, and Super-Admin roles.
- **Intake & Triage Pipeline:** Digital intake triage, document completeness checks, physical receipt verification, and administrative routing.
- **Document Viewer & Verification Engine:** High-density two-column file viewer supporting PDF, Word, Excel, and image formats with file-format-specific visual coding.
- **Plain English Administrative Workflow:** Standardized, unambiguous workflow terminology (`Triage`, `Reviewer Assigned`, `Under Review`, `Approved`, `Action Required`, `Waiting`) designed for zero operator confusion.
- **AI Decision Support:** Preliminary ethical risk and review classification predictions powered by a scikit-learn microservice.
- **Automated Certificate Generation:** Server-side generation of official PDF recommendation letters, certificates of ethical clearance, and formal committee endorsements (TCPDF/FPDI).
- **Audit Logging & History:** Complete timestamped audit trail of all status transitions, reviewer remarks, and document revisions.

## Brand Commitments

- **Institutional Identity:** Western Mindanao State University (WMSU).
- **Core Brand Color:** WMSU Crimson (`#8B0000`) symbolizing academic authority and institutional dignity.
- **Complementary Accents:** Firebrick (`#B22222`), Slate neutrals, and an Obsidian Crimson (`#1a0505`) anchor sidebar.
- **Tone of Voice:** Authoritative, dignified, objective, and scholarly. Zero playful SaaS jargon, zero decorative fluff.

## Evidence on Hand

- `REOC_MANUAL.md`: Complete institutional ethics review committee operational guidelines.
- `PROCEDURE_FLOWCHART.pdf`: Standard operating procedure workflow from submission to ethical clearance.
- Comprehensive database migrations, Blade templates, and REST controller routes for complete administrative and reviewer operations.

## Product Principles

1. **Absolute Audit Integrity:** Every decision, evaluator annotation, revision cycle, and status transition must be chronologically logged and attributable.
2. **High-Density Operational Ergonomics:** Prioritize dense, scannable data ledgers over decorative whitespace; enable committee administrators to triage large submission volumes rapidly.
3. **Plain English Clarity:** Use clear, straightforward terminology for statuses and actions. Eliminate bureaucratic acronyms and confusing labels.
4. **High-Contrast Semantic Typography:** Communicate protocol and document statuses through explicit, uppercase bold typography rather than ambiguous colored pills or decorative dots.
5. **Dignified Scholarly Aesthetic:** Present an institutional ledger environment that conveys the seriousness and permanence of academic ethical governance.

## Accessibility & Inclusion

- Adherence to WCAG 2.1 AA standards for contrast across all text and interactive elements.
- Strict rejection of color-only status signifiers; statuses feature explicit uppercase textual labels.
- Tabular numeral alignment (`tabular-nums`) for protocol identifiers, dates, and currency values to ensure optical rhythm for low-vision and scanning users.
