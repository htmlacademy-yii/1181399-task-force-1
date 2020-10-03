<?php

namespace frontend\services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class YandexMapsApiService
{
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getPositionFromAddress(string $address, int $limit = 1)
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru'
        ]);

        $data = null;
        try {
            $request = new Request('GET', '/1.x');
            $response = $client->send($request, [
                'query' => [
                    'apikey' => $this->apiKey,
                    'geocode' => $address,
                    'format' => 'json',
                    'results' => $limit
                ]
            ]);

            $content = $response->getBody()->getContents();
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException("Invalid json format", $request, $response);
            }
        } catch (RequestException $e) {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        return $data;
    }

    public function getCoordsFromResponse($response)
    {
        if (!$response) {
            return null;
        }

        if (!isset($response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
            return null;
        }

        $coordsString = $response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
        [$w, $h] = explode(' ', $coordsString);
        return ['lat' => $w, 'long' => $h];
    }

    public function getCityFromResponse($response)
    {
        if (!$response) {
            return null;
        }

        if (!isset($response['response']['GeoObjectCollection']['featureMember'][0]
            ['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'])) {
            return null;
        }

        $components = $response['response']['GeoObjectCollection']['featureMember'][0]
            ['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'];

        foreach ($components as $component) {
            if (isset($component['kind']) && $component['kind'] === 'locality') {
                return $component['name'];
            }
        }

        return null;
    }
}
