<?php

namespace Rajtika\Mongovity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Models\ActivityLog;

class MongoActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = \Rajtika\Mongovity\Services\Mongovity::get();
        return view(Mongovity::NAMESPACE . '::index', compact('activities'));
    }
}
