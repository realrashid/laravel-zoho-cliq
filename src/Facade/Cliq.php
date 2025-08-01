<?php

namespace RealRashid\Cliq\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \RealRashid\Cliq\Cliq card(string $title, string $icon, string $thumbnail, string $botName, array $buttons, string $theme = 'modern-inline')
 * @method static array send(string $message)
 * @method static \RealRashid\Cliq\Cliq to(string|array $channels)
 * @method static void setDefaultChannel(string|array $channels)
 * @method static string getAccessToken()
 * @method static void setAccessToken(string $accessToken)
 * @method static \RealRashid\Cliq\Cliq toChannel()
 */
class Cliq extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cliq';
    }
}
