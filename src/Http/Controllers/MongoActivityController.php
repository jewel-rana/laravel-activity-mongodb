<?php

namespace Rajtika\Mongovity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rajtika\Mongovity\Constants\Mongovity;

class MongoActivityController extends Controller
{
    public function index(Request $request): View
    {
        return view(Mongovity::NAMESPACE . '::index');
    }
}
