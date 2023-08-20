<?php

use Rajtika\Mongovity\Services\Mongovity;

if (! function_exists('activity')) {
    function activity(string $logName = null): Mongovity
    {
        return app(Mongovity::class);
    }
}
