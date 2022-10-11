<?php

require_once 'common.php';

function isVideo($fileName) {
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);;
    $extension = strtolower($extension);
    return in_array($extension, ['mp4', 'webm']);
}

function getContentType($fileName) {
    $data = [
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'mp4' => 'video/mp4',
        'png' => 'image/png',
        'webm' => 'video/webm',
        'webp' => 'image/webp',
    ];

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);;
    $extension = strtolower($extension);
    return $data[$extension] ?? null;
}

if (!function_exists('readline')) {
    function readline($prompt) {
        echo $prompt . "\n";
        return stream_get_line(STDIN, 1024, PHP_EOL);
    }
}
