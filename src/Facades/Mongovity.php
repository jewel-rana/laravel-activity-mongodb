<?php

namespace Rajtika\Mongovity\Facades;

use Illuminate\Support\Facades\Facade;

class Mongovity extends Facade
{
    /**
     * @method static \Rajtika\Mongovity\Services\Mongovity by()
     * @return string
     * @method static \Rajtika\Mongovity\Services\Mongovity get()
     */
    protected static function getFacadeAccessor(): string
    {
        return \Rajtika\Mongovity\Services\Mongovity::class;
    }
}
