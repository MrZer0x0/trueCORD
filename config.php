<?php
// trueCORD config bootstrap.
// Main editable file: config.json

function tc_config_defaults() {
    return [
        'project' => [
            'name' => 'trueCORD',
            'tagline' => 'Современная платформа общения для сообществ, команд и друзей.',
            'description' => 'Deploy your own Discord-like chat platform with text channels, direct messages, voice rooms and media sharing.',
            'language' => 'ru',
            'url' => '',
            'api_endpoint' => 'truecord_api.php',
            'default_theme' => 'truecolor',
            'show_install_button' => true,
            'default_server' => [
                'name' => 'trueCORD',
                'description' => 'Main community server',
                'icon' => 'icon_tC_main.png',
            ],
            'default_channel' => [
                'name' => 'general',
                'topic' => 'Welcome to trueCORD',
                'icon' => '',
            ],
        ],
        'owner' => [
            'super_admin_name' => 'Admin',
        ],
        'registration' => [
            'open' => true,
            'mode' => 'discord',
            'auto_validate' => true,
            'require_terms_acceptance' => true,
            'min_age' => 18,
            'username_min_length' => 4,
            'first_admin_bypass_username_min_length' => true,
            'first_registered_user_becomes_super_admin' => true,
            'username_max_length' => 24,
            'password_min_length' => 8,
            'password_max_length' => 128,
        ],
        'permissions' => [
            'create_server' => 'member',
            'create_channel' => 'member',
            'create_voice_room' => 'member',
        ],
        'discovery' => [
            'server_directory_mode' => 'invite_only',
            'channel_list_mode' => 'all',
        ],
        // Политики членства в основном сервере (server_id=1).
        // Это единственные «поведенческие» отличия сборок trueCORD <-> tes3chat:
        // меняются ТОЛЬКО здесь, в config.json — код общий.
        'membership' => [
            // true  (trueCORD): новый пользователь сразу становится участником основного сервера.
            // false (tes3chat): пользователь регистрируется как НЕ участник и вступает сам через join_server.
            'new_user_joins_main' => true,
            // true  (trueCORD): основной сервер №1 подчиняется server_directory_mode (в invite_only может скрываться).
            // false (tes3chat): основной сервер №1 виден всегда, даже в режиме invite_only.
            'main_server_always_visible' => false,
            // true  (trueCORD): при подтягивании/миграции базы членство пересчитывается и достраивается автоматически.
            // false (tes3chat): существующие значения is_member НЕ трогаются — читаются из базы как есть.
            'auto_reconcile_on_migrate' => true,
        ],
        'messaging' => [
            'enabled' => true,
            'dm_enabled' => true,
            'dm_require_validation' => false,
            // Кто может начинать ЛС с другими участниками:
            //   'shared_space' — любой, кто состоит с собеседником в общем сервере (по умолчанию);
            //   'verified_only' — только верифицированные пользователи;
            //   'everyone' — любой пользователь, даже без общих серверов.
            'dm_policy' => 'shared_space',
            'message_max_length' => 4000,
            'dm_max_length' => 4000,
            'messages_per_page' => 30,
        ],
        'uploads' => [
            'max_size_default' => 8 * 1024 * 1024,
            'max_size_validated' => 32 * 1024 * 1024,
            'max_size_admin' => 128 * 1024 * 1024,
            // Сжатие изображений при загрузке (jpeg/png/webp без прозрачности; gif и прозрачные png не трогаются).
            'image_compress' => true,
            'image_max_dim' => 2000,        // макс. сторона в пикселях
            'image_size_gate' => 1048576,   // не трогать файлы меньше этого размера (байт)
            'image_quality' => 82,          // качество jpeg/webp 1..100
            // Миниатюры для предпросмотра в ленте (ускоряют загрузку: грузится маленькая версия, оригинал — по клику).
            'image_thumbs' => true,         // включить генерацию миниатюр
            'image_thumb_dim' => 480,       // макс. сторона миниатюры, px
            'image_thumb_quality' => 70,    // качество миниатюры 1..100
        ],
        'voice' => [
            'enabled' => true,
            'screen_share_enabled' => true,
            'max_participants' => 0,
            'signal_ttl' => 180,
            'call_signal_ttl' => 600,
            'timeout' => 30,
        ],
        'presence' => [
            'online_timeout' => 90,
        ],
        'pwa' => [
            'short_name' => 'trueCORD',
            'description' => 'Современная платформа общения для сообществ, команд и друзей',
            'theme_color' => '#0077ff',
            'background_color' => '#edeef0',
        ],
        'database' => [
            'path' => 'truecord.db',
            'busy_timeout' => 15000,
            'cache_size' => -16000,
            'mmap_size' => 134217728,
            'optimize_chance' => 500,
            'optimize_interval' => 3600,
        ],
        'security' => [
            'debug_mode' => false,
            'cors_enabled' => false,
            'cors_origin' => '*',
        ],
        'webrtc' => [
            'ice_servers' => [
                ['urls' => 'stun:stun.l.google.com:19302'],
                ['urls' => 'stun:stun1.l.google.com:19302'],
                ['urls' => 'stun:stun2.l.google.com:19302'],
            ],
        ],
        'legal' => [
            'registration_terms_file' => 'terms_registration.html',
            'registration_terms_title' => 'Пользовательское соглашение',
            'terms_checkbox_label' => 'Я прочитал(а) и принимаю Пользовательское соглашение.',
            'registration_modal_note' => 'Регистрируясь, пользователь принимает правила сервиса и обязуется соблюдать действующее законодательство.',
            'privacy_policy_url' => 'privacy.html',
        ],
    ];
}

function tc_array_merge_distinct(array $defaults, array $custom) {
    foreach ($custom as $key => $value) {
        if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
            $defaults[$key] = tc_array_merge_distinct($defaults[$key], $value);
        } else {
            $defaults[$key] = $value;
        }
    }
    return $defaults;
}

function tc_cfg_get(array $cfg, string $path, $default = null) {
    $node = $cfg;
    foreach (explode('.', $path) as $part) {
        if (!is_array($node) || !array_key_exists($part, $node)) return $default;
        $node = $node[$part];
    }
    return $node;
}

function tc_define($name, $value) {
    if (!defined($name)) define($name, $value);
}

$tcDefaults = tc_config_defaults();
$tcConfigPath = __DIR__ . '/config.json';
$tcCustom = [];
if (is_file($tcConfigPath)) {
    $json = file_get_contents($tcConfigPath);
    $parsed = json_decode((string)$json, true);
    if (is_array($parsed)) $tcCustom = $parsed;
}
$tcCfg = tc_array_merge_distinct($tcDefaults, $tcCustom);
$tcTermsFile = (string)tc_cfg_get($tcCfg, 'legal.registration_terms_file', 'terms_registration.html');
$tcTermsPath = __DIR__ . '/' . ltrim($tcTermsFile, '/');
$tcTermsHtml = is_file($tcTermsPath) ? (string)file_get_contents($tcTermsPath) : '';
if ($tcTermsHtml === '') {
    $tcTermsHtml = '<h4>Пользовательское соглашение</h4><p>Пожалуйста, добавьте ваш текст оферты в файл terms_registration.html.</p>';
}
$tcRegMode = strtolower((string)tc_cfg_get($tcCfg, 'registration.mode', 'discord'));
$tcRequireValidation = in_array($tcRegMode, ['approval', 'validated', 'manual_validation'], true);
$tcAutoValidate = $tcRequireValidation ? (bool)tc_cfg_get($tcCfg, 'registration.auto_validate', false) : true;

$GLOBALS['TC_APP_CONFIG'] = $tcCfg;
tc_define('APP_LANG', (string)tc_cfg_get($tcCfg, 'project.language', 'ru'));
tc_define('SITE_NAME', (string)tc_cfg_get($tcCfg, 'project.name', 'trueCORD'));
tc_define('SITE_TAGLINE', (string)tc_cfg_get($tcCfg, 'project.tagline', ''));
tc_define('SITE_DESCRIPTION', (string)tc_cfg_get($tcCfg, 'project.description', ''));
tc_define('SITE_URL', rtrim((string)tc_cfg_get($tcCfg, 'project.url', ''), '/'));
tc_define('API_ENDPOINT', (string)tc_cfg_get($tcCfg, 'project.api_endpoint', 'truecord_api.php'));
tc_define('DEFAULT_THEME', (string)tc_cfg_get($tcCfg, 'project.default_theme', 'truecolor'));
tc_define('PWA_ENABLED', (bool)tc_cfg_get($tcCfg, 'project.show_install_button', true));
tc_define('DEFAULT_SERVER_NAME', (string)tc_cfg_get($tcCfg, 'project.default_server.name', SITE_NAME));
tc_define('DEFAULT_SERVER_DESCRIPTION', (string)tc_cfg_get($tcCfg, 'project.default_server.description', 'Main community server'));
tc_define('DEFAULT_SERVER_ICON', (string)tc_cfg_get($tcCfg, 'project.default_server.icon', 'icon_tC_main.png'));
tc_define('DEFAULT_CHANNEL_NAME', (string)tc_cfg_get($tcCfg, 'project.default_channel.name', 'general'));
tc_define('DEFAULT_CHANNEL_TOPIC', (string)tc_cfg_get($tcCfg, 'project.default_channel.topic', 'Welcome'));
tc_define('DEFAULT_CHANNEL_ICON', (string)tc_cfg_get($tcCfg, 'project.default_channel.icon', ''));

tc_define('OWNER_NAME', (string)tc_cfg_get($tcCfg, 'owner.super_admin_name', 'Admin'));

// Ключ YouTube Data API v3 (необязательно). Если задан — поиск идёт через
// официальный API; если пуст — сервер парсит выдачу YouTube (без ключа).
tc_define('YOUTUBE_API_KEY', (string)tc_cfg_get($tcCfg, 'integrations.youtube_api_key', ''));

tc_define('REGISTRATION_OPEN', (bool)tc_cfg_get($tcCfg, 'registration.open', true));
tc_define('REQUIRE_VALIDATION', $tcRequireValidation);
tc_define('AUTO_VALIDATE', $tcAutoValidate);
tc_define('REQUIRE_TERMS_ACCEPTANCE', (bool)tc_cfg_get($tcCfg, 'registration.require_terms_acceptance', true));
tc_define('MINIMUM_AGE', (int)tc_cfg_get($tcCfg, 'registration.min_age', 18));
tc_define('USERNAME_MIN_LEN', (int)tc_cfg_get($tcCfg, 'registration.username_min_length', 4));
tc_define('FIRST_ADMIN_BYPASS_USERNAME_MIN_LENGTH', (bool)tc_cfg_get($tcCfg, 'registration.first_admin_bypass_username_min_length', true));
tc_define('FIRST_REGISTERED_USER_BECOMES_SUPER_ADMIN', (bool)tc_cfg_get($tcCfg, 'registration.first_registered_user_becomes_super_admin', true));
tc_define('USERNAME_MAX_LEN', (int)tc_cfg_get($tcCfg, 'registration.username_max_length', 24));
tc_define('PASSWORD_MIN_LEN', (int)tc_cfg_get($tcCfg, 'registration.password_min_length', 8));
tc_define('PASSWORD_MAX_LEN', (int)tc_cfg_get($tcCfg, 'registration.password_max_length', 128));

tc_define('CREATE_SERVER_PERMISSION', (string)tc_cfg_get($tcCfg, 'permissions.create_server', 'super_admin'));
tc_define('CREATE_CHANNEL_PERMISSION', (string)tc_cfg_get($tcCfg, 'permissions.create_channel', 'member'));
tc_define('CREATE_VOICE_PERMISSION', (string)tc_cfg_get($tcCfg, 'permissions.create_voice_room', 'member'));

tc_define('SERVER_DIRECTORY_MODE', (string)tc_cfg_get($tcCfg, 'discovery.server_directory_mode', 'invite_only'));
tc_define('CHANNEL_LIST_MODE', (string)tc_cfg_get($tcCfg, 'discovery.channel_list_mode', 'all'));

// Политики членства (единственные «поведенческие» отличия сборок — см. секцию membership в config.php).
tc_define('NEW_USER_JOINS_MAIN', (bool)tc_cfg_get($tcCfg, 'membership.new_user_joins_main', true));
tc_define('MAIN_SERVER_ALWAYS_VISIBLE', (bool)tc_cfg_get($tcCfg, 'membership.main_server_always_visible', false));
tc_define('AUTO_RECONCILE_MEMBERSHIP_ON_MIGRATE', (bool)tc_cfg_get($tcCfg, 'membership.auto_reconcile_on_migrate', true));

tc_define('MESSAGE_FEATURE_ENABLED', (bool)tc_cfg_get($tcCfg, 'messaging.enabled', true));
tc_define('DM_ENABLED', (bool)tc_cfg_get($tcCfg, 'messaging.dm_enabled', true));
tc_define('DM_REQUIRE_VALIDATION', (bool)tc_cfg_get($tcCfg, 'messaging.dm_require_validation', false));
tc_define('DM_POLICY', (string)tc_cfg_get($tcCfg, 'messaging.dm_policy', 'shared_space'));
tc_define('MESSAGE_MAX_LEN', (int)tc_cfg_get($tcCfg, 'messaging.message_max_length', 4000));
tc_define('DM_MAX_LEN', (int)tc_cfg_get($tcCfg, 'messaging.dm_max_length', 4000));
tc_define('MESSAGES_PER_PAGE', (int)tc_cfg_get($tcCfg, 'messaging.messages_per_page', 30));

tc_define('UPLOAD_MAX_SIZE_DEFAULT', (int)tc_cfg_get($tcCfg, 'uploads.max_size_default', 8 * 1024 * 1024));
tc_define('UPLOAD_MAX_SIZE_VALIDATED', (int)tc_cfg_get($tcCfg, 'uploads.max_size_validated', 32 * 1024 * 1024));
tc_define('UPLOAD_MAX_SIZE_ADMIN', (int)tc_cfg_get($tcCfg, 'uploads.max_size_admin', 128 * 1024 * 1024));
tc_define('IMAGE_COMPRESS', (bool)tc_cfg_get($tcCfg, 'uploads.image_compress', true));
tc_define('IMAGE_MAX_DIM', (int)tc_cfg_get($tcCfg, 'uploads.image_max_dim', 2000));
tc_define('IMAGE_SIZE_GATE', (int)tc_cfg_get($tcCfg, 'uploads.image_size_gate', 1048576));
tc_define('IMAGE_QUALITY', (int)tc_cfg_get($tcCfg, 'uploads.image_quality', 82));
tc_define('IMAGE_THUMBS', (bool)tc_cfg_get($tcCfg, 'uploads.image_thumbs', true));
tc_define('IMAGE_THUMB_DIM', (int)tc_cfg_get($tcCfg, 'uploads.image_thumb_dim', 480));
tc_define('IMAGE_THUMB_QUALITY', (int)tc_cfg_get($tcCfg, 'uploads.image_thumb_quality', 70));

tc_define('VOICE_ENABLED', (bool)tc_cfg_get($tcCfg, 'voice.enabled', true));
tc_define('SCREEN_SHARE_ENABLED', (bool)tc_cfg_get($tcCfg, 'voice.screen_share_enabled', true));
tc_define('VOICE_MAX_PARTICIPANTS', (int)tc_cfg_get($tcCfg, 'voice.max_participants', 0));
tc_define('VOICE_SIGNAL_TTL', (int)tc_cfg_get($tcCfg, 'voice.signal_ttl', 180));
tc_define('CALL_SIGNAL_TTL', (int)tc_cfg_get($tcCfg, 'voice.call_signal_ttl', 600));
tc_define('VOICE_TIMEOUT', (int)tc_cfg_get($tcCfg, 'voice.timeout', 30));
tc_define('ONLINE_TIMEOUT', (int)tc_cfg_get($tcCfg, 'presence.online_timeout', 90));

tc_define('PWA_SHORT_NAME', (string)tc_cfg_get($tcCfg, 'pwa.short_name', SITE_NAME));
tc_define('PWA_DESCRIPTION', (string)tc_cfg_get($tcCfg, 'pwa.description', SITE_DESCRIPTION));
tc_define('PWA_THEME_COLOR', (string)tc_cfg_get($tcCfg, 'pwa.theme_color', '#2d7dff'));
tc_define('PWA_BG_COLOR', (string)tc_cfg_get($tcCfg, 'pwa.background_color', '#1e1f22'));

// Цвет системного статус-бара до загрузки JS: соответствует фону (--bg0) дефолтной темы,
// чтобы не было синей вспышки. После загрузки JS статус-бар динамически следует за выбранной темой.
$tcThemeBg = [
    'truecolor' => '#090d16',
    'vk'        => '#edeef0',
    'discord'   => '#1e1f22',
    'telegram'  => '#0e1621',
];
$tcDefTheme = strtolower((string)tc_cfg_get($tcCfg, 'project.default_theme', 'truecolor'));
tc_define('STATUS_BAR_COLOR', $tcThemeBg[$tcDefTheme] ?? '#090d16');

tc_define('DB_PATH', __DIR__ . '/' . ltrim((string)tc_cfg_get($tcCfg, 'database.path', 'truecord.db'), '/'));
tc_define('DB_BUSY_TIMEOUT', (int)tc_cfg_get($tcCfg, 'database.busy_timeout', 15000));
tc_define('DB_CACHE_SIZE', (int)tc_cfg_get($tcCfg, 'database.cache_size', -16000));
tc_define('DB_MMAP_SIZE', (int)tc_cfg_get($tcCfg, 'database.mmap_size', 134217728));
tc_define('DB_OPTIMIZE_CHANCE', (int)tc_cfg_get($tcCfg, 'database.optimize_chance', 500));
tc_define('DB_OPTIMIZE_INTERVAL', (int)tc_cfg_get($tcCfg, 'database.optimize_interval', 3600));

tc_define('SCHEMA_VERSION', 23);
tc_define('DEBUG_MODE', (bool)tc_cfg_get($tcCfg, 'security.debug_mode', false));
tc_define('CORS_ENABLED', (bool)tc_cfg_get($tcCfg, 'security.cors_enabled', false));
tc_define('CORS_ORIGIN', (string)tc_cfg_get($tcCfg, 'security.cors_origin', ''));

tc_define('ICE_SERVERS', json_encode((array)tc_cfg_get($tcCfg, 'webrtc.ice_servers', []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

tc_define('REGISTRATION_TERMS_TITLE', (string)tc_cfg_get($tcCfg, 'legal.registration_terms_title', 'Пользовательское соглашение'));
tc_define('TERMS_CHECKBOX_LABEL', (string)tc_cfg_get($tcCfg, 'legal.terms_checkbox_label', 'Я принимаю правила сервиса.'));
tc_define('REGISTRATION_MODAL_NOTE', (string)tc_cfg_get($tcCfg, 'legal.registration_modal_note', ''));
tc_define('PRIVACY_POLICY_URL', (string)tc_cfg_get($tcCfg, 'legal.privacy_policy_url', 'privacy.html'));
tc_define('REGISTRATION_TERMS_HTML', $tcTermsHtml);
