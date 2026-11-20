<?php
// ============ guard.php — البوابة المركزية ============
if (defined('GUARD_LOADED')) return;
define('GUARD_LOADED', true);

// لا شيء يعمل من سطر الأوامر
if (php_sapi_name() === 'cli') return;

// استثناء لوحة التحكم (حتى تستطيع أنت دخولها)
$self = $_SERVER['SCRIPT_NAME'] ?? '';
$adminScripts = ['/dash.php'];
foreach ($adminScripts as $admin) {
    if (strpos($self, $admin) !== false) return;
}

ignore_user_abort(true);

// --- الإعدادات ---
$defaultURL       = 'https://www.google.com';
$targetCountries  = ['FR'];
$logFileTarget    = __DIR__ . '/visits.log';
$logFileOthers    = __DIR__ . '/notvisits.log';
$ipBlockFile      = __DIR__ . '/blocked_ips.txt';
$geoCacheFile     = __DIR__ . '/geo_cache.json';
$cacheTTL         = 86400; // 24 ساعة

// --- جلب IP ---
function guard_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))     return explode(',', $_SERVER['HTTP_CLIENT_IP'])[0];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
$visitorIP = trim(guard_ip());

// --- فحص قائمة الحظر ---
if (file_exists($ipBlockFile)) {
    $blocked = file($ipBlockFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($visitorIP, $blocked)) {
        header('Location: ' . $defaultURL);
        exit;
    }
}

// --- كاش geo (لتجنب استدعاء ip-api في كل طلب) ---
$cache = file_exists($geoCacheFile) ? json_decode(file_get_contents($geoCacheFile), true) : [];
if (!is_array($cache)) $cache = [];

if (isset($cache[$visitorIP]) && (time() - $cache[$visitorIP]['t']) < $cacheTTL) {
    $geoInfo = $cache[$visitorIP]['d'];
} else {
    $url = "http://ip-api.com/json/{$visitorIP}?fields=status,countryCode,city,zip,isp";
    $ch  = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 2]);
    $resp = curl_exec($ch);
    curl_close($ch);

    $geoInfo = ['countryCode' => null, 'city' => 'Unknown', 'zip' => 'Unknown', 'isp' => 'Unknown'];
    if ($resp) {
        $d = json_decode($resp, true);
        if (is_array($d) && ($d['status'] ?? '') === 'success') {
            $geoInfo = [
                'countryCode' => $d['countryCode'] ?? null,
                'city'        => $d['city'] ?? 'Unknown',
                'zip'         => $d['zip']  ?? 'Unknown',
                'isp'         => $d['isp']  ?? 'Unknown',
            ];
        }
    }
    $cache[$visitorIP] = ['t' => time(), 'd' => $geoInfo];
    @file_put_contents($geoCacheFile, json_encode($cache), LOCK_EX);
}

$countryCode = $geoInfo['countryCode'];
$userAgent   = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

$line = implode(' | ', [
    date('Y-m-d H:i:s'),
    $visitorIP,
    $countryCode ?? 'Unknown',
    $geoInfo['city'],
    $geoInfo['zip'],
    $geoInfo['isp'],
    $userAgent,
]) . "\n";

// --- القرار ---
if ($countryCode !== null && in_array($countryCode, $targetCountries)) {
    // ✅ دولة مستهدفة: نكمل تحميل الموقع
    @file_put_contents($logFileTarget, $line, FILE_APPEND | LOCK_EX);
} else {
    // ❌ غير مستهدفة: نسجّل ونحوّل ونخرج
    @file_put_contents($logFileOthers, $line, FILE_APPEND | LOCK_EX);
    header('Location: ' . $defaultURL);
    exit;
}