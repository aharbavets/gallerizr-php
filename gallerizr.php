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
        <link rel="stylesheet" href="index.css"/>
    </head>
    <body>
        <div class="gallerizr-container">
EOF;

foreach ($images as $image) {
    if (in_array($image, ['.', '..', 'index.html', 'index.css'])) {
        continue;
    }

    $url = 'file://' . htmlspecialchars($path) . '/' . htmlspecialchars($image);
    $contentType = getContentType($image);
    if (!$contentType) {
        $content .= "<div class='gallerizr-item'><h1 class=\"error\">Unknown content type ($image)</h1></div>";
    } if (isVideo($image)) {
        $content .= "<div class='gallerizr-item'><video controls loop><source src=\"$url\"/></video></div>";
    } else {
        $content .= "<div class='gallerizr-item'><a target=\"_blank\" href=\$url\"><img src=\"$url\" alt=\"$url\"></a></div>";
    }
}

$content .= <<<'EOF'
            </div>
        </body>
    </html>
EOF;

$htmlFileName = "$path/index.html";

file_put_contents($htmlFileName, $content);

file_put_contents($path . '/index.css', <<<'EOF'
img, video { 
    max-width: 100% 
}
img, video {
    max-width: 100%
}

.gallerizr-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: stretch;
}

.gallerizr-item {
    display: inline-block;
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: content;
    border: red 1px solid;
}

EOF
);

`rundll32 url.dll,FileProtocolHandler file://$htmlFileName`;
