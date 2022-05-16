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

    // перебираем 3 заложенных статуса
    foreach ($status AS $item) {
        try {
            do {
                $result = $api->lead->getAll($crm_user_id, $item, $id, $ifmodif, $count, $offset);

                foreach ($result['result'] as $lead) {

                    // если воронка не Стажерство, пропускаем
                    if ($lead['pipeline_id'] != 4484737) continue;

                    // так же, если нет доп полей
                    if (!$lead['custom_fields']) continue;

                    // дата доп поля сделки
                    $dateLeads = $lead['custom_fields'][0]['values'][0]['value'];

                    // проверка на валидность даты
                    $date = DateTime::createFromFormat('Y-m-d h:m:s', $dateLeads);
                    if (!$date) continue;

                    // нужные нам диапазоны дат (сегодня + 30 дней)
                    if (strtotime($dateLeads) < $date_from || strtotime($dateLeads) > $date_to) continue;

//                    $dopFieldID = $lead['custom_fields']['0']['id']; // ID доп поля с датой
//                    $statusID = $lead['status_id']; // ID статуса сделки

                    // если в массиве есть такая дата, увеличиваем на 1
                    if (array_key_exists($dateLeads, $ajax_array)) $ajax_array[$dateLeads] = ++$ajax_array[$dateLeads];
                    // иначе записываем со значением первой записи
                    else $ajax_array[$dateLeads] = 1;
                }

                $offset += count($result['result']);

            } while ($offset % $count == 0);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // возвращаем результат в календарь
    print_r($ajax_array);
}

leadsDate();