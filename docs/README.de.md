<div align="center">
<img src="preview.webp">

**Eine selbst gehostete Chat-Plattform im Discord-Stil, die du wirklich selbst betreiben kannst.**

Server, Textkanäle, Direktnachrichten, Sprachräume, Anrufe, Reaktionen und Dateiaustausch — in einer vertrauten Oberfläche. Kein Build-Schritt, kein Node, kein Datenbankserver, um den man sich kümmern muss. Nur PHP und eine SQLite-Datei.

[Русский](../README.md) · [English](README.en.md) · [Deutsch](README.de.md) · [Français](README.fr.md)

</div>

---

## Warum es das gibt

Ich wollte einen Ort, an dem meine Community sich treffen kann, ohne von fremden Servern abhängig zu sein, ohne Werbung und ohne dafür einen DevOps-Abschluss zu brauchen. Die meisten selbst gehosteten Chat-Apps verlangen Docker, eine Postgres-Instanz, eine Message-Queue und einen halben Tag deines Lebens, bevor du einen Login-Bildschirm siehst. trueCORD ist das Gegenteil: Dateien auf fast jeden günstigen PHP-Hoster legen, Seite öffnen, fertig.

Es läuft als Single-Page-App auf Basis einer kleinen PHP-API. Der gesamte Zustand liegt in einer einzigen SQLite-Datei. Wenn dein Hoster einen WordPress-Blog ausliefern kann, kann er auch das hier.

## Was drin ist

- **Server und Kanäle** — organisiere Unterhaltungen so, wie du es gewohnt bist.
- **Direktnachrichten** — private Eins-zu-eins-Chats, mit Bearbeitung und Reaktionen.
- **Sprachräume und Anrufe** — Echtzeit-Kommunikation (WebRTC), mit Bildschirmfreigabe.
- **Reaktionen** — Emojis auf jede Nachricht.
- **Dateiaustausch** — Bilder, Audio, Video und Dokumente. Große Bilder werden automatisch komprimiert, und der Feed lädt leichte Vorschaubilder; die volle Größe öffnet sich per Klick.
- **Vier Sprachen ab Werk** — Englisch, Russisch, Deutsch, Französisch, live umschaltbar.
- **Themes** — mehrere eingebaute Themes, darunter ein helles und ein AMOLED-freundliches dunkles.
- **PWA** — auf Handys und Desktops installierbar.
- **Konfiguration in einer Datei** — Branding, Richtlinien und Limits liegen in `config.json`. Zum Umbenennen oder Anpassen musst du nie den Code anfassen.

## Voraussetzungen

Du brauchst nicht viel. Ein typisches Shared-Hosting-Konto reicht.

- **PHP 7.3 oder neuer** (8.1+ empfohlen — schneller, und der Code unterstützt es vollständig)
- **PDO-SQLite**-Erweiterung (fast immer standardmäßig aktiviert)
- **GD**-Erweiterung — für Bildkomprimierung und Vorschaubilder. Die App funktioniert auch ohne sie, sie verkleinert dann nur keine Bilder.
- **Ein Webserver** — Apache funktioniert dank der mitgelieferten `.htaccess` sofort. Nginx geht auch, du fügst nur ein paar Regeln von Hand hinzu (siehe unten).
- **HTTPS** — dringend empfohlen und von Browsern für Sprache/Anrufe und PWA-Installation vorausgesetzt.

Kein Node.js. Kein Composer. Keine separate Datenbank. Kein Build-Schritt.

## Installation

Kurzfassung: hochladen, Rechte setzen, im Browser öffnen.

**1. Die Dateien auf den Server bringen.**

```bash
git clone https://github.com/MrZer0/truecord.git
cd truecord
```

Oder einfach das ZIP herunterladen und den Inhalt per FTP in dein Web-Root hochladen (z. B. `public_html/`).

**2. Den uploads-Ordner beschreibbar machen.**

Hier speichert die App hochgeladene Dateien und Bild-Vorschauen.

```bash
mkdir -p uploads
chmod 755 uploads
```

Auf den meisten Shared-Hosts ist `755` in Ordnung. Falls Uploads fehlschlagen, versuche `775`. Die SQLite-Datenbankdatei wird beim ersten Start automatisch im App-Verzeichnis erstellt, daher muss auch dieser Ordner beschreibbar sein.

**3. Deine Instanz konfigurieren.**

Öffne `config.json` und setze zumindest Projektname und Beschreibung. Alles ist direkt in der Datei kommentiert. Was die meisten zuerst ändern:

- `project.name` — der Name in Titel und Seitenleiste
- `project.description` — der Text auf dem Login-Bildschirm
- `owner.super_admin_name` — der Benutzername, der zum Super-Admin wird
- `messaging.dm_policy` — wer wem schreiben darf (`shared_space`, `verified_only` oder `everyone`)
- `security.cors_enabled` — auf `false` lassen, außer du weißt, dass du es brauchst

**4. Im Browser öffnen.**

Rufe deine Domain auf. Du siehst den Login-/Registrierungsbildschirm. Registriere das Konto, dessen Name mit `owner.super_admin_name` übereinstimmt — es wird automatisch zum Administrator.

Das war's. Es gibt keinen Installations-Assistenten, weil es nichts zu installieren gibt.

### Hinweis zu Nginx

Die mitgelieferte `.htaccess` deckt Apache ab. Unter Nginx liefert die App Seite und API über denselben Einstiegspunkt aus, ausgefeiltes Routing ist also nicht nötig — aber du solltest Konfiguration und Datenbank schützen. Eine minimale Ergänzung:

```nginx
location ~* /(config\.json|.*\.db)$ { deny all; }
location ~ /\.ht { deny all; }
```

Stelle sicher, dass PHP-Dateien wie üblich von deinem PHP-FPM-Block verarbeitet werden.

## Aktualisieren

Sichere zuerst **immer** deine `config.json` und deine `.db`-Datei.

Ersetze dann die Anwendungsdateien (`index.php`, `truecord_api.php`, `api_modules/`, `i18n.js`, `config.php`, `sw.js`) durch die neuen Versionen. Konfiguration und Datenbank bleiben unangetastet. Mache nach dem Update einen harten Reload (Strg+Umschalt+R), damit der Browser das neue Frontend lädt. Das Datenbankschema migriert sich bei Bedarf beim ersten Laden selbst.

## Konfiguration auf einen Blick

Alles liegt in `config.json`. Einige der Stellschrauben:

| Einstellung | Wirkung |
|---|---|
| `project.name` / `project.description` | Branding auf Seite und Login-Bildschirm |
| `project.default_theme` | Theme, das neue Besucher zuerst sehen |
| `membership.*` | Ob neue Nutzer dem Hauptserver automatisch beitreten, Sichtbarkeitsregeln |
| `messaging.dm_policy` | DM-Rechte: `shared_space` / `verified_only` / `everyone` |
| `uploads.image_compress` | Große Bilder beim Upload automatisch verkleinern |
| `uploads.image_thumbs` | Feed-Vorschaubilder für schnelleres Laden erzeugen |
| `security.debug_mode` | Detaillierte Fehler anzeigen (in Produktion auf `false` lassen) |
| `security.cors_enabled` | Cross-Origin-API-Zugriff (standardmäßig aus) |

Zwei Instanzen können exakt denselben Code verwenden und sich nur in ihrer `config.json` unterscheiden — genau so ist das Projekt gedacht.

## Sicherheitshinweise

- Passwörter werden mit PHPs `password_hash` gehasht. Tokens nutzen `random_bytes`.
- Alle Datenbankzugriffe verwenden Prepared Statements.
- Hochgeladene Dateien werden mit `X-Content-Type-Options: nosniff` und einer Sandbox-Content-Security-Policy ausgeliefert; alles, was kein bekannt sicherer Medientyp ist, wird zum Download gezwungen statt gerendert.
- Es gibt eine einfache Ratenbegrenzung beim Login gegen Brute Force.
- Lasse `security.debug_mode` in Produktion auf `false`, damit keine Fehlerdetails nach außen dringen.
- Betreibe die App immer über HTTPS.

## Lizenz

Siehe [LICENSE](../LICENSE).

## Autor

Erstellt von **MrZer0**.

Wenn du eine Instanz betreibst, einen Fehler findest oder etwas darauf aufbaust — ich höre gern davon.
