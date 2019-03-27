<?php

namespace App\Commands;

use App\Services\ApixuWeatherService;
use BotMan\BotMan\BotMan;

class WeatherActionCommands
{
    /**
     * @var \App\Services\ApixuWeatherService
     */
    private $weatherService;

    /**
     * WeatherActionCommands constructor.
     *
     * @param \App\Services\ApixuWeatherService $weatherService
     */
    public function __construct(ApixuWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function __invoke(BotMan $bot)
    {
        $extras = $bot->getMessage()->getExtras(); // Get Extras from message
        $apiParameters = $extras['apiParameters']; // apiParams from dialogflow

        $location = $apiParameters['address']->country ?? $apiParameters['address']->city ?? '';

        $weather = $this->weatherService->getCurrentWeather($location); // getting weather info from apixu.com

        $bot->reply(view('messages.weather', compact('location', 'weather'))->render()); //reply to message
    }
}