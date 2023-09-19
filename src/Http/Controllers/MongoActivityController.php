<?php

namespace Rajtika\Mongovity\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Models\ActivityLog;

class MongoActivityController extends Controller
{
    public function test()
    {
        return app(\Rajtika\Mongovity\Services\Mongovity::class)->by(User::first())->log('Test');
    }
    public function index(Request $request)
    {
        if ($request->wantsJson() && $request->acceptsJson()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $columns = $request->get('columns');
            $column = $columns[$request->input('order.0.column')]['data'];
            $order = $request->input('order.0.dir');
            $keyword = $request->input('search.value');
            $query = ActivityLog::query();
            if ($keyword) {
                $query->where('causer_id', '=', (int)$keyword);
                if (strpos($keyword, '.')) {
                    $query->orWhere('ip', 'like', "$keyword%");
                } elseif (strlen($keyword) >= 5) {
                    $query->orWhere('causer_mobile', 'like', "$keyword%");
                }
            }

            $startDate = $request->input('date_from') ?? now()->format('Y-m-d');
            $endDate = $request->input('date_to') ?? now()->format('Y-m-d');

            $query->whereBetween(
                'created_at', array(
                Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()
            ));

            $total = $query->count();
            $activities = $query->orderBy($column, $order)
                ->offset($start)
                ->limit($limit)
                ->get()
                ->map(function($item, $key) {
                    return $item->format();
                });
            return response()->json(
                [
                    'draw' => $request->get('draw'),
                    'recordsTotal' => $total,
                    'recordsFiltered' => $total,
                    'data' => $activities->toArray()
                ]
            );
        }
        return view(Mongovity::NAMESPACE . '::index')->with(['title' => 'Activity logs']);
    }
}
