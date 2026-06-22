// trueCORD Service Worker — PWA offline support
// v2: HTML/JS всегда из сети (чтобы переводы и разметка обновлялись сразу),
// в кэш кладём только статичные ассеты (иконки). Это убирает баг с "застывшими"
// сырыми ключами i18n из-за устаревшего кэша index.php / i18n.js.
const CACHE_NAME = 'truecord-v31';
const STATIC_ASSETS = [
  './icon_tC_main.png',
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE_NAME).then(cache =>
      cache.addAll(STATIC_ASSETS).catch(() => {})
    )
  );
  self.skipWaiting();
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', e => {
  const url = new URL(e.request.url);
  const path = url.pathname;

  // Никогда не кэшируем API и загрузки
  if (path.includes('truecord_api.php') || path.includes('/uploads/')) {
    return;
  }

  // HTML-документ и динамика (index.php, i18n.js, любые .php/.js) — ТОЛЬКО из сети.
  // Кэш используем лишь как аварийный фоллбэк при полном офлайне.
  const isDynamic =
    e.request.mode === 'navigate' ||
    path.endsWith('.php') ||
    path.endsWith('/') ||
    path.endsWith('i18n.js') ||
    path.endsWith('.js');

  if (isDynamic) {
    e.respondWith(
      fetch(e.request, { cache: 'no-store' }).catch(() => caches.match(e.request))
    );
    return;
  }

  // Статичные ассеты (картинки, шрифты) — cache-first для скорости.
  e.respondWith(
    caches.match(e.request).then(hit =>
      hit || fetch(e.request).then(res => {
        if (res && res.status === 200 && e.request.method === 'GET') {
          const clone = res.clone();
          caches.open(CACHE_NAME).then(c => c.put(e.request, clone));
        }
        return res;
      }).catch(() => hit)
    )
  );
});
