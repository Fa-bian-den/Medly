<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class ScheduleController extends Controller
{
    protected ScheduleService $service;

    public function __construct(ScheduleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Schedule::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('schedules.index', ['schedules' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', Schedule::class);
        return view('schedules.create');
    }

    public function store(ScheduleRequest $request): RedirectResponse
    {
        $this->authorize('create', Schedule::class);
        $schedule = $this->service->create($request->validated());
        return Redirect::route('schedules.show', $schedule)->with('status', 'Schedule creado');
    }

    public function show(Schedule $schedule): View
    {
        $this->authorize('view', $schedule);
        return view('schedules.show', ['schedule' => $schedule]);
    }

    public function edit(Schedule $schedule): View
    {
        $this->authorize('update', $schedule);
        return view('schedules.edit', ['schedule' => $schedule]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);
        $this->service->update($schedule->id, $request->validated());
        return Redirect::route('schedules.show', $schedule)->with('status', 'Schedule actualizado');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);
        $this->service->delete($schedule->id);
        return Redirect::route('schedules.index')->with('status', 'Schedule eliminado');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', Schedule::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}