<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class ActivitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activities = Activity::with(['updates' => function ($query) {
            $query->whereDate('date', today())->latest();
        }, 'updates.user', 'created_by'])->get();
        return view('activities.index', ['activities' => $activities, 'selectedActivity' => null]);
    }

    public function edit($id)
    {
        $activities = Activity::with(['updates' => function ($query) {
            $query->whereDate('date', today())->latest();
        }, 'updates.user', 'created_by'])->get();
        $selectedActivity = Activity::with(['updates' => function ($query) {
            $query->whereDate('date', today())->latest();
        }, 'updates.user', 'created_by'])->findOrFail($id);
        return view('activities.index', compact('activities', 'selectedActivity'));
    }

    public function dashboard()
    {
        $activities = Activity::with(['updates' => function ($query) {
            $query->whereDate('date', today())->latest();
        }, 'updates.user', 'created_by'])->get();
        return view('activities.dashboard', compact('activities'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $data['user_id'] = Auth::id();
        Activity::create($data);
        return redirect()->route('dashboard')->with('success', 'Activity created');
    }

    public function showUpdateForm($id)
    {
        $activity = Activity::with('created_by')->findOrFail($id);
        return view('activities.update', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:Pending,Done',
            'remark' => 'required|string|max:255',
        ]);
        $data['activity_id'] = $id;
        $data['user_id'] = Auth::id();
        $data['date'] = now()->format('Y-m-d');
        ActivityUpdate::create($data);
        return redirect()->route('activities.index')->with('success', 'Activity updated');
    }
}