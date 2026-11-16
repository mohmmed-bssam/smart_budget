<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Goal;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
 public function index()
{
    $itemsPerPage = (int) Setting::where('key', 'items_per_page')
        ->value('value');

    $itemsPerPage = $itemsPerPage ?: 10;

    $goals = Goal::with('savings')
        ->where('user_id', Auth::id())
        ->latest()
        ->paginate($itemsPerPage);

    return view('goals.index', compact('goals'));
}


    public function create()
    {
        return view('goals.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'target_amount' => 'required|numeric|min:0.01',

            'currency' => 'required|in:USD,EUR,ILS,JOD,GBP',

            'deadline' => 'nullable|date|after_or_equal:today',
        ]);


        $validated['user_id'] = Auth::id();

        $validated['current_amount'] = 0;

        $validated['status'] = 'active';


        Goal::create($validated);


        flash()->success(
            __('messages.goal_created')
        );


        return redirect()
            ->route('goals.index');
    }


  public function show(Goal $goal)
{
    $this->authorizeGoal($goal);


    $goal->load([
        'savings.account'
    ]);


    $accounts = Account::where(
        'user_id',
        auth()->id()
    )
        ->orderBy('created_at')
        ->get();


    $savedAmount =
        $goal->savings->sum('amount');


    $remaining = max(
        $goal->target_amount - $savedAmount,
        0
    );


    $percentage =
        $goal->target_amount > 0
            ? round(
                ($savedAmount / $goal->target_amount) * 100,
                2
            )
            : 0;


    return view(
        'goals.show',
        compact(
            'goal',
            'accounts',
            'savedAmount',
            'remaining',
            'percentage'
        )
    );
}


    public function edit(Goal $goal)
    {
        $this->authorizeGoal($goal);


        return view(
            'goals.edit',
            compact('goal')
        );
    }


    public function update(
        Request $request,
        Goal $goal
    ) {
        $this->authorizeGoal($goal);


        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'target_amount' => 'required|numeric|min:0.01',

            'currency' => 'required|in:USD,EUR,ILS,JOD,GBP',

            'deadline' => 'nullable|date',

            'status' =>
                'required|in:active,completed,cancelled',
        ]);


        $goal->update($validated);


        flash()->success(
            __('messages.goal_updated')
        );


        return redirect()
            ->route('goals.index');
    }


    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);


        $goal->delete();


        flash()->warning(
            __('messages.goal_deleted')
        );


        return redirect()
            ->route('goals.index');
    }


    private function authorizeGoal(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
