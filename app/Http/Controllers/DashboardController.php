<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $tasks = $request->user()->tasks()->where(function (Builder $query) {
            $query->where('priority', true)
                ->where('due_at', '<', now('America/Chicago')->startOfDay()->utc());
        })->orWhere(function (Builder $query) {
            $query->where('due_at', '>=', now('America/Chicago')->startOfDay()->utc());
        })->orderBy('tasks.due_at')->get();

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
        ]);
    }
}
