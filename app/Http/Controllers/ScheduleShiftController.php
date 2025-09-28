<?php

namespace App\Http\Controllers;

use App\Models\ScheduleShift;
use App\Http\Requests\ScheduleShiftRequest;
use App\Services\ScheduleShiftService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class ScheduleShiftController extends Controller
{
    protected ScheduleShiftService $service;

    public function __construct(ScheduleShiftService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', ScheduleShift::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('schedule_shifts.index', ['shifts' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', ScheduleShift::class);
        return view('schedule_shifts.create');
    }

    public function store(ScheduleShiftRequest $request): RedirectResponse
    {
        $this->authorize('create', ScheduleShift::class);
        $shift = $this->service->create($request->validated());
        return Redirect::route('schedule_shifts.show', $shift)->with('status', 'Shift creado');
    }

    public function show(ScheduleShift $scheduleShift): View
    {
        $this->authorize('view', $scheduleShift);
        return view('schedule_shifts.show', ['shift' => $scheduleShift]);
    }

    public function edit(ScheduleShift $scheduleShift): View
    {
        $this->authorize('update', $scheduleShift);
        return view('schedule_shifts.edit', ['shift' => $scheduleShift]);
    }

    public function update(ScheduleShiftRequest $request, ScheduleShift $scheduleShift): RedirectResponse
    {
        $this->authorize('update', $scheduleShift);
        $this->service->update($scheduleShift->id, $request->validated());
        return Redirect::route('schedule_shifts.show', $scheduleShift)->with('status', 'Shift actualizado');
    }

    public function destroy(ScheduleShift $scheduleShift): RedirectResponse
    {
        $this->authorize('delete', $scheduleShift);
        $this->service->delete($scheduleShift->id);
        return Redirect::route('schedule_shifts.index')->with('status', 'Shift eliminado');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', ScheduleShift::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}