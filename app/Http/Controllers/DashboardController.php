<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $clientCount  = Client::count();
        $projectCount = Project::count();
        $taskCount    = Task::count();

        $tasksByStatus = Task::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $projectsByStatus = Project::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentProjects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'clientCount',
            'projectCount',
            'taskCount',
            'tasksByStatus',
            'projectsByStatus',
            'recentProjects'
        ));
    }
}
