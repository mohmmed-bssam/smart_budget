<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{

    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

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

            'target_amount' => 'required|numeric|min:0',

            'deadline' => 'nullable|date',

        ]);


        $validated['user_id'] = auth()->id();

        $validated['current_amount'] = 0;

        $validated['status'] = 'active';


        Goal::create($validated);


        flash()->success(__('messages.goal_created'));


        return redirect()
            ->route('goals.index');
    }




    public function show(Goal $goal)
    {
        $this->authorizeGoal($goal);


        return view('goals.show', compact('goal'));
    }




    public function edit(Goal $goal)
    {
        $this->authorizeGoal($goal);


        return view('goals.edit', compact('goal'));
    }




    public function update(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal);


        $validated = $request->validate([

            'title' => 'required|string|max:255',

            'target_amount' => 'required|numeric|min:0',

            'deadline' => 'nullable|date',

            'status' => 'required|string',

        ]);



        $goal->update($validated);



        flash()->success(__('messages.goal_updated'));



        return redirect()
            ->route('goals.index');
    }




    public function destroy(Goal $goal)
    {
        $this->authorizeGoal($goal);


        $goal->delete();



        flash()->warning(__('messages.goal_deleted'));



        return redirect()
            ->route('goals.index');
    }





    private function authorizeGoal(Goal $goal)
    {
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }
    }
}