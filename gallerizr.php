<?php
require_once 'functions.php';

$path = readline('Enter path:');
$path = preg_replace('|[\\\/]$|', '', $path);

$images = $path ? scandir($path) : [];

$sort = readline('How would you like to sort images [0 - date, 1 - random]?');

switch ($sort) {
    case '1':
        shuffle($images);
        break;
    case '0':
    default:
        $dateHash = [];
        foreach ($images as $i => $image) {
            try {
                $date = filemtime($path . '/' . $image);
            } catch (Exception $e) {
                $date = $i;
            }
            $dateHash[$date] = $image;
            ksort($dateHash);
            $images = array_reverse(array_values($dateHash));
        }
        break;
}

$content = <<<'EOF'
<html lang="en">
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
EOF;

foreach ($images as $image) {
    if (in_array($image, ['.', '..', 'index.html', 'index.css'])) {
        continue;
    }

    $url = 'file://' . htmlspecialchars($path) . '/' . htmlspecialchars($image);
    $contentType = getContentType($image);
    if (!$contentType) {
        $content .= "<h1 class=\"error\">Unknown content type ($image)</h1>";
    } if (isVideo($image)) {
        $content .= "<video controls loop><source src=\"$url\"/></video>";
    } else {
        $content .= "<a target=\"_blank\" href=\$url\"><img src=\"$url\" alt=\"$url\"></a>";
    }
}

$content .= '</body></html>';

$htmlFileName = "$path/index.html";

file_put_contents($htmlFileName, $content);

file_put_contents($path . '/index.css', <<<'EOF'
img, video { 
    max-width: 100% 
}
EOF
);

`rundll32 url.dll,FileProtocolHandler file://$htmlFileName`;
