# Was ist Knospe?

Knospe ist ein Lehr-Boilerplate. Ein Boilerplate ist ein fertig eingerichtetes Grundgerüst, das du als Startpunkt für eigene Projekte nimmst. "Lehr-" heißt: Der eigentliche Zweck ist nicht das fertige Produkt, sondern das Verstehen. Jede Ecke ist bewusst durchschaubar gehalten und ausführlich erklärt.

## Motivation

Viele Grundgerüste funktionieren, aber niemand versteht mehr, warum. Magie passiert hinter verschlossenen Türen, und wenn etwas klemmt, steht man ratlos da. Knospe geht den umgekehrten Weg: Der Code versteckt nichts. Du kannst jede Anfrage vom Browser bis zur Datenbank mit dem Finger nachverfolgen, weil der Kern klein und lesbar ist.

## Zielgruppe

Knospe richtet sich an Einsteiger, die Webentwicklung von Grund auf begreifen wollen, und an Fortgeschrittene, die eigene Erweiterungen selbst und selbstgehostet bauen möchten. Du musst nicht schon PHP können - dafür gibt es die Grundlagen-Kapitel. Wer bereits Erfahrung hat, springt direkt zur Architektur.

## Warum kein großes Framework?

Ein umfangreiches Framework nimmt dir viel Arbeit ab, aber es versteckt auch viel. Für Lernzwecke ist das ungünstig: Man lernt das Werkzeug, nicht die Sache dahinter. Knospe baut deshalb einen eigenen kleinen Kern - Container, Router, Middleware-Pipeline - nach etablierten PSR-Ideen. So siehst du selbst, wie so ein Kern funktioniert. Sieh dir dazu `backend/src/Core/` an.

## Was ist anders?

- Alles ist lesbar und kommentiert. Im Code stehen Hinweise wie `// Lern mehr: docs/...`, die auf den passenden Leitfaden zeigen.
- Der Stack ist bewusst überschaubar: PHP 8.5 im Backend, eine Svelte-5-Oberfläche im Frontend, PostgreSQL 16 als Datenbank, Docker für den Betrieb.
- Ein Plugin-System zeigt, wie man sauber erweitert, ohne den Kern anzufassen.

## Vorkenntnisse

Hilfreich sind Grundbegriffe: eine Datei speichern, ein Terminal öffnen, im Browser eine Adresse aufrufen. Alles Weitere lernst du hier. Beginne mit [Einstieg für Anfänger](00-einstieg-fuer-anfaenger.md) oder direkt mit [Schnellstart mit Docker](02-installation-docker.md).
