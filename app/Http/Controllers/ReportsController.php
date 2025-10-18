<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ActivityUpdate;
use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ActivityUpdate::with(['activity', 'user']);

     
        if ($request->has('start') && $request->start) {
            $query->whereDate('date', '>=', $request->start);
        }
        if ($request->has('end') && $request->end) {
            $query->whereDate('date', '<=', $request->end);
        }

        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $updates = $query->get();

        return view('reports.index', compact('updates'));
    }
}