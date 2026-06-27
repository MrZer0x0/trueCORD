<?php
// trueCORD API module (auto-split). Included within the main request scope of truecord_api.php.
// Shares: $db (PDO), $d (request array), $action (string) and all helper functions.
// Served only via include from the router; guard prevents direct/standalone execution.
if (!isset($db) || !isset($d)) { return; }
// --- handlers ---
    if ($action === 'voice_get_rooms') {
        $sid = (int)($d['serverId'] ?? 1);
        $check = $db->prepare("SELECT id FROM voice_rooms WHERE server_id=?"); $check->execute([$sid]);
        if (!$check->fetch()) {
            $t = nowSec();
            $db->prepare("INSERT INTO voice_rooms(server_id,name,position,created_at) VALUES(?,?,0,?)")->execute([$sid,'Main',$t]);
            $db->prepare("INSERT INTO voice_rooms(server_id,name,position,created_at) VALUES(?,?,1,?)")->execute([$sid,'Second',$t]);
        }
        $myRole = getRole($db, $sid, $meId);
        $rq     = $db->prepare("SELECT id,name,position FROM voice_rooms WHERE server_id=? ORDER BY position,id"); $rq->execute([$sid]); $rooms = [];
        foreach ($rq->fetchAll() as $r) {
            $rid = (int)$r['id'];
            $rooms[] = ['id'=>$rid,'name'=>(string)$r['name'],'position'=>(int)$r['position'],'participants'=>getVoiceParticipants($db,$rid),'canManage'=>isAdmin($myRole)||$meName===OWNER_NAME];
        }
        apiOk(['rooms'=>$rooms,'myRole'=>$myRole]);
    }

    if ($action === 'voice_create_room') {
        $sid = (int)($d['serverId'] ?? 0); $myRole = getRole($db, $sid, $meId);
        if (!permissionAllows(CREATE_VOICE_PERMISSION, $myRole, $meName, $meGlobalRole, (int)($me['validated'] ?? 0) === 1)) apiFail('Нет прав');
        $name = trim((string)($d['name'] ?? '')); if (!$name) apiFail('Введите название'); if (mb_strlen($name) > 32) apiFail('Макс. 32 символа');
        $pos = $db->prepare("SELECT COALESCE(MAX(position),0)+1 FROM voice_rooms WHERE server_id=?"); $pos->execute([$sid]); $nextPos = (int)$pos->fetchColumn();
        $db->prepare("INSERT INTO voice_rooms(server_id,name,position,created_at) VALUES(?,?,?,?)")->execute([$sid,$name,$nextPos,nowSec()]);
        $id = (int)$db->lastInsertId();
        apiOk(['room'=>['id'=>$id,'name'=>$name,'position'=>$nextPos,'participants'=>[],'canManage'=>true]]);
    }

    if ($action === 'voice_update_room') {
        $rid = (int)($d['roomId'] ?? 0); $rq = $db->prepare("SELECT id,server_id FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $room = $rq->fetch();
        if (!$room) apiFail('Комната не найдена');
        $myRole = getRole($db, (int)$room['server_id'], $meId); if (!isAdmin($myRole) && $meName !== OWNER_NAME && $meGlobalRole !== 'super_admin' && $meGlobalRole !== 'project_admin') apiFail('Нет прав');
        $name = trim((string)($d['name'] ?? '')); if (!$name) apiFail('Введите название'); if (mb_strlen($name) > 32) apiFail('Макс. 32 символа');
        $db->prepare("UPDATE voice_rooms SET name=? WHERE id=?")->execute([$name,$rid]);
        apiOk(['roomId'=>$rid,'name'=>$name]);
    }

    if ($action === 'voice_delete_room') {
        $rid = (int)($d['roomId'] ?? 0); $rq = $db->prepare("SELECT id,server_id FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $room = $rq->fetch();
        if (!$room) apiFail('Комната не найдена');
        $myRole = getRole($db, (int)$room['server_id'], $meId); if (!isAdmin($myRole) && $meName !== OWNER_NAME && $meGlobalRole !== 'super_admin' && $meGlobalRole !== 'project_admin') apiFail('Нет прав');
        $cnt = $db->prepare("SELECT COUNT(*) FROM voice_rooms WHERE server_id=?"); $cnt->execute([(int)$room['server_id']]);
        if ((int)$cnt->fetchColumn() <= 1) apiFail('Нельзя удалить последнюю комнату');
        $db->prepare("DELETE FROM voice_participants WHERE room_id=?")->execute([$rid]);
        $db->prepare("DELETE FROM voice_signals WHERE room_id=?")->execute([$rid]);
        $db->prepare("DELETE FROM voice_events WHERE room_id=?")->execute([$rid]);
        $db->prepare("DELETE FROM voice_rooms WHERE id=?")->execute([$rid]);
        apiOk(['roomId' => $rid]);
    }

    if ($action === 'voice_join') {
        if (!$meValidated) apiFail('Для голосового чата необходима верификация аккаунта');
        $rid = (int)($d['roomId'] ?? 0); if ($rid <= 0) apiFail('Укажите roomId');
        $rq  = $db->prepare("SELECT id,server_id,name FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $rm = $rq->fetch();
        if (!$rm) apiFail('Комната не найдена');
        if (isUserMuted($db, (int)$rm['server_id'], $meId)) apiFail('Вы заглушены на этом сервере');
        $now = nowSec(); $thr = $now - 30;

        $db->prepare("DELETE FROM voice_signals WHERE room_id=? AND to_user_id=? AND created_at<?")
           ->execute([$rid, $meId, $now - 30]);

        $db->prepare("DELETE FROM voice_participants WHERE user_id=?")->execute([$meId]);
        $db->prepare("INSERT OR REPLACE INTO voice_participants(room_id,user_id,last_ping,muted) VALUES(?,?,?,0)")->execute([$rid,$meId,$now]);
        $db->prepare("INSERT INTO voice_events(room_id,user_id,user_name,event_type,created_at) VALUES(?,?,?,'join',?)")->execute([$rid,$meId,$meName,$now]);

        $p = $db->prepare("SELECT vp.user_id,u.name,u.avatar FROM voice_participants vp JOIN users u ON u.id=vp.user_id WHERE vp.room_id=? AND vp.user_id!=? AND vp.last_ping>=?");
        $p->execute([$rid,$meId,$thr]); $existing = [];
        foreach ($p->fetchAll() as $r) $existing[] = ['userId'=>(int)$r['user_id'],'name'=>(string)$r['name'],'avatar'=>(string)($r['avatar']??'')];

        $newPeerData = json_encode([
            'userId' => $meId,
            'name'   => $meName,
            'avatar' => (string)($me['avatar'] ?? ''),
        ]);
        foreach ($existing as $ep) {
            $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,'new-peer',?,?)")
               ->execute([$rid, $meId, (int)$ep['userId'], $newPeerData, $now]);
        }

        apiOk(['roomId'=>$rid,'existing'=>$existing,'roomName'=>(string)$rm['name']]);
    }

    if ($action === 'voice_leave') {
        $vpQ = $db->prepare("SELECT room_id FROM voice_participants WHERE user_id=?"); $vpQ->execute([$meId]); $vp = $vpQ->fetch();
        if ($vp) {
            $now = nowSec();
            $db->prepare("INSERT INTO voice_events(room_id,user_id,user_name,event_type,created_at) VALUES(?,?,?,'leave',?)")->execute([(int)$vp['room_id'],$meId,$meName,$now]);
            $db->prepare("DELETE FROM voice_signals WHERE room_id=? AND from_user_id=?")
               ->execute([(int)$vp['room_id'], $meId]);
        }
        $db->prepare("DELETE FROM voice_participants WHERE user_id=?")->execute([$meId]);
        apiOk([]);
    }

    if ($action === 'voice_set_mute') {
        $rid = (int)($d['roomId'] ?? 0); $muted = (int)($d['muted'] ?? 0);
        if ($rid > 0) $db->prepare("UPDATE voice_participants SET muted=? WHERE room_id=? AND user_id=?")->execute([$muted,$rid,$meId]);
        apiOk([]);
    }

    if ($action === 'voice_ping') {
        $rid = (int)($d['roomId'] ?? 0);
        if ($rid > 0) $db->prepare("UPDATE voice_participants SET last_ping=? WHERE room_id=? AND user_id=?")->execute([nowSec(),$rid,$meId]);
        apiOk([]);
    }

    if ($action === 'voice_signal') {
        $rid = (int)($d['roomId'] ?? 0); $toId = (int)($d['toUserId'] ?? 0); $type = (string)($d['type'] ?? ''); $data = (string)($d['data'] ?? '');
        if (!$rid||!$toId||!$type||!$data) apiFail('Неверные параметры');
        if (!in_array($type, ['offer','answer','ice-candidate','ice-restart','renegotiate','reconnect','new-peer','stream-started','stream-stopped','stream-request','stream-unrequest','stream-reaction','voice-kicked','voice-force-mute'], true)) apiFail('Неверный тип');
        $now = nowSec();
        $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,?,?,?)")->execute([$rid,$meId,$toId,$type,$data,$now]);
        $db->prepare("DELETE FROM voice_signals WHERE created_at<?")->execute([$now-180]);
        apiOk([]);
    }

    // Совместный просмотр: рассылаем сигнал ВСЕМ участникам комнаты (кроме отправителя).
    if ($action === 'watch_signal') {
        $rid = (int)($d['roomId'] ?? 0); $type = (string)($d['type'] ?? ''); $data = (string)($d['data'] ?? '');
        if (!$rid || !$type) apiFail('Неверные параметры');
        if (!in_array($type, ['watch-open','watch-close','watch-play','watch-pause','watch-seek','watch-state','watch-request-state','watch-emoji','watch-chat'], true)) apiFail('Неверный тип');
        if (mb_strlen($data) > 4000) apiFail('Слишком большие данные');
        $now = nowSec();
        // Получатели — все активные участники комнаты, кроме меня.
        $p = $db->prepare("SELECT user_id FROM voice_participants WHERE room_id=? AND user_id<>? AND last_ping>?");
        $p->execute([$rid, $meId, $now - 60]);
        $ins = $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,?,?,?)");
        foreach ($p->fetchAll() as $row) {
            $ins->execute([$rid, $meId, (int)$row['user_id'], $type, $data, $now]);
        }
        $db->prepare("DELETE FROM voice_signals WHERE created_at<?")->execute([$now - 180]);
        apiOk([]);
    }

    if ($action === 'voice_poll') {
        $rid = (int)($d['roomId'] ?? 0); $sinceId = (int)($d['sinceId'] ?? 0); if ($rid <= 0) apiFail('Укажите roomId');
        $now = nowSec();
        $db->prepare("UPDATE voice_participants SET last_ping=? WHERE room_id=? AND user_id=?")->execute([$now,$rid,$meId]);
        $s = $db->prepare("SELECT vs.id,vs.from_user_id,vs.type,vs.data,u.name FROM voice_signals vs JOIN users u ON u.id=vs.from_user_id WHERE vs.room_id=? AND vs.to_user_id=? AND vs.id>? ORDER BY vs.id ASC LIMIT 100");
        $s->execute([$rid,$meId,$sinceId]); $signals = []; $lastId = $sinceId;
        foreach ($s->fetchAll() as $r) {
            $signals[] = ['id'=>(int)$r['id'],'fromId'=>(int)$r['from_user_id'],'name'=>(string)$r['name'],'type'=>(string)$r['type'],'data'=>(string)$r['data']];
            $lastId = max($lastId, (int)$r['id']);
        }
        apiOk(['signals'=>$signals,'lastId'=>$lastId,'participants'=>getVoiceParticipants($db,$rid),'serverTime'=>$now]);
    }

    if ($action === 'voice_heartbeat') {
        $rid     = (int)($d['roomId'] ?? 0);
        $sinceId = (int)($d['sinceId'] ?? 0);
        $muted   = isset($d['muted']) ? (int)$d['muted'] : null;
        if ($rid <= 0) apiFail('Укажите roomId');
        $now = nowSec();

        if ($muted !== null) {
            $db->prepare("UPDATE voice_participants SET last_ping=?,muted=? WHERE room_id=? AND user_id=?")
               ->execute([$now, $muted, $rid, $meId]);
        } else {
            $db->prepare("UPDATE voice_participants SET last_ping=? WHERE room_id=? AND user_id=?")
               ->execute([$now, $rid, $meId]);
        }

        $s = $db->prepare("
            SELECT vs.id, vs.from_user_id, vs.type, vs.data, u.name
            FROM voice_signals vs
            JOIN users u ON u.id = vs.from_user_id
            WHERE vs.room_id=? AND vs.to_user_id=? AND vs.id>?
            ORDER BY vs.id ASC LIMIT 100
        ");
        $s->execute([$rid, $meId, $sinceId]);
        $signals = []; $lastId = $sinceId;
        foreach ($s->fetchAll() as $r) {
            $signals[] = [
                'id'     => (int)$r['id'],
                'fromId' => (int)$r['from_user_id'],
                'name'   => (string)$r['name'],
                'type'   => (string)$r['type'],
                'data'   => (string)$r['data'],
            ];
            $lastId = max($lastId, (int)$r['id']);
        }

        apiOk([
            'signals'      => $signals,
            'lastId'       => $lastId,
            'participants' => getVoiceParticipants($db, $rid),
            'serverTime'   => $now,
        ]);
    }

    // ── VOICE KICK (remove user from voice room) ──────────────────
    if ($action === 'voice_kick_user') {
        $targetId = (int)($d['targetId'] ?? 0);
        $rid      = (int)($d['roomId'] ?? 0);
        if (!$targetId || !$rid) apiFail('Неверные параметры');
        // Find server for permission check
        $rq = $db->prepare("SELECT server_id FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $rm = $rq->fetch();
        if (!$rm) apiFail('Комната не найдена');
        $sid = (int)$rm['server_id'];
        $myRole = getRole($db, $sid, $meId);
        $isGlobal = $meName === OWNER_NAME || $meGlobalRole === 'super_admin' || $meGlobalRole === 'project_admin';
        if (!isMod($myRole) && !$isGlobal) apiFail('Нет прав');
        // Remove participant
        $now = nowSec();
        $db->prepare("INSERT INTO voice_events(room_id,user_id,user_name,event_type,created_at) VALUES(?,?,(SELECT name FROM users WHERE id=?),'kicked',?)")
           ->execute([$rid, $targetId, $targetId, $now]);
        $db->prepare("DELETE FROM voice_signals WHERE room_id=? AND from_user_id=?")->execute([$rid, $targetId]);
        $db->prepare("DELETE FROM voice_participants WHERE user_id=? AND room_id=?")->execute([$targetId, $rid]);
        // Send a signal to the kicked user so they disconnect on client side
        $kickData = json_encode(['kickedBy' => $meId, 'kickedByName' => $meName]);
        $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,'voice-kicked',?,?)")
           ->execute([$rid, $meId, $targetId, $kickData, $now]);
        apiOk([]);
    }

    // ── VOICE MUTE (server-side force mute in voice) ──────────────
    if ($action === 'voice_mute_user') {
        $targetId = (int)($d['targetId'] ?? 0);
        $rid      = (int)($d['roomId'] ?? 0);
        $forceMute = (int)($d['forceMute'] ?? 1);
        if (!$targetId || !$rid) apiFail('Неверные параметры');
        $rq = $db->prepare("SELECT server_id FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $rm = $rq->fetch();
        if (!$rm) apiFail('Комната не найдена');
        $sid = (int)$rm['server_id'];
        $myRole = getRole($db, $sid, $meId);
        $isGlobal = $meName === OWNER_NAME || $meGlobalRole === 'super_admin' || $meGlobalRole === 'project_admin';
        if (!isMod($myRole) && !$isGlobal) apiFail('Нет прав');
        $now = nowSec();
        // Set force_muted flag on participant (with fallback if column missing)
        try {
            $db->prepare("UPDATE voice_participants SET muted=?, force_muted=? WHERE room_id=? AND user_id=?")->execute([$forceMute, $forceMute, $rid, $targetId]);
        } catch (Exception $e) {
            // force_muted column might not exist yet — add it and retry
            try { $db->exec("ALTER TABLE voice_participants ADD COLUMN force_muted INTEGER DEFAULT 0"); } catch (Exception $e2) {}
            $db->prepare("UPDATE voice_participants SET muted=?, force_muted=? WHERE room_id=? AND user_id=?")->execute([$forceMute, $forceMute, $rid, $targetId]);
        }
        // Send signal so client knows they're force-muted
        $muteData = json_encode(['mutedBy' => $meId, 'mutedByName' => $meName, 'forceMute' => $forceMute]);
        $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,'voice-force-mute',?,?)")
           ->execute([$rid, $meId, $targetId, $muteData, $now]);
        apiOk([]);
    }

    if ($action === 'voice_reconnect') {
        if (!$meValidated) apiFail('Для голосового чата необходима верификация аккаунта');
        $rid = (int)($d['roomId'] ?? 0); if ($rid <= 0) apiFail('Укажите roomId');
        $rq  = $db->prepare("SELECT id,server_id FROM voice_rooms WHERE id=?"); $rq->execute([$rid]); $rm = $rq->fetch();
        if (!$rm) apiFail('Комната не найдена');

        $now = nowSec(); $thr = $now - 30;

        $db->prepare("UPDATE voice_participants SET last_ping=? WHERE room_id=? AND user_id=?")->execute([$now, $rid, $meId]);

        $p = $db->prepare("SELECT user_id FROM voice_participants WHERE room_id=? AND user_id!=? AND last_ping>=?");
        $p->execute([$rid, $meId, $thr]);
        $otherIds = array_column($p->fetchAll(), 'user_id');

        $db->prepare("DELETE FROM voice_signals WHERE room_id=? AND to_user_id=? AND created_at<?")
           ->execute([$rid, $meId, $now - 10]);

        $reconnectData = json_encode(['userId' => $meId, 'name' => $meName]);
        foreach ($otherIds as $otherId) {
            $db->prepare("INSERT INTO voice_signals(room_id,from_user_id,to_user_id,type,data,created_at) VALUES(?,?,?,'reconnect',?,?)")
               ->execute([$rid, $meId, (int)$otherId, $reconnectData, $now]);
        }

        $db->prepare("INSERT INTO voice_events(room_id,user_id,user_name,event_type,created_at) VALUES(?,?,?,'reconnect',?)")
           ->execute([$rid, $meId, $meName, $now]);

        apiOk([
            'roomId'      => $rid,
            'notified'    => count($otherIds),
            'serverTime'  => $now,
        ]);
    }

    if ($action === 'get_ice_servers') {
        $iceServers = defined('ICE_SERVERS') ? json_decode(ICE_SERVERS, true) : null;
        if (!is_array($iceServers)) {
            $iceServers = [
                ['urls' => 'stun:stun.l.google.com:19302'],
                ['urls' => 'stun:stun1.l.google.com:19302'],
                ['urls' => 'stun:stun2.l.google.com:19302'],
            ];
        }

        $turnCfgFile = __DIR__ . '/turn_config.json';
        if (file_exists($turnCfgFile)) {
            $turnCfgRaw = file_get_contents($turnCfgFile);
            if ($turnCfgRaw !== false) {
                $turnCfg = json_decode($turnCfgRaw, true);
                if (!empty($turnCfg['servers']) && is_array($turnCfg['servers'])) {
                    $iceServers = array_merge($turnCfg['servers'], $iceServers);
                }
            }
        }

        apiOk([
            'iceServers'  => $iceServers,
            'serverTime'  => nowSec(),
            'rtcConfig'   => [
                'iceCandidatePoolSize'    => 10,
                'iceTransportPolicy'      => 'all',
                'bundlePolicy'            => 'max-bundle',
                'rtcpMuxPolicy'           => 'require',
            ],
        ]);
    }

    // ══ get_channel_app ══════════════════════════════════════════
    if ($action === 'get_channel_app') {
        $chId = (int)($d['channelId'] ?? 0);
        if ($chId <= 0) apiFail('Укажите channelId');
        $s = $db->prepare("SELECT id,server_id,owner_id,name,type,app_html,app_icon,perm_read,is_public,app_ref_channel_id FROM channels WHERE id=?");
        $s->execute([$chId]); $ch = $s->fetch();
        if (!$ch) apiFail('Канал не найден');
        if (($ch['type'] ?? 'text') !== 'app') apiFail('Это не мини-приложение');
        $myRole = getRole($db, (int)$ch['server_id'], $meId);
        $pr = (string)($ch['perm_read'] ?? 'all');
        if ($pr === 'admins' && !isAdmin($myRole) && (int)$ch['owner_id'] !== $meId) apiFail('Нет доступа');

        $refId   = (int)($ch['app_ref_channel_id'] ?? 0);
        $appHtml = (string)($ch['app_html'] ?? '');
        $appIcon = (string)($ch['app_icon'] ?? '');
        $refName = '';
        if ($refId > 0) {
            $refQ = $db->prepare("SELECT c.app_html, c.app_icon, c.name, s.name AS srv_name FROM channels c JOIN servers s ON s.id=c.server_id WHERE c.id=? AND c.type='app' AND c.is_public=1");
            $refQ->execute([$refId]);
            $refCh = $refQ->fetch();
            if ($refCh) {
                $appHtml = (string)$refCh['app_html'];
                if (empty($appIcon)) $appIcon = (string)$refCh['app_icon'];
                $refName = (string)$refCh['srv_name'] . ' › ' . (string)$refCh['name'];
            } else {
                $appHtml = '<p style="padding:24px;font-family:sans-serif;color:#c66">⚠ Источник приложения недоступен или был удалён.</p>';
            }
        }
        apiOk([
            'channelId'     => $chId,
            'name'          => (string)$ch['name'],
            'appIcon'       => $appIcon,
            'appHtml'       => $appHtml,
            'isPublic'      => (int)($ch['is_public'] ?? 0),
            'refChannelId'  => $refId,
            'refSourceName' => $refName,
        ]);
    }

    // ══ get_app_catalog ══════════════════════════════════════════
    if ($action === 'get_app_catalog') {
        $s = $db->prepare("
            SELECT c.id, c.name, c.app_icon, c.description, s.name AS server_name, s.id AS server_id
            FROM channels c
            JOIN servers s ON s.id = c.server_id
            WHERE c.type='app' AND c.is_public=1
            ORDER BY c.id DESC
            LIMIT 100
        ");
        $s->execute();
        $apps = [];
        foreach ($s->fetchAll() as $r) {
            $apps[] = [
                'id'          => (int)$r['id'],
                'name'        => (string)$r['name'],
                'appIcon'     => (string)($r['app_icon'] ?? '🧩'),
                'description' => (string)($r['description'] ?? ''),
                'serverName'  => (string)$r['server_name'],
                'serverId'    => (int)$r['server_id'],
            ];
        }
        apiOk(['apps' => $apps]);
    }

    // ══ add_app_from_catalog ═════════════════════════════════════
    if ($action === 'add_app_from_catalog') {
        $sid      = (int)($d['serverId'] ?? 0);
        $refChId  = (int)($d['refChannelId'] ?? 0);
        if ($sid <= 0 || $refChId <= 0) apiFail('Неверные параметры');
        $myRole = getRole($db, $sid, $meId);
        if (!isAdmin($myRole) && $meName !== OWNER_NAME && $meGlobalRole !== 'super_admin' && $meGlobalRole !== 'project_admin') apiFail('Нет прав');
        $srcQ = $db->prepare("SELECT id,name,app_icon,description FROM channels WHERE id=? AND type='app' AND is_public=1");
        $srcQ->execute([$refChId]); $src = $srcQ->fetch();
        if (!$src) apiFail('Приложение не найдено или не является публичным');
        $ownerQ = $db->prepare("SELECT server_id FROM channels WHERE id=?"); $ownerQ->execute([$refChId]); $ownerCh = $ownerQ->fetch();
        if ($ownerCh && (int)$ownerCh['server_id'] === $sid) apiFail('Это приложение уже на вашем сервере');
        $existQ = $db->prepare("SELECT id FROM channels WHERE server_id=? AND app_ref_channel_id=?");
        $existQ->execute([$sid, $refChId]);
        if ($existQ->fetch()) apiFail('Это приложение уже добавлено на данный сервер');
        $t = nowSec();
        $db->prepare("INSERT INTO channels(server_id,owner_id,name,topic,description,avatar,perm_read,perm_write,type,app_icon,app_html,is_public,app_ref_channel_id,created_at) VALUES(?,?,?,?,?,'','all','admins','app',?,?,0,?,?)")
           ->execute([$sid, $meId, (string)$src['name'], '', (string)($src['description']??''), (string)($src['app_icon']??'🧩'), '', $refChId, $t]);
        $newId = (int)$db->lastInsertId();
        apiOk(['channelId' => $newId, 'name' => (string)$src['name'], 'appIcon' => (string)($src['app_icon']??'🧩')]);
    }

    // ══ unlink_media ═════════════════════════════════════════════
    if ($action === 'unlink_media') {
        $msgId = (int)($d['messageId'] ?? 0);
        $isDm  = !empty($d['isDm']);
        if (!$msgId) apiFail('Укажите messageId');
        if ($isDm) {
            $s = $db->prepare("SELECT id,from_user_id FROM dm_messages WHERE id=?");
            $s->execute([$msgId]); $msg = $s->fetch();
            if (!$msg) apiOk([]);
            $canEdit = (int)$msg['from_user_id'] === $meId
                || $meName === OWNER_NAME
                || $meGlobalRole === 'super_admin';
            if (!$canEdit) apiFail('Нет прав');
            $db->prepare("UPDATE dm_messages SET image='' WHERE id=?")->execute([$msgId]);
        } else {
            $s = $db->prepare("SELECT id,user_id,channel_id FROM messages WHERE id=?");
            $s->execute([$msgId]); $msg = $s->fetch();
            if (!$msg) apiOk([]);
            $chq = $db->prepare("SELECT server_id FROM channels WHERE id=?");
            $chq->execute([(int)$msg['channel_id']]); $chRow = $chq->fetch();
            $canEdit = (int)$msg['user_id'] === $meId || $meName === OWNER_NAME || $meGlobalRole === 'super_admin';
            if (!$canEdit && $chRow) {
                $r2 = getRole($db, (int)$chRow['server_id'], $meId);
                $canEdit = isMod($r2);
            }
            if (!$canEdit) apiFail('Нет прав');
            $db->prepare("UPDATE messages SET image='' WHERE id=?")->execute([$msgId]);
        }
        apiOk(['messageId' => $msgId]);
    }

    apiFail('Неизвестный action: ' . $action);

