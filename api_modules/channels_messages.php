<?php
// trueCORD API module (auto-split). Included within the main request scope of truecord_api.php.
// Shares: $db (PDO), $d (request array), $action (string) and all helper functions.
// Served only via include from the router; guard prevents direct/standalone execution.
if (!isset($db) || !isset($d)) { return; }
// --- handlers ---
    if ($action === 'get_channels') {
        $sid    = (int)($d['serverId'] ?? 1);
        $myRole = getRole($db, $sid, $meId);
        $s = $db->prepare("SELECT * FROM channels WHERE server_id=? ORDER BY position,id");
        $s->execute([$sid]); $chs = [];
        foreach ($s->fetchAll() as $ch) {
            $pr = (string)($ch['perm_read'] ?? 'all');
            if ($pr === 'admins' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId) continue;
            if (defined('CHANNEL_LIST_MODE') && CHANNEL_LIST_MODE === 'public_only' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId && (int)($ch['is_public'] ?? 0) !== 1) continue;
            $chs[] = buildChannel($db, $ch, $myRole, $meId, $meName);
        }
        apiOk(['channels' => $chs, 'myRole' => $myRole]);
    }

    // ══ create_channel ═══════════════════════════════════════════
    if ($action === 'create_channel') {
        $sid    = (int)($d['serverId'] ?? 1); $myRole = getRole($db, $sid, $meId);
        if (!permissionAllows(CREATE_CHANNEL_PERMISSION, $myRole, $meName, $meGlobalRole, (int)($me['validated'] ?? 0) === 1)) apiFail('Нет прав для создания каналов');
        tc_rateLimit($db, 'create_channel', $meId, 10, 60); // не более 10 каналов в минуту
        $chType = (string)($d['type'] ?? 'text');
        if (!in_array($chType, ['text','app'], true)) $chType = 'text';
        $name  = $chType === 'app'
            ? trim((string)($d['name'] ?? ''))
            : (string)preg_replace('/[^a-z0-9_\-а-яё]/ui', '', strtolower(trim((string)($d['name'] ?? ''))));
        $topic = trim((string)($d['topic'] ?? '')); $desc = trim((string)($d['description'] ?? ''));
        $pr    = (string)($d['permRead'] ?? 'all'); $pw = (string)($d['permWrite'] ?? 'all');
        $appIcon  = trim((string)($d['appIcon'] ?? ''));
        $appHtml  = (string)($d['appHtml'] ?? '');
        $isPublic = (int)($d['isPublic'] ?? 0);
        if (!$name) apiFail('Введите название канала');
        if (mb_strlen($desc) > 3000) apiFail('Описание: максимум 3000 символов');
        if (!in_array($pr, ['all','members','admins'], true)) apiFail('Неверное permRead');
        if (!in_array($pw, ['all','members','admins'], true)) apiFail('Неверное permWrite');
        ensureMember($db, $sid, $meId);
        $myRole = getRole($db, $sid, $meId);
        if ($chType === 'app' && mb_strlen($appHtml) > 1048576) apiFail('HTML мини-приложения: макс. 1 МБ');
        $s = $db->prepare("SELECT id FROM channels WHERE server_id=? AND name=?"); $s->execute([$sid, $name]);
        if ($s->fetch()) apiFail('Канал с таким именем уже существует');
        $db->prepare("INSERT INTO channels(server_id,owner_id,name,topic,description,avatar,perm_read,perm_write,type,app_icon,app_html,is_public,app_ref_channel_id,created_at) VALUES(?,?,?,?,?,'',?,?,?,?,?,?,0,?)")
           ->execute([$sid, $meId, $name, $topic, $desc, $pr, $pw, $chType, $appIcon, $appHtml, $isPublic, nowSec()]);
        $id    = (int)$db->lastInsertId();
        $newCh = ['id'=>$id,'server_id'=>$sid,'owner_id'=>$meId,'name'=>$name,'topic'=>$topic,'description'=>$desc,'avatar'=>'','perm_read'=>$pr,'perm_write'=>$pw,'type'=>$chType,'app_icon'=>$appIcon,'app_html'=>$appHtml,'is_public'=>$isPublic,'app_ref_channel_id'=>0];
        apiOk(['channel' => buildChannel($db, $newCh, $myRole, $meId, $meName)]);
    }

    // ══ update_channel ═══════════════════════════════════════════
    if ($action === 'update_channel') {
        $chId = (int)($d['channelId'] ?? 0);
        $s    = $db->prepare("SELECT id,server_id,owner_id,name,topic,description,avatar,perm_read,perm_write,is_public,app_ref_channel_id FROM channels WHERE id=?"); $s->execute([$chId]); $ch = $s->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        if (!canManageCh($db, $ch, $meId, $meName, $myRole)) apiFail('Нет прав');
        $chType = (string)($d['type'] ?? ($ch['type'] ?? 'text'));
        if (!in_array($chType, ['text','app'], true)) $chType = 'text';
        $name  = $chType === 'app'
            ? trim((string)($d['name'] ?? ''))
            : (string)preg_replace('/[^a-z0-9_\-а-яё]/ui', '', strtolower(trim((string)($d['name'] ?? ''))));
        $topic = trim((string)($d['topic'] ?? '')); $desc = trim((string)($d['description'] ?? ''));
        $pr    = (string)($d['permRead'] ?? 'all'); $pw = (string)($d['permWrite'] ?? 'all');
        $appIcon  = trim((string)($d['appIcon'] ?? ($ch['app_icon'] ?? '')));
        $appHtml  = isset($d['appHtml']) ? (string)$d['appHtml'] : (string)($ch['app_html'] ?? '');
        $isPublic = (int)($d['isPublic'] ?? ($ch['is_public'] ?? 0));
        if (!$name) apiFail('Введите название');
        if (mb_strlen($desc) > 3000) apiFail('Макс. 3000 символов');
        if ($chType === 'app' && mb_strlen($appHtml) > 1048576) apiFail('HTML мини-приложения: макс. 1 МБ');
        $db->prepare("UPDATE channels SET name=?,topic=?,description=?,perm_read=?,perm_write=?,type=?,app_icon=?,app_html=?,is_public=? WHERE id=?")
           ->execute([$name, $topic, $desc, $pr, $pw, $chType, $appIcon, $appHtml, $isPublic, $chId]);
        $ch['name']=$name; $ch['topic']=$topic; $ch['description']=$desc; $ch['perm_read']=$pr;
        $ch['perm_write']=$pw; $ch['type']=$chType; $ch['app_icon']=$appIcon; $ch['app_html']=$appHtml;
        $ch['is_public']=$isPublic;
        apiOk(['channel' => buildChannel($db, $ch, $myRole, $meId, $meName)]);
    }

    // ══ update_channel_avatar ════════════════════════════════════
    if ($action === 'update_channel_avatar') {
        $chId = (int)($d['channelId'] ?? 0);
        $s    = $db->prepare("SELECT id,server_id,owner_id FROM channels WHERE id=?"); $s->execute([$chId]); $ch = $s->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        if (!canManageCh($db, $ch, $meId, $meName, $myRole)) apiFail('Нет прав');
        $url = doUpload($db, $meId, (string)($d['image'] ?? ''), (string)($d['mime'] ?? 'image/jpeg'));
        $db->prepare("UPDATE channels SET avatar=? WHERE id=?")->execute([$url, $chId]);
        apiOk(['avatar' => $url, 'channelId' => $chId]);
    }

    // ══ delete_channel ═══════════════════════════════════════════
    if ($action === 'delete_channel') {
        $sid  = (int)($d['serverId'] ?? 0); $chId = (int)($d['channelId'] ?? 0);
        $s    = $db->prepare("SELECT id,server_id,owner_id FROM channels WHERE id=?"); $s->execute([$chId]); $ch = $s->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, $sid, $meId);
        if (!canManageCh($db, $ch, $meId, $meName, $myRole)) apiFail('Нет прав');
        $cnt = $db->prepare("SELECT COUNT(*) FROM channels WHERE server_id=?"); $cnt->execute([$sid]);
        if ((int)$cnt->fetchColumn() <= 1) apiFail('Нельзя удалить последний канал');
        $db->exec('BEGIN');
        try {
            $msgIds = $db->prepare("SELECT id FROM messages WHERE channel_id=?"); $msgIds->execute([$chId]);
            $mids   = array_map('intval', array_column($msgIds->fetchAll(), 'id'));
            if (!empty($mids)) {
                $mph = implode(',', array_fill(0, count($mids), '?'));
                $db->prepare("DELETE FROM reactions WHERE message_id IN($mph)")->execute($mids);
                $db->prepare("DELETE FROM message_comments WHERE message_id IN($mph)")->execute($mids);
            }
            $db->prepare("DELETE FROM messages WHERE channel_id=?")->execute([$chId]);
            $db->prepare("DELETE FROM typing_signals WHERE channel_id=?")->execute([$chId]);
            $db->prepare("DELETE FROM channels WHERE id=?")->execute([$chId]);
            $db->exec('COMMIT');
        } catch (Exception $e) {
            try { $db->exec('ROLLBACK'); } catch (Exception $e2) {}
            apiFail('Ошибка: ' . $e->getMessage());
        }
        apiOk([]);
    }

    // ══ mark_channel_read ════════════════════════════════════════
    if ($action === 'mark_channel_read') { apiOk([]); }

    // ══ messages ═════════════════════════════════════════════════
    if ($action === 'messages') {
        $chId   = (int)($d['channelId'] ?? 1);
        $since  = (int)($d['since'] ?? 0);
        $before = isset($d['before']) ? (int)$d['before'] : null;
        $limit  = min(50, max(1, (int)($d['limit'] ?? 10)));

        $q = $db->prepare("SELECT server_id,owner_id,perm_read FROM channels WHERE id=?"); $q->execute([$chId]); $ch = $q->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        $pr     = (string)($ch['perm_read'] ?? 'all');
        if ($pr === 'admins' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId) apiFail('Нет доступа');

        if ($before !== null) {
            $s       = $db->prepare("SELECT * FROM messages WHERE channel_id=? AND id<? ORDER BY id DESC LIMIT ?");
            $s->execute([$chId, $before, $limit]);
            $rows    = array_reverse($s->fetchAll()); $hasMore = count($rows) >= $limit;
        } elseif ($since > 0) {
            $s       = $db->prepare("SELECT * FROM messages WHERE channel_id=? AND id>? ORDER BY id ASC LIMIT 100");
            $s->execute([$chId, $since]);
            $rows    = $s->fetchAll(); $hasMore = false;
        } else {
            $s       = $db->prepare("SELECT * FROM messages WHERE channel_id=? ORDER BY id DESC LIMIT ?");
            $s->execute([$chId, $limit]);
            $rows    = array_reverse($s->fetchAll());
            $totalQ  = $db->prepare("SELECT COUNT(*) FROM messages WHERE channel_id=?"); $totalQ->execute([$chId]);
            $hasMore = (int)$totalQ->fetchColumn() > $limit;
        }

        apiOk(['messages' => buildMsgBatch($db, $rows, $meId), 'hasMore' => $hasMore]);
    }

    // ══ search_messages ══════════════════════════════════════════
    // Поиск по содержимому (text) и/или автору (user_name) в пределах канала.
    // Параметры: channelId, query (строка), by ('all'|'text'|'author'), limit.
    if ($action === 'search_messages') {
        $chId  = (int)($d['channelId'] ?? 1);
        $query = trim((string)($d['query'] ?? ''));
        $by    = (string)($d['by'] ?? 'all');
        if (!in_array($by, ['all','text','author'], true)) $by = 'all';
        $limit = min(100, max(1, (int)($d['limit'] ?? 50)));
        if (mb_strlen($query) < 1) apiFail('Введите запрос для поиска');
        if (mb_strlen($query) > 200) apiFail('Слишком длинный запрос');

        // Проверка доступа к каналу (как в messages)
        $q = $db->prepare("SELECT server_id,owner_id,perm_read FROM channels WHERE id=?");
        $q->execute([$chId]); $ch = $q->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        $pr     = (string)($ch['perm_read'] ?? 'all');
        if ($pr === 'admins' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId) apiFail('Нет доступа');

        // Экранируем спецсимволы LIKE, ищем регистронезависимо подстроку
        $esc  = str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $query);
        $like = '%' . $esc . '%';

        if ($by === 'text') {
            $sql = "SELECT * FROM messages WHERE channel_id=? AND deleted=0 AND type='msg'
                    AND text LIKE ? ESCAPE '\\' ORDER BY id DESC LIMIT ?";
            $params = [$chId, $like, $limit];
        } elseif ($by === 'author') {
            $sql = "SELECT * FROM messages WHERE channel_id=? AND deleted=0 AND type='msg'
                    AND user_name LIKE ? ESCAPE '\\' ORDER BY id DESC LIMIT ?";
            $params = [$chId, $like, $limit];
        } else {
            $sql = "SELECT * FROM messages WHERE channel_id=? AND deleted=0 AND type='msg'
                    AND (text LIKE ? ESCAPE '\\' OR user_name LIKE ? ESCAPE '\\')
                    ORDER BY id DESC LIMIT ?";
            $params = [$chId, $like, $like, $limit];
        }
        $s = $db->prepare($sql); $s->execute($params);
        $rows = $s->fetchAll();
        apiOk(['messages' => buildMsgBatch($db, $rows, $meId), 'query' => $query, 'by' => $by, 'count' => count($rows)]);
    }


    // ══ send ═════════════════════════════════════════════════════
    if ($action === 'send') {
        $chId  = (int)($d['channelId'] ?? 1); $text = trim((string)($d['text'] ?? '')); $image = trim((string)($d['image'] ?? '')); $rpTo = !empty($d['replyTo']) ? (int)$d['replyTo'] : null;
        if (!$text && !$image) apiFail('Сообщение пустое');
        tc_rateLimit($db, 'send', $meId, 30, 10); // не более 30 сообщений за 10 секунд
        if (mb_strlen($text) > 4000) apiFail('Сообщение слишком длинное (макс. 4000 символов)');
        if ($image && !$meValidated) apiFail('Для отправки файлов необходима верификация аккаунта');
        $q = $db->prepare("SELECT perm_write,owner_id,server_id FROM channels WHERE id=?"); $q->execute([$chId]); $ch = $q->fetch();
        if ($ch) {
            $myRole = getRole($db, (int)$ch['server_id'], $meId);
            $pw     = (string)($ch['perm_write'] ?? 'all');
            if ($pw === 'admins' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId) apiFail('Запись запрещена');
            if (isUserMuted($db, (int)$ch['server_id'], $meId)) apiFail('Вы заглушены на этом сервере');
            if (preg_match('/@(everyone|here)/i', $text) && !isMod($myRole) && $meName !== OWNER_NAME && $meGlobalRole !== 'super_admin')
                apiFail('Только модераторы могут использовать @everyone и @here');
        }
        $db->prepare("DELETE FROM typing_signals WHERE channel_id=? AND user_id=?")->execute([$chId, $meId]);
        $at = nowMs();
        $db->prepare("INSERT INTO messages(channel_id,user_id,user_name,text,image,reply_to,type,deleted,at) VALUES(?,?,?,?,?,?,'msg',0,?)")->execute([$chId, $meId, $meName, $text, $image, $rpTo, $at]);
        $id   = (int)$db->lastInsertId();
        $s    = $db->prepare("SELECT * FROM messages WHERE id=?"); $s->execute([$id]); $row = $s->fetch();
        $msgs = buildMsgBatch($db, [$row], $meId);
        apiOk(['msg' => $msgs[0]]);
    }

    // ══ edit_message ═════════════════════════════════════════════
    if ($action === 'edit_message') {
        $msgId = (int)($d['messageId'] ?? 0); $newText = trim((string)($d['text'] ?? ''));
        if (!$msgId) apiFail('Укажите messageId'); if (!$newText) apiFail('Текст не может быть пустым');
        if (mb_strlen($newText) > 4000) apiFail('Сообщение слишком длинное');
        $s   = $db->prepare("SELECT id,user_id,channel_id,deleted FROM messages WHERE id=?"); $s->execute([$msgId]); $msg = $s->fetch();
        if (!$msg) apiFail('Сообщение не найдено'); if ((int)$msg['deleted']) apiFail('Нельзя редактировать удалённое сообщение');
        if ((int)$msg['user_id'] !== $meId && $meName !== OWNER_NAME && $meGlobalRole !== 'super_admin') apiFail('Можно редактировать только свои сообщения');
        $db->prepare("UPDATE messages SET text=?,edited=1 WHERE id=?")->execute([$newText, $msgId]);
        apiOk(['messageId'=>$msgId,'text'=>$newText,'textHtml'=>linkify($newText)]);
    }

    // ══ forward_message ══════════════════════════════════════════
    if ($action === 'forward_message') {
        $msgId      = (int)($d['messageId'] ?? 0); $targetChId = (int)($d['targetChannelId'] ?? 0);
        $s          = $db->prepare("SELECT * FROM messages WHERE id=? AND deleted=0"); $s->execute([$msgId]); $origMsg = $s->fetch();
        if (!$origMsg) apiFail('Сообщение не найдено');
        $q2 = $db->prepare("SELECT perm_write,owner_id,server_id FROM channels WHERE id=?"); $q2->execute([$targetChId]); $ch = $q2->fetch();
        if (!$ch) apiFail('Канал не найден');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        if (isUserMuted($db, (int)$ch['server_id'], $meId)) apiFail('Вы заглушены на этом сервере');
        $at = nowMs();
        $db->prepare("INSERT INTO messages(channel_id,user_id,user_name,text,image,reply_to,type,deleted,at) VALUES(?,?,?,?,?,?,'msg',0,?)")->execute([$targetChId, $meId, $meName, (string)($origMsg['text']??''), (string)($origMsg['image']??''), null, $at]);
        $id   = (int)$db->lastInsertId();
        $s2   = $db->prepare("SELECT * FROM messages WHERE id=?"); $s2->execute([$id]); $row = $s2->fetch();
        $msgs = buildMsgBatch($db, [$row], $meId);
        $msg  = $msgs[0]; $msg['forwardedFromId'] = (int)$origMsg['user_id']; $msg['forwardedFromName'] = (string)$origMsg['user_name'];
        apiOk(['msg' => $msg]);
    }

    // ══ delete_message ═══════════════════════════════════════════
    if ($action === 'delete_message') {
        $msgId = (int)($d['messageId'] ?? 0);
        $s     = $db->prepare("SELECT id,user_id,channel_id FROM messages WHERE id=?"); $s->execute([$msgId]); $msg = $s->fetch();
        if (!$msg) apiFail('Сообщение не найдено');
        $isMine = (int)$msg['user_id'] === $meId;
        $chq    = $db->prepare("SELECT server_id,owner_id FROM channels WHERE id=?"); $chq->execute([(int)$msg['channel_id']]); $ch2 = $chq->fetch();
        $canDel = $isMine || $meName === OWNER_NAME || $meGlobalRole === 'super_admin';
        if (!$canDel && $ch2) { $myRole = getRole($db, (int)$ch2['server_id'], $meId); $canDel = isMod($myRole) || (int)$ch2['owner_id'] === $meId; }
        if (!$canDel) apiFail('Нет прав на удаление');
        $db->prepare("UPDATE messages SET deleted=1,text='',image='' WHERE id=?")->execute([$msgId]);
        $db->prepare("DELETE FROM reactions WHERE message_id=?")->execute([$msgId]);
        apiOk(['messageId' => $msgId]);
    }

    // ══ add_reaction ═════════════════════════════════════════════
    if ($action === 'add_reaction') {
        $msgId = (int)($d['messageId'] ?? 0); $emoji = mb_substr(trim((string)($d['emoji'] ?? '')), 0, 8);
        if (!$msgId || !$emoji) apiFail('Укажите messageId и emoji');
        $s = $db->prepare("SELECT id,channel_id,deleted FROM messages WHERE id=?"); $s->execute([$msgId]); $msg = $s->fetch();
        if (!$msg) apiFail('Сообщение не найдено'); if ((int)($msg['deleted'] ?? 0)) apiFail('Нельзя реагировать на удалённое сообщение');
        $chk = $db->prepare("SELECT id FROM reactions WHERE message_id=? AND user_id=? AND emoji=?"); $chk->execute([$msgId, $meId, $emoji]); $ex = $chk->fetch();
        if ($ex) {
            $db->prepare("DELETE FROM reactions WHERE message_id=? AND user_id=? AND emoji=?")->execute([$msgId, $meId, $emoji]);
        } else {
            $cnt2 = $db->prepare("SELECT COUNT(DISTINCT emoji) FROM reactions WHERE message_id=?"); $cnt2->execute([$msgId]);
            if ((int)$cnt2->fetchColumn() >= 20) apiFail('Максимум 20 различных реакций на сообщение');
            $db->prepare("INSERT OR IGNORE INTO reactions(message_id,user_id,emoji,created_at) VALUES(?,?,?,?)")->execute([$msgId, $meId, $emoji, nowSec()]);
        }
        $reacts = getReactionsForMsgs($db, [$msgId], $meId);
        apiOk(['reactions'=>$reacts[$msgId]??[],'messageId'=>$msgId]);
    }

    // ══ get_reactions ════════════════════════════════════════════
    if ($action === 'get_reactions') {
        $chId = (int)($d['channelId'] ?? 0);
        $s    = $db->prepare("SELECT id FROM messages WHERE channel_id=? AND deleted=0"); $s->execute([$chId]);
        $ids  = array_map('intval', array_column($s->fetchAll(), 'id'));
        $out  = []; foreach (getReactionsForMsgs($db, $ids, $meId) as $mid => $r) $out[(string)$mid] = $r;
        apiOk(['reactions' => $out]);
    }

    // ══ get_reaction_users ═══════════════════════════════════════
    if ($action === 'get_reaction_users') {
        $msgId = (int)($d['messageId'] ?? 0); $emoji = mb_substr(trim((string)($d['emoji'] ?? '')), 0, 8);
        if (!$msgId || !$emoji) apiFail('Укажите messageId и emoji');
        $s = $db->prepare("SELECT u.id,u.name,u.avatar FROM reactions r JOIN users u ON u.id=r.user_id WHERE r.message_id=? AND r.emoji=? ORDER BY r.created_at ASC");
        $s->execute([$msgId, $emoji]); $users = [];
        foreach ($s->fetchAll() as $u) $users[] = ['id'=>(int)$u['id'],'name'=>(string)$u['name'],'avatar'=>(string)($u['avatar']??'')];
        apiOk(['users'=>$users,'emoji'=>$emoji]);
    }

    // ══ upload ═══════════════════════════════════════════════════
    if ($action === 'upload') {
        if (!$meValidated) apiFail('Для загрузки файлов необходима верификация аккаунта');
        $url = doUpload($db, $meId, (string)($d['image'] ?? ''), (string)($d['mime'] ?? 'image/jpeg'), (string)($d['originalName'] ?? ''));
        apiOk(['url' => $url]);
    }

    // ══ update_avatar ════════════════════════════════════════════
    if ($action === 'update_avatar') {
        $url = doUpload($db, $meId, (string)($d['image'] ?? ''), (string)($d['mime'] ?? 'image/jpeg'));
        $db->prepare("UPDATE users SET avatar=? WHERE id=?")->execute([$url, $meId]);
        apiOk(['avatar' => $url]);
    }

    // ══ set_typing ═══════════════════════════════════════════════

    // ══ youtube_search ═══════════════════════════════════════════
    // Поиск видео на YouTube. С ключом (config: integrations.youtube_api_key)
    // — через официальный Data API; без ключа — парсит выдачу YouTube.
    if ($action === 'youtube_search') {
        $query = trim((string)($d['query'] ?? ''));
        if ($query === '') apiFail('Введите поисковый запрос');
        if (mb_strlen($query) > 150) apiFail('Слишком длинный запрос');
        tc_rateLimit($db, 'yt_search', $meId, 20, 30); // не чаще 20 за 30 сек

        $key = defined('YOUTUBE_API_KEY') ? YOUTUBE_API_KEY : '';
        $results = [];

        if ($key !== '') {
            // ── официальный YouTube Data API v3 ──
            $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=18'
                 . '&q=' . rawurlencode($query) . '&key=' . rawurlencode($key);
            $raw = tc_httpGet($url);
            $j   = json_decode((string)$raw, true);
            if (is_array($j) && !empty($j['items'])) {
                foreach ($j['items'] as $it) {
                    $vid = $it['id']['videoId'] ?? '';
                    if (!$vid) continue;
                    $sn = $it['snippet'] ?? [];
                    $results[] = [
                        'id'        => $vid,
                        'title'     => (string)($sn['title'] ?? ''),
                        'author'    => (string)($sn['channelTitle'] ?? ''),
                        'thumbnail' => "https://i.ytimg.com/vi/{$vid}/mqdefault.jpg",
                    ];
                }
            } elseif (is_array($j) && !empty($j['error'])) {
                apiFail('YouTube API: ' . (string)($j['error']['message'] ?? 'ошибка'));
            }
        } else {
            // ── без ключа: парсим страницу результатов ──
            $url = 'https://www.youtube.com/results?search_query=' . rawurlencode($query) . '&hl=ru';
            $html = tc_httpGet($url);
            if ($html && preg_match('/var ytInitialData = (\{.*?\});<\/script>/s', $html, $m)) {
                $data = json_decode($m[1], true);
                if (is_array($data)) {
                    $results = tc_ytExtract($data);
                }
            }
            // запасной грубый парс, если структура не нашлась
            if (!$results && $html && preg_match_all('/"videoRenderer":\{"videoId":"([\w-]{11})".*?"text":"([^"]+)"/s', $html, $mm, PREG_SET_ORDER)) {
                $seen = [];
                foreach ($mm as $one) {
                    $vid = $one[1];
                    if (isset($seen[$vid])) continue; $seen[$vid] = 1;
                    $results[] = [
                        'id'        => $vid,
                        'title'     => tc_jsonUnescape($one[2]),
                        'author'    => '',
                        'thumbnail' => "https://i.ytimg.com/vi/{$vid}/mqdefault.jpg",
                    ];
                    if (count($results) >= 18) break;
                }
            }
        }

        apiOk(['results' => array_slice($results, 0, 18), 'query' => $query, 'keyed' => ($key !== '')]);
    }
