<?php

require_once(__DIR__ . '/vendor/autoload.php');

function leadsDate()
{
    // сегодняшняя дата
    $date_from = strtotime(date('Y-m-d 00:00:00'));
    // сегодняшняя дата + 30 дней
    $date_to = strtotime(date('Y-m-d 00:00:00', strtotime('+31 days')));

    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', '23bc075b710da43f0ffb50ff9e889aed');
    $api = new Introvert\ApiClient();
    $api->getConfig()->setHost('https://api.s1.yadrocrm.ru/');

    $crm_user_id = null; // int[] | фильтр по id ответственного
    $status = array(['41477662', '41477659', '41477665']); // int[] | фильтр по id статуса
    $id = null; // int[] | фильтр по id
    $ifmodif = null; // string | фильтр по дате изменения. timestamp или строка в формате 'D, j M Y H:i:s'
    $count = 250; // int | Количество запрашиваемых элементов
    $offset = 0; // int | смещение, относительно которого нужно вернуть элементы

    $ajax_array = []; // результирующий массив
    $leadsID = '1520663'; // ID доп поля сделки

    // перебираем 3 заложенных статуса
    foreach ($status AS $item) {
        try {
            do {
                $result = $api->lead->getAll($crm_user_id, $item, $id, $ifmodif, $count, $offset);

                foreach ($result['result'] as $lead) {

                    // если доп поля отсутствуют, пропускаем сделку
                    if (!$lead['custom_fields']) continue;

                    // перебор доп полей
                    foreach ($lead['custom_fields'] AS $customFields) {
                        // если доп поле не с нашим ID, пропускаем
                        if ($customFields['id'] != $leadsID) continue;

                        // дата доп поля
                        $dateLeads = $customFields['values'][0]['value'];
                        // если не попадает во врмеменные рамки, пропускаем
                        if (strtotime($dateLeads) < $date_from || strtotime($dateLeads) > $date_to) continue;

                        /*
                         * записываем дату в результирующий массив
                         * если такой даты в массиве нет - ставим количество 1
                         * иначе увеличиваем на 1
                        */
                        if (!array_key_exists($dateLeads, $ajax_array)) $ajax_array[$dateLeads] = 1;
                        else $ajax_array[$dateLeads] = ++$ajax_array[$dateLeads];
                    }
                }

                $offset += count($result['result']);

            } while ($offset % $count == 0);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // возвращаем результат в календарь
    print_r(json_encode($ajax_array));
}

leadsDate();