<?php

namespace App\Http\Controllers;

use App\Models\ScheduleException;
use App\Http\Requests\ScheduleExceptionRequest;
use App\Services\ScheduleExceptionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class ScheduleExceptionController extends Controller
{
    protected ScheduleExceptionService $service;

    public function __construct(ScheduleExceptionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', ScheduleException::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('schedule_exceptions.index', ['exceptions' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', ScheduleException::class);
        return view('schedule_exceptions.create');
    }

    public function store(ScheduleExceptionRequest $request): RedirectResponse
    {
        $this->authorize('create', ScheduleException::class);
        $ex = $this->service->create($request->validated());
        return Redirect::route('schedule_exceptions.show', $ex)->with('status', 'Excepción creada');
    }

    public function show(ScheduleException $scheduleException): View
    {
        $this->authorize('view', $scheduleException);
        return view('schedule_exceptions.show', ['exception' => $scheduleException]);
    }

    public function edit(ScheduleException $scheduleException): View
    {
        $this->authorize('update', $scheduleException);
        return view('schedule_exceptions.edit', ['exception' => $scheduleException]);
    }

    public function update(ScheduleExceptionRequest $request, ScheduleException $scheduleException): RedirectResponse
    {
        $this->authorize('update', $scheduleException);
        $this->service->update($scheduleException->id, $request->validated());
        return Redirect::route('schedule_exceptions.show', $scheduleException)->with('status', 'Excepción actualizada');
    }

    public function destroy(ScheduleException $scheduleException): RedirectResponse
    {
        $this->authorize('delete', $scheduleException);
        $this->service->delete($scheduleException->id);
        return Redirect::route('schedule_exceptions.index')->with('status', 'Excepción eliminada');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', ScheduleException::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}