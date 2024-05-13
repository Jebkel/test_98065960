<?php

namespace App\Services;

use App\Contracts\HhApiInterface;

class HhApi implements HhApiInterface
{

    /**
     * Recursively extracts names and IDs from the given data array.
     *
     * @param array $data The data array containing areas.
     * @return array The extracted names and IDs.
     */
    private function extractNamesAndIDs(array $data): array
    {
        $result = [];
        foreach ($data as $item) {
            if (empty($item['areas'])) {
                $result[$item['id']] = $item['name'];
            } else {
                $result += $this->extractNamesAndIDs($item['areas']);
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
    private function generateUniqKey(string $key, array $existing_keys) : string {
        if (str_contains($key, ' ')) {
            $key = explode(' ', $key)[0];
        }
        while (in_array($key, $existing_keys, true)) {
            $key .= '1';
        }
        return $key;
    }

    /**
     * Fetches and retrieves the names of countries from the HH.ru API.
     *
     * @param string $language The language code for localization.
     * @return array The array of country names indexed by their IDs.
     */
    private function getNames(string $language): array
    {
        $response = \Http::get('https://api.hh.ru/areas?locale=' . $language);
        # Не делал обработку ошибки API

        # P.s указываю россию так, ибо она сразу первая идёт, но лучше брать по ID
        return $this->extractNamesAndIDs($response->json()[0]['areas']);
    }

    /**
     * Retrieves the countries and their names from the HH.ru API.
     *
     * @return array The array of country names indexed by unique keys.
     */
    public function getCountries(): array
    {
        $ru_areas = $this->getNames('RU');
        $en_areas = $this->getNames("EN");

        $result = [];
        $existing_keys = [];

        foreach ($ru_areas as $ru_key => $ru_value) {
            $en_key = $en_areas[$ru_key] ?? null;
            if ($en_key) {
                $en_key = $this->generateUniqKey($en_key, $existing_keys);
                $existing_keys[] = $en_key;
                $result[$en_key] = $ru_value;
            }
        }

        return $result;
    }
}