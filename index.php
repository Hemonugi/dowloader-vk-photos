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
 * @param array $params
 * @return string
 */
function getOfVkApi(string $method, string $methodName, $params = []) {
    $vkToken = include "config/vk/tokens.php";
    return "https://api.vk.com/method/$method.$methodName?v=5.101&access_token=$vkToken[$method]";
}

function getFriendsOnline() {
    $ch = curl_init();

    curl_setopt(
        $ch,
        CURLOPT_URL,
        getOfVkApi('friends', 'getOnline')
    );

    curl_exec($ch);
    curl_close($ch);
}
