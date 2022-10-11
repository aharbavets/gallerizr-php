<?php
// Design taken from https://getbootstrap.com/docs/4.1/examples/carousel/
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

ob_start();
echo '<html><body><style>img, video { max-width: 100% } </style>';

foreach ($images as $image) {
    $url = 'file://' . htmlspecialchars($path) . '/' . htmlspecialchars($image);
    $contentType = getContentType($image);
    if (!$contentType) {
        echo "<h1 class=\"error\">Unknown content type ($image)</h1>";
    } if (isVideo($image)) {
        ?>
        <video controls loop>
            <source src="<?= $url ?>">
        </video>
        <?php
    } else {
        ?>
        <a target="_blank" href="<?= $url ?>">
            <img src="<?= $url ?>" alt="">
        </a>
        <?php
    }
}

echo '</body></html>';

$content = ob_get_clean();
$outputFileName = $path . '/index.html';
file_put_contents($outputFileName, $content);

`rundll32 url.dll,FileProtocolHandler file://$outputFileName`;
