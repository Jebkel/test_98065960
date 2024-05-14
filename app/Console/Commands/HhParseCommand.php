<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Services\HhApi;
use DB;
use Illuminate\Console\Command;

class HhParseCommand extends Command
{
    protected $signature = 'hh:parse';

    protected $description = 'Парсит города с hh и загружает в бд';

    public function handle(): void
    {
        // На моей локальной машине этот способ загрузки занимал примерно 1 секунду(7к записей)

        $batchSize = 1000;

        $dataChunks = array_chunk(HhApi::getCountries(), $batchSize, true);

        foreach ($dataChunks as $chunk) {
            $altNames = array_keys($chunk);

            // Получаем существующие записи по alt_name
            $existingRecords = Area::whereIn('alt_name', $altNames)->pluck('alt_name', 'id');

            $updates = [];
            $inserts = [];

            foreach ($chunk as $altName => $name) {
                if (isset($existingRecords[$altName])) {
                    // Если запись существует, добавляем её в массив для обновления
                    $updates[$existingRecords[$altName]] = ['name' => $name];
                } else {
                    // Если запись не существует, добавляем её в массив для вставки
                    $inserts[] = ['alt_name' => $altName, 'name' => $name];
                }
            }

            // Массовое обновление существующих записей
            if (!empty($updates)) {
                foreach ($updates as $id => $values) {
                    Area::where('id', $id)->update($values);
                }
            }

            // Пакетная вставка новых записей
            if (!empty($inserts)) {
                DB::table('areas')->insert($inserts);
            }
        }
    }
}
