<?php
/**
 * Created by PhpStorm.
 * User: oleghalin
 * Date: 2019-03-27
 * Time: 21:37
 */

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class ApixuWeatherService
{
    public function __construct()
    {
        $handler = new HandlerStack();
        $handler->setHandler(new CurlHandler());

        $key = config('services.weather.key');

        $handler->unshift(Middleware::mapRequest(function (RequestInterface $request) use ($key) {
            return $request->withUri(Uri::withQueryValue($request->getUri(), 'key', $key));
        }));

        $this->client = new Client([
            'base_uri' => 'https://api.apixu.com/v1/',
            'handler'  => $handler,
        ]);
    }

    public function getCurrentWeather($city)
    {
        return $this->request('current.json', [
            'query' => [
                'q' => $city
            ],
        ]);
    }

    public function request($endpoint, $params = [], $method = 'GET')
    {
        $response = $this->client->request($method, $endpoint, $params);

        return json_decode($response->getBody()->getContents());
    }
}