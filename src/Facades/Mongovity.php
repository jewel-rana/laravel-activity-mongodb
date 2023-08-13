<?php

namespace Rajtika\Mongovity\Facades;

use Illuminate\Support\Facades\Facade;

class Mongovity extends Facade
{
    /**
     * @method static \Rajtika\Mongovity\Services\Mongovity test()
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Rajtika\Mongovity\Services\Mongovity::class;
    }
}
