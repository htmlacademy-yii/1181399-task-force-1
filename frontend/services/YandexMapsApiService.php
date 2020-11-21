<?php

namespace frontend\services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Yii;

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

    /**
     * Получаем координаты из адреса, введенного пользователем
     *
     * @param string $address
     * @param int $limit
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPositionFromAddress(string $address, int $limit = 1)
    {
        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru'
        ]);

        $data = null;

        $address = $this->appendCityToSearchString($address);

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

    /**
     * Вытаскиваем координаты из ответа, который был получен предыдущим методом
     *
     * @param $response
     * @return array|null
     */
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

    /**
     * Вытаскиваем название города из ответа от яндекса.
     *
     * @param $response
     * @return mixed|null
     */
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

    /**
     * Выполняем действия из тз: подключаем название города пользователя, если он есть, к запросу.
     *
     * @param string $address
     * @return string
     * @throws \Throwable
     */
    public function appendCityToSearchString(string $address)
    {
        if (isset(Yii::$app->user->getIdentity()->city->name)) {
            $address = Yii::$app->user->getIdentity()->city->name . ', ' . $address;
        }

        return $address;
    }
}
