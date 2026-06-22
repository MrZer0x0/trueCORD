<div align="center">
<img src="preview.webp">

**Une plateforme de chat auto-hébergée façon Discord, que vous pouvez vraiment faire tourner vous-même.**

Serveurs, canaux textuels, messages privés, salons vocaux, appels, réactions et partage de fichiers — dans une interface familière. Pas d'étape de build, pas de Node, pas de serveur de base de données à surveiller. Juste PHP et un fichier SQLite.

[Русский](../README.md) · [English](README.en.md) · [Deutsch](README.de.md) · [Français](README.fr.md)

</div>

---

## Pourquoi ce projet

Je voulais un endroit où ma communauté pourrait se retrouver sans dépendre des serveurs de quelqu'un d'autre, sans publicité, et sans avoir besoin d'un diplôme DevOps pour que ça tourne. La plupart des applis de chat auto-hébergées réclament Docker, une instance Postgres, une file de messages et une demi-journée de votre vie avant de voir un écran de connexion. trueCORD, c'est l'inverse : déposez les fichiers sur presque n'importe quel hébergement PHP bon marché, ouvrez la page, c'est fait.

Ça fonctionne comme une application monopage adossée à une petite API PHP. Tout l'état tient dans un seul fichier SQLite. Si votre hébergeur sait servir un blog WordPress, il sait faire tourner ceci.

## Ce qu'il y a dedans

- **Serveurs et canaux** — organisez les conversations comme vous en avez l'habitude.
- **Messages privés** — discussions privées en tête-à-tête, avec édition et réactions.
- **Salons vocaux et appels** — communication en temps réel (WebRTC), avec partage d'écran.
- **Réactions** — emoji sur n'importe quel message.
- **Partage de fichiers** — images, audio, vidéo et documents. Les grandes images sont compressées automatiquement, et le fil charge des miniatures légères ; la taille réelle s'ouvre au clic.
- **Quatre langues d'origine** — anglais, russe, allemand, français, commutables à la volée.
- **Thèmes** — plusieurs thèmes intégrés, dont un clair et un sombre adapté à l'AMOLED.
- **PWA** — installable sur mobile et ordinateur.
- **Configuration en un seul fichier** — image de marque, règles et limites vivent dans `config.json`. Pour rebrander ou ajuster, vous ne touchez jamais au code.

## Prérequis

Il ne faut pas grand-chose. Un compte d'hébergement mutualisé classique suffit.

- **PHP 7.3 ou plus récent** (8.1+ recommandé — plus rapide, et le code le prend entièrement en charge)
- Extension **PDO SQLite** (presque toujours activée par défaut)
- Extension **GD** — pour la compression d'images et les miniatures. L'appli fonctionne aussi sans, elle ne redimensionnera simplement pas les images.
- **Un serveur web** — Apache fonctionne d'emblée grâce au `.htaccess` fourni. Nginx convient aussi, vous ajouterez juste quelques règles à la main (voir plus bas).
- **HTTPS** — fortement recommandé, et exigé par les navigateurs pour le vocal/les appels et l'installation PWA.

Pas de Node.js. Pas de Composer. Pas de base de données séparée. Pas d'étape de build.

## Installation

En bref : téléverser, donner les droits, ouvrir dans un navigateur.

**1. Mettre les fichiers sur votre serveur.**

```bash
git clone https://github.com/MrZer0/truecord.git
cd truecord
```

Ou téléchargez simplement le ZIP et téléversez le contenu vers la racine de votre site (par ex. `public_html/`) en FTP.

**2. Rendre le dossier uploads accessible en écriture.**

L'appli y stocke les fichiers téléversés et les miniatures d'images.

```bash
mkdir -p uploads
chmod 755 uploads
```

Sur la plupart des hébergements mutualisés, `755` convient. Si les envois échouent, essayez `775`. Le fichier de base SQLite est créé automatiquement au premier lancement dans le dossier de l'appli, donc ce dossier doit aussi être accessible en écriture.

**3. Configurer votre instance.**

Ouvrez `config.json` et renseignez au moins le nom et la description du projet. Tout est commenté directement dans le fichier. Ce que l'on change en premier, en général :

- `project.name` — le nom affiché dans le titre et la barre latérale
- `project.description` — le texte de l'écran de connexion
- `owner.super_admin_name` — le nom d'utilisateur qui devient super-administrateur
- `messaging.dm_policy` — qui peut écrire à qui en privé (`shared_space`, `verified_only` ou `everyone`)
- `security.cors_enabled` — laissez `false` sauf si vous savez en avoir besoin

**4. Ouvrir dans le navigateur.**

Rendez-vous sur votre domaine. Vous obtenez l'écran de connexion/inscription. Inscrivez le compte dont le nom correspond à `owner.super_admin_name` — il devient automatiquement administrateur.

C'est tout. Il n'y a pas d'assistant d'installation parce qu'il n'y a rien à installer.

### À propos de Nginx

Le `.htaccess` fourni couvre Apache. Sous Nginx, l'appli sert la page et l'API depuis le même point d'entrée, pas besoin de routage compliqué — mais protégez la configuration et la base. Un ajout minimal :

```nginx
location ~* /(config\.json|.*\.db)$ { deny all; }
location ~ /\.ht { deny all; }
```

Assurez-vous que les fichiers PHP sont traités par votre bloc PHP-FPM comme d'habitude.

## Mise à jour

Sauvegardez **toujours** d'abord votre `config.json` et votre fichier `.db`.

Remplacez ensuite les fichiers de l'application (`index.php`, `truecord_api.php`, `api_modules/`, `i18n.js`, `config.php`, `sw.js`) par les nouvelles versions. Votre configuration et votre base restent intactes. Après la mise à jour, faites un rechargement forcé (Ctrl+Maj+R) pour que le navigateur prenne le nouveau front-end. Le schéma de la base se met à jour tout seul au premier chargement si nécessaire.

## La configuration en un coup d'œil

Tout vit dans `config.json`. Quelques réglages :

| Réglage | Effet |
|---|---|
| `project.name` / `project.description` | Image de marque sur la page et l'écran de connexion |
| `project.default_theme` | Thème vu en premier par les nouveaux visiteurs |
| `membership.*` | Adhésion automatique des nouveaux au serveur principal, règles de visibilité |
| `messaging.dm_policy` | Droits de MP : `shared_space` / `verified_only` / `everyone` |
| `uploads.image_compress` | Redimensionnement auto des grandes images à l'envoi |
| `uploads.image_thumbs` | Génération de miniatures pour un fil plus rapide |
| `security.debug_mode` | Afficher les erreurs détaillées (laissez `false` en production) |
| `security.cors_enabled` | Accès API cross-origin (désactivé par défaut) |

Deux instances peuvent utiliser exactement le même code et ne différer que par leur `config.json` — c'est ainsi que le projet est conçu.

## Notes de sécurité

- Les mots de passe sont hachés avec `password_hash` de PHP. Les jetons utilisent `random_bytes`.
- Tous les accès à la base passent par des requêtes préparées.
- Les fichiers téléversés sont servis avec `X-Content-Type-Options: nosniff` et une Content-Security-Policy en bac à sable ; tout ce qui n'est pas un type de média connu comme sûr est forcé au téléchargement plutôt qu'au rendu.
- Une limitation basique du débit de connexion protège contre la force brute.
- Gardez `security.debug_mode` à `false` en production pour ne pas exposer les détails d'erreur.
- Servez toujours en HTTPS.

## Licence

Voir [LICENSE](../LICENSE).

## Auteur

Réalisé par **MrZer0**.

Si vous faites tourner une instance, trouvez un bug ou construisez quelque chose par-dessus — j'aimerais beaucoup le savoir.
