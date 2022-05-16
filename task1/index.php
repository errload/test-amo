<?php

require_once(__DIR__ . '/vendor/autoload.php');

function getClients() {
    return [
        [
            'id' => '1',
            'name' => 'introvert',
            'api' => '23bc075b710da43f0ffb50ff9e889aed',
        ],
        [
            'id' => '2',
            'name' => 'unknown',
            'api' => '',
        ],
    ];
}

function leadsDate($date_from, $date_to) {
    $clients = getClients();
    $total_sum = 0; // суммарный бюджет

    echo '
        <table border="1">
            <tr style="font-weight: bold;">
                <td>id</td>
                <td>name</td>
                <td>total_sum</td>
            </tr>
    ';

    foreach ($clients AS $client) {

        Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', $client['api']);
        $api = new Introvert\ApiClient();
        $api->getConfig()->setHost('https://api.s1.yadrocrm.ru/');

        $crm_user_id = null; // int[] | фильтр по id ответственного
        $status = 142; // int[] | фильтр по id статуса
        $id = null; // int[] | фильтр по id
        $ifmodif = null; // string | фильтр по дате изменения. timestamp или строка в формате 'D, j M Y H:i:s'
        $count = 250; // int | Количество запрашиваемых элементов
        $offset = 0; // int | смещение, относительно которого нужно вернуть элементы

        try {
            do {
                // успешные сделки
                $result = $api->lead->getAll($crm_user_id, $status, $id, $ifmodif, $count, $offset);

                // перебор полученных сделок
                foreach ($result['result'] as $lead) {
                    // диапазон дат "от" и "до"
                    if ($lead['date_create'] >= $date_from && $lead['date_close'] !== 0 && $lead['date_close'] <= $date_to) {
                        // прибавляем к общей сумме
                        $total_sum += $lead['price'];
                    }
                }

                $offset += count($result['result']);

            } while ($offset % $count == 0);

            // вывод успешных сделок текущего пользователя
            echo '
                <tr>
                    <td>' . $client['id'] . '</td>
                    <td>' . $client['name'] . '</td>
                    <td>' . $total_sum . '</td>
                </tr>
            ';

        } catch (Exception $e) {
            // вывод ошибки в случае отсутствия доступа
            echo '
                <tr>
                    <td>' . $client['id'] . '</td>
                    <td>' . $client['name'] . '</td>
                    <td>' . $e->getMessage() . '</td>
                </tr>
            ';
        }
    }

    // вывод общей суммы всех пользователей
    echo '
            <tr style="font-weight: bold;">
                <td colspan="2">total</td>
                <td> ' . $total_sum . ' </td>
            </tr>
        </table>
    ';
}

leadsDate('1628590652', '1652343737');