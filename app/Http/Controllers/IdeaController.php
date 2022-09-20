<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    public function index()
    {
        return view('idea.index');
    }

    public function create()
    {
        //
    }

    public function store(StoreIdeaRequest $request)
    {
        //
    }

    public function show(Idea $idea)
    {
        return view('idea.show', [
            'idea'       => $idea,
            'votesCount' => $idea->votes()->count(),
            'backUrl' => url()->previous() !== url()->full() && url()->previous() !== route('login')
                ? url()->previous()
                : route('idea.index'),
        ]);
    }

    public function edit(Idea $idea)
    {
        //
    }

    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        //
    }

    public function destroy(Idea $idea)
    {
        //
    }
}
