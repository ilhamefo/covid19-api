<?php

$html_string = file_get_contents('https://www.worldometers.info/coronavirus/');
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html_string);
libxml_clear_errors();
$xpath = new DOMXpath($dom);

$len = $xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr');
$val = [
    'time_accessed' => time(),
    'data' => []
];
for ($i = 1; $i <= $len->length; $i++) {
    $temp['country_id'] = 'ID' . $i;
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[1]') as $x) {
        $temp['country'] = strtolower(trim($x->textContent));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[2]') as $x) {
        $temp['total_cases'] = (int) str_replace(',', '', trim($x->textContent));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[3]') as $x) {
        $temp['new_cases'] = (int) str_replace(',', '', str_replace('+', '', trim($x->textContent)));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[4]') as $x) {
        $temp['total_deaths'] = (int) str_replace(',', '', str_replace('+', '', trim($x->textContent)));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[5]') as $x) {
        $temp['total_recovered'] = (int) str_replace(',', '', str_replace('+', '', trim($x->textContent)));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[6]') as $x) {
        $temp['actice_cases'] = (int) str_replace(',', '', str_replace('+', '', trim($x->textContent)));
    }
    foreach ($xpath->query('//*[@id="main_table_countries_today"]/tbody[1]/tr[' . $i . ']/td[7]') as $x) {
        $temp['serious_or_critical'] = (int) str_replace(',', '', str_replace('+', '', trim($x->textContent)));
    }
    array_push($val['data'], $temp);
}

echo json_encode($val);
