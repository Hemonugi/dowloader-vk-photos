<?php
/**
 * Чтобы обратиться к методу API ВКонтакте, Вам необходимо выполнить POST или GET запрос такого вида:
 * https://api.vk.com/method/METHOD_NAME?PARAMETERS&access_token=ACCESS_TOKEN&v=V
 *
 * METHOD_NAME (обязательно) — название метода API, к которому Вы хотите обратиться.
 * PARAMETERS (опционально) — входные параметры соответствующего метода API, последовательность пар name=value, разделенных амперсандом.
 * ACCESS_TOKEN (обязательно) — ключ доступа.
 * V (обязательно) — используемая версия API. Использование этого параметра применяет некоторые изменения в формате ответа различных методов.
 **/

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

    $photos = json_decode(curl_exec($ch), true)['response']['items'];

    foreach ($photos as $photo) {
        $photoUrl = array_pop($photo['sizes'])['url'];

        $content = file_get_contents($photoUrl);
        file_put_contents('pictures/'.pathinfo($photoUrl)['basename'], $content);
        echo "Download:  $photoUrl \n";
    }

    curl_close($ch);
}

savePictures();
