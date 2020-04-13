<?php

declare(strict_types=1);

namespace App\Classes\V1;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class ChannelReport
 * @package App\Classes\V1
 */
abstract class ChannelReport
{
    /**
     * @var Client
     */
    public static $client;

    /**
     * Goutte client implemented to use web scrapping
     * All Clients (Channels) must extends this class to have the Goutte client in your scope
     * If you need to modify the HttpClient in the future, you just need to pass your override attributes
     */
    public function __construct()
    {
        self::$client = new Client(HttpClient::create([
            'timeout' => 60
        ]));
    }
}
