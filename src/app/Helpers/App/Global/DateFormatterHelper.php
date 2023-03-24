<?php


use App\Repositories\Core\Setting\SettingRepository;
use Carbon\Carbon;

if (!function_exists('dateFormat')) {

    function dateFormat($date)
    {
        $dateFormat = resolve(SettingRepository::class)
            ->findAppSettingWithName('date_format', 'app');
        $newDate = $date . ' 00:00:00';

        return Carbon::parse(strtotime($newDate))->format($dateFormat->value);
    }
}
