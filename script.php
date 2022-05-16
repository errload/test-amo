<?php

require_once(__DIR__ . '/vendor/autoload.php');

// диапазон дат с календаря
$firstDate = null;
$lastDate = null;

// если были переданы значения, работаем с ними
if (isset($_GET['firstDate']) && isset($_GET['lastDate'])) {
    $firstDate = $_GET['firstDate'];
    $lastDate = $_GET['lastDate'];
}

function leadsDate($date_from, $date_to)
{
    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', '23bc075b710da43f0ffb50ff9e889aed');
    $api = new Introvert\ApiClient();
    $api->getConfig()->setHost('https://api.s1.yadrocrm.ru/');

    $crm_user_id = null; // int[] | фильтр по id ответственного
    $status = array(['41477662', '41477659', '41477665']); // int[] | фильтр по id статуса (41477662, 41477659, 41477665)
    $id = null; // int[] | фильтр по id
    $ifmodif = null; // string | фильтр по дате изменения. timestamp или строка в формате 'D, j M Y H:i:s'
    $count = 250; // int | Количество запрашиваемых элементов
    $offset = 0; // int | смещение, относительно которого нужно вернуть элементы

    // перебираем 3 заложенных статуса
    foreach ($status AS $item) {
        try {
            do {
                $result = $api->lead->getAll($crm_user_id, $item, $id, $ifmodif, $count, $offset);

                foreach ($result['result'] as $lead) {
                    // если воронка не Стажерство, пропускаем
                    if ($lead['pipeline_id'] != 4484737) continue;

                    if ($lead['date_create'] >= $date_from && $lead['date_create'] <= $date_to) {

//                        $dopFielsID = $lead['custom_fields']['0']['id']; // ID доп поля с датой
//                        $statusID = $lead['status_id']; // ID статуса сделки

//                        echo '<pre>';
//                        print_r($lead);
                    }
                }

                $offset += count($result['result']);

            } while ($offset % $count == 0);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

leadsDate('1652475600', '1653944400');