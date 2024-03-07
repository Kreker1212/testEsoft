<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function allTask(TaskRepository $repository): View
    {
        $myId = auth('web')->user()->id;
        $bossId = auth()->user()->boss_id;
        $responsiblePeople = null;

        $allMyTask = $repository->getTasksByDesc($myId);
        $allUsers = User::all();

        if (!isset($bossId)) {
            $responsiblePeople = User::query()->where('boss_id', $myId)->get();
        }

        return view('tasks', [
            'allTask' => $allMyTask,
            'all_users' => $allUsers,
            'resp_people' => $responsiblePeople,
        ]);
    }

    public function showFilterTask(TaskRepository $repository, Request $req): View
    {
        $myId = auth('web')->user()->id;

        $responsiblePeople = null;
        $bossId = auth()->user()->boss_id;

        $allTask = $repository->getTasksByDesc($myId);

        $allUsers = User::all();

        if (!isset($bossId)) {
            $responsiblePeople = User::query()->where('boss_id', $myId)->get();
        }


        $value = $req->input('filter');
        switch ($value) {
            case 'tasks_today' :
                $allTask = $repository->getTasksToday($myId);
                break;
            case 'tasks_week':
                $allTask = $repository->getTasksOnWeek($myId);
                break;
            case 'tasks_more_week':
                $allTask = $repository->getTasksMoreWeek($myId);
                break;

            default:
                return view('tasks', [
                    'allTask' => $allTask,
                    'all_users' => $allUsers,
                    'resp_people' => $responsiblePeople,
                ]);

        }

        return view('tasks', [
            'allTask' => $allTask,
            'all_users' => $allUsers,
            'resp_people' => $responsiblePeople
        ]);
    }

    public function showFilterTaskResponsible(TaskRepository $repository, Request $req): View
    {
        $myId = auth('web')->user()->id;
        $responsibleId = $req->input('filter_responsible');
        $responsiblePeople = null;
        $bossId = auth()->user()->boss_id;

        $allTask = $repository->getTasksByDesc($myId);


        $allUsers = User::all();

        if (!isset($bossId)) {
            $responsiblePeople = User::query()->where('boss_id', $myId)->get();
        }


        if (isset($responsibleId)) {
            $allTask = $repository->getTasksResponsible($myId, $responsibleId);
        }


        return view('tasks', [
            'allTask' => $allTask,
            'all_users' => $allUsers,
            'resp_people' => $responsiblePeople
        ]);
    }

    public function addTaskSubmit(TaskRequest $req): RedirectResponse
    {

        Task::create([
            'title' => $req->title,
            'description' => $req->description,
            'date_end' => $req->date_end,
            'priority' => $req->priority,
            'creator_id' => $req->creator_id,
            'responsible_id' => $req->responsible_id,
        ]);

        return redirect(route('show.home'));
    }

    public function changeTask(TaskRepository $repository, Request $req): RedirectResponse
    {
        $task = $repository->findById($req->id_course);
        $task->title = $req->title ?? $task->title;
        $task->description = $req->description ?? $task->description;
        $task->date_end = $req->date_end ?? $task->date_end;
        $task->priority = $req->priority ?? $task->priority;
        $task->status = $req->status ?? $task->status;
        $task->date_end = $req->date_end ?? $task->date_end;
        $task->responsible_id = $req->responsible_id ?? $task->responsible_id;
        $task->save();

        return redirect(route('show.home'));
    }
}
