<?php
require_once __DIR__ . '/config.php';

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        if ($needle === '') return true;
        $len = strlen($needle);
        return substr($haystack, -$len) === $needle;
    }
}

// ── Оценка: хватит ли памяти PHP, чтобы загрузить картинку W×H в GD ──────────
// imagecreatefromstring/imagecreatetruecolor держат изображение как W*H*4 байта
// в памяти (плюс служебка). На больших фото это легко превышает memory_limit и
// даёт фатальную ошибку (которую не поймать try/catch) → загрузка падает с
// «Внутренней ошибкой». Здесь заранее прикидываем и, если не влезает, пропускаем
// GD-обработку (сохраняем оригинал) вместо краха.
function tc_gd_mem_ok(int $w, int $h): bool {
    if ($w <= 0 || $h <= 0) return false;
    $limitStr = @ini_get('memory_limit');
    if ($limitStr === false || $limitStr === '' || (int)$limitStr === -1) return true; // без лимита
    $limit = (int)$limitStr;
    $unit  = strtolower(substr(trim($limitStr), -1));
    if ($unit === 'g') $limit *= 1024 * 1024 * 1024;
    elseif ($unit === 'm') $limit *= 1024 * 1024;
    elseif ($unit === 'k') $limit *= 1024;
    if ($limit <= 0) return true;
    // Нужно место под исходник и под результат ресэмпла: с запасом ~6 байт на пиксель.
    $need = ($w * $h * 6) + (int)@memory_get_usage(true) + (8 * 1024 * 1024);
    return $need < $limit;
}

// ── Определение реального MIME по содержимому файла ──────────────────────────
// Возвращает определённый по байтам MIME-тип или null, если определить не удалось.
// Для изображений getimagesizefromstring надёжнее finfo; для остального — finfo_buffer.
function tc_sniff_mime(string $bin): ?string {
    if ($bin === '') return null;
    // Картинки: точный подтип из сигнатуры (jpeg/png/gif/webp/bmp/...).
    if (function_exists('getimagesizefromstring')) {
        $info = @getimagesizefromstring($bin);
        if ($info && !empty($info['mime'])) return (string)$info['mime'];
    }
    // Остальные типы: finfo по содержимому.
    if (function_exists('finfo_buffer')) {
        $f = @finfo_open(FILEINFO_MIME_TYPE);
        if ($f) {
            $m = @finfo_buffer($f, $bin);
            @finfo_close($f);
            if (is_string($m) && $m !== '' && $m !== 'application/octet-stream') return $m;
        }
    }
    return null; // не смогли определить — не блокируем (доверяем заявленному + карте разрешённых типов)
}

// ── Генератор миниатюр (верхний уровень: доступен и в раннем GET-блоке, и в doUpload) ──
// Делает уменьшенную JPEG-версию из бинарных данных изображения. Возвращает строку JPEG или null
// (GIF / прозрачный PNG / мелкие / ошибки / нет GD — миниатюра не делается, нужно отдавать оригинал).
function tc_make_thumb_bin(string $bin, string $mime): ?string {
    if (defined('IMAGE_THUMBS') && !IMAGE_THUMBS) return null;
    if (!in_array($mime, ['image/jpeg','image/png','image/webp'], true)) return null;
    if (!function_exists('imagecreatefromstring')) return null;
    $maxDim = defined('IMAGE_THUMB_DIM') ? max(120, (int)IMAGE_THUMB_DIM) : 480;
    $q      = defined('IMAGE_THUMB_QUALITY') ? min(100, max(1, (int)IMAGE_THUMB_QUALITY)) : 70;
    $info = @getimagesizefromstring($bin);
    if (!$info) return null;
    [$w, $h] = $info;
    if ($w <= 0 || $h <= 0) return null;
    if (!tc_gd_mem_ok((int)$w, (int)$h)) return null; // не влезет в память — без миниатюры
    if ($mime === 'image/png') {
        $t = @imagecreatefromstring($bin);
        if ($t === false) return null;
        $alpha = (function_exists('imagecolortransparent') && imagecolortransparent($t) >= 0);
        if (!$alpha) {
            $tw = imagesx($t); $th = imagesy($t);
            for ($i = 0; $i < 16 && !$alpha; $i++) {
                $px = min((int)($tw*$i/16), $tw-1); $py = min((int)($th*$i/16), $th-1);
                if (((imagecolorat($t, $px, $py) >> 24) & 0x7F) > 0) $alpha = true;
            }
        }
        imagedestroy($t);
        if ($alpha) return null;
    }
    if (max($w, $h) <= $maxDim) return null; // уже мелкая — миниатюра не нужна
    $src = @imagecreatefromstring($bin);
    if ($src === false) return null;
    $scale = $maxDim / max($w, $h);
    $nw = max(1, (int)round($w * $scale));
    $nh = max(1, (int)round($h * $scale));
    $dst = imagecreatetruecolor($nw, $nh);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefilledrectangle($dst, 0, 0, $nw, $nh, $white);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($src);
    ob_start();
    $ok = imagejpeg($dst, null, $q);
    $out = ob_get_clean();
    imagedestroy($dst);
    if (!$ok || $out === false || strlen($out) === 0) return null;
    return $out;
}
// ══════════════════════════════════════════════════════════════════
//  trueCORD API  —  Optimised Edition + Mobile Audio Fix
//  • Схема инициализируется один раз (PRAGMA user_version)
//  • last_seen обновляется не чаще 1 раза в 30 сек → нет лишних блокировок
//  • N+1 запросы заменены батчевыми (messages, get_servers, get_server_members, dm_conversations)
//  • Транзакции BEGIN IMMEDIATE → BEGIN (DEFERRED) где write не нужен сразу
//  • Cleanup мусора случайно 1/20 запросов вместо каждого heartbeat
//  • Обратная совместимость со всеми существующими таблицами и индексами
//  ★ АУДИО: таймаут участника 15→30 сек, сигналы 60→180 сек, ICE-restart,
//  ★ АУДИО: voice_heartbeat (ping+poll в 1 запрос), get_ice_servers,
//  ★ АУДИО: voice_reconnect, new-peer уведомления, dm_call 300→600 сек
//  ★ FIX v16: членство теперь добровольное — только owner/admin/mod автоматически участники
// ══════════════════════════════════════════════════════════════════

// ── STATIC FILE SERVING ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $uri = strtolower(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '');
    if (str_ends_with($uri, 'icon_tC_main.png') || str_ends_with($uri, 'favicon.ico')) {
        $p = __DIR__ . '/icon_tC_main.png';
        if (file_exists($p)) {
            header('Content-Type: image/png');
            header('Cache-Control: public, max-age=86400');
            readfile($p); exit;
        }
        http_response_code(404); exit;
    }
    if (str_contains($uri, '/uploads/')) {
        $uploadsDir = realpath(__DIR__ . '/uploads');
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '';
        $requested = realpath(__DIR__ . $requestPath);
        if (!$uploadsDir || !$requested || !str_starts_with($requested, $uploadsDir . DIRECTORY_SEPARATOR) || !is_file($requested)) {
            http_response_code(404); exit;
        }
        $mime = mime_content_type($requested) ?: 'application/octet-stream';
        // ── Миниатюра для ленты: ?thumb ──
        // Если запрошена миниатюра и это превьюшимая картинка — отдаём кэш <file>.thumb.jpg,
        // а при его отсутствии генерируем один раз на лету, сохраняем и отдаём. Иначе — оригинал.
        if (isset($_GET['thumb']) && in_array($mime, ['image/jpeg','image/png','image/webp'], true)) {
            $thumbPath = preg_replace('/\.[^.\/]+$/', '', $requested) . '.thumb.jpg';
            if (is_file($thumbPath)) {
                header('X-Content-Type-Options: nosniff');
                header("Content-Security-Policy: default-src 'none'; sandbox; img-src 'self'");
                header('Content-Type: image/jpeg');
                header('Content-Disposition: inline');
                header('Cache-Control: public, max-age=604800');
                readfile($thumbPath); exit;
            }
            $orig = @file_get_contents($requested);
            if ($orig !== false) {
                $thumb = tc_make_thumb_bin($orig, $mime);
                if ($thumb !== null) {
                    @file_put_contents($thumbPath, $thumb); // кэшируем (молча, даже если запись не удалась)
                    header('X-Content-Type-Options: nosniff');
                    header("Content-Security-Policy: default-src 'none'; sandbox; img-src 'self'");
                    header('Content-Type: image/jpeg');
                    header('Content-Disposition: inline');
                    header('Cache-Control: public, max-age=604800');
                    echo $thumb; exit;
                }
            }
            // Миниатюра невозможна (GIF/прозрачный/мелкая/ошибка) — продолжаем отдавать оригинал ниже.
        }
        // Защита от stored XSS через загруженные файлы:
        // браузер не должен MIME-sniff'ить и не должен рендерить файл как HTML на нашем origin.
        $safeInline = ['image/jpeg','image/png','image/gif','image/webp',
                       'video/mp4','video/webm',
                       'audio/mpeg','audio/ogg','audio/wav','audio/flac','audio/aac','audio/mp4','audio/webm'];
        // Любой тип, который браузер может счесть за HTML/скрипт, отдаём как вложение.
        $forceDownload = !in_array($mime, $safeInline, true)
            || stripos($mime, 'html') !== false
            || stripos($mime, 'xml')  !== false
            || stripos($mime, 'svg')  !== false;
        header('X-Content-Type-Options: nosniff');
        header("Content-Security-Policy: default-src 'none'; sandbox; img-src 'self'; media-src 'self'");
        if ($forceDownload) {
            // Перебиваем тип на нейтральный и форсируем скачивание — файл не выполнится в браузере.
            $mime = 'application/octet-stream';
            $dlName = basename($requested);
            header('Content-Disposition: attachment; filename="' . preg_replace('/[^\w.\-]/', '_', $dlName) . '"');
        } else {
            header('Content-Disposition: inline');
        }
        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=86400');
        readfile($requested); exit;
    }
    http_response_code(405); exit;
}

ob_start();
set_error_handler(function ($no, $str, $file, $line) {
    throw new ErrorException($str, 0, $no, $file, $line);
});

function sendError(string $m): void {
    while (ob_get_level() > 0) ob_end_clean();
    if (!headers_sent()) header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
    exit;
}
set_exception_handler(function (Throwable $e) {
    $detail = get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine();
    error_log('[trueCORD] ' . $detail);
    sendError((defined('DEBUG_MODE') && DEBUG_MODE) ? $detail : 'Внутренняя ошибка сервера');
});

try {
    error_reporting(0);
    ini_set('display_errors', '0');
    header('Content-Type: application/json; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    if (defined('CORS_ENABLED') && CORS_ENABLED && defined('CORS_ORIGIN') && CORS_ORIGIN !== '') {
        header('Access-Control-Allow-Origin: ' . CORS_ORIGIN);
        header('Vary: Origin');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { ob_end_clean(); exit(0); }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('POST only');

    $body = file_get_contents('php://input');
    // Если тело пустое, но клиент заявил Content-Length и при этом $_POST/$_FILES
    // тоже пусты — почти наверняка сработал лимит post_max_size PHP (частая причина
    // «загрузка дошла до 100%, потом ошибка» при отправке крупного фото).
    if (($body === '' || $body === false) && empty($_POST) && empty($_FILES)) {
        $cl = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
        if ($cl > 0) {
            sendError('Файл слишком большой для сервера (превышен post_max_size в настройках PHP). Уменьшите файл или увеличьте post_max_size/upload_max_filesize.');
        }
    }
    $d    = json_decode($body, true);
    if (!$d || !isset($d['action'])) sendError('Bad request');

    if (!extension_loaded('pdo'))                              sendError('PDO not loaded');
    if (!in_array('sqlite', PDO::getAvailableDrivers(), true)) sendError('SQLite driver unavailable');

    $DB_PATH = defined('DB_PATH') ? DB_PATH : (__DIR__ . '/truecord.db');
    if (!is_writable(__DIR__)) sendError('Dir not writable');

    // ── DATABASE CONNECTION + PRAGMA ─────────────────────────────
    $db = new PDO('sqlite:' . $DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $db->exec('PRAGMA busy_timeout    = ' . (defined('DB_BUSY_TIMEOUT') ? DB_BUSY_TIMEOUT : 15000));
    $db->exec('PRAGMA journal_mode    = WAL');
    $db->exec('PRAGMA synchronous     = NORMAL');
    $db->exec('PRAGMA foreign_keys    = ON');
    $db->exec('PRAGMA cache_size      = ' . (defined('DB_CACHE_SIZE') ? DB_CACHE_SIZE : -16000));
    $db->exec('PRAGMA temp_store      = MEMORY');
    $db->exec('PRAGMA mmap_size       = ' . (defined('DB_MMAP_SIZE') ? DB_MMAP_SIZE : 134217728));
    $db->exec('PRAGMA wal_autocheckpoint = 2000');
    $db->exec('PRAGMA page_size       = 4096');
    $db->exec('PRAGMA read_uncommitted = 0');

    // ── SCHEMA VERSIONING ────────────────────────────────────────
    // ★ FIX v16: поднята версия схемы
    if (!defined('SCHEMA_VERSION')) define('SCHEMA_VERSION', 22);
    if (!defined('OWNER_NAME'))     define('OWNER_NAME', 'Admin');

    $dbVer = (int)$db->query('PRAGMA user_version')->fetchColumn();
    if ($dbVer < SCHEMA_VERSION) {
        _initSchema($db);

        // Discord-like режим: обычные серверы не выдаются всем автоматически.
        // Пользователь видит только серверы, куда он уже вступил; вступление — по приглашению.
        // tes3chat (AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE=false): НЕ трогаем членство — is_member читается из базы как есть.
        if ((!defined('AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE') || AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE)
            && $dbVer > 0 && $dbVer < 22 && defined('SERVER_DIRECTORY_MODE') && SERVER_DIRECTORY_MODE === 'invite_only') {
            try {
                $db->exec('BEGIN');
                $db->exec("UPDATE server_members SET is_member=0 WHERE role='member'");
                $db->exec("UPDATE server_members SET is_member=1 WHERE role IN('owner','admin','moderator')");
                $db->exec("
                    UPDATE server_members SET is_member=1
                    WHERE user_id IN (SELECT owner_id FROM servers)
                      AND server_id IN (SELECT id FROM servers sm2 WHERE sm2.owner_id = server_members.user_id)
                ");
                $db->exec('COMMIT');
            } catch (Exception $e) {
                try { $db->exec('ROLLBACK'); } catch (Exception $e2) {}
            }
        }

        $db->exec('PRAGMA user_version = ' . SCHEMA_VERSION);
    }

    // ── HOT MIGRATIONS / SAFETY NET ──────────────────────────────
    // На некоторых старых установках PRAGMA user_version уже мог быть обновлён,
    // но таблица приватности ЛС ещё не была создана. Поэтому держим лёгкую
    // защитную миграцию вне основного блока версии схемы.
    $db->exec("CREATE TABLE IF NOT EXISTS dm_privacy (
        user_id INTEGER NOT NULL,
        other_user_id INTEGER NOT NULL DEFAULT 0,
        allow_dm INTEGER DEFAULT NULL,
        allow_audio INTEGER DEFAULT NULL,
        allow_video INTEGER DEFAULT NULL,
        updated_at INTEGER DEFAULT 0,
        PRIMARY KEY(user_id, other_user_id)
    )");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_privacy_user ON dm_privacy(user_id, other_user_id)");

    // Safety net для новых таблиц: на существующих установках user_version уже мог быть
    // равен SCHEMA_VERSION, поэтому блок _initSchema не выполнится. Создаём здесь идемпотентно.
    $db->exec("CREATE TABLE IF NOT EXISTS dm_reactions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        dm_message_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        emoji TEXT NOT NULL,
        created_at INTEGER DEFAULT 0,
        UNIQUE(dm_message_id, user_id, emoji)
    )");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_reactions_msg ON dm_reactions(dm_message_id)");
    $db->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        ip TEXT NOT NULL,
        fails INTEGER DEFAULT 0,
        first_at INTEGER DEFAULT 0,
        locked_until INTEGER DEFAULT 0,
        PRIMARY KEY(ip)
    )");

    // Универсальный счётчик частоты действий (отправка сообщений, создание серверов и т.п.).
    // Ключ — "<bucket>:<userId>" (например "send:42"). Скользящее окно.
    $db->exec("CREATE TABLE IF NOT EXISTS rate_limits (
        rkey TEXT NOT NULL,
        count INTEGER DEFAULT 0,
        window_start INTEGER DEFAULT 0,
        PRIMARY KEY(rkey)
    )");

    // Аудит-лог действий модерации: кто, кого, что, когда, причина.
    $db->exec("CREATE TABLE IF NOT EXISTS moderation_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        actor_id INTEGER NOT NULL,
        actor_name TEXT DEFAULT '',
        action TEXT NOT NULL,
        target_id INTEGER DEFAULT 0,
        target_name TEXT DEFAULT '',
        server_id INTEGER DEFAULT 0,
        reason TEXT DEFAULT '',
        created_at INTEGER DEFAULT 0
    )");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_modlog_created ON moderation_log(created_at)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_modlog_target ON moderation_log(target_id)");

    // ── HELPERS ──────────────────────────────────────────────────
    function nowSec(): int { return time(); }
    function nowMs():  int { return (int)round(microtime(true) * 1000); }

    function apiOk(array $data = []): void {
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array_merge(['ok' => true], $data),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    function apiFail(string $m, int $httpCode = 400): void {
        while (ob_get_level() > 0) ob_end_clean();
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
        exit;
    }

    function linkify(string $t): string {
        $s = htmlspecialchars($t, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return preg_replace(
            '/(https?:\/\/[^\s<>"\']+)/u',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $s
        );
    }

    function lpFirstUrl(string $text): string {
        if (preg_match('/https?:\/\/[^\s<>"\']+/iu', $text, $m)) {
            return rtrim((string)$m[0], "\"'.,!?)];}");
        }
        return '';
    }

    function lpIsSafePublicUrl(string $url): bool {
        $p = parse_url($url);
        if (!$p || empty($p['scheme']) || empty($p['host'])) return false;
        $scheme = strtolower((string)$p['scheme']);
        if (!in_array($scheme, ['http','https'], true)) return false;
        $host = strtolower((string)$p['host']);
        if ($host === 'localhost' || str_ends_with($host, '.localhost')) return false;
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ? false : true;
        }
        $ips = @gethostbynamel($host);
        if ($ips) {
            foreach ($ips as $ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return false;
            }
        }
        return true;
    }

    function lpAbsUrl(string $base, string $maybe): string {
        $maybe = trim(html_entity_decode($maybe, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        if ($maybe === '') return '';
        if (preg_match('/^https?:\/\//i', $maybe)) return $maybe;
        if (str_starts_with($maybe, '//')) {
            $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
            return $scheme . ':' . $maybe;
        }
        $bp = parse_url($base);
        if (!$bp || empty($bp['scheme']) || empty($bp['host'])) return '';
        $root = $bp['scheme'] . '://' . $bp['host'] . (isset($bp['port']) ? ':' . $bp['port'] : '');
        if (str_starts_with($maybe, '/')) return $root . $maybe;
        $path = isset($bp['path']) ? preg_replace('~/[^/]*$~', '/', $bp['path']) : '/';
        return $root . $path . $maybe;
    }

    function lpFetchHtml(string $url): string {
        if (!lpIsSafePublicUrl($url)) return '';
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 3,
                CURLOPT_CONNECTTIMEOUT => 3,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_USERAGENT      => 'trueCORD Link Preview/1.0',
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_RANGE          => '0-1048576',
                CURLOPT_HTTPHEADER     => ['Accept: text/html,application/xhtml+xml;q=0.9,*/*;q=0.4'],
            ]);
            $html = curl_exec($ch);
            $ct   = (string)curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);
            if (!is_string($html) || $html === '') return '';
            if ($ct && stripos($ct, 'text/html') === false && stripos($ct, 'application/xhtml') === false) return '';
            return substr($html, 0, 1048576);
        }
        $ctx = stream_context_create(['http'=>[
            'method'=>'GET','timeout'=>5,
            'header'=>"User-Agent: trueCORD Link Preview/1.0\r\nAccept: text/html,application/xhtml+xml;q=0.9,*/*;q=0.4\r\n"
        ]]);
        $html = @file_get_contents($url, false, $ctx, 0, 1048576);
        return is_string($html) ? $html : '';
    }

    function lpMeta(string $html, string $key): string {
        $k = preg_quote($key, '/');
        $patterns = [
            '/<meta\s+[^>]*(?:property|name)=["\']'.$k.'["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/isu',
            '/<meta\s+[^>]*content=["\']([^"\']*)["\'][^>]*(?:property|name)=["\']'.$k.'["\'][^>]*>/isu',
        ];
        foreach ($patterns as $p) if (preg_match($p, $html, $m)) return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        return '';
    }

    // ── YouTube search helpers ──
    // GET для доверенных доменов google/youtube (JSON или HTML, до 3 МБ).
    function tc_httpGet(string $url): string {
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $ok = false;
        foreach (['googleapis.com','youtube.com','www.youtube.com','i.ytimg.com'] as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed) || $host === $allowed) { $ok = true; break; }
        }
        // допускаем googleapis.com и youtube.com c www
        if (!preg_match('/(^|\.)(googleapis\.com|youtube\.com)$/', $host)) $ok = false; else $ok = true;
        if (!$ok) return '';
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 3,
                CURLOPT_CONNECTTIMEOUT => 4,
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; trueCORD/1.0)',
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER     => ['Accept-Language: ru,en;q=0.8'],
            ]);
            $out = curl_exec($ch);
            curl_close($ch);
            return is_string($out) ? substr($out, 0, 3145728) : '';
        }
        $ctx = stream_context_create(['http'=>[
            'method'=>'GET','timeout'=>8,
            'header'=>"User-Agent: Mozilla/5.0 (compatible; trueCORD/1.0)\r\nAccept-Language: ru,en;q=0.8\r\n"
        ]]);
        $out = @file_get_contents($url, false, $ctx, 0, 3145728);
        return is_string($out) ? $out : '';
    }

    // Декод JSON-escape последовательностей (\uXXXX, \n и т.п.) в строке.
    function tc_jsonUnescape(string $s): string {
        $d = json_decode('"' . str_replace('"', '\\"', $s) . '"');
        return is_string($d) ? $d : $s;
    }

    // Рекурсивно достаёт videoRenderer-карточки из ytInitialData.
    function tc_ytExtract(array $data): array {
        $out = [];
        $walk = function ($node) use (&$walk, &$out) {
            if (!is_array($node)) return;
            if (isset($node['videoRenderer']) && is_array($node['videoRenderer'])) {
                $vr  = $node['videoRenderer'];
                $vid = (string)($vr['videoId'] ?? '');
                if ($vid !== '' && count($out) < 24) {
                    $title  = '';
                    if (isset($vr['title']['runs'][0]['text'])) $title = (string)$vr['title']['runs'][0]['text'];
                    elseif (isset($vr['title']['simpleText'])) $title = (string)$vr['title']['simpleText'];
                    $author = '';
                    if (isset($vr['ownerText']['runs'][0]['text'])) $author = (string)$vr['ownerText']['runs'][0]['text'];
                    elseif (isset($vr['longBylineText']['runs'][0]['text'])) $author = (string)$vr['longBylineText']['runs'][0]['text'];
                    $dur = '';
                    if (isset($vr['lengthText']['simpleText'])) $dur = (string)$vr['lengthText']['simpleText'];
                    $out[$vid] = [
                        'id'        => $vid,
                        'title'     => $title,
                        'author'    => $author,
                        'duration'  => $dur,
                        'thumbnail' => "https://i.ytimg.com/vi/{$vid}/mqdefault.jpg",
                    ];
                }
            }
            foreach ($node as $v) if (is_array($v)) $walk($v);
        };
        $walk($data);
        return array_values($out);
    }

    function lpTitle(string $html): string {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/isu', $html, $m)) {
            return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        }
        return '';
    }

    function getLinkPreview(PDO $db, string $url): array {
        $url = trim($url);
        if ($url === '' || !lpIsSafePublicUrl($url)) return ['ok'=>false, 'error'=>'URL недоступен'];
        $url = mb_substr($url, 0, 1200);
        $now = nowSec();
        try {
            $s = $db->prepare("SELECT title,description,image,site_name,final_url,fetched_at,fail FROM link_previews WHERE url=? LIMIT 1");
            $s->execute([$url]);
            $row = $s->fetch();
            if ($row && ($now - (int)$row['fetched_at']) < 86400) {
                if ((int)($row['fail'] ?? 0)) return ['ok'=>false, 'error'=>'Нет предпросмотра'];
                return ['ok'=>true,'preview'=>[
                    'url'=>$url,'finalUrl'=>(string)($row['final_url'] ?: $url),'title'=>(string)$row['title'],
                    'description'=>(string)$row['description'],'image'=>(string)$row['image'],'siteName'=>(string)$row['site_name']
                ]];
            }
        } catch (Exception $e) {}

        $html = lpFetchHtml($url);
        if ($html === '') {
            try { $db->prepare("INSERT OR REPLACE INTO link_previews(url,title,description,image,site_name,final_url,fetched_at,fail) VALUES(?,?,?,?,?,?,?,1)")->execute([$url,'','','','',$url,$now]); } catch (Exception $e) {}
            return ['ok'=>false,'error'=>'Нет предпросмотра'];
        }
        $title = lpMeta($html, 'og:title') ?: lpMeta($html, 'twitter:title') ?: lpTitle($html);
        $desc  = lpMeta($html, 'og:description') ?: lpMeta($html, 'twitter:description') ?: lpMeta($html, 'description');
        $img   = lpMeta($html, 'og:image') ?: lpMeta($html, 'twitter:image');
        $site  = lpMeta($html, 'og:site_name');
        $final = lpMeta($html, 'og:url') ?: $url;
        $img   = lpAbsUrl($url, $img);
        $final = lpAbsUrl($url, $final) ?: $url;
        $title = trim(preg_replace('/\s+/u', ' ', strip_tags($title)) ?? '');
        $desc  = trim(preg_replace('/\s+/u', ' ', strip_tags($desc)) ?? '');
        $site  = trim(preg_replace('/\s+/u', ' ', strip_tags($site)) ?? '');
        $title = mb_substr($title, 0, 180); $desc = mb_substr($desc, 0, 280); $site = mb_substr($site, 0, 80);
        if ($title === '' && $desc === '' && $img === '') {
            try { $db->prepare("INSERT OR REPLACE INTO link_previews(url,title,description,image,site_name,final_url,fetched_at,fail) VALUES(?,?,?,?,?,?,?,1)")->execute([$url,'','','','',$url,$now]); } catch (Exception $e) {}
            return ['ok'=>false,'error'=>'Нет предпросмотра'];
        }
        try { $db->prepare("INSERT OR REPLACE INTO link_previews(url,title,description,image,site_name,final_url,fetched_at,fail) VALUES(?,?,?,?,?,?,?,0)")->execute([$url,$title,$desc,$img,$site,$final,$now]); } catch (Exception $e) {}
        return ['ok'=>true,'preview'=>['url'=>$url,'finalUrl'=>$final,'title'=>$title,'description'=>$desc,'image'=>$img,'siteName'=>$site]];
    }


    function getClientIP(): string {
        foreach (['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_X_REAL_IP','REMOTE_ADDR'] as $k) {
            if (!empty($_SERVER[$k])) {
                $ip = trim(explode(',', $_SERVER[$k])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
            }
        }
        return '';
    }

    // ── Защита логина от брутфорса (по IP) ───────────────────────
    // Окно 15 минут, до 10 неудачных попыток, затем блок на 15 минут.
    // Возвращает 0 если можно пробовать, иначе — сколько секунд осталось до разблокировки.
    function loginLockRemaining(PDO $db, string $ip): int {
        if ($ip === '') return 0;
        try {
            $s = $db->prepare("SELECT fails,first_at,locked_until FROM login_attempts WHERE ip=? LIMIT 1");
            $s->execute([$ip]); $row = $s->fetch();
        } catch (Exception $e) { return 0; }
        if (!$row) return 0;
        $now = nowSec();
        $lockedUntil = (int)($row['locked_until'] ?? 0);
        if ($lockedUntil > $now) return $lockedUntil - $now;
        return 0;
    }

    function loginRegisterFail(PDO $db, string $ip): void {
        if ($ip === '') return;
        $now = nowSec();
        $window = 900;   // 15 минут
        $maxFails = 10;
        $lockSecs = 900; // блок на 15 минут
        try {
            $s = $db->prepare("SELECT fails,first_at FROM login_attempts WHERE ip=? LIMIT 1");
            $s->execute([$ip]); $row = $s->fetch();
            if (!$row) {
                $db->prepare("INSERT OR REPLACE INTO login_attempts(ip,fails,first_at,locked_until) VALUES(?,?,?,0)")
                   ->execute([$ip, 1, $now]);
                return;
            }
            $firstAt = (int)$row['first_at'];
            $fails = (int)$row['fails'];
            // Окно истекло — начинаем заново.
            if (($now - $firstAt) > $window) {
                $db->prepare("UPDATE login_attempts SET fails=1,first_at=?,locked_until=0 WHERE ip=?")
                   ->execute([$now, $ip]);
                return;
            }
            $fails++;
            $lockedUntil = $fails >= $maxFails ? $now + $lockSecs : 0;
            $db->prepare("UPDATE login_attempts SET fails=?,locked_until=? WHERE ip=?")
               ->execute([$fails, $lockedUntil, $ip]);
        } catch (Exception $e) {}
    }

    function loginResetFails(PDO $db, string $ip): void {
        if ($ip === '') return;
        try { $db->prepare("DELETE FROM login_attempts WHERE ip=?")->execute([$ip]); } catch (Exception $e) {}
    }

    // ── Универсальный rate-limit для действий записи ──────────────────────────
    // $bucket — имя действия ('send', 'create_server'...), $id — обычно userId.
    // Разрешено $limit действий за $windowSec секунд (скользящее окно).
    // При превышении вызывает apiFail и прекращает обработку. Любая ошибка БД → пропускаем
    // (rate-limit не должен ронять основной функционал на проблемном хостинге).
    function tc_rateLimit(PDO $db, string $bucket, int $id, int $limit, int $windowSec): void {
        if ($id <= 0 || $limit <= 0) return;
        $key = $bucket . ':' . $id;
        $now = nowSec();
        try {
            $s = $db->prepare("SELECT count, window_start FROM rate_limits WHERE rkey=? LIMIT 1");
            $s->execute([$key]); $row = $s->fetch();
            if (!$row || ($now - (int)$row['window_start']) >= $windowSec) {
                $db->prepare("INSERT OR REPLACE INTO rate_limits(rkey,count,window_start) VALUES(?,1,?)")
                   ->execute([$key, $now]);
                return;
            }
            $count = (int)$row['count'] + 1;
            if ($count > $limit) {
                $retry = $windowSec - ($now - (int)$row['window_start']);
                apiFail('Слишком часто. Попробуйте через ' . max(1, $retry) . ' с', 429);
            }
            $db->prepare("UPDATE rate_limits SET count=? WHERE rkey=?")->execute([$count, $key]);
        } catch (Exception $e) { /* не блокируем при сбое БД */ }
    }

    // ── Запись действия модерации в аудит-лог ─────────────────────────────────
    function tc_logModeration(PDO $db, array $actor, string $action, int $targetId = 0,
                              string $targetName = '', int $serverId = 0, string $reason = ''): void {
        try {
            $db->prepare("INSERT INTO moderation_log
                (actor_id,actor_name,action,target_id,target_name,server_id,reason,created_at)
                VALUES(?,?,?,?,?,?,?,?)")
               ->execute([
                   (int)($actor['id'] ?? 0), (string)($actor['name'] ?? ''),
                   $action, $targetId, $targetName, $serverId,
                   mb_substr($reason, 0, 500), nowSec(),
               ]);
        } catch (Exception $e) { /* лог не должен ломать само действие */ }
    }

    // ── authUser ─────────────────────────────────────────────────
    function authUser(PDO $db, array $d): ?array {
        $uid = (int)($d['userId'] ?? 0);
        $tok = (string)($d['token'] ?? '');
        if ($uid <= 0 || $tok === '') return null;

        $s = $db->prepare("
            SELECT u.id, u.name, u.avatar, u.status, u.global_role,
                   u.validated, u.terms_accepted, u.last_seen
            FROM users u
            JOIN user_sessions us ON us.user_id = u.id
            WHERE us.user_id = ? AND us.token = ?
            LIMIT 1
        ");
        $s->execute([$uid, $tok]);
        $u = $s->fetch();

        if (!$u) {
            $s2 = $db->prepare("
                SELECT id, name, avatar, status, global_role,
                       validated, terms_accepted, last_seen
                FROM users WHERE id = ? AND token = ? LIMIT 1
            ");
            $s2->execute([$uid, $tok]);
            $u = $s2->fetch();
            if ($u) {
                try {
                    $db->prepare("INSERT OR IGNORE INTO user_sessions(user_id,token,created_at,last_seen) VALUES(?,?,?,?)")
                       ->execute([$uid, $tok, nowSec(), nowSec()]);
                } catch (Exception $e) {}
            }
        }
        if (!$u) return null;

        if (!REQUIRE_VALIDATION && !(int)($u['validated'] ?? 0)) {
            $db->prepare("UPDATE users SET validated=1 WHERE id=?")->execute([$uid]);
            $u['validated'] = 1;
        }
        if (!(int)($u['validated'] ?? 0)) {
            $autoVal = false;
            if (!empty($u['global_role'])) {
                $autoVal = true;
            } else {
                $chkR = $db->prepare("SELECT 1 FROM server_members WHERE user_id=? AND role IN('owner','admin','moderator') LIMIT 1");
                $chkR->execute([$uid]);
                if ($chkR->fetch()) $autoVal = true;
            }
            if ($autoVal) {
                $db->prepare("UPDATE users SET validated=1 WHERE id=?")->execute([$uid]);
                $u['validated'] = 1;
            }
        }

        $now = nowSec();
        if (($now - (int)$u['last_seen']) > 30) {
            try {
                // Одиночный UPDATE без транзакции — снижает нагрузку при большом числе пользователей
                $db->prepare("UPDATE users SET last_seen=? WHERE id=? AND last_seen<?")
                   ->execute([$now, $uid, $now - 25]);
                $db->prepare("UPDATE user_sessions SET last_seen=? WHERE user_id=? AND token=?")
                   ->execute([$now, $uid, $tok]);
            } catch (Exception $e) {}
        }
        return $u;
    }

    function getRole(PDO $db, int $sid, int $uid): string {
        $s = $db->prepare("SELECT owner_id FROM servers WHERE id=?");
        $s->execute([$sid]);
        $srv = $s->fetch();
        if ($srv && (int)$srv['owner_id'] === $uid) return 'owner';
        $m = $db->prepare("SELECT role FROM server_members WHERE server_id=? AND user_id=? AND is_member=1");
        $m->execute([$sid, $uid]);
        $row = $m->fetch();
        return $row ? (string)$row['role'] : 'member';
    }

    function isAdmin(string $r): bool { return in_array($r, ['owner','admin'], true); }
    function isMod(string $r):   bool { return in_array($r, ['owner','admin','moderator'], true); }

    function hasGlobalManagePower(string $name, string $globalRole): bool {
        return $name === OWNER_NAME || in_array($globalRole, ['super_admin','project_admin'], true);
    }

    function permissionAllows(string $rule, string $serverRole, string $name, string $globalRole, bool $isValidated = true): bool {
        $rule = strtolower(trim($rule));
        if (hasGlobalManagePower($name, $globalRole)) return true;
        switch ($rule) {
            case 'all':
                return $isValidated || !REQUIRE_VALIDATION;
            case 'member':
                return in_array($serverRole, ['member','moderator','admin','owner'], true);
            case 'mod':
            case 'moderator':
                return in_array($serverRole, ['moderator','admin','owner'], true);
            case 'admin':
                return in_array($serverRole, ['admin','owner'], true);
            case 'owner':
                return $serverRole === 'owner';
            case 'project_admin':
                return $globalRole === 'project_admin';
            case 'super_admin':
                return $globalRole === 'super_admin';
            default:
                return false;
        }
    }

    function siteBaseUrl(): string {
        if (defined('SITE_URL') && SITE_URL) return rtrim(SITE_URL, '/');
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        return $host ? $scheme . '://' . $host : '';
    }

    function inviteLink(string $code): string {
        $base = siteBaseUrl();
        // Hash route works on shared hosting even without rewrite support.
        return $base ? ($base . '/#inv/' . $code) : ('#inv/' . $code);
    }

    function normalizeInviteCode($raw): string {
        $s = trim((string)$raw);
        if ($s === '') return '';
        $parts = parse_url($s);
        if (is_array($parts)) {
            if (!empty($parts['fragment'])) {
                $frag = (string)$parts['fragment'];
                if (preg_match('~(?:^|/)inv/([A-Za-z0-9_-]+)~', $frag, $m)) return $m[1];
                if (preg_match('~(?:^|/)inv_([A-Za-z0-9_-]+)~', $frag, $m)) return $m[1];
            }
            if (!empty($parts['path'])) {
                $path = (string)$parts['path'];
                if (preg_match('~(?:^|/)inv_([A-Za-z0-9_-]+)~', $path, $m)) return $m[1];
                if (preg_match('~(?:^|/)invite/([A-Za-z0-9_-]+)~', $path, $m)) return $m[1];
            }
        }
        $s = preg_replace('~^#?/?inv/~', '', $s);
        $s = preg_replace('~^inv_~', '', $s);
        return preg_replace('~[^A-Za-z0-9_-]~', '', $s) ?: '';
    }

    function isUserMuted(PDO $db, int $sid, int $uid): bool {
        $s = $db->prepare("SELECT id FROM user_mutes WHERE server_id=? AND user_id=? AND (muted_until=0 OR muted_until>?) LIMIT 1");
        $s->execute([$sid, $uid, nowSec()]);
        return (bool)$s->fetch();
    }

    function isGloballyBanned(PDO $db, int $uid, string $ip = ''): bool {
        $s = $db->prepare("SELECT id FROM global_bans WHERE user_id=? LIMIT 1");
        $s->execute([$uid]);
        if ($s->fetch()) return true;
        if ($ip) {
            $s2 = $db->prepare("SELECT id FROM global_bans WHERE reg_ip=? AND reg_ip!='' LIMIT 1");
            $s2->execute([$ip]);
            if ($s2->fetch()) return true;
        }
        return false;
    }


    function isSuperAdminIdentity(string $name, string $globalRole): bool {
        return $name === OWNER_NAME || in_array($globalRole, ['super_admin','project_admin'], true);
    }

    function getUserNameRole(PDO $db, int $uid): ?array {
        $s = $db->prepare("SELECT id,name,global_role FROM users WHERE id=? LIMIT 1");
        $s->execute([$uid]);
        $u = $s->fetch();
        return $u ?: null;
    }

    function usersShareDmSpace(PDO $db, int $a, int $b): bool {
        if ($a <= 0 || $b <= 0 || $a === $b) return true;
        $q = $db->prepare("
            SELECT 1
            FROM server_members ma
            JOIN server_members mb ON mb.server_id = ma.server_id
            WHERE ma.user_id=? AND mb.user_id=?
              AND ma.is_member=1 AND mb.is_member=1
            LIMIT 1
        ");
        $q->execute([$a, $b]);
        return (bool)$q->fetch();
    }

    function canStartDmWith(PDO $db, int $fromId, int $toId, string $fromName = '', string $fromRole = ''): bool {
        if ($fromId <= 0 || $toId <= 0 || $fromId === $toId) return false;
        if (isSuperAdminIdentity($fromName, $fromRole)) return true;
        $policy = defined('DM_POLICY') ? DM_POLICY : 'shared_space';
        if ($policy === 'everyone') return true;          // любой пользователь
        if ($policy === 'verified_only') {                // только верифицированные отправители
            $s = $db->prepare("SELECT validated,global_role FROM users WHERE id=?");
            $s->execute([$fromId]); $u = $s->fetch();
            if (!$u) return false;
            if ((int)($u['validated'] ?? 0) === 1 || (string)($u['global_role'] ?? '') !== '') return true;
            return false;
        }
        // 'shared_space' (по умолчанию): общий сервер с собеседником.
        return usersShareDmSpace($db, $fromId, $toId);
    }

    function dmPrivacyAllowed(PDO $db, int $ownerId, int $actorId, string $kind, string $actorName = '', string $actorRole = ''): bool {
        if ($ownerId <= 0 || $actorId <= 0 || $ownerId === $actorId) return true;
        if (isSuperAdminIdentity($actorName, $actorRole)) return true;
        $cols = ['dm'=>'allow_dm','audio'=>'allow_audio','video'=>'allow_video'];
        $col = $cols[$kind] ?? 'allow_dm';

        // Персональное правило важнее общего.
        $s = $db->prepare("SELECT $col AS v FROM dm_privacy WHERE user_id=? AND other_user_id=? LIMIT 1");
        $s->execute([$ownerId, $actorId]);
        $row = $s->fetch();
        if ($row && $row['v'] !== null) return (int)$row['v'] === 1;

        // Общее правило. Если строки нет — всё разрешено, как раньше.
        $g = $db->prepare("SELECT $col AS v FROM dm_privacy WHERE user_id=? AND other_user_id=0 LIMIT 1");
        $g->execute([$ownerId]);
        $grow = $g->fetch();
        if ($grow && $grow['v'] !== null) return (int)$grow['v'] === 1;
        return true;
    }

    function canManageCh(PDO $db, array $ch, int $uid, string $uname, string $srvRole): bool {
        if ($uname === OWNER_NAME)         return true;
        if ((int)$ch['owner_id'] === $uid) return true;
        if (isAdmin($srvRole))             return true;
        return false;
    }

    function ensureMember(PDO $db, int $sid, int $uid): void {
        $db->prepare("INSERT OR IGNORE INTO server_members(server_id,user_id,role,joined_at,is_member) VALUES(?,?,'member',?,1)")
           ->execute([$sid, $uid, nowSec()]);
        $db->prepare("UPDATE server_members SET is_member=1 WHERE server_id=? AND user_id=?")
           ->execute([$sid, $uid]);
    }

    function maxBytes(PDO $db, int $uid): int {
        $r = $db->prepare("SELECT name,validated FROM users WHERE id=?");
        $r->execute([$uid]); $row = $r->fetch();
        if ($row && $row['name'] === OWNER_NAME) return PHP_INT_MAX;
        $s = $db->prepare("SELECT role FROM server_members WHERE user_id=? AND role IN('owner','admin') LIMIT 1");
        $s->execute([$uid]);
        if ($s->fetch()) return UPLOAD_MAX_SIZE_ADMIN;
        if ($row && (int)($row['validated'] ?? 0) === 1) return UPLOAD_MAX_SIZE_VALIDATED;
        return UPLOAD_MAX_SIZE_DEFAULT;
    }

    // ── buildMsgBatch ────────────────────────────────────────────
    function buildMsgBatch(PDO $db, array $rows, int $myUid): array {
        if (empty($rows)) return [];

        $ids = array_map('intval', array_column($rows, 'id'));
        $ph  = implode(',', array_fill(0, count($ids), '?'));

        $ccSt = $db->prepare("SELECT message_id, COUNT(*) AS cnt FROM message_comments WHERE message_id IN($ph) GROUP BY message_id");
        $ccSt->execute($ids);
        $commentCounts = array_column($ccSt->fetchAll(), 'cnt', 'message_id');

        $replyIds = array_values(array_filter(array_unique(array_column($rows, 'reply_to'))));
        $replyMap = [];
        if (!empty($replyIds)) {
            $ph2 = implode(',', array_fill(0, count($replyIds), '?'));
            $rSt = $db->prepare("SELECT id, user_name, text, image, deleted FROM messages WHERE id IN($ph2)");
            $rSt->execute($replyIds);
            foreach ($rSt->fetchAll() as $ref) $replyMap[(int)$ref['id']] = $ref;
        }

        $reactions = getReactionsForMsgs($db, $ids, $myUid);

        $result = [];
        foreach ($rows as $r) {
            $msgId = (int)$r['id'];
            if ((int)($r['deleted'] ?? 0) === 1) {
                $result[] = [
                    'id' => $msgId, 'userId' => (int)$r['user_id'],
                    'name' => (string)$r['user_name'],
                    'text' => '', 'textHtml' => '', 'image' => '',
                    'replyTo' => null, 'replyPreview' => null,
                    'type' => 'deleted', 'deleted' => true, 'edited' => false,
                    'at' => (int)$r['at'], 'commentCount' => 0,
                    'reactions' => $reactions[$msgId] ?? [],
                ];
                continue;
            }
            $rp = null;
            if (!empty($r['reply_to']) && isset($replyMap[(int)$r['reply_to']])) {
                $ref = $replyMap[(int)$r['reply_to']];
                $del = (int)($ref['deleted'] ?? 0);
                $rp  = [
                    'name'  => (string)$ref['user_name'],
                    'text'  => $del ? '[удалено]' : (string)$ref['text'],
                    'image' => $del ? '' : (string)$ref['image'],
                ];
            }
            $raw      = (string)($r['text'] ?? '');
            $result[] = [
                'id'           => $msgId,
                'userId'       => (int)$r['user_id'],
                'name'         => (string)$r['user_name'],
                'text'         => $raw,
                'textHtml'     => linkify($raw),
                'image'        => (string)($r['image'] ?? ''),
                'replyTo'      => !empty($r['reply_to']) ? (int)$r['reply_to'] : null,
                'replyPreview' => $rp,
                'type'         => (string)($r['type'] ?? 'msg'),
                'deleted'      => false,
                'edited'       => (bool)(int)($r['edited'] ?? 0),
                'at'           => (int)$r['at'],
                'commentCount' => (int)($commentCounts[$msgId] ?? 0),
                'reactions'    => $reactions[$msgId] ?? [],
            ];
        }
        return $result;
    }

    function buildMsg(PDO $db, array $r): array {
        $res = buildMsgBatch($db, [$r], 0);
        return $res[0] ?? [];
    }

    function buildChannel(PDO $db, array $ch, string $myRole, int $myUid, string $myName): array {
        $ownerName = '';
        if (!empty($ch['owner_id'])) {
            $s = $db->prepare("SELECT name FROM users WHERE id=?");
            $s->execute([(int)$ch['owner_id']]);
            $or = $s->fetch();
            if ($or) $ownerName = $or['name'];
        }
        return [
            'id'             => (int)$ch['id'],
            'name'           => (string)$ch['name'],
            'topic'          => (string)($ch['topic'] ?? ''),
            'description'    => (string)($ch['description'] ?? ''),
            'avatar'         => (string)($ch['avatar'] ?? ''),
            'ownerId'        => (int)($ch['owner_id'] ?? 0),
            'ownerName'      => $ownerName,
            'permRead'       => (string)($ch['perm_read'] ?? 'all'),
            'permWrite'      => (string)($ch['perm_write'] ?? 'all'),
            'myRole'         => $myRole,
            'canManage'      => canManageCh($db, $ch, $myUid, $myName, $myRole),
            'type'           => (string)($ch['type'] ?? 'text'),
            'appIcon'        => (string)($ch['app_icon'] ?? ''),
            'hasAppHtml'     => !empty($ch['app_html']),
            'isPublic'       => (int)($ch['is_public'] ?? 0),
            'appRefChannelId'=> (int)($ch['app_ref_channel_id'] ?? 0),
        ];
    }

    // Сжимает/ресайзит крупные JPEG/PNG/WebP через GD. GIF и PNG с прозрачностью не трогает.
    // Возвращает [бинарные данные, mime, ext]; при недоступности GD или ошибке — исходные данные без изменений.
    function compressImageIfNeeded(string $bin, string $mime): array {
        $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? '';
        // Сжимаем только jpeg/png/webp. GIF (в т.ч. анимация) — как есть.
        if ($ext === '') return [$bin, $mime, null];
        if (defined('IMAGE_COMPRESS') && !IMAGE_COMPRESS) return [$bin, $mime, null]; // выключено в конфиге
        if (!function_exists('imagecreatefromstring')) return [$bin, $mime, null]; // GD не установлен

        $maxDim   = defined('IMAGE_MAX_DIM')   ? max(200, IMAGE_MAX_DIM)   : 2000;
        $sizeGate = defined('IMAGE_SIZE_GATE') ? max(0,   IMAGE_SIZE_GATE) : 1048576;
        $quality  = defined('IMAGE_QUALITY')   ? min(100, max(1, IMAGE_QUALITY)) : 82;
        $info = @getimagesizefromstring($bin);
        if (!$info) return [$bin, $mime, null];
        [$w, $h] = $info;
        $tooBig = (strlen($bin) > $sizeGate) || ($w > $maxDim) || ($h > $maxDim);
        if (!$tooBig) return [$bin, $mime, null]; // мелкие не трогаем
        // Если декодирование такого размера не влезет в memory_limit — не рискуем
        // фатальной ошибкой, отдаём оригинал как есть.
        if (!tc_gd_mem_ok((int)$w, (int)$h)) return [$bin, $mime, null];

        // PNG с прозрачностью (логотипы/стикеры) не трогаем, чтобы не потерять альфу.
        if ($mime === 'image/png') {
            $tmp = @imagecreatefromstring($bin);
            if ($tmp === false) return [$bin, $mime, null];
            $hasAlpha = false;
            if (function_exists('imagecolortransparent') && imagecolortransparent($tmp) >= 0) $hasAlpha = true;
            if (!$hasAlpha) {
                // Проверим несколько пикселей на наличие альфы.
                $tw = imagesx($tmp); $th = imagesy($tmp);
                $steps = 16;
                for ($i = 0; $i < $steps && !$hasAlpha; $i++) {
                    $px = (int)($tw * $i / $steps); $py = (int)($th * $i / $steps);
                    $rgba = imagecolorat($tmp, min($px,$tw-1), min($py,$th-1));
                    if ((($rgba >> 24) & 0x7F) > 0) $hasAlpha = true;
                }
            }
            if ($hasAlpha) { imagedestroy($tmp); return [$bin, $mime, null]; }
            imagedestroy($tmp);
        }

        $src = @imagecreatefromstring($bin);
        if ($src === false) return [$bin, $mime, null];
        $w = imagesx($src); $h = imagesy($src);
        $scale = min(1.0, $maxDim / max($w, $h));
        $nw = max(1, (int)round($w * $scale));
        $nh = max(1, (int)round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        // Белый фон под возможную прозрачность (мы сюда попадаем только для непрозрачных).
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($src);

        ob_start();
        $ok = false; $outMime = $mime; $outExt = $ext;
        if ($mime === 'image/png') {
            // Непрозрачный PNG выгоднее отдать как JPEG.
            $ok = imagejpeg($dst, null, $quality); $outMime = 'image/jpeg'; $outExt = 'jpg';
        } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
            $ok = imagewebp($dst, null, $quality);
        } else {
            $ok = imagejpeg($dst, null, $quality); $outMime = 'image/jpeg'; $outExt = 'jpg';
        }
        $out = ob_get_clean();
        imagedestroy($dst);

        if (!$ok || $out === false || strlen($out) === 0) return [$bin, $mime, null];
        // Если сжатие не дало выигрыша — оставляем оригинал.
        if (strlen($out) >= strlen($bin) && $outMime === $mime) return [$bin, $mime, null];
        return [$out, $outMime, $outExt];
    }

    // Обёртка над верхнеуровневым генератором (логика едина с эндпоинтом ?thumb).
    function makeThumbnail(string $bin, string $mime): ?string {
        return tc_make_thumb_bin($bin, $mime);
    }

    function doUpload(PDO $db, int $uid, string $b64, string $mime, string $originalName = ''): string {
        $clean = (string)preg_replace('/^data:[^;]+;base64,/', '', $b64);
        $bin   = base64_decode($clean, true);
        if ($bin === false || strlen($bin) === 0) apiFail('Ошибка декодирования файла');
        $max = maxBytes($db, $uid);
        if (strlen($bin) > $max) {
            $mb = $max === PHP_INT_MAX ? '∞' : round($max / 1048576);
            apiFail("Файл превышает лимит {$mb} МБ");
        }

        // ── Проверка реального типа файла по содержимому (не доверяем MIME от клиента) ──
        // Клиент присылает заявленный $mime, но расширение и способ выдачи зависят от типа,
        // поэтому тип определяется по байтам и сверяется с заявленным. Несовпадение → отказ.
        // ВАЖНО: сниффинг обёрнут в try/catch — при активном set_error_handler даже
        // подавленный через @ warning внутри getimagesizefromstring/finfo превращается
        // в исключение и иначе уронил бы всю загрузку «Внутренней ошибкой».
        $sniffed = null;
        try { $sniffed = tc_sniff_mime($bin); } catch (\Throwable $e) { $sniffed = null; }
        if ($sniffed !== null) {
            // Группа (image/video/audio/...) должна совпадать; для изображений проверяем и подтип.
            $declaredGroup = explode('/', $mime)[0];
            $sniffedGroup  = explode('/', $sniffed)[0];
            if ($declaredGroup === 'image' || $sniffedGroup === 'image') {
                if ($sniffed !== $mime) {
                    // Доверяем результату сниффинга, а не клиенту.
                    $mime = $sniffed;
                }
            } elseif ($declaredGroup !== $sniffedGroup) {
                apiFail('Содержимое файла не соответствует заявленному типу');
            } else {
                $mime = $sniffed;
            }
        }

        // Сжатие/ресайз крупных изображений (jpeg/png/webp без прозрачности). GIF и прозрачные PNG не трогаются.
        // Любая ошибка GD здесь НЕ должна валить загрузку — просто сохраняем оригинал.
        $forcedExt = null;
        if (str_starts_with($mime, 'image/')) {
            try {
                [$bin, $mime, $forcedExt] = compressImageIfNeeded($bin, $mime);
            } catch (\Throwable $e) {
                error_log('[trueCORD] compress skipped: ' . $e->getMessage());
                $forcedExt = null; // оставляем оригинальные $bin/$mime
            }
        }
        $map = [
            'image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp',
            'video/mp4'=>'mp4','video/webm'=>'webm',
            'audio/mpeg'=>'mp3','audio/mp3'=>'mp3','audio/ogg'=>'ogg','audio/wav'=>'wav',
            'audio/wave'=>'wav','audio/flac'=>'flac','audio/aac'=>'aac','audio/mp4'=>'m4a',
            'audio/x-m4a'=>'m4a','audio/opus'=>'opus','audio/webm'=>'webm',
            'application/zip'=>'zip','application/x-zip-compressed'=>'zip',
            'application/x-rar-compressed'=>'rar','application/x-rar'=>'rar',
            'application/vnd.rar'=>'rar','application/x-7z-compressed'=>'7z',
            'application/x-tar'=>'tar','application/gzip'=>'gz',
            'application/x-gzip'=>'gz','application/x-bzip2'=>'bz2',
            'text/plain'=>'txt','text/markdown'=>'md','text/x-markdown'=>'md',
            'application/json'=>'json','text/csv'=>'csv','text/xml'=>'xml',
            'application/xml'=>'xml','text/x-log'=>'log',
        ];
        if (!isset($map[$mime]) && str_starts_with($mime, 'audio/')) {
            $sub        = explode('/', $mime)[1];
            $map[$mime] = preg_replace('/[^a-z0-9]/', '', strtolower($sub)) ?: 'audio';
        }
        if (!isset($map[$mime])) apiFail('Недопустимый тип: ' . $mime);
        $dir = __DIR__ . '/uploads/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) apiFail('Не удалось создать uploads/');
        if (!is_writable($dir)) apiFail('uploads/ недоступна для записи');
        $ext = $forcedExt ?: $map[$mime];
        if (!empty($originalName)) {
            $origBase = pathinfo($originalName, PATHINFO_FILENAME);
            $origBase = preg_replace('/[^\p{L}\p{N}_\-\.]/u', '_', $origBase);
            $origBase = preg_replace('/_+/', '_', $origBase);
            $origBase = trim($origBase, '_');
            if (empty($origBase)) $origBase = 'file';
            $origBase = mb_substr($origBase, 0, 40);
            $fname    = $origBase . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
        } else {
            $fname = bin2hex(random_bytes(12)) . '.' . $ext;
        }
        if (file_put_contents($dir . $fname, $bin) === false) apiFail('Ошибка записи файла');
        // Сразу создаём миниатюру для ленты (молча; если не вышло — фронт отдаст оригинал).
        // Обёрнуто в try/catch: ошибка миниатюры не должна срывать уже успешную загрузку.
        if (str_starts_with($mime, 'image/')) {
            try {
                $thumb = makeThumbnail($bin, $mime);
                if ($thumb !== null) {
                    $thumbName = preg_replace('/\.[^.]+$/', '', $fname) . '.thumb.jpg';
                    @file_put_contents($dir . $thumbName, $thumb);
                }
            } catch (\Throwable $e) {
                error_log('[trueCORD] thumb skipped: ' . $e->getMessage());
            }
        }
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host  = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base  = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        return $https . '://' . $host . $base . '/uploads/' . $fname;
    }

    function getReactionsForMsgs(PDO $db, array $msgIds, int $myUid): array {
        if (empty($msgIds)) return [];
        $ph = implode(',', array_fill(0, count($msgIds), '?'));
        $s  = $db->prepare("
            SELECT r.message_id, r.emoji,
                   COUNT(*) AS cnt,
                   MAX(CASE WHEN r.user_id=? THEN 1 ELSE 0 END) AS mine
            FROM reactions r
            WHERE r.message_id IN($ph)
            GROUP BY r.message_id, r.emoji
            ORDER BY r.message_id, MIN(r.created_at)
        ");
        $s->execute(array_merge([$myUid], $msgIds));
        $out = [];
        foreach ($s->fetchAll() as $row) {
            $mid = (int)$row['message_id'];
            if (!isset($out[$mid])) $out[$mid] = [];
            $out[$mid][] = ['emoji' => (string)$row['emoji'], 'count' => (int)$row['cnt'], 'mine' => (bool)(int)$row['mine']];
        }
        return $out;
    }

    // Определяет фактическое имя колонки-ссылки на сообщение в таблице dm_reactions.
    // В разных установках встречается и 'dm_message_id' (новые), и 'dm_id' (старые форки).
    function dmReactionColumn(PDO $db): ?string {
        static $cached = false; static $col = null;
        if ($cached) return $col;
        $cached = true; $col = null;
        try {
            $cols = $db->query("PRAGMA table_info(dm_reactions)")->fetchAll();
            $names = array_map(function($c){ return (string)$c['name']; }, $cols);
            if (in_array('dm_message_id', $names, true))      $col = 'dm_message_id';
            elseif (in_array('dm_id', $names, true))          $col = 'dm_id';
        } catch (Exception $e) { $col = null; }
        return $col;
    }

    // То же самое, но для личных сообщений. Устойчиво к имени колонки и к отсутствию таблицы:
    // любая ошибка реакций НЕ должна ронять загрузку самих сообщений.
    function getDmReactionsForMsgs(PDO $db, array $dmIds, int $myUid): array {
        if (empty($dmIds)) return [];
        $col = dmReactionColumn($db);
        if ($col === null) return []; // таблицы/колонки нет — просто без реакций
        try {
            $ph = implode(',', array_fill(0, count($dmIds), '?'));
            $s  = $db->prepare("
                SELECT r.$col AS mid, r.emoji,
                       COUNT(*) AS cnt,
                       MAX(CASE WHEN r.user_id=? THEN 1 ELSE 0 END) AS mine
                FROM dm_reactions r
                WHERE r.$col IN($ph)
                GROUP BY r.$col, r.emoji
                ORDER BY r.$col, MIN(r.created_at)
            ");
            $s->execute(array_merge([$myUid], $dmIds));
            $out = [];
            foreach ($s->fetchAll() as $row) {
                $mid = (int)$row['mid'];
                if (!isset($out[$mid])) $out[$mid] = [];
                $out[$mid][] = ['emoji' => (string)$row['emoji'], 'count' => (int)$row['cnt'], 'mine' => (bool)(int)$row['mine']];
            }
            return $out;
        } catch (Exception $e) {
            error_log('[trueCORD] dm_reactions read failed: ' . $e->getMessage());
            return [];
        }
    }

    function getVoiceParticipants(PDO $db, int $roomId): array {
        $thr = nowSec() - 30;
        // Try with force_muted, fall back without it
        try {
            $s = $db->prepare("
                SELECT vp.user_id, u.name, u.avatar, vp.muted, vp.force_muted
                FROM voice_participants vp
                JOIN users u ON u.id = vp.user_id
                WHERE vp.room_id = ? AND vp.last_ping >= ?
            ");
            $s->execute([$roomId, $thr]);
            $hasForce = true;
        } catch (Exception $e) {
            $s = $db->prepare("
                SELECT vp.user_id, u.name, u.avatar, vp.muted
                FROM voice_participants vp
                JOIN users u ON u.id = vp.user_id
                WHERE vp.room_id = ? AND vp.last_ping >= ?
            ");
            $s->execute([$roomId, $thr]);
            $hasForce = false;
        }
        $out = [];
        foreach ($s->fetchAll() as $p) {
            $out[] = ['userId' => (int)$p['user_id'], 'name' => (string)$p['name'], 'avatar' => (string)($p['avatar'] ?? ''), 'muted' => (bool)(int)($p['muted'] ?? 0), 'forceMuted' => $hasForce ? (bool)(int)($p['force_muted'] ?? 0) : false];
        }
        return $out;
    }

    function getUserRoleColor(PDO $db, int $serverId, int $userId): string {
        $s = $db->prepare("
            SELECT sr.color FROM user_server_roles usr
            JOIN server_roles sr ON sr.id = usr.role_id
            WHERE usr.server_id=? AND usr.user_id=?
            ORDER BY sr.position DESC LIMIT 1
        ");
        $s->execute([$serverId, $userId]);
        $r = $s->fetch();
        return $r ? (string)$r['color'] : '';
    }

    // ── ROUTER ───────────────────────────────────────────────────
    $action = (string)($d['action'] ?? '');

    // ── DISPATCH (modular) ───────────────────────────────────────
    // Хендлеры разнесены по доменным модулям и подключаются через include,
    // который наследует текущую область видимости ($db, $d, $action и хелперы).
    // Каждый хендлер завершается apiOk()/apiFail() с exit, поэтому порядок include
    // безопасен: до совпадения по $action модули просто "проваливаются" дальше.
    $TC_API_MODULES = [
        'auth',               // register, login, invite info, verify_session, logout, link_preview
        'users_presence',     // heartbeat, set_status, get_users
        'servers_roles',      // servers, members, roles, comments
        'channels_messages',  // channels, messages, reactions, upload, avatar
        'moderation',         // typing, mute/kick, validate, global ban/role, invites
        'dm',                 // direct messages, games, dm-calls
        'voice',              // voice rooms, signaling, ICE, app channels, unlink_media
    ];
    foreach ($TC_API_MODULES as $tcMod) {
        $tcPath = __DIR__ . '/api_modules/' . $tcMod . '.php';
        if (is_file($tcPath)) { include $tcPath; }
    }

} catch (Throwable $e) {
    $detail = get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine();
    error_log('[trueCORD] ' . $detail);
    sendError((defined('DEBUG_MODE') && DEBUG_MODE) ? $detail : 'Внутренняя ошибка сервера');
}

// ══════════════════════════════════════════════════════════════════
//  SCHEMA INIT
// ══════════════════════════════════════════════════════════════════
function _initSchema(PDO $db): void {
    $db->exec('BEGIN');
    try {
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE COLLATE NOCASE,
            pass TEXT NOT NULL,
            token TEXT DEFAULT '',
            avatar TEXT DEFAULT '',
            status TEXT DEFAULT 'online',
            last_seen INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0,
            global_role TEXT DEFAULT '',
            validated INTEGER DEFAULT 0,
            validated_by INTEGER DEFAULT 0,
            terms_accepted INTEGER DEFAULT 0,
            reg_ip TEXT DEFAULT ''
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS user_sessions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            token TEXT NOT NULL UNIQUE,
            device TEXT DEFAULT '',
            created_at INTEGER DEFAULT 0,
            last_seen INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_sessions_token ON user_sessions(token)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_sessions_user  ON user_sessions(user_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS servers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT DEFAULT '',
            icon TEXT DEFAULT '',
            owner_id INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS server_members (
            server_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            role TEXT DEFAULT 'member',
            joined_at INTEGER DEFAULT 0,
            left_at INTEGER DEFAULT 0,
            is_member INTEGER DEFAULT 1,
            PRIMARY KEY(server_id, user_id)
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_members_user_id ON server_members(user_id, is_member)");

        $db->exec("CREATE TABLE IF NOT EXISTS channels (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            owner_id INTEGER DEFAULT 0,
            name TEXT NOT NULL,
            topic TEXT DEFAULT '',
            description TEXT DEFAULT '',
            avatar TEXT DEFAULT '',
            perm_read TEXT DEFAULT 'all',
            perm_write TEXT DEFAULT 'all',
            position INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            channel_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            user_name TEXT NOT NULL,
            text TEXT DEFAULT '',
            image TEXT DEFAULT '',
            reply_to INTEGER DEFAULT NULL,
            type TEXT DEFAULT 'msg',
            deleted INTEGER DEFAULT 0,
            edited INTEGER DEFAULT 0,
            at INTEGER NOT NULL
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_messages_channel_id ON messages(channel_id, id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_messages_user_id    ON messages(user_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS message_comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            message_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            user_name TEXT NOT NULL,
            user_avatar TEXT DEFAULT '',
            text TEXT NOT NULL,
            at INTEGER NOT NULL
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_comments_msg ON message_comments(message_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS reactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            message_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            emoji TEXT NOT NULL,
            created_at INTEGER DEFAULT 0,
            UNIQUE(message_id, user_id, emoji)
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_reactions_msg ON reactions(message_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS dm_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            from_user_id INTEGER NOT NULL,
            to_user_id INTEGER NOT NULL,
            text TEXT DEFAULT '',
            image TEXT DEFAULT '',
            deleted INTEGER DEFAULT 0,
            at INTEGER NOT NULL,
            read_at INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_pair ON dm_messages(from_user_id, to_user_id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_to   ON dm_messages(to_user_id, read_at)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_msg_conv ON dm_messages(from_user_id, to_user_id, id)");

        // Реакции на личные сообщения. Отдельная таблица, т.к. dm_messages.id и messages.id
        // нумеруются независимо и могут пересекаться — общий reactions использовать нельзя.
        $db->exec("CREATE TABLE IF NOT EXISTS dm_reactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            dm_message_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            emoji TEXT NOT NULL,
            created_at INTEGER DEFAULT 0,
            UNIQUE(dm_message_id, user_id, emoji)
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_reactions_msg ON dm_reactions(dm_message_id)");

        // Защита от брутфорса пароля: фиксируем неудачные попытки входа по IP.
        $db->exec("CREATE TABLE IF NOT EXISTS login_attempts (
            ip TEXT NOT NULL,
            fails INTEGER DEFAULT 0,
            first_at INTEGER DEFAULT 0,
            locked_until INTEGER DEFAULT 0,
            PRIMARY KEY(ip)
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS dm_blacklist (
            user_id INTEGER NOT NULL,
            blocked_user_id INTEGER NOT NULL,
            created_at INTEGER DEFAULT 0,
            PRIMARY KEY(user_id, blocked_user_id)
        )");


        $db->exec("CREATE TABLE IF NOT EXISTS dm_privacy (
            user_id INTEGER NOT NULL,
            other_user_id INTEGER NOT NULL DEFAULT 0,
            allow_dm INTEGER DEFAULT NULL,
            allow_audio INTEGER DEFAULT NULL,
            allow_video INTEGER DEFAULT NULL,
            updated_at INTEGER DEFAULT 0,
            PRIMARY KEY(user_id, other_user_id)
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dm_privacy_user ON dm_privacy(user_id, other_user_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS server_invites (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            creator_id INTEGER NOT NULL,
            code TEXT NOT NULL UNIQUE,
            max_uses INTEGER DEFAULT 0,
            uses INTEGER DEFAULT 0,
            expires_at INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_invites_code ON server_invites(code)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_invites_srv  ON server_invites(server_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS voice_rooms (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            position INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_vrooms_srv ON voice_rooms(server_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS voice_participants (
            room_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            last_ping INTEGER NOT NULL DEFAULT 0,
            muted INTEGER DEFAULT 0,
            PRIMARY KEY(room_id, user_id)
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS voice_events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            room_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            user_name TEXT NOT NULL,
            event_type TEXT NOT NULL,
            created_at INTEGER NOT NULL
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_vevents_room ON voice_events(room_id, id)");

        $db->exec("CREATE TABLE IF NOT EXISTS voice_signals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            room_id INTEGER NOT NULL,
            from_user_id INTEGER NOT NULL,
            to_user_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            data TEXT NOT NULL,
            created_at INTEGER NOT NULL
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_vsig_to ON voice_signals(to_user_id, room_id, id)");

        $db->exec("CREATE TABLE IF NOT EXISTS dm_call_signals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            from_user_id INTEGER NOT NULL,
            to_user_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            data TEXT DEFAULT '',
            created_at INTEGER NOT NULL
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dmcall_to   ON dm_call_signals(to_user_id, id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dmcall_from ON dm_call_signals(from_user_id, id)");

        $db->exec("CREATE TABLE IF NOT EXISTS typing_signals (
            channel_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            user_name TEXT NOT NULL,
            updated_at INTEGER NOT NULL,
            PRIMARY KEY(channel_id, user_id)
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS user_mutes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            muted_by INTEGER NOT NULL,
            reason TEXT DEFAULT '',
            muted_until INTEGER DEFAULT 0,
            created_at INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_mutes_srv_user  ON user_mutes(server_id, user_id)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_mutes_user_srv  ON user_mutes(server_id, user_id, muted_until)");

        $db->exec("CREATE TABLE IF NOT EXISTS server_kicks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            kicked_by INTEGER NOT NULL,
            reason TEXT DEFAULT '',
            created_at INTEGER DEFAULT 0
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS global_bans (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            username TEXT DEFAULT '',
            reg_ip TEXT DEFAULT '',
            banned_by INTEGER NOT NULL,
            reason TEXT DEFAULT '',
            created_at INTEGER DEFAULT 0,
            UNIQUE(user_id)
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_gbans_ip  ON global_bans(reg_ip)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_gbans_uid ON global_bans(user_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS server_roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            server_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            color TEXT DEFAULT '#c9aa71',
            position INTEGER DEFAULT 0,
            permissions TEXT DEFAULT '',
            created_at INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_roles_srv ON server_roles(server_id)");

        $db->exec("CREATE TABLE IF NOT EXISTS user_server_roles (
            server_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            role_id INTEGER NOT NULL,
            PRIMARY KEY(server_id, user_id, role_id)
        )");


        $db->exec("CREATE TABLE IF NOT EXISTS link_previews (
            url TEXT PRIMARY KEY,
            title TEXT DEFAULT '',
            description TEXT DEFAULT '',
            image TEXT DEFAULT '',
            site_name TEXT DEFAULT '',
            final_url TEXT DEFAULT '',
            fetched_at INTEGER DEFAULT 0,
            fail INTEGER DEFAULT 0
        )");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_link_previews_fetched ON link_previews(fetched_at)");

        $db->exec("CREATE TABLE IF NOT EXISTS server_notif_settings (
            user_id INTEGER NOT NULL,
            server_id INTEGER NOT NULL,
            muted INTEGER DEFAULT 0,
            PRIMARY KEY(user_id, server_id)
        )");

        $db->exec("CREATE INDEX IF NOT EXISTS idx_users_last_seen ON users(last_seen)");
        $db->exec("CREATE INDEX IF NOT EXISTS idx_users_name      ON users(name COLLATE NOCASE)");

        $mig = function (PDO $db, string $tbl, string $col, string $def) {
            try {
                $cols = $db->query("PRAGMA table_info({$tbl})")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($cols as $c) if ($c['name'] === $col) return;
                $db->exec("ALTER TABLE {$tbl} ADD COLUMN {$col} {$def}");
            } catch (Exception $e) {}
        };

        $mig($db,'users','avatar',          "TEXT    DEFAULT ''");
        $mig($db,'users','token',           "TEXT    DEFAULT ''");
        $mig($db,'users','status',          "TEXT    DEFAULT 'online'");
        $mig($db,'users','last_seen',       "INTEGER DEFAULT 0");
        $mig($db,'users','created_at',      "INTEGER DEFAULT 0");
        $mig($db,'users','global_role',     "TEXT    DEFAULT ''");
        $mig($db,'users','validated',       "INTEGER DEFAULT 0");
        $mig($db,'users','validated_by',    "INTEGER DEFAULT 0");
        $mig($db,'users','terms_accepted',  "INTEGER DEFAULT 0");
        $mig($db,'users','reg_ip',          "TEXT    DEFAULT ''");
        $mig($db,'servers','description',   "TEXT    DEFAULT ''");
        $mig($db,'servers','icon',          "TEXT    DEFAULT ''");
        $mig($db,'servers','owner_id',      "INTEGER DEFAULT 0");
        $mig($db,'servers','created_at',    "INTEGER DEFAULT 0");
        $mig($db,'channels','server_id',    "INTEGER DEFAULT 0");
        $mig($db,'channels','owner_id',     "INTEGER DEFAULT 0");
        $mig($db,'channels','topic',        "TEXT    DEFAULT ''");
        $mig($db,'channels','description',  "TEXT    DEFAULT ''");
        $mig($db,'channels','avatar',       "TEXT    DEFAULT ''");
        $mig($db,'channels','perm_read',    "TEXT    DEFAULT 'all'");
        $mig($db,'channels','perm_write',   "TEXT    DEFAULT 'all'");
        $mig($db,'channels','position',     "INTEGER DEFAULT 0");
        $mig($db,'channels','created_at',   "INTEGER DEFAULT 0");
        $mig($db,'messages','channel_id',   "INTEGER DEFAULT 0");
        $mig($db,'messages','user_id',      "INTEGER DEFAULT 0");
        $mig($db,'messages','user_name',    "TEXT    DEFAULT ''");
        $mig($db,'messages','text',         "TEXT    DEFAULT ''");
        $mig($db,'messages','image',        "TEXT    DEFAULT ''");
        $mig($db,'messages','reply_to',     "INTEGER DEFAULT NULL");
        $mig($db,'messages','type',         "TEXT    DEFAULT 'msg'");
        $mig($db,'messages','deleted',      "INTEGER DEFAULT 0");
        $mig($db,'messages','edited',       "INTEGER DEFAULT 0");
        $mig($db,'dm_messages','from_user_id', "INTEGER DEFAULT 0");
        $mig($db,'dm_messages','to_user_id',   "INTEGER DEFAULT 0");
        $mig($db,'dm_messages','text',         "TEXT    DEFAULT ''");
        $mig($db,'dm_messages','image',        "TEXT    DEFAULT ''");
        $mig($db,'dm_messages','deleted',      "INTEGER DEFAULT 0");
        $mig($db,'dm_messages','read_at',      "INTEGER DEFAULT 0");
        $mig($db,'voice_rooms','position',     "INTEGER DEFAULT 0");
        $mig($db,'voice_participants','muted',  "INTEGER DEFAULT 0");
        $mig($db,'voice_participants','force_muted',  "INTEGER DEFAULT 0");
        $mig($db,'server_members','left_at',   "INTEGER DEFAULT 0");
        $mig($db,'server_members','is_member', "INTEGER DEFAULT 1");
        $mig($db,'channels','type',            "TEXT    DEFAULT 'text'");
        $mig($db,'channels','app_html',        "TEXT    DEFAULT ''");
        $mig($db,'channels','app_icon',        "TEXT    DEFAULT ''");
        $mig($db,'channels','is_public',          "INTEGER DEFAULT 0");
        $mig($db,'channels','app_ref_channel_id', "INTEGER DEFAULT 0");

        if (!$db->query("SELECT id FROM servers WHERE id=1")->fetch()) {
            $ownerRow = $db->query("SELECT id FROM users WHERE name='" . OWNER_NAME . "'")->fetch();
            $ownId    = $ownerRow ? (int)$ownerRow['id'] : 0;
            $t        = time();
            $db->prepare("INSERT INTO servers (id,name,description,icon,owner_id,created_at) VALUES (1,?,?,?,?,?)")->execute([DEFAULT_SERVER_NAME, DEFAULT_SERVER_DESCRIPTION, DEFAULT_SERVER_ICON, $ownId, $t]);
            $db->prepare("INSERT INTO channels (id,server_id,owner_id,name,topic,description,avatar,created_at) VALUES (1,1,?,?,?,?,?,?)")->execute([$ownId, DEFAULT_CHANNEL_NAME, DEFAULT_CHANNEL_TOPIC, DEFAULT_SERVER_DESCRIPTION, defined('DEFAULT_CHANNEL_ICON') ? DEFAULT_CHANNEL_ICON : '', $t]);
            if ($ownId > 0)
                $db->prepare("INSERT OR IGNORE INTO server_members(server_id,user_id,role,joined_at,is_member) VALUES(1,?,'owner',?,1)")->execute([$ownId,$t]);
        }

        $db->exec("UPDATE users SET global_role='super_admin', validated=1 WHERE name='" . OWNER_NAME . "'");
        $db->exec("UPDATE users SET validated=1 WHERE id IN (SELECT DISTINCT user_id FROM server_members WHERE role IN('owner','admin','moderator')) AND validated=0");
        $db->exec("UPDATE users SET validated=1 WHERE global_role!='' AND validated=0");
        if (!REQUIRE_VALIDATION) {
            $db->exec("UPDATE users SET validated=1 WHERE validated=0");
            if (!defined('SERVER_DIRECTORY_MODE') || SERVER_DIRECTORY_MODE !== 'invite_only') {
                $db->exec("UPDATE server_members SET is_member=1 WHERE is_member=0 AND left_at=0");
            }
        }
        // Основной сервер использует иконку сайта, но обычный канал не должен наследовать эту иконку.
        if (defined('DEFAULT_SERVER_ICON') && DEFAULT_SERVER_ICON !== '') {
            $srvIcon = $db->quote(DEFAULT_SERVER_ICON);
            $db->exec("UPDATE servers SET icon=$srvIcon WHERE id=1 AND (icon IS NULL OR icon='' OR icon='🎧' OR icon='🏰')");
        }
        if (defined('DEFAULT_CHANNEL_ICON') && DEFAULT_CHANNEL_ICON !== '') {
            $icon = $db->quote(DEFAULT_CHANNEL_ICON);
            $name = $db->quote(DEFAULT_CHANNEL_NAME);
            $db->exec("UPDATE channels SET avatar=$icon WHERE (avatar IS NULL OR avatar='') AND server_id=1 AND name=$name");
            $db->exec("UPDATE channels SET avatar=$icon WHERE (avatar IS NULL OR avatar='') AND id=1");
        } else {
            $db->exec("UPDATE channels SET avatar='' WHERE server_id=1 AND (avatar='icon_tC_main.png' OR avatar='icon_tC_192.png' OR avatar='icon_tC_512.png')");
        }

        // Основной сервер trueCORD является стартовым: все зарегистрированные пользователи должны быть его участниками.
        // tes3chat (AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE=false): членство добровольное и хранится в базе —
        // НЕ добавляем всех пользователей в основной сервер принудительно, существующие is_member читаются как есть.
        if (!defined('AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE') || AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE) {
            $tMain = time();
            $db->exec("INSERT OR IGNORE INTO server_members(server_id,user_id,role,joined_at,is_member) SELECT 1,id,'member',$tMain,1 FROM users WHERE id NOT IN (SELECT user_id FROM server_kicks WHERE server_id=1)");
            $db->exec("UPDATE server_members SET is_member=1,left_at=0 WHERE server_id=1 AND role='member' AND user_id NOT IN (SELECT user_id FROM server_kicks WHERE server_id=1)");
        }

        // В invite-only режиме обычные участники не получают доступ ко всем серверам автоматически.
        $db->exec("UPDATE server_members SET is_member=1 WHERE (is_member IS NULL OR is_member=0) AND left_at=0 AND role IN('owner','admin','moderator')");

        // Владельцы серверов — всегда участники своих серверов
        $db->exec("
            UPDATE server_members SET is_member=1
            WHERE is_member=0 AND left_at=0
              AND EXISTS (
                SELECT 1 FROM servers s
                WHERE s.id = server_members.server_id
                  AND s.owner_id = server_members.user_id
              )
        ");

        if (!$db->query("SELECT id FROM voice_rooms WHERE server_id=1")->fetch()) {
            $t = time();
            $db->exec("INSERT INTO voice_rooms(server_id,name,position,created_at) VALUES(1,'Main',0,$t)");
            $db->exec("INSERT INTO voice_rooms(server_id,name,position,created_at) VALUES(1,'Second',1,$t)");
        }

        $db->exec('COMMIT');
    } catch (Exception $e) {
        try { $db->exec('ROLLBACK'); } catch (Exception $e2) {}
        throw $e;
    }
}