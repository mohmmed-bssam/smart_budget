<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\Goal;
use Illuminate\Http\Request;

class SavingController extends Controller
{

    public function index()
    {
        $savings = Saving::whereHas('goal', function ($query) {

            $query->where('user_id', auth()->id());
        })
            ->with('goal')
            ->latest()
            ->paginate(10);


        return view('savings.index', compact('savings'));
    }




    public function create()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->get();


        return view('savings.create', compact('goals'));
    }




    public function store(Request $request)
    {
        $validated = $request->validate([

            'goal_id' => 'required|exists:goals,id',

            'amount' => 'required|numeric|min:0',

            'date' => 'required|date',

            'note' => 'nullable|string',

        ]);



        Saving::create($validated);



        flash()->success(__('messages.saving_created'));



        return redirect()
            ->route('savings.index');
    }




    public function show(Saving $saving)
    {
        return view('savings.show', compact('saving'));
    }




    public function edit(Saving $saving)
    {
        return view('savings.edit', compact('saving'));
    }




    public function update(Request $request, Saving $saving)
    {

        $validated = $request->validate([

            'amount' => 'required|numeric|min:0',

            'date' => 'required|date',

            'note' => 'nullable|string',

        ]);



        $saving->update($validated);



        flash()->success(__('messages.saving_updated'));



        return redirect()
            ->route('savings.index');
    }




    public function destroy(Saving $saving)
    {
        $saving->delete();


        flash()->warning(__('messages.saving_deleted'));


        return redirect()
            ->route('savings.index');
    }
}