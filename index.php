<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(defined('APP_LANG') ? APP_LANG : 'en', ENT_QUOTES, 'UTF-8') ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></title>
<script>
(function(){
  try{
    if(!localStorage.getItem('truecordFirstLoadSeen')){
      document.documentElement.classList.add('tc-first-visit');
    }
  }catch(e){
    document.documentElement.classList.add('tc-first-visit');
  }
})();
</script>
<link rel="icon" href="icon_tC_main.png" type="image/png">
<script>window.APP_DEFAULT_LANG=<?= json_encode(defined('APP_LANG') ? APP_LANG : 'en') ?>;
// Подчищаем возможный старый Service Worker и его кэш, из-за которого на части
// устройств (особенно Android) «залипали» устаревшие i18n.js/index.php — отсюда
// сырые ключи settings.* в интерфейсе. Если SW не был зарегистрирован — ничего не делает.
(function(){
  try{
    if('serviceWorker' in navigator){
      navigator.serviceWorker.getRegistrations().then(function(rs){
        rs.forEach(function(r){
          // обновляем активный SW (новый sw.js v3 берёт HTML/JS из сети),
          // а совсем старые регистрации без свежего файла — удаляем
          r.update().catch(function(){});
        });
      }).catch(function(){});
    }
    if(window.caches&&caches.keys){
      caches.keys().then(function(keys){
        keys.forEach(function(k){ if(k&&k.indexOf('truecord-v3')!==0) caches.delete(k); });
      }).catch(function(){});
    }
  }catch(e){}
})();
</script>
<script src="i18n.js?v=9"></script>
<link rel="apple-touch-icon" href="icon_tC_192.png">
<link rel="manifest" href="manifest.php">
<meta name="theme-color" content="<?= htmlspecialchars(STATUS_BAR_COLOR, ENT_QUOTES, 'UTF-8') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>
<style>
/* Весь текст сайта — эмодзи как запасной шрифт */
body{
  font-family:var(--font-body),sans-serif;
}

/* Элементы где эмодзи — основные иконки интерфейса */
.srv-icon-inner,
.ws-icon,
.join-srv-icon,
.ep-em,
.ep-tab,
.ep-custom-inp,
.ctx-em-quick,
.r-pill,
.msg-audio-wrap .audio-icon,
.vr-icon,
.vp-mute-icon,
.up-avatar-inner,
.up-status-dot + *,
.sp-dot,
.call-avatar,
.call-btn,
.inp-btn,
.icon-btn,
.vb-btn,
.ch-icon,
.hdr-ch-icon,
.mb-role-icon,
.mb-av,
.dm-av,
.msg-av,
.vp-av,
.fp-btn,
.fp-icon,
.ch-act-btn,
.vr-act-btn,
.load-more-btn,
.ctx-item,
.mention-av,
.comment-av,
.banner-av,
/* БОЛЬШОЙ БЛОК (оставить как есть, просто найти его конец): */
.toast,
.np-title,
.np-text{
  font-family:var(--font-body),sans-serif;
}

/* ДОБАВИТЬ СРАЗУ ПОСЛЕ — переопределяем шрифт тостов: */
/* Тосты: обычный шрифт первым, эмодзи — запасным.
   Иначе emoji fallback рендерит цифры как эмодзи-символы */
.toast,
.np-title,
.np-text {
  font-family: var(--font-body),  sans-serif;
}
:root{
  --app-h: 100vh;      /* ← НОВОЕ: реальная высота окна, задаётся JS */
  --safe-bottom: 0px;  /* ← НОВОЕ: отступ снизу (safe area), задаётся JS */
  --bg0:#0e0c09;--bg1:#161310;--bg2:#1e1b14;--bg3:#272318;--bg4:#312c1f;--bg5:#3c3526;
  --gold:#c9aa71;--gold2:#a8884e;--gold3:#7a6032;--gold-glow:rgba(201,170,113,.18);--gold-dim:rgba(201,170,113,.08);
  --green:#4e9160;--red:#962929;--red2:#b03232;--yellow:#d4a020;--blue:#4a7fa3;
  --status-online:#4e9160;--status-away:#d4a020;--status-dnd:#b03232;--status-invisible:#5a5040;--status-offline:#3a3a3a;
  --text:#e8d5b0;--text2:#b09a6e;--text3:#7a6640;--text4:#3e3220;
  --border:#2a231a;--border2:#352d1f;
  --srv-w:68px;--ch-w:234px;--mb-w:232px;--hdr-h:48px;
  --radius:6px;--radius-sm:4px;--shadow:0 4px 32px rgba(0,0,0,.75);
  --font-body:'Inter',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
  --font-heading:'Cinzel','Georgia',serif;
  /* Liquid Glass */
  /* ── Liquid Glass ── */
  --glass-bg: rgba(14,12,9,0.65);
  --glass-panel: rgba(20,17,12,0.78);
  --glass-bg2: rgba(18,15,10,0.75);
  --glass-border: rgba(255,255,255,0.10);
  --glass-border-gold: rgba(201,170,113,0.20);
  --glass-blur: blur(40px) saturate(180%);
  --glass-blur-sm: blur(20px) saturate(150%);
  --glass-shadow: 0 12px 48px rgba(0,0,0,0.75), 0 0 0 1px rgba(255,255,255,0.06), inset 0 1px 0 rgba(255,255,255,0.08);
  --glass-shadow-sm: 0 4px 24px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.05), inset 0 1px 0 rgba(255,255,255,0.07);
  --glass-highlight: inset 0 1px 0 rgba(255,255,255,0.10), inset 0 -1px 0 rgba(0,0,0,0.25);
  --glass-tint: rgba(201,170,113,0.04);
}

/* trueCOLOR — dark mix of VK, Discord and Telegram */
[data-theme="truecolor"]{
  --bg0:#090d16;--bg1:#101827;--bg2:#151f31;--bg3:#1a2740;--bg4:#213252;--bg5:#2b4270;
  --gold:#2d7dff;--gold2:#5396ff;--gold3:#85b5ff;--gold-glow:rgba(45,125,255,.24);--gold-dim:rgba(45,125,255,.12);
  --green:#43b581;--red:#ed4245;--red2:#ff5f62;--yellow:#f0b232;--blue:#2d7dff;
  --status-online:#43b581;--status-away:#f0b232;--status-dnd:#ed4245;--status-invisible:#6e7f9f;--status-offline:#4d5a73;
  --text:#eef3ff;--text2:#c7d3eb;--text3:#8ea0c2;--text4:#5e7090;
  --border:#20304f;--border2:#2a3d60;
  --shadow:0 10px 34px rgba(3,9,20,.34);
  --font-heading:var(--font-body);
  --glass-bg:rgba(12,20,34,.74);
  --glass-panel:rgba(13,21,35,.82);
  --glass-bg2:rgba(15,25,42,.86);
  --glass-border:rgba(255,255,255,.08);
  --glass-border-gold:rgba(45,125,255,.22);
}
[data-theme="truecolor"] body,[data-theme="truecolor"] #app{background:linear-gradient(180deg,#0a0f19 0%,#0c1320 100%)!important;color:var(--text)!important}
[data-theme="truecolor"] #ctxMenu,
[data-theme="truecolor"] .toast,
[data-theme="truecolor"] #notifPanel,
[data-theme="truecolor"] #emojiPicker{
  background:rgba(16,24,39,.96)!important;border:1px solid rgba(255,255,255,.07)!important;border-radius:14px!important;
  box-shadow:0 16px 44px rgba(0,0,0,.36)!important;color:var(--text)!important;backdrop-filter:blur(22px)!important;
}
[data-theme="truecolor"] .toast.ok{border-left:3px solid #43b581!important}
[data-theme="truecolor"] .toast.err{border-left:3px solid #ff5f62!important}
[data-theme="truecolor"] .toast.info{border-left:3px solid #2d7dff!important}
[data-theme="truecolor"] #serverBar{background:linear-gradient(180deg,#0d1522 0%,#0b111d 100%)!important;border-right:1px solid rgba(255,255,255,.06)!important}
[data-theme="truecolor"] #chSidebar{background:rgba(15,22,35,.98)!important;border-right:1px solid rgba(255,255,255,.06)!important}
[data-theme="truecolor"] #mainArea,[data-theme="truecolor"] #memberSidebar{background:linear-gradient(180deg,#121b2b 0%,#101827 100%)!important}
[data-theme="truecolor"] #sidebarHeader,[data-theme="truecolor"] #mainHeader,[data-theme="truecolor"] #inputOuter,[data-theme="truecolor"] #dmInputOuter{background:rgba(16,24,39,.88)!important;border-color:rgba(255,255,255,.06)!important;backdrop-filter:blur(18px)!important}
[data-theme="truecolor"] .ch-item,[data-theme="truecolor"] .dm-item,[data-theme="truecolor"] .mb-item{color:var(--text3)!important}
[data-theme="truecolor"] .ch-item:hover,[data-theme="truecolor"] .dm-item:hover,[data-theme="truecolor"] .mb-item:hover{background:rgba(45,125,255,.10)!important;color:var(--text)!important}
[data-theme="truecolor"] .ch-item.active,[data-theme="truecolor"] .dm-item.active,[data-theme="truecolor"] .mention-item.active{background:linear-gradient(90deg,rgba(45,125,255,.18),rgba(132,94,247,.10))!important;color:var(--text)!important;box-shadow:inset 0 0 0 1px rgba(45,125,255,.12)!important}
[data-theme="truecolor"] .srv-icon.active{border-color:#2d7dff!important;box-shadow:0 0 0 2px rgba(45,125,255,.18)!important}
[data-theme="truecolor"] .srv-icon:hover{border-color:#85b5ff!important}
[data-theme="truecolor"] .srv-indicator,[data-theme="truecolor"] .srv-icon.active .srv-indicator{background:#2d7dff!important}
[data-theme="truecolor"] .btn-gold{background:linear-gradient(135deg,#2d7dff,#5b6cff)!important;color:#fff!important;border:none!important;border-radius:12px!important;box-shadow:0 10px 24px rgba(45,125,255,.24)!important}
[data-theme="truecolor"] .btn-gold:hover{background:linear-gradient(135deg,#5396ff,#7380ff)!important}
[data-theme="truecolor"] .fi{background:rgba(8,14,26,.78)!important;border:1px solid rgba(120,150,210,.16)!important;border-radius:12px!important;color:var(--text)!important}
[data-theme="truecolor"] .fi:focus{border-color:rgba(45,125,255,.56)!important;box-shadow:0 0 0 3px rgba(45,125,255,.16)!important}
[data-theme="truecolor"] .fi::placeholder{color:#7385a7!important}
[data-theme="truecolor"] .terms-scroll{background:rgba(8,14,26,.72)!important;border:1px solid rgba(120,150,210,.12)!important;color:var(--text2)!important}
[data-theme="truecolor"] #voiceBar{background:rgba(12,20,34,.88)!important;border-top:1px solid rgba(45,125,255,.18)!important;box-shadow:0 -8px 30px rgba(0,0,0,.18)!important;backdrop-filter:blur(18px)!important}
[data-theme="truecolor"] .vb-btn{background:rgba(255,255,255,.04)!important;border:1px solid rgba(255,255,255,.06)!important;color:var(--text2)!important;border-radius:12px!important}
[data-theme="truecolor"] .vb-btn.active{background:rgba(45,125,255,.18)!important;border-color:rgba(45,125,255,.28)!important;color:#fff!important}
[data-theme="truecolor"] #floatingPlayer,[data-theme="truecolor"] #streamViewer{background:rgba(9,15,26,.94)!important;border:1px solid rgba(255,255,255,.07)!important;border-radius:18px!important;box-shadow:0 18px 50px rgba(0,0,0,.42)!important}
[data-theme="truecolor"] .modal{background:rgba(15,22,35,.96)!important;border:1px solid rgba(255,255,255,.06)!important;border-radius:18px!important;backdrop-filter:blur(22px)!important;box-shadow:0 18px 50px rgba(0,0,0,.38)!important}
[data-theme="truecolor"] h1,[data-theme="truecolor"] h2,[data-theme="truecolor"] h3,[data-theme="truecolor"] .modal h2,[data-theme="truecolor"] .srv-title,[data-theme="truecolor"] .hdr-ch-name,[data-theme="truecolor"] .up-name{font-family:var(--font-body)!important}

/* Discord token map: removes legacy gold/Morrowind colors when Discord theme is active. */
[data-theme="discord"]{
  --bg0:#1e1f22;--bg1:#2b2d31;--bg2:#313338;--bg3:#383a40;--bg4:#404249;--bg5:#4e5058;
  --gold:#5865f2;--gold2:#4752c4;--gold3:#3b45b6;--gold-glow:rgba(88,101,242,.20);--gold-dim:rgba(88,101,242,.12);
  --green:#23a55a;--red:#da373c;--red2:#ed4245;--yellow:#f0b232;--blue:#5865f2;
  --status-online:#23a55a;--status-away:#f0b232;--status-dnd:#ed4245;--status-invisible:#747f8d;--status-offline:#80848e;
  --text:#f2f3f5;--text2:#dbdee1;--text3:#949ba4;--text4:#6d6f78;
  --border:#3f4147;--border2:#4e5058;
  --font-heading:var(--font-body);
  --shadow:0 8px 24px rgba(0,0,0,.32);
}
[data-theme="discord"] ::-webkit-scrollbar-thumb{background:#4e5058!important;border-radius:4px!important}
[data-theme="discord"] ::-webkit-scrollbar-thumb:hover{background:#6d6f78!important}
[data-theme="discord"] .terms-scroll{color:#b5bac1!important;line-height:1.65!important}
[data-theme="discord"] .terms-scroll h4,
[data-theme="discord"] .terms-scroll strong{color:#dbdee1!important;font-family:var(--font-body)!important}
[data-theme="discord"] .privacy-note,
[data-theme="discord"] .info-block,
[data-theme="discord"] .login-tabs{border-color:rgba(255,255,255,.08)!important}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;-webkit-text-size-adjust:100%}
body{font-family:var(--font-body),sans-serif;background:var(--bg0);color:var(--text);font-size:15px;line-height:1.5;-webkit-font-smoothing:antialiased}

/* ── Подсветка краёв экрана при разговоре в голосовом ── */
#voiceGlow{position:fixed;inset:0;z-index:0;pointer-events:none}
#voiceGlow .vglow{position:absolute;left:0;right:0;height:24vh;opacity:0;transition:opacity .4s ease}
#voiceGlow .vglow-top{top:0;background:radial-gradient(120% 80% at 50% 0%,color-mix(in srgb,var(--green) 38%,transparent),transparent 72%)}
#voiceGlow .vglow-bottom{bottom:0;background:radial-gradient(120% 80% at 50% 100%,color-mix(in srgb,var(--gold) 42%,transparent),transparent 72%)}
body.vglow-remote #voiceGlow .vglow-top{opacity:.26;animation:vglowPulse 2.6s ease-in-out infinite}
body.vglow-local #voiceGlow .vglow-bottom{opacity:.3;animation:vglowPulse 2.6s ease-in-out infinite}
@keyframes vglowPulse{0%,100%{opacity:.16}50%{opacity:.32}}
@media(prefers-reduced-motion:reduce){body.vglow-remote #voiceGlow .vglow-top,body.vglow-local #voiceGlow .vglow-bottom{animation:none}}
/* ── Динамический фон (GPU-LIGHT): мягкие цветные пятна.
   Оптимизации против нагрузки на GPU:
   • убран filter:blur(70px) — размытие фильтром пересчитывается каждый кадр
     и стоит O(radius²) на пиксель; вместо него «мягкость» уже зашита в сам
     radial-gradient (плавный спад до transparent), это статичная отрисовка;
   • убран mix-blend-mode:screen — он заставляет браузер каждый кадр
     композитить весь слой во внеэкранный буфер (самый тяжёлый пункт);
   • анимируется только transform (translate3d) — это работа композитора
     на GPU без перерисовки слоёв (paint);
   • меньше пятен и медленнее анимация → меньше перерисовок. */
#dynBg{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:var(--bg0)}
/* Переключатель в настройках */
.settings-toggle-row{display:flex;align-items:center;justify-content:space-between;gap:14px;cursor:pointer;width:100%}
.settings-toggle-copy{display:flex;flex-direction:column;gap:2px;min-width:0}
.settings-toggle-title{font-size:14px;font-weight:700;color:var(--text)}
.settings-toggle-sub{font-size:12px;color:var(--text3)}
.settings-switch{position:relative;flex-shrink:0;width:46px;height:26px}
.settings-switch input{position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer}
.settings-switch-track{position:absolute;inset:0;border-radius:99px;background:var(--bg4);border:1px solid var(--border);transition:background .18s,border-color .18s}
.settings-switch-track::after{content:'';position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:var(--text3);transition:transform .18s,background .18s}
.settings-switch input:checked + .settings-switch-track{background:color-mix(in srgb,var(--gold) 30%,var(--bg4));border-color:color-mix(in srgb,var(--gold) 45%,transparent)}
.settings-switch input:checked + .settings-switch-track::after{transform:translateX(20px);background:var(--gold)}
#dynBg .dyn-blob{position:absolute;border-radius:50%;opacity:.5;will-change:transform;transform:translateZ(0);backface-visibility:hidden}
/* Мягкость зашита в градиент (спад до transparent на ~70%), без filter:blur. */
.dyn-blob.db1{width:60vw;height:60vw;background:radial-gradient(circle at 50% 50%,color-mix(in srgb,var(--gold) 60%,transparent),transparent 70%);top:-14vw;left:-10vw;animation:dynDrift1 48s ease-in-out infinite alternate}
.dyn-blob.db2{width:54vw;height:54vw;background:radial-gradient(circle at 50% 50%,color-mix(in srgb,var(--blue) 55%,transparent),transparent 70%);bottom:-16vw;right:-10vw;animation:dynDrift2 60s ease-in-out infinite alternate}
.dyn-blob.db3{width:46vw;height:46vw;background:radial-gradient(circle at 50% 50%,color-mix(in srgb,var(--green) 45%,transparent),transparent 70%);top:24%;left:38%;animation:dynDrift3 72s ease-in-out infinite alternate}
/* translate3d — анимация на композиторе (GPU), без paint/layout. */
@keyframes dynDrift1{0%{transform:translate3d(0,0,0) scale(1)}100%{transform:translate3d(8vw,10vh,0) scale(1.08)}}
@keyframes dynDrift2{0%{transform:translate3d(0,0,0) scale(1.05)}100%{transform:translate3d(-9vw,-7vh,0) scale(.96)}}
@keyframes dynDrift3{0%{transform:translate3d(0,0,0) scale(1)}100%{transform:translate3d(6vw,-8vh,0) scale(1.1)}}
/* 4-е пятно убрано для снижения числа крупных слоёв. */
.dyn-blob.db4{display:none}
/* Светлая тема — мягче. */
[data-theme="vk"] #dynBg .dyn-blob{opacity:.28}
/* Когда фон выключен пользователем — показываем обычный фон темы */
body.dynbg-off #dynBg{display:none}
/* Уважаем системную настройку «меньше движения» — статичные пятна без анимации */
@media(prefers-reduced-motion:reduce){#dynBg .dyn-blob{animation:none}}
/* ── Стеклянные несущие панели (когда динамический фон включён) ── */
body:not(.dynbg-off) #chSidebar,
body:not(.dynbg-off) #memberSidebar,
body:not(.dynbg-off) #mainArea,
body:not(.dynbg-off) #mainHeader,
body:not(.dynbg-off) #serverBar,
body:not(.dynbg-off) #voiceBar{
  background:var(--glass-panel)!important;
  backdrop-filter:blur(26px) saturate(1.35)!important;
  -webkit-backdrop-filter:blur(26px) saturate(1.35)!important;
}
body:not(.dynbg-off) #chSidebar{border-right:1px solid var(--glass-border)!important}
body:not(.dynbg-off) #memberSidebar{border-left:1px solid var(--glass-border)!important}
body:not(.dynbg-off) #mainHeader{border-bottom:1px solid var(--glass-border)!important}
body:not(.dynbg-off) #serverBar{border-right:1px solid var(--glass-border)!important}
/* mainArea прозрачнее — сквозь него лучше видно фон за лентой */
body:not(.dynbg-off) #mainArea{background:color-mix(in srgb,var(--glass-panel) 60%,transparent)!important}
/* Сообщения/лента не размываем (текст должен быть резким), фон уже даёт глубину */
body:not(.dynbg-off) #messagesWrap{background:transparent!important}
/* ── Плавающий «остров» поля ввода ──────────────────────────────
   Поле ввода всегда парит над лентой (для всех тем), а сообщения
   уезжают под него. Раньше это работало только при включённом
   динамическом фоне; теперь — всегда. */
#chView{position:relative}
/* Контейнер ввода прижат к низу. По умолчанию (без динам. фона) под
   островом сплошная подложка цвета ленты — сообщения НЕ просвечивают.
   Цвет подложки совпадает с фоном ленты в каждой теме (см. ниже). */
#inputOuter{position:absolute;left:0;right:0;bottom:0;z-index:5;background:var(--bg2)}
/* truecolor (тёмная): лента — градиент, его нижний цвет #101827 */
[data-theme="truecolor"] #inputOuter,
[data-theme="truecolor"] #typingBar{background:#101827}
/* vk (светлая): лента #edeef0 */
[data-theme="vk"] #inputOuter,
[data-theme="vk"] #typingBar{background:#edeef0}
/* typingBar (полоска «печатает…») красим в цвет ленты, чтобы он сливался
   с фоном и при этом перекрывал сообщения под собой. */
#typingBar{background:var(--bg2)}
/* При включённом динамическом фоне подложку убираем — остров «стеклянный»,
   и сквозь него специально виден размытый фон (glassmorphism). */
body:not(.dynbg-off) #inputOuter{background:transparent!important}
#messagesWrap{padding-bottom:calc(var(--composer-h,84px) + 8px)!important}
#typingBar{position:absolute;left:0;right:0;bottom:calc(var(--composer-h,84px) - 2px);z-index:5;pointer-events:none}
body:not(.dynbg-off) #typingBar{background:transparent!important}
@media(prefers-reduced-motion:reduce){#dynBg .dyn-blob{animation:none}}
/* Приложение и лоадер — поверх фона */
#app,#firstVisitLoader,#loginScreen,#modalBg{position:relative;z-index:1}
h1,h2,h3,.modal h2,.srv-title,.hdr-ch-name,.up-name{font-family:var(--font-heading)}
::-webkit-scrollbar{width:4px;height:4px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:var(--bg5);border-radius:2px}::-webkit-scrollbar-thumb:hover{background:var(--gold3)}
*{scrollbar-width:thin;scrollbar-color:var(--bg5) transparent}
.st-online{background:var(--status-online)!important}.st-away{background:var(--status-away)!important}.st-dnd{background:var(--status-dnd)!important}.st-invisible{background:var(--status-invisible)!important}.st-offline{background:var(--status-offline)!important}

/* ══ Discord theme overrides ══ */
[data-theme="discord"] .modal{
  background:rgba(49,51,56,0.95)!important;border:1px solid rgba(255,255,255,0.06)!important;
  border-radius:8px!important;backdrop-filter:blur(20px)!important;
  box-shadow:0 8px 32px rgba(0,0,0,.5)!important;
}
[data-theme="discord"] #ctxMenu{background:#2b2d31!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:4px!important;box-shadow:0 8px 16px rgba(0,0,0,.3)!important}
[data-theme="discord"] .toast{background:#2b2d31!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:4px!important}
[data-theme="discord"] .toast.ok{border-left:3px solid #23a55a!important}
[data-theme="discord"] .toast.err{border-left:3px solid #ed4245!important}
[data-theme="discord"] .toast.info{border-left:3px solid #5865f2!important}
[data-theme="discord"] .login-wrap{border-radius:8px!important;border:none!important;background:#313338!important}
[data-theme="discord"] .login-info{background:linear-gradient(135deg,#2b2d31,#1e1f22)!important;border-right:1px solid rgba(255,255,255,0.06)!important}
[data-theme="discord"] .login-info h1{color:#5865f2!important}
[data-theme="discord"] .btn-gold{background:#5865f2!important;color:#fff!important;border-radius:3px!important}
[data-theme="discord"] .btn-gold:hover{background:#4752c4!important}
[data-theme="discord"] .fi{background:#1e1f22!important;border:none!important;border-radius:3px!important}
[data-theme="discord"] .fi:focus{box-shadow:0 0 0 2px rgba(88,101,242,0.4)!important}
[data-theme="discord"] #loginScreen{background:radial-gradient(circle at top,#5865f226 0,transparent 35%),linear-gradient(180deg,#1e1f22 0%,#111214 100%)!important}
[data-theme="discord"] .login-wrap{max-width:880px!important;box-shadow:0 18px 60px rgba(0,0,0,.38)!important}
[data-theme="discord"] .login-info{background:linear-gradient(180deg,#2b2d31 0%,#232428 100%)!important;padding:32px 28px!important}
[data-theme="discord"] .login-form{background:#313338!important;padding:32px 28px!important}
[data-theme="discord"] .login-info h1{color:#f2f3f5!important;font-size:30px!important;letter-spacing:.01em!important}
[data-theme="discord"] .login-info .tagline{color:#b5bac1!important;font-size:14px!important}
[data-theme="discord"] .info-block h3{color:#b5bac1!important;font-family:var(--font-body)!important;font-size:12px!important;letter-spacing:.04em!important}
[data-theme="discord"] .info-block p{color:#dbdee1!important}
[data-theme="discord"] .info-links a{color:#a5b4fc!important;border-bottom-color:#5865f2!important}
[data-theme="discord"] .privacy-note{color:#949ba4!important;border-top:1px solid rgba(255,255,255,0.08)!important}
[data-theme="discord"] .login-tab{color:#949ba4!important}
[data-theme="discord"] .login-tab.active{border-color:#5865f2!important;color:#f2f3f5!important}
[data-theme="discord"] .login-tab:hover:not(.active){color:#dbdee1!important}
[data-theme="discord"] .fl{color:#b5bac1!important;font-family:var(--font-body)!important;font-size:12px!important;letter-spacing:.04em!important}
[data-theme="discord"] .fi{color:#f2f3f5!important}
[data-theme="discord"] .fi::placeholder{color:#949ba4!important}
[data-theme="discord"] .terms-check span,[data-theme="discord"] .remember-check span{color:#dbdee1!important}
[data-theme="discord"] #notifPanel{background:#2b2d31!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:8px!important}
[data-theme="discord"] .terms-scroll{background:#1e1f22!important;border:none!important}
[data-theme="discord"] #emojiPicker{background:#2b2d31!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:8px!important}
[data-theme="discord"] #voiceBar{background:rgba(43,45,49,0.85)!important;border-top:1px solid rgba(88,101,242,0.25)!important;box-shadow:none!important;backdrop-filter:blur(12px)!important}
[data-theme="discord"] .vb-btn{border-radius:4px!important}
[data-theme="discord"] .srv-icon.active{border-color:#5865f2!important}
[data-theme="discord"] .srv-icon:hover{border-color:#4752c4!important}
[data-theme="discord"] .srv-indicator{background:#5865f2!important}
[data-theme="discord"] .srv-icon.active .srv-indicator{background:#5865f2!important}
[data-theme="discord"] h1,[data-theme="discord"] h2,[data-theme="discord"] h3,[data-theme="discord"] .modal h2,[data-theme="discord"] .srv-title,[data-theme="discord"] .hdr-ch-name,[data-theme="discord"] .up-name{font-family:var(--font-body)!important}
[data-theme="discord"] #floatingPlayer{background:rgba(43,45,49,0.92)!important;border-radius:8px!important}
[data-theme="discord"] #sidebarHeader{background:#2b2d31!important}
[data-theme="discord"] #miniAppModal .mini-app-header{background:#2b2d31!important}
[data-theme="discord"] #streamViewer{background:#1e1f22!important}
[data-theme="discord"] .ch-item.active{background:#404249!important}
[data-theme="discord"] .mention-item.active{background:#404249!important}

/* ══ Telegram theme overrides ══ */
[data-theme="telegram"] .modal{
  background:rgba(23,33,43,0.96)!important;border:1px solid rgba(255,255,255,0.06)!important;
  border-radius:12px!important;backdrop-filter:blur(24px)!important;
  box-shadow:0 8px 40px rgba(0,0,0,.5)!important;
}
[data-theme="telegram"] #ctxMenu{background:#17212b!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:10px!important;box-shadow:0 4px 24px rgba(0,0,0,.4)!important}
[data-theme="telegram"] .toast{background:#17212b!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:10px!important}
[data-theme="telegram"] .toast.ok{border-left:3px solid #4fae4e!important}
[data-theme="telegram"] .toast.err{border-left:3px solid #ef5350!important}
[data-theme="telegram"] .toast.info{border-left:3px solid #6ab2f2!important}
[data-theme="telegram"] .login-wrap{border-radius:12px!important;border:none!important;background:#17212b!important}
[data-theme="telegram"] .login-info{background:linear-gradient(135deg,#1b2836,#0e1621)!important;border-right:1px solid rgba(255,255,255,0.06)!important}
[data-theme="telegram"] .login-info h1{color:#6ab2f2!important}
[data-theme="telegram"] .btn-gold{background:#6ab2f2!important;color:#fff!important;border-radius:20px!important}
[data-theme="telegram"] .btn-gold:hover{background:#4a9ae0!important}
[data-theme="telegram"] .fi{background:#0e1621!important;border:1px solid rgba(106,178,242,0.15)!important;border-radius:10px!important}
[data-theme="telegram"] .fi:focus{border-color:rgba(106,178,242,0.5)!important}
[data-theme="telegram"] #notifPanel{background:#17212b!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:12px!important}
[data-theme="telegram"] .terms-scroll{background:#0e1621!important;border:1px solid rgba(106,178,242,0.1)!important}
[data-theme="telegram"] #emojiPicker{background:#17212b!important;border:1px solid rgba(255,255,255,0.06)!important;border-radius:12px!important}
[data-theme="telegram"] #voiceBar{background:rgba(23,33,43,0.88)!important;border-top:1px solid rgba(106,178,242,0.2)!important;box-shadow:none!important;backdrop-filter:blur(14px)!important}
[data-theme="telegram"] .vb-btn{border-radius:50%!important}
[data-theme="telegram"] .srv-icon.active{border-color:#6ab2f2!important}
[data-theme="telegram"] .srv-icon:hover{border-color:#4a9ae0!important}
[data-theme="telegram"] .srv-indicator{background:#6ab2f2!important}
[data-theme="telegram"] .srv-icon.active .srv-indicator{background:#6ab2f2!important}
[data-theme="telegram"] h1,[data-theme="telegram"] h2,[data-theme="telegram"] h3,[data-theme="telegram"] .modal h2,[data-theme="telegram"] .srv-title,[data-theme="telegram"] .hdr-ch-name,[data-theme="telegram"] .up-name{font-family:var(--font-body)!important}
[data-theme="telegram"] #floatingPlayer{background:rgba(23,33,43,0.92)!important;border-radius:16px!important}
[data-theme="telegram"] #sidebarHeader{background:#17212b!important}
[data-theme="telegram"] #miniAppModal .mini-app-header{background:#17212b!important}
[data-theme="telegram"] #streamViewer{background:#0e1621!important}
[data-theme="telegram"] .ch-item.active{background:#2b3945!important}
[data-theme="telegram"] .mention-item.active{background:#2b3945!important}
[data-theme="telegram"] .msg-text{line-height:1.65!important}
[data-theme="telegram"] #inputBar{border-radius:0 0 12px 12px!important}
[data-theme="telegram"] #msgInput{border-radius:20px!important}


/* ══ VK theme overrides ══ */
[data-theme="vk"]{
  color-scheme:light;
}
[data-theme="vk"] body,[data-theme="vk"] #loginScreen{background:#edeef0!important;color:#000!important}
[data-theme="vk"] .modal{
  background:#fff!important;border:1px solid #dce1e6!important;border-radius:12px!important;
  box-shadow:0 10px 38px rgba(0,0,0,.14)!important;color:#000!important;
}
[data-theme="vk"] #ctxMenu,[data-theme="vk"] .toast,[data-theme="vk"] #notifPanel,[data-theme="vk"] #emojiPicker{background:#fff!important;border:1px solid #dce1e6!important;border-radius:10px!important;box-shadow:0 8px 28px rgba(0,0,0,.14)!important;color:#000!important}
[data-theme="vk"] .toast.ok{border-left:3px solid #4bb34b!important}
[data-theme="vk"] .toast.err{border-left:3px solid #e64646!important}
[data-theme="vk"] .toast.info{border-left:3px solid #0077ff!important}
[data-theme="vk"] .login-wrap{border-radius:14px!important;border:1px solid #dce1e6!important;background:#fff!important;box-shadow:0 12px 42px rgba(0,0,0,.12)!important}
[data-theme="vk"] .login-info{background:#f5f7fa!important;border-right:1px solid #dce1e6!important}
[data-theme="vk"] .login-form{background:#fff!important}
[data-theme="vk"] .login-info h1{color:#000!important;font-family:var(--font-body)!important}
[data-theme="vk"] .login-info .tagline,[data-theme="vk"] .info-block p,[data-theme="vk"] .privacy-note{color:#626d7a!important}
[data-theme="vk"] .info-block h3,[data-theme="vk"] .fl{color:#818c99!important;font-family:var(--font-body)!important}
[data-theme="vk"] .info-links a{color:#0077ff!important;border-bottom-color:#0077ff!important}
[data-theme="vk"] .btn-gold{background:#0077ff!important;color:#fff!important;border:none!important;border-radius:8px!important}
[data-theme="vk"] .btn-gold:hover{background:#006be6!important}
[data-theme="vk"] .btn-ghost{background:#f0f2f5!important;color:#2c2d2e!important;border:1px solid #dce1e6!important}
[data-theme="vk"] .btn-red{background:#e64646!important;color:#fff!important;border:none!important}
[data-theme="vk"] .fi{background:#fff!important;border:1px solid #dce1e6!important;border-radius:8px!important;color:#000!important}
[data-theme="vk"] .fi:focus{border-color:#0077ff!important;box-shadow:0 0 0 3px rgba(0,119,255,.15)!important}
[data-theme="vk"] .fi::placeholder{color:#99a2ad!important}
[data-theme="vk"] .login-tab{color:#818c99!important}
[data-theme="vk"] .login-tab.active{border-color:#0077ff!important;color:#000!important}
[data-theme="vk"] .terms-scroll{background:#f5f7fa!important;border:1px solid #dce1e6!important;color:#626d7a!important}
[data-theme="vk"] #serverBar{background:#fff!important;border-right:1px solid #dce1e6!important;border-top-color:#dce1e6!important}
[data-theme="vk"] #chSidebar,[data-theme="vk"] #memberSidebar,[data-theme="vk"] #mainArea{background:#edeef0!important;color:#000!important}
[data-theme="vk"] #sidebarHeader,[data-theme="vk"] #mainHeader,[data-theme="vk"] #inputOuter,[data-theme="vk"] #dmInputOuter{background:#fff!important;border-color:#dce1e6!important;color:#000!important}
[data-theme="vk"] .ch-item,[data-theme="vk"] .dm-item,[data-theme="vk"] .mb-item{color:#626d7a!important}
[data-theme="vk"] .ch-item:hover,[data-theme="vk"] .dm-item:hover,[data-theme="vk"] .mb-item:hover{background:#f0f2f5!important;color:#000!important}
[data-theme="vk"] .ch-item.active,[data-theme="vk"] .dm-item.active{background:#e5ebf1!important;color:#000!important}
[data-theme="vk"] .srv-icon.active{border-color:#0077ff!important}
[data-theme="vk"] .srv-icon:hover{border-color:#99c8ff!important}
[data-theme="vk"] .srv-indicator,[data-theme="vk"] .srv-icon.active .srv-indicator{background:#0077ff!important}
[data-theme="vk"] .msg-bubble,[data-theme="vk"] .msg-content,[data-theme="vk"] .dm-msg{color:#000!important}
[data-theme="vk"] #voiceBar{background:rgba(255,255,255,.94)!important;border-top:1px solid #dce1e6!important;box-shadow:0 -4px 18px rgba(0,0,0,.08)!important;backdrop-filter:blur(12px)!important}
[data-theme="vk"] .vb-btn{background:#f0f2f5!important;border:1px solid #dce1e6!important;color:#626d7a!important;border-radius:10px!important}
[data-theme="vk"] .vb-btn.active{background:#e5f2ff!important;border-color:#99c8ff!important;color:#0077ff!important}
[data-theme="vk"] #miniAppModal .mini-app-header{background:#fff!important;border-color:#dce1e6!important}
[data-theme="vk"] #streamViewer,[data-theme="vk"] #floatingPlayer{background:#fff!important;border-color:#dce1e6!important;color:#000!important}
/* VK (white) theme: the floating music player uses light text by default — make everything dark & readable. */
[data-theme="vk"] #floatingPlayer .fp-label-text{color:#5a6470!important}
[data-theme="vk"] #floatingPlayer .fp-hbtn{background:#eef1f5!important;color:#5a6470!important}
[data-theme="vk"] #floatingPlayer .fp-hbtn:hover{background:#e2e7ee!important;color:#1c1c1e!important}
[data-theme="vk"] #floatingPlayer .fp-art{background:#eef1f5!important}
[data-theme="vk"] #floatingPlayer .fp-track-name{color:#1c1c1e!important}
[data-theme="vk"] #floatingPlayer .fp-track-sub,
[data-theme="vk"] #floatingPlayer .fp-track-mini-name{color:#7a828c!important}
[data-theme="vk"] #floatingPlayer .fp-dl-btn{background:#eef1f5!important;border-color:#dce1e6!important;color:#5a6470!important}
[data-theme="vk"] #floatingPlayer .fp-dl-btn:hover{background:#dbeeff!important;color:#0077ff!important}
[data-theme="vk"] #floatingPlayer .fp-time-row{color:#7a828c!important}
[data-theme="vk"] #floatingPlayer .fp-progress{background:#e2e7ee!important}
[data-theme="vk"] #floatingPlayer .fp-progress-bar{background:linear-gradient(90deg,#3a8dff,#0077ff)!important}
[data-theme="vk"] #floatingPlayer .fp-progress-bar::after{background:#0077ff!important}
[data-theme="vk"] #floatingPlayer .fp-btn{background:#eef1f5!important;border-color:#dce1e6!important;color:#3a4250!important}
[data-theme="vk"] #floatingPlayer .fp-btn:hover{background:#e2e7ee!important;color:#1c1c1e!important;border-color:#cfd6df!important}
[data-theme="vk"] #floatingPlayer .fp-btn.fp-play{background:#dbeeff!important;border-color:#9ecbff!important;color:#0077ff!important}
[data-theme="vk"] #floatingPlayer .fp-btn.active{color:#0077ff!important;border-color:#9ecbff!important;background:#eaf4ff!important}
[data-theme="vk"] #floatingPlayer .fp-vol-icon{color:#7a828c!important}
[data-theme="vk"] #floatingPlayer .fp-vol-icon:hover{color:#3a4250!important}
[data-theme="vk"] #floatingPlayer .fp-vol-slider{accent-color:#0077ff!important}
[data-theme="vk"] #floatingPlayer .fp-vol-pct{color:#7a828c!important}
[data-theme="vk"] #floatingPlayer #fpAurora{opacity:.25!important}
[data-theme="vk"] h1,[data-theme="vk"] h2,[data-theme="vk"] h3,[data-theme="vk"] .modal h2,[data-theme="vk"] .srv-title,[data-theme="vk"] .hdr-ch-name,[data-theme="vk"] .up-name{font-family:var(--font-body)!important;color:#000!important}

/* ══════════════════════════════════════════════════════
   TES3-ICONS — iOS-style SVG icon system
   Использует CSS mask + background-color для тонирования
══════════════════════════════════════════════════════ */
.ti{
  display:inline-flex;align-items:center;justify-content:center;
  flex-shrink:0;line-height:1;
}
.ti svg{
  width:18px;height:18px;
  display:block;flex-shrink:0;
}
/* Размеры */
.ti-sm svg{width:.85em;height:.85em;}
.ti-lg svg{width:1.25em;height:1.25em;}
.ti-xl svg{width:1.5em;height:1.5em;}

/* БЫЛО: height:100vh;height:100dvh; */
#app{display:flex;height:100vh;height:var(--app-h,100vh);width:100vw;overflow:hidden;position:fixed;inset:0}
/* SERVER BAR */
#serverBar{
  width:var(--srv-w);min-width:var(--srv-w);
  background:var(--bg0);
  display:flex;flex-direction:column;align-items:center;padding:8px 0;gap:4px;
  overflow-y:auto;overflow-x:visible;
  border-right:1px solid var(--border);
  z-index:20;flex-shrink:0
}
/* display:contents ломается в Safari iOS/iPadOS (дети исчезают из рендера) — используем flex */
#srvIcons{display:flex;flex-direction:column;align-items:center;gap:4px}
.srv-icon{width:48px;height:48px;aspect-ratio:1/1;border-radius:16px;cursor:pointer;position:relative;flex-shrink:0;flex-grow:0;overflow:visible;transition:border-radius .2s;border:2px solid transparent;display:flex;align-items:center;justify-content:center;box-sizing:border-box}
.srv-icon-inner{
  border-radius:inherit;overflow:hidden;
  background:var(--bg2);
  border:none;
  display:flex;align-items:center;justify-content:center;font-size:20px;
  color:var(--text);
  transition:border-radius .2s,background .15s,filter .2s,opacity .2s;
  flex-shrink:0;
  /* Always a perfect square sized by the shortest side of the icon, never a rectangle; image fills the whole rounded frame. */
  width:100%;height:100%;aspect-ratio:1/1;box-sizing:border-box;
}
.srv-icon-inner img{width:100%;height:100%;aspect-ratio:1/1;object-fit:cover;object-position:center;border-radius:inherit;display:block;pointer-events:none;-webkit-user-drag:none;user-select:none}
.srv-icon:hover .srv-icon-inner{border-radius:14px;background:var(--gold-glow)}
.srv-icon.active .srv-icon-inner{border-radius:14px;background:var(--gold-dim)}
.srv-icon.active{border-color:var(--gold);border-radius:14px}
.srv-icon:hover{border-color:var(--gold3);border-radius:14px}
.srv-icon.not-member .srv-icon-inner{filter:grayscale(1);opacity:.45}
.srv-icon.not-member:hover .srv-icon-inner{filter:grayscale(.5);opacity:.7}
.srv-icon.kicked-srv .srv-icon-inner{filter:grayscale(1);opacity:.35}
.srv-indicator{position:absolute;left:-10px;top:50%;transform:translateY(-50%);width:4px;background:var(--text);border-radius:0 4px 4px 0;height:0;transition:height .15s;z-index:1}
.srv-icon.active .srv-indicator{height:36px;background:var(--gold)}
.srv-icon:hover .srv-indicator{height:18px}
.srv-notif-dot{position:absolute;top:-5px;right:-5px;min-width:16px;height:16px;border-radius:8px;background:var(--red2);border:2px solid var(--bg0);display:none;font-size:9px;font-weight:700;color:#fff;align-items:center;justify-content:center;padding:0 3px;z-index:10;pointer-events:none}
.srv-notif-dot.mention-dot{background:var(--gold);color:var(--bg0)}
.srv-voice-dot{position:absolute;bottom:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:var(--green);border:2px solid var(--bg0);display:none;z-index:11;pointer-events:none;animation:voiceDotPulse 2s ease-in-out infinite}
.srv-voice-dot.show{display:block}
/* Drag & drop reordering visuals */
.srv-icon[draggable="true"]{cursor:grab}
.srv-icon.srv-dragging{opacity:.55;transform:scale(.92);cursor:grabbing}
.srv-icon.srv-drop-before{position:relative}
/* Aura ring around server icons: green = voice active in that server, red (pulsing) = unread mention. */
.srv-icon.srv-voice-ring .srv-icon-inner{box-shadow:0 0 0 2px var(--bg0),0 0 0 4px #43b581,0 0 12px rgba(67,181,129,.55)}
.srv-icon.srv-mention-ring .srv-icon-inner{box-shadow:0 0 0 2px var(--bg0),0 0 0 4px #ed4245;animation:srvMentionRing 1.4s ease-in-out infinite}
/* If both voice and mention are active, the pulsing red mention ring takes priority. */
.srv-icon.srv-voice-ring.srv-mention-ring .srv-icon-inner{animation:srvMentionRing 1.4s ease-in-out infinite}
@keyframes srvMentionRing{
  0%,100%{box-shadow:0 0 0 2px var(--bg0),0 0 0 4px #ed4245,0 0 0 4px rgba(237,66,69,.55)}
  50%{box-shadow:0 0 0 2px var(--bg0),0 0 0 4px #ed4245,0 0 0 9px rgba(237,66,69,0)}
}
@keyframes voiceDotPulse{0%,100%{transform:scale(1);box-shadow:0 0 0 0 rgba(78,145,96,.7)}50%{transform:scale(1.15);box-shadow:0 0 0 4px rgba(78,145,96,0)}}
.srv-sep{width:32px;height:1px;background:var(--border2);flex-shrink:0;margin:2px 0}
/* Иконки управления в server bar */
.srv-admin-btn{
  width:36px;height:36px;border-radius:10px;
  background:rgba(74,127,163,.12);border:1px solid rgba(74,127,163,.25);
  cursor:pointer;display:flex;align-items:center;justify-content:center;
  color:var(--blue);transition:.15s;flex-shrink:0;
}
.srv-admin-btn:hover{background:rgba(74,127,163,.28);border-color:rgba(74,127,163,.5);}
.srv-admin-btn svg{width:18px;height:18px;}
.srv-superadmin-btn{
  width:36px;height:36px;border-radius:10px;
  background:rgba(255,159,67,.1);border:1px solid rgba(255,159,67,.28);
  cursor:pointer;display:flex;align-items:center;justify-content:center;
  color:#ff9f43;transition:.15s;flex-shrink:0;
  animation:superAdminGlow 3s ease-in-out infinite;
}
.srv-superadmin-btn:hover{background:rgba(255,159,67,.22);}
.srv-superadmin-btn svg{width:18px;height:18px;}
@keyframes superAdminGlow{
  0%,100%{box-shadow:0 0 0 0 rgba(255,159,67,0);}
  50%{box-shadow:0 0 8px 2px rgba(255,159,67,.2);}
}
/* CHANNEL SIDEBAR */
#chSidebar{
  width:var(--ch-w);min-width:var(--ch-w);
  background:var(--bg1);
  display:flex;flex-direction:column;overflow:hidden;z-index:15;flex-shrink:0;
  border-right:1px solid var(--border)
}
#sidebarHeader{
  min-height:var(--hdr-h);padding:0 12px;
  display:flex;align-items:center;justify-content:space-between;
  background:var(--bg1);
  border-bottom:1px solid var(--border);
  cursor:pointer;user-select:none;flex-shrink:0;transition:background .15s
}
#sidebarHeader:hover{background:var(--bg3)}
#sidebarHeader .srv-title{font-weight:700;font-size:14px;color:var(--gold);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
#sidebarHeader .gear-icon{color:var(--text3);font-size:18px;opacity:.7;transition:opacity .15s,transform .2s;flex-shrink:0}
#sidebarHeader:hover .gear-icon{opacity:1;transform:rotate(30deg)}
.ch-scroll{flex:1;overflow-y:auto;overflow-x:hidden;padding:4px 0 8px}
.ch-category{padding:16px 8px 2px 8px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;transition:color .15s;font-family:var(--font-heading)}
.ch-category:hover{color:var(--text2)}
.ch-category .cat-add{width:18px;height:18px;display:flex;align-items:center;justify-content:center;border-radius:3px;font-size:16px;opacity:0;transition:opacity .15s,background .15s}
.ch-category:hover .cat-add{opacity:1}
.ch-category .cat-add:hover{background:var(--bg4)}
.cat-add .ti svg{width:13px;height:13px;display:block;}
.ch-item{display:flex;align-items:center;gap:6px;padding:3px 6px 3px 10px;margin:1px 6px;border-radius:var(--radius-sm);cursor:pointer;color:var(--text3);transition:background .1s,color .1s;position:relative;min-height:32px}
.ch-item:hover{background:var(--bg3);color:var(--text2)}
.ch-item.active{background:var(--bg4);color:var(--text)}
.ch-item.has-unread{color:var(--text);font-weight:600}
.ch-item.muted{opacity:.45}
.ch-icon{width:20px;height:20px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px;border-radius:50%;overflow:hidden}
.ch-icon img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.ch-name{flex:1;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ch-badges{display:flex;align-items:center;gap:4px;flex-shrink:0}
.ch-unread-badge,.ch-mention-badge{background:var(--red2);color:#fff;font-size:10px;border-radius:8px;padding:1px 5px;min-width:18px;text-align:center;font-weight:600}
.ch-mention-badge{background:var(--gold);color:var(--bg0)}
.ch-unread-dot{width:8px;height:8px;border-radius:50%;background:var(--text);position:absolute;left:0;flex-shrink:0}
.ch-actions{display:none;align-items:center;gap:2px;flex-shrink:0}
.ch-item:hover .ch-actions{display:flex}
.ch-item:hover .ch-badges{display:none}
.ch-act-btn{width:22px;height:22px;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:13px;opacity:.6;transition:opacity .1s,background .1s}
.ch-act-btn:hover{opacity:1;background:var(--bg5)}
/* VOICE ROOMS */
.voice-room{display:flex;align-items:center;gap:6px;padding:3px 6px 3px 10px;margin:1px 6px;border-radius:var(--radius-sm);cursor:pointer;color:var(--text3);transition:background .1s,color .1s;min-height:32px}
.voice-room:hover{background:var(--bg3);color:var(--text2)}
.voice-room.active{background:rgba(78,145,96,.18);color:var(--green)}
.voice-room .vr-icon{width:20px;height:20px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0;}
.voice-room .vr-icon svg{width:15px;height:15px;display:block;}
.voice-room .vr-name{flex:1;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.voice-room .vr-count{font-size:11px;color:var(--text3);flex-shrink:0}
.voice-room .vr-actions{display:none;align-items:center;gap:2px;flex-shrink:0}
.voice-room:hover .vr-actions{display:flex}
.voice-room:hover .vr-count{display:none}
.vr-act-btn{width:22px;height:22px;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:13px;opacity:.6;transition:opacity .1s,background .1s}
.vr-act-btn:hover{opacity:1;background:var(--bg5)}
.voice-parts-wrap{padding-left:0;margin-bottom:2px}
.voice-part-item{display:flex;align-items:center;gap:6px;padding:2px 8px 2px 32px;margin:0 6px;border-radius:var(--radius-sm);cursor:pointer;font-size:13px;color:var(--text3);transition:background .1s,color .1s;min-height:24px}
.voice-part-item:hover{background:var(--bg3);color:var(--text2)}
.voice-part-item.is-me{color:var(--green)}
.vp-av{width:18px;height:18px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:10px;overflow:hidden;flex-shrink:0}
.vp-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.vp-name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1}
.vp-mute-icon{font-size:0;flex-shrink:0;color:var(--red2);display:flex;align-items:center;}
.vp-mute-icon svg{width:12px;height:12px;display:block;}
.voice-part-item.force-muted{opacity:.6}
.voice-part-item.force-muted .vp-mute-icon{color:#ff4444!important}
/* VOICE ACTIVITY DETECTION */
.voice-part-item.speaking{background:rgba(78,145,96,.12)!important;color:var(--green)!important;transition:background .15s}
.voice-part-item.speaking .vp-av{outline:2px solid var(--green);outline-offset:1px;animation:vadRing .7s ease-in-out infinite}
@keyframes vadRing{0%,100%{outline-color:rgba(78,145,96,.9);outline-width:2px}50%{outline-color:rgba(78,145,96,.25);outline-width:3px}}
.vp-wave{display:none;align-items:center;gap:2px;flex-shrink:0;height:14px;margin-left:2px}
.voice-part-item.speaking .vp-wave{display:flex}
.vp-wave-bar{width:3px;background:var(--green);border-radius:2px;animation:vadWave .55s ease-in-out infinite}
.vp-wave-bar:nth-child(1){height:4px;animation-delay:.00s}
.vp-wave-bar:nth-child(2){height:9px;animation-delay:.10s}
.vp-wave-bar:nth-child(3){height:6px;animation-delay:.20s}
.vp-wave-bar:nth-child(4){height:11px;animation-delay:.13s}
@keyframes vadWave{0%,100%{transform:scaleY(.3);opacity:.6}50%{transform:scaleY(1);opacity:1}}
/* Discord-like контекстное меню участника войса */
.voice-user-card{min-width:236px;padding:6px;position:relative}
.voice-user-head{display:flex;align-items:center;gap:9px;padding:7px 32px 8px 7px}
.voice-user-head .vu-av{width:32px;height:32px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;font-size:13px;color:var(--text)}
.voice-user-head .vu-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.voice-user-head .vu-name{font-weight:700;color:var(--text);font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.voice-user-close{position:absolute;top:8px;right:8px;width:24px;height:24px;border-radius:8px;border:1px solid transparent;background:transparent;color:var(--text3);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .12s,color .12s,border-color .12s}
.voice-user-close:hover{background:var(--bg4);color:var(--text);border-color:var(--border2)}
.voice-user-close svg{width:13px;height:13px;display:block}
.ctx-volume-box{padding:9px 10px 10px;display:flex;flex-direction:column;gap:7px}
.ctx-volume-top{display:flex;align-items:center;justify-content:space-between;gap:10px;font-size:11px;color:var(--text2);text-transform:uppercase;letter-spacing:.04em}
.ctx-volume-val{font-size:11px;color:var(--gold);font-variant-numeric:tabular-nums;text-transform:none;letter-spacing:0}
.ctx-volume-row{display:flex;align-items:center;gap:8px}
.ctx-volume-row svg{width:15px;height:15px;color:var(--text2);flex-shrink:0}
.ctx-volume-slider{width:100%;height:18px;appearance:none;-webkit-appearance:none;background:transparent;cursor:pointer}
.ctx-volume-slider::-webkit-slider-runnable-track{height:4px;border-radius:999px;background:linear-gradient(90deg,var(--gold),rgba(255,255,255,.16))}
.ctx-volume-slider::-webkit-slider-thumb{
  -webkit-appearance:none;width:13px;height:13px;border-radius:50%;background:var(--text);
  border:1px solid var(--gold2);box-shadow:0 0 8px rgba(201,170,113,.35);margin-top:-4.5px;
}
.ctx-volume-slider::-moz-range-track{height:4px;border-radius:999px;background:rgba(255,255,255,.16)}
.ctx-volume-slider::-moz-range-progress{height:4px;border-radius:999px;background:var(--gold)}
.ctx-volume-slider::-moz-range-thumb{width:13px;height:13px;border-radius:50%;background:var(--text);border:1px solid var(--gold2)}
/* VOICE BAR — Discord-like компактный вид */
#voiceBar{
  display:none;flex-shrink:0;
  background:#232428!important;
  border-top:1px solid #1e1f22!important;
  box-shadow:0 -1px 0 rgba(255,255,255,.04),0 -8px 22px rgba(0,0,0,.18)!important;
  padding:8px 10px;gap:7px;flex-direction:column;
  color:#dbdee1;
  backdrop-filter:none!important;-webkit-backdrop-filter:none!important;
}
#voiceBar.show{display:flex}
#voiceBar.local-speaking{border-top-color:rgba(35,165,90,.45)!important;box-shadow:0 -1px 0 rgba(35,165,90,.22),0 -8px 22px rgba(0,0,0,.18)!important}
#voiceBar.local-speaking .vb-room-name::after{content:'';margin-left:6px;padding:1px 6px;border-radius:999px;background:rgba(35,165,90,.16);border:1px solid rgba(35,165,90,.36);color:#3ba55d;font-size:10px;font-family:var(--font-body);font-weight:700;letter-spacing:.02em}
#voiceBar.local-speaking #vbMuteBtn{box-shadow:0 0 0 1px rgba(35,165,90,.38) inset!important;border-color:rgba(35,165,90,.38)!important;color:#3ba55d!important}
#voiceBar .vb-main{display:flex;align-items:center;gap:8px;min-width:0}
#voiceBar .vb-room{flex:1;min-width:0;overflow:hidden}
#voiceBar .vb-room-name{font-size:13px;font-weight:700;color:#3ba55d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:flex;align-items:center;gap:6px;line-height:1.2}
#voiceBar .vb-room-name svg,#voiceBar .vb-room-name .ti svg{width:14px;height:14px;display:block;flex-shrink:0}
#voiceBar .vb-room-status{font-size:11px;color:#949ba4;white-space:nowrap;line-height:1.35}
#voiceBar .vb-btns{display:flex;gap:6px;flex-shrink:0;align-items:center}
.vb-btn{
  width:30px;height:30px;border-radius:8px!important;
  background:#2b2d31!important;
  border:1px solid rgba(255,255,255,.06)!important;
  cursor:pointer;display:flex;align-items:center;justify-content:center;
  transition:background .14s,color .14s,border-color .14s,transform .14s;
  flex-shrink:0;color:#dbdee1!important;
  box-shadow:none!important;
  backdrop-filter:none!important;-webkit-backdrop-filter:none!important;
}
.vb-btn svg{width:16px;height:16px;display:block;stroke:currentColor!important}
.vb-btn .ti{display:flex;align-items:center;justify-content:center}
.vb-btn .ti svg{display:block}
.vb-btn:hover{background:#383a40!important;color:#fff!important;transform:translateY(-1px)}
.vb-btn.active-mute{background:#da373c!important;border-color:#da373c!important;color:#fff!important;box-shadow:none!important}
.vb-btn.vb-leave{background:rgba(218,55,60,.14)!important;border-color:rgba(218,55,60,.30)!important;color:#f23f42!important}
.vb-btn.vb-leave:hover{background:#da373c!important;border-color:#da373c!important;color:#fff!important;box-shadow:none!important}
.vb-btn.active-stream{background:rgba(88,101,242,.20)!important;border-color:rgba(88,101,242,.38)!important;color:#a5b4fc!important;box-shadow:none!important}
.vb-btn.active-stream:hover{background:#5865f2!important;color:#fff!important}
#voiceBar .vb-volume{display:flex;align-items:center;gap:8px;color:#949ba4}
#voiceBar .vb-volume svg{width:13px;height:13px;display:block;flex-shrink:0}

/* VOICE WORKSPACE — Discord-like channel stage */
#voiceStage{display:none;flex:1;min-height:0;overflow:hidden;background:linear-gradient(180deg,var(--bg1),var(--bg2));flex-direction:column}
#voiceStage.show{display:flex}
.voice-stage-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 18px;border-bottom:1px solid var(--border);background:rgba(0,0,0,.08);flex-shrink:0}
.voice-stage-title{min-width:0;display:flex;align-items:center;gap:10px}
.voice-stage-title .vst-icon{width:34px;height:34px;border-radius:12px;background:rgba(78,145,96,.16);border:1px solid rgba(78,145,96,.28);color:var(--green);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.voice-stage-title h2{margin:0;font-size:18px;color:var(--text);font-family:var(--font-heading);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.voice-stage-title p{margin:2px 0 0;color:var(--text3);font-size:12px}
.voice-stage-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.vs-btn{height:34px;border-radius:10px;border:1px solid var(--border2);background:var(--bg3);color:var(--text2);display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:0 11px;cursor:pointer;font-size:13px;font-weight:700;transition:background .12s,color .12s,border-color .12s,transform .12s}
.vs-btn:hover{background:var(--bg4);color:var(--text);border-color:rgba(88,101,242,.35)}
.vs-btn:active{transform:scale(.98)}
.vs-btn.danger{background:rgba(237,66,69,.14);border-color:rgba(237,66,69,.28);color:#ff7779}
.vs-btn.danger:hover{background:#ed4245;color:#fff;border-color:#ed4245}
.vs-btn.active{background:rgba(78,145,96,.16);border-color:rgba(78,145,96,.36);color:var(--green)}
.voice-stage-body{flex:1;min-height:0;display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:14px;padding:14px;overflow:hidden}
.voice-stage-main{min-width:0;min-height:0;display:flex;flex-direction:column;gap:14px;overflow-y:auto;overflow-x:hidden;padding-right:2px;scrollbar-gutter:stable}

.voice-stage-grid-wrap{min-width:0;display:flex;flex-direction:column;gap:10px;flex:0 0 auto;order:2}
.voice-stage-grid-head{display:flex;align-items:center;justify-content:space-between;gap:10px;color:var(--text2);font-size:12px;font-weight:800;padding:0 2px}
.voice-stage-grid-hint{color:var(--text3);font-size:11px;font-weight:700}
.voice-stage-grid{display:grid;grid-template-columns:repeat(var(--vs-grid-cols,2),minmax(0,1fr));gap:12px;align-content:start}
.av-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;text-transform:uppercase;line-height:1;border-radius:inherit}
.vs-tile{position:relative;border-radius:16px;overflow:hidden;cursor:pointer;
  background:var(--bg2);border:1px solid var(--border);
  box-shadow:0 2px 10px rgba(0,0,0,.06);
  display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
  padding:18px 14px 14px;gap:10px;min-height:0;
  transition:transform .14s ease,box-shadow .14s ease,border-color .14s ease}
.vs-tile:hover{transform:translateY(-2px);border-color:var(--border2);box-shadow:0 8px 24px rgba(0,0,0,.12)}
.vs-tile.featured{grid-column:span 2}
.vs-tile.speaking{border-color:var(--green);box-shadow:0 0 0 2px color-mix(in srgb,var(--green) 35%,transparent),0 6px 20px rgba(0,0,0,.10)}
.vs-tile.streaming .vs-tile-av{box-shadow:0 0 0 3px #ed4245,0 4px 14px rgba(0,0,0,.18);animation:vsStreamPulse 1.5s ease-in-out infinite}
@keyframes vsStreamPulse{
  0%{box-shadow:0 0 0 3px #ed4245,0 0 0 0 rgba(237,66,69,.55)}
  70%{box-shadow:0 0 0 3px #ed4245,0 0 0 14px rgba(237,66,69,0)}
  100%{box-shadow:0 0 0 3px #ed4245,0 0 0 0 rgba(237,66,69,0)}
}
.vs-tile.watching{outline:2px solid var(--blue);outline-offset:-2px}
.vs-tile-bg{display:none}
.vs-tile-avatar{position:static;display:flex;align-items:center;justify-content:center}
.vs-tile-avatar .vs-tile-av{width:72px;height:72px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#fff;font-size:30px;font-weight:800;box-shadow:0 4px 14px rgba(0,0,0,.18)}
.vs-tile-avatar .vs-tile-av img{width:100%;height:100%;object-fit:cover;display:block}
.vs-tile.featured .vs-tile-avatar .vs-tile-av{width:92px;height:92px;font-size:38px}
.vs-tile-body{position:static;z-index:1;width:100%;display:flex;flex-direction:column;align-items:center;gap:8px}
.vs-tile-topline{display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap}
.vs-tile-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:999px;background:var(--bg4);border:1px solid var(--border);color:var(--text2);font-size:10px;font-weight:800}
.vs-tile-pill.muted{color:#e0484b;border-color:color-mix(in srgb,#ed4245 30%,transparent);background:color-mix(in srgb,#ed4245 10%,var(--bg3))}
.vs-tile-pill.live{color:#ed4245;border-color:color-mix(in srgb,#ed4245 30%,transparent);background:color-mix(in srgb,#ed4245 12%,var(--bg3))}
.vs-tile-pill.watch{color:var(--blue);border-color:color-mix(in srgb,var(--blue) 30%,transparent);background:color-mix(in srgb,var(--blue) 12%,var(--bg3))}
.vs-tile-name{color:var(--text);font-size:15px;font-weight:800;line-height:1.2;text-align:center;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.vs-tile.featured .vs-tile-name{font-size:18px}
.vs-tile-sub{color:var(--text3);font-size:12px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:5px;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.vs-tile-actions{display:flex;gap:8px;align-items:center;justify-content:center;flex-wrap:nowrap;margin-top:2px}
.vs-tile-icon-actions{display:flex;gap:8px;align-items:center;justify-content:center;flex-wrap:nowrap}
.vs-tile-icon-btn{width:38px;height:38px;flex:0 0 38px;border-radius:50%;border:1px solid var(--border);background:var(--bg3);color:var(--text2);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:background .12s,color .12s,border-color .12s}
.vs-tile-icon-btn:hover{background:var(--bg4);color:var(--text)}
.vs-tile-icon-btn.is-active{background:color-mix(in srgb,var(--green) 18%,var(--bg3));border-color:color-mix(in srgb,var(--green) 35%,transparent);color:var(--green)}
.vs-tile-icon-btn.is-off{background:color-mix(in srgb,#ed4245 14%,var(--bg3));border-color:color-mix(in srgb,#ed4245 30%,transparent);color:#e0484b}
.vs-tile-icon-btn.is-live{background:color-mix(in srgb,#ed4245 16%,var(--bg3));border-color:color-mix(in srgb,#ed4245 32%,transparent);color:#ed4245}
.vs-tile-icon-btn:disabled{opacity:.45;cursor:default}
.vs-tile-btn{height:30px;padding:0 10px;border-radius:999px;border:1px solid var(--border);background:var(--bg3);color:var(--text2);display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:800;cursor:pointer}
.vs-tile-btn:hover{background:var(--bg4);color:var(--text)}
.vs-tile-btn.live{background:color-mix(in srgb,#ed4245 14%,var(--bg3));border-color:color-mix(in srgb,#ed4245 30%,transparent);color:#ed4245}
.vs-tile-btn.manage{color:var(--text2)}
#voiceStageGrid[data-cols="1"] .vs-tile.featured{grid-column:span 1}
@media(max-width:900px){
  .voice-stage-grid-wrap{order:2}
  .voice-stage-grid{grid-template-columns:repeat(var(--vs-grid-cols-mobile,2),minmax(0,1fr))}
  .vs-tile{min-height:118px}
  .vs-tile.featured{grid-column:span 2;min-height:176px}
  .vs-tile-avatar .vs-tile-av{width:58px;height:58px;font-size:24px}
  .vs-tile.featured .vs-tile-avatar .vs-tile-av{width:72px;height:72px;font-size:30px}
  .vs-tile-name{font-size:15px}
}
@media(max-width:560px){
  .voice-stage-grid-head{padding:0;font-size:11px}
  .vs-tile{min-height:108px;border-radius:16px}
  .vs-tile.featured{min-height:156px}
  .vs-tile-body{padding:12px}
  .vs-tile-actions{gap:6px}
  .vs-tile-btn{height:28px;padding:0 9px;font-size:10px}
}
.voice-stage-stream{order:1;flex:1 1 300px;min-height:240px;max-height:min(52vh,480px);border-radius:18px;background:radial-gradient(circle at 50% 0%,rgba(88,101,242,.18),transparent 42%),#050608;border:1px solid rgba(255,255,255,.08);box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 16px 44px rgba(0,0,0,.28);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
.voice-stage-stream video{width:100%;height:100%;object-fit:contain;background:#000;display:block}
/* Совместный просмотр */
.watch-player-wrap{position:absolute;inset:0;display:flex;flex-direction:column;background:#000;z-index:4}
#watchPlayerHost{flex:1;min-height:0;position:relative}
#watchPlayerHost iframe,#watchPlayerHost #watchYtTarget{position:absolute;inset:0;width:100%!important;height:100%!important;border:0}
.watch-bar{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:8px 12px;background:var(--glass-panel,rgba(12,20,34,.86));backdrop-filter:blur(18px) saturate(1.3);-webkit-backdrop-filter:blur(18px) saturate(1.3);border-top:1px solid var(--glass-border,rgba(255,255,255,.08))}
.watch-live{display:inline-flex;align-items:center;gap:7px;font-size:12px;font-weight:800;color:var(--text)}
.watch-dot{width:8px;height:8px;border-radius:50%;background:#ed4245;box-shadow:0 0 0 4px rgba(237,66,69,.18);animation:streamDotPulse 1.4s ease-in-out infinite}
.watch-bar-actions{display:flex;gap:8px}
.vs-empty-stream{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:10px;color:var(--text3);padding:24px;max-width:440px}
.vs-empty-stream .big{width:66px;height:66px;border-radius:22px;display:flex;align-items:center;justify-content:center;background:rgba(88,101,242,.12);border:1px solid rgba(88,101,242,.22);color:#a5b4fc}
.vs-empty-stream b{color:var(--text);font-size:17px}
.vs-empty-stream span{font-size:13px;line-height:1.45}
.vs-watch-pill{position:absolute;left:14px;top:14px;right:14px;max-width:calc(100% - 28px);z-index:4;display:none;align-items:center;gap:7px;padding:7px 10px;border-radius:999px;background:rgba(0,0,0,.55);border:1px solid rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:800;backdrop-filter:blur(12px)}
.vs-watch-pill #voiceStageWatchName{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.vs-watch-actions{margin-left:auto;display:flex;align-items:center;gap:6px;flex-shrink:0}
.vs-watch-btn{width:26px;height:26px;border-radius:50%;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.10);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;transition:background .12s,transform .12s}
.vs-watch-btn:hover{background:rgba(255,255,255,.18);transform:scale(1.04)}
.vs-watch-btn svg{width:14px;height:14px;display:block}
.voice-stage-stream.watching .vs-watch-pill{display:flex}
.vs-live-dot{width:8px;height:8px;border-radius:50%;background:#ed4245;animation:streamDotPulse 1.4s ease-in-out infinite;flex-shrink:0}
.voice-stage-controls{order:3;display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap;padding:10px;border-radius:16px;background:var(--bg2);border:1px solid var(--border);position:sticky;bottom:0;z-index:3;box-shadow:0 -6px 18px rgba(0,0,0,.10)}
.voice-stage-side{min-width:0;min-height:0;border-radius:18px;background:var(--bg2);border:1px solid var(--border);overflow:hidden;display:flex;flex-direction:column}
.voice-stage-side-head{padding:12px 12px 8px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;gap:8px;align-items:center;color:var(--text2);font-size:13px;font-weight:800}
.voice-stage-users{padding:8px;overflow-y:auto;min-height:0;display:flex;flex-direction:column;gap:7px}
.vs-user{display:flex;align-items:center;gap:9px;padding:8px;border-radius:12px;background:var(--bg3);border:1px solid transparent;color:var(--text2);transition:background .12s,border-color .12s,color .12s}
.vs-user.speaking{background:color-mix(in srgb,var(--green) 14%,var(--bg3));border-color:color-mix(in srgb,var(--green) 32%,transparent);color:var(--green)}
.vs-user.me{box-shadow:inset 3px 0 0 var(--green)}
.vs-user.force-muted{opacity:.62}
.vs-av{width:34px;height:34px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;color:var(--text);font-weight:800}
.vs-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.vs-meta{min-width:0;flex:1}.vs-name{font-size:13px;font-weight:800;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.vs-sub{font-size:11px;color:var(--text3);margin-top:1px;display:flex;align-items:center;gap:6px}.vs-user.speaking .vs-sub{color:var(--green)}
.vs-user-actions{display:flex;gap:5px;align-items:center}.vs-mini{width:28px;height:28px;border-radius:9px;border:1px solid var(--border2);background:var(--bg3);color:var(--text3);display:flex;align-items:center;justify-content:center;cursor:pointer}.vs-mini:hover{color:var(--text);background:var(--bg4)}.vs-mini.stream{color:#ff6464;border-color:rgba(237,66,69,.22);background:rgba(237,66,69,.10)}
.vs-mini.stream.is-active,.vs-tile-btn.live.is-active{background:rgba(88,101,242,.18);border-color:rgba(88,101,242,.34);color:#dbe7ff}
@media(max-width:900px){
  /* На мобильных сначала показываем окно трансляции и кнопки управления,
     а список участников переносим ниже. */
  .voice-stage-body{grid-template-columns:1fr;overflow-y:auto;display:flex;flex-direction:column}
  .voice-stage-main{order:1;flex:0 0 auto;overflow:visible;padding-right:0}
  .voice-stage-side{order:2;min-height:0;flex:1 1 auto;max-height:none}
  .voice-stage-head{padding:12px}
  .voice-stage-actions .vs-btn span.lbl{display:none}
  .voice-stage-stream{min-height:220px;max-height:none}
  .vs-tile{padding:14px 10px 12px;gap:8px}
  .vs-tile-avatar .vs-tile-av{width:58px;height:58px;font-size:24px}
  .vs-tile-name{font-size:14px}
  .vs-tile-icon-btn{width:34px;height:34px;flex:0 0 34px}
}
@media(max-width:560px){
  .voice-stage-stream{min-height:200px}
  .voice-stage-controls{justify-content:stretch}
  .voice-stage-controls .vs-btn{flex:1}
  .voice-stage-title h2{font-size:16px}
}
/* Мобильный split-view для группового голосового окна со стримом */
@media(max-width:900px){
  #voiceStage.has-stream .voice-stage-body{overflow:hidden;gap:10px;min-height:0}
  #voiceStage.has-stream .voice-stage-side{flex:1 1 46%;min-height:0}
  #voiceStage.has-stream .voice-stage-main{flex:1 1 54%;min-height:0;display:flex;flex-direction:column}
  #voiceStage.has-stream .voice-stage-stream{flex:1 1 auto;min-height:0}
}
.voice-stage-stream{cursor:pointer}
.voice-stage-stream:-webkit-full-screen{width:100%!important;height:100%!important;max-height:none!important;border-radius:0!important;background:#000!important}
.voice-stage-stream:-moz-full-screen{width:100%!important;height:100%!important;max-height:none!important;border-radius:0!important;background:#000!important}
.voice-stage-stream:fullscreen{width:100%!important;height:100%!important;max-height:none!important;border-radius:0!important;background:#000!important}
.voice-stage-stream:-webkit-full-screen video,.voice-stage-stream:fullscreen video{width:100%!important;height:100%!important;object-fit:contain!important}

#vbVolSlider{flex:1;height:18px;appearance:none;-webkit-appearance:none;background:transparent;cursor:pointer;accent-color:#5865f2}
#vbVolSlider::-webkit-slider-runnable-track{height:4px;border-radius:999px;background:#3b3d44}
#vbVolSlider::-webkit-slider-thumb{-webkit-appearance:none;width:14px;height:14px;border-radius:50%;background:#5865f2;border:2px solid #232428;margin-top:-5px;box-shadow:0 1px 4px rgba(0,0,0,.35)}
#vbVolSlider::-moz-range-track{height:4px;border-radius:999px;background:#3b3d44}
#vbVolSlider::-moz-range-progress{height:4px;border-radius:999px;background:#5865f2}
#vbVolSlider::-moz-range-thumb{width:12px;height:12px;border-radius:50%;background:#5865f2;border:2px solid #232428}
@media(max-width:740px){
  #voiceBar{padding:7px 9px;gap:6px}
  #voiceBar .vb-btn{width:28px;height:28px;border-radius:8px!important}
  #voiceBar .vb-btns{gap:5px}
  #voiceBar .vb-room-name{font-size:12px}
}
/* ── MODERN FLOATING PLAYER — Liquid Glass ─────────────────── */
/* ══════════════════════════════════════════════
   FLOATING PLAYER — Compact Liquid Glass
   Northern Lights visualizer
══════════════════════════════════════════════ */
#floatingPlayer{
  position:fixed;bottom:76px;right:12px;z-index:2200;
  background:rgba(6,8,12,0.82);
  border:1px solid rgba(255,255,255,0.08);
  border-top:1px solid rgba(255,255,255,0.14);
  border-radius:22px;
  box-shadow:0 24px 64px rgba(0,0,0,.9),
             0 0 0 1px rgba(255,255,255,.04),
             inset 0 1px 0 rgba(255,255,255,.08);
  width:300px;display:none;flex-direction:column;
  overflow:hidden;user-select:none;
  backdrop-filter:blur(48px) saturate(200%);
  -webkit-backdrop-filter:blur(48px) saturate(200%);
}
#floatingPlayer.show{display:flex}

/* Northern lights canvas */
#fpAurora{
  position:absolute;inset:0;pointer-events:none;z-index:0;
  opacity:0.55;border-radius:22px;overflow:hidden;
}
#fpAurora canvas{width:100%;height:100%;display:block;}

/* All inner content above aurora */
#floatingPlayer > *:not(#fpAurora){position:relative;z-index:1;}

/* Header */
#floatingPlayer .fp-header{
  display:flex;align-items:center;gap:8px;
  padding:12px 12px 8px;cursor:move;
}
#floatingPlayer .fp-icon{
  display:flex;align-items:center;flex-shrink:0;
  color:rgba(255,255,255,0.45);
}
#floatingPlayer .fp-icon svg{width:13px;height:13px;display:block;}
#floatingPlayer .fp-label-text{
  font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;
  color:rgba(255,255,255,0.35);flex:1;
}
#floatingPlayer .fp-header-btns{display:flex;align-items:center;gap:4px;flex-shrink:0;}
.fp-hbtn{
  width:22px;height:22px;border-radius:7px;
  background:rgba(255,255,255,0.06);
  border:1px solid rgba(255,255,255,0.08);
  color:rgba(255,255,255,0.45);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:.14s;flex-shrink:0;
}
.fp-hbtn:hover{background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.85);}
.fp-hbtn svg{width:11px;height:11px;display:block;}

/* Main row: art + info */
#floatingPlayer .fp-art-row{
  display:flex;align-items:center;gap:11px;
  padding:0 12px 10px;
}
#floatingPlayer .fp-art{
  width:48px;height:48px;border-radius:12px;flex-shrink:0;
  background:rgba(255,255,255,0.06);
  border:1px solid rgba(255,255,255,0.08);
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
}
#floatingPlayer .fp-disc{
  animation:fpDiscSpin 4s linear infinite;
  animation-play-state:paused;
  display:flex;align-items:center;justify-content:center;
  width:100%;height:100%;font-size:28px;
}
#floatingPlayer.playing .fp-disc{animation-play-state:running;}
#floatingPlayer.playing .fp-art{
  box-shadow:0 0 20px rgba(100,200,255,0.2),0 0 40px rgba(80,150,255,0.1);
}
@keyframes fpDiscSpin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
#floatingPlayer .fp-track-info{flex:1;min-width:0;}
#floatingPlayer .fp-track-name{
  font-size:13px;font-weight:600;color:rgba(255,255,255,0.9);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:2px;
}
#floatingPlayer .fp-track-sub{font-size:10px;color:rgba(255,255,255,0.4);}
/* Download button next to track */
.fp-dl-btn{
  width:26px;height:26px;border-radius:8px;flex-shrink:0;
  background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);
  color:rgba(255,255,255,0.45);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:.14s;
}
.fp-dl-btn:hover{background:rgba(100,200,255,0.15);color:rgba(150,220,255,0.9);}
.fp-dl-btn svg{width:13px;height:13px;display:block;}

/* Progress */
#floatingPlayer .fp-prog-wrap{padding:0 12px 8px;}
#floatingPlayer .fp-time-row{
  display:flex;justify-content:space-between;
  font-size:9px;color:rgba(255,255,255,0.35);
  margin-bottom:5px;font-variant-numeric:tabular-nums;letter-spacing:.02em;
}
#floatingPlayer .fp-progress{
  width:100%;height:3px;
  background:rgba(255,255,255,0.08);
  border-radius:99px;cursor:pointer;position:relative;transition:height .15s;
}
#floatingPlayer .fp-progress:hover{height:5px;}
#floatingPlayer .fp-progress-bar{
  height:100%;
  background:linear-gradient(90deg,rgba(80,180,255,0.7),rgba(140,220,255,0.9));
  border-radius:99px;width:0;position:relative;transition:width .3s linear;
}
#floatingPlayer .fp-progress-bar::after{
  content:'';position:absolute;right:-5px;top:50%;transform:translateY(-50%);
  width:10px;height:10px;border-radius:50%;
  background:rgba(160,230,255,0.95);
  opacity:0;transition:opacity .15s;
  box-shadow:0 0 8px rgba(100,200,255,.8);
}
#floatingPlayer .fp-progress:hover .fp-progress-bar::after{opacity:1;}

/* Controls */
#floatingPlayer .fp-controls{
  display:flex;align-items:center;justify-content:center;
  gap:6px;padding:4px 12px 10px;
}
/* Glass control buttons */
#floatingPlayer .fp-btn{
  width:34px;height:34px;border-radius:11px;
  background:rgba(255,255,255,0.06);
  border:1px solid rgba(255,255,255,0.08);
  color:rgba(255,255,255,0.6);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:all .15s;flex-shrink:0;
  backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
}
#floatingPlayer .fp-btn svg{width:15px;height:15px;display:block;}
#floatingPlayer .fp-btn:hover{
  background:rgba(255,255,255,0.12);
  color:rgba(255,255,255,0.9);
  border-color:rgba(255,255,255,0.14);
  transform:scale(1.06);
}
#floatingPlayer .fp-btn.fp-play{
  width:44px;height:44px;border-radius:14px;
  background:rgba(100,190,255,0.18);
  border:1px solid rgba(120,210,255,0.3);
  color:rgba(160,230,255,0.95);
  box-shadow:0 4px 16px rgba(80,160,255,0.25),
             inset 0 1px 0 rgba(255,255,255,0.15);
}
#floatingPlayer .fp-btn.fp-play svg{width:18px;height:18px;}
#floatingPlayer .fp-btn.fp-play:hover{
  background:rgba(100,190,255,0.28);
  box-shadow:0 6px 24px rgba(80,160,255,0.4),
             inset 0 1px 0 rgba(255,255,255,0.2);
  transform:scale(1.05);
}
#floatingPlayer .fp-btn.active-mode{
  color:rgba(140,220,255,0.9)!important;
  border-color:rgba(100,190,255,0.3)!important;
  background:rgba(80,160,255,0.1)!important;
}

/* Volume row */
#floatingPlayer .fp-vol-row{
  display:flex;align-items:center;gap:7px;
  padding:6px 12px 12px;
  border-top:1px solid rgba(255,255,255,0.05);
}
#floatingPlayer .fp-vol-icon{
  flex-shrink:0;cursor:pointer;
  color:rgba(255,255,255,0.4);transition:color .12s;
  display:flex;align-items:center;
}
#floatingPlayer .fp-vol-icon:hover{color:rgba(255,255,255,0.7);}
#floatingPlayer .fp-vol-icon svg{width:14px;height:14px;display:block;}
#floatingPlayer .fp-vol-slider{
  flex:1;height:3px;cursor:pointer;
  accent-color:rgba(120,210,255,0.9);
}
#floatingPlayer .fp-vol-pct{
  font-size:9px;color:rgba(255,255,255,0.35);
  min-width:28px;text-align:right;font-variant-numeric:tabular-nums;
}

/* ── Minimized ───────────────────────────────── */
#floatingPlayer.fp-minimized .fp-art-row,
#floatingPlayer.fp-minimized .fp-prog-wrap,
#floatingPlayer.fp-minimized .fp-controls,
#floatingPlayer.fp-minimized .fp-vol-row{display:none!important}
#floatingPlayer.fp-minimized #fpAurora{display:none}
#floatingPlayer.fp-minimized{
  width:auto!important;min-width:0!important;max-width:260px;
  border-radius:22px!important;
}
#floatingPlayer.fp-minimized .fp-header{
  padding:8px 10px 8px 12px!important;
  cursor:pointer!important;gap:6px!important;
}
#floatingPlayer.fp-minimized .fp-label-text{display:none!important}
#floatingPlayer.fp-minimized .fp-track-mini{display:flex!important}
.fp-track-mini{display:none;align-items:center;gap:6px;flex:1;min-width:0;}
.fp-track-mini-name{
  font-size:12px;color:rgba(160,230,255,0.85);font-weight:600;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:80px;
}
.fp-mini-controls{display:none;align-items:center;gap:2px;flex-shrink:0;}
#floatingPlayer.fp-minimized .fp-mini-controls{display:flex!important}
.fp-mini-btn{
  width:26px;height:26px;border-radius:8px;
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.07);
  color:rgba(255,255,255,0.5);cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:.12s;flex-shrink:0;
}
.fp-mini-btn svg{width:12px;height:12px;display:block;}
.fp-mini-btn:hover{background:rgba(100,190,255,0.15);color:rgba(160,230,255,0.9);}
.fp-mini-btn.fp-mini-play{
  background:rgba(100,190,255,0.18) !important;
  color:rgba(160,230,255,0.95) !important;
  border:1px solid rgba(120,210,255,0.25) !important;
  width:30px !important;height:30px !important;
  border-radius:9px !important;
  box-shadow:0 2px 8px rgba(80,160,255,0.2) !important;
  transform:none !important;
}
.fp-mini-btn.fp-mini-play svg{width:13px !important;height:13px !important;display:block;}
.fp-mini-btn.fp-mini-play:hover{
  background:rgba(100,190,255,0.30) !important;
  box-shadow:0 2px 12px rgba(80,160,255,0.35) !important;
  transform:none !important;
}
#floatingPlayer.fp-minimized .fp-close{display:none!important}
.fp-min-btn{
  width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.07);
  border:none;color:var(--text3);cursor:pointer;font-size:11px;
  display:flex;align-items:center;justify-content:center;transition:.15s;flex-shrink:0;
}
.fp-min-btn:hover{background:rgba(255,255,255,.14);color:var(--text)}

/* ── STREAM VIEWER — свёрнутое состояние ─────────────────────── */
#streamViewer.sv-minimized .sv-video-wrap{display:none!important}
#streamViewer.sv-minimized{
  width:auto!important;min-width:0!important;max-width:280px!important;
  resize:none!important;
  border-radius:28px!important;
  background:linear-gradient(135deg,rgba(20,28,38,.96),rgba(14,20,28,.96))!important;
  box-shadow:0 6px 28px rgba(0,0,0,.85),0 0 0 2px rgba(74,127,163,.25),inset 0 1px 0 rgba(255,255,255,.06)!important;
}
#streamViewer.sv-minimized .sv-header{
  border-bottom:none!important;border-radius:28px!important;
  padding:8px 10px 8px 12px!important;cursor:pointer!important;
}
#streamViewer.sv-minimized .sv-title{font-size:12px!important}
.sv-mini-leave{
  display:none;padding:3px 10px;border-radius:12px;
  background:rgba(150,41,41,.35);border:1px solid rgba(150,41,41,.4);
  color:var(--red2);cursor:pointer;font-size:11px;font-weight:600;
  font-family:inherit;transition:.12s;white-space:nowrap;flex-shrink:0;
}
.sv-mini-leave:hover{background:var(--red);color:#fff;border-color:var(--red)}
#streamViewer.sv-minimized .sv-mini-leave{display:flex!important;align-items:center}
#streamViewer.sv-minimized .sv-btn:not(.sv-min-btn){display:none!important}
.sv-min-btn{
  width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.07);
  border:none;color:var(--text3);cursor:pointer;font-size:11px;
  display:flex;align-items:center;justify-content:center;transition:.15s;
}
.sv-min-btn:hover{background:rgba(255,255,255,.14);color:var(--text)}

/* STREAM INDICATOR ON VOICE PARTICIPANT */
.vp-stream-btn{
  display:none;width:12px;height:12px;border-radius:50%;
  background:#e03232;border:none;font-size:0;
  align-items:center;justify-content:center;
  cursor:pointer;flex-shrink:0;margin-left:6px;padding:0;
  animation:streamDotPulse 1.4s ease-in-out infinite;
  transition:transform .15s;
}
.vp-stream-btn.visible{display:flex}
.vp-stream-btn:hover{transform:scale(1.35);background:#ff3c3c}
@keyframes streamDotPulse{
  0%,100%{box-shadow:0 0 0 0 rgba(224,50,50,.8);opacity:1}
  55%   {box-shadow:0 0 0 6px rgba(224,50,50,0);opacity:.75}
}

/* STREAM VIEWER WINDOW */
#streamViewer{
  position:fixed;bottom:76px;left:12px;z-index:2300;
  background:rgba(4,6,10,0.90);
  border:1px solid rgba(255,255,255,0.10);
  border-top:1px solid rgba(74,127,163,0.25);
  border-radius:16px;
  box-shadow:0 16px 48px rgba(0,0,0,.9),0 0 0 1px rgba(74,127,163,.15),inset 0 1px 0 rgba(255,255,255,.06);
  backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
  display:none;flex-direction:column;overflow:hidden;user-select:none;
  min-width:280px;width:440px;max-width:calc(100vw - 24px);
  max-height:calc(var(--app-h,100vh) - 24px);resize:both;
}
#streamViewer.show{display:flex}
#streamViewer:-webkit-full-screen{width:100%!important;height:100%!important;border-radius:0;resize:none}
#streamViewer:-moz-full-screen{width:100%!important;height:100%!important;border-radius:0;resize:none}
#streamViewer:fullscreen{width:100%!important;height:100%!important;border-radius:0;resize:none}
.sv-header{
  display:flex;align-items:center;gap:8px;padding:6px 10px;
  background:var(--bg1);border-bottom:1px solid var(--border);
  cursor:move;flex-shrink:0;
}
.sv-live-dot{
  width:8px;height:8px;border-radius:50%;background:var(--red2);flex-shrink:0;
  animation:svLivePulse 1s ease-in-out infinite;
}
@keyframes svLivePulse{0%,100%{opacity:1}50%{opacity:.3}}
.sv-title{
  flex:1;font-size:12px;font-weight:600;color:var(--blue);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.sv-btn{
  width:26px;height:26px;border-radius:4px;background:none;border:none;
  color:var(--text3);cursor:pointer;font-size:13px;
  display:flex;align-items:center;justify-content:center;transition:.12s;
}
.sv-btn:hover{background:var(--bg3);color:var(--text)}
.sv-video-wrap{
  flex:1;background:#000;display:flex;align-items:center;
  justify-content:center;min-height:180px;position:relative;overflow:hidden;
}
.sv-video-wrap video{width:100%;height:100%;object-fit:contain;display:block}
.sv-audio-controls{
  position:absolute;left:12px;bottom:12px;z-index:9;
  display:flex;align-items:center;gap:7px;
  padding:6px 8px;border-radius:999px;
  background:rgba(5,8,12,.62);
  border:1px solid rgba(255,255,255,.14);
  box-shadow:0 10px 28px rgba(0,0,0,.50),inset 0 1px 0 rgba(255,255,255,.09);
  backdrop-filter:blur(18px) saturate(160%);-webkit-backdrop-filter:blur(18px) saturate(160%);
  opacity:0;pointer-events:none;transform:translateY(8px);
  transition:opacity .16s ease,transform .16s ease,background .16s ease;
}
#streamViewer.sv-controls-visible .sv-audio-controls,
.sv-audio-controls:focus-within{
  opacity:1;pointer-events:auto;transform:translateY(0);
}
#streamViewer.sv-minimized .sv-audio-controls{display:none!important}
.sv-audio-controls.is-own{display:none!important}
.sv-audio-btn{
  width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.12);color:rgba(235,244,255,.88);cursor:pointer;
  display:flex;align-items:center;justify-content:center;transition:.12s;flex-shrink:0;padding:0;
}
.sv-audio-btn:hover{background:rgba(74,127,163,.34);color:#fff;transform:scale(1.04)}
.sv-audio-btn svg{width:17px;height:17px;display:block}
.sv-audio-slider{
  width:112px;min-width:78px;height:22px;opacity:1;cursor:pointer;
  appearance:none;-webkit-appearance:none;background:transparent;
}
.sv-audio-slider::-webkit-slider-runnable-track{height:4px;border-radius:999px;background:linear-gradient(90deg,rgba(120,190,230,.95),rgba(255,255,255,.22))}
.sv-audio-slider::-webkit-slider-thumb{
  -webkit-appearance:none;width:13px;height:13px;border-radius:50%;
  background:#eaf6ff;border:1px solid rgba(74,127,163,.45);
  box-shadow:0 0 10px rgba(120,190,230,.55);margin-top:-4.5px;
}
.sv-audio-slider::-moz-range-track{height:4px;border-radius:999px;background:rgba(255,255,255,.22)}
.sv-audio-slider::-moz-range-progress{height:4px;border-radius:999px;background:rgba(120,190,230,.95)}
.sv-audio-slider::-moz-range-thumb{width:13px;height:13px;border-radius:50%;background:#eaf6ff;border:1px solid rgba(74,127,163,.45)}
.sv-audio-label{
  display:inline-block;font-size:10px;color:rgba(235,244,255,.78);min-width:30px;text-align:right;
  font-variant-numeric:tabular-nums;letter-spacing:.02em;
}

.sv-reactions{
  position:absolute;right:12px;bottom:12px;z-index:9;
  display:flex;align-items:center;gap:6px;padding:6px;
  border-radius:999px;background:rgba(5,8,12,.58);
  border:1px solid rgba(255,255,255,.12);
  box-shadow:0 10px 28px rgba(0,0,0,.48),inset 0 1px 0 rgba(255,255,255,.08);
  backdrop-filter:blur(16px) saturate(150%);-webkit-backdrop-filter:blur(16px) saturate(150%);
  opacity:0;pointer-events:none;transform:translateY(8px);
  transition:opacity .16s ease,transform .16s ease;
}
#streamViewer.sv-controls-visible .sv-reactions,
.sv-reactions:focus-within{opacity:1;pointer-events:auto;transform:translateY(0)}
#streamViewer.sv-minimized .sv-reactions{display:none!important}

.sv-reaction-btn{
  width:30px;height:30px;border-radius:50%;border:1px solid rgba(255,255,255,.10);
  background:rgba(255,255,255,.08);cursor:pointer;display:flex;align-items:center;justify-content:center;
  font-size:16px;line-height:1;transition:transform .12s ease,background .12s ease,box-shadow .12s ease;
  font-family:var(--font-body),sans-serif;
}
.sv-reaction-btn:hover{background:rgba(201,170,113,.20);transform:scale(1.12);box-shadow:0 0 14px rgba(201,170,113,.18)}
.sv-reaction-float{
  position:absolute;left:18px;bottom:52px;z-index:12;pointer-events:none;
  font-family:var(--font-body),sans-serif;font-size:30px;
  text-shadow:0 4px 18px rgba(0,0,0,.85);animation:svReactionFloat 1.9s ease-out forwards;
}
.sv-reaction-name{
  display:block;font-family:var(--font-body);font-size:10px;color:rgba(255,255,255,.78);
  margin-top:-2px;text-align:center;white-space:nowrap;max-width:92px;overflow:hidden;text-overflow:ellipsis;
}
@keyframes svReactionFloat{
  0%{opacity:0;transform:translate(0,10px) scale(.74)}
  12%{opacity:1;transform:translate(0,0) scale(1)}
  100%{opacity:0;transform:translate(var(--rx,28px),-150px) scale(1.45)}
}
.sv-no-stream{
  color:var(--text3);font-size:13px;text-align:center;padding:24px;
  display:flex;flex-direction:column;align-items:center;gap:8px;
}
/* DM CALL — Liquid Glass */
#dmCallOverlay{
  position:fixed;inset:0;
  background:rgba(0,0,0,.55);
  backdrop-filter:blur(40px) saturate(180%);-webkit-backdrop-filter:blur(40px) saturate(180%);
  z-index:6000;display:none;align-items:center;justify-content:center;
  flex-direction:column;gap:20px;text-align:center;padding:24px;
}
#dmCallOverlay.show{display:flex}
.call-avatar{width:96px;height:96px;border-radius:50%;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:40px;overflow:hidden;border:3px solid var(--gold);animation:callRing 1.2s ease-in-out infinite}
.call-avatar img{width:100%;height:100%;object-fit:cover;border-radius:50%}
@keyframes callRing{0%,100%{box-shadow:0 0 0 0 rgba(201,170,113,.7)}60%{box-shadow:0 0 0 20px rgba(201,170,113,0)}}
.call-name{font-size:22px;font-weight:700;color:var(--gold);font-family:var(--font-heading)}
.call-status{font-size:14px;color:var(--text3)}
.call-btns{display:flex;gap:24px;margin-top:8px}
.call-btn{width:64px;height:64px;border-radius:50%;border:none;cursor:pointer;font-size:26px;display:flex;align-items:center;justify-content:center;transition:transform .15s,background .15s}
.call-btn:active{transform:scale(.92)}
.call-btn-accept{background:var(--green)}
.call-btn-reject,.call-btn-hangup{background:var(--red)}
#dmCallBar{
  display:none;
  background:rgba(8,18,10,0.75);
  border-bottom:1px solid rgba(78,145,96,0.2);
  backdrop-filter:blur(24px) saturate(160%);-webkit-backdrop-filter:blur(24px) saturate(160%);
  box-shadow:inset 0 -1px 0 rgba(0,0,0,0.2);
  padding:6px 12px;align-items:center;gap:10px;flex-shrink:0
}
#dmCallBar.show{display:flex}
.dcb-name{flex:1;font-size:13px;font-weight:600;color:var(--green)}
.dcb-timer{font-size:12px;color:var(--text3);font-family:'Consolas','Courier New',monospace}
.dcb-btn{padding:3px 10px;border-radius:var(--radius-sm);border:none;cursor:pointer;font-size:12px;font-family:inherit;font-weight:600;transition:background .12s}
.dcb-btn-mute{background:var(--bg4);color:var(--text2)}.dcb-btn-mute:hover,.dcb-btn-mute.muted{background:var(--red);color:#fff}
.dcb-btn-end{background:var(--red);color:#fff}.dcb-btn-end:hover{background:var(--red2)}
.dcb-btn-video{background:rgba(74,127,163,.18);color:#9ccbe8;border:1px solid rgba(74,127,163,.25)}
.dcb-btn-video:hover{background:rgba(74,127,163,.32);color:#d8f1ff}
@media(max-width:640px){#dmCallBar{gap:6px;padding:6px 8px}.dcb-name{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.dcb-btn{padding:4px 8px}.dcb-btn-end{font-size:0}.dcb-btn-end .ti{margin:0}.dcb-btn-mute{font-size:0}.dcb-btn-mute .ti{margin:0}}

/* UI inspection polish: stable SVG icon sizing and safer touch targets */
.icon-btn svg,.dcb-btn svg,.dmvc-btn svg,.sv-btn svg,.yt-fp-btn svg{display:block;flex-shrink:0}
.icon-btn:focus-visible,.dcb-btn:focus-visible,.dmvc-btn:focus-visible,.sv-btn:focus-visible,.yt-fp-btn:focus-visible{outline:2px solid rgba(201,170,113,.75);outline-offset:2px}
@media(max-width:740px){.icon-btn{min-width:38px;min-height:38px}.dmvc-controls{gap:6px;overflow-x:auto;justify-content:flex-start;padding-left:10px;padding-right:10px}.dmvc-controls::-webkit-scrollbar{display:none}}

/* MENTION SUGGEST */
#mentionSuggest{
  position:fixed;
  background:rgba(10,8,6,0.88);
  border:1px solid rgba(255,255,255,0.10);
  border-top:1px solid rgba(255,255,255,0.16);
  border-radius:14px;
  box-shadow:0 12px 40px rgba(0,0,0,.8),inset 0 1px 0 rgba(255,255,255,.08);
  backdrop-filter:blur(32px) saturate(160%);-webkit-backdrop-filter:blur(32px) saturate(160%);
  z-index:800;min-width:240px;max-width:320px;max-height:360px;overflow-y:auto;display:none
}
#mentionSuggest .ms-header{padding:6px 12px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--font-heading);border-bottom:1px solid var(--border);margin-bottom:2px;position:sticky;top:0;background:var(--bg1);z-index:1}
#mentionSuggest .ms-empty{padding:10px 12px;font-size:13px;color:var(--text3);text-align:center}
#mentionSuggest.open{display:block}
.mention-item{display:flex;align-items:center;gap:8px;padding:7px 12px;cursor:pointer;font-size:14px;color:var(--text2);transition:background .1s}
.mention-item:hover,.mention-item.active{background:var(--bg3);color:var(--text)}
.mention-av{width:24px;height:24px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:12px;overflow:hidden;flex-shrink:0}
.mention-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.mention-name{font-weight:600}
.mention-status{width:7px;height:7px;border-radius:50%;flex-shrink:0}
/* USER PANEL */
#userPanel{
  background:var(--bg0);
  padding:6px 8px;display:flex;align-items:center;gap:8px;
  border-top:1px solid var(--border);
  flex-shrink:0;min-height:52px
}
.up-avatar{width:34px;height:34px;border-radius:50%;cursor:pointer;flex-shrink:0;overflow:visible;position:relative;display:flex;align-items:center;justify-content:center}
.up-avatar-inner{width:34px;height:34px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;background:var(--bg3);font-size:16px;flex-shrink:0}
.up-avatar-inner img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.up-status-dot{position:absolute;bottom:-1px;right:-1px;width:12px;height:12px;border-radius:50%;background:var(--status-online);border:2px solid var(--bg0);cursor:pointer;transition:background .2s;z-index:1}
.up-info{flex:1;overflow:hidden}
.up-name{font-size:13px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.up-tag{font-size:11px;color:var(--text3);cursor:pointer;transition:color .15s}
.up-tag:hover{color:var(--text2)}
.up-btns{display:flex;gap:2px}
.icon-btn{width:30px;height:30px;border-radius:var(--radius-sm);background:transparent;border:none;color:var(--text3);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.12s,transform .12s cubic-bezier(.2,.8,.2,1);-webkit-tap-highlight-color:transparent}
.icon-btn:hover{background:var(--bg4);color:var(--text)}
.icon-btn:active{transform:scale(.88)}
.icon-btn .ti{display:flex;align-items:center;justify-content:center;}
.icon-btn .ti svg{display:block;width:18px;height:18px;}
#memberToggleBtn{
  width:auto;min-width:30px;padding:0 7px;gap:5px;
}
#memberCountBadge{
  display:flex;align-items:center;gap:4px;
  color:var(--text3);white-space:nowrap;
}
#memberCountBadge svg{display:block;flex-shrink:0;}
.mbadge-num{
  font-size:11px;font-weight:600;font-variant-numeric:tabular-nums;
  display:inline-flex;align-items:baseline;gap:1px;
  color:var(--text2);letter-spacing:-.01em;
}
.mbadge-sep{font-size:9px;color:var(--text3);margin:0 1px;}
.mbadge-total{font-size:10px;color:var(--text3);font-weight:500;}
#memberToggleBtn:hover #memberCountBadge{color:var(--text);}
#memberToggleBtn:hover .mbadge-num{color:var(--text);}
#memberToggleBtn:hover .mbadge-total{color:var(--text2);}
.status-picker{
  position:absolute;bottom:44px;left:0;
  background:rgba(10,8,6,0.88);
  border:1px solid rgba(255,255,255,0.10);
  border-top:1px solid rgba(255,255,255,0.15);
  border-radius:14px;
  box-shadow:0 12px 40px rgba(0,0,0,.8),inset 0 1px 0 rgba(255,255,255,.08);
  backdrop-filter:blur(32px) saturate(160%);-webkit-backdrop-filter:blur(32px) saturate(160%);
  padding:4px;z-index:400;min-width:180px;display:none
}
.status-picker.open{display:block}
.sp-item{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:8px;cursor:pointer;font-size:13px;color:rgba(255,255,255,0.75);transition:.12s;margin:1px 2px;}
.sp-item:hover{background:rgba(255,255,255,.10);color:#fff;}
.sp-item.active{color:var(--text);background:var(--bg3)}
.sp-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
/* MAIN */
#mainHeader{
  height:var(--hdr-h);
  background:var(--bg2);
  display:flex;align-items:center;padding:0 12px;gap:8px;
  border-bottom:1px solid var(--border);
  flex-shrink:0;z-index:5
}
.hdr-ch-icon{font-size:18px;color:var(--text3);flex-shrink:0;width:22px;height:22px;display:flex;align-items:center;justify-content:center;overflow:hidden;border-radius:50%}
.hdr-ch-icon img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.hdr-ch-name{font-weight:700;font-size:14px;color:var(--text);flex-shrink:0}
.hdr-topic{font-size:13px;color:var(--text3);border-left:1px solid var(--border2);padding-left:10px;margin-left:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1}
.hdr-actions{display:flex;gap:2px;margin-left:auto;flex-shrink:0}
.mobile-only{display:none!important}.desktop-only{display:flex!important}
#mainArea{flex:1;display:flex;flex-direction:column;background:var(--bg2);overflow:hidden;min-width:0}
#memberSidebar{
  width:var(--mb-w);min-width:var(--mb-w);
  background:var(--bg1);
  display:flex;flex-direction:column;overflow:hidden;flex-shrink:0;
  border-left:1px solid var(--border);
  transition:width .2s,min-width .2s
}
#memberSidebar.collapsed{width:0;min-width:0;border:none;overflow:hidden}
.mb-header{padding:16px 12px 8px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);border-bottom:1px solid var(--border);flex-shrink:0;font-family:var(--font-heading)}
.mb-scroll{flex:1;overflow-y:auto;padding:4px 0 8px}
.mb-section{padding:16px 8px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--font-heading)}
.mb-item{display:flex;align-items:center;gap:8px;padding:5px 8px;margin:0 4px;border-radius:var(--radius-sm);cursor:pointer;transition:background .1s}
.mb-item:hover{background:var(--bg3)}
.mb-av{width:32px;height:32px;border-radius:50%;background:var(--bg4);flex-shrink:0;position:relative;display:flex;align-items:center;justify-content:center;font-size:14px;overflow:visible;isolation:auto}
.mb-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.mb-status{position:absolute;bottom:-2px;right:-2px;width:11px;height:11px;border-radius:50%;border:2px solid var(--bg1);background:var(--status-online);z-index:4;box-shadow:0 2px 8px rgba(0,0,0,.25)}
.mb-name{font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1}
.mb-name.role-owner{color:var(--gold)}.mb-name.role-admin{color:var(--blue)}.mb-name.role-moderator{color:#8b6fc4}.mb-name.role-project-admin{color:#ff9f43}
.mb-role-icon{font-size:12px;flex-shrink:0}
.mb-validated-badge{font-size:10px;color:var(--green);flex-shrink:0;background:rgba(78,145,96,.15);border-radius:3px;padding:1px 4px}
.mb-muted-badge{font-size:10px;color:var(--red2);flex-shrink:0}
/* MESSAGES */
#messagesWrap{flex:1;overflow-y:auto;overflow-x:hidden;display:flex;flex-direction:column;padding:8px 0;-webkit-overflow-scrolling:touch}
#messagesList{display:flex;flex-direction:column}
.load-more-btn{display:flex;align-items:center;justify-content:center;padding:8px;margin:4px 16px;background:var(--bg3);border:1px solid var(--border2);border-radius:var(--radius-sm);cursor:pointer;font-size:13px;color:var(--text3);transition:background .12s,color .12s;gap:6px}
.load-more-btn:hover{background:var(--bg4);color:var(--text2)}.load-more-btn.loading{opacity:.6;pointer-events:none}
.msg-day-div{display:flex;align-items:center;gap:8px;padding:8px 16px;color:var(--text3);font-size:12px}
.msg-day-div::before,.msg-day-div::after{content:'';flex:1;height:1px;background:var(--border)}
.msg-row{display:flex;gap:14px;padding:2px 16px;position:relative;transition:background .1s}
.msg-row:hover{background:rgba(201,170,113,.04)}
.msg-row.first-in-group{margin-top:14px}
.msg-row.mentioned{background:rgba(201,170,113,.08);border-left:3px solid var(--gold);padding-left:13px}
.msg-row.mentioned:hover{background:rgba(201,170,113,.12)}
.msg-av{width:40px;height:40px;border-radius:50%;flex-shrink:0;overflow:hidden;cursor:pointer;background:var(--bg4);margin-top:2px;display:flex;align-items:center;justify-content:center;font-size:16px;border:1px solid var(--border2)}
.msg-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.msg-body{flex:1;min-width:0}
.msg-meta{display:flex;align-items:baseline;gap:8px;margin-bottom:1px;flex-wrap:wrap}
.msg-author{font-weight:600;font-size:14px;cursor:pointer;transition:color .1s}
.msg-author:hover{text-decoration:underline}
.msg-author.cl-owner{color:var(--gold)}.msg-author.cl-admin{color:var(--blue)}.msg-author.cl-moderator{color:#8b6fc4}.msg-author.cl-me{color:#9ea8d2}.msg-author.cl-superadmin{color:#ff9f43}.msg-author.cl-projectadmin{color:#e67e22}
.msg-ts{font-size:11px;color:var(--text3)}.msg-edited-tag{font-size:11px;color:var(--text3);font-style:italic}
.msg-text{font-size:15px;line-height:1.55;word-break:break-word;white-space:pre-wrap;color:var(--text)}
.msg-text a{color:var(--blue);text-decoration:none}.msg-text a:hover{text-decoration:underline}
.msg-text .mention{background:var(--gold-dim);color:var(--gold);border-radius:3px;padding:0 3px;cursor:pointer;font-weight:600}
.msg-text code{background:var(--bg3);padding:1px 5px;border-radius:3px;font-family:'Consolas','Courier New',monospace;font-size:13px;color:var(--text)}
.msg-text pre{background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;overflow-x:auto;margin:6px 0}
.msg-text pre code{background:none;padding:0;font-size:13px}
.msg-text .spoiler{background:var(--bg5);color:transparent;border-radius:3px;cursor:pointer;padding:0 3px;transition:color .2s,background .2s;user-select:none}
.msg-text .spoiler.revealed{color:var(--text);background:var(--bg4)}
.msg-text blockquote{border-left:3px solid var(--gold3);padding-left:8px;color:var(--text2);margin:2px 0}
.msg-text strong{font-weight:700}.msg-text em{font-style:italic}.msg-text del{text-decoration:line-through;opacity:.7}
.msg-text.deleted-msg{color:var(--text3);font-style:italic;font-size:14px}

/* Link preview cards */
.link-preview-card{
  margin-top:7px;max-width:520px;display:flex;gap:10px;overflow:hidden;
  background:rgba(201,170,113,.055);border:1px solid var(--border2);border-left:3px solid var(--gold3);
  border-radius:8px;text-decoration:none;color:var(--text);transition:background .12s,border-color .12s,transform .12s;
}
.link-preview-card:hover{background:rgba(201,170,113,.09);border-color:rgba(201,170,113,.35);text-decoration:none;transform:translateY(-1px)}
.link-preview-body{min-width:0;flex:1;padding:9px 10px;display:flex;flex-direction:column;gap:3px}
.link-preview-site{font-size:11px;color:var(--text3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-transform:uppercase;letter-spacing:.04em}
.link-preview-title{font-size:14px;font-weight:700;color:var(--gold);line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.link-preview-desc{font-size:12px;color:var(--text2);line-height:1.35;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.link-preview-img{width:112px;min-width:112px;min-height:86px;background:var(--bg3);object-fit:cover;border-left:1px solid var(--border2)}
.link-preview-loading{margin-top:7px;max-width:420px;font-size:12px;color:var(--text3);border-left:3px solid var(--border2);padding:5px 8px;background:var(--bg2);border-radius:6px;opacity:.8}
@media(max-width:640px){.link-preview-card{max-width:100%}.link-preview-img{width:88px;min-width:88px}.link-preview-desc{-webkit-line-clamp:2}}

/* YouTube inline preview + floating player */
.youtube-preview-card{position:relative;cursor:pointer;border-left-color:#d63c32;background:rgba(214,60,50,.07)}
.youtube-preview-card:hover{background:rgba(214,60,50,.12);border-color:rgba(214,60,50,.38)}
.youtube-preview-media{width:148px;min-width:148px;min-height:92px;position:relative;background:var(--bg0);border-left:1px solid var(--border2);overflow:hidden;display:flex;align-items:center;justify-content:center}
.youtube-preview-media img{width:100%;height:100%;object-fit:cover;display:block}
.youtube-preview-play{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:46px;height:32px;border-radius:10px;background:rgba(180,30,30,.88);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.18)}
.youtube-preview-play svg{width:17px;height:17px;margin-left:2px;display:block}
.youtube-preview-actions{display:flex;gap:6px;margin-top:6px;align-items:center;flex-wrap:wrap}
.youtube-preview-action{height:26px;border-radius:8px;border:1px solid rgba(201,170,113,.26);background:rgba(0,0,0,.18);color:var(--text2);display:inline-flex;align-items:center;gap:5px;padding:0 8px;font-size:12px;text-decoration:none;cursor:pointer;transition:.12s}
.youtube-preview-action:hover{background:rgba(201,170,113,.12);color:var(--gold);text-decoration:none}
.youtube-preview-action svg{width:13px;height:13px;display:block}
#ytFloatPlayer{position:fixed;right:18px;bottom:84px;width:min(720px,calc(100vw - 28px));aspect-ratio:16/9;background:rgba(8,7,5,.92);border:1px solid rgba(201,170,113,.28);border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.82),inset 0 1px 0 rgba(255,255,255,.08);z-index:3600;display:none;overflow:hidden;backdrop-filter:blur(28px) saturate(150%);-webkit-backdrop-filter:blur(28px) saturate(150%)}
#ytFloatPlayer.show{display:block}
#ytFloatPlayer.yt-min{width:320px;aspect-ratio:16/9}
.yt-fp-frame{position:absolute;inset:0;background:#000}
.yt-fp-frame iframe{width:100%;height:100%;border:0;display:block}
.yt-fp-top{position:absolute;left:0;right:0;top:0;height:46px;display:flex;align-items:center;gap:8px;padding:8px 10px;background:linear-gradient(to bottom,rgba(0,0,0,.72),rgba(0,0,0,0));opacity:0;transition:opacity .18s;z-index:2}
#ytFloatPlayer:hover .yt-fp-top,#ytFloatPlayer.yt-show-controls .yt-fp-top{opacity:1}
.yt-fp-title{flex:1;min-width:0;color:#fff;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-shadow:0 1px 4px #000;cursor:move}
.yt-fp-controls{position:absolute;left:0;right:0;bottom:0;height:54px;display:flex;align-items:center;gap:8px;padding:10px;background:linear-gradient(to top,rgba(0,0,0,.78),rgba(0,0,0,0));opacity:0;transition:opacity .18s;z-index:2}
#ytFloatPlayer:hover .yt-fp-controls,#ytFloatPlayer.yt-show-controls .yt-fp-controls{opacity:1}
.yt-fp-btn{width:34px;height:34px;border-radius:10px;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.08);color:rgba(255,255,255,.88);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.12s;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
.yt-fp-btn:hover{background:rgba(201,170,113,.18);color:var(--gold);border-color:rgba(201,170,113,.35);transform:scale(1.04)}
.yt-fp-btn svg{width:17px;height:17px;display:block}
.yt-fp-open{width:auto;padding:0 10px;gap:6px;font-size:12px;text-decoration:none}
.yt-fp-spacer{flex:1}
.yt-fp-range{width:92px;accent-color:var(--gold);height:4px;cursor:pointer}
@media(max-width:640px){#ytFloatPlayer{right:8px;left:8px;bottom:70px;width:auto}.youtube-preview-media{width:112px;min-width:112px}.youtube-preview-actions{display:none}.yt-fp-range{width:72px}}

/* DM video call + mobile performance */
#ytFloatPlayer{resize:both;min-width:280px;min-height:158px;max-width:calc(100vw - 16px);max-height:calc(100vh - 24px)}
#ytFloatPlayer.yt-min{resize:none;min-height:auto}
#dmVideoCallWindow{position:fixed;right:14px;bottom:88px;width:min(520px,calc(100vw - 24px));aspect-ratio:16/10;background:rgba(8,7,5,.94);border:1px solid rgba(201,170,113,.28);border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.82),inset 0 1px 0 rgba(255,255,255,.08);z-index:3550;display:none;overflow:hidden;backdrop-filter:blur(28px) saturate(150%);-webkit-backdrop-filter:blur(28px) saturate(150%);resize:both;min-width:280px;min-height:220px;max-width:calc(100vw - 16px);max-height:calc(100vh - 24px)}
#dmVideoCallWindow.show{display:block}
.dmvc-remote{position:absolute;inset:0;background:#000;display:flex;align-items:center;justify-content:center;color:var(--text3);font-size:13px}
.dmvc-remote video{width:100%;height:100%;object-fit:contain;background:#000;display:block}
.dmvc-local{position:absolute;right:10px;bottom:58px;width:28%;max-width:180px;min-width:92px;aspect-ratio:16/10;background:#111;border:1px solid rgba(201,170,113,.35);border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.55)}
.dmvc-local video{width:100%;height:100%;object-fit:cover;transform:scaleX(-1);background:#000;display:block}
.dmvc-top{position:absolute;left:0;right:0;top:0;height:44px;display:flex;align-items:center;gap:8px;padding:8px 10px;background:linear-gradient(to bottom,rgba(0,0,0,.72),rgba(0,0,0,0));z-index:2}
.dmvc-title{flex:1;min-width:0;color:#fff;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:move;text-shadow:0 1px 4px #000}
.dmvc-controls{position:absolute;left:0;right:0;bottom:0;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:linear-gradient(to top,rgba(0,0,0,.78),rgba(0,0,0,0));z-index:2}
.dmvc-btn{height:34px;min-width:34px;border-radius:10px;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.08);color:rgba(255,255,255,.88);display:flex;align-items:center;justify-content:center;gap:5px;cursor:pointer;transition:.12s;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);font-size:12px;padding:0 9px}
.dmvc-btn:hover{background:rgba(201,170,113,.18);color:var(--gold);border-color:rgba(201,170,113,.35)}
.dmvc-btn.danger{background:rgba(150,41,41,.35);border-color:rgba(200,60,60,.35)}
.dmvc-btn.active{background:rgba(201,170,113,.22);color:var(--gold)}
.dmvc-btn svg{width:16px;height:16px;display:block}
#hdrDmVideoReturnBtn.call-video-live{display:flex!important;color:#9ccbe8;border-color:rgba(74,127,163,.35);background:rgba(74,127,163,.14)}
@media(max-width:740px){
  body{overscroll-behavior:none;touch-action:manipulation}
  #ytFloatPlayer{left:8px!important;right:8px!important;bottom:calc(70px + var(--safe-bottom,0px));width:auto!important;max-height:55vh;resize:none;border-radius:14px}
  #dmVideoCallWindow{left:8px!important;right:8px!important;bottom:calc(70px + var(--safe-bottom,0px));width:auto!important;max-height:58vh;resize:none;border-radius:14px}
  .dmvc-local{right:8px;bottom:62px;width:34%;min-width:86px}
  .dmvc-btn{height:36px;min-width:36px;padding:0 8px}
  .msg{content-visibility:auto;contain-intrinsic-size:70px}
  .link-preview-card{max-width:calc(100vw - 80px)}
  .sv-reactions{right:8px;gap:4px}.sv-reaction-btn{width:30px;height:30px;font-size:16px}
}

/* DM CALL SURFACE — messenger-like audio/video call window */
#dmVideoCallWindow{
  position:fixed!important;
  right:14px;bottom:88px;
  width:min(560px,calc(100vw - 24px));
  height:min(720px,calc(100vh - 112px));
  aspect-ratio:auto!important;
  background:#050608;
  border:1px solid rgba(255,255,255,.10);
  border-top-color:rgba(255,255,255,.16);
  border-radius:24px;
  box-shadow:0 28px 90px rgba(0,0,0,.78),inset 0 1px 0 rgba(255,255,255,.10);
  z-index:6500;
  display:none;
  overflow:hidden;
  resize:both;
  min-width:300px;min-height:420px;
  max-width:calc(100vw - 16px);max-height:calc(100vh - 24px);
  backdrop-filter:blur(30px) saturate(160%);-webkit-backdrop-filter:blur(30px) saturate(160%);
  touch-action:manipulation;
}
#dmVideoCallWindow.show{display:block}
#dmVideoCallWindow.dmvc-audio-only{width:min(430px,calc(100vw - 24px));height:min(620px,calc(100vh - 112px));}
#dmVideoCallWindow .dmvc-stage{position:absolute;inset:0;overflow:hidden;background:#050608;}
#dmVideoCallWindow .dmvc-remote{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:rgba(255,255,255,.72);font-size:13px;text-align:center;background:radial-gradient(circle at 50% 22%,rgba(201,170,113,.23),transparent 34%),radial-gradient(circle at 22% 86%,rgba(74,127,163,.18),transparent 34%),linear-gradient(145deg,#0b0d11,#050608 58%,#11100c);}
#dmVideoCallWindow .dmvc-remote::before{content:'';position:absolute;inset:-20%;background:linear-gradient(120deg,transparent 20%,rgba(255,255,255,.05),transparent 48%);opacity:.55;transform:rotate(10deg);pointer-events:none;}
#dmVideoCallWindow .dmvc-remote video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;background:#000;display:block;z-index:1;transition:opacity .18s ease,visibility .18s ease,filter .18s ease;}
#dmVideoCallWindow.dmvc-screen-mode .dmvc-remote video{object-fit:contain;}
#dmVideoCallWindow .dmvc-remote.no-video video{opacity:0;visibility:hidden;pointer-events:none;}
#dmVideoCallWindow .dmvc-remote-card{position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;gap:12px;padding:22px;max-width:min(320px,80%);transition:opacity .18s ease,transform .18s ease;}
#dmVideoCallWindow .dmvc-remote-avatar{width:132px;height:132px;border-radius:50%;overflow:hidden;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);box-shadow:0 18px 60px rgba(0,0,0,.52),0 0 0 12px rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center;color:#fff;font-size:48px;font-weight:800;text-transform:uppercase;transition:box-shadow .18s ease,border-color .18s ease,transform .18s ease;}
#dmVideoCallWindow .dmvc-remote-avatar img{width:100%;height:100%;object-fit:cover;display:block;}
#dmVideoCallWindow .dmvc-remote-name{color:#fff;font-size:22px;font-weight:800;line-height:1.15;text-shadow:0 2px 18px rgba(0,0,0,.75);overflow:hidden;text-overflow:ellipsis;max-width:100%;white-space:nowrap;}
#dmVideoCallWindow .dmvc-remote-hint{color:rgba(255,255,255,.62);font-size:13px;line-height:1.35;}
#dmVideoCallWindow.dmvc-has-remote-video .dmvc-remote-card{opacity:0;transform:scale(.94);pointer-events:none;}
#dmVideoCallWindow .dmvc-remote.speaking .dmvc-remote-avatar{border-color:rgba(67,181,129,.78);box-shadow:0 0 0 8px rgba(67,181,129,.12),0 0 0 18px rgba(67,181,129,.10),0 22px 70px rgba(19,102,63,.42);transform:scale(1.02);}
#dmVideoCallWindow .dmvc-remote.speaking::after{content:'';position:absolute;inset:12px;border-radius:24px;border:1px solid rgba(67,181,129,.55);box-shadow:0 0 0 1px rgba(67,181,129,.14),0 0 48px rgba(67,181,129,.20);pointer-events:none;z-index:2;}
#dmVideoCallWindow .dmvc-local{position:absolute;right:18px;bottom:98px;width:112px;height:112px;aspect-ratio:1/1;border-radius:50%;overflow:hidden;background:#111;z-index:4;border:2px solid rgba(255,255,255,.82);box-shadow:0 14px 42px rgba(0,0,0,.58),0 0 0 1px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;transition:transform .18s ease,opacity .18s ease,border-color .18s ease,box-shadow .18s ease;}
#dmVideoCallWindow .dmvc-local video{width:100%;height:100%;object-fit:cover;transform:scaleX(-1);background:#000;display:block;}
#dmVideoCallWindow .dmvc-local.no-video video{display:none;}
#dmVideoCallWindow .dmvc-local-avatar{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,rgba(255,255,255,.10),rgba(255,255,255,.04));color:#fff;font-size:30px;font-weight:800;text-transform:uppercase;}
#dmVideoCallWindow .dmvc-local-avatar img{width:100%;height:100%;object-fit:cover;display:block;}
#dmVideoCallWindow .dmvc-local:not(.no-video) .dmvc-local-avatar{display:none;}
#dmVideoCallWindow .dmvc-local.speaking{border-color:rgba(67,181,129,.86);box-shadow:0 0 0 4px rgba(67,181,129,.18),0 18px 42px rgba(19,102,63,.28),0 0 0 1px rgba(0,0,0,.35);}
#dmVideoCallWindow .dmvc-pip-label{position:absolute;left:50%;bottom:7px;transform:translateX(-50%);padding:2px 7px;border-radius:999px;background:rgba(0,0,0,.46);color:#fff;font-size:10px;font-weight:700;line-height:1;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);}
#dmVideoCallWindow .dmvc-share-preview{display:none;position:absolute;left:14px;right:14px;bottom:96px;height:42%;border-radius:18px;overflow:hidden;background:rgba(5,6,8,.92);border:1px solid rgba(255,255,255,.12);box-shadow:0 18px 50px rgba(0,0,0,.45);z-index:3;}
#dmVideoCallWindow .dmvc-share-preview video{width:100%;height:100%;display:block;object-fit:contain;background:#000;}
#dmVideoCallWindow .dmvc-share-label{position:absolute;left:10px;top:10px;z-index:2;padding:5px 9px;border-radius:999px;background:rgba(0,0,0,.55);border:1px solid rgba(255,255,255,.12);color:#fff;font-size:11px;font-weight:800;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);}
#dmVideoCallWindow.dmvc-mobile-split .dmvc-share-preview{display:block;}
#dmVideoCallWindow .dmvc-top{position:absolute;left:0;right:0;top:0;min-height:68px;display:flex;align-items:flex-start;gap:10px;padding:14px 14px 26px;background:linear-gradient(to bottom,rgba(0,0,0,.74),rgba(0,0,0,.34) 58%,rgba(0,0,0,0));z-index:5;opacity:0;transform:translateY(-10px);transition:opacity .22s ease,transform .22s ease;}
#dmVideoCallWindow .dmvc-head{flex:1;min-width:0;cursor:move;user-select:none;}
#dmVideoCallWindow .dmvc-title{color:#fff;font-size:15px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-shadow:0 1px 8px #000;line-height:1.2;}
#dmVideoCallWindow .dmvc-subtitle{margin-top:3px;color:rgba(255,255,255,.68);font-size:12px;font-weight:600;font-variant-numeric:tabular-nums;text-shadow:0 1px 8px #000;}
#dmVideoCallWindow .dmvc-controls{position:absolute;left:50%;right:auto;bottom:18px;transform:translateX(-50%) translateY(12px);display:flex;align-items:center;justify-content:center;gap:10px;padding:10px 12px;border-radius:999px;background:rgba(0,0,0,.43);border:1px solid rgba(255,255,255,.12);box-shadow:0 18px 52px rgba(0,0,0,.50),inset 0 1px 0 rgba(255,255,255,.10);z-index:6;opacity:0;transition:opacity .22s ease,transform .22s ease;backdrop-filter:blur(24px) saturate(160%);-webkit-backdrop-filter:blur(24px) saturate(160%);max-width:calc(100% - 24px);overflow-x:auto;scrollbar-width:none;}
#dmVideoCallWindow .dmvc-controls::-webkit-scrollbar{display:none;}
#dmVideoCallWindow.dmvc-controls-visible .dmvc-top,#dmVideoCallWindow:hover .dmvc-top,#dmVideoCallWindow:focus-within .dmvc-top{opacity:1;transform:translateY(0);pointer-events:auto;}
#dmVideoCallWindow.dmvc-controls-visible .dmvc-controls,#dmVideoCallWindow:hover .dmvc-controls,#dmVideoCallWindow:focus-within .dmvc-controls{opacity:1;transform:translateX(-50%) translateY(0);pointer-events:auto;}
#dmVideoCallWindow .dmvc-btn{width:46px;height:46px;min-width:46px;border-radius:50%;padding:0;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.13);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .13s ease,background .13s ease,border-color .13s ease,opacity .13s ease;backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);font-size:0;}
#dmVideoCallWindow .dmvc-btn:hover{transform:translateY(-1px) scale(1.04);background:rgba(255,255,255,.20);border-color:rgba(255,255,255,.26);color:#fff;}
#dmVideoCallWindow .dmvc-btn:active{transform:scale(.94);}
#dmVideoCallWindow .dmvc-btn svg{width:21px;height:21px;display:block;}
#dmVideoCallWindow .dmvc-btn.muted,#dmVideoCallWindow .dmvc-btn.off{background:rgba(150,41,41,.46);border-color:rgba(255,100,100,.45);}
#dmVideoCallWindow .dmvc-btn.danger{width:56px;min-width:56px;background:linear-gradient(145deg,#d64545,#9b2020);border-color:rgba(255,120,120,.48);box-shadow:0 10px 28px rgba(150,41,41,.35);}
#dmVideoCallWindow .dmvc-btn.danger svg{width:23px;height:23px;}
#dmVideoCallWindow .dmvc-close{background:rgba(0,0,0,.28);}
#dmVideoCallWindow .dmvc-screen-mobile-note{display:none;}
#dmVideoCallWindow.dmvc-audio-only .dmvc-local{bottom:88px;}
#hdrDmVideoReturnBtn.call-video-live{display:flex!important;color:#9ccbe8;border-color:rgba(74,127,163,.35);background:rgba(74,127,163,.14)}
#dmCallBar .dcb-btn-video{display:inline-flex;}
@media(max-width:740px){
  #dmVideoCallWindow{
    left:0!important;right:auto!important;top:0!important;bottom:auto!important;
    width:100vw!important;height:var(--app-h,100vh)!important;max-width:none!important;max-height:none!important;
    min-width:0;min-height:0;border-radius:0;border:0;resize:none;box-shadow:none;z-index:7000;
  }
  #dmVideoCallWindow.dmvc-audio-only{width:100vw!important;height:var(--app-h,100vh)!important;}
  #dmVideoCallWindow .dmvc-top{padding:calc(12px + env(safe-area-inset-top,0px)) 14px 30px;min-height:calc(70px + env(safe-area-inset-top,0px));}
  #dmVideoCallWindow .dmvc-title{font-size:16px;}
  #dmVideoCallWindow .dmvc-subtitle{font-size:12px;}
  #dmVideoCallWindow .dmvc-remote-avatar{width:124px;height:124px;font-size:44px;}
  #dmVideoCallWindow .dmvc-remote-name{font-size:24px;max-width:82vw;}
  #dmVideoCallWindow .dmvc-local{right:16px;bottom:calc(102px + env(safe-area-inset-bottom,0px));width:92px;height:92px;border-width:2px;}
  #dmVideoCallWindow .dmvc-controls{bottom:calc(16px + env(safe-area-inset-bottom,0px));gap:8px;padding:9px 10px;max-width:calc(100vw - 16px);}
  #dmVideoCallWindow .dmvc-btn{width:46px;height:46px;min-width:46px;}
  #dmVideoCallWindow .dmvc-btn.danger{width:58px;min-width:58px;}
  #dmVideoCallWindow .dmvc-screen-btn{display:none;}
  #dmVideoCallWindow.dmvc-audio-only .dmvc-local{bottom:calc(96px + env(safe-area-inset-bottom,0px));}
  #dmVideoCallWindow.dmvc-mobile-split .dmvc-stage{display:grid;grid-template-rows:minmax(0,1fr) minmax(0,1fr);}
  #dmVideoCallWindow.dmvc-mobile-split .dmvc-remote{position:relative;min-height:0;}
  #dmVideoCallWindow.dmvc-mobile-split .dmvc-share-preview{position:relative;left:auto;right:auto;bottom:auto;height:auto;border-radius:0;border-left:0;border-right:0;border-bottom:0;box-shadow:inset 0 1px 0 rgba(255,255,255,.06);}
  #dmVideoCallWindow.dmvc-mobile-split .dmvc-local{width:74px;height:74px;right:12px;bottom:calc(114px + env(safe-area-inset-bottom,0px));}
}
@media(min-width:741px){
  #dmVideoCallWindow.dmvc-wide{
    width:min(860px,calc(100vw - 28px));
    height:auto;
    aspect-ratio:16/9!important;
    min-width:360px;
    min-height:220px;
    resize:horizontal;
  }
  #dmVideoCallWindow.dmvc-wide .dmvc-stage{background:#000;}
  #dmVideoCallWindow.dmvc-wide .dmvc-remote{background:#000;}
  #dmVideoCallWindow.dmvc-wide .dmvc-remote::before{display:none;}
  #dmVideoCallWindow.dmvc-wide .dmvc-remote video{object-fit:contain!important;}
  #dmVideoCallWindow.dmvc-wide .dmvc-local{width:126px;height:126px;}
}



.msg-edit-box{margin-top:4px;display:flex;flex-direction:column;gap:4px}
.msg-edit-textarea{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(201,170,113,0.3);border-radius:10px;color:var(--text);font-size:15px;padding:6px 10px;resize:none;outline:none;font-family:inherit;line-height:1.5;max-height:160px;overflow-y:auto;backdrop-filter:blur(4px);}
.msg-edit-actions{display:flex;gap:6px}
.msg-edit-save{background:var(--gold);color:var(--bg0);border:none;border-radius:var(--radius-sm);padding:4px 12px;cursor:pointer;font-size:13px;font-family:inherit;font-weight:600}
.msg-edit-save:hover{background:var(--gold2)}
.msg-edit-cancel{background:var(--bg4);color:var(--text2);border:none;border-radius:var(--radius-sm);padding:4px 12px;cursor:pointer;font-size:13px;font-family:inherit}
.msg-edit-hint{font-size:11px;color:var(--text3)}
.msg-system{padding:2px 16px;font-size:13px;color:var(--text3);font-style:italic;display:flex;align-items:center;gap:8px}
.msg-reply-ref{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text3);margin-bottom:3px;cursor:pointer;padding-left:24px;position:relative}
.msg-reply-ref::before{content:'';position:absolute;left:-14px;top:8px;width:28px;height:11px;border-top:2px solid var(--border2);border-left:2px solid var(--border2);border-radius:4px 0 0 0}
.msg-reply-ref:hover{color:var(--text2)}
.msg-reply-name{color:var(--text2);font-weight:600;flex-shrink:0}
.msg-reply-text{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.msg-img{max-width:min(420px,80vw);max-height:300px;border-radius:var(--radius);margin-top:6px;cursor:pointer;display:block;border:1px solid var(--border)}
.msg-video{max-width:min(420px,80vw);max-height:300px;border-radius:var(--radius);margin-top:6px;border:1px solid var(--border)}
.msg-audio-wrap{display:flex;align-items:center;gap:8px;margin-top:6px;background:var(--bg3);border:1px solid var(--border2);border-radius:var(--radius);padding:8px 12px;max-width:380px;cursor:pointer}
.msg-audio-wrap:hover{background:var(--bg4);border-color:var(--gold3)}
.msg-audio-wrap .audio-icon{font-size:24px;flex-shrink:0}
.msg-audio-info{flex:1;min-width:0}
.msg-audio-name{font-size:13px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.msg-audio-meta{font-size:11px;color:var(--text3)}
.file-card{display:inline-flex;align-items:center;gap:10px;padding:10px 14px;background:var(--bg3);border:1px solid var(--border2);border-radius:var(--radius);margin-top:6px;cursor:pointer;transition:background .12s;max-width:380px}
.file-card:hover{background:var(--bg4);border-color:var(--gold3)}
.file-card-info{flex:1;min-width:0}
.file-card-name{font-size:13px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.file-card-meta{font-size:11px;color:var(--text3)}
.file-card-dl{background:var(--bg5);border:none;color:var(--gold);border-radius:var(--radius-sm);padding:4px 10px;cursor:pointer;font-size:12px;font-family:inherit;transition:.12s;flex-shrink:0}
.file-card-dl:hover{background:var(--gold);color:var(--bg0)}
.upload-progress-wrap{height:3px;background:var(--bg4);border-radius:2px;overflow:hidden;margin-bottom:6px;display:none}
.upload-progress-wrap.show{display:block}
.upload-progress-bar{height:100%;background:var(--gold);border-radius:2px;transition:width .15s ease-out;width:0}
/* REACTIONS */
.msg-reactions{display:flex;flex-wrap:wrap;gap:3px;margin-top:4px}
.r-pill{display:inline-flex;align-items:center;gap:3px;padding:2px 7px;border-radius:8px;background:var(--bg4);border:1px solid var(--border2);cursor:pointer;font-size:13px;transition:.12s;user-select:none;min-width:36px;position:relative}
.r-pill:hover{border-color:var(--gold3);background:var(--gold-dim)}
.r-pill.r-mine{border-color:var(--gold);background:rgba(201,170,113,.15)}
.r-pill .r-cnt{font-size:12px;color:var(--text2)}
.r-add-btn{display:inline-flex;align-items:center;justify-content:center;width:28px;height:24px;border-radius:8px;background:var(--bg4);border:1px solid transparent;cursor:pointer;font-size:14px;opacity:0;transition:opacity .12s,border-color .12s;align-self:flex-start;margin-top:2px;flex-shrink:0}
.msg-row:hover .r-add-btn{opacity:.6}.r-add-btn:hover{opacity:1!important;border-color:var(--gold3)}
.r-who-tooltip{position:fixed;background:var(--bg0);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow);z-index:1200;padding:8px 12px;font-size:12px;color:var(--text2);pointer-events:none;max-width:200px;display:none;white-space:pre-line}
.r-who-tooltip.show{display:block}
/* COMMENTS */
.msg-comment-bar{display:flex;align-items:center;gap:4px;margin-top:5px;padding:2px 0;flex-wrap:wrap}
.msg-comment-bar .msg-reactions{margin-top:0;display:inline-flex;flex-wrap:wrap;gap:3px}
.msg-comment-bar .r-add-btn{align-self:center;margin-top:0}
.msg-comment-btn{display:inline-flex;align-items:center;gap:4px;font-size:12px;color:var(--text3);cursor:pointer;background:none;border:none;font-family:inherit;padding:2px 6px;border-radius:var(--radius-sm);transition:.1s}
.msg-comment-btn:hover{background:var(--bg3);color:var(--text2)}
.msg-comment-count{font-weight:600;color:var(--gold)}
.msg-comments-panel{margin-top:6px;background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden}
.msg-comment-item{display:flex;align-items:flex-start;gap:8px;padding:6px 10px;border-bottom:1px solid var(--border);font-size:13px}
.msg-comment-item:last-child{border-bottom:none}
.comment-av{width:22px;height:22px;border-radius:50%;background:var(--bg4);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;overflow:hidden}
.comment-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.comment-body{flex:1;min-width:0}
.comment-meta{display:flex;align-items:baseline;gap:6px;margin-bottom:1px}
.comment-name{font-weight:600;font-size:12px;color:var(--text2)}
.comment-ts{font-size:10px;color:var(--text3)}
.comment-text{color:var(--text);word-break:break-word;white-space:pre-wrap}
.comment-input-row{display:flex;gap:6px;padding:6px 10px;border-top:1px solid var(--border);background:var(--bg1)}
.comment-input{flex:1;background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:4px 8px;color:var(--text);font-size:13px;outline:none;font-family:inherit}
.comment-input:focus{border-color:var(--gold3)}
.comment-send-btn{background:var(--gold);color:var(--bg0);border:none;border-radius:var(--radius-sm);padding:4px 10px;cursor:pointer;font-size:12px;font-family:inherit;font-weight:600;flex-shrink:0}
.comment-send-btn:hover{background:var(--gold2)}
/* CHANNEL BANNER */
#chBanner{display:none;flex-shrink:0;background:linear-gradient(to bottom,var(--bg3),var(--bg2));padding:14px 16px;border-bottom:1px solid var(--border);gap:14px;align-items:flex-start}
#chBanner.show{display:flex}
.banner-av{width:60px;height:60px;border-radius:50%;background:var(--bg4);flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:26px;border:2px solid var(--border2)}
.banner-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.banner-name{font-size:18px;font-weight:700;color:var(--gold);margin-bottom:4px;font-family:var(--font-heading)}
.banner-desc{font-size:14px;color:var(--text2);white-space:pre-wrap;max-width:600px}
.banner-meta{font-size:12px;color:var(--text3);margin-top:6px}
/* INPUT */
#inputOuter{
  flex-shrink:0;padding:0 12px 12px;
  padding-bottom:calc(12px + var(--safe-bottom,0px));
  background:transparent;
}
#replyBar{display:none;background:var(--bg3);border-radius:var(--radius) var(--radius) 0 0;padding:7px 12px;font-size:13px;color:var(--text2);align-items:center;gap:8px;border:1px solid var(--border);border-bottom:none}
#replyBar.show{display:flex}
#replyBar .rply-cancel{margin-left:auto;cursor:pointer;opacity:.6;font-size:18px;line-height:1;transition:opacity .1s}
#replyBar .rply-cancel:hover{opacity:1}
/* DRAG DROP */
#dropZone{position:fixed;inset:0;background:rgba(201,170,113,.15);border:3px dashed var(--gold);z-index:999;display:none;align-items:center;justify-content:center;flex-direction:column;gap:12px;pointer-events:none;font-size:20px;color:var(--gold);font-family:var(--font-heading);font-weight:700;text-align:center}
#dropZone.active{display:flex}
/* PENDING FILES */
.pending-files-wrap{display:none;flex-wrap:wrap;gap:6px;background:var(--bg4);border:1px dashed var(--border2);border-radius:var(--radius);padding:6px 10px;margin-bottom:4px}
.pending-files-wrap.show{display:flex}
.pending-file-chip{display:inline-flex;align-items:center;gap:5px;background:var(--bg3);border:1px solid var(--border2);border-radius:4px;padding:3px 8px;font-size:12px;color:var(--text2);max-width:180px}
.pending-file-chip .pfc-name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1}
.pending-file-chip .pfc-rm{cursor:pointer;opacity:.6;font-size:14px;flex-shrink:0}
.pending-file-chip .pfc-rm:hover{opacity:1;color:var(--red2)}
.input-box{background:var(--glass-bg,color-mix(in srgb,var(--bg3) 55%,transparent));border:1px solid var(--glass-border,color-mix(in srgb,var(--border2) 70%,transparent));border-radius:var(--radius);display:flex;align-items:flex-end;gap:4px;padding:6px 8px;transition:border-color .15s,background .15s,box-shadow .15s;backdrop-filter:blur(22px) saturate(1.4);-webkit-backdrop-filter:blur(22px) saturate(1.4);box-shadow:0 8px 30px rgba(0,0,0,.18),inset 0 1px 0 rgba(255,255,255,.05)}
/* По умолчанию (динам. фон выключен) остров НЕпрозрачный — лента под ним
   не просвечивает. Прозрачное «стекло» включается только вместе с фоном. */
/* Остров всегда «стеклянный», даже при выключенном динамическом фоне:
   используем полупрозрачный фон темы + лёгкое размытие, без сплошной заливки. */
body.dynbg-off .input-box{background:var(--glass-bg,color-mix(in srgb,var(--bg3) 72%,transparent));backdrop-filter:blur(14px) saturate(1.3);-webkit-backdrop-filter:blur(14px) saturate(1.3)}
[data-theme="truecolor"] body.dynbg-off .input-box{background:rgba(26,39,64,.82)}
[data-theme="vk"] body.dynbg-off .input-box{background:rgba(255,255,255,.92)}
.input-box:focus-within{box-shadow:0 10px 36px rgba(0,0,0,.24),inset 0 1px 0 rgba(255,255,255,.07)}
.input-box:focus-within{border-color:var(--gold3);}
.msg-textarea{flex:1;background:transparent;border:none;color:var(--text);font-size:15px;resize:none;line-height:1.5;max-height:160px;outline:none;font-family:inherit;padding:2px 0;overflow-y:auto;-webkit-appearance:none;appearance:none}
.msg-textarea::placeholder{color:var(--text3)}
.inp-btn{width:32px;height:32px;border-radius:10px;background:transparent;border:none;color:var(--text3);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;transition:color .12s,background .12s,transform .12s cubic-bezier(.2,.8,.2,1);flex-shrink:0;-webkit-tap-highlight-color:transparent}
.inp-btn:hover{color:var(--gold);background:var(--gold-dim)}
.inp-btn:active{transform:scale(.9)}
/* TYPING */
#typingBar{min-height:18px;padding:0 12px;font-size:12px;color:var(--text3);display:flex;align-items:center;gap:4px;flex-shrink:0}
.typing-dots{display:inline-flex;gap:3px;align-items:center}
.typing-dot{width:5px;height:5px;border-radius:50%;background:var(--text3);animation:typingBounce 1.2s ease-in-out infinite}
.typing-dot:nth-child(2){animation-delay:.2s}.typing-dot:nth-child(3){animation-delay:.4s}
@keyframes typingBounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-4px)}}
/* DM PANEL */
.dm-search-box{padding:8px;border-bottom:1px solid var(--border);flex-shrink:0}
.dm-search-inp{width:100%;background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:5px 10px;color:var(--text);font-size:13px;outline:none;font-family:inherit}
.dm-search-inp:focus{border-color:var(--gold3)}
.dm-new-btn{display:flex;align-items:center;gap:8px;padding:7px 12px;margin:4px 8px;border-radius:var(--radius-sm);cursor:pointer;color:var(--text3);font-size:14px;transition:background .1s,color .1s;background:none;border:none;width:calc(100% - 16px);text-align:left;font-family:inherit}
.dm-new-btn:hover{background:var(--bg3);color:var(--text)}
.channel-create-row{display:flex;align-items:center;gap:8px;margin:4px 8px 8px;padding:7px 10px;border-radius:var(--radius-sm);color:var(--text3);font-size:13px;cursor:pointer;transition:background .12s,color .12s;border:1px dashed rgba(88,101,242,.28);background:rgba(88,101,242,.05)}
.channel-create-row:hover{background:rgba(88,101,242,.12);color:var(--text)}
.channel-create-row .ti{color:var(--brand)}
.new-dm-search{padding:8px 0 10px}
.new-dm-list{max-height:360px;overflow-y:auto;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg1)}
.dm-scroll{flex:1;overflow-y:auto;overflow-x:hidden;padding:2px 0;-webkit-overflow-scrolling:touch}
.dm-section{padding:12px 8px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);font-family:var(--font-heading)}
.dm-item{display:flex;align-items:center;gap:8px;padding:6px 10px;margin:1px 6px;border-radius:var(--radius-sm);cursor:pointer;transition:.1s;position:relative}
.dm-item:hover{background:var(--bg3)}.dm-item.active{background:var(--bg4)}
.dm-av{width:32px;height:32px;border-radius:50%;background:var(--bg4);flex-shrink:0;position:relative;display:flex;align-items:center;justify-content:center;font-size:14px;overflow:visible;isolation:auto}
.dm-badge{position:absolute;bottom:-4px;left:-4px;background:var(--red2);color:#fff;font-size:9px;font-weight:700;border-radius:8px;padding:0 4px;min-width:16px;height:16px;text-align:center;line-height:16px;z-index:4;border:1.5px solid var(--bg1);box-shadow:0 2px 8px rgba(0,0,0,.28)}
.dm-del-btn{display:none;width:18px;height:18px;border-radius:50%;border:none;background:rgba(150,41,41,.65);color:#fff;font-size:9px;cursor:pointer;align-items:center;justify-content:center;transition:.15s;flex-shrink:0;padding:0;line-height:1}
.dm-item:hover .dm-del-btn{display:flex}
.dm-del-btn:hover{background:rgba(190,50,50,.95);transform:scale(1.15)}
.dm-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.dm-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.dm-status{position:absolute;bottom:-2px;right:-2px;width:10px;height:10px;border-radius:50%;border:2px solid var(--bg1);z-index:4;box-shadow:0 2px 8px rgba(0,0,0,.25)}
.dm-info{flex:1;overflow:hidden}
.dm-name{font-size:14px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.dm-last{font-size:12px;color:var(--text3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
/* WELCOME */
#welcomeScreen{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--text3);text-align:center;padding:32px;background:var(--bg2)}
#welcomeScreen .ws-icon{font-size:72px;margin-bottom:20px;opacity:.7}
#welcomeScreen h2{font-size:22px;color:var(--gold);margin-bottom:8px}
#welcomeScreen p{font-size:15px;max-width:380px;line-height:1.6}
/* JOIN SERVER SCREEN */
#joinServerScreen{flex:1;display:none;flex-direction:column;align-items:center;justify-content:center;color:var(--text3);text-align:center;padding:32px;background:var(--bg2)}
#joinServerScreen.show{display:flex}
.join-srv-icon{width:80px;height:80px;border-radius:20px;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:36px;margin:0 auto 16px;overflow:hidden;border:2px solid var(--border2)}
.join-srv-icon img{width:100%;height:100%;object-fit:cover;border-radius:18px}
.join-srv-name{font-size:22px;font-weight:700;color:var(--gold);margin-bottom:8px;font-family:var(--font-heading)}
.join-srv-desc{font-size:14px;color:var(--text2);margin-bottom:24px;max-width:300px}
/* EMOJI PICKER */
#emojiPicker{position:fixed;background:var(--bg1);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow);z-index:900;width:316px;display:none;overflow:hidden;flex-direction:column}
#emojiPicker.open{display:flex}
.ep-tabs{display:flex;padding:4px;gap:2px;border-bottom:1px solid var(--border);flex-shrink:0;overflow-x:auto}
.ep-tab{width:32px;height:32px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;transition:.12s;flex-shrink:0}
.ep-tab:hover,.ep-tab.active{background:var(--bg4)}
.ep-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:1px;padding:6px;max-height:260px;overflow-y:auto;flex:1}
.ep-em{width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:var(--radius-sm);font-size:20px;cursor:pointer;transition:.1s}
.ep-em:hover{background:var(--bg4);transform:scale(1.15)}
.ep-custom{padding:6px 8px;border-top:1px solid var(--border);display:flex;gap:6px;flex-shrink:0;align-items:center}
.ep-custom-inp{flex:1;background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:4px 8px;color:var(--text);font-size:18px;outline:none;text-align:center;font-family:inherit}
.ep-custom-inp:focus{border-color:var(--gold3)}
.ep-custom-btn{background:var(--gold);color:var(--bg0);border:none;border-radius:var(--radius-sm);padding:4px 10px;cursor:pointer;font-weight:700;font-size:13px;transition:background .12s}
.ep-custom-btn:hover{background:var(--gold2)}
/* FORMAT TOOLBAR */
#formatToolbar{position:fixed;display:none;background:var(--bg1);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow);padding:3px 5px;z-index:1500;flex-direction:row;gap:1px;align-items:center;pointer-events:auto}
#formatToolbar.show{display:flex}
.fmt-btn{width:26px;height:26px;border-radius:4px;background:transparent;border:1px solid transparent;color:var(--text2);cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;transition:.1s;padding:0;font-family:'Consolas','Courier New',monospace;line-height:1}
.fmt-btn:hover{background:var(--bg4);border-color:var(--border2);color:var(--text)}
.fmt-sep{width:1px;height:18px;background:var(--border2);margin:0 2px;flex-shrink:0}
/* CONTEXT MENU */
#ctxMenu{position:fixed;background:var(--bg1);border:1px solid var(--border2);border-radius:var(--radius);box-shadow:var(--shadow);z-index:1000;min-width:170px;padding:4px;display:none}
#ctxMenu.open{display:block}
.ctx-em-row{display:flex;gap:2px;padding:4px 6px;border-bottom:1px solid var(--border);margin-bottom:2px}
.ctx-em-quick{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--radius-sm);font-size:18px;cursor:pointer;transition:.1s}
.ctx-em-quick:hover{background:var(--bg4);transform:scale(1.1)}
.ctx-em-more{flex:1;display:flex;align-items:center;justify-content:center;border-radius:var(--radius-sm);font-size:13px;color:var(--text3);cursor:pointer;transition:.1s}
.ctx-em-more:hover{background:var(--bg4);color:var(--text)}
.ctx-item{padding:7px 10px;border-radius:var(--radius-sm);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:flex-start;gap:8px;color:var(--text2);transition:.1s;font-family:var(--font-body),sans-serif;letter-spacing:0;word-spacing:0;text-align:left;white-space:nowrap}
.ctx-item:hover{background:var(--bg3);color:var(--text)}
#ctxMenu .ctx-item svg{flex-shrink:0}
.voice-user-card .ctx-item{width:100%;min-width:0}
.voice-user-card .ctx-volume-box,.voice-user-card .ctx-volume-top,.voice-user-card .ctx-volume-row{font-family:var(--font-body),sans-serif;letter-spacing:0;word-spacing:0}
.voice-user-card .ctx-volume-top span:first-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ctx-item.danger{color:var(--red)}.ctx-item.danger:hover{background:var(--red);color:#fff}
.ctx-sep{height:1px;background:rgba(255,255,255,0.08);margin:4px 8px;}
/* MODAL — Liquid Glass */
#modalBg{
  position:fixed;inset:0;
  background:rgba(0,0,0,.5);
  backdrop-filter:blur(20px) saturate(150%);-webkit-backdrop-filter:blur(20px) saturate(150%);
  z-index:500;display:none;align-items:center;justify-content:center;
  padding:16px;overflow-y:auto;
}
#modalBg.open{display:flex}
.modal{
  background:var(--glass-bg2,rgba(18,15,10,0.78));
  backdrop-filter:var(--glass-blur,blur(40px) saturate(180%));
  -webkit-backdrop-filter:var(--glass-blur,blur(40px) saturate(180%));
  border:1px solid var(--glass-border,rgba(255,255,255,0.10));
  border-top:1px solid rgba(255,255,255,0.14);
  border-radius:20px;
  box-shadow:var(--glass-shadow,0 12px 48px rgba(0,0,0,.75),0 0 0 1px rgba(255,255,255,.06),inset 0 1px 0 rgba(255,255,255,.08));
  padding:24px;
  width:100%;max-width:460px;
  max-height:calc(var(--app-h,100vh) - 32px);
  overflow-y:auto;position:relative;margin:auto;
}
.modal-close{
  position:absolute;top:12px;right:12px;z-index:20;
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);
  border-radius:50%;width:28px;height:28px;
  color:var(--text3);font-size:14px;cursor:pointer;line-height:1;
  display:flex;align-items:center;justify-content:center;
  transition:background .15s,color .15s;
}
.modal-close:hover{background:rgba(255,255,255,.14);color:var(--text)}
.modal-close .ti{display:flex;align-items:center;justify-content:center;}
.modal-close .ti{display:flex;align-items:center;justify-content:center;}
.modal-close .ti svg{width:14px;height:14px;display:block;}
.modal h2{font-size:17px;font-weight:700;color:var(--gold);margin-bottom:4px}
.modal .sub{font-size:13px;color:var(--text3);margin-bottom:18px}
.fg{margin-bottom:14px}
.fl{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text2);margin-bottom:5px;font-family:var(--font-heading)}
.fi{
  width:100%;
  background:var(--bg0);
  border:1px solid var(--border2);
  border-radius:10px;padding:9px 12px;color:var(--text);font-size:14px;outline:none;
  font-family:inherit;transition:border-color .15s;
  -webkit-appearance:none;appearance:none;
}
.fi:focus{border-color:var(--gold3)}
textarea.fi{resize:vertical;min-height:72px}
select.fi{cursor:pointer}
.fi-hint{font-size:12px;color:var(--text3);margin-top:3px}
.btn{padding:10px 18px;border-radius:13px;border:1px solid transparent;font-size:14px;font-weight:650;cursor:pointer;transition:transform .12s cubic-bezier(.2,.8,.2,1),background .15s,box-shadow .15s,border-color .15s;font-family:inherit;letter-spacing:.01em;-webkit-tap-highlight-color:transparent}
.btn:active{transform:scale(.96)}
.btn-full{width:100%}
.btn-gold{background:linear-gradient(180deg,var(--gold),var(--gold2));color:#fff;box-shadow:0 6px 18px color-mix(in srgb,var(--gold) 35%,transparent),inset 0 1px 0 rgba(255,255,255,.22)}
.btn-gold:hover{box-shadow:0 8px 24px color-mix(in srgb,var(--gold) 45%,transparent),inset 0 1px 0 rgba(255,255,255,.28)}
.btn-red{background:linear-gradient(180deg,var(--red2),var(--red));color:#fff;box-shadow:0 6px 18px color-mix(in srgb,var(--red) 35%,transparent),inset 0 1px 0 rgba(255,255,255,.18)}
.btn-red:hover{box-shadow:0 8px 24px color-mix(in srgb,var(--red) 45%,transparent),inset 0 1px 0 rgba(255,255,255,.24)}
.btn-ghost{background:var(--glass-bg,color-mix(in srgb,var(--bg3) 65%,transparent));color:var(--text2);border:1px solid var(--glass-border,var(--border2));backdrop-filter:blur(16px) saturate(1.3);-webkit-backdrop-filter:blur(16px) saturate(1.3);box-shadow:inset 0 1px 0 rgba(255,255,255,.06)}
.btn-ghost:hover{background:color-mix(in srgb,var(--bg4) 80%,transparent);color:var(--text);border-color:color-mix(in srgb,var(--gold) 30%,var(--border2))}
.btn-row{display:flex;gap:8px;margin-top:10px}.btn-row .btn{flex:1}
.av-upload{display:flex;flex-direction:column;align-items:center;gap:10px;margin-bottom:16px}
.av-preview{width:80px;height:80px;border-radius:50%;background:var(--bg3);overflow:hidden;cursor:pointer;position:relative;display:flex;align-items:center;justify-content:center;font-size:30px;border:2px solid var(--border2)}
.av-preview img{width:100%;height:100%;object-fit:cover}
.av-ov{position:absolute;inset:0;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;opacity:0;transition:.15s;font-size:22px}
.av-preview:hover .av-ov{opacity:1}
.srv-av-preview{border-radius:14px!important}
/* TERMS */
.terms-scroll{max-height:220px;overflow-y:auto;background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;font-size:12px;color:var(--text3);line-height:1.7;margin-bottom:10px}
.terms-scroll h4{color:var(--gold2);font-size:12px;margin:8px 0 3px;font-family:var(--font-heading)}
.terms-check{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:var(--text2);margin-bottom:10px;cursor:pointer}
.terms-check input[type=checkbox]{width:16px;height:16px;flex-shrink:0;margin-top:2px;cursor:pointer;accent-color:var(--gold)}
.remember-check{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text2);margin:10px 0;cursor:pointer;user-select:none}
.remember-check input[type=checkbox]{width:15px;height:15px;flex-shrink:0;cursor:pointer;accent-color:var(--gold)}
.remember-check .rc-hint{font-size:11px;color:var(--text3);margin-top:2px}
.val-badge{display:inline-flex;align-items:center;gap:3px;background:rgba(78,145,96,.2);border:1px solid rgba(78,145,96,.4);border-radius:4px;padding:1px 6px;font-size:11px;color:var(--green);font-weight:600}
.rm-role-item{display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:var(--radius-sm);cursor:pointer;font-size:12px;font-weight:600;color:var(--text);transition:.12s}
.rm-role-item:hover{background:var(--bg3)}
.rm-role-item.active{background:var(--bg4);border-left:2px solid var(--gold)}
.unval-badge{background:rgba(180,60,60,.15);border-color:rgba(180,60,60,.3);color:var(--red2)}
/* ROLE BADGE */
.role-badge{display:inline-flex;align-items:center;gap:3px;border-radius:4px;padding:1px 6px;font-size:11px;font-weight:600;margin-left:4px;vertical-align:middle}
/* LIGHTBOX */
#lightbox{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:2000;display:none;align-items:center;justify-content:center;cursor:zoom-out}
#lightbox.open{display:flex}
#lightbox img{max-width:88vw;max-height:calc(var(--app-h,100vh) * 0.92);border-radius:var(--radius);object-fit:contain;cursor:default}
/* Glass controls for the image lightbox */
.lb-glass-btn{
  position:fixed;z-index:2010;display:flex;align-items:center;justify-content:center;
  border:1px solid rgba(255,255,255,.18);color:#fff;cursor:pointer;
  background:rgba(255,255,255,.10);
  backdrop-filter:blur(18px) saturate(160%);-webkit-backdrop-filter:blur(18px) saturate(160%);
  box-shadow:0 8px 32px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.18);
  transition:background .15s,transform .15s,opacity .15s;
}
.lb-glass-btn:hover{background:rgba(255,255,255,.20);transform:scale(1.06)}
.lb-glass-btn:active{transform:scale(.96)}
.lb-close{top:calc(16px + var(--safe-top,0px));right:16px;width:44px;height:44px;border-radius:50%}
.lb-nav{top:50%;transform:translateY(-50%);width:52px;height:52px;border-radius:50%}
.lb-nav:hover{transform:translateY(-50%) scale(1.06)}
.lb-nav:active{transform:translateY(-50%) scale(.96)}
.lb-prev{left:16px}
.lb-next{right:16px}
.lb-nav[hidden],.lb-counter[hidden]{display:none!important}
.lb-counter{
  position:fixed;bottom:calc(20px + var(--safe-bottom,0px));left:50%;transform:translateX(-50%);
  z-index:2010;padding:6px 14px;border-radius:999px;color:#fff;font-size:13px;font-weight:600;
  background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.16);
  backdrop-filter:blur(18px) saturate(160%);-webkit-backdrop-filter:blur(18px) saturate(160%);
  box-shadow:0 8px 32px rgba(0,0,0,.45);pointer-events:none;
}
@media(max-width:640px){
  .lb-nav{width:46px;height:46px}
  .lb-prev{left:8px}.lb-next{right:8px}
  .lb-close{right:10px;width:40px;height:40px}
  #lightbox img{max-width:94vw}
}
/* Mosaic gallery in messages */
.msg-gallery{display:grid;gap:3px;margin-top:6px;max-width:min(420px,80vw);border-radius:var(--radius);overflow:hidden}
.msg-gallery.g-1{grid-template-columns:1fr}
.msg-gallery.g-2{grid-template-columns:1fr 1fr}
.msg-gallery.g-3{grid-template-columns:1fr 1fr}
.msg-gallery.g-4{grid-template-columns:1fr 1fr}
.msg-gallery.g-many{grid-template-columns:1fr 1fr 1fr}
.msg-gallery .g-cell{position:relative;overflow:hidden;cursor:pointer;background:var(--bg2);aspect-ratio:1/1}
.msg-gallery .g-cell img{width:100%;height:100%;object-fit:contain;object-position:center;display:block;transition:transform .2s}
.msg-gallery .g-cell:hover img{transform:scale(1.04)}
/* 3 images: first tall on the left */
.msg-gallery.g-3 .g-cell:first-child{grid-row:span 2;aspect-ratio:auto}
/* single image keeps a natural look */
.msg-gallery.g-1 .g-cell{aspect-ratio:auto;max-height:300px}
.msg-gallery.g-1 .g-cell img{object-fit:contain;background:var(--bg2);max-height:300px}
.msg-gallery .g-more{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.55);color:#fff;font-size:22px;font-weight:700;backdrop-filter:blur(2px)}
.msg-attach-group{display:flex;flex-direction:column;gap:6px;margin-top:6px}
/* MINI-APP — Liquid Glass */
.ch-item.ch-app .ch-icon{background:var(--gold-dim);color:var(--gold);font-size:14px}
.ch-item.ch-app .ch-name{color:var(--gold2)}
.ch-item.ch-app:hover .ch-name{color:var(--gold)}
#miniAppContainer{position:fixed;inset:0;pointer-events:none;z-index:5500;}
.ma-window{
  position:absolute;pointer-events:auto;display:none;flex-direction:column;
  background:var(--glass-bg,rgba(22,19,16,0.88));
  backdrop-filter:var(--glass-blur,blur(28px));
  -webkit-backdrop-filter:var(--glass-blur,blur(28px));
  border:1px solid var(--glass-border,rgba(201,170,113,0.18));
  border-radius:14px;
  box-shadow:var(--glass-shadow,0 20px 60px rgba(0,0,0,.85),0 0 0 1px rgba(201,170,113,.12),inset 0 1px 0 rgba(255,255,255,.06));
  overflow:hidden;min-width:280px;min-height:160px;
}
.ma-window.open{display:flex}

/* ── свёрнутое состояние — плавающая иконка ── */
.ma-window.ma-minimized .mini-app-body,
.ma-window.ma-minimized .ma-resize-handle{display:none!important}
.ma-window.ma-minimized .maBtnMax,
.ma-window.ma-minimized .maBtnFs{display:none!important}
.ma-window.ma-minimized{
  width:auto!important;min-width:0!important;max-width:220px!important;
  height:auto!important;min-height:0!important;
  top:auto!important;right:auto!important;
  bottom:calc(76px + var(--safe-bottom,0px))!important;
  left:12px!important;
  transform:none!important;
  border-radius:28px!important;
  border:1px solid rgba(201,170,113,.35)!important;
  box-shadow:0 6px 28px rgba(0,0,0,.8),0 0 0 2px rgba(201,170,113,.18),inset 0 1px 0 rgba(255,255,255,.1)!important;
  background:linear-gradient(135deg,rgba(49,44,31,.95),rgba(39,35,24,.95))!important;
  backdrop-filter:blur(24px)!important;
  -webkit-backdrop-filter:blur(24px)!important;
}
.ma-window.ma-minimized .mini-app-header{
  border-radius:28px!important;
  border-bottom:none!important;
  padding:8px 14px 8px 10px!important;
  cursor:pointer!important;
  background:transparent!important;
  gap:6px!important;
}
.ma-window.ma-minimized .mini-app-header:hover{
  background:rgba(255,255,255,.05)!important;
}
.ma-window.ma-minimized .ma-icon{
  font-size:22px!important;
  width:32px!important;height:32px!important;
  display:flex!important;align-items:center!important;justify-content:center!important;
  background:rgba(201,170,113,.12)!important;
  border-radius:50%!important;
  flex-shrink:0!important;
}
.ma-window.ma-minimized .ma-title{
  font-size:12px!important;
  max-width:120px!important;
  color:var(--gold)!important;
  opacity:.9!important;
}
/* В свёрнутом — скрываем всё кроме кнопок Min и Close */
.ma-window.ma-minimized .maBtnMax,
.ma-window.ma-minimized .maBtnFs{display:none!important;}
.ma-window.ma-minimized .ma-close-btn{display:flex!important;}
.ma-window.ma-minimized .maBtnMin{
  background:rgba(255,255,255,.08)!important;
  border-radius:50%!important;
  width:22px!important;height:22px!important;
  font-size:11px!important;
}

/* ── полноэкранный режим ── */
.ma-window:-webkit-full-screen{width:100%!important;height:100%!important;border-radius:0!important}
.ma-window:-moz-full-screen{width:100%!important;height:100%!important;border-radius:0!important}
.ma-window:fullscreen{width:100%!important;height:100%!important;border-radius:0!important}

.mini-app-header{
  display:flex;align-items:center;gap:8px;padding:9px 12px;
  background:rgba(255,255,255,.03);
  border-bottom:1px solid rgba(201,170,113,.12);
  flex-shrink:0;cursor:move;user-select:none;
}
.mini-app-header .ma-icon{font-size:20px;flex-shrink:0;pointer-events:none}
.mini-app-header .ma-title{
  flex:1;font-size:14px;font-weight:700;color:var(--gold);
  font-family:var(--font-heading);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  pointer-events:none;
}
.ma-btns{display:flex;gap:2px;flex-shrink:0}
.ma-wbtn{
  width:26px;height:26px;border-radius:6px;
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);
  color:var(--text3);font-size:13px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:background .12s,color .12s;flex-shrink:0;font-family:inherit;line-height:1;
}
.ma-wbtn:hover{background:rgba(255,255,255,.12);color:var(--text);border-color:rgba(255,255,255,.12)}
.ma-wbtn.ma-close-btn:hover{background:var(--red)!important;color:#fff;border-color:var(--red)!important}
.mini-app-body{flex:1;overflow:hidden;background:#fff;min-height:0;position:relative}
.mini-app-body iframe{width:100%;height:100%;border:none;display:block}
.ma-resize-handle{
  position:absolute;bottom:0;right:0;width:20px;height:20px;
  cursor:nwse-resize;z-index:10;
}
.ma-resize-handle::before{
  content:'';position:absolute;bottom:3px;right:3px;
  border-style:solid;border-width:0 0 10px 10px;
  border-color:transparent transparent var(--border2) transparent;
  transition:border-color .12s;
}
.ma-resize-handle:hover::before{border-color:transparent transparent var(--gold3) transparent}

@media(max-width:740px){
  .ma-window.open{
    top:0!important;left:0!important;transform:none!important;
    width:100vw!important;height:var(--app-h,100vh)!important;
    border-radius:0!important;
  }
  .ma-window.ma-minimized.open{
    height:auto!important;top:auto!important;right:auto!important;
    bottom:calc(70px + var(--safe-bottom,0px))!important;
    left:10px!important;width:auto!important;
    border-radius:28px!important;
  }
  .ma-resize-handle{display:none!important}
}

/* JOIN SERVER MODAL */
.join-modal-icon{width:80px;height:80px;border-radius:20px;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:36px;margin:0 auto 16px;overflow:hidden;border:2px solid var(--border2)}
.join-modal-icon img{width:100%;height:100%;object-fit:cover;border-radius:18px}
.join-modal-name{font-size:22px;font-weight:700;color:var(--gold);margin-bottom:8px;font-family:var(--font-heading);text-align:center}
.join-modal-desc{font-size:14px;color:var(--text2);margin-bottom:16px;max-width:300px;text-align:center;margin-left:auto;margin-right:auto}
.join-modal-members{font-size:12px;color:var(--text3);text-align:center;margin-bottom:20px}
/* SERVER ADMIN PANEL */
.srv-admin-section{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin:12px 0 6px;font-family:var(--font-heading)}
/* MEDIA PLAYER MODAL */
#mediaPlayerModal{position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:2500;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
#mediaPlayerModal.open{display:flex}
.media-player-wrap{background:var(--bg1);border:1px solid var(--border2);border-radius:var(--radius);padding:20px;max-width:min(700px,95vw);width:100%;max-height:calc(var(--app-h,100vh) * 0.9);overflow-y:auto;position:relative;display:flex;flex-direction:column;gap:14px}
.media-player-header{display:flex;align-items:center;gap:10px;flex-shrink:0}
.media-player-title{flex:1;font-size:15px;font-weight:700;color:var(--gold);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:var(--font-heading)}
.media-player-content audio{width:100%;outline:none;border-radius:var(--radius-sm);background:var(--bg3)}
.media-player-content video{width:100%;max-height:60vh;border-radius:var(--radius-sm);background:#000;outline:none}
.media-player-content img{width:100%;max-height:70vh;object-fit:contain;border-radius:var(--radius-sm);cursor:zoom-in}
.media-player-content .text-view{background:var(--bg0);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;font-family:'Consolas','Courier New',monospace;font-size:13px;color:var(--text2);white-space:pre-wrap;max-height:60vh;overflow-y:auto}
.media-player-nav{display:flex;gap:8px;flex-shrink:0}
/* ── NOTIF BELL PANEL ───────────────────────────────────────── */
.up-notif-btn{
  position:relative;width:30px;height:30px;border-radius:var(--radius-sm);
  background:transparent;border:none;color:var(--text3);cursor:pointer;
  display:flex;align-items:center;justify-content:center;font-size:16px;
  transition:.12s;flex-shrink:0;
}
.up-notif-btn:hover{background:var(--bg4);color:var(--text)}
.up-notif-btn.has-notif{color:var(--gold);animation:bellShake .6s ease-in-out;}
.up-notif-btn .ti{display:flex;align-items:center;justify-content:center;}
.up-notif-btn .ti svg{width:18px;height:18px;display:block;}
@keyframes bellShake{0%,100%{transform:rotate(0)}20%{transform:rotate(-18deg)}40%{transform:rotate(16deg)}60%{transform:rotate(-12deg)}80%{transform:rotate(8deg)}}

.up-notif-badge{
  position:absolute;top:-3px;right:-3px;
  min-width:16px;height:16px;border-radius:8px;
  background:var(--red2);border:2px solid var(--bg0);
  display:none;font-size:9px;font-weight:700;color:#fff;
  align-items:center;justify-content:center;padding:0 2px;
  pointer-events:none;
}
.up-notif-badge.show{display:flex}
#notifPanel{
  position:fixed;bottom:64px;left:72px;z-index:3500;
  width:320px;max-height:480px;
  background:rgba(10,8,6,0.82);
  border:1px solid rgba(255,255,255,0.10);
  border-top:1px solid rgba(255,255,255,0.16);
  border-radius:20px;
  box-shadow:0 20px 60px rgba(0,0,0,.85),0 0 0 1px rgba(255,255,255,.05),inset 0 1px 0 rgba(255,255,255,.10);
  backdrop-filter:blur(40px) saturate(180%);-webkit-backdrop-filter:blur(40px) saturate(180%);
  display:none;flex-direction:column;overflow:hidden;
  animation:notifPanelIn .22s cubic-bezier(.34,1.56,.64,1);
}
#notifPanel.open{display:flex}
@keyframes notifPanelIn{from{opacity:0;transform:translateY(12px) scale(.95)}to{opacity:1;transform:none}}
#notifPanel .np-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 14px 10px;border-bottom:1px solid rgba(255,255,255,.06);
  flex-shrink:0;
}
#notifPanel .np-header-title{
  font-size:13px;font-weight:700;color:var(--gold);
  font-family:var(--font-heading);
}
#notifPanel .np-header-actions{display:flex;gap:6px;align-items:center}
#notifPanel .np-clear-all{
  font-size:10px;padding:3px 9px;border-radius:99px;border:none;
  background:rgba(255,255,255,.08);color:rgba(255,255,255,.5);
  cursor:pointer;font-family:inherit;transition:.12s;
}
#notifPanel .np-clear-all:hover{background:rgba(255,255,255,.16);color:#fff}
#notifPanel .np-close{
  width:22px;height:22px;border-radius:50%;border:none;
  background:rgba(255,255,255,.07);color:rgba(255,255,255,.45);
  cursor:pointer;display:flex;align-items:center;justify-content:center;
  transition:.12s;
}
#notifPanel .np-close svg{display:block;}
#notifPanel .np-close:hover{background:rgba(255,255,255,.15);color:#fff}
#notifPanel .np-list{flex:1;overflow-y:auto;padding:6px 8px}
#notifPanel .np-empty{
  padding:32px 16px;text-align:center;font-size:13px;color:rgba(255,255,255,.3);
}
#notifPanel .np-item{
  display:flex;align-items:flex-start;gap:10px;
  padding:9px 10px;border-radius:10px;cursor:pointer;
  transition:background .12s;margin-bottom:2px;position:relative;
}
#notifPanel .np-item:hover{background:var(--bg3)}
#notifPanel .np-item-av{
  width:34px;height:34px;border-radius:50%;flex-shrink:0;
  background:var(--bg4);display:flex;align-items:center;
  justify-content:center;font-size:15px;overflow:hidden;color:var(--text2);
}
#notifPanel .np-item-av img{width:100%;height:100%;object-fit:cover;border-radius:50%}
#notifPanel .np-item-body{flex:1;min-width:0}
#notifPanel .np-item-title{
  font-size:12px;font-weight:700;color:var(--text);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  display:flex;align-items:center;gap:5px;
}
#notifPanel .np-item-text{
  font-size:11px;color:var(--text3);margin-top:2px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
#notifPanel .np-item-del{
  width:20px;height:20px;border-radius:6px;border:none;
  background:transparent;color:var(--text4);
  cursor:pointer;flex-shrink:0;align-self:center;
  display:flex;align-items:center;justify-content:center;transition:.12s;
  padding:0;
}
#notifPanel .np-item-del svg{display:block;}
#notifPanel .np-item-del:hover{background:var(--bg4);color:var(--text)}
#notifPanel .np-reply-btn{
  padding:4px 9px;border-radius:8px;border:1px solid rgba(201,170,113,.2);
  background:rgba(201,170,113,.10);color:rgba(201,170,113,.85);
  font-size:10px;font-weight:600;cursor:pointer;font-family:inherit;
  transition:.12s;white-space:nowrap;flex-shrink:0;align-self:center;
  display:flex;align-items:center;
}
#notifPanel .np-reply-btn:hover{background:rgba(201,170,113,.35)}
@media(max-width:740px){
  #notifPanel{left:8px;bottom:62px;width:calc(100vw - 16px);}
}
/* TOAST */
#toastBox{position:fixed;bottom:160px;right:12px;z-index:3000;display:flex;flex-direction:column;gap:6px;pointer-events:none}
.toast{background:var(--bg1);border:1px solid var(--border2);padding:8px 14px;border-radius:var(--radius);font-size:13px;box-shadow:var(--shadow);animation:toastIn .2s;max-width:280px;display:flex;align-items:center;gap:8px;pointer-events:auto}
.toast.ok{border-left:3px solid var(--green)}.toast.err{border-left:3px solid var(--red2)}.toast.info{border-left:3px solid var(--gold)}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:none}}
.notif-popup{position:fixed;top:12px;right:12px;z-index:3001;background:var(--bg1);border:1px solid var(--border2);border-left:3px solid var(--gold);border-radius:var(--radius);padding:10px 14px;box-shadow:var(--shadow);max-width:280px;animation:toastIn .2s;cursor:pointer}
.notif-popup .np-title{font-size:12px;font-weight:700;color:var(--gold);margin-bottom:2px;font-family:var(--font-heading)}
.notif-popup .np-text{font-size:13px;color:var(--text2);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:240px}

/* ══════════════════════════════════════════════
   DYNAMIC ISLAND — iPhone style
══════════════════════════════════════════════ */
#dynamicIsland{
  position:fixed;top:12px;left:50%;
  transform:translateX(-50%);
  z-index:4000;pointer-events:none;
  display:flex;flex-direction:column;align-items:center;
}
#diPill{
  background:rgba(6,5,3,0.85);
  border:1px solid rgba(255,255,255,.10);
  border-top:1px solid rgba(255,255,255,.16);
  border-radius:99px;
  box-shadow:0 8px 32px rgba(0,0,0,.9),0 0 0 1px rgba(255,255,255,.04),inset 0 1px 0 rgba(255,255,255,.10);
  backdrop-filter:blur(40px) saturate(200%);-webkit-backdrop-filter:blur(40px) saturate(200%);
  display:flex;align-items:center;overflow:hidden;
  min-width:0;max-width:min(500px,92vw);
  pointer-events:auto;cursor:pointer;user-select:none;
  /* Все переходы одним transition — как у iPhone */
  transition:
    width .5s cubic-bezier(.25,.46,.45,.94),
    min-width .5s cubic-bezier(.25,.46,.45,.94),
    height .5s cubic-bezier(.25,.46,.45,.94),
    border-radius .5s cubic-bezier(.25,.46,.45,.94),
    opacity .35s ease,
    transform .35s cubic-bezier(.34,1.56,.64,1);
  will-change:transform,opacity,width,height,border-radius;
}

/* СОСТОЯНИЯ — скрыто */
#diPill.di-hidden{
  opacity:0;
  transform:scale(.5) translateY(-8px);
  pointer-events:none;
  transition:opacity .25s ease,transform .25s cubic-bezier(.55,.06,.68,.19);
}
/* СОСТОЯНИЯ — компактная пилюля */
#diPill.di-compact{
  height:36px;border-radius:99px;
  opacity:1;transform:scale(1) translateY(0);
  min-width:120px;
}
/* СОСТОЯНИЯ — флэш уведомления (шире) */
#diPill.di-notif{
  height:36px;border-radius:99px;
  opacity:1;transform:scale(1) translateY(0);
  min-width:200px;
}
/* СОСТОЯНИЯ — развёрнуто */
#diPill.di-expanded{
  border-radius:26px;
  opacity:1;transform:scale(1) translateY(0);
  min-width:280px;
  /* Позволяем высоте расти по содержимому */
  height:auto;
  align-items:flex-start;
}

/* Анимация появления — пульс как у iPhone */
@keyframes diAppear{
  0%  {transform:scale(.4) translateY(-6px);opacity:0;}
  60% {transform:scale(1.04) translateY(1px);opacity:1;}
  80% {transform:scale(.98);}
  100%{transform:scale(1) translateY(0);opacity:1;}
}
@keyframes diDisappear{
  0%  {transform:scale(1);opacity:1;}
  100%{transform:scale(.5) translateY(-8px);opacity:0;}
}
#diPill.di-anim-in{animation:diAppear .45s cubic-bezier(.34,1.56,.64,1) forwards;}
#diPill.di-anim-out{animation:diDisappear .3s cubic-bezier(.55,.06,.68,.19) forwards;}

/* ── Compact row ─────────────────────────────── */
#diCompact{
  display:flex;align-items:center;gap:8px;
  padding:0 14px;height:36px;width:100%;flex-shrink:0;
  min-width:0;overflow:hidden;
}
#diPill.di-expanded #diCompact{display:none;}
.di-left{display:flex;align-items:center;gap:7px;flex:1;min-width:0;}
.di-icon{font-size:0;flex-shrink:0;line-height:1;display:flex;align-items:center;justify-content:center;}
.di-icon svg{display:block;width:14px;height:14px;}
.di-text{
  font-size:12px;font-weight:500;color:rgba(255,255,255,.85);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;
  letter-spacing:-.01em;
}
.di-right{display:flex;align-items:center;gap:5px;flex-shrink:0;}
/* Живая точка */
.di-dot{
  width:7px;height:7px;border-radius:50%;
  background:var(--gold);flex-shrink:0;
  animation:diDotPulse 1.6s ease-in-out infinite;
}
@keyframes diDotPulse{
  0%,100%{opacity:1;transform:scale(1);}
  50%{opacity:.35;transform:scale(.65);}
}
.di-dot.di-dot-red{background:var(--red2);}
.di-dot.di-dot-green{background:var(--green);}

/* Mini play button */
.di-mini-play{
  width:24px;height:24px;border-radius:50%;border:none;
  background:rgba(201,170,113,.18);color:rgba(201,170,113,.9);
  cursor:pointer;font-size:11px;
  display:flex;align-items:center;justify-content:center;
  transition:background .12s;flex-shrink:0;
}
.di-mini-play:hover{background:rgba(201,170,113,.32);}

/* ── Expanded content ────────────────────────── */
#diExpanded{
  display:none;flex-direction:column;width:100%;
  padding:16px 16px 14px;gap:12px;
  /* Содержимое должно определять высоту */
  flex-shrink:0;
}
#diPill.di-expanded #diExpanded{display:flex;}
.di-exp-header{display:flex;align-items:center;gap:10px;}
.di-exp-icon{font-size:26px;flex-shrink:0;}
.di-exp-info{flex:1;min-width:0;}
.di-exp-title{
  font-size:14px;font-weight:700;color:#fff;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  font-family:var(--font-heading);letter-spacing:-.01em;
}
.di-exp-sub{
  font-size:11px;color:rgba(255,255,255,.45);margin-top:2px;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.di-exp-close{
  width:24px;height:24px;border-radius:50%;
  background:rgba(255,255,255,.1);border:none;
  color:rgba(255,255,255,.5);cursor:pointer;font-size:11px;
  display:flex;align-items:center;justify-content:center;transition:.15s;flex-shrink:0;
}
.di-exp-close:hover{background:rgba(255,255,255,.2);color:#fff;}
.di-exp-close .ti svg{width:12px;height:12px;display:block;}

/* Controls */
.di-exp-controls{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.di-ctrl-btn{
  padding:6px 12px;border-radius:99px;border:none;
  font-size:12px;font-weight:600;cursor:pointer;
  font-family:inherit;transition:.15s;
  display:flex;align-items:center;justify-content:center;gap:4px;letter-spacing:-.01em;
  min-width:32px;
}
.di-ctrl-btn svg{display:block;flex-shrink:0;}
.di-ctrl-primary{background:var(--gold);color:var(--bg0);}
.di-ctrl-primary:hover{background:var(--gold2);}
.di-ctrl-secondary{background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);}
.di-ctrl-secondary:hover{background:rgba(255,255,255,.18);color:#fff;}
.di-ctrl-danger{background:rgba(180,30,30,.35);color:#ff6b6b;}
.di-ctrl-danger:hover{background:rgba(180,30,30,.6);color:#fff;}

/* Progress */
.di-progress{
  width:100%;height:3px;background:rgba(255,255,255,.12);
  border-radius:2px;overflow:hidden;cursor:pointer;transition:height .15s;
}
.di-progress:hover{height:5px;}
.di-progress-bar{
  height:100%;
  background:linear-gradient(90deg,rgba(201,170,113,.6),var(--gold));
  border-radius:2px;width:0;transition:width .4s linear;
}

/* Notif list */
.di-notif-list{display:flex;flex-direction:column;gap:5px;max-height:200px;overflow-y:auto;}
.di-notif-item{
  display:flex;align-items:flex-start;gap:8px;
  padding:8px 10px;border-radius:12px;
  background:rgba(255,255,255,.05);
  cursor:pointer;transition:background .12s;
}
.di-notif-item:hover{background:rgba(255,255,255,.1);}
.di-notif-av{
  width:30px;height:30px;border-radius:50%;
  background:rgba(255,255,255,.1);
  display:flex;align-items:center;justify-content:center;
  font-size:14px;flex-shrink:0;overflow:hidden;
}
.di-notif-av img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.di-notif-body{flex:1;min-width:0;}
.di-notif-title{font-size:12px;font-weight:600;color:#fff;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.di-notif-text{font-size:11px;color:rgba(255,255,255,.45);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-top:1px;}
.di-notif-reply{
  padding:3px 9px;border-radius:99px;border:none;
  background:rgba(201,170,113,.18);color:rgba(201,170,113,.9);
  font-size:10px;font-weight:600;cursor:pointer;font-family:inherit;
  transition:.12s;white-space:nowrap;flex-shrink:0;align-self:center;
}
.di-notif-reply:hover{background:rgba(201,170,113,.35);}

/* Мобильная оптимизация Dynamic Island */
@media(max-width:740px){
  #dynamicIsland{
    top:calc(6px + env(safe-area-inset-top, 0px));
    z-index:8000;
    left:50%;
    transform:translateX(-50%);
    /* position:fixed уже на родителе, явно повторяем для надёжности */
    position:fixed;
  }
  #diPill{
    max-width:min(340px, calc(100vw - 16px));
  }
  #diPill.di-compact,#diPill.di-notif{
    height:34px;
  }
  #diCompact{
    height:34px;
    padding:0 10px;
    gap:6px;
  }
  .di-text{ font-size:11px; }
  .di-icon{ font-size:0; }
  .di-icon svg{width:13px;height:13px;}
  /* Expanded на мобильном — растягиваем на всю ширину */
  #diPill.di-expanded{
    min-width:calc(100vw - 32px) !important;
    max-width:calc(100vw - 32px) !important;
    border-radius:20px !important;
    /* height:auto — важно для мобильного */
    height:auto !important;
    align-items:flex-start !important;
  }
  #diExpanded{
    padding:12px 14px 12px;
    gap:10px;
    width:100%;
  }
  .di-exp-title{ font-size:13px; }
  .di-notif-list{ max-height:50vh; overflow-y:auto; }
  /* Прижимаем DI ближе к краю когда открыта мобильная клавиатура */
  body.keyboard-open #dynamicIsland{
    opacity:0.35;
    pointer-events:none;
    transform:translateX(-50%) scale(0.8) translateY(-2px);
  }
}
/* Планшет */
@media(min-width:741px) and (max-width:1024px){
  #diPill.di-expanded{
    min-width:320px;
    max-width:420px;
  }
}

/* ══════════════════════════════════════════════
   QUICK REPLY POPOVER
══════════════════════════════════════════════ */
#quickReplyContainer{
  position:fixed;bottom:calc(80px + var(--safe-bottom,0px));right:12px;
  z-index:3900;display:flex;flex-direction:column;gap:8px;
  align-items:flex-end;pointer-events:none;
}
.qr-window{
  background:rgba(10,8,6,0.82);
  border:1px solid rgba(255,255,255,0.10);
  border-top:1px solid rgba(255,255,255,0.16);
  border-radius:20px;
  box-shadow:0 16px 48px rgba(0,0,0,.85),0 0 0 1px rgba(255,255,255,.05),
             inset 0 1px 0 rgba(255,255,255,.10);
  backdrop-filter:blur(40px) saturate(180%);-webkit-backdrop-filter:blur(40px) saturate(180%);
  width:300px;max-width:calc(100vw - 24px);
  display:flex;flex-direction:column;overflow:hidden;
  pointer-events:auto;
  animation:qrSlideIn .25s cubic-bezier(.34,1.56,.64,1);
}
@keyframes qrSlideIn{from{opacity:0;transform:translateY(16px) scale(.95)}to{opacity:1;transform:none}}
.qr-header{
  display:flex;align-items:center;gap:8px;
  padding:10px 12px 8px;border-bottom:1px solid rgba(255,255,255,.05);
  flex-shrink:0;
}
.qr-av{
  width:26px;height:26px;border-radius:50%;background:var(--bg4);
  display:flex;align-items:center;justify-content:center;
  font-size:12px;overflow:hidden;flex-shrink:0;
}
.qr-av img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.qr-title{flex:1;font-size:12px;font-weight:700;color:var(--gold);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:var(--font-heading);}
.qr-sub{font-size:10px;color:var(--text3);}
.qr-close{
  width:20px;height:20px;border-radius:50%;background:rgba(255,255,255,.07);
  border:none;color:var(--text3);cursor:pointer;font-size:10px;
  display:flex;align-items:center;justify-content:center;transition:.12s;flex-shrink:0;
}
.qr-close:hover{background:rgba(255,255,255,.14);color:var(--text)}
.qr-msgs{
  flex:1;overflow-y:auto;padding:8px 10px;
  display:flex;flex-direction:column;gap:4px;
  max-height:160px;min-height:40px;
}
.qr-msg{
  display:flex;flex-direction:column;gap:1px;
  padding:5px 8px;border-radius:8px;max-width:90%;
}
.qr-msg.qr-mine{align-self:flex-end;background:rgba(201,170,113,.15);}
.qr-msg.qr-other{align-self:flex-start;background:rgba(255,255,255,.05);}
.qr-msg-name{font-size:9px;font-weight:700;color:var(--gold);opacity:.7;}
.qr-msg-text{font-size:12px;color:var(--text);line-height:1.4;word-break:break-word;}
.qr-msg-time{font-size:9px;color:var(--text4);align-self:flex-end;}
.qr-input-row{
  display:flex;align-items:flex-end;gap:6px;
  padding:8px 10px 10px;border-top:1px solid rgba(255,255,255,.05);
  flex-shrink:0;
}
.qr-textarea{
  flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);
  border-radius:10px;padding:7px 10px;font-size:13px;color:var(--text);
  font-family:inherit;resize:none;outline:none;
  max-height:80px;min-height:34px;line-height:1.4;
  transition:border-color .12s;
}
.qr-textarea:focus{border-color:rgba(201,170,113,.35);}
.qr-send{
  width:32px;height:32px;border-radius:50%;background:var(--gold);
  border:none;color:var(--bg0);cursor:pointer;font-size:14px;
  display:flex;align-items:center;justify-content:center;
  transition:.12s;flex-shrink:0;
}
.qr-send:hover{background:var(--gold2);transform:scale(1.08);}
.qr-open-btn{
  padding:4px 10px;border-radius:99px;border:none;
  background:rgba(201,170,113,.12);color:var(--gold);
  font-size:10px;font-weight:600;cursor:pointer;font-family:inherit;
  transition:.12s;margin-left:auto;
}
.qr-open-btn:hover{background:rgba(201,170,113,.25);}
@media(max-width:740px){
  #quickReplyContainer{right:8px;bottom:calc(70px + var(--safe-bottom,0px));}
  .qr-window{width:calc(100vw - 16px);max-width:none;}
  #dynamicIsland{top:6px;}
  #diPill{min-width:100px;height:32px;}
}

/* FIRST VISIT LOADER */
#firstVisitLoader{
  position:fixed;inset:0;z-index:20000;display:none;align-items:center;justify-content:center;
  background:
    radial-gradient(circle at 50% 22%,rgba(201,170,113,.20),transparent 34%),
    radial-gradient(circle at 14% 80%,rgba(201,170,113,.10),transparent 30%),
    linear-gradient(180deg,var(--bg0) 0%,#070605 100%);
  color:var(--text);overflow:hidden;padding:24px;opacity:1;transition:opacity .42s ease,visibility .42s ease;
}
html.tc-first-visit #firstVisitLoader{display:flex}
#firstVisitLoader.hide{opacity:0;visibility:hidden;pointer-events:none}
.fvl-card{position:relative;display:flex;flex-direction:column;align-items:center;text-align:center;gap:16px;animation:fvlUp .72s cubic-bezier(.2,.8,.2,1) both}
.fvl-logo-wrap{position:relative;width:116px;height:116px;display:flex;align-items:center;justify-content:center}
.fvl-ring{position:absolute;inset:0;border-radius:50%;border:1px solid rgba(201,170,113,.20);animation:fvlSpin 1.65s linear infinite}
.fvl-ring:before{content:"";position:absolute;inset:-1px;border-radius:50%;border:2px solid transparent;border-top-color:var(--gold);filter:drop-shadow(0 0 12px var(--gold-glow))}
.fvl-logo{width:78px;height:78px;border-radius:24px;box-shadow:0 18px 42px rgba(0,0,0,.45),0 0 38px rgba(201,170,113,.18);animation:fvlPulse 1.55s ease-in-out infinite}
.fvl-title{font-family:var(--font-heading);font-size:26px;font-weight:800;letter-spacing:.05em;color:var(--gold);text-shadow:0 0 22px var(--gold-glow)}
.fvl-sub{font-size:13px;color:var(--text2);margin-top:-8px}
.fvl-bar{width:min(240px,70vw);height:4px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden;box-shadow:inset 0 0 0 1px rgba(255,255,255,.04)}
.fvl-bar:before{content:"";display:block;width:42%;height:100%;border-radius:inherit;background:linear-gradient(90deg,transparent,var(--gold),transparent);animation:fvlBar 1.05s ease-in-out infinite}
@keyframes fvlSpin{to{transform:rotate(360deg)}}
@keyframes fvlPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.055)}}
@keyframes fvlBar{0%{transform:translateX(-110%)}100%{transform:translateX(260%)}}
@keyframes fvlUp{from{opacity:0;transform:translateY(14px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}
@media(prefers-reduced-motion:reduce){.fvl-card,.fvl-ring,.fvl-logo,.fvl-bar:before{animation:none!important}}
[data-theme="discord"] #firstVisitLoader{background:radial-gradient(circle at top,#5865f233 0,transparent 38%),linear-gradient(180deg,#1e1f22 0%,#111214 100%)}
[data-theme="telegram"] #firstVisitLoader{background:radial-gradient(circle at top,#6ab2f233 0,transparent 38%),linear-gradient(180deg,#0e1621 0%,#071019 100%)}
[data-theme="vk"] #firstVisitLoader{background:linear-gradient(180deg,#edeef0 0%,#dce1e6 100%);color:#000}
/* LOGIN */
#loginScreen{position:fixed;inset:0;background:var(--bg0);display:flex;align-items:flex-start;justify-content:center;z-index:9999;padding:16px;overflow-y:auto}
.login-wrap{display:grid;grid-template-columns:1fr;width:100%;max-width:820px;margin:auto;background:var(--bg1);border:1px solid var(--border2);border-radius:var(--radius);overflow:hidden}
@media(min-width:640px){.login-wrap{grid-template-columns:1fr 1fr}}
.login-info{background:linear-gradient(135deg,var(--bg3),var(--bg0));padding:28px 24px;display:flex;flex-direction:column;border-right:1px solid var(--border)}
.login-brand{display:flex;align-items:center;gap:12px;margin-bottom:8px}
.login-brand img{width:42px;height:42px;border-radius:50%;display:block;box-shadow:0 6px 18px rgba(0,0,0,.28)}
.login-brand-text{display:flex;flex-direction:column;min-width:0}
.login-info h1{font-size:22px;font-weight:800;color:var(--gold);margin:0}
.login-info .tagline{font-size:13px;color:var(--text2);margin-bottom:20px;line-height:1.6}
.info-block{margin-bottom:14px}
.info-block h3{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gold3);margin-bottom:5px;font-family:var(--font-heading)}
.info-block p{font-size:13px;color:var(--text3);line-height:1.6}
.info-links{display:flex;gap:12px;margin-top:6px;flex-wrap:wrap}
.info-links a{font-size:12px;color:var(--gold);text-decoration:none;border-bottom:1px solid var(--gold3);padding-bottom:1px}
.info-links a:hover{color:var(--gold2)}
.privacy-note{margin-top:auto;font-size:11px;color:var(--text4);padding-top:14px;border-top:1px solid var(--border);line-height:1.6}
.login-form{padding:28px 24px}
.login-tabs{display:flex;border-bottom:1px solid var(--border);margin-bottom:20px}
.login-tab{flex:1;padding:8px;text-align:center;cursor:pointer;border-bottom:2px solid transparent;color:var(--text3);font-weight:600;font-size:14px;transition:.15s;margin-bottom:-1px}
.login-tab.active{border-color:var(--gold);color:var(--gold)}.login-tab:hover:not(.active){color:var(--text2)}
.err-msg{font-size:13px;color:var(--red2);margin-bottom:8px;min-height:18px}

/* AUTH SURFACES — always dark, regardless of current app theme */
#firstVisitLoader{
  background:
    radial-gradient(circle at 18% 18%,rgba(45,125,255,.18),transparent 28%),
    radial-gradient(circle at 82% 12%,rgba(123,92,255,.22),transparent 32%),
    radial-gradient(circle at 50% 80%,rgba(58,91,163,.18),transparent 34%),
    linear-gradient(180deg,#050914 0%,#0b1120 48%,#0d1526 100%)!important;
  color:#eef3ff!important;
}
#firstVisitLoader:before,#firstVisitLoader:after,#loginScreen:before,#loginScreen:after{content:"";position:absolute;border-radius:50%;filter:blur(70px);pointer-events:none}
#firstVisitLoader:before{width:280px;height:280px;left:-70px;top:-40px;background:rgba(45,125,255,.16)}
#firstVisitLoader:after{width:340px;height:340px;right:-120px;bottom:-110px;background:rgba(123,92,255,.14)}
#firstVisitLoader .fvl-ring{border-color:rgba(255,255,255,.10)!important}
#firstVisitLoader .fvl-ring:before{border-top-color:#4b8cff!important;filter:drop-shadow(0 0 16px rgba(45,125,255,.34))!important}
#firstVisitLoader .fvl-logo{box-shadow:0 22px 50px rgba(0,0,0,.44),0 0 42px rgba(45,125,255,.22)!important}
#firstVisitLoader .fvl-title{font-family:var(--font-body)!important;color:#eef3ff!important;text-shadow:0 0 24px rgba(45,125,255,.18)!important}
#firstVisitLoader .fvl-sub{color:#a9bbdc!important}
#firstVisitLoader .fvl-bar{background:rgba(255,255,255,.08)!important}
#firstVisitLoader .fvl-bar:before{background:linear-gradient(90deg,transparent,#4b8cff,#8c6bff,transparent)!important}
#loginScreen{position:fixed;inset:0;background:radial-gradient(circle at 12% 18%,rgba(45,125,255,.18),transparent 26%),radial-gradient(circle at 84% 16%,rgba(140,95,255,.18),transparent 28%),radial-gradient(circle at 50% 78%,rgba(44,70,126,.18),transparent 36%),linear-gradient(180deg,#050913 0%,#09101d 46%,#0d1526 100%)!important;color:#eef3ff!important}
#loginScreen:before{width:320px;height:320px;left:-120px;top:-70px;background:rgba(45,125,255,.15)}
#loginScreen:after{width:420px;height:420px;right:-140px;bottom:-140px;background:rgba(125,92,255,.12)}
#loginScreen .login-wrap{position:relative;z-index:1;max-width:980px!important;background:rgba(10,17,30,.82)!important;border:1px solid rgba(255,255,255,.08)!important;border-radius:24px!important;overflow:hidden!important;box-shadow:0 24px 80px rgba(0,0,0,.42)!important;backdrop-filter:blur(28px)!important}
@media(min-width:760px){#loginScreen .login-wrap{grid-template-columns:1.05fr .95fr!important}}
#loginScreen .login-info{background:linear-gradient(180deg,rgba(18,28,45,.88) 0%,rgba(12,18,30,.68) 100%)!important;border-right:1px solid rgba(255,255,255,.06)!important;padding:34px 30px!important}
#loginScreen .login-form{background:rgba(7,12,22,.56)!important;padding:34px 30px!important}
#loginScreen .login-brand img{border-radius:16px!important;width:54px!important;height:54px!important;box-shadow:0 14px 32px rgba(0,0,0,.34)!important}
#loginScreen .login-info h1{font-family:var(--font-body)!important;font-size:30px!important;letter-spacing:.01em!important;color:#f2f6ff!important}
#loginScreen .login-info .tagline{font-size:14px!important;line-height:1.7!important;color:#b6c4e0!important;margin-bottom:18px!important}
#loginScreen .info-block{background:var(--glass-bg,rgba(255,255,255,.04))!important;border:1px solid var(--glass-border,rgba(255,255,255,.08))!important;border-radius:18px!important;padding:14px 16px!important;margin-bottom:14px!important;backdrop-filter:blur(24px) saturate(1.4)!important;-webkit-backdrop-filter:blur(24px) saturate(1.4)!important;box-shadow:0 8px 30px rgba(0,0,0,.18),inset 0 1px 0 rgba(255,255,255,.06)!important}
#loginScreen .info-block h3{font-family:var(--font-body)!important;font-size:11px!important;letter-spacing:.12em!important;color:#85b5ff!important}
#loginScreen .info-block p{font-size:13px!important;color:#d6e1f5!important}
#loginScreen .info-links a{color:#85b5ff!important;border-bottom-color:rgba(133,181,255,.4)!important}
#loginScreen .privacy-note{margin-top:8px!important;color:#96a9c8!important;border-top:1px solid rgba(255,255,255,.06)!important;padding-top:18px!important}
#loginScreen .login-tabs{border-bottom:1px solid rgba(255,255,255,.08)!important;margin-bottom:22px!important;background:rgba(255,255,255,.025)!important;border-radius:14px!important;padding:5px!important}
#loginScreen .login-tab{border-radius:10px!important;border-bottom:none!important;color:#8fa1c2!important;margin-bottom:0!important}
#loginScreen .login-tab.active{background:linear-gradient(135deg,rgba(45,125,255,.22),rgba(123,92,255,.18))!important;color:#fff!important;box-shadow:inset 0 0 0 1px rgba(120,160,255,.14)!important}
#loginScreen .login-tab:hover:not(.active){color:#dfe7fb!important;background:rgba(255,255,255,.04)!important}
#loginScreen .fl{color:#9eb1d2!important;font-size:12px!important;letter-spacing:.04em!important}
#loginScreen .fi{background:rgba(6,12,22,.72)!important;border:1px solid rgba(133,181,255,.12)!important;border-radius:14px!important;color:#edf3ff!important}
#loginScreen .fi:focus{border-color:rgba(75,140,255,.64)!important;box-shadow:0 0 0 3px rgba(45,125,255,.14)!important}
#loginScreen .fi::placeholder{color:#7183a6!important}
#loginScreen .terms-scroll{background:rgba(6,12,22,.64)!important;border:1px solid rgba(133,181,255,.10)!important;color:#c9d6ef!important}
#loginScreen .terms-check span,#loginScreen .remember-check span{color:#d6e1f5!important}
#loginScreen .remember-check,#loginScreen .terms-check{background:rgba(255,255,255,.03)!important;border:1px solid rgba(255,255,255,.05)!important;border-radius:14px!important;padding:12px 14px!important}
#loginScreen .remember-check input,#loginScreen .terms-check input{accent-color:#2d7dff}
#loginScreen .rc-hint{color:#8ea0c2!important}
#loginScreen .btn-gold{background:linear-gradient(135deg,#2d7dff,#6a63ff)!important;color:#fff!important;border:none!important;border-radius:14px!important;box-shadow:0 14px 30px rgba(45,125,255,.22)!important}
#loginScreen .btn-gold:hover{background:linear-gradient(135deg,#4b8cff,#8377ff)!important}
#loginScreen .err-msg{color:#ff7f86!important}
#loginScreen .fi-hint{color:#7c8eb0!important}
.invite-welcome-card{display:none;position:relative;overflow:hidden;margin-bottom:12px;padding:16px;border-radius:20px;background:linear-gradient(145deg,rgba(19,33,58,.92),rgba(20,26,46,.86) 46%,rgba(38,28,70,.82) 100%);border:1px solid rgba(133,181,255,.18);box-shadow:0 14px 34px rgba(0,0,0,.22),inset 0 1px 0 rgba(255,255,255,.07)}
.invite-welcome-card:before{content:"";position:absolute;inset:-80px auto auto -70px;width:170px;height:170px;border-radius:50%;background:radial-gradient(circle,rgba(45,125,255,.18),transparent 68%);pointer-events:none}
.invite-welcome-card:after{content:"";position:absolute;inset:auto -70px -80px auto;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(135,97,255,.16),transparent 68%);pointer-events:none}
.invite-welcome-top{position:relative;z-index:1;display:flex;align-items:center;gap:12px;margin-bottom:12px}
.invite-welcome-icon{width:58px;height:58px;border-radius:18px;background:linear-gradient(180deg,rgba(255,255,255,.11),rgba(255,255,255,.04));border:1px solid rgba(255,255,255,.10);display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:25px;box-shadow:0 10px 22px rgba(0,0,0,.22)}
.invite-welcome-icon img{width:100%;height:100%;object-fit:cover;display:block}
.invite-welcome-copy{min-width:0;flex:1}
.invite-kicker{display:inline-flex;align-items:center;gap:7px;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#9fc0ff;font-weight:800;margin-bottom:5px}
.invite-kicker:before{content:"";width:7px;height:7px;border-radius:50%;background:#5ca0ff;box-shadow:0 0 12px rgba(92,160,255,.55)}
.invite-server-name{font-size:20px;line-height:1.15;color:#f7faff;font-weight:800;letter-spacing:-.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.invite-server-meta{font-size:12px;color:#c7d3ea;margin-top:5px;line-height:1.45;max-width:420px}
.invite-welcome-grid{position:relative;z-index:1;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-bottom:12px}
.invite-stat-card{position:relative;padding:10px 11px;border-radius:15px;background:rgba(255,255,255,.045);border:1px solid rgba(255,255,255,.07);backdrop-filter:blur(10px);overflow:hidden}
.invite-stat-card:before{content:"";position:absolute;inset:0 auto 0 0;width:3px;background:linear-gradient(180deg,#53a0ff,#7f6bff)}
.invite-stat-label{font-size:9px;font-weight:800;letter-spacing:.10em;text-transform:uppercase;color:#89a7da;margin-bottom:5px}
.invite-stat-value{font-size:15px;font-weight:800;color:#f2f6ff;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.invite-stat-sub{display:none}
.invite-welcome-actions{position:relative;z-index:1;display:flex;gap:8px;flex-wrap:wrap}
.invite-welcome-actions .btn{flex:1;min-width:120px;padding:9px 12px!important}
.invite-welcome-actions .btn.btn-ghost{background:rgba(255,255,255,.06)!important;border:1px solid rgba(255,255,255,.08)!important;color:#f0f4ff!important;border-radius:14px!important}
.invite-welcome-actions .btn.btn-ghost:hover{background:rgba(255,255,255,.10)!important}
@media(max-width:639px){.invite-welcome-top{align-items:flex-start}.invite-welcome-card{padding:14px}.invite-server-name{font-size:18px}.invite-welcome-grid{grid-template-columns:1fr}.invite-welcome-actions .btn{min-width:100%;flex:1 0 100%}}
/* Compact auth form + password strength */
#loginScreen .login-wrap{margin:auto!important}
#loginScreen .login-info,#loginScreen .login-form{padding:20px 22px!important}
#loginScreen .login-form{padding-bottom:24px!important}
#loginScreen .info-block{padding:10px 13px!important;margin-bottom:9px!important}
#loginScreen .privacy-note{padding-top:9px!important}
#loginScreen .fg{margin-bottom:9px!important}
#loginScreen .login-tabs{margin-bottom:12px!important}
#loginScreen .login-form .fi{height:42px!important}
#loginScreen #fReg .pw-strength{margin:-2px 0 8px!important}
#loginScreen #fReg .pw-strength-text{font-size:11px!important;line-height:1.3!important}
#loginScreen #fReg #regTermsWrap{margin-bottom:8px!important}
#loginScreen #fReg .terms-scroll{max-height:58px!important;overflow:auto!important;font-size:11px!important;line-height:1.4!important;padding:8px 10px!important;margin-bottom:8px!important}
#loginScreen #fReg .terms-check{padding:8px 11px!important;margin-top:0!important}
#loginScreen #fReg .terms-check span{font-size:12px!important;line-height:1.3!important}
.pw-strength{margin:-4px 0 12px}
.pw-strength-bar{height:7px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden;border:1px solid rgba(255,255,255,.06)}
.pw-strength-fill{height:100%;width:0%;border-radius:inherit;background:#ff5f62;transition:.18s}
.pw-strength-fill.medium{background:#f0b232}
.pw-strength-fill.good{background:#43b581}
.pw-strength-fill.strong{background:linear-gradient(90deg,#2d7dff,#43b581)}
.pw-strength-text{font-size:11px;color:#8ea0c2;margin-top:6px;line-height:1.35}
@media(max-height:760px){
  #loginScreen .login-info,#loginScreen .login-form{padding:18px 22px!important}
  #loginScreen .login-brand img{width:44px!important;height:44px!important}
  #loginScreen .login-info h1{font-size:25px!important}
  #loginScreen .info-block p{font-size:12px!important;line-height:1.45!important}
  #loginScreen .privacy-note{display:none!important}
}



/* Invite landing for guests — reference style */
#loginScreen.has-invite{
  background:
    radial-gradient(circle at 18% 16%,rgba(88,101,242,.34),transparent 32%),
    radial-gradient(circle at 82% 78%,rgba(35,165,90,.18),transparent 28%),
    linear-gradient(135deg,#101218,#181b25)!important;
}
#loginScreen.has-invite .login-wrap{
  max-width:980px!important;
  grid-template-columns:minmax(300px,1.05fr) minmax(320px,.95fr)!important;
  border-radius:28px!important;
  border:1px solid rgba(255,255,255,.12)!important;
  background:rgba(31,34,45,.82)!important;
  box-shadow:0 30px 90px rgba(0,0,0,.48)!important;
  backdrop-filter:blur(24px)!important;
  max-height:calc(100vh - 40px)!important;
}
@media(max-width:760px){
  #loginScreen.has-invite .login-wrap{grid-template-columns:1fr!important;border-radius:22px!important}
  .invite-hero-card{min-height:auto!important}
}
#loginScreen.has-invite #inviteWelcomeCard{display:none!important}
.invite-hero-card{
  min-height:520px;
  padding:30px;
  position:relative;
  overflow:hidden;
  background:linear-gradient(160deg,rgba(88,101,242,.22),rgba(35,39,52,.92) 46%,rgba(15,17,23,.96));
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  border-right:1px solid rgba(255,255,255,.08);
}
.invite-hero-card:before{
  content:'';
  position:absolute;
  width:240px;
  height:240px;
  border-radius:80px;
  background:linear-gradient(135deg,rgba(88,101,242,.42),rgba(255,255,255,.04));
  right:-78px;
  top:-86px;
  transform:rotate(18deg);
}
.invite-hero-card:after{
  content:'';
  position:absolute;
  inset:auto -80px -110px auto;
  width:320px;
  height:190px;
  border-radius:999px;
  background:rgba(35,165,90,.14);
  filter:blur(18px);
}
.invite-hero-top,.invite-hero-main,.invite-hero-foot{position:relative;z-index:1}
.invite-pill{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 12px;
  border-radius:999px;
  background:rgba(255,255,255,.1);
  border:1px solid rgba(255,255,255,.12);
  color:#dbdee1;
  font-size:12px;
  font-weight:800;
  text-transform:uppercase;
  letter-spacing:.08em;
}
.invite-server-icon{
  width:96px;
  height:96px;
  border-radius:30px;
  background:linear-gradient(135deg,#5865f2,#3b46c3);
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
  box-shadow:0 18px 48px rgba(0,0,0,.38);
  margin:26px 0 18px;
  color:white;
  font-size:38px;
}
.invite-server-icon img{width:100%;height:100%;object-fit:cover}
.invite-hero-title{
  font-size:34px;
  line-height:1.05;
  font-weight:900;
  color:#fff;
  margin:0 0 10px;
  letter-spacing:-.04em;
}
.invite-hero-sub{
  color:#b5bac1;
  font-size:15px;
  line-height:1.55;
  margin:0;
  max-width:420px;
}
.invite-meta-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}
.invite-meta-chip{
  display:inline-flex;
  align-items:center;
  gap:7px;
  border-radius:12px;
  background:rgba(0,0,0,.22);
  border:1px solid rgba(255,255,255,.1);
  padding:9px 11px;
  color:#f2f3f5;
  font-size:13px;
}
.invite-hero-foot{color:#949ba4;font-size:12px;line-height:1.45}
#loginScreen.has-invite .login-form{
  background:rgba(49,51,56,.86)!important;
  padding:34px 30px!important;
  display:flex!important;
  flex-direction:column!important;
  justify-content:center!important;
}
#loginScreen.has-invite .login-tabs{
  background:rgba(0,0,0,.18)!important;
  border:1px solid rgba(255,255,255,.08)!important;
  border-radius:16px!important;
  padding:5px!important;
  margin-bottom:20px!important;
}
#loginScreen.has-invite .login-tab{border:0!important;border-radius:12px!important;margin:0!important;color:#b5bac1!important}
#loginScreen.has-invite .login-tab.active{background:#5865f2!important;color:white!important;box-shadow:0 8px 24px rgba(88,101,242,.32)!important}
#loginScreen.has-invite .fi{background:#1e1f22!important;border-color:rgba(255,255,255,.1)!important;border-radius:12px!important;color:#f2f3f5!important}
#loginScreen.has-invite .fl{color:#dbdee1!important}
.invite-auth-note{
  margin:-2px 0 16px;
  padding:12px 14px;
  border-radius:14px;
  background:rgba(88,101,242,.13);
  border:1px solid rgba(88,101,242,.28);
  color:#dbdee1;
  font-size:13px;
  line-height:1.45;
}
.invite-auth-note b{color:#fff}
.invite-error-card{
  padding:24px;
  border-radius:24px;
  background:rgba(240,71,71,.13);
  border:1px solid rgba(240,71,71,.28);
  color:#fff;
}
@media(max-height:760px){
  #loginScreen.has-invite .login-wrap{max-height:calc(100vh - 24px)!important}
  .invite-hero-card{min-height:455px!important;padding:24px!important}
  .invite-server-icon{width:78px;height:78px;border-radius:24px;margin:18px 0 14px!important}
  .invite-hero-title{font-size:29px!important}
  .invite-hero-sub{font-size:13px!important}
  .invite-meta-chip{font-size:12px!important;padding:7px 9px!important}
  #loginScreen.has-invite .login-form{padding:24px!important}
}

/* MOBILE OVERLAY */
#mobileOverlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:49;-webkit-tap-highlight-color:transparent}
#mobileOverlay.open{display:block}
/* MOBILE */
@media(max-width:740px){
  :root{--ch-w:82vw;--mb-w:80vw}
  .mobile-only{display:flex!important}.desktop-only{display:none!important}

  /* БЫЛО: padding-bottom:calc(60px + env(safe-area-inset-bottom,0px)) */
  #app{flex-direction:column;padding-bottom:calc(74px + var(--safe-bottom,0px))}

  /* БЫЛО: height:calc(60px + env(...));padding-bottom:env(...) */
  #serverBar{
    position:fixed;bottom:0;left:0;right:0;width:100%!important;min-width:0!important;
    height:calc(74px + var(--safe-bottom,0px));
    flex-direction:row;overflow-x:auto;overflow-y:hidden;
    padding:8px 10px calc(8px + var(--safe-bottom,0px));
    border-right:none;border-top:1px solid rgba(255,255,255,.06);z-index:50;
    justify-content:flex-start;align-items:center;gap:10px;
    background:rgba(30,31,34,.96);
    box-shadow:0 -8px 24px rgba(0,0,0,.28);
    backdrop-filter:blur(18px) saturate(135%);
    -webkit-backdrop-filter:blur(18px) saturate(135%);
    -webkit-overflow-scrolling:touch;
    touch-action:pan-x;
    scrollbar-width:none;
  }
  #serverBar::-webkit-scrollbar{display:none}

  #srvIcons{display:flex!important;flex-direction:row;align-items:center;gap:10px;flex-shrink:0;padding-right:2px}
  #serverBar .srv-sep{width:1px;height:34px;flex-shrink:0;margin:0 1px;background:rgba(255,255,255,.08)}
  #serverBar .srv-icon{width:44px;height:44px;flex-shrink:0;touch-action:manipulation;border:none;border-radius:14px;background:transparent}
  #serverBar .srv-icon .srv-icon-inner{width:44px;height:44px;border-radius:14px;border:1px solid transparent;background:var(--bg3);font-size:18px;transition:transform .16s,border-color .16s,background .16s,box-shadow .16s,filter .16s,opacity .16s}
  #serverBar .srv-icon.active .srv-icon-inner{background:rgba(88,101,242,.18);border-color:rgba(88,101,242,.38);box-shadow:0 0 0 1px rgba(88,101,242,.1) inset;transform:translateY(-2px)}
  #serverBar .srv-icon:hover .srv-icon-inner{border-radius:14px;background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.12)}
  #serverBar .srv-icon.not-member .srv-icon-inner{opacity:.42;filter:grayscale(1)}
  #serverBar .srv-icon.active{border:none;border-radius:14px}
  #serverBar .srv-icon:hover{border:none;border-radius:14px}
  #serverBar .srv-icon .srv-notif-dot{top:-4px;right:-4px;border-color:rgba(30,31,34,.96)}
  #serverBar .srv-icon .srv-voice-dot{bottom:-2px;right:-2px;border-color:rgba(30,31,34,.96)}
  #serverBar .srv-admin-btn,#serverBar .srv-superadmin-btn{width:42px;height:42px;border-radius:14px;flex-shrink:0}
  #serverBar .srv-indicator{display:none!important}

  /* БЫЛО: bottom:calc(60px + env(safe-area-inset-bottom,0px)) */
  #chSidebar{
    position:fixed;top:0;left:0;
    bottom:calc(74px + var(--safe-bottom,0px));
    width:var(--ch-w)!important;min-width:0!important;
    transform:translateX(-100%);transition:transform .25s cubic-bezier(.4,0,.2,1);
    z-index:50;box-shadow:4px 0 24px rgba(0,0,0,.5);
    will-change:transform;
    -webkit-overflow-scrolling:touch;
  }
  #chSidebar.open{transform:translateX(0)}

  /* Safari: touch events on sidebar children */
  #chSidebar .ch-scroll,#chSidebar .dm-scroll{
    -webkit-overflow-scrolling:touch;
    touch-action:pan-y;
  }

  /* БЫЛО: bottom:calc(60px + env(safe-area-inset-bottom,0px)) */
  #memberSidebar{
    position:fixed!important;top:0;right:0;
    bottom:calc(74px + var(--safe-bottom,0px));
    width:var(--mb-w)!important;min-width:0!important;
    transform:translateX(110%);transition:transform .25s cubic-bezier(.4,0,.2,1);
    z-index:50;display:flex!important;box-shadow:-4px 0 24px rgba(0,0,0,.5);
    border-left:1px solid var(--border2);
    will-change:transform;
  }
  #memberSidebar.open{transform:translateX(0)}
  #memberSidebar.collapsed{transform:translateX(110%)}

  /* БЫЛО: height:100vh...;height:100dvh...; (две строки) */
  #mainArea{
    width:100%;
    height:calc(var(--app-h,100vh) - 74px - var(--safe-bottom,0px));
  }

  #mainHeader{padding:0 8px}.hdr-topic{display:none}
  #inputOuter,#dmInputOuter{position:sticky!important;bottom:0;z-index:5;background:transparent!important}
  #chView #messagesWrap{padding-bottom:0!important}
  .msg-img,.msg-video{max-width:calc(100vw - 90px)}
  #emojiPicker{width:calc(100vw - 16px);max-width:316px;left:8px!important}

  /* МОБИЛЬНЫЕ МОДАЛЬНЫЕ ОКНА — компактные */
  .modal{
    margin:0 auto;
    border-radius:16px;
    padding:18px 16px;
    max-height:calc(var(--app-h,100vh) - 24px);
    /* Liquid glass на мобильном */
    background:rgba(18,15,10,0.94);
  }
  #modalBg{
    padding:8px;
    align-items:flex-end;
  }
  #modalBg .modal{
    border-radius:20px 20px 14px 14px;
    width:100%;
    max-width:100%;
    margin:0;
  }
  .modal h2{font-size:15px;margin-bottom:3px}
  .modal .sub{font-size:12px;margin-bottom:12px}
  .fg{margin-bottom:10px}
  .fi{padding:8px 10px;font-size:14px}
  .btn{padding:10px 16px;font-size:14px}
  .btn-row{gap:6px;margin-top:8px}

  /* БЫЛО: bottom:calc(180px + env(safe-area-inset-bottom,0px)) */
  #toastBox{bottom:calc(194px + var(--safe-bottom,0px))}

  .notif-popup{right:8px;max-width:calc(100vw - 24px)}
  #mentionSuggest{max-width:calc(100vw - 24px)}

  /* БЫЛО: bottom:calc(76px + env(safe-area-inset-bottom,0px)) */
  #floatingPlayer{bottom:calc(88px + var(--safe-bottom,0px));right:8px;width:calc(100vw - 16px)}
  #floatingPlayer.fp-minimized{width:auto!important;max-width:calc(100vw - 16px)}

  /* Safari: кнопки должны реагировать на touch */
  .srv-icon,.ch-item,.voice-room,.dm-item,.mb-item,.btn,.ma-wbtn,.vb-btn,.ctx-item{
    touch-action:manipulation;
    cursor:pointer;
  }
}
/* ── SAFARI iOS СПЕЦИФИЧНЫЕ ФИКСЫ ───────────────────────────── */
/* Safari не поддерживает position:fixed внутри transform-контейнеров корректно */
/* Обеспечиваем стабильный z-index стек */
#app{isolation:isolate}
/* Предотвращаем "прилипание" hover-состояния на iOS */
@media(hover:none){
  .srv-icon:hover .srv-icon-inner{border-radius:50%;background:var(--bg2)}
  .srv-icon:hover{border-color:transparent;border-radius:50%}
  .ch-item:hover{background:transparent;color:var(--text3)}
  .voice-room:hover{background:transparent;color:var(--text3)}
}
/* Убираем мигание при tap на iOS */
*{-webkit-tap-highlight-color:rgba(0,0,0,0)}
/* Фикс input zoom на iOS (предотвращаем масштабирование при фокусе) */
@media(max-width:740px){
  input.fi,textarea.fi,select.fi,.msg-textarea,.dm-search-inp,
  .msg-edit-textarea,.comment-input{
    font-size:16px!important;
  }
  #streamViewer{left:6px!important;bottom:68px!important;min-width:200px!important;width:calc(100vw - 12px)!important;max-width:calc(100vw - 12px)!important;max-height:calc(var(--app-h,100vh) - 74px)!important;resize:none!important}
  #streamViewer .sv-header{
    position:absolute!important;
    left:8px!important;
    right:8px!important;
    top:8px!important;
    z-index:12!important;
    border-radius:18px!important;
    background:rgba(8,12,18,.72)!important;
    border:1px solid rgba(255,255,255,.12)!important;
    backdrop-filter:blur(18px) saturate(145%)!important;
    -webkit-backdrop-filter:blur(18px) saturate(145%)!important;
    box-shadow:0 12px 28px rgba(0,0,0,.32)!important;
    opacity:0!important;
    transform:translateY(-10px)!important;
    pointer-events:none!important;
    transition:opacity .22s ease,transform .22s ease!important;
  }
  #streamViewer.sv-controls-visible .sv-header,
  #streamViewer .sv-header:focus-within,
  #streamViewer.sv-minimized .sv-header{
    opacity:1!important;
    transform:translateY(0)!important;
    pointer-events:auto!important;
  }
  #streamViewer .sv-video-wrap{min-height:200px!important;}
}

/* Mobile auth / invite landing: no full-page scrolling */
@media(max-width:740px){
  #loginScreen{
    padding:10px!important;
    align-items:center!important;
    justify-content:center!important;
    overflow:hidden!important;
    height:100dvh!important;
    min-height:100dvh!important;
  }

  #loginScreen .login-wrap{
    width:100%!important;
    max-width:430px!important;
    height:calc(100dvh - 20px)!important;
    max-height:calc(100dvh - 20px)!important;
    display:grid!important;
    grid-template-columns:1fr!important;
    grid-template-rows:auto 1fr!important;
    border-radius:22px!important;
    overflow:hidden!important;
  }

  #loginScreen .login-info{
    border-right:none!important;
    border-bottom:1px solid rgba(255,255,255,.06)!important;
    padding:14px 16px!important;
    min-height:0!important;
    max-height:34dvh!important;
    overflow:hidden!important;
  }

  #loginScreen .login-brand{
    margin-bottom:6px!important;
    gap:10px!important;
  }

  #loginScreen .login-brand img{
    width:38px!important;
    height:38px!important;
    border-radius:12px!important;
  }

  #loginScreen .login-info h1{
    font-size:23px!important;
    line-height:1.1!important;
  }

  #loginScreen .login-info .tagline{
    font-size:12px!important;
    line-height:1.35!important;
    margin-bottom:8px!important;
    display:-webkit-box!important;
    -webkit-line-clamp:2!important;
    -webkit-box-orient:vertical!important;
    overflow:hidden!important;
  }

  #loginScreen .info-block{
    display:none!important;
  }

  #loginScreen .privacy-note{
    display:none!important;
  }

  #loginScreen .login-form{
    min-height:0!important;
    padding:14px 16px!important;
    overflow:hidden!important;
    display:flex!important;
    flex-direction:column!important;
  }

  #loginScreen .login-tabs{
    margin-bottom:12px!important;
    padding:4px!important;
    flex-shrink:0!important;
  }

  #loginScreen .login-tab{
    padding:8px 6px!important;
    font-size:14px!important;
  }

  #loginScreen .fg{
    margin-bottom:9px!important;
  }

  #loginScreen .fl{
    font-size:10px!important;
    margin-bottom:5px!important;
  }

  #loginScreen .fi{
    min-height:40px!important;
    padding:9px 12px!important;
    font-size:16px!important;
    border-radius:12px!important;
  }

  #loginScreen .fi-hint{
    font-size:10px!important;
    margin-top:4px!important;
  }

  #loginScreen .remember-check,
  #loginScreen .terms-check{
    padding:8px 10px!important;
    margin-bottom:9px!important;
    border-radius:12px!important;
  }

  #loginScreen .remember-check span,
  #loginScreen .terms-check span{
    font-size:12px!important;
    line-height:1.25!important;
  }

  #loginScreen .rc-hint{
    display:none!important;
  }

  #loginScreen .err-msg{
    min-height:16px!important;
    margin-bottom:6px!important;
    font-size:12px!important;
  }

  #loginScreen .btn-full{
    min-height:42px!important;
    padding:10px 14px!important;
    border-radius:14px!important;
    flex-shrink:0!important;
  }

  #loginScreen #fLogin,
  #loginScreen #fReg{
    min-height:0!important;
    overflow:hidden!important;
  }

  #loginScreen #fReg .terms-scroll{
    display:none!important;
  }

  #loginScreen #fReg .terms-check{
    margin-top:0!important;
  }

  #loginScreen #fReg .pw-strength{
    margin:-2px 0 8px!important;
  }

  #loginScreen #fReg .pw-strength-bar{
    height:6px!important;
  }

  #loginScreen #fReg .pw-strength-text{
    font-size:10px!important;
    line-height:1.25!important;
    margin-top:4px!important;
  }

  /* Invite mode on mobile: compact card at top, form below, no page scroll */
  #loginScreen.has-invite .login-wrap{
    grid-template-rows:auto 1fr!important;
    max-width:430px!important;
  }

  #loginScreen.has-invite #loginInfoPanel{
    padding:0!important;
    max-height:35dvh!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite .invite-hero-card{
    min-height:0!important;
    height:auto!important;
    max-height:35dvh!important;
    padding:16px!important;
    border-right:none!important;
    border-bottom:1px solid rgba(255,255,255,.08)!important;
    justify-content:flex-start!important;
    gap:8px!important;
  }

  #loginScreen.has-invite .invite-hero-card:before{
    width:160px!important;
    height:160px!important;
    right:-70px!important;
    top:-80px!important;
  }

  #loginScreen.has-invite .invite-hero-card:after{
    display:none!important;
  }

  #loginScreen.has-invite .invite-pill{
    padding:6px 9px!important;
    font-size:9px!important;
    letter-spacing:.06em!important;
  }

  #loginScreen.has-invite .invite-server-icon{
    width:54px!important;
    height:54px!important;
    border-radius:17px!important;
    margin:8px 0 8px!important;
    font-size:24px!important;
    box-shadow:0 10px 26px rgba(0,0,0,.28)!important;
  }

  #loginScreen.has-invite .invite-hero-title{
    font-size:22px!important;
    line-height:1.08!important;
    margin:0 0 6px!important;
    display:-webkit-box!important;
    -webkit-line-clamp:2!important;
    -webkit-box-orient:vertical!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite .invite-hero-sub{
    font-size:11px!important;
    line-height:1.35!important;
    display:-webkit-box!important;
    -webkit-line-clamp:2!important;
    -webkit-box-orient:vertical!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite .invite-meta-row{
    gap:6px!important;
    margin-top:8px!important;
  }

  #loginScreen.has-invite .invite-meta-chip{
    padding:5px 7px!important;
    font-size:10px!important;
    border-radius:9px!important;
  }

  #loginScreen.has-invite .invite-hero-foot{
    display:none!important;
  }

  #loginScreen.has-invite .login-form{
    padding:12px 14px!important;
    justify-content:flex-start!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite .invite-auth-note{
    margin:0 0 9px!important;
    padding:8px 10px!important;
    border-radius:12px!important;
    font-size:11px!important;
    line-height:1.3!important;
    flex-shrink:0!important;
  }

  #loginScreen.has-invite .invite-auth-note br{
    display:none!important;
  }
}

/* Very short phones */
@media(max-width:740px) and (max-height:670px){
  #loginScreen{
    padding:6px!important;
  }

  #loginScreen .login-wrap{
    height:calc(100dvh - 12px)!important;
    max-height:calc(100dvh - 12px)!important;
    border-radius:18px!important;
  }

  #loginScreen .login-info{
    padding:10px 13px!important;
    max-height:28dvh!important;
  }

  #loginScreen .login-brand img{
    width:32px!important;
    height:32px!important;
  }

  #loginScreen .login-info h1{
    font-size:20px!important;
  }

  #loginScreen .login-info .tagline{
    -webkit-line-clamp:1!important;
    margin-bottom:0!important;
  }

  #loginScreen .login-form{
    padding:10px 13px!important;
  }

  #loginScreen .login-tabs{
    margin-bottom:8px!important;
  }

  #loginScreen .fg{
    margin-bottom:7px!important;
  }

  #loginScreen .fi{
    min-height:36px!important;
    padding:7px 10px!important;
  }

  #loginScreen .remember-check,
  #loginScreen .terms-check{
    padding:7px 9px!important;
    margin-bottom:7px!important;
  }

  #loginScreen #fReg .fi-hint{
    display:none!important;
  }

  #loginScreen #fReg .pw-strength-text{
    display:none!important;
  }

  #loginScreen.has-invite #loginInfoPanel{
    max-height:30dvh!important;
  }

  #loginScreen.has-invite .invite-hero-card{
    max-height:30dvh!important;
    padding:12px!important;
  }

  #loginScreen.has-invite .invite-server-icon{
    width:42px!important;
    height:42px!important;
    margin:6px 0!important;
  }

  #loginScreen.has-invite .invite-hero-title{
    font-size:18px!important;
    -webkit-line-clamp:1!important;
  }

  #loginScreen.has-invite .invite-hero-sub{
    display:none!important;
  }

  #loginScreen.has-invite .invite-meta-row{
    margin-top:6px!important;
  }

  #loginScreen.has-invite .invite-auth-note{
    display:none!important;
  }
}


/* Transparent invite/server icons: keep full image visible, especially on mobile */
.invite-server-icon img,
.invite-welcome-icon img{
  object-fit:contain!important;
  padding:8px!important;
  background:rgba(255,255,255,.035)!important;
}
.login-brand img,
.fvl-logo{
  object-fit:contain!important;
}
@media(max-width:740px){
  #loginScreen.has-invite .invite-server-icon img{
    padding:6px!important;
    object-fit:contain!important;
  }
  .invite-server-icon{
    background:linear-gradient(135deg,rgba(88,101,242,.78),rgba(59,70,195,.58))!important;
  }
}



/* Auth layout polish — Android + PC */
@media(min-width:741px){
  #loginScreen{
    padding:18px!important;
    overflow:hidden!important;
  }
  #loginScreen .login-wrap,
  #loginScreen.has-invite .login-wrap{
    width:min(100%,1490px)!important;
    max-width:1490px!important;
    max-height:calc(100vh - 36px)!important;
    height:auto!important;
    overflow:hidden!important;
    grid-template-columns:minmax(380px,1.02fr) minmax(420px,.98fr)!important;
  }
  #loginScreen.has-invite .login-form,
  #loginScreen .login-form{
    padding:28px 28px!important;
    overflow:hidden!important;
    justify-content:center!important;
  }
  #loginScreen.has-invite #loginInfoPanel,
  #loginScreen .login-info{
    overflow:hidden!important;
  }
  #loginScreen .invite-auth-note{
    margin:0 0 14px!important;
    padding:14px 16px!important;
    font-size:14px!important;
    line-height:1.5!important;
  }
  #loginScreen .remember-check{
    align-items:flex-start!important;
    gap:10px!important;
    padding:12px 14px!important;
  }
  #loginScreen .remember-check > span{
    display:flex!important;
    flex-direction:column!important;
    gap:4px!important;
    line-height:1.35!important;
  }
  #loginScreen .remember-check .rc-hint{
    display:block!important;
    margin-top:0!important;
    font-size:12px!important;
    line-height:1.35!important;
  }
  #loginScreen .err-msg{
    min-height:18px!important;
  }
}

@media(max-width:740px){
  #loginScreen{
    padding:8px!important;
    align-items:flex-start!important;
    justify-content:flex-start!important;
    overflow:hidden!important;
  }
  #loginScreen .login-wrap,
  #loginScreen.has-invite .login-wrap{
    width:100%!important;
    max-width:430px!important;
    height:auto!important;
    min-height:0!important;
    max-height:calc(100dvh - 16px)!important;
    margin:0 auto!important;
    grid-template-rows:auto minmax(0,1fr)!important;
  }
  #loginScreen .login-info{
    max-height:31dvh!important;
  }
  #loginScreen.has-invite #loginInfoPanel{
    max-height:31dvh!important;
  }
  #loginScreen.has-invite .invite-hero-card{
    max-height:31dvh!important;
    min-height:0!important;
    padding:14px!important;
  }
  #loginScreen.has-invite .invite-server-icon{
    width:50px!important;
    height:50px!important;
    margin:8px 0 6px!important;
  }
  #loginScreen.has-invite .invite-hero-title{
    font-size:20px!important;
    margin:0 0 4px!important;
  }
  #loginScreen.has-invite .invite-hero-sub{
    font-size:11px!important;
    line-height:1.3!important;
    -webkit-line-clamp:2!important;
  }
  #loginScreen.has-invite .invite-meta-row{
    margin-top:6px!important;
  }
  #loginScreen.has-invite .invite-meta-chip{
    font-size:10px!important;
    padding:5px 7px!important;
  }
  #loginScreen .login-form,
  #loginScreen.has-invite .login-form{
    min-height:0!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    -webkit-overflow-scrolling:touch!important;
    overscroll-behavior:contain!important;
    justify-content:flex-start!important;
    padding:12px 14px!important;
  }
  #loginScreen .login-tabs{
    margin-bottom:10px!important;
  }
  #loginScreen .invite-auth-note{
    margin:0 0 8px!important;
    padding:8px 10px!important;
    font-size:11px!important;
    line-height:1.28!important;
  }
  #loginScreen .fg{
    margin-bottom:8px!important;
  }
  #loginScreen .fi{
    min-height:38px!important;
    padding:8px 11px!important;
  }
  #loginScreen .remember-check,
  #loginScreen .terms-check{
    padding:8px 10px!important;
    margin-bottom:8px!important;
  }
  #loginScreen .remember-check > span,
  #loginScreen .terms-check > span{
    display:flex!important;
    flex-direction:column!important;
    gap:2px!important;
  }
  #loginScreen .remember-check .rc-hint{
    display:none!important;
  }
  #loginScreen .btn-full{
    min-height:40px!important;
    margin-top:4px!important;
  }
  #loginScreen .err-msg{
    min-height:14px!important;
    margin-bottom:4px!important;
  }
}

@media(max-width:740px) and (max-height:700px){
  #loginScreen .login-wrap,
  #loginScreen.has-invite .login-wrap{
    max-height:calc(100dvh - 12px)!important;
  }
  #loginScreen .login-info,
  #loginScreen.has-invite #loginInfoPanel{
    max-height:27dvh!important;
  }
  #loginScreen.has-invite .invite-hero-card{
    max-height:27dvh!important;
    padding:12px!important;
  }
  #loginScreen.has-invite .invite-server-icon{
    width:42px!important;
    height:42px!important;
    margin:6px 0 5px!important;
  }
  #loginScreen.has-invite .invite-hero-title{
    font-size:18px!important;
    -webkit-line-clamp:1!important;
  }
  #loginScreen.has-invite .invite-hero-sub{
    display:none!important;
  }
  #loginScreen .login-form,
  #loginScreen.has-invite .login-form{
    padding:10px 12px!important;
  }
  #loginScreen .fi{min-height:36px!important;padding:7px 10px!important}
  #loginScreen .remember-check,#loginScreen .terms-check{padding:7px 9px!important}
  #loginScreen .invite-auth-note{display:none!important}
}


/* Android transparent icons + invite registration layout fix */
.invite-server-icon,
.invite-welcome-icon{
  background:linear-gradient(135deg,rgba(88,101,242,.88),rgba(59,70,195,.72))!important;
}

.invite-server-icon img,
.invite-welcome-icon img{
  object-fit:contain!important;
  background:transparent!important;
  border:none!important;
  box-shadow:none!important;
}

@media(max-width:740px){
  /* На Android прозрачные PNG не должны получать белую подложку */
  #loginScreen.has-invite .invite-server-icon{
    background:linear-gradient(135deg,rgba(88,101,242,.78),rgba(59,70,195,.58))!important;
    border:1px solid rgba(255,255,255,.10)!important;
  }

  #loginScreen.has-invite .invite-server-icon img,
  #loginScreen .invite-welcome-icon img,
  #loginScreen .srv-icon-inner img{
    width:100%!important;
    height:100%!important;
    object-fit:contain!important;
    padding:7px!important;
    background:transparent!important;
    border:none!important;
    box-shadow:none!important;
    border-radius:inherit!important;
  }

  /* Форма регистрации при инвайте: компактная, но не ломается */
  #loginScreen.has-invite .login-wrap{
    display:grid!important;
    grid-template-rows:auto minmax(0,1fr)!important;
    height:calc(100dvh - 16px)!important;
    max-height:calc(100dvh - 16px)!important;
  }

  #loginScreen.has-invite .login-form{
    min-height:0!important;
    height:100%!important;
    overflow:hidden!important;
    display:flex!important;
    flex-direction:column!important;
  }

  #loginScreen.has-invite .login-tabs{
    flex-shrink:0!important;
  }

  #loginScreen.has-invite #fLogin,
  #loginScreen.has-invite #fReg{
    min-height:0!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    -webkit-overflow-scrolling:touch!important;
    overscroll-behavior:contain!important;
    padding-bottom:4px!important;
  }

  #loginScreen.has-invite #fReg .fg{
    margin-bottom:7px!important;
  }

  #loginScreen.has-invite #fReg .fi{
    min-height:36px!important;
    padding:7px 10px!important;
  }

  #loginScreen.has-invite #fReg .fi-hint{
    font-size:10px!important;
    line-height:1.2!important;
    margin-top:3px!important;
  }

  #loginScreen.has-invite #fReg .pw-strength{
    margin:-2px 0 7px!important;
  }

  #loginScreen.has-invite #fReg .pw-strength-bar{
    height:5px!important;
  }

  #loginScreen.has-invite #fReg .pw-strength-text{
    font-size:10px!important;
    line-height:1.2!important;
    margin-top:3px!important;
  }

  #loginScreen.has-invite #regTermsWrap{
    margin-bottom:7px!important;
  }

  #loginScreen.has-invite #regTermsWrap .terms-scroll{
    display:none!important;
  }

  #loginScreen.has-invite #regTermsWrap .terms-check{
    margin:0!important;
    padding:7px 9px!important;
  }

  #loginScreen.has-invite #regTermsWrap .terms-check span{
    font-size:11px!important;
    line-height:1.22!important;
  }

  #loginScreen.has-invite #fReg .err-msg{
    min-height:13px!important;
    font-size:11px!important;
    margin-bottom:4px!important;
  }

  #loginScreen.has-invite #fReg .btn-full{
    min-height:38px!important;
    padding:8px 12px!important;
    margin-top:0!important;
  }
}

@media(max-width:740px) and (max-height:700px){
  #loginScreen.has-invite #fReg .fi-hint,
  #loginScreen.has-invite #fReg .pw-strength-text{
    display:none!important;
  }

  #loginScreen.has-invite #fReg .fg{
    margin-bottom:6px!important;
  }

  #loginScreen.has-invite #regTermsWrap .terms-check span{
    font-size:10px!important;
  }
}


/* Registration submit button must stay inside auth card */
#loginScreen .login-form{
  box-sizing:border-box!important;
}

#loginScreen #fLogin,
#loginScreen #fReg{
  box-sizing:border-box!important;
}

@media(max-width:740px){
  #loginScreen.has-invite .login-wrap{
    overflow:hidden!important;
  }

  #loginScreen.has-invite .login-form{
    min-height:0!important;
    height:100%!important;
    max-height:100%!important;
    overflow:hidden!important;
    box-sizing:border-box!important;
    padding-bottom:14px!important;
  }

  #loginScreen.has-invite #fLogin,
  #loginScreen.has-invite #fReg{
    flex:1 1 auto!important;
    min-height:0!important;
    max-height:100%!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    padding-bottom:18px!important;
    box-sizing:border-box!important;
    scrollbar-width:none!important;
  }

  #loginScreen.has-invite #fLogin::-webkit-scrollbar,
  #loginScreen.has-invite #fReg::-webkit-scrollbar{
    display:none!important;
  }

  #loginScreen.has-invite #fReg .btn-full{
    position:relative!important;
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
    width:100%!important;
    box-sizing:border-box!important;
    margin:6px 0 0!important;
    transform:none!important;
  }

  #loginScreen.has-invite #fReg .err-msg{
    min-height:12px!important;
  }
}

/* On wider screens, prevent auth content from leaking under the card */
@media(min-width:741px){
  #loginScreen.has-invite .login-form{
    max-height:calc(100vh - 72px)!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite #fReg,
  #loginScreen.has-invite #fLogin{
    max-height:calc(100vh - 230px)!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    padding-bottom:18px!important;
    scrollbar-width:thin!important;
  }

  #loginScreen.has-invite #fReg .btn-full{
    margin-bottom:2px!important;
  }
}


/* Remove right invite note and disable registration inner scroll */
#loginScreen.has-invite #inviteAuthNote{
  display:none!important;
}

#loginScreen.has-invite .login-form{
  justify-content:center!important;
  overflow:hidden!important;
}

#loginScreen.has-invite #fReg,
#loginScreen.has-invite #fLogin{
  overflow:visible!important;
  max-height:none!important;
  padding-bottom:0!important;
}

@media(max-width:740px){
  #loginScreen.has-invite .login-form{
    justify-content:flex-start!important;
    overflow:hidden!important;
  }

  #loginScreen.has-invite #fReg,
  #loginScreen.has-invite #fLogin{
    overflow:visible!important;
    max-height:none!important;
    padding-bottom:0!important;
  }

  #loginScreen.has-invite #fReg .fg{
    margin-bottom:7px!important;
  }

  #loginScreen.has-invite #fReg .fi{
    min-height:35px!important;
    padding:7px 10px!important;
  }

  #loginScreen.has-invite #fReg .fi-hint,
  #loginScreen.has-invite #fReg .pw-strength-text{
    display:none!important;
  }

  #loginScreen.has-invite #fReg .pw-strength{
    margin:-2px 0 6px!important;
  }

  #loginScreen.has-invite #regTermsWrap{
    margin-bottom:6px!important;
  }

  #loginScreen.has-invite #regTermsWrap .terms-scroll{
    display:none!important;
  }

  #loginScreen.has-invite #fReg .btn-full{
    min-height:38px!important;
    margin-top:2px!important;
  }
}

@media(max-width:740px) and (max-height:700px){
  #loginScreen.has-invite #fReg .terms-check span{
    font-size:10px!important;
    line-height:1.15!important;
  }

  #loginScreen.has-invite #fReg .btn-full{
    min-height:36px!important;
    padding:7px 10px!important;
  }
}


/* PWA install suggestion */
.pwa-install-prompt{
  position:fixed;
  right:18px;
  bottom:18px;
  z-index:12000;
  width:min(390px,calc(100vw - 24px));
  display:none;
  align-items:center;
  gap:12px;
  padding:14px 44px 14px 14px;
  border-radius:22px;
  background:linear-gradient(135deg,rgba(15,24,40,.94),rgba(25,32,54,.94));
  border:1px solid rgba(133,181,255,.18);
  box-shadow:0 18px 50px rgba(0,0,0,.38),inset 0 1px 0 rgba(255,255,255,.07);
  color:#eef3ff;
  backdrop-filter:blur(22px) saturate(140%);
  -webkit-backdrop-filter:blur(22px) saturate(140%);
  transform:translateY(18px) scale(.98);
  opacity:0;
  pointer-events:none;
  transition:opacity .22s ease,transform .22s ease;
}
.pwa-install-prompt.show{
  display:flex;
  opacity:1;
  pointer-events:auto;
  transform:translateY(0) scale(1);
}
.pwa-install-icon{
  width:48px;
  height:48px;
  flex:0 0 48px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg,rgba(45,125,255,.22),rgba(114,89,255,.16));
  border:1px solid rgba(255,255,255,.08);
  overflow:hidden;
}
.pwa-install-icon img{
  width:34px;
  height:34px;
  object-fit:contain;
  display:block;
}
.pwa-install-copy{
  min-width:0;
  flex:1;
}
.pwa-install-title{
  font-size:15px;
  font-weight:800;
  line-height:1.2;
  color:#fff;
}
.pwa-install-text{
  margin-top:3px;
  font-size:12px;
  line-height:1.35;
  color:#b8c7e4;
}
.pwa-install-ios{
  display:none;
  margin-top:5px;
  font-size:11px;
  line-height:1.35;
  color:#9fb8e8;
}
.pwa-install-actions{
  display:flex;
  gap:7px;
  flex-shrink:0;
}
.pwa-install-btn{
  border:none;
  border-radius:12px;
  padding:8px 11px;
  font-size:12px;
  font-weight:800;
  cursor:pointer;
  font-family:inherit;
  transition:transform .12s ease,opacity .12s ease,background .12s ease;
}
.pwa-install-btn:hover{transform:translateY(-1px)}
.pwa-install-btn.primary{
  background:linear-gradient(135deg,#2d7dff,#6a63ff);
  color:white;
  box-shadow:0 10px 24px rgba(45,125,255,.25);
}
.pwa-install-btn.ghost{
  background:rgba(255,255,255,.07);
  color:#dbe6fb;
  border:1px solid rgba(255,255,255,.08);
}
.pwa-install-close{
  position:absolute;
  top:8px;
  right:10px;
  width:24px;
  height:24px;
  border:none;
  border-radius:50%;
  background:rgba(255,255,255,.06);
  color:#b8c7e4;
  font-size:18px;
  line-height:22px;
  cursor:pointer;
}
.pwa-install-close:hover{
  background:rgba(255,255,255,.10);
  color:#fff;
}
.pwa-install-prompt.ios .pwa-install-btn.primary{display:none}
.pwa-install-prompt.ios .pwa-install-ios{display:block}
@media(max-width:740px){
  .pwa-install-prompt{
    left:10px;
    right:10px;
    bottom:calc(84px + var(--safe-bottom,0px));
    width:auto;
    padding:12px 38px 12px 12px;
    border-radius:20px;
    gap:10px;
  }
  .pwa-install-icon{
    width:42px;
    height:42px;
    flex-basis:42px;
    border-radius:14px;
  }
  .pwa-install-icon img{
    width:30px;
    height:30px;
  }
  .pwa-install-title{font-size:14px}
  .pwa-install-text{font-size:11px}
  .pwa-install-actions{
    flex-direction:column;
    gap:5px;
  }
  .pwa-install-btn{
    padding:7px 9px;
    font-size:11px;
  }
}
@media(max-width:420px){
  .pwa-install-prompt{
    align-items:flex-start;
  }
  .pwa-install-actions{
    width:82px;
  }
}


/* Chat invite preview cards */
.chat-invite-preview-loading{
  margin-top:8px;
  max-width:430px;
  border-radius:18px;
  padding:14px 16px;
  background:linear-gradient(135deg,rgba(88,101,242,.12),rgba(45,125,255,.08));
  border:1px solid rgba(133,181,255,.16);
  color:var(--text3);
  font-size:12px;
}
.chat-invite-card{
  margin-top:8px;
  max-width:460px;
  overflow:hidden;
  border-radius:20px;
  background:linear-gradient(145deg,rgba(22,32,53,.96),rgba(17,24,39,.96) 54%,rgba(25,20,48,.94));
  border:1px solid rgba(133,181,255,.18);
  box-shadow:0 14px 34px rgba(0,0,0,.24),inset 0 1px 0 rgba(255,255,255,.07);
  color:#eef3ff;
}
.chat-invite-head{
  position:relative;
  padding:16px;
  overflow:hidden;
}
.chat-invite-head:before{
  content:"";
  position:absolute;
  width:150px;
  height:150px;
  border-radius:48px;
  right:-58px;
  top:-72px;
  background:linear-gradient(135deg,rgba(88,101,242,.42),rgba(255,255,255,.04));
  transform:rotate(18deg);
}
.chat-invite-pill{
  position:relative;
  z-index:1;
  display:inline-flex;
  align-items:center;
  gap:7px;
  padding:6px 10px;
  border-radius:999px;
  background:rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.10);
  color:#cfd9f2;
  font-size:10px;
  font-weight:800;
  letter-spacing:.08em;
  text-transform:uppercase;
  margin-bottom:14px;
}
.chat-invite-main{
  position:relative;
  z-index:1;
  display:flex;
  gap:13px;
  align-items:center;
}
.chat-invite-icon{
  width:62px;
  height:62px;
  flex:0 0 62px;
  border-radius:20px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg,rgba(88,101,242,.80),rgba(59,70,195,.58));
  border:1px solid rgba(255,255,255,.10);
  overflow:hidden;
  font-size:27px;
}
.chat-invite-icon img{
  width:100%;
  height:100%;
  object-fit:contain!important;
  padding:8px;
  background:transparent!important;
  border:none!important;
  box-shadow:none!important;
}
.chat-invite-info{
  min-width:0;
  flex:1;
}
.chat-invite-title{
  font-size:18px;
  line-height:1.18;
  font-weight:900;
  color:#fff;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}
.chat-invite-desc{
  margin-top:4px;
  color:#b8c7e4;
  font-size:12px;
  line-height:1.35;
  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;
  overflow:hidden;
}
.chat-invite-meta{
  display:flex;
  flex-wrap:wrap;
  gap:7px;
  margin-top:12px;
}
.chat-invite-chip{
  display:inline-flex;
  align-items:center;
  gap:6px;
  border-radius:11px;
  background:rgba(0,0,0,.22);
  border:1px solid rgba(255,255,255,.09);
  padding:7px 9px;
  color:#e7edff;
  font-size:12px;
  font-weight:650;
}
.chat-invite-actions{
  display:flex;
  gap:8px;
  padding:0 16px 16px;
}
.chat-invite-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:7px;
  min-height:36px;
  padding:8px 12px;
  border-radius:13px;
  border:none;
  font-family:inherit;
  font-size:13px;
  font-weight:800;
  cursor:pointer;
  transition:transform .12s,opacity .12s;
}
.chat-invite-btn:hover{transform:translateY(-1px)}
.chat-invite-btn.primary{
  flex:1;
  background:linear-gradient(135deg,#2d7dff,#6a63ff);
  color:#fff;
  box-shadow:0 10px 24px rgba(45,125,255,.22);
}
.chat-invite-btn.ghost{
  background:rgba(255,255,255,.07);
  color:#dce7ff;
  border:1px solid rgba(255,255,255,.08);
}
.chat-invite-error{
  margin-top:8px;
  max-width:430px;
  border-radius:16px;
  padding:13px 15px;
  background:rgba(237,66,69,.12);
  border:1px solid rgba(237,66,69,.26);
  color:#ffb8bd;
  font-size:12px;
}
[data-theme="vk"] .chat-invite-card{
  background:#fff!important;
  color:#000!important;
  border:1px solid #dce1e6!important;
  box-shadow:0 8px 24px rgba(0,0,0,.10)!important;
}
[data-theme="vk"] .chat-invite-title{color:#000!important}
[data-theme="vk"] .chat-invite-desc{color:#626d7a!important}
[data-theme="vk"] .chat-invite-chip{background:#f5f7fa!important;color:#2c3e50!important;border-color:#dce1e6!important}
[data-theme="vk"] .chat-invite-pill{background:#f5f7fa!important;color:#0077ff!important;border-color:#dce1e6!important}
@media(max-width:740px){
  .chat-invite-card{max-width:100%;border-radius:18px}
  .chat-invite-head{padding:14px}
  .chat-invite-icon{width:54px;height:54px;flex-basis:54px;border-radius:17px}
  .chat-invite-title{font-size:16px}
  .chat-invite-desc{font-size:11px}
  .chat-invite-meta{gap:6px;margin-top:10px}
  .chat-invite-chip{font-size:11px;padding:6px 8px}
  .chat-invite-actions{padding:0 14px 14px}
  .chat-invite-btn{min-height:34px;font-size:12px}
}


/* Modern site and YouTube previews */
.link-preview-card{
  margin-top:9px!important;
  max-width:540px!important;
  display:flex!important;
  gap:0!important;
  overflow:hidden!important;
  background:linear-gradient(145deg,rgba(17,27,45,.92),rgba(13,19,32,.94))!important;
  border:1px solid rgba(133,181,255,.16)!important;
  border-left:3px solid #2d7dff!important;
  border-radius:18px!important;
  color:#eef3ff!important;
  box-shadow:0 10px 28px rgba(0,0,0,.20),inset 0 1px 0 rgba(255,255,255,.06)!important;
}
.link-preview-card:hover{
  background:linear-gradient(145deg,rgba(21,34,57,.96),rgba(16,23,39,.96))!important;
  border-color:rgba(83,150,255,.42)!important;
  transform:translateY(-1px)!important;
}
.link-preview-body{
  padding:13px 14px!important;
  gap:5px!important;
}
.link-preview-site{
  display:flex!important;
  align-items:center!important;
  gap:7px!important;
  font-size:11px!important;
  color:#8fb5ff!important;
  font-weight:800!important;
  text-transform:uppercase!important;
  letter-spacing:.08em!important;
}
.link-preview-site:before{
  content:"";
  width:7px;
  height:7px;
  border-radius:50%;
  background:#2d7dff;
  box-shadow:0 0 12px rgba(45,125,255,.55);
  flex:0 0 7px;
}
.link-preview-title{
  font-size:15px!important;
  font-weight:850!important;
  color:#fff!important;
  line-height:1.25!important;
}
.link-preview-desc{
  font-size:12px!important;
  color:#b8c7e4!important;
  line-height:1.4!important;
}
.link-preview-img{
  width:132px!important;
  min-width:132px!important;
  min-height:100px!important;
  background:rgba(255,255,255,.04)!important;
  border-left:1px solid rgba(255,255,255,.08)!important;
  object-fit:cover!important;
}
.link-preview-loading{
  margin-top:9px!important;
  max-width:440px!important;
  padding:10px 12px!important;
  border-radius:14px!important;
  background:rgba(45,125,255,.08)!important;
  border:1px solid rgba(133,181,255,.14)!important;
  border-left:3px solid rgba(45,125,255,.55)!important;
  color:#9fb2d6!important;
}
.youtube-preview-card{
  position:relative!important;
  cursor:pointer!important;
  background:linear-gradient(145deg,rgba(42,16,20,.96),rgba(16,19,32,.96))!important;
  border-left-color:#ff3b3b!important;
}
.youtube-preview-card:hover{
  background:linear-gradient(145deg,rgba(55,18,24,.98),rgba(20,24,39,.98))!important;
  border-color:rgba(255,70,70,.42)!important;
}
.youtube-preview-card .link-preview-site{
  color:#ff9b9b!important;
}
.youtube-preview-card .link-preview-site:before{
  background:#ff3b3b!important;
  box-shadow:0 0 12px rgba(255,59,59,.55)!important;
}
.youtube-preview-media{
  width:168px!important;
  min-width:168px!important;
  min-height:104px!important;
  background:#090b10!important;
  border-left:1px solid rgba(255,255,255,.08)!important;
}
.youtube-preview-play{
  width:52px!important;
  height:36px!important;
  border-radius:13px!important;
  background:linear-gradient(135deg,#ff2d2d,#c91818)!important;
  box-shadow:0 10px 28px rgba(0,0,0,.50),0 0 0 1px rgba(255,255,255,.12) inset!important;
}
.youtube-preview-actions{
  margin-top:8px!important;
}
.youtube-preview-action{
  height:29px!important;
  border-radius:10px!important;
  border:1px solid rgba(255,255,255,.10)!important;
  background:rgba(255,255,255,.06)!important;
  color:#e9efff!important;
  font-size:12px!important;
  font-weight:750!important;
}
.youtube-preview-action:hover{
  background:rgba(255,59,59,.16)!important;
  color:#fff!important;
}
[data-theme="vk"] .link-preview-card{
  background:#fff!important;
  color:#000!important;
  border:1px solid #dce1e6!important;
  border-left:3px solid #0077ff!important;
  box-shadow:0 8px 24px rgba(0,0,0,.10)!important;
}
[data-theme="vk"] .link-preview-title{color:#000!important}
[data-theme="vk"] .link-preview-desc{color:#626d7a!important}
[data-theme="vk"] .link-preview-site{color:#0077ff!important}
[data-theme="vk"] .link-preview-img{border-left:1px solid #dce1e6!important;background:#f5f7fa!important}
/* VK (light) theme: YouTube preview card was dark with washed-out buttons (light text on light bg). */
[data-theme="vk"] .youtube-preview-card{background:#fff!important;border:1px solid #dce1e6!important;border-left:3px solid #d63c32!important}
[data-theme="vk"] .youtube-preview-card:hover{background:#f6f8fb!important;border-color:#d63c32!important}
[data-theme="vk"] .youtube-preview-card .link-preview-site{color:#d63c32!important}
[data-theme="vk"] .youtube-preview-card .link-preview-title{color:#000!important}
[data-theme="vk"] .youtube-preview-card .link-preview-desc{color:#626d7a!important}
[data-theme="vk"] .youtube-preview-action{background:#f0f2f5!important;border:1px solid #d3d9e0!important;color:#1c1c1e!important}
[data-theme="vk"] .youtube-preview-action:hover{background:#d63c32!important;border-color:#d63c32!important;color:#fff!important}
[data-theme="vk"] .youtube-preview-media{background:#0a0a0a!important;border-left:1px solid #dce1e6!important}
@media(max-width:640px){
  .link-preview-card{
    max-width:100%!important;
    border-radius:16px!important;
  }
  .link-preview-body{
    padding:11px 12px!important;
  }
  .link-preview-title{
    font-size:14px!important;
  }
  .link-preview-img,
  .youtube-preview-media{
    width:104px!important;
    min-width:104px!important;
    min-height:88px!important;
  }
  .link-preview-desc{
    -webkit-line-clamp:2!important;
  }
  .youtube-preview-play{
    width:44px!important;
    height:31px!important;
  }
}
@media(max-width:430px){
  .link-preview-card{
    flex-direction:column!important;
  }
  .link-preview-img,
  .youtube-preview-media{
    width:100%!important;
    min-width:100%!important;
    height:150px!important;
    min-height:150px!important;
    border-left:none!important;
    border-top:1px solid rgba(255,255,255,.08)!important;
    order:2;
  }
}


/* trueCOLOR flagship refresh */
[data-theme="truecolor"]{
  --tc-accent:#2d7dff;
  --tc-accent-2:#7a6bff;
  --tc-accent-soft:#7fb2ff;
  --tc-panel-top:rgba(29,42,67,.90);
  --tc-panel-bottom:rgba(13,19,32,.92);
  --tc-glass-hi:rgba(255,255,255,.07);
  --tc-glass-mid:rgba(255,255,255,.045);
  --tc-voice-glow:rgba(67,181,129,.28);
  --tc-voice-glow-2:rgba(67,181,129,.14);
}

[data-theme="truecolor"] body,
[data-theme="truecolor"] #app{
  background:
    radial-gradient(circle at 14% 14%,rgba(45,125,255,.15),transparent 26%),
    radial-gradient(circle at 84% 10%,rgba(122,107,255,.12),transparent 24%),
    radial-gradient(circle at 50% 100%,rgba(48,86,158,.14),transparent 34%),
    linear-gradient(180deg,#08101b 0%,#0a1220 44%,#0d1524 100%)!important;
}

[data-theme="truecolor"] #sidebarHeader,
[data-theme="truecolor"] #mainHeader,
[data-theme="truecolor"] #inputOuter,
[data-theme="truecolor"] #dmInputOuter,
[data-theme="truecolor"] #voiceBar,
[data-theme="truecolor"] .modal,
[data-theme="truecolor"] #notifPanel,
[data-theme="truecolor"] #emojiPicker{
  position:relative!important;
  overflow:hidden!important;
}
/* floatingPlayer & streamViewer must keep their fixed bottom-right positioning;
   position:fixed already establishes a containing block for their ::before overlay. */
[data-theme="truecolor"] #floatingPlayer,
[data-theme="truecolor"] #streamViewer{
  overflow:hidden!important;
}

[data-theme="truecolor"] #serverBar::before,
[data-theme="truecolor"] #chSidebar::before,
[data-theme="truecolor"] #mainArea::before,
[data-theme="truecolor"] #memberSidebar::before,
[data-theme="truecolor"] .modal::before,
[data-theme="truecolor"] #voiceBar::before{
  content:"";
  position:absolute;
  inset:0;
  pointer-events:none;
  background:
    linear-gradient(180deg,rgba(255,255,255,.04),transparent 22%),
    radial-gradient(circle at 100% 0,rgba(122,107,255,.08),transparent 28%);
  opacity:.9;
}

[data-theme="truecolor"] #serverBar{
  background:
    linear-gradient(180deg,rgba(18,27,43,.94) 0%,rgba(11,17,29,.98) 100%)!important;
  border-right:1px solid rgba(255,255,255,.05)!important;
  box-shadow:inset -1px 0 0 rgba(255,255,255,.03), 8px 0 32px rgba(0,0,0,.18)!important;
}

[data-theme="truecolor"] #chSidebar{
  background:
    linear-gradient(180deg,rgba(17,25,40,.95) 0%,rgba(13,19,33,.98) 100%)!important;
  border-right:1px solid rgba(255,255,255,.05)!important;
  box-shadow:inset -1px 0 0 rgba(255,255,255,.03)!important;
}

[data-theme="truecolor"] #mainArea,
[data-theme="truecolor"] #memberSidebar{
  background:
    radial-gradient(circle at 0 0,rgba(45,125,255,.06),transparent 24%),
    linear-gradient(180deg,rgba(18,27,43,.94) 0%,rgba(12,18,30,.98) 100%)!important;
}

[data-theme="truecolor"] #sidebarHeader,
[data-theme="truecolor"] #mainHeader,
[data-theme="truecolor"] #inputOuter,
[data-theme="truecolor"] #dmInputOuter{
  background:linear-gradient(180deg,rgba(21,31,49,.78),rgba(14,21,36,.86))!important;
  border-color:rgba(255,255,255,.06)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 10px 24px rgba(0,0,0,.10)!important;
  backdrop-filter:blur(20px) saturate(140%)!important;
}

[data-theme="truecolor"] .srv-icon{
  background:linear-gradient(180deg,rgba(255,255,255,.04),rgba(255,255,255,.015))!important;
  border:1px solid rgba(255,255,255,.06)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03),0 8px 20px rgba(0,0,0,.18)!important;
}
[data-theme="truecolor"] .srv-icon:hover{
  transform:translateY(-1px)!important;
  background:linear-gradient(180deg,rgba(45,125,255,.14),rgba(255,255,255,.03))!important;
  border-color:rgba(133,181,255,.42)!important;
  box-shadow:0 10px 26px rgba(16,32,70,.32)!important;
}
[data-theme="truecolor"] .srv-icon.active{
  background:linear-gradient(180deg,rgba(45,125,255,.24),rgba(122,107,255,.14))!important;
  border-color:rgba(125,177,255,.52)!important;
  box-shadow:0 0 0 1px rgba(255,255,255,.04) inset,0 0 0 3px rgba(45,125,255,.12),0 16px 34px rgba(22,44,90,.34)!important;
}

[data-theme="truecolor"] .ch-item,
[data-theme="truecolor"] .dm-item,
[data-theme="truecolor"] .mb-item{
  border:1px solid transparent!important;
  border-radius:14px!important;
  transition:background .16s,border-color .16s,transform .16s,box-shadow .16s!important;
}
[data-theme="truecolor"] .ch-item:hover,
[data-theme="truecolor"] .dm-item:hover,
[data-theme="truecolor"] .mb-item:hover{
  transform:translateX(2px)!important;
  background:linear-gradient(90deg,rgba(45,125,255,.11),rgba(122,107,255,.06))!important;
  border-color:rgba(133,181,255,.10)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03)!important;
}
[data-theme="truecolor"] .ch-item.active,
[data-theme="truecolor"] .dm-item.active,
[data-theme="truecolor"] .mention-item.active{
  background:linear-gradient(90deg,rgba(45,125,255,.20),rgba(122,107,255,.12))!important;
  border-color:rgba(133,181,255,.18)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05), 0 10px 20px rgba(12,24,48,.18)!important;
}

[data-theme="truecolor"] .fi{
  background:linear-gradient(180deg,rgba(8,14,26,.78),rgba(8,14,26,.60))!important;
  border:1px solid rgba(120,150,210,.16)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03)!important;
}
[data-theme="truecolor"] .fi:hover{
  border-color:rgba(133,181,255,.24)!important;
}
[data-theme="truecolor"] .fi:focus{
  background:linear-gradient(180deg,rgba(9,16,29,.92),rgba(8,14,26,.78))!important;
  border-color:rgba(83,150,255,.58)!important;
  box-shadow:0 0 0 3px rgba(45,125,255,.14),0 12px 28px rgba(12,34,74,.16)!important;
}

[data-theme="truecolor"] .btn-gold{
  background:linear-gradient(135deg,#2d7dff 0%,#5f7bff 50%,#7a6bff 100%)!important;
  box-shadow:0 12px 28px rgba(45,125,255,.28), inset 0 1px 0 rgba(255,255,255,.16)!important;
}
[data-theme="truecolor"] .btn-gold:hover{
  background:linear-gradient(135deg,#5396ff 0%,#6d8bff 50%,#8a7bff 100%)!important;
  transform:translateY(-1px)!important;
}

[data-theme="truecolor"] .modal,
[data-theme="truecolor"] #ctxMenu,
[data-theme="truecolor"] #notifPanel,
[data-theme="truecolor"] #emojiPicker,
[data-theme="truecolor"] #floatingPlayer,
[data-theme="truecolor"] #streamViewer{
  background:
    linear-gradient(180deg,rgba(24,35,57,.92),rgba(14,21,35,.96))!important;
  border:1px solid rgba(255,255,255,.07)!important;
  box-shadow:0 22px 54px rgba(0,0,0,.40), inset 0 1px 0 rgba(255,255,255,.06)!important;
  backdrop-filter:blur(24px) saturate(150%)!important;
}

[data-theme="truecolor"] #voiceBar{
  background:
    linear-gradient(180deg,rgba(17,27,43,.88),rgba(12,19,32,.94))!important;
  border-top:1px solid rgba(84,143,255,.18)!important;
  box-shadow:0 -8px 30px rgba(0,0,0,.20), inset 0 1px 0 rgba(255,255,255,.04)!important;
}
[data-theme="truecolor"] .vb-btn{
  background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.03))!important;
  border:1px solid rgba(255,255,255,.07)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05)!important;
}
[data-theme="truecolor"] .vb-btn:hover{
  background:linear-gradient(180deg,rgba(45,125,255,.12),rgba(255,255,255,.04))!important;
  border-color:rgba(83,150,255,.24)!important;
}
[data-theme="truecolor"] .vb-btn.active{
  background:linear-gradient(180deg,rgba(45,125,255,.22),rgba(122,107,255,.12))!important;
  border-color:rgba(83,150,255,.32)!important;
  box-shadow:0 10px 22px rgba(45,125,255,.14)!important;
}

[data-theme="truecolor"] #voiceBar.local-speaking{
  border-top-color:rgba(67,181,129,.48)!important;
  box-shadow:0 -1px 0 rgba(67,181,129,.22),0 -10px 28px rgba(0,0,0,.18), 0 0 0 1px rgba(67,181,129,.04) inset!important;
}
[data-theme="truecolor"] #voiceBar.local-speaking::after{
  content:"";
  position:absolute;
  inset:-30% -10%;
  pointer-events:none;
  background:
    radial-gradient(circle at 20% 70%,rgba(67,181,129,.14),transparent 20%),
    linear-gradient(90deg,transparent,rgba(67,181,129,.14),rgba(67,181,129,.06),transparent);
  filter:blur(18px);
  animation:tcSpeakSweep 2.4s linear infinite;
}
[data-theme="truecolor"] #voiceBar.local-speaking .vb-room-name{
  color:#78d7a0!important;
  text-shadow:0 0 18px rgba(67,181,129,.18);
}
[data-theme="truecolor"] #voiceBar.local-speaking .vb-room-name::after{
  background:rgba(67,181,129,.14)!important;
  border-color:rgba(67,181,129,.34)!important;
  color:#8be0b0!important;
  box-shadow:0 0 0 1px rgba(255,255,255,.02) inset, 0 0 16px rgba(67,181,129,.12)!important;
}
[data-theme="truecolor"] #voiceBar.local-speaking #vbMuteBtn{
  border-color:rgba(67,181,129,.34)!important;
  color:#78d7a0!important;
  box-shadow:0 0 0 1px rgba(67,181,129,.24) inset, 0 0 20px rgba(67,181,129,.12)!important;
}

[data-theme="truecolor"] .voice-part-item,
[data-theme="truecolor"] .vs-user{
  position:relative!important;
  overflow:hidden!important;
  background:linear-gradient(180deg,rgba(255,255,255,.02),rgba(255,255,255,.01))!important;
  border:1px solid rgba(255,255,255,.05)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03)!important;
}
[data-theme="truecolor"] .voice-part-item.speaking,
[data-theme="truecolor"] .vs-user.speaking{
  background:
    linear-gradient(90deg,rgba(67,181,129,.10),rgba(67,181,129,.06),rgba(45,125,255,.04))!important;
  border-color:rgba(67,181,129,.22)!important;
  color:#78d7a0!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.04),0 12px 24px rgba(22,42,34,.14)!important;
}
[data-theme="truecolor"] .voice-part-item.speaking::after,
[data-theme="truecolor"] .vs-user.speaking::after{
  content:"";
  position:absolute;
  inset:-20% -10%;
  pointer-events:none;
  background:
    linear-gradient(90deg,transparent,rgba(67,181,129,.18),rgba(255,255,255,.05),transparent);
  filter:blur(16px);
  animation:tcSpeakSweep 2.1s linear infinite;
}
[data-theme="truecolor"] .voice-part-item.speaking .vp-av,
[data-theme="truecolor"] .vs-user.speaking .vs-av{
  box-shadow:0 0 0 1px rgba(67,181,129,.20),0 0 0 5px rgba(67,181,129,.08),0 0 28px rgba(67,181,129,.14)!important;
  outline:none!important;
}
[data-theme="truecolor"] .voice-part-item.speaking .vp-wave,
[data-theme="truecolor"] .vs-user.speaking .vs-sub{
  color:#8be0b0!important;
}
[data-theme="truecolor"] .voice-part-item.speaking .vp-name,
[data-theme="truecolor"] .vs-user.speaking .vs-name{
  color:#dff8eb!important;
}

[data-theme="truecolor"] .msg,
[data-theme="truecolor"] .msg-item,
[data-theme="truecolor"] .message-item{
  border-radius:16px!important;
}
[data-theme="truecolor"] .mention{
  background:linear-gradient(90deg,rgba(45,125,255,.16),rgba(122,107,255,.10))!important;
  border-color:rgba(133,181,255,.18)!important;
}

[data-theme="truecolor"] .toast{
  background:linear-gradient(180deg,rgba(20,31,49,.96),rgba(14,21,35,.98))!important;
  box-shadow:0 20px 44px rgba(0,0,0,.34)!important;
}

@keyframes tcSpeakSweep{
  0%{transform:translateX(-22%)}
  50%{transform:translateX(12%)}
  100%{transform:translateX(28%)}
}


/* Redesigned personal settings modal */
.settings-modal{
  max-width:760px!important;
  padding:0!important;
  overflow:hidden!important;
}
.settings-modal #modalBody{
  padding:0!important;
}
.settings-shell{
  padding:22px;
}
.settings-hero{
  display:grid;
  grid-template-columns:minmax(0,1.2fr) 220px;
  gap:18px;
  align-items:stretch;
  margin-bottom:18px;
}
.settings-hero-copy,
.settings-profile-card,
.settings-card,
.settings-accordion,
.settings-bottom-grid > *{
  position:relative;
  overflow:hidden;
  border-radius:20px;
  border:1px solid rgba(255,255,255,.08);
  background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.025));
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 14px 36px rgba(0,0,0,.16);
}
.settings-hero-copy{
  padding:22px 22px 20px;
  background:
    radial-gradient(circle at 90% 0,rgba(122,107,255,.16),transparent 34%),
    linear-gradient(145deg,rgba(45,125,255,.14),rgba(255,255,255,.03));
}
.settings-hero-copy::after,
.settings-profile-card::after,
.settings-card::after{
  content:"";
  position:absolute;
  inset:auto -18% -42% auto;
  width:180px;
  height:180px;
  border-radius:999px;
  background:radial-gradient(circle,rgba(45,125,255,.12),transparent 68%);
  pointer-events:none;
}
.settings-kicker{
  display:inline-flex;
  align-items:center;
  gap:7px;
  padding:7px 11px;
  border-radius:999px;
  background:var(--gold-dim);
  border:1px solid var(--gold-glow);
  color:var(--gold);
  font-size:11px;
  font-weight:800;
  letter-spacing:.08em;
  text-transform:uppercase;
  margin-bottom:14px;
}
.settings-hero h2{
  margin:0 0 8px!important;
  font-size:34px!important;
  line-height:1.02!important;
  color:#fff!important;
}
.settings-hero .sub{
  margin:0!important;
  max-width:460px;
  font-size:14px!important;
  line-height:1.55!important;
  color:var(--text2)!important;
}
.settings-profile-card{
  padding:18px;
  text-align:center;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:8px;
  background:
    radial-gradient(circle at 50% 0,rgba(122,107,255,.18),transparent 30%),
    linear-gradient(180deg,rgba(24,37,60,.78),rgba(16,24,40,.88));
}
.settings-avatar-wrap{margin:0!important}
.settings-avatar{
  width:92px!important;
  height:92px!important;
  border-radius:28px!important;
  box-shadow:0 14px 32px rgba(0,0,0,.26),0 0 0 1px rgba(255,255,255,.08) inset!important;
}
.settings-avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
}
.settings-profile-name{
  font-size:21px;
  font-weight:900;
  color:#fff;
  line-height:1.1;
}
.settings-profile-meta{
  font-size:13px;
  color:var(--text2);
  white-space:nowrap;
  display:inline-flex;
  align-items:center;
  gap:6px;
}
.settings-profile-badges{
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:8px;
  margin-top:2px;
}
.settings-badge{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:7px 10px;
  border-radius:999px;
  background:rgba(0,0,0,.18);
  border:1px solid rgba(255,255,255,.08);
  color:#dce6ff;
  font-size:11px;
  font-weight:800;
}
.settings-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:16px;
}
.settings-card{
  padding:18px;
}
.settings-card-head{
  margin-bottom:14px;
}
.settings-card-title{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:17px;
  font-weight:850;
  color:#fff;
  line-height:1.25;
}
.settings-card-title .ti{display:inline-flex;align-items:center;flex-shrink:0}
.settings-card-title .ti svg{width:16px;height:16px;display:block}
.settings-card-sub{
  margin-top:4px;
  font-size:12px;
  line-height:1.45;
  color:var(--text3);
}
.settings-static{
  opacity:.78!important;
}
.settings-theme-note{
  display:flex;
  align-items:flex-start;
  gap:10px;
  padding:12px 13px;
  border-radius:15px;
  background:rgba(45,125,255,.08);
  border:1px solid rgba(133,181,255,.12);
  color:var(--text2);
  font-size:12px;
  line-height:1.45;
  margin-top:4px;
}
.settings-theme-dot{
  width:10px;
  height:10px;
  border-radius:50%;
  background:linear-gradient(135deg,#2d7dff,#7a6bff);
  box-shadow:0 0 16px rgba(45,125,255,.35);
  margin-top:3px;
  flex:0 0 10px;
}
.settings-soft-btn{
  min-height:44px!important;
  border-radius:14px!important;
  background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.03))!important;
}
.settings-actions-row{
  display:flex;
  gap:10px;
  margin-top:14px;
}
.settings-actions-row .btn{
  flex:1;
}
.settings-accordion{
  margin-top:16px;
  padding:0;
}
.settings-accordion summary{
  list-style:none;
  cursor:pointer;
  display:flex;
  align-items:center;
  gap:8px;
  padding:16px 18px;
  font-size:14px;
  font-weight:800;
  color:#eef3ff;
  user-select:none;
}
.settings-accordion summary::-webkit-details-marker{display:none}
.settings-accordion summary:hover{
  background:rgba(255,255,255,.03);
}
.settings-accordion-body{
  padding:0 18px 18px;
}
.settings-bottom-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
  margin-top:16px;
}
.settings-bottom-grid > .btn{
  min-height:46px!important;
  border-radius:16px!important;
}
.settings-modal .modal-close{
  top:16px!important;
  right:16px!important;
  width:42px!important;
  height:42px!important;
  border-radius:16px!important;
  background:rgba(255,255,255,.06)!important;
  border:1px solid rgba(255,255,255,.09)!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05)!important;
}
.settings-modal .modal-close .ti svg{
  width:16px!important;
  height:16px!important;
}
@media(max-width:820px){
  .settings-hero{
    grid-template-columns:1fr;
  }
  .settings-profile-card{
    flex-direction:column;
    justify-content:center;
    text-align:center;
    align-items:center;
    padding:18px 16px;
    gap:10px;
  }
  .settings-profile-badges{
    justify-content:center;
    flex-direction:column;
    align-items:stretch;
    width:100%;
    max-width:280px;
  }
  .settings-profile-badges .settings-badge{
    justify-content:center;
  }
}
@media(max-width:740px){
  .settings-modal{
    max-width:100%!important;
  }
  .settings-shell{
    padding:14px;
  }
  .settings-hero{
    gap:12px;
    margin-bottom:12px;
  }
  .settings-hero-copy{
    padding:16px;
    border-radius:18px;
  }
  .settings-hero h2{
    font-size:25px!important;
  }
  .settings-hero .sub{
    font-size:13px!important;
  }
  .settings-profile-card{
    border-radius:18px;
    gap:12px;
  }
  .settings-avatar{
    width:72px!important;
    height:72px!important;
    border-radius:22px!important;
  }
  .settings-profile-name{
    font-size:18px;
  }
  .settings-grid,
  .settings-bottom-grid{
    grid-template-columns:1fr;
    gap:12px;
  }
  .settings-card,
  .settings-accordion{
    border-radius:18px;
  }
  .settings-card{
    padding:15px;
  }
  .settings-actions-row{
    flex-direction:column;
  }
}


/* Fix context menu and clickable mentions after trueCOLOR refresh */
[data-theme="truecolor"] #ctxMenu{
  position:fixed!important;
  overflow:visible!important;
  z-index:20000!important;
}
[data-theme="truecolor"] #ctxMenu.open{
  display:block!important;
}
.msg-text .mention{
  cursor:pointer;
  user-select:none;
  border-radius:7px;
  padding:1px 4px;
  transition:background .12s,color .12s,transform .12s;
}
.msg-text .mention:hover{
  background:rgba(45,125,255,.18)!important;
  color:#9fc8ff!important;
  transform:translateY(-1px);
}


/* Compact settings fit fix: user + server settings */
.settings-modal,
.server-settings-modal{
  width:min(960px,calc(100vw - 28px))!important;
  max-width:min(960px,calc(100vw - 28px))!important;
  max-height:calc(100vh - 28px)!important;
  display:flex!important;
  flex-direction:column!important;
  overflow:hidden!important;
}
.settings-modal #modalBody,
.server-settings-modal #modalBody{
  padding:0!important;
  overflow:auto!important;
  max-height:calc(100vh - 28px)!important;
  scrollbar-width:thin;
}
.settings-shell,
.server-settings-shell{
  padding:18px;
  min-width:0;
}
.settings-hero{
  grid-template-columns:minmax(0,1.1fr) 250px!important;
  align-items:stretch!important;
}
.settings-hero h2{
  font-size:28px!important;
  line-height:1.02!important;
  word-break:break-word;
}
.settings-hero .sub{
  max-width:none!important;
  display:-webkit-box;
  -webkit-box-orient:vertical;
  -webkit-line-clamp:4;
  overflow:hidden;
}
.settings-profile-card{
  min-width:0;
}
.settings-grid{
  grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important;
}
.settings-card,
.server-settings-maincard,
.server-settings-sidecard{
  min-width:0;
}
.settings-card .fi,
.server-settings-shell .fi{
  min-width:0;
}
.settings-accordion{
  overflow:hidden;
}
.settings-bottom-grid{
  grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important;
}
.server-settings-hero{
  display:grid;
  grid-template-columns:minmax(0,1.25fr) 290px;
  gap:16px;
  margin-bottom:16px;
}
.server-settings-maincard,
.server-settings-sidecard{
  position:relative;
  overflow:hidden;
  border-radius:20px;
  border:1px solid rgba(255,255,255,.08);
  background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.025));
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 14px 36px rgba(0,0,0,.16);
  padding:18px;
}
.server-settings-maincard{
  background:
    radial-gradient(circle at 100% 0,rgba(122,107,255,.18),transparent 28%),
    linear-gradient(145deg,rgba(45,125,255,.12),rgba(255,255,255,.03));
}
.server-settings-kicker{
  display:inline-flex;
  align-items:center;
  gap:7px;
  padding:7px 11px;
  border-radius:999px;
  background:var(--gold-dim);
  border:1px solid var(--gold-glow);
  color:var(--gold);
  font-size:11px;
  font-weight:800;
  letter-spacing:.08em;
  text-transform:uppercase;
  margin-bottom:14px;
}
.server-settings-brand{
  display:flex;
  gap:16px;
  align-items:center;
}
.server-settings-avatar{
  flex:0 0 auto;
  margin:0!important;
}
.server-settings-avatar .av-preview{
  width:88px!important;
  height:88px!important;
  border-radius:24px!important;
}
.server-settings-headcopy{
  min-width:0;
}
.server-settings-headcopy h2{
  margin:0 0 6px!important;
  font-size:28px!important;
  line-height:1.05!important;
  color:#fff!important;
  word-break:break-word;
}
.server-settings-headcopy .sub{
  margin:0!important;
  color:var(--text2)!important;
  font-size:13px!important;
  line-height:1.48!important;
}
.server-settings-badges{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  margin-top:12px;
}
.server-quick-actions{
  display:flex;
  flex-direction:column;
  gap:8px;
  margin-top:12px;
}
.server-settings-grid{
  display:grid;
  grid-template-columns:minmax(0,1fr) minmax(0,1fr);
  gap:16px;
}
.server-settings-modal .modal-close,
.settings-modal .modal-close{
  top:14px!important;
  right:14px!important;
}

/* ── VK (светлая) тема: карточки настроек ────────────────────────
   База рассчитана на тёмные темы (белый текст, полупрозрачный белый фон).
   На светлом VK-фоне заголовки были белым по белому — невидимы. Чиним. */
[data-theme="vk"] .settings-card,
[data-theme="vk"] .settings-accordion,
[data-theme="vk"] .settings-bottom-grid > *,
[data-theme="vk"] .server-settings-maincard,
[data-theme="vk"] .server-settings-sidecard{
  background:#ffffff!important;
  border:1px solid var(--border)!important;
  box-shadow:0 6px 20px rgba(0,0,0,.06)!important;
}
[data-theme="vk"] .settings-hero-copy,
[data-theme="vk"] .settings-profile-card{
  background:
    radial-gradient(circle at 90% 0,rgba(0,119,255,.12),transparent 36%),
    linear-gradient(145deg,rgba(0,119,255,.10),rgba(255,255,255,.6))!important;
  border:1px solid var(--border)!important;
  box-shadow:0 6px 20px rgba(0,0,0,.06)!important;
}
[data-theme="vk"] .server-settings-maincard{
  background:
    radial-gradient(circle at 100% 0,rgba(0,119,255,.10),transparent 30%),
    #ffffff!important;
}
[data-theme="vk"] .settings-card-title,
[data-theme="vk"] .settings-hero h2,
[data-theme="vk"] .server-settings-headcopy h2{
  color:var(--text)!important;
}
[data-theme="vk"] .settings-kicker,
[data-theme="vk"] .server-settings-kicker{
  background:rgba(0,119,255,.08)!important;
  border:1px solid rgba(0,119,255,.18)!important;
  color:var(--gold2)!important;
}
/* декоративные радиальные «капли» в углу карточек на светлом фоне лишние */
[data-theme="vk"] .settings-hero-copy::after,
[data-theme="vk"] .settings-profile-card::after,
[data-theme="vk"] .settings-card::after{
  opacity:.4;
}

/* ── Эргономика: гарантируем, что заголовок не обрезается сверху ── */
.settings-card-head,
.server-settings-headcopy{
  padding-top:2px;
}
.settings-card-title,
.server-settings-headcopy h2{
  line-height:1.2;
}

@media(max-width:980px){
  .settings-modal,
  .server-settings-modal{
    width:min(100vw - 20px,880px)!important;
    max-width:min(100vw - 20px,880px)!important;
  }
  .settings-hero,
  .server-settings-hero,
  .settings-grid,
  .server-settings-grid{
    grid-template-columns:1fr!important;
  }
  .settings-profile-card{
    flex-direction:row;
    justify-content:flex-start;
    text-align:left;
    gap:12px;
  }
  .settings-profile-badges{
    justify-content:flex-start!important;
  }
}
@media(max-height:860px){
  .settings-shell,
  .server-settings-shell{
    padding:14px;
  }
  .settings-hero h2,
  .server-settings-headcopy h2{
    font-size:24px!important;
  }
  .settings-card,
  .server-settings-maincard,
  .server-settings-sidecard{
    padding:15px;
  }
}
@media(max-width:740px){
  .settings-modal,
  .server-settings-modal{
    width:calc(100vw - 12px)!important;
    max-width:calc(100vw - 12px)!important;
    max-height:calc(100vh - 12px)!important;
    border-radius:20px!important;
  }
  .settings-modal #modalBody,
  .server-settings-modal #modalBody{
    max-height:calc(100vh - 12px)!important;
  }
  .settings-shell,
  .server-settings-shell{
    padding:12px;
  }
  .settings-hero h2,
  .server-settings-headcopy h2{
    font-size:23px!important;
  }
  .settings-hero-copy,
  .settings-profile-card,
  .settings-card,
  .settings-accordion,
  .server-settings-maincard,
  .server-settings-sidecard{
    border-radius:18px!important;
  }
  .server-settings-brand{
    align-items:flex-start;
  }
  .server-settings-avatar .av-preview{
    width:72px!important;
    height:72px!important;
    border-radius:20px!important;
  }
  .settings-bottom-grid{
    grid-template-columns:1fr!important;
  }
}


/* Better select/dropdown palette for settings and dark themes */
.fi{
  line-height:1.35;
}
select.fi{
  cursor:pointer;
  padding-right:38px!important;
  background-image:
    linear-gradient(45deg,transparent 50%,rgba(220,232,255,.78) 50%),
    linear-gradient(135deg,rgba(220,232,255,.78) 50%,transparent 50%);
  background-position:
    calc(100% - 18px) calc(50% - 2px),
    calc(100% - 12px) calc(50% - 2px);
  background-size:6px 6px, 6px 6px;
  background-repeat:no-repeat;
}
select.fi:hover{
  border-color:rgba(133,181,255,.28)!important;
}
select.fi option,
select.fi optgroup{
  font-family:inherit;
}

[data-theme="truecolor"],
[data-theme="default"],
[data-theme="midnight"],
[data-theme="amoled"],
[data-theme="discord"],
[data-theme="telegram"],
#loginScreen{
  color-scheme:dark;
}

[data-theme="truecolor"] select.fi,
[data-theme="default"] select.fi,
[data-theme="midnight"] select.fi,
[data-theme="amoled"] select.fi,
[data-theme="discord"] select.fi,
[data-theme="telegram"] select.fi,
#loginScreen select.fi{
  background-color:#0b1423!important;
  color:#eef3ff!important;
  border-color:rgba(120,150,210,.18)!important;
}

[data-theme="truecolor"] select.fi option,
[data-theme="default"] select.fi option,
[data-theme="midnight"] select.fi option,
[data-theme="amoled"] select.fi option,
[data-theme="discord"] select.fi option,
[data-theme="telegram"] select.fi option,
#loginScreen select.fi option{
  background:#0f1828!important;
  color:#eef3ff!important;
}

[data-theme="truecolor"] select.fi optgroup,
[data-theme="default"] select.fi optgroup,
[data-theme="midnight"] select.fi optgroup,
[data-theme="amoled"] select.fi optgroup,
[data-theme="discord"] select.fi optgroup,
[data-theme="telegram"] select.fi optgroup,
#loginScreen select.fi optgroup{
  background:#0c1320!important;
  color:#8fb5ff!important;
}

.login-lang-bottom{
  display:flex;
  align-items:center;
  gap:10px;
  margin:14px 0 0;
  padding:10px 0 0;
  border-top:1px solid var(--border);
  background:none;
  border-radius:0;
}
.login-lang-bottom .fl{
  margin:0!important;
  font-size:12px;
  color:var(--text3);
  white-space:nowrap;
  flex-shrink:0;
}
.login-lang-bottom .login-lang-select{
  width:auto;
  min-height:0;
  height:34px;
  flex:1;
  font-size:13px;
  padding:0 30px 0 12px;
  border-radius:9px;
}
.login-lang-row{
  display:grid;
  grid-template-columns:1fr;
  gap:6px;
  margin:0 0 16px;
  padding:12px 14px;
  border:1px solid rgba(255,255,255,.06);
  border-radius:16px;
  background:rgba(255,255,255,.03);
}
.login-lang-row .fl{
  margin:0!important;
}
.login-lang-select{
  width:100%;
  min-height:40px;
}

[data-theme="vk"] select.fi{
  color-scheme:light;
  background-image:
    linear-gradient(45deg,transparent 50%,rgba(40,56,84,.62) 50%),
    linear-gradient(135deg,rgba(40,56,84,.62) 50%,transparent 50%);
}
[data-theme="vk"] select.fi option,
[data-theme="vk"] select.fi optgroup{
  background:#ffffff!important;
  color:#1f2328!important;
}

.settings-card select.fi,
.server-settings-shell select.fi,
.modal select.fi{
  min-height:44px;
  border-radius:14px!important;
}

.settings-card select.fi:focus,
.server-settings-shell select.fi:focus,
.modal select.fi:focus{
  box-shadow:0 0 0 3px rgba(45,125,255,.16)!important;
}

[data-theme="truecolor"] .settings-card select.fi,
[data-theme="truecolor"] .server-settings-shell select.fi,
[data-theme="truecolor"] .modal select.fi{
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03);
}

[data-theme="truecolor"] .settings-card .fl,
[data-theme="truecolor"] .server-settings-shell .fl{
  color:#c7d7f2!important;
}

[data-theme="truecolor"] .settings-theme-note{
  background:rgba(45,125,255,.09)!important;
  border-color:rgba(133,181,255,.16)!important;
}


/* Server settings aligned with user settings look */
.server-settings-shell .settings-hero.settings-hero-compact{
  grid-template-columns:minmax(0,1fr) 320px!important;
}
.server-settings-shell .settings-profile-card.compact{
  min-height:210px;
}
.server-settings-shell .settings-profile-card .settings-profile-name{
  font-size:18px;
}
.server-settings-actions{
  display:grid;
  grid-template-columns:minmax(0,1fr) minmax(0,1fr);
  gap:10px;
}
.server-settings-actions .btn{
  min-height:44px;
}
.server-settings-stack{
  display:flex;
  flex-direction:column;
  gap:8px;
}
.server-settings-meta-note{
  font-size:12px;
  line-height:1.48;
  color:var(--text3);
  margin-top:8px;
}
@media(max-width:740px){
  .server-settings-shell .settings-hero.settings-hero-compact{
    grid-template-columns:1fr!important;
  }
  .server-settings-actions{
    grid-template-columns:1fr;
  }
}


/* Unified big windows / channel / mini-app styling */
.channel-settings-shell .settings-hero.settings-hero-compact,
.app-editor-shell .settings-hero.settings-hero-compact{
  grid-template-columns:minmax(0,1fr) 320px!important;
}
.channel-settings-shell .settings-profile-card.compact,
.app-editor-shell .settings-profile-card.compact{
  min-height:210px;
}
.channel-settings-shell .settings-profile-card .settings-profile-name,
.app-editor-shell .settings-profile-card .settings-profile-name{
  font-size:18px;
}
.channel-settings-grid,
.app-editor-grid{
  display:grid;
  grid-template-columns:minmax(0,1fr) minmax(0,1fr);
  gap:16px;
}
.app-editor-stack{
  display:flex;
  flex-direction:column;
  gap:8px;
}
.upload-chip{
  display:inline-flex;
  align-items:center;
  gap:6px;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.08);
  border-radius:999px;
  padding:8px 12px;
  cursor:pointer;
  font-size:12px;
  color:var(--text2);
  transition:.16s;
}
.upload-chip:hover{background:rgba(255,255,255,.07)}
.app-code-textarea{
  min-height:220px;
  font-family:'Consolas','Courier New',monospace!important;
  font-size:12px!important;
  line-height:1.55!important;
}
.miniapi-callout{
  margin-top:10px;
  border-radius:16px;
  padding:14px 14px;
  border:1px solid rgba(133,181,255,.16);
  background:rgba(45,125,255,.08);
}
.miniapi-callout-title{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:13px;
  font-weight:800;
  color:#dce8ff;
  margin-bottom:6px;
}
.miniapi-callout-copy{
  font-size:12px;
  color:var(--text2);
  line-height:1.55;
}
.app-catalog-shell .settings-card{
  padding:14px!important;
}
.app-catalog-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:12px;
  margin-top:4px;
}
.app-catalog-item{
  min-width:0;
  border-radius:18px;
  border:1px solid rgba(255,255,255,.08);
  background:
    radial-gradient(circle at 100% 0,rgba(122,107,255,.14),transparent 28%),
    linear-gradient(145deg,rgba(45,125,255,.08),rgba(255,255,255,.03));
  box-shadow:inset 0 1px 0 rgba(255,255,255,.04),0 10px 28px rgba(0,0,0,.12);
  padding:14px;
  display:flex;
  flex-direction:column;
  gap:10px;
}
.app-catalog-head{
  display:flex;
  align-items:flex-start;
  gap:10px;
  min-width:0;
}
.app-catalog-icon{
  width:42px;height:42px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  background:rgba(255,255,255,.05);
  font-size:24px;flex-shrink:0;
  overflow:hidden;
}
.app-catalog-icon img{width:100%;height:100%;object-fit:cover}
.app-catalog-name{
  font-size:15px;font-weight:800;color:#fff;line-height:1.15;
  word-break:break-word;
}
.app-catalog-meta{
  font-size:12px;color:var(--text3);line-height:1.45;margin-top:4px;
}
.app-catalog-actions{
  display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;
}
.big-modal-list{
  display:flex;
  flex-direction:column;
  gap:8px;
  max-height:min(56vh,480px);
  overflow-y:auto;
  padding-right:4px;
}
.big-modal-row{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px 12px;
  border-radius:14px;
  background:rgba(255,255,255,.035);
  border:1px solid rgba(255,255,255,.07);
}
.big-modal-row .grow{flex:1;min-width:0}
.big-modal-row .title{
  font-size:14px;font-weight:700;color:#fff;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
}
.big-modal-row .desc{
  font-size:12px;color:var(--text3);line-height:1.45;
}
.big-modal-row .actions{
  display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;
}
.big-modal-icon{
  width:42px;height:42px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  background:rgba(255,255,255,.05);flex-shrink:0;
}
.miniapp-api-body{
  background:
    radial-gradient(circle at top left,rgba(45,125,255,.20),transparent 26%),
    linear-gradient(135deg,#08101d,#0e1626 48%,#121830);
}
.miniapp-api-body .layout{
  background:rgba(10,14,24,.45);
  border:1px solid rgba(255,255,255,.06);
  border-radius:22px;
  overflow:hidden;
  margin:14px auto;
}
.miniapp-api-body .sidebar{
  background:rgba(10,17,29,.88);
}
.miniapp-api-body .main{
  background:rgba(21,28,44,.62);
}
.miniapp-api-body .hero{
  border-radius:22px;
  background:
    radial-gradient(circle at 100% 0,rgba(122,107,255,.18),transparent 30%),
    linear-gradient(145deg,rgba(45,125,255,.16),rgba(255,255,255,.03));
  border:1px solid rgba(133,181,255,.18);
}
.miniapp-api-body .method-card,
.miniapp-api-body .feat-card{
  background:rgba(11,17,28,.72);
  border-color:rgba(255,255,255,.08);
}
@media(max-width:980px){
  .channel-settings-shell .settings-hero.settings-hero-compact,
  .app-editor-shell .settings-hero.settings-hero-compact,
  .channel-settings-grid,
  .app-editor-grid{
    grid-template-columns:1fr!important;
  }
}
@media(max-width:740px){
  .app-catalog-grid{grid-template-columns:1fr}
  .big-modal-row{align-items:flex-start;flex-direction:column}
  .big-modal-row .actions{width:100%;justify-content:stretch}
  .big-modal-row .actions .btn{flex:1}
  .channel-settings-shell .settings-hero.settings-hero-compact,
  .app-editor-shell .settings-hero.settings-hero-compact{
    grid-template-columns:1fr!important;
  }
}


/* Mobile/tablet layout repair: apply phone UI up to 980px */
@media(max-width:980px){
  :root{
    --mobile-serverbar-h:calc(74px + var(--safe-bottom,0px));
    --mobile-top-h:0px;
    --mobile-ch-w:min(88vw,360px);
    --mobile-mb-w:min(86vw,340px);
  }

  html,body{
    width:100%!important;
    min-width:0!important;
    overflow:hidden!important;
  }

  #app{
    position:fixed!important;
    inset:0!important;
    width:100vw!important;
    height:var(--app-h,100vh)!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    padding-bottom:var(--mobile-serverbar-h)!important;
  }

  #serverBar{
    position:fixed!important;
    left:0!important;
    right:0!important;
    bottom:0!important;
    top:auto!important;
    width:100vw!important;
    min-width:0!important;
    max-width:100vw!important;
    height:var(--mobile-serverbar-h)!important;
    min-height:var(--mobile-serverbar-h)!important;
    display:flex!important;
    flex-direction:row!important;
    align-items:center!important;
    justify-content:flex-start!important;
    gap:10px!important;
    overflow-x:auto!important;
    overflow-y:hidden!important;
    padding:8px 10px calc(8px + var(--safe-bottom,0px))!important;
    border-right:0!important;
    border-top:1px solid rgba(255,255,255,.08)!important;
    z-index:160!important;
    background:rgba(10,16,28,.96)!important;
    box-shadow:0 -10px 30px rgba(0,0,0,.35)!important;
    backdrop-filter:blur(18px) saturate(140%)!important;
    -webkit-backdrop-filter:blur(18px) saturate(140%)!important;
    scrollbar-width:none!important;
    touch-action:pan-x!important;
  }
  #serverBar::-webkit-scrollbar{display:none!important;}

  #srvIcons{
    display:flex!important;
    flex-direction:row!important;
    align-items:center!important;
    gap:10px!important;
    flex:0 0 auto!important;
  }
  #serverBar .srv-sep{
    width:1px!important;
    height:34px!important;
    margin:0 2px!important;
    flex:0 0 1px!important;
  }
  #serverBar .srv-icon,
  #serverBar .srv-icon-inner{
    width:46px!important;
    height:46px!important;
    min-width:46px!important;
    min-height:46px!important;
    flex:0 0 46px!important;
    border-radius:16px!important;
  }
  #serverBar .srv-admin-btn,
  #serverBar .srv-superadmin-btn{
    width:44px!important;
    height:44px!important;
    min-width:44px!important;
    flex:0 0 44px!important;
    border-radius:15px!important;
  }
  #serverBar .srv-indicator{display:none!important;}

  #mainArea{
    position:fixed!important;
    left:0!important;
    right:0!important;
    top:0!important;
    bottom:var(--mobile-serverbar-h)!important;
    width:100vw!important;
    height:auto!important;
    min-width:0!important;
    min-height:0!important;
    max-width:100vw!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    z-index:1!important;
  }

  #chSidebar{
    position:fixed!important;
    left:0!important;
    right:auto!important;
    top:0!important;
    bottom:var(--mobile-serverbar-h)!important;
    width:var(--mobile-ch-w)!important;
    min-width:0!important;
    max-width:var(--mobile-ch-w)!important;
    height:auto!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    transform:translate3d(-105%,0,0)!important;
    transition:transform .24s cubic-bezier(.4,0,.2,1)!important;
    z-index:140!important;
    box-shadow:18px 0 42px rgba(0,0,0,.48)!important;
    border-right:1px solid rgba(255,255,255,.08)!important;
  }
  #chSidebar.open{transform:translate3d(0,0,0)!important;}

  #memberSidebar{
    position:fixed!important;
    right:0!important;
    left:auto!important;
    top:0!important;
    bottom:var(--mobile-serverbar-h)!important;
    width:var(--mobile-mb-w)!important;
    min-width:0!important;
    max-width:var(--mobile-mb-w)!important;
    height:auto!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    transform:translate3d(105%,0,0)!important;
    transition:transform .24s cubic-bezier(.4,0,.2,1)!important;
    z-index:141!important;
    box-shadow:-18px 0 42px rgba(0,0,0,.48)!important;
    border-left:1px solid rgba(255,255,255,.08)!important;
  }
  #memberSidebar.open{transform:translate3d(0,0,0)!important;}
  #memberSidebar.collapsed{transform:translate3d(105%,0,0)!important;}

  #mobileOverlay{
    position:fixed!important;
    left:0!important;
    right:0!important;
    top:0!important;
    bottom:var(--mobile-serverbar-h)!important;
    z-index:130!important;
    background:rgba(0,0,0,.58)!important;
    backdrop-filter:blur(3px)!important;
    -webkit-backdrop-filter:blur(3px)!important;
  }

  #mainHeader{
    min-height:var(--hdr-h)!important;
    flex:0 0 var(--hdr-h)!important;
    max-width:100vw!important;
  }

  #chView,
  #dmView{
    flex:1 1 auto!important;
    min-height:0!important;
    min-width:0!important;
    max-width:100vw!important;
    overflow:hidden!important;
  }

  #messagesWrap,
  #dmMessagesWrap{
    flex:1 1 auto!important;
    min-height:0!important;
    overflow-y:auto!important;
    -webkit-overflow-scrolling:touch!important;
  }

  #inputOuter,
  #dmInputOuter{
    position:relative!important;
    bottom:auto!important;
    flex:0 0 auto!important;
    z-index:5!important;
  }

  .mobile-only{display:flex!important;}
  .desktop-only{display:none!important;}

  #modalBg{
    z-index:500!important;
  }

  #ctxMenu,
  #emojiPicker,
  #notifPanel{
    z-index:520!important;
  }
}

/* Smaller phones */
@media(max-width:740px){
  :root{
    --mobile-ch-w:min(90vw,340px);
    --mobile-mb-w:min(88vw,330px);
  }
}


/* trueCOLOR-only mobile layout guard: keep decorations from overriding panel geometry */
@media(max-width:980px){
  [data-theme="truecolor"] #app{
    position:fixed!important;
    inset:0!important;
    width:100vw!important;
    height:var(--app-h,100vh)!important;
    overflow:hidden!important;
  }

  [data-theme="truecolor"] #serverBar{
    position:fixed!important;
    left:0!important;
    right:0!important;
    bottom:0!important;
    top:auto!important;
    width:100vw!important;
    min-width:0!important;
    max-width:100vw!important;
    height:calc(74px + var(--safe-bottom,0px))!important;
    min-height:calc(74px + var(--safe-bottom,0px))!important;
    display:flex!important;
    flex-direction:row!important;
    align-items:center!important;
    justify-content:flex-start!important;
    overflow-x:auto!important;
    overflow-y:hidden!important;
    padding:8px 10px calc(8px + var(--safe-bottom,0px))!important;
    z-index:160!important;
    border-right:0!important;
    border-top:1px solid rgba(255,255,255,.08)!important;
    box-shadow:0 -10px 30px rgba(0,0,0,.35)!important;
  }

  [data-theme="truecolor"] #srvIcons{
    display:flex!important;
    flex-direction:row!important;
    align-items:center!important;
    gap:10px!important;
    flex:0 0 auto!important;
  }

  [data-theme="truecolor"] #serverBar .srv-icon,
  [data-theme="truecolor"] #serverBar .srv-icon-inner{
    width:46px!important;
    height:46px!important;
    min-width:46px!important;
    min-height:46px!important;
    flex:0 0 46px!important;
    border-radius:16px!important;
  }

  [data-theme="truecolor"] #mainArea{
    position:fixed!important;
    left:0!important;
    right:0!important;
    top:0!important;
    bottom:calc(74px + var(--safe-bottom,0px))!important;
    width:100vw!important;
    height:auto!important;
    min-width:0!important;
    max-width:100vw!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    z-index:1!important;
  }

  [data-theme="truecolor"] #chSidebar{
    position:fixed!important;
    left:0!important;
    right:auto!important;
    top:0!important;
    bottom:calc(74px + var(--safe-bottom,0px))!important;
    width:min(88vw,360px)!important;
    min-width:0!important;
    max-width:min(88vw,360px)!important;
    height:auto!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    transform:translate3d(-105%,0,0)!important;
    transition:transform .24s cubic-bezier(.4,0,.2,1)!important;
    z-index:140!important;
    box-shadow:18px 0 42px rgba(0,0,0,.48)!important;
  }
  [data-theme="truecolor"] #chSidebar.open{
    transform:translate3d(0,0,0)!important;
  }

  [data-theme="truecolor"] #memberSidebar{
    position:fixed!important;
    right:0!important;
    left:auto!important;
    top:0!important;
    bottom:calc(74px + var(--safe-bottom,0px))!important;
    width:min(86vw,340px)!important;
    min-width:0!important;
    max-width:min(86vw,340px)!important;
    height:auto!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    transform:translate3d(105%,0,0)!important;
    transition:transform .24s cubic-bezier(.4,0,.2,1)!important;
    z-index:141!important;
    box-shadow:-18px 0 42px rgba(0,0,0,.48)!important;
  }
  [data-theme="truecolor"] #memberSidebar.open{
    transform:translate3d(0,0,0)!important;
  }
  [data-theme="truecolor"] #memberSidebar.collapsed{
    transform:translate3d(105%,0,0)!important;
  }

  [data-theme="truecolor"] #mobileOverlay{
    position:fixed!important;
    left:0!important;
    right:0!important;
    top:0!important;
    bottom:calc(74px + var(--safe-bottom,0px))!important;
    z-index:130!important;
  }

  [data-theme="truecolor"] #chView,
  [data-theme="truecolor"] #dmView{
    min-width:0!important;
    max-width:100vw!important;
    overflow:hidden!important;
  }

  [data-theme="truecolor"] #messagesWrap,
  [data-theme="truecolor"] #dmMessagesWrap{
    flex:1 1 auto!important;
    min-height:0!important;
    overflow-y:auto!important;
    -webkit-overflow-scrolling:touch!important;
  }

  [data-theme="truecolor"] #inputOuter,
  [data-theme="truecolor"] #dmInputOuter{
    position:relative!important;
    bottom:auto!important;
    flex:0 0 auto!important;
    overflow:visible!important;
  }
}


/* Compact mobile layout for create channel modal */
.create-channel-modal{
  display:flex;
  flex-direction:column;
  min-width:0;
}
.create-channel-modal h2{
  margin-right:46px!important;
}
.create-channel-sub{
  color:var(--text3);
  font-size:12px;
  margin:-4px 0 12px;
  line-height:1.45;
}
@media(max-width:740px){
  #modalBg .modal:has(.create-channel-modal){
    width:calc(100vw - 14px)!important;
    max-width:calc(100vw - 14px)!important;
    max-height:calc(var(--app-h,100vh) - 18px)!important;
    padding:14px!important;
    border-radius:20px!important;
    align-self:center!important;
  }
  #modalBg .modal:has(.create-channel-modal) #modalBody{
    max-height:calc(var(--app-h,100vh) - 46px)!important;
    overflow-y:auto!important;
    padding:0!important;
  }
  .create-channel-modal h2{
    font-size:20px!important;
    line-height:1.15!important;
    margin:0 48px 6px 0!important;
    padding:0!important;
  }
  .create-channel-sub{
    font-size:12px!important;
    line-height:1.35!important;
    margin:0 0 10px!important;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
  }
  .create-channel-modal .fg{
    margin-bottom:8px!important;
  }
  .create-channel-modal .fl{
    font-size:9px!important;
    margin-bottom:4px!important;
    letter-spacing:.075em!important;
  }
  .create-channel-modal .fi{
    min-height:42px!important;
    padding:8px 12px!important;
    font-size:16px!important;
    border-radius:14px!important;
  }
  .create-channel-modal textarea.fi{
    min-height:72px!important;
    max-height:86px!important;
    resize:none!important;
  }
  .create-channel-modal select.fi{
    min-height:42px!important;
  }
  .create-channel-submit{
    min-height:44px!important;
    padding:10px 14px!important;
    margin-top:2px!important;
    position:sticky!important;
    bottom:0!important;
    z-index:3!important;
    box-shadow:0 -10px 20px rgba(8,12,20,.22),0 10px 24px rgba(45,125,255,.22)!important;
  }
}
@media(max-width:390px),(max-height:760px){
  .create-channel-modal h2{
    font-size:18px!important;
    margin-bottom:4px!important;
  }
  .create-channel-sub{
    font-size:11px!important;
    -webkit-line-clamp:1;
    margin-bottom:8px!important;
  }
  .create-channel-modal .fg{
    margin-bottom:6px!important;
  }
  .create-channel-modal .fi{
    min-height:38px!important;
    padding:7px 10px!important;
    font-size:15px!important;
  }
  .create-channel-modal textarea.fi{
    min-height:58px!important;
    max-height:68px!important;
  }
  .create-channel-submit{
    min-height:40px!important;
    padding:8px 12px!important;
  }
}

/* ════════════════════════════════════════════════════════════════
   DESIGN POLISH PATCH — trueCORD
   Области: радиусы, кнопки, инпуты, боковые панели, сообщения,
            тосты, модалки, контекстное меню, юзер-панель, скроллбар
   ════════════════════════════════════════════════════════════════ */

/* ── 1. ДИЗАЙН-ТОКЕНЫ: унифицированные радиусы и переходы ───── */
:root {
  --radius:8px;
  --radius-sm:6px;
  --radius-lg:14px;
  --radius-xl:20px;
  --transition-fast:.12s ease;
  --transition-base:.18s ease;
  --transition-slow:.28s ease;
}

/* ── 2. КНОПКИ — чуть больше округлость, чёткий focus-ring ──── */
.btn {
  border-radius:var(--radius-lg) !important;
  padding:10px 20px !important;
  letter-spacing:.01em;
  transition:background var(--transition-fast), transform var(--transition-fast),
             box-shadow var(--transition-fast) !important;
}
.btn:active { transform:scale(.97) !important; }
.btn:focus-visible {
  outline:2px solid var(--gold);
  outline-offset:2px;
}
.btn-gold {
  box-shadow:0 4px 14px var(--gold-glow) !important;
}
.btn-gold:hover {
  box-shadow:0 6px 20px var(--gold-glow) !important;
  transform:translateY(-1px) !important;
}
.btn-ghost {
  border:1px solid var(--border2) !important;
}

/* ── 3. ИНПУТЫ (.fi) — чуть более «живые», улучшенный placeholder ── */
.fi {
  border-radius:var(--radius-lg) !important;
  padding:10px 14px !important;
  font-size:14px !important;
  border:1px solid var(--border2) !important;
  transition:border-color var(--transition-base),
             box-shadow var(--transition-base) !important;
}
.fi:focus {
  border-color:var(--gold) !important;
  box-shadow:0 0 0 3px var(--gold-dim) !important;
}
textarea.fi { line-height:1.55 !important; }

/* ── 4. FORM LABEL (.fl) — чуть мягче, Inter вместо Cinzel ──── */
.fl {
  font-size:11px !important;
  font-family:var(--font-body) !important;
  letter-spacing:.06em !important;
  color:var(--text3) !important;
  font-weight:600 !important;
  margin-bottom:6px !important;
}

/* ── 5. INPUT BOX (поле ввода сообщений) ─────────────────────── */
.input-box {
  border-radius:var(--radius-lg) !important;
  padding:8px 10px !important;
  border:1px solid var(--border2) !important;
  background:var(--bg3) !important;
  transition:border-color var(--transition-base),
             box-shadow var(--transition-base) !important;
}
.input-box:focus-within {
  border-color:var(--gold) !important;
  box-shadow:0 0 0 3px var(--gold-dim) !important;
}
.inp-btn {
  width:32px !important;
  height:32px !important;
  border-radius:var(--radius) !important;
  transition:color var(--transition-fast),
             background var(--transition-fast),
             transform var(--transition-fast) !important;
}
.inp-btn:hover {
  transform:scale(1.08) !important;
}
.inp-btn:active { transform:scale(.93) !important; }

/* ── 6. СЕРВЕРНАЯ БОКОВАЯ ПАНЕЛЬ ────────────────────────────── */
.srv-icon {
  transition:border-radius var(--transition-base),
             transform var(--transition-fast) !important;
}
.srv-icon:hover { transform:scale(1.05) !important; }
.srv-icon:active { transform:scale(.96) !important; }
.srv-icon-inner {
  transition:border-radius var(--transition-base),
             background var(--transition-base),
             filter var(--transition-slow),
             opacity var(--transition-slow) !important;
}
/* Разделитель серверов — чуть тоньше и прозрачнее */
.srv-sep {
  width:28px !important;
  background:var(--border) !important;
  margin:3px 0 !important;
}
/* Кнопки суперадмина/админа — убираем анимацию у стандартного */
.srv-admin-btn {
  border-radius:var(--radius) !important;
  transition:background var(--transition-base),
             border-color var(--transition-base) !important;
}

/* ── 7. КАНАЛЬНАЯ БОКОВАЯ ПАНЕЛЬ ─────────────────────────────── */
.ch-item {
  border-radius:var(--radius) !important;
  margin:1px 8px !important;
  padding:4px 8px 4px 10px !important;
  transition:background var(--transition-fast),
             color var(--transition-fast) !important;
}
.ch-item:hover { background:var(--bg3) !important; }
.ch-item.active {
  background:var(--bg4) !important;
}
/* Категория каналов */
.ch-category {
  padding:18px 10px 3px !important;
  letter-spacing:.07em !important;
}
/* "Создать канал" — кнопка в сайдбаре */
.channel-create-row {
  border-radius:var(--radius) !important;
  margin:4px 8px 10px !important;
}
/* Заголовок сервера */
#sidebarHeader {
  transition:background var(--transition-base) !important;
}

/* ── 8. СООБЩЕНИЯ ────────────────────────────────────────────── */
/* Hover-подсветка строки */
.msg-row {
  border-radius:var(--radius-sm) !important;
  transition:background var(--transition-fast) !important;
}
/* Аватар */
.msg-av {
  border-radius:50% !important;
  transition:opacity var(--transition-fast) !important;
  border:none !important;
}
.msg-av:hover { opacity:.85 !important; }
/* Текст — чуть лучше межстрочный интервал */
.msg-text { line-height:1.6 !important; }
/* Код */
.msg-text code {
  border-radius:var(--radius-sm) !important;
  padding:2px 6px !important;
  font-size:12.5px !important;
}
.msg-text pre {
  border-radius:var(--radius) !important;
  padding:12px 14px !important;
}
/* Изображения и видео */
.msg-img, .msg-video {
  border-radius:var(--radius-lg) !important;
  border:none !important;
  box-shadow:0 4px 16px rgba(0,0,0,.3) !important;
}
/* Аудио-карточка */
.msg-audio-wrap {
  border-radius:var(--radius-lg) !important;
  transition:background var(--transition-fast),
             border-color var(--transition-fast) !important;
}
/* Файл-карточка */
.file-card {
  border-radius:var(--radius-lg) !important;
  transition:background var(--transition-fast) !important;
}
/* Редактирование сообщения */
.msg-edit-textarea {
  border-radius:var(--radius-lg) !important;
}
.msg-edit-save {
  border-radius:var(--radius) !important;
}
.msg-edit-cancel {
  border-radius:var(--radius) !important;
}
/* Реакции */
.r-pill {
  border-radius:99px !important;
  transition:background var(--transition-fast),
             transform var(--transition-fast) !important;
}
.r-pill:hover { transform:scale(1.06) !important; }

/* ── 9. ТОСТЫ — чуть крупнее, мягче ────────────────────────── */
.toast {
  border-radius:var(--radius-lg) !important;
  padding:10px 16px !important;
  font-size:13.5px !important;
  box-shadow:0 8px 28px rgba(0,0,0,.35),
             0 2px 6px rgba(0,0,0,.18) !important;
  backdrop-filter:blur(16px) !important;
  -webkit-backdrop-filter:blur(16px) !important;
  max-width:300px !important;
}
.toast.ok  { border-left-width:3px !important; }
.toast.err { border-left-width:3px !important; }

/* ── 10. МОДАЛЬНЫЕ ОКНА ──────────────────────────────────────── */
.modal {
  border-radius:var(--radius-xl) !important;
}
.modal h2 {
  font-size:18px !important;
  margin-bottom:6px !important;
  line-height:1.25 !important;
}
.modal .sub {
  line-height:1.55 !important;
  margin-bottom:20px !important;
}
.modal-close {
  border-radius:50% !important;
  width:30px !important;
  height:30px !important;
  transition:background var(--transition-fast),
             color var(--transition-fast) !important;
}
.modal-close:hover { transform:scale(1.08) !important; }
/* Фон модала — чуть более контрастный overlay */
#modalBg {
  background:rgba(0,0,0,.6) !important;
}

/* ── 11. КОНТЕКСТНОЕ МЕНЮ ────────────────────────────────────── */
#ctxMenu {
  border-radius:var(--radius-lg) !important;
  padding:5px !important;
  box-shadow:0 8px 32px rgba(0,0,0,.42),
             0 2px 8px rgba(0,0,0,.22) !important;
  backdrop-filter:blur(20px) !important;
  -webkit-backdrop-filter:blur(20px) !important;
}
.ctx-item {
  border-radius:var(--radius) !important;
  padding:8px 12px !important;
  font-size:13.5px !important;
  transition:background var(--transition-fast),
             color var(--transition-fast) !important;
}
.ctx-sep {
  margin:5px 8px !important;
  background:var(--border) !important;
}

/* ── 12. ЮЗЕР-ПАНЕЛЬ (нижний левый угол) ─────────────────────── */
#userPanel {
  border-top:1px solid var(--border) !important;
  padding:8px 10px !important;
}
.up-avatar-inner {
  border-radius:50% !important;
  transition:opacity var(--transition-fast) !important;
}
.up-avatar:hover .up-avatar-inner { opacity:.85 !important; }
.up-name { letter-spacing:.01em !important; }
.up-tag { font-size:11.5px !important; }

/* ── 13. MEMBER SIDEBAR ─────────────────────────────────────── */
.mb-item {
  border-radius:var(--radius) !important;
  margin:0 6px !important;
  padding:5px 8px !important;
  transition:background var(--transition-fast) !important;
}
.mb-item:hover { background:var(--bg3) !important; }

/* ── 14. VOICE BAR ───────────────────────────────────────────── */
.vb-btn {
  border-radius:var(--radius-lg) !important;
  transition:background var(--transition-fast),
             border-color var(--transition-fast),
             transform var(--transition-fast) !important;
}
.vb-btn:hover { transform:scale(1.06) !important; }
.vb-btn:active { transform:scale(.94) !important; }

/* ── 15. SCROLLBAR — чуть шире для удобства ─────────────────── */
::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-thumb {
  border-radius:99px;
  background:var(--bg5);
}

/* ── 16. REPLY BAR ───────────────────────────────────────────── */
#replyBar {
  border-radius:var(--radius-lg) var(--radius-lg) 0 0 !important;
  padding:8px 14px !important;
}

/* ── 17. EMOJI PICKER, FORMAT TOOLBAR ───────────────────────── */
#emojiPicker {
  border-radius:var(--radius-lg) !important;
  box-shadow:0 10px 36px rgba(0,0,0,.38) !important;
}
#formatToolbar {
  border-radius:var(--radius-lg) !important;
  box-shadow:0 6px 24px rgba(0,0,0,.32) !important;
}

/* ── 18. LOGIN PAGE ──────────────────────────────────────────── */
.login-wrap {
  border-radius:var(--radius-xl) !important;
  border:1px solid var(--border2) !important;
  overflow:hidden !important;
  box-shadow:0 24px 64px rgba(0,0,0,.55),
             0 2px 8px rgba(0,0,0,.22) !important;
}
.login-tab {
  transition:color var(--transition-base),
             border-color var(--transition-base) !important;
}
.login-tab.active {
  border-bottom-width:2px !important;
}

/* ── 19. WELCOME SCREEN ─────────────────────────────────────── */
#welcomeScreen {
  background:var(--bg1) !important;
}
#welcomeScreen h2 {
  font-size:20px !important;
  margin-bottom:10px !important;
}

/* ── 20. HEADER ─────────────────────────────────────────────── */
#mainHeader {
  border-bottom:1px solid var(--border) !important;
  padding:0 14px !important;
}
.hdr-ch-name {
  font-size:14px !important;
  letter-spacing:.01em !important;
}
.hdr-topic {
  font-size:12.5px !important;
  padding-left:12px !important;
}
.icon-btn {
  border-radius:var(--radius) !important;
  transition:background var(--transition-fast),
             color var(--transition-fast),
             transform var(--transition-fast) !important;
}
.icon-btn:hover { transform:scale(1.08) !important; }
.icon-btn:active { transform:scale(.93) !important; }

/* ── 21. PENDING FILE CHIPS ─────────────────────────────────── */
.pending-file-chip {
  border-radius:var(--radius) !important;
  padding:4px 10px !important;
}

/* ── 22. ДЕНЬ-РАЗДЕЛИТЕЛЬ В ЧАТЕ ────────────────────────────── */
.msg-day-div {
  font-size:11px !important;
  letter-spacing:.04em !important;
  text-transform:uppercase !important;
}

/* ── 23. TYPING INDICATOR ────────────────────────────────────── */
.typing-dot {
  width:6px !important;
  height:6px !important;
}

/* ── 24. truecolor-тема: уточнения ──────────────────────────── */
[data-theme="truecolor"] .btn-gold {
  border-radius:var(--radius-lg) !important;
}
[data-theme="truecolor"] .fi {
  border-radius:var(--radius-lg) !important;
}
[data-theme="truecolor"] .ch-item.active {
  border-left:2px solid var(--gold) !important;
  padding-left:8px !important;
}
[data-theme="truecolor"] .msg-row:hover {
  background:rgba(45,125,255,.04) !important;
}
[data-theme="truecolor"] .msg-row.mentioned {
  border-left:3px solid var(--gold) !important;
  background:rgba(45,125,255,.07) !important;
}
[data-theme="truecolor"] .msg-row.mentioned:hover {
  background:rgba(45,125,255,.10) !important;
}

/* ── 25. discord-тема: уточнения ────────────────────────────── */
[data-theme="discord"] .btn {
  border-radius:var(--radius-sm) !important;
}
[data-theme="discord"] .fi {
  border-radius:var(--radius-sm) !important;
}
[data-theme="discord"] .modal {
  border-radius:var(--radius-lg) !important;
}
[data-theme="discord"] .ch-item {
  border-radius:var(--radius-sm) !important;
}

/* ── 26. Сглаживаем жёсткие переходы состояний ─────────────── */
.ch-item,.mb-item,.voice-room,.ctx-item,.srv-icon,.btn,.fi,
.inp-btn,.vb-btn,.icon-btn,.dm-item {
  will-change:auto;
}

/* ── 27. DM items ────────────────────────────────────────────── */
.dm-item {
  border-radius:var(--radius) !important;
  margin:1px 8px !important;
  transition:background var(--transition-fast),
             color var(--transition-fast) !important;
}

/* ── 28. VOICE ROOM items ────────────────────────────────────── */
.voice-room {
  border-radius:var(--radius) !important;
  transition:background var(--transition-fast),
             color var(--transition-fast) !important;
}

/* ── 29. MENTION highlight в тексте ─────────────────────────── */
.msg-text .mention {
  border-radius:var(--radius-sm) !important;
  padding:1px 4px !important;
}

/* ── 30. IMAGE LIGHTBOX ──────────────────────────────────────── */
#lightbox img {
  border-radius:var(--radius-lg) !important;
  box-shadow:0 20px 60px rgba(0,0,0,.6) !important;
}


/* ============================================================
   PATCH: светлая VK-тема + надежная кликабельность выхода
   ============================================================ */
[data-theme="vk"] #modalBg{
  background:rgba(0,0,0,.28)!important;
}
[data-theme="vk"] #modalBg .modal{
  background:#f7f8fa!important;
  color:#000!important;
  border:1px solid #dce1e6!important;
  box-shadow:0 22px 70px rgba(0,0,0,.18)!important;
}
[data-theme="vk"] .settings-shell{background:#f7f8fa!important}
[data-theme="vk"] .settings-card,
[data-theme="vk"] .settings-accordion,
[data-theme="vk"] .settings-bottom-grid > *,
[data-theme="vk"] .server-settings-maincard,
[data-theme="vk"] .server-settings-sidecard,
[data-theme="vk"] .channel-settings-grid > *,
[data-theme="vk"] .app-editor-grid > *,
[data-theme="vk"] .app-catalog-item,
[data-theme="vk"] .big-modal-row{
  background:#fff!important;
  color:#000!important;
  border-color:#dce1e6!important;
  box-shadow:0 8px 26px rgba(0,0,0,.07)!important;
}
[data-theme="vk"] .settings-hero-copy,
[data-theme="vk"] .settings-profile-card,
[data-theme="vk"] .server-settings-maincard{
  background:linear-gradient(145deg,#f7fbff 0%,#ffffff 100%)!important;
  border-color:#cfd8e3!important;
  box-shadow:0 8px 26px rgba(0,0,0,.07)!important;
}
[data-theme="vk"] .settings-card-title,
[data-theme="vk"] .settings-profile-name,
[data-theme="vk"] .settings-hero h2,
[data-theme="vk"] .server-settings-headcopy h2,
[data-theme="vk"] .big-modal-row .title,
[data-theme="vk"] .app-catalog-name{color:#000!important}
[data-theme="vk"] .settings-card-sub,
[data-theme="vk"] .settings-profile-meta,
[data-theme="vk"] .settings-hero .sub,
[data-theme="vk"] .big-modal-row .desc,
[data-theme="vk"] .app-catalog-meta{color:#626d7a!important}
[data-theme="vk"] .settings-badge{
  background:#f0f2f5!important;
  border-color:#dce1e6!important;
  color:#2c2d2e!important;
}
[data-theme="vk"] .settings-kicker,
[data-theme="vk"] .server-settings-kicker{
  background:#e5f2ff!important;
  border-color:#bfddff!important;
  color:#0077ff!important;
}
[data-theme="vk"] .settings-theme-note,
[data-theme="vk"] .miniapi-callout{
  background:#f5f7fa!important;
  border-color:#dce1e6!important;
  color:#626d7a!important;
}
[data-theme="vk"] .terms-check span,
[data-theme="vk"] .miniapi-callout-copy{color:#626d7a!important}
[data-theme="vk"] .miniapi-callout-title,
[data-theme="vk"] .terms-check strong{color:#0077ff!important}
[data-theme="vk"] .settings-card::after,
[data-theme="vk"] .settings-profile-card::after,
[data-theme="vk"] .settings-hero-copy::after{display:none!important}
#userPanel, #userPanel .up-btns, #userPanel .icon-btn{
  isolation:isolate;
}
#userPanel .icon-btn[onclick*="Logout"],
#userPanel .icon-btn[onclick*="logout"],
.settings-bottom-grid .btn-red[onclick*="Logout"],
.settings-bottom-grid .btn-red[onclick*="logout"]{
  position:relative!important;
  z-index:2147483000!important;
  pointer-events:auto!important;
  touch-action:manipulation;
}
#modalBg.open{z-index:30000!important}
#modalBg.open .modal{position:relative!important;z-index:30001!important;pointer-events:auto!important}
#modalBg.open .modal-close{z-index:30003!important;pointer-events:auto!important}
#modalBg.open #modalBody{position:relative!important;z-index:30002!important}


/* ============================================================
   PATCH 2: modal polish, i18n-safe windows, VK light contrast
   ============================================================ */
#modalBg .modal{
  max-height:min(92vh,860px)!important;
  display:flex!important;
  flex-direction:column!important;
}
#modalBg #modalBody{
  overflow:auto!important;
  min-height:0!important;
  scrollbar-gutter:stable!important;
}
#modalBg .modal-close{
  background:rgba(255,255,255,.08)!important;
  border:1px solid rgba(255,255,255,.10)!important;
  backdrop-filter:blur(10px);
}
.modal-sub{
  color:var(--text3);
  font-size:14px;
  line-height:1.45;
  margin:-4px 0 14px;
}
.new-dm-modal{max-width:640px;width:min(640px,calc(100vw - 28px));}
.new-dm-list{
  max-height:min(52vh,520px)!important;
  overflow:auto!important;
  border:1px solid var(--border2)!important;
  border-radius:14px!important;
  padding:8px!important;
  background:rgba(0,0,0,.10)!important;
}
.new-dm-list .dm-item{
  border-radius:13px!important;
  padding:10px 12px!important;
  margin:0!important;
  min-height:52px!important;
}
.new-dm-list .dm-item:hover{background:rgba(45,125,255,.12)!important;}
.admin-panel-modal{width:min(650px,calc(100vw - 28px));}
.admin-panel-modal h2{margin-bottom:12px!important;}
.admin-section-title{
  font-size:11px;
  font-weight:800;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:var(--text3);
  margin:14px 0 8px;
  font-family:var(--font-heading);
}
.admin-users-list,.admin-bans-list{
  overflow:auto;
  border:1px solid var(--border2);
  border-radius:16px;
  background:rgba(0,0,0,.10);
  padding:8px;
}
.admin-users-list{max-height:min(48vh,420px);}
.admin-bans-list{max-height:min(28vh,220px);}
.admin-users-list > div{
  display:grid!important;
  grid-template-columns:minmax(120px,1fr) auto!important;
  gap:10px!important;
  align-items:center!important;
  padding:10px 0!important;
}
.admin-users-list > div > div:last-child{
  display:flex!important;
  flex-wrap:wrap!important;
  gap:8px!important;
  justify-content:flex-end!important;
}
.admin-users-list .btn{
  min-width:58px!important;
  height:40px!important;
  padding:0 10px!important;
  border-radius:16px!important;
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
}

/* ── Админ-панель: аккордеон (список имён, клик → компактные кнопки) ── */
.admin-search-wrap{margin:0 0 10px}
.admin-search-input{width:100%;height:40px;border-radius:12px;background:var(--bg2);border:1px solid var(--border);color:var(--text);padding:0 14px;font-size:14px;box-sizing:border-box;outline:none}
.admin-search-input:focus{border-color:var(--blue)}
.admin-search-input::placeholder{color:var(--text4)}
.admin-rows{display:flex;flex-direction:column;gap:6px;max-height:min(52vh,440px);overflow-y:auto;padding-right:2px}
.admin-row{border-radius:12px;background:var(--bg2);border:1px solid var(--border);transition:border-color .12s}
.admin-row.open{overflow:visible}
.admin-row-actions{display:flex;flex-wrap:wrap;gap:8px;padding:10px 12px 12px;border-top:1px solid var(--border);border-radius:0 0 12px 12px}
.admin-row-actions[hidden]{display:none!important}
.admin-row.open{border-color:var(--border2)}
.admin-row.is-banned{border-color:color-mix(in srgb,#ed4245 28%,var(--border));background:color-mix(in srgb,#ed4245 6%,var(--bg2))}
.admin-row-head{display:flex;align-items:center;gap:11px;width:100%;min-height:56px;padding:9px 12px;background:none;border:none;cursor:pointer;text-align:left;color:inherit;font-family:inherit;font-size:14px;line-height:1.3;box-sizing:border-box}
.admin-row-head:hover{background:var(--bg3)}
.admin-row-av{width:38px;height:38px;min-width:38px;min-height:38px;flex:0 0 38px;border-radius:50%;position:relative;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;align-self:center}
.admin-row-av img{width:38px;height:38px;object-fit:cover;border-radius:50%;display:block}
.admin-row-av .av-fallback{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px}
.admin-row-copy{min-width:0;flex:1;display:flex;flex-direction:column;gap:2px}
.admin-row-name{font-size:14px;font-weight:800;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:flex;align-items:center;gap:5px;line-height:1.25}
.admin-vrf{color:var(--green);font-size:12px}
.admin-row-sub{font-size:12px;color:var(--text3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:flex;align-items:center;gap:5px;line-height:1.2}
.admin-row-caret{color:var(--text3);display:flex;align-items:center;transition:transform .18s ease;flex-shrink:0}
.admin-row.open .admin-row-caret{transform:rotate(180deg)}
.admin-mini-btn{width:40px;height:40px;flex:0 0 40px;border-radius:11px;border:1px solid var(--border);background:var(--bg3);color:var(--text2);font-size:13px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:background .12s,color .12s,border-color .12s}
.admin-mini-btn:hover{background:var(--bg4);color:var(--text)}
.admin-mini-btn.ok{color:var(--green);border-color:color-mix(in srgb,var(--green) 32%,transparent);background:color-mix(in srgb,var(--green) 12%,var(--bg3))}
.admin-mini-btn.gold{color:var(--gold);border-color:color-mix(in srgb,var(--gold) 32%,transparent)}
.admin-mini-btn.red{color:#e0484b;border-color:color-mix(in srgb,#ed4245 32%,transparent);background:color-mix(in srgb,#ed4245 10%,var(--bg3))}
.admin-mini-btn.red:hover{background:color-mix(in srgb,#ed4245 18%,var(--bg3))}
.admin-mini-btn.unban{color:var(--green);border-color:color-mix(in srgb,var(--green) 36%,transparent);background:color-mix(in srgb,var(--green) 14%,var(--bg3))}
.admin-mini-btn.unban:hover{background:color-mix(in srgb,var(--green) 22%,var(--bg3))}
.admin-panel-subhead{display:flex;align-items:baseline;justify-content:space-between;gap:10px;margin-bottom:8px;flex-wrap:wrap}
.admin-ban-counter{font-size:12px;font-weight:700;color:var(--text3)}
.admin-inline-badge{font-size:10px;font-weight:800;padding:2px 7px;border-radius:99px;display:inline-flex;align-items:center;gap:3px}
.admin-inline-badge-gold{background:color-mix(in srgb,var(--gold) 18%,transparent);color:var(--gold)}
.admin-inline-badge-orange{background:rgba(230,126,34,.18);color:#e67e22}
.admin-inline-badge-red{background:color-mix(in srgb,#ed4245 16%,transparent);color:#e0484b}
.admin-status-dot{position:absolute;right:-1px;bottom:-1px;width:11px;height:11px;border-radius:50%;border:2px solid var(--bg2)}
.admin-unread-badge{position:absolute;top:-4px;right:-4px;min-width:16px;height:16px;padding:0 4px;border-radius:99px;background:#ed4245;color:#fff;font-size:9px;font-weight:800;display:flex;align-items:center;justify-content:center}
.app-editor-shell .settings-profile-badges{display:flex;justify-content:center;gap:8px;flex-wrap:wrap;}
.app-editor-shell .settings-card-title,.app-editor-shell .settings-profile-name{overflow-wrap:anywhere;}
.app-code-textarea{min-height:220px!important;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace!important;}
[data-theme="vk"] #modalBg .modal-close{
  background:#eef1f5!important;
  border-color:#d5dce5!important;
  color:#2c2d2e!important;
}
[data-theme="vk"] #modalBg .modal-close:hover{background:#e5ebf2!important;}
[data-theme="vk"] .modal-sub,
[data-theme="vk"] .create-channel-sub{color:#626d7a!important;}
[data-theme="vk"] .new-dm-list,
[data-theme="vk"] .admin-users-list,
[data-theme="vk"] .admin-bans-list{
  background:#f5f7fa!important;
  border-color:#dce1e6!important;
}
[data-theme="vk"] .new-dm-list .dm-item{background:#fff!important;color:#2c2d2e!important;border:1px solid #e7ecf1!important;}
[data-theme="vk"] .new-dm-list .dm-item:hover{background:#eef5ff!important;border-color:#c9def8!important;}
[data-theme="vk"] .new-dm-list .dm-name,
[data-theme="vk"] .admin-panel-modal h2{color:#111!important;}
[data-theme="vk"] .new-dm-list .dm-last,
[data-theme="vk"] .admin-section-title{color:#626d7a!important;}
[data-theme="vk"] .settings-card[style*="opacity"],
[data-theme="vk"] .settings-accordion[style*="opacity"]{
  opacity:1!important;
  background:#f8fafc!important;
  border:1px dashed #cfd8e3!important;
}
[data-theme="vk"] .settings-card[style*="opacity"] *,
[data-theme="vk"] .settings-accordion[style*="opacity"] *{color:#5f6b7a!important;}
[data-theme="vk"] .fi,
[data-theme="vk"] input.fi,
[data-theme="vk"] select.fi,
[data-theme="vk"] textarea.fi{
  background:#fff!important;
  color:#111!important;
  border-color:#d5dce5!important;
}
[data-theme="vk"] .fi::placeholder{color:#818c99!important;}
[data-theme="vk"] .btn-ghost,
[data-theme="vk"] .settings-soft-btn,
[data-theme="vk"] .upload-chip{
  background:#f0f2f5!important;
  border-color:#d5dce5!important;
  color:#111!important;
}
[data-theme="vk"] .btn-ghost:hover,
[data-theme="vk"] .settings-soft-btn:hover,
[data-theme="vk"] .upload-chip:hover{background:#e8edf3!important;}
@media (max-width:720px){
  .admin-users-list > div{grid-template-columns:1fr!important;}
  .admin-users-list > div > div:last-child{justify-content:flex-start!important;}
  .app-editor-shell .settings-hero{grid-template-columns:1fr!important;}
}


/* PATCH 3: stronger VK-light sidebar and modal contrast */
[data-theme="vk"] #chSidebar{background:#f6f8fb!important;border-right:1px solid #d6dee8!important;}
[data-theme="vk"] #chList{background:#f6f8fb!important;}
[data-theme="vk"] .ch-category{color:#6f7d8f!important;font-weight:800!important;}
[data-theme="vk"] .channel-create-row{background:#eef3fb!important;border:1px dashed #b9cdf1!important;color:#536476!important;}
[data-theme="vk"] .channel-create-row:hover{background:#e5f0ff!important;color:#1f2328!important;}
[data-theme="vk"] .ch-item{background:transparent!important;color:#536476!important;}
[data-theme="vk"] .ch-item.active{background:#e2ebf5!important;color:#111!important;box-shadow:inset 3px 0 0 #0077ff!important;}
[data-theme="vk"] .ch-item .ch-icon{color:#1f2328!important;}
[data-theme="vk"] #userPanel{background:#fff!important;border-top:1px solid #d6dee8!important;}
[data-theme="vk"] #userPanel .up-status{color:#3f8f3f!important;}
[data-theme="vk"] #mainHeader{box-shadow:0 1px 0 #d6dee8!important;}
[data-theme="vk"] #inputBar,[data-theme="vk"] #msgInput,[data-theme="vk"] #dmInput{background:transparent!important;color:#111!important;}
/* VK: внешний бар = панель (#fff), а поле ввода — мягкая серая заливка как в VK-инпутах,
   чтобы строка ввода читалась единым элементом и не «выпадала» белым по серому. */
[data-theme="vk"] #inputOuter,[data-theme="vk"] #dmInputOuter{background:#ffffff!important;}
[data-theme="vk"] .input-box{background:#f0f2f5!important;border-color:#dce1e6!important;}
[data-theme="vk"] .input-box:focus-within{border-color:#a3b3c8!important;background:#fff!important;}
[data-theme="vk"] .msg-textarea{background:transparent!important;color:#111!important;}
[data-theme="vk"] .msg-textarea::placeholder{color:#8b95a1!important;}
[data-theme="vk"] .modal .av-preview{background:#f3f6fa!important;border-color:#cbd5e1!important;color:#111!important;}


/* PATCH 4: full i18n polish, compact profile/admin/notif modals */
.profile-modal-v2{width:min(560px,calc(100vw - 28px));}
.profile-modal-v2 .profile-head{text-align:center;margin-bottom:14px;}
.profile-modal-v2 .profile-actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-top:10px;}
.profile-modal-v2 .profile-actions .btn-full{margin-top:0!important;}
.profile-modal-v2 .profile-mod-actions{display:grid!important;grid-template-columns:repeat(auto-fit,minmax(145px,1fr));gap:8px!important;}
.profile-modal-v2 .profile-mod-actions .btn{min-height:42px!important;}
.notif-modal{width:min(560px,calc(100vw - 28px));}
.notif-modal h2{margin-bottom:14px!important;}
.notif-option{width:100%;display:grid;grid-template-columns:34px 1fr 26px;gap:12px;align-items:center;border:1px solid transparent;background:transparent;color:var(--text);border-radius:14px;padding:14px 16px;margin:6px 0;text-align:left;cursor:pointer;}
.notif-option:hover,.notif-option.active{background:var(--bg3);border-color:var(--border2);}
.notif-option-icon{font-size:22px;display:flex;align-items:center;justify-content:center;}
.notif-option-copy b{display:block;font-size:15px;color:var(--text);}
.notif-option-copy small{display:block;margin-top:3px;color:var(--text3);font-size:13px;}
.notif-check{width:22px;height:22px;border-radius:50%;border:2px solid var(--border2);display:flex;align-items:center;justify-content:center;color:var(--gold);font-weight:800;}
[data-theme="vk"] .notif-option{background:#fff!important;border-color:#e0e6ee!important;color:#111!important;}
[data-theme="vk"] .notif-option:hover,[data-theme="vk"] .notif-option.active{background:#edf5ff!important;border-color:#c8dcf5!important;}
[data-theme="vk"] .notif-option-copy b{color:#111!important;}
[data-theme="vk"] .notif-option-copy small{color:#667385!important;}
[data-theme="vk"] .profile-modal-v2 .role-box{background:#f7f9fc!important;border-color:#d9e1eb!important;}
@media (max-width:560px){.profile-modal-v2 .profile-actions{grid-template-columns:1fr}.profile-modal-v2 .profile-mod-actions{grid-template-columns:1fr!important}}



/* PATCH 5: streamlined management modals + theme cleanup */
.roles-manager-modal{width:min(980px,calc(100vw - 28px));}
.roles-manager-shell{display:grid;grid-template-columns:280px minmax(0,1fr);min-height:500px;max-height:72vh;margin:-16px -18px;overflow:hidden;}
.roles-manager-sidebar{display:flex;flex-direction:column;border-right:1px solid var(--border);background:var(--bg1);min-width:0;}
.roles-manager-sidebar-head{padding:16px 16px 10px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:8px;}
.roles-manager-title{font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--text3);}
.roles-manager-sub{min-width:26px;height:26px;padding:0 8px;border-radius:999px;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--text2);}
.roles-manager-list{padding:10px;overflow:auto;display:flex;flex-direction:column;gap:8px;}
.roles-manager-actions{padding:12px;border-top:1px solid var(--border);}
.rm-role-item{width:100%;border:none;background:transparent;text-align:left;padding:10px 12px;border-radius:14px;display:flex;align-items:center;gap:10px;font-size:13px;}
.rm-role-item.active{border-left:none;background:var(--bg4);box-shadow:inset 0 0 0 1px var(--border2);}
.rm-role-dot,.assign-role-dot,.rm-member-dot{display:inline-block;width:12px;height:12px;border-radius:50%;flex-shrink:0;}
.rm-role-dot.lg{width:16px;height:16px;}
.rm-role-name{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.roles-manager-detail{overflow:auto;padding:18px;}
.rm-empty-state{height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:var(--text3);padding:32px;}
.rm-empty-icon{font-size:34px;margin-bottom:12px;}
.rm-empty-title{font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;}
.rm-empty-sub{font-size:13px;line-height:1.45;}
.rm-list-empty{padding:16px;color:var(--text3);font-size:13px;text-align:center;}
.rm-detail-wrap{max-width:680px;}
.rm-detail-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;}
.rm-role-badge{display:inline-flex;align-items:center;gap:10px;padding:10px 14px;border-radius:14px;background:var(--bg3);font-weight:800;}
.rm-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;}
.rm-section-label{font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:10px;}
.rm-members-list{display:flex;flex-direction:column;gap:8px;max-height:280px;overflow:auto;}
.rm-member-row,.rm-add-member-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 12px;border-radius:14px;background:var(--bg3);border:1px solid var(--border);}
.rm-member-meta{display:flex;align-items:center;gap:10px;min-width:0;}
.rm-member-name{font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.rm-add-member-row{width:100%;border:none;cursor:pointer;text-align:left;color:var(--text);}
.rm-add-member-row:hover{background:var(--bg4);}
.rm-add-member-act{font-size:12px;font-weight:700;color:var(--gold);}
.assign-role-modal{width:min(620px,calc(100vw - 28px));}
.assign-role-shell{display:flex;flex-direction:column;gap:14px;}
.assign-role-list{display:flex;flex-direction:column;gap:8px;max-height:min(52vh,420px);overflow:auto;padding-right:2px;}
.assign-role-item{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 14px;border-radius:14px;background:var(--bg3);border:1px solid var(--border);}
.assign-role-meta{display:flex;align-items:center;gap:10px;min-width:0;}
.assign-role-name{font-size:14px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.privacy-modal{width:min(640px,calc(100vw - 28px));}
.privacy-modal-shell{display:flex;flex-direction:column;gap:14px;}
.privacy-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;}
.privacy-alert{padding:12px 14px;border-radius:14px;border:1px solid var(--gold3);background:color-mix(in srgb, var(--gold) 10%, transparent);color:var(--gold);font-size:13px;line-height:1.45;}
.admin-users-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;max-height:min(56vh,520px);}
.admin-user-card{padding:14px;border-radius:16px;background:var(--bg3);border:1px solid var(--border);display:flex;flex-direction:column;gap:12px;}
.admin-user-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;}
.admin-user-name{font-size:14px;font-weight:800;color:var(--text);}
.admin-user-badges{display:flex;flex-wrap:wrap;gap:6px;justify-content:flex-end;}
.admin-chip{display:inline-flex;align-items:center;gap:4px;padding:4px 8px;border-radius:999px;font-size:11px;font-weight:700;background:var(--bg4);color:var(--text2);}
.admin-chip-warm{background:rgba(255,159,67,.14);color:#ffb15c;}
.admin-chip-orange{background:rgba(230,126,34,.14);color:#f29c50;}
.admin-user-actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;}
.admin-user-actions .btn{min-width:0!important;height:40px!important;}
.admin-ban-row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 4px;border-bottom:1px solid var(--border);}
.admin-ban-row:last-child{border-bottom:none;}
.admin-ban-copy{display:flex;flex-direction:column;gap:4px;min-width:0;font-size:13px;}
.admin-ban-copy span{color:var(--text3);}
.server-admin-modal{width:min(920px,calc(100vw - 28px));}
.server-admin-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px;max-height:min(58vh,520px);overflow:auto;}
.server-admin-card{display:grid;grid-template-columns:52px minmax(0,1fr);gap:12px;padding:14px;border-radius:16px;background:var(--bg3);border:1px solid var(--border);align-items:start;}
.server-admin-user{width:52px;height:52px;border-radius:16px;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:20px;overflow:hidden;}
.server-admin-copy{min-width:0;display:flex;flex-direction:column;gap:4px;}
.server-admin-name{font-size:14px;font-weight:800;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.server-admin-desc{font-size:12px;color:var(--text3);}
.server-admin-actions{grid-column:1/-1;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;}
.server-admin-actions .btn{min-width:0;}
[data-theme="vk"] .roles-manager-sidebar,[data-theme="vk"] .roles-manager-shell,[data-theme="vk"] .assign-role-item,[data-theme="vk"] .rm-member-row,[data-theme="vk"] .rm-add-member-row,[data-theme="vk"] .server-admin-card,[data-theme="vk"] .admin-user-card{border-color:#dce1e6!important;}
[data-theme="vk"] .roles-manager-sidebar,[data-theme="vk"] .roles-manager-shell{background:#fff!important;}
[data-theme="vk"] .roles-manager-list,[data-theme="vk"] .roles-manager-detail{background:#f7f8fa!important;}
[data-theme="vk"] .rm-role-item.active,[data-theme="vk"] .assign-role-item,[data-theme="vk"] .rm-member-row,[data-theme="vk"] .rm-add-member-row,[data-theme="vk"] .server-admin-card,[data-theme="vk"] .admin-user-card{background:#fff!important;}
@media (max-width:820px){
  .roles-manager-modal{width:100%!important;max-width:100%!important;}
  .roles-manager-shell{grid-template-columns:1fr;max-height:none;min-height:0;margin:-12px -14px;}
  .roles-manager-sidebar{border-right:none;border-bottom:1px solid var(--border);}
  .roles-manager-sidebar-head{padding-right:48px;}
  .roles-manager-list{max-height:42vh;}
  .roles-manager-detail{padding:16px 14px;}
  /* Пустое состояние detail на узком экране не должно раздувать модалку на весь рост —
     даём ему компактную высоту, чтобы список ролей был сразу виден. */
  .roles-manager-detail:has(.rm-empty-state){display:none;}
  .rm-form-grid,.admin-user-actions,.server-admin-actions{grid-template-columns:1fr;}
  .rm-detail-header{flex-wrap:wrap;}
}


/* PATCH 9: fixed roles manager layout, scrollbars and create-role button fit */
#modalBg .modal.roles-manager-modal{
  width:min(960px,calc(100vw - 24px))!important;
  max-width:min(960px,calc(100vw - 24px))!important;
  padding:0!important;
  overflow:hidden!important;
}
#modalBg .modal.roles-manager-modal #modalBody{
  width:100%!important;
  min-width:0!important;
  overflow:hidden!important;
  scrollbar-gutter:auto!important;
}
.roles-manager-window{
  width:100%;
  min-width:0;
  display:flex;
  flex-direction:column;
  overflow:hidden;
  max-height:min(760px,calc(var(--app-h,100vh) - 32px));
}
.roles-manager-top{
  flex:0 0 auto;
  min-height:64px;
  padding:18px 64px 14px 22px;
  display:flex;
  align-items:center;
  border-bottom:1px solid var(--border);
  background:linear-gradient(180deg,rgba(255,255,255,.045),rgba(255,255,255,.015));
}
.roles-manager-top-title{
  min-width:0;
  display:flex;
  align-items:center;
  gap:10px;
  color:var(--gold);
  font-family:var(--font-heading);
  font-weight:800;
  font-size:22px;
  line-height:1.1;
}
.roles-manager-top-title span:last-child{
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}
.roles-manager-shell{
  margin:0!important;
  min-height:0!important;
  height:min(620px,max(360px,calc(var(--app-h,100vh) - 120px)));
  max-height:none!important;
  display:grid!important;
  grid-template-columns:minmax(240px,300px) minmax(0,1fr)!important;
  overflow:hidden!important;
  border:0!important;
}
.roles-manager-sidebar{
  min-width:0!important;
  min-height:0!important;
  overflow:hidden!important;
  background:rgba(8,15,28,.38)!important;
}
.roles-manager-sidebar-head{
  flex:0 0 auto;
  padding:18px 18px 12px!important;
  border-bottom:0!important;
}
.roles-manager-title{
  font-size:12px!important;
  letter-spacing:.1em!important;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}
.roles-manager-sub{display:none!important;}
.roles-manager-list{
  flex:1 1 auto!important;
  min-height:0!important;
  overflow:auto!important;
  padding:8px 12px 12px!important;
  gap:8px!important;
}
.roles-manager-actions{
  flex:0 0 auto!important;
  padding:12px 14px 16px!important;
  border-top:1px solid var(--border)!important;
  background:linear-gradient(180deg,rgba(10,18,32,.12),rgba(10,18,32,.42));
}
.roles-manager-actions .btn{
  width:100%!important;
  min-width:0!important;
  max-width:100%!important;
  box-sizing:border-box!important;
  min-height:46px!important;
  padding:0 14px!important;
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:8px!important;
  white-space:nowrap!important;
  overflow:hidden!important;
  text-overflow:ellipsis!important;
}
.roles-manager-actions .btn span:last-child{
  min-width:0;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}
.rm-role-item{
  box-sizing:border-box!important;
  min-height:52px!important;
  padding:13px 14px!important;
  border:1px solid var(--border)!important;
  background:rgba(255,255,255,.025)!important;
}
.rm-role-item:hover{background:var(--bg3)!important;}
.rm-role-item.active{background:var(--bg4)!important;border-color:var(--border2)!important;}
.roles-manager-detail{
  min-width:0!important;
  min-height:0!important;
  overflow:auto!important;
  padding:22px!important;
  background:linear-gradient(180deg,rgba(255,255,255,.018),rgba(255,255,255,.005));
}
.rm-empty-state{
  min-height:100%!important;
  height:auto!important;
  padding:28px!important;
}
.rm-empty-title{
  font-size:18px!important;
  line-height:1.2!important;
}
.rm-empty-sub{
  max-width:300px;
  margin:0 auto;
  font-size:14px!important;
}
.roles-manager-modal .modal-close{
  top:16px!important;
  right:16px!important;
}
[data-theme="vk"] .roles-manager-top{
  background:#fff!important;
  border-bottom-color:#dce1e6!important;
}
[data-theme="vk"] .roles-manager-top-title{color:#0077ff!important;}
[data-theme="vk"] .roles-manager-sidebar{background:#f7f8fa!important;}
[data-theme="vk"] .roles-manager-actions{background:#fff!important;}
[data-theme="vk"] .rm-role-item{background:#fff!important;border-color:#dce1e6!important;color:#111!important;}
@media (max-width:820px){
  #modalBg .modal.roles-manager-modal{
    width:calc(100vw - 16px)!important;
    max-width:calc(100vw - 16px)!important;
    border-radius:20px!important;
  }
  .roles-manager-window{max-height:calc(var(--app-h,100vh) - 16px);}
  .roles-manager-top{min-height:58px;padding:16px 58px 12px 16px;}
  .roles-manager-top-title{font-size:20px;}
  .roles-manager-shell{
    grid-template-columns:1fr!important;
    height:auto!important;
    max-height:calc(var(--app-h,100vh) - 74px)!important;
    overflow:auto!important;
  }
  .roles-manager-sidebar{
    border-right:none!important;
    border-bottom:1px solid var(--border)!important;
    max-height:52vh!important;
  }
  .roles-manager-sidebar-head{padding:14px 14px 8px!important;}
  .roles-manager-list{max-height:34vh!important;padding:8px 10px 10px!important;}
  .roles-manager-actions{padding:10px 12px 12px!important;}
  .roles-manager-detail{
    padding:16px 14px!important;
    min-height:190px!important;
  }
  .roles-manager-detail:has(.rm-empty-state){display:block!important;}
  .rm-empty-state{min-height:160px!important;padding:20px 12px!important;}
}
@media (max-width:460px){
  .roles-manager-actions .btn{min-height:44px!important;font-size:13px!important;}
  .rm-role-item{min-height:48px!important;padding:11px 12px!important;}
}



/* PATCH 6: VK / trueCORD White settings contrast fix */
[data-theme="vk"] .settings-accordion summary,
[data-theme="vk"] .settings-accordion summary .ti,
[data-theme="vk"] .settings-accordion summary svg{
  color:#111827!important;
  opacity:1!important;
}
[data-theme="vk"] .settings-accordion summary:hover{
  background:#eef3f8!important;
}
[data-theme="vk"] .settings-accordion[open] summary{
  color:#111827!important;
  border-bottom:1px solid #dce1e6!important;
  background:#f8fafc!important;
}
[data-theme="vk"] .settings-accordion-body,
[data-theme="vk"] .settings-accordion-body .fl,
[data-theme="vk"] .settings-accordion-body label,
[data-theme="vk"] .settings-accordion-body .settings-card-title,
[data-theme="vk"] .settings-accordion-body .settings-card-sub{
  color:#111827!important;
  opacity:1!important;
}
[data-theme="vk"] .settings-accordion-body .fl,
[data-theme="vk"] .settings-card .fl{
  color:#64748b!important;
  font-weight:800!important;
}
[data-theme="vk"] .settings-bottom-grid > .btn,
[data-theme="vk"] .settings-bottom-grid > button{
  color:#111827!important;
  background:#ffffff!important;
  border-color:#d5dce5!important;
  opacity:1!important;
}
[data-theme="vk"] .settings-bottom-grid > .btn:hover,
[data-theme="vk"] .settings-bottom-grid > button:hover{
  background:#eef3f8!important;
}
[data-theme="vk"] .settings-bottom-grid .btn-red,
[data-theme="vk"] .settings-bottom-grid .btn-red *{
  color:#b42318!important;
}
[data-theme="vk"] .settings-card,
[data-theme="vk"] .settings-accordion{
  opacity:1!important;
}
[data-theme="vk"] .settings-card *,
[data-theme="vk"] .settings-accordion *{
  text-shadow:none!important;
}



/* PATCH 7: restore classic compact admin panel */
.admin-panel-classic{width:min(760px,calc(100vw - 28px));}
.admin-users-classic{background:transparent!important;border:none!important;padding:0!important;max-height:min(48vh,430px)!important;overflow:auto;}
.admin-user-row{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:14px;padding:10px 0;border-bottom:1px solid var(--border);}
.admin-user-main{min-width:0;display:flex;align-items:center;gap:10px;}
.admin-user-copy{min-width:0;display:flex;flex-direction:column;gap:4px;}
.admin-user-name{font-size:15px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.admin-user-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;min-height:16px;}
.admin-inline-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:999px;background:var(--bg3);border:1px solid var(--border2);font-size:11px;font-weight:700;color:var(--text2);}
.admin-inline-badge-gold{color:var(--gold);}
.admin-inline-badge-orange{color:#e59a48;}
.admin-actions-strip{display:flex;align-items:center;gap:6px;flex-wrap:nowrap;}
.admin-icon-btn,.admin-icon-passive{width:34px;height:34px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;line-height:1;border:1px solid var(--border2);background:var(--bg3);color:var(--text2);box-shadow:none;}
.admin-icon-btn{cursor:pointer;transition:.12s ease;}
.admin-icon-btn:hover{transform:translateY(-1px);background:var(--bg4);}
.admin-icon-btn-gold{color:var(--gold2);}
.admin-icon-btn-red{background:rgba(200,60,60,.18);border-color:rgba(200,60,60,.35);color:#ffb1b1;}
.admin-icon-btn-red:hover{background:rgba(200,60,60,.24);}
.admin-icon-passive{opacity:.75;cursor:default;}
.admin-bans-classic{background:transparent!important;border:none!important;padding:0!important;max-height:min(26vh,220px)!important;overflow:auto;}
.admin-ban-row{display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);}
.admin-ban-copy{min-width:0;display:flex;flex-direction:column;gap:4px;}
.admin-ban-copy strong{font-size:14px;color:var(--text);}
.admin-ban-copy span{font-size:12px;color:var(--text3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
[data-theme="vk"] .admin-users-classic,[data-theme="vk"] .admin-bans-classic{background:transparent!important;}
[data-theme="vk"] .admin-user-name,[data-theme="vk"] .admin-ban-copy strong{color:#111!important;}
[data-theme="vk"] .admin-inline-badge,[data-theme="vk"] .admin-icon-btn,[data-theme="vk"] .admin-icon-passive{background:#f2f4f7!important;border-color:#d7dee8!important;color:#2c2d2e!important;}
[data-theme="vk"] .admin-icon-btn-red{background:#faecec!important;border-color:#efb8b8!important;color:#c33c3c!important;}
@media (max-width:760px){.admin-user-row,.admin-ban-row{grid-template-columns:1fr;}.admin-actions-strip{flex-wrap:wrap;}}

/* ── ANDROID ALPHA/CLIP FIX ──────────────────────────────────────────
   На Android Chrome дочерний <img> с object-fit не всегда обрезается по
   border-radius родителя из-за отдельного GPU-слоя, и прозрачные PNG
   показывают фон страницы сквозь маску. Решение:
   1) контейнеры аватарок образуют собственный слой (isolation+translateZ),
      тогда overflow:hidden обрезает корректно;
   2) у самих <img> явный непрозрачный фон-подложка, чтобы прозрачные
      области PNG не «дырявили» аватар. */
.srv-icon-inner,.vs-av,.vs-tile-av,.av-preview,.msg-av,.up-avatar-inner,.call-avatar,
.settings-avatar,.server-settings-avatar,.dm-av,.mb-av,.comment-av,.mention-av,
.vp-av,.vu-av,.banner-av,.di-notif-av,.np-item-av,.qr-av,
.ch-icon,.hdr-ch-icon,.app-catalog-icon{
  overflow:hidden;
  isolation:isolate;
  -webkit-backface-visibility:hidden;
  backface-visibility:hidden;
  background-color:var(--bg3);
}
.srv-icon-inner img,.vs-av img,.vs-tile-av img,.av-preview img,.msg-av img,.up-avatar-inner img,
.call-avatar img,.settings-avatar img,.server-settings-avatar img,.dm-av img,
.mb-av img,.comment-av img,.mention-av img,.vp-av img,.vu-av img,.banner-av img,
.di-notif-av img,.np-item-av img,.qr-av img,.ch-icon img,.hdr-ch-icon img,
.app-catalog-icon img{
  background-color:var(--bg3);
}


/* PATCH 8: аккуратная карточка профиля/сервера в настройках
   - статус в узкой версии уходит под ник, а не занимает строку ачивок;
   - компактная карточка сервера остаётся ровной колонкой даже при <=980px. */
@media (min-width:741px) and (max-width:980px){
  .settings-modal .settings-profile-card:not(.compact){
    display:grid!important;
    grid-template-columns:auto minmax(0,1fr) minmax(190px,260px);
    grid-template-areas:
      "avatar name badges"
      "avatar meta badges";
    align-items:center!important;
    justify-content:stretch!important;
    column-gap:14px!important;
    row-gap:2px!important;
    text-align:left!important;
    padding:16px!important;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-avatar-wrap{
    grid-area:avatar;
    align-self:center;
    justify-self:start;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-name{
    grid-area:name;
    min-width:0;
    max-width:100%;
    overflow:hidden;
    text-overflow:ellipsis;
    white-space:nowrap;
    align-self:end;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-meta{
    grid-area:meta;
    min-width:0;
    max-width:100%;
    justify-self:start;
    align-self:start;
    margin-top:3px;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-badges{
    grid-area:badges;
    width:100%;
    max-width:260px;
    justify-self:end;
    align-self:center;
    display:flex!important;
    flex-direction:column!important;
    align-items:stretch!important;
    justify-content:center!important;
    gap:8px!important;
    margin-top:0!important;
    min-width:0;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-badges .settings-badge{
    width:100%;
    justify-content:center;
    min-width:0;
    max-width:100%;
    min-height:40px;
    padding-left:12px;
    padding-right:12px;
    white-space:normal;
    overflow-wrap:anywhere;
    text-align:center;
  }
}

@media (max-width:740px){
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-meta{
    margin-top:-3px;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-badges{
    width:100%;
    max-width:280px;
    margin-left:auto;
    margin-right:auto;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-badges .settings-badge{
    width:100%;
    justify-content:center;
    white-space:normal;
    text-align:center;
  }
}

.server-settings-shell .settings-profile-card.compact{
  padding:0!important;
  min-height:238px!important;
  display:flex!important;
  flex-direction:column!important;
  align-items:stretch!important;
  justify-content:flex-start!important;
  gap:0!important;
  text-align:center!important;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-banner{
  display:block;
  width:100%;
  height:58px;
  flex:0 0 58px;
  background:
    radial-gradient(circle at 50% -20%,rgba(122,107,255,.20),transparent 62%),
    linear-gradient(135deg,rgba(45,125,255,.14),rgba(255,255,255,.03));
  border-bottom:1px solid rgba(255,255,255,.07);
}
.server-settings-shell .settings-profile-card.compact .settings-profile-card-body{
  position:relative;
  z-index:1;
  width:100%;
  min-width:0;
  margin-top:-36px;
  padding:0 18px 18px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:flex-start;
  gap:7px;
}
.server-settings-shell .settings-profile-card.compact .settings-avatar-floating{
  margin:0 0 4px!important;
  align-self:center!important;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-name,
.server-settings-shell .settings-profile-card.compact .settings-profile-meta{
  width:100%;
  max-width:100%;
  min-width:0;
  text-align:center;
  justify-content:center;
  overflow:hidden;
  text-overflow:ellipsis;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-name{
  white-space:nowrap;
  font-size:20px!important;
  line-height:1.15;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-meta{
  white-space:normal;
  line-height:1.35;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-badges{
  width:100%;
  display:grid!important;
  grid-template-columns:1fr;
  gap:8px!important;
  margin-top:8px!important;
}
.server-settings-shell .settings-profile-card.compact .settings-profile-badges .settings-badge{
  width:100%;
  min-height:40px;
  justify-content:center;
  border-radius:18px;
  font-size:12px;
  white-space:normal;
  text-align:center;
}
[data-theme="vk"] .server-settings-shell .settings-profile-card.compact .settings-profile-banner{
  background:linear-gradient(135deg,#eef6ff 0%,#ffffff 100%)!important;
  border-bottom-color:#dce1e6!important;
}



/* PATCH 9: mobile user settings profile layout
   Fix: on narrow screens the status must stay under the nickname,
   not inline/behind it. Keep server compact cards untouched. */
@media (max-width:740px){
  .settings-modal .settings-profile-card:not(.compact){
    display:flex!important;
    flex-direction:column!important;
    align-items:center!important;
    justify-content:center!important;
    text-align:center!important;
    gap:8px!important;
    padding:18px 16px!important;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-avatar-wrap{
    order:1;
    margin:0!important;
    align-self:center!important;
    justify-self:center!important;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-name{
    order:2;
    display:block!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    margin:2px 0 0!important;
    text-align:center!important;
    line-height:1.15!important;
    overflow:hidden!important;
    text-overflow:ellipsis!important;
    white-space:nowrap!important;
    position:relative!important;
    z-index:1;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-meta{
    order:3;
    display:flex!important;
    width:100%!important;
    max-width:100%!important;
    min-width:0!important;
    margin:0!important;
    justify-content:center!important;
    align-items:center!important;
    text-align:center!important;
    white-space:normal!important;
    overflow-wrap:anywhere!important;
    position:relative!important;
    z-index:1;
  }
  .settings-modal .settings-profile-card:not(.compact) .settings-profile-badges{
    order:4;
    width:100%;
    max-width:280px;
    margin:4px auto 0!important;
  }
}



/* PATCH 8: compact admin panel + avatar-attached status/unread badges */
.admin-panel-window{
  width:min(760px,calc(100vw - 24px))!important;
  max-width:min(760px,calc(100vw - 24px))!important;
  padding:18px!important;
  overflow:hidden!important;
}
.admin-panel-window #modalBody{padding:0!important;overflow:hidden!important;min-width:0!important;}
.admin-panel-clean{width:100%!important;min-width:0;max-height:calc(var(--app-h,100vh) - 64px);display:flex;flex-direction:column;}
.admin-panel-clean h2{margin:0 42px 10px 0!important;line-height:1.15!important;}
.admin-panel-clean .admin-section-title{margin:12px 0 8px!important;}
.admin-panel-clean .admin-users-classic{
  max-height:min(52vh,430px)!important;
  padding:0 4px 0 0!important;
  overflow:auto!important;
}
.admin-panel-clean .admin-bans-classic{
  max-height:min(24vh,200px)!important;
  padding:0 4px 0 0!important;
}
.admin-panel-clean .admin-user-row{
  display:grid!important;
  grid-template-columns:minmax(0,1fr) auto!important;
  align-items:center!important;
  gap:10px!important;
  padding:8px 0!important;
  min-width:0!important;
}
.admin-panel-clean .admin-user-main{min-width:0!important;gap:10px!important;}
.admin-user-avatar,
.server-admin-user{position:relative;overflow:visible!important;isolation:isolate;}
.admin-user-avatar{
  width:38px;height:38px;border-radius:50%;background:var(--bg3);border:1px solid var(--border2);
  display:flex;align-items:center;justify-content:center;flex:0 0 auto;font-size:16px;font-weight:800;color:var(--text);
}
.admin-user-avatar img{width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;background:var(--bg3);}
.admin-status-dot,.server-admin-status{
  position:absolute;right:-2px;bottom:-2px;width:12px;height:12px;border-radius:50%;
  border:2px solid var(--bg1);z-index:5;box-shadow:0 2px 8px rgba(0,0,0,.28);
}
.admin-unread-badge,.server-admin-unread,
.dm-badge{
  position:absolute;top:-5px;right:-5px;left:auto;bottom:auto;z-index:6;
  min-width:17px;height:17px;border-radius:999px;padding:0 4px;
  display:flex;align-items:center;justify-content:center;
  background:var(--red2);color:#fff;border:2px solid var(--bg1);
  font-size:9px;font-weight:800;line-height:1;box-shadow:0 2px 8px rgba(0,0,0,.28);
}
.admin-panel-clean .admin-actions-strip{
  display:grid!important;
  grid-template-columns:repeat(6,30px)!important;
  gap:5px!important;
  justify-content:end!important;
  align-items:center!important;
  flex-wrap:nowrap!important;
}
.admin-panel-clean .admin-icon-btn,
.admin-panel-clean .admin-icon-passive{
  width:30px!important;height:30px!important;border-radius:10px!important;font-size:12px!important;padding:0!important;min-width:0!important;
}
.admin-panel-clean .admin-icon-btn .ti svg{width:13px!important;height:13px!important;}
.admin-panel-clean .admin-inline-badge{padding:2px 7px!important;font-size:10px!important;}
/* Status dots and unread badges must sit on top of avatars, not be clipped by the Android image mask fix. */
.dm-av,.mb-av{overflow:visible!important;isolation:isolate;}
.dm-av img,.mb-av img{border-radius:50%;display:block;background:var(--bg3);}
/* Preview avatars must always center-crop the image inside the circle, never align to an edge. */
.dm-av img,.mb-av img,.di-notif-av img,.np-item-av img,.qr-av img,.mention-av img,.comment-av img{
  width:100%!important;height:100%!important;object-fit:cover!important;object-position:center center!important;
}
.dm-status,.mb-status{z-index:5!important;}
.server-admin-user{border-radius:50%!important;}
.server-admin-user img{border-radius:50%!important;display:block;background:var(--bg3);}
.server-admin-card{grid-template-columns:44px minmax(0,1fr)!important;padding:12px!important;gap:10px!important;}
.server-admin-user{width:44px!important;height:44px!important;font-size:18px!important;}
.server-admin-actions{grid-template-columns:repeat(auto-fit,minmax(88px,1fr))!important;gap:7px!important;}
.server-admin-actions .btn{height:36px!important;border-radius:12px!important;padding:0 10px!important;}
@media (max-width:560px){
  .admin-panel-window{width:calc(100vw - 16px)!important;max-width:calc(100vw - 16px)!important;padding:14px!important;}
  .admin-panel-clean{max-height:calc(var(--app-h,100vh) - 42px);}
  /* Keep name on the left and the action buttons on the right (compact icon grid), not a full-width stacked column. */
  .admin-panel-clean .admin-user-row{grid-template-columns:minmax(0,1fr) auto!important;align-items:center!important;gap:10px!important;}
  .admin-panel-clean .admin-actions-strip{grid-template-columns:repeat(3,28px)!important;grid-auto-rows:28px!important;gap:5px!important;width:auto!important;justify-content:end!important;justify-items:stretch!important;}
  .admin-panel-clean .admin-icon-btn,.admin-panel-clean .admin-icon-passive{width:28px!important;height:28px!important;min-width:0!important;}
}
@media (max-width:380px){
  .admin-panel-clean .admin-actions-strip{grid-template-columns:repeat(3,26px)!important;grid-auto-rows:26px!important;gap:4px!important;}
  .admin-panel-clean .admin-icon-btn,.admin-panel-clean .admin-icon-passive{width:26px!important;height:26px!important;}
}
[data-theme="vk"] .admin-user-avatar{background:#f2f4f7!important;border-color:#d7dee8!important;color:#2c2d2e!important;}
[data-theme="vk"] .admin-status-dot,[data-theme="vk"] .server-admin-status,[data-theme="vk"] .admin-unread-badge,[data-theme="vk"] .server-admin-unread,[data-theme="vk"] .dm-badge{border-color:#fff!important;}

/* PATCH 9 (переработано): анимации голосовой сетки. Дизайн карточек теперь в базовых .vs-tile стилях и наследует тему. */
@keyframes vsAuraPulse{0%,100%{transform:scale(1);opacity:.72}50%{transform:scale(1.08);opacity:1}}
@keyframes vsAuraSoft{0%,100%{transform:scale(.96);opacity:.18}50%{transform:scale(1.08);opacity:.34}}
@media(max-width:560px){
  .voice-stage-controls .vs-btn{flex:1 1 100%!important}
}
/* PATCH 10: фиксированная сетка — без перескакивания плиток при смене активного говорящего */
.voice-stage-grid{align-items:stretch!important;grid-auto-flow:row!important}
.vs-tile,
.vs-tile.featured{
  grid-column:auto!important;
}
.vs-tile.featured .vs-tile-avatar .vs-tile-av{
  width:76px!important;
  height:76px!important;
  font-size:30px!important;
}
.vs-tile.featured .vs-tile-name{font-size:15px!important}
@media(max-width:900px){
  .vs-tile.featured .vs-tile-avatar .vs-tile-av{width:64px!important;height:64px!important;font-size:25px!important}
}


/* PATCH 11: mobile voice controls as compact round buttons */
@media(max-width:740px){
  .voice-stage-head{
    align-items:center!important;
    gap:8px!important;
  }
  .voice-stage-actions{
    gap:6px!important;
    flex-shrink:0!important;
  }
  .voice-stage-actions .vs-btn{
    width:40px!important;
    height:40px!important;
    min-width:40px!important;
    flex:0 0 40px!important;
    padding:0!important;
    border-radius:50%!important;
    gap:0!important;
  }
  .voice-stage-actions .vs-btn .lbl,
  .voice-stage-actions .vs-btn > span:not(.ti){
    display:none!important;
  }
  .voice-stage-actions .vs-btn .ti{
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
  }
  .voice-stage-controls{
    align-self:center!important;
    width:max-content!important;
    max-width:calc(100vw - 22px)!important;
    flex-wrap:nowrap!important;
    justify-content:center!important;
    gap:8px!important;
    padding:8px!important;
    border-radius:999px!important;
    margin:0 auto!important;
    overflow-x:auto!important;
    scrollbar-width:none!important;
  }
  .voice-stage-controls::-webkit-scrollbar{display:none!important}
  .voice-stage-controls .vs-btn{
    width:46px!important;
    height:46px!important;
    min-width:46px!important;
    flex:0 0 46px!important;
    padding:0!important;
    border-radius:50%!important;
    gap:0!important;
  }
  .voice-stage-controls .vs-btn > span:not(.ti){
    display:none!important;
  }
  .voice-stage-controls .vs-btn .ti{
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
  }
  .voice-stage-controls .vs-btn svg{
    width:19px!important;
    height:19px!important;
  }
}
@media(max-width:560px){
  .voice-stage-controls{position:sticky!important;bottom:8px!important}
  .voice-stage-controls .vs-btn{
    width:44px!important;
    height:44px!important;
    min-width:44px!important;
    flex:0 0 44px!important;
  }
}

/* PATCH 12: на мобильных нижние дублирующие контролы убраны.
   Управление микрофоном/звуком/трансляцией остаётся на плитке участника и в нижней панели войса. */
@media(max-width:740px){
  .voice-stage-controls{display:none!important}
}


/* PATCH 13: mobile inline stream layout — stream first, avatar rail below */
@media(max-width:900px){
  #voiceStage.has-stream .voice-stage-body{
    display:flex!important;
    flex-direction:column!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    gap:10px!important;
    padding:10px!important;
  }
  #voiceStage.has-stream .voice-stage-main{
    display:flex!important;
    flex-direction:column!important;
    gap:10px!important;
    overflow:visible!important;
    min-height:0!important;
    padding-right:0!important;
  }
  #voiceStage.has-stream .voice-stage-stream{
    order:1!important;
    width:100%!important;
    flex:0 0 auto!important;
    min-height:220px!important;
    height:clamp(220px,56dvh,460px)!important;
    max-height:calc(100dvh - 230px)!important;
    border-radius:18px!important;
  }
  #voiceStage.has-stream .voice-stage-grid-wrap{
    order:2!important;
    flex:0 0 auto!important;
    min-width:0!important;
    gap:0!important;
  }
  #voiceStage.has-stream .voice-stage-grid-head{
    display:none!important;
  }
  #voiceStage.has-stream .voice-stage-grid{
    display:flex!important;
    grid-template-columns:none!important;
    grid-auto-flow:column!important;
    align-items:center!important;
    justify-content:flex-start!important;
    gap:10px!important;
    padding:4px 2px 8px!important;
    overflow-x:auto!important;
    overflow-y:visible!important;
    scrollbar-width:none!important;
    -webkit-overflow-scrolling:touch!important;
  }
  #voiceStage.has-stream .voice-stage-grid::-webkit-scrollbar{display:none!important}
  #voiceStage.has-stream .vs-tile,
  #voiceStage.has-stream .vs-tile.featured{
    width:58px!important;
    height:58px!important;
    min-width:58px!important;
    min-height:58px!important;
    flex:0 0 58px!important;
    grid-column:auto!important;
    border-radius:50%!important;
    display:block!important;
    overflow:visible!important;
    background:transparent!important;
    border:0!important;
    box-shadow:none!important;
    padding:0!important;
    transform:none!important;
  }
  #voiceStage.has-stream .vs-tile:hover{transform:none!important;box-shadow:none!important}
  #voiceStage.has-stream .vs-tile::before,
  #voiceStage.has-stream .vs-tile-bg,
  #voiceStage.has-stream .vs-tile-body{
    display:none!important;
  }
  #voiceStage.has-stream .vs-tile-avatar{
    position:absolute!important;
    inset:0!important;
    display:flex!important;
    align-items:center!important;
    justify-content:center!important;
  }
  #voiceStage.has-stream .vs-tile-avatar::before{display:none!important}
  #voiceStage.has-stream .vs-tile-avatar .vs-tile-av,
  #voiceStage.has-stream .vs-tile.featured .vs-tile-avatar .vs-tile-av{
    width:52px!important;
    height:52px!important;
    min-width:52px!important;
    min-height:52px!important;
    font-size:20px!important;
    border-radius:50%!important;
    border:2px solid rgba(255,255,255,.16)!important;
    box-shadow:0 10px 24px rgba(0,0,0,.22)!important;
  }
  #voiceStage.has-stream .vs-tile.speaking .vs-tile-avatar .vs-tile-av{
    border-color:rgba(78,145,96,.78)!important;
    box-shadow:0 0 0 4px rgba(78,145,96,.18),0 0 24px rgba(78,145,96,.30)!important;
  }
  #voiceStage.has-stream .vs-tile.watching .vs-tile-avatar .vs-tile-av{
    border-color:rgba(88,101,242,.78)!important;
    box-shadow:0 0 0 4px rgba(88,101,242,.18),0 10px 24px rgba(0,0,0,.22)!important;
  }
  #voiceStage.has-stream .vs-tile.streaming .vs-tile-av{
    box-shadow:0 0 0 3px #ed4245,0 4px 14px rgba(0,0,0,.18)!important;
    animation:vsStreamPulse 1.5s ease-in-out infinite!important;
  }
  #voiceStage.has-stream .voice-stage-side{
    display:none!important;
  }
  #voiceStage.has-stream .voice-stage-controls{
    order:3!important;
    position:relative!important;
    bottom:auto!important;
    margin:2px auto 0!important;
    z-index:2!important;
  }
}
@media(max-width:560px){
  #voiceStage.has-stream .voice-stage-body{padding:8px!important;gap:8px!important}
  #voiceStage.has-stream .voice-stage-stream{
    min-height:190px!important;
    height:clamp(190px,50dvh,360px)!important;
    max-height:calc(100dvh - 210px)!important;
    border-radius:16px!important;
  }
  #voiceStage.has-stream .vs-tile,
  #voiceStage.has-stream .vs-tile.featured{
    width:52px!important;height:52px!important;min-width:52px!important;min-height:52px!important;flex-basis:52px!important;
  }
  #voiceStage.has-stream .vs-tile-avatar .vs-tile-av,
  #voiceStage.has-stream .vs-tile.featured .vs-tile-avatar .vs-tile-av{
    width:48px!important;height:48px!important;min-width:48px!important;min-height:48px!important;font-size:18px!important;
  }
}


/* PATCH 14: keep notification panel fixed on mobile/trueCOLOR */
#notifPanel,
[data-theme="truecolor"] #notifPanel{
  position:fixed!important;
  top:auto!important;
  right:auto!important;
  bottom:64px!important;
  left:72px!important;
  width:320px!important;
  max-width:calc(100vw - 88px)!important;
  max-height:min(480px,calc(100dvh - 92px - var(--safe-bottom,0px)))!important;
  overflow:hidden!important;
  transform-origin:left bottom!important;
  z-index:3500!important;
}
@media(max-width:980px){
  #notifPanel,
  [data-theme="truecolor"] #notifPanel{
    position:fixed!important;
    top:auto!important;
    right:10px!important;
    bottom:calc(86px + var(--safe-bottom,0px))!important;
    left:10px!important;
    width:auto!important;
    max-width:none!important;
    max-height:min(420px,calc(100dvh - 112px - var(--safe-bottom,0px)))!important;
  }
}
@media(max-width:740px){
  #notifPanel,
  [data-theme="truecolor"] #notifPanel{
    left:8px!important;
    right:8px!important;
    bottom:calc(88px + var(--safe-bottom,0px))!important;
    width:auto!important;
    max-height:min(380px,calc(100dvh - 108px - var(--safe-bottom,0px)))!important;
    border-radius:18px!important;
  }
}


/* PATCH 16: light theme palettes — dark text on light panels
   VK/trueCORD White is a real light palette now: late dark overrides
   from voice, settings, admin, stream and mini-windows no longer leave
   white text or dark cards inside the light theme. */
[data-theme="vk"]{
  color-scheme:light!important;
  --bg0:#edeef0;
  --bg1:#f5f7fa;
  --bg2:#ffffff;
  --bg3:#f0f2f5;
  --bg4:#e5ebf1;
  --bg5:#dce1e6;
  --text:#1f2328;
  --text2:#4f5b67;
  --text3:#6f7b88;
  --text4:#97a3af;
  --border:#dce1e6;
  --border2:#c9d1da;
  --gold:#0077ff;
  --gold2:#006be6;
  --gold3:#99c8ff;
  --gold-glow:rgba(0,119,255,.16);
  --gold-dim:rgba(0,119,255,.08);
  --glass-bg:rgba(255,255,255,.78);
  --glass-panel:rgba(255,255,255,.86);
  --glass-bg2:rgba(255,255,255,.94);
  --glass-border:rgba(0,0,0,.08);
  --glass-border-gold:rgba(0,119,255,.22);
}
[data-theme="vk"] body,
[data-theme="vk"] #app,
[data-theme="vk"] #mainArea,
[data-theme="vk"] #memberSidebar,
[data-theme="vk"] #chSidebar{
  color:#1f2328!important;
}
[data-theme="vk"] #mainArea,
[data-theme="vk"] #memberSidebar,
[data-theme="vk"] #chSidebar,
[data-theme="vk"] .voice-stage,
[data-theme="vk"] .voice-stage-body,
[data-theme="vk"] .settings-shell,
[data-theme="vk"] .server-settings-shell,
[data-theme="vk"] .channel-settings-shell,
[data-theme="vk"] .app-editor-shell{
  background:#edeef0!important;
}
[data-theme="vk"] #sidebarHeader,
[data-theme="vk"] #mainHeader,
[data-theme="vk"] #inputOuter,
[data-theme="vk"] #dmInputOuter,
[data-theme="vk"] .voice-stage-head,
[data-theme="vk"] .voice-stage-controls,
[data-theme="vk"] .settings-card,
[data-theme="vk"] .settings-accordion,
[data-theme="vk"] .server-settings-maincard,
[data-theme="vk"] .server-settings-sidecard,
[data-theme="vk"] .channel-settings-card,
[data-theme="vk"] .app-editor-card,
[data-theme="vk"] .admin-panel-window,
[data-theme="vk"] .admin-panel-clean,
[data-theme="vk"] .modal,
[data-theme="vk"] #ctxMenu,
[data-theme="vk"] #notifPanel,
[data-theme="vk"] #emojiPicker,
[data-theme="vk"] #floatingPlayer,
[data-theme="vk"] #streamViewer{
  background:#ffffff!important;
  color:#1f2328!important;
  border-color:#dce1e6!important;
  box-shadow:0 10px 28px rgba(0,0,0,.08)!important;
}
[data-theme="vk"] .voice-stage-title,
[data-theme="vk"] .voice-stage-head h2,
[data-theme="vk"] .voice-stage-subtitle,
[data-theme="vk"] .settings-card-title,
[data-theme="vk"] .settings-profile-name,
[data-theme="vk"] .settings-hero h2,
[data-theme="vk"] .server-settings-headcopy h2,
[data-theme="vk"] .modal h2,
[data-theme="vk"] .hdr-ch-name,
[data-theme="vk"] .srv-title,
[data-theme="vk"] .up-name,
[data-theme="vk"] .msg-name,
[data-theme="vk"] .dm-name,
[data-theme="vk"] .mb-name,
[data-theme="vk"] .voice-room-name,
[data-theme="vk"] .voice-part-name,
[data-theme="vk"] .admin-user-name,
[data-theme="vk"] .admin-ban-copy strong,
[data-theme="vk"] .vs-tile-name{
  color:#1f2328!important;
}
[data-theme="vk"] .settings-card-sub,
[data-theme="vk"] .settings-profile-meta,
[data-theme="vk"] .server-settings-meta-note,
[data-theme="vk"] .modal-sub,
[data-theme="vk"] .ch-category,
[data-theme="vk"] .msg-meta,
[data-theme="vk"] .dm-last,
[data-theme="vk"] .mb-meta,
[data-theme="vk"] .voice-stage-meta,
[data-theme="vk"] .vs-tile-status,
[data-theme="vk"] .vs-tile-role,
[data-theme="vk"] .admin-ban-copy span{
  color:#6f7b88!important;
}
[data-theme="vk"] .vs-tile,
[data-theme="vk"] .vs-tile.featured,
[data-theme="vk"] .voice-stage-side,
[data-theme="vk"] .voice-stage-main,
[data-theme="vk"] .voice-stage-stream,
[data-theme="vk"] .voice-stage-avatars,
[data-theme="vk"] .voice-stage-mobile-avatars,
[data-theme="vk"] .vp-row,
[data-theme="vk"] .vu-row,
[data-theme="vk"] .voice-room,
[data-theme="vk"] .ch-item,
[data-theme="vk"] .dm-item,
[data-theme="vk"] .mb-item,
[data-theme="vk"] .mention-item,
[data-theme="vk"] .settings-badge,
[data-theme="vk"] .server-admin-user-row,
[data-theme="vk"] .admin-user-row,
[data-theme="vk"] .admin-ban-row{
  background:#ffffff!important;
  color:#1f2328!important;
  border-color:#dce1e6!important;
  box-shadow:0 6px 18px rgba(0,0,0,.05)!important;
}
[data-theme="vk"] .vs-tile::before,
[data-theme="vk"] .vs-tile::after{
  opacity:.18!important;
}
[data-theme="vk"] .vs-tile:hover,
[data-theme="vk"] .ch-item:hover,
[data-theme="vk"] .dm-item:hover,
[data-theme="vk"] .mb-item:hover,
[data-theme="vk"] .voice-room:hover,
[data-theme="vk"] .mention-item:hover{
  background:#f5f7fa!important;
  color:#1f2328!important;
  transform:none!important;
}
[data-theme="vk"] .vs-tile.active,
[data-theme="vk"] .vs-tile.speaking,
[data-theme="vk"] .ch-item.active,
[data-theme="vk"] .dm-item.active,
[data-theme="vk"] .voice-room.active,
[data-theme="vk"] .mention-item.active{
  background:#eef6ff!important;
  color:#1f2328!important;
  border-color:#99c8ff!important;
  box-shadow:0 0 0 1px rgba(0,119,255,.12),0 8px 22px rgba(0,119,255,.08)!important;
}
[data-theme="vk"] .vs-tile-pill,
[data-theme="vk"] .vs-tile-btn,
[data-theme="vk"] .vs-btn,
[data-theme="vk"] .vb-btn,
[data-theme="vk"] .admin-icon-btn,
[data-theme="vk"] .admin-icon-passive,
[data-theme="vk"] .settings-soft-btn,
[data-theme="vk"] .btn-ghost,
[data-theme="vk"] .sv-btn,
[data-theme="vk"] .fp-btn{
  background:#f0f2f5!important;
  color:#2c2d2e!important;
  border-color:#dce1e6!important;
}
[data-theme="vk"] .vs-btn:hover,
[data-theme="vk"] .vb-btn:hover,
[data-theme="vk"] .sv-btn:hover,
[data-theme="vk"] .fp-btn:hover,
[data-theme="vk"] .settings-soft-btn:hover{
  background:#e5ebf1!important;
  color:#1f2328!important;
}
[data-theme="vk"] .vs-btn.active,
[data-theme="vk"] .vb-btn.active,
[data-theme="vk"] .vs-tile-btn.is-active,
[data-theme="vk"] .vs-tile-btn.live.is-active{
  background:#e5f2ff!important;
  color:#0077ff!important;
  border-color:#99c8ff!important;
}
[data-theme="vk"] .vs-tile-pill.live,
[data-theme="vk"] .vs-tile-btn.live,
[data-theme="vk"] .vp-stream-btn{
  background:#fff0f0!important;
  color:#c33c3c!important;
  border-color:#efb8b8!important;
}
[data-theme="vk"] .fi,
[data-theme="vk"] input,
[data-theme="vk"] textarea,
[data-theme="vk"] select,
[data-theme="vk"] .msg-textarea,
[data-theme="vk"] .dm-search-inp{
  background:#ffffff!important;
  color:#1f2328!important;
  border-color:#dce1e6!important;
}
[data-theme="vk"] .fi::placeholder,
[data-theme="vk"] textarea::placeholder,
[data-theme="vk"] input::placeholder{
  color:#97a3af!important;
}
[data-theme="vk"] select.fi,
[data-theme="vk"] select.fi option,
[data-theme="vk"] select.fi optgroup{
  color-scheme:light!important;
  background:#ffffff!important;
  color:#1f2328!important;
}
[data-theme="vk"] .msg-bubble,
[data-theme="vk"] .msg-content,
[data-theme="vk"] .dm-msg,
[data-theme="vk"] .comment-body,
[data-theme="vk"] .post-card{
  background:transparent!important;
  color:#1f2328!important;
}
[data-theme="vk"] .msg-text,
[data-theme="vk"] .dm-msg-text,
[data-theme="vk"] .comment-text{
  color:#1f2328!important;
}
[data-theme="vk"] .srv-icon-inner,
[data-theme="vk"] .vs-av,
[data-theme="vk"] .vs-tile-av,
[data-theme="vk"] .vp-av,
[data-theme="vk"] .vu-av,
[data-theme="vk"] .msg-av,
[data-theme="vk"] .dm-av,
[data-theme="vk"] .mb-av,
[data-theme="vk"] .settings-avatar,
[data-theme="vk"] .admin-user-avatar{
  background:#f0f2f5!important;
  color:#2c2d2e!important;
  border-color:#dce1e6!important;
}
[data-theme="vk"] .sv-header,
[data-theme="vk"] .fp-header{
  background:rgba(255,255,255,.86)!important;
  color:#1f2328!important;
  border-color:#dce1e6!important;
}
[data-theme="vk"] .sv-title,
[data-theme="vk"] .fp-title{
  color:#1f2328!important;
}
@media(max-width:740px){
  [data-theme="vk"] #streamViewer .sv-header{
    background:rgba(255,255,255,.82)!important;
    color:#1f2328!important;
    border-color:#dce1e6!important;
  }
}



/* PATCH 17: reliable server settings click after voice-stage/channel switching */
#chSidebar,
#chSidePanel,
#sidebarHeader{
  pointer-events:auto!important;
}
#sidebarHeader{
  position:relative!important;
  z-index:25!important;
  touch-action:manipulation!important;
}
#sidebarHeader .gear-icon,
#srvAdminBtn{
  pointer-events:auto!important;
}
/* Voice workspace must never cover the channel sidebar/header hitbox. */
#voiceStage{
  pointer-events:auto;
}
@media(min-width:741px){
  #chSidebar{position:relative!important;z-index:30!important;}
  #mainArea{position:relative!important;z-index:1!important;}
}


/* PATCH 17: mobile stream avatar clip — remove square cutoff under round avatars */
#voiceStage.has-stream .voice-stage-grid-wrap,
#voiceStage.has-stream .voice-stage-grid,
#voiceStage.has-stream .vs-tile-avatar{
  background:transparent!important;
}
#voiceStage.has-stream .vs-tile,
#voiceStage.has-stream .vs-tile.featured{
  contain:paint;
}
#voiceStage.has-stream .vs-tile-avatar,
#voiceStage.has-stream .vs-tile-avatar .vs-tile-av{
  border-radius:50%!important;
  overflow:hidden!important;
  isolation:isolate!important;
  background-clip:padding-box!important;
  -webkit-mask-image:-webkit-radial-gradient(white, black)!important;
  clip-path:circle(50% at 50% 50%)!important;
}
#voiceStage.has-stream .vs-tile-avatar .vs-tile-av img{
  width:100%!important;
  height:100%!important;
  display:block!important;
  object-fit:cover!important;
  border-radius:50%!important;
  overflow:hidden!important;
  -webkit-transform:translateZ(0)!important;
  transform:translateZ(0)!important;
  background:transparent!important;
}

/* ── Иконка сервера в приглашении: когда есть картинка, ведём себя как .srv-icon в списке ──
   Убираем фиолетовую подложку-градиент и рамку, картинка заполняет всю скруглённую плашку
   (object-fit:cover, без padding). Это устраняет «рамку» по периметру логотипа. */
.invite-server-icon.has-img,
.invite-welcome-icon.has-img,
.chat-invite-icon.has-img,
#loginScreen.has-invite .invite-server-icon.has-img,
#loginScreen .invite-welcome-icon.has-img{
  background:transparent!important;
  border:none!important;
}
.invite-server-icon.has-img img,
.invite-welcome-icon.has-img img,
.chat-invite-icon.has-img img,
#loginScreen.has-invite .invite-server-icon.has-img img,
#loginScreen .invite-welcome-icon.has-img img{
  width:100%!important;
  height:100%!important;
  object-fit:cover!important;
  object-position:center center!important;
  padding:0!important;
  background:transparent!important;
  border:none!important;
  box-shadow:none!important;
  border-radius:inherit!important;
  display:block!important;
}

/* ── Нижний бар серверов (мобильный): сплошной фон по всей видимой ширине ──
   Бар горизонтально прокручивается, поэтому обычный фон рисуется лишь на ширину вьюпорта
   и «обрывается» при скролле. Кладём фон отдельным фиксированным слоем под иконки. */
@media(max-width:768px){
  #serverBar{
    background:transparent!important;
    background-image:none!important;
    border-top:none!important;
  }
  #serverBar::before{
    content:'';
    position:fixed;
    left:0;right:0;
    bottom:0;
    height:calc(74px + var(--safe-bottom,0px));
    background:var(--bg1);
    border-top:1px solid var(--border, rgba(255,255,255,.06));
    z-index:0;
    pointer-events:none;
  }
  #serverBar > *{position:relative;z-index:1}
}
/* ===== ПОЛЕ ВВОДА — «ОСТРОВ» НАД ЛЕНТОЙ =====
   Обёртки #inputOuter / #dmInputOuter всегда полностью прозрачны во ВСЕХ
   темах (discord, truecolor, vk и др.) и при любом состоянии эффектов:
   убираем подложку (фон, рамку, тень, размытие, псевдо-слой), которую
   раньше навешивали тематические правила. Видимым «стеклянным островом»
   остаётся только .input-box. Высокая специфичность + html-префикс,
   чтобы перебить любые тематические !important-правила. */
html body #inputOuter,html body #dmInputOuter,
html body.dynbg-off #inputOuter,html body.dynbg-off #dmInputOuter,
html body[data-theme] #inputOuter,html body[data-theme] #dmInputOuter,
html[data-theme] body #inputOuter,html[data-theme] body #dmInputOuter,
html[data-theme] body.dynbg-off #inputOuter,html[data-theme] body.dynbg-off #dmInputOuter{
  background:transparent!important;
  background-image:none!important;
  border:none!important;
  box-shadow:none!important;
  backdrop-filter:none!important;
  -webkit-backdrop-filter:none!important;
}
html body #inputOuter::before,html body #dmInputOuter::before,
html[data-theme] body #inputOuter::before,html[data-theme] body #dmInputOuter::before{
  display:none!important;content:none!important;
}
/* Полоска «печатает…» тоже без сплошного фона во всех темах/состояниях —
   именно её непрозрачный фон создавал тёмную «подложку» над островом. */
html body #typingBar,html body.dynbg-off #typingBar,
html body[data-theme] #typingBar,html[data-theme] body #typingBar,
html[data-theme] body.dynbg-off #typingBar{
  background:transparent!important;background-image:none!important;
}
/* Пункты «Динамический фон» и «Живая анимация» показываем только в
   обычном (десктопном) виде. В мобильном они скрыты, а эффекты там
   принудительно выключены логикой инициализации. Порог 980px совпадает
   с isMobileLike(). */
@media(max-width:980px){
  .settings-effects-only{display:none!important}
}

</style>
</head>
<body data-theme="<?= htmlspecialchars(DEFAULT_THEME, ENT_QUOTES, 'UTF-8') ?>">
<!-- DYNAMIC BACKGROUND (Apple-style drifting blobs; toggle in settings) -->
<div id="dynBg" aria-hidden="true">
  <span class="dyn-blob db1"></span>
  <span class="dyn-blob db2"></span>
  <span class="dyn-blob db3"></span>
  <span class="dyn-blob db4"></span>
</div>
<div id="voiceGlow" aria-hidden="true">
  <span class="vglow vglow-top"></span>
  <span class="vglow vglow-bottom"></span>
</div>
<!-- FIRST VISIT LOADER -->
<div id="firstVisitLoader" aria-live="polite" aria-label="Загрузка TrueCord">
  <div class="fvl-card">
    <div class="fvl-logo-wrap">
      <div class="fvl-ring"></div>
      <img class="fvl-logo" src="icon_tC_main.png" alt="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="fvl-title"><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></div>
    <div class="fvl-sub" data-i18n="server.preparing">Подготавливаем пространство общения…</div>
    <div class="fvl-bar"></div>
  </div>
</div>

<!-- LOGIN -->
<div id="loginScreen">
<div class="login-wrap">
  <div class="login-info" id="loginInfoPanel">
    <div class="login-brand">
      <img src="icon_tC_main.png" alt="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>">
      <div class="login-brand-text">
        <h1><?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></h1>
      </div>
    </div>
    <p class="tagline" data-i18n="auth.tagline"><?= htmlspecialchars(SITE_TAGLINE, ENT_QUOTES, 'UTF-8') ?></p>
    <div id="inviteWelcomeCard" class="invite-welcome-card">
      <div class="invite-welcome-top">
        <div class="invite-welcome-icon" id="inviteWelcomeIcon"><span class="ti ti-lg" data-ti="castle"></span></div>
        <div class="invite-welcome-copy">
          <div class="invite-kicker" data-i18n="auth.inviteToServer">Вас приглашают на сервер</div>
          <div class="invite-server-name" id="inviteWelcomeName" data-i18n="server.community">Сервер сообщества</div>
          <div class="invite-server-meta" id="inviteWelcomeMeta" data-i18n="auth.inviteLoginPrompt">Войдите или зарегистрируйтесь, чтобы принять приглашение и сразу попасть на сервер.</div>
        </div>
      </div>
      <div class="invite-welcome-grid">
        <div class="invite-stat-card">
          <div class="invite-stat-label" data-i18n="common.members">Участники</div>
          <div class="invite-stat-value" id="inviteWelcomeMembers">0</div>
          <div class="invite-stat-sub" data-i18n="server.alreadyMember">Уже в этом сообществе</div>
        </div>
        <div class="invite-stat-card">
          <div class="invite-stat-label" data-i18n="server.invitedBy">Пригласил</div>
          <div class="invite-stat-value" id="inviteWelcomeInviter">—</div>
          <div class="invite-stat-sub" data-i18n="server.personalInvite">Личный инвайт на сервер</div>
        </div>
      </div>
      <div class="invite-welcome-actions">
        <button class="btn btn-gold" onclick="setTab('login');focusAuthField('lName')" data-i18n="common.login">Войти</button>
        <button class="btn btn-ghost" onclick="setTab('reg');focusAuthField('rName')" data-i18n="common.register">Регистрация</button>
      </div>
    </div>
    <div class="info-block">
      <h3 data-i18n="nav.about">О платформе</h3>
      <p><?= htmlspecialchars(SITE_DESCRIPTION, ENT_QUOTES, 'UTF-8') ?></p>
      <div class="info-links"><a href="#" onclick="showTermsModal();return false"><span class="ti ti-sm" data-ti="scroll"></span> <span data-i18n="ui.offer">Оферта</span></a></div>
    </div>
    <div class="info-block">
      <h3 data-i18n="nav.aboutTitle">Основные возможности</h3>
      <p><span class="ti ti-sm" data-ti="megaphone"></span> <span data-i18n="nav.serversAndChannels">Серверы и каналы</span> · <span class="ti ti-sm" data-ti="chat"></span> <span data-i18n="nav.dm">Личные сообщения</span> · <span class="ti ti-sm" data-ti="phone"></span> <span data-i18n="call.audioVideo">Аудио и видео звонки</span> · <span class="ti ti-sm" data-ti="speaker"></span> <span data-i18n="voice.voiceRooms">Голосовые комнаты</span> · <span class="ti ti-sm" data-ti="reaction"></span> <span data-i18n="ui.reactions">Реакции</span> · <span class="ti ti-sm" data-ti="attach"></span> <span data-i18n="ui.fileSharing">Обмен файлами</span></p>
    </div>
    <div class="privacy-note"><span class="ti ti-sm" data-ti="sparkles"></span> <span data-i18n="auth.tagline">Всё необходимое для комфортного общения, совместной работы и сообщества собрано в одном месте.</span></div>
  </div>
  <div class="login-form">
    <div id="inviteAuthNote" class="invite-auth-note" style="display:none"></div>
    <div class="login-tabs">
      <div class="login-tab active" onclick="setTab('login')" data-i18n="common.enter">Вход</div>
      <div class="login-tab" onclick="setTab('reg')" data-i18n="common.register">Регистрация</div>
    </div>
    <div id="fLogin">
      <div class="fg"><label class="fl" data-i18n="auth.username">Имя пользователя</label><input class="fi" id="lName" placeholder="Введите имя пользователя" data-i18n-ph="auth.enterUsername" autocomplete="username"></div>
 <div class="fg"><label class="fl" data-i18n="auth.password">Пароль</label><input class="fi" type="password" id="lPass" placeholder="••••••••" autocomplete="current-password"></div>
<label class="remember-check">
  <input type="checkbox" id="rememberMeCheck" checked>
  <span>
    <span data-i18n="auth.rememberMe">Запомнить меня</span>
    <span class="rc-hint"><span data-i18n="ui.statusOnClose">При закрытии браузера статус сменится на</span> «<span data-i18n="presence.invisible">Невидимый</span>»</span>
  </span>
</label>
<div class="err-msg" id="lErr"></div>
<button class="btn btn-gold btn-full" onclick="doLogin()" data-i18n="common.login">Войти</button>
    </div>
    <div id="fReg" style="display:none">
      <div class="fg"><label class="fl" data-i18n="auth.username">Имя пользователя</label><input class="fi" id="rName" placeholder="Минимум 4 символа" data-i18n-ph="auth.min4" autocomplete="username"><div class="fi-hint">4–<?= USERNAME_MAX_LEN ?> <span data-i18n="auth.usernameHint">символов: буквы, цифры,</span> _, -, <span data-i18n="auth.dot">точка</span></div></div>
      <div class="fg"><label class="fl" data-i18n="auth.password">Пароль</label><input class="fi" type="password" id="rPass" placeholder="Минимум 8 символов" data-i18n-ph="auth.min8" autocomplete="new-password" oninput="updatePasswordStrength(this.value)"></div>
      <div class="pw-strength" id="pwStrength">
        <div class="pw-strength-bar"><div class="pw-strength-fill" id="pwStrengthFill"></div></div>
        <div class="pw-strength-text" id="pwStrengthText" data-i18n="auth.enterPassword">Введите пароль: минимум 8 символов, буквы и цифры.</div>
      </div>
      <?php $showTerms = defined('REQUIRE_TERMS_ACCEPTANCE') ? REQUIRE_TERMS_ACCEPTANCE : true; ?>
      <div style="margin-bottom:12px;<?= $showTerms ? '' : 'display:none' ?>" id="regTermsWrap">
        <div class="terms-scroll" id="regTermsText" data-i18n-html="legal.termsHtml"><?= REGISTRATION_TERMS_HTML ?></div>
        <label class="terms-check" id="termsCheckLabel">
          <input type="checkbox" id="termsCheckbox" <?= $showTerms ? '' : 'checked' ?>>
          <span data-i18n="legal.checkboxLabel"><?= htmlspecialchars(TERMS_CHECKBOX_LABEL, ENT_QUOTES, 'UTF-8') ?></span>
        </label>
      </div>
      <div class="err-msg" id="rErr"></div>
      <button class="btn btn-gold btn-full" onclick="doRegister()" data-i18n="auth.createAccount">Создать аккаунт</button>
    </div>
    <div class="login-lang-row login-lang-bottom">
      <label class="fl" for="loginLangSelect" data-i18n="lang.language">Язык</label>
      <select class="fi login-lang-select" id="loginLangSelect" data-lang-selector onchange="setLangLive(this.value)">
        <option value="auto" data-i18n="lang.auto">Автоматически</option>
        <option value="en">English</option>
        <option value="ru">Русский</option>
        <option value="de">Deutsch</option>
        <option value="fr">Français</option>
      </select>
    </div>
  </div>
</div>
</div>

<!-- DM CALL OVERLAY -->
<div id="dmCallOverlay">
  <div class="call-avatar" id="callAvatar"><span class="ti ti-xl" data-ti="user"></span></div>
  <div class="call-name" id="callName">—</div>
  <div class="call-status" id="callStatus">Входящий звонок…</div>
  <div class="call-btns" id="callBtns">
    <button class="call-btn call-btn-accept" onclick="dmCallAccept()"><span class="ti ti-lg"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.06 6.06l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span></button>
    <button class="call-btn call-btn-reject" onclick="dmCallReject()"><span class="ti ti-lg" data-ti="phoneOff"></span></button>
  </div>
</div>

<!-- DROP ZONE -->
<div id="dropZone"><div style="font-size:48px;display:flex;justify-content:center"><span class="ti" style="display:flex"><svg viewBox="0 0 24 24" width="48" height="48"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg></span></div><div>Отпустите файлы для загрузки</div></div>

<!-- REACTION TOOLTIP -->
<div class="r-who-tooltip" id="rWhoTooltip"></div>

<!-- MEDIA PLAYER MODAL -->
<div id="mediaPlayerModal" onclick="if(event.target===this)closeMediaPlayer()">
  <div class="media-player-wrap">
    <div class="media-player-header">
      <div class="media-player-title" id="mediaPlayerTitle">Медиа</div>
      <button class="modal-close" style="position:relative;top:0;right:0;flex-shrink:0" onclick="closeMediaPlayer()"><span class="ti"><svg viewBox="0 0 24 24" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></button>
    </div>
    <div class="media-player-content" id="mediaPlayerContent"></div>
    <div class="media-player-nav" id="mediaPlayerNav"></div>
  </div>
</div>
<!-- MINI-APP MODAL -->
<!-- MINI-APP WINDOWS CONTAINER -->
<div id="miniAppContainer"></div>

<!-- FLOATING PLAYER (modern) -->
<div id="floatingPlayer">
  <!-- Northern Lights aurora -->
  <div id="fpAurora"><canvas id="fpAuroraCanvas"></canvas></div>
  <!-- Header -->
  <div class="fp-header" id="fpHeader">
    <span class="fp-icon"><svg viewBox="0 0 24 24"><path d="M9 18V5l12-2v13" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></span>
    <span class="fp-label-text">Плеер</span>
    <div class="fp-track-mini">
      <span class="fp-track-mini-name" id="fpMiniName">—</span>
    </div>
    <div class="fp-mini-controls">
      <button class="fp-mini-btn" onclick="fpPrev();event.stopPropagation()" data-i18n-title="common.prev.short" title="Пред."><svg viewBox="0 0 24 24"><polygon points="19 20 9 12 19 4 19 20" fill="currentColor"/><line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
      <button class="fp-mini-btn fp-mini-play" id="fpMiniPlayBtn" onclick="fpTogglePlay();event.stopPropagation()"><svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg></button>
      <button class="fp-mini-btn" onclick="fpNext();event.stopPropagation()" data-i18n-title="common.next.short" title="След."><svg viewBox="0 0 24 24"><polygon points="5 4 15 12 5 20 5 4" fill="currentColor"/><line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
    </div>
    <div class="fp-header-btns">
      <button class="fp-hbtn" id="fpMinBtn" onclick="fpToggleMin();event.stopPropagation()" data-i18n-title="common.minimize" title="Свернуть"><svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
      <button class="fp-hbtn" onclick="closeFloatingPlayer();event.stopPropagation()" data-i18n-title="common.close" title="Закрыть"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    </div>
  </div>
  <!-- Art + Track info -->
  <div class="fp-art-row">
    <div class="fp-art">
      <div class="fp-disc" id="fpDisc" style="color:rgba(140,210,255,0.6)"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" opacity=".4"/><circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="1.5" fill="none" opacity=".6"/><circle cx="12" cy="12" r="2" fill="currentColor"/></svg></div>
    </div>
    <div class="fp-track-info">
      <div class="fp-track-name" id="fpTrackName">—</div>
      <div class="fp-track-sub" id="fpQueueInfo"></div>
    </div>
    <button class="fp-dl-btn" id="fpDlBtn" onclick="fpDownloadCurrent()" data-i18n-title="common.download" title="Скачать"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
  </div>
  <!-- Progress -->
  <div class="fp-prog-wrap">
    <div class="fp-time-row">
      <span id="fpCurrentTime">0:00</span>
      <span id="fpDuration">0:00</span>
    </div>
    <div class="fp-progress" id="fpProgress" onclick="fpSeek(event)">
      <div class="fp-progress-bar" id="fpProgressBar"></div>
    </div>
  </div>
  <!-- Controls -->
  <div class="fp-controls">
    <button class="fp-btn" id="fpShuffleBtn" onclick="fpToggleShuffle()" data-i18n-title="common.shuffle" title="Перемешать"><svg viewBox="0 0 24 24"><polyline points="16 3 21 3 21 8" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="4" y1="20" x2="21" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><polyline points="21 16 21 21 16 21" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="15" y1="15" x2="21" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="4" y1="4" x2="9" y2="9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
    <button class="fp-btn" onclick="fpPrev()" data-i18n-title="common.previous" title="Предыдущий"><svg viewBox="0 0 24 24"><polygon points="19 20 9 12 19 4 19 20" fill="currentColor"/><line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
    <button class="fp-btn fp-play" id="fpPlayBtn" onclick="fpTogglePlay()"><svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg></button>
    <button class="fp-btn" onclick="fpNext()" data-i18n-title="common.next" title="Следующий"><svg viewBox="0 0 24 24"><polygon points="5 4 15 12 5 20 5 4" fill="currentColor"/><line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
    <button class="fp-btn" id="fpRepeatBtn" onclick="fpToggleRepeat()" data-i18n-title="common.repeat" title="Повтор"><svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 11V9a4 4 0 0 1 4-4h14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><polyline points="7 23 3 19 7 15" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 13v2a4 4 0 0 1-4 4H3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></button>
  </div>
  <!-- Volume -->
  <div class="fp-vol-row">
    <span class="fp-vol-icon" id="fpMuteBtn" onclick="fpToggleMute()" data-i18n-title="common.sound" title="Звук"><svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span>
    <input type="range" class="fp-vol-slider" id="fpVolSlider" min="0" max="100" value="100" oninput="fpSetVolume(this.value)">
    <span class="fp-vol-pct" id="fpVolLabel">100%</span>
  </div>
</div>

<!-- STREAM VIEWER -->
<div id="streamViewer">
  <div class="sv-header" id="svHeader">
    <div class="sv-live-dot"></div>
    <span class="sv-title" id="svTitle">Трансляция</span>
    <button class="sv-mini-leave" onclick="closeStreamViewer();event.stopPropagation()" data-i18n-title="common.exit" title="Выйти"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span> Выйти</button>
    <button class="sv-btn sv-min-btn" id="svMinBtn" onclick="svToggleMin();event.stopPropagation()" data-i18n-title="common.minimize" title="Свернуть">─</button>
    <button class="sv-btn" id="svFullscreenBtn" onclick="svToggleFullscreen();event.stopPropagation()" title="На весь экран" aria-label="На весь экран"><span class="ti"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></span></button>
    <button class="sv-btn" onclick="hideStreamViewerWindow();event.stopPropagation()" title="Закрыть окно, не останавливая просмотр"><span class="ti"><svg viewBox="0 0 24 24" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></button>
  </div>
  <div class="sv-video-wrap" id="svVideoWrap">
    <div class="sv-no-stream" id="svNoStream">
      <span style="font-size:32px">🖥</span>
      <span>Трансляция недоступна</span>
    </div>
    <div class="sv-audio-controls" id="svAudioControls" aria-label="Громкость трансляции">
      <button class="sv-audio-btn" id="svMuteBtn" onclick="svToggleMute();event.stopPropagation()" title="Отключить звук трансляции" aria-label="Отключить звук трансляции"><svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></button>
      <input class="sv-audio-slider" id="svVolSlider" type="range" min="0" max="100" value="100" oninput="svSetVolume(this.value);event.stopPropagation()" onclick="event.stopPropagation()" title="Громкость трансляции" aria-label="Громкость трансляции">
      <span class="sv-audio-label" id="svVolLabel">100%</span>
    </div>
    <div class="sv-reactions" id="svReactions" aria-label="Реакции на трансляцию">
      <button class="sv-reaction-btn" onclick="svSendReaction('🔥');event.stopPropagation()" title="Огонь">🔥</button>
      <button class="sv-reaction-btn" onclick="svSendReaction('😂');event.stopPropagation()" title="Смешно">😂</button>
      <button class="sv-reaction-btn" onclick="svSendReaction('👏');event.stopPropagation()" title="Аплодисменты">👏</button>
      <button class="sv-reaction-btn" onclick="svSendReaction('❤️');event.stopPropagation()" title="Нравится">❤️</button>
      <button class="sv-reaction-btn" onclick="svSendReaction('😮');event.stopPropagation()" title="Вау">😮</button>
    </div>
  </div>
</div>


<!-- APP -->
<div id="app" style="display:none">
<div id="mobileOverlay" onclick="closeOverlays()"></div>
<div id="mentionSuggest"></div>

<!-- Server bar -->
<div id="serverBar">
  <div class="srv-icon" id="dmSrvBtn" onclick="openDmMode()" title="Личные сообщения" data-i18n-title="nav.dm">
    <div class="srv-indicator"></div>
    <div class="srv-icon-inner" id="dmSrvIconInner" data-ti="msg" style="display:flex;align-items:center;justify-content:center;font-size:22px"></div>
    <div class="srv-notif-dot" id="dmBadgeDot"></div>
  </div>
  <div id="superAdminSrvBtn" style="display:none" title="Панель суперадмина" onclick="showAdminPanel()"></div>
  <div id="srvAdminBtn" style="display:none" title="Управление сервером"></div>
  <div class="srv-sep"></div>
  <div id="srvIcons"></div>
</div>

<!-- Channel sidebar -->
<div id="chSidebar">
  <div id="dmSidePanel" style="display:none;flex:1;flex-direction:column;overflow:hidden">
    <div style="min-height:var(--hdr-h);padding:0 12px;display:flex;align-items:center;background:var(--bg1);border-bottom:1px solid var(--border);flex-shrink:0">
      <span style="font-weight:700;font-size:14px;color:var(--gold);font-family:var(--font-heading)" data-i18n="nav.dm">Личные сообщения</span>
    </div>
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
      <div class="dm-search-box"><input class="dm-search-inp" placeholder="Найти…" data-i18n-ph="nav.findShort" id="dmSearchInp" oninput="filterDM(this.value)"></div>
      <button class="dm-new-btn" onclick="showNewDmModal()" style="display:flex;align-items:center;gap:6px;justify-content:center"><span class="ti ti-sm"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span><span data-i18n="nav.newMessage">Новое сообщение</span></button>
      <div class="dm-section" data-i18n="nav.conversations">Разговоры</div>
      <div class="dm-scroll" id="dmList"></div>
    </div>
  </div>
  <div id="chSidePanel" style="flex:1;display:flex;flex-direction:column;overflow:hidden">
    <div id="sidebarHeader" onclick="showServerSettings()">
      <span class="srv-title" id="srvTitle">trueCORD</span>
      <span class="gear-icon" data-ti="gear" style="display:flex;align-items:center;justify-content:center;width:18px;height:18px"></span>
    </div>
    <div class="ch-scroll" id="chList"></div>
  </div>
<div id="voiceBar">
    <div class="vb-main">
      <div class="vb-room">
        <div class="vb-room-name" id="vbRoomName"><span class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><path d="M3 18v-6a9 9 0 0 1 18 0v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></span> <span data-i18n="voice.voiceChat">Голосовой чат</span></div>
        <div class="vb-room-status" id="vbRoomStatus" data-i18n="voice.connected">Подключено</div>
      </div>
      <div class="vb-btns">
        <button class="vb-btn" id="vbMuteBtn" onclick="voiceToggleMute()" title="Микрофон" data-i18n-title="voice.mic"><span class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span></button>
        <button class="vb-btn" id="vbSpkBtn" onclick="voiceToggleSpeaker()" title="Звук" data-i18n-title="voice.sound"><span class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span></button>
       <button class="vb-btn" id="vbStreamBtn" onclick="voiceStartStream()" title="Трансляция экрана" data-i18n-title="voice.screenShare">
  <span id="vbStreamIcon" class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
</button>
        <button class="vb-btn vb-leave" onclick="voiceLeave()" title="Покинуть" data-i18n-title="voice.leave"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></button>
      </div>
    </div>
    <div class="vb-volume">
      <span><svg viewBox="0 0 24 24" width="11" height="11"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span>
      <input type="range" id="vbVolSlider" min="0" max="100" value="100" oninput="voiceSetVolume(this.value)">
      <span><svg viewBox="0 0 24 24" width="11" height="11"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span>
    </div>
  </div>

  <div id="userPanel">
    <div class="up-avatar" id="upAvWrap" onclick="toggleStatusPicker(event)" title="Статус" data-i18n-title="common.status">
      <div class="up-avatar-inner" id="upAv"><span class="ti" data-ti="user"></span></div>
      <div class="up-status-dot" id="upStatusDot"></div>
      <div class="status-picker" id="statusPicker">
        <div class="sp-item active" onclick="setMyStatus('online')"><div class="sp-dot st-online"></div><span data-i18n="presence.online">В сети</span></div>
        <div class="sp-item" onclick="setMyStatus('away')"><div class="sp-dot st-away"></div><span data-i18n="presence.idle">Отошёл</span></div>
        <div class="sp-item" onclick="setMyStatus('dnd')"><div class="sp-dot st-dnd"></div><span data-i18n="presence.dnd">Не беспокоить</span></div>
        <div class="sp-item" onclick="setMyStatus('invisible')"><div class="sp-dot st-invisible"></div><span data-i18n="presence.invisible">Невидимый</span></div>
      </div>
    </div>
    <div class="up-info">
      <div class="up-name" id="upName">—</div>
      <div class="up-tag" id="upStatusLabel" onclick="toggleStatusPicker(event)">В сети</div>
    </div>
    <div class="up-btns">
      <button class="up-notif-btn" id="notifBellBtn" onclick="toggleNotifPanel(event)" title="Уведомления" data-i18n-title="common.notifications">
        <span class="ti" style="display:inline-flex"><svg viewBox="0 0 24 24" width="18" height="18"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span><span class="up-notif-badge" id="notifBadge"></span>
      </button>
      <button class="icon-btn" onclick="showMyProfile()" title="Профиль" data-i18n-title="common.profile" data-ti="gear"></button>
      <button class="icon-btn" onclick="robustLogout(event)" title="Выйти" data-i18n-title="settings.logout" data-ti="logout"></button>
    </div>
  </div>
</div>

<!-- Main area -->
<div id="mainArea">
  <div id="mainHeader">
    <button class="icon-btn mobile-only" onclick="toggleChSidebar()" data-ti="menu" style="font-size:18px"></button>
    <div id="hdrIcon" class="hdr-ch-icon"><span class="ti" data-ti="chat"></span></div>
    <div id="hdrName" class="hdr-ch-name">—</div>
    <div id="hdrTopic" class="hdr-topic"></div>
    <div class="hdr-actions">
      <button class="icon-btn" id="hdrCallBtn" onclick="startDmCall()" title="Звонок" data-ti="phone" style="display:none"></button>
      <button class="icon-btn" id="hdrVideoCallBtn" onclick="startDmVideoCall()" title="Видеозвонок" data-ti="video" style="display:none"></button>
      <button class="icon-btn" id="hdrDmVideoReturnBtn" onclick="showDmVideoWindow()" title="Открыть окно видеозвонка" data-ti="expand" style="display:none"></button>
      <button class="icon-btn" id="hdrDmPrivacyBtn" onclick="showDmPrivacyForCurrent()" title="Приватность ЛС и звонков" data-ti="gear" style="display:none"></button>
      <button class="icon-btn" id="hdrDmClearBtn" onclick="showDmClearModal()" title="Очистить ЛС до даты" data-ti="trash" style="display:none"></button>
      <button class="icon-btn" id="hdrCopyLink" onclick="copyChannelLink()" title="Ссылка" data-ti="link" style="display:none"></button>
      <button class="icon-btn" id="memberToggleBtn" onclick="toggleMemberSidebar()" title="Участники" data-i18n-title="common.members"><span id="memberCountBadge"></span></button>

    </div>
  </div>
  <div id="dmCallBar">
    <span class="ti"><svg viewBox="0 0 24 24" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.06 6.06l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span>
    <span class="dcb-name" id="dcbName">Звонок</span>
    <span class="dcb-timer" id="dcbTimer">00:00</span>
    <button class="dcb-btn dcb-btn-mute" id="dcbMuteBtn" onclick="dmCallToggleMute()" style="display:inline-flex;align-items:center;gap:5px"><span class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span> Вкл</button>
    <button class="dcb-btn dcb-btn-video" id="dcbVideoBtn" onclick="showDmVideoWindow()" title="Открыть видео" style="display:none;align-items:center;gap:5px"><span class="ti"><svg viewBox="0 0 24 24" width="16" height="16"><rect x="2" y="4" width="15" height="16" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M17 9l5-3v12l-5-3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span> Видео</button>
    <button class="dcb-btn dcb-btn-end" onclick="dmCallHangup()" style="display:inline-flex;align-items:center;gap:5px"><span class="ti"><svg viewBox="0 0 24 24" width="18" height="18"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.44-2.85M5.28 10.2A19.44 19.44 0 0 1 3.03 6a2 2 0 0 1 1.97-2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.91 11.9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="23" y1="1" x2="1" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span> Завершить</button>
  </div>
  <div id="welcomeScreen">
    <div class="ws-icon"><span class="ti" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center"><svg viewBox="0 0 24 24" width="24" height="24"><line x1="5" y1="19" x2="19" y2="5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="15 5 19 5 19 9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="5" y1="15" x2="8" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></div>
    <h2><span data-i18n="auth.welcomeTo">Добро пожаловать в</span> <?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?></h2>
    <p data-i18n="server.emptyServers">Серверы появятся здесь после вступления по приглашению. Также можно открыть личные сообщения.</p><button class="btn btn-gold" onclick="showJoinInviteModal()" style="margin-top:14px" data-i18n="nav.joinByInvite">Вступить по приглашению</button>
  </div>
  <div id="joinServerScreen">
    <div class="join-srv-icon" id="joinSrvIcon"><span class="ti" style="display:flex;align-items:center;justify-content:center"><svg viewBox="0 0 24 24" width="28" height="28"><path d="M3 21V9l3-3h3V3h2v3h2V3h2v3h3l3 3v12H3z" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linejoin="round"/><rect x="9" y="15" width="6" height="6" stroke="currentColor" stroke-width="1.6" fill="none"/></svg></span></div>
    <div class="join-srv-name" id="joinSrvName">Сервер</div>
    <div class="join-srv-desc" id="joinSrvDesc">Вы ещё не вступили на этот сервер</div>
    <button class="btn btn-gold" onclick="doJoinCurrentServer()" id="joinSrvBtn" style="display:inline-flex;align-items:center;gap:7px;justify-content:center"><span class="ti"><svg viewBox="0 0 24 24" width="14" height="14"><line x1="5" y1="19" x2="19" y2="5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="15 5 19 5 19 9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="5" y1="15" x2="8" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span> Вступить на сервер</button>
  </div>
  <div id="chView" style="display:none;flex:1;flex-direction:column;overflow:hidden;min-height:0">
    <div id="chBanner"></div>
    <div id="messagesWrap"><div id="messagesList"></div></div>
    <div id="typingBar"></div>
    <div id="inputOuter">
      <div class="upload-progress-wrap" id="uploadProgressWrap"><div class="upload-progress-bar" id="uploadProgressBar"></div></div>
      <div id="pendingFilesWrap" class="pending-files-wrap"></div>
      <div id="replyBar">
        <span>↩ Ответ <strong id="replyToName"></strong>: <em id="replyPreview" style="color:var(--text3)"></em></span>
        <span class="rply-cancel" onclick="cancelReply()" style="display:flex;align-items:center"><span class="ti"><svg viewBox="0 0 24 24" width="12" height="12"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></span>
      </div>
      <div class="input-box">
        <button class="inp-btn" onclick="triggerUpload('msgFile')" title="Прикрепить"><span class="ti" data-ti="attach"></span></button>
        <input type="file" id="msgFile" accept="image/*,video/*,audio/*,.zip,.rar,.7z,.tar,.gz,.bz2,.mp3,.ogg,.wav,.flac,.txt,.md,.json,.log,.csv" multiple style="display:none" onchange="handleMultiUpload(this,'channel')">
        <textarea id="msgInput" class="msg-textarea" rows="1" placeholder="Написать сообщение…" data-i18n-ph="nav.writeMessage" onkeydown="onMsgKey(event)" oninput="onMsgInput(this)"></textarea>
        <button class="inp-btn" onclick="openEmojiForInput()" title="Эмодзи">😊</button>
        <button class="inp-btn" onclick="sendMsg()" title="Отправить" style="color:var(--gold)">➤</button>
      </div>
    </div>
  </div>

  <div id="voiceStage">
    <div class="voice-stage-head">
      <div class="voice-stage-title">
        <div class="vst-icon"><span class="ti"><svg viewBox="0 0 24 24" width="18" height="18"><path d="M3 18v-6a9 9 0 0 1 18 0v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></span></div>
        <div style="min-width:0"><h2 id="voiceStageName" data-i18n="voice.stageTitle">Голосовой канал</h2><p id="voiceStageStatus" data-i18n="voice.connecting">Подключение…</p></div>
      </div>
      <div class="voice-stage-actions">
        <button class="vs-btn" id="voiceWatchBtn" onclick="watchPrompt()" title="Совместный просмотр" data-i18n-title="watch.start"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><polygon points="10,7 15,10 10,13" fill="currentColor"/></svg></span><span class="lbl" data-i18n="watch.watch">Смотреть вместе</span></button>
        <button class="vs-btn" id="voiceStageFullscreenBtn" onclick="voiceStageFullscreenStream(event)" title="Открыть трансляцию во весь экран" data-i18n-title="stream.openFullscreen"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span class="lbl" data-i18n="common.fullscreen">Весь экран</span></button>
        <button class="vs-btn" id="voiceStageDetachBtn" onclick="voiceStageOpenFloatingStream()" title="Открыть трансляцию отдельным окном" data-i18n-title="voice.openSeparateWindow"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M15 3h6v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M10 14L21 3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span><span class="lbl" data-i18n="common.window">Окно</span></button>
        <button class="vs-btn danger" onclick="voiceLeave()"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span><span class="lbl" data-i18n="voice.leave">Выйти</span></button>
      </div>
    </div>
    <div class="voice-stage-body">
      <div class="voice-stage-main">
        <div class="voice-stage-grid-wrap">
          <div class="voice-stage-grid-head"><span data-i18n="voice.callGrid">Сетка созвона</span><span class="voice-stage-grid-hint" data-i18n="voice.activeSpeakerFeatured">активный участник выделяется</span></div>
          <div class="voice-stage-grid" id="voiceStageGrid"></div>
        </div>
        <div class="voice-stage-stream" id="voiceStageStream" onclick="voiceStageTapPrimary(event)">
          <div class="watch-player-wrap" id="watchPlayerWrap" style="display:none">
            <div id="watchPlayerHost"></div>
            <div class="watch-bar">
              <span class="watch-live"><span class="watch-dot"></span><span id="watchHostName" data-i18n="watch.sharedView">Совместный просмотр</span></span>
              <span class="watch-bar-actions">
                <button class="vs-watch-btn" onclick="watchSyncPull(event)" title="Синхронизировать со всеми" data-i18n-title="watch.resync"><svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-3-6.7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><polyline points="21 3 21 9 15 9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                <button class="vs-watch-btn" onclick="watchClose(true);event.stopPropagation()" title="Закрыть просмотр" data-i18n-title="watch.close"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
              </span>
            </div>
          </div>
          <div class="vs-watch-pill" id="voiceStageWatchPill"><span class="vs-live-dot"></span><span id="voiceStageWatchName" data-i18n="voice.streaming">Трансляция</span><span class="vs-watch-actions"><button class="vs-watch-btn" onclick="voiceStageFullscreenStream(event)" title="Во весь экран" aria-label="Во весь экран" data-i18n-title="stream.openFullscreen" data-i18n-aria="stream.openFullscreen"><svg viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></button><button class="vs-watch-btn" onclick="voiceStageOpenFloatingStream();event.stopPropagation()" title="Открыть окном" aria-label="Открыть окном" data-i18n-title="voice.openWindow" data-i18n-aria="voice.openWindow"><svg viewBox="0 0 24 24"><path d="M15 3h6v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M10 14L21 3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></button></span></div>
          <div class="vs-empty-stream" id="voiceStageEmpty">
            <div class="big"><span class="ti"><svg viewBox="0 0 24 24" width="34" height="34"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span></div>
            <b data-i18n="voice.opened">Голосовой канал открыт</b>
            <span data-i18n="voice.streamHint">Здесь появится трансляция. Нажмите красный индикатор у участника или запустите свою трансляцию.</span>
          </div>
        </div>
        <div class="voice-stage-controls">
          <button class="vs-btn" id="vsMuteBtn" onclick="voiceToggleMute()"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span><span data-i18n="voice.mic">Микрофон</span></button>
          <button class="vs-btn" id="vsSpkBtn" onclick="voiceToggleSpeaker()"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span><span data-i18n="voice.sound">Звук</span></button>
          <button class="vs-btn" id="vsStreamBtn" onclick="voiceStartStream()"><span class="ti"><svg viewBox="0 0 24 24" width="15" height="15"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span data-i18n="voice.streaming">Трансляция</span></button>
        </div>
      </div>
      <aside class="voice-stage-side">
        <div class="voice-stage-side-head"><span data-i18n="common.members">Участники</span><span id="voiceStageCount">0</span></div>
        <div class="voice-stage-users" id="voiceStageUsers"></div>
      </aside>
    </div>
  </div>
  <div id="dmView" style="display:none;flex:1;flex-direction:column;overflow:hidden;min-height:0">
    <div id="dmMsgWrap" style="flex:1;overflow-y:auto;overflow-x:hidden;padding:8px 0;-webkit-overflow-scrolling:touch">
      <div id="dmMsgList" style="display:flex;flex-direction:column"></div>
    </div>
    <div id="dmInputOuter" style="flex-shrink:0;padding:0 12px 12px;padding-bottom:calc(12px + var(--safe-bottom,0px));background:transparent">
      <div class="upload-progress-wrap" id="dmUploadProgressWrap"><div class="upload-progress-bar" id="dmUploadProgressBar"></div></div>
      <div id="dmPendingFilesWrap" class="pending-files-wrap"></div>
      <div class="input-box">
        <button class="inp-btn" onclick="triggerUpload('dmFile')"><span class="ti" data-ti="attach"></span></button>
        <input type="file" id="dmFile" accept="image/*,video/*,audio/*,.zip,.rar,.7z,.tar,.gz,.bz2,.mp3,.ogg,.wav,.flac,.txt,.md,.json,.log,.csv" multiple style="display:none" onchange="handleMultiUpload(this,'dm')">
        <textarea id="dmInput" class="msg-textarea" rows="1" placeholder="Написать сообщение…" data-i18n-ph="nav.writeMessage" onkeydown="onDmKey(event)" oninput="onDmInput(this)"></textarea>
        <button class="inp-btn" onclick="openEmojiForDm()">😊</button>
        <button class="inp-btn" onclick="sendDmMsg()" style="color:var(--gold)">➤</button>
      </div>
    </div>
  </div>
</div>
<div id="memberSidebar" class="collapsed">
  <div class="mb-header">Участники</div>
  <div class="mb-scroll" id="memberList"></div>
</div>
</div>

<!-- EMOJI PICKER -->
<div id="emojiPicker">
  <div class="ep-tabs" id="epTabs"></div>
  <div class="ep-grid" id="epGrid"></div>
  <div class="ep-custom">
    <input class="ep-custom-inp" id="epCustom" placeholder="✍ Свой эмодзи" maxlength="8" onkeydown="if(event.key==='Enter'){applyCustomEmoji();event.preventDefault()}">
    <button class="ep-custom-btn" onclick="applyCustomEmoji()">OK</button>
  </div>
</div>

<!-- FORMAT TOOLBAR -->
<div id="formatToolbar"></div>

<!-- CONTEXT MENU -->
<div id="ctxMenu">
  <div class="ctx-em-row" id="ctxEmRow"></div>
  <div class="ctx-item" id="ctxReply" onclick="ctxReply()" style="display:flex;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><polyline points="9 17 4 12 9 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M20 18v-2a4 4 0 0 0-4-4H4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Ответить</div>
  <div class="ctx-item" id="ctxComment" onclick="ctxComment()" style="display:flex;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><path d="M20 2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3l2.5 3 2.5-3h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>Комментировать</div>
  <div class="ctx-item" id="ctxEdit" onclick="ctxEdit()" style="display:flex;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>Редактировать</div>
  <div class="ctx-item" id="ctxMention" onclick="ctxMention()" style="display:flex;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Упомянуть</div>
  <div class="ctx-item" id="ctxForward" onclick="ctxForward()" style="display:none;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><polyline points="15 17 20 12 15 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M4 18v-2a4 4 0 0 1 4-4h12" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Переслать</div>
  <div class="ctx-item" id="ctxCopy" onclick="ctxCopy()" style="display:flex;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Копировать текст</div>
  <div class="ctx-item" id="ctxCopyLink" onclick="ctxCopyMsgLink()" style="display:none;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Ссылка</div>
  <div class="ctx-sep"></div>
  <div class="ctx-item danger" id="ctxDel" onclick="ctxDelete()" style="display:none;align-items:center;gap:7px"><svg viewBox="0 0 24 24" width="13" height="13"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>Удалить</div>
</div>

<!-- DYNAMIC ISLAND -->
<div id="dynamicIsland">
  <div id="diPill" class="di-hidden">
    <!-- Compact (collapsed) -->
    <div id="diCompact">
      <div class="di-left">
        <span class="di-icon" id="diIcon"><span class="ti" data-ti="music"></span></span>
        <span class="di-text" id="diText">—</span>
      </div>
      <div class="di-right" id="diRight">
        <span class="di-dot" id="diDot" style="display:none"></span>
        <button class="di-mini-play" id="diMiniPlay" style="display:none" onclick="event.stopPropagation()"><span class="ti" data-ti="play"></span></button>
      </div>
    </div>
    <!-- Expanded -->
    <div id="diExpanded">
      <div class="di-exp-header">
        <span class="di-exp-icon" id="diExpIcon"><span class="ti" data-ti="music"></span></span>
        <div class="di-exp-info">
          <div class="di-exp-title" id="diExpTitle">—</div>
          <div class="di-exp-sub" id="diExpSub"></div>
        </div>
        <button class="di-exp-close" id="diExpClose" onclick="DI.collapse();event.stopPropagation()"><span class="ti"><svg viewBox="0 0 24 24" width="12" height="12"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></button>
      </div>
      <div id="diExpControls" class="di-exp-controls"></div>
      <div id="diExpExtra"></div>
    </div>
  </div>
</div>

<!-- QUICK REPLY -->
<div id="quickReplyContainer"></div>

<!-- MODAL -->
<div id="modalBg" onclick="bgClose(event)">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()"><span class="ti"><svg viewBox="0 0 24 24" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></span></button>
    <div id="modalBody"></div>
  </div>
</div>

<!-- LIGHTBOX -->
<div id="lightbox">
  <button id="lbClose" class="lb-glass-btn lb-close" title="Закрыть" data-i18n-title="common.close" onclick="closeLightbox()"><svg viewBox="0 0 24 24" width="20" height="20"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
  <button id="lbPrev" class="lb-glass-btn lb-nav lb-prev" title="Назад" data-i18n-title="common.previous" onclick="event.stopPropagation();lbStep(-1)"><svg viewBox="0 0 24 24" width="26" height="26"><polyline points="15 5 8 12 15 19" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
  <img id="lbImg" alt="" onclick="event.stopPropagation()">
  <button id="lbNext" class="lb-glass-btn lb-nav lb-next" title="Вперёд" data-i18n-title="common.next" onclick="event.stopPropagation();lbStep(1)"><svg viewBox="0 0 24 24" width="26" height="26"><polyline points="9 5 16 12 9 19" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
  <div id="lbCounter" class="lb-counter"></div>
</div>
<div id="toastBox"></div>

<script>
'use strict';
const TI = {
  msg: `<svg viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3l2.5 3 2.5-3h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  gear: `<svg viewBox="0 0 24 24"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  logout: `<svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/><polyline points="16 17 21 12 16 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  bell: `<svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  phone: `<svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.06 6.06l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  link: `<svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  users: `<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  menu: `<svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="3" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  play: `<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/></svg>`,
  pause: `<svg viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" rx="1" fill="currentColor"/><rect x="14" y="4" width="4" height="16" rx="1" fill="currentColor"/></svg>`,
  prev: `<svg viewBox="0 0 24 24"><polygon points="19 20 9 12 19 4 19 20" fill="currentColor"/><line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>`,
  next: `<svg viewBox="0 0 24 24"><polygon points="5 4 15 12 5 20 5 4" fill="currentColor"/><line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>`,
  close: `<svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  plus: `<svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  min: `<svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  expand: `<svg viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="9 21 3 21 3 15" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="3" x2="14" y2="10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="3" y1="21" x2="10" y2="14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  restore: `<svg viewBox="0 0 24 24"><polyline points="4 14 10 14 10 20" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="20 10 14 10 14 4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="14" y1="10" x2="21" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="3" y1="21" x2="10" y2="14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  fullscreen: `<svg viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  stream: `<svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>`,
  mic: `<svg viewBox="0 0 24 24"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  micoff: `<svg viewBox="0 0 24 24"><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  vol: `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  voloff: `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  voice: `<svg viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0 1 18 0v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  leave: `<svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><polyline points="10 17 15 12 10 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="15" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  shield: `<svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  crown: `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linejoin="round"/></svg>`,
  star: `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke="currentColor" stroke-width="1.6" fill="currentColor" stroke-linejoin="round"/></svg>`,
  admin: `<svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><polyline points="9 12 11 14 15 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  puzzle: `<svg viewBox="0 0 24 24"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="16" y1="8" x2="2" y2="22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17.5" y1="15" x2="9" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  code: `<svg viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="8 6 2 12 8 18" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  book: `<svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  grid: `<svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.8" fill="none"/><rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.8" fill="none"/><rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.8" fill="none"/><rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  invite: `<svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="19" y1="8" x2="19" y2="14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="22" y1="11" x2="16" y2="11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  kick: `<svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="17" y1="11" x2="23" y2="11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  mute: `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  edit: `<svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  delete: `<svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  reply: `<svg viewBox="0 0 24 24"><polyline points="9 17 4 12 9 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 18v-2a4 4 0 0 0-4-4H4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  forward: `<svg viewBox="0 0 24 24"><polyline points="15 17 20 12 15 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 18v-2a4 4 0 0 1 4-4h12" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  copy: `<svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  pin: `<svg viewBox="0 0 24 24"><line x1="12" y1="17" x2="12" y2="22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  reaction: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M8 13s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="9" y1="9" x2="9.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><line x1="15" y1="9" x2="15.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>`,
  attach: `<svg viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  send: `<svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><polygon points="22 2 15 22 11 13 2 9 22 2" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/></svg>`,
  hash: `<svg viewBox="0 0 24 24"><line x1="4" y1="9" x2="20" y2="9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="4" y1="15" x2="20" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="10" y1="3" x2="8" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="16" y1="3" x2="14" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  search: `<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  live: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="currentColor"/><path d="M6.3 6.3a8 8 0 0 0 0 11.4M17.7 6.3a8 8 0 0 1 0 11.4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M3.5 3.5a13 13 0 0 0 0 17M20.5 3.5a13 13 0 0 1 0 17" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  note: `<svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  key: `<svg viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  lock: `<svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  flag: `<svg viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="4" y1="22" x2="4" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,

  // ── iOS Liquid Glass UI Icons ──────────────────────────
  swords: `<svg viewBox="0 0 24 24"><line x1="5" y1="19" x2="17" y2="7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><polyline points="14 7 17 7 17 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="19" y1="5" x2="17" y2="7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="19" y1="19" x2="7" y2="7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><polyline points="10 7 7 7 7 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="5" y1="5" x2="7" y2="7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  castle: `<svg viewBox="0 0 24 24"><path d="M4 21V11l2-2V4h2v3h2V4h4v3h2V4h2v5l2 2v10z" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linejoin="round"/><rect x="9" y="14" width="6" height="7" rx="3" stroke="currentColor" stroke-width="1.6" fill="none"/><line x1="4" y1="11" x2="20" y2="11" stroke="currentColor" stroke-width="1.6"/></svg>`,
  megaphone: `<svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 1 6 8" stroke="currentColor" stroke-width="0" fill="none"/><path d="M3 11v2a1 1 0 0 0 1 1h2l7 4V6L6 10H4a1 1 0 0 0-1 1z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M16 8a4 4 0 0 1 0 8" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19 5a8 8 0 0 1 0 14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  chat: `<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  callPhone: `<svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.13.81.36 1.6.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.06 6.06l1.27-1.27a2 2 0 0 1 2.11-.45c1.21.34 2 .57 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  speaker: `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  smile: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="9" y1="9" x2="9.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><line x1="15" y1="9" x2="15.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>`,
  paperclip: `<svg viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  music: `<svg viewBox="0 0 24 24"><path d="M9 18V5l12-2v13" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  lockClosed: `<svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  scroll: `<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><polyline points="14 2 14 8 20 8" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="8" y1="13" x2="16" y2="13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".5"/><line x1="8" y1="17" x2="14" y2="17" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".5"/></svg>`,
  user: `<svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  userGroup: `<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  image: `<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/><polyline points="21 15 16 10 5 21" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  video: `<svg viewBox="0 0 24 24"><rect x="2" y="4" width="15" height="16" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M17 9l5-3v12l-5-3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`,
  archive: `<svg viewBox="0 0 24 24"><path d="M21 8v13H3V8" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M1 3h22v5H1z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="10" y1="12" x2="14" y2="12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  file: `<svg viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><polyline points="13 2 13 9 20 9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  camera: `<svg viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  trash: `<svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  clipboard: `<svg viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><rect x="8" y="2" width="8" height="4" rx="1" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  warning: `<svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="12" y1="17" x2="12.01" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
  palette: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="8" cy="10" r="1.3" fill="currentColor"/><circle cx="12" cy="7.5" r="1.3" fill="currentColor"/><circle cx="16" cy="10" r="1.3" fill="currentColor"/><path d="M14.5 14.5c1 1.5-.5 3-2.5 3s-3-1.5-2.5-3" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round"/></svg>`,
  ban: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  bellOn: `<svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`,
  bellOff: `<svg viewBox="0 0 24 24"><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M18.63 13A17.89 17.89 0 0 1 18 8" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M18 8a6 6 0 0 0-9.33-5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  refresh: `<svg viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="1 20 1 14 7 14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
  phoneOff: `<svg viewBox="0 0 24 24"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45c.84.25 1.75.5 2.81.7A2 2 0 0 1 22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.91 9.91l-.82.82a2 2 0 0 1-2.11.45c-1.06-.34-1.97-.57-2.81-.7A2 2 0 0 1 1 8.55v-3a2 2 0 0 1 2.18-2A19.79 19.79 0 0 1 12 6.62c1.52.84 2.97 1.84 4.31 3" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  download: `<svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  speakerMuted: `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`,
  playBtn: `<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/></svg>`,
  paintbrush: `<svg viewBox="0 0 24 24"><path d="M20 4v5l-9 7-3.5-3.5L14 4z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><circle cx="7" cy="17" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`,
  // Вспомогательная функция — создаёт span.ti с нужной иконкой
  html(name, cls=''){
    const svg = this[name] || this.note;
    return `<span class="ti ${cls}">${svg}</span>`;
  }
};
(function(){
  function applyVP(){
    // Реальная высота окна (без баров браузера/телеграма)
    document.documentElement.style.setProperty('--app-h', window.innerHeight+'px');

    // Вычисляем safe-area-inset-bottom безопасным способом
    let sb=0;
    try{
      const el=document.createElement('div');
      el.style.cssText='position:fixed;bottom:0;left:0;width:1px;height:1px;'+
        'padding-bottom:env(safe-area-inset-bottom,0px);pointer-events:none;visibility:hidden';
      document.documentElement.appendChild(el);
      sb=Math.max(0,parseInt(getComputedStyle(el).paddingBottom)||0);
      el.remove();
    }catch(e){}

    // В Telegram WebView safe-area часто возвращает мусор — ограничиваем разумным максимумом
    if(sb>60) sb=0;
    document.documentElement.style.setProperty('--safe-bottom', sb+'px');
  }

  applyVP();
  window.addEventListener('resize', applyVP, {passive:true});
  // Keyboard open detection для мобильного (скрываем DI)
  if(window.visualViewport){
    window.visualViewport.addEventListener('resize',()=>{
      const isKb=window.visualViewport.height < window.innerHeight*0.75;
      document.body.classList.toggle('keyboard-open',isKb);
    });
  }
  window.addEventListener('orientationchange',()=>setTimeout(applyVP,300), {passive:true});

  // Telegram иногда меняет размер viewport ПОСЛЕ загрузки
  if(window.ResizeObserver){
    new ResizeObserver(applyVP).observe(document.documentElement);
  }
})();
if('scrollRestoration' in history) history.scrollRestoration='manual';

// Горизонтальная прокрутка панели серверов колесом мыши (мобильный вид на ПК).
// В мобильной раскладке #serverBar — горизонтальная лента с overflow-x:auto,
// но вертикальное колесо мыши её не крутит. Транслируем deltaY → scrollLeft.
(function(){
  function bindHWheel(){
    const bar=document.getElementById('serverBar');
    if(!bar || bar._hWheelBound) return;
    bar._hWheelBound=true;
    bar.addEventListener('wheel',function(e){
      // Работает только когда лента реально горизонтальная и есть что скроллить
      if(bar.scrollWidth<=bar.clientWidth) return;
      // Если жест уже горизонтальный (тачпад) — не мешаем
      if(Math.abs(e.deltaX)>Math.abs(e.deltaY)) return;
      bar.scrollLeft+=e.deltaY;
      e.preventDefault();
    },{passive:false});
  }
  bindHWheel();
  document.addEventListener('DOMContentLoaded',bindHWheel);
  window.addEventListener('load',bindHWheel);
})();

// ── SAFARI iOS FIXES ──────────────────────────────────────────
(function(){
  // Определяем Safari на iOS
  const isSafari=/^((?!chrome|android).)*safari/i.test(navigator.userAgent);
  const isIOS=/iPad|iPhone|iPod/.test(navigator.userAgent)||(navigator.platform==='MacIntel'&&navigator.maxTouchPoints>1);

  if(isIOS||isSafari){
    // 1. Фикс 300ms задержки на тач-событиях для Safari < 13
    document.addEventListener('touchstart',function(){},true);

    // 2. Предотвращаем bounce-скролл страницы, но разрешаем скролл внутри элементов
    document.addEventListener('touchmove',function(e){
      let el=e.target;
      while(el&&el!==document.body){
        const style=getComputedStyle(el);
        if((style.overflowY==='auto'||style.overflowY==='scroll')&&el.scrollHeight>el.clientHeight) return;
        if((style.overflowX==='auto'||style.overflowX==='scroll')&&el.scrollWidth>el.clientWidth) return;
        el=el.parentElement;
      }
      if(!e.target.closest('#messagesWrap,#dmMsgWrap,.ch-scroll,.dm-scroll,.mb-scroll,.modal,.terms-scroll,#miniAppModal .mini-app-body')){
        e.preventDefault();
      }
    },{passive:false});

    // 3. Фикс кнопок serverBar на Safari — onclick иногда не срабатывает без cursor:pointer
    document.addEventListener('DOMContentLoaded',function(){
      // Добавляем пустой touchstart handler ко всем кликабельным элементам,
      // чтобы Safari включал :active state и обрабатывал click-события
      function addSafariClickFix(selector){
        document.querySelectorAll(selector).forEach(el=>{
          if(!el.dataset.safariFixed){
            el.dataset.safariFixed='1';
            el.addEventListener('touchstart',function(){},true);
          }
        });
      }
      const selectors='#serverBar .srv-icon, #chList .ch-item, #chList .voice-room, .dm-item, .mb-item, .btn, .ctx-item';
      addSafariClickFix(selectors);

      // Наблюдаем за добавлением новых элементов
      const obs=new MutationObserver(function(){
        addSafariClickFix(selectors);
      });
      obs.observe(document.getElementById('srvIcons')||document.body,{childList:true,subtree:true});
    });
  }
})();


// ── UI Icon helper: generates inline SVG icons for modal/dynamic content ──
function ti(name,size){
  const s=TI[name]||TI.note;
  const sz=size||18;
  return '<span class="ti" style="display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;line-height:1;vertical-align:middle">'+s.replace(/(<svg[^>]*)(>)/,'$1 width="'+sz+'" height="'+sz+'"$2')+'</span>';
}

const API=<?= json_encode(defined('API_ENDPOINT') ? API_ENDPOINT : 'truecord_api.php') ?>;
const OWNER_NAME=<?= json_encode(defined('OWNER_NAME') ? OWNER_NAME : 'Admin') ?>;
const APP_CFG=<?= json_encode([
  'siteName'=>SITE_NAME,
  'siteUrl'=>SITE_URL,
  'defaultTheme'=>DEFAULT_THEME,
  'registrationOpen'=>REGISTRATION_OPEN,
  'requireTermsAcceptance'=>REQUIRE_TERMS_ACCEPTANCE,
  'requireValidation'=>REQUIRE_VALIDATION,
  'usernameMinLength'=>USERNAME_MIN_LEN,
  'firstAdminBypassUsernameMinLength'=>FIRST_ADMIN_BYPASS_USERNAME_MIN_LENGTH,
  'firstRegisteredUserBecomesSuperAdmin'=>FIRST_REGISTERED_USER_BECOMES_SUPER_ADMIN,
  'usernameMaxLength'=>USERNAME_MAX_LEN,
  'passwordMinLength'=>PASSWORD_MIN_LEN,
  'createServerPermission'=>CREATE_SERVER_PERMISSION,
  'createChannelPermission'=>CREATE_CHANNEL_PERMISSION,
  'createVoicePermission'=>CREATE_VOICE_PERMISSION,
  'dmPolicy'=>DM_POLICY,
  'registrationTermsTitle'=>REGISTRATION_TERMS_TITLE,
  'registrationModalNote'=>REGISTRATION_MODAL_NOTE,
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>;

const S={
  me:null,tok:null,srvId:null,chId:null,dmUid:null,mode:'channel',
  _dmLoading:false,_dmPollLock:false,hasMoreDmMsgs:false,loadingMoreDm:false,_dmScrollBound:false,dmBaselineId:0,_dmInitDone:false,dmNotifyFloor:Infinity,
  servers:[],channels:[],members:[],dmConvs:[],allUsers:[],
  reactions:{},onlineUsers:[],myStatus:'online',replyTo:null,ctxTarget:null,
  epMode:null,epReactMsgId:null,epReactIsDm:false,pendingFiles:[],dmPendingFiles:[],
  lastMsgId:0,lastDmId:0,unread:{},mentionCount:{},
  srvUnread:{},srvMentions:{},rememberMe:false,
  prevStatus:null,
  pollTimer:null,myRole:'member',audioCtx:null,bgPollIdx:0,
  readObserver:null,notifiedMsgIds:new Set(),_fmtTextarea:null,
  voiceBgCounter:0,serverVoiceActive:{},lastCallId:0,
  chLastIds:{},bgChPollIdx2:0,hasMoreMsgs:false,loadingMore:false,_chScrollBound:false,_chLoadingToken:0,chNotifyFloor:Infinity,_chInitDone:false,
  typingTimer:null,lastVoiceEventId:0,
  currentSrvPendingJoin:null,
};
// ★ FIX: флаг первого heartbeat — игнорируем старые call-сигналы при перезагрузке
let _isFirstHeartbeat = true;
window.addEventListener('DOMContentLoaded',()=>{
  if(!APP_CFG.registrationOpen){
    const tabs=document.querySelectorAll('.login-tab');
    if(tabs[1]) tabs[1].style.display='none';
    const regWrap=q('fReg');
    if(regWrap) regWrap.innerHTML='<div class=\"err-msg\" style=\"display:block\">Регистрация на этом экземпляре отключена администратором.</div>';
  }
});
const VOICE={roomId:null,roomName:'',stream:null,peers:{},lastSigId:0,muted:false,timer:null,rooms:[],volume:1,speakerMuted:false,userVolumes:{},screenStream:null,streamMode:'screen',streamers:{},currentStreamUserId:null,streamInlineActive:false,streamFps:30,streamViewers:{},streamWatchers:{}};
// ── DEAD MEDIA TRACKER (предотвращает повторные тосты) ───────
const _deadMediaUrls = new Set();

// ── VOICE ACTIVITY DETECTION ─────────────────────────────────
const VAD={
  analysers:{},      // userId -> {analyser, dataArray, source}
  speaking:{},       // userId -> bool
  holdCounters:{},   // userId -> frames remaining
  localSrc:null,
  localAn:null,
  localDa:null,
  timer:null,
  THRESHOLD:4,       // RMS порог для локального time-domain сигнала; ниже лучше ловит тихую речь
  HOLD:6,            // Кадров задержки перед сбросом "говорит"
};

function setupLocalVAD(stream){
  try{
    const ctx=getAC();if(!ctx||!stream) return;
    // Для индикатора речи анализируем сырой микрофон, если он есть.
    // Обработанный поток после noise gate может быть слишком тихим и не проходить порог.
    const vadStream=stream._rawStream||stream;
    const src=ctx.createMediaStreamSource(vadStream);
    const an=ctx.createAnalyser();
    an.fftSize=512;an.smoothingTimeConstant=0.25;
    src.connect(an);
    VAD.localSrc=src;VAD.localAn=an;
    VAD.localDa=new Uint8Array(an.frequencyBinCount);
  }catch(e){
    console.warn('[setupLocalVAD] failed:',e);
  }
}


function setupRemoteVAD(userId,stream){
  try{
    if(!stream) return;
    const ctx=getAC();if(!ctx) return;
    const src=ctx.createMediaStreamSource(stream);
    const an=ctx.createAnalyser();
    an.fftSize=512;an.smoothingTimeConstant=0.25;
    src.connect(an);
    VAD.analysers[userId]={analyser:an,dataArray:new Uint8Array(an.frequencyBinCount),source:src};
  }catch(e){}
}

function vadRms(da){
  // Используем time-domain уровень вокруг 128. Так локальный микрофон
  // надёжнее определяется после шумоподавления/noise gate, чем по FFT bins.
  let s=0;
  for(let i=0;i<da.length;i++){
    const v=da[i]-128;
    s+=v*v;
  }
  return Math.sqrt(s/Math.max(1,da.length));
}

function vadTick(uid,rms){
  const speaking=rms>VAD.THRESHOLD;
  if(speaking){
    VAD.holdCounters[uid]=VAD.HOLD;
    if(!VAD.speaking[uid]){VAD.speaking[uid]=true;return true;}
  } else {
    if((VAD.holdCounters[uid]||0)>0){
      VAD.holdCounters[uid]--;
      if(VAD.holdCounters[uid]===0&&VAD.speaking[uid]){VAD.speaking[uid]=false;return true;}
    } else if(VAD.speaking[uid]){VAD.speaking[uid]=false;return true;}
  }
  return false;
}

function startVADLoop(){
  // ВАЖНО: не вызываем stopVADLoop() здесь.
  // setupLocalVAD() уже создал анализатор микрофона прямо перед запуском цикла;
  // старый код тут же очищал localAn/localDa, поэтому пользователь не видел
  // собственную подсветку "говорю". Очищаем только сам таймер.
  clearInterval(VAD.timer);
  VAD.timer=null;
  VAD.timer=setInterval(()=>{
    let changed=false;
    // Локальный пользователь
    if(VAD.localAn&&VAD.localDa&&S.me&&!VOICE.muted){
      VAD.localAn.getByteTimeDomainData(VAD.localDa);
      if(vadTick(S.me.id,vadRms(VAD.localDa))) changed=true;
    } else if(S.me&&VOICE.muted&&VAD.speaking[S.me.id]){
      VAD.speaking[S.me.id]=false;changed=true;
    }
    // Удалённые участники
    Object.entries(VAD.analysers).forEach(([uid,{analyser,dataArray}])=>{
      analyser.getByteTimeDomainData(dataArray);
      if(vadTick(parseInt(uid),vadRms(dataArray))) changed=true;
    });
    if(changed) updateVoiceSpeakingUI();
  },100);
}

function stopVADLoop(){
  clearInterval(VAD.timer);VAD.timer=null;
  try{VAD.localSrc?.disconnect();}catch(e){}
  VAD.localSrc=null;VAD.localAn=null;VAD.localDa=null;
  Object.values(VAD.analysers).forEach(({source})=>{try{source?.disconnect();}catch(e){}});
  VAD.analysers={};VAD.speaking={};VAD.holdCounters={};
}

function updateVoiceSpeakingUI(){
  const myId=S.me?.id?parseInt(S.me.id,10):0;
  const localSpeaking=!!(myId&&VAD.speaking[myId]&&!VOICE.muted);

  document.querySelectorAll('.voice-part-item[data-user-id]').forEach(el=>{
    const uid=parseInt(el.dataset.userId,10);
    const sp=!!VAD.speaking[uid];
    el.classList.toggle('speaking',sp);
    const av=el.querySelector('.vp-av');
    if(av) av.classList.toggle('speaking-av',sp);
  });

  const vb=q('voiceBar');
  if(vb){
    vb.classList.toggle('local-speaking',localSpeaking);
    vb.title=localSpeaking?'Ваш микрофон сейчас передаёт голос':'';
  }
  // Подсветка краёв экрана: верх — говорит собеседник, низ — говорю я.
  // Активна только когда я в голосовом канале.
  const inVoice=!!(VOICE&&VOICE.roomId);
  let remoteSpeaking=false;
  if(inVoice){
    for(const k in VAD.speaking){ if(VAD.speaking[k] && parseInt(k,10)!==myId){ remoteSpeaking=true; break; } }
  }
  document.body.classList.toggle('vglow-local', inVoice && localSpeaking);
  document.body.classList.toggle('vglow-remote', inVoice && remoteSpeaking);
  if(typeof _svUpdateEchoDucking==='function') _svUpdateEchoDucking();
  updateVoiceStageSpeakingUI();
}

const DMCALL={active:false,withUserId:null,withUserName:'',withAvatar:'',stream:null,pc:null,isInitiator:false,muted:false,ringing:false,ringtoneTimer:null,startTime:null,timerInterval:null,lastSigId:0,videoEnabled:false,wantVideo:false,screenSharing:false,videoSender:null,_remoteVideo:null,_localVideo:null,_videoDrag:null,_videoWindow:null,_controlsHideTimer:null};
const DMCALL_VAD={local:null,remote:null,timer:null,localSpeaking:false,remoteSpeaking:false,localHold:0,remoteHold:0,THRESHOLD:4,HOLD:6};
function dmCallClearVadSource(kind){const src=DMCALL_VAD[kind];if(!src) return;try{src.source?.disconnect?.();}catch(e){}DMCALL_VAD[kind]=null;}
function dmCallSetupVadSource(kind,stream,useRaw=false){dmCallClearVadSource(kind);try{const ctx=getAC();if(!ctx||!stream) return;const srcStream=useRaw?(stream._rawStream||stream):stream;const source=ctx.createMediaStreamSource(srcStream);const analyser=ctx.createAnalyser();analyser.fftSize=512;analyser.smoothingTimeConstant=0.28;source.connect(analyser);DMCALL_VAD[kind]={source,analyser,dataArray:new Uint8Array(analyser.frequencyBinCount)};}catch(e){}}
function dmCallSpeakingFromAnalyser(entry){if(!entry?.analyser||!entry?.dataArray) return 0;entry.analyser.getByteTimeDomainData(entry.dataArray);return vadRms(entry.dataArray);}
function dmCallSetSpeakingUI(localSpeaking,remoteSpeaking){DMCALL_VAD.localSpeaking=!!localSpeaking;DMCALL_VAD.remoteSpeaking=!!remoteSpeaking;q('dmvcLocalBox')?.classList.toggle('speaking',!!localSpeaking);q('dmvcRemoteBox')?.classList.toggle('speaking',!!remoteSpeaking);}
function dmCallStartVADLoop(){clearInterval(DMCALL_VAD.timer);DMCALL_VAD.timer=setInterval(()=>{let localSpeaking=false,remoteSpeaking=false;if(DMCALL.active&&DMCALL_VAD.local&&!DMCALL.muted){const rms=dmCallSpeakingFromAnalyser(DMCALL_VAD.local);if(rms>DMCALL_VAD.THRESHOLD){DMCALL_VAD.localHold=DMCALL_VAD.HOLD;localSpeaking=true;}else if((DMCALL_VAD.localHold||0)>0){DMCALL_VAD.localHold--;localSpeaking=true;}}else DMCALL_VAD.localHold=0;if(DMCALL.active&&DMCALL_VAD.remote){const rms=dmCallSpeakingFromAnalyser(DMCALL_VAD.remote);if(rms>DMCALL_VAD.THRESHOLD){DMCALL_VAD.remoteHold=DMCALL_VAD.HOLD;remoteSpeaking=true;}else if((DMCALL_VAD.remoteHold||0)>0){DMCALL_VAD.remoteHold--;remoteSpeaking=true;}}else DMCALL_VAD.remoteHold=0;dmCallSetSpeakingUI(localSpeaking,remoteSpeaking);},120);}
function dmCallStopVADLoop(){clearInterval(DMCALL_VAD.timer);DMCALL_VAD.timer=null;dmCallClearVadSource('local');dmCallClearVadSource('remote');DMCALL_VAD.localHold=0;DMCALL_VAD.remoteHold=0;dmCallSetSpeakingUI(false,false);}

// ── WAKE LOCK ────────────────────────────────────────────────
let _wakeLock = null;
async function requestWakeLock(){
  if(!('wakeLock' in navigator)) return; // Safari < 16 не поддерживает
  try{
    _wakeLock = await navigator.wakeLock.request('screen');
    _wakeLock.addEventListener('release', ()=>{ _wakeLock = null; });
  } catch(e){ console.warn('[WakeLock]', e.message); }
}
async function releaseWakeLock(){
  try{ await _wakeLock?.release(); } catch(e){}
  _wakeLock = null;
}
// При возврате на страницу WakeLock теряется — восстанавливаем
document.addEventListener('visibilitychange', ()=>{
  if(document.visibilityState==='visible' && _wakeLock===null){
    if(VOICE.roomId!==null || DMCALL.active) requestWakeLock();
  }
});
// ── SILENT BG AUDIO (держит аудио-сессию живой в фоне) ──────

// ★ АУДИО FIX 1: let вместо const — будет перезаписан из get_ice_servers
// Базовые публичные STUN + TURN (запасной вариант).
// ВАЖНО: для связи Android↔iOS через мобильные сети (симметричный NAT/CGNAT)
// одного STUN недостаточно — нужен TURN-релей. Без TURN VAD виден (сигналинг идёт
// по HTTP-поллингу), но сам медиапоток не устанавливается. Ниже — бесплатные
// публичные TURN OpenRelay (metered.ca) как запасной вариант. Для продакшена
// настройте собственный coturn и пропишите его в config.json → webrtc.ice_servers.
let STUN_CFG={
  iceServers:[
    {urls:'stun:stun.l.google.com:19302'},
    {urls:'stun:stun1.l.google.com:19302'},
    {urls:'stun:stun.cloudflare.com:3478'},
    {urls:'turn:openrelay.metered.ca:80',username:'openrelayproject',credential:'openrelayproject'},
    {urls:'turn:openrelay.metered.ca:443',username:'openrelayproject',credential:'openrelayproject'},
    {urls:'turn:openrelay.metered.ca:443?transport=tcp',username:'openrelayproject',credential:'openrelayproject'},
  ],
  iceCandidatePoolSize:2,
  bundlePolicy:'max-bundle',
  rtcpMuxPolicy:'require',
  iceTransportPolicy:'all',
};


// ★ АУДИО FIX 2: загружаем ICE серверы с сервера (включая TURN)
async function loadIceServers(){
  try{
    const r=await api({action:'get_ice_servers'});
    if(r.ok&&r.iceServers&&r.iceServers.length){
      // Сливаем серверные ICE с локальными фолбэками и убираем дубли по urls.
      // Так мы никогда не теряем STUN/TURN-запасные варианты, даже если в конфиге
      // сервера прописан неполный список.
      const merged=[...r.iceServers, ...STUN_CFG.iceServers];
      const seen=new Set();
      const dedup=[];
      for(const s of merged){
        const key=JSON.stringify(s.urls)+'|'+(s.username||'');
        if(seen.has(key)) continue;
        seen.add(key); dedup.push(s);
      }
      STUN_CFG={
        iceServers:dedup,
        iceCandidatePoolSize:2,
        bundlePolicy:'max-bundle',
        rtcpMuxPolicy:'require',
        iceTransportPolicy:'all',
      };
    }
  }catch(e){}
}


// ── ADAPTIVE OPUS + NOISE SUPPRESSION ───────────────────────
// Цель: голос должен оставаться стабильным на слабой сети.
// 1) Браузерные DSP-фильтры микрофона: echoCancellation / noiseSuppression / autoGainControl.
// 2) Дополнительный лёгкий noise gate + фильтры речи через WebAudio.
// 3) Opus DTX/FEC + адаптивный maxBitrate через RTCRtpSender.setParameters().
const VOICE_AUDIO={
  min:16000,
  low:24000,
  mid:32000,
  high:48000,
  screen:128000,
  max:128000,
  current:32000,
  monitorMs:5000,
  noiseGate:true,
};
const VOICE_VIDEO={
  // Для игры/экрана слишком низкий битрейт даёт рваную картинку.
  // Поэтому нижние уровни подняты, а адаптация меняет качество мягче.
  min:600000,
  low:1200000,
  mid:2500000,
  high:4000000,
  max:6000000,
  monitorMs:7000,
};

function isMobileLike(){
  return /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent||'') || window.innerWidth<=980;
}

function voiceAudioConstraints(){
  return {
    echoCancellation:true,
    noiseSuppression:true,
    autoGainControl:true,
    channelCount:{ideal:1},
    sampleRate:{ideal:48000},
    sampleSize:{ideal:16},
    latency:{ideal:0.02},
  };
}

async function getOptimizedMicStream(){
  const raw=await navigator.mediaDevices.getUserMedia({audio:voiceAudioConstraints(),video:false});
  return createNoiseSuppressedMicStream(raw);
}

async function createNoiseSuppressedMicStream(rawStream){
  if(!VOICE_AUDIO.noiseGate) return rawStream;
  try{
    const ctx=getAC();
    if(!ctx) return rawStream;
    const src=ctx.createMediaStreamSource(rawStream);
    const hp=ctx.createBiquadFilter();
    hp.type='highpass';hp.frequency.value=90;hp.Q.value=0.7;
    const lp=ctx.createBiquadFilter();
    lp.type='lowpass';lp.frequency.value=7600;lp.Q.value=0.7;
    const comp=ctx.createDynamicsCompressor();
    comp.threshold.value=-42;
    comp.knee.value=22;
    comp.ratio.value=3.2;
    comp.attack.value=0.004;
    comp.release.value=0.18;
    const gate=ctx.createScriptProcessor(1024,1,1);
    let gateGain=1;
    let noiseFloor=0.010;
    gate.onaudioprocess=e=>{
      const input=e.inputBuffer.getChannelData(0);
      const output=e.outputBuffer.getChannelData(0);
      let sum=0;
      for(let i=0;i<input.length;i++) sum+=input[i]*input[i];
      const rms=Math.sqrt(sum/input.length);
      // Плавно отслеживаем фон, но только когда сигнал тихий.
      if(rms<0.035) noiseFloor=noiseFloor*0.985+rms*0.015;
      const open=Math.max(0.014,noiseFloor*2.8);
      const close=Math.max(0.009,noiseFloor*1.8);
      const target=rms>open?1:(rms<close?0.10:gateGain);
      gateGain += (target-gateGain)*(target>gateGain?0.35:0.08);
      for(let i=0;i<input.length;i++) output[i]=input[i]*gateGain;
    };
    const dest=ctx.createMediaStreamDestination();
    src.connect(hp);hp.connect(lp);lp.connect(comp);comp.connect(gate);gate.connect(dest);
    const processed=dest.stream;
    processed._rawStream=rawStream;
    processed._audioNodes={ctx,src,hp,lp,comp,gate,dest};
    processed._cleanupAudioProcessing=()=>{
      try{gate.disconnect();}catch(e){}
      try{comp.disconnect();}catch(e){}
      try{lp.disconnect();}catch(e){}
      try{hp.disconnect();}catch(e){}
      try{src.disconnect();}catch(e){}
      try{rawStream.getTracks().forEach(t=>t.stop());}catch(e){}
    };
    return processed;
  }catch(e){
    console.warn('[audio noise suppression] fallback to browser DSP:',e);
    return rawStream;
  }
}

function stopOptimizedAudioStream(stream){
  if(!stream) return;
  try{stream._cleanupAudioProcessing?.();}catch(e){}
  try{stream.getTracks().forEach(t=>t.stop());}catch(e){}
  try{stream._rawStream?.getTracks?.().forEach(t=>t.stop());}catch(e){}
}

function voiceInitialBitrate(){
  const room=VOICE.rooms?.find?.(x=>x.id===VOICE.roomId);
  const n=room?.participants?.length||1;
  if(isMobileLike()) return n>4?VOICE_AUDIO.low:VOICE_AUDIO.mid;
  if(n>8) return VOICE_AUDIO.low;
  if(n>4) return VOICE_AUDIO.mid;
  return VOICE_AUDIO.high;
}

function voiceSetStatusText(txt){
  const el=q('vbRoomStatus');
  if(el&&txt) el.textContent=txt;
}
function voiceBitrateStatus(kind,bitrate){
  const value=Math.max(1,Math.round((bitrate||0)/1000));
  const lang=((window.I18N?.getLang?.()||localStorage.getItem('lang')||'ru')+'').slice(0,2).toLowerCase();
  const connected=i18nt('voice.connected','Подключено');
  if(lang==='en') return `${connected} · ${kind==='video'?'video':'audio'} ${value} kbps`;
  if(lang==='de') return `${connected} · ${kind==='video'?'Video':'Audio'} ${value} kbit/s`;
  if(lang==='fr') return `${connected} · ${kind==='video'?'vidéo':'audio'} ${value} kb/s`;
  return `${connected} · ${kind==='video'?'видео':'звук'} ${value} кбит/с`;
}

async function applyAudioSenderBitrate(sender,bitrate){
  if(!sender||!sender.getParameters) return false;
  bitrate=Math.max(VOICE_AUDIO.min,Math.min(VOICE_AUDIO.max,bitrate|0));
  try{
    const p=sender.getParameters();
    if(!p.encodings||!p.encodings.length) p.encodings=[{}];
    p.encodings[0].maxBitrate=bitrate;
    // Поддерживается не везде, но браузеры просто проигнорируют неизвестное.
    p.encodings[0].priority='high';
    p.encodings[0].networkPriority='high';
    if('dtx' in p.encodings[0]) p.encodings[0].dtx=true;
    await sender.setParameters(p);
    return true;
  }catch(e){
    return false;
  }
}

function optimizeOpusSdp(desc,bitrate){
  if(!desc||!desc.sdp) return desc;
  bitrate=Math.max(VOICE_AUDIO.min,Math.min(VOICE_AUDIO.max,bitrate||VOICE_AUDIO.current));
  let sdp=desc.sdp;
  const m=sdp.match(/a=rtpmap:(\d+) opus\/48000(?:\/2)?/i);
  if(!m) return desc;
  const pt=m[1];
  const opusBr=Math.max(12000,Math.min(128000,bitrate||VOICE_AUDIO.current));
  // Для обычного голоса оставляем mono + DTX. Для звука трансляции/игры
  // нужен stereo и без агрессивного DTX, иначе музыка/игровой звук режется.
  const isScreenAudio=opusBr>64000;
  const params=isScreenAudio
    ? `useinbandfec=1;usedtx=0;maxaveragebitrate=${opusBr};stereo=1;sprop-stereo=1;ptime=20;minptime=10`
    : `useinbandfec=1;usedtx=1;maxaveragebitrate=${opusBr};stereo=0;sprop-stereo=0;ptime=20;minptime=10`;
  const fmtp=new RegExp(`a=fmtp:${pt} ([^\\r\\n]*)`,'i');
  if(fmtp.test(sdp)){
    sdp=sdp.replace(fmtp,(line,old)=>{
      let merged=old
        .replace(/(?:^|;)\s*useinbandfec=\d+/ig,'')
        .replace(/(?:^|;)\s*usedtx=\d+/ig,'')
        .replace(/(?:^|;)\s*maxaveragebitrate=\d+/ig,'')
        .replace(/(?:^|;)\s*stereo=\d+/ig,'')
        .replace(/(?:^|;)\s*sprop-stereo=\d+/ig,'')
        .replace(/(?:^|;)\s*ptime=\d+/ig,'')
        .replace(/(?:^|;)\s*minptime=\d+/ig,'')
        .replace(/^;+|;+$/g,'')
        .trim();
      merged=merged?merged+';'+params:params;
      return `a=fmtp:${pt} ${merged}`;
    });
  }else{
    sdp=sdp.replace(new RegExp(`(a=rtpmap:${pt} opus/48000(?:/2)?\\r?\\n)`,'i'),`$1a=fmtp:${pt} ${params}\r\n`);
  }
  return new RTCSessionDescription({type:desc.type,sdp});
}

async function createOptimizedOffer(pc,bitrate,opts={}){
  const offer=await pc.createOffer(Object.assign({offerToReceiveAudio:true},opts||{}));
  return optimizeOpusSdp(offer,bitrate);
}

async function createOptimizedAnswer(pc,bitrate){
  const answer=await pc.createAnswer();
  return optimizeOpusSdp(answer,bitrate);
}

function getAudioSenderFromPc(pc){
  return pc?.getSenders?.().find(s=>s.track&&s.track.kind==='audio')||null;
}

async function setPeerOutgoingAudioTrack(peer,track,mode='mic'){
  if(!peer||!peer.pc||!track) return null;
  const pc=peer.pc;
  let sender=peer.audioSender||getAudioSenderFromPc(pc);
  try{
    if(sender){
      await sender.replaceTrack(track);
    }else{
      const st=(mode==='screen')?(VOICE.screenStream||new MediaStream([track])):(VOICE.stream||new MediaStream([track]));
      sender=pc.addTrack(track,st);
    }
    peer.audioSender=sender;
    await setPeerAudioBitrate(peer,mode==='screen'?VOICE_AUDIO.screen:voiceInitialBitrate(),mode);
    return sender;
  }catch(e){
    console.warn('setPeerOutgoingAudioTrack failed:',mode,e);
    return null;
  }
}


async function restorePeerMicAudio(peer){
  if(!peer||!peer.pc) return;
  const micTrack=VOICE.stream?.getAudioTracks?.()[0]||null;
  if(!micTrack) return;
  await setPeerOutgoingAudioTrack(peer,micTrack,'mic');
}

async function enableStreamAudioForViewer(uid){
  const peer=VOICE.peers[uid];
  if(!peer||!peer.pc||!VOICE._screenAudioTrack) return;
  VOICE.streamViewers[uid]=true;

  // Звук трансляции отправляем отдельным sender'ом, а не заменяем микрофон.
  // Так зритель слышит обычный голосовой чат через mic sender и звук игры
  // через stream sender, без подмешивания голоса стримера в stream track.
  try{
    if(peer.streamAudioSender){
      await peer.streamAudioSender.replaceTrack(VOICE._screenAudioTrack);
    }else{
      const st=VOICE.screenStream||new MediaStream([VOICE._screenAudioTrack]);
      peer.streamAudioSender=peer.pc.addTrack(VOICE._screenAudioTrack,st);
    }
    await applyAudioSenderBitrate(peer.streamAudioSender,VOICE_AUDIO.screen||128000);
    peer._streamAudioEnabled=true;
    await renegotiateVoicePeers('stream-audio-enable',{forceLocalOffer:true,onlyTo:uid});
  }catch(e){
    console.warn('enableStreamAudioForViewer failed:',uid,e);
  }
}

async function disableStreamAudioForViewer(uid){
  delete VOICE.streamViewers[uid];
  const peer=VOICE.peers[uid];
  if(!peer) return;
  peer._streamAudioEnabled=false;
  peer.streamAudioTrack=null;
  peer.streamAudioStream=null;
  try{
    if(peer.streamAudioSender){
      await peer.streamAudioSender.replaceTrack(null);
      await renegotiateVoicePeers('stream-audio-disable',{forceLocalOffer:true,onlyTo:uid});
    }
  }catch(e){console.warn('disableStreamAudioForViewer failed:',uid,e);}
}

async function addOptimizedAudioTrack(pc,stream,peer){
  const track=stream?.getAudioTracks?.()[0];
  if(!track) return null;
  let sender=null;
  try{sender=pc.addTrack(track,stream);}catch(e){}
  if(peer&&sender){
    peer.audioSender=sender;
    peer._audioBitrate=voiceInitialBitrate();
    await applyAudioSenderBitrate(sender,peer._audioBitrate);
  }
  return sender;
}

async function setPeerAudioBitrate(peer,bitrate,reason=''){
  if(!peer) return;
  const sender=peer.audioSender||getAudioSenderFromPc(peer.pc);
  if(!sender) return;
  bitrate=Math.max(VOICE_AUDIO.min,Math.min(VOICE_AUDIO.max,bitrate|0));
  if(peer._audioBitrate===bitrate) return;
  peer._audioBitrate=bitrate;
  await applyAudioSenderBitrate(sender,bitrate);
  if(reason&&VOICE.roomId) voiceSetStatusText(voiceBitrateStatus('audio',bitrate));
}

function voiceVideoInitialBitrate(){
  if(isMobileLike()) return VOICE_VIDEO.mid;
  const fps=VOICE.streamFps||30;
  if(fps>=60) return VOICE_VIDEO.high;
  return VOICE_VIDEO.mid;
}

function getVideoSenderFromPc(pc){
  return pc?.getSenders?.().find(s=>s.track&&s.track.kind==='video')||null;
}

async function applyVideoSenderParams(sender,bitrate,opts={}){
  if(!sender||!sender.getParameters) return false;
  bitrate=Math.max(VOICE_VIDEO.min,Math.min(VOICE_VIDEO.max,bitrate|0));
  try{
    const p=sender.getParameters();
    if(!p.encodings||!p.encodings.length) p.encodings=[{}];
    const e=p.encodings[0];
    e.maxBitrate=bitrate;
    // FPS не режем aggressively: для игры это выглядит как фризы.
    // Пусть WebRTC снижает разрешение/качество, но держит движение плавным.
    e.maxFramerate=opts.fps||Math.max(30,Math.min(VOICE.streamFps||30,60));
    e.scaleResolutionDownBy=opts.scale||1;
    e.priority='medium';
    e.networkPriority='medium';
    try{sender.track.contentHint='motion';}catch(_e){}
    try{p.degradationPreference='maintain-framerate';}catch(_e){}
    await sender.setParameters(p);
    return true;
  }catch(e){
    return false;
  }
}

async function setPeerVideoBitrate(peer,bitrate,reason=''){
  if(!peer) return;
  const sender=peer.videoSender||getVideoSenderFromPc(peer.pc);
  if(!sender) return;
  bitrate=Math.max(VOICE_VIDEO.min,Math.min(VOICE_VIDEO.max,bitrate|0));
  if(peer._videoBitrate===bitrate&&peer._videoScale) return;
  peer._videoBitrate=bitrate;
  let scale=1,fps=Math.max(30,Math.min(VOICE.streamFps||30,60));
  // Плавность важнее резкости: держим FPS, а при плохой сети уменьшаем разрешение.
  if(bitrate<=VOICE_VIDEO.min){scale=1.8;}
  else if(bitrate<=VOICE_VIDEO.low){scale=1.45;}
  else if(bitrate<=VOICE_VIDEO.mid){scale=1.18;}
  else {scale=1;}
  peer._videoScale=scale;
  await applyVideoSenderParams(sender,bitrate,{scale,fps});
  if(reason&&VOICE.roomId) voiceSetStatusText(voiceBitrateStatus('video',bitrate));
}

async function addOrReplaceVoiceVideoTrack(peer,track,stream){
  if(!peer||!peer.pc||!track) return;
  const pc=peer.pc;
  let sender=peer.videoSender||getVideoSenderFromPc(pc);
  try{
    if(sender){
      await sender.replaceTrack(track);
    }else{
      sender=pc.addTrack(track,stream||new MediaStream([track]));
    }
    peer.videoSender=sender;
    peer._videoBitrate=peer._videoBitrate||voiceVideoInitialBitrate();
    await setPeerVideoBitrate(peer,peer._videoBitrate,'video-start');
  }catch(e){
    console.warn('add/replace video track failed:',e);
  }
}

function startPeerBitrateMonitor(peer,uid){
  if(!peer||!peer.pc) return;
  if(peer._bitrateTimer) clearInterval(peer._bitrateTimer);
  peer._goodNet=0;
  peer._audioBitrate=peer._audioBitrate||voiceInitialBitrate();
  peer._bitrateTimer=setInterval(async()=>{
    if(!VOICE.roomId||!VOICE.peers[uid]||!peer.pc||peer.pc.connectionState==='closed') return;
    try{
      const stats=await peer.pc.getStats();
      let rtt=0,loss=0,avail=0;
      stats.forEach(st=>{
        if(st.type==='remote-inbound-rtp'&&(st.kind==='audio'||st.mediaType==='audio')){
          if(typeof st.roundTripTime==='number') rtt=Math.max(rtt,st.roundTripTime);
          if(typeof st.fractionLost==='number') loss=Math.max(loss,st.fractionLost);
        }
        if(st.type==='candidate-pair'&&st.state==='succeeded'&&st.nominated){
          if(typeof st.currentRoundTripTime==='number') rtt=Math.max(rtt,st.currentRoundTripTime);
          if(typeof st.availableOutgoingBitrate==='number') avail=Math.max(avail,st.availableOutgoingBitrate);
        }
      });
      let target=peer._audioBitrate||VOICE_AUDIO.mid;
      if((avail&&avail<70000)||rtt>0.65||loss>0.10){
        target=VOICE_AUDIO.min;peer._goodNet=0;
      }else if((avail&&avail<120000)||rtt>0.38||loss>0.04){
        target=VOICE_AUDIO.low;peer._goodNet=0;
      }else if(rtt>0.22||loss>0.015){
        target=VOICE_AUDIO.mid;peer._goodNet=0;
      }else{
        peer._goodNet=(peer._goodNet||0)+1;
        if(peer._goodNet>=3) target=Math.min(voiceInitialBitrate(),VOICE_AUDIO.high);
      }
      await setPeerAudioBitrate(peer,target,'stats');

      const hasVideo=!!(peer.videoSender||getVideoSenderFromPc(peer.pc));
      if(hasVideo){
        let vt=peer._videoBitrate||voiceVideoInitialBitrate();
        const veryBad=(avail&&avail<900000)||rtt>0.85||loss>0.16;
        const bad=(avail&&avail<1600000)||rtt>0.55||loss>0.08;
        const midBad=(avail&&avail<2800000)||rtt>0.35||loss>0.035;
        if(veryBad){peer._badVideo=(peer._badVideo||0)+1;peer._goodVideo=0;if(peer._badVideo>=2) vt=VOICE_VIDEO.min;}
        else if(bad){peer._badVideo=(peer._badVideo||0)+1;peer._goodVideo=0;if(peer._badVideo>=2) vt=VOICE_VIDEO.low;}
        else if(midBad){peer._badVideo=0;peer._goodVideo=0;vt=Math.min(vt,VOICE_VIDEO.mid);}
        else{
          peer._badVideo=0;
          peer._goodVideo=(peer._goodVideo||0)+1;
          if(peer._goodVideo>=4) vt=Math.max(vt,Math.min(voiceVideoInitialBitrate(),VOICE_VIDEO.high));
          if(peer._goodVideo>=8&&!isMobileLike()) vt=VOICE_VIDEO.max;
        }
        await setPeerVideoBitrate(peer,vt,'video-stats');
      }
    }catch(e){}
  },VOICE_AUDIO.monitorMs);
}

function stopPeerBitrateMonitor(peer){
  if(peer?._bitrateTimer){clearInterval(peer._bitrateTimer);peer._bitrateTimer=null;}
}

// Floating player state
const FP={
  queue:[],currentIdx:0,audio:null,playing:false,muted:false,volume:1,
  shuffle:false,repeat:false,minimized:false,
};


const STATUS_META={
  online:{label:'В сети',icon:'⬤',color:'var(--status-online)',i18n:'presence.online'},
  away:{label:'Отошёл',icon:'◌',color:'var(--status-away)',i18n:'presence.idle'},
  dnd:{label:'Не беспокоить',icon:'⛔',color:'var(--status-dnd)',i18n:'presence.dnd'},
  invisible:{label:'Невидимый',icon:'○',color:'var(--status-invisible)',i18n:'presence.invisible'},
  offline:{label:'Не в сети',icon:'●',color:'var(--status-offline)',i18n:'presence.offline'},
};
// Подставляем локализованные метки статусов и обновляем их при смене языка.
function refreshStatusMetaLabels(){
  try{
    if(!window.I18N) return;
    for(const k in STATUS_META){
      const key=STATUS_META[k].i18n;
      if(key) STATUS_META[k].label=I18N.t(key);
    }
  }catch(e){}
}
refreshStatusMetaLabels();
document.addEventListener('i18n:changed',()=>{ try{ refreshStatusMetaLabels(); if(window.updateUserPanel) updateUserPanel(); if(window.syncLangSelectors) syncLangSelectors(); if(typeof applyVoiceStageI18n==='function') applyVoiceStageI18n(); if(typeof renderVoiceWorkspace==='function') renderVoiceWorkspace(); }catch(e){} });

const ARCHIVE_EXTS=/\.(zip|rar|7z|tar|gz|bz2|tar\.gz|tar\.bz2)$/i;
const AUDIO_EXTS=/\.(mp3|ogg|wav|flac|aac|m4a|opus)$/i;
const TEXT_EXTS=/\.(txt|md|json|csv|log|xml|yaml|yml)$/i;
function isArchive(url){return ARCHIVE_EXTS.test(url||'');}
function isVideo(url){return /\.(mp4|webm)/i.test(url||'');}
function isAudio(url){return AUDIO_EXTS.test(url||'');}
function isTextFile(url){return TEXT_EXTS.test(url||'');}
function archiveIcon(ext){const e=(ext||'').toLowerCase();if(e==='zip'||e==='gz'||e==='bz2') return '🗜';return ti('archive',14);}

// iOS Audio unlock
['touchstart','click','keydown'].forEach(ev=>{
  document.addEventListener(ev,function unlock(){
    if(!S.audioCtx){try{S.audioCtx=new(window.AudioContext||window.webkitAudioContext)();}catch(e){}}
    if(S.audioCtx?.state==='suspended') S.audioCtx.resume().catch(()=>{});
    document.removeEventListener(ev,unlock);
  },{once:true,passive:true});
});

function getAC(){
  if(!S.audioCtx){try{S.audioCtx=new(window.AudioContext||window.webkitAudioContext)();}catch(e){return null;}}
  if(S.audioCtx.state==='suspended') S.audioCtx.resume().catch(()=>{});
  return S.audioCtx;
}

function playSound(t){
  if(localStorage.getItem('soundMute')==='1') return;
  try{
    const ctx=getAC();if(!ctx) return;
    const osc=ctx.createOscillator(),g=ctx.createGain();
    osc.connect(g);g.connect(ctx.destination);
    const n=ctx.currentTime;
    if(t==='message'){osc.type='sine';osc.frequency.setValueAtTime(880,n);osc.frequency.exponentialRampToValueAtTime(660,n+.2);g.gain.setValueAtTime(.2,n);g.gain.exponentialRampToValueAtTime(.001,n+.25);osc.start(n);osc.stop(n+.25);}
    else if(t==='mention'){osc.type='sine';osc.frequency.setValueAtTime(1100,n);osc.frequency.setValueAtTime(880,n+.1);g.gain.setValueAtTime(.3,n);g.gain.exponentialRampToValueAtTime(.001,n+.35);osc.start(n);osc.stop(n+.35);}
    else if(t==='dm'){osc.type='triangle';osc.frequency.setValueAtTime(660,n);osc.frequency.exponentialRampToValueAtTime(440,n+.3);g.gain.setValueAtTime(.18,n);g.gain.exponentialRampToValueAtTime(.001,n+.3);osc.start(n);osc.stop(n+.3);}
    else if(t==='ringtone'){osc.type='sine';osc.frequency.setValueAtTime(960,n);osc.frequency.setValueAtTime(800,n+.15);osc.frequency.setValueAtTime(960,n+.3);osc.frequency.setValueAtTime(800,n+.45);g.gain.setValueAtTime(.0,n);g.gain.linearRampToValueAtTime(.35,n+.05);g.gain.setValueAtTime(.35,n+.55);g.gain.exponentialRampToValueAtTime(.001,n+.65);osc.start(n);osc.stop(n+.65);}
    else if(t==='call_accepted'){osc.type='sine';osc.frequency.setValueAtTime(600,n);osc.frequency.exponentialRampToValueAtTime(900,n+.15);g.gain.setValueAtTime(.25,n);g.gain.exponentialRampToValueAtTime(.001,n+.3);osc.start(n);osc.stop(n+.3);}
    else if(t==='call_ended'){osc.type='sine';osc.frequency.setValueAtTime(500,n);osc.frequency.exponentialRampToValueAtTime(300,n+.3);g.gain.setValueAtTime(.2,n);g.gain.exponentialRampToValueAtTime(.001,n+.35);osc.start(n);osc.stop(n+.35);}
    else if(t==='voice_join'){osc.type='sine';osc.frequency.setValueAtTime(440,n);osc.frequency.exponentialRampToValueAtTime(660,n+.2);g.gain.setValueAtTime(.15,n);g.gain.exponentialRampToValueAtTime(.001,n+.3);osc.start(n);osc.stop(n+.3);}
    else if(t==='voice_leave'){osc.type='sine';osc.frequency.setValueAtTime(660,n);osc.frequency.exponentialRampToValueAtTime(330,n+.25);g.gain.setValueAtTime(.15,n);g.gain.exponentialRampToValueAtTime(.001,n+.3);osc.start(n);osc.stop(n+.3);}
  }catch(e){}
}
function startRingtone(){if(DMCALL.ringing) return;DMCALL.ringing=true;let cnt=0;const ring=()=>{if(!DMCALL.ringing) return;playSound('ringtone');cnt++;if(cnt<15) DMCALL.ringtoneTimer=setTimeout(ring,1800);};ring();}
function stopRingtone(){DMCALL.ringing=false;clearTimeout(DMCALL.ringtoneTimer);DMCALL.ringtoneTimer=null;}

// ── EMOJI DATA ──────────────────────────────────────────────
const ECATS=[
  {e:'😀',n:'Смайлы',list:['😀','😃','😄','😁','😆','😅','😂','🤣','🥲','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😋','😛','😝','😜','🤪','😎','🥸','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳','🥵','🥶','😱','😨','😰','😥','🤗','🤔','🤭','🤫','🤥','😶','😐','😑','😬','🙄','😯','😦','😧','😮','😲','🥱','😴','😵','🤐','🥴','🤢','🤮','🤧','😷','🤒','🤕','🤑','🤠','😈','👿','💩','👻','💀','👽','🤖','😺','😸','😹','😻','😼','😽','🙀','😿','😾']},
  {e:'👋',n:'Жесты',list:['👋','🤚','🖐','✋','🖖','👌','🤌','🤏','✌','🤞','🤟','🤘','🤙','👈','👉','👆','👇','👍','👎','✊','👊','🤛','🤜','👏','🙌','👐','🤲','🤝','🙏','✍','💅','💪','🦾','🦵','🦶','👂','🦻','👃','👀','👅','👄','💋']},
  {e:'❤️',n:'Сердца',list:['❤','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣','💕','💞','💓','💗','💖','💘','💝','💟']},
  {e:'🔥',n:'Символы',list:['🔥','⭐','🌟','💫','✨','⚡','🌈','🎉','🎊','🎈','🎁','🏆','🥇','🎯','🎮','🎲','🧩','💡','🔑','⚙','⚔','🛡','🏹','📜','📝','🌙','☀','🌊','💎','👑','🔮','🪄','🗡','📯','🔔','🎵','🎶','🌌','🌙','☄','🌀','🌪']},
  {e:'⚔️',n:'TES/Фэнтези',list:['⚔','🗡','🛡','🏹','🧙','🧝','🧛','🧟','🧞','🐉','🦄','🏰','🏯','🗺','📜','🔮','🪄','💀','☠','👑','💎','🌑','🌕','🌌','🔭','🗝','⚗','🧿','🪬','🦅','🦉','🦇','🐺','🦊','🧪','🌿','🍄','🗿','⛩']},
  {e:'🐱',n:'Животные',list:['🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮','🐷','🐸','🐵','🙈','🙉','🙊','🐔','🐧','🦆','🦅','🦉','🦇','🐺','🐴','🦄','🐉','🦋','🐌','🐞','🐜','🦗','🦂','🦈','🐬','🐳','🐙','🦑','🦐','🦀','🦞','🐡','🐠','🐟','🦢','🦩','🕊','🐇','🦔']},
  {e:'🍕',n:'Еда',list:['🍎','🍊','🍋','🍌','🍍','🥭','🍑','🍒','🍓','🫐','🥝','🥥','🥑','🌽','🌶','🍄','🍞','🥐','🧀','🥚','🍳','🥞','🥓','🥩','🍗','🍖','🌭','🍔','🍟','🍕','🌮','🌯','🍜','🍣','🍱','🍣','🍤','🍙','🍚','🍛','🍝','🦪','🍦','🍧','🍨','🍩','🍪','🎂','🍰','🧁','🍫','🍬','🍭','🍯','☕','🍵','🧃','🥤','🧋','🍺','🍻','🥂','🍷','🍸','🍹','🧉','🍾']},
  {e:'🌍',n:'Природа',list:['🌍','🌎','🌏','🏔','⛰','🌋','🏕','🏖','🏜','🏝','🌅','🌄','🌠','🌃','🌆','🌇','🌌','🌉','☀','🌤','⛅','🌦','🌧','⛈','🌩','🌨','❄','🌬','💨','💧','💦','🌊','🌫','🌈','⚡','🔥','🌀','🌪','🌡','🌙','🌛','🌜','🌝','🌞','⭐','🌟','💫','✨','☄','🪐']},
  {e:'🚗',n:'Транспорт',list:['🚗','🚕','🚙','🚌','🏎','🚓','🚑','🚒','🚚','🚜','🏍','🛵','🚲','🛴','🛹','✈','🛩','🚁','🚀','🛸','⛵','🚤','🛥','🚢','🚂','🚄','🚅','🚇','🚌','🛰','🪂','⚓','🛟']},
  {e:'🏆',n:'Спорт',list:['⚽','🏀','🏈','⚾','🎾','🏐','🏉','🎱','🏓','🏸','🥊','🥋','🎯','⛳','🎣','🏆','🥇','🥈','🥉','🏅','🎖','🎳','🏋','🤼','🤸','🏄','🚴','🏊','🧘','🧗','🚵','🤺','⛷','🏂','🤾']},
  {e:'💼',n:'Офис',list:['💼','📁','📂','📊','📈','📉','📋','📌','📎','✂','📝','📓','📔','📒','📕','📗','📘','📙','📚','📖','🔖','💰','💵','💶','💷','💸','💳','🧾','✉','📧','📨','📩','📦','📜','📃','📄','📑','📅','📆','📇','🖥','💻','📱','⌨','🖱','💾','💿','📀','🖨','📡','🔋','🔌']},
  {e:'🔧',n:'Инструменты',list:['🔧','🪛','🔨','🪚','⚒','🛠','⛏','🪝','⚙','🔩','🧲','🔑','🗝','🔐','🔏','🔓','🔒','🪜','🧰','🔦','💡','🧪','🧫','🧬','🔬','🔭','🩺','⚗','🧯','🛒']},
];

const QUICK_REACTIONS=['👍','👎','❤','🤣','🥲','😊','😢','🔥'];

// ── MARKDOWN ────────────────────────────────────────────────
function applyMarkdown(rawText){
  let h=String(rawText||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  h=h.replace(/```([\s\S]*?)```/g,'<pre><code>$1</code></pre>');
  h=h.replace(/`([^`\n]+)`/g,'<code>$1</code>');
  h=h.replace(/\*\*(.+?)\*\*/gs,'<strong>$1</strong>');
  h=h.replace(/\*(.+?)\*/gs,'<em>$1</em>');
  h=h.replace(/~~(.+?)~~/gs,'<del>$1</del>');
  h=h.replace(/\|\|(.+?)\|\|/gs,'<span class="spoiler" onclick="this.classList.toggle(\'revealed\')">$1</span>');
  h=h.replace(/(^|\n)&gt; ([^\n]+)/g,'$1<blockquote>$2</blockquote>');
  h=h.replace(/(https?:\/\/[^\s<>"'&]+)/g,'<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
  h=h.replace(/@(everyone|here)/g,'<span class="mention" style="color:var(--gold);background:rgba(201,170,113,.15)">@$1</span>');
  h=h.replace(/(^|[^\wА-Яа-яЁё_.-])@([A-Za-zА-Яа-яЁё0-9_.-]{2,32})/g,(full,prefix,name)=>`${prefix}<span class="mention" data-mention="${escAttr(name)}">@${name}</span>`);
  return h;

}
function findUserByMentionName(name){
  name=String(name||'').replace(/^@/,'').toLowerCase();
  if(!name) return null;
  const pools=[S.members||[],S.allUsers||[],S.servers?.flatMap?.(()=>[])||[]];
  for(const pool of pools){
    for(const u of pool||[]){
      if(String(u.name||'').toLowerCase()===name) return u;
    }
  }
  return null;
}
window.openMentionProfileByName=function(name){
  const u=findUserByMentionName(name);
  if(u&&u.id){showUserProfile(u.id);return;}
  toast('Пользователь не найден в текущем списке','err');
};
document.addEventListener('click',e=>{
  const mention=e.target.closest?.('.msg-text .mention[data-mention]');
  if(!mention) return;
  e.preventDefault();
  e.stopPropagation();
  openMentionProfileByName(mention.dataset.mention||mention.textContent||'');
},true);

// ── FORMAT TOOLBAR ──────────────────────────────────────────
function setupFormatToolbar(){
  const tb=q('formatToolbar');
  const formats=[
    {icon:'<b>B</b>',title:'Жирный',before:'**',after:'**'},
    {icon:'<i>I</i>',title:'Курсив',before:'*',after:'*'},
    {icon:'<del>S</del>',title:'Зачёркнутый',before:'~~',after:'~~'},
    {icon:'<code>`</code>',title:'Код',before:'`',after:'`'},
    {icon:'```',title:'Блок кода',before:'```\n',after:'\n```'},
    {icon:'||',title:'Спойлер',before:'||',after:'||'},
    null,
    {icon:'&gt;',title:'Цитата',before:'> ',after:'',prefix:true},
  ];
  formats.forEach(f=>{
    if(!f){const sep=document.createElement('div');sep.className='fmt-sep';tb.appendChild(sep);return;}
    const btn=document.createElement('button');btn.type='button';btn.className='fmt-btn';btn.title=f.title;btn.innerHTML=f.icon;
    btn.onmousedown=e=>{e.preventDefault();applyFormat(f.before,f.after,f.prefix||false);};
    tb.appendChild(btn);
  });
  ['msgInput','dmInput'].forEach(id=>{
    const ta=document.getElementById(id);if(!ta) return;
    const show=()=>checkSelectionForFormat(ta);
    ta.addEventListener('mouseup',show);ta.addEventListener('keyup',show);ta.addEventListener('select',show);
    ta.addEventListener('blur',()=>{setTimeout(()=>{if(!q('formatToolbar').matches(':hover')) hideFormatToolbar();},150);});
  });
  document.addEventListener('mousedown',e=>{if(!e.target.closest('#formatToolbar')) hideFormatToolbar();});
}
function checkSelectionForFormat(ta){
  const s=ta.selectionStart,e=ta.selectionEnd;
  if(s===e){hideFormatToolbar();return;}
  S._fmtTextarea=ta;
  const tb=q('formatToolbar');tb.classList.add('show');
  const rect=ta.getBoundingClientRect();
  let top=rect.top-40;if(top<4) top=rect.bottom+4;
  let left=Math.max(4,Math.min(rect.left+4,window.innerWidth-240));
  tb.style.top=top+'px';tb.style.left=left+'px';
}
function hideFormatToolbar(){q('formatToolbar').classList.remove('show');}
function applyFormat(before,after,prefix){
  const ta=S._fmtTextarea;if(!ta) return;
  const start=ta.selectionStart,end=ta.selectionEnd;
  const selected=ta.value.substring(start,end);
  let newText;
  if(prefix){const lines=selected.split('\n').map(l=>before+l).join('\n');newText=ta.value.substring(0,start)+lines+ta.value.substring(end);ta.value=newText;ta.selectionStart=start;ta.selectionEnd=start+lines.length;}
  else{newText=ta.value.substring(0,start)+before+selected+after+ta.value.substring(end);ta.value=newText;ta.selectionStart=start+before.length;ta.selectionEnd=end+before.length;}
  ta.focus();autoGrow(ta);hideFormatToolbar();
}

// ── MENTION AUTOCOMPLETE ────────────────────────────────────
let _ms={active:false,query:'',startPos:0,selIdx:0,items:[],textarea:null};
function onMsgInput(ta){autoGrow(ta);checkMentionTrigger(ta);sendTypingSignal();}
function onDmInput(ta){autoGrow(ta);checkMentionTrigger(ta);}
// СТАЛО: \u0400-\u04FF добавляет поддержку кириллицы
function checkMentionTrigger(ta){
  const val=ta.value,pos=ta.selectionStart;
  const before=val.slice(0,pos);
  const match=before.match(/@([\w\u0400-\u04FF]*)$/);
  if(match){_ms.textarea=ta;_ms.startPos=match.index;_ms.query=match[1].toLowerCase();showMentionSuggest(ta);}
  else hideMentionSuggest();
}

// СТАЛО: используем S.members (участники текущего сервера), а не S.allUsers
function getMentionUsers(){
    const list = S.members.length > 0 ? S.members : S.allUsers;
    return list.filter(u => u.id !== S.me?.id);
}

function showMentionSuggest(ta){
  const q2 = _ms.query;
  const isMod2 = isMod(S.myRole)||S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';

  const specials = isMod2
    ? [{id:-1,name:'everyone',avatar:''},{id:-2,name:'here',avatar:''}]
        .filter(s => !q2 || s.name.startsWith(q2))
    : [];

  const allUsers = getMentionUsers();

  const users = q2 === ''
    ? allUsers.slice(0, 30)
    : allUsers
        .filter(u => {
          const name = u.name.toLowerCase();
          const query = q2.toLowerCase();
          return name.startsWith(query) || name.includes(query);
        })
        .sort((a, b) => {
          const q3 = q2.toLowerCase();
          const aStarts = a.name.toLowerCase().startsWith(q3);
          const bStarts = b.name.toLowerCase().startsWith(q3);
          if (aStarts && !bStarts) return -1;
          if (!aStarts && bStarts) return 1;
          return a.name.localeCompare(b.name);
        })
        .slice(0, 25);

  const all = [...specials, ...users];
  if (!all.length) { hideMentionSuggest(); return; }

  _ms.items = all;
  const ms = q('mentionSuggest');
  ms.innerHTML = '';

  const header = document.createElement('div');
  header.className = 'ms-header';
  if (q2) {
    header.innerHTML = `${ti('search',14)} "${q2}" — найдено: ${all.length}`;
  } else {
    header.innerHTML = `<span class="ti" style="display:inline-flex;align-items:center;margin-right:6px" ><svg viewBox="0 0 24 24" width="14" height="14"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span>${t('common.members')} (${allUsers.length})`;
  }
  ms.appendChild(header);

  if (all.length === 0) {
    const empty = document.createElement('div');
    empty.className = 'ms-empty';
    empty.textContent = 'Никого не найдено';
    ms.appendChild(empty);
  }

  all.forEach((u, i) => {
    const online = S.onlineUsers.find(x => x.id === u.id);
    const stCls = online ? 'st-'+(online.status||'online') : 'st-offline';
    const av = u.avatar && u.avatar.startsWith('http')
      ? `<img src="${u.avatar}" alt="">`
      : (u.name[0] || '?');
    const d = document.createElement('div');
    d.className = 'mention-item' + (i === _ms.selIdx ? ' active' : '');

    let displayName = esc(u.name);
    if (q2 && u.id > 0) {
      const idx = u.name.toLowerCase().indexOf(q2.toLowerCase());
      if (idx !== -1) {
        displayName =
          esc(u.name.slice(0, idx)) +
          `<mark style="background:var(--gold-dim);color:var(--gold);border-radius:2px">` +
          esc(u.name.slice(idx, idx + q2.length)) +
          `</mark>` +
          esc(u.name.slice(idx + q2.length));
      }
    }

    const label = u.id < 0
      ? `<span style="color:var(--gold);font-weight:700">@${esc(u.name)}</span>`
      : `<span class="mention-name">${displayName}</span>`;

    const onlineLabel = online
      ? `<span style="font-size:10px;color:var(--text3);flex-shrink:0">${online.status === 'online' ? '🟢' : online.status === 'away' ? '🟡' : '⚫'}</span>`
      : `<span style="font-size:10px;color:var(--text3);flex-shrink:0">⚫</span>`;

    d.innerHTML = `<div class="mention-av">${av}</div>${label}${onlineLabel}`;
    d.onmousedown = e => { e.preventDefault(); insertMention(u.name); };
    ms.appendChild(d);
  });

  const totalFiltered = q2
    ? allUsers.filter(u => u.name.toLowerCase().includes(q2.toLowerCase())).length
    : allUsers.length;
  if (totalFiltered > all.length) {
    const more = document.createElement('div');
    more.style.cssText = 'padding:6px 12px;font-size:11px;color:var(--text3);text-align:center;border-top:1px solid var(--border)';
    more.textContent = `...ещё ${totalFiltered - all.length} — уточните запрос`;
    ms.appendChild(more);
  }

  const rect = ta.getBoundingClientRect();
  const msMaxH = 360;
  let top = rect.top - msMaxH - 4;
  if (top < 8) top = rect.bottom + 4;
  let left = rect.left + 8;
  if (left + 320 > window.innerWidth - 8) left = window.innerWidth - 328;

  ms.style.top  = Math.max(8, top)  + 'px';
  ms.style.left = Math.max(8, left) + 'px';
  ms.classList.add('open');
  _ms.active = true;
  _ms.selIdx = 0;
}

function hideMentionSuggest(){q('mentionSuggest').classList.remove('open');_ms.active=false;_ms.selIdx=0;}
function insertMention(name){
  const ta=_ms.textarea;if(!ta) return;
  const val=ta.value,pos=ta.selectionStart;
  const before=val.slice(0,_ms.startPos);const after=val.slice(pos);
  ta.value=before+'@'+name+' '+after;
  ta.selectionStart=ta.selectionEnd=before.length+name.length+2;
  ta.focus();autoGrow(ta);hideMentionSuggest();
}
function mentionKeyNav(e){
  if(!_ms.active) return false;
  const items=_ms.items;if(!items.length) return false;
  if(e.key==='ArrowDown'){e.preventDefault();_ms.selIdx=(_ms.selIdx+1)%items.length;updateMentionSel();return true;}
  if(e.key==='ArrowUp'){e.preventDefault();_ms.selIdx=(_ms.selIdx-1+items.length)%items.length;updateMentionSel();return true;}
  if(e.key==='Enter'||e.key==='Tab'){e.preventDefault();insertMention(items[_ms.selIdx].name);return true;}
  if(e.key==='Escape'){hideMentionSuggest();return true;}
  return false;
}
function updateMentionSel(){q('mentionSuggest').querySelectorAll('.mention-item').forEach((el,i)=>el.classList.toggle('active',i===_ms.selIdx));}
document.addEventListener('click',e=>{if(!e.target.closest('#mentionSuggest')&&!e.target.closest('.msg-textarea')) hideMentionSuggest();});

// ── TYPING INDICATOR ────────────────────────────────────────
function sendTypingSignal(){
  if(!S.chId||S.mode!=='channel') return;
  clearTimeout(S.typingTimer);
  api({action:'set_typing',channelId:S.chId}).catch(()=>{});
  S.typingTimer=setTimeout(()=>{},5000);
}
async function pollTyping(){
  if(!S.chId||S.mode!=='channel') return;
  const r=await api({action:'get_typing',channelId:S.chId});
  if(!r.ok) return;
  const typers=r.typing||[];
  const bar=q('typingBar');
  if(typers.length===0){bar.innerHTML='';return;}
  const names=typers.slice(0,3).map(n=>`<strong>${esc(n)}</strong>`).join(', ');
  const suffix=typers.length>3?` и ещё ${typers.length-3}`:'';
  bar.innerHTML=`<div class="typing-dots"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div><span>${names}${suffix} печатает${typers.length>1?'ют':''}…</span>`;
}

// ── API ──────────────────────────────────────────────────────
async function api(data){
  if(S.me){data.userId=S.me.id;data.token=S.tok;}
  try{
    const r=await fetch(API,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data),cache:'no-cache'});
    if(!r.ok) return{ok:false,error:'HTTP '+r.status};
    return await r.json();
  }catch(e){return{ok:false,error:e.message};}
}
function apiUploadXHR(data,onProgress){
  return new Promise(resolve=>{
    if(S.me){data.userId=S.me.id;data.token=S.tok;}
    const xhr=new XMLHttpRequest();
    xhr.upload.onprogress=e=>{if(e.lengthComputable) onProgress(Math.round(e.loaded/e.total*100));};
    xhr.onload=()=>{try{resolve(JSON.parse(xhr.responseText));}catch(e){resolve({ok:false,error:'Parse error'});}};
    xhr.onerror=()=>resolve({ok:false,error:'Network error'});
    xhr.open('POST',API);xhr.setRequestHeader('Content-Type','application/json');
    xhr.send(JSON.stringify(data));
  });
}

// ── AUTH ─────────────────────────────────────────────────────
function setTab(t){
  if(t==='reg' && !APP_CFG.registrationOpen){toast(I18N.t('toast.regClosed'),'info');t='login';}
  q('fLogin').style.display=t==='login'?'':'none';
  q('fReg').style.display=t==='reg'?'':'none';
  document.querySelectorAll('.login-tab').forEach((el,i)=>{el.classList.toggle('active',(i===0&&t==='login')||(i===1&&t==='reg'));});
}
function focusAuthField(id){setTimeout(()=>q(id)?.focus(),40);}

async function doLogin(){
  const name=q('lName').value.trim(), pass=q('lPass').value;
  const rememberMe=q('rememberMeCheck')?.checked??false;
  q('lErr').textContent='';
  const r=await api({action:'login',name,pass});
  if(!r.ok){q('lErr').textContent=r.error;return;}
  startSession(r.user,r.token,rememberMe);
}

function passwordStrengthScore(pass){
  let score=0;
  if(pass.length>=8) score++;
  if(pass.length>=12) score++;
  if(/[a-zа-яё]/i.test(pass) && /\d/.test(pass)) score++;
  if(/[A-ZА-ЯЁ]/.test(pass) && /[a-zа-яё]/.test(pass)) score++;
  if(/[^A-Za-zА-Яа-яЁё0-9]/.test(pass)) score++;
  return Math.min(score,5);
}
function updatePasswordStrength(pass){
  const fill=q('pwStrengthFill'), text=q('pwStrengthText');
  if(!fill||!text) return;
  const s=passwordStrengthScore(pass||'');
  const pct=[0,20,40,60,80,100][s];
  fill.style.width=pct+'%';
  fill.className='pw-strength-fill';
  if(s>=4) fill.classList.add('strong');
  else if(s===3) fill.classList.add('good');
  else if(s===2) fill.classList.add('medium');
  if(!pass){text.textContent='Введите пароль: минимум 8 символов, буквы и цифры.';return;}
  if(pass.length<8){text.textContent='Слишком короткий пароль — нужно минимум 8 символов.';return;}
  if(s<3){text.textContent='Слабый пароль: добавьте цифры, разные буквы или символы.';return;}
  if(s<4){text.textContent='Нормальный пароль. Для усиления добавьте спецсимвол или длину 12+.';return;}
  text.textContent='Хороший пароль.';
}
async function doRegister(){
  const name=q('rName').value.trim(),pass=q('rPass').value;
  const termsOk=q('termsCheckbox')?.checked??true;
  q('rErr').textContent='';
  if(!APP_CFG.registrationOpen){q('rErr').textContent='Регистрация закрыта';return;}
  const minName=Math.max(4,APP_CFG.usernameMinLength||4);
  const minPass=Math.max(8,APP_CFG.passwordMinLength||8);
  const firstAdminBypass=!!APP_CFG.firstAdminBypassUsernameMinLength && (!!APP_CFG.firstRegisteredUserBecomesSuperAdmin || name===OWNER_NAME);
  if(!firstAdminBypass && name.length<minName){q('rErr').textContent=`Имя: минимум ${minName} символов`;return;}
  if(name.length>APP_CFG.usernameMaxLength){q('rErr').textContent=`Имя: максимум ${APP_CFG.usernameMaxLength} символов`;return;}
  if(!/^[A-Za-zА-Яа-яЁё0-9_.-]+$/.test(name)){q('rErr').textContent='Имя может содержать только буквы, цифры, _, -, точку';return;}
  if(pass.length<minPass){q('rErr').textContent=`Пароль: минимум ${minPass} символов`;return;}
  if(passwordStrengthScore(pass)<3){q('rErr').textContent='Пароль слишком слабый: добавьте цифры, буквы разного регистра или символы';return;}
  if(APP_CFG.requireTermsAcceptance && !termsOk){q('rErr').textContent='Необходимо принять Пользовательское соглашение';return;}
  const r=await api({action:'register',name,pass,termsAccepted:termsOk});
  if(!r.ok){q('rErr').textContent=r.error;return;}
  startSession(r.user,r.token,true);
}

// ── Fullscreen safety ──────────────────────────────────────────
// Если открывается любое модальное/плавающее окно поверх fullscreen,
// сначала выходим из fullscreen. Иначе браузер может оставить старый
// полноэкранный элемент поверх нового окна или заблокировать управление.
function exitFullscreenForWindowOpen(){
  try{
    if(document.fullscreenElement && document.exitFullscreen){
      document.exitFullscreen().catch(()=>{});
    }
  }catch(e){}
}
function isAnyFullscreen(){return !!document.fullscreenElement;}

function showTermsModal(){
  const lang=(window.I18N?.getLang?.()||'').slice(0,2).toLowerCase();
  // Prefer translated agreement; fall back to the server-provided (config) text for the current locale.
  const txt=i18nt('legal.termsHtml','')||q('regTermsText')?.innerHTML||'';
  const title=i18nt('legal.termsTitle','')||APP_CFG.registrationTermsTitle||'Пользовательское соглашение';
  const note=i18nt('legal.modalNote','')||APP_CFG.registrationModalNote||'';
  showModal(`<h2>${ti("scroll",18)} ${esc(title)}</h2><div class="terms-scroll" style="max-height:400px">${txt}</div>${note?`<div style="margin-top:10px;padding:8px 10px;background:var(--bg0);border-radius:var(--radius-sm);font-size:11px;color:var(--text3);line-height:1.7">${esc(note)}</div>`:''}<button class="btn btn-ghost btn-full" style="margin-top:10px" onclick="closeModal()">${t('common.close')}</button>`);
}
function startSession(user, token, rememberMe=false){
  S.me=user; S.tok=token; S.myStatus=user.status||'online'; S.rememberMe=rememberMe;
  const sessData={user,token};
  if(rememberMe){
    localStorage.setItem('sess',JSON.stringify(sessData));
    localStorage.setItem('rememberMe','1');
    sessionStorage.removeItem('sess');
  } else {
    sessionStorage.setItem('sess',JSON.stringify(sessData));
    localStorage.removeItem('sess');
    localStorage.removeItem('rememberMe');
  }
  q('loginScreen').style.display='none';
  q('app').style.display='flex';
  const pendingInv=sessionStorage.getItem('pendingInvite');
  if(pendingInv) sessionStorage.removeItem('pendingInvite');
  initApp().then(()=>{if(pendingInv) acceptPendingInviteAfterAuth(pendingInv);});
}
function clearAllSessions(){
  localStorage.removeItem('sess');
  localStorage.removeItem('rememberMe');
  sessionStorage.removeItem('sess');
}

function updateStoredSession(userObj){
  if(!S.me) return;
  if(S.rememberMe){
    const stored=JSON.parse(localStorage.getItem('sess')||'{}');
    stored.user=userObj||S.me;
    localStorage.setItem('sess',JSON.stringify(stored));
  } else {
    const stored=JSON.parse(sessionStorage.getItem('sess')||'{}');
    stored.user=userObj||S.me;
    sessionStorage.setItem('sess',JSON.stringify(stored));
  }
}

async function verifyAndInit(token, savedPrevStatus=null){
  const r=await api({action:'verify_session'});
  if(!r.ok){
    clearAllSessions();
    q('loginScreen').style.display='flex';
    q('app').style.display='none';
    if(r.error&&r.error!=='Не авторизован') q('lErr').textContent=r.error;
    return;
  }
  S.me=r.user;

  if(S.rememberMe && savedPrevStatus && savedPrevStatus!=='invisible'){
    S.myStatus=savedPrevStatus;
    const stored=JSON.parse(localStorage.getItem('sess')||'{}');
    delete stored.prevStatus;
    localStorage.setItem('sess',JSON.stringify(stored));
    api({action:'set_status',status:S.myStatus}).catch(()=>{});
    toast(I18N.t('toast.statusRestored',{label:STATUS_META[S.myStatus]?.label||S.myStatus}),'info',2000);
  } else {
    S.myStatus=r.user.status||'online';
  }

  updateStoredSession(r.user);
  updateUserPanel();
  const pendingInv=sessionStorage.getItem('pendingInvite');
  if(pendingInv) sessionStorage.removeItem('pendingInvite');
  await initApp();
  if(pendingInv) acceptPendingInviteAfterAuth(pendingInv);
}


async function doLogout(){
  if(window.__tcLogoutInProgress) return;
  window.__tcLogoutInProgress=true;
  saveLocation();
  try{ if(VOICE.roomId!==null) await voiceLeave(true); }catch(e){}
  try{ if(DMCALL.active) await dmCallHangup(true); }catch(e){}
  try{ await api({action:'logout',token:S.tok}); }catch(e){}
  clearAllSessions();
  location.reload();
}
window.robustLogout=function(ev){
  try{ ev?.preventDefault?.(); ev?.stopPropagation?.(); }catch(e){}
  try{ closeModal(); }catch(e){}
  setTimeout(()=>doLogout(),0);
  return false;
};


// ── INIT ─────────────────────────────────────────────────────
async function initApp(){
  // ★ Restore saved font size and theme on startup
  const savedFont=localStorage.getItem('tes3FontSize');
  if(savedFont && savedFont!=='15'){
    // Defer to ensure setFontSizeLive is defined
    setTimeout(()=>{if(window.setFontSizeLive) setFontSizeLive(savedFont);},0);
  }
  const _rawSavedTheme=localStorage.getItem('tes3Theme')||'';
  if(_rawSavedTheme){
    setThemeLive(normalizeThemeChoice(_rawSavedTheme));
  } else {
    // No saved choice → use the configured default theme (dark trueCORD / truecolor).
    setThemeLive(normalizeThemeChoice(APP_CFG.defaultTheme||'truecolor'));
  }
  // Эффекты (динамический фон) выключены по умолчанию.
  // Включаются только если пользователь явно сохранил выбор ('1').
  // В мобильном виде эффекты всегда выключены, независимо от сохранённого выбора.
  // «Живая анимация» удалена как функция — слой никогда не включается.
  const _effMobileOff = isMobileLike();
  if(_effMobileOff || localStorage.getItem('tes3DynBg')!=='1') document.body.classList.add('dynbg-off');
  updateUserPanel();buildEmojiPicker();setupFormatToolbar();setupDragDrop();setupClipboardPaste();
  // iOS Safari (вкладка браузера) не имеет Notification — обращение к bare-идентификатору
  // выбрасывало ReferenceError и обрывало инициализацию (пустой бар, нет голоса, нет ленты).
  try{
    if(typeof Notification!=="undefined"&&Notification&&Notification.permission==='default'){
      const p=Notification.requestPermission();
      if(p&&typeof p.catch==='function') p.catch(()=>{});
    }
  }catch(e){}
  // ★ АУДИО FIX: загружаем ICE серверы ПЕРВЫМ ДЕЛОМ
  await loadIceServers();
  await loadUsers();await loadServers();
  startPoll();handleHash();
  await restoreLocation();
  // Первый быстрый опрос после восстановления экрана: обновляет бейджи каналов и ЛС без клика.
  await refreshCurrentServerUnreadNow().catch(()=>{});
  await loadDmConvs().catch(()=>{});
  doPoll().catch(()=>{});
  initFloatingPlayer();
}
// ── CLIPBOARD PASTE ──────────────────────────────────────────
function setupClipboardPaste(){
  const handlePaste=async(e,mode)=>{
    const items=e.clipboardData?.items;
    if(!items||!items.length) return;

    const imageItems=Array.from(items).filter(it=>it.kind==='file'&&it.type.startsWith('image/'));
    const fileItems=Array.from(items).filter(it=>it.kind==='file'&&!it.type.startsWith('image/'));

    if(!imageItems.length&&!fileItems.length) return;

    e.preventDefault();

    // Изображения из буфера (скриншоты)
    for(const item of imageItems){
      const raw=item.getAsFile();
      if(!raw) continue;
      const ext=item.type.split('/')[1]||'png';
      const named=new File([raw],`screenshot_${Date.now()}.${ext}`,{type:item.type});
      toast(I18N.t('toast.pasteImage'),'info',1800);
      await processFileForUpload(named,mode);
    }

    // Обычные файлы из буфера
    for(const item of fileItems){
      const raw=item.getAsFile();
      if(!raw) continue;
      await processFileForUpload(raw,mode);
    }
  };

  // Вставка в поле сообщения канала
  const msgInput=document.getElementById('msgInput');
  if(msgInput) msgInput.addEventListener('paste',e=>handlePaste(e,'channel'));

  // Вставка в поле ЛС
  const dmInput=document.getElementById('dmInput');
  if(dmInput) dmInput.addEventListener('paste',e=>handlePaste(e,'dm'));

  // Глобальная вставка (когда фокус не в textarea, но пользователь нажал Ctrl+V)
  document.addEventListener('paste',e=>{
    if(e.target.closest('#msgInput, #dmInput, .fi, .comment-input, .dm-search-inp')) return;
    const items=e.clipboardData?.items;
    if(!items) return;
    const hasFile=Array.from(items).some(it=>it.kind==='file');
    if(!hasFile) return;
    const mode=S.mode==='dm'?'dm':'channel';
    if(mode==='channel'&&!S.chId) return;
    if(mode==='dm'&&!S.dmUid) return;
    handlePaste(e,mode);
  });
}

// ── DRAG DROP ────────────────────────────────────────────────
function setupDragDrop(){
  const dz=q('dropZone');let dragDepth=0;
  const isFileDrag=e=>!!e.dataTransfer?.types && Array.prototype.includes.call(e.dataTransfer.types,'Files') && !_srvDragActive;
  document.addEventListener('dragenter',e=>{if(!isFileDrag(e)) return;dragDepth++;dz.classList.add('active');e.preventDefault();});
  document.addEventListener('dragleave',e=>{if(_srvDragActive) return;dragDepth--;if(dragDepth<=0){dragDepth=0;dz.classList.remove('active');}});
  document.addEventListener('dragover',e=>{if(_srvDragActive) return;if(isFileDrag(e)) e.preventDefault();});
  document.addEventListener('drop',async e=>{
    if(_srvDragActive){dz.classList.remove('active');dragDepth=0;return;}
    if(!isFileDrag(e)){dz.classList.remove('active');dragDepth=0;return;}
    e.preventDefault();dragDepth=0;dz.classList.remove('active');
    const files=Array.from(e.dataTransfer?.files||[]);if(!files.length) return;
    const mode=S.mode==='dm'?'dm':'channel';
    for(const file of files) await processFileForUpload(file,mode);
  });
}

// ── MULTI-FILE UPLOAD ────────────────────────────────────────
async function handleMultiUpload(input,mode){
  const files=Array.from(input.files||[]);if(!files.length) return;
  input.value='';
  for(const file of files) await processFileForUpload(file,mode);
}
async function processFileForUpload(file,mode){
  let mime=file.type||'application/octet-stream';
  // Fallback по расширению — 7z/rar/tar браузеры часто отдают как octet-stream
  const _ext=(file.name.split('.').pop()||'').toLowerCase();
  const EXT_MIME_MAP={
    '7z':'application/x-7z-compressed',
    'rar':'application/vnd.rar',
    'zip':'application/zip',
    'tar':'application/x-tar',
    'gz':'application/gzip',
    'bz2':'application/x-bzip2',
    'mp3':'audio/mpeg',
    'ogg':'audio/ogg',
    'wav':'audio/wav',
    'flac':'audio/flac',
    'aac':'audio/aac',
    'm4a':'audio/x-m4a',
    'opus':'audio/opus',
    'mp4':'video/mp4',
    'webm':'video/webm',
    'jpg':'image/jpeg',
    'jpeg':'image/jpeg',
    'png':'image/png',
    'gif':'image/gif',
    'webp':'image/webp',
  };
  if((mime==='application/octet-stream'||!mime)&&EXT_MIME_MAP[_ext]){
    mime=EXT_MIME_MAP[_ext];
  }
  const allowed=['image/jpeg','image/png','image/gif','image/webp','video/mp4','video/webm','audio/mpeg','audio/ogg','audio/wav','audio/flac','audio/aac','audio/mp4','audio/opus','application/zip','application/x-zip-compressed','application/x-rar-compressed','application/x-rar','application/vnd.rar','application/x-7z-compressed','application/x-tar','application/gzip','application/x-gzip','application/x-bzip2','text/plain','text/markdown','application/json','text/csv','text/xml','application/xml'];
  const mimeOk=allowed.includes(mime)||mime.startsWith('audio/')||mime.startsWith('text/');
  if(!mimeOk){toast(`Недопустимый тип: ${file.name}`,'err');return;}
  const wrapId=mode==='dm'?'dmUploadProgressWrap':'uploadProgressWrap';
  const barId=mode==='dm'?'dmUploadProgressBar':'uploadProgressBar';
  q(wrapId).classList.add('show');q(barId).style.width='0%';
  const reader=new FileReader();
  const b64=await new Promise(res=>{reader.onload=e=>res(e.target.result);reader.readAsDataURL(file);});
  const r=await apiUploadXHR({action:'upload',image:b64,mime,originalName:file.name},pct=>{q(barId).style.width=pct+'%';});
  q(wrapId).classList.remove('show');
  if(!r.ok){toast(r.error||'Ошибка загрузки','err');return;}
  const fileObj={url:r.url,name:file.name,mime};
  if(mode==='dm'){S.dmPendingFiles.push(fileObj);renderPendingFiles('dm');}
  else{S.pendingFiles.push(fileObj);renderPendingFiles('channel');}
}
function getPendingFileIcon(f){if(f.mime.startsWith('image/')) return ti('image',14);if(f.mime.startsWith('video/')) return ti('video',14);if(f.mime.startsWith('audio/')) return ti('music',14);if(isArchive(f.url)) return ti('archive',14);return ti('file',14);}
function renderPendingFiles(mode){
  const wrap=q(mode==='dm'?'dmPendingFilesWrap':'pendingFilesWrap');
  const arr=mode==='dm'?S.dmPendingFiles:S.pendingFiles;
  if(!arr.length){wrap.classList.remove('show');wrap.innerHTML='';return;}
  wrap.classList.add('show');
  wrap.innerHTML=arr.map((f,i)=>`<div class="pending-file-chip"><span>${getPendingFileIcon(f)}</span><span class="pfc-name" title="${esc(f.name)}">${esc(f.name)}</span><span class="pfc-rm" onclick="removePendingFile('${mode}',${i})">✕</span></div>`).join('');
}
window.removePendingFile=function(mode,idx){if(mode==='dm') S.dmPendingFiles.splice(idx,1);else S.pendingFiles.splice(idx,1);renderPendingFiles(mode);};
function cancelAttach(){S.pendingFiles=[];renderPendingFiles('channel');}
function cancelDmAttach(){S.dmPendingFiles=[];renderPendingFiles('dm');}
function triggerUpload(id){document.getElementById(id)?.click();}

// ── FLOATING MP3 PLAYER (modern) ────────────────────────────
function initFloatingPlayer(){
  const fp=q('floatingPlayer');
  makeDraggable(fp,q('fpHeader'));
  // Клик по хедеру в свёрнутом состоянии — разворачивает (только если не было drag)
  q('fpHeader').addEventListener('click',function(e){
    if(e.target.closest('button')) return;
    if(fp._dragMoved) return;
    if(FP.minimized) fpToggleMin();
  });
  initStreamViewer();
}

// ── FLOATING PLAYER MINIMIZE ─────────────────────────────────
const FP_MIN={};
window.fpToggleMin=function(){
  FP.minimized=!FP.minimized;
  const el=q('floatingPlayer');
  const btn=q('fpMinBtn');
  if(FP.minimized){
    const rect=el.getBoundingClientRect();
    FP_MIN.savedStyle={left:el.style.left,top:el.style.top,bottom:el.style.bottom,right:el.style.right};
    el.classList.add('fp-minimized');
    if(window._fpAuroraStop) window._fpAuroraStop();
    if(btn){btn.innerHTML=`<svg viewBox="0 0 24 24" width="10" height="10"><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/></svg>`;btn.title='Развернуть';}
    _fpSyncMiniBtn();
  } else {
    el.classList.remove('fp-minimized');
    if(FP.playing&&window._fpAuroraStart) window._fpAuroraStart();
    if(btn){btn.innerHTML=`<svg viewBox="0 0 24 24" width="10" height="10"><line x1="5" y1="12" x2="19" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`;btn.title='Свернуть';}
  }
};
function _fpSyncMiniBtn(){
  const mini=q('fpMiniPlayBtn');
  if(!mini) return;
  // Синхронизируем иконку с состоянием воспроизведения
  mini.innerHTML=FP.playing?`<svg viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" rx="1.5" fill="currentColor"/><rect x="14" y="4" width="4" height="16" rx="1.5" fill="currentColor"/></svg>`:`<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg>`;
  mini.classList.add('fp-mini-play');
}
function _fpSyncMiniName(){
  const nm=q('fpMiniName');
  const tr=q('fpTrackName');
  if(nm&&tr) nm.textContent=tr.textContent||'—';
}
function fpFmtTime(sec){
  if(!isFinite(sec)||isNaN(sec)) return '0:00';
  const m=Math.floor(sec/60),s=Math.floor(sec%60);
  return m+':'+String(s).padStart(2,'0');
}
function openFloatingPlayer(tracks,startIdx=0){
  FP.queue=tracks;
  FP.currentIdx=Math.max(0,Math.min(startIdx,tracks.length-1));
  const _fpEl=q('floatingPlayer');
  // Always re-anchor to bottom-right when (re)opening, clearing any stale inline drag position
  // that could otherwise strand the player near the top of the screen.
  if(_fpEl && !_fpEl.classList.contains('show')){
    _fpEl.style.left='';_fpEl.style.top='';_fpEl.style.right='';_fpEl.style.bottom='';
    _fpEl._dragMoved=false;
  }
  _fpEl.classList.add('show');
  fpLoadCurrent();fpPlay();
  DI.hide();
  const track=FP.queue[FP.currentIdx];
  if(track){
    setTimeout(()=>DI.notify({
      icon:ti('music',14),
      avatarEmoji:ti('music',14),
      title:'▶ '+(track.name||'Музыка'),
      text: FP.queue.length>1?`Трек ${FP.currentIdx+1} из ${FP.queue.length}`:'Воспроизведение',
      dotColor:'green',
      onClick(){ const fp=q('floatingPlayer');if(fp&&fp._fpMin) fpToggleMin(); DI.clearNotifs(); },
    }),120);
  }
}
// ── NORTHERN LIGHTS AURORA ────────────────────────────────────
(function initAurora(){
  const canvas = document.getElementById('fpAuroraCanvas');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let W=300, H=160, t=0, rafId=null, active=false;

  function resize(){
    const fp = document.getElementById('floatingPlayer');
    if(!fp) return;
    W = fp.offsetWidth||300; H = fp.offsetHeight||160;
    canvas.width=W; canvas.height=H;
  }

  function wave(x, t, freq, phase, amp, yBase){
    return yBase + Math.sin(x*freq + t + phase)*amp + Math.sin(x*freq*1.7 + t*0.7 + phase)*amp*0.4;
  }

  function draw(){
    if(!active){ rafId=null; return; }
    t += 0.012;
    ctx.clearRect(0,0,W,H);

    const layers = [
      {col:'rgba(40,180,120,', freq:0.018, phase:0,    amp:22, yBase:H*0.45, alpha:0.18},
      {col:'rgba(60,140,255,', freq:0.022, phase:1.2,  amp:18, yBase:H*0.5,  alpha:0.16},
      {col:'rgba(120,80,255,', freq:0.015, phase:2.4,  amp:25, yBase:H*0.55, alpha:0.12},
      {col:'rgba(80,220,200,', freq:0.025, phase:0.8,  amp:15, yBase:H*0.42, alpha:0.14},
      {col:'rgba(160,100,255,',freq:0.012, phase:3.6,  amp:30, yBase:H*0.6,  alpha:0.10},
    ];

    layers.forEach(l=>{
      const grad = ctx.createLinearGradient(0,0,0,H);
      grad.addColorStop(0,   l.col+'0)');
      grad.addColorStop(0.3, l.col+l.alpha+')');
      grad.addColorStop(0.7, l.col+(l.alpha*0.6)+')');
      grad.addColorStop(1,   l.col+'0)');

      ctx.beginPath();
      ctx.moveTo(0, H);
      for(let x=0; x<=W; x+=3){
        const y = wave(x/W, t, l.freq*W, l.phase, l.amp, l.yBase);
        ctx.lineTo(x, y);
      }
      ctx.lineTo(W, H); ctx.closePath();
      ctx.fillStyle = grad;
      ctx.fill();
    });

    rafId = requestAnimationFrame(draw);
  }

  function start(){
    if(active) return;
    resize(); active=true;
    if(!rafId) draw();
  }
  function stop(){
    active=false;
    if(rafId){cancelAnimationFrame(rafId);rafId=null;}
  }

  // Запускаем когда плеер показан
  const fp = document.getElementById('floatingPlayer');
  if(fp){
    const obs = new MutationObserver(()=>{
      if(fp.classList.contains('show') && !fp.classList.contains('fp-minimized')) start();
      else stop();
    });
    obs.observe(fp, {attributes:true, attributeFilter:['class']});
  }
  window._fpAuroraStart=start; window._fpAuroraStop=stop;
})();

// ── Download current track ────────────────────────────────────
window.fpDownloadCurrent=function(){
  const track=FP.queue[FP.currentIdx];
  if(!track||!track.url) return;
  const a=document.createElement('a');
  a.href=track.url; a.download=track.name||'track';
  a.style.display='none'; document.body.appendChild(a);
  a.click(); setTimeout(()=>a.remove(),200);
};

function closeFloatingPlayer(){
  DI.hide(); // скрываем DI при закрытии плеера
  q('floatingPlayer').classList.remove('show','playing','fp-minimized');
  if(FP.audio){FP.audio.pause();FP.audio.src='';FP.audio=null;}
  FP.playing=false;FP.minimized=false;
  const btn=q('fpPlayBtn');if(btn) btn.innerHTML=TI.play;
  const mini=q('fpMiniPlayBtn');if(mini) mini.innerHTML=TI.play;
  const mbtn=q('fpMinBtn');if(mbtn){mbtn.textContent='─';mbtn.title='Свернуть';}
  diHidePlayer();
}
function fpLoadCurrent(){
  const track=FP.queue[FP.currentIdx];if(!track) return;
  if(FP.audio){FP.audio.pause();FP.audio.src='';FP.audio.onended=null;FP.audio.ontimeupdate=null;}
  FP.audio=new Audio(track.url);
  FP.audio.volume=FP.muted?0:(FP.volume||1);
  const tname=track.name||track.url.split('/').pop()||'Трек';
  const nameEl=q('fpTrackName');if(nameEl) nameEl.textContent=tname;
  const miniNm=q('fpMiniName');if(miniNm) miniNm.textContent=tname;
  const subEl=q('fpQueueInfo');if(subEl) subEl.textContent=FP.queue.length>1?`${FP.currentIdx+1} / ${FP.queue.length} треков`:'';
  const bar=q('fpProgressBar');if(bar) bar.style.width='0%';
  const cur=q('fpCurrentTime');const dur=q('fpDuration');
  if(cur) cur.textContent='0:00';if(dur) dur.textContent='0:00';
  FP.audio.onloadedmetadata=()=>{if(dur) dur.textContent=fpFmtTime(FP.audio.duration);};
  FP.audio.ontimeupdate=()=>{
    if(FP.audio&&FP.audio.duration){
      const pct=FP.audio.currentTime/FP.audio.duration*100;
      if(bar) bar.style.width=pct+'%';
      if(cur) cur.textContent=fpFmtTime(FP.audio.currentTime);
    }
  };
  FP.audio.onended=()=>{
    if(FP.repeat){FP.audio.currentTime=0;fpPlay();}
    else fpNext();
  };
}
function fpPlay(){
  if(!FP.audio) return;
  if(FP._diPauseTimer){clearTimeout(FP._diPauseTimer);FP._diPauseTimer=null;}
  FP.audio.play().catch(()=>{});FP.playing=true;
  const PAUSE_SVG=`<svg viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" rx="1.5" fill="currentColor"/><rect x="14" y="4" width="4" height="16" rx="1.5" fill="currentColor"/></svg>`;
  const btn=q('fpPlayBtn');if(btn) btn.innerHTML=PAUSE_SVG;
  const mini=q('fpMiniPlayBtn');if(mini) mini.innerHTML=PAUSE_SVG;
  q('floatingPlayer').classList.add('playing');
  if(window._fpAuroraStart) window._fpAuroraStart();
}
function fpPause(){
  if(!FP.audio) return;
  FP.audio.pause();FP.playing=false;
  const PLAY_SVG=`<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg>`;
  const btn=q('fpPlayBtn');if(btn) btn.innerHTML=PLAY_SVG;
  const mini=q('fpMiniPlayBtn');if(mini) mini.innerHTML=PLAY_SVG;
  q('floatingPlayer').classList.remove('playing');
  if(window._fpAuroraStop) window._fpAuroraStop();
  DI.hide();
}
window.fpTogglePlay=function(){if(FP.playing) fpPause();else fpPlay();};
window.fpNext=function(){
  if(FP.shuffle&&FP.queue.length>1){
    let idx;do{idx=Math.floor(Math.random()*FP.queue.length);}while(idx===FP.currentIdx&&FP.queue.length>1);
    FP.currentIdx=idx;
  } else FP.currentIdx=(FP.currentIdx+1)%FP.queue.length;
  fpLoadCurrent();fpPlay();
  const newTrack=FP.queue[FP.currentIdx];
  if(newTrack){
    DI.notify({
      icon:ti('music',14),
      avatarEmoji:ti('music',14),
      title:'▶ '+newTrack.name,
      text: FP.queue.length>1?`Трек ${FP.currentIdx+1} из ${FP.queue.length}`:'',
      dotColor:'',
      onClick(){ const fp=q('floatingPlayer');if(fp&&fp._fpMin) fpToggleMin(); DI.clearNotifs(); },
    });
  }
};
window.fpPrev=function(){
  FP.currentIdx=(FP.currentIdx-1+FP.queue.length)%FP.queue.length;
  fpLoadCurrent();fpPlay();
  const prevTrack=FP.queue[FP.currentIdx];
  if(prevTrack){
    DI.notify({
      icon:ti('music',14),
      avatarEmoji:ti('music',14),
      title:'▶ '+prevTrack.name,
      text: FP.queue.length>1?`Трек ${FP.currentIdx+1} из ${FP.queue.length}`:'',
      dotColor:'',
      onClick(){ DI.clearNotifs(); },
    });
  }
};
window.fpToggleMute=function(){
  FP.muted=!FP.muted;
  if(FP.audio) FP.audio.volume=FP.muted?0:(FP.volume||1);
  const icon=q('fpMuteBtn');if(icon) icon.innerHTML=FP.muted?`<svg viewBox="0 0 24 24" width="14" height="14"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`:(`<svg viewBox="0 0 24 24" width="14" height="14"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`);
  const slider=q('fpVolSlider');if(slider) slider.value=FP.muted?0:Math.round((FP.volume||1)*100);
  const lbl=q('fpVolLabel');if(lbl) lbl.textContent=(FP.muted?0:Math.round((FP.volume||1)*100))+'%';
};
window.fpSetVolume=function(val){
  FP.volume=parseInt(val)/100;FP.muted=false;
  if(FP.audio) FP.audio.volume=FP.volume;
  const icon=q('fpMuteBtn');if(icon) icon.innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;
  const lbl=q('fpVolLabel');if(lbl) lbl.textContent=val+'%';
};
window.fpSeek=function(e){
  if(!FP.audio||!FP.audio.duration) return;
  const rect=q('fpProgress').getBoundingClientRect();
  FP.audio.currentTime=(e.clientX-rect.left)/rect.width*FP.audio.duration;
};
window.fpToggleShuffle=function(){
  FP.shuffle=!FP.shuffle;
  const btn=q('fpShuffleBtn');if(btn) btn.classList.toggle('active-mode',FP.shuffle);
};
window.fpToggleRepeat=function(){
  FP.repeat=!FP.repeat;
  const btn=q('fpRepeatBtn');if(btn) btn.classList.toggle('active-mode',FP.repeat);
};


// ── VOICE WORKSPACE ──────────────────────────────────────────
function currentVoiceRoom(){return VOICE.rooms?.find(r=>parseInt(r.id,10)===parseInt(VOICE.roomId,10))||null;}
function voiceRoomDisplayName(name){
  const raw=String(name||'').trim();
  const low=raw.toLowerCase();
  if(['главный','main','lounge','lobby','general voice'].includes(low)) return t('voice.defaultMain');
  if(['второй','second','voice 2','secondary'].includes(low)) return t('voice.defaultSecond');
  return raw||t('voice.stageTitle');
}
function voiceStageRoomName(roomId){return voiceRoomDisplayName(VOICE.rooms?.find(r=>parseInt(r.id,10)===parseInt(roomId,10))?.name||VOICE.roomName||t('voice.stageTitle'));}
function i18nt(key,fallback,vars){
  try{
    const val=t(key,vars);
    return (val==null||val===key)?(fallback||key):val;
  }catch(e){return fallback||key;}
}
function applyVoiceStageI18n(){
  try{window.I18N?.apply?.(q('voiceStage'));}catch(e){}
  const setText=(sel,key,fallback)=>{const el=document.querySelector(sel);if(el) el.textContent=i18nt(key,fallback);};
  setText('.voice-stage-grid-head [data-i18n="voice.callGrid"]','voice.callGrid','Сетка созвона');
  setText('.voice-stage-grid-head [data-i18n="voice.activeSpeakerFeatured"]','voice.activeSpeakerFeatured','активный участник выделяется');
  setText('#voiceStageEmpty [data-i18n="voice.opened"]','voice.opened','Голосовой канал открыт');
  setText('#voiceStageEmpty [data-i18n="voice.streamHint"]','voice.streamHint','Здесь появится трансляция. Нажмите красный индикатор у участника или запустите свою трансляцию.');
  setText('#voiceStageWatchName','voice.streaming','Трансляция');
}
function openVoiceWorkspace(roomId,roomName){
  const vs=q('voiceStage');if(!vs) return;
  // Запоминаем последний текстовый канал перед переходом в voice-stage.
  // После выхода/переключения это не даёт приложению застрять в S.mode='voice'.
  if(S.mode==='channel'&&S.chId) S.lastTextChId=S.chId;
  S.mode='voice';S.chId=null;S.dmUid=null;
  q('welcomeScreen')&&(q('welcomeScreen').style.display='none');
  q('chView')&&(q('chView').style.display='none');
  q('dmView')&&(q('dmView').style.display='none');
  q('joinServerScreen')&&q('joinServerScreen').classList.remove('show');
  vs.style.display='flex';vs.classList.add('show');
  q('hdrCopyLink')&&(q('hdrCopyLink').style.display='none');
  q('hdrCallBtn')&&(q('hdrCallBtn').style.display='none');
  q('hdrVideoCallBtn')&&(q('hdrVideoCallBtn').style.display='none');
  q('hdrDmPrivacyBtn')&&(q('hdrDmPrivacyBtn').style.display='none');
  q('hdrDmClearBtn')&&(q('hdrDmClearBtn').style.display='none');
  q('memberToggleBtn')&&(q('memberToggleBtn').style.display='');
  const name=roomName||voiceStageRoomName(roomId);
  if(q('hdrIcon')) q('hdrIcon').innerHTML=ti('speaker',18);
  if(q('hdrName')) q('hdrName').textContent=name;
  if(q('hdrTopic')) q('hdrTopic').textContent=i18nt('voice.stageTitle','Голосовой канал');
  if(q('voiceStageName')) q('voiceStageName').textContent=name;
  document.title='trueCORD — 🔊 '+name;
  applyVoiceStageI18n();
  renderVoiceWorkspace();
}
function hideVoiceWorkspace(){
  const vs=q('voiceStage');if(vs){vs.classList.remove('show');vs.style.display='none';}
  voiceStageClearVideo();
}
function voiceStageControlsState(){
  const m=q('vsMuteBtn'); if(m) m.classList.toggle('active',!VOICE.muted);
  const sp=q('vsSpkBtn'); if(sp) sp.classList.toggle('active',!VOICE.speakerMuted);
  const st=q('vsStreamBtn');
  if(st){
    st.classList.toggle('active',!!VOICE.screenStream);
    st.title=VOICE.screenStream?t('stream.stop'):t('stream.start');
  }
}
function voicePrimaryStreamUid(){
  const room=currentVoiceRoom();
  const parts=(room?.participants||[]).slice();
  const current=parseInt(VOICE.currentStreamUserId||0,10);
  if(current&&((VOICE.streamers&&VOICE.streamers[current])||(current===S.me?.id&&VOICE.screenStream))) return current;
  const first=parts.find(p=>{
    const uid=parseInt(p.userId,10);
    return !!(VOICE.streamers&&VOICE.streamers[uid])||(uid===S.me?.id&&VOICE.screenStream);
  });
  return parseInt(first?.userId||0,10)||0;
}
function voiceParticipantName(uid){
  const room=currentVoiceRoom();
  const p=(room?.participants||[]).find(x=>parseInt(x.userId,10)===parseInt(uid,10));
  return p?.name||i18nt('common.user','Пользователь');
}
window.voiceToggleStreamPreview=function(uid,name){
  uid=parseInt(uid||voicePrimaryStreamUid()||0,10);
  if(!uid){toast(i18nt('voice.noActiveStream','Нет активной трансляции'),'info');return;}
  const same=parseInt(VOICE.currentStreamUserId||0,10)===uid;
  // «Смотреть» управляет только встроенным предпросмотром. Если уже открыто
  // отдельное окно, повторный клик не должен закрывать трансляцию и дёргать layout.
  if(same&&VOICE.streamInlineActive){closeStreamViewer();return;}
  openStreamViewer(uid,name||voiceParticipantName(uid),false);
};
function renderVoiceWorkspace(){
  const vs=q('voiceStage'); if(!vs||!vs.classList.contains('show')) return;
  const room=currentVoiceRoom();
  const parts=(room?.participants||[]).slice();
  if(q('voiceStageName')) q('voiceStageName').textContent=voiceRoomDisplayName(VOICE.roomName||room?.name||i18nt('voice.stageTitle','Голосовой канал'));
  if(!parts.length&&S.me&&VOICE.roomId){parts.push({userId:S.me.id,name:S.me.name||meName,avatar:S.me.avatar||'',muted:VOICE.muted});}
  if(q('voiceStageStatus')) q('voiceStageStatus').textContent=VOICE.roomId?`${i18nt('common.connected','Подключено')} · ${parts.length||1} ${i18nt('common.members','Участники').toLowerCase()}`:i18nt('common.notConnected','Не подключено');
  if(q('voiceStageCount')) q('voiceStageCount').textContent=String(parts.length||0);
  const list=q('voiceStageUsers');
  const grid=q('voiceStageGrid');
  if(!list||!grid) return;

  // Фиксированная сетка созвона: участники больше не сортируются по тому,
  // кто сейчас говорит/стримит. Из-за старой сортировки карточки менялись
  // местами и визуально «подпрыгивали» при каждом срабатывании VAD.
  const roomOrderKey=String(VOICE.roomId||room?.id||'voice');
  if(VOICE.stageOrderKey!==roomOrderKey){
    VOICE.stageOrderKey=roomOrderKey;
    VOICE.stageOrder=[];
  }
  const presentIds=new Set(parts.map(p=>parseInt(p.userId,10)).filter(Boolean));
  VOICE.stageOrder=(VOICE.stageOrder||[]).filter(uid=>presentIds.has(uid));
  parts.forEach(p=>{
    const uid=parseInt(p.userId,10);
    if(uid&&!VOICE.stageOrder.includes(uid)) VOICE.stageOrder.push(uid);
  });
  const orderMap=new Map((VOICE.stageOrder||[]).map((uid,idx)=>[uid,idx]));
  const sortParts=parts.slice().sort((a,b)=>{
    const auid=parseInt(a.userId,10), buid=parseInt(b.userId,10);
    return (orderMap.get(auid)??999999)-(orderMap.get(buid)??999999);
  });

  const desktopCols=parts.length<=1?1:(parts.length>=5?3:2);
  const mobileCols=parts.length<=1?1:2;
  grid.dataset.cols=String(desktopCols);
  grid.style.setProperty('--vs-grid-cols',String(desktopCols));
  grid.style.setProperty('--vs-grid-cols-mobile',String(mobileCols));

  grid.innerHTML=sortParts.map(p=>{
    const uid=parseInt(p.userId,10); const isMe=uid===S.me?.id;
    const speaking=!!VAD.speaking?.[uid]&&!(isMe&&VOICE.muted);
    const muted=!!p.muted||(isMe&&VOICE.muted)||!!p.forceMuted;
    const streamer=!!VOICE.streamers?.[uid]||(isMe&&!!VOICE.screenStream);
    const watching=!!VOICE.streamInlineActive&&parseInt(VOICE.currentStreamUserId||0,10)===uid;
    // Не делаем активного говорящего «featured»: размер всех окон остается одинаковым.
    const featured=false;
    const sub=speaking?i18nt('voice.speaking','Говорит'):(streamer?i18nt('voice.streamAvailable','Трансляция доступна'):(muted?i18nt('voice.micOff','Микрофон выключен'):i18nt('voice.inVoice','В голосе')));
    const av=p.avatar&&String(p.avatar).startsWith('http')?`<img src="${esc(p.avatar)}" alt="">`:avatarFallbackHtml(p.name,uid);
    const micAction=isMe
      ? `<button class="vs-tile-icon-btn ${VOICE.muted?'is-off':'is-active'}" onclick="event.stopPropagation();voiceToggleMute()" title="${escAttr(VOICE.muted?i18nt('voice.micOff','Микрофон выключен'):i18nt('voice.mic','Микрофон'))}" aria-label="${escAttr(i18nt('voice.mic','Микрофон'))}">${VOICE.muted?ti('micoff',15):ti('mic',15)}</button>`
      : `<button class="vs-tile-icon-btn ${muted?'is-off':'is-active'}" disabled title="${escAttr(muted?i18nt('voice.micOff','Микрофон выключен'):i18nt('voice.mic','Микрофон'))}" aria-label="${escAttr(i18nt('voice.mic','Микрофон'))}">${muted?ti('micoff',15):ti('mic',15)}</button>`;
    const soundAction=isMe
      ? `<button class="vs-tile-icon-btn ${VOICE.speakerMuted?'is-off':'is-active'}" onclick="event.stopPropagation();voiceToggleSpeaker()" title="${escAttr(i18nt('voice.sound','Звук'))}" aria-label="${escAttr(i18nt('voice.sound','Звук'))}">${VOICE.speakerMuted?ti('speakerMuted',15):ti('speaker',15)}</button>`
      : `<button class="vs-tile-icon-btn" onclick="event.stopPropagation();voiceStageOpenUserMenu(event,${uid})" title="${escAttr(i18nt('voice.sound','Звук'))}" aria-label="${escAttr(i18nt('voice.sound','Звук'))}">${ti('speaker',15)}</button>`;
    const streamAction=isMe
      ? `<button class="vs-tile-icon-btn ${VOICE.screenStream?'is-live':''}" onclick="event.stopPropagation();${VOICE.screenStream?'voiceStopStream()':'voiceStartStream()'}" title="${escAttr(VOICE.screenStream?i18nt('stream.stop','Остановить трансляцию'):i18nt('voice.streaming','Трансляция'))}" aria-label="${escAttr(i18nt('voice.streaming','Трансляция'))}">${ti('stream',15)}</button>`
      : `<button class="vs-tile-icon-btn ${streamer?'is-live':''}" ${streamer?'':'disabled'} onclick="event.stopPropagation();${streamer?`voiceStageTileStreamAction(${uid})`:''}" title="${escAttr(streamer?i18nt('voice.watchStream','Смотреть трансляцию'):i18nt('voice.streamUnavailable','Трансляция недоступна'))}" aria-label="${escAttr(i18nt('voice.streaming','Трансляция'))}">${ti('stream',15)}</button>`;
    return `<div class="vs-tile${featured?' featured':''}${speaking?' speaking':''}${streamer?' streaming':''}${watching?' watching':''}" data-vs-tile-user-id="${uid}" onclick="voiceStageOpenParticipantTile(${uid},${streamer?1:0})">
      <div class="vs-tile-bg"></div>
      <div class="vs-tile-avatar"><div class="vs-tile-av">${av}</div></div>
      <div class="vs-tile-body">
        <div class="vs-tile-topline">
          <span class="vs-tile-pill ${muted?'muted':''}">${muted?ti('speakerMuted',11):ti('speaker',11)} ${muted?i18nt('voice.micOff','Микрофон выключен'):i18nt('presence.online','В сети')}</span>
          ${streamer?`<span class="vs-tile-pill live"><span class="vs-live-dot"></span> ${i18nt('voice.streaming','Трансляция')}</span>`:''}
          ${watching?`<span class="vs-tile-pill watch">${i18nt('common.window','Окно')}</span>`:''}
        </div>
        <div class="vs-tile-name">${esc(p.name||i18nt('common.user','Пользователь'))}${isMe?' · '+i18nt('common.you','Вы'):''}</div>
        <div class="vs-tile-sub">${sub}</div>
        <div class="vs-tile-actions">
          <div class="vs-tile-icon-actions">${micAction}${soundAction}${streamAction}</div>
        </div>
      </div>
    </div>`;
  }).join('')||`<div class="vs-empty-stream" style="min-height:160px;grid-column:1/-1"><span>${i18nt('voice.noParticipants','Нет участников')}</span></div>`;

  list.innerHTML=parts.map(p=>{
    const uid=parseInt(p.userId,10); const isMe=uid===S.me?.id;
    const speaking=!!VAD.speaking?.[uid]&&!(isMe&&VOICE.muted);
    const muted=!!p.muted||(isMe&&VOICE.muted)||!!p.forceMuted;
    const streamer=!!VOICE.streamers?.[uid]||(isMe&&!!VOICE.screenStream);
    const avatar=voiceUserAvatarHtml(p.name||'?',p.avatar||'',uid);
    const sub=speaking?i18nt('voice.speaking','Говорит'):(muted?i18nt('voice.micOff','Микрофон выключен'):i18nt('voice.inVoice','В голосе'));
    return `<div class="vs-user${isMe?' me':''}${speaking?' speaking':''}${p.forceMuted?' force-muted':''}" data-vs-user-id="${uid}">
      <div class="vs-av">${avatar}</div><div class="vs-meta"><div class="vs-name">${esc(p.name||i18nt('common.user','Пользователь'))}${isMe?' · '+i18nt('common.you','Вы'):''}</div><div class="vs-sub">${muted?ti('speakerMuted',12):ti('speaker',12)} ${sub}</div></div>
      <div class="vs-user-actions">${streamer?`<button class="vs-mini stream${(VOICE.streamInlineActive&&parseInt(VOICE.currentStreamUserId||0,10)===uid)?' is-active':''}" onclick="event.stopPropagation();voiceStageWatchStreamByUid(${uid})" title="${(VOICE.streamInlineActive&&parseInt(VOICE.currentStreamUserId||0,10)===uid)?i18nt('common.close','Закрыть'):i18nt('voice.watchStream','Смотреть трансляцию')}"><span class="vs-live-dot"></span></button>`:''}<button class="vs-mini" onclick="event.stopPropagation();voiceStageOpenUserMenu(event,${uid})" title="${i18nt('voice.manageUser','Управление')}">${ti('gear',13)}</button></div>
    </div>`;
  }).join('')||`<div class="vs-empty-stream" style="min-height:160px"><span>${i18nt('voice.noParticipants','Нет участников')}</span></div>`;
  applyVoiceStageI18n();
  voiceStageControlsState();
  updateVoiceStageSpeakingUI();
}
function updateVoiceStageSpeakingUI(){
  document.querySelectorAll('.vs-user[data-vs-user-id]').forEach(el=>{
    const uid=parseInt(el.dataset.vsUserId,10);
    const isMe=uid===S.me?.id;
    const speaking=!!VAD.speaking?.[uid]&&!(isMe&&VOICE.muted);
    el.classList.toggle('speaking',speaking);
    const sub=el.querySelector('.vs-sub');
    if(sub&&speaking) sub.innerHTML=`${ti('speaker',12)} ${i18nt('voice.speaking','Говорит')}`;
  });
  document.querySelectorAll('.vs-tile[data-vs-tile-user-id]').forEach(el=>{
    const uid=parseInt(el.dataset.vsTileUserId,10);
    const isMe=uid===S.me?.id;
    const speaking=!!VAD.speaking?.[uid]&&!(isMe&&VOICE.muted);
    el.classList.toggle('speaking',speaking);
    const sub=el.querySelector('.vs-tile-sub');
    if(sub&&speaking) sub.innerHTML=`${ti('speaker',12)} ${i18nt('voice.speaking','Говорит')}`;
  });
}
function voiceStageClearVideo(){
  VOICE.streamInlineActive=false;
  const slot=q('voiceStageStream'); if(!slot) return;
  const vid=slot.querySelector('video'); if(vid){try{vid.pause();vid.srcObject=null;}catch(e){} vid.remove();}
  slot.classList.remove('watching');
  q('voiceStage')?.classList.remove('has-stream');
  q('voiceStageEmpty')&&(q('voiceStageEmpty').style.display='flex');
  if(q('voiceStageWatchName')) q('voiceStageWatchName').textContent=i18nt('voice.streaming','Трансляция');
}
function voiceStageTapPrimary(event){if(event) event.stopPropagation();if(!VOICE.currentStreamUserId) return;voiceStageFullscreenStream(event);}
function voiceStageShowVideo(stream,label){
  const slot=q('voiceStageStream'); if(!slot||!stream) return;
  VOICE.streamInlineActive=true;
  q('voiceStage')?.classList.add('has-stream');
  q('voiceStageEmpty')&&(q('voiceStageEmpty').style.display='none');
  slot.classList.add('watching');
  if(q('voiceStageWatchName')) q('voiceStageWatchName').textContent=label||t('voice.streaming');
  let vid=slot.querySelector('video');
  if(!vid){vid=document.createElement('video');vid.autoplay=true;vid.setAttribute('playsinline','');slot.appendChild(vid);}
  vid.srcObject=_svBuildViewerStream(stream);
  vid.muted=(VOICE.currentStreamUserId===S.me?.id)||SV_AUDIO.muted||SV_AUDIO.ducked||SV_AUDIO.volume<=0;
  vid.volume=Math.max(0,Math.min(1,SV_AUDIO.volume||1));
  vid.play().catch(()=>{});
}
window.voiceStageWatchStream=function(uid,name){voiceToggleStreamPreview(uid,name||i18nt('common.user','Пользователь'));};
window.voiceStageWatchStreamByUid=function(uid){
  const room=currentVoiceRoom();
  const p=(room?.participants||[]).find(x=>parseInt(x.userId,10)===parseInt(uid,10))||{};
  voiceToggleStreamPreview(parseInt(uid,10),p.name||i18nt('common.user','Пользователь'));
};
window.voiceStageTileStreamAction=function(uid){
  uid=parseInt(uid,10);
  voiceStageWatchStreamByUid(uid);
};
window.voiceStageOpenParticipantTile=function(uid,hasStream){
  uid=parseInt(uid,10);
  if(hasStream){voiceStageTileStreamAction(uid);return;}
  showUserProfile(uid);
};
window.voiceStageOpenUserMenu=function(ev,uid){
  const room=currentVoiceRoom();
  const p=(room?.participants||[]).find(x=>parseInt(x.userId,10)===parseInt(uid,10))||{};
  showVoiceUserMenu(ev,parseInt(uid,10),p.name||i18nt('common.user','Пользователь'),p.avatar||'',VOICE.roomId||0,!!p.forceMuted);
};

window.voiceStageOpenFloatingStream=function(){
  const uid=parseInt(VOICE.currentStreamUserId||voicePrimaryStreamUid()||0,10);
  if(!uid){toast(i18nt('voice.noActiveStream','Нет активной трансляции'),'info');return;}
  openStreamViewer(uid,voiceParticipantName(uid),true);
};
function _fullscreenElement(){return document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement||null;}
function _requestFullscreen(el){
  if(!el) return Promise.resolve();
  const fn=el.requestFullscreen||el.webkitRequestFullscreen||el.mozRequestFullScreen||el.msRequestFullscreen;
  try{return fn?Promise.resolve(fn.call(el)).catch(()=>{}):Promise.resolve();}catch(e){return Promise.resolve();}
}
function _exitFullscreen(){
  const fn=document.exitFullscreen||document.webkitExitFullscreen||document.mozCancelFullScreen||document.msExitFullscreen;
  try{return fn?Promise.resolve(fn.call(document)).catch(()=>{}):Promise.resolve();}catch(e){return Promise.resolve();}
}
window.voiceStageFullscreenStream=function(event){
  if(event) event.stopPropagation();
  const uid=parseInt(VOICE.currentStreamUserId||voicePrimaryStreamUid()||0,10);
  if(!uid){toast(i18nt('voice.noActiveStream','Нет активной трансляции'),'info');return;}
  if(parseInt(VOICE.currentStreamUserId||0,10)!==uid||!VOICE.streamInlineActive) openStreamViewer(uid,voiceParticipantName(uid),false);
  const slot=q('voiceStageStream');
  if(_fullscreenElement()===slot){_exitFullscreen();return;}
  _requestFullscreen(slot);
};

// ── STREAM VIEWER ────────────────────────────────────────────
function initStreamViewer(){
  const sv=q('streamViewer');
  makeDraggable(sv,q('svHeader'));
  // Клик по хедеру в свёрнутом состоянии — разворачивает (только если не было drag)
  q('svHeader').addEventListener('click',function(e){
    if(e.target.closest('button,input,select,textarea')) return;
    if(sv._dragMoved) return; // игнорируем клик после перетаскивания
    if(SV_MIN.minimized) svToggleMin();
  });
  const wrap=q('svVideoWrap');
  if(wrap){
    const showStreamControls=function(e){
      if(e&&e.target&&e.target.closest('.sv-audio-controls,.sv-reactions,button,input,select,textarea')) return;
      sv.classList.add('sv-controls-visible');
      clearTimeout(sv._controlsTimer);
      sv._controlsTimer=setTimeout(()=>sv.classList.remove('sv-controls-visible'),2000);
    };
    wrap.addEventListener('pointermove',showStreamControls,{passive:true});
    wrap.addEventListener('pointerdown',showStreamControls,{passive:true});
    wrap.addEventListener('touchstart',showStreamControls,{passive:true});
    wrap.addEventListener('mouseleave',function(){
      clearTimeout(sv._controlsTimer);
      sv._controlsTimer=setTimeout(()=>sv.classList.remove('sv-controls-visible'),450);
    });
  }
}

const SV_MIN={minimized:false};
// SV_AUDIO.ducked = временное локальное приглушение звука стрима,
// когда в voice-чате говорит кто-то кроме стримера. Это убирает эхо:
// голос уже слышен через обычный voice audio, поэтому дублирующий голос,
// случайно захваченный браузером в audio track трансляции, не проигрывается.
const SV_AUDIO={volume:1,muted:false,ducked:false};
function _svVolumeIcon(muted,volume){
  if(muted||volume<=0){
    return `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;
  }
  if(volume<0.45){
    return `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.5 9.5a3.5 3.5 0 0 1 0 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;
  }
  return `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;
}
function _svApplyAudioState(){
  const uid=VOICE.currentStreamUserId;
  const own=(uid===S.me?.id);
  const controls=q('svAudioControls');
  const vid=q('svVideoWrap')?.querySelector('video');
  if(controls){
    controls.classList.toggle('is-own',!!own);
    controls.classList.toggle('is-ducked',!!SV_AUDIO.ducked&&!own);
  }
  const userMuted=own||SV_AUDIO.muted||SV_AUDIO.volume<=0;
  const effectiveMuted=userMuted||SV_AUDIO.ducked;
  if(vid){
    vid.muted=effectiveMuted;
    // Не меняем выбранный пользователем процент громкости. Если включён anti-echo duck,
    // звук временно глушится через muted, а после речи возвращается к прежнему уровню.
    vid.volume=own?0:Math.max(0,Math.min(1,SV_AUDIO.volume));
  }
  const btn=q('svMuteBtn');
  if(btn){
    btn.innerHTML=_svVolumeIcon(effectiveMuted,SV_AUDIO.volume);
    btn.title=SV_AUDIO.ducked&&!own?t('voice.streamTemporaryMuted'):(effectiveMuted?t('voice.turnOnStreamSound'):t('voice.turnOffStreamSound'));
    btn.setAttribute('aria-label',btn.title);
  }
  const slider=q('svVolSlider');
  const label=q('svVolLabel');
  const pct=Math.round((SV_AUDIO.muted?0:SV_AUDIO.volume)*100);
  if(slider) slider.value=String(pct);
  if(label) label.textContent=SV_AUDIO.ducked&&!own?'voice':(pct+'%');
}
function _svShouldDuckStreamAudio(){
  const streamerId=parseInt(VOICE.currentStreamUserId||0,10);
  if(!streamerId||streamerId===S.me?.id) return false;
  // Если говорит сам стример, не глушим звук стрима: его голос не должен попадать
  // в stream-audio через эту защиту. Глушим только когда говорит зритель/собеседник,
  // потому что именно его голос чаще всего возвращается эхом через захват системного звука у стримера.
  return Object.entries(VAD.speaking||{}).some(([uid,sp])=>{
    uid=parseInt(uid,10);
    return !!sp && uid && uid!==streamerId;
  });
}
function _svUpdateEchoDucking(){
  const duck=!!_svShouldDuckStreamAudio();
  if(SV_AUDIO.ducked===duck) return;
  SV_AUDIO.ducked=duck;
  _svApplyAudioState();
}
window.svToggleMute=function(){
  SV_AUDIO.muted=!SV_AUDIO.muted;
  if(!SV_AUDIO.muted&&SV_AUDIO.volume<=0) SV_AUDIO.volume=0.5;
  _svApplyAudioState();
};
window.svSetVolume=function(val){
  const v=Math.max(0,Math.min(100,parseInt(val,10)||0));
  SV_AUDIO.volume=v/100;
  SV_AUDIO.muted=(v===0);
  _svApplyAudioState();
};

const SV_REACTION_ALLOWED=['🔥','😂','👏','❤️','😮','👍','👀'];
function svShowReaction(emoji,name){
  if(!SV_REACTION_ALLOWED.includes(emoji)) return;
  const wrap=q('svVideoWrap');
  if(!wrap) return;
  const el=document.createElement('div');
  el.className='sv-reaction-float';
  el.style.setProperty('--rx',((Math.random()*58)-14).toFixed(0)+'px');
  const safeName=name?esc(name):'';
  el.innerHTML=`<span>${emoji}</span>${safeName?`<span class="sv-reaction-name">${safeName}</span>`:''}`;
  wrap.appendChild(el);
  setTimeout(()=>el.remove(),2100);
}
function svBroadcastReaction(payload,excludeUid=0){
  if(!VOICE.roomId||!S.me) return;
  const ids=Object.keys(VOICE.streamWatchers||{}).map(n=>parseInt(n,10)).filter(Boolean);
  ids.forEach(uid=>{
    if(uid===excludeUid) return;
    api({action:'voice_signal',roomId:VOICE.roomId,toUserId:uid,type:'stream-reaction',data:JSON.stringify(payload)}).catch(()=>{});
  });
}
window.svSendReaction=function(emoji){
  if(!SV_REACTION_ALLOWED.includes(emoji)) return;
  const streamerId=parseInt(VOICE.currentStreamUserId||0,10);
  if(!streamerId||!VOICE.roomId||!S.me) return;
  const payload={emoji,streamerId,fromId:S.me.id,fromName:S.me.name||'Гость',at:Date.now()};
  svShowReaction(emoji,payload.fromName);
  if(streamerId===S.me.id){
    svBroadcastReaction(payload,0);
  }else{
    api({action:'voice_signal',roomId:VOICE.roomId,toUserId:streamerId,type:'stream-reaction',data:JSON.stringify(payload)}).catch(()=>{});
  }
};
window.svToggleMin=function(){
  SV_MIN.minimized=!SV_MIN.minimized;
  const el=q('streamViewer');
  const btn=q('svMinBtn');
  if(SV_MIN.minimized){
    el.classList.add('sv-minimized');
    if(btn){btn.textContent='▢';btn.title='Развернуть';}
  } else {
    el.classList.remove('sv-minimized');
    if(btn){btn.textContent='─';btn.title='Свернуть';}
  }
};
function makeDraggable(el,handle){
  let sx=0,sy=0,sl=0,st=0,dragging=false,didMove=false;
  // Флаг доступен снаружи через el._dragMoved
  el._dragMoved=false;
  function onStart(e){
    if(e.target.closest('button')) return;
    const t=e.touches?.[0];
    sx=t?t.clientX:e.clientX; sy=t?t.clientY:e.clientY;
    const rect=el.getBoundingClientRect();
    sl=rect.left; st=rect.top;
    dragging=true; didMove=false; el._dragMoved=false;
    e.preventDefault();
  }
  function onMove(e){
    if(!dragging) return;
    const t=e.touches?.[0];
    const cx=t?t.clientX:e.clientX; const cy=t?t.clientY:e.clientY;
    const dx=cx-sx, dy=cy-sy;
    if(!didMove&&Math.abs(dx)<4&&Math.abs(dy)<4) return; // dead zone
    if(!didMove){
      didMove=true; el._dragMoved=true;
      // Фиксируем left/top только при реальном движении, сохраняя текущую позицию
      el.style.left=sl+'px'; el.style.top=st+'px';
      el.style.right='auto'; el.style.bottom='auto';
    }
    el.style.left=Math.max(0,Math.min(sl+dx,window.innerWidth-el.offsetWidth))+'px';
    el.style.top=Math.max(0,Math.min(st+dy,window.innerHeight-el.offsetHeight))+'px';
    if(el.id==='streamViewer') clampStreamViewer();
    if(e.cancelable) e.preventDefault();
  }
  function onEnd(){
    dragging=false;
    // Сбрасываем флаг через небольшой таймаут — после того как click обработан
    setTimeout(()=>{didMove=false; el._dragMoved=false;},50);
  }
  handle.addEventListener('mousedown',onStart);
  handle.addEventListener('touchstart',onStart,{passive:false});
  document.addEventListener('mousemove',onMove);
  document.addEventListener('touchmove',onMove,{passive:false});
  document.addEventListener('mouseup',onEnd);
  document.addEventListener('touchend',onEnd);
}


function clampFloatingToViewport(el){
  if(!el||el.classList.contains('sv-minimized')||document.fullscreenElement===el) return;
  const pad=8;
  const vw=window.innerWidth||document.documentElement.clientWidth||800;
  const vh=window.innerHeight||document.documentElement.clientHeight||600;
  const maxW=Math.max(220,vw-pad*2);
  const maxH=Math.max(180,vh-pad*2);
  if(el.offsetWidth>maxW) el.style.width=maxW+'px';
  if(el.offsetHeight>maxH) el.style.height=maxH+'px';
  const r=el.getBoundingClientRect();
  let left=r.left, top=r.top;
  if(r.right>vw-pad) left=Math.max(pad,vw-pad-r.width);
  if(r.bottom>vh-pad) top=Math.max(pad,vh-pad-r.height);
  if(left<pad) left=pad;
  if(top<pad) top=pad;
  el.style.left=left+'px';el.style.top=top+'px';el.style.right='auto';el.style.bottom='auto';
}
function clampStreamViewer(){clampFloatingToViewport(q('streamViewer'));}
window.addEventListener('resize',()=>{try{clampStreamViewer();}catch(e){}},{passive:true});

function openStreamViewer(userId,userName,showWindow=true){
  userId=parseInt(userId,10)||0;
  VOICE.currentStreamUserId=userId;
  q('svTitle').textContent=esc(userName)+' — '+i18nt('voice.streaming','трансляция');
  const sv=q('streamViewer');
  if(showWindow){
    // Режим «Окно» теперь полностью отделён от встроенного предпросмотра.
    // Плавающий viewer не добавляет класс has-stream и не перестраивает voice-layout.
    if(VOICE.streamInlineActive) voiceStageClearVideo();
    VOICE.streamInlineActive=false;
    exitFullscreenForWindowOpen();
    sv.classList.add('show');
    setTimeout(clampStreamViewer,0);
  }else if(sv){
    // Встроенный предпросмотр в голосовом канале — только по явному «Смотреть»/fullscreen.
    VOICE.streamInlineActive=true;
    q('voiceStage')?.classList.add('has-stream');
    sv.classList.remove('show','sv-minimized');
    SV_MIN.minimized=false;
    const btn=q('svMinBtn');if(btn){btn.textContent='─';btn.title='Свернуть';}
  }
  renderVoiceWorkspace();
  if(typeof _svApplyAudioState==='function') _svApplyAudioState();
  diShowStream(userName+' — '+i18nt('voice.streaming','трансляция'));
  // Своя трансляция
  if(userId===S.me?.id&&VOICE.screenStream){_svShowVideo(VOICE.screenStream);return;}
  const peer=VOICE.peers[userId];
  if(peer&&peer.videoStream){
    _svShowVideo(peer.videoStream);
    // Видео уже пришло, но звук демонстрации включаем только после явного открытия просмотра.
    try{
      if(VOICE.roomId&&userId!==S.me?.id){
        if(VOICE.peers[userId]) VOICE.peers[userId]._expectStreamAudio=true;
        api({action:'voice_signal',roomId:VOICE.roomId,toUserId:userId,type:'stream-request',data:JSON.stringify({from:S.me?.id||0,wantsAudio:true})});
      }
    }catch(e){}
  } else {
    const ns=q('svNoStream');if(ns) ns.style.display='';
    q('svVideoWrap').querySelector('video')?.remove();
    // Просим владельца трансляции повторно добавить видеотрек и сделать offer.
    // Это чинит случай, когда кнопка стрима уже видна, а video m-line ещё не договорён.
    try{
      if(VOICE.roomId&&userId!==S.me?.id){
        if(VOICE.peers[userId]) VOICE.peers[userId]._expectStreamAudio=true;
        api({action:'voice_signal',roomId:VOICE.roomId,toUserId:userId,type:'stream-request',data:JSON.stringify({from:S.me?.id||0,wantsAudio:true})});
      }
    }catch(e){}
    // Подождать пока видеотрек придёт по WebRTC
    const retryId=setInterval(()=>{
      if(VOICE.currentStreamUserId!==userId){clearInterval(retryId);return;}
      const p=VOICE.peers[userId];
      if(userId===S.me?.id&&VOICE.screenStream){clearInterval(retryId);_svShowVideo(VOICE.screenStream);return;}
      if(p?.videoStream){clearInterval(retryId);_svShowVideo(p.videoStream);}
    },500);
    setTimeout(()=>clearInterval(retryId),15000);
  }
}

function _svBuildViewerStream(stream){
  const uid=VOICE.currentStreamUserId;
  const peer=uid?VOICE.peers[uid]:null;
  if(!stream) return stream;
  // Свой предпросмотр оставляем как есть и без звука.
  if(uid===S.me?.id) return stream;
  const out=new MediaStream();
  try{stream.getVideoTracks?.().forEach(t=>out.addTrack(t));}catch(e){}
  const added=new Set();
  const addAudio=t=>{if(t&&!added.has(t.id)){added.add(t.id);out.addTrack(t);}};
  // Аудио из stream с video считается звуком демонстрации. Обычный voice audio
  // сюда не добавляем никогда, чтобы не было эха голосового канала.
  try{stream.getAudioTracks?.().forEach(addAudio);}catch(e){}
  if(peer?.streamAudioTrack) addAudio(peer.streamAudioTrack);
  return out.getTracks().length?out:stream;
}

function _svShowVideo(stream){
  const wrap=q('svVideoWrap');
  const sv=q('streamViewer');
  // Встроенный предпросмотр обновляется только в inline-режиме. Открытие
  // отдельного окна не трогает сетку/разметку голосового канала.
  if(VOICE.streamInlineActive){
    try{voiceStageShowVideo(stream,(q('svTitle')?.textContent||'Трансляция'));}catch(e){}
  }
  if(!wrap) return;
  const floatingVisible=!!sv?.classList.contains('show');
  if(!floatingVisible){
    const oldVid=wrap.querySelector('video');
    if(oldVid){oldVid.pause?.();oldVid.srcObject=null;oldVid.remove();}
    const ns=q('svNoStream');if(ns) ns.style.display='none';
    return;
  }
  q('svNoStream').style.display='none';
  let vid=wrap.querySelector('video');
  if(!vid){
    vid=document.createElement('video');
    vid.autoplay=true;vid.setAttribute('playsinline','');
    vid.style.cssText='width:100%;height:100%;object-fit:contain;display:block';
    wrap.appendChild(vid);
  }
  // Собираем stream для viewer только из video track и stream-audio track.
  // Voice-аудио peer'а сюда не попадает.
  vid.srcObject=_svBuildViewerStream(stream);
  if(typeof _svApplyAudioState==='function') _svApplyAudioState();
  else {vid.muted=(VOICE.currentStreamUserId===S.me?.id);vid.volume=1;}
  const play=()=>vid.play().catch(()=>{});
  play();
  if(!vid.muted){
    const resume=()=>{play();document.removeEventListener('click',resume,true);document.removeEventListener('touchend',resume,true);document.removeEventListener('keydown',resume,true);};
    document.addEventListener('click',resume,{once:true,capture:true});
    document.addEventListener('touchend',resume,{once:true,capture:true});
    document.addEventListener('keydown',resume,{once:true,capture:true});
  }
}
function _svExitFullscreenIfActive(){
  try{
    const sv=q('streamViewer');
    const fs=_fullscreenElement();
    if(fs&&(fs===sv||sv.contains(fs))){
      _exitFullscreen();
    }
  }catch(e){}
}

function hideStreamViewerWindow(){
  const sv=q('streamViewer');
  if(!sv) return;
  _svExitFullscreenIfActive();
  sv.classList.remove('show','sv-minimized');
  SV_MIN.minimized=false;
  const btn=q('svMinBtn');if(btn){btn.textContent='─';btn.title='Свернуть';}
  const vid=q('svVideoWrap')?.querySelector('video');
  // Закрываем только плавающее окно просмотра. Не отправляем stream-unrequest,
  // не очищаем voice workspace и не сбрасываем currentStreamUserId — трансляция
  // должна продолжать идти в канале, как в Discord. Видео в рабочей области
  // остаётся активным, а окно можно открыть снова кнопкой «Окно».
  if(vid){vid.pause?.();vid.srcObject=null;vid.remove();}
  diShowStream(q('svTitle')?.textContent||'Трансляция');
}
window.hideStreamViewerWindow=hideStreamViewerWindow;

function closeStreamViewer(){
  SV_AUDIO.ducked=false;
  const closingUid=VOICE.currentStreamUserId;
  const sv=q('streamViewer');
  _svExitFullscreenIfActive();
  sv.classList.remove('show','sv-minimized');
  SV_MIN.minimized=false;
  const btn=q('svMinBtn');if(btn){btn.textContent='─';btn.title='Свернуть';}
  const vid=q('svVideoWrap').querySelector('video');
  if(vid){vid.srcObject=null;vid.remove();}
  if(closingUid&&VOICE.peers[closingUid]){
    VOICE.peers[closingUid]._expectStreamAudio=false;
    VOICE.peers[closingUid].streamAudioTrack=null;
    VOICE.peers[closingUid].streamAudioStream=null;
  }
  if(closingUid&&closingUid!==S.me?.id&&VOICE.roomId){
    try{api({action:'voice_signal',roomId:VOICE.roomId,toUserId:closingUid,type:'stream-unrequest',data:JSON.stringify({from:S.me?.id||0})});}catch(e){}
  }
  VOICE.currentStreamUserId=null;
  voiceStageClearVideo();
  renderVoiceWorkspace();
  diHideStream();
}
window.svToggleFullscreen=function(){
  const sv=q('streamViewer');
  if(!_fullscreenElement()) _requestFullscreen(sv);
  else _exitFullscreen();
};

function openMediaPlayer(url,title,channelAudioQueue=null){
  exitFullscreenForWindowOpen();
  if(isAudio(url)){
    let queue=[{url,name:title||url.split('/').pop()}];
    let startIdx=0;
    if(channelAudioQueue&&channelAudioQueue.length>0){
      queue=channelAudioQueue;
      startIdx=queue.findIndex(t=>t.url===url);
      if(startIdx<0) startIdx=0;
    }
    openFloatingPlayer(queue,startIdx);
    return;
  }
  const modal=q('mediaPlayerModal');
  const content=q('mediaPlayerContent');
  const nav=q('mediaPlayerNav');
  q('mediaPlayerTitle').textContent=title||url.split('/').pop()||'Медиа';
  nav.innerHTML=`<button class="btn btn-ghost" style="flex:1" onclick="window.open('${esc(url)}','_blank')">${ti("download",12)} Скачать</button><button class="btn btn-ghost" style="flex:1" onclick="closeMediaPlayer()">✕ Закрыть</button>`;
  if(isVideo(url)){
    content.innerHTML=`<video controls autoplay playsinline style="width:100%;max-height:60vh;border-radius:var(--radius-sm);background:#000;outline:none"><source src="${esc(url)}">Ваш браузер не поддерживает видео.</video>`;
  } else if(/\.(jpg|jpeg|png|gif|webp)$/i.test(url)){
    content.innerHTML=`<img src="${esc(url)}" alt="${esc(title||'')}" style="width:100%;max-height:70vh;object-fit:contain;border-radius:var(--radius-sm);cursor:zoom-in" onclick="openLightbox('${esc(url)}')">`;
  } else if(isTextFile(url)){
    content.innerHTML=`<div class="text-view" id="textViewContent">${t('common.loadingDots')}</div>`;
    fetch(url).then(r=>r.text()).then(t=>{const el=document.getElementById('textViewContent');if(el) el.textContent=t;}).catch(()=>{});
  } else {
    content.innerHTML=`<div style="text-align:center;padding:24px"><div style="font-size:56px;margin-bottom:12px">${ti("file",48)}</div><div style="color:var(--text2)">${esc(title||url.split('/').pop()||'Файл')}</div></div>`;
  }
  modal.classList.add('open');
}
function closeMediaPlayer(){
  const modal=q('mediaPlayerModal');modal.classList.remove('open');
  const content=q('mediaPlayerContent');
  content.querySelectorAll('audio,video').forEach(m=>{m.pause();m.src='';});
  content.innerHTML='';
}

function getChannelAudioQueue(){
  const list=q('messagesList');if(!list) return [];
  const wraps=list.querySelectorAll('.msg-audio-wrap');
  const queue=[];
  wraps.forEach(w=>{
    const url=w.dataset.url;const name=w.dataset.name;
    if(url) queue.push({url,name:name||url.split('/').pop()});
  });
  return queue;
}

// ── LOCATION SAVE/RESTORE ────────────────────────────────────
function saveLocation(){
  if(!S.me) return;
  try{localStorage.setItem('lastLoc_'+S.me.id,JSON.stringify({mode:S.mode,srvId:S.srvId,chId:S.chId,dmUid:S.dmUid,lastMsgId:S.lastMsgId}));}catch(e){}
}
async function restoreLocation(){
  if(!S.me) return;
  try{
    const raw=localStorage.getItem('lastLoc_'+S.me.id);
    if(raw){
      const loc=JSON.parse(raw);
      if(loc.mode==='dm'&&loc.dmUid){
        await openDmMode();
        const u=S.allUsers.find(x=>x.id===loc.dmUid);
        if(u){await openDmConv(loc.dmUid,u.name,u.avatar||'');return;}
      }
      else if(loc.srvId){
  const srv=S.servers.find(x=>x.id===loc.srvId);
  if(srv){
    await selectServer(loc.srvId,true);
    if(loc.chId&&srv.isMember){
      const ch=S.channels.find(x=>x.id===loc.chId);
      if(ch){await selectChannel(loc.chId);return;}
    }
    // Авто-выбор первого канала если нет сохранённого (десктоп)
    if(srv.isMember&&S.channels.length){
      await selectChannel(S.channels[0].id);
    }
    return;
  }
}

    }
  }catch(e){}
  const memberSrv=S.servers.find(s=>s.isMember);
  if(memberSrv) await selectServer(memberSrv.id);
  else if(S.servers.length) await selectServer(S.servers[0].id);
}

window.addEventListener('beforeunload',()=>{
  if(S.me) saveLocation();
  if(VOICE.roomId!==null) api({action:'voice_leave'});
  if(DMCALL.active&&DMCALL.withUserId) api({action:'dm_call_hangup',toUserId:DMCALL.withUserId});

  if(S.me){
    if(S.rememberMe){
      if(S.myStatus!=='invisible'){
        const stored=JSON.parse(localStorage.getItem('sess')||'{}');
        stored.prevStatus=S.myStatus;
        localStorage.setItem('sess',JSON.stringify(stored));
      }
      navigator.sendBeacon&&navigator.sendBeacon(API,JSON.stringify({
        action:'set_status',status:'invisible',userId:S.me.id,token:S.tok
      }));
    } else {
      navigator.sendBeacon&&navigator.sendBeacon(API,JSON.stringify({
        action:'logout',userId:S.me.id,token:S.tok
      }));
    }
  }
});
// ── Автопереподключение голоса при смене/восстановлении сети ──
window.addEventListener('online',()=>{
  toast('Сеть восстановлена','ok',2500);
  if(VOICE.roomId!==null) setTimeout(()=>voiceReconnect().catch(()=>{}),1500);
});
window.addEventListener('offline',()=>{
  toast(ti('warning',14)+' Соединение потеряно','err',4000);
});
// Network Information API может срабатывать каждые 10-20 секунд просто из-за
// изменения оценки downlink/rtt. Раньше это вызывало voiceReconnect(), который
// рассылал reconnect-сигнал и заставлял собеседников закрывать WebRTC peer.
// Результат: циклический обрыв голоса. Теперь изменение сети только мягко
// проверяет локальные peer'ы и не трогает живые соединения.
let _lastNetInfoVoiceCheck=0;
if('connection' in navigator){
  navigator.connection.addEventListener('change',()=>{
    if(VOICE.roomId===null) return;
    const now=Date.now();
    if(now-_lastNetInfoVoiceCheck<30000) return;
    _lastNetInfoVoiceCheck=now;
    setTimeout(()=>voiceRepairPeers('network-info-change').catch(()=>{}),1200);
  });
}

// ★ АУДИО FIX 8: при возврате из фона переподключаем голосовой чат
document.addEventListener('visibilitychange',()=>{
  if(document.visibilityState==='visible'){
    // 1. Разблокируем AudioContext
    if(S.audioCtx?.state==='suspended') S.audioCtx.resume().catch(()=>{});

    // 2. Перезапускаем poll если он умер
    if(S.me && !S.pollTimer) startPoll();

    // 4. Восстанавливаем WakeLock (он теряется при смене вкладки)
    if((VOICE.roomId!==null || DMCALL.active) && !_wakeLock){
      requestWakeLock();
    }

    // 5. Реконнект голосового чата
    if(VOICE.roomId!==null){
      setTimeout(()=>{
        voiceReconnect().catch(()=>{});
        // Разблокируем audio-элементы собеседников
        Object.values(VOICE.peers).forEach(peer=>{
          if(peer.audioEl && peer.audioEl.paused && peer.audioEl.srcObject){
            peer.audioEl.muted = false;
            peer.audioEl.play().catch(()=>{});
          }
        });
      }, 800);
    }

    // 6. Реконнект DM-звонка
    if(DMCALL.active && DMCALL.pc){
      const state = DMCALL.pc.iceConnectionState;
      if(state==='disconnected' || state==='failed'){
        dmCallInitiatePeer().catch(()=>{});
      }
    }
  } else {
    // Фон: AudioContext может приостановиться — это нормально
  }
});


function updateUserPanel(){
  const u=S.me;if(!u) return;
  q('upName').textContent=u.name;
  const av=q('upAv');
  av.innerHTML=u.avatar?`<img src="${u.avatar}" alt="${esc(u.name)}">`:`<span>${u.name[0]||ti('user',16)}</span>`;
  updateStatusUI(S.myStatus);
}
function updateStatusUI(st){
  const m=STATUS_META[st]||STATUS_META.online;
  q('upStatusDot').style.background=m.color;
  q('upStatusLabel').textContent=m.label;
  q('upStatusLabel').style.color=m.color;
  document.querySelectorAll('.sp-item').forEach((el,i)=>{const keys=Object.keys(STATUS_META);el.classList.toggle('active',keys[i]===st);});
}

// ── STATUS PICKER ────────────────────────────────────────────
function toggleStatusPicker(e){e.stopPropagation();q('statusPicker').classList.toggle('open');}
async function setMyStatus(st){
  S.myStatus=st;
  q('statusPicker').classList.remove('open');
  updateStatusUI(st);
  await api({action:'set_status',status:st});
  if(S.rememberMe){
    const stored=JSON.parse(localStorage.getItem('sess')||'{}');
    delete stored.prevStatus;
    if(stored.user) stored.user.status=st;
    localStorage.setItem('sess',JSON.stringify(stored));
  }
}

document.addEventListener('click',e=>{if(!e.target.closest('#upAvWrap')&&!e.target.closest('#upStatusLabel')) q('statusPicker').classList.remove('open');});

// ── READ OBSERVER ────────────────────────────────────────────
function setupReadObserver(){
  if(!window.IntersectionObserver) return;
  S.readObserver=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(!entry.isIntersecting) return;if(S.chId&&S.unread[S.chId]>0) setTimeout(()=>markCurrentChannelRead(),500);});},{threshold:0.5});
}
let _markReadPending=false;
async function markCurrentChannelRead(){
  if(!S.chId||_markReadPending||!S.unread[S.chId]) return;
  _markReadPending=true;
  S.unread[S.chId]=0;S.mentionCount[S.chId]=0;
  renderChannels();updateAllSrvDots();
  setStoredChLast(S.chId,S.lastMsgId);
  S.chLastIds[S.chId]=S.lastMsgId;
  await api({action:'mark_channel_read',channelId:S.chId,lastReadId:S.lastMsgId});
  _markReadPending=false;
}
function observeMsg(el){if(S.readObserver&&el&&el.dataset.msgId) S.readObserver.observe(el);}

function chLastKey(chId){return 'tes3_last_ch_'+(S.me?.id||0)+'_'+chId;}
function getStoredChLast(chId){
  try{return parseInt(localStorage.getItem(chLastKey(chId))||'0',10)||0;}catch(e){return 0;}
}
function setStoredChLast(chId,lastId){
  if(!chId||!lastId) return;
  try{localStorage.setItem(chLastKey(chId),String(lastId));}catch(e){}
}

function computeSrvBadge(sid){
  if(sid===S.srvId&&S.mode==='channel'){
    const total=S.channels.reduce((s,ch)=>s+(S.unread[ch.id]||0),0);
    const hasMention=S.channels.some(ch=>(S.mentionCount[ch.id]||0)>0);
    return{count:total,mention:hasMention};
  }
  return{count:S.srvUnread[sid]||0,mention:!!(S.srvMentions[sid])};
}
function renderSrvDots(){
  S.servers.forEach(srv=>{
    const dot=document.getElementById('srvDot'+srv.id);
    const{count,mention}=computeSrvBadge(srv.id);
    if(dot){
      if(mention){dot.style.display='flex';dot.textContent='@';dot.classList.add('mention-dot');}
      else if(count>0){dot.style.display='flex';dot.textContent=count>99?'99+':String(count);dot.classList.remove('mention-dot');}
      else{dot.style.display='none';dot.classList.remove('mention-dot');}
    }
    const icon=document.getElementById('srvIcon'+srv.id);
    if(icon) icon.classList.toggle('srv-mention-ring',!!mention);
  });
}
function updateAllSrvDots(){renderSrvDots();}
function updateServerVoiceDots(){S.servers.forEach(srv=>{const on=!!(S.serverVoiceActive[srv.id]);const dot=document.getElementById('srvVoiceDot'+srv.id);if(dot) dot.classList.toggle('show',on);const icon=document.getElementById('srvIcon'+srv.id);if(icon) icon.classList.toggle('srv-voice-ring',on);});}

// ── HASH ROUTING ─────────────────────────────────────────────
window.addEventListener('hashchange',handleHash);
function handleInviteCode(code){
  code=extractInviteCode(code);
  if(!code) return false;
  if(S.me) showInvitePreview(code);
  else{
    sessionStorage.setItem('pendingInvite',code);
    showInviteHint();
    showInviteGuestLanding(code);
  }
  return true;
}
function getPathInviteCode(){
  const path=location.pathname||'';
  const m=path.match(/(?:^|\/)inv_([A-Za-z0-9_-]+)$/) || path.match(/(?:^|\/)invite\/([A-Za-z0-9_-]+)$/);
  return m?m[1]:'';
}

function detectPendingInviteForGuest(){
  if(S.me) return '';
  const hash=(location.hash||'').slice(1);
  let code='';
  if(hash&&hash.startsWith('inv/')){
    code=hash.slice(4);
    history.replaceState(null,'',location.pathname);
  } else {
    code=getPathInviteCode();
    if(code){
      history.replaceState(null,'',location.pathname.replace(/\/?(?:inv_[A-Za-z0-9_-]+|invite\/[A-Za-z0-9_-]+)$/,'')||'/');
    }
  }
  code=extractInviteCode(code);
  if(code){
    sessionStorage.setItem('pendingInvite',code);
    showInviteGuestLanding(code);
  }
  return code;
}
function handleHash(){
  const hash=location.hash.slice(1);
  if(hash&&hash.startsWith('inv/')){const code=hash.slice(4);history.replaceState(null,'',location.pathname);handleInviteCode(code);return;}
  const pathInvite=getPathInviteCode();
  if(pathInvite){history.replaceState(null,'',location.pathname.replace(/\/?(?:inv_[A-Za-z0-9_-]+|invite\/[A-Za-z0-9_-]+)$/,'')||'/');handleInviteCode(pathInvite);return;}
  if(!hash) return;
  if(hash.startsWith('ch/')){const parts=hash.split('/');const sid=parseInt(parts[1]||'0'),cid=parseInt(parts[2]||'0');if(sid&&cid){selectServer(sid).then(()=>selectChannel(cid));history.replaceState(null,'',location.pathname);}}
}
function renderInviteWelcome(inv){
  // Старый компактный блок больше не главный сценарий, но оставлен как fallback.
  const card=q('inviteWelcomeCard');
  if(!card||!inv) return;
  const icon=q('inviteWelcomeIcon');
  const name=q('inviteWelcomeName');
  const meta=q('inviteWelcomeMeta');
  const members=q('inviteWelcomeMembers');
  const inviter=q('inviteWelcomeInviter');
  const iconHtml=serverIconHtml(inv.serverIcon,inv.serverName,'style="width:100%;height:100%;object-fit:cover"')||ti('castle',18);
  if(icon){ icon.innerHTML=iconHtml; icon.classList.toggle('has-img', !!inv.serverIcon); }
  if(name) name.textContent=inv.serverName||'Приглашение на сервер';
  if(meta) meta.innerHTML=`Современное пространство для общения, голосовых каналов и совместных активностей. Войдите или зарегистрируйтесь, чтобы сразу принять приглашение.`;
  if(members){
    const mc = inv.memberCount;
    members.textContent = (mc===undefined || mc===null || mc==='') ? '—' : String(Number(mc)||mc);
  }
  if(inviter) inviter.textContent=String(inv.creatorName||'Участник');
  card.style.display='block';
  const note=q('invHint'); if(note) note.remove();
}
function showInviteHint(){
  const code=sessionStorage.getItem('pendingInvite')||'';
  if(code) showInviteGuestLanding(code);
}
async function showInviteGuestLanding(code){
  const cleanCode=extractInviteCode(code).replace(/^inv_/,'');
  if(!cleanCode) return;
  q('loginScreen')?.classList.add('has-invite');
  const info=q('loginInfoPanel');
  const note=q('inviteAuthNote');
  if(note){
    note.style.display='none';
    note.innerHTML=`<b>Вас пригласили на сервер.</b><br>Войдите или создайте аккаунт — после этого trueCORD автоматически примет приглашение.`;
  }
  if(info){
    info.innerHTML=`<div class="invite-hero-card">
      <div class="invite-hero-top"><span class="invite-pill">${ti('invite',14)} Приглашение trueCORD</span></div>
      <div class="invite-hero-main">
        <div class="invite-server-icon">${ti('loader',34)}</div>
        <h2 class="invite-hero-title">Проверяем приглашение…</h2>
        <p class="invite-hero-sub">Сейчас загрузим сервер, участников и данные приглашения.</p>
      </div>
      <div class="invite-hero-foot">Ссылка будет сохранена до входа или регистрации.</div>
    </div>`;
  }
  try{
    const r=await api({action:'get_invite_info',code:cleanCode});
    if(!r.ok) throw new Error(r.error||'Invite error');
    const inv=r.invite;
    sessionStorage.setItem('pendingInvite',cleanCode);
    const hasIcon=!!inv.serverIcon;
    const icon=hasIcon?`<img src="${esc(inv.serverIcon)}" alt="">`:ti('castle',38);
    if(info){
      info.innerHTML=`<div class="invite-hero-card">
        <div class="invite-hero-top"><span class="invite-pill">${ti('invite',14)} Вы получили приглашение</span></div>
        <div class="invite-hero-main">
          <div class="invite-server-icon${hasIcon?' has-img':''}">${icon}</div>
          <h2 class="invite-hero-title">${esc(inv.serverName)}</h2>
          <p class="invite-hero-sub">${esc(inv.serverDesc||'Присоединяйтесь к серверу, чтобы открыть каналы и начать общение.')}</p>
          <div class="invite-meta-row">
            <span class="invite-meta-chip">${ti('userGroup',14)} ${Number(inv.memberCount||0)} участников</span>
            <span class="invite-meta-chip">${ti('shield',14)} от ${esc(inv.creatorName||'админа')}</span>
          </div>
        </div>
        <div class="invite-hero-foot">Войдите или зарегистрируйтесь справа. После успешного входа мы автоматически добавим вас на сервер.</div>
      </div>`;
    }
    if(note){
      note.innerHTML=`<b>Приглашение на «${esc(inv.serverName)}».</b><br>Войдите или зарегистрируйтесь, чтобы принять его автоматически.`;
    }
  }catch(e){
    // Даже если данные не пришли, не убираем invite-landing у гостя:
    // карточка остаётся видимой, а код сохраняется для авто-принятия после входа.
    sessionStorage.setItem('pendingInvite',cleanCode);
    if(info){
      info.innerHTML=`<div class="invite-hero-card">
        <div class="invite-hero-top"><span class="invite-pill">${ti('invite',14)} Приглашение trueCORD</span></div>
        <div class="invite-hero-main">
          <div class="invite-server-icon">${ti('castle',38)}</div>
          <h2 class="invite-hero-title">Приглашение на сервер</h2>
          <p class="invite-hero-sub">Войдите или зарегистрируйтесь. После входа trueCORD попробует автоматически принять приглашение.</p>
          <div class="invite-meta-row">
            <span class="invite-meta-chip">${ti('link',14)} код сохранён</span>
            <span class="invite-meta-chip">${ti('shield',14)} данные загрузятся автоматически</span>
          </div>
        </div>
        <div class="invite-hero-foot">Если сервер не откроется после входа, ссылка могла истечь или быть удалена.</div>
      </div>`;
    }
    if(note){
      note.innerHTML=`<b>Приглашение сохранено.</b><br>Войдите или зарегистрируйтесь, чтобы принять его автоматически.`;
    }
  }
}
async function loadInviteLanding(code){
  // Совместимость со старым названием функции.
  return showInviteGuestLanding(code);
}
function extractInviteCode(input){
  input=String(input||'').trim();
  if(!input) return '';
  const hash=input.match(/#\/?inv\/([^\s?#]+)/);
  if(hash) return hash[1].replace(/^inv_/,'').replace(/[^A-Za-z0-9_-]/g,'');
  const direct=input.match(/(?:^|[\s\/#])inv_([A-Za-z0-9_-]+)/);
  if(direct) return direct[1];
  const path=input.match(/(?:^|[\s\/])invite\/([A-Za-z0-9_-]+)/);
  if(path) return path[1];
  return input.replace(/^#?\/?inv\//,'').replace(/^inv_/,'').replace(/[^A-Za-z0-9_-]/g,'');
}
function showJoinInviteModal(){
  showModal(`<h2>${ti('link',18)} ${t('nav.joinByInvite')}</h2>
    <div class="modal-sub">${t('server.joinInviteDesc')}</div>
    <div class="fg"><label class="fl">${t('server.inviteLabel')}</label><input class="fi" id="inviteCodeInput" placeholder="${t('server.invitePlaceholder')}"></div>
    <button class="btn btn-gold btn-full" onclick="openInviteFromInput()">${t('server.checkInvite')}</button>
    <button class="btn btn-ghost btn-full" style="margin-top:8px" onclick="closeModal()">${t('common.cancel')}</button>`);
  setTimeout(()=>q('inviteCodeInput')?.focus(),80);
}
window.openInviteFromInput=function(){
  const code=extractInviteCode(q('inviteCodeInput')?.value||'');
  if(!code){toast(t('server.enterInvite'),'err');return;}
  showInvitePreview(code);
};
async function showInvitePreview(code){
  const cleanCode=code.replace(/^inv_/,'');
  const r=await api({action:'get_invite_info',code:cleanCode});if(!r.ok){toast(r.error,'err');return;}
  const inv=r.invite;
  const iconHtml=serverIconHtml(inv.serverIcon,inv.serverName,'style="width:100%;height:100%;object-fit:cover"')||ti('castle',18);
  const alreadyMember=S.servers.some(s=>s.id===inv.serverId&&s.isMember);
  showModal(`<h2>${ti('link',18)} ${t('server.inviteTitle')}</h2><div style="background:var(--bg3);border-radius:14px;padding:16px;text-align:center;margin-bottom:16px;border:1px solid var(--border2)"><div style="width:64px;height:64px;border-radius:14px;background:var(--bg4);margin:0 auto 10px;display:flex;align-items:center;justify-content:center;font-size:28px;overflow:hidden">${iconHtml}</div><div style="font-size:18px;font-weight:700;color:var(--gold);font-family:var(--font-heading)">${esc(inv.serverName)}</div><div style="font-size:12px;color:var(--text3);margin-top:4px">${ti('userGroup',13)} ${t('server.membersBy',{count:inv.memberCount,name:esc(inv.creatorName)})}</div></div>${alreadyMember?`<button class="btn btn-ghost btn-full" onclick="selectServer(${inv.serverId});closeModal()">${t('server.openAsMember')}</button>`:`<button class="btn btn-gold btn-full" onclick="joinByInvite('${esc(cleanCode)}',${inv.serverId})">${ti('swords',14)} ${t('server.joinShort')}</button>`}<button class="btn btn-ghost btn-full" style="margin-top:8px" onclick="closeModal()">${t('common.close')}</button>`);
}

async function acceptPendingInviteAfterAuth(code){
  const cleanCode=extractInviteCode(code).replace(/^inv_/,'');
  if(!cleanCode) return;
  try{
    let sid=0;
    try{
      const info=await api({action:'get_invite_info',code:cleanCode});
      sid=info?.invite?.serverId||0;
    }catch(e){}
    const r=await api({action:'join_by_invite',code:cleanCode});
    if(!r.ok){
      // Если уже участник или сервер требует ручного подтверждения — покажем обычный preview.
      showInvitePreview(cleanCode);
      return;
    }
    sid=sid||r.serverId||r.sid||r.invite?.serverId||0;
    sessionStorage.removeItem('pendingInvite');
    toast(t('server.welcomeToast'),'ok');
    await loadServers();
    if(sid) await selectServer(sid);
    else if(S.servers?.length) await selectServer(S.servers[0].id);
  }catch(e){
    showInvitePreview(cleanCode);
  }
}
window.joinByInvite=async function(code,sid){const r=await api({action:'join_by_invite',code});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('server.welcomeToast'),'ok');await loadServers();const targetSid=sid||r.serverId||r.sid||0;if(targetSid) await selectServer(targetSid);};
function copyChannelLink(){if(!S.chId) return;const link=`${location.origin+location.pathname}#ch/${S.srvId}/${S.chId}`;navigator.clipboard?.writeText(link).then(()=>toast('Ссылка скопирована '+ti('link',13),'ok'));}

// ── SERVERS ──────────────────────────────────────────────────
async function loadServers(){
  const r=await api({action:'get_servers'});if(!r.ok) return;
  S.servers=r.servers;
  r.servers.forEach(s=>{S.serverVoiceActive[s.id]=!!(s.voiceActive);});
  renderSrvBar();
}
function createServerPolicyAllows(){
  if(!S.me) return false;
  const rule=String(APP_CFG.createServerPermission||'super_admin').toLowerCase().trim();
  const isGlobal=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';
  if(isGlobal) return true;
  const accountReady=!!S.me && (S.me.validated||!APP_CFG.requireValidation);
  if(!accountReady) return false;
  if(rule==='all'||rule==='member') return true;
  if(rule==='project_admin') return S.me?.globalRole==='project_admin';
  if(rule==='super_admin') return S.me?.globalRole==='super_admin';
  if(rule==='admin') return S.me?.globalRole==='project_admin'||S.me?.globalRole==='super_admin';
  return false;
}
function createServerPolicyHint(){
  if(!S.me) return 'Войдите в аккаунт';
  if(!(S.me.validated||!APP_CFG.requireValidation)) return 'Создание сервера доступно после верификации аккаунта';
  return 'Создание серверов сейчас недоступно';
}

function isImageIcon(v){
  v=String(v||'').trim();
  return !!v && (/^(https?:|data:image\/|uploads\/|\.\/uploads\/)/i.test(v) || /\.(png|jpe?g|gif|webp|svg)(\?.*)?$/i.test(v));
}
function imageIconHtml(v,alt='',extra=''){
  v=String(v||'').trim();
  if(!isImageIcon(v)) return '';
  return `<img src="${esc(v)}" alt="${esc(alt)}" ${extra}>`;
}
function serverIconHtml(icon,name,extra=''){
  icon=String(icon||'').trim();
  const img=imageIconHtml(icon,name,extra);
  if(img) return img;
  // Если в базе случайно оказался кусок data/base64 или слишком длинная строка,
  // не выводим её как текст в аватаре сервера.
  if(!icon || icon.length>8 || /[\s="'<>]/.test(icon)) return esc(String(name||'С')[0] || 'С');
  return esc(icon);
}
function channelIconHtml(avatar){
  avatar=String(avatar||'').trim();
  const img=imageIconHtml(avatar,'#');
  return img || '#';
}

function srvOrderKey(){return 'tcSrvOrder_'+(S.me?.id||'anon');}
function loadSrvOrder(){try{const raw=localStorage.getItem(srvOrderKey());if(!raw) return null;const arr=JSON.parse(raw);return Array.isArray(arr)?arr.map(Number):null;}catch(e){return null;}}
function saveSrvOrder(){try{const order=[...document.querySelectorAll('#srvIcons .srv-icon[data-srv-id]')].map(el=>parseInt(el.dataset.srvId,10)).filter(Boolean);localStorage.setItem(srvOrderKey(),JSON.stringify(order));}catch(e){}}
// Order S.servers according to the user's saved drag order; unknown/new servers keep their original relative order at the end.
function applySrvOrder(list){
  const order=loadSrvOrder();
  if(!order||!order.length) return list;
  const pos=new Map();order.forEach((id,i)=>pos.set(id,i));
  return [...list].sort((a,b)=>{
    const pa=pos.has(a.id)?pos.get(a.id):Infinity;
    const pb=pos.has(b.id)?pos.get(b.id):Infinity;
    if(pa!==pb) return pa-pb;
    return 0;
  });
}
let _srvDragEl=null;
let _srvDragActive=false;
function srvDragStart(e){
  _srvDragEl=e.currentTarget;_srvDragActive=true;
  e.currentTarget.classList.add('srv-dragging');
  try{
    e.dataTransfer.effectAllowed='move';
    // Custom type (NOT 'Files') so the chat file-drop handler ignores this drag.
    e.dataTransfer.setData('application/x-truecord-srv',String(e.currentTarget.dataset.srvId||''));
    e.dataTransfer.setData('text/plain',String(e.currentTarget.dataset.srvId||''));
    // Use the icon element itself as the drag image, never the raw <img> file.
    const r=e.currentTarget.getBoundingClientRect();
    e.dataTransfer.setDragImage(e.currentTarget, r.width/2, r.height/2);
  }catch(_){}
}
function srvDragEnd(e){e.currentTarget.classList.remove('srv-dragging');document.querySelectorAll('#srvIcons .srv-drop-before').forEach(el=>el.classList.remove('srv-drop-before'));_srvDragEl=null;_srvDragActive=false;saveSrvOrder();}
function srvDragOver(e){
  if(!_srvDragEl) return;
  e.preventDefault();e.stopPropagation();try{e.dataTransfer.dropEffect='move';}catch(_){}
  const tgt=e.currentTarget;if(tgt===_srvDragEl) return;
  const c=q('srvIcons');const rect=tgt.getBoundingClientRect();
  // Vertical rail on desktop, horizontal on mobile — decide insertion by the dominant axis.
  const horizontal=getComputedStyle(c).flexDirection.startsWith('row');
  const before=horizontal?(e.clientX < rect.left+rect.width/2):(e.clientY < rect.top+rect.height/2);
  document.querySelectorAll('#srvIcons .srv-drop-before').forEach(el=>el.classList.remove('srv-drop-before'));
  if(before){tgt.classList.add('srv-drop-before');c.insertBefore(_srvDragEl,tgt);}
  else{c.insertBefore(_srvDragEl,tgt.nextSibling);}
}
function srvDrop(e){if(_srvDragEl){e.preventDefault();e.stopPropagation();}}

function renderSrvBar(){
  const c=q('srvIcons');c.innerHTML='';
  applySrvOrder(S.servers).forEach(s=>{
    const isMember=s.isMember;
    const d=document.createElement('div');
    const hasVoice=!!S.serverVoiceActive[s.id];
    const hasMention=!!(S.srvMentions&&S.srvMentions[s.id]);
    d.className='srv-icon'+(s.id===S.srvId?' active':'')+(s.kicked?' kicked-srv':'')+(isMember?'':' not-member')+(hasVoice?' srv-voice-ring':'')+(hasMention?' srv-mention-ring':'');
    d.title=s.name+(s.kicked?' (Вы были исключены)':isMember?'':' (нажмите, чтобы вступить)');
    d.id='srvIcon'+s.id;
    d.dataset.srvId=s.id;
    d.onclick=()=>selectServer(s.id);
    // Drag & drop reordering (only real servers, not join/create buttons). Order is saved per user.
    if(isMember && !s.kicked){
      d.setAttribute('draggable','true');
      d.addEventListener('dragstart',srvDragStart);
      d.addEventListener('dragend',srvDragEnd);
      d.addEventListener('dragover',srvDragOver);
      d.addEventListener('drop',srvDrop);
    }
    const innerContent=serverIconHtml(s.icon,s.name,'draggable="false"');
    d.innerHTML=`<div class="srv-indicator"></div><div class="srv-icon-inner">${innerContent}</div><div class="srv-notif-dot" id="srvDot${s.id}"></div><div class="srv-voice-dot${S.serverVoiceActive[s.id]?' show':''}" id="srvVoiceDot${s.id}"></div>`;
    c.appendChild(d);
  });
  const j=document.createElement('div');j.className='srv-icon srv-join-icon';j.title=t('nav.joinByInvite');
  j.innerHTML=`<div class="srv-indicator"></div><div class="srv-icon-inner" style="display:flex;align-items:center;justify-content:center;font-size:18px"><span class="ti">${TI.link}</span></div>`;
  j.onclick=showJoinInviteModal;c.appendChild(j);
  const canCreate=createServerPolicyAllows();
  if(canCreate){
    const a=document.createElement('div');a.className='srv-icon srv-create-icon';a.title=t('server.createTitle');
    a.innerHTML=`<div class="srv-indicator"></div><div class="srv-icon-inner" style="display:flex;align-items:center;justify-content:center;font-size:20px"><span class="ti">${TI.plus}</span></div>`;
    a.onclick=showCreateServerModal;c.appendChild(a);
  }
  renderSrvDots();
  // Управление: глобальные — только панель владельца; серверные — только управление сервером
  const isGlob = isGlobalAdmin();
  const adminBtn = q('srvAdminBtn');
  if(adminBtn){
    // Показываем только серверным admin/owner (не глобальным — у них своя кнопка)
    const isServerAdmin = (S.myRole==='owner'||S.myRole==='admin') && !isGlob;
    if(isServerAdmin && S.srvId){
      adminBtn.style.display='flex'; adminBtn.style.alignItems='center'; adminBtn.style.justifyContent='center';
      adminBtn.className='srv-admin-btn';
      adminBtn.innerHTML=TI.shield;
      adminBtn.onclick=()=>showServerSettings();
      adminBtn.title='Управление сервером';
    } else {
      adminBtn.style.display='none';
    }
  }
  // Иконка владельца проекта / супер-администратора (заменяет серверную для глобальных)
  const saBtn = q('superAdminSrvBtn');
  if(saBtn){
    const isSA = S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin';
    if(isSA){
      saBtn.style.display='flex'; saBtn.style.alignItems='center'; saBtn.style.justifyContent='center';
      saBtn.className='srv-superadmin-btn';
      saBtn.innerHTML=TI.star;
      saBtn.onclick=()=>showAdminPanel();
      saBtn.title=S.me?.name===OWNER_NAME?t('settings.controlPanel'):t('nav.superAdminPanel');
    } else {
      saBtn.style.display='none';
    }
  }
}

async function selectServer(id,silent=false){
  S.srvId=id;S.mode='channel';
  q('dmSidePanel').style.display='none';q('chSidePanel').style.display='flex';q('chSidePanel').style.flexDirection='column';
  const s=S.servers.find(x=>x.id===id);
  q('srvTitle').textContent=s?.name||'Сервер';
  document.querySelectorAll('#srvIcons .srv-icon').forEach(el=>{
    const dotId=el.querySelector('.srv-notif-dot')?.id;
    const sid=dotId?parseInt(dotId.replace('srvDot',''),10):0;
    el.classList.toggle('active',sid===id);
  });
  q('dmSrvBtn').classList.remove('active');

  if(s&&!s.isMember){
    S.currentSrvPendingJoin=s;
    q('welcomeScreen').style.display='none';q('chView').style.display='none';q('dmView').style.display='none';
    q('joinServerScreen').classList.remove('show');
    q('chList').innerHTML='';
    q('srvTitle').textContent=s.name;
    // Показываем приветственное модальное окно
    const iconHtml=serverIconHtml(s.icon,s.name,'style="width:100%;height:100%;object-fit:cover;border-radius:18px"');
    showModal(`<div style="text-align:center">
      <div class="join-modal-icon">${iconHtml}</div>
      <div class="join-modal-name">${esc(s.name)}</div>
      <div class="join-modal-desc">${esc(s.description||t('server.welcomeDefault'))}</div>
      <div class="join-modal-members">${ti('userGroup',13)} ${t('server.membersBy',{count:s.memberCount||'?',name:esc(s.ownerName||s.creatorName||'')}).replace(/\s*[·•].*$/,'')}</div>
      ${s.kicked?`<div style="color:var(--red2);font-size:13px;margin-bottom:12px">${ti('warning',14)} ${t('server.kickedBefore')}</div>`:''}
      <button class="btn btn-gold btn-full" onclick="doJoinCurrentServer();closeModal()">${ti('swords',14)} ${t('server.joinServer')}</button>
      <button class="btn btn-ghost btn-full" style="margin-top:8px" onclick="closeModal()">${t('common.later')}</button>
    </div>`);
    return;
  }
  q('joinServerScreen').classList.remove('show');
  S.currentSrvPendingJoin=null;

  await loadChannels(id); await loadMembers(id); await loadUsers(); saveLocation();
  refreshCurrentServerUnreadNow().catch(()=>{});
  const lastChId=parseInt(localStorage.getItem('lastCh_srv_'+id)||'0');
  const lastCh=lastChId&&S.channels.find(x=>x.id===lastChId);
  if(window.innerWidth<=980&&!silent){
    // Safari iOS: открываем боковую панель каналов при выборе сервера
    const sb=q('chSidebar');
    if(sb&&!sb.classList.contains('open')){
      sb.classList.add('open');
      q('mobileOverlay').classList.add('open');
      sb.getBoundingClientRect(); // forced reflow for Safari
    }
    if(lastCh){await selectChannel(lastChId);return;}
    // На мобильном не выбираем канал автоматически — пользователь видит список
  } else if(!silent){
    // Десктоп: восстанавливаем последний канал, иначе открываем первый
    if(lastCh){await selectChannel(lastChId);return;}
    if(S.channels.length){await selectChannel(S.channels[0].id);return;}
  }
}

window.doJoinCurrentServer=async function(){
  const s=S.currentSrvPendingJoin;if(!s) return;
  const r=await api({action:'join_server',serverId:s.id});
  if(!r.ok){toast(r.error,'err');return;}
  toast(I18N.t('toast.joinedServer',{name:s.name}),'ok');
  s.isMember=true;
  q('joinServerScreen').classList.remove('show');
  S.currentSrvPendingJoin=null;
  renderSrvBar();
  await loadChannels(s.id);await loadMembers(s.id);
  if(S.channels.length) await selectChannel(S.channels[0].id);
};

async function leaveCurrentServer(){
  if(!S.srvId) return;
  const s=S.servers.find(x=>x.id===S.srvId);
  if(!s) return;
  if(!confirm(`Покинуть сервер "${s.name}"?`)) return;
  const r=await api({action:'leave_server',serverId:S.srvId});
  if(!r.ok){toast(r.error,'err');return;}
  toast(I18N.t('toast.leftServer',{name:s.name}),'info');
  s.isMember=false;
  renderSrvBar();
  q('chView').style.display='none';q('welcomeScreen').style.display='flex';
  q('chList').innerHTML='';
}

// ── CHANNELS ─────────────────────────────────────────────────
async function loadChannels(sid){
  const r=await api({action:'get_channels',serverId:sid});if(!r.ok) return;
  S.channels=r.channels;S.myRole=r.myRole;
  S.channels.forEach(ch=>{
    const storedLast=getStoredChLast(ch.id);
    if(ch.id!==S.chId) S.unread[ch.id]=ch.unread||0;
    if(!(ch.id in S.chLastIds)) S.chLastIds[ch.id]=storedLast||null;
  });
  if(sid===S.srvId){
    S.srvUnread[sid]=S.channels.reduce((s,ch)=>s+(ch.id!==S.chId?S.unread[ch.id]||0:0),0);
    S.srvMentions[sid]=S.channels.some(ch=>ch.id!==S.chId&&(S.mentionCount[ch.id]||0)>0);
  }
  renderChannels();updateAllSrvDots();
  await loadVoiceRooms(sid);
}
function getNotif(chId){return localStorage.getItem('notif_'+chId)||'all';}
function setNotif(chId,v){localStorage.setItem('notif_'+chId,v);renderChannels();}
function notifIcon(chId){const v=getNotif(chId);return v==='mentions'?`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`:v==='mute'?`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M18 8a6 6 0 0 0-9.33-4.98M13.73 3.07A6 6 0 0 1 18 8c0 4.37-1.42 6.97-2.42 8H2m9.93-9.93L2 19.2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`:''}

function channelPermAllows(rule,ch){
  rule=String(rule||'all').toLowerCase();
  if(isGlobalAdmin?.()) return true;
  if(ch&&parseInt(ch.ownerId||0,10)===parseInt(S.me?.id||0,10)) return true;
  if(rule==='all') return true;
  if(rule==='members') return !!S.srvId&&['member','moderator','admin','owner'].includes(S.myRole||'member');
  if(rule==='admins') return isAdmin2(S.myRole||'member');
  return false;
}
function canViewChannel(ch){return !!ch&&channelPermAllows(ch.permRead||'all',ch);}
function canWriteChannel(ch){return !!ch&&canViewChannel(ch)&&channelPermAllows(ch.permWrite||'all',ch);}
function canManageChannel(ch){return !!ch&&(!!ch.canManage||isGlobalAdmin?.()||parseInt(ch.ownerId||0,10)===parseInt(S.me?.id||0,10));}
function canManageCurrentServer(){return isAdmin2(S.myRole||'member')||isGlobalAdmin?.();}
function canModerateCurrentServer(){return isMod(S.myRole||'member')||isGlobalAdmin?.();}
function canCreateChannelCurrentServer(){
  const addRule=(APP_CFG.createChannelPermission||'member').toLowerCase();
  const accountReady=!!S.me && (S.me.validated||!APP_CFG.requireValidation);
  return accountReady && (S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin'||['owner','admin'].includes(S.myRole)||addRule==='all'||(addRule==='member'&&['member','moderator','admin','owner'].includes(S.myRole||'member'))||(addRule==='mod'&&['moderator','admin','owner'].includes(S.myRole)));
}
function assertCanCreateChannel(){
  if(canCreateChannelCurrentServer()) return true;
  toast('Нет прав для создания каналов','err');
  return false;
}
function assertCanManageChannel(ch){
  if(canManageChannel(ch)) return true;
  toast('Нет прав для редактирования этого канала','err');
  return false;
}
function renderChannels(){
  const el=q('chList');el.innerHTML='';
  const canAddCh=canCreateChannelCurrentServer();

  // Текстовые каналы
  const textChs=S.channels.filter(ch=>(ch.type||'text')==='text'&&canViewChannel(ch));
  const appChs=S.channels.filter(ch=>ch.type==='app'&&canViewChannel(ch));

  const cat=document.createElement('div');cat.className='ch-category';
  cat.innerHTML=`<span>▸ ${t('channel.categoryText')}</span>${canAddCh?`<span class="cat-add" title="${t('channel.create')}" onclick="event.stopPropagation();showCreateChannelModal()">${ti('plus',13)}</span>`:''}`;
  el.appendChild(cat);
  if(canAddCh){
    const createRow=document.createElement('div');
    createRow.className='channel-create-row';
    createRow.innerHTML=`<span class="ti">${ti('plus',13)}</span><span>${t('channel.createOwn')}</span>`;
    createRow.onclick=()=>showCreateChannelModal();
    el.appendChild(createRow);
  }
  textChs.forEach(ch=>{
    const notif=getNotif(ch.id),unread=S.unread[ch.id]||0,mentions=S.mentionCount[ch.id]||0;
    const isMuted=notif==='mute',isActive=ch.id===S.chId;
    const d=document.createElement('div');
    d.className='ch-item'+(isActive?' active':'')+(unread&&!isMuted&&!isActive?' has-unread':'')+(isMuted?' muted':'');
    d.dataset.chId=ch.id;
    const iconHtml=`<div class="ch-icon">${channelIconHtml(ch.avatar)}</div>`;
    let badges='';
    if(mentions>0&&!isMuted&&!isActive) badges+=`<span class="ch-mention-badge">@${mentions>9?'9+':mentions}</span>`;
    else if(unread>0&&!isMuted&&!isActive) badges+=`<span class="ch-unread-badge">${unread>99?'99+':unread}</span>`;
    const ni=notifIcon(ch.id);
    let acts='';
    if(canManageChannel(ch)) acts+=`<span class="ch-act-btn" onclick="showChSettings(event,${ch.id})">${ti('gear',13)}</span>`;
    acts+=`<span class="ch-act-btn ti" onclick="showNotifModal(event,${ch.id})"><svg viewBox="0 0 24 24" width="13" height="13"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span>`;
    d.innerHTML=`${unread&&!isActive&&!isMuted?`<div class="ch-unread-dot"></div>`:''}${iconHtml}<span class="ch-name">#${esc(ch.name)}</span>${ni?`<span style="font-size:11px;color:var(--text3)">${ni}</span>`:''}<div class="ch-badges">${badges}</div><div class="ch-actions">${acts}</div>`;
    d.onclick=()=>selectChannel(ch.id);
    el.appendChild(d);
  });
    // Мини-приложения
  if(appChs.length||canAddCh){
    const appCat=document.createElement('div');appCat.className='ch-category';
    appCat.innerHTML=`<span>▸ ${t('app.apps')}</span>${canAddCh?`<span class="cat-add" onclick="event.stopPropagation();showCreateAppChannelModal()">${ti('plus',13)}</span>`:''}`;
    el.appendChild(appCat);
    appChs.forEach(ch=>{
      const d=document.createElement('div');
      d.className='ch-item ch-app';d.dataset.chId=ch.id;
      let acts='';
      if(canManageChannel(ch)) acts+=`<span class="ch-act-btn" onclick="event.stopPropagation();showEditAppChannel(${ch.id})">${ti('gear',13)}</span>`;
      d.innerHTML=`<div class="ch-icon">${esc(ch.appIcon||ti('puzzle',16))}</div><span class="ch-name">${esc(ch.name)}</span><div class="ch-actions">${acts}</div>`;
      d.onclick=()=>openMiniApp(ch.id);
      el.appendChild(d);
    });
  }

  renderVoiceRooms(VOICE.rooms||[]);
}

// ── LOAD MORE ────────────────────────────────────────────────
function setupLoadMoreScroll(){
  const wrap=q('messagesWrap');if(!wrap||S._chScrollBound) return;
  S._chScrollBound=true;
  wrap.addEventListener('scroll',()=>{
    if(wrap.scrollTop<90&&S.hasMoreMsgs&&!S.loadingMore) loadMoreMessages();
  },{passive:true});
}
async function loadMoreMessages(){
  const channelId=S.chId;
  if(!channelId||S.loadingMore||!S.hasMoreMsgs) return;
  S.loadingMore=true;
  const list=q('messagesList');
  const firstEl=list?.querySelector('[data-msg-id]');
  const firstId=firstEl?parseInt(firstEl.dataset.msgId):0;
  if(!firstId){S.loadingMore=false;return;}
  const btn=list.querySelector('.load-more-btn');if(btn) btn.classList.add('loading');
  const r=await api({action:'messages',channelId,before:firstId,limit:15});
  S.loadingMore=false;
  if(channelId!==S.chId) return;
  if(!r.ok) return;
  if(btn) btn.remove();
  const msgs=r.messages||[];
  if(msgs.length===0){S.hasMoreMsgs=false;return;}
  const wrap=q('messagesWrap');const prevScrollH=wrap.scrollHeight;
  const frag=document.createDocumentFragment();
  if(r.hasMore){const newBtn=document.createElement('div');newBtn.className='load-more-btn';newBtn.innerHTML='⬆ '+t('channel.loadMore');newBtn.onclick=loadMoreMessages;frag.appendChild(newBtn);}
  else S.hasMoreMsgs=false;
  msgs.forEach(m=>{
    if(m.deleted||m.type==='deleted') return;
    if(list.querySelector(`[data-msg-id="${m.id}"]`)) return;
    const el=buildMsgEl(m);if(!el) return;
    if(m.reactions?.length) S.reactions[m.id]=m.reactions;
    frag.appendChild(el);observeMsg(el);
  });
  list.insertBefore(frag,list.firstChild);
  wrap.scrollTop=wrap.scrollHeight-prevScrollH;renderAllReacts();
}

async function selectChannel(id){
  const targetCh=S.channels.find(c=>parseInt(c.id,10)===parseInt(id,10));
  if(!canViewChannel(targetCh)){toast('Нет доступа','err');renderChannels();return;}
  const loadToken=++S._chLoadingToken;
  // Важно: при клике по текстовому каналу из голосового режима возвращаем
  // режим канала. Иначе showServerSettings() видел S.mode='voice' и шестерёнка
  // сервера после выхода/переключения из войса иногда не прожималась.
  S.mode='channel';
  S.chId=id;S.lastTextChId=id;S.dmUid=null;S.lastMsgId=0;S.hasMoreMsgs=false;S.loadingMore=false;S.chNotifyFloor=Infinity;S._chInitDone=false;
  S.reactions={};S.replyTo=null;S.pendingFiles=[];renderPendingFiles('channel');
  S.unread[id]=0;S.mentionCount[id]=0;
  S.srvUnread[S.srvId]=S.channels.reduce((s,ch)=>s+(ch.id!==id?S.unread[ch.id]||0:0),0);
  S.srvMentions[S.srvId]=S.channels.some(ch=>ch.id!==id&&(S.mentionCount[ch.id]||0)>0);
  // Снимаем уведомления для этого канала
  NOTIF_STORE.clearByKey('ch:'+id);
  renderChannels();updateAllSrvDots();
  q('welcomeScreen').style.display='none';q('dmView').style.display='none';q('voiceStage')&&(q('voiceStage').classList.remove('show'),q('voiceStage').style.display='none');q('joinServerScreen').classList.remove('show');
  q('chView').style.display='flex';q('chView').style.flexDirection='column';if(window.innerWidth<=980) closeOverlays();
  q('hdrCopyLink').style.display='flex';q('hdrCallBtn').style.display='none';if(q('hdrVideoCallBtn')) q('hdrVideoCallBtn').style.display='none';if(q('hdrDmPrivacyBtn')) q('hdrDmPrivacyBtn').style.display='none';q('hdrDmClearBtn').style.display='none';
q('memberToggleBtn').style.display='';
  cancelReply();
  const ch=S.channels.find(c=>c.id===id);updateChHeader(ch);
  q('messagesList').innerHTML='';closeOverlays();
  const r=await api({action:'messages',channelId:id,since:0,limit:20});
  if(loadToken!==S._chLoadingToken||id!==S.chId) return;
  if(r.ok&&r.messages.length){
    const list=q('messagesList');
    S.hasMoreMsgs=!!r.hasMore;
    if(r.hasMore){const btn=document.createElement('div');btn.className='load-more-btn';btn.innerHTML='⬆ '+t('channel.loadMore');btn.onclick=loadMoreMessages;list.appendChild(btn);}
    r.messages.forEach(m=>{if(m.deleted||m.type==='deleted') return;const el=buildMsgEl(m);if(!el) return;list.appendChild(el);observeMsg(el);if(m.reactions?.length) S.reactions[m.id]=m.reactions;S.notifiedMsgIds.add(m.id);});
    S.lastMsgId=r.messages.at(-1).id;renderAllReacts();scrollStableToBottom('messagesWrap');
    await api({action:'mark_channel_read',channelId:id,lastReadId:S.lastMsgId});
  } else {S.hasMoreMsgs=false;scrollStableToBottom('messagesWrap');}
  S.chLastIds[id]=S.lastMsgId;
  setStoredChLast(id,S.lastMsgId);
  // Порог уведомлений канала: уведомляем только о сообщениях новее загруженных при открытии.
  S.chNotifyFloor=S.lastMsgId;
  S._chInitDone=true;
  document.title='trueCORD — #'+(ch?.name||'канал');
  localStorage.setItem('lastCh_srv_'+S.srvId,id);saveLocation();setupLoadMoreScroll();
}

function updateChHeader(ch){
  if(!ch) return;
  q('hdrName').textContent='#'+ch.name;q('hdrTopic').textContent=ch.topic||'';
  if(ch.avatar) q('hdrIcon').innerHTML=`<img src="${ch.avatar}" alt="#" onerror="this.parentElement.textContent='#'">`;
  else q('hdrIcon').textContent='#';
  const banner=q('chBanner');
  if(ch.description||ch.avatar){
    banner.className='show';
    banner.innerHTML=`<div class="banner-av">${ch.avatar&&isImageIcon(ch.avatar)?imageIconHtml(ch.avatar,'#'):ti('megaphone',16)}</div><div style="flex:1;min-width:0"><div class="banner-name">#${esc(ch.name)}</div><div class="banner-desc">${esc(ch.description||'')}</div><div class="banner-meta">${ch.ownerName?`${ti('crown',13)} ${esc(ch.ownerName)}`:''}${ch.topic?` · ${esc(ch.topic)}`:''}</div></div>`;
  } else banner.className='';
}

// ── VOICE ROOMS ──────────────────────────────────────────────
async function loadVoiceRooms(serverId){const r=await api({action:'voice_get_rooms',serverId});if(!r.ok) return;VOICE.rooms=r.rooms;renderVoiceRooms(r.rooms);}
function renderVoiceRooms(rooms){
  if(!rooms) return;
  q('chList').querySelectorAll('.voice-cat,.voice-room,.voice-parts-wrap').forEach(el=>el.remove());
  if(!rooms.length) return;
  const el=q('chList');
  const voiceRule=(APP_CFG.createVoicePermission||'member').toLowerCase();
  const voiceAccountReady=!!S.me && (S.me.validated||!APP_CFG.requireValidation);
  const canManage=voiceAccountReady&&(S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin'||['owner','admin'].includes(S.myRole)||voiceRule==='all'||(voiceRule==='member'&&['member','moderator','admin','owner'].includes(S.myRole||'member'))||(voiceRule==='mod'&&['moderator','admin','owner'].includes(S.myRole)));
  const cat=document.createElement('div');cat.className='ch-category voice-cat';
  cat.innerHTML=`<span>▸ ${t('channel.categoryVoice')}</span>${canManage?`<span class="cat-add" title="${t('voice.newRoom')}" onclick="event.stopPropagation();showCreateVoiceRoomModal()">${ti('plus',13)}</span>`:''}`;
  el.appendChild(cat);
  rooms.forEach(room=>{
    const isActive=VOICE.roomId===room.id;const cnt=room.participants?.length||0;
    const d=document.createElement('div');d.className='voice-room'+(isActive?' active':'');d.dataset.roomId=room.id;
    let actBtns=canManage?`<span class="vr-act-btn" onclick="event.stopPropagation();showVoiceRoomSettings(${room.id},'${esc(room.name)}')">${ti('gear',13)}</span>`:'';
    d.innerHTML=`<div class="vr-icon ti"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M3 18v-6a9 9 0 0 1 18 0v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></div><div class="vr-name">${esc(voiceRoomDisplayName(room.name))}</div><div class="vr-count">${cnt>0?cnt:''}</div><div class="vr-actions">${actBtns}</div>`;
    d.onclick=()=>voiceJoin(room.id,voiceRoomDisplayName(room.name));el.appendChild(d);
    if(room.participants?.length){
      const wrap=document.createElement('div');wrap.className='voice-parts-wrap';
      room.participants.forEach(p=>{
        const isMe=p.userId===S.me?.id;
        const av=p.avatar?`<img src="${p.avatar}" alt="">`:(p.name?p.name[0]:'?');
        const forceMuted=p.forceMuted;
        const muteIcon=(p.muted||forceMuted)?`<span class="vp-mute-icon ti"${forceMuted?` title="${t('voice.modMuted')}"`:''}><svg viewBox="0 0 24 24" width="12" height="12"><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>`:'';
       const isStreaming=!!(VOICE.streamers&&VOICE.streamers[p.userId]);
        const streamBtnHtml=`<button class="vp-stream-btn${isStreaming?' visible':''}" data-uid="${p.userId}" `+
          `onclick="event.stopPropagation();voiceToggleStreamPreview(${p.userId},'${esc(p.name)}')" `+
          `title="${t('voice.watchStream')} ${esc(p.name)}">🖥</button>`;
        const item=document.createElement('div');
item.className='voice-part-item'+(isMe?' is-me':'')+(forceMuted?' force-muted':'')+(VAD.speaking[p.userId]?' speaking':'');
item.dataset.userId=p.userId;
item.innerHTML=`<div class="vp-av">${av}</div><div class="vp-name">${esc(p.name)}</div>`+
  `<div class="vp-wave"><div class="vp-wave-bar"></div><div class="vp-wave-bar"></div>`+
  `<div class="vp-wave-bar"></div><div class="vp-wave-bar"></div></div>${muteIcon}${streamBtnHtml}`;
        if(!isMe){
          item.onclick=function(ev){
            ev.preventDefault();ev.stopPropagation();
            showVoiceUserMenu(ev, p.userId, p.name, p.avatar||'', room.id, forceMuted);
          };
          item.oncontextmenu=function(ev){
            ev.preventDefault();ev.stopPropagation();
            showVoiceUserMenu(ev, p.userId, p.name, p.avatar||'', room.id, forceMuted);
          };
        } else {
          item.onclick=function(){showUserProfile(p.userId);};
        }
        wrap.appendChild(item);
      });
      el.appendChild(wrap);
    }
  });
}

async function voiceJoin(roomId,roomName){
  voiceLoadUserVolumes();
  if(VOICE.roomId===roomId){openVoiceWorkspace(roomId,roomName);return;}
  if(VOICE.roomId!==null) await voiceLeave(true);
  // ★ FIX: нельзя войти в голосовой чат во время активного звонка в ЛС
  if(DMCALL.active){
    toast(ti('warning',14)+' '+t('voice.finishDmCallFirst'),'err',3000);
    return;
  }
  try{
    toast(t('voice.micAccess'),'info',2000);
    VOICE.stream=await getOptimizedMicStream();
  }catch(e){
    toast(ti('close',14)+' '+t('voice.noMicAccess',{error:e.message}),'err');
    return;
  }
  const r=await api({action:'voice_join',roomId});
  if(!r.ok){toast(r.error,'err');stopOptimizedAudioStream(VOICE.stream);VOICE.stream=null;return;}
  VOICE.roomId=roomId;VOICE.roomName=roomName;VOICE.muted=false;VOICE.forceMuted=false;VOICE.lastSigId=0;VOICE.peers={};
  VOICE._reconnecting=false;VOICE._polling=false;
await requestWakeLock();            // ← добавить

 playSound('voice_join');showVoiceBar(roomName);openVoiceWorkspace(roomId,roomName);renderChannels();
setupLocalVAD(VOICE.stream);
startVADLoop();
for(const p of(r.existing||[])){
    // Инициатор — тот у кого меньший userId (избегаем двойного offer)
    const iAmInitiator=(S.me?.id||0)<p.userId;
    await voiceCreatePeer(p.userId,iAmInitiator);
  }
  clearInterval(VOICE.timer);
  // ★ АУДИО FIX 5: интервал 1000мс вместо 2000мс для быстрого обмена ICE кандидатами
  VOICE.timer=setInterval(voicePoll,1500);
  // Если в комнате уже идёт совместный просмотр — попросим текущее состояние.
  setTimeout(()=>{ if(VOICE.roomId===roomId) watchSend('watch-request-state',{}); }, 1800);
}

async function voiceLeave(silent=false){
  // 1. Немедленно останавливаем таймер, чтобы не было новых запросов
  clearInterval(VOICE.timer);VOICE.timer=null;

  // 2. Останавливаем трансляцию экрана
  cleanupScreenAudioMix();
  if(VOICE.screenStream){
    VOICE.screenStream.getTracks().forEach(t=>{try{t.stop();}catch(e){}});
    VOICE.screenStream=null;
  }
  const streamBtn=q('vbStreamBtn');
  if(streamBtn){streamBtn.classList.remove('active-stream');const ic=document.getElementById('vbStreamIcon');if(ic) ic.innerHTML='<svg viewBox="0 0 24 24" width="16" height="16"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';}

  // 3. Уведомляем других участников об остановке стрима если нужно
  if(VOICE.roomId&&S.me&&VOICE.streamers&&VOICE.streamers[S.me.id]){
    const sData=JSON.stringify({userId:S.me.id});
    Object.keys(VOICE.peers).forEach(uid=>{
      try{api({action:'voice_signal',roomId:VOICE.roomId,toUserId:parseInt(uid),type:'stream-stopped',data:sData});}catch(e){}
    });
  }

  // 4. Закрываем все peer-соединения: сначала видео, потом аудио
  const peerClosePromises=Object.entries(VOICE.peers).map(([uid,p])=>new Promise(res=>{
    try{
      // Останавливаем все треки через sender'ы
      if(p.pc){
        p.pc.getSenders().forEach(s=>{try{s.track?.stop();}catch(e){}});
        p.pc.close();
      }
    }catch(e){}
    // Чистим аудио элемент
    if(p.audioEl){try{p.audioEl.pause();p.audioEl.srcObject=null;p.audioEl.remove();}catch(e){}}
    res();
  }));
  await Promise.all(peerClosePromises);
  VOICE.peers={};
  VOICE._reconnecting=false;VOICE._polling=false;

  // 5. Останавливаем локальный стрим микрофона
  if(VOICE.stream){
    stopOptimizedAudioStream(VOICE.stream);
    VOICE.stream=null;
  }

  // 6. Фиксируем ID комнаты и сбрасываем состояние
  const wasRoom=VOICE.roomId;
  VOICE.roomId=null;VOICE.roomName='';VOICE.muted=false;
  VOICE.streamers={};VOICE.currentStreamUserId=null;VOICE.streamInlineActive=false;
  document.body.classList.remove('vglow-local','vglow-remote');
  try{ if(WATCH.active) watchClose(false); }catch(_){}

  // 7. Освобождаем WakeLock
  releaseWakeLock();
  stopVADLoop();

  // 8. Закрываем UI
  closeStreamViewer();
  hideVoiceWorkspace();
  hideVoiceBar();
  renderChannels();

  // 9. Отправляем leave на сервер (после UI cleanup)
  if(wasRoom!==null){
    try{await api({action:'voice_leave'});}catch(e){}
    if(!silent) playSound('voice_leave');
  }
}

function voiceToggleMute(){
  // Prevent un-muting if force-muted by moderator
  if(VOICE.forceMuted && VOICE.muted){
    toast(ti('speakerMuted',14)+' Вы заглушены модератором. Размутить может только модератор.','err',3000);
    return;
  }
  VOICE.muted=!VOICE.muted;
  if(VOICE.stream) VOICE.stream.getAudioTracks().forEach(t=>t.enabled=!VOICE.muted);
  try{if(VOICE._screenMicGain) VOICE._screenMicGain.gain.value=VOICE.muted?0:1;}catch(e){}
  const btn=q('vbMuteBtn');btn.innerHTML=VOICE.muted?`<svg viewBox="0 0 24 24" width="16" height="16"><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`:(`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`);btn.classList.toggle('active-mute',VOICE.muted);
  if(VOICE.roomId) api({action:'voice_set_mute',roomId:VOICE.roomId,muted:VOICE.muted?1:0});
  if(VOICE.muted&&S.me?.id) VAD.speaking[S.me.id]=false;
  renderChannels();renderVoiceWorkspace();voiceStageControlsState();
  updateVoiceSpeakingUI();
}

// ── VOICE PARTICIPANT CONTEXT MENU (Discord-like DM / local mute / volume) ──────
window._voiceMenuTarget=null;
function voiceCtxIcon(kind){
  if(kind==='dm') return `<svg viewBox="0 0 24 24" width="14" height="14" style="flex-shrink:0"><path d="M20 2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3l2.5 3 2.5-3h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`;
  if(kind==='mute') return `<svg viewBox="0 0 24 24" width="14" height="14" style="flex-shrink:0"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;
  if(kind==='vol') return `<svg viewBox="0 0 24 24" width="15" height="15"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;
  if(kind==='kick') return `<svg viewBox="0 0 24 24" width="14" height="14" style="flex-shrink:0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="17" y1="11" x2="23" y2="11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;
  return `<svg viewBox="0 0 24 24" width="14" height="14" style="flex-shrink:0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>`;
}
// Детерминированный яркий цвет заглушки аватара по ключу (имя/id) — стиль Discord.
// Один и тот же пользователь всегда получает один и тот же цвет.
const AVATAR_COLORS=['#5865f2','#3ba55d','#faa61a','#ed4245','#eb459e','#9b59b6','#1abc9c','#e67e22','#3498db','#e91e63','#00bcd4','#ff7043','#7e57c2','#26a69a','#ec407a','#5c6bc0'];
function avatarColor(key){
  const s=String(key==null?'':key);
  let h=0;
  for(let i=0;i<s.length;i++){h=(h*31+s.charCodeAt(i))>>>0;}
  return AVATAR_COLORS[h%AVATAR_COLORS.length];
}
// Возвращает HTML заглушки с цветным фоном (для случаев без загруженного фото).
function avatarFallbackHtml(name,key){
  const ch=esc(String(name||'?')[0]||'?').toUpperCase();
  const col=avatarColor(key!=null?key:name);
  return `<span class="av-fallback" style="background:${col}">${ch}</span>`;
}
function voiceUserAvatarHtml(name, avatar, key){
  if(avatar&&String(avatar).startsWith('http')) return `<img src="${esc(avatar)}" alt="">`;
  return avatarFallbackHtml(name,key!=null?key:name);
}

function placeFloatingMenuInViewport(el,x,y){
  if(!el) return;
  const pad=8;
  el.style.maxWidth=`calc(100vw - ${pad*2}px)`;
  el.style.maxHeight=`calc(100vh - ${pad*2}px)`;
  el.style.overflowY='auto';
  el.style.left='0px';
  el.style.top='0px';
  const r=el.getBoundingClientRect();
  const w=Math.min(r.width||240,window.innerWidth-pad*2);
  const h=Math.min(r.height||220,window.innerHeight-pad*2);
  let left=x;
  let top=y;
  if(left+w>window.innerWidth-pad) left=window.innerWidth-pad-w;
  if(top+h>window.innerHeight-pad) top=window.innerHeight-pad-h;
  if(left<pad) left=pad;
  if(top<pad) top=pad;
  el.style.left=Math.round(left)+'px';
  el.style.top=Math.round(top)+'px';
}
window.showVoiceUserMenu=function(ev, uid, name, avatar='', roomId=0, isCurrentlyForceMuted=false){
  if(ev){ev.preventDefault();ev.stopPropagation();}
  const ctx=q('ctxMenu');
  const volPct=Math.round(voiceGetUserVolume(uid)*100);
  const locallyMuted=voiceGetUserVolume(uid)<=0;
  const isSuperGlobal=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';
  const canMod=isMod(S.myRole)||isSuperGlobal;
  const dmUser=(S.allUsers||[]).find(u=>parseInt(u.id,10)===parseInt(uid,10));
  const canOpenDm=!!dmUser&&canStartDmUser(dmUser);
  window._voiceMenuTarget={uid,name,avatar,roomId,isCurrentlyForceMuted};
  const modHtml=canMod?`
    <div class="ctx-sep"></div>
    <div class="ctx-item" onclick="voiceModMuteFromMenu(${uid});closeCtx()">${voiceCtxIcon('mute')} ${isCurrentlyForceMuted?'Размутить в войсе':'Замутить в войсе'}</div>
    <div class="ctx-item danger" onclick="voiceKickFromMenu(${uid});closeCtx()">${voiceCtxIcon('kick')} Отключить из войса</div>`:'';
  ctx.innerHTML=`
    <div class="voice-user-card">
      <button class="voice-user-close" type="button" title="Закрыть" onclick="closeCtx();event.stopPropagation();"><svg viewBox="0 0 24 24"><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
      <div class="voice-user-head">
        <div class="vu-av">${voiceUserAvatarHtml(name,avatar)}</div>
        <div class="vu-name">${esc(name)}</div>
      </div>
      ${canOpenDm?`<div class="ctx-item" onclick="voiceOpenDmFromMenu();closeCtx()">${voiceCtxIcon('dm')} Написать в ЛС</div>`:''}
      <div class="ctx-item" onclick="voiceToggleLocalMute(${uid});closeCtx()">${voiceCtxIcon('mute')} ${locallyMuted?'Размутить':'Замутить'}</div>
      <div class="ctx-sep"></div>
      <div class="ctx-volume-box" onclick="event.stopPropagation()">
        <div class="ctx-volume-top"><span>Громкость пользователя</span><span class="ctx-volume-val" id="ctxVoiceVolVal">${volPct}%</span></div>
        <div class="ctx-volume-row">${voiceCtxIcon('vol')}<input class="ctx-volume-slider" id="ctxVoiceVolSlider" type="range" min="0" max="200" value="${volPct}" oninput="voiceSetUserVolume(${uid},this.value)"></div>
      </div>
      ${modHtml}
    </div>`;
  const x=ev?.clientX??(window.innerWidth/2), y=ev?.clientY??(window.innerHeight/2);
  ctx.classList.add('open');
  placeFloatingMenuInViewport(ctx,x,y);
};
// Backward compatibility for old moderator context callers.
window.showVoicePartCtx=function(ev, uid, name, roomId, isCurrentlyForceMuted){
  return showVoiceUserMenu(ev,uid,name,'',roomId,isCurrentlyForceMuted);
};

let _voiceUserMenuMouseGuardInstalled=false;
function installVoiceUserMenuMouseGuard(){
  if(_voiceUserMenuMouseGuardInstalled) return;
  _voiceUserMenuMouseGuardInstalled=true;
  document.addEventListener('mousemove',function(ev){
    const ctx=q('ctxMenu');
    if(!ctx||!ctx.classList.contains('open')||!ctx.querySelector('.voice-user-card')) return;
    const r=ctx.getBoundingClientRect();
    const pad=72;
    if(ev.clientX<r.left-pad||ev.clientX>r.right+pad||ev.clientY<r.top-pad||ev.clientY>r.bottom+pad){
      closeCtx();
    }
  },{passive:true});
}
installVoiceUserMenuMouseGuard();
window.voiceOpenDmFromMenu=async function(){
  const t=window._voiceMenuTarget;if(!t) return;
  const dmUser=(S.allUsers||[]).find(u=>parseInt(u.id,10)===parseInt(t.uid,10));
  if(!dmUser||!canStartDmUser(dmUser)){toast('Нет прав для личных сообщений с этим пользователем','err');return;}
  await openDmMode();
  await openDmConv(t.uid,t.name,t.avatar||'');
};
window.voiceToggleLocalMute=function(uid){
  const cur=voiceGetUserVolume(uid);
  voiceSetUserVolume(uid,cur<=0?100:0);
};

function voiceFindRoomParticipant(roomId, uid){
  roomId=parseInt(roomId||VOICE.roomId||0,10);
  uid=parseInt(uid,10)||0;
  const room=(VOICE.rooms||[]).find(r=>parseInt(r.id,10)===roomId);
  if(!room) return null;
  const p=(room.participants||[]).find(x=>parseInt(x.userId,10)===uid);
  return p||null;
}
function voiceUpdateRoomParticipant(roomId, uid, patch){
  roomId=parseInt(roomId||VOICE.roomId||0,10);
  uid=parseInt(uid,10)||0;
  const room=(VOICE.rooms||[]).find(r=>parseInt(r.id,10)===roomId);
  if(!room||!Array.isArray(room.participants)) return;
  const idx=room.participants.findIndex(x=>parseInt(x.userId,10)===uid);
  if(idx>=0) Object.assign(room.participants[idx],patch||{});
}
function voiceRemoveRoomParticipant(roomId, uid){
  roomId=parseInt(roomId||VOICE.roomId||0,10);
  uid=parseInt(uid,10)||0;
  const room=(VOICE.rooms||[]).find(r=>parseInt(r.id,10)===roomId);
  if(!room||!Array.isArray(room.participants)) return;
  room.participants=room.participants.filter(x=>parseInt(x.userId,10)!==uid);
}
window.voiceMuteUser=async function(uid,name='',roomId=0,forceMute=1){
  uid=parseInt(uid,10)||0;
  roomId=parseInt(roomId||VOICE.roomId||0,10);
  forceMute=forceMute?1:0;
  if(!uid||!roomId){toast('Не удалось определить пользователя или голосовой канал','err');return;}
  if(uid===S.me?.id){toast('Нельзя модерировать самого себя в войсе','err');return;}
  const label=name||voiceParticipantName(uid)||'пользователь';
  const r=await api({action:'voice_mute_user',roomId:roomId,targetId:uid,forceMute:forceMute});
  if(!r.ok){toast(r.error||'Не удалось изменить мут в войсе','err',3500);return;}
  // Оптимистично обновляем интерфейс сразу, не дожидаясь следующего heartbeat.
  voiceUpdateRoomParticipant(roomId,uid,{forceMuted:!!forceMute,muted:!!forceMute});
  if(!forceMute){
    const p=voiceFindRoomParticipant(roomId,uid);
    if(p) p.muted=false;
  }
  renderChannels();
  renderVoiceWorkspace();
  toast((forceMute?'Замутил в войсе: ':'Размутил в войсе: ')+label,'ok',2500);
};
window.voiceKickUser=async function(uid,name='',roomId=0){
  uid=parseInt(uid,10)||0;
  roomId=parseInt(roomId||VOICE.roomId||0,10);
  if(!uid||!roomId){toast('Не удалось определить пользователя или голосовой канал','err');return;}
  if(uid===S.me?.id){toast('Нельзя выгнать самого себя из войса','err');return;}
  const label=name||voiceParticipantName(uid)||'пользователь';
  const r=await api({action:'voice_kick_user',roomId:roomId,targetId:uid});
  if(!r.ok){toast(r.error||'Не удалось отключить пользователя от войса','err',3500);return;}
  // Сразу убираем пользователя из локальной сетки и закрываем его peer, чтобы кнопка визуально сработала мгновенно.
  voiceRemoveRoomParticipant(roomId,uid);
  try{ if(typeof _voiceClosePeer==='function') _voiceClosePeer(uid); }catch(e){}
  try{ delete VAD.speaking[uid]; }catch(e){}
  renderChannels();
  renderVoiceWorkspace();
  toast('Отключил из войса: '+label,'ok',2500);
};

window.voiceModMuteFromMenu=function(uid){
  const t=window._voiceMenuTarget;if(!t) return;
  voiceMuteUser(uid,t.name,t.roomId,t.isCurrentlyForceMuted?0:1);
};
window.voiceKickFromMenu=function(uid){
  const t=window._voiceMenuTarget;if(!t) return;
  voiceKickUser(uid,t.name,t.roomId);
};

// Handle incoming voice-kicked and voice-force-mute signals
// ══════════════════════════════════════════════════════════════
//  Совместный просмотр YouTube (синхронный, через voice-сигналинг)
// ══════════════════════════════════════════════════════════════
const WATCH={active:false,player:null,videoId:'',apiLoading:false,apiReady:false,
  suppress:false,        // подавляем исходящие события когда применяем удалённое состояние
  lastSentAt:0};

// Извлекает ID видео из ссылки YouTube (watch?v=, youtu.be/, shorts/, embed/).
function watchParseId(url){
  if(!url) return '';
  url=String(url).trim();
  // голый 11-символьный id
  if(/^[\w-]{11}$/.test(url)) return url;
  let m;
  if((m=url.match(/[?&]v=([\w-]{11})/))) return m[1];
  if((m=url.match(/youtu\.be\/([\w-]{11})/))) return m[1];
  if((m=url.match(/\/shorts\/([\w-]{11})/))) return m[1];
  if((m=url.match(/\/embed\/([\w-]{11})/))) return m[1];
  if((m=url.match(/\/live\/([\w-]{11})/))) return m[1];
  return '';
}

function watchLoadApi(cb){
  if(window.YT && window.YT.Player){ WATCH.apiReady=true; cb&&cb(); return; }
  // если уже грузится — дождёмся
  const prev=window.onYouTubeIframeAPIReady;
  window.onYouTubeIframeAPIReady=function(){ WATCH.apiReady=true; if(typeof prev==='function'){try{prev();}catch(e){}} cb&&cb(); };
  if(WATCH.apiLoading) return;
  WATCH.apiLoading=true;
  const s=document.createElement('script');
  s.src='https://www.youtube.com/iframe_api';
  s.onerror=()=>{WATCH.apiLoading=false;toast(tf('watch.apiError','Не удалось загрузить YouTube'),'err');};
  document.head.appendChild(s);
}

// Запрос ссылки у пользователя (открывает модалку ввода).
window.watchPrompt=function(){
  if(!VOICE.roomId){ toast(tf('watch.needVoice','Зайдите в голосовой канал'),'info'); return; }
  showModal(`
    <div style="padding:4px">
      <h2 style="margin:0 0 6px">${ti('chat',18)} ${tf('watch.title','Совместный просмотр')}</h2>
      <p style="margin:0 0 14px;color:var(--text3);font-size:13px">${tf('watch.hint','Вставьте ссылку на видео YouTube — оно запустится у всех в этом голосовом канале одновременно.')}</p>
      <input class="fi" id="watchUrlInp" placeholder="https://youtube.com/watch?v=…" style="width:100%;margin-bottom:14px" onkeydown="if(event.key==='Enter')watchStartFromInput()">
      <div class="btn-row">
        <button class="btn btn-ghost" onclick="closeModal()">${tf('common.cancel','Отмена')}</button>
        <button class="btn btn-gold" onclick="watchStartFromInput()">${tf('watch.start','Запустить')}</button>
      </div>
    </div>`);
  setTimeout(()=>q('watchUrlInp')?.focus(),50);
};

window.watchStartFromInput=function(){
  const v=q('watchUrlInp')?.value||'';
  const id=watchParseId(v);
  if(!id){ toast(tf('watch.badUrl','Не похоже на ссылку YouTube'),'err'); return; }
  closeModal();
  watchOpen(id,0,true,true);   // открываем локально и рассылаем всем
};

// Открыть плеер. broadcast=true — разослать остальным; autoplay — стартовать воспроизведение.
function watchOpen(videoId,startSeconds,broadcast,autoplay){
  if(!videoId) return;
  WATCH.videoId=videoId; WATCH.active=true;
  const wrap=q('watchPlayerWrap'); const empty=q('voiceStageEmpty');
  if(wrap) wrap.style.display='block';
  if(empty) empty.style.display='none';
  q('voiceStageStream')?.classList.add('watch-active');
  watchLoadApi(()=>{
    const host=q('watchPlayerHost'); if(!host) return;
    host.innerHTML='<div id="watchYtTarget"></div>';
    WATCH.player=new YT.Player('watchYtTarget',{
      width:'100%',height:'100%',videoId:videoId,
      playerVars:{autoplay:autoplay?1:0,rel:0,modestbranding:1,playsinline:1,start:Math.floor(startSeconds||0)},
      events:{
        'onReady':(e)=>{ if(startSeconds>0){try{e.target.seekTo(startSeconds,true);}catch(_){}} if(autoplay){try{e.target.playVideo();}catch(_){}} },
        'onStateChange':watchOnStateChange
      }
    });
  });
  if(broadcast) watchSend('watch-open',{videoId:videoId,t:Math.floor(startSeconds||0)});
}

// Локальные изменения плеера → рассылаем остальным (если не мы применяем удалённое).
function watchOnStateChange(e){
  if(WATCH.suppress) return;
  if(!VOICE.roomId) return;
  const now=Date.now();
  let t=0; try{t=WATCH.player.getCurrentTime();}catch(_){}
  if(e.data===YT.PlayerState.PLAYING){ watchSend('watch-play',{t}); }
  else if(e.data===YT.PlayerState.PAUSED){ watchSend('watch-pause',{t}); }
}

function watchSend(type,payload){
  if(!VOICE.roomId) return;
  WATCH.lastSentAt=Date.now();
  api({action:'watch_signal',roomId:VOICE.roomId,type:type,data:JSON.stringify(payload||{})}).catch(()=>{});
}

// Входящие watch-сигналы от других участников.
function watchHandleSignal(sig){
  let d={}; try{d=JSON.parse(sig.data||'{}');}catch(_){}
  switch(sig.type){
    case 'watch-open':
      if(d.videoId){ toast(`${esc(sig.name||'')}: ${tf('watch.opened','включил совместный просмотр')}`,'info'); watchOpen(d.videoId,d.t||0,false,true); }
      break;
    case 'watch-close':
      watchClose(false);
      break;
    case 'watch-request-state':
      // Кто-то только что подключился — отправим текущее состояние (если плеер у нас есть).
      if(WATCH.active&&WATCH.player){ let t=0,playing=false; try{t=WATCH.player.getCurrentTime();playing=WATCH.player.getPlayerState()===YT.PlayerState.PLAYING;}catch(_){}
        watchSend('watch-state',{videoId:WATCH.videoId,t,playing}); }
      break;
    case 'watch-state':
      if(d.videoId){ if(!WATCH.active||WATCH.videoId!==d.videoId){ watchOpen(d.videoId,d.t||0,false,d.playing); } else { watchApply(d.t,d.playing); } }
      break;
    case 'watch-play':  watchApply(d.t,true); break;
    case 'watch-pause': watchApply(d.t,false); break;
    case 'watch-seek':  watchApply(d.t,null); break;
  }
}

// Применить удалённое состояние к нашему плееру, не порождая эхо-сигналов.
function watchApply(t,playing){
  if(!WATCH.player) return;
  WATCH.suppress=true;
  try{
    if(typeof t==='number'){
      let cur=0; try{cur=WATCH.player.getCurrentTime();}catch(_){}
      if(Math.abs(cur-t)>1.5){ WATCH.player.seekTo(t,true); }   // подстраиваемся только при заметном расхождении
    }
    if(playing===true) WATCH.player.playVideo();
    else if(playing===false) WATCH.player.pauseVideo();
  }catch(_){}
  setTimeout(()=>{WATCH.suppress=false;},400);
}

// Кнопка «ресинхронизация»: запросить актуальное состояние у других.
window.watchSyncPull=function(ev){ ev&&ev.stopPropagation(); watchSend('watch-request-state',{}); toast(tf('watch.resyncing','Синхронизация…'),'info',1500); };

// Закрыть просмотр. broadcast=true — сообщить остальным.
window.watchClose=function(broadcast){
  if(broadcast && WATCH.active) watchSend('watch-close',{});
  WATCH.active=false; WATCH.videoId='';
  try{ WATCH.player&&WATCH.player.destroy&&WATCH.player.destroy(); }catch(_){}
  WATCH.player=null;
  const wrap=q('watchPlayerWrap'); if(wrap) wrap.style.display='none';
  const host=q('watchPlayerHost'); if(host) host.innerHTML='';
  q('voiceStageStream')?.classList.remove('watch-active');
  // вернуть пустой плейсхолдер, если нет активной видеотрансляции
  if(!VOICE.currentStreamUserId){ const empty=q('voiceStageEmpty'); if(empty) empty.style.display=''; }
};

function handleVoiceSignalSpecial(sig){
  // ── Совместный просмотр ──
  if(sig.type && sig.type.indexOf('watch-')===0){ watchHandleSignal(sig); return true; }
  if(sig.type==='voice-kicked'){
    try{
      const d=JSON.parse(sig.data);
      toast('⚠ '+t('voice.kickedFromVoice',{name:d.kickedByName||t('roles.moderator')}),'err',5000);
    }catch(e){}
    voiceLeave(true);
    return true;
  }
  if(sig.type==='voice-force-mute'){
    try{
      const d=JSON.parse(sig.data);
      if(d.forceMute){
        VOICE.forceMuted=true;
        VOICE.muted=true;
        if(VOICE.stream) VOICE.stream.getAudioTracks().forEach(t=>t.enabled=false);
        toast(`${ti('speakerMuted',14)} ${t('voice.modMuted')}`,'err',4000);
      } else {
        VOICE.forceMuted=false;
        toast(ti('speaker',14)+' '+t('voice.mic'),'ok',3000);
      }
      // Update mute button UI
      const btn=q('vbMuteBtn');
      if(btn){
        btn.innerHTML=VOICE.muted?`<svg viewBox="0 0 24 24" width="16" height="16"><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`:(`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`);
        btn.classList.toggle('active-mute',VOICE.muted);
      }
      renderChannels();
      if(VOICE.roomId) api({action:'voice_set_mute',roomId:VOICE.roomId,muted:VOICE.muted?1:0});
    }catch(e){}
    return true;
  }
  return false;
}


function voiceLoadUserVolumes(){
  if(!S.me) return;
  try{
    const raw=localStorage.getItem('tes3_voice_user_volumes_'+S.me.id);
    VOICE.userVolumes=raw?JSON.parse(raw):{};
  }catch(e){VOICE.userVolumes={};}
}
function voiceSaveUserVolumes(){
  if(!S.me) return;
  try{localStorage.setItem('tes3_voice_user_volumes_'+S.me.id,JSON.stringify(VOICE.userVolumes||{}));}catch(e){}
}
function voiceGetUserVolume(uid){
  uid=String(uid);
  const v=VOICE.userVolumes&&VOICE.userVolumes[uid]!=null?parseFloat(VOICE.userVolumes[uid]):1;
  return Number.isFinite(v)?Math.max(0,Math.min(2,v)):1;
}
function voiceCalcPeerVolume(uid){
  if(VOICE.speakerMuted) return 0;
  const global=Math.max(0,Math.min(1,VOICE.volume||1));
  return Math.max(0,Math.min(1,global*voiceGetUserVolume(uid)));
}
function voiceApplyPeerVolume(uid){
  const p=VOICE.peers?.[uid];
  if(p&&p.audioEl) p.audioEl.volume=voiceCalcPeerVolume(uid);
}
function voiceApplyAllPeerVolumes(){
  Object.keys(VOICE.peers||{}).forEach(uid=>voiceApplyPeerVolume(uid));
}
function voiceVolumeIcon(uid){
  const v=voiceGetUserVolume(uid);
  if(v<=0){return `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;}
  return `<svg viewBox="0 0 24 24"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;
}
window.voiceSetUserVolume=function(uid,val){
  uid=String(uid);
  const pct=Math.max(0,Math.min(200,parseInt(val,10)||0));
  if(!VOICE.userVolumes) VOICE.userVolumes={};
  VOICE.userVolumes[uid]=pct/100;
  voiceSaveUserVolumes();
  voiceApplyPeerVolume(uid);
  const ctxLbl=q('ctxVoiceVolVal');if(ctxLbl) ctxLbl.textContent=pct+'%';
  const ctxSlider=q('ctxVoiceVolSlider');if(ctxSlider) ctxSlider.value=pct;
};
window.voiceResetUserVolume=function(uid){
  uid=String(uid);
  if(VOICE.userVolumes) delete VOICE.userVolumes[uid];
  voiceSaveUserVolumes();voiceApplyPeerVolume(uid);renderChannels();
};
window.voiceToggleUserVolumePop=function(uid,ev){
  if(ev){ev.stopPropagation();ev.preventDefault();}
  const wrap=document.querySelector(`.vp-volume-wrap[data-uid="${uid}"]`);
  if(!wrap) return;
  document.querySelectorAll('.vp-volume-wrap.open').forEach(w=>{if(w!==wrap) w.classList.remove('open');});
  wrap.classList.toggle('open');
};

function voiceToggleSpeaker(){
  VOICE.speakerMuted=!VOICE.speakerMuted;
  voiceApplyAllPeerVolumes();
  const btn=q('vbSpkBtn');
  if(btn){btn.innerHTML=VOICE.speakerMuted?`<svg viewBox="0 0 24 24" width="16" height="16"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`:(`<svg viewBox="0 0 24 24" width="16" height="16"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`);btn.classList.toggle('active-mute',VOICE.speakerMuted);}
  const slider=q('vbVolSlider');if(slider) slider.value=VOICE.speakerMuted?0:Math.round((VOICE.volume||1)*100);
  const lbl=q('vbVolLabel');if(lbl) lbl.textContent=(VOICE.speakerMuted?0:Math.round((VOICE.volume||1)*100))+'%';
  voiceStageControlsState();
  renderVoiceWorkspace();
}

function voiceSetVolume(val){
  VOICE.volume=parseInt(val)/100;
  VOICE.speakerMuted=false;
  voiceApplyAllPeerVolumes();
  const btn=q('vbSpkBtn');if(btn){btn.innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" stroke="currentColor" stroke-width="1.8" fill="currentColor" stroke-linejoin="round"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg>`;btn.classList.remove('active-mute');}
  const lbl=q('vbVolLabel');if(lbl) lbl.textContent=val+'%';
  voiceStageControlsState();
  renderVoiceWorkspace();
}


async function getDisplayMediaFortrueCORD(fps,wantAudio){
  if(!navigator.mediaDevices?.getDisplayMedia) throw new Error(t('stream.unsupportedDisplayMedia'));
  const targetFps=isMobileLike()?Math.max(15,Math.min(fps||24,30)):Math.max(24,Math.min(fps||30,60));
  const video={frameRate:{ideal:targetFps,max:targetFps},width:{ideal:isMobileLike()?1280:1920},height:{ideal:isMobileLike()?720:1080},cursor:'always'};
  const audioFull={
    echoCancellation:false,
    noiseSuppression:false,
    autoGainControl:false,
    channelCount:{ideal:2},
    sampleRate:{ideal:48000},
    sampleSize:{ideal:16}
  };
  const tries=[];
  if(wantAudio){
    // ВАЖНО: не используем общий systemAudio:'include' как fallback.
    // Общий системный микс захватывает и входящий voice-чата, поэтому зрители
    // слышат себя/других повторно в звуке трансляции. Просим только звук
    // выбранного окна/вкладки. Если браузер не умеет такой audio track, лучше
    // запустить стрим без звука, чем вернуть эхо voice-канала.
    tries.push({video,audio:audioFull,systemAudio:'exclude',windowAudio:'window',surfaceSwitching:'include',selfBrowserSurface:'exclude',preferCurrentTab:false});
    tries.push({video,audio:true,systemAudio:'exclude',windowAudio:'window',surfaceSwitching:'include',selfBrowserSurface:'exclude',preferCurrentTab:false});
  }
  tries.push({video,audio:false,systemAudio:'exclude',selfBrowserSurface:'exclude',preferCurrentTab:false});
  let lastErr=null;
  for(const c of tries){
    try{
      const st=await navigator.mediaDevices.getDisplayMedia(c);
      return sanitizeDisplayStreamAudio(st,!!wantAudio);
    }catch(e){lastErr=e;if(e&&e.name==='NotAllowedError') throw e;}
  }
  throw lastErr||new Error('getDisplayMedia failed');
}

function sanitizeDisplayStreamAudio(stream,wantAudio){
  if(!stream) return stream;
  const videoTrack=stream.getVideoTracks?.()[0]||null;
  const settings=videoTrack?.getSettings?.()||{};
  const surface=String(settings.displaySurface||'').toLowerCase();
  const audioTracks=stream.getAudioTracks?.()||[];

  // Если выбран весь экран/монитор, браузеры часто отдают общий системный микс.
  // Такой микс почти всегда содержит голоса из voice-чата. Чтобы не было эха,
  // не отправляем этот audio track зрителям.
  if(wantAudio&&audioTracks.length&&surface==='monitor'){
    audioTracks.forEach(t=>{try{stream.removeTrack(t);t.stop();}catch(e){}});
    setTimeout(()=>toast(t('stream.monitorAudioMuted'),'info',6500),0);
  }
  return stream;
}

async function getCameraMediaFortrueCORD(fps){
  const video={
    width:{ideal:isMobileLike()?1280:1920},
    height:{ideal:isMobileLike()?720:1080},
    frameRate:{ideal:Math.min(fps||30,60),max:Math.min(fps||30,60)},
    facingMode:'user'
  };
  return navigator.mediaDevices.getUserMedia({video,audio:false});
}

function getSelectedStreamMode(){
  return q('streamSourceCamera')?.checked?'camera':'screen';
}

function updateStreamModalModeUI(){
  const mode=getSelectedStreamMode();
  const audioRow=q('streamShareAudioRow');
  const audioCb=q('streamShareAudio');
  if(audioRow) audioRow.style.opacity=mode==='screen'?'1':'.45';
  if(audioCb) audioCb.disabled=mode!=='screen';
}

function cleanupScreenAudioMix(){
  try{VOICE._screenAudioCleanup?.();}catch(e){}
  VOICE._screenAudioCleanup=null;
  VOICE._screenAudioTrack=null;
  VOICE._screenMicGain=null;
}

function buildScreenShareAudioTrack(screenStream){
  cleanupScreenAudioMix();
  const screenAudioTrack=screenStream?.getAudioTracks?.()[0]||null;
  if(!screenAudioTrack) return null;

  // ВАЖНО: не миксуем сюда микрофон и не берём общий output голосового чата.
  // Микрофон уже идёт отдельным voice audio sender'ом. Если смешать его со
  // звуком трансляции, зрители получают дубль голоса/эхо. Если в системный
  // audio capture сам браузер/ОС уже включил голосовой чат, JS не может
  // вычесть его из системного трека, но мы хотя бы не добавляем второй дубль.
  VOICE._screenAudioTrack=screenAudioTrack;
  VOICE._screenAudioCleanup=()=>{};
  return screenAudioTrack;
}

async function replaceVoiceAudioTrackForAll(track,mode){
  if(!track) return;
  for(const [uid,peer] of Object.entries(VOICE.peers)){
    await setPeerOutgoingAudioTrack(peer,track,mode||'mic');
  }
}

async function renegotiateVoicePeers(reason,opts={}){
  const forceLocalOffer=!!opts.forceLocalOffer;
  const onlyTo=opts.onlyTo?parseInt(opts.onlyTo,10):0;
  for(const [uid,peer] of Object.entries(VOICE.peers)){
    const uidNum=parseInt(uid,10);
    if(onlyTo&&uidNum!==onlyTo) continue;
    const pc=peer?.pc;
    if(!pc) continue;

    // Обычные служебные renegotiate делает только сторона с меньшим userId,
    // но трансляция экрана/камеры добавляет локальный video m-line.
    // Поэтому владелец трансляции обязан отправить offer сам, иначе зрители
    // видят кнопку стрима, но получают "Трансляция недоступна".
    if(!forceLocalOffer&&!voiceShouldInitiatePeer(uidNum)) continue;

    try{
      if(pc.signalingState!=='stable') continue;
      const offer=await createOptimizedOffer(pc,peer._audioBitrate||VOICE_AUDIO.mid);
      await pc.setLocalDescription(offer);
      if(VOICE.roomId&&VOICE.peers[uid]===peer){
        await api({action:'voice_signal',roomId:VOICE.roomId,toUserId:uidNum,type:'offer',data:JSON.stringify(pc.localDescription)});
      }
    }catch(e){console.warn('renegotiateVoicePeers failed:',reason,uid,e);}
  }
}

async function voiceStartStream(){
  if(VOICE.screenStream){voiceStopStream();return;}
  const cur=VOICE.streamFps||30;
  const savedMode=VOICE.streamMode||'screen';
  const presets=[25,30,45,60,75,90];
  const btns=presets.map(v=>`<button class="btn ${v===cur?'btn-gold':'btn-ghost'}" style="flex:1;padding:8px 0;font-size:14px;font-weight:700" onclick="setStreamFps(${v});document.getElementById('streamFpsSelected').value=${v};document.querySelectorAll('#streamFpsGrid .btn').forEach(b=>b.className='btn btn-ghost');this.className='btn btn-gold'">${v}</button>`).join('');
  showModal(`<h2><svg viewBox="0 0 24 24" width="18" height="18" style="vertical-align:middle;margin-right:6px"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><span data-i18n="stream.settingsTitle">Настройки трансляции</span></h2>
    <p class="sub" data-i18n="stream.settingsSubtitle">Источник, FPS и адаптивный битрейт. При плохой сети качество видео снизится автоматически, а звук останется приоритетным.</p>
    <input type="hidden" id="streamFpsSelected" value="${cur}">
    <div class="fg"><label class="fl" data-i18n="stream.sourceTitle">Источник трансляции</label>
      <label style="display:flex;align-items:center;gap:8px;margin:6px 0;color:var(--text)"><input type="radio" name="streamSource" id="streamSourceScreen" value="screen" ${savedMode!=='camera'?'checked':''} onchange="updateStreamModalModeUI()"> <span data-i18n="stream.sourceScreen">Экран / окно / вкладка</span></label>
      <label style="display:flex;align-items:center;gap:8px;margin:6px 0;color:var(--text)"><input type="radio" name="streamSource" id="streamSourceCamera" value="camera" ${savedMode==='camera'?'checked':''} onchange="updateStreamModalModeUI()"> <span data-i18n="stream.sourceCamera">Камера</span></label>
    </div>
    <label id="streamShareAudioRow" style="display:flex;align-items:flex-start;gap:8px;margin:10px 0 14px;color:var(--text2);font-size:13px;line-height:1.35">
      <input type="checkbox" id="streamShareAudio" checked style="margin-top:2px">
      <span><b style="color:var(--text)" data-i18n="stream.shareAudio">Передавать звук экрана/игры</b><br><span data-i18n="stream.shareAudioDesc">Для звука без эха выбери окно игры или вкладку. Если браузер не даст audio track, будет только видео.</span></span>
    </label>
    <div id="streamFpsGrid" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px">${btns}</div>
    <div class="fg"><label class="fl" data-i18n="stream.customFps">Или вручную (25–90)</label>
      <input class="fi" type="number" id="customFpsInput" min="25" max="90" value="${cur}" style="text-align:center;font-size:16px;font-weight:700">
    </div>
    <div class="btn-row">
      <button class="btn btn-gold" style="flex:1" onclick="const f=parseInt(q('customFpsInput')?.value)||parseInt(q('streamFpsSelected')?.value)||30;setStreamFps(f);const m=getSelectedStreamMode();const a=!!q('streamShareAudio')?.checked;VOICE.streamMode=m;VOICE._pendingStreamWantAudio=a;try{localStorage.setItem('tes3_stream_mode',m)}catch(e){};closeModal();doStartStream(m,a)" data-i18n="stream.start">Начать трансляцию</button>
      <button class="btn btn-ghost" style="flex:1" onclick="closeModal()" data-i18n="common.cancel">Отмена</button>
    </div>`);
  setTimeout(updateStreamModalModeUI,0);
}

async function doStartStream(mode,wantAudioOverride){
  const fps=VOICE.streamFps||30;
  mode=mode||VOICE.streamMode||'screen';
  VOICE.streamMode=mode;
  // Важно: модальное окно уже закрыто, поэтому checkbox читать отсюда нельзя.
  // Иначе audio:false и звук игры вообще не запрашивался.
  const wantAudio=mode==='screen'&&(typeof wantAudioOverride==='boolean'?wantAudioOverride:!!VOICE._pendingStreamWantAudio);
  try{
    const screenStream=mode==='camera'
      ? await getCameraMediaFortrueCORD(fps)
      : await getDisplayMediaFortrueCORD(fps,wantAudio);
    VOICE.screenStream=screenStream;
    if(mode==='screen'&&wantAudio&&!screenStream.getAudioTracks().length){
      toast(t('stream.noAudioTrack'),'info',6500);
    }else if(mode==='screen'&&wantAudio&&screenStream.getAudioTracks().length){
      toast(t('stream.audioCaptured'),'ok',2200);
    }
const btn=q('vbStreamBtn');
if(btn){
  btn.classList.add('active-stream');
  btn.title=t('stream.stop');
  const ic=document.getElementById('vbStreamIcon');
  if(ic) ic.innerHTML='<span style="width:10px;height:10px;border-radius:50%;background:#e03232;display:inline-block;animation:streamDotPulse 1.4s ease-in-out infinite"></span>';
}
    const videoTrack=screenStream.getVideoTracks()[0];
    const screenAudioTrack=buildScreenShareAudioTrack(screenStream);
    if(videoTrack) videoTrack.onended=()=>voiceStopStream();
    screenStream.getAudioTracks().forEach(t=>{t.onended=()=>{toast(t('stream.audioStopped'),'info',2500);};});

    for(const [uid,peer] of Object.entries(VOICE.peers)){
      if(!peer?.pc) continue;
      if(videoTrack) await addOrReplaceVoiceVideoTrack(peer,videoTrack,screenStream);
    }

    if(!screenAudioTrack&&wantAudio){
      toast(t('stream.noAudioTrack'),'info',4500);
    }

    // ВАЖНО: звук трансляции НЕ добавляем всем участникам комнаты.
    // Он включается только для тех, кто реально открыл просмотр и отправил stream-request.
    // Видео можно договорить заранее, а audio sender оставляем микрофонным.
    VOICE.streamViewers={};
    VOICE.streamWatchers={};
    await renegotiateVoicePeers('screen-start-video-only',{forceLocalOffer:true});

    // Отмечаем себя как стримера и оповещаем других
    VOICE.streamers[S.me.id]=true;
    const sData=JSON.stringify({userId:S.me.id,name:S.me.name||meName,hasAudio:!!screenAudioTrack,mode:VOICE.streamMode||'screen'});
    Object.keys(VOICE.peers).forEach(uid=>{
      if(VOICE.roomId) api({action:'voice_signal',roomId:VOICE.roomId,toUserId:parseInt(uid),type:'stream-started',data:sData});
    });
    // Не открываем окно/предпросмотр автоматически: интерфейс не сдвигается,
    // а просмотр запускается только по кнопке «Смотреть», «Окно» или «Весь экран».
    renderChannels();renderVoiceWorkspace();
    toast(VOICE.streamMode==='camera'?t('stream.startedCamera'):(screenAudioTrack?t('stream.startedWithAudio'):t('stream.startedWithoutAudio')),'ok',3000);
  }catch(e){
    cleanupScreenAudioMix();
    if(e.name!=='NotAllowedError') toast(t('stream.error',{error:e.message}),'err');
  }
}

function voiceStopStream(){
  cleanupScreenAudioMix();
  if(VOICE.screenStream){
    VOICE.screenStream.getTracks().forEach(t=>t.stop());
    VOICE.screenStream=null;
  }
  // Оповещаем других об остановке трансляции
  if(VOICE.roomId&&S.me){
    const sData=JSON.stringify({userId:S.me.id});
    Object.keys(VOICE.peers).forEach(uid=>{
      api({action:'voice_signal',roomId:VOICE.roomId,toUserId:parseInt(uid),type:'stream-stopped',data:sData});
    });
  }
  delete VOICE.streamers[S.me?.id];
  VOICE.streamViewers={};
  if(VOICE.currentStreamUserId===S.me?.id) closeStreamViewer();
  Object.values(VOICE.peers).forEach(p=>{
    const vs=p.videoSender||getVideoSenderFromPc(p.pc);
    if(vs) vs.replaceTrack(null).catch(()=>{});
    if(p.streamAudioSender) p.streamAudioSender.replaceTrack(null).catch(()=>{});
    p.videoSender=null;
    p.streamAudioSender=null;
    p._streamAudioEnabled=false;
    p._videoBitrate=null;
  });
  if(VOICE.stream){
    const micTrack=VOICE.stream.getAudioTracks()[0];
    if(micTrack){
      Object.values(VOICE.peers).forEach(({pc})=>{
        if(!pc) return;
        pc.getSenders().forEach(s=>{if(s.track&&s.track.kind==='audio') s.replaceTrack(micTrack).then(()=>{const pr=Object.values(VOICE.peers).find(p=>p.pc===pc);if(pr){pr.audioSender=s;setPeerAudioBitrate(pr,voiceInitialBitrate(),'mic');}}).catch(()=>{});});
      });
    }
  }
  renegotiateVoicePeers('screen-stop',{forceLocalOffer:true});
renderVoiceWorkspace();
const btn=q('vbStreamBtn');
if(btn){
  btn.classList.remove('active-stream');
  btn.title=i18nt('voice.screenShare','Трансляция экрана');
  const ic=document.getElementById('vbStreamIcon');
  if(ic) ic.innerHTML='<svg viewBox="0 0 24 24" width="16" height="16"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
}
}

function showVoiceBar(name){q('voiceBar').classList.add('show');q('vbRoomName').innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M3 18v-6a9 9 0 0 1 18 0v6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg>` +' '+esc(name);q('vbRoomStatus').textContent=i18nt('voice.connected','Подключено');q('vbMuteBtn').innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`;q('vbMuteBtn').classList.remove('active-mute');
}
window.setStreamFps=function(fps){
  fps=Math.max(25,Math.min(90,fps||30));
  VOICE.streamFps=fps;
  try{localStorage.setItem('tes3_stream_fps',fps);}catch(e){}
  const inp=q('customFpsInput');if(inp) inp.value=fps;
};
try{const savedFps=parseInt(localStorage.getItem('tes3_stream_fps'));if(savedFps>=25&&savedFps<=90) VOICE.streamFps=savedFps;}catch(e){}
try{const savedMode=localStorage.getItem('tes3_stream_mode');if(savedMode==='camera'||savedMode==='screen') VOICE.streamMode=savedMode;}catch(e){}
window.updateStreamModalModeUI=updateStreamModalModeUI;
window.getSelectedStreamMode=getSelectedStreamMode;
function hideVoiceBar(){const vb=q('voiceBar');if(vb) vb.classList.remove('show','local-speaking');}

// Единое правило выбора инициатора WebRTC-соединения.
// Только пользователь с меньшим userId создаёт offer. Это убирает SDP glare,
// когда оба клиента одновременно шлют offer и соединение начинает рваться.
function voiceShouldInitiatePeer(otherUserId){
  return (S.me?.id||0) < parseInt(otherUserId,10);
}

function voicePeerIsUsable(peer){
  if(!peer||!peer.pc) return false;
  const cs=peer.pc.connectionState;
  const is=peer.pc.iceConnectionState;
  return cs==='connected'||cs==='connecting'||is==='connected'||is==='completed'||is==='checking';
}

// ★ АУДИО FIX 3: voiceCreatePeer с мониторингом ICE состояния и автоперезапуском
async function voiceCreatePeer(toUserId,initiator){
  // Если живой peer — вернуть
  const existing=VOICE.peers[toUserId];
  if(existing){
    const st=existing.pc?.connectionState;
    if(st==='connected'||st==='connecting') return existing;
    _voiceClosePeer(toUserId);
  }

  const pc=new RTCPeerConnection(STUN_CFG);

  // ── Audio element ─────────────────────────────────────────
  // НЕ ставим muted:true — это ломает autoplay на мобильном.
  // Вместо этого полагаемся на user gesture (вход в комнату = жест)
  const audioEl=document.createElement('audio');
  audioEl.autoplay=true;
  audioEl.setAttribute('playsinline','');
  audioEl.setAttribute('webkit-playsinline','');
  document.body.appendChild(audioEl);

  const prev=VOICE.peers[toUserId]?._recreateAttempt||0;
  const peer={pc,audioEl,initiator,_iceBuf:[],_connTimer:null,_iceFailCount:0,_recreateAttempt:prev,_voiceAudioAttached:false,_expectStreamAudio:false,streamAudioTrack:null,streamAudioStream:null};
  VOICE.peers[toUserId]=peer;

  // ── Локальные треки ───────────────────────────────────────
  if(VOICE.stream){
    await addOptimizedAudioTrack(pc,VOICE.stream,peer);
  }
  if(VOICE.screenStream){
    const vt=VOICE.screenStream.getVideoTracks?.()[0];
    if(vt){
      await addOrReplaceVoiceVideoTrack(peer,vt,VOICE.screenStream);
      // Звук трансляции не даём автоматически всем. Только после stream-request.
      if(VOICE.streamViewers?.[toUserId]&&VOICE._screenAudioTrack){
        try{await enableStreamAudioForViewer(toUserId);}catch(e){}
      }
      setTimeout(()=>{
        try{renegotiateVoicePeers('stream-new-peer',{forceLocalOffer:true,onlyTo:toUserId});}catch(e){}
      },700);
    }
  }

  // ── Входящий трек ─────────────────────────────────────────
  pc.ontrack=e=>{
    const stream=e.streams&&e.streams[0];
    if(!stream) return;
    if(e.track.kind==='video'){
      const p=VOICE.peers[toUserId];
      if(p){
        p.videoStream=stream;
        VOICE.streamers[toUserId]=true;
        document.querySelectorAll(`.vp-stream-btn[data-uid="${toUserId}"]`).forEach(b=>b.classList.add('visible'));
        renderChannels();
        if(VOICE.currentStreamUserId===toUserId) _svShowVideo(stream);
      }
      return;
    }
    // Аудио трансляции может прийти либо в том же MediaStream, что и video,
    // либо отдельным audio-only track после stream-request. Его нельзя отдавать
    // в общий voice audioEl, иначе звук стрима превращается в голосовой чат и
    // появляется эхо/самопрослушивание. Voice-треком считаем только первый
    // обычный audio-only track peer'а; следующий audio-only при открытом viewer
    // считаем звуком трансляции.
    const p=VOICE.peers[toUserId];
    if(stream.getVideoTracks?.().length){
      if(p){
        p.videoStream=stream;
        if(stream.getAudioTracks?.().length){
          p.streamAudioStream=stream;
          p.streamAudioTrack=stream.getAudioTracks()[0]||null;
        }
        VOICE.streamers[toUserId]=true;
        document.querySelectorAll(`.vp-stream-btn[data-uid="${toUserId}"]`).forEach(b=>b.classList.add('visible'));
        renderChannels();
        if(VOICE.currentStreamUserId===toUserId) _svShowVideo(stream);
      }
      return;
    }

    if(p&&p._voiceAudioAttached&&(p._expectStreamAudio||VOICE.currentStreamUserId===toUserId)){
      p.streamAudioStream=stream;
      p.streamAudioTrack=e.track;
      if(VOICE.currentStreamUserId===toUserId&&p.videoStream) _svShowVideo(p.videoStream);
      return;
    }

    // Обычный голосовой микрофон
    p&&(p._voiceAudioAttached=true);
    audioEl.srcObject=stream;
    audioEl.volume=voiceCalcPeerVolume(toUserId);
    setupRemoteVAD(toUserId,stream);
    // Запускаем воспроизведение — с обходом политики autoplay
    const tryPlay=()=>{
      const p=audioEl.play();
      if(p&&p.catch) p.catch(()=>{
        // Autoplay заблокирован — ждём любого пользовательского жеста
        const resume=()=>{
          audioEl.play().catch(()=>{});
          document.removeEventListener('click',resume,true);
          document.removeEventListener('touchend',resume,true);
          document.removeEventListener('keydown',resume,true);
        };
        document.addEventListener('click',resume,{once:true,capture:true});
        document.addEventListener('touchend',resume,{once:true,capture:true});
        document.addEventListener('keydown',resume,{once:true,capture:true});
      });
    };
    tryPlay();
  };

  // ── Trickle ICE ───────────────────────────────────────────
  pc.onicecandidate=e=>{
    if(e.candidate&&VOICE.roomId){
      api({action:'voice_signal',roomId:VOICE.roomId,toUserId,
           type:'ice-candidate',data:JSON.stringify(e.candidate)}).catch(()=>{});
    }
  };

  // ── ICE gathering complete — если нет кандидатов → проблема с STUN
  pc.onicegatheringstatechange=()=>{
    if(pc.iceGatheringState==='complete'&&VOICE.roomId){
      // Отправляем финальный null-кандидат для обозначения конца gathering
      api({action:'voice_signal',roomId:VOICE.roomId,toUserId,
           type:'ice-candidate',data:JSON.stringify(null)}).catch(()=>{});
    }
  };

  // ── ICE connection state ──────────────────────────────────
  pc.oniceconnectionstatechange=()=>{
    const st=pc.iceConnectionState;
    if(st==='connected'||st==='completed'){
      peer._iceFailCount=0;
      if(peer._connTimer){clearTimeout(peer._connTimer);peer._connTimer=null;}
    } else if(st==='failed'){
      peer._iceFailCount=(peer._iceFailCount||0)+1;
      if(peer._iceFailCount<=2&&VOICE.roomId&&initiator){
        // ICE restart — пересоздаём кандидатов без полного пересоздания PC
        pc.restartIce();
        createOptimizedOffer(pc,peer._audioBitrate||voiceInitialBitrate(),{iceRestart:true})
          .then(o=>pc.setLocalDescription(o))
          .then(()=>{
            if(VOICE.roomId&&pc.localDescription)
              api({action:'voice_signal',roomId:VOICE.roomId,toUserId,
                   type:'offer',data:JSON.stringify(pc.localDescription)}).catch(()=>{});
          }).catch(()=>{});
      } else {
        _voiceRecreatePeer(toUserId);
      }
    } else if(st==='disconnected'){
      // На мобильном/плавающем Wi‑Fi disconnected часто временный.
      // Раньше через 12 сек peer закрывался, что давало регулярную тишину.
      // Теперь не закрываем живой PC: инициатор пробует ICE restart,
      // а полный recreate делаем только если состояние не восстановилось долго.
      if(peer._connTimer) clearTimeout(peer._connTimer);
      peer._connTimer=setTimeout(async()=>{
        peer._connTimer=null;
        if(!VOICE.peers[toUserId]||!VOICE.roomId) return;
        if(pc.iceConnectionState==='disconnected'&&voiceShouldInitiatePeer(toUserId)&&pc.signalingState==='stable'){
          try{
            pc.restartIce?.();
            const offer=await createOptimizedOffer(pc,peer._audioBitrate||voiceInitialBitrate(),{iceRestart:true});
            await pc.setLocalDescription(offer);
            if(VOICE.roomId&&VOICE.peers[toUserId]===peer){
              await api({action:'voice_signal',roomId:VOICE.roomId,toUserId,
                         type:'offer',data:JSON.stringify(pc.localDescription)}).catch(()=>{});
            }
          }catch(e){}
        }
        // Полное пересоздание только после 45 сек непрерывного disconnected.
        setTimeout(()=>{
          if(VOICE.peers[toUserId]&&pc.iceConnectionState==='disconnected'&&VOICE.roomId){
            _voiceRecreatePeer(toUserId);
          }
        },33000);
      },12000);
    }
  };

  // ── Connection state ──────────────────────────────────────
  pc.onconnectionstatechange=()=>{
    const st=pc.connectionState;
    if(st==='connected'||st==='completed'){
      if(peer._connTimer){clearTimeout(peer._connTimer);peer._connTimer=null;}
      peer._iceFailCount=0;
      peer._recreateAttempt=0; // успешно подключились — сбрасываем счётчик
    } else if(st==='failed'){
      _voiceRecreatePeer(toUserId);
    }
  };

  startPeerBitrateMonitor(peer,toUserId);

  // ── Initiator отправляет offer ────────────────────────────
  if(initiator){
    try{
      const offer=await createOptimizedOffer(pc,peer._audioBitrate||voiceInitialBitrate());
      await pc.setLocalDescription(offer);
      if(VOICE.roomId&&VOICE.peers[toUserId]===peer){
        await api({action:'voice_signal',roomId:VOICE.roomId,toUserId,
                   type:'offer',data:JSON.stringify(pc.localDescription)});
      }
    }catch(e){
      console.warn('voiceCreatePeer offer failed:',e);
    }
  }
  return VOICE.peers[toUserId];
}

// Закрыть peer без side-effects
function _voiceClosePeer(uid){
  const p=VOICE.peers[uid];
  if(!p) return;
  if(p._connTimer) clearTimeout(p._connTimer);
  stopPeerBitrateMonitor(p);
  try{p.streamAudioSender?.replaceTrack?.(null);}catch(e){}
  try{p.pc?.close();}catch(e){}
  if(p.audioEl){p.audioEl.srcObject=null;p.audioEl.remove();}
  if(VOICE.streamers[uid]){
    delete VOICE.streamers[uid];
    document.querySelectorAll(`.vp-stream-btn[data-uid="${uid}"]`).forEach(b=>b.classList.remove('visible'));
    if(VOICE.currentStreamUserId===uid) closeStreamViewer();
  }
  delete VOICE.peers[uid];
}

// Пересоздать peer с задержкой (экспоненциальный backoff)
async function _voiceRecreatePeer(uid){
  if(!VOICE.roomId) return;
  const peer=VOICE.peers[uid];
  const attempt=Math.min(peer?._recreateAttempt||0,5);
  // Экспоненциальный backoff: 500ms, 1s, 2s, 4s, max 4s
  const delay=Math.min(500*(2**attempt),4000);
  const nextAttempt=attempt+1;
  _voiceClosePeer(uid);
  await new Promise(r=>setTimeout(r,delay));
  if(!VOICE.roomId||VOICE.peers[uid]) return; // уже создан кем-то другим
  // Инициатором становится тот у кого МЕНЬШИЙ userId
  // Это гарантирует что только один из двух шлёт offer
  const initiator=voiceShouldInitiatePeer(uid);
  const np=await voiceCreatePeer(uid,initiator);
  if(np) np._recreateAttempt=nextAttempt;
}

async function voiceRepairPeers(reason='local-check'){
  if(VOICE.roomId===null) return;
  const ids=Object.keys(VOICE.peers).map(Number);
  for(const uid of ids){
    const peer=VOICE.peers[uid];
    const pc=peer?.pc;
    if(!pc){_voiceClosePeer(uid);continue;}
    const cs=pc.connectionState;
    const ice=pc.iceConnectionState;

    // Закрываем только реально мёртвые соединения.
    if(cs==='failed'||cs==='closed'||ice==='failed'){
      _voiceRecreatePeer(uid);
      continue;
    }

    // disconnected может быть кратковременным состоянием на мобильном интернете.
    // Не закрываем peer. Только инициатор делает ICE restart через offer.
    if(ice==='disconnected'&&voiceShouldInitiatePeer(uid)){
      try{
        if(pc.signalingState==='stable'){
          pc.restartIce?.();
          const offer=await createOptimizedOffer(pc,peer._audioBitrate||voiceInitialBitrate(),{iceRestart:true});
          await pc.setLocalDescription(offer);
          if(VOICE.roomId&&VOICE.peers[uid]===peer){
            await api({action:'voice_signal',roomId:VOICE.roomId,toUserId:uid,
                       type:'offer',data:JSON.stringify(pc.localDescription)});
          }
        }
      }catch(e){
        console.warn('voiceRepairPeers ICE restart failed:',reason,e);
      }
    }
  }
}

async function voiceReconnect(){
  // ВАЖНО: этот reconnect теперь локальный. Он больше не рассылает всем
  // сигнал reconnect и не заставляет клиентов закрывать рабочие WebRTC peer'ы.
  if(VOICE.roomId===null||VOICE._reconnecting) return;
  VOICE._reconnecting=true;
  try{
    await voiceRepairPeers('manual-reconnect');
  }catch(e){
    console.warn('voiceReconnect error:',e);
  }finally{
    setTimeout(()=>{VOICE._reconnecting=false;},2500);
  }
}

// ★ АУДИО FIX 2: voiceHandleSignal с обработкой новых типов сигналов
async function voiceHandleSignal(sig){
  const{fromId,type,data}=sig;
  let peer=VOICE.peers[fromId];
if(type==='stream-request'){
    if(VOICE.screenStream&&VOICE.roomId&&S.me){
      try{
        let req={};try{req=JSON.parse(data||'{}')||{};}catch(_e){}
        const wantsAudio=req.wantsAudio===true;
        VOICE.streamWatchers=VOICE.streamWatchers||{};
        VOICE.streamWatchers[fromId]=true;
        const peer=await voiceCreatePeer(fromId,VOICE.peers[fromId]?.initiator??voiceShouldInitiatePeer(fromId));
        const vt=VOICE.screenStream.getVideoTracks?.()[0];
        if(peer&&vt) await addOrReplaceVoiceVideoTrack(peer,vt,VOICE.screenStream);
        // Только зритель, который реально открыл окно стрима, получает звук демонстрации.
        // Авто-запросы для позднего входа договаривают только видео.
        if(wantsAudio&&peer&&VOICE._screenAudioTrack){
          await enableStreamAudioForViewer(fromId);
        }else if(!wantsAudio){
          await disableStreamAudioForViewer(fromId);
        }
        await renegotiateVoicePeers('stream-request',{forceLocalOffer:true,onlyTo:fromId});
      }catch(e){console.warn('stream-request handling failed',e);}
    }
    return;
  }
if(type==='stream-started'){
    VOICE.streamers[fromId]=true;
    try{
      if(!VOICE.peers[fromId]&&VOICE.roomId){
        voiceCreatePeer(fromId,voiceShouldInitiatePeer(fromId)).catch(()=>{});
      }
      // Если статус стрима пришёл раньше video-track, сразу просим стримера
      // повторить renegotiate. Это чинит вход в комнату с уже активным стримом.
      setTimeout(()=>{
        const p=VOICE.peers[fromId];
        if(VOICE.roomId&&(!p||!p.videoStream)){
          api({action:'voice_signal',roomId:VOICE.roomId,toUserId:fromId,type:'stream-request',data:JSON.stringify({from:S.me?.id||0,wantsAudio:false})}).catch(()=>{});
        }
      },900);
    }catch(e){}
    document.querySelectorAll(`.vp-stream-btn[data-uid="${fromId}"]`).forEach(b=>b.classList.add('visible'));
    renderChannels();renderVoiceWorkspace();
    const pRoom=VOICE.rooms.find(r=>r.id===VOICE.roomId);
    const pUser=pRoom?.participants?.find(p=>p.userId===fromId);
    const pName=pUser?.name||i18nt('common.user','Пользователь');
    toast(
  `<span style="display:inline-flex;align-items:center;gap:8px">` +
  `<span style="width:10px;height:10px;border-radius:50%;background:#e03232;flex-shrink:0;` +
  `display:inline-block;animation:streamDotPulse 1.4s ease-in-out infinite"></span>` +
  `<b>${esc(pName)}</b> начал трансляцию — нажмите красный индикатор или «Смотреть» для просмотра</span>`,
  'info', 4200, true
);
    return;
  }
if(type==='stream-reaction'){
    let payload={};
    try{payload=JSON.parse(data||'{}')||{};}catch(_e){}
    const emoji=String(payload.emoji||'');
    const streamerId=parseInt(payload.streamerId||fromId||0,10);
    const senderName=String(payload.fromName||sig.name||'');
    if(!SV_REACTION_ALLOWED.includes(emoji)) return;

    // Если это мы стримим, реакция пришла от зрителя: показываем у себя
    // и пересылаем всем зрителям текущей трансляции.
    if(VOICE.screenStream&&S.me&&streamerId===S.me.id){
      if(VOICE.currentStreamUserId===S.me.id) svShowReaction(emoji,senderName);
      svBroadcastReaction({emoji,streamerId,fromId:payload.fromId||fromId,fromName:senderName,at:Date.now()},fromId);
      return;
    }

    // Если мы зритель этой трансляции, показываем реакцию в открытом окне.
    if(VOICE.currentStreamUserId===streamerId){
      svShowReaction(emoji,senderName);
    }
    return;
  }
  if(type==='stream-unrequest'){
    if(VOICE.streamWatchers) delete VOICE.streamWatchers[fromId];
    if(VOICE.screenStream&&VOICE.roomId){
      try{await disableStreamAudioForViewer(fromId);}catch(e){console.warn('stream-unrequest failed',e);}
      try{await renegotiateVoicePeers('stream-audio-off',{forceLocalOffer:true,onlyTo:fromId});}catch(e){}
    }
    return;
  }
  if(type==='stream-stopped'){
    delete VOICE.streamers[fromId];
    const peer=VOICE.peers[fromId];
    if(peer){peer.videoStream=null;peer.streamAudioTrack=null;peer.streamAudioStream=null;peer._expectStreamAudio=false;}
    document.querySelectorAll(`.vp-stream-btn[data-uid="${fromId}"]`).forEach(b=>b.classList.remove('visible'));
    if(VOICE.currentStreamUserId===fromId) closeStreamViewer();
    renderChannels();renderVoiceWorkspace();
    return;
  }
  // Новый пир присоединился. Offer создаёт только сторона с меньшим userId.
  if(type==='new-peer'){
    if(VOICE.roomId){
      const delay=200+Math.floor(Math.random()*400);
      setTimeout(async()=>{
        if(!VOICE.roomId) return;
        const existing=VOICE.peers[fromId];
        if(voicePeerIsUsable(existing)) return;
        await voiceCreatePeer(fromId,voiceShouldInitiatePeer(fromId));
      },delay);
    }
    return;
  }

  // Reconnect-сигнал больше не должен убивать живой звук.
  // Если peer рабочий — игнорируем. Если он реально плохой — пересоздаём.
  if(type==='reconnect'){
    const existing=VOICE.peers[fromId];
    if(voicePeerIsUsable(existing)) return;
    if(VOICE.roomId){
      if(existing) _voiceClosePeer(fromId);
      await new Promise(r=>setTimeout(r,300+Math.floor(Math.random()*500)));
      await voiceCreatePeer(fromId,voiceShouldInitiatePeer(fromId));
    }
    return;
  }

  if(type==='offer'||type==='ice-restart'){
    // Закрываем мёртвый peer
    if(peer&&peer.pc?.signalingState==='closed'){
      _voiceClosePeer(fromId); peer=null;
    }
    // Создаём peer если нет (не-инициатор)
    if(!peer) peer=await voiceCreatePeer(fromId,false);
    if(!peer) return;

    try{
      const desc=new RTCSessionDescription(JSON.parse(data));

      // Glare: оба прислали offer одновременно
      // "Polite peer" = тот у кого БОЛЬШИЙ userId — он отступает
      if(peer.pc.signalingState==='have-local-offer'){
        const weArePolite=(S.me?.id||0)>fromId;
        if(weArePolite){
          // Мы вежливый — ждём пока браузер поддержит perfect negotiation
          // Если нет — просто создаём новый peer вместо rollback
          _voiceClosePeer(fromId);
          peer=await voiceCreatePeer(fromId,false);
          if(!peer) return;
        } else {
          // Мы грубый — игнорируем входящий offer, наш выигрывает
          return;
        }
      }

      await peer.pc.setRemoteDescription(desc);

      // Сбрасываем буфер ICE кандидатов
      if(peer._iceBuf?.length){
        for(const ic of peer._iceBuf){
          try{ await peer.pc.addIceCandidate(ic); }catch(e){}
        }
        peer._iceBuf=[];
      }

      const answer=await createOptimizedAnswer(peer.pc,peer._audioBitrate||voiceInitialBitrate());
      await peer.pc.setLocalDescription(answer);
      await api({action:'voice_signal',roomId:VOICE.roomId,toUserId:fromId,
                 type:'answer',data:JSON.stringify(peer.pc.localDescription)});
    }catch(e){
      console.warn('offer/ice-restart handle error:',e);
      // При ошибке пересоздаём peer полностью
      _voiceRecreatePeer(fromId);
    }
  }

  else if(type==='answer'&&peer){
    try{
      if(peer.pc.signalingState!=='have-local-offer'){
        console.warn('Stale answer, signalingState:',peer.pc.signalingState);
        return;
      }
      await peer.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(data)));
      if(peer._iceBuf?.length){
        for(const ic of peer._iceBuf){
          try{ await peer.pc.addIceCandidate(ic); }catch(e){}
        }
        peer._iceBuf=[];
      }
    }catch(e){ console.warn('answer handle error:',e); }
  }

  else if(type==='ice-candidate'&&peer){
    try{
      const parsed=JSON.parse(data);
      // null = end of candidates (gathering complete)
      if(!parsed) return;
      const candidate=new RTCIceCandidate(parsed);
      if(peer.pc.remoteDescription?.type){
        try{ await peer.pc.addIceCandidate(candidate); }catch(e){}
      } else {
        if(!peer._iceBuf) peer._iceBuf=[];
        peer._iceBuf.push(candidate);
      }
    }catch(e){}
  }
}

// ★ АУДИО FIX 4: voicePoll использует voice_heartbeat (ping + poll в одном запросе)
async function voicePoll(){
  if(VOICE.roomId===null) return;
  if(VOICE._polling) return;
  VOICE._polling=true;
  try{
    const r=await api({
      action:'voice_heartbeat',
      roomId:VOICE.roomId,
      sinceId:VOICE.lastSigId,
      muted:VOICE.muted?1:0
    });
    if(!r.ok) return;

    // Обрабатываем сигналы строго по порядку
    for(const sig of(r.signals||[])){
      if(sig.id>VOICE.lastSigId){
        if(!handleVoiceSignalSpecial(sig)){
          await voiceHandleSignal(sig);
        }
        VOICE.lastSigId=sig.id;
      }
    }
    if(r.lastId&&r.lastId>VOICE.lastSigId) VOICE.lastSigId=r.lastId;

    if(r.participants){
      const room=VOICE.rooms.find(x=>x.id===VOICE.roomId);
      if(room){
        room.participants=r.participants;
        renderChannels();
        renderVoiceWorkspace();

        // Закрываем peer'ы ушедших участников
        const activeIds=new Set(r.participants.map(p=>p.userId));
        for(const uid of Object.keys(VOICE.peers)){
          if(!activeIds.has(parseInt(uid))) _voiceClosePeer(parseInt(uid));
        }

        // Участник в комнате, но peer ещё не создан.
        // Offer создаёт только сторона с меньшим userId, иначе ждём входящий offer.
        for(const p of r.participants){
          if(p.userId===S.me?.id) continue;
          if(VOICE.peers[p.userId]) continue;
          const delay=300+Math.floor(Math.random()*700);
          setTimeout(async()=>{
            if(VOICE.roomId&&!VOICE.peers[p.userId]){
              const peer=await voiceCreatePeer(p.userId,voiceShouldInitiatePeer(p.userId));
              if(peer&&VOICE.screenStream&&S.me){
                // Новый участник зашёл в комнату, где стрим уже идёт.
                // Отправляем ему статус стрима и принудительный offer с video/audio.
                const vt=VOICE.screenStream.getVideoTracks?.()[0];
                if(vt) await addOrReplaceVoiceVideoTrack(peer,vt,VOICE.screenStream);
                // Поздно вошедшему отправляем только видео/статус. Звук дастся после клика просмотра.
                const sData=JSON.stringify({userId:S.me.id,name:S.me.name||meName,hasAudio:!!VOICE._screenAudioTrack,mode:VOICE.streamMode||'screen'});
                api({action:'voice_signal',roomId:VOICE.roomId,toUserId:p.userId,type:'stream-started',data:sData}).catch(()=>{});
                await renegotiateVoicePeers('stream-late-join',{forceLocalOffer:true,onlyTo:p.userId});
              }
            }
          },delay);
        }
      }
    }
  }catch(e){
    console.warn('voicePoll error:',e);
  }finally{
    VOICE._polling=false; // ВСЕГДА сбрасываем флаг
  }
}

function showCreateVoiceRoomModal(){showModal(`<h2>${ti('speaker',18)} ${t('voice.newRoom')}</h2><div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="vrName" placeholder="${t('voice.roomPlaceholder')}" maxlength="32"></div><button class="btn btn-gold btn-full" onclick="createVoiceRoom()">${t('common.create')}</button>`);setTimeout(()=>q('vrName')?.focus(),100);}
window.createVoiceRoom=async function(){const name=q('vrName')?.value.trim();if(!name){toast(t('toast.enterName'),'err');return;}const r=await api({action:'voice_create_room',serverId:S.srvId,name});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('voice.roomCreated'),'ok');await loadVoiceRooms(S.srvId);};
window.showVoiceRoomSettings=function(roomId,roomName){showModal(`<h2>${ti('gear',18)} ${t('voice.roomSettings')}</h2><div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="vrEditName" value="${esc(roomName)}" maxlength="32"></div><div class="btn-row"><button class="btn btn-gold" onclick="updateVoiceRoom(${roomId})">${t('common.save')}</button><button class="btn btn-red" onclick="deleteVoiceRoom(${roomId})">${t('common.delete')}</button></div>`);};
window.updateVoiceRoom=async function(roomId){const name=q('vrEditName')?.value.trim();if(!name) return;const r=await api({action:'voice_update_room',roomId,name});if(!r.ok){toast(r.error,'err');return;}closeModal();toast('Сохранено','ok');await loadVoiceRooms(S.srvId);};
window.deleteVoiceRoom=async function(roomId){if(!confirm('Удалить?')) return;if(VOICE.roomId===roomId) await voiceLeave(true);const r=await api({action:'voice_delete_room',roomId});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('toast.deleted'),'ok');await loadVoiceRooms(S.srvId);};

// ── DM CALL ──────────────────────────────────────────────────
function showDmCallOverlay(fromId,fromName,fromAvatar,isIncoming){
  const ov=q('dmCallOverlay');
  const av=q('callAvatar');
  if(fromAvatar&&fromAvatar.startsWith('http')) av.innerHTML=`<img src="${fromAvatar}" alt="">`;else av.textContent=fromName?fromName[0]:ti('user',14);
  q('callName').textContent=fromName||'Пользователь';q('callStatus').textContent=isIncoming?'Входящий звонок…':'Вызов…';
  q('callBtns').innerHTML=isIncoming?`<button class="call-btn call-btn-accept" onclick="dmCallAccept()">`+`<svg viewBox="0 0 24 24" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.06 6.06l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg>`+`</button><button class="call-btn call-btn-reject" onclick="dmCallReject()">`+`<svg viewBox="0 0 24 24" width="18" height="18"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.44-2.85M5.28 10.2A19.44 19.44 0 0 1 3.03 6a2 2 0 0 1 1.97-2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.91 11.9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="23" y1="1" x2="1" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`+`</button>`:`<button class="call-btn call-btn-hangup" onclick="dmCallHangup()">`+`<svg viewBox="0 0 24 24" width="18" height="18"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.44-2.85M5.28 10.2A19.44 19.44 0 0 1 3.03 6a2 2 0 0 1 1.97-2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.91 11.9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="23" y1="1" x2="1" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>`+`</button>`;
  ov.classList.add('show');
}
function hideDmCallOverlay(){q('dmCallOverlay').classList.remove('show');}
function showDmCallBar(name){
  q('dmCallBar').classList.add('show');
  updateDmVideoRestoreButton();
  q('dcbName').textContent=name;
  q('dcbTimer').textContent='00:00';
  q('dcbMuteBtn').innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Вкл`;
  q('dcbMuteBtn').classList.remove('muted');
  DMCALL.startTime=Date.now();clearInterval(DMCALL.timerInterval);
  const tick=()=>{
    if(!DMCALL.startTime){clearInterval(DMCALL.timerInterval);return;}
    const txt=dmCallTimerText();
    const tEl=q('dcbTimer'); if(tEl) tEl.textContent=txt;
    const surfaceTimer=q('dmvcCallStatus'); if(surfaceTimer) surfaceTimer.textContent=txt;
  };
  tick();
  DMCALL.timerInterval=setInterval(tick,1000);
  setTimeout(()=>{ if(DMCALL.active) showDmCallWindow(); },80);
}
function hideDmCallBar(){
  q('dmCallBar').classList.remove('show');
  clearInterval(DMCALL.timerInterval);DMCALL.timerInterval=null;DMCALL.startTime=null;
  const vw=document.getElementById('dmVideoCallWindow'); if(vw) vw.classList.remove('show');
  updateDmVideoRestoreButton();
}
window.startDmCall=async function(video=false){
  if(!S.dmUid) return;
  if(DMCALL.active){toast('Уже идёт звонок','err');return;}
  // ★ FIX: автоматически покидаем голосовой чат при начале звонка в ЛС
  if(VOICE.roomId!==null) await voiceLeave(true);
  const u=S.allUsers.find(x=>x.id===S.dmUid)||{name:'Пользователь',avatar:''};DMCALL.withUserId=S.dmUid;
DMCALL.withUserName=u.name;DMCALL.withAvatar=u.avatar||'';DMCALL.isInitiator=true;DMCALL.active=true;DMCALL.muted=false;DMCALL.wantVideo=!!video;DMCALL.videoEnabled=!!video;const r=await api({action:'dm_call_start',toUserId:S.dmUid,video:DMCALL.wantVideo?1:0});if(!r.ok){toast(r.error,'err');dmCallReset();return;}showDmCallOverlay(S.dmUid,u.name,u.avatar,false);toast(`${ti('callPhone',14)} ${DMCALL.wantVideo?'Видеовызов':'Вызов'} ${u.name}…`,'info');};
window.startDmVideoCall=function(){return startDmCall(true);};
window.dmCallAccept=async function(){
  if(!DMCALL.withUserId) return;
  stopRingtone();
  hideDmCallOverlay();
  // ★ FIX: покидаем голосовой чат до принятия звонка
  if(VOICE.roomId!==null) await voiceLeave(true);
  await requestWakeLock();
  await api({action:'dm_call_answer',toUserId:DMCALL.withUserId});
  await dmCallSetupStream();
  if(DMCALL.isInitiator) await dmCallInitiatePeer();
  showDmCallBar(DMCALL.withUserName);
  playSound('call_accepted');
};
window.dmCallReject=async function(){if(!DMCALL.withUserId) return;stopRingtone();const toId=DMCALL.withUserId;hideDmCallOverlay();await api({action:'dm_call_reject',toUserId:toId});dmCallReset();toast('Звонок отклонён','info');};
window.dmCallHangup=async function(silent=false){const toId=DMCALL.withUserId;releaseWakeLock();
stopRingtone();hideDmCallOverlay();hideDmCallBar();if(toId) await api({action:'dm_call_hangup',toUserId:toId});dmCallCleanupPeer();dmCallReset();if(!silent) playSound('call_ended');};
window.dmCallToggleMute=function(){DMCALL.muted=!DMCALL.muted;if(DMCALL.stream) DMCALL.stream.getAudioTracks().forEach(t=>t.enabled=!DMCALL.muted);const btn=q('dcbMuteBtn');btn.innerHTML=DMCALL.muted?`<svg viewBox="0 0 24 24" width="16" height="16"><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M9 9v3a3 3 0 0 0 5.12 2.12M15 9.34V4a3 3 0 0 0-5.94-.6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M17 16.95A7 7 0 0 1 5 12v-2m14 0v2a7 7 0 0 1-.11 1.23" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Выкл`:(`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><line x1="12" y1="19" x2="12" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="8" y1="23" x2="16" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Вкл`);btn.classList.toggle('muted',DMCALL.muted);syncDmCallSurface();dmvcRevealControls();};
function dmVideoConstraints(){
  const mobile=isMobileLike();
  return {
    width:{ideal:mobile?960:1280,max:mobile?1280:1920},
    height:{ideal:mobile?540:720,max:mobile?720:1080},
    frameRate:{ideal:mobile?24:30,max:mobile?24:30},
    facingMode:'user'
  };
}
async function getDmCallMediaStream(withVideo=false){
  const mic=await getOptimizedMicStream();
  const out=new MediaStream();
  mic.getAudioTracks().forEach(t=>out.addTrack(t));
  out._cleanupAudioProcessing=()=>stopOptimizedAudioStream(mic);
  if(withVideo){
    try{
      const cam=await navigator.mediaDevices.getUserMedia({video:dmVideoConstraints(),audio:false});
      cam.getVideoTracks().forEach(t=>out.addTrack(t));
      out._cameraStream=cam;
    }catch(e){
      toast('Камера недоступна, звонок начат голосом','info',3500);
      DMCALL.wantVideo=false;DMCALL.videoEnabled=false;
    }
  }
  return out;
}
async function dmCallSetupStream(){
  if(DMCALL.stream) return;
  try{DMCALL.stream=await getDmCallMediaStream(!!DMCALL.wantVideo);DMCALL.videoEnabled=!!DMCALL.stream.getVideoTracks().length;dmCallSetupVadSource('local',DMCALL.stream,true);dmCallStartVADLoop();syncDmCallSurface();}
  catch(e){toast('Нет доступа к микрофону','err');await dmCallHangup(true);}
}

function updateDmVideoRestoreButton(){
  const should=!!DMCALL.active;
  const barBtn=q('dcbVideoBtn');
  if(barBtn){
    barBtn.style.display=should?'inline-flex':'none';
    barBtn.title='Открыть окно звонка';
    barBtn.innerHTML=`<svg viewBox="0 0 24 24" width="16" height="16"><path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M9 21h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg> Окно`;
  }
  const hdrBtn=q('hdrDmVideoReturnBtn');
  if(hdrBtn){
    hdrBtn.style.display=should?'flex':'none';
    hdrBtn.title='Открыть окно звонка';
    hdrBtn.classList.toggle('call-video-live',should);
  }
  if(should) syncDmCallSurface();
}
function dmvcGetVideoAspect(){
  const remote=DMCALL._remoteVideo||q('dmvcRemoteVideo');
  const local=q('dmvcLocalVideo');
  let w=remote?.videoWidth||0,h=remote?.videoHeight||0;
  if((!w||!h)&&local){w=local.videoWidth||0;h=local.videoHeight||0;}
  if((!w||!h)&&DMCALL.stream?.getVideoTracks?.().length){
    const st=DMCALL.stream.getVideoTracks()[0].getSettings?.()||{};
    w=st.width||0;h=st.height||0;
  }
  if(!w||!h) return DMCALL.screenSharing?16/9:16/9;
  return Math.max(.55,Math.min(2.4,w/h));
}
function dmvcFitDesktopVideoWindow(force=false){
  const el=document.getElementById('dmVideoCallWindow');
  if(!el||isMobileLike()||!el.classList.contains('show')||el.classList.contains('dmvc-audio-only')) return;
  if(!force&&DMCALL._videoAutoFitDone) return;
  const pad=14;
  const vw=window.innerWidth||document.documentElement.clientWidth||1024;
  const vh=window.innerHeight||document.documentElement.clientHeight||720;
  const ratio=dmvcGetVideoAspect();
  const maxW=Math.max(360,vw-pad*2);
  const maxH=Math.max(260,vh-112);
  let targetW=Math.min(maxW,Math.max(420,Math.min(900,maxH*ratio)));
  let targetH=targetW/ratio;
  if(targetH>maxH){targetH=maxH;targetW=targetH*ratio;}
  if(targetW>maxW){targetW=maxW;targetH=targetW/ratio;}
  targetW=Math.max(320,targetW);
  targetH=Math.max(220,targetH);
  el.style.aspectRatio=String(ratio);
  el.style.width=Math.round(targetW)+'px';
  el.style.height=Math.round(targetH)+'px';
  DMCALL._videoAutoFitDone=true;
  clampDmVideoWindow();
}
function clampDmVideoWindow(){
  const el=document.getElementById('dmVideoCallWindow');
  if(!el) return;
  const pad=8;
  const vw=window.innerWidth||document.documentElement.clientWidth||360;
  const vh=window.innerHeight||document.documentElement.clientHeight||640;
  const r=el.getBoundingClientRect();
  let w=Math.min(r.width||520,vw-pad*2);
  let h=Math.min(r.height||320,vh-pad*2);
  if(!isMobileLike()&&el.classList.contains('dmvc-wide')&&!el.classList.contains('dmvc-audio-only')){
    const ratio=dmvcGetVideoAspect();
    const maxW=vw-pad*2,maxH=vh-pad*2;
    h=w/ratio;
    if(h>maxH){h=maxH;w=h*ratio;}
    if(w>maxW){w=maxW;h=w/ratio;}
    el.style.aspectRatio=String(ratio);
  }
  if(Math.abs(w-r.width)>1) el.style.width=w+'px';
  if(Math.abs(h-r.height)>1) el.style.height=h+'px';
  const nr=el.getBoundingClientRect();
  let left=nr.left,top=nr.top;
  if(left+nr.width>vw-pad) left=vw-pad-nr.width;
  if(top+nr.height>vh-pad) top=vh-pad-nr.height;
  left=Math.max(pad,left);top=Math.max(pad,top);
  if(!isMobileLike()){
    el.style.left=left+'px';el.style.top=top+'px';el.style.right='auto';el.style.bottom='auto';
  }
}
window.addEventListener('resize',()=>{DMCALL._videoAutoFitDone=false;dmvcFitDesktopVideoWindow(true);clampDmVideoWindow();});
function dmCallTimerText(){
  if(!DMCALL.startTime) return 'Соединение…';
  const sec=Math.max(0,Math.floor((Date.now()-DMCALL.startTime)/1000));
  return `${String(Math.floor(sec/60)).padStart(2,'0')}:${String(sec%60).padStart(2,'0')}`;
}
function dmCallInitial(name){
  const v=(name||'').trim();
  return (v?v.charAt(0):'•').toUpperCase();
}
function dmvcSetAvatar(el,name,avatar){
  if(!el) return;
  if(avatar&&String(avatar).startsWith('http')) el.innerHTML=`<img src="${esc(avatar)}" alt="">`;
  else el.textContent=dmCallInitial(name);
}
function dmvcRevealControls(){
  const el=document.getElementById('dmVideoCallWindow');
  if(!el) return;
  el.classList.add('dmvc-controls-visible');
  clearTimeout(DMCALL._controlsHideTimer);
  DMCALL._controlsHideTimer=setTimeout(()=>{
    const cur=document.activeElement;
    if(el.matches(':hover')||(cur&&el.contains(cur))) return;
    el.classList.remove('dmvc-controls-visible');
  },isMobileLike()?2600:3200);
}
function syncDmCallSurface(){
  const el=document.getElementById('dmVideoCallWindow');
  if(!el) return;
  const localTracks=DMCALL.stream?.getVideoTracks?.().filter(t=>t.readyState!=='ended')||[];
  const hasLocalVideo=localTracks.length>0;
  const remoteStream=(DMCALL._remoteVideo?.srcObject instanceof MediaStream)?DMCALL._remoteVideo.srcObject:null;
  const remoteTracks=remoteStream?remoteStream.getVideoTracks().filter(t=>t.readyState!=='ended'):[];
  const hasRemoteVideo=remoteTracks.some(t=>!t.muted);
  const mobile=isMobileLike();
  const showMobileSharePreview=!!(DMCALL.screenSharing&&mobile&&hasLocalVideo);
  const hasAnyVideo=hasLocalVideo||hasRemoteVideo||DMCALL.videoEnabled||DMCALL.wantVideo||DMCALL.screenSharing;
  el.classList.toggle('dmvc-audio-only',!hasAnyVideo);
  el.classList.toggle('dmvc-has-remote-video',hasRemoteVideo);
  el.classList.toggle('dmvc-screen-mode',!!DMCALL.screenSharing);
  el.classList.toggle('dmvc-wide',!!hasAnyVideo&&!mobile);
  el.classList.toggle('dmvc-mobile-split',showMobileSharePreview);
  const remoteBox=q('dmvcRemoteBox'); if(remoteBox) remoteBox.classList.toggle('no-video',!hasRemoteVideo);

  const nm=DMCALL.withUserName||'Пользователь';
  const title=q('dmvcTitle'); if(title) title.textContent=(hasAnyVideo?'Видеозвонок':'Звонок')+' · '+nm;
  const rName=q('dmvcRemoteName'); if(rName) rName.textContent=nm;
  const hint=q('dmvcRemoteHint');
  if(hint){
    if(showMobileSharePreview) hint.textContent=hasRemoteVideo?t('call.remoteTopShareBelow'):t('call.yourShareBelow');
    else hint.textContent=hasAnyVideo?(hasRemoteVideo?'':'Камера собеседника отключена'):'Аудиозвонок';
  }
  const sub=q('dmvcCallStatus'); if(sub) sub.textContent=dmCallTimerText();
  dmvcSetAvatar(q('dmvcRemoteAvatar'),nm,DMCALL.withAvatar||'');
  dmvcSetAvatar(q('dmvcLocalAvatar'),S.me?.name||'Я',S.me?.avatar||'');

  const localBox=q('dmvcLocalBox');
  const lv=q('dmvcLocalVideo');
  const showLocalRoundVideo=hasLocalVideo&&!showMobileSharePreview;
  if(localBox) localBox.classList.toggle('no-video',!showLocalRoundVideo);
  if(lv){
    if(showLocalRoundVideo){
      const old=lv.srcObject instanceof MediaStream?lv.srcObject:null;
      const same=old&&old.getVideoTracks().length===localTracks.length&&old.getVideoTracks().every((t,i)=>t.id===localTracks[i].id);
      if(!same) lv.srcObject=new MediaStream(localTracks);
      lv.play().catch(()=>{});
    }else lv.srcObject=null;
  }
  const shareWrap=q('dmvcSharePreview');
  const shareVideo=q('dmvcSharePreviewVideo');
  if(shareWrap) shareWrap.style.display=showMobileSharePreview?'block':'none';
  if(shareVideo){
    if(showMobileSharePreview){
      const old=shareVideo.srcObject instanceof MediaStream?shareVideo.srcObject:null;
      const same=old&&old.getVideoTracks().length===localTracks.length&&old.getVideoTracks().every((t,i)=>t.id===localTracks[i].id);
      if(!same) shareVideo.srcObject=new MediaStream(localTracks);
      shareVideo.play().catch(()=>{});
    }else shareVideo.srcObject=null;
  }
  const mic=q('dmvcMicBtn');
  if(mic){mic.classList.toggle('muted',!!DMCALL.muted);mic.title=DMCALL.muted?'Включить микрофон':'Выключить микрофон';mic.setAttribute('aria-label',mic.title);}
  const cam=q('dmvcCamBtn');
  if(cam){cam.classList.toggle('off',!hasLocalVideo);cam.title=hasLocalVideo?'Выключить камеру':'Включить камеру';cam.setAttribute('aria-label',cam.title);}
  const scr=q('dmvcScreenBtn');
  if(scr){scr.classList.toggle('active',!!DMCALL.screenSharing);}
  dmCallSetSpeakingUI(DMCALL_VAD.localSpeaking,DMCALL_VAD.remoteSpeaking);
  if(hasAnyVideo&&!mobile) requestAnimationFrame(()=>dmvcFitDesktopVideoWindow(false));
}
function ensureDmVideoWindow(){
  let el=document.getElementById('dmVideoCallWindow');
  if(el) return el;
  el=document.createElement('div');el.id='dmVideoCallWindow';
  el.innerHTML=`
    <div class="dmvc-stage" id="dmvcStage">
      <div class="dmvc-remote" id="dmvcRemoteBox">
        <div class="dmvc-remote-card">
          <div class="dmvc-remote-avatar" id="dmvcRemoteAvatar">•</div>
          <div class="dmvc-remote-name" id="dmvcRemoteName">Пользователь</div>
          <div class="dmvc-remote-hint" id="dmvcRemoteHint">Соединение…</div>
        </div>
      </div>
      <div class="dmvc-local no-video" id="dmvcLocalBox" title="Как вы выглядите">
        <video id="dmvcLocalVideo" autoplay muted playsinline></video>
        <div class="dmvc-local-avatar" id="dmvcLocalAvatar">Я</div>
        <span class="dmvc-pip-label">Вы</span>
      </div>
      <div class="dmvc-share-preview" id="dmvcSharePreview">
        <div class="dmvc-share-label" data-i18n="call.yourShare">Ваша трансляция</div>
        <video id="dmvcSharePreviewVideo" autoplay muted playsinline></video>
      </div>
      <div class="dmvc-top">
        <div class="dmvc-head" id="dmvcDragHandle">
          <div class="dmvc-title" id="dmvcTitle">Звонок</div>
          <div class="dmvc-subtitle" id="dmvcCallStatus">Соединение…</div>
        </div>
        <button class="dmvc-btn dmvc-close" id="dmvcClose" title="Свернуть" aria-label="Свернуть"><svg viewBox="0 0 24 24"><path d="M5 12h14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg></button>
      </div>
      <div class="dmvc-controls" id="dmvcControls">
        <button class="dmvc-btn" id="dmvcMicBtn" onclick="dmCallToggleMute();dmvcRevealControls();event.stopPropagation()" title="Микрофон" aria-label="Микрофон"><svg viewBox="0 0 24 24"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" stroke="currentColor" stroke-width="1.9" fill="none"/><path d="M19 10v2a7 7 0 0 1-14 0v-2" stroke="currentColor" stroke-width="1.9" fill="none" stroke-linecap="round"/><path d="M12 19v4M8 23h8" stroke="currentColor" stroke-width="1.9" fill="none" stroke-linecap="round"/></svg></button>
        <button class="dmvc-btn" id="dmvcCamBtn" onclick="dmCallToggleVideo();dmvcRevealControls();event.stopPropagation()" title="Камера" aria-label="Камера"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.9" fill="none"/><path d="M16 10l6-4v12l-6-4" stroke="currentColor" stroke-width="1.9" fill="none" stroke-linejoin="round"/></svg></button>
        <button class="dmvc-btn dmvc-screen-btn" id="dmvcScreenBtn" onclick="dmCallShareScreen();dmvcRevealControls();event.stopPropagation()" title="Показать экран" aria-label="Показать экран"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.9" fill="none"/><path d="M8 21h8M12 17v4" stroke="currentColor" stroke-width="1.9" fill="none" stroke-linecap="round"/></svg></button>
        <button class="dmvc-btn danger" onclick="dmCallHangup();event.stopPropagation()" title="Завершить" aria-label="Завершить"><svg viewBox="0 0 24 24"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07M5.28 10.2A19.44 19.44 0 0 1 3.03 6a2 2 0 0 1 1.97-2h3a2 2 0 0 1 2 1.72" stroke="currentColor" stroke-width="1.9" fill="none" stroke-linecap="round"/><line x1="23" y1="1" x2="1" y2="23" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg></button>
      </div>
    </div>`;
  document.body.appendChild(el);
  try{window.I18N?.apply?.(el);}catch(e){}
  el.querySelector('#dmvcClose').onclick=(e)=>{e.stopPropagation();el.classList.remove('show');updateDmVideoRestoreButton();};
  ['pointerdown','pointermove','touchstart','click'].forEach(ev=>el.addEventListener(ev,()=>dmvcRevealControls(),{passive:true}));
  const head=el.querySelector('#dmvcDragHandle');
  head.addEventListener('pointerdown',e=>{if(e.button!==0||isMobileLike()) return;const r=el.getBoundingClientRect();DMCALL._videoDrag={dx:e.clientX-r.left,dy:e.clientY-r.top};head.setPointerCapture?.(e.pointerId);dmvcRevealControls();});
  head.addEventListener('pointermove',e=>{if(!DMCALL._videoDrag) return;el.style.left=Math.max(8,Math.min(window.innerWidth-120,e.clientX-DMCALL._videoDrag.dx))+'px';el.style.top=Math.max(8,Math.min(window.innerHeight-80,e.clientY-DMCALL._videoDrag.dy))+'px';el.style.right='auto';el.style.bottom='auto';});
  head.addEventListener('pointerup',()=>{DMCALL._videoDrag=null;clampDmVideoWindow();});
  return el;
}
function showDmCallWindow(){
  if(!DMCALL.active){toast('Нет активного звонка','info');return null;}
  const el=ensureDmVideoWindow();
  exitFullscreenForWindowOpen();
  el.classList.add('show','dmvc-controls-visible');
  DMCALL._videoAutoFitDone=false;
  syncDmCallSurface();
  dmvcFitDesktopVideoWindow(true);
  clampDmVideoWindow();
  updateDmVideoRestoreButton();
  dmvcRevealControls();
  return el;
}
function showDmVideoWindow(){return showDmCallWindow();}
function attachDmRemoteVideo(track){
  const el=showDmVideoWindow();if(!el) return;
  const box=el.querySelector('#dmvcRemoteBox');
  let v=el.querySelector('#dmvcRemoteVideo');
  if(!v){
    v=document.createElement('video');
    v.id='dmvcRemoteVideo';v.autoplay=true;v.playsInline=true;
    v.addEventListener('loadedmetadata',()=>{DMCALL._videoAutoFitDone=false;dmvcFitDesktopVideoWindow(true);syncDmCallSurface();},{passive:true});
    v.addEventListener('resize',()=>{DMCALL._videoAutoFitDone=false;dmvcFitDesktopVideoWindow(true);syncDmCallSurface();},{passive:true});
    box.insertBefore(v,box.firstChild);
  }
  track.onmute=()=>syncDmCallSurface();
  track.onunmute=()=>syncDmCallSurface();
  track.onended=()=>syncDmCallSurface();
  const st=v.srcObject instanceof MediaStream?v.srcObject:new MediaStream();
  if(!st.getTracks().some(t=>t.id===track.id)) st.addTrack(track);
  v.srcObject=st;
  v.play().catch(()=>{});
  DMCALL._remoteVideo=v;
  syncDmCallSurface();
  updateDmVideoRestoreButton();
  dmvcRevealControls();
}
async function setDmOutgoingVideoTrack(track){
  if(!DMCALL.pc) return;
  let sender=DMCALL.videoSender||DMCALL.pc.getSenders().find(s=>s.track&&s.track.kind==='video');
  if(sender){await sender.replaceTrack(track||null);}else if(track){sender=DMCALL.pc.addTrack(track,DMCALL.stream||new MediaStream([track]));}
  DMCALL.videoSender=sender||null;
  if(track){showDmVideoWindow();await applyVideoSenderParams(sender,isMobileLike()?700000:1400000,{scaleResolutionDownBy:isMobileLike()?1.25:1});}
  syncDmCallSurface();
  updateDmVideoRestoreButton();
  if(DMCALL.pc.signalingState==='stable'){
    const offer=await createOptimizedOffer(DMCALL.pc,VOICE_AUDIO.mid);
    await DMCALL.pc.setLocalDescription(offer);
    await api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'renegotiate',data:JSON.stringify(offer)});
  }
}
window.dmCallToggleVideo=async function(){
  if(!DMCALL.active) return;
  if(DMCALL.videoEnabled){
    DMCALL.videoEnabled=false;DMCALL.screenSharing=false;
    const vt=DMCALL.stream?.getVideoTracks?.()[0];if(vt){vt.stop();DMCALL.stream.removeTrack(vt);}await setDmOutgoingVideoTrack(null);
    const lv=q('dmvcLocalVideo');if(lv) lv.srcObject=null;syncDmCallSurface();updateDmVideoRestoreButton();dmvcRevealControls();return;
  }
  try{const cam=await navigator.mediaDevices.getUserMedia({video:dmVideoConstraints(),audio:false});const vt=cam.getVideoTracks()[0];DMCALL.stream.addTrack(vt);DMCALL.videoEnabled=true;DMCALL.screenSharing=false;await setDmOutgoingVideoTrack(vt);showDmVideoWindow();}
  catch(e){toast('Камера недоступна','err');}
};
window.dmCallShareScreen=async function(){
  if(!DMCALL.active) return;
  if(!navigator.mediaDevices?.getDisplayMedia){toast('На этом телефоне браузер не даёт доступ к трансляции экрана','err',4500);return;}
  try{
    const st=await navigator.mediaDevices.getDisplayMedia({video:{frameRate:{ideal:isMobileLike()?20:30,max:isMobileLike()?24:30},width:{ideal:isMobileLike()?960:1280},height:{ideal:isMobileLike()?540:720}},audio:false});
    const vt=st.getVideoTracks()[0];if(!vt) return;
    DMCALL.stream.getVideoTracks().forEach(t=>{try{DMCALL.stream.removeTrack(t);t.stop();}catch(e){}});
    DMCALL.stream.addTrack(vt);DMCALL.videoEnabled=true;DMCALL.screenSharing=true;vt.onended=()=>dmCallToggleVideo();await setDmOutgoingVideoTrack(vt);showDmVideoWindow();syncDmCallSurface();
  }catch(e){if(e.name!=='NotAllowedError') toast('Трансляция экрана недоступна: '+e.message,'err');}
};

// ★ АУДИО FIX 7: dmCallInitiatePeer с ICE restart при сбое
async function dmCallInitiatePeer(){
  if(!DMCALL.withUserId||!DMCALL.stream) return;
  const pc=new RTCPeerConnection(STUN_CFG);DMCALL.pc=pc;

  // ★ Audio элемент с корректной обработкой autoplay
  const audioEl=document.createElement('audio');
  audioEl.autoplay=true;
  audioEl.setAttribute('playsinline','');
  audioEl.muted=true; // ★ Начинаем заглушенным для iOS
  document.body.appendChild(audioEl);
  DMCALL._remoteAudio=audioEl;

  DMCALL.audioSender=await addOptimizedAudioTrack(pc,DMCALL.stream,{pc,audioSender:null,_audioBitrate:VOICE_AUDIO.mid});
  const localVideoTrack=DMCALL.stream?.getVideoTracks?.()[0];
  if(localVideoTrack){DMCALL.videoSender=pc.addTrack(localVideoTrack,DMCALL.stream);showDmVideoWindow();applyVideoSenderParams(DMCALL.videoSender,isMobileLike()?700000:1400000,{scaleResolutionDownBy:isMobileLike()?1.25:1}).catch(()=>{});}

  pc.ontrack=e=>{
    const tr=e.track;
    if(tr&&tr.kind==='video'){attachDmRemoteVideo(tr);return;}
    if(e.streams&&e.streams[0]){
      const onlyAudio=new MediaStream(e.streams[0].getAudioTracks());
      audioEl.srcObject=onlyAudio;
      dmCallSetupVadSource('remote',onlyAudio,false);
      dmCallStartVADLoop();
      audioEl.muted=false;
      audioEl.play().catch(()=>{
        const unlock=()=>{
          audioEl.muted=false;
          audioEl.play().catch(()=>{});
        };
        document.addEventListener('touchstart',unlock,{once:true,passive:true});
        document.addEventListener('click',unlock,{once:true});
      });
    }
  };

  pc.onicecandidate=e=>{
    if(e.candidate&&DMCALL.withUserId){
      api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'ice-candidate',data:JSON.stringify(e.candidate)});
    }
  };

  // ★ АУДИО FIX: ICE restart при сбое DM звонка
  pc.oniceconnectionstatechange=()=>{
    if(pc.iceConnectionState==='failed'&&DMCALL.isInitiator&&DMCALL.withUserId){
      pc.restartIce();
      createOptimizedOffer(pc,VOICE_AUDIO.low,{iceRestart:true})
        .then(o=>pc.setLocalDescription(o))
        .then(()=>{
          if(DMCALL.withUserId&&pc.localDescription){
            api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'ice-restart',data:JSON.stringify(pc.localDescription)});
          }
        })
        .catch(()=>{});
    }
  };

  if(DMCALL.isInitiator){
    const offer=await createOptimizedOffer(pc,VOICE_AUDIO.mid);
    await pc.setLocalDescription(offer);
    await api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'offer',data:JSON.stringify(offer)});
  }
}

// ★ АУДИО FIX 8: dmCallHandleSignal с ice-restart и исправлением race condition
async function dmCallHandleSignal(sig){
  const{type,data,fromId}=sig;
  if(type==='incoming_call'){
    if(DMCALL.active){await api({action:'dm_call_reject',toUserId:fromId});return;}
    const u=S.allUsers.find(x=>x.id===fromId)||{name:'Пользователь',avatar:''};
    DMCALL.withUserId=fromId;DMCALL.withUserName=sig.fromName||u.name;DMCALL.withAvatar=sig.fromAvatar||u.avatar||'';
    try{const cd=JSON.parse(data||'{}');DMCALL.wantVideo=!!cd.video;DMCALL.videoEnabled=!!cd.video;}catch(e){DMCALL.wantVideo=false;DMCALL.videoEnabled=false;}
    DMCALL.isInitiator=false;DMCALL.active=true;
    startRingtone();showDmCallOverlay(fromId,DMCALL.withUserName,DMCALL.withAvatar,true);if(DMCALL.wantVideo){const st=q('callStatus');if(st) st.textContent='Входящий видеозвонок…';}
    if(typeof Notification!=="undefined"&&Notification&&Notification.permission==='granted') try{new Notification(ti('callPhone',14)+' Входящий звонок',{body:DMCALL.withUserName+' звонит вам',icon:DMCALL.withAvatar||undefined});}catch(e){}
    return;
  }
  if(type==='call_answered'){
    hideDmCallOverlay();await dmCallSetupStream();await dmCallInitiatePeer();showDmCallBar(DMCALL.withUserName);playSound('call_accepted');return;
  }
  if(type==='call_rejected'){stopRingtone();hideDmCallOverlay();dmCallCleanupPeer();dmCallReset();toast('Звонок отклонён','info');playSound('call_ended');return;}
  if(type==='call_hangup'){stopRingtone();hideDmCallOverlay();hideDmCallBar();dmCallCleanupPeer();dmCallReset();toast('Звонок завершён','info');playSound('call_ended');return;}

  // ★ АУДИО FIX: исправление race condition — offer может прийти до создания PC
  if(type==='offer'&&!DMCALL.pc&&DMCALL.active&&!DMCALL.isInitiator){
    await dmCallSetupStream();
    await dmCallInitiatePeer();
  }

  if(!DMCALL.pc) return;

  if(type==='offer'){
    try{
      await DMCALL.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(data)));
      const answer=await createOptimizedAnswer(DMCALL.pc,VOICE_AUDIO.mid);
      await DMCALL.pc.setLocalDescription(answer);
      await api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'answer',data:JSON.stringify(answer)});
    }catch(e){}
  }
  else if(type==='answer'){
    try{await DMCALL.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(data)));}catch(e){}
  }
  else if(type==='ice-candidate'){
    try{await DMCALL.pc.addIceCandidate(new RTCIceCandidate(JSON.parse(data)));}catch(e){}
  }
  // ★ ICE restart от инициатора
  else if(type==='renegotiate'){
    try{
      await DMCALL.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(data)));
      const answer=await createOptimizedAnswer(DMCALL.pc,VOICE_AUDIO.mid);
      await DMCALL.pc.setLocalDescription(answer);
      await api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'answer',data:JSON.stringify(answer)});
    }catch(e){}
  }
  else if(type==='ice-restart'){
    try{
      await DMCALL.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(data)));
      const answer=await createOptimizedAnswer(DMCALL.pc,VOICE_AUDIO.mid);
      await DMCALL.pc.setLocalDescription(answer);
      await api({action:'dm_call_signal',toUserId:DMCALL.withUserId,type:'answer',data:JSON.stringify(answer)});
    }catch(e){}
  }
}

function dmCallCleanupPeer(){
  try{DMCALL.pc?.close();}catch(e){}
  DMCALL.pc=null;
  if(DMCALL._remoteAudio){DMCALL._remoteAudio.srcObject=null;DMCALL._remoteAudio.remove();DMCALL._remoteAudio=null;}
  if(DMCALL._remoteVideo){DMCALL._remoteVideo.srcObject=null;DMCALL._remoteVideo=null;}
  clearTimeout(DMCALL._controlsHideTimer);
  dmCallStopVADLoop();
  const vw=document.getElementById('dmVideoCallWindow');
  if(vw){
    vw.classList.remove('show','dmvc-has-remote-video','dmvc-wide','dmvc-screen-mode','dmvc-mobile-split');
    vw.style.aspectRatio='';vw.style.width='';vw.style.height='';
    const rv=vw.querySelector('#dmvcRemoteVideo'); if(rv) rv.remove();
    const lv=vw.querySelector('#dmvcLocalVideo'); if(lv) lv.srcObject=null;
    const sp=vw.querySelector('#dmvcSharePreviewVideo'); if(sp) sp.srcObject=null;
  }
  updateDmVideoRestoreButton();
  if(DMCALL.stream){try{DMCALL.stream._cameraStream?.getTracks?.().forEach(t=>t.stop());}catch(e){}stopOptimizedAudioStream(DMCALL.stream);DMCALL.stream=null;}
}
function dmCallReset(){
  DMCALL.active=false;DMCALL.withUserId=null;DMCALL.withUserName='';DMCALL.withAvatar='';DMCALL.isInitiator=false;DMCALL.muted=false;DMCALL.audioSender=null;DMCALL.videoSender=null;DMCALL.videoEnabled=false;DMCALL.wantVideo=false;DMCALL.screenSharing=false;DMCALL._remoteVideo=null;DMCALL._videoAutoFitDone=false;
  clearTimeout(DMCALL._controlsHideTimer);
  updateDmVideoRestoreButton();
  syncDmCallSurface();
}

// ── DIRECT MESSAGES ──────────────────────────────────────────
// СТАЛО: на мобильном открываем боковую панель с DM-списком
async function openDmMode(){
  S.mode='dm';S.chId=null;
  q('dmSidePanel').style.display='flex';q('chSidePanel').style.display='none';
  q('dmSrvBtn').classList.add('active');
  document.querySelectorAll('#srvIcons .srv-icon').forEach(el=>el.classList.remove('active'));
  q('hdrCopyLink').style.display='none';q('hdrCallBtn').style.display='none';if(q('hdrVideoCallBtn')) q('hdrVideoCallBtn').style.display='none';if(q('hdrDmPrivacyBtn')) q('hdrDmPrivacyBtn').style.display='none';q('hdrDmClearBtn').style.display='none';
  q('memberToggleBtn').style.display='none';
  await loadDmConvs();await loadUsers();
  if(window.innerWidth<=980){
    // На мобильном: открываем боковую панель со списком контактов ЛС
    q('chSidebar').classList.add('open');
    q('mobileOverlay').classList.add('open');
  } else {
    closeOverlays();
  }
  saveLocation();
}

async function loadUsers(){const r=await api({action:'get_users'});if(r.ok) S.allUsers=r.users;}
async function loadDmConvs(){
  const r=await api({action:'dm_conversations'});if(!r.ok) return;
  if(r.restricted){S.dmConvs=[];q('dmList').innerHTML=`<div style="padding:16px 12px;font-size:13px;color:var(--red2)">${ti('warning',14)} Для личных сообщений требуется верификация аккаунта.<br><span style="color:var(--text3);font-size:12px">Обратитесь к администратору или модератору вашего сервера. Ограничение действует согласно законодательству РФ.</span></div>`;return;}
  S.dmConvs=r.conversations;renderDmList();
}
function renderDmList(filter=''){
  const el=q('dmList');el.innerHTML='';const lf=filter.toLowerCase();
  const convs=S.dmConvs.filter(c=>!lf||c.name.toLowerCase().includes(lf));
  if(!convs.length){
    el.innerHTML=filter
      ? `<div style="padding:16px 12px;font-size:13px;color:var(--text3)">Нет результатов</div>`
      : `<div style="padding:16px 12px;font-size:13px;color:var(--text3);line-height:1.45">Нет разговоров.<br><button class="dm-new-btn" style="margin:10px 0 0;width:100%;justify-content:center" onclick="showNewDmModal()">${ti('plus',13)} Начать ЛС</button></div>`;
    return;
  }
  convs.forEach(c=>{
    const isActive=c.userId===S.dmUid;
    const d=document.createElement('div');d.className='dm-item'+(isActive?' active':'');
    const stCls='st-'+(c.online?c.status||'online':'offline');
    const avContent=c.avatar&&c.avatar.startsWith('http')?`<img src="${esc(c.avatar)}" alt="">`:avatarFallbackHtml(c.name,c.userId);
    const lastMsgPreview=c.lastMsg?(c.lastMsg.length>40?c.lastMsg.slice(0,40)+'…':c.lastMsg):'';
    const badgeHtml=c.unread>0?`<div class="dm-badge">${c.unread>99?'99+':c.unread}</div>`:'';
    d.innerHTML=`<div class="dm-av">${avContent}<div class="dm-status ${stCls}"></div>${badgeHtml}</div><div class="dm-info"><div class="dm-name">${esc(c.name)}</div><div class="dm-last">${esc(lastMsgPreview)}</div></div><button class="dm-del-btn" title="Удалить чат">✕</button>`;
    d.querySelector('.dm-del-btn').onclick=e=>{e.stopPropagation();deleteDmConv(c.userId,c.name);};
    d.onclick=()=>openDmConv(c.userId,c.name,c.avatar||'');
    el.appendChild(d);
  });
}
async function deleteDmConv(uid,name){
  if(!confirm(`Удалить переписку с ${name}?`)) return;
  const r=await api({action:'dm_delete_conversation',withUserId:uid});
  if(!r.ok){toast(r.error,'err');return;}
  S.dmConvs=S.dmConvs.filter(c=>c.userId!==uid);
  if(S.dmUid===uid){S.dmUid=null;q('dmView').style.display='none';q('welcomeScreen').style.display='flex';}
  renderDmList();toast('Чат удалён','ok');
}

function showDmClearModal(){
  if(!S.dmUid){toast('Сначала откройте личный чат','err');return;}
  const user=S.allUsers.find(u=>u.id===S.dmUid);
  const name=user?.name||q('hdrName')?.textContent||'пользователем';
  const today=new Date().toISOString().slice(0,10);
  showModal(`
    <h2>${ti('trash',18)} Очистить ЛС</h2>
    <p style="color:var(--text2);font-size:13px;margin-bottom:12px">
      ${t('channel.messages')} в чате с <b>${esc(name)}</b> будут удалены <b>для обоих участников</b>.
    </p>
    <label class="fi-label">Удалить сообщения до даты включительно</label>
    <input class="fi" type="date" id="dmClearBeforeDate" max="${today}" value="${today}">
    <div style="margin-top:10px;padding:10px;border:1px solid rgba(150,41,41,.35);background:rgba(150,41,41,.08);border-radius:6px;color:var(--text2);font-size:12px;line-height:1.45">
      Действие необратимо: старые сообщения будут удалены из общей переписки, а не только скрыты у вас.
    </div>
    <div class="modal-actions" style="margin-top:14px">
      <button class="btn" onclick="closeModal()">Отмена</button>
      <button class="btn btn-red" onclick="clearDmBeforeSelectedDate()">Удалить</button>
    </div>
  `);
}

async function clearDmBeforeSelectedDate(){
  if(!S.dmUid) return;
  const inp=q('dmClearBeforeDate');
  const date=inp?.value||'';
  if(!/^\d{4}-\d{2}-\d{2}$/.test(date)){toast('Выберите дату','err');return;}
  const cutoff=new Date(date+'T23:59:59.999');
  const cutoffMs=cutoff.getTime();
  if(!Number.isFinite(cutoffMs)){toast('Неверная дата','err');return;}
  const user=S.allUsers.find(u=>u.id===S.dmUid);
  const name=user?.name||q('hdrName')?.textContent||'пользователем';
  if(!confirm(`Удалить для обоих участников все сообщения с ${name} до ${date} включительно?`)) return;
  const uid=S.dmUid;
  const r=await api({action:'dm_clear_before',withUserId:uid,beforeMs:cutoffMs});
  if(!r.ok){toast(r.error||'Не удалось очистить чат','err');return;}
  closeModal();
  if(S.dmUid===uid){
    q('dmMsgList').innerHTML='';
    S.lastDmId=0;S.hasMoreDmMsgs=false;S.loadingMoreDm=false;
    await loadDmMsgs(uid);
    requestAnimationFrame(()=>scrollBottom('dmMsgWrap'));
  }
  await loadDmConvs();
  toast(`Удалено сообщений: ${r.deleted||0}`,'ok');
}
function filterDM(v){renderDmList(v);}
function canStartDmUser(u){
  if(!u||u.id===S.me?.id) return false;
  // Политика 'verified_only' требует, чтобы текущий пользователь был верифицирован.
  if(APP_CFG.dmPolicy==='verified_only' && !(S.me?.validated||S.me?.globalRole)) return false;
  // Иначе доверяем серверному решению canDm (учитывает общий сервер / everyone / супер-админа).
  if(u.canDm===false) return false;
  return true;
}
function buildNewDmUserRows(filter=''){
  const lf=(filter||'').toLowerCase();
  return S.allUsers
    .filter(u=>canStartDmUser(u) && (!lf||String(u.name||'').toLowerCase().includes(lf)))
    .map(u=>{
      const av=u.avatar&&u.avatar.startsWith('http')?`<img src="${esc(u.avatar)}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`:avatarFallbackHtml(u.name,u.id);
      const stCls='st-'+(u.online?(u.status||'online'):'offline');
      return `<div class="dm-item" style="cursor:pointer" onclick="openDmConv(${u.id},'${esc(u.name)}','${esc(u.avatar||'')}');closeModal()"><div class="dm-av" style="width:32px;height:32px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">${av}<div class="dm-status ${stCls}"></div></div><div class="dm-info"><div class="dm-name">${esc(u.name)}</div><div class="dm-last">${t('dm.canWriteShared')}</div></div></div>`;
    }).join('') || `<div style="padding:16px 12px;font-size:13px;color:var(--text3);line-height:1.45">${t('dm.usersNotFound')}<br><span style="font-size:12px">${t('dm.availableOnlyShared')}</span></div>`;
}
function filterNewDmModal(v){const el=q('newDmUserList');if(el) el.innerHTML=buildNewDmUserRows(v);}
function showNewDmModal(){
  showModal(`<div class="new-dm-modal"><h2>${ti('chat',18)} ${t('dm.newPrivateMessage')}</h2><p class="modal-sub">${t('dm.newPrivateDesc')}</p><div class="new-dm-search"><input class="fi" id="newDmSearch" placeholder="${t('dm.findUser')}" oninput="filterNewDmModal(this.value)"></div><div class="new-dm-list" id="newDmUserList">${buildNewDmUserRows('')}</div></div>`);
  setTimeout(()=>q('newDmSearch')?.focus(),80);
}
async function openDmConv(uid,name,avatar){
  S.dmUid=uid;S.mode='dm';
  S._dmLoading=true;
  S._dmPollLock=true;
  S._dmInitDone=false;
  S.dmBaselineId=0;
  // Порог уведомлений: пока не установлен реальный максимум — блокируем любые попапы (Infinity).
  // Это гарантирует тишину во время первичной загрузки и дозагрузки истории.
  S.dmNotifyFloor=Infinity;
  // Снимаем уведомления для этого диалога
  NOTIF_STORE.clearByKey('dm:'+uid);
  q('welcomeScreen').style.display='none';q('chView').style.display='none';q('joinServerScreen').classList.remove('show');
  // Скрываем голосовую сцену при переходе в ЛС — иначе её разметка накладывается на чат.
  { const vs=q('voiceStage'); if(vs){ vs.classList.remove('show'); vs.style.display='none'; } }
  q('dmView').style.display='flex';q('dmView').style.flexDirection='column';
  q('hdrCopyLink').style.display='none';q('hdrCallBtn').style.display='flex';if(q('hdrVideoCallBtn')) q('hdrVideoCallBtn').style.display='flex';if(q('hdrDmPrivacyBtn')) q('hdrDmPrivacyBtn').style.display='flex';q('hdrDmClearBtn').style.display='flex';
  q('memberToggleBtn').style.display='none';
  q('hdrName').textContent=name;q('hdrTopic').textContent='';
  q('hdrIcon').innerHTML=avatar&&avatar.startsWith('http')?`<img src="${esc(avatar)}" alt="${esc(name)}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`:avatarFallbackHtml(name,uid);
  q('dmMsgList').innerHTML='';S.lastDmId=0;S.hasMoreDmMsgs=false;S.loadingMoreDm=false;closeOverlays();renderDmList();
  document.title='trueCORD — ЛС: '+name;
  await loadDmMsgs(uid);
  if(S.dmUid!==uid) return; // диалог переключили во время загрузки
  S._dmLoading=false;
  // baseline/floor = последнее показанное сообщение. Всё <= floor — это история (не уведомляем).
  S.dmBaselineId=S.lastDmId;
  S.dmNotifyFloor=S.lastDmId;   // теперь уведомляем только о сообщениях с id строго больше
  S._dmInitDone=true;
  scrollStableToBottom('dmMsgWrap');
  // Запас по времени на медленную загрузку картинок перед разблокировкой поллинга.
  setTimeout(()=>{ if(S.dmUid===uid) S._dmPollLock=false; },500);
  saveLocation();
}


async function loadDmMsgs(uid){
  const r=await api({action:'dm_messages',withUserId:uid,limit:15});
  if(!r.ok){
    if(r.error?.includes('верификация')){
      q('dmMsgList').innerHTML=`<div style="text-align:center;padding:24px;color:var(--red2);font-size:14px">${ti('warning',14)} ${esc(r.error)}</div>`;
    }
    return;
  }
  // Если пользователь переключил диалог пока шла загрузка — выходим
  if(S.dmUid!==uid) return;
  const el=q('dmMsgList');
  el.innerHTML='';
  S.hasMoreDmMsgs=!!r.hasMore;
  if(S.hasMoreDmMsgs){
    const btn=document.createElement('div');
    btn.className='load-more-btn';
    btn.innerHTML='⬆ '+t('channel.loadMore');
    btn.onclick=loadMoreDmMsgs;
    el.appendChild(btn);
  }
  (r.messages||[]).forEach(m=>{
    {const _e=buildDmMsgEl(m);if(_e)el.appendChild(_e);}
    S.lastDmId=Math.max(S.lastDmId,m.id);
    S.notifiedMsgIds.add(m.id);
  });
  setupDmLoadMoreScroll();
  // Сразу прижимаем к низу: должны быть видны НОВЫЕ сообщения, а старые — выше при прокрутке.
  scrollStableToBottom('dmMsgWrap');
}
// Прижимает контейнер к низу и повторяет это после загрузки картинок/превью,
// которые меняют высоту уже после первичного скролла.
function scrollStableToBottom(wrapId){
  const wrap=q(wrapId);if(!wrap) return;
  const go=()=>{wrap.scrollTop=wrap.scrollHeight;};
  go();
  requestAnimationFrame(go);
  setTimeout(go,60);setTimeout(go,200);setTimeout(go,500);
  // Когда догружаются изображения внутри — снова прижимаем к низу.
  wrap.querySelectorAll('img').forEach(img=>{
    if(!img.complete){img.addEventListener('load',go,{once:true});img.addEventListener('error',go,{once:true});}
  });
}

function setupDmLoadMoreScroll(){
  const wrap=q('dmMsgWrap');
  if(!wrap||S._dmScrollBound) return;
  S._dmScrollBound=true;
  wrap.addEventListener('scroll',()=>{
    // Дозагрузка старых — только после полной инициализации диалога и реальной прокрутки вверх.
    if(wrap.scrollTop<80&&S.hasMoreDmMsgs&&!S.loadingMoreDm&&!S._dmLoading&&S._dmInitDone) loadMoreDmMsgs();
  });
}

async function loadMoreDmMsgs(){
  if(!S.dmUid||S.loadingMoreDm||!S.hasMoreDmMsgs) return;
  const uid=S.dmUid;
  const list=q('dmMsgList');
  const firstEl=list.querySelector('[data-msg-id]');
  const firstId=firstEl?parseInt(firstEl.dataset.msgId,10):0;
  if(!firstId) return;
  S.loadingMoreDm=true;
  const btn=list.querySelector('.load-more-btn');
  if(btn) btn.classList.add('loading');
  const r=await api({action:'dm_messages',withUserId:uid,before:firstId,limit:15});
  S.loadingMoreDm=false;
  if(S.dmUid!==uid) return;
  if(btn) btn.remove();
  if(!r.ok) return;
  const msgs=r.messages||[];
  if(!msgs.length){S.hasMoreDmMsgs=false;return;}
  const wrap=q('dmMsgWrap');
  const prevScrollH=wrap.scrollHeight;
  const frag=document.createDocumentFragment();
  S.hasMoreDmMsgs=!!r.hasMore;
  if(S.hasMoreDmMsgs){
    const newBtn=document.createElement('div');
    newBtn.className='load-more-btn';
    newBtn.innerHTML='⬆ '+t('channel.loadMore');
    newBtn.onclick=loadMoreDmMsgs;
    frag.appendChild(newBtn);
  }
  msgs.forEach(m=>{
    if(list.querySelector(`[data-msg-id="${m.id}"]`)) return;
    {const _e=buildDmMsgEl(m);if(_e)frag.appendChild(_e);}
    S.notifiedMsgIds.add(m.id);
  });
  list.insertBefore(frag,list.firstChild);
  wrap.scrollTop=wrap.scrollHeight-prevScrollH;
}


// ── LINK PREVIEWS ───────────────────────────────────────────
const LINK_PREVIEW_CACHE=new Map();
function firstUrlFromText(text){
  const m=String(text||'').match(/https?:\/\/[^\s<>"']+/i);
  return m?m[0].replace(/[.,!?;:)\]}]+$/,''):'';
}
function hostFromUrl(url){try{return new URL(url).hostname.replace(/^www\./,'');}catch(e){return url;}}
function youtubeIdFromUrl(url){
  try{
    const u=new URL(url);
    const h=u.hostname.replace(/^www\./,'').replace(/^m\./,'');
    if(h==='youtu.be') return (u.pathname.split('/').filter(Boolean)[0]||'').replace(/[^a-zA-Z0-9_-]/g,'');
    if(h==='youtube.com'||h==='youtube-nocookie.com'){
      if(u.searchParams.get('v')) return u.searchParams.get('v').replace(/[^a-zA-Z0-9_-]/g,'');
      const parts=u.pathname.split('/').filter(Boolean);
      if((parts[0]==='shorts'||parts[0]==='embed'||parts[0]==='live')&&parts[1]) return parts[1].replace(/[^a-zA-Z0-9_-]/g,'');
    }
  }catch(e){}
  return '';
}
function isYouTubeUrl(url){return !!youtubeIdFromUrl(url);}
function youtubeWatchUrl(url){const id=youtubeIdFromUrl(url);return id?`https://www.youtube.com/watch?v=${id}`:url;}
function youtubeThumbUrl(url){const id=youtubeIdFromUrl(url);return id?`https://i.ytimg.com/vi/${id}/hqdefault.jpg`:'';}
function youtubeEmbedUrl(url){const id=youtubeIdFromUrl(url);return id?`https://www.youtube-nocookie.com/embed/${id}?autoplay=1&rel=0&modestbranding=1&enablejsapi=1&playsinline=1`:'';}

const INVITE_PREVIEW_CACHE=new Map();
function inviteCodeFromText(text){
  text=String(text||'');
  if(!text) return '';

  // Важно: не прогоняем любую ссылку через extractInviteCode,
  // иначе обычные URL превращаются в «код инвайта» и ломают предпросмотр сайтов.
  const urlRe=/https?:\/\/[^\s<>"']+/gi;
  let m;
  while((m=urlRe.exec(text))){
    const raw=m[0];
    try{
      const u=new URL(raw);
      const hash=(u.hash||'').match(/#\/?inv\/([A-Za-z0-9_-]+)/);
      if(hash) return hash[1].replace(/^inv_/,'');
      const parts=u.pathname.split('/').filter(Boolean);
      const last=parts[parts.length-1]||'';
      const prev=parts[parts.length-2]||'';
      if(last.startsWith('inv_')) return last.replace(/^inv_/,'').replace(/[^A-Za-z0-9_-]/g,'');
      if(prev==='invite' && last) return last.replace(/^inv_/,'').replace(/[^A-Za-z0-9_-]/g,'');
    }catch(e){}
  }

  const codeMatch=text.match(/(?:^|\s)(inv_[A-Za-z0-9_-]+)(?=\s|$)/);
  if(codeMatch) return codeMatch[1].replace(/^inv_/,'');

  const hashText=text.match(/#\/?inv\/([A-Za-z0-9_-]+)/);
  if(hashText) return hashText[1].replace(/^inv_/,'');

  const pathText=text.match(/(?:^|\s)invite\/([A-Za-z0-9_-]+)(?=\s|$)/);
  if(pathText) return pathText[1].replace(/^inv_/,'');
  return '';
}
function buildInvitePreviewSlot(m){
  if(!m||m.deleted) return '';
  const code=inviteCodeFromText(m.text||'');
  if(!code) return '';
  return `<div class="chat-invite-preview-loading" data-invite-preview="1" data-code="${esc(code)}">Загрузка приглашения…</div>`;
}
function renderChatInvitePreviewHtml(inv,code){
  const cleanCode=String(code||inv.code||'').replace(/^inv_/,'');
  const hasIcon=!!inv.serverIcon;
  const icon=hasIcon?`<img src="${esc(inv.serverIcon)}" alt="" loading="lazy">`:ti('castle',28);
  const desc=inv.serverDesc||'Присоединяйтесь к серверу, чтобы открыть каналы и начать общение.';
  const alreadyMember=Array.isArray(S.servers)&&S.servers.some(s=>s.id===inv.serverId&&s.isMember);
  const action=alreadyMember
    ? `<button class="chat-invite-btn primary" onclick="selectServer(${Number(inv.serverId)||0})">✓ Открыть сервер</button>`
    : `<button class="chat-invite-btn primary" onclick="joinByInvite('${esc(cleanCode)}',${Number(inv.serverId)||0})">${ti('swords',14)} Присоединиться</button>`;
  return `<div class="chat-invite-card" data-code="${esc(cleanCode)}">
    <div class="chat-invite-head">
      <div class="chat-invite-pill">${ti('invite',13)} Приглашение trueCORD</div>
      <div class="chat-invite-main">
        <div class="chat-invite-icon${hasIcon?' has-img':''}">${icon}</div>
        <div class="chat-invite-info">
          <div class="chat-invite-title">${esc(inv.serverName||'Сервер')}</div>
          <div class="chat-invite-desc">${esc(desc)}</div>
          <div class="chat-invite-meta">
            <span class="chat-invite-chip">${ti('userGroup',13)} ${Number(inv.memberCount||0)} участников</span>
            <span class="chat-invite-chip">${ti('shield',13)} от ${esc(inv.creatorName||'админа')}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="chat-invite-actions">
      ${action}
      <button class="chat-invite-btn ghost" onclick="showInvitePreview('${esc(cleanCode)}')">Подробнее</button>
    </div>
  </div>`;
}
async function hydrateInvitePreview(root,m){
  if(!root||!m||m.deleted) return false;
  const slot=root.querySelector('[data-invite-preview="1"]');
  if(!slot) return false;
  const code=(slot.dataset.code||inviteCodeFromText(m.text||'')).replace(/^inv_/,'');
  if(!code){slot.remove();return false;}
  if(INVITE_PREVIEW_CACHE.has(code)){
    const inv=INVITE_PREVIEW_CACHE.get(code);
    if(inv) slot.outerHTML=renderChatInvitePreviewHtml(inv,code);
    else slot.outerHTML=`<div class="chat-invite-error">Приглашение недоступно или истекло.</div>`;
    return true;
  }
  const r=await api({action:'get_invite_info',code});
  if(!root.isConnected) return true;
  const fresh=root.querySelector(`[data-invite-preview="1"][data-code="${(window.CSS&&CSS.escape?CSS.escape(code):String(code).replace(/[^a-zA-Z0-9_-]/g,'\\$&'))}"]`);
  if(!fresh) return true;
  if(!r.ok||!r.invite){
    INVITE_PREVIEW_CACHE.set(code,null);
    fresh.outerHTML=`<div class="chat-invite-error">${esc(r.error||'Приглашение недоступно или истекло.')}</div>`;
    return true;
  }
  INVITE_PREVIEW_CACHE.set(code,r.invite);
  fresh.outerHTML=renderChatInvitePreviewHtml(r.invite,code);
  return true;
}

function buildLinkPreviewSlot(m){
  if(!m||m.deleted) return '';
  const inviteSlot=buildInvitePreviewSlot(m);
  if(inviteSlot) return inviteSlot;
  const url=firstUrlFromText(m.text||'');
  if(!url) return '';
  return `<div class="link-preview-loading" data-link-preview="1" data-url="${esc(url)}">Загрузка предпросмотра ссылки…</div>`;
}
function renderLinkPreviewHtml(p){
  const url=p.finalUrl||p.url||'#';
  const site=p.siteName||hostFromUrl(url);
  const title=p.title||site;
  const desc=p.description||'';
  if(isYouTubeUrl(url)){
    const img=p.image||youtubeThumbUrl(url);
    const uarg=encodeURIComponent(url);
    const targ=encodeURIComponent(title);
    return `<div class="link-preview-card youtube-preview-card" data-youtube-url="${esc(url)}" onclick="openYouTubePlayer(decodeURIComponent('${uarg}'),decodeURIComponent('${targ}'))"><div class="link-preview-body"><div class="link-preview-site">YouTube · ${esc(site)}</div><div class="link-preview-title">${esc(title)}</div>${desc?`<div class="link-preview-desc">${esc(desc)}</div>`:''}<div class="youtube-preview-actions"><button class="youtube-preview-action" type="button" onclick="event.stopPropagation();openYouTubePlayer(decodeURIComponent('${uarg}'),decodeURIComponent('${targ}'))"><svg viewBox="0 0 24 24"><polygon points="8 5 19 12 8 19 8 5" fill="currentColor"/></svg>Смотреть</button><a class="youtube-preview-action" href="${safeUrl(youtubeWatchUrl(url))}" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()"><svg viewBox="0 0 24 24"><path d="M14 4h6v6M10 14 20 4M20 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>На сайт</a></div></div><div class="youtube-preview-media">${img?`<img src="${safeUrl(img)}" loading="lazy" alt="" onerror="this.remove()">`:''}<div class="youtube-preview-play"><svg viewBox="0 0 24 24"><polygon points="8 5 19 12 8 19 8 5" fill="currentColor"/></svg></div></div></div>`;
  }
  const img=p.image?`<img class="link-preview-img" src="${safeUrl(p.image)}" loading="lazy" alt="" onerror="this.remove()">`:'';
  return `<a class="link-preview-card" href="${safeUrl(url)}" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()"><div class="link-preview-body"><div class="link-preview-site">${esc(site)}</div><div class="link-preview-title">${esc(title)}</div>${desc?`<div class="link-preview-desc">${esc(desc)}</div>`:''}</div>${img}</a>`;
}
async function hydrateLinkPreview(root,m){
  if(!root||!m||m.deleted) return;
  if(await hydrateInvitePreview(root,m)) return;
  const slot=root.querySelector('[data-link-preview="1"]');
  if(!slot) return;
  const url=slot.dataset.url||firstUrlFromText(m.text||'');
  if(!url){slot.remove();return;}
  if(LINK_PREVIEW_CACHE.has(url)){
    const p=LINK_PREVIEW_CACHE.get(url);
    if(p) slot.outerHTML=renderLinkPreviewHtml(p); else slot.remove();
    return;
  }
  const r=await api({action:'link_preview',url});
  if(!r.ok||!r.preview){LINK_PREVIEW_CACHE.set(url,null);slot.remove();return;}
  LINK_PREVIEW_CACHE.set(url,r.preview);
  if(!root.isConnected) return;
  const fresh=root.querySelector(`[data-link-preview="1"][data-url="${(window.CSS&&CSS.escape?CSS.escape(url):String(url).replace(/[^a-zA-Z0-9_-]/g,'\\$&'))}"]`);
  if(fresh) fresh.outerHTML=renderLinkPreviewHtml(r.preview);
}

const YT_PLAYER={el:null,iframe:null,url:'',title:'',playing:true,muted:false,vol:80,hideTimer:null,drag:null};
function ytCmd(func,args=[]){
  try{YT_PLAYER.iframe?.contentWindow?.postMessage(JSON.stringify({event:'command',func,args}),'*');}catch(e){}
}
function ensureYouTubePlayer(){
  if(YT_PLAYER.el) return YT_PLAYER.el;
  const el=document.createElement('div');
  el.id='ytFloatPlayer';
  el.innerHTML=`<div class="yt-fp-frame" id="ytFpFrame"></div><div class="yt-fp-top"><div class="yt-fp-title" id="ytFpTitle">YouTube</div><button class="yt-fp-btn" id="ytFpMin" title="Свернуть"><svg viewBox="0 0 24 24"><path d="M5 12h14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg></button><a class="yt-fp-btn yt-fp-open" id="ytFpOpen" target="_blank" rel="noopener noreferrer" title="Открыть на YouTube"><svg viewBox="0 0 24 24"><path d="M14 4h6v6M10 14 20 4M20 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h5" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>На сайт</a><button class="yt-fp-btn" id="ytFpClose" title="Закрыть"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg></button></div><div class="yt-fp-controls"><button class="yt-fp-btn" id="ytFpPlay" title="Пауза/воспроизведение"><svg viewBox="0 0 24 24"><path id="ytFpPlayIcon" d="M8 5v14l11-7z" fill="currentColor"/></svg></button><button class="yt-fp-btn" id="ytFpMute" title="Звук"><svg viewBox="0 0 24 24"><path id="ytFpMuteIcon" d="M4 9v6h4l5 4V5L8 9H4z" fill="currentColor"/><path d="M16 9c1.3 1.7 1.3 4.3 0 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></button><input class="yt-fp-range" id="ytFpVol" type="range" min="0" max="100" value="80"><div class="yt-fp-spacer"></div><button class="yt-fp-btn" id="ytFpFull" title="Во весь экран"><svg viewBox="0 0 24 24"><path d="M8 4H4v4M16 4h4v4M8 20H4v-4M20 16v4h-4" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></button></div>`;
  document.body.appendChild(el);
  YT_PLAYER.el=el;
  const by=id=>el.querySelector('#'+id);
  by('ytFpClose').onclick=()=>{exitFullscreenForWindowOpen();closeYouTubePlayer();};
  by('ytFpMin').onclick=()=>{exitFullscreenForWindowOpen();el.classList.toggle('yt-min');};
  by('ytFpPlay').onclick=()=>toggleYouTubePlay();
  by('ytFpMute').onclick=()=>toggleYouTubeMute();
  by('ytFpVol').oninput=e=>{YT_PLAYER.vol=parseInt(e.target.value,10)||0;ytCmd('setVolume',[YT_PLAYER.vol]);if(YT_PLAYER.vol>0&&YT_PLAYER.muted){YT_PLAYER.muted=false;ytCmd('unMute');updateYouTubeIcons();}};
  by('ytFpFull').onclick=()=>{if(document.fullscreenElement===el){document.exitFullscreen?.();}else if(el.requestFullscreen){el.requestFullscreen().catch(()=>{});}};
  el.addEventListener('mousemove',showYouTubeControls);
  el.addEventListener('touchstart',showYouTubeControls,{passive:true});
  const title=by('ytFpTitle');
  title.addEventListener('pointerdown',e=>{
    if(e.button!==0) return;
    const rect=el.getBoundingClientRect();
    YT_PLAYER.drag={dx:e.clientX-rect.left,dy:e.clientY-rect.top};
    title.setPointerCapture?.(e.pointerId);
  });
  title.addEventListener('pointermove',e=>{
    if(!YT_PLAYER.drag) return;
    el.style.left=Math.max(8,Math.min(window.innerWidth-120,e.clientX-YT_PLAYER.drag.dx))+'px';
    el.style.top=Math.max(8,Math.min(window.innerHeight-80,e.clientY-YT_PLAYER.drag.dy))+'px';
    el.style.right='auto';el.style.bottom='auto';
  });
  title.addEventListener('pointerup',()=>{YT_PLAYER.drag=null;});
  return el;
}
function showYouTubeControls(){
  const el=YT_PLAYER.el;if(!el) return;
  el.classList.add('yt-show-controls');
  clearTimeout(YT_PLAYER.hideTimer);
  YT_PLAYER.hideTimer=setTimeout(()=>el.classList.remove('yt-show-controls'),2200);
}
function updateYouTubeIcons(){
  const el=YT_PLAYER.el;if(!el) return;
  const play=el.querySelector('#ytFpPlayIcon');
  if(play) play.setAttribute('d',YT_PLAYER.playing?'M7 5h4v14H7zM13 5h4v14h-4z':'M8 5v14l11-7z');
  const mute=el.querySelector('#ytFpMuteIcon');
  if(mute) mute.setAttribute('d',YT_PLAYER.muted||YT_PLAYER.vol===0?'M4 9v6h4l5 4V5L8 9H4z':'M4 9v6h4l5 4V5L8 9H4z');
}
function toggleYouTubePlay(){YT_PLAYER.playing=!YT_PLAYER.playing;ytCmd(YT_PLAYER.playing?'playVideo':'pauseVideo');updateYouTubeIcons();showYouTubeControls();}
function toggleYouTubeMute(){YT_PLAYER.muted=!YT_PLAYER.muted;ytCmd(YT_PLAYER.muted?'mute':'unMute');updateYouTubeIcons();showYouTubeControls();}
window.openYouTubePlayer=function(url,title='YouTube'){
  exitFullscreenForWindowOpen();
  const embed=youtubeEmbedUrl(url);if(!embed){window.open(url,'_blank','noopener');return;}
  const el=ensureYouTubePlayer();
  YT_PLAYER.url=youtubeWatchUrl(url);YT_PLAYER.title=title||'YouTube';YT_PLAYER.playing=true;YT_PLAYER.muted=false;
  el.querySelector('#ytFpTitle').textContent=YT_PLAYER.title;
  el.querySelector('#ytFpOpen').href=YT_PLAYER.url;
  el.querySelector('#ytFpFrame').innerHTML=`<iframe src="${esc(embed)}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
  YT_PLAYER.iframe=el.querySelector('iframe');
  el.classList.add('show','yt-show-controls');
  setTimeout(()=>{ytCmd('setVolume',[YT_PLAYER.vol]);},900);
  updateYouTubeIcons();showYouTubeControls();
};
window.closeYouTubePlayer=function(){
  const el=YT_PLAYER.el;if(!el) return;
  el.classList.remove('show');
  el.querySelector('#ytFpFrame').innerHTML='';
  YT_PLAYER.iframe=null;
};
document.addEventListener('click',e=>{
  const a=e.target.closest?.('.msg-text a[href]');
  if(!a) return;
  const href=a.href||a.getAttribute('href')||'';
  if(isYouTubeUrl(href)){
    e.preventDefault();e.stopPropagation();
    openYouTubePlayer(href,a.textContent.trim()||'YouTube');
  }
},true);

function buildDmMsgEl(m){
  if(m.deleted||m.type==='deleted') return null;   // удалённые не показываем
  const isMine=m.userId===S.me?.id;
  const div=document.createElement('div');div.className='msg-row first-in-group';div.dataset.msgId=m.id;
  const ts=fmtTime(m.at);
  const avContent=m.avatar&&m.avatar.startsWith('http')?`<img src="${m.avatar}" alt="${esc(m.name)}">`:avatarFallbackHtml(m.name,m.userId);
  let content='';
  if(m.text) content+=`<div class="msg-text">${applyMarkdown(m.text)}${m.edited?'<span class="msg-edited-tag"> (ред.)</span>':''}</div>`;if(m.image) content+=buildMediaHtml(m.image,m.fileName||'',m.id,true);content+=buildLinkPreviewSlot(m);
  div.innerHTML=`<div class="msg-av" onclick="showUserProfile(${m.userId})">${avContent}</div><div class="msg-body"><div class="msg-meta"><span class="msg-author ${isMine?'cl-me':''}">${esc(m.name)}</span><span class="msg-ts">${ts}</span></div>${content}<div class="msg-comment-bar"><div class="msg-reactions" id="reacts${m.id}"></div><div class="r-add-btn" onclick="openEmojiForReact(${m.id},true)" title="Добавить реакцию">${ti('smile',15)}</div></div></div>`;
  div.addEventListener('contextmenu',e=>{e.preventDefault();showCtxMenu(e,m,'dm');});div.addEventListener('touchstart',e=>{handleTouchStart(e,m,'dm');},{passive:true});setTimeout(()=>hydrateLinkPreview(div,m),0);
  if(m.reactions&&m.reactions.length){S.reactions[m.id]=m.reactions;setTimeout(()=>renderReacts(m.id,m.reactions),0);}
  return div;
}
async function sendDmMsg(){
  if(!S.dmUid) return;
  const ta=q('dmInput');const text=ta.value.trim();const files=S.dmPendingFiles.slice();
  if(!text&&!files.length) return;
  ta.value='';autoGrow(ta);hideMentionSuggest();S.dmPendingFiles=[];renderPendingFiles('dm');
  if(files.length){
    const imageField=packAttachments(files);
    const r=await api({action:'dm_send',toUserId:S.dmUid,text,image:imageField});
    if(!r.ok){toast(r.error,'err');}else{const _e=buildDmMsgEl(r.msg);if(_e)q('dmMsgList').appendChild(_e);S.lastDmId=r.msg.id;S.notifiedMsgIds.add(r.msg.id);}
  } else {
    const r=await api({action:'dm_send',toUserId:S.dmUid,text});if(!r.ok){toast(r.error,'err');return;}
    {const _e=buildDmMsgEl(r.msg);if(_e)q('dmMsgList').appendChild(_e);}S.lastDmId=r.msg.id;S.notifiedMsgIds.add(r.msg.id);
  }
  scrollBottom('dmMsgWrap');await loadDmConvs();
}
function onDmKey(e){if(mentionKeyNav(e)) return;if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendDmMsg();}}
async function pollDm(){
  // Не поллим пока идёт начальная загрузка или переключение диалога
  if(!S.dmUid||S._dmLoading||S._dmPollLock) return;
  const uid=S.dmUid;
  const r=await api({action:'dm_poll',withUserId:uid,since:S.lastDmId});
  if(!r.ok||!r.messages?.length) return;
  // Если диалог переключили пока шёл запрос — выходим
  if(S.dmUid!==uid) return;
  const el=q('dmMsgList');const atBottom=isAtBottom('dmMsgWrap');
  r.messages.forEach(m=>{
    // Двойная защита: по DOM-элементу и по notifiedMsgIds для своих сообщений
    if(el.querySelector(`[data-msg-id="${m.id}"]`)) return;
    if(m.userId===S.me?.id && S.notifiedMsgIds.has(m.id)) return;
    {const _e=buildDmMsgEl(m);if(_e)el.appendChild(_e);}
    S.lastDmId=Math.max(S.lastDmId,m.id);
    // Уведомление ТОЛЬКО о сообщении собеседника, чей id строго больше порога,
    // зафиксированного на момент полной загрузки диалога. История (<= floor) и любые
    // догруженные старые сообщения никогда не уведомляют.
    const isReallyNew = S._dmInitDone && Number.isFinite(S.dmNotifyFloor) && m.id > S.dmNotifyFloor;
    if(isReallyNew && !S.notifiedMsgIds.has(m.id) && m.userId!==S.me?.id){
      playSound('dm');S.notifiedMsgIds.add(m.id);
      S.dmNotifyFloor=Math.max(S.dmNotifyFloor,m.id);
      const sender=S.allUsers.find(x=>x.id===m.userId)||{name:m.name||'?',avatar:''};
      // Если уже открыто QR окно для этого диалога — инжектируем туда
      if(!QR.injectMsg(m.userId,'dm',m)){
        showNotifPopup(m.name, m.text||'[файл]',{
          msgId: m.id,
          sourceKey: 'dm:'+m.userId,
          colorKey: m.userId,
          avatar: sender.avatar||'',
          avatarEmoji: (m.name||'?')[0],
          icon:ti('chat',14),
          onClick(){ openDmConv(m.userId,m.name,sender.avatar||''); DI.clearNotifs(); },
          onReply(){
            QR.open({
              id:'dm_'+m.userId,
              title:m.name,
              sub:'Личные сообщения',
              avatar:sender.avatar||'',
              avatarEmoji:(m.name||'?')[0],
              mode:'dm',
              targetId:m.userId,
              onOpen(){ openDmConv(m.userId,m.name,sender.avatar||''); },
            });
            DI.clearNotifs();
          },
        });
      }
    } else if(m.userId!==S.me?.id){
      // Историю, подтянутую поллингом, просто помечаем как уже учтённую, без попапа/звука.
      S.notifiedMsgIds.add(m.id);
    }
  });
  if(atBottom) scrollBottom('dmMsgWrap');
  // Обновляем реакции на уже показанных сообщениях (свои и собеседника).
  try{
    const rr=await api({action:'dm_get_reactions',withUserId:uid});
    if(rr.ok&&S.dmUid===uid){
      Object.entries(rr.reactions||{}).forEach(([mid,reacts])=>{const id=parseInt(mid,10);S.reactions[id]=reacts;renderReacts(id,reacts);});
    }
  }catch(e){}
}

// ── MESSAGES ─────────────────────────────────────────────────
function buildMsgEl(m,prevMsg){
  if(!m||m.type==='deleted'||m.deleted) return null;
  const div=document.createElement('div');
  const isFirst=!prevMsg||prevMsg.userId!==m.userId||(m.at-prevMsg.at)>300000;
  div.className='msg-row'+(isFirst?' first-in-group':'');div.dataset.msgId=m.id;
  const isMine=m.userId===S.me?.id;
  const member=S.members.find(x=>x.id===m.userId);
  const role=member?.role||'member';const gRole=member?.globalRole||'';
  let clsAuthor=role==='owner'?'cl-owner':role==='admin'?'cl-admin':role==='moderator'?'cl-moderator':isMine?'cl-me':'';
  if(gRole==='super_admin') clsAuthor='cl-superadmin';
  else if(gRole==='project_admin') clsAuthor='cl-projectadmin';
  let authorStyle='';
  if(member?.roleColor) authorStyle=`style="color:${esc(member.roleColor)}"`;
  const ts=fmtTime(m.at);
  const avContent=member?.avatar&&member.avatar.startsWith('http')?`<img src="${member.avatar}" alt="">`:m.name?m.name[0]:ti('user',14);
  let replyHtml='';
  if(m.replyPreview){const rp=m.replyPreview;replyHtml=`<div class="msg-reply-ref" onclick="scrollToMsg(${m.replyTo||0})"><span class="msg-reply-name">${esc(rp.name)}</span><span class="msg-reply-text">${esc(rp.text||'[файл]')}</span></div>`;}
  let fwdHtml='';
  if(m.forwardedFromName) fwdHtml=`<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text3);margin-bottom:3px;padding:3px 8px;background:var(--bg3);border-radius:3px;border-left:2px solid var(--gold3)" style="display:flex;align-items:center;gap:6px"><svg viewBox="0 0 24 24" width="13" height="13"><polyline points="15 17 20 12 15 7" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 18v-2a4 4 0 0 1 4-4h12" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg> Переслано от <strong>${esc(m.forwardedFromName)}</strong></div>`;
  let textHtml='';
  if(m.text) textHtml=`<div class="msg-text">${applyMarkdown(m.text)}${m.edited?'<span class="msg-edited-tag"> (ред.)</span>':''}</div>`;
  let mediaHtml='';
 if(m.image) mediaHtml=buildMediaHtml(m.image,null,m.id,false);
  const linkPreviewHtml=buildLinkPreviewSlot(m);
  const mentionMe=m.text&&S.me&&(m.text.includes('@'+S.me.name)||m.text.includes('@everyone')||m.text.includes('@here'));
  if(mentionMe) div.classList.add('mentioned');
  const valBadge=member?.validated?`<span class="val-badge ti" title="Верифицирован" style="display:inline-flex;align-items:center"><svg viewBox="0 0 24 24" width="14" height="14"><polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></span>`:'';
  const cmtCount=m.commentCount||0;
  const cmtHtml=`<div class="msg-comment-bar"><button class="msg-comment-btn" onclick="toggleComments(${m.id},this)" title="Комментарии">${ti('chat',14)}${cmtCount>0?`<span class="msg-comment-count"> ${cmtCount}</span>`:''}</button><div class="msg-reactions" id="reacts${m.id}"></div><div class="r-add-btn" onclick="openEmojiForReact(${m.id})" title="Добавить реакцию">${ti('smile',15)}</div></div>`;
  div.innerHTML=`${isFirst?`<div class="msg-av" onclick="showUserProfile(${m.userId})">${avContent}</div>`:`<div style="width:40px;flex-shrink:0"></div>`}<div class="msg-body">${isFirst?`<div class="msg-meta"><span class="msg-author ${clsAuthor}" ${authorStyle} onclick="showUserProfile(${m.userId})">${esc(m.name)}</span>${valBadge}<span class="msg-ts">${ts}</span></div>`:''} ${replyHtml}${fwdHtml}${textHtml}${mediaHtml}${linkPreviewHtml}${cmtHtml}</div>`;
  div.addEventListener('contextmenu',e=>{e.preventDefault();showCtxMenu(e,m,'channel');});
  div.addEventListener('touchstart',e=>{handleTouchStart(e,m,'channel');},{passive:true});
  setTimeout(()=>hydrateLinkPreview(div,m),0);
  return div;
}

// ── COMMENTS ─────────────────────────────────────────────────
window.toggleComments=async function(msgId,btn){
  const row=btn.closest('.msg-row');if(!row) return;
  let panel=row.querySelector('.msg-comments-panel');
  if(panel){panel.remove();return;}
  panel=document.createElement('div');panel.className='msg-comments-panel';panel.dataset.msgId=msgId;
  panel.innerHTML=`<div class="comment-input-row"><input class="comment-input" id="cmtInp${msgId}" placeholder="Написать комментарий…" maxlength="500" onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();submitComment(${msgId})}"><button class="comment-send-btn" onclick="submitComment(${msgId})">➤</button></div>`;
  row.querySelector('.msg-body').appendChild(panel);
  await loadComments(msgId,panel);
};
async function loadComments(msgId,panel){
  const r=await api({action:'get_comments',messageId:msgId});
  if(!r.ok) return;
  const cmts=r.comments||[];
  panel.querySelectorAll('.comment-item-wrap').forEach(el=>el.remove());
  const inputRow=panel.querySelector('.comment-input-row');
  cmts.forEach(c=>{
    const wrap=document.createElement('div');wrap.className='comment-item-wrap';
    const av=c.avatar&&c.avatar.startsWith('http')?`<img src="${c.avatar}" alt="">`:c.name[0]||'?';
    wrap.innerHTML=`<div class="msg-comment-item"><div class="comment-av">${av}</div><div class="comment-body"><div class="comment-meta"><span class="comment-name">${esc(c.name)}</span><span class="comment-ts">${fmtTime(c.at)}</span></div><div class="comment-text">${esc(c.text)}</div></div></div>`;
    panel.insertBefore(wrap,inputRow);
  });
}
window.submitComment=async function(msgId){
  const inp=document.getElementById('cmtInp'+msgId);if(!inp) return;
  const text=inp.value.trim();if(!text) return;
  const r=await api({action:'add_comment',messageId:msgId,text});
  if(!r.ok){toast(r.error,'err');return;}
  inp.value='';
  const row=document.querySelector(`[data-msg-id="${msgId}"]`);
  if(row){const panel=row.querySelector('.msg-comments-panel');if(panel) await loadComments(msgId,panel);}
  if(row){
    const cmtBtn=row.querySelector('.msg-comment-btn');
    if(cmtBtn&&r.commentCount!==undefined){
      cmtBtn.innerHTML=`${ti('chat',14)} ${r.commentCount>0?`<span class="msg-comment-count">${r.commentCount}</span>`:''} ${r.commentCount<=1?'Комментарий':'Комментарии'}`;
    }
  }
};

function packAttachments(files){
  // One file → plain URL (backward compatible). Many → JSON array of URLs.
  const urls=files.map(f=>f.url).filter(Boolean);
  if(urls.length<=1) return urls[0]||'';
  return JSON.stringify(urls);
}
function parseAttachments(image){
  // Returns an array of URLs. Handles plain URL or JSON-array string.
  const s=String(image||'').trim();
  if(!s) return [];
  if(s[0]==='['){
    try{const arr=JSON.parse(s);if(Array.isArray(arr)) return arr.map(x=>String(x)).filter(Boolean);}catch(_){}
  }
  return [s];
}
function isImageUrl(url){return /\.(png|jpe?g|gif|webp|bmp|avif)(\?.*)?$/i.test(url||'');}
// Build the full media block for a message: handles single or multiple attachments,
// grouping images into a mosaic gallery and stacking file/audio/video cards together.
function buildMediaHtml(image,fileName,msgId,isDm){
  const urls=parseAttachments(image);
  if(!urls.length) return '';
  if(urls.length===1) return buildSingleMediaHtml(urls[0],fileName,msgId,isDm);
  const imgs=urls.filter(isImageUrl);
  const others=urls.filter(u=>!isImageUrl(u));
  let html='';
  if(imgs.length===1){
    // A single image (even alongside other files) shows in full, never cropped to a square.
    html+=buildImageHtml(imgs[0],msgId,isDm);
  } else if(imgs.length>1){
    html+=buildGalleryHtml(imgs,msgId);
  }
  if(others.length){
    html+='<div class="msg-attach-group">'+others.map(u=>buildSingleMediaHtml(u,null,msgId,isDm)).join('')+'</div>';
  }
  return html;
}
function buildImageHtml(url,msgId,isDm){
  const mid=msgId||0;const dm=isDm?'true':'false';
  return `<img class="msg-img" src="${esc(thumbUrl(url))}" alt="изображение" loading="lazy" decoding="async" onclick="openLightbox('${esc(url)}')" onerror="handleThumbError(this,'${esc(url)}',${mid},${dm})">`;
}
// Превью-URL для ленты: для наших загруженных картинок добавляем ?thumb (сервер отдаёт/генерит миниатюру).
// Внешние URL и data: не трогаем.
function thumbUrl(url){
  if(!url) return url;
  const u=String(url);
  if(/^data:/i.test(u)) return u;
  // только наши /uploads/ картинки
  if(!/(^|\/)uploads\//i.test(u)) return u;
  if(!/\.(png|jpe?g|webp)(\?.*)?$/i.test(u)) return u; // gif и пр. — как есть (анимация)
  return u + (u.includes('?')?'&':'?') + 'thumb=1';
}
// Если миниатюра не загрузилась — пробуем оригинал, затем общий обработчик ошибок медиа.
window.handleThumbError=function(img,origUrl,mid,dm){
  if(img&&img.dataset.triedOrig!=='1'){img.dataset.triedOrig='1';img.src=origUrl;return;}
  handleMediaError(origUrl,mid,dm==='true'||dm===true,img);
};
function buildGalleryHtml(imgs,msgId){
  const n=imgs.length;
  const cls=n===1?'g-1':n===2?'g-2':n===3?'g-3':n===4?'g-4':'g-many';
  const enc=encodeURIComponent(JSON.stringify(imgs));
  const max=n>4?(n>=7?6:n):n; // show up to 6 cells, last shows "+N"
  let cells='';
  for(let i=0;i<max;i++){
    const isLastShown=(i===max-1)&&(n>max);
    const overlay=isLastShown?`<div class="g-more">+${n-max+1}</div>`:'';
    cells+=`<div class="g-cell" onclick="openGalleryFromEl(this,${i})" data-gallery="${enc}"><img src="${esc(thumbUrl(imgs[i]))}" loading="lazy" decoding="async" alt="изображение" onerror="if(this.dataset.t!=='1'){this.dataset.t='1';this.src='${esc(imgs[i])}';}else{this.closest('.g-cell').style.display='none';}">${overlay}</div>`;
  }
  return `<div class="msg-gallery ${cls}">${cells}</div>`;
}
window.openGalleryFromEl=function(el,idx){
  try{const arr=JSON.parse(decodeURIComponent(el.dataset.gallery||'[]'));openLightbox(null,arr,idx);}catch(_){}
};
function buildSingleMediaHtml(url,fileName,msgId,isDm){
  if(!url) return '';
  const fname=fileName||(url.split('/').pop()||'');
  const mid=msgId||0;
  const dm=isDm?'true':'false';
  if(isAudio(url)){
    const ext=(fname.split('.').pop()||'').toUpperCase();
    const displayName=fname||url.split('/').pop()||'Аудио';
    return `<div class="msg-audio-wrap" data-url="${esc(url)}" data-name="${esc(displayName)}" onclick="checkAndPlayAudio('${esc(url)}',${mid},${dm},'${esc(displayName)}')">
      <div class="audio-icon" style="display:flex;align-items:center;color:var(--gold)"><svg viewBox="0 0 24 24" width="18" height="18"><path d="M9 18V5l12-2v13" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/><circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></div>
      <div class="msg-audio-info">
        <div class="msg-audio-name">${esc(displayName)}</div>
        <div class="msg-audio-meta">${ext} · нажмите для воспроизведения</div>
      </div>
      <button onclick="event.stopPropagation();(function(u,n){const a=document.createElement('a');a.href=u;a.download=n;a.click();})('${esc(url)}','${esc(displayName)}')" title="Скачать" style="background:none;border:none;cursor:pointer;color:var(--text3);padding:4px;display:flex;align-items:center;border-radius:6px;flex-shrink:0;transition:.12s" onmouseover="this.style.color='var(--text2)'" onmouseout="this.style.color='var(--text3)'"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round"/><polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
      <span style="display:flex;align-items:center;flex-shrink:0;color:var(--gold)"><svg viewBox="0 0 24 24" width="16" height="16"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg></span>
    </div>`;
  }
  if(isVideo(url)){
    return `<div style="margin-top:6px">
      <video class="msg-video" src="${esc(url)}" controls playsinline preload="metadata" onclick="event.stopPropagation()" style="cursor:default" onerror="handleMediaError('${esc(url)}',${mid},${dm},this)"></video>
      <div style="display:flex;gap:6px;margin-top:4px">
        <button class="file-card-dl" onclick="openMediaPlayer('${esc(url)}','${esc(fname)}')">⛶ Открыть</button>
        <button class="file-card-dl" onclick="window.open('${esc(url)}','_blank')">${ti("download",12)} Скачать</button>
      </div>
    </div>`;
  }
  if(isArchive(url)){
    const ext=fname.split('.').pop()||'';
    const displayName=fname||url.split('/').pop()||url;
    return `<div class="file-card" onclick="window.open('${esc(url)}','_blank')"><span style="font-size:28px">${archiveIcon(ext)}</span><div class="file-card-info"><div class="file-card-name">${esc(displayName)}</div><div class="file-card-meta">.${esc(ext.toUpperCase())} архив</div></div><button class="file-card-dl" onclick="event.stopPropagation();window.open('${esc(url)}','_blank')">${ti("download",12)} Скачать</button></div>`;
  }
  if(isTextFile(url)){
    const displayName=fname||url.split('/').pop()||'Текстовый файл';
    return `<div class="file-card" onclick="openMediaPlayer('${esc(url)}','${esc(displayName)}')"><span style="font-size:28px">${ti("file",24)}</span><div class="file-card-info"><div class="file-card-name">${esc(displayName)}</div><div class="file-card-meta">Текстовый файл</div></div><button class="file-card-dl" onclick="event.stopPropagation();window.open('${esc(url)}','_blank')">${ti("download",12)} Скачать</button></div>`;
  }
  return `<img class="msg-img" src="${esc(thumbUrl(url))}" alt="изображение" loading="lazy" decoding="async" onclick="openLightbox('${esc(url)}')" onerror="handleThumbError(this,'${esc(url)}',${mid},${dm})">`;
}


function renderAllReacts(){Object.entries(S.reactions).forEach(([mid,reacts])=>{renderReacts(parseInt(mid),reacts);});}
function renderReacts(msgId,reacts){
  const el=document.getElementById('reacts'+msgId);if(!el) return;el.innerHTML='';
  (reacts||[]).forEach(r=>{
    const pill=document.createElement('div');pill.className='r-pill'+(r.mine?' r-mine':'');pill.title=r.emoji+' × '+r.count;
    pill.innerHTML=`<span>${r.emoji}</span><span class="r-cnt">${r.count}</span>`;
    pill.onclick=()=>toggleReaction(msgId,r.emoji);
    pill.addEventListener('mouseenter',async e=>{showReactionWho(e,msgId,r.emoji);});
    pill.addEventListener('mouseleave',()=>{q('rWhoTooltip').classList.remove('show');});
    pill.addEventListener('contextmenu',async e=>{e.preventDefault();showReactionWho(e,msgId,r.emoji);});
    let tooltipTimer=null;
    pill.addEventListener('touchstart',e=>{
      const touch=e.touches?.[0];if(!touch) return;
      tooltipTimer=setTimeout(()=>{tooltipTimer=null;showReactionWho({clientX:touch.clientX,clientY:touch.clientY},msgId,r.emoji);},600);
    },{passive:true});
    pill.addEventListener('touchend',()=>{if(tooltipTimer){clearTimeout(tooltipTimer);tooltipTimer=null;}},{passive:true});
    pill.addEventListener('touchmove',()=>{if(tooltipTimer){clearTimeout(tooltipTimer);tooltipTimer=null;}},{passive:true});
    el.appendChild(pill);
  });
}
async function showReactionWho(e,msgId,emoji){
  const dm=(S.mode==='dm');
  const r=await api(dm?{action:'dm_get_reaction_users',messageId:msgId,emoji}:{action:'get_reaction_users',messageId:msgId,emoji});if(!r.ok) return;
  const tip=q('rWhoTooltip');
  const names=(r.users||[]).slice(0,10).map(u=>esc(u.name)).join('\n');
  tip.textContent=emoji+' '+(names||'Никто');
  const x=Math.max(8,Math.min(e.clientX-60,window.innerWidth-210));
  const y=Math.max(8,e.clientY-90);
  tip.style.top=y+'px';tip.style.left=x+'px';tip.classList.add('show');
  clearTimeout(tip._hideTimer);tip._hideTimer=setTimeout(()=>tip.classList.remove('show'),4000);
}
document.addEventListener('click',()=>q('rWhoTooltip').classList.remove('show'));
async function toggleReaction(msgId,emoji,isDm){
  // isDm можно передать явно; иначе определяем по текущему режиму.
  const dm = (isDm===undefined) ? (S.mode==='dm') : !!isDm;
  const r=await api(dm
    ? {action:'dm_add_reaction',messageId:msgId,emoji}
    : {action:'add_reaction',messageId:msgId,emoji});
  if(!r.ok){toast(r.error,'err');return;}
  S.reactions[msgId]=r.reactions;renderReacts(msgId,r.reactions);
}

async function sendMsg(){
  if(!S.chId) return;
  const ta=q('msgInput');const text=ta.value.trim();const files=S.pendingFiles.slice();
  if(!text&&!files.length) return;
  const rpTo=S.replyTo?.id||null;
  ta.value='';autoGrow(ta);cancelReply();hideMentionSuggest();S.pendingFiles=[];renderPendingFiles('channel');
  if(files.length){
    // Group all attachments into ONE message (JSON array of URLs when more than one).
    const imageField=packAttachments(files);
    const r=await api({action:'send',channelId:S.chId,text,image:imageField,replyTo:rpTo});
    if(!r.ok){toast(r.error,'err');}else{appendMsg(r.msg);scrollBottom('messagesWrap');}
  } else {
    const r=await api({action:'send',channelId:S.chId,text,image:'',replyTo:rpTo});if(!r.ok){toast(r.error,'err');return;}
    appendMsg(r.msg);scrollBottom('messagesWrap');
  }
}
function appendMsg(m){
  if(!m||m.deleted||m.type==='deleted') return;
  const list=q('messagesList');const el=buildMsgEl(m);if(!el) return;
  list.appendChild(el);observeMsg(el);
  if(m.reactions?.length){S.reactions[m.id]=m.reactions;renderReacts(m.id,m.reactions);}
  S.lastMsgId=Math.max(S.lastMsgId,m.id);S.notifiedMsgIds.add(m.id);
}
function onMsgKey(e){if(mentionKeyNav(e)) return;if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMsg();}}
function cancelReply(){S.replyTo=null;q('replyBar').classList.remove('show');}
function setReply(m){S.replyTo=m;q('replyToName').textContent=m.name;q('replyPreview').textContent=m.text?m.text.slice(0,80):'[файл]';q('replyBar').classList.add('show');q('msgInput').focus();}
function scrollToMsg(id){const el=document.querySelector(`[data-msg-id="${id}"]`);if(el){el.scrollIntoView({behavior:'smooth',block:'center'});el.style.background='rgba(201,170,113,.2)';setTimeout(()=>el.style.background='',1500);}}

// ── EDIT MESSAGE ─────────────────────────────────────────────
function startEditMsg(m){
  const editMode=_ctxMode;   // 'channel' | 'dm' — запоминаем до закрытия меню
  closeCtx();const msgBody=document.querySelector(`[data-msg-id="${m.id}"] .msg-body`);if(!msgBody) return;
  const existingBox=msgBody.querySelector('.msg-edit-box');if(existingBox){existingBox.remove();return;}
  const textEl=msgBody.querySelector('.msg-text');if(textEl) textEl.style.display='none';
  const box=document.createElement('div');box.className='msg-edit-box';
  box.innerHTML=`<textarea class="msg-edit-textarea" id="editTA_${m.id}" data-edit-mode="${editMode}" rows="2">${esc(m.text||'')}</textarea><div class="msg-edit-actions"><button class="msg-edit-save" onclick="saveEditMsg(${m.id})">Сохранить</button><button class="msg-edit-cancel" onclick="cancelEditMsg(${m.id})">Отмена</button><span class="msg-edit-hint">Enter — сохранить · Esc — отмена</span></div>`;
  msgBody.appendChild(box);
  const ta=document.getElementById('editTA_'+m.id);
  if(ta){ta.style.height='auto';ta.style.height=ta.scrollHeight+'px';ta.focus();ta.setSelectionRange(ta.value.length,ta.value.length);ta.onkeydown=e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();saveEditMsg(m.id);}if(e.key==='Escape'){cancelEditMsg(m.id);}};ta.oninput=()=>{ta.style.height='auto';ta.style.height=ta.scrollHeight+'px';};}
}
async function saveEditMsg(msgId){const ta=document.getElementById('editTA_'+msgId);if(!ta) return;const newText=ta.value.trim();if(!newText){toast('Текст не может быть пустым','err');return;}const mode=ta.getAttribute('data-edit-mode')||'channel';const action=mode==='dm'?'dm_edit':'edit_message';const r=await api({action,messageId:msgId,text:newText});if(!r.ok){toast(r.error,'err');return;}cancelEditMsg(msgId,r.text,r.textHtml);toast('Сообщение изменено ✓','ok');}
function cancelEditMsg(msgId,newText,newHtml){const row=document.querySelector(`[data-msg-id="${msgId}"]`);if(!row) return;const msgBody=row.querySelector('.msg-body');if(!msgBody) return;const box=msgBody.querySelector('.msg-edit-box');if(box) box.remove();let textEl=msgBody.querySelector('.msg-text');if(newText&&textEl){textEl.style.display='';textEl.innerHTML=applyMarkdown(newText)+'<span class="msg-edited-tag"> (ред.)</span>';}else if(textEl){textEl.style.display='';}}

// ── POLL ─────────────────────────────────────────────────────
function startPoll(){
  if(S.pollTimer) clearInterval(S.pollTimer);
  S.pollTimer=setInterval(doPoll,1500);
  doPoll();
  setInterval(pollTyping,2000);
  setupReadObserver();
}
async function doPoll(){
  if(!S.me) return;
  const hb=await api({action:'heartbeat',status:S.myStatus,callSinceId:S.lastCallId,voiceEventsSince:S.lastVoiceEventId});
  if(hb.ok){
    S.onlineUsers=hb.online||[];updateOnlineMembers();updateDmBadge(hb.dmUnread||0);
    // ★ FIX: на первом heartbeat только синхронизируем lastCallId, не обрабатываем
// старые сигналы из БД (иначе после перезагрузки браузера звонок "возвращается")
if(!_isFirstHeartbeat && hb.callSignals?.length) for(const sig of hb.callSignals) await dmCallHandleSignal(sig);
_isFirstHeartbeat = false;
if(hb.lastCallId>S.lastCallId) S.lastCallId=hb.lastCallId;
    // СТАЛО: уведомления только для участников той же комнаты
if(hb.voiceEvents?.length){
  for(const ve of hb.voiceEvents){
    if(ve.userId===S.me?.id) continue;
    // Уведомляем только если текущий пользователь находится в той же голосовой комнате
    if(VOICE.roomId!==null && ve.roomId===VOICE.roomId){
      if(ve.type==='join'){
        playSound('voice_join');
        toast(`${esc(ve.userName)} вошёл в голосовой чат`,'info',2500);
      } else if(ve.type==='leave'){
        playSound('voice_leave');
        toast(`${esc(ve.userName)} покинул голосовой чат`,'info',2500);
      }
    }
  }
  S.lastVoiceEventId=hb.lastVoiceEventId||S.lastVoiceEventId;
}
  }
  if(S.mode==='channel'&&S.chId){
    const r=await api({action:'messages',channelId:S.chId,since:S.lastMsgId,limit:50});
    if(r.ok&&r.messages?.length){
      const atBottom=isAtBottom('messagesWrap');
      r.messages.forEach(m=>{
        if(q('messagesList')?.querySelector(`[data-msg-id="${m.id}"]`)) return;
        appendMsg(m);
        if(!S.notifiedMsgIds.has(m.id)&&m.userId!==S.me?.id){
          const isReallyNew=S._chInitDone&&Number.isFinite(S.chNotifyFloor)&&m.id>S.chNotifyFloor;
          const srvMuted=S.servers.find(s=>s.id===S.srvId)?.notifMuted;
          if(isReallyNew&&!srvMuted){
            const notif=getNotif(S.chId);
            const isMention=m.text&&(m.text.includes('@'+S.me.name)||m.text.includes('@everyone')||m.text.includes('@here'));
            if(notif==='all'||(notif==='mentions'&&isMention)){
              playSound(isMention?'mention':'message');
              S.chNotifyFloor=Math.max(S.chNotifyFloor,m.id);
              const chName='#'+(S.channels.find(c=>c.id===S.chId)?.name||'канал');
              const sender=S.members.find(x=>x.id===m.userId)||{avatar:''};
              if(!QR.injectMsg(S.chId,'channel',m)){
                showNotifPopup(chName, m.name+': '+(m.text||'[файл]'),{
                  msgId: m.id,
                  sourceKey: 'ch:'+S.chId,
                  colorKey: m.userId,
                  avatar: sender.avatar||'',
                  avatarEmoji: (m.name||'?')[0],
                  icon: isMention?'@':ti('chat',14),
                  onClick(){ selectChannel(S.chId); DI.clearNotifs(); },
                  onReply(){
                    QR.open({
                      id:'ch_'+S.chId,
                      title: chName,
                      sub: S.servers.find(s=>s.id===S.srvId)?.name||'',
                      avatar: sender.avatar||'',
                      avatarEmoji:'#',
                      mode:'channel',
                      targetId: S.chId,
                      onOpen(){ selectChannel(S.chId); },
                    });
                    DI.clearNotifs();
                  },
                });
              }
            }
            if(isMention){S.mentionCount[S.chId]=(S.mentionCount[S.chId]||0)+1;renderChannels();updateAllSrvDots();}
          }
          S.notifiedMsgIds.add(m.id);
        }
      });
      if(atBottom) scrollBottom('messagesWrap');
      S.lastMsgId=r.messages.at(-1).id;
      S.chLastIds[S.chId]=S.lastMsgId;
      setStoredChLast(S.chId,S.lastMsgId);
    }
    S.bgPollIdx=(S.bgPollIdx+1)%5;
    if(S.bgPollIdx===0){const rr=await api({action:'get_reactions',channelId:S.chId});if(rr.ok) Object.entries(rr.reactions||{}).forEach(([mid,reacts])=>{S.reactions[parseInt(mid)]=reacts;renderReacts(parseInt(mid),reacts);});}
  }
  S.bgChPollIdx2=(S.bgChPollIdx2||0)+1;
  if(S.bgChPollIdx2%4===0&&S.mode==='channel'){
    for(const ch of S.channels){
      if(ch.id===S.chId) continue;
      const since=S.chLastIds[ch.id];
      const rb=await api({action:'messages',channelId:ch.id,since:since??0,limit:20});
      if(!rb.ok) continue;
      if(rb.messages?.length){
        const lastId=rb.messages.at(-1).id;
        if(since!==null){
          const newMsgs=rb.messages.filter(m=>!m.deleted&&m.type!=='deleted'&&m.userId!==S.me?.id);
          if(newMsgs.length){
            S.unread[ch.id]=(S.unread[ch.id]||0)+newMsgs.length;
            newMsgs.forEach(m=>{if(m.text&&S.me&&(m.text.includes('@'+S.me.name)||m.text.includes('@everyone')||m.text.includes('@here'))){S.mentionCount[ch.id]=(S.mentionCount[ch.id]||0)+1;}});
            S.srvUnread[S.srvId]=S.channels.reduce((s,c)=>s+(c.id!==S.chId?S.unread[c.id]||0:0),0);
            S.srvMentions[S.srvId]=S.channels.some(c=>c.id!==S.chId&&(S.mentionCount[c.id]||0)>0);
            renderChannels();updateAllSrvDots();
          }
        }
        S.chLastIds[ch.id]=lastId;
        if(since===null) setStoredChLast(ch.id,lastId);
      } else if(since===null) S.chLastIds[ch.id]=0;
    }
  }
  if(S.mode==='dm'&&S.dmUid) await pollDm();
  S.voiceBgCounter=(S.voiceBgCounter+1)%2;
  if(S.voiceBgCounter===0&&S.srvId){
    const vr=await api({action:'voice_get_rooms',serverId:S.srvId});if(vr.ok){VOICE.rooms=vr.rooms;renderVoiceRooms(vr.rooms);}
    const sv=await api({action:'get_servers'});if(sv.ok){sv.servers.forEach(s=>{S.serverVoiceActive[s.id]=!!(s.voiceActive);const existing=S.servers.find(x=>x.id===s.id);if(existing) existing.isMember=s.isMember;});updateServerVoiceDots();renderSrvBar();}
  }
}
async function refreshCurrentServerUnreadNow(){
  if(!S.me||!S.srvId||!S.channels?.length) return;
  for(const ch of S.channels){
    if(ch.id===S.chId) continue;
    const since=S.chLastIds[ch.id]??getStoredChLast(ch.id);
    const r=await api({action:'messages',channelId:ch.id,since:since||0,limit:20});
    if(!r.ok) continue;
    const msgs=(r.messages||[]).filter(m=>!m.deleted&&m.type!=='deleted');
    if(!msgs.length){
      if(!since){S.chLastIds[ch.id]=0;}
      continue;
    }
    const lastId=msgs.at(-1).id;
    if(since){
      const newMsgs=msgs.filter(m=>m.id>since&&m.userId!==S.me?.id);
      if(newMsgs.length){
        S.unread[ch.id]=(S.unread[ch.id]||0)+newMsgs.length;
        newMsgs.forEach(m=>{
          if(m.text&&(m.text.includes('@'+S.me.name)||m.text.includes('@everyone')||m.text.includes('@here'))){
            S.mentionCount[ch.id]=(S.mentionCount[ch.id]||0)+1;
          }
        });
      }
    } else {
      // Первый запуск для канала: запоминаем текущую вершину, чтобы не пометить всю историю как новую.
      setStoredChLast(ch.id,lastId);
    }
    S.chLastIds[ch.id]=lastId;
  }
  S.srvUnread[S.srvId]=S.channels.reduce((sum,ch)=>sum+(ch.id!==S.chId?(S.unread[ch.id]||0):0),0);
  S.srvMentions[S.srvId]=S.channels.some(ch=>ch.id!==S.chId&&(S.mentionCount[ch.id]||0)>0);
  renderChannels();updateAllSrvDots();
}

function updateDmBadge(n){const dot=q('dmBadgeDot');if(n>0){dot.style.display='flex';dot.textContent=n>99?'99+':n;}else dot.style.display='none';}
function updateOnlineMembers(){document.querySelectorAll('.mb-item').forEach(el=>{const uid=parseInt(el.dataset.uid||'0');const online=S.onlineUsers.find(u=>u.id===uid);const dot=el.querySelector('.mb-status');if(dot){const st=online?online.status||'online':'offline';dot.className='mb-status st-'+st;}});}

// ── MEMBERS ──────────────────────────────────────────────────
async function loadMembers(sid){const r=await api({action:'get_server_members',serverId:sid});if(!r.ok) return;S.members=r.members;renderMembers();}
function renderMembers(){
    const el=q('memberList');el.innerHTML='';
  const onlineCount=S.members.filter(m=>S.onlineUsers.some(o=>o.id===m.id&&o.status!=='invisible')).length;
  const hdr=q('memberSidebar').querySelector('.mb-header');
  if(hdr) hdr.textContent=`${t('common.members')} — ${onlineCount} ${t('presence.online').toLowerCase()}`;
  // ★ Sort: online first within each group
  const sortOF=arr=>[...arr].sort((a,b)=>{
    const aO=S.onlineUsers.some(o=>o.id===a.id&&o.status!=='invisible');
    const bO=S.onlineUsers.some(o=>o.id===b.id&&o.status!=='invisible');
    if(aO!==bO) return aO?-1:1;
    return a.name.localeCompare(b.name);
  });
  const owners=sortOF(S.members.filter(m=>m.role==='owner'));
  const admins=sortOF(S.members.filter(m=>m.role==='admin'));
  const mods=sortOF(S.members.filter(m=>m.role==='moderator'));
  const rest=sortOF(S.members.filter(m=>!['owner','admin','moderator'].includes(m.role)));
  const sec=(title,arr)=>{
    if(!arr.length) return;const s=document.createElement('div');s.className='mb-section';s.innerHTML=title;el.appendChild(s);
    arr.forEach(m=>{
      const d=document.createElement('div');d.className='mb-item';d.dataset.uid=m.id;
      const st=S.onlineUsers.find(u=>u.id===m.id)?.status||m.status||'offline';const isOnline=S.onlineUsers.some(u=>u.id===m.id)&&st!=='invisible';
      const avContent=m.avatar&&m.avatar.startsWith('http')?`<img src="${m.avatar}" alt="">`:(m.name[0]||'?');
      const roleIcon=m.role==='owner'?ti('crown',13):m.role==='admin'?ti('shield',13):m.role==='moderator'?ti('swords',13):'';
      const roleCls=m.role==='owner'?'role-owner':m.role==='admin'?'role-admin':m.role==='moderator'?'role-moderator':'';
      const gRoleIcon=m.globalRole==='super_admin'?`<span class="ti" style="display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;line-height:1;vertical-align:middle"><svg viewBox="0 0 24 24" width="14" height="14"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke="currentColor" stroke-width="1.6" fill="currentColor" stroke-linejoin="round"/></svg></span>`:m.globalRole==='project_admin'?`<span class="ti" style="display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;line-height:1;vertical-align:middle"><svg viewBox="0 0 24 24" width="14" height="14"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="2" y1="12" x2="22" y2="12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z" stroke="currentColor" stroke-width="1.8" fill="none"/></svg></span>`:``;
      const valBadge=m.validated?`<span class="mb-validated-badge">✓</span>`:'';
      const mutedBadge=m.mutedUntil?`<span class="mb-muted-badge">${ti('speakerMuted',12)}</span>`:'';
      const dotSt=isOnline?st:'offline';
      const nameStyle=m.roleColor?`style="color:${esc(m.roleColor)}"`:''
      d.innerHTML=`<div class="mb-av">${avContent}<div class="mb-status st-${dotSt}"></div></div><div class="mb-name ${roleCls}" ${nameStyle}>${esc(m.name)}</div>${valBadge}${mutedBadge}${gRoleIcon?`<div class="mb-role-icon">${gRoleIcon}</div>`:roleIcon?`<div class="mb-role-icon">${roleIcon}</div>`:''}`;
      d.onclick=()=>showUserProfile(m.id);el.appendChild(d);

    });
  // Обновляем badge после цикла
  const badge=document.getElementById('memberCountBadge');
  const mbtn=document.getElementById('memberToggleBtn');
  if(badge){
    const onlineCnt=S.members.filter(m=>S.onlineUsers.some(o=>o.id===m.id&&o.status!=='invisible')).length;
    const totalCnt=S.members.length;
    badge.innerHTML=`<svg viewBox="0 0 24 24" width="14" height="14"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg><span class="mbadge-num">${onlineCnt}<span class="mbadge-sep">/</span><span class="mbadge-total">${totalCnt}</span></span>`;
    if(mbtn) mbtn.title=`${t('common.members')} — ${onlineCnt} ${t('presence.online').toLowerCase()}, ${totalCnt} total`;
  }
  };
  sec(ti('crown',14)+' '+t('roles.owner'),owners);sec(ti('shield',14)+' '+t('roles.admin'),admins);sec(ti('swords',14)+' '+t('roles.moderator'),mods);sec(ti('userGroup',14)+' '+t('common.members'),rest);
}

let S_memberSideOpen=false;
function toggleMemberSidebar(){
  const sb=q('memberSidebar');const isMobile=window.innerWidth<=980;
  if(isMobile){
    const open=sb.classList.contains('open');
    q('chSidebar')?.classList.remove('open');
    sb.classList.toggle('open',!open);
    sb.classList.toggle('collapsed',open);
    q('mobileOverlay').classList.toggle('open',!open);
    S_memberSideOpen=!open;
  }else{
    const collapsed=sb.classList.contains('collapsed');
    sb.classList.toggle('collapsed',!collapsed);
    S_memberSideOpen=collapsed;
  }
}
function closeOverlays(){
  q('mobileOverlay').classList.remove('open');
  q('chSidebar').classList.remove('open');
  q('memberSidebar').classList.remove('open');
  if(window.innerWidth<=980) q('memberSidebar').classList.add('collapsed');
  S_memberSideOpen=false;
  // Safari: сбрасываем pointer-events если вдруг застряли
  const ov=q('mobileOverlay');
  if(ov) ov.style.pointerEvents='';
}
function toggleChSidebar(){
  const sb=q('chSidebar');
  const open=sb.classList.contains('open');
  if(window.innerWidth<=980){
    q('memberSidebar')?.classList.remove('open');
    q('memberSidebar')?.classList.add('collapsed');
    S_memberSideOpen=false;
  }
  sb.classList.toggle('open',!open);
  q('mobileOverlay').classList.toggle('open',!open);
  // Safari iOS fix: принудительный reflow для корректного рендера translate
  if(!open){sb.getBoundingClientRect();}
}

// ── CONTEXT MENU ─────────────────────────────────────────────
let _ctxMsg=null,_ctxMode='channel';
function showCtxMenu(e,m,mode){
  if(!e||!m) return;
  _ctxMsg=m;_ctxMode=mode;
  const menu=q('ctxMenu');
  if(!menu) return;
  const ch=mode==='channel'?S.channels.find(c=>parseInt(c.id,10)===parseInt(S.chId,10)):null;
  const canView=mode==='dm'||canViewChannel(ch);
  const canWrite=mode==='dm'||canWriteChannel(ch);
  const canManage=mode==='dm'?false:canManageChannel(ch);
  if(!canView) return;
  menu.classList.remove('open');
  menu.style.position='fixed';
  menu.style.zIndex='20000';
  const er=q('ctxEmRow');
  if(!er) return;
  er.innerHTML='';
  if(canWrite&&!m.deleted){
    er.style.display='flex';
    QUICK_REACTIONS.forEach(em=>{const b=document.createElement('div');b.className='ctx-em-quick';b.textContent=em;b.onclick=()=>{toggleReaction(m.id,em);closeCtx();};er.appendChild(b);});
    const more=document.createElement('div');more.className='ctx-em-more';more.textContent='…';more.onclick=()=>{openEmojiForReact(m.id);closeCtx();};er.appendChild(more);
  }else{
    er.style.display='none';
  }
  const sd=(id,val)=>{const el=q(id);if(el) el.style.display=val;};
  const hasForwardTargets=mode==='channel'&&S.channels.some(c=>parseInt(c.id,10)!==parseInt(S.chId||0,10)&&canWriteChannel(c));
  sd('ctxReply',mode==='channel'&&canWrite&&!m.deleted?'flex':'none');
  sd('ctxComment',mode==='channel'&&canWrite&&!m.deleted?'flex':'none');
  sd('ctxEdit',(mode==='channel'||mode==='dm')&&m.userId===S.me?.id&&!m.deleted&&!!m.text?'flex':'none');
  sd('ctxMention',canWrite&&!m.deleted?'flex':'none');
  sd('ctxForward',mode==='channel'&&canView&&!m.deleted&&hasForwardTargets?'flex':'none');
  sd('ctxCopy',m.text&&!m.deleted?'flex':'none');
  sd('ctxCopyLink',mode==='channel'&&canView&&!m.deleted?'flex':'none');
  const canDel=m.userId===S.me?.id||S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin'||isMod(S.myRole)||canManage;
  sd('ctxDel',canDel&&!m.deleted?'flex':'none');
  q('ctxMenu')?.querySelectorAll('.ctx-sep').forEach(sep=>{
    const hasDanger=q('ctxDel')?.style.display!=='none';
    sep.style.display=hasDanger?'':'none';
  });
  const x=Math.min(e.clientX,window.innerWidth-180);const y=Math.min(e.clientY,window.innerHeight-320);
  menu.style.left=x+'px';menu.style.top=y+'px';menu.classList.add('open');e.stopPropagation();
}
function isMod(role){return['owner','admin','moderator'].includes(role);}
function isAdmin2(role){return['owner','admin'].includes(role);}
function isGlobalAdmin(){return S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';}
window.ctxReply=function(){if(_ctxMsg&&_ctxMode==='channel') setReply(_ctxMsg);closeCtx();};
window.ctxComment=function(){if(!_ctxMsg) return;closeCtx();const row=document.querySelector(`[data-msg-id="${_ctxMsg.id}"]`);if(row){const btn=row.querySelector('.msg-comment-btn');if(btn) toggleComments(_ctxMsg.id,btn);}};
window.ctxEdit=function(){if(_ctxMsg) startEditMsg(_ctxMsg);};
window.ctxMention=function(){if(!_ctxMsg) return;const ta=S.mode==='dm'?q('dmInput'):q('msgInput');if(ta){ta.value+='@'+_ctxMsg.name+' ';ta.focus();autoGrow(ta);}closeCtx();};
window.ctxForward=function(){if(!_ctxMsg) return;closeCtx();showForwardModal(_ctxMsg.id);};
window.ctxCopy=function(){if(_ctxMsg?.text) navigator.clipboard?.writeText(_ctxMsg.text).then(()=>toast('Скопировано','ok'));closeCtx();};
window.ctxCopyMsgLink=function(){if(!_ctxMsg||!S.chId) return;const link=`${location.origin+location.pathname}#ch/${S.srvId}/${S.chId}`;navigator.clipboard?.writeText(link).then(()=>toast('Ссылка скопирована '+ti('link',13),'ok'));closeCtx();};
window.ctxDelete=async function(){
  if(!_ctxMsg) return;closeCtx();
  if(_ctxMode==='dm'){const r=await api({action:'dm_delete',messageId:_ctxMsg.id});if(r.ok){const el=document.querySelector(`[data-msg-id="${_ctxMsg.id}"]`);if(el){const body=el.querySelector('.msg-body');if(body){body.querySelectorAll('.msg-text,.msg-img,.msg-video,.msg-audio-wrap,.file-card,.msg-gallery,.msg-attach-group,.msg-reactions').forEach(n=>n.remove());const dt=document.createElement('span');dt.className='msg-text deleted-msg';dt.innerHTML=ti('trash',12)+' [удалено]';body.appendChild(dt);}}}else toast(r.error,'err');return;}
  const r=await api({action:'delete_message',messageId:_ctxMsg.id});
  if(r.ok){const el=document.querySelector(`[data-msg-id="${_ctxMsg.id}"]`);if(el){const body=el.querySelector('.msg-body');if(body){body.querySelectorAll('.msg-text,.msg-img,.msg-video,.msg-audio-wrap,.file-card,.msg-gallery,.msg-attach-group,.msg-reactions').forEach(n=>n.remove());const dt=document.createElement('span');dt.className='msg-text deleted-msg';dt.innerHTML=ti('trash',12)+' [удалено]';body.appendChild(dt);}}}else toast(r.error,'err');
};
function closeCtx(){q('ctxMenu').classList.remove('open');}
document.addEventListener('click',e=>{if(!e.target.closest('#ctxMenu')) closeCtx();});

let _touchTimer=null,_touchStartPos=null;
function handleTouchStart(e,m,mode){
  const touch=e.touches?.[0];if(!touch) return;
  _touchStartPos={x:touch.clientX,y:touch.clientY};
  if(_touchTimer){clearTimeout(_touchTimer);_touchTimer=null;}
  _touchTimer=setTimeout(()=>{_touchTimer=null;showCtxMenu({clientX:_touchStartPos.x,clientY:_touchStartPos.y,stopPropagation:()=>{}},m,mode);},600);
}
document.addEventListener('touchmove',()=>{if(_touchTimer){clearTimeout(_touchTimer);_touchTimer=null;}},{passive:true});
document.addEventListener('touchend',()=>{if(_touchTimer){clearTimeout(_touchTimer);_touchTimer=null;}},{passive:true});

// ── FORWARD ──────────────────────────────────────────────────
function showForwardModal(msgId){
  const targets=S.channels.filter(ch=>parseInt(ch.id,10)!==parseInt(S.chId||0,10)&&canWriteChannel(ch));
  if(!targets.length){toast('Нет доступных каналов для пересылки','err');return;}
  const opts=targets.map(ch=>`<div class="ch-item" style="cursor:pointer;margin:2px 0" onclick="doForward(${msgId},${ch.id})"><div class="ch-icon">#</div><span class="ch-name">#${esc(ch.name)}</span></div>`).join('');
  showModal(`<h2>↪ Переслать сообщение</h2><div style="max-height:300px;overflow-y:auto">${opts}</div>`);
}
window.doForward=async function(msgId,chId){const r=await api({action:'forward_message',messageId:msgId,targetChannelId:chId});if(!r.ok){toast(r.error,'err');return;}closeModal();toast('Переслано ✓','ok');if(chId===S.chId){appendMsg(r.msg);scrollBottom('messagesWrap');}};

// ── EMOJI PICKER ─────────────────────────────────────────────
function buildEmojiPicker(){
  const tabs=q('epTabs'),grid=q('epGrid');tabs.innerHTML='';grid.innerHTML='';
  ECATS.forEach((cat,i)=>{const t=document.createElement('div');t.className='ep-tab'+(i===0?' active':'');t.textContent=cat.e;t.title=cat.n;t.onclick=()=>{tabs.querySelectorAll('.ep-tab').forEach(x=>x.classList.remove('active'));t.classList.add('active');renderEpGrid(cat.list);};tabs.appendChild(t);});
  renderEpGrid(ECATS[0].list);
}
function renderEpGrid(list){const g=q('epGrid');g.innerHTML='';list.forEach(em=>{const d=document.createElement('div');d.className='ep-em';d.textContent=em;d.onclick=()=>applyEmoji(em);g.appendChild(d);});}
function applyEmoji(em){
  if(S.epMode==='react'&&S.epReactMsgId){toggleReaction(S.epReactMsgId,em,S.epReactIsDm);closeEmojiPicker();return;}
  const ta=S.epMode==='dm'?q('dmInput'):q('msgInput');if(!ta) return;
  const s=ta.selectionStart,e=ta.selectionEnd;ta.value=ta.value.slice(0,s)+em+ta.value.slice(e);ta.selectionStart=ta.selectionEnd=s+em.length;ta.focus();autoGrow(ta);closeEmojiPicker();
}
window.applyCustomEmoji=function(){const v=q('epCustom').value.trim();if(v) applyEmoji(v);q('epCustom').value='';};
function openEmojiForInput(){S.epMode='channel';openEmojiNearEl(q('msgInput'));}
function openEmojiForDm(){S.epMode='dm';openEmojiNearEl(q('dmInput'));}
function openEmojiForReact(msgId,isDm){S.epMode='react';S.epReactMsgId=msgId;S.epReactIsDm=(isDm===undefined)?(S.mode==='dm'):!!isDm;const el=document.getElementById('reacts'+msgId);openEmojiNearEl(el||document.body);}
function openEmojiNearEl(el){
  const p=q('emojiPicker');p.classList.add('open');const rect=el.getBoundingClientRect();const ph=300,pw=316;
  let top=rect.top-ph-8;if(top<8) top=rect.bottom+8;let left=Math.max(8,Math.min(rect.left,window.innerWidth-pw-8));
  p.style.top=top+'px';p.style.left=left+'px';
}
function closeEmojiPicker(){q('emojiPicker').classList.remove('open');}
document.addEventListener('click',e=>{if(!e.target.closest('#emojiPicker')&&!e.target.closest('.inp-btn')&&!e.target.closest('.r-add-btn')) closeEmojiPicker();});

// ── SERVER MODALS ─────────────────────────────────────────────
function showCreateServerModal(){
  if(!createServerPolicyAllows()){toast(createServerPolicyHint(),'err');return;}
  showModal(`<h2>${ti('castle',18)} ${t('server.createTitle')}</h2><div class="modal-sub">${t('server.createDesc')}</div><div class="av-upload"><div class="av-preview srv-av-preview" id="srvAvPreview" onclick="q('srvAvFile').click()">${ti('castle',28)}<div class="av-ov">${ti('camera',14)}</div></div><input type="file" id="srvAvFile" accept="image/*" style="display:none" onchange="previewSrvAv(this)"></div><div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="newSrvName" placeholder="${t('server.namePlaceholder')}" maxlength="32"></div><div class="fg"><label class="fl">${t('settings.srvDesc')}</label><input class="fi" id="newSrvDesc" placeholder="${t('server.descPlaceholder')}" maxlength="120"></div><div class="fg"><label class="fl">${t('server.iconEmoji')}</label><input class="fi" id="newSrvIcon" placeholder="${t('app.iconOptional')}" maxlength="4" value=""></div><button class="btn btn-gold btn-full" onclick="createServer()">${t('common.create')}</button>`);
  setTimeout(()=>q('newSrvName')?.focus(),100);
}
window.previewSrvAv=function(inp){const file=inp.files[0];if(!file) return;const rd=new FileReader();rd.onload=e=>{const p=q('srvAvPreview');p.innerHTML=`<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:14px"><div class="av-ov">${ti("camera",14)}</div>`;p._file=file;};rd.readAsDataURL(file);};
window.createServer=async function(){
  if(!createServerPolicyAllows()){toast(createServerPolicyHint(),'err');return;}
  const name=q('newSrvName')?.value.trim();const desc=q('newSrvDesc')?.value.trim();let icon=q('newSrvIcon')?.value.trim()||'';
  if(!name){toast(t('toast.enterName'),'err');return;}
  const r=await api({action:'create_server',name,description:desc,icon});if(!r.ok){toast(r.error,'err');return;}
  const sid=r.server.id;
  const prev=q('srvAvPreview');
  if(prev?._file){const mime=prev._file.type||'image/jpeg';const rd=new FileReader();rd.onload=async ev=>{await api({action:'update_server_icon',serverId:sid,image:ev.target.result,mime});await loadServers();await selectServer(sid);};rd.readAsDataURL(prev._file);}
  else{await loadServers();await selectServer(sid);}
  closeModal();toast(t('server.createdToast'),'ok');
};

function showServerSettings(){
  // Настройки относятся к текущему серверу, а не к конкретному текстовому каналу.
  // Поэтому разрешаем открывать их и из voice-stage/после переключения из войса.
  if(!S.srvId||S.mode==='dm') return;
  try{closeCtx&&closeCtx();}catch(e){}
  try{closeOverlays&&closeOverlays();}catch(e){}
  const srv=S.servers.find(s=>s.id===S.srvId); if(!srv) return;
  const canEdit=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin'||isAdmin2(S.myRole);
  const isSuperOrOwner=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';
  const canDelete=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.myRole==='owner';
  const notifMuted=!!srv.notifMuted;
  const ownerName=S.members.find(m=>m.role==='owner')?.name||'—';
  const iconHtml=serverIconHtml(srv.icon,srv.name,'style="width:100%;height:100%;object-fit:cover;border-radius:18px"');
  const avatarMarkup=isImageIcon(srv.icon) ? `<img src="${srv.icon}" style="width:100%;height:100%;object-fit:cover;border-radius:18px">` : iconHtml;

  showModal(`
    <div class="settings-shell server-settings-shell">
      <div class="settings-hero settings-hero-compact">
        <div class="settings-hero-pane settings-hero-copy">
          <div class="settings-kicker">${ti('gear',14)} ${t('settings.srvKicker')}</div>
          <h2 style="margin-bottom:8px">${esc(srv.name)}</h2>
          <div class="sub">${esc(srv.description||t('settings.srvDefaultDesc'))}</div>
          <div class="settings-inline-chips">
            <span class="settings-badge">${ti('crown',12)} ${t('settings.owner')}: ${esc(ownerName)}</span>
            <span class="settings-badge">${ti('userGroup',12)} ${t('settings.membersN',{n:S.members.length})}</span>
            <span class="settings-badge">${ti('hash',12)} ${t('settings.channelsN',{n:S.channels?.length||0})}</span>
          </div>
        </div>

        <div class="settings-profile-card compact">
          <div class="settings-profile-banner"></div>
          <div class="settings-profile-card-body">
            <div class="settings-avatar-wrap settings-avatar-floating ${canEdit?'av-upload':''}">
              <div class="av-preview settings-avatar" id="srvEditAvPrev" onclick="${canEdit ? "q('srvEditAvFile').click()" : "void(0)"}">
                ${avatarMarkup}
                ${canEdit ? `<div class="av-ov">${ti('camera',14)}</div>` : ``}
              </div>
              ${canEdit ? `<input type="file" id="srvEditAvFile" accept="image/*" style="display:none" onchange="uploadSrvIcon(this)">` : ``}
            </div>
            <div class="settings-profile-name">${esc(srv.name)}</div>
            <div class="settings-profile-meta">${ti('sparkles',12)} ${t('settings.srvSpace')}</div>
            <div class="settings-profile-badges">
              <span class="settings-badge">${ti('link',12)} ${t('settings.invites')}</span>
              <span class="settings-badge">${ti('shield',12)} ${t('settings.rolesPerms')}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="settings-grid">
        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('edit',15)} ${t('settings.cardMain')}</div>
            <div class="settings-card-sub">${t('settings.cardMainSub')}</div>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.srvName')}</label>
            <input class="fi" id="editSrvName" value="${esc(srv.name)}" maxlength="32">
          </div>

          <div class="fg">
            <label class="fl">${t('settings.srvDesc')}</label>
            <input class="fi" id="editSrvDesc" value="${esc(srv.description||'')}" maxlength="120">
          </div>

          <div class="fg">
            <label class="fl">${t('settings.srvIconEmoji')}</label>
            <input class="fi" id="editSrvIcon" value="${esc(srv.icon&&!isImageIcon(srv.icon)&&srv.icon.length<=8?srv.icon:'')}" maxlength="4" placeholder="${t('settings.srvIconPlaceholder')}">
          </div>

          <input type="hidden" id="srvCurrentIconUrl" value="${isImageIcon(srv.icon)?esc(srv.icon):''}">

          ${canEdit ? `
          <div class="settings-actions-row">
            <button class="btn btn-gold" onclick="saveServerSettings()">${ti('check',14)} ${t('common.save')}</button>
            <button class="btn btn-ghost" onclick="closeModal()">${t('common.cancel')}</button>
          </div>` : `
          <div class="settings-theme-note">
            <div class="settings-theme-dot"></div>
            <span>${t('settings.noEditRights')}</span>
          </div>`}
        </section>

        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('shield',15)} ${t('settings.cardManage')}</div>
            <div class="settings-card-sub">${t('settings.cardManageSub')}</div>
          </div>

          <div class="server-settings-stack">
            ${canModerateCurrentServer() ? `<button class="btn btn-ghost btn-full settings-soft-btn" onclick="showServerAdminPanel()">${ti('userGroup',14)} ${t('settings.members')}</button>` : ``}
            ${canEdit ? `<button class="btn btn-ghost btn-full settings-soft-btn" onclick="showInviteManager()">${ti('link',14)} ${t('settings.invitations')}</button>` : ``}
            ${canEdit ? `<button class="btn btn-ghost btn-full settings-soft-btn" onclick="showRolesManager()">${ti('grid',14)} ${t('settings.roles')}</button>` : ``}
            ${canEdit ? `<button class="btn btn-ghost btn-full settings-soft-btn" onclick="showTransferServerModal()">${ti('refresh',14)} ${t('settings.transferOwnership')}</button>` : ``}
            ${(!canModerateCurrentServer()&&!canEdit) ? `<div class="settings-theme-note"><div class="settings-theme-dot"></div><span>${t('settings.noEditRights')}</span></div>` : ``}
          </div>

          <div class="server-settings-meta-note">${canEdit||canModerateCurrentServer()?t('settings.unifiedNote'):t('settings.noEditRights')}</div>
        </section>
      </div>

      <details class="settings-accordion">
        <summary>${ti('bell',14)} ${t('settings.srvNotifActions')}</summary>
        <div class="settings-accordion-body">
          <div class="server-settings-actions">
            <button class="btn btn-ghost btn-full settings-soft-btn" onclick="toggleSrvNotif()" id="srvNotifBtn">${notifMuted?`${ti('bellOn',14)} ${t('settings.enableNotif')}`:`${ti('bellOff',14)} ${t('settings.disableNotif')}`}</button>
            ${srv.isMember&&S.myRole!=='owner'
              ? `<button class="btn btn-ghost btn-full" style="color:var(--red2)" onclick="closeModal();leaveCurrentServer()">${ti('logout',14)} ${t('settings.leaveServer')}</button>`
              : `<button class="btn btn-ghost btn-full" onclick="closeModal()">${ti('check',14)} ${t('settings.done')}</button>`}
            ${isSuperOrOwner?`<button class="btn btn-ghost btn-full" onclick="showAdminPanel()">${ti('star',14)} ${t('settings.controlPanel')}</button>`:''}
            ${canDelete?`<button class="btn btn-red btn-full" onclick="confirmDeleteServer(${S.srvId})">${ti('trash',14)} ${t('settings.deleteServer')}</button>`:''}
          </div>
        </div>
      </details>
    </div>
  `);

  document.querySelector('#modalBg .modal')?.classList.add('server-settings-modal');
}
window.toggleSrvNotif=window.toggleSrvNotif=window.toggleSrvNotif=async function(){
  const srv=S.servers.find(s=>s.id===S.srvId);if(!srv) return;
  const newMuted=!srv.notifMuted;
  const r=await api({action:'set_server_notif',serverId:S.srvId,muted:newMuted?1:0});
  if(!r.ok){toast(r.error,'err');return;}
  srv.notifMuted=newMuted;
  const btn=q('srvNotifBtn');if(btn) btn.innerHTML=newMuted?ti('bellOn',14)+' '+t('settings.enableNotif'):ti('bellOff',14)+' '+t('settings.disableNotif');
  toast(newMuted?t('toast.notifOff'):t('toast.notifOn'),'ok');
};
window.confirmDeleteServer=function(sid){if(!(S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.myRole==='owner')){toast('Нет прав для удаления сервера','err');return;}if(!confirm('Удалить сервер?')) return;if(!confirm('Подтвердите удаление.')) return;deleteServerById(sid);};
async function deleteServerById(sid){
  const r=await api({action:'delete_server',serverId:sid});
  if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('toast.serverDeleted'),'ok');
  await loadServers();
  const memberSrv=S.servers.find(s=>s.isMember);
  if(memberSrv) await selectServer(memberSrv.id);
  else{S.srvId=null;q('welcomeScreen').style.display='flex';q('chView').style.display='none';q('chList').innerHTML='';q('srvTitle').textContent='trueCORD';}
}
window.uploadSrvIcon=async function(inp){
  if(!canManageCurrentServer()){toast('Нет прав для редактирования сервера','err');return;}
  const file=inp.files[0];if(!file) return;
  const mime=file.type||'image/jpeg';
  const rd=new FileReader();
  rd.onload=async ev=>{
    const r=await api({action:'update_server_icon',serverId:S.srvId,image:ev.target.result,mime});
    if(!r.ok){toast(r.error,'err');return;}
    await loadServers();
    const prev=q('srvEditAvPrev');
    if(prev) prev.innerHTML=`<img src="${r.icon}" style="width:100%;height:100%;object-fit:cover;border-radius:14px"><div class="av-ov">${ti('camera',14)}</div>`;
    // ← ИСПРАВЛЕНИЕ: сохраняем URL загруженной иконки
    const hiddenUrl=q('srvCurrentIconUrl');
    if(hiddenUrl) hiddenUrl.value=r.icon;
    toast(t('toast.iconUpdated'),'ok');
  };
  rd.readAsDataURL(file);
};
window.saveServerSettings=async function(){
  if(!canManageCurrentServer()){toast('Нет прав для редактирования сервера','err');return;}
  const name=q('editSrvName')?.value.trim();
  const desc=q('editSrvDesc')?.value.trim();
  // ← ИСПРАВЛЕНИЕ: приоритет у загруженного URL, иначе берём эмодзи
  const urlIcon=q('srvCurrentIconUrl')?.value.trim();
  const icon=urlIcon||q('editSrvIcon')?.value.trim();
if(!name){toast(t('toast.enterName'),'err');return;}const r=await api({action:'update_server',serverId:S.srvId,name,description:desc,icon});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('toast.saved'),'ok');await loadServers();q('srvTitle').textContent=name;};
function showTransferServerModal(){
  if(!(S.myRole==='owner'||S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin')){toast('Нет прав для передачи сервера','err');return;}
  const members=S.members.filter(m=>m.id!==S.me?.id);
  const opts=members.map(m=>`<option value="${m.id}">${esc(m.name)}</option>`).join('');
  showModal(`<h2>${ti('refresh',18)} ${t('server.transferTitle')}</h2><p class="sub" style="color:var(--red2)">${ti('warning',14)} ${t('server.transferWarning')}</p><div class="fg"><label class="fl">${t('server.newOwner')}</label><select class="fi" id="newOwnerSel">${opts}</select></div><div class="btn-row"><button class="btn btn-red" onclick="transferServerOwnership()">${t('common.save')}</button><button class="btn btn-ghost" onclick="closeModal()">${t('common.cancel')}</button></div>`);
}
window.transferServerOwnership=async function(){if(!(S.myRole==='owner'||S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin')){toast('Нет прав для передачи сервера','err');return;}const nid=parseInt(q('newOwnerSel')?.value||'0');if(!nid) return;if(!confirm(t('server.transferConfirm'))) return;const r=await api({action:'transfer_server_ownership',serverId:S.srvId,newOwnerId:nid});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('server.transferredToast'),'ok');await loadServers();await loadMembers(S.srvId);};

// ── ROLES MANAGER (Discord-style) ────────────────────────────
let _rolesCache=[];
async function showRolesManager(activeRoleId=null){
  closeModal();
  if(!S.srvId){toast(t('common.selectServer'),'err');return;}
  if(!canManageCurrentServer()){toast('Нет прав для управления ролями','err');return;}
  const r=await api({action:'get_roles',serverId:S.srvId});
  _rolesCache=r.ok?r.roles:[];

  const roleList=_rolesCache.map(role=>{
    const isActive=activeRoleId===role.id;
    return `<button class="rm-role-item${isActive?' active':''}" type="button" onclick="rmSelectRole(${role.id})" data-rid="${role.id}">
      <span class="rm-role-dot" style="background:${esc(role.color)}"></span>
      <span class="rm-role-name">${esc(role.name)}</span>
    </button>`;
  }).join('');

  const detailHtml=activeRoleId
    ? await rmBuildDetail(activeRoleId)
    : `<div class="rm-empty-state"><div class="rm-empty-icon">${ti('smile',24)}</div><div class="rm-empty-title">${t('roles.managerSelectTitle')}</div><div class="rm-empty-sub">${t('roles.managerSelectSub')}</div></div>`;

  showModal(`<div class="roles-manager-window">
    <div class="roles-manager-top">
      <div class="roles-manager-top-title">${ti('shield',20)} <span>${t('admin.roles')}</span></div>
    </div>
    <div class="roles-manager-shell">
      <aside class="roles-manager-sidebar">
        <div class="roles-manager-sidebar-head">
          <div class="roles-manager-title">${t('admin.roles')} (${_rolesCache.length})</div>
        </div>
        <div class="roles-manager-list" id="rmRoleList">${roleList||`<div class="rm-list-empty">${t('common.empty')}</div>`}</div>
        <div class="roles-manager-actions">
          <button class="btn btn-gold btn-full" type="button" onclick="rmCreateRole()">${ti('plus',14)} <span>${t('roles.create')}</span></button>
        </div>
      </aside>
      <section class="roles-manager-detail" id="rmDetail">${detailHtml}</section>
    </div>
  </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('roles-manager-modal');
}

async function rmBuildDetail(roleId){
  const role=_rolesCache.find(r=>r.id===roleId);
  if(!role) return `<div class="rm-list-empty">${t('roles.notFound')}</div>`;
  const membersHtml=await rmBuildMembers(roleId,role);
  return `
    <div class="rm-detail-wrap">
      <div class="rm-detail-header">
        <div class="rm-role-badge"><span class="rm-role-dot lg" style="background:${esc(role.color)}"></span><span style="color:${esc(role.color)}">${esc(role.name)}</span></div>
      </div>
      <div class="rm-form-grid">
        <div class="fg"><label class="fl">${t('settings.name')}</label><input class="fi" id="rmEditName" value="${esc(role.name)}" maxlength="32"></div>
        <div class="fg"><label class="fl">${t('roles.color')}</label><input type="color" class="fi" id="rmEditColor" value="${esc(role.color)}" style="height:42px;padding:4px 6px;cursor:pointer"></div>
      </div>
      <div class="settings-actions-row rm-top-actions">
        <button class="btn btn-gold" onclick="rmSaveRole(${role.id})">${t('common.save')}</button>
        <button class="btn btn-red" onclick="rmDeleteRole(${role.id})">${t('common.delete')}</button>
      </div>
      <div class="ctx-sep" style="margin:16px 0"></div>
      <div class="rm-section-label">${t('roles.membersWithRole')}</div>
      <div class="rm-members-list" id="rmMembersList">${membersHtml}</div>
      <button class="btn btn-ghost btn-full" style="margin-top:12px" onclick="rmShowAddMember(${role.id})">${ti('plus',14)} ${t('roles.addMember')}</button>
    </div>`;
}

async function rmBuildMembers(roleId,role){
  const r=await api({action:'get_role_members',serverId:S.srvId,roleId});
  const memberIds=new Set(r.ok?r.userIds:[]);
  const allMembers=S.members||[];
  let html='';
  for(const m of allMembers){
    if(memberIds.has(m.id)){
      html+=`<div class="rm-member-row"><div class="rm-member-meta"><span class="rm-member-dot" style="background:${esc(role.color)}"></span><span class="rm-member-name">${esc(m.name)}</span></div><button class="btn btn-ghost" onclick="rmRemoveMember(${m.id},${roleId})">${t('roles.remove')}</button></div>`;
    }
  }
  return html||`<div class="rm-list-empty">${t('roles.unassigned')}</div>`;
}

window.rmSelectRole=function(roleId){showRolesManager(roleId);};

window.rmCreateRole=async function(){
  const name=prompt(t('roles.newRolePrompt'));
  if(!name||!name.trim()) return;
  const r=await api({action:'create_role',serverId:S.srvId,name:name.trim(),color:'#c9aa71'});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('roles.createdToast'),'ok');
  await showRolesManager(r.role?.id||null);
};

window.rmSaveRole=async function(roleId){
  const name=q('rmEditName')?.value.trim();
  const color=q('rmEditColor')?.value||'#c9aa71';
  if(!name){toast(t('toast.enterName'),'err');return;}
  const r=await api({action:'update_role',roleId,name,color});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('toast.saved'),'ok');
  await loadMembers(S.srvId);
  await showRolesManager(roleId);
};

window.rmDeleteRole=async function(roleId){
  if(!confirm(t('roles.deleteConfirm'))) return;
  const r=await api({action:'delete_role',roleId});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('roles.deletedToast'),'ok');
  await loadMembers(S.srvId);
  await showRolesManager();
};

window.rmRemoveMember=async function(uid,roleId){
  const r=await api({action:'remove_role',serverId:S.srvId,targetId:uid,roleId});
  if(!r.ok){toast(r.error,'err');return;}
  await loadMembers(S.srvId);
  await showRolesManager(roleId);
};

window.rmShowAddMember=function(roleId){
  const allM=S.members.filter(m=>m.id!==S.me?.id);
  const list=allM.map(m=>`<button type="button" class="rm-add-member-row" onclick="rmAddMember(${m.id},${roleId})"><span>${esc(m.name)}</span><span class="rm-add-member-act">${t('roles.assign')}</span></button>`).join('');
  q('rmDetail').innerHTML=`
    <div class="rm-detail-wrap">
      <div class="rm-section-label">${t('roles.addMemberTitle')}</div>
      <div class="rm-members-list">${list||`<div class="rm-list-empty">${t('roles.noMembersAvailable')}</div>`}</div>
      <button class="btn btn-ghost btn-full" style="margin-top:12px" onclick="showRolesManager(${roleId})">${t('common.back')}</button>
    </div>`;
};

window.rmAddMember=async function(uid,roleId){
  const r=await api({action:'assign_role',serverId:S.srvId,targetId:uid,roleId});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('roles.assignedToast'),'ok');
  await loadMembers(S.srvId);
  await showRolesManager(roleId);
};

// ── ASSIGN ROLE FROM PROFILE ────────────────────────────────
window.showAssignRoleModal = async function(uid) {
  if(!canManageCurrentServer()){toast('Нет прав для назначения ролей','err');return;}
  const u = S.members.find(x=>x.id===uid) || S.allUsers.find(x=>x.id===uid) || {id:uid, name:t('common.user')};
  if(!S.srvId){toast(t('common.selectServer'),'err');return;}
  const rolesR = await api({action:'get_roles', serverId:S.srvId});
  const roles = rolesR.ok ? rolesR.roles : [];
  const userRolesR = await api({action:'get_user_roles', serverId:S.srvId, targetId:uid});
  const userRoles = userRolesR.ok ? userRolesR.roles : [];
  const userRoleIds = new Set(userRoles.map(r => r.id));
  if (!roles.length) {
    showModal(`<div class="assign-role-shell"><h2>${ti("palette",18)} ${t('roles.modalTitle',{name:u.name})}</h2>
      <p class="modal-sub">${t('roles.noRoles')}</p>
      <div class="settings-actions-row"><button class="btn btn-gold" onclick="showRolesManager()">${t('roles.create')}</button><button class="btn btn-ghost" onclick="closeModal()">${t('common.close')}</button></div></div>`);
    document.querySelector('#modalBg .modal')?.classList.add('assign-role-modal');
    return;
  }
  const rolesList = roles.map(role => {
    const has = userRoleIds.has(role.id);
    return `<div class="assign-role-item">
      <div class="assign-role-meta"><span class="assign-role-dot" style="background:${esc(role.color)}"></span><span class="assign-role-name" style="color:${esc(role.color)}">${esc(role.name)}</span></div>
      ${has ? `<button class="btn btn-red" onclick="removeRoleFromUser(${uid},${role.id})">${t('roles.remove')}</button>` : `<button class="btn btn-gold" onclick="assignRoleToUser(${uid},${role.id})">${t('roles.assign')}</button>`}
    </div>`;
  }).join('');
  showModal(`<div class="assign-role-shell"><h2>${ti("palette",18)} ${t('roles.modalTitle',{name:u.name})}</h2>
    <div class="assign-role-list">${rolesList}</div>
    <div class="settings-actions-row"><button class="btn btn-ghost" onclick="closeModal()">${t('common.close')}</button></div></div>`);
  document.querySelector('#modalBg .modal')?.classList.add('assign-role-modal');
};
window.assignRoleToUser = async function(uid, roleId) {
  const r = await api({action:'assign_role', serverId:S.srvId, targetId:uid, roleId});
  if (!r.ok) { toast(r.error,'err'); return; }
  toast(t('roles.assignedToast'),'ok');
  await showAssignRoleModal(uid);
  await loadMembers(S.srvId);
};
window.removeRoleFromUser = async function(uid, roleId) {
  const r = await api({action:'remove_role', serverId:S.srvId, targetId:uid, roleId});
  if (!r.ok) { toast(r.error,'err'); return; }
  toast(t('roles.removedToast'),'ok');
  await showAssignRoleModal(uid);
  await loadMembers(S.srvId);
};

// ── SUPER ADMIN PANEL ─────────────────────────────────────────
// tf: t() с запасным текстом, если ключ не найден (на случай устаревшего i18n.js на сервере).
function tf(key,fallback){const v=t(key);return v===key?fallback:v;}
async function showAdminPanel(){
  const isSuperOrOwner=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin';
  closeModal();
  const bansR=await api({action:'get_global_bans'});const bans=bansR.ok?bansR.bans:[];
  try{await loadDmConvs();}catch(e){}
  const bannedIds=new Set(bans.map(b=>parseInt(b.userId,10)));
  const banReason={}; bans.forEach(b=>{banReason[parseInt(b.userId,10)]=b.reason||'';});
  // Пользователи: сначала не забаненные, затем забаненные — единым списком.
  const usersSorted=S.allUsers.filter(u=>u.id!==S.me?.id).slice().sort((a,b)=>{
    const ba=bannedIds.has(a.id)?1:0, bb=bannedIds.has(b.id)?1:0;
    if(ba!==bb) return ba-bb;                       // забаненные в конец
    return String(a.name||'').localeCompare(String(b.name||''),'ru');
  });
  const rowHtml=(u)=>{
    const online=S.onlineUsers.find(o=>o.id===u.id);
    const st=(online?online.status:(u.status||'offline'))||'offline';
    const dotSt=(st&&st!=='invisible')?st:'offline';
    const conv=(S.dmConvs||[]).find(c=>c.userId===u.id);
    const unread=conv?parseInt(conv.unread||0,10):0;
    const avContent=u.avatar&&u.avatar.startsWith('http')?`<img src="${escAttr(u.avatar)}" alt="">`:avatarFallbackHtml(u.name,u.id);
    const safeName=esc(JSON.stringify(u.name));
    const banned=bannedIds.has(u.id);
    const roleBadge=banned?`<span class="admin-inline-badge admin-inline-badge-red">${tf('admin.banned','Заблокирован')}</span>`
      :u.globalRole==='super_admin'?`<span class="admin-inline-badge admin-inline-badge-gold">${ti('star',10)} SA</span>`
      :u.globalRole==='project_admin'?`<span class="admin-inline-badge admin-inline-badge-orange">PA</span>`:'';
    return `
    <div class="admin-row${banned?' is-banned':''}" data-uid="${u.id}" data-name="${escAttr((u.name||'').toLowerCase())}">
      <button class="admin-row-head" onclick="toggleAdminRow(${u.id})" aria-expanded="false">
        <span class="admin-row-av">${avContent}<span class="admin-status-dot st-${dotSt}"></span>${unread>0?`<span class="admin-unread-badge">${unread>99?'99+':unread}</span>`:''}</span>
        <span class="admin-row-copy">
          <span class="admin-row-name">${esc(u.name)}${u.validated?' <span class="admin-vrf">✓</span>':''}</span>
          <span class="admin-row-sub">${roleBadge||esc(STATUS_META[dotSt]?.label||'')}</span>
        </span>
        <span class="admin-row-caret"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></span>
      </button>
      <div class="admin-row-actions" id="adminRowAct${u.id}" hidden>
        ${!u.validated?`<button class="admin-mini-btn ok" onclick="validateUserInPanel(${u.id})" title="${tf('admin.verify','Верифицировать')}">✓</button>`:(isSuperOrOwner?`<button class="admin-mini-btn" onclick="unvalidateUser(${u.id})" title="${tf('common.unverify','Снять верификацию')}">✕</button>`:'')}
        <button class="admin-mini-btn gold" onclick="setSuperAdmin(${u.id},${u.globalRole!=='super_admin'?'true':'false'})" title="${u.globalRole!=='super_admin'?tf('roles.superAdmin','Супер-админ'):tf('common.remove','Убрать')}">${ti('star',14)}</button>
        <button class="admin-mini-btn" onclick="setProjectAdmin(${u.id},${u.globalRole!=='project_admin'?'true':'false'})" title="${u.globalRole!=='project_admin'?tf('roles.projectAdmin','Админ проекта'):tf('common.remove','Убрать')}">PA</button>
        <button class="admin-mini-btn" onclick="muteUserModal(${u.id},${safeName})" title="${tf('admin.mute','Мут')}">${ti('speakerMuted',14)}</button>
        ${banned
          ? `<button class="admin-mini-btn unban" onclick="globalUnbanUser(${u.id})" title="${tf('admin.unban','Разбанить')}">${ti('check',14)}</button>`
          : `<button class="admin-mini-btn red" onclick="globalBanUser(${u.id},${safeName})" title="${tf('admin.ban','Бан')}">${ti('ban',14)}</button>`}
        <button class="admin-mini-btn red" onclick="deleteUserConfirm(${u.id},${safeName})" title="${tf('common.delete','Удалить')}">${ti('trash',14)}</button>
      </div>
    </div>`;
  };
  const allUsersHtml=usersSorted.map(rowHtml).join('');
  // Забаненные, которых нет в списке пользователей (например удалённые аккаунты) — отдельными строками.
  const orphanBans=bans.filter(b=>!S.allUsers.some(u=>u.id===parseInt(b.userId,10)));
  const orphanHtml=orphanBans.map(b=>`
    <div class="admin-row is-banned" data-name="${escAttr((b.username||'').toLowerCase())}">
      <div class="admin-row-head" style="cursor:default">
        <span class="admin-row-av">${avatarFallbackHtml(b.username,b.userId)}</span>
        <span class="admin-row-copy">
          <span class="admin-row-name">${esc(b.username)}</span>
          <span class="admin-row-sub"><span class="admin-inline-badge admin-inline-badge-red">${tf('admin.banned','Заблокирован')}</span>${b.reason?' '+esc(b.reason):''}</span>
        </span>
        <button class="admin-mini-btn unban" onclick="globalUnbanUser(${b.userId})" title="${tf('admin.unban','Разбанить')}" style="margin-left:auto">${ti('check',14)}</button>
      </div>
    </div>`).join('');
  showModal(`
    <div class="admin-panel-modal admin-panel-clean">
      <h2>${ti('shield',18)} ${tf('settings.controlPanel','Панель управления')}</h2>
      <div class="admin-panel-subhead">
        <span class="admin-section-title" style="margin:0">${tf('admin.users','Пользователи')}</span>
        <span class="admin-ban-counter">${tf('admin.globalBans','Глобальные баны')}: ${bans.length}</span>
      </div>
      <div class="admin-search-wrap"><input class="admin-search-input" id="adminUserSearch" placeholder="${tf('common.search','Поиск')}" oninput="filterAdminUsers(this.value)"></div>
      <div class="admin-rows" id="adminUsersList">${allUsersHtml}${orphanHtml}</div>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('admin-panel-window');
}
window.toggleAdminRow=function(uid){
  const act=document.getElementById('adminRowAct'+uid);
  if(!act) return;
  const row=act.closest('.admin-row');
  const head=row?.querySelector('.admin-row-head');
  const open=act.hasAttribute('hidden')===false;
  // Закрываем остальные (аккордеон)
  document.querySelectorAll('#adminUsersList .admin-row-actions').forEach(a=>{if(a!==act){a.setAttribute('hidden','');a.closest('.admin-row')?.classList.remove('open');a.closest('.admin-row')?.querySelector('.admin-row-head')?.setAttribute('aria-expanded','false');}});
  if(open){act.setAttribute('hidden','');row?.classList.remove('open');head?.setAttribute('aria-expanded','false');}
  else{act.removeAttribute('hidden');row?.classList.add('open');head?.setAttribute('aria-expanded','true');}
}
window.filterAdminUsers=function(qv){
  const term=String(qv||'').trim().toLowerCase();
  document.querySelectorAll('#adminUsersList .admin-row').forEach(row=>{
    const name=row.getAttribute('data-name')||'';
    row.style.display=(!term||name.includes(term))?'':'none';
  });
}
window.deleteUserConfirm=function(uid,name){if(!confirm(t('admin.deleteProfileConfirm',{name}))) return;deleteUserById(uid,name);};
async function deleteUserById(uid,name){const r=await api({action:'delete_user',targetId:uid});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.profileDeleted',{name}),'ok');await loadUsers();await showAdminPanel();}
window.validateUser=async function(uid){const r=await api({action:'validate_user',targetId:uid});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.verifiedToast'),'ok');await loadMembers(S.srvId);await loadUsers();showUserProfile(uid);};
window.validateUserInPanel=async function(uid){const r=await api({action:'validate_user',targetId:uid});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.verifiedToast'),'ok');await loadUsers();await loadMembers(S.srvId);await showAdminPanel();};
window.unvalidateUser=async function(uid){const r=await api({action:'unvalidate_user',targetId:uid});if(!r.ok){toast(r.error,'err');return;}toast(t('profile.unverifiedToast'),'ok');await loadUsers();await showAdminPanel();};
window.setSuperAdmin=async function(uid,give){const role=give?'super_admin':'';const r=await api({action:'set_global_role',targetId:uid,role});if(!r.ok){toast(r.error,'err');return;}toast(give?t('admin.assignedSuper'):t('admin.roleRemoved'),'ok');await loadUsers();await showAdminPanel();};
window.setProjectAdmin=async function(uid,give){const role=give?'project_admin':'';const r=await api({action:'set_global_role',targetId:uid,role});if(!r.ok){toast(r.error,'err');return;}toast(give?t('admin.assignedProject'):t('admin.roleRemoved'),'ok');await loadUsers();await showAdminPanel();};
window.globalBanUser=async function(uid,name){const reason=prompt(t('admin.banReasonFor',{name}))??'';if(reason===null) return;const r=await api({action:'global_ban_user',targetId:uid,reason});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.userBlocked',{name}),'ok');await showAdminPanel();};
window.globalUnbanUser=async function(uid){const r=await api({action:'global_unban_user',targetId:uid});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.unblocked'),'ok');await showAdminPanel();};
window.kickUser=async function(uid){const reason=prompt(t('admin.kickReason'))??'';if(reason===null) return;const r=await api({action:'kick_user',serverId:S.srvId,targetId:uid,reason});if(!r.ok){toast(r.error,'err');return;}toast(t('admin.kicked'),'ok');await loadMembers(S.srvId);closeModal();};
window.muteUserModal=function(uid,name){showModal(`<h2>${ti('speakerMuted',18)} ${t('admin.muteUserTitle',{name:esc(name)})}</h2><div class="fg"><label class="fl">${t('common.durationMinutes')}</label><input class="fi" type="number" id="muteDur" value="10" min="0"></div><div class="fg"><label class="fl">${t('common.reason')}</label><input class="fi" id="muteReason" placeholder="${t('common.optional')}"></div><div class="btn-row"><button class="btn btn-red" onclick="doMuteUser(${uid})">${t('admin.mute')}</button><button class="btn btn-ghost" onclick="closeModal()">${t('common.cancel')}</button></div>`);};
window.doMuteUser=async function(uid){const duration=parseInt(q('muteDur')?.value||'10');const reason=q('muteReason')?.value.trim()||'';const r=await api({action:'mute_user',serverId:S.srvId,targetId:uid,duration,reason});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('admin.mutedToast'),'ok');await loadMembers(S.srvId);};

// ── CHANNEL MODALS ───────────────────────────────────────────
function showCreateChannelModal(){
  if(!assertCanCreateChannel()) return;
  showModal(`<div class="create-channel-modal"><h2>${ti('plus',18)} ${t('channel.createOwn')}</h2><p class="create-channel-sub">${t('channel.createDesc')}</p><div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="newChName" placeholder="${t('channel.namePlaceholder')}" maxlength="32"></div><div class="fg"><label class="fl">${t('channel.topic')}</label><input class="fi" id="newChTopic" placeholder="${t('channel.topicPlaceholder')}" maxlength="120"></div><div class="fg"><label class="fl">${t('settings.srvDesc')}</label><textarea class="fi" id="newChDesc" maxlength="3000" placeholder="${t('channel.descPlaceholder')}"></textarea></div><div class="fg"><label class="fl">${t('channel.read')}</label><select class="fi" id="newChRead"><option value="all">${t('perm.all')}</option><option value="members" selected>${t('perm.members')}</option><option value="admins">${t('perm.admins')}</option></select></div><div class="fg"><label class="fl">${t('channel.write')}</label><select class="fi" id="newChWrite"><option value="all">${t('perm.all')}</option><option value="members" selected>${t('perm.members')}</option><option value="admins">${t('perm.admins')}</option></select></div><button class="btn btn-gold btn-full create-channel-submit" onclick="createChannel()">${t('channel.create')}</button></div>`);
  setTimeout(()=>q('newChName')?.focus(),100);
}
window.createChannel=async function(){if(!assertCanCreateChannel()) return;const name=q('newChName')?.value.trim();const topic=q('newChTopic')?.value.trim();const desc=q('newChDesc')?.value.trim();const pr=q('newChRead')?.value||'all';const pw=q('newChWrite')?.value||'all';if(!name){toast(t('toast.enterName'),'err');return;}const r=await api({action:'create_channel',serverId:S.srvId,name,topic,description:desc,permRead:pr,permWrite:pw});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('channel.createdToast'),'ok');await loadChannels(S.srvId);await selectChannel(r.channel.id);};
window.showChSettings=async function(e,chId){
  e.stopPropagation();
  const ch=S.channels.find(c=>c.id===chId); if(!ch) return;
  if(!assertCanManageChannel(ch)) return;
  const iconMarkup=ch.avatar&&isImageIcon(ch.avatar)?imageIconHtml(ch.avatar,'#'):ti('megaphone',16);
  const readMap={all:t('perm.all'),members:t('perm.members'),admins:t('perm.admins')};
  const writeMap={all:t('perm.all'),members:t('perm.members'),admins:t('perm.admins')};
  showModal(`
    <div class="settings-shell channel-settings-shell">
      <div class="settings-hero settings-hero-compact">
        <div class="settings-hero-pane settings-hero-copy">
          <div class="settings-kicker">${ti('gear',14)} ${t('channel.settings')}</div>
          <h2 style="margin-bottom:8px">#${esc(ch.name)}</h2>
          <div class="sub">${esc(ch.description||t('channel.defaultDesc'))}</div>
          <div class="settings-inline-chips">
            <span class="settings-badge">${ti('hash',12)} ${t('channel.textChannel')}</span>
            <span class="settings-badge">${ti('eye',12)} ${t('channel.read')}: ${readMap[ch.permRead]||t('perm.all')}</span>
            <span class="settings-badge">${ti('edit',12)} ${t('channel.write')}: ${writeMap[ch.permWrite]||t('perm.all')}</span>
          </div>
        </div>

        <div class="settings-profile-card compact">
          <div class="settings-profile-banner"></div>
          <div class="settings-profile-card-body">
            <div class="settings-avatar-wrap settings-avatar-floating av-upload">
              <div class="av-preview settings-avatar" id="chAvPrev" onclick="q('chAvFile').click()">
                ${iconMarkup}
                <div class="av-ov">${ti('camera',14)}</div>
              </div>
              <input type="file" id="chAvFile" accept="image/*" style="display:none" onchange="uploadChAvatar(this,${chId})">
            </div>
            <div class="settings-profile-name">#${esc(ch.name)}</div>
            <div class="settings-profile-meta">${esc(ch.topic||t('channel.noTopic'))}</div>
            <div class="settings-profile-badges">
              <span class="settings-badge">${ti('shield',12)} ${t('channel.accessRights')}</span>
              <span class="settings-badge">${ti('message',12)} ${t('channel.messages')}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="channel-settings-grid">
        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('edit',15)} ${t('settings.cardMain')}</div>
            <div class="settings-card-sub">${t('channel.mainSub')}</div>
          </div>

          <div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="editChName" value="${esc(ch.name)}" maxlength="32"></div>
          <div class="fg"><label class="fl">${t('channel.topic')}</label><input class="fi" id="editChTopic" value="${esc(ch.topic||'')}" maxlength="120"></div>
          <div class="fg"><label class="fl">${t('settings.srvDesc')}</label><textarea class="fi" id="editChDesc" maxlength="3000">${esc(ch.description||'')}</textarea></div>
        </section>

        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('shield',15)} ${t('channel.accessActions')}</div>
            <div class="settings-card-sub">${t('channel.accessSub')}</div>
          </div>

          <div class="fg"><label class="fl">${t('channel.read')}</label><select class="fi" id="editChRead"><option value="all" ${ch.permRead==='all'?'selected':''}>${t('perm.all')}</option><option value="members" ${ch.permRead==='members'?'selected':''}>${t('perm.members')}</option><option value="admins" ${ch.permRead==='admins'?'selected':''}>${t('perm.admins')}</option></select></div>
          <div class="fg"><label class="fl">${t('channel.write')}</label><select class="fi" id="editChWrite"><option value="all" ${ch.permWrite==='all'?'selected':''}>${t('perm.all')}</option><option value="members" ${ch.permWrite==='members'?'selected':''}>${t('perm.members')}</option><option value="admins" ${ch.permWrite==='admins'?'selected':''}>${t('perm.admins')}</option></select></div>

          <div class="settings-actions-row">
            <button class="btn btn-gold" onclick="saveChSettings(${chId})">${ti('check',14)} ${t('common.save')}</button>
            <button class="btn btn-red" onclick="deleteChannel(${chId})">${ti('trash',14)} ${t('common.delete')}</button>
          </div>
        </section>
      </div>
    </div>
  `);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
};
window.uploadChAvatar=async function(inp,chId){const ch=S.channels.find(c=>c.id===chId);if(!ch||!assertCanManageChannel(ch)) return;const file=inp.files[0];if(!file) return;const mime=file.type||'image/jpeg';const rd=new FileReader();rd.onload=async ev=>{const r=await api({action:'update_channel_avatar',channelId:chId,image:ev.target.result,mime});if(!r.ok){toast(r.error,'err');return;}const prev=q('chAvPrev');if(prev) prev.innerHTML=`<img src="${r.avatar}"><div class="av-ov">${ti('camera',14)}</div>`;toast(t('toast.avatarUpdated'),'ok');await loadChannels(S.srvId);};rd.readAsDataURL(file);};
window.saveChSettings=async function(chId){const ch=S.channels.find(c=>c.id===chId);if(!ch||!assertCanManageChannel(ch)) return;const name=q('editChName')?.value.trim();const topic=q('editChTopic')?.value.trim();const desc=q('editChDesc')?.value.trim();const pr=q('editChRead')?.value||'all';const pw=q('editChWrite')?.value||'all';if(!name){toast(t('toast.enterName'),'err');return;}const r=await api({action:'update_channel',channelId:chId,name,topic,description:desc,permRead:pr,permWrite:pw});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('toast.saved'),'ok');await loadChannels(S.srvId);if(S.chId===chId) updateChHeader(r.channel);};
window.deleteChannel=async function(chId){const ch=S.channels.find(c=>c.id===chId);if(ch&&!assertCanManageChannel(ch)) return;if(!confirm(t('confirm.deleteChannel'))) return;const r=await api({action:'delete_channel',serverId:S.srvId,channelId:chId});if(!r.ok){toast(r.error,'err');return;}closeModal();toast(t('toast.deleted'),'ok');if(S.chId===chId){S.chId=null;q('chView').style.display='none';q('welcomeScreen').style.display='flex';}await loadChannels(S.srvId);};
function showNotifModal(e,chId){
  e.stopPropagation();const cur=getNotif(chId);
  const opts=[{v:'all',icon:ti('bellOn',16),t:t('notifications.all'),d:t('notifications.allDesc')},{v:'mentions',icon:'@',t:t('notifications.mentions'),d:t('notifications.mentionsDesc')},{v:'mute',icon:ti('bellOff',16),t:t('notifications.mute'),d:t('notifications.muteDesc')}];
  showModal(`<div class="notif-modal"><h2>${ti('bell',18)} ${t('notifications.channelTitle')}</h2>${opts.map(o=>`<button class="notif-option ${cur===o.v?'active':''}" onclick="setChNotif('${chId}','${o.v}')"><span class="notif-option-icon">${o.icon}</span><span class="notif-option-copy"><b>${o.t}</b><small>${o.d}</small></span><span class="notif-check">${cur===o.v?'✓':''}</span></button>`).join('')}</div>`);
}
window.setChNotif=function(chId,v){setNotif(parseInt(chId),v);closeModal();};

// ── INVITE MANAGER ───────────────────────────────────────────
async function showInviteManager(){
  closeModal();if(!canManageCurrentServer()){toast('Нет прав для просмотра приглашений','err');return;}const r=await api({action:'get_invites',serverId:S.srvId});const invites=r.ok?r.invites:[];
  const list=invites.length?invites.map(inv=>`<div style="background:var(--bg3);border-radius:6px;padding:10px 12px;margin-bottom:8px;font-size:13px"><div style="display:flex;align-items:center;gap:8px;margin-bottom:4px"><code style="flex:1;background:var(--bg0);padding:3px 8px;border-radius:4px;font-size:11px;word-break:break-all">${esc(inv.link)}</code><button class="btn btn-ghost" style="padding:3px 8px;font-size:12px" onclick="navigator.clipboard?.writeText('${esc(inv.link)}').then(()=>toast('Скопировано','ok'))">${ti('clipboard',13)}</button><button class="btn btn-red" style="padding:3px 8px;font-size:12px" onclick="deleteInvite('${esc(inv.code)}')">✕</button></div><div style="color:var(--text3)">от ${esc(inv.creatorName)} · ${inv.uses}${inv.maxUses?'/'+inv.maxUses:''} исп.</div></div>`).join(''):'<p style="color:var(--text3);font-size:13px">Нет приглашений</p>';
  showModal(`<h2>${ti('link',18)} ${t('server.invitesTitle')}</h2><p style="font-size:12px;color:var(--text3);margin-bottom:8px">${t('server.inviteFormat',{url:(APP_CFG.siteUrl||location.origin)})}</p><button class="btn btn-gold btn-full" onclick="createInvite()" style="margin-bottom:16px">${ti('plus',14)} ${t('server.createInvite')}</button>${list}`);
}
window.createInvite=async function(){const r=await api({action:'create_invite',serverId:S.srvId,maxUses:0,expiresHours:0});if(!r.ok){toast(r.error,'err');return;}navigator.clipboard?.writeText(r.link).then(()=>toast('Ссылка скопирована '+ti('link',13),'ok'));await showInviteManager();};
window.deleteInvite=async function(code){await api({action:'delete_invite',serverId:S.srvId,code});await showInviteManager();toast(t('toast.deleted'),'ok');};

// ── USER PROFILE ─────────────────────────────────────────────
function showMyProfile(){
  const av=S.me?.avatar?`<img src="${S.me.avatar}">`:(S.me?.name[0]||ti('user',14));
  const curFont=localStorage.getItem('tes3FontSize')||'15';
  const curTheme=normalizeThemeChoice(localStorage.getItem('tes3Theme')||APP_CFG.defaultTheme||'truecolor');
  const curLang=(window.I18N?I18N.choice():'auto');
  const curPop=localStorage.getItem('tes3PopupNotif')!=='0'?'1':'0';
  const statusMap={
    online:{label:t('presence.online'),dot:'🟢'},
    away:{label:t('presence.idle'),dot:'🟡'},
    dnd:{label:t('presence.dnd'),dot:'🔴'},
    invisible:{label:t('presence.invisible'),dot:'⚪'}
  };
  const statusView=statusMap[S.myStatus||'online']||statusMap.online;

  showModal(`
    <div class="settings-shell">
      <div class="settings-hero">
        <div class="settings-hero-copy">
          <div class="settings-kicker">${ti('gear',14)} ${t('settings.profileKicker')}</div>
          <h2>${t('settings.title')}</h2>
          <div class="sub">${t('settings.subtitle')}</div>
        </div>

        <div class="settings-profile-card">
          <div class="av-upload settings-avatar-wrap">
            <div class="av-preview settings-avatar" id="myAvPrev" onclick="q('myAvFile').click()">${av}<div class="av-ov">${ti('camera',14)}</div></div>
            <input type="file" id="myAvFile" accept="image/*" style="display:none" onchange="uploadMyAvatar(this)">
          </div>
          <div class="settings-profile-name">${esc(S.me?.name||'')}</div>
          <div class="settings-profile-meta">${statusView.dot} ${statusView.label}</div>
          <div class="settings-profile-badges">
            <span class="settings-badge">${ti('shield',12)} ${S.me?.validated?t('settings.verified'):t('settings.notVerified')}</span>
            <span class="settings-badge">${ti('palette',12)} ${curTheme==='vk'?t('settings.themeWhite'):curTheme==='truecolor'?t('settings.themeBlack'):'Discord'}</span>
          </div>
        </div>
      </div>

      <div class="settings-grid">
        <section class="settings-card">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('user',15)} ${t('settings.cardAccount')}</div>
            <div class="settings-card-sub">${t('settings.cardAccountSub')}</div>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.name')}</label>
            <div class="fi settings-static">${esc(S.me?.name||'')}</div>
          </div>

          <div class="fg">
            <label class="fl">${t('common.status')}</label>
            <select class="fi" id="myStatusSel">
              <option value="online" ${S.myStatus==='online'?'selected':''}>🟢 ${t('presence.online')}</option>
              <option value="away" ${S.myStatus==='away'?'selected':''}>🟡 ${t('presence.idle')}</option>
              <option value="dnd" ${S.myStatus==='dnd'?'selected':''}>🔴 ${t('presence.dnd')}</option>
              <option value="invisible" ${S.myStatus==='invisible'?'selected':''}>⚪ ${t('presence.invisible')}</option>
            </select>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.notifSound')}</label>
            <select class="fi" id="soundPref" onchange="setSoundPref(this.value)">
              <option value="0" ${localStorage.getItem('soundMute')!=='1'?'selected':''}>${ti('bellOn',13)} ${t('settings.soundOn')}</option>
              <option value="1" ${localStorage.getItem('soundMute')==='1'?'selected':''}>${ti('bellOff',13)} ${t('settings.soundOff')}</option>
            </select>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.popupMessages')}</label>
            <select class="fi" id="popupNotifPref" onchange="setPopupNotifPref(this.value)">
              <option value="1" ${curPop==='1'?'selected':''}>${t('settings.enabled')}</option>
              <option value="0" ${curPop==='0'?'selected':''}>${t('settings.disabled')}</option>
            </select>
          </div>

          <button class="btn btn-ghost btn-full settings-soft-btn" onclick="showDmPrivacySettings(0,'')">${ti('gear',14)} ${t('ui.dmPrivacy')}</button>
        </section>

        <section class="settings-card">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('palette',15)} ${t('settings.cardAppearance')}</div>
            <div class="settings-card-sub">${t('settings.cardAppearanceSub')}</div>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.textSize')}</label>
            <select class="fi" onchange="setFontSizeLive(this.value)">
              <option value="13" ${curFont==='13'?'selected':''}>${t('settings.fontSmall')}</option>
              <option value="15" ${curFont==='15'?'selected':''}>${t('settings.fontNormal')}</option>
              <option value="16" ${curFont==='16'?'selected':''}>${t('settings.fontLarge')}</option>
              <option value="18" ${curFont==='18'?'selected':''}>${t('settings.fontXLarge')}</option>
            </select>
          </div>

          <div class="fg">
            <label class="fl">${t('settings.theme')}</label>
            <select class="fi" onchange="setThemeLive(this.value)">
              <option value="vk" ${curTheme==='vk'?'selected':''}>${t('settings.themeWhite')}</option>
              <option value="discord" ${curTheme==='discord'?'selected':''}>Discord</option>
              <option value="truecolor" ${curTheme==='truecolor'?'selected':''}>${t('settings.themeBlack')}</option>
            </select>
          </div>

          <div class="fg settings-effects-only" data-desktop-only="1">
            <label class="settings-toggle-row" for="dynBgToggle">
              <span class="settings-toggle-copy">
                <span class="settings-toggle-title">${tf('settings.dynBg','Динамический фон')}</span>
                <span class="settings-toggle-sub">${tf('settings.dynBgHint','Плавные цветные пятна за интерфейсом')}</span>
              </span>
              <span class="settings-switch">
                <input type="checkbox" id="dynBgToggle" ${document.body.classList.contains('dynbg-off')?'':'checked'} onchange="toggleDynBg(this.checked)">
                <span class="settings-switch-track"></span>
              </span>
            </label>
          </div>

          <div class="fg">
            <label class="fl" data-i18n="lang.language">Язык</label>
            <select class="fi" data-lang-selector onchange="setLangLive(this.value)">
              <option value="auto" ${curLang==='auto'?'selected':''}>${I18N.t('lang.auto')}${window.I18N?' ('+I18N.t('lang.'+I18N.lang)+')':''}</option>
              <option value="en" ${curLang==='en'?'selected':''}>English</option>
              <option value="ru" ${curLang==='ru'?'selected':''}>Русский</option>
              <option value="de" ${curLang==='de'?'selected':''}>Deutsch</option>
              <option value="fr" ${curLang==='fr'?'selected':''}>Français</option>
            </select>
          </div>

          <div class="settings-theme-note">
            <div class="settings-theme-dot"></div>
            <span>${t('settings.themeNote')}</span>
          </div>

          <div class="settings-actions-row">
            <button class="btn btn-gold" onclick="saveMyProfile()">${t('common.save')}</button>
            <button class="btn btn-ghost" onclick="closeModal()">${t('common.cancel')}</button>
          </div>
        </section>
      </div>

      <details class="settings-accordion">
        <summary>${ti('lock',14)} ${t('settings.security')}</summary>
        <div class="settings-accordion-body">
          <div class="fg"><label class="fl">${t('settings.currentPassword')}</label><input class="fi" type="password" id="oldPassInp"></div>
          <div class="fg"><label class="fl">${t('settings.newPassword')}</label><input class="fi" type="password" id="newPassInp"></div>
          <div class="fg"><label class="fl">${t('settings.repeatPassword')}</label><input class="fi" type="password" id="newPass2Inp"></div>
          <button class="btn btn-gold btn-full" onclick="changeMyPassword()">${t('settings.changePassword')}</button>
        </div>
      </details>

      <div class="settings-bottom-grid">
        <button class="btn btn-ghost btn-full settings-soft-btn" onclick="showBlacklist()">${ti("ban",14)} ${t('settings.blacklist')}</button>
        <button class="btn btn-red btn-full" onclick="robustLogout(event)">${ti('logout',14)} ${t('settings.logout')}</button>
      </div>
    </div>
  `);

  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
}
window.uploadMyAvatar=window.uploadMyAvatar=async function(inp){const file=inp.files[0];if(!file) return;const mime=file.type||'image/jpeg';const rd=new FileReader();rd.onload=async ev=>{const r=await api({action:'update_avatar',image:ev.target.result,mime});if(!r.ok){toast(r.error,'err');return;}if(S.me) S.me.avatar=r.avatar;updateUserPanel();const prev=q('myAvPrev');if(prev) prev.innerHTML=`<img src="${r.avatar}"><div class="av-ov">${ti('camera',14)}</div>`;toast(t('toast.avatarUpdated'),'ok');const sess=JSON.parse(localStorage.getItem('sess')||'{}');if(sess.user){sess.user.avatar=r.avatar;localStorage.setItem('sess',JSON.stringify(sess));}};rd.readAsDataURL(file);};
window.saveMyProfile=async function(){const st=q('myStatusSel')?.value||'online';S.myStatus=st;updateStatusUI(st);await api({action:'set_status',status:st});closeModal();toast(t('toast.saved'),'ok');};
window.setSoundPref=function(v){localStorage.setItem('soundMute',v);};
window.setPopupNotifPref=function(v){localStorage.setItem('tes3PopupNotif',v==='0'?'0':'1');};

function normalizeThemeChoice(v){
  const allowed=['vk','discord','truecolor'];
  if(allowed.includes(v)) return v;
  // Fall back to the configured default theme (dark trueCORD), not the white VK theme.
  const def=(typeof APP_CFG!=='undefined'&&APP_CFG.defaultTheme)?APP_CFG.defaultTheme:'truecolor';
  return allowed.includes(def)?def:'truecolor';
}

window.showDmPrivacyForCurrent=function(){
  if(!S.dmUid) return;
  const fallbackName=q('hdrName')?.textContent||t('common.user');
  const u=S.allUsers.find(x=>x.id===S.dmUid)||{name:fallbackName};
  showDmPrivacySettings(S.dmUid,u.name||fallbackName);
};
function privacySelectHtml(id,val,global=false){
  const cur=val==null?'inherit':String(val);
  if(global){
    return `<select class="fi" id="${id}"><option value="1" ${cur==='1'?'selected':''}>${t('privacy.allowed')}</option><option value="0" ${cur==='0'?'selected':''}>${t('privacy.denied')}</option></select>`;
  }
  return `<select class="fi" id="${id}"><option value="inherit" ${cur==='inherit'?'selected':''}>${t('privacy.inherit')}</option><option value="1" ${cur==='1'?'selected':''}>${t('privacy.allowed')}</option><option value="0" ${cur==='0'?'selected':''}>${t('privacy.denied')}</option></select>`;
}
window.showDmPrivacySettings=async function(otherUserId=0,otherName=''){
  const r=await api({action:'dm_privacy_get',otherUserId:otherUserId||0});
  if(!r.ok){toast(r.error||t('privacy.error'),'err');return;}
  const isGlobal=!otherUserId;
  const data=isGlobal?(r.global||{}):(r.user||{});
  const resolvedName=otherName||r.other?.name||t('common.user');
  const title=isGlobal?t('privacy.titleGlobal'):t('privacy.titleFor',{name:resolvedName});
  const hint=isGlobal?t('privacy.globalHint'):t('privacy.personalHint');
  const isSuper=!!r.other?.isSuperAdmin;
  showModal(`
    <div class="privacy-modal-shell">
      <h2>${ti('gear',18)} ${title}</h2>
      <p class="modal-sub">${hint}</p>
      ${isSuper?`<div class="privacy-alert">${t('privacy.superHint')}</div>`:''}
      <div class="privacy-grid">
        <div class="fg"><label class="fl">${t('privacy.directMessages')}</label>${privacySelectHtml('privDm',data.allow_dm,isGlobal)}</div>
        <div class="fg"><label class="fl">${t('privacy.audioCalls')}</label>${privacySelectHtml('privAudio',data.allow_audio,isGlobal)}</div>
        <div class="fg"><label class="fl">${t('privacy.videoCalls')}</label>${privacySelectHtml('privVideo',data.allow_video,isGlobal)}</div>
      </div>
      <div class="settings-actions-row">
        <button class="btn btn-gold" onclick="saveDmPrivacySettings(${otherUserId||0})" ${isSuper&&!isGlobal?'disabled style="opacity:.5;pointer-events:none"':''}>${t('common.save')}</button>
        <button class="btn btn-ghost" onclick="closeModal()">${t('common.cancel')}</button>
      </div>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('privacy-modal');
};
window.saveDmPrivacySettings=async function(otherUserId=0){
  const r=await api({
    action:'dm_privacy_save',
    otherUserId:otherUserId||0,
    allowDm:q('privDm')?.value,
    allowAudio:q('privAudio')?.value,
    allowVideo:q('privVideo')?.value
  });
  if(!r.ok){toast(r.error||t('privacy.saveFailed'),'err');return;}
  toast(t('privacy.savedToast'),'ok');
  closeModal();
};
window.setFontSizeLive=function(v){
  localStorage.setItem('tes3FontSize',v);
  const n=parseInt(v);
  // Remove old sheet
  const old=document.getElementById('fontScaleSheet');
  if(old) old.remove();
  // Don't inject anything for default size
  if(n===15) return;
  const style=document.createElement('style');
  style.id='fontScaleSheet';
  style.textContent=`
    .msg-text{font-size:${n}px!important}
    .ch-name{font-size:${Math.max(11,n-2)}px!important}
    .vr-name{font-size:${Math.max(11,n-2)}px!important}
    .mb-name{font-size:${Math.max(11,n-2)}px!important}
    .dm-name{font-size:${Math.max(11,n-2)}px!important}
    .hdr-ch-name{font-size:${Math.max(12,n-1)}px!important}
    .hdr-topic{font-size:${Math.max(11,n-2)}px!important}
    .ctx-item{font-size:${Math.max(12,n-1)}px!important}
    .mention-item{font-size:${n}px!important}
    .msg-author{font-size:${Math.max(12,n-1)}px!important}
    .msg-ts{font-size:${Math.max(10,n-4)}px!important}
    .msg-system{font-size:${Math.max(11,n-2)}px!important}
    .fi{font-size:${Math.max(12,n-1)}px!important}
    .modal h2{font-size:${Math.max(15,n+2)}px!important}
    .modal .sub{font-size:${Math.max(11,n-2)}px!important}
    #msgInput{font-size:${n}px!important}
    #dmInput{font-size:${n}px!important}
    .toast{font-size:${Math.max(11,n-2)}px!important}
    .np-text{font-size:${Math.max(12,n-1)}px!important}
    .voice-part-item{font-size:${Math.max(11,n-2)}px!important}
    .ch-category{font-size:${Math.max(9,n-6)}px!important}
    body{font-size:${n}px!important}
  `;
  document.head.appendChild(style);
};
window.syncLangSelectors=function(){
  try{
    const choice=window.I18N?.choice?.()||'auto';
    document.querySelectorAll('[data-lang-selector]').forEach(sel=>{ if(sel&&sel.value!==choice) sel.value=choice; });
  }catch(e){}
};
window.setLangLive=function(v){
  if(!window.I18N) return;
  const applied=I18N.set(v, true); // сохраняет выбор ('auto' тоже) и переводит DOM
  // Перерисовываем динамические части, чтобы перевод подхватился сразу
  try{ syncLangSelectors(); }catch(e){}
  try{ refreshStatusMetaLabels(); }catch(e){}
  try{ if(window.updateUserPanel) updateUserPanel(); }catch(e){}
  try{ if(typeof renderChannels==='function'&&S.channels) renderChannels(); }catch(e){}
  try{ if(typeof renderSrvBar==='function') renderSrvBar(); }catch(e){}
  try{ if(typeof applyVoiceStageI18n==='function') applyVoiceStageI18n(); }catch(e){}
  try{ if(typeof renderVoiceWorkspace==='function') renderVoiceWorkspace(); }catch(e){}
  return applied;
};
window.toggleDynBg=function(on){
  document.body.classList.toggle('dynbg-off',!on);
  localStorage.setItem('tes3DynBg',on?'1':'0');
};
window.toggleLiveScene=function(on){
  document.body.classList.toggle('livescene-on',!!on);
  localStorage.setItem('tes3LiveScene',on?'1':'0');
  const sel=document.getElementById('liveSceneSelect');
  if(sel) sel.style.display=on?'':'none';
};
window.setLiveScene=function(scene){
  const sea=(scene==='sea');
  document.body.classList.toggle('livescene-sea',sea);
  localStorage.setItem('tes3LiveSceneType',sea?'sea':'forest');
};
window.setThemeLive=function(v){
  localStorage.setItem('tes3Theme',v);
  const r=document.documentElement.style;
  // Remove all custom theme properties first
  ['--bg0','--bg1','--bg2','--bg3','--bg4','--bg5','--text','--text2','--text3','--text4','--gold','--gold2','--gold3','--gold-glow','--gold-dim','--border','--border2','--green','--red','--red2','--yellow','--blue','--status-online','--status-away','--status-dnd','--status-invisible','--status-offline','--glass-bg','--glass-bg2','--glass-border','--glass-border-gold','--font-heading'].forEach(p=>r.removeProperty(p));
  document.documentElement.removeAttribute('data-theme');
  document.body?.removeAttribute('data-theme');
  if(v==='truecolor'){
    document.documentElement.setAttribute('data-theme','truecolor');
    document.body?.setAttribute('data-theme','truecolor');
    r.setProperty('--bg0','#090d16');r.setProperty('--bg1','#101827');r.setProperty('--bg2','#151f31');r.setProperty('--bg3','#1a2740');r.setProperty('--bg4','#213252');r.setProperty('--bg5','#2b4270');
    r.setProperty('--text','#eef3ff');r.setProperty('--text2','#c7d3eb');r.setProperty('--text3','#8ea0c2');r.setProperty('--text4','#5e7090');
    r.setProperty('--gold','#2d7dff');r.setProperty('--gold2','#6d8bff');r.setProperty('--gold3','#98c2ff');r.setProperty('--gold-glow','rgba(45,125,255,.30)');r.setProperty('--gold-dim','rgba(45,125,255,.14)');
    r.setProperty('--green','#43b581');r.setProperty('--red','#ed4245');r.setProperty('--red2','#ff5f62');r.setProperty('--yellow','#f0b232');r.setProperty('--blue','#2d7dff');
    r.setProperty('--border','#20304f');r.setProperty('--border2','#2a3d60');
    r.setProperty('--glass-bg','rgba(18,28,46,.78)');r.setProperty('--glass-bg2','rgba(15,24,40,.90)');
    r.setProperty('--glass-border','rgba(255,255,255,.08)');r.setProperty('--glass-border-gold','rgba(45,125,255,.22)');
    r.setProperty('--status-online','#43b581');r.setProperty('--status-away','#f0b232');r.setProperty('--status-dnd','#ed4245');r.setProperty('--status-invisible','#6e7f9f');r.setProperty('--status-offline','#4d5a73');
    r.setProperty('--font-heading','var(--font-body)');
  }
  else if(v==='vk'){
    document.documentElement.setAttribute('data-theme','vk');
    document.body?.setAttribute('data-theme','vk');
    r.setProperty('--bg0','#edeef0');r.setProperty('--bg1','#f5f7fa');r.setProperty('--bg2','#ffffff');r.setProperty('--bg3','#f0f2f5');r.setProperty('--bg4','#e5ebf1');r.setProperty('--bg5','#dce1e6');
    r.setProperty('--text','#1f2328');r.setProperty('--text2','#4f5b67');r.setProperty('--text3','#6f7b88');r.setProperty('--text4','#97a3af');
    r.setProperty('--gold','#0077ff');r.setProperty('--gold2','#006be6');r.setProperty('--gold3','#99c8ff');r.setProperty('--gold-glow','rgba(0,119,255,.16)');r.setProperty('--gold-dim','rgba(0,119,255,.08)');
    r.setProperty('--green','#4bb34b');r.setProperty('--red','#e64646');r.setProperty('--red2','#e64646');r.setProperty('--yellow','#ffa000');r.setProperty('--blue','#0077ff');
    r.setProperty('--border','#dce1e6');r.setProperty('--border2','#c9d1da');
    r.setProperty('--glass-bg','rgba(255,255,255,0.78)');r.setProperty('--glass-bg2','rgba(255,255,255,0.92)');
    r.setProperty('--glass-border','rgba(0,0,0,0.08)');r.setProperty('--glass-border-gold','rgba(0,119,255,0.22)');
    r.setProperty('--status-online','#4bb34b');r.setProperty('--status-away','#ffa000');r.setProperty('--status-dnd','#e64646');
  }
  else if(v==='midnight'){r.setProperty('--bg0','#050508');r.setProperty('--bg1','#0a0a10');r.setProperty('--bg2','#101018');r.setProperty('--bg3','#18182a');r.setProperty('--bg4','#202038');r.setProperty('--bg5','#2a2a44');r.setProperty('--text','#c8c8e0');r.setProperty('--text2','#8888aa');r.setProperty('--text3','#5a5a7a');r.setProperty('--gold','#8a9fd4');r.setProperty('--gold2','#6a7fb4');r.setProperty('--gold3','#4a5f94');r.setProperty('--border','#1a1a2e');r.setProperty('--border2','#222240');}
  else if(v==='sepia'){r.setProperty('--bg0','#1a150e');r.setProperty('--bg1','#201a12');r.setProperty('--bg2','#28201a');r.setProperty('--bg3','#332a1e');r.setProperty('--bg4','#3e3424');r.setProperty('--bg5','#4a3e2c');r.setProperty('--text','#e8d8c0');r.setProperty('--text2','#c0a880');r.setProperty('--text3','#8a7450');r.setProperty('--gold','#d4a050');r.setProperty('--gold2','#b88838');r.setProperty('--gold3','#8a6628');r.setProperty('--border','#302418');r.setProperty('--border2','#3a2e20');}
  else if(v==='amoled'){r.setProperty('--bg0','#000000');r.setProperty('--bg1','#000000');r.setProperty('--bg2','#0a0a08');r.setProperty('--bg3','#141410');r.setProperty('--bg4','#1e1e18');r.setProperty('--bg5','#282820');r.setProperty('--text','#e0d0b0');r.setProperty('--text2','#a08860');r.setProperty('--text3','#685030');r.setProperty('--gold','#c9aa71');r.setProperty('--gold2','#a8884e');r.setProperty('--gold3','#7a6032');r.setProperty('--border','#1a1810');r.setProperty('--border2','#222018');}
  else if(v==='discord'){
    document.documentElement.setAttribute('data-theme','discord');
    r.setProperty('--bg0','#1e1f22');r.setProperty('--bg1','#2b2d31');r.setProperty('--bg2','#313338');r.setProperty('--bg3','#383a40');r.setProperty('--bg4','#404249');r.setProperty('--bg5','#4e5058');
    r.setProperty('--text','#f2f3f5');r.setProperty('--text2','#b5bac1');r.setProperty('--text3','#6d6f78');
    r.setProperty('--gold','#5865f2');r.setProperty('--gold2','#4752c4');r.setProperty('--gold3','#3c45a5');
    r.setProperty('--green','#23a55a');r.setProperty('--red','#da373c');r.setProperty('--red2','#ed4245');r.setProperty('--blue','#5865f2');
    r.setProperty('--border','#1e1f22');r.setProperty('--border2','#2b2d31');
    r.setProperty('--glass-bg','rgba(30,31,34,0.75)');r.setProperty('--glass-bg2','rgba(43,45,49,0.85)');
    r.setProperty('--glass-border','rgba(255,255,255,0.06)');r.setProperty('--glass-border-gold','rgba(88,101,242,0.25)');
    r.setProperty('--status-online','#23a55a');r.setProperty('--status-away','#f0b232');r.setProperty('--status-dnd','#ed4245');
  }
  else if(v==='telegram'){
    document.documentElement.setAttribute('data-theme','telegram');
    r.setProperty('--bg0','#0e1621');r.setProperty('--bg1','#17212b');r.setProperty('--bg2','#1b2836');r.setProperty('--bg3','#242f3d');r.setProperty('--bg4','#2b3945');r.setProperty('--bg5','#3e4c5a');
    r.setProperty('--text','#f5f5f5');r.setProperty('--text2','#8b9bab');r.setProperty('--text3','#5b6b7b');
    r.setProperty('--gold','#6ab2f2');r.setProperty('--gold2','#4a9ae0');r.setProperty('--gold3','#3a7abd');
    r.setProperty('--green','#4fae4e');r.setProperty('--red','#e05d5d');r.setProperty('--red2','#ef5350');r.setProperty('--blue','#6ab2f2');
    r.setProperty('--border','#0e1621');r.setProperty('--border2','#17212b');
    r.setProperty('--glass-bg','rgba(14,22,33,0.78)');r.setProperty('--glass-bg2','rgba(23,33,43,0.88)');
    r.setProperty('--glass-border','rgba(255,255,255,0.06)');r.setProperty('--glass-border-gold','rgba(106,178,242,0.2)');
    r.setProperty('--status-online','#4fae4e');r.setProperty('--status-away','#e6a817');r.setProperty('--status-dnd','#ef5350');
  }
  setTimeout(broadcastMiniAppTheme,0);
  try{ syncStatusBarColor(); }catch(e){}
};
// Красим системный статус-бар (Android Chrome / PWA) в цвет фона текущей темы.
// theme-color раньше был статичным (синий) и не менялся под тему — теперь читаем реальный --bg0.
window.syncStatusBarColor=function(){
  let bg='';
  try{ bg=getComputedStyle(document.documentElement).getPropertyValue('--bg0').trim(); }catch(e){}
  if(!bg){ try{ bg=getComputedStyle(document.body).getPropertyValue('--bg0').trim(); }catch(e){} }
  if(!bg) return;
  let m=document.querySelector('meta[name="theme-color"]');
  if(!m){ m=document.createElement('meta'); m.setAttribute('name','theme-color'); document.head.appendChild(m); }
  m.setAttribute('content',bg);
};
window.changeMyPassword=async function(){
  const o=q('oldPassInp')?.value,n=q('newPassInp')?.value,n2=q('newPass2Inp')?.value;
  if(!o){toast(t('password.enterCurrent'),'err');return;}
  if(!n||n.length<APP_CFG.passwordMinLength){toast(`Минимум ${APP_CFG.passwordMinLength} символов`,'err');return;}
  if(n!==n2){toast(t('password.mismatch'),'err');return;}
  const r=await api({action:'change_password',oldPassword:o,newPassword:n});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('password.changed'),'ok');q('oldPassInp').value='';q('newPassInp').value='';q('newPass2Inp').value='';
};

function showUserProfile(uid){
  const u=S.members.find(x=>x.id===uid)||S.allUsers.find(x=>x.id===uid)||{id:uid,name:t('common.user'),avatar:'',role:'member',status:'offline',validated:0,globalRole:''};
  const profileRolesBoxId='profileRolesBox_'+uid+'_'+Date.now();
  const isMe=uid===S.me?.id;
  const isSuperGlobal=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';
  const canMod=isMod(S.myRole)||isSuperGlobal;
  const canAdmin=isAdmin2(S.myRole)||isSuperGlobal;
  const online=S.onlineUsers.find(x=>x.id===uid);
  const st=online?.status||'offline';
  const stMeta=STATUS_META[st]||STATUS_META.offline;
  const avContent=u.avatar&&u.avatar.startsWith('http')?`<img src="${esc(u.avatar)}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`:avatarFallbackHtml(u.name||t('common.user'),u.id);
  const roleIcon={owner:ti('star',14),admin:ti('check',14),moderator:`<svg viewBox="0 0 24 24" width="14" height="14"><line x1="5" y1="19" x2="19" y2="5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="15 5 19 5 19 9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`};
  const roleLabel=u.globalRole==='super_admin'?t('roles.superAdmin'):u.globalRole==='project_admin'?t('roles.projectAdmin'):u.role==='owner'?t('roles.owner'):u.role==='admin'?t('roles.admin'):u.role==='moderator'?t('roles.moderator'):t('roles.member');
  const roleTone=u.globalRole==='super_admin'?'#ff9f43':u.globalRole==='project_admin'?'#e67e22':u.role==='owner'?'var(--gold)':u.role==='admin'?'var(--blue)':u.role==='moderator'?'#8b6fc4':'var(--text3)';
  const roleBadge=`<span style="color:${roleTone};font-size:12px;display:inline-flex;align-items:center;gap:4px;justify-content:center">${roleIcon[u.role]||''} ${roleLabel}</span>`;
  const roleColor=u.roleColor?`<span class="role-badge" style="background:${esc(u.roleColor)}22;color:${esc(u.roleColor)};border:1px solid ${esc(u.roleColor)}44">●</span>`:'';
  const primaryActions=[];
  if(!isMe&&canStartDmUser(u)) primaryActions.push(`<button class="btn btn-gold btn-full" onclick="openDmConv(${u.id},'${esc(u.name)}','${esc(u.avatar||'')}');closeModal()">${ti('chat',14)} ${t('profile.message')}</button>`);
  if(!isMe&&canStartDmUser(u)) primaryActions.push(`<button class="btn btn-ghost btn-full" onclick="startCallFromProfile(${u.id},'${esc(u.name)}','${esc(u.avatar||'')}')">${ti('phone',14)} ${t('profile.call')}</button>`);
  if(canAdmin&&!isMe) primaryActions.push(`<button class="btn btn-ghost btn-full" onclick="showAssignRoleModal(${u.id})">${ti('grid',14)} ${t('profile.assignRole')}</button>`);
  if(isMe) primaryActions.push(`<button class="btn btn-ghost btn-full" onclick="closeModal();showMyProfile()">${ti('gear',14)} ${t('common.editProfile')}</button>`);
  const modActions=[];
  if(canMod&&!isMe){
    if(!u.validated) modActions.push(`<button class="btn btn-ghost" onclick="validateUser(${u.id})">✓ ${t('common.verify')}</button>`);
    else if(isSuperGlobal) modActions.push(`<button class="btn btn-ghost" style="color:var(--red2)" onclick="unvalidateUser(${u.id})">✗ ${t('common.unverify')}</button>`);
    if(canAdmin){
      modActions.push(`<button class="btn btn-ghost" onclick="setMemberRole(${u.id},'moderator')">${roleIcon.moderator} ${t('roles.moderator')}</button>`);
      modActions.push(`<button class="btn btn-ghost" onclick="setMemberRole(${u.id},'member')">${ti('user',14)} ${t('common.remove')}</button>`);
    }
    modActions.push(`<button class="btn btn-ghost" onclick="muteUserModal(${u.id},'${esc(u.name)}')">${ti('speakerMuted',14)} ${t('admin.mute')}</button>`);
    modActions.push(`<button class="btn btn-red" onclick="kickUser(${u.id})">${ti('kick',14)} ${t('admin.kick')}</button>`);
  }
  showModal(`
    <div class="profile-modal-shell">
      <div class="profile-head">
        <div style="width:92px;height:92px;border-radius:50%;background:var(--bg3);margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-size:38px;overflow:hidden;border:2px solid var(--border2)">${avContent}</div>
        <div style="font-size:20px;font-weight:800;font-family:var(--font-heading);${u.roleColor?'color:'+esc(u.roleColor):''}">${esc(u.name)}${roleColor}</div>
        <div style="margin-top:6px">${u.validated?`<span class="val-badge">✓ ${t('profile.verified')}</span>`:`<span class="val-badge unval-badge">✗ ${t('profile.notVerified')}</span>`}</div>
        <div style="font-size:13px;color:${stMeta.color};margin-top:6px">${stMeta.icon} ${stMeta.label}</div>
        <div style="margin-top:6px">${roleBadge}</div>
        <div id="${profileRolesBoxId}" class="role-box" style="margin:14px auto 0;max-width:400px;text-align:left;background:var(--bg2);border:1px solid var(--border);border-radius:14px;padding:12px 14px">
          <div style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:8px;font-family:var(--font-heading)">${t('roles.serverRoles')}</div>
          <div style="font-size:12px;color:var(--text3)">${t('common.loadingDots')}</div>
        </div>
      </div>
      ${primaryActions.length?`<div class="profile-actions">${primaryActions.join('')}</div>`:''}
      ${modActions.length?`<div class="ctx-sep" style="margin:14px 0"></div><div class="profile-mod-actions">${modActions.join('')}</div>`:''}
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('profile-modal-v2');
  loadProfileServerRoles(uid, profileRolesBoxId);
}
async function loadProfileServerRoles(uid, boxId){
  const box=q(boxId);
  if(!box) return;
  if(!S.srvId){box.style.display='none';return;}
  try{
    const r=await api({action:'get_user_roles',serverId:S.srvId,targetId:uid});
    const boxNow=q(boxId);
    if(!boxNow) return;
    const roles=r.ok&&Array.isArray(r.roles)?r.roles:[];
    const roleChips=roles.length?roles.map(role=>`<span class="role-badge" style="margin:0 6px 6px 0;background:${esc(role.color)}22;color:${esc(role.color)};border:1px solid ${esc(role.color)}55">● ${esc(role.name)}</span>`).join(''):`<span style="font-size:12px;color:var(--text3)">${t('roles.noExtra')}</span>`;
    boxNow.innerHTML=`<div style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:8px;font-family:var(--font-heading)">${t('roles.serverRoles')}</div><div style="display:flex;flex-wrap:wrap;align-items:center">${roleChips}</div>`;
  }catch(e){
    const boxNow=q(boxId);
    if(boxNow) boxNow.innerHTML=`<div style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);margin-bottom:8px;font-family:var(--font-heading)">${t('roles.serverRoles')}</div><div style="font-size:12px;color:var(--red2)">${t('roles.loadFailed')}</div>`;
  }
}
window.startCallFromProfile=function(uid,name,avatar){closeModal();openDmConv(uid,name,avatar).then(()=>startDmCall());};
window.setMemberRole=async function(uid,role){const r=await api({action:'set_member_role',serverId:S.srvId,targetId:uid,role});if(!r.ok){toast(r.error,'err');return;}toast(t('roles.changedToast'),'ok');closeModal();await loadMembers(S.srvId);};

/* ─── MODAL ──────────────────────────────────── */
function showModal(html){
  exitFullscreenForWindowOpen();
  const modal=document.querySelector('#modalBg .modal');
  if(modal) modal.className='modal';
  q('modalBody').innerHTML=html;
  // Переводим динамически вставленный контент (data-i18n внутри модалок)
  try{ if(window.I18N) I18N.apply(q('modalBody')); }catch(e){}
  q('modalBg').classList.add('open');
}
function closeModal(){q('modalBg').classList.remove('open');q('modalBody').innerHTML='';}
function bgClose(e){if(e.target===q('modalBg')) closeModal();}

/* ─── LIGHTBOX ───────────────────────────────── */
let _lbList=[], _lbIdx=0;
function openLightbox(src,list,idx){
  // Backward compatible: openLightbox('url') opens a single image.
  if(Array.isArray(list)&&list.length){_lbList=list.slice();_lbIdx=Math.max(0,Math.min(idx||0,_lbList.length-1));}
  else{_lbList=[src];_lbIdx=0;}
  _lbRender();
  q('lightbox').classList.add('open');
}
function _lbRender(){
  const img=q('lbImg');if(img) img.src=_lbList[_lbIdx]||'';
  const multi=_lbList.length>1;
  const prev=q('lbPrev'),next=q('lbNext'),cnt=q('lbCounter');
  if(prev) prev.hidden=!multi;
  if(next) next.hidden=!multi;
  if(cnt){cnt.hidden=!multi;cnt.textContent=multi?`${_lbIdx+1} / ${_lbList.length}`:'';}
}
function lbStep(dir){
  if(_lbList.length<2) return;
  _lbIdx=(_lbIdx+dir+_lbList.length)%_lbList.length;
  _lbRender();
}
function openLightboxGallery(urls,idx){openLightbox(null,urls,idx);}
function closeLightbox(){q('lightbox').classList.remove('open');}
// Click on the dark backdrop (not the image/buttons) closes; keyboard nav.
document.addEventListener('click',function(e){
  const lb=document.getElementById('lightbox');
  if(lb&&lb.classList.contains('open')&&e.target===lb) closeLightbox();
});
document.addEventListener('keydown',function(e){
  const lb=document.getElementById('lightbox');
  if(!lb||!lb.classList.contains('open')) return;
  if(e.key==='Escape') closeLightbox();
  else if(e.key==='ArrowLeft') lbStep(-1);
  else if(e.key==='ArrowRight') lbStep(1);
});

/* ─── TOAST / NOTIF ──────────────────────────── */
// ══════════════════════════════════════════════════════════════
// DYNAMIC ISLAND — iPhone-style engine
// API:
//   DI.show(cfg)    — постоянная активность (плеер, стрим, звонок)
//   DI.notify(cfg)  — одиночное уведомление с автоскрытием
//   DI.toast(msg, type, dur) — системные тосты через island
//   DI.hide()       — скрыть
//   DI.expand()     — развернуть
//   DI.collapse()   — свернуть в пилюлю
//   DI.clearNotifs()
// cfg: { icon, text, title, sub, mode, dot, dotColor, actions[], onClick, onReply, avatar, avatarEmoji }
// ══════════════════════════════════════════════════════════════
const DI = (function(){
  const _pill    = ()=>document.getElementById('diPill');

  // Внутреннее состояние
  let _state = 'hidden';   // 'hidden' | 'compact' | 'notif' | 'expanded'
  let _current  = null;    // текущая «постоянная» активность (плеер, стрим)
  let _notifQueue = [];    // история уведомлений
  let _hideTimer  = null;
  let _animTimer  = null;
  let _progressRaf = null;

  // ── Утилиты ────────────────────────────────────────────────
  function _setState(s){
    const p=_pill();if(!p) return;
    p.classList.remove('di-hidden','di-compact','di-notif','di-expanded');
    if(s!=='hidden') p.classList.add('di-'+s);
    else p.classList.add('di-hidden');
    _state=s;
  }

  function _animateIn(targetState, onDone){
    const p=_pill();if(!p) return;
    // Отменяем любую текущую анимацию исчезновения
    p.classList.remove('di-anim-in','di-anim-out');
    if(_animTimer){clearTimeout(_animTimer);_animTimer=null;}
    if(_state==='hidden'){
      _setState(targetState);
      void p.offsetWidth; // reflow — запускаем новую анимацию
      p.classList.add('di-anim-in');
      _animTimer=setTimeout(()=>{
        _animTimer=null;
        p.classList.remove('di-anim-in');
        onDone&&onDone();
      },450);
    } else {
      _setState(targetState);
      onDone&&onDone();
    }
  }

  function _animateOut(onDone){
    const p=_pill();if(!p) return;
    p.classList.remove('di-anim-in','di-anim-out');
    p.classList.add('di-anim-out');
    if(_animTimer) clearTimeout(_animTimer);
    _animTimer=setTimeout(()=>{
      p.classList.remove('di-anim-out');
      _setState('hidden');
      onDone&&onDone();
    },300);
  }

  // ── Рендер compact ────────────────────────────────────────
  function _renderCompact(cfg){
    const icon=document.getElementById('diIcon');
    const text=document.getElementById('diText');
    const dot =document.getElementById('diDot');
    const mpl =document.getElementById('diMiniPlay');
    if(icon){ if(cfg.icon&&cfg.icon.includes('<svg')) icon.innerHTML=cfg.icon; else icon.textContent=cfg.icon||'●'; }
    if(text) text.textContent=cfg.text||'';
    if(dot){
      if(cfg.dot){
        dot.style.display='';
        dot.className='di-dot'+(cfg.dotColor==='red'?' di-dot-red':cfg.dotColor==='green'?' di-dot-green':'');
      } else dot.style.display='none';
    }
    if(mpl){
      if(cfg.mode==='player'){
        mpl.style.display='flex';
        mpl.innerHTML=FP.playing?`<svg viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" rx="1.5" fill="currentColor"/><rect x="14" y="4" width="4" height="16" rx="1.5" fill="currentColor"/></svg>`:`<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg>`;
        mpl.onclick=function(e){e.stopPropagation();fpTogglePlay();mpl.innerHTML=FP.playing?`<svg viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" rx="1.5" fill="currentColor"/><rect x="14" y="4" width="4" height="16" rx="1.5" fill="currentColor"/></svg>`:`<svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor"/></svg>`;};
      } else mpl.style.display='none';
    }
  }

  // ── Рендер expanded ──────────────────────────────────────
  function _renderExpanded(cfg){
    const ei=document.getElementById('diExpIcon');
    const et=document.getElementById('diExpTitle');
    const es=document.getElementById('diExpSub');
    const ec=document.getElementById('diExpControls');
    const ex=document.getElementById('diExpExtra');
    if(ei){ if(cfg.icon&&cfg.icon.includes('<svg')) ei.innerHTML=cfg.icon; else ei.textContent=cfg.icon||'●'; }
    if(et) et.textContent=cfg.title||cfg.text||'';
    if(es) es.textContent=cfg.sub||'';
    if(ec){
      ec.innerHTML='';
      (cfg.actions||[]).forEach(a=>{
        const b=document.createElement('button');
        b.className='di-ctrl-btn '+(a.style||'di-ctrl-secondary');
        // SVG в label → innerHTML, иначе textContent
        if(typeof a.label==='string'&&a.label.trimStart().startsWith('<'))
          b.innerHTML=a.label;
        else
          b.textContent=a.label||'';
        b.onclick=e=>{e.stopPropagation();a.action&&a.action();};
        ec.appendChild(b);
      });
    }
    if(ex){
      ex.innerHTML='';
      if(cfg.mode==='player'){
        const prog=document.createElement('div');
        prog.className='di-progress';
        prog.innerHTML='<div class="di-progress-bar" id="diProgressBar"></div>';
        prog.onclick=e=>{
          e.stopPropagation();
          if(!FP.audio||!FP.audio.duration) return;
          const r=prog.getBoundingClientRect();
          FP.audio.currentTime=(e.clientX-r.left)/r.width*FP.audio.duration;
        };
        ex.appendChild(prog);
        _startProgressRaf();
      }
      if((cfg.mode==='notifs'||!cfg.mode)&&_notifQueue.length){
        // Заголовок с кнопкой «Очистить все»
        const hdr=document.createElement('div');
        hdr.style.cssText='display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;';
        hdr.innerHTML=`<span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35)">Уведомления</span>`+
          `<button style="font-size:10px;padding:2px 8px;border-radius:99px;border:none;background:rgba(255,255,255,.1);color:rgba(255,255,255,.55);cursor:pointer;font-family:inherit;transition:.12s" id="diClearAllBtn">Очистить все</button>`;
        hdr.querySelector('#diClearAllBtn').onclick=e=>{
          e.stopPropagation();
          _notifQueue=[];
          api.collapse();
          const dot=document.getElementById('diDot');
          if(dot){dot.style.display='none';dot.className='di-dot';}
          // Также обновить badge колокольчика
          if(window._notifBell) window._notifBell.update();
        };
        ex.appendChild(hdr);
        const list=document.createElement('div');list.className='di-notif-list';
        _notifQueue.slice(0,10).forEach((n,idx)=>{
          const row=document.createElement('div');row.className='di-notif-item';
          row.style.position='relative';
          const avHtml=n.avatar&&n.avatar.startsWith('http')
            ?`<img src="${n.avatar}" alt="">`:(n.avatarEmoji||n.icon||ti('chat',14));
          row.innerHTML=`<div class="di-notif-av">${avHtml}</div>`+
            `<div class="di-notif-body">`+
            `<div class="di-notif-title">${esc(n.title||'')}</div>`+
            `<div class="di-notif-text">${esc((n.text||'').slice(0,70))}</div>`+
            `</div>`+
            (n.onReply?`<button class="di-notif-reply">↩</button>`:'') +
            `<button class="di-notif-del" data-idx="${idx}" title="Удалить" style="width:18px;height:18px;border-radius:50%;border:none;background:rgba(255,255,255,.08);color:rgba(255,255,255,.4);cursor:pointer;font-size:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:.12s;margin-left:4px">✕</button>`;
          row.querySelector('.di-notif-del').onclick=e=>{
            e.stopPropagation();
            const i=parseInt(e.target.dataset.idx);
            _notifQueue.splice(i,1);
            if(_notifQueue.length){ api.expand(); } else { api.collapse(); }
            if(window._notifBell) window._notifBell.update();
          };
          row.onclick=e=>{
            if(e.target.classList.contains('di-notif-reply')){
              e.stopPropagation();n.onReply&&n.onReply();return;
            }
            if(e.target.classList.contains('di-notif-del')) return;
            n.onClick&&n.onClick();api.collapse();
          };
          list.appendChild(row);
        });
        ex.appendChild(list);
      }
    }
  }

  function _startProgressRaf(){
    if(_progressRaf) cancelAnimationFrame(_progressRaf);
    (function tick(){
      const bar=document.getElementById('diProgressBar');
      if(!bar||_state!=='expanded'){_progressRaf=null;return;}
      if(FP.audio&&FP.audio.duration)
        bar.style.width=(FP.audio.currentTime/FP.audio.duration*100)+'%';
      _progressRaf=requestAnimationFrame(tick);
    })();
  }

  // ── Клик на пилюлю ───────────────────────────────────────
  function _setupClick(){
    const p=_pill();if(!p) return;
    p.onclick=function(e){
      if(_state==='expanded') api.collapse();
      else if(_state!=='hidden') api.expand();
    };
  }

  // ── Публичный API ────────────────────────────────────────
  const api={
    /**
     * show — постоянная активность (плеер, стрим, звонок)
     * остаётся пока не вызван hide()
     */
    show(cfg){
      if(_hideTimer){clearTimeout(_hideTimer);_hideTimer=null;}
      _current=cfg;
      _renderCompact(cfg);
      _setupClick();
      if(_state==='hidden') _animateIn('compact');
      else _setState('compact');
    },

    /**
     * notify — уведомление с автоскрытием и flash-анимацией
     * Если island занят плеером/стримом — показываем поверх как flash
     */
    notify(cfg){
      _notifQueue.unshift(cfg);
      if(_notifQueue.length>10) _notifQueue.length=10;

      const wasPlayer=_current&&(_current.mode==='player'||_current.mode==='stream'||_current.mode==='call');

      // Рендерим компактный notif
      _renderCompact({
        icon:  cfg.icon||ti('chat',14),
        text:  cfg.title||(cfg.text||'').slice(0,40)||'Новое сообщение',
        dot:   true,
        dotColor: cfg.dotColor||'',
        mode:  'notifs',
      });
      _setupClick();

      if(window._notifBell) window._notifBell.update();
      if(_state==='expanded'){
        // Если развёрнуто — просто обновляем список
        _renderExpanded(Object.assign({},_current||{},
          {mode:'notifs',icon:cfg.icon||ti('chat',14),title:cfg.title||'Уведомления',sub:''}));
        return;
      }

      // Flash: показать как 'notif' (шире), потом вернуться к compact/скрыться
      if(_state==='hidden'){
        _animateIn('notif',()=>{
          if(_hideTimer) clearTimeout(_hideTimer);
          _hideTimer=setTimeout(()=>{
            _hideTimer=null;
            if(_state==='expanded') return; // пользователь развернул — не трогаем
            if(wasPlayer&&_current){
              // Возврат к плееру: рендерим снова с правильным cfg
              _renderCompact(_current);
              _setState('compact');
            } else if(_state!=='hidden'){
              _animateOut(()=>{_current=null;});
            }
          },3500);
        });
      } else {
        // Уже видно — flash пилюлю и ждём
        _setState('notif');
        if(_hideTimer) clearTimeout(_hideTimer);
        _hideTimer=setTimeout(()=>{
          _hideTimer=null;
          if(_state==='expanded') return;
          if(wasPlayer&&_current){
            _renderCompact(_current);_setState('compact');
          } else if(_state!=='hidden'){
            _animateOut(()=>{_current=null;});
          }
        },3500);
      }
    },

    /**
     * toast — системные сообщения через island (вместо старых toast)
     * type: 'ok' | 'err' | 'info'
     */
    toast(msg, type='info', dur=3000){
      const SVG_OK  ='<svg viewBox="0 0 24 24" width="13" height="13"><polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      const SVG_ERR ='<svg viewBox="0 0 24 24" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>';
      const SVG_INFO='<svg viewBox="0 0 24 24" width="13" height="13"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8" fill="none"/><line x1="12" y1="8" x2="12" y2="8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><line x1="12" y1="12" x2="12" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
      const icons={ok:SVG_OK,err:SVG_ERR,info:SVG_INFO};
      const dotColors={ok:'green',err:'red',info:''};
      if(_hideTimer){clearTimeout(_hideTimer);_hideTimer=null;}
      _renderCompact({
        icon:  icons[type]||'ℹ',
        text:  msg,
        dot:   type!=='info',
        dotColor: dotColors[type]||'',
        mode:  'toast',
      });
      _setupClick();
      if(_state==='hidden') _animateIn('notif');
      else _setState('notif');
      _hideTimer=setTimeout(()=>{
        if(_current&&_state!=='expanded'){
          // Возврат к постоянной активности
          _renderCompact(_current);_setState('compact');
        } else if(_state!=='expanded'){
          _animateOut(()=>{_current=null;});
        }
        _hideTimer=null;
      },dur+300);
    },

    expand(){
      if(_state==='hidden'||(!_current&&!_notifQueue.length)) return;
      if(_hideTimer){clearTimeout(_hideTimer);_hideTimer=null;}
      const cfg=_current||{mode:'notifs',icon:ti('chat',14),title:'Уведомления',text:''};
      _renderExpanded(Object.assign({},cfg,{
        mode: cfg.mode||(_notifQueue.length?'notifs':''),
      }));
      // Устанавливаем expanded ПОСЛЕ рендера — браузер знает высоту содержимого
      void _pill()?.offsetWidth; // reflow
      _setState('expanded');
      // Клик на крестик
      const cl=document.getElementById('diExpClose');
      if(cl) cl.onclick=e=>{e.stopPropagation();api.collapse();};
    },

    collapse(){
      if(_progressRaf){cancelAnimationFrame(_progressRaf);_progressRaf=null;}
      if(_current){
        _renderCompact(_current);_setState('compact');
      } else if(_notifQueue.length){
        _setState('notif');
        if(_hideTimer) clearTimeout(_hideTimer);
        _hideTimer=setTimeout(()=>{
          _animateOut(()=>{_current=null;});_hideTimer=null;
        },2000);
      } else {
        _animateOut(()=>{_current=null;});
      }
    },

    hide(){
      if(_progressRaf){cancelAnimationFrame(_progressRaf);_progressRaf=null;}
      if(_hideTimer){clearTimeout(_hideTimer);_hideTimer=null;}
      if(_state!=='hidden') _animateOut(()=>{_current=null;});
      else _current=null;
    },

    clearNotifs(){
      _notifQueue=[];
      const dot=document.getElementById('diDot');
      if(dot){dot.style.display='none';dot.className='di-dot';}
      if(window._notifBell) window._notifBell.update();
    },

    update(patch){
      if(!_current) return;
      Object.assign(_current,patch);
      if(_state!=='expanded') _renderCompact(_current);
    },

    get mode(){return _current?.mode||(_notifQueue.length?'notifs':null);},
    get state(){return _state;},
  };

  // Инициализация
  document.addEventListener('DOMContentLoaded',()=>{
    _setState('hidden');
    _setupClick();
  });

  return api;
})();

// ── Интеграция Dynamic Island с плеером ──────────────────────
function diShowPlayer(){ /* deprecated — плеер использует только DI.notify */ }
function diHidePlayer(){
  if(DI.mode==='player') DI.hide();
}

// ── Интеграция Dynamic Island с трансляцией ──────────────────
function diShowStream(title){
  DI.show({
    icon:'🖥',
    text: title||'Трансляция',
    title:'Трансляция',
    sub:  title||'',
    mode: 'stream',
    dot:  true, dotColor:'red',
    actions:[
      {label:'⛶ Весь экран', action:()=>{voiceStageFullscreenStream();}, style:'di-ctrl-secondary'},
      {label:'▣ Окно',       action:()=>{voiceStageOpenFloatingStream();}, style:'di-ctrl-secondary'},
      {label:'🚪 Выйти',     action:closeStreamViewer,                style:'di-ctrl-danger'},
    ],
  });
}
function diHideStream(){
  if(DI.mode==='stream') DI.hide();
}

// ══════════════════════════════════════════════════════════════
// QUICK REPLY API
// QR.open({ id, title, sub, avatar, mode:'dm'|'channel',
//           targetId, load() }) — открыть окно
// QR.close(id)  — закрыть
// ══════════════════════════════════════════════════════════════
const QR = (function(){
  const _windows={};  // id -> {el, msgs, targetId, mode, ...}
  const _container=()=>document.getElementById('quickReplyContainer');

  function _buildWindow(cfg){
    const id=cfg.id;
    const el=document.createElement('div');
    el.className='qr-window';el.dataset.qrId=id;

    const avHtml=cfg.avatar&&cfg.avatar.startsWith('http')
      ?`<img src="${cfg.avatar}" alt="">`:esc(cfg.avatarEmoji||cfg.title?.[0]||ti('chat',14));

    el.innerHTML=`
      <div class="qr-header">
        <div class="qr-av">${avHtml}</div>
        <div style="flex:1;min-width:0">
          <div class="qr-title">${esc(cfg.title||'')}</div>
          ${cfg.sub?`<div class="qr-sub">${esc(cfg.sub)}</div>`:''}
        </div>
        <button class="qr-open-btn" data-qr-open="${id}" title="Открыть чат">↗</button>
        <button class="qr-close" data-qr-close="${id}" title="Закрыть">✕</button>
      </div>
      <div class="qr-msgs" id="qrMsgs_${id}"></div>
      <div class="qr-input-row">
        <textarea class="qr-textarea" id="qrInput_${id}" rows="1"
          placeholder="Написать…" maxlength="2000"></textarea>
        <button class="qr-send" data-qr-send="${id}" title="Отправить">➤</button>
      </div>`;

    // Events
    el.querySelector('[data-qr-close]').onclick=()=>QR.close(id);
    el.querySelector('[data-qr-open]').onclick=()=>{
      cfg.onOpen&&cfg.onOpen();QR.close(id);
    };
    el.querySelector('[data-qr-send]').onclick=()=>_send(id);
    const ta=el.querySelector(`#qrInput_${id}`);
    ta.addEventListener('keydown',e=>{
      if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();_send(id);}
    });
    ta.addEventListener('input',()=>{
      ta.style.height='auto';
      ta.style.height=Math.min(ta.scrollHeight,80)+'px';
    });

    return el;
  }

  function _appendMsg(id,m,mine){
    const list=document.getElementById('qrMsgs_'+id);if(!list) return;
    const row=document.createElement('div');
    row.className='qr-msg '+(mine?'qr-mine':'qr-other');
    const t=m.createdAt?fmtTime(m.createdAt):'';
    row.innerHTML=
      (mine?'':`<div class="qr-msg-name">${esc(m.name||'')}</div>`)+
      `<div class="qr-msg-text">${esc(m.text||'[файл]')}</div>`+
      (t?`<div class="qr-msg-time">${t}</div>`:'');
    list.appendChild(row);
    list.scrollTop=list.scrollHeight;
  }

  async function _send(id){
    const w=_windows[id];if(!w) return;
    const ta=document.getElementById('qrInput_'+id);if(!ta) return;
    const text=ta.value.trim();if(!text) return;
    ta.value='';ta.style.height='auto';
    let r;
    if(w.mode==='dm'){
      r=await api({action:'dm_send',toUserId:w.targetId,text});
      if(r.ok){_appendMsg(id,Object.assign({},r.msg,{name:S.me?.name}),true);S.lastDmId=r.msg.id;S.notifiedMsgIds.add(r.msg.id);}
    } else {
      r=await api({action:'send',channelId:w.targetId,text,image:'',replyTo:null});
      if(r.ok){_appendMsg(id,Object.assign({},r.msg,{name:S.me?.name}),true);S.notifiedMsgIds.add(r.msg.id);}
    }
    if(!r.ok) toast(r.error,'err');
  }

  async function _loadHistory(id){
    const w=_windows[id];if(!w) return;
    let msgs=[];
    if(w.mode==='dm'){
      const r=await api({action:'dm_messages',withUserId:w.targetId,limit:15});
      if(r.ok) msgs=r.messages||[];
    } else {
      const r=await api({action:'messages',channelId:w.targetId,since:0,limit:15});
      if(r.ok) msgs=r.messages||[];
    }
    msgs.forEach(m=>_appendMsg(id,m,m.userId===S.me?.id));
  }

  const pub={
    open(cfg){
      const id=cfg.id||('qr_'+Date.now());
      if(_windows[id]){
        // уже открыто — фокус на инпут
        const ta=document.getElementById('qrInput_'+id);if(ta) ta.focus();
        return;
      }
      const el=_buildWindow(Object.assign({id},cfg));
      _windows[id]={el,mode:cfg.mode,targetId:cfg.targetId,cfg};
      const c=_container();if(c){c.appendChild(el);c.style.pointerEvents='auto';}
      _loadHistory(id);
      const ta=document.getElementById('qrInput_'+id);if(ta) setTimeout(()=>ta.focus(),100);
    },
    close(id){
      const w=_windows[id];if(!w) return;
      w.el.style.opacity='0';w.el.style.transform='translateY(12px) scale(.95)';
      w.el.style.transition='opacity .2s,transform .2s';
      setTimeout(()=>{w.el.remove();delete _windows[id];
        const c=_container();if(c&&!Object.keys(_windows).length) c.style.pointerEvents='none';
      },200);
    },
    closeAll(){Object.keys(_windows).forEach(id=>pub.close(id));},
    // Добавить входящее сообщение в открытое окно
    injectMsg(targetId,mode,msg){
      const id=Object.keys(_windows).find(k=>{
        const w=_windows[k];
        return w.mode===mode&&w.targetId===targetId;
      });
      if(!id) return false;
      _appendMsg(id,msg,msg.userId===S.me?.id);
      return true;
    },
    isOpen(targetId,mode){
      return Object.values(_windows).some(w=>w.mode===mode&&w.targetId===targetId);
    },
  };
  return pub;
})();
window.QR=QR;
window.DI=DI;

// toast — теперь все системные тосты идут в Dynamic Island
// Старый #toastBox оставлен только как запасной вариант
function toast(msg,type='info',dur=3000,html=false){
  const plainMsg=msg.replace(/<[^>]+>/g,' ').replace(/\s+/g,' ').trim();
  DI.toast(plainMsg,type,dur);
}



// ══════════════════════════════════════════════════════════════
// MINI-APP THEME BRIDGE
// Мини-приложения могут получить текущую тему через getTheme
// и автоматически получать событие theme при открытии/смене темы.
// ══════════════════════════════════════════════════════════════
function miniAppThemeName(){
  return normalizeThemeChoice(localStorage.getItem('tes3Theme') || APP_CFG.defaultTheme || document.body?.getAttribute('data-theme') || 'truecolor');
}
function miniAppThemePayload(){
  const cs=getComputedStyle(document.documentElement);
  const keys=['--bg0','--bg1','--bg2','--bg3','--bg4','--bg5','--text','--text2','--text3','--gold','--gold2','--gold3','--border','--border2','--green','--red','--red2','--blue','--glass-bg','--glass-bg2','--glass-border','--glass-border-gold','--status-online','--status-away','--status-dnd'];
  const vars={};
  keys.forEach(k=>{ const v=cs.getPropertyValue(k).trim(); if(v) vars[k]=v; });
  return {ok:true, platform:'trueCORD', theme:miniAppThemeName(), vars};
}
function postMiniAppThemeToFrame(frame){
  try{ frame?.contentWindow?.postMessage({tes3:true,event:'theme',result:miniAppThemePayload()},'*'); }catch(e){}
}
function broadcastMiniAppTheme(){
  document.querySelectorAll('#miniAppContainer iframe').forEach(postMiniAppThemeToFrame);
}

// ══════════════════════════════════════════════════════════════
// trueCORD MINIAPP BRIDGE API
// Доступен мини-приложениям через:
//   window.parent.TES3API.method(params)   — прямой вызов
//   window.parent.postMessage({tes3:true, action, ...}, '*') — postMessage
// ══════════════════════════════════════════════════════════════
window.TES3API = (function(){
  function _curChId(){
    // Находим chId открытого мини-апп окна по фрейму
    const frames=Object.entries(MAM._windows||{});
    for(const [id,w] of frames){
      if(w.el) return parseInt(id);
    }
    return null;
  }

  const api_pub = {
    // ── Уведомления (Dynamic Island) ──────────────────────────
    notify(title, text, opts={}){
      DI.notify({
        icon: opts.icon||ti('puzzle',14),
        avatarEmoji: opts.avatarEmoji||title?.[0]||ti('puzzle',14),
        title, text,
        dotColor: opts.dotColor||'',
        onClick: opts.onClick||null,
      });
    },
    toast(message, type='info', duration=3000){
      DI.toast(message, type, duration);
    },

    // ── Отправить сообщение в канал от имени пользователя ────
    async sendMessage(channelId, text){
      if(!channelId||!text) return {ok:false,error:'channelId и text обязательны'};
      return await api({action:'send', channelId, text, image:'', replyTo:null});
    },

    // ── Получить последние сообщения канала ──────────────────
    async getMessages(channelId, limit=20){
      if(!channelId) return {ok:false,error:'channelId обязателен'};
      return await api({action:'messages', channelId, since:0, limit});
    },

    // ── Список участников текущего сервера ───────────────────
    getMembers(){
      return {ok:true, members: S.members.map(m=>({
        id:m.id, name:m.name, avatar:m.avatar||'',
        role:m.role, validated:!!m.validated,
        online: S.onlineUsers.some(u=>u.id===m.id),
        status: S.onlineUsers.find(u=>u.id===m.id)?.status||'offline',
      }))};
    },

    // ── Текущий пользователь ─────────────────────────────────
    getMe(){
      if(!S.me) return {ok:false,error:'Не авторизован'};
      return {ok:true, user:{
        id:S.me.id, name:S.me.name, avatar:S.me.avatar||'',
        validated:!!S.me.validated, globalRole:S.me.globalRole||'',
      }};
    },

    // ── Пригласить пользователя в игру / событие ─────────────
    // Отправляет системное сообщение в текущий канал с кнопкой
    async inviteToGame(gameName, payload={}){
      const chId = S.chId; if(!chId) return {ok:false,error:'Нет активного канала'};
      const invText = `[INVITE:${gameName}] ${JSON.stringify(payload)}`;
      return await api({action:'send', channelId:chId, text:invText, image:'', replyTo:null});
    },

    // ── Принять приглашение ───────────────────────────────────
    // Мини-апп парсит входящие сообщения через getMessages и
    // вызывает acceptInvite когда пользователь принял
    async acceptInvite(channelId, inviteMessageId){
      const text=`[ACCEPT:${inviteMessageId}]`;
      return await api({action:'send', channelId, text, image:'', replyTo:inviteMessageId});
    },

    // ── Хранилище мини-апп (ключ-значение, per-channel) ──────
    async storeGet(key){
      const chId=_curChId(); if(!chId) return {ok:false};
      return await api({action:'miniapp_store_get', channelId:chId, key});
    },
    async storeSet(key, value){
      const chId=_curChId(); if(!chId) return {ok:false};
      return await api({action:'miniapp_store_set', channelId:chId, key, value:JSON.stringify(value)});
    },
    async storeDelete(key){
      const chId=_curChId(); if(!chId) return {ok:false};
      return await api({action:'miniapp_store_del', channelId:chId, key});
    },

    // ── Realtime pub/sub через polling ───────────────────────
    // Мини-апп подписывается на события канала
    _subscribers: {},
    subscribe(channelId, callback){
      if(!this._subscribers[channelId]) this._subscribers[channelId]=[];
      this._subscribers[channelId].push(callback);
      return ()=>{ this._subscribers[channelId]=this._subscribers[channelId].filter(cb=>cb!==callback); };
    },
    _dispatch(channelId, msg){
      (this._subscribers[channelId]||[]).forEach(cb=>{ try{cb(msg);}catch(e){} });
    },

    // ── Закрыть текущее мини-приложение ──────────────────────
    close(){
      const chId=_curChId(); if(chId) MAM.close(chId);
    },
    minimize(){
      const chId=_curChId(); if(chId) MAM.toggleMin(chId);
    },

    // ── Открыть ЛС с пользователем ───────────────────────────
    openDM(userId, userName, avatar=''){
      openDmConv(userId, userName, avatar);
    },

    // ── Получить информацию о сервере ────────────────────────
    getServer(){
      const s=S.servers.find(x=>x.id===S.srvId);
      if(!s) return {ok:false};
      return {ok:true, server:{id:s.id, name:s.name, icon:s.icon, description:s.description}};
    },

    // ── Тема интерфейса ───────────────────────────────────────
    getTheme(){
      return miniAppThemePayload();
    },

    // ── Версия API ────────────────────────────────────────────
    version: '1.0.0',
    platform: 'trueCORD',
  };

  // postMessage bridge — для iframe без доступа к window.parent
  window.addEventListener('message', async(e)=>{
    if(!e.data||(!e.data.tes3&&!e.data.truecord)) return;
    const {action, callId} = e.data;
    let result = {ok:false, error:'unknown action'};
    try{
      switch(action){
        case 'notify':    api_pub.notify(e.data.title,e.data.text,e.data.opts||{}); result={ok:true}; break;
        case 'toast':     api_pub.toast(e.data.message,e.data.type,e.data.duration); result={ok:true}; break;
        case 'getMe':     result=api_pub.getMe(); break;
        case 'getMembers':result=api_pub.getMembers(); break;
        case 'getServer': result=api_pub.getServer(); break;
        case 'getTheme':  result=api_pub.getTheme(); break;
        case 'sendMessage': result=await api_pub.sendMessage(e.data.channelId,e.data.text); break;
        case 'getMessages': result=await api_pub.getMessages(e.data.channelId,e.data.limit); break;
        case 'inviteToGame':result=await api_pub.inviteToGame(e.data.gameName,e.data.payload); break;
        case 'acceptInvite':result=await api_pub.acceptInvite(e.data.channelId,e.data.inviteMessageId); break;
        case 'storeGet':  result=await api_pub.storeGet(e.data.key); break;
        case 'storeSet':  result=await api_pub.storeSet(e.data.key,e.data.value); break;
        case 'storeDelete':result=await api_pub.storeDelete(e.data.key); break;
        case 'close':     api_pub.close(); result={ok:true}; break;
        case 'minimize':  api_pub.minimize(); result={ok:true}; break;
        case 'openDM':    api_pub.openDM(e.data.userId,e.data.userName,e.data.avatar); result={ok:true}; break;
        // ── Heartbeat — для получения online-игроков и game-сигналов ──
        case 'heartbeat': result=await api({action:'heartbeat',status:e.data.status||'online',gameSince:e.data.gameSince||0,callSinceId:e.data.callSinceId||0,voiceEventsSince:e.data.voiceEventsSince||0}); break;
        // ── Game invite система ────────────────────────────────────────
        case 'game_invite': result=await api({action:'game_invite',toUserId:e.data.toUserId,game:e.data.game,data:e.data.data||''}); break;
        case 'game_accept': result=await api({action:'game_accept',toUserId:e.data.toUserId,game:e.data.game,data:e.data.data||''}); break;
        case 'game_reject': result=await api({action:'game_reject',toUserId:e.data.toUserId,game:e.data.game}); break;
        case 'game_cancel': result=await api({action:'game_cancel',toUserId:e.data.toUserId,game:e.data.game}); break;
        case 'game_signal': result=await api({action:'game_signal',toUserId:e.data.toUserId,game:e.data.game,type:e.data.type,data:e.data.data||''}); break;
      }
    }catch(err){ result={ok:false, error:err.message}; }
    if(callId&&e.source){
      e.source.postMessage({tes3:true, truecord:true, callId, result}, '*');
    }
  });

  // Диспатчим новые сообщения в подписчиков мини-апп
  const _origAppendMsg = typeof appendMsg !== 'undefined' ? appendMsg : null;

  return api_pub;
})();

// Public mini-app API alias. New integrations should use window.TrueCordAPI;
// window.TES3API is kept as a backward-compatible alias for existing mini-apps.
window.TrueCordAPI = window.TES3API;

// ══════════════════════════════════════════════════════════
// NOTIF STORE — хранилище уведомлений для колокольчика
// ══════════════════════════════════════════════════════════
const NOTIF_STORE = (function(){
  let _items = [];   // [{id, title, text, icon, avatar, avatarEmoji, ts, onClick, onReply}]
  let _nextId = 1;

  function _renderBadge(){
    const badge = document.getElementById('notifBadge');
    const bell  = document.getElementById('notifBellBtn');
    const n = _items.length;
    if(!badge) return;
    if(n > 0){
      badge.textContent = n > 99 ? '99+' : n;
      badge.classList.add('show');
      if(bell) bell.classList.add('has-notif');
    } else {
      badge.textContent = '';
      badge.classList.remove('show');
      if(bell) bell.classList.remove('has-notif');
    }
  }

  function _renderList(){
    const list = document.getElementById('notifPanelList');
    if(!list) return;
    list.innerHTML = '';
    if(!_items.length){
      list.innerHTML = '<div class="np-empty">Нет уведомлений</div>';
      return;
    }
    _items.forEach(n=>{
      const row = document.createElement('div');
      row.className = 'np-item';
      const avHtml = n.avatar && n.avatar.startsWith('http')
        ? `<img src="${esc(n.avatar)}" alt="">`
        : avatarFallbackHtml(n.title||n.avatarEmoji||'?', n.colorKey!=null?n.colorKey:(n.title||''));
      const ts = fmtTime(n.ts);
      // Иконка типа уведомления
      const typeIcon = n.icon && !n.icon.startsWith('http') && n.icon.length < 4
        ? `<span style="display:flex;align-items:center;opacity:.7"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M20 2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3l2.5 3 2.5-3h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span>`
        : `<span style="display:flex;align-items:center;opacity:.7"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M20 2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3l2.5 3 2.5-3h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/></svg></span>`;
      row.innerHTML =
        `<div class="np-item-av">${avHtml}</div>`+
        `<div class="np-item-body">`+
        `<div class="np-item-title">${typeIcon}${esc(n.title)}</div>`+
        `<div class="np-item-text">${esc((n.text||'').slice(0,80))}</div>`+
        `</div>`+
        (n.onReply ? `<button class="np-reply-btn" title="Ответить"><span style="display:flex;align-items:center;gap:4px"><svg viewBox="0 0 24 24" width="12" height="12"><polyline points="9 17 4 12 9 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 18v-2a4 4 0 0 0-4-4H4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>Ответить</span></button>` : '')+
        `<button class="np-item-del" title="Удалить" style="display:flex;align-items:center;justify-content:center"><svg viewBox="0 0 24 24" width="11" height="11"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>`;
      // клик на строку
      row.addEventListener('click', e=>{
        if(e.target.closest('.np-item-del')){
          NOTIF_STORE.remove(n.id); return;
        }
        if(e.target.closest('.np-reply-btn')){
          n.onReply && n.onReply(); closeNotifPanel(); return;
        }
        n.onClick && n.onClick(); closeNotifPanel();
      });
      list.appendChild(row);
    });
  }

  return {
    add(cfg){
      // Не добавляем дубль по msgId
      if(cfg.msgId && _items.some(x=>x.msgId===cfg.msgId)) return;
      // Удаляем старые уведомления от того же отправителя/канала (sourceKey)
      // чтобы не копились дубли: dm:userId или ch:channelId
      if(cfg.sourceKey) _items = _items.filter(x=>x.sourceKey!==cfg.sourceKey);
      const item = Object.assign({id:_nextId++, ts:Date.now()}, cfg);
      _items.unshift(item);
      if(_items.length > 50) _items.length = 50;
      _renderBadge();
      _renderList();
    },
    remove(id){
      _items = _items.filter(x=>x.id!==id);
      _renderBadge();
      _renderList();
    },
    // Вызывается при открытии диалога/канала — снимает все его уведомления
    clearByKey(key){
      const before = _items.length;
      _items = _items.filter(x=>x.sourceKey!==key);
      if(_items.length !== before){ _renderBadge(); _renderList(); }
    },
    clearAll(){
      _items = [];
      _renderBadge();
      _renderList();
    },
    renderList: _renderList,
    get count(){ return _items.length; },
  };
})();

function toggleNotifPanel(e){
  e && e.stopPropagation();
  const p = document.getElementById('notifPanel');
  if(!p) return;
  if(p.classList.contains('open')){
    p.classList.remove('open');
  } else {
    NOTIF_STORE.renderList();
    p.classList.add('open');
  }
}
function closeNotifPanel(){
  document.getElementById('notifPanel')?.classList.remove('open');
}
function notifPanelClearAll(){
  NOTIF_STORE.clearAll();
}
// Закрытие при клике вне панели
document.addEventListener('click', function(e){
  const p = document.getElementById('notifPanel');
  const btn = document.getElementById('notifBellBtn');
  if(p && p.classList.contains('open') && !p.contains(e.target) && e.target!==btn && !btn?.contains(e.target)){
    closeNotifPanel();
  }
});

function showNotifPopup(title,text,opts={}){
  if(typeof Notification!=="undefined"&&Notification&&Notification.permission==='granted'&&document.hidden){
    try{new Notification(title,{body:text.slice(0,100),icon:opts.avatar||undefined});}catch(e){}
  }
  // Добавляем в хранилище панели уведомлений
  NOTIF_STORE.add({
    icon:  opts.icon||ti('chat',14),
    avatar:opts.avatar||'',
    avatarEmoji: opts.avatarEmoji||title?.[0]||ti('chat',14),
    colorKey: opts.colorKey!=null?opts.colorKey:null,
    title: title,
    text:  text,
    msgId: opts.msgId||null,
    sourceKey: opts.sourceKey||null,
    onClick: opts.onClick||null,
    onReply: opts.onReply||null,
  });
  if(localStorage.getItem('tes3PopupNotif')==='0') return;
  DI.notify({
    icon:  opts.icon||ti('chat',14),
    avatar:opts.avatar||'',
    avatarEmoji: opts.avatarEmoji||title?.[0]||ti('chat',14),
    title: title,
    text:  text,
    dotColor: opts.dotColor||'',
    onClick: opts.onClick||null,
    onReply: opts.onReply||null,
  });
}

/* ─── UTILS ──────────────────────────────────── */
function q(id){return document.getElementById(id);}
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function escAttr(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;').replace(/\\/g,'\\\\');}
// Возвращает безопасный URL для href/src: пропускает только http(s), относительные и data:image.
// Блокирует javascript:, vbscript:, data:text/html и прочие исполняемые схемы (defense-in-depth к серверной проверке).
function safeUrl(u){
  const s=String(u||'').trim();
  if(s==='') return '';
  // относительные и протокол-относительные URL безопасны
  if(/^(\/|\.|#|\?)/.test(s)||/^\/\//.test(s)) return esc(s);
  const m=s.match(/^([a-z][a-z0-9+.\-]*):/i);
  if(!m) return esc(s); // нет схемы — относительный путь
  const scheme=m[1].toLowerCase();
  if(scheme==='http'||scheme==='https'||scheme==='mailto') return esc(s);
  if(scheme==='data'&&/^data:image\//i.test(s)) return esc(s); // только картинки в data:
  return ''; // всё остальное (javascript:, vbscript:, data:text/html…) отбрасываем
}
function autoGrow(ta){ta.style.height='auto';ta.style.height=Math.min(ta.scrollHeight,160)+'px';updateComposerHeight();}
// Высота поля ввода → CSS-переменная, чтобы лента не пряталась под «парящим» стеклом.
function updateComposerHeight(){
  const io=document.getElementById('inputOuter');
  if(!io) return;
  const h=io.offsetHeight||84;
  document.documentElement.style.setProperty('--composer-h',h+'px');
}
// Следим за изменением размера поля (реплай-бар, вложения, перенос строк).
(function initComposerObserver(){
  function attach(){
    const io=document.getElementById('inputOuter');
    if(!io){ setTimeout(attach,500); return; }
    updateComposerHeight();
    if(window.ResizeObserver){ try{ new ResizeObserver(updateComposerHeight).observe(io); }catch(_){} }
    window.addEventListener('resize',updateComposerHeight);
  }
  if(document.readyState!=='loading') attach(); else document.addEventListener('DOMContentLoaded',attach);
})();
function scrollBottom(id){const el=q(id);if(el) el.scrollTop=el.scrollHeight;}
function isAtBottom(id){const el=q(id);if(!el) return true;return el.scrollHeight-el.scrollTop-el.clientHeight<80;}
function fmtTime(ts){const d=new Date(ts>9999999999?ts:ts*1000);const now=new Date();const isSame=d.toDateString()===now.toDateString();if(isSame) return d.toLocaleTimeString('ru',{hour:'2-digit',minute:'2-digit'});return d.toLocaleDateString('ru',{day:'numeric',month:'short'})+' '+d.toLocaleTimeString('ru',{hour:'2-digit',minute:'2-digit'});}

/* ─── FIRST VISIT LOADER ─────────────────────── */
(function(){
  const KEY='truecordFirstLoadSeen';
  let shouldShow=false;
  try{shouldShow=!localStorage.getItem(KEY);}catch(e){shouldShow=document.documentElement.classList.contains('tc-first-visit');}
  if(!shouldShow) return;

  const started=Date.now();
  function finishFirstVisitLoader(){
    const minTime=1450;
    const delay=Math.max(0,minTime-(Date.now()-started));
    setTimeout(()=>{
      const loader=q('firstVisitLoader');
      if(loader){
        loader.classList.add('hide');
        setTimeout(()=>{loader.remove();},520);
      }
      document.documentElement.classList.remove('tc-first-visit');
      try{localStorage.setItem(KEY,'1');}catch(e){}
    },delay);
  }

  if(document.readyState==='complete') finishFirstVisitLoader();
  else window.addEventListener('load',finishFirstVisitLoader,{once:true});
  setTimeout(finishFirstVisitLoader,4200);
})();

/* ─── BOOT ───────────────────────────────────── */
(function(){
  let sess=null, rememberMe=false, prevStatus=null;

  const isRemembered=localStorage.getItem('rememberMe')==='1';

  if(isRemembered){
    try{
      const raw=localStorage.getItem('sess');
      if(raw){const p=JSON.parse(raw);if(p?.user&&p?.token){sess=p;rememberMe=true;prevStatus=p.prevStatus||null;}}
    }catch(e){}
  }

  if(!sess){
    try{
      const raw=sessionStorage.getItem('sess');
      if(raw){const p=JSON.parse(raw);if(p?.user&&p?.token) sess=p;}
    }catch(e){}
  }

  if(!sess&&!isRemembered){
    try{
      const raw=localStorage.getItem('sess');
      if(raw){const p=JSON.parse(raw);if(p?.user&&p?.token){sess=p;rememberMe=true;}}
    }catch(e){}
  }

  detectPendingInviteForGuest();

  if(sess?.user&&sess?.token){
    S.me=sess.user; S.tok=sess.token;
    S.myStatus=sess.user.status||'online';
    S.rememberMe=rememberMe;
    q('loginScreen').style.display='none';
    q('app').style.display='flex';
    verifyAndInit(sess.token, prevStatus);
    return;
  }

  const hash=location.hash.slice(1);
  if(hash.startsWith('inv/')){
    sessionStorage.setItem('pendingInvite',hash.slice(4));
    showInviteHint();
    showInviteGuestLanding(hash.slice(4));
  } else {
    const pathInvite=getPathInviteCode();
    if(pathInvite){
      history.replaceState(null,'',location.pathname.replace(/\/?(?:inv_[A-Za-z0-9_-]+|invite\/[A-Za-z0-9_-]+)$/,'')||'/');
      sessionStorage.setItem('pendingInvite',pathInvite);
      showInviteHint();
      showInviteGuestLanding(pathInvite);
    } else {
      const pendingInvite=sessionStorage.getItem('pendingInvite');
      if(pendingInvite){showInviteHint();showInviteGuestLanding(pendingInvite);}
    }
  }
})();
// ══════════════════════════════════════════════════════════════
// MINI-APP MANAGER — множественные окна
// ══════════════════════════════════════════════════════════════
const MAM = (function(){
  const _windows = {};   // chId -> { el, minimized, maximized, savedStyle, dragInited, resizeInited }
  let   _zTop = 5500;    // z-index стек

  // ── Создать DOM окна ───────────────────────────────────────
  function _buildEl(chId, icon, title){
    const id = 'maWin_'+chId;
    const el = document.createElement('div');
    el.className = 'ma-window';
    el.id = id;
    el.innerHTML = `
      <div class="mini-app-header ma-header-${chId}">
        <span class="ma-icon ma-icon-${chId}">${icon}</span>
        <span class="ma-title ma-title-${chId}">${title}</span>
        <div class="ma-btns">
          <button class="ma-wbtn maBtnMin" onclick="MAM.toggleMin(${chId});event.stopPropagation()" title="Свернуть">─</button>
          <button class="ma-wbtn maBtnMax" onclick="MAM.toggleMax(${chId});event.stopPropagation()" title="Развернуть">⛶</button>
          <button class="ma-wbtn maBtnFs"  onclick="MAM.toggleFs(${chId});event.stopPropagation()"  title="Полный экран">⤢</button>
          <button class="ma-wbtn ma-close-btn" onclick="MAM.close(${chId});event.stopPropagation()" title="Закрыть">✕</button>
        </div>
      </div>
      <div class="mini-app-body ma-body-${chId}"></div>
      <div class="ma-resize-handle ma-resize-${chId}"></div>`;
    document.getElementById('miniAppContainer').appendChild(el);
    return el;
  }

  // ── Перетаскивание ─────────────────────────────────────────
  function _initDrag(chId){
    const w = _windows[chId]; if(!w||w.dragInited) return;
    w.dragInited = true;
    const el = w.el;
    const handle = el.querySelector('.ma-header-'+chId);
    let sx=0,sy=0,sl=0,st=0,dragging=false,didMove=false;
    el._dragMoved=false;
    function onStart(e){
      if(e.target.closest('button')) return;
      const t=e.touches?.[0];
      sx=t?t.clientX:e.clientX; sy=t?t.clientY:e.clientY;
      const rect=el.getBoundingClientRect();
      sl=rect.left; st=rect.top;
      // Конвертируем bottom→top немедленно чтобы drag работал при свёрнутом состоянии
      el.style.top=rect.top+'px'; el.style.bottom='auto';
      el.style.left=rect.left+'px'; el.style.right='auto';
      dragging=true; didMove=false; el._dragMoved=false;
      _zTop++; el.style.zIndex=_zTop;
      e.preventDefault();
    }
    function onMove(e){
      if(!dragging) return;
      const t=e.touches?.[0];
      const cx=t?t.clientX:e.clientX; const cy=t?t.clientY:e.clientY;
      const dx=cx-sx, dy=cy-sy;
      if(!didMove&&Math.abs(dx)<4&&Math.abs(dy)<4) return;
      if(!didMove){ didMove=true; el._dragMoved=true; el.style.left=sl+'px'; el.style.top=st+'px'; el.style.right='auto'; el.style.bottom='auto'; }
      el.style.left=Math.max(0,Math.min(sl+dx,window.innerWidth-el.offsetWidth))+'px';
      el.style.top=Math.max(0,Math.min(st+dy,window.innerHeight-el.offsetHeight))+'px';
      if(e.cancelable) e.preventDefault();
    }
    function onEnd(){ dragging=false; setTimeout(()=>{didMove=false;el._dragMoved=false;},50); }
    handle.addEventListener('mousedown',onStart);
    handle.addEventListener('touchstart',onStart,{passive:false});
    document.addEventListener('mousemove',onMove);
    document.addEventListener('touchmove',onMove,{passive:false});
    document.addEventListener('mouseup',onEnd);
    document.addEventListener('touchend',onEnd);
    // Клик на свёрнутый заголовок — разворачивает
    handle.addEventListener('click',function(e){
      if(e.target.closest('button')) return;
      if(el._dragMoved) return;
      if(_windows[chId]?.minimized) MAM.toggleMin(chId);
    });
    // Клик в любом месте окна — поднять наверх
    el.addEventListener('mousedown',()=>{ _zTop++; el.style.zIndex=_zTop; });
  }

  // ── Resize ─────────────────────────────────────────────────
  function _initResize(chId){
    const w = _windows[chId]; if(!w||w.resizeInited) return;
    w.resizeInited = true;
    const el = w.el;
    const handle = el.querySelector('.ma-resize-'+chId);
    if(!handle) return;
    let sx=0,sy=0,sw=0,sh=0,resizing=false;
    handle.addEventListener('mousedown',e=>{
      if(w.maximized||w.minimized||document.fullscreenElement) return;
      sx=e.clientX; sy=e.clientY;
      sw=el.offsetWidth; sh=el.offsetHeight;
      resizing=true; e.preventDefault();
    });
    document.addEventListener('mousemove',e=>{
      if(!resizing) return;
      el.style.width=Math.max(280,sw+(e.clientX-sx))+'px';
      el.style.height=Math.max(160,sh+(e.clientY-sy))+'px';
    });
    document.addEventListener('mouseup',()=>{ resizing=false; });
  }

  // ── Центрировать окно ──────────────────────────────────────
  function _center(chId){
    if(window.innerWidth<=980) return;
    const el = _windows[chId]?.el; if(!el) return;
    const appH=parseInt(getComputedStyle(document.documentElement).getPropertyValue('--app-h'))||window.innerHeight;
    const count = Object.keys(_windows).length;
    const offset = (count-1)*28; // каскадное смещение
    const w=Math.min(860,window.innerWidth-24);
    const h=Math.min(appH-80,Math.max(420,appH-120));
    el.style.width=w+'px'; el.style.height=h+'px';
    el.style.left=Math.round((window.innerWidth-w)/2+offset)+'px';
    el.style.top=(60+offset)+'px';
    el.style.transform='none';
  }

  // ── Публичный API ──────────────────────────────────────────
  const pub = {
    async open(chId){
      exitFullscreenForWindowOpen();
      // Уже открыто?
      if(_windows[chId]){
        const w=_windows[chId];
        if(w.minimized) pub.toggleMin(chId);
        _zTop++; w.el.style.zIndex=_zTop;
        return;
      }
      const r=await api({action:'get_channel_app',channelId:chId});
      if(!r.ok){toast(r.error,'err');return;}

      const el = _buildEl(chId, r.appIcon||ti('puzzle',14), r.name||'Мини-приложение');
      const body = el.querySelector('.ma-body-'+chId);
      const iframe = document.createElement('iframe');
      iframe.setAttribute('sandbox','allow-scripts allow-forms allow-popups allow-same-origin');
      iframe.srcdoc=r.appHtml||'<p style="padding:24px;font-family:sans-serif;color:#888">Нет содержимого</p>';
      iframe.addEventListener('load',()=>setTimeout(()=>postMiniAppThemeToFrame(iframe),0));
      body.appendChild(iframe);
      setTimeout(()=>postMiniAppThemeToFrame(iframe),80);

      _windows[chId] = { el, minimized:false, maximized:false, savedStyle:null, dragInited:false, resizeInited:false };
      _zTop++; el.style.zIndex=_zTop;

      if(window.innerWidth<=980){
        el.style.top='0'; el.style.left='0'; el.style.width='100vw';
        const appH=parseInt(getComputedStyle(document.documentElement).getPropertyValue('--app-h'))||window.innerHeight;
        el.style.height=appH+'px'; el.style.borderRadius='0';
      } else {
        _center(chId);
      }
      el.classList.add('open');
      _initDrag(chId); _initResize(chId);
    },

    close(chId){
      const w=_windows[chId]; if(!w) return;
      if(document.fullscreenElement===w.el) document.exitFullscreen().catch(()=>{});
      w.el.style.transition='opacity .15s,transform .15s';
      w.el.style.opacity='0'; w.el.style.transform='scale(0.9)';
      setTimeout(()=>{ w.el.remove(); delete _windows[chId]; },160);
    },

    closeAll(){
      Object.keys(_windows).forEach(id=>pub.close(parseInt(id)));
    },

    toggleMin(chId){
      const w=_windows[chId]; if(!w||w.maximized) return;
      const el=w.el;
      w.minimized=!w.minimized;
      const btnMin=el.querySelector('.maBtnMin');
      const btnMax=el.querySelector('.maBtnMax');
      const btnFs=el.querySelector('.maBtnFs');
      if(w.minimized){
        w.savedStyle={width:el.style.width,height:el.style.height,top:el.style.top,left:el.style.left,transform:el.style.transform,borderRadius:el.style.borderRadius};
        el.classList.add('ma-minimized');
        if(btnMin){btnMin.textContent='▢';btnMin.title='Развернуть';}
        // Позиция в ряд снизу: каждое следующее смещается вправо
        requestAnimationFrame(()=>{
          const minWins=Object.values(_windows).filter(x=>x.minimized&&x.el!==el);
          const slot=minWins.length;
          const elW=el.offsetWidth||200;
          const safeB=parseInt(getComputedStyle(document.documentElement).getPropertyValue('--safe-bottom'))||0;
          const bottomPx=62+safeB;
          el.style.position='fixed';
          el.style.bottom=bottomPx+'px'; el.style.top='auto';
          el.style.left=(10+slot*(elW+8))+'px'; el.style.right='auto';
          el.style.zIndex=++_zTop;
          el.style.opacity='0'; el.style.transform='scale(0.7) translateY(20px)';
          requestAnimationFrame(()=>{
            el.style.transition='opacity .2s,transform .2s';
            el.style.opacity='1'; el.style.transform='scale(1) translateY(0)';
            setTimeout(()=>{el.style.transition='';},220);
          });
        });
      } else {
        el.style.transition='opacity .15s,transform .15s';
        el.style.opacity='0'; el.style.transform='scale(0.85)';
        setTimeout(()=>{
          el.classList.remove('ma-minimized');
          el.style.transition=''; el.style.opacity=''; el.style.transform='';
          const s=w.savedStyle||{};
          el.style.width=s.width||''; el.style.height=s.height||'';
          el.style.top=s.top||''; el.style.left=s.left||'';
          el.style.transform=s.transform||'none'; el.style.borderRadius=s.borderRadius||'';
          el.style.zIndex=++_zTop;
          if(!s.width) _center(chId);
          if(btnMin){btnMin.textContent='─';btnMin.title='Свернуть';}
        },160);
      }
    },

    toggleMax(chId){
      const w=_windows[chId]; if(!w) return;
      const el=w.el;
      if(w.minimized){ w.minimized=false; el.classList.remove('ma-minimized'); el.querySelector('.maBtnMin').textContent='─'; }
      w.maximized=!w.maximized;
      const btnMax=el.querySelector('.maBtnMax');
      if(w.maximized){
        const rect=el.getBoundingClientRect();
        w.prevRect={left:rect.left,top:rect.top,width:rect.width,height:rect.height};
        const appH=parseInt(getComputedStyle(document.documentElement).getPropertyValue('--app-h'))||window.innerHeight;
        el.style.left='0'; el.style.top='0'; el.style.transform='none';
        el.style.width='100vw'; el.style.height=appH+'px'; el.style.borderRadius='0';
        el.classList.add('ma-maximized');
        if(btnMax){btnMax.textContent='❐';btnMax.title='Восстановить';}
      } else {
        el.classList.remove('ma-maximized'); el.style.borderRadius='';
        if(w.prevRect){
          el.style.left=w.prevRect.left+'px'; el.style.top=w.prevRect.top+'px';
          el.style.width=w.prevRect.width+'px'; el.style.height=w.prevRect.height+'px';
        } else _center(chId);
        if(btnMax){btnMax.textContent='⛶';btnMax.title='Развернуть';}
      }
    },

    toggleFs(chId){
      const w=_windows[chId]; if(!w) return;
      const el=w.el;
      if(!document.fullscreenElement){
        el.requestFullscreen().catch(()=>toast('Полный экран недоступен','err',2000));
      } else {
        document.exitFullscreen();
      }
    },
  };

  // fullscreen change handler
  document.addEventListener('fullscreenchange',()=>{
    Object.entries(_windows).forEach(([chId,w])=>{
      const btn=w.el.querySelector('.maBtnFs'); if(!btn) return;
      const isFs=document.fullscreenElement===w.el;
      btn.textContent=isFs?'⤡':'⤢'; btn.title=isFs?'Выйти из полного экрана':'Полный экран';
    });
  });

  return pub;
})();

// ── Обратная совместимость старых вызовов ─────────────────────
async function openMiniApp(chId){ return MAM.open(chId); }
function closeMiniApp(){ MAM.closeAll(); }
window.miniAppToggleMin=function(chId){ MAM.toggleMin(chId); };
window.miniAppToggleMax=function(chId){ MAM.toggleMax(chId); };
window.miniAppToggleFs=function(chId){ MAM.toggleFs(chId); };
window.openMiniApp=openMiniApp;
window.closeMiniApp=closeMiniApp;
window.MAM=MAM;

// ── HTML FILE LOADER FOR MINI-APP ────────────────────────────
function loadHtmlFileToTextarea(input, textareaId){
  const file=input.files[0];if(!file) return;
  if(file.size>1048576){toast('Файл слишком большой (макс. 1 МБ)','err');return;}
  const reader=new FileReader();
  reader.onload=e=>{
    const ta=document.getElementById(textareaId);
    if(ta){ta.value=e.target.result;autoGrow(ta);}
    toast(`📂 "${esc(file.name)}" загружен`,'ok',2000);
  };
  reader.onerror=()=>toast('Ошибка чтения файла','err');
  reader.readAsText(file,'UTF-8');
}

// ── MEDIA 404 HANDLER ────────────────────────────────────────
async function handleMediaError(url,msgId,isDm,el){
  if(!url){try{if(el) el.style.display='none';}catch(e){}return;}
  // Уже известно что файл мёртв — просто скрываем, без fetch и тоста
  if(_deadMediaUrls.has(url)){
    try{if(el) el.style.display='none';}catch(e){}
    return;
  }
  // Добавляем СРАЗУ чтобы повторный onerror не запустил ещё один fetch
  _deadMediaUrls.add(url);
  try{if(el) el.style.display='none';}catch(e){}
  if(!msgId) return;
  try{
    const res=await fetch(url,{method:'HEAD'});
    if(res.status===404){
      // Чистим ссылку в БД (best-effort, тихо при ошибке прав)
      api({action:'unlink_media',messageId:msgId,isDm:!!isDm}).catch(()=>{});
    }
  }catch(e){}
}

// ── AUDIO PRE-CHECK BEFORE PLAY ──────────────────────────────
async function checkAndPlayAudio(url,msgId,isDm,name){
  // Уже известно что файл мёртв — заменяем виджет без fetch и повторного тоста
  if(_deadMediaUrls.has(url)){
    _replaceDeadAudioWidget(msgId);
    toast('Файл недоступен','err',2000);
    return;
  }
  // Показываем плеер сразу — до проверки доступности файла
  const _q=getChannelAudioQueue();
  const _si=_q.findIndex(t=>t.url===url);
  openFloatingPlayer(_q.length>0?_q:[{url,name:name||url.split('/').pop()}],_si>=0?_si:0);
  // Проверяем доступность файла в фоне
  try{
    const res=await fetch(url,{method:'HEAD'});
    if(res.status===404){
      _deadMediaUrls.add(url);
      closeFloatingPlayer();
      _replaceDeadAudioWidget(msgId);
      if(msgId) api({action:'unlink_media',messageId:msgId,isDm:!!isDm}).catch(()=>{});
      toast('Файл не найден','err',2500);
      return;
    }
  }catch(e){}
}

function _replaceDeadAudioWidget(msgId){
  if(!msgId) return;
  const msgRow=document.querySelector(`[data-msg-id="${msgId}"]`);
  if(!msgRow) return;
  const widget=msgRow.querySelector('.msg-audio-wrap, .file-card');
  if(!widget) return;
  const ph=document.createElement('div');
  ph.style.cssText='display:inline-flex;align-items:center;gap:6px;padding:4px 10px;'+
    'background:var(--bg3);border-radius:var(--radius-sm);font-size:12px;'+
    'color:var(--text3);margin-top:4px;border:1px dashed var(--border2);cursor:default';
  ph.innerHTML=ti('warning',14)+' Файл удалён или недоступен';
  widget.parentNode?.replaceChild(ph,widget);
}


// ── CREATE/EDIT APP CHANNEL MODAL ────────────────────────────
function showCreateAppChannelModal(){
  if(!assertCanCreateChannel()) return;
  showModal(`
    <div class="settings-shell app-editor-shell">
      <div class="settings-hero settings-hero-compact">
        <div class="settings-hero-pane settings-hero-copy">
          <div class="settings-kicker">${ti("puzzle",14)} ${t('app.miniApp')}</div>
          <h2 style="margin-bottom:8px">${t('app.createMiniApp')}</h2>
          <div class="sub">${t('app.createDesc')}</div>
          <div class="settings-inline-chips">
            <span class="settings-badge">${ti('code',12)} HTML</span>
            <span class="settings-badge">${ti('book',12)} ${t('app.catalog')}</span>
            <span class="settings-badge">${ti('sparkles',12)} ${t('app.truecordApi')}</span>
          </div>
        </div>

        <div class="settings-profile-card compact">
          <div class="settings-profile-banner"></div>
          <div class="settings-profile-card-body">
            <div class="settings-avatar-wrap settings-avatar-floating">
              <div class="av-preview settings-avatar">${ti('puzzle',18)}</div>
            </div>
            <div class="settings-profile-name">${t('app.newApp')}</div>
            <div class="settings-profile-meta">${t('app.profileMeta')}</div>
            <div class="settings-profile-badges">
              <span class="settings-badge">${ti('link',12)} ${t('app.apiBridge')}</span>
              <span class="settings-badge">${ti('grid',12)} ${t('app.catalog')}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="app-editor-grid">
        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('edit',15)} ${t('settings.cardMain')}</div>
            <div class="settings-card-sub">${t('app.mainSubCreate')}</div>
          </div>

          <div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="newAppName" placeholder="${t('app.myAppPlaceholder')}" maxlength="32"></div>
          <div class="fg"><label class="fl">${t('settings.srvIconEmoji')}</label><input class="fi" id="newAppIcon" placeholder="${t('app.iconOptional')}" maxlength="4" value=""></div>
          <div class="fg"><label class="fl">${t('settings.srvDesc')}</label><input class="fi" id="newAppDesc" placeholder="${t('settings.srvDesc')}" maxlength="120"></div>
          <div class="fg"><label class="fl">${t('channel.read')}</label><select class="fi" id="newAppRead"><option value="all">${t('perm.all')}</option><option value="members">${t('perm.members')}</option><option value="admins">${t('perm.admins')}</option></select></div>
          <label class="terms-check" style="margin-bottom:0">
            <input type="checkbox" id="newAppPublic">
            <span>
              <strong style="color:var(--gold)">${ti("book",13)} ${t('app.public')} </strong> — ${t('app.publicCreateDesc')}
              <span style="display:block;font-size:11px;color:var(--text3);margin-top:2px">${t('app.htmlSourceNoDuplicate')}</span>
            </span>
          </label>
        </section>

        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('code',15)} ${t('app.htmlApiTitle')}</div>
            <div class="settings-card-sub">${t('app.htmlSub')}</div>
          </div>

          <div class="fg">
            <label class="fl">${t('app.htmlContent')}</label>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px">
              <label class="upload-chip">
                📂 ${t('app.uploadHtml')}
                <input type="file" accept=".html,.htm" style="display:none" onchange="loadHtmlFileToTextarea(this,'newAppHtml')">
              </label>
              <button class="btn btn-ghost" type="button" onclick="insertApiTemplate()">${ti('code',13)} ${t('app.insertTemplate')}</button>
              <button class="btn btn-ghost" type="button" onclick="window.open('miniapp_api.html','_blank')">${ti('link',13)} ${t('app.apiDocs')}</button>
            </div>
            <textarea class="fi app-code-textarea" id="newAppHtml" rows="8" placeholder="&lt;html&gt;...&lt;/html&gt;"></textarea>
          </div>

          <div class="miniapi-callout">
            <div class="miniapi-callout-title">
              <svg viewBox="0 0 24 24" width="14" height="14"><polyline points="16 18 22 12 16 6" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/><polyline points="8 6 2 12 8 18" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
              ${t('app.miniApiTitle')}
            </div>
            <div class="miniapi-callout-copy">${t('app.apiCalloutCreate')}</div>
          </div>

          <div class="settings-actions-row" style="margin-top:12px">
            <button class="btn btn-gold" onclick="createAppChannel()">${ti('check',14)} ${t('common.create')}</button>
            <button class="btn btn-ghost" onclick="closeModal();showAppCatalog(S.srvId)">${ti('grid',14)} ${t('app.catalog')}</button>
          </div>
        </section>
      </div>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
  setTimeout(()=>q('newAppName')?.focus(),100);
}



window.insertApiTemplate=function(){
  const ta=q('newAppHtml');
  if(!ta) return;
  const html=[
    "<!DOCTYPE html>",
    "<html>",
    "<head><meta charset=\"UTF-8\">",
    "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">",
    "<title>Mini App</title>",
    "<style>",
    "body{font-family:system-ui,sans-serif;background:#1a1510;color:#e8d5b0;margin:0;padding:16px}",
    "button{background:#c9aa71;color:#1a1510;border:none;border-radius:6px;padding:8px 16px;cursor:pointer;font-weight:600}",
    "</style></head><body>",
    "<h2>Mini App</h2>",
    "<button onclick=\"testAPI()\">Test API</button>",
    "<pre id=\"out\" style=\"margin-top:12px;font-size:12px;color:#7a9e7a\"></pre>",
    "<scr"+"ipt>",
    "class TES3Bridge{",
    "  constructor(){this._pending={};this._id=0;",
    "    window.addEventListener('message',function(e){",
    "      if(!e.data||!e.data.tes3||!e.data.callId)return;",
    "      var r=e.data;",
    "      if(this._pending[r.callId]){this._pending[r.callId](r.result);delete this._pending[r.callId];}",
    "    }.bind(this));",
    "  }",
    "  call(action,params){",
    "    params=params||{};",
    "    return new Promise(function(resolve){",
    "      var id=++this._id;this._pending[id]=resolve;",
    "      window.parent.postMessage(Object.assign({tes3:true,callId:id,action:action},params),'*');",
    "    }.bind(this));",
    "  }",
    "}",
    "var API=new TES3Bridge();",
    "function testAPI(){",
    "  API.call('getMe').then(function(me){",
    "    document.getElementById('out').textContent=JSON.stringify(me,null,2);",
    "    if(me&&me.ok)API.call('toast',{message:'Hello '+me.user.name+'!',type:'ok'});",
    "  });",
    "}",
    "<"+"/scr"+"ipt>",
    "</body></html>"
  ].join("\n");
  ta.value=html;
  autoGrow(ta);
  toast('Template inserted','ok',2000);
};
window.showCreateAppChannelModal=showCreateAppChannelModal;
window.createAppChannel=async function(){
  if(!assertCanCreateChannel()) return;
  const name=q('newAppName')?.value.trim();
  const icon=q('newAppIcon')?.value.trim()||ti('puzzle',14);
  const desc=q('newAppDesc')?.value.trim();
  const html=q('newAppHtml')?.value||'';
  const pr=q('newAppRead')?.value||'all';
  const isPublic=q('newAppPublic')?.checked?1:0;
  if(!name){toast(t('toast.enterName'),'err');return;}
  const r=await api({action:'create_channel',serverId:S.srvId,name,topic:'',description:desc,permRead:pr,permWrite:'admins',type:'app',appIcon:icon,appHtml:html,isPublic});
  if(!r.ok){toast(r.error,'err');return;}
  closeModal();toast(t('app.createdToast'),'ok');await loadChannels(S.srvId);
};
window.showEditAppChannel=async function(chId){
  const ch=S.channels.find(c=>c.id===chId); if(!ch) return;
  if(!assertCanManageChannel(ch)) return;
  const isRef=(ch.appRefChannelId||0)>0;
  let currentHtml='';
  let refLabel='';
  if(isRef){
    const htmlR=await api({action:'get_channel_app',channelId:chId});
    refLabel=htmlR.ok&&htmlR.refSourceName?htmlR.refSourceName:t('app.externalServer');
  } else {
    const htmlR=await api({action:'get_channel_app',channelId:chId});
    currentHtml=htmlR.ok?htmlR.appHtml:'';
  }
  const htmlSection=isRef
    ? `<div class="fg">
         <label class="fl">${t('app.htmlContent')}</label>
         <div class="miniapi-callout" style="margin-top:0">
           <div class="miniapi-callout-title">${ti("link",13)} Внешний источник</div>
           <div class="miniapi-callout-copy">HTML хранится на сервере-источнике: <strong style="color:#fff">${esc(refLabel)}</strong><br><span style="font-size:11px;color:var(--text3)">Изменения на источнике автоматически отражаются здесь.</span></div>
         </div>
       </div>`
    : `<div class="fg">
         <label class="fl">${t('app.htmlContent')}</label>
         <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px">
           <label class="upload-chip">
             📂 ${t('app.uploadHtml')}
             <input type="file" accept=".html,.htm" style="display:none" onchange="loadHtmlFileToTextarea(this,'editAppHtml')">
           </label>
           <button class="btn btn-ghost" type="button" onclick="window.open('miniapp_api.html','_blank')">${ti('link',13)} ${t('app.apiDocs')}</button>
         </div>
         <textarea class="fi app-code-textarea" id="editAppHtml" rows="8">${esc(currentHtml)}</textarea>
       </div>`;

  showModal(`
    <div class="settings-shell app-editor-shell">
      <div class="settings-hero settings-hero-compact">
        <div class="settings-hero-pane settings-hero-copy">
          <div class="settings-kicker">${ti("gear",14)} ${t('app.settingsTitle')}</div>
          <h2 style="margin-bottom:8px">${esc(ch.name)}</h2>
          <div class="sub">${esc(ch.description||t('app.editDesc'))}</div>
          <div class="settings-inline-chips">
            <span class="settings-badge">${ti('puzzle',12)} Mini App</span>
            <span class="settings-badge">${ti('eye',12)} ${t('channel.read')}: ${ch.permRead==='all'?t('perm.all'):ch.permRead==='members'?t('perm.members'):t('perm.admins')}</span>
            ${isRef?`<span class="settings-badge">${ti('link',12)} ${t('app.connectedFromCatalog')}</span>`:''}
          </div>
        </div>

        <div class="settings-profile-card compact">
          <div class="settings-profile-banner"></div>
          <div class="settings-profile-card-body">
            <div class="settings-avatar-wrap settings-avatar-floating">
              <div class="av-preview settings-avatar">${esc(ch.appIcon||ti('puzzle',16))}</div>
            </div>
            <div class="settings-profile-name">${esc(ch.name)}</div>
            <div class="settings-profile-meta">${isRef?t('app.connectedFromCatalog'):t('app.localMiniApp')}</div>
            <div class="settings-profile-badges">
              <span class="settings-badge">${ti('code',12)} HTML</span>
              <span class="settings-badge">${ti('book',12)} ${ch.isPublic?t('app.public'):t('app.local')}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="app-editor-grid">
        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('edit',15)} ${t('settings.cardMain')}</div>
            <div class="settings-card-sub">${t('app.mainSubEdit')}</div>
          </div>
          <div class="fg"><label class="fl">${t('settings.srvName')}</label><input class="fi" id="editAppName" value="${esc(ch.name)}" maxlength="32"></div>
          <div class="fg"><label class="fl">${t('settings.srvIconEmoji')}</label><input class="fi" id="editAppIcon" value="${esc(ch.appIcon||ti('puzzle',16))}" maxlength="4"></div>
          <div class="fg"><label class="fl">${t('settings.srvDesc')}</label><input class="fi" id="editAppDesc" value="${esc(ch.description||'')}" maxlength="120"></div>
          <div class="fg"><label class="fl">${t('channel.read')}</label><select class="fi" id="editAppRead"><option value="all" ${ch.permRead==='all'?'selected':''}>${t('perm.all')}</option><option value="members" ${ch.permRead==='members'?'selected':''}>${t('perm.members')}</option><option value="admins" ${ch.permRead==='admins'?'selected':''}>${t('perm.admins')}</option></select></div>
          ${!isRef?`<label class="terms-check" style="margin-bottom:0"><input type="checkbox" id="editAppPublic" ${ch.isPublic?'checked':''}><span><strong style="color:var(--gold)">${ti("book",13)} ${t('app.public')} </strong> — ${t('app.publicEditDesc')}</span></label>`:''}
        </section>

        <section class="settings-card settings-card-tight">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('code',15)} ${t('app.codeApi')}</div>
            <div class="settings-card-sub">${t('app.codeApiSub')}</div>
          </div>
          ${htmlSection}
          <div class="miniapi-callout">
            <div class="miniapi-callout-title">${ti('sparkles',13)} ${t('app.miniApiTitle')}</div>
            <div class="miniapi-callout-copy">${t('app.apiCalloutEdit')}</div>
          </div>
          <div class="settings-actions-row" style="margin-top:12px">
            <button class="btn btn-gold" onclick="saveAppChannel(${chId},${isRef})">${ti('check',14)} ${t('common.save')}</button>
            <button class="btn btn-red" onclick="deleteChannel(${chId})">${ti('trash',14)} ${t('common.delete')}</button>
          </div>
        </section>
      </div>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
};

window.saveAppChannel=async function(chId,isRef){
  const ch=S.channels.find(c=>c.id===chId);if(!ch||!assertCanManageChannel(ch)) return;
  const name=q('editAppName')?.value.trim();
  const icon=q('editAppIcon')?.value.trim()||ti('puzzle',14);
  const desc=q('editAppDesc')?.value.trim();
  const html=isRef?undefined:(q('editAppHtml')?.value||'');
  const pr=q('editAppRead')?.value||'all';
  const isPublic=isRef?0:(q('editAppPublic')?.checked?1:0);
  if(!name){toast(t('toast.enterName'),'err');return;}
  const payload={action:'update_channel',channelId:chId,name,topic:'',description:desc,permRead:pr,permWrite:'admins',type:'app',appIcon:icon,isPublic};
  if(!isRef) payload.appHtml=html;
  const r=await api(payload);
  if(!r.ok){toast(r.error,'err');return;}
  closeModal();toast(t('toast.saved'),'ok');await loadChannels(S.srvId);
};
// ── APP CATALOG ───────────────────────────────────────────────
async function showAppCatalog(targetServerId){
  const r=await api({action:'get_app_catalog'});
  if(!r.ok){toast(r.error,'err');return;}
  const apps=r.apps||[];
  if(!apps.length){
    showModal(`
      <div class="settings-shell app-catalog-shell">
        <section class="settings-card">
          <div class="settings-card-head">
            <div class="settings-card-title">${ti('grid',18)} ${t('app.catalogTitle')}</div>
            <div class="settings-card-sub">${t('app.catalogSub')}</div>
          </div>
          <div class="settings-theme-note">
            <div class="settings-theme-dot"></div>
            <span>${t('app.catalogEmpty')}</span>
          </div>
          <button class="btn btn-ghost btn-full" onclick="closeModal()">${t('common.close')}</button>
        </section>
      </div>`);
    document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
    return;
  }

  const list=apps.map(a=>`
    <div class="app-catalog-item">
      <div class="app-catalog-head">
        <div class="app-catalog-icon">${esc(a.appIcon||ti('puzzle',16))}</div>
        <div class="grow" style="min-width:0">
          <div class="app-catalog-name">${esc(a.name)}</div>
          <div class="app-catalog-meta">${ti('castle',11)} ${esc(a.serverName)}${a.description?' · '+esc(a.description.slice(0,90)):''}</div>
        </div>
      </div>
      <div class="settings-inline-chips" style="margin-top:0">
        <span class="settings-badge">${ti('link',12)} ${t('app.noHtmlDuplicate')}</span>
      </div>
      <div class="app-catalog-actions">
        ${canCreateChannelCurrentServer()?`<button class="btn btn-gold btn-full" onclick="addAppFromCatalog(${a.id},${targetServerId})">${ti('plus',12)} ${t('common.add')}</button>`:''}
      </div>
    </div>`).join('');

  showModal(`
    <div class="settings-shell app-catalog-shell">
      <section class="settings-card">
        <div class="settings-card-head">
          <div class="settings-card-title">${ti('grid',18)} ${t('app.catalogTitle')}</div>
          <div class="settings-card-sub">${t('app.catalogChoose')}</div>
        </div>
        <div class="app-catalog-grid">${list}</div>
        <div class="settings-actions-row" style="margin-top:14px">
          <button class="btn btn-ghost" onclick="closeModal()">${t('common.close')}</button>
          ${canCreateChannelCurrentServer()?`<button class="btn btn-gold" onclick="closeModal();showCreateAppChannelModal()">${ti('puzzle',14)} ${t('app.createOwn')}</button>`:''}
        </div>
      </section>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
}

window.addAppFromCatalog=async function(refChId,serverId){
  if(!assertCanCreateChannel()) return;
  const r=await api({action:'add_app_from_catalog',serverId,refChannelId:refChId});
  if(!r.ok){toast(r.error,'err');return;}
  closeModal();
  toast(t('app.addedToast',{name:esc(r.name)}),'ok');
  await loadChannels(serverId);
};

window.showAppCatalog=showAppCatalog;

// ── SERVER ADMIN PANEL (per-server) ──────────────────────────
window.showServerAdminPanel=async function(){
  closeModal();
  if(!S.srvId) return;
  if(!canModerateCurrentServer()){toast('Нет прав для просмотра управления участниками','err');return;}
  await loadMembers(S.srvId);
  const srv=S.servers.find(s=>s.id===S.srvId);
  const myRole=S.myRole;
  const isGlobal=S.me?.name===OWNER_NAME||S.me?.globalRole==='super_admin'||S.me?.globalRole==='project_admin';
  try{await loadDmConvs();}catch(e){}
  const membersHtml=S.members.filter(m=>m.id!==S.me?.id).map(m=>{
    const roleLabel=m.role==='owner'?t('roles.owner'):m.role==='admin'?t('roles.admin'):m.role==='moderator'?t('roles.moderator'):t('roles.member');
    const online=S.onlineUsers.find(o=>o.id===m.id);
    const st=(online?online.status:(m.status||'offline'))||'offline';
    const dotSt=(st&&st!=='invisible')?st:'offline';
    const conv=(S.dmConvs||[]).find(c=>c.userId===m.id);
    const unread=conv?parseInt(conv.unread||0,10):0;
    const av=m.avatar&&m.avatar.startsWith('http')?`<img src="${escAttr(m.avatar)}" style="width:100%;height:100%;object-fit:cover" alt="">`:esc((m.name||'?')[0]);
    const safeName=esc(JSON.stringify(m.name));
    const actions=[
      canModerateCurrentServer()&&!m.validated?`<button class="server-admin-mini" title="${escAttr(t('admin.verify'))}" aria-label="${escAttr(t('admin.verify'))}" onclick="validateUserSrv(${m.id})">${ti('check',13)}</button>`:'',
      (isAdmin2(myRole)||isGlobal)?`<button class="server-admin-mini accent" title="${escAttr(t('admin.roles'))}" aria-label="${escAttr(t('admin.roles'))}" onclick="showAssignRoleModal(${m.id})">${ti('shield',13)}</button>`:'',
      canModerateCurrentServer()?`<button class="server-admin-mini" title="${escAttr(t('admin.mute'))}" aria-label="${escAttr(t('admin.mute'))}" onclick="muteUserModal(${m.id},${safeName})">${ti('mute',13)}</button>`:'',
      canModerateCurrentServer()?`<button class="server-admin-mini danger" title="${escAttr(t('admin.kick'))}" aria-label="${escAttr(t('admin.kick'))}" onclick="kickUser(${m.id})">${ti('kick',13)}</button>`:''
    ].join('');
    return `<div class="server-admin-card">
      <div class="server-admin-user">${av}<span class="server-admin-status st-${dotSt}"></span>${unread>0?`<span class="server-admin-unread">${unread>99?'99+':unread}</span>`:''}</div>
      <div class="server-admin-copy"><div class="server-admin-name">${esc(m.name)} ${m.validated?'<span style="color:var(--green);font-size:11px">✓</span>':''}</div><div class="server-admin-desc">${roleLabel}${dotSt?` · ${esc(dotSt)}`:''}</div></div>
      <div class="server-admin-actions">${actions}</div>
    </div>`;
  }).join('');

  showModal(`
    <div class="settings-shell">
      <section class="settings-card">
        <div class="settings-card-head">
          <div class="settings-card-title">${ti('shield',18)} ${t('server.manage')}</div>
          <div class="settings-card-sub">${esc(srv?.name||'')} · ${S.members.length} ${t('server.membersCount')}</div>
        </div>
        <div class="server-admin-list">${membersHtml||`<div class="settings-theme-note"><div class="settings-theme-dot"></div><span>${t('server.noMembers')}</span></div>`}</div>
      </section>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal','server-admin-modal');
};
window.validateUserSrv=async function(uid){
  const r=await api({action:'validate_user',targetId:uid,serverId:S.srvId});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('admin.verifiedToast'),'ok');
  await loadMembers(S.srvId);await loadUsers();
  showServerAdminPanel();
};
// ── BLACKLIST ────────────────────────────────────────────────
window.showBlacklist=async function(){
  const r=await api({action:'dm_blacklist_get'});
  const list=r.ok?r.list:[];
  const listHtml=list.length?list.map(u=>`<div class="big-modal-row">
    <div class="big-modal-icon">${esc((u.name||'?')[0])}</div>
    <div class="grow">
      <div class="title">${esc(u.name)}</div>
      <div class="desc">${t('blacklist.userBlocked')}</div>
    </div>
    <div class="actions">
      <button class="btn btn-ghost" style="font-size:11px" onclick="dmUnblock(${u.id})">${t('blacklist.unblock')}</button>
    </div>
  </div>`).join(''):`<div class="settings-theme-note"><div class="settings-theme-dot"></div><span>${t('blacklist.empty')}</span></div>`;
  showModal(`
    <div class="settings-shell">
      <section class="settings-card">
        <div class="settings-card-head">
          <div class="settings-card-title">${ti('ban',18)} ${t('settings.blacklist')}</div>
          <div class="settings-card-sub">${t('blacklist.manageBlocked')}</div>
        </div>
        <div class="big-modal-list">${listHtml}</div>
        <button class="btn btn-ghost btn-full" style="margin-top:12px" onclick="closeModal()">${t('common.close')}</button>
      </section>
    </div>`);
  document.querySelector('#modalBg .modal')?.classList.add('settings-modal');
};
window.dmBlock=async function(uid){
  if(!confirm(t('blacklist.confirmBlock'))) return;
  const r=await api({action:'dm_blacklist_add',blockUserId:uid});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('blacklist.blockedToast'),'ok');closeModal();
};
window.dmUnblock=async function(uid){
  const r=await api({action:'dm_blacklist_remove',blockUserId:uid});
  if(!r.ok){toast(r.error,'err');return;}
  toast(t('blacklist.unblockedToast'),'ok');showBlacklist();
};

document.addEventListener('DOMContentLoaded',()=>{
  // i18n: переводим статический HTML согласно автоопределённому/выбранному языку
  try{ if(window.I18N) I18N.apply(document); }catch(e){}
  try{ if(window.syncLangSelectors) syncLangSelectors(); }catch(e){}
  // Заполняем SVG-иконки по data-ti атрибутам
  document.querySelectorAll('[data-ti]').forEach(el=>{
    const name=el.dataset.ti;
    const svg=TI[name];
    if(svg) el.innerHTML=`<span class="ti">${svg}</span>`;
  });
  q('lPass')?.addEventListener('keydown',e=>{if(e.key==='Enter') doLogin();});
  q('lName')?.addEventListener('keydown',e=>{if(e.key==='Enter') doLogin();});
  q('rPass')?.addEventListener('keydown',e=>{if(e.key==='Enter') doRegister();});
  q('rName')?.addEventListener('keydown',e=>{if(e.key==='Enter') doRegister();});
  const pendingInvite=sessionStorage.getItem('pendingInvite');
  if(pendingInvite){showInviteHint();showInviteGuestLanding(pendingInvite);}
});
</script>


<!-- PWA INSTALL PROMPT -->
<div id="pwaInstallPrompt" class="pwa-install-prompt" aria-live="polite">
  <div class="pwa-install-icon">
    <img src="icon_tC_main.png" alt="">
  </div>
  <div class="pwa-install-copy">
    <div class="pwa-install-title">Установить trueCORD?</div>
    <div class="pwa-install-text">Открывайте как приложение: быстрее, удобнее и без лишних вкладок браузера.</div>
    <div class="pwa-install-ios" id="pwaInstallIosHint">На iPhone/iPad: нажмите «Поделиться» → «На экран Домой».</div>
  </div>
  <div class="pwa-install-actions">
    <button class="pwa-install-btn primary" id="pwaInstallBtn" onclick="installPwaApp()">Установить</button>
    <button class="pwa-install-btn ghost" onclick="hidePwaInstallPrompt(true)">${t('common.later')}</button>
  </div>
  <button class="pwa-install-close" onclick="hidePwaInstallPrompt(true)" title="Закрыть">×</button>
</div>

<!-- NOTIF PANEL -->
<div id="notifPanel">
  <div class="np-header">
    <span class="np-header-title" style="display:flex;align-items:center;gap:7px"><span style="display:flex;align-items:center;opacity:.8"><svg viewBox="0 0 24 24" width="15" height="15"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.8" fill="none" stroke-linecap="round"/></svg></span>Уведомления</span>
    <div class="np-header-actions">
      <button class="np-clear-all" onclick="notifPanelClearAll()">Очистить все</button>
      <button class="np-close" onclick="closeNotifPanel()" style="display:flex;align-items:center;justify-content:center"><svg viewBox="0 0 24 24" width="11" height="11"><line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
    </div>
  </div>
  <div class="np-list" id="notifPanelList">
    <div class="np-empty">Нет уведомлений</div>
  </div>
</div>
</body>
</html>