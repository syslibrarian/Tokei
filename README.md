# Tokei (統計) – Veranstaltungs- und Statistikdatenbank

Das Projekt Tokei (japanisch für Statistik) soll der Stadtbibliothek Tempelhof-Schöneberg dabei helfen, statische Daten sauberer und besser zu erfassen. Dabei baut Tokei auf drei verschiedenen Modulen auf, die ineinandergreifen.

## Modul 1 – Veranstaltungen

Das erste Modul soll alle Veranstaltungen erfassen – dabei werden sowohl medien- und bibliothekspädagogische Angebote als auch andere Veranstaltungen. Die Kerndaten sind dabei Titel oder Gruppen, Teilnehmer, Personal, Fremdpersonal sowie Start- und Endzeitpunkt. Um die Arbeit der Kinder- und Jugendabteilung zu vereinfachen, können Kindergärten und Schulen definiert werden. Aus diesen Daten kann die Teilnehmerstatistik je nach Standort automatisch ermittelt werden.
Der aktuelle Fokus der Entwicklung liegt dabei darauf, zwei bisher verwendete Excel-Dateien im Fachbereich zu ersetzen und für die Kinder- und Jugendabteilung einen Mehrwert zu schaffen, indem auch Absagen und Nichterscheinen statistisch erfasst werden.
Die Zahlen werden nach den Grund- und Leistungsdaten (GLD) sowie der deutschen Bibliotheksstatistik (DBS) aufbereitet und ausgegeben.

## Module 2 – Monatsstatistik

Das zweite Modul umfasst für den Fachbereich die Erfassung der Standorte. Diesen können Besuche sowie Ausleihzahlen je Monat zugewiesen werden. Dazu werden weitere Daten wie Öffnungsstunden, besetzte Schichten, ehrenamtliche Mitarbeiter erfasst. Die Daten werden erneut für die GLD sowie die DBS aufbereitet und ausgegeben.

## Module 3 – Ausgabe KLR für das Bezirksamt

Das dritte Modul bereitet alle Daten – Veranstaltungen sowie aus der Monatsstatistik nach den Standorten des Fachbereiches – auf und stellt die KLR als fortlaufende, monatlich ausdruckbare Tabelle dar, die über die Browser-Funktion ausgedruckt und abgerufen werden kann.

## Export von statistischen Daten

Alle statistischen Daten können in einem CSV- sowie JSON-Format exportiert werden.

## Projektverantwortlich
* Karsten Achterratn (Projektleitung)
* Andreas Hundacker (Qualitätssicherung)

## Releases
### System-Release-Note: Tokei v0.5.0 (Meilenstein: Kernarchitektur)

#### 1. Basissystem & Berechtigungsstruktur
* **Mandantenfähige Identitäts- und Rollenverwaltung:** * Strukturierte Abbildung von Dienststellen, Hierarchien und Funktionskreisen.
* **Kontextbasierte Zugriffskontrolle (ABAC):** * Revisionssichere und unbestechliche Validierung von Zugriffsrechten direkt auf Objektebene (Sperrung von Fremdzugriffen außerhalb des zugewiesenen Zuständigkeitsbereichs).

#### 2. Stammdaten & Institutionelle Grundlagen
* **Dienststellen- und Standortregister:** * Zentrale Erfassung und Zuordnung aller physischen Bibliotheksstandorte.
* **Pädagogisches Einzugsmanagement:** * Strukturierte Verwaltung von Schulen, Kitas und externen Bildungsträgern zur rechtskonformen Abbildung kooperativer Angebote.

#### 3. Fachverfahren: Veranstaltungsmanagement
* **Mandatstrennung im Bildungsbereich:** * Systemseitig garantierte, virtuelle Trennung der Fachstatistiken nach Zielgruppen (Schulklassen, Kindertagesstätten sowie allgemeine Publikumsveranstaltungen).
* **Konformität nach DBS & GLD:** * Automatische Aggregationslogik der statistischen Kennzahlen gemäß den Vorgaben der *Deutschen Bibliotheksstatistik (DBS)* und der *Gemeinsamen Leitlinie Datenmanagement (GLD)*.

#### 4. Berichtswesen & Validierung
* **Periodische Standortberichte:** * Standardisierte, monatliche Datenerfassung zur Aggregation der lokalen Leistungsdaten direkt an den Dienststellen.

#### 5. Kosten- und Leistungsrechnung (KLR) & Audit-Trail
* **KLR-Datenableitung:** * Automatisierte Generierung finanzrelevanter Kennzahlen direkt aus den validierten Monatsberichten zur Entlastung des Controlling-Bereichs.
* **Integritätssicherung (Änderungsnachweis):** * Visuelle Kennzeichnung und systemseitige Protokollierung (Audit-Trail), falls Monatsberichte nach dem vorläufigen Abschluss nachträglich modifiziert oder korrigiert wurden.

## Projekt- und Meilensteinplan: Tokei (Weg zu v1.0.0 und Ausblick v1.5.0)

### Phase 1: Kontinuierliche Qualitätssicherung & Systemergänzung
**Zeitraum:** Juli 2026 – Januar 2027

* **Technische Dokumentation & Systemhandbuch:** * Erstellung der System- und Administrationsdokumentation zur Sicherung der langfristigen Wartbarkeit.
* **Qualitätssicherung durch Testautomatisierung:** * Implementierung von automatisierten Integrationstests (Pest) zur dauerhaften Absicherung des Rechtesystems und der Fachlogik.
* **UI/UX-Optimierung (Interface-Anpassungen):** * Kontinuierliche Verfeinerung der Benutzeroberfläche basierend auf dem Feedback der Dienststellen.
* **Visuelle Datenaufbereitung (Dashboard & Eye-Candy):** * Integration von dynamischen Diagrammen und Auswertungen via Chart.js im zentralen Dashboard für das Management-Reporting.
* **Deployment-Vorbereitung:** * Konzeption und Ausarbeitung eines standardisierten Installations-Werkzeugs.

---

### Phase 2: Evaluierung der Pilotphase & Bereitstellung der Entwicklungsumgebung
**Zeitraum:** Juli 2026 – August 2026

* **Pilotierung im Echtbetrieb (Interface-Tests):** * Durchführung strukturierter Nutzertests mit der Bezirkszentralbibliothek (BZB) und der Fahrbibliothek zur Validierung der Workflows.
* **Infrastruktur-Automatisierung:** * Erstellung von CLI-Konsolenbefehlen zur automatisierten Bereitstellung und Initialisierung standardisierter Entwicklungsumgebungen.

---

### Phase 3: Integration des Fachverfahrens & Audit-Log-Implementierung
**Zeitraum:** September 2026 – Januar 2027

* **Validierung des automatisierten Berichtswesens:** * Intensivtests des Eventsystems zur fehlerfreien, automatischen Zuweisung und Berechnung der GLD/DBS-Kennzahlen für die Monatsberichte.
* **Administrativer Abnahmetest:** * Prüfung und Freigabe der Monatsberichtserfassung der BZB und Fahrbibliothek durch die zentrale Administration.
* **Rollenbasierte Oberflächen-Anpassung:** * Optimierung des Dashboards und der Steuerungswerkzeuge speziell für die Bedürfnisse der Stellenleitung und Statistikverantwortlichen.
* **Einführung des Revisions-Journals:** * Implementierung eines lückenlosen Protokollsystems (Audit-Trail) zum rechtssicheren Nachweis nachträglicher Änderungen an Monatsberichten.

---

### Phase 4: Systemstart & Überführung in den Produktivbetrieb
**Zeitraum:** Januar 2027

* **Meilenstein v1.0.0 (General Availability):** * Offizielle Überführung des Systems in die stabile Version 1.0.0.
* **Inbetriebnahme KLR-Modul:** * Bereitstellung und Freigabe von Tokei als primäres Software-Werkzeug für die Kosten- und Leistungsrechnung.

---

### Ausblick: Evolutionärstufe zu Version 1.5.0
**Zeitraum:** Ab Februar 2027

* **Automatisiertes Meldewesen:** * Direkte Generierung und Export der offiziellen GLD- und DBS-Reports aus den aggregierten Systemdaten.
* **Historisierung & Langzeitanalyse:** * Implementierung von Datenstrukturen zur Erfassung und zum Vergleich historischer Vorjahresdaten.
* **Erweiterte Datenvisualisierung:** * Ausbau der Dashboard-Metriken für tiefergehende statistische Analysen.
* **Erweiterte Recherche-Werkzeuge:** * Bereitstellung dedizierter Abfrage-Tools zur detaillierten Event-Analyse.
* **Interoperabilität & Schnittstellen (REST-API):** * Bereitstellung einer gesicherten REST-API zur standardisierten Datenübergabe in den Formaten JSON und CSV an übergeordnete Systeme.