<?php

namespace Rajtika\Mongovity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Models\ActivityLog;

class MongoActivityController extends Controller
{
    public function index(Request $request)
    {
        if($request->wantsJson() && $request->acceptsJson()) {
            $limit = $request->input('length');
            $start = $request->input('start');
            $columns = $request->get('columns');
            $column = $columns[$request->input('order.0.column')]['data'];
            $order = $request->input('order.0.dir');
            $query = ActivityLog::query();
            $keyword = $request->input('search.value');
            if($keyword) {
                $query->where('causer_id', $keyword);
                $query->orWhere(function($query) use ($keyword) {
                    $query->where('causer_mobile', 'like', "%$keyword%");
                    $query->where('causer_name', 'like', "%$keyword%");
                });
            }

            $total = $query->count();
            $activities = $query->orderBy($column, $order)->offset($start)->limit($limit)->get();
            return response()->json(
                [
                    'draw' => $request->get('draw'),
                    'recordsTotal' => $total,
                    'recordsFiltered' => $total,
                    'data' => $activities->toArray()
                ]
            );
        }
        return view(Mongovity::NAMESPACE . '::index');
    }
}
