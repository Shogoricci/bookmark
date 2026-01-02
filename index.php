<?php require_once 'read.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Map Bookmark System</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root { --bg: #050a0f; --accent: #ff3333; --panel: rgba(16, 25, 36, 0.9); --text: #e0f7fa; }
        body { background: var(--bg); color: var(--text); font-family: sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 350px; gap: 20px; }
        
        h1 { grid-column: 1 / 3; text-align: center; color: #00f2ff; text-shadow: 0 0 10px #00f2ff; }

        /* 地図エリア */
        #map { height: 500px; border: 1px solid #00f2ff; border-radius: 8px; margin-bottom: 20px; }

        /* フォーム・検索エリア */
        .side-panel { background: var(--panel); padding: 20px; border-radius: 8px; border: 1px solid #333; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 10px; background: #000; border: 1px solid #444; color: #fff; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: var(--accent); color: white; border: none; cursor: pointer; font-weight: bold; }

        /* グリッド表示 */
        .grid { grid-column: 1 / 3; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; margin-top: 20px; }
        .card { background: var(--panel); border: 1px solid #333; padding: 15px; border-radius: 5px; position: relative; }
        .card h3 { margin: 0 0 10px; color: #00f2ff; }
        .card p { font-size: 0.85rem; color: #ccc; }
        .del-btn { color: var(--accent); position: absolute; top: 10px; right: 10px; text-decoration: none; font-size: 0.8rem; }
        
        .search-box { grid-column: 1 / 3; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>GEOGRAPHICAL DATA HUB</h1>

    <!-- 地図 -->
    <div id="map"></div>

    <!-- 登録パネル -->
    <div class="side-panel">
        <h3>地点登録</h3>
        <p style="font-size:0.7rem;">地図をクリックして座標を取得</p>
        <form action="write.php" method="POST">
            <input type="text" name="title" placeholder="地点名・店名" required>
            <input type="text" name="lat" id="lat" placeholder="緯度" readonly required>
            <input type="text" name="lng" id="lng" placeholder="経度" readonly required>
            <textarea name="comment" placeholder="コメント・名所の特徴" rows="4"></textarea>
            <button type="submit">SYSTEM DEPLOY</button>
        </form>
    </div>

    <!-- 検索ボックス -->
    <div class="search-box">
        <form action="index.php" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="店名やキーワードで検索..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" style="width: 150px; background: #00f2ff; color: #000;">SEARCH</button>
            <a href="index.php" style="color:#fff; text-decoration:none; padding-top:10px;">Clear</a>
        </form>
    </div>

    <!-- データ一覧（グリッド） -->
    <div class="grid">
        <?php foreach ($locations as $loc): ?>
            <div class="card">
                <a href="write.php?delete=<?= $loc['id'] ?>" class="del-btn" onclick="return confirm('削除しますか？')">DELETE</a>
                <h3><?= htmlspecialchars($loc['title']) ?></h3>
                <p>📍 <?= $loc['lat'] ?>, <?= $loc['lng'] ?></p>
                <p><?= nl2br(htmlspecialchars($loc['comment'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 地図の初期化（日本中心）
    const map = L.map('map').setView([36.2048, 138.2529], 5);

    // 地図のタイル（ダークモード風）
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 既存のデータを地図に表示
    const locations = <?= json_encode($locations) ?>;
    locations.forEach(loc => {
        L.circleMarker([loc.lat, loc.lng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.8,
            radius: 8
        }).addTo(map).bindPopup(`<b>${loc.title}</b><br>${loc.comment}`);
    });

    // クリックで座標取得
    map.on('click', function(e) {
        document.getElementById('lat').value = e.latlng.lat.toFixed(8);
        document.getElementById('lng').value = e.latlng.lng.toFixed(8);
        
        // クリックした場所に一時的なマーカーを表示
        L.popup()
            .setLatLng(e.latlng)
            .setContent("座標を取得しました")
            .openOn(map);
    });
</script>

</body>
</html>