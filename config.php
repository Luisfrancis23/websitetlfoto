<?php

date_default_timezone_set('Asia/Dili');

define('APP_ROOT', __DIR__);
define('UPLOAD_DIR', APP_ROOT . '/uploads');
define('DB_PATH', APP_ROOT . '/data/gallery.db');

if (!is_dir(APP_ROOT . '/data')) {
    mkdir(APP_ROOT . '/data', 0777, true);
}

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

function asset_url(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}
