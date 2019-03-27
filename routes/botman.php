<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Middleware\ApiAi;

$botman = resolve('botman');

$dialogflow = ApiAi::create('c57d7b99b0764b5aac3a1211209e5a71')->listenForAction();

$botman->middleware->received($dialogflow);

$botman->hears('Weather', \App\Commands\WeatherActionCommands::class)->middleware($dialogflow);