<?php

namespace hazeld\i18ntl\utils;

use pocketmine\Server;

final class Logger
{
    public static function error(string $msg): void
    {
        Server::getInstance()->getLogger()->error($msg);
    }
}
