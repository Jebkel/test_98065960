<?php

namespace App\Services;

use App\Contracts\HhApiInterface;
use Http;

class HhApi implements HhApiInterface
{

    public static function transliterate($textcyr): array|string
    {
        $cyr = array(
            'ж',
            'ч',
            'щ',
            'ш',
            'ю',
            'а',
            'б',
            'в',
            'г',
            'д',
            'е',
            'з',
            'и',
            'й',
            'к',
            'л',
            'м',
            'н',
            'о',
            'п',
            'р',
            'с',
            'т',
            'у',
            'ф',
            'х',
            'ц',
            'ъ',
            'ь',
            'я',
            'Ж',
            'Ч',
            'Щ',
            'Ш',
            'Ю',
            'А',
            'Б',
            'В',
            'Г',
            'Д',
            'Е',
            'З',
            'И',
            'Й',
            'К',
            'Л',
            'М',
            'Н',
            'О',
            'П',
            'Р',
            'С',
            'Т',
            'У',
            'Ф',
            'Х',
            'Ц',
            'Ъ',
            'Ь',
            'Я'
        );
        $lat = array(
            'zh',
            'ch',
            'sht',
            'sh',
            'yu',
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'z',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'c',
            'y',
            'x',
            'q',
            'Zh',
            'Ch',
            'Sht',
            'Sh',
            'Yu',
            'A',
            'B',
            'V',
            'G',
            'D',
            'E',
            'Z',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'R',
            'S',
            'T',
            'U',
            'F',
            'H',
            'c',
            'Y',
            'X',
            'Q'
        );
        return str_replace($cyr, $lat, $textcyr);
    }

    /**
     * Recursively extracts names and IDs from the given data array.
     *
     * @param array $data The data array containing areas.
     * @return array The extracted names and IDs.
     */
    private static function extractNamesAndIDs(array $data): array
    {
        $result = [];
        foreach ($data as $item) {
            if (empty($item['areas'])) {
                $result[$item['id']] = $item['name'];
            } else {
                $result += self::extractNamesAndIDs($item['areas']);
            }
        }
        return $result;
    }

    /**
     * Generates a unique key for the given key among the existing keys.
     *
     * @param string $key The key to be made unique.
     * @param array $existing_keys Array containing existing keys.
     * @return string The generated unique key.
     */
    private static function generateUniqKey(string $key, array $existing_keys): string
    {
        if (str_contains($key, ' ')) {
            $key = explode(' ', $key)[0];
        } else {
            return self::transliterate($key);
        }
        $counter = 1;
        while (in_array($key . $counter, $existing_keys, true)) {
            $counter++;
        }
        return self::transliterate($key) . $counter;
    }

    /**
     * Fetches and retrieves the names of countries from the HH.ru API.
     *
     * @param string $language The language code for localization.
     * @return array The array of country names indexed by their IDs.
     */
    private static function getNames(string $language): array
    {
        $response = Http::withoutVerifying()->get('https://api.hh.ru/areas?locale=' . $language);
        # Не делал обработку ошибки API

        # P.s указываю россию так, ибо она сразу первая идёт, но лучше брать по ID
        return self::extractNamesAndIDs($response->json()[0]['areas']);
    }

    /**
     * Retrieves the countries and their names from the HH.ru API.
     *
     * @return array The array of country names indexed by unique keys.
     */
    public static function getCountries(): array
    {
        $ru_areas = self::getNames('RU');
        $en_areas = self::getNames("EN");

        $result = [];

        foreach ($ru_areas as $ru_key => $ru_value) {
            $en_key = $en_areas[$ru_key];
            $uniq_en_key = self::generateUniqKey($en_key, $result);
            $result[$uniq_en_key] = $ru_value;
        }

        return $result;
    }
}