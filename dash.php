<?php
require_once __DIR__ . '/guard.php';
session_start();
require_once 'adawat/php/detect.php';
define('IMG_PATH', 'adawat/img/navigateur/');
define('DEVICE_ICON_PATH', 'adawat/img/appareils/');
$ipBlockFile = 'blocked_ips.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['block_ip'])) {
    $ip_to_block = trim($_POST['block_ip']);
    if (filter_var($ip_to_block, FILTER_VALIDATE_IP)) {
        if (!file_exists($ipBlockFile)) touch($ipBlockFile);
        $blocked = file($ipBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!in_array($ip_to_block, $blocked)) {
            file_put_contents($ipBlockFile, $ip_to_block . PHP_EOL, FILE_APPEND);
            $_SESSION['message'] = ['text' => "L'IP {$ip_to_block} a été bloquée.", 'type' => 'success'];
        } else {
            $_SESSION['message'] = ['text' => "L'IP {$ip_to_block} était déjà bloquée.", 'type' => 'info'];
        }
    } else {
        $_SESSION['message'] = ['text' => "Format d'IP invalide.", 'type' => 'error'];
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET));
    exit();
}

$blocked_ips = file_exists($ipBlockFile)
    ? array_flip(array_map('trim', file($ipBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)))
    : [];

$logType = $_GET['log'] ?? 'visits';
$logFile = ($logType === 'notvisits') ? 'notvisits.log' : 'visits.log';

$svg_icons = [
    'human'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',
    'bot'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6h-4V4c0-1.1-.9-2-2-2h-2c-1.1 0-2 .9-2 2v2H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-2h2v2h-2V4zM7 16c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm10 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-4-4H7v-2h6v2z"/></svg>',
    'refresh' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>',
    'block'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM4 12c0-4.42 3.58-8 8-8 1.85 0 3.55.63 4.9 1.69L5.69 16.9C4.63 15.55 4 13.85 4 12zm8 8c-1.85 0-3.55-.63-4.9-1.69L18.31 7.1C19.37 8.45 20 10.15 20 12c0 4.42-3.58 8-8 8z"/></svg>',
];

$visitors = []; 
$stats = ['total' => 0, 'unique_ips' => 0, 'top_countries' => [], 'top_browsers' => []];

if (file_exists($logFile)) {
    $lines = array_reverse(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    $stats['total'] = count($lines);
    $ip_list = []; $country_counts = []; $browser_counts = [];

    foreach ($lines as $line) {
        $parts = explode(' | ', $line, 7);
        if (count($parts) !== 7) continue;
        list($date, $ip, $countryCode, $city, $zip, $isp, $userAgent) = $parts;

        if ($logType === 'notvisits' && isset($blocked_ips[$ip])) continue;

        $browser = new BrowserDetection($userAgent);
        $browserName = $browser->getName();

        $visitors[] = [
            'date' => $date, 'ip' => $ip, 'countryCode' => strtolower($countryCode),
            'city' => $city, 'zip' => $zip, 'isp' => $isp,
            'browserName' => $browserName, 'platform' => $browser->getPlatform(),
        ];

        $ip_list[] = $ip;
        $country_counts[$countryCode] = ($country_counts[$countryCode] ?? 0) + 1;
        $browser_counts[$browserName] = ($browser_counts[$browserName] ?? 0) + 1;
    }

    if ($stats['total'] > 0) {
        $stats['unique_ips'] = count(array_unique($ip_list));
        arsort($country_counts); arsort($browser_counts);
        $stats['top_countries'] = array_slice($country_counts, 0, 3, true);
        $stats['top_browsers'] = array_slice($browser_counts, 0, 3, true);
    }
}

function getBrowserLogoPath($browserName) { 
    $name = strtolower(strtok($browserName, ' '));
    $valid = ['chrome','firefox','safari','edge','opera'];
    return in_array($name, $valid) ? IMG_PATH . $name . '.png' : IMG_PATH . 'unknown.png';
}
function getDeviceIconPath($platform) { 
    if (stripos($platform,'Windows')!==false) return DEVICE_ICON_PATH.'windows.png';
    if (stripos($platform,'Macintosh')!==false) return DEVICE_ICON_PATH.'macintosh.png';
    if (stripos($platform,'iPhone')!==false) return DEVICE_ICON_PATH.'iphone.png';
    if (stripos($platform,'iPad')!==false) return DEVICE_ICON_PATH.'ipad.png';
    if (stripos($platform,'Android')!==false) return DEVICE_ICON_PATH.'android.png';
    if (stripos($platform,'Linux')!==false) return DEVICE_ICON_PATH.'linux.png';
    return DEVICE_ICON_PATH.'desktop.png';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Dashboard</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
:root{
    --bg:#010409;--card:rgba(22,27,34,0.7);--txt:#e6edf3;--txt2:#7d8590;
    --prim:#0ea5e9;--border:rgba(48,54,61,0.8);--glow:rgba(14,165,233,0.2);
    --success:#28a745;--error:#dc3545;
}
*,*::before,*::after{box-sizing:border-box}
html,body{margin:0;padding:0;height:100%;font-family:'Inter',sans-serif;background:var(--bg);color:var(--txt);}
#matrix-canvas{position:fixed;inset:0;z-index:-1;opacity:0.6;}
.container{max-width:1600px;margin:0 auto;padding:12px;background:var(--card);border-radius:16px;border:1px solid var(--border-color);backdrop-filter:blur(30px);}

.header-controls{display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;align-items:center;margin-bottom:20px;}
h1{font-size:clamp(1.8rem,6vw,2.4rem);margin:0;text-shadow:0 0 10px var(--prim);font-weight:700;}
.controls-right{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;}

.log-switcher{display:flex;background:rgba(0,0,0,0.4);border-radius:50px;padding:4px;border:1px solid var(--border-color);}
.log-switcher a{display:flex;align-items:center;gap:8px;padding:8px 16px;border-radius:50px;font-weight:500;transition:.3s;font-size:clamp(0.9rem,3vw,1rem);}
.log-switcher a.active{background:var(--prim);color:#fff;box-shadow:0 0 15px var(--glow);}
.refresh-btn{width:44px;height:44px;border-radius:50%;background:rgba(0,0,0,0.4);border:1px solid var(--border-color);display:grid;place-items:center;transition:.3s;}
.refresh-btn:hover{transform:rotate(360deg);box-shadow:0 0 15px var(--glow);}
.refresh-btn svg, .log-switcher svg{width:18px;height:18px;}

.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-bottom:24px;}
.stat-card{background:rgba(0,0,0,0.3);padding:20px;border-radius:12px;border:1px solid var(--border-color);transition:.3s;}
.stat-card:hover{transform:translateY(-5px);box-shadow:0 10px 25px var(--glow);}

.message{padding:14px;border-radius:10px;margin-bottom:20px;font-weight:500;}
.message.success{background:rgba(40,167,69,0.2);border:1px solid var(--success);color:var(--success);}
.message.error{background:rgba(220,53,69,0.2);border:1px solid var(--error);color:var(--error);}
.message.info{background:rgba(23,162,184,0.2);border:1px solid #17a2b8;color:#17a2b8;}

.table-wrapper{overflow-x:auto;border-radius:12px;border:1px solid var(--border-color);}
table{width:100%;border-collapse:collapse;}
th{background:rgba(0,0,0,0.4);padding:12px 8px;text-align:left;position:sticky;top:0;z-index:10;font-weight:600;}
td{padding:12px 8px;border-bottom:1px solid var(--border-color);font-size:0.94rem;}
.block-btn{width:36px;height:36px;border-radius:50%;border:1px solid var(--error);color:var(--error);background:transparent;display:grid;place-items:center;cursor:pointer;transition:.3s;}
.block-btn:hover{background:var(--error);color:#fff;transform:scale(1.15);}
.block-btn svg{width:16px;height:16px;}

.cell-content{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.logo{height:22px;width:22px;object-fit:contain;border-radius:4px;flex-shrink:0;}

/* RESPONSIVE (déjà parfait, juste un petit plus pour les très petits écrans) */
@media (max-width: 768px){
    .container{padding:10px;}
    .header-controls{flex-direction:column;text-align:center;}
    .stats-grid{grid-template-columns:1fr;}
    table thead{display:none;}
    table tr{display:block;margin-bottom:14px;background:rgba(0,0,0,0.25);border:1px solid var(--border-color);border-radius:12px;overflow:hidden;}
    table td{display:flex;flex-direction:column;padding:12px 16px;text-align:right;border-bottom:1px solid var(--border-color);gap:6px;}
    table td::before{content:attr(data-label);font-weight:600;color:var(--txt2);text-align:left;font-size:0.9rem;}
    table td:last-child{border:none;}
    .cell-content{flex-direction:row;justify-content:flex-end;}
}
@media (max-width: 480px){
    .manual-block-form{flex-direction:column;}
    .manual-block-form input,.manual-block-form button{width:100%;height:48px;border-radius:8px;}
    .manual-block-form button{margin-top:8px;}
}
</style>
</head>
<body>
<canvas id="matrix-canvas"></canvas>

<div class="container">
    <div class="header-controls">
        <h1>Dashboard</h1>
        <div class="controls-right">
            <div class="log-switcher">
                <a href="?log=visits" class="<?php echo $logType==='visits'?'active':'' ?>">
                    <?php echo $svg_icons['human'] ?> <span>Ciblés</span>
                </a>
                <a href="?log=notvisits" class="<?php echo $logType==='notvisits'?'active':'' ?>">
                    <?php echo $svg_icons['bot'] ?> <span>Autres</span>
                </a>
            </div>
            <a href="" class="refresh-btn" title="Rafraîchir">
                <?php echo $svg_icons['refresh'] ?>
            </a>
        </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="message <?php echo htmlspecialchars($_SESSION['message']['type']) ?>">
            <?php echo $_SESSION['message']['text'] ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Statistiques</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;text-align:center;margin-top:12px;">
                <div><div style="font-size:2rem;font-weight:700;"><?php echo number_format($stats['total']) ?></div><div style="color:var(--txt2)">Visites</div></div>
                <div><div style="font-size:2rem;font-weight:700;"><?php echo number_format($stats['unique_ips']) ?></div><div style="color:var(--txt2)">IP Uniques</div></div>
            </div>
        </div>

        <div class="stat-card"><h3>Top 3 Pays</h3><ul style="list-style:none;padding:0;margin:10px 0;">
            <?php foreach($stats['top_countries'] as $c=>$n): ?>
                <li style="display:flex;align-items:center;gap:10px;margin:8px 0;">
                    <img src="https://flagcdn.com/w20/<?php echo strtolower($c)?>.png" class="logo" alt=""> <?php echo strtoupper($c)?> <span style="margin-left:auto;color:var(--txt2)">(<?php echo $n ?>)</span>
                </li>
            <?php endforeach; ?>
        </ul></div>

        <div class="stat-card"><h3>Top 3 Navigateurs</h3><ul style="list-style:none;padding:0;margin:10px 0;">
            <?php foreach($stats['top_browsers'] as $b=>$n): ?>
                <li style="display:flex;align-items:center;gap:10px;margin:8px 0;">
                    <img src="<?php echo getBrowserLogoPath($b) ?>" class="logo" alt=""> <?php echo htmlspecialchars($b) ?> <span style="margin-left:auto;color:var(--txt2)">(<?php echo $n ?>)</span>
                </li>
            <?php endforeach; ?>
        </ul></div>

        <div class="stat-card">
            <h3>Bloquer une IP</h3>
            <form method="post" class="manual-block-form" style="display:flex;gap:8px;margin-top:12px;">
                <input type="text" name="block_ip" placeholder="123.45.67.89" required style="flex:1;padding:10px;border-radius:8px;border:1px solid var(--border-color);background:rgba(0,0,0,0.3);color:var(--txt);">
                <button type="submit" style="width:48px;height:48px;background:var(--error);border:none;border-radius:8px;display:grid;place-items:center;">
                    <?php echo $svg_icons['block'] ?>
                </button>
            </form>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr>
                <th>Date</th><th>IP</th><th>Pays</th><th>Ville/CP</th><th>Fournisseur</th><th>Navigateur</th><th>Appareil</th><th>Action</th>
            </tr></thead>
            <tbody>
                <?php foreach($visitors as $v): ?>
                <tr>
                    <td data-label="Date"><?php echo htmlspecialchars($v['date']) ?></td>
                    <td data-label="IP"><?php echo htmlspecialchars($v['ip']) ?></td>
                    <td data-label="Pays"><div class="cell-content"><img src="https://flagcdn.com/w40/<?php echo $v['countryCode'] ?>.png" class="logo" alt=""><span><?php echo strtoupper($v['countryCode']) ?></span></div></td>
                    <td data-label="Ville/CP"><?php echo htmlspecialchars($v['city'].' / '.$v['zip']) ?></td>
                    <td data-label="Fournisseur"><?php echo htmlspecialchars($v['isp']) ?></td>
                    <td data-label="Navigateur"><div class="cell-content"><img src="<?php echo getBrowserLogoPath($v['browserName']) ?>" class="logo" alt=""><span><?php echo htmlspecialchars($v['browserName']) ?></span></div></td>
                    <td data-label="Appareil"><div class="cell-content"><img src="<?php echo getDeviceIconPath($v['platform']) ?>" class="logo" alt=""><span><?php echo htmlspecialchars($v['platform']) ?></span></div></td>
                    <td data-label="Action">
                        <form method="post"><input type="hidden" name="block_ip" value="<?php echo htmlspecialchars($v['ip']) ?>">
                            <button type="submit" class="block-btn" title="Bloquer"><?php echo $svg_icons['block'] ?></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const canvas = document.getElementById('matrix-canvas');
const ctx = canvas.getContext('2d');
const resize = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
resize();
window.addEventListener('resize', resize);

const fontSize = 14;
let columns = canvas.width / fontSize;
const drops = Array(Math.floor(columns)).fill(1);

const draw = () => {
    ctx.fillStyle = 'rgba(1, 4, 9, 0.05)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#39ff14';
    ctx.font = fontSize + 'px monospace';

    for (let i = 0; i < drops.length; i++) {
        const text = Math.random() > 0.5 ? '0' : '1';
        ctx.fillText(text, i * fontSize, drops[i] * fontSize);
        if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
        drops[i]++;
    }
};
setInterval(draw, 50);
</script>
</body>
</html>