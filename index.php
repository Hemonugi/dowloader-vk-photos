<?php

/**
 * @param string $method
 * @param string $methodName
 * @return string
 */
function getOfVkApi(string $method, string $methodName) {
    $vkToken = include "config/vk/tokens.php";
    return "https://api.vk.com/method/$method.$methodName?album_id=saved&count=1000&v=5.101&access_token=$vkToken[$method]";
}

function savePictures() {
    $ch = curl_init();

    curl_setopt_array(
        $ch, [
            CURLOPT_URL => getOfVkApi('photos', 'get'),
            CURLOPT_RETURNTRANSFER => true
        ]
    );

    $curlResponse = json_decode(curl_exec($ch), true)['response'];
    $photosInDir = scandir('pictures');
    $photosUrl = [];
    $photosUrlBasename = [];

    foreach ($curlResponse['items'] as $photo) {
        $url = array_pop($photo['sizes'])['url'];
        $photosUrl[] = $url;
        $photosUrlBasename[] = pathinfo($url)['basename'];
    }

    $needDownloads = array_keys(array_diff($photosUrlBasename, $photosInDir));
    foreach ($needDownloads as $key) {
        $url = $photosUrl[$key];

        $content = file_get_contents($url);
        file_put_contents('pictures/'.pathinfo($url)['basename'], $content);
        echo "Download: $url\n";
    }

    echo "\nresult: " . (count(scandir('pictures')) - 2) . '/' . $curlResponse['count'];
    echo "\nУникальных картинок: " . (count(array_unique($photosUrl)));

    curl_close($ch);
}

savePictures();
