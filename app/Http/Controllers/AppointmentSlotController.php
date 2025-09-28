<?php

namespace App\Http\Controllers;

use App\Models\AppointmentSlot;
use App\Http\Requests\AppointmentSlotRequest;
use App\Services\AppointmentSlotService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class AppointmentSlotController extends Controller
{
    protected AppointmentSlotService $service;

    public function __construct(AppointmentSlotService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', AppointmentSlot::class);

        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));

        return view('appointment_slots.index', ['slots' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', AppointmentSlot::class);
        return view('appointment_slots.create');
    }

    public function store(AppointmentSlotRequest $request): RedirectResponse
    {
        $this->authorize('create', AppointmentSlot::class);
        $slot = $this->service->create($request->validated());
        return Redirect::route('appointment_slots.show', $slot)->with('status', 'Slot creado');
    }

    public function show(AppointmentSlot $appointmentSlot): View
    {
        $this->authorize('view', $appointmentSlot);
        return view('appointment_slots.show', ['slot' => $appointmentSlot]);
    }

    public function edit(AppointmentSlot $appointmentSlot): View
    {
        $this->authorize('update', $appointmentSlot);
        return view('appointment_slots.edit', ['slot' => $appointmentSlot]);
    }

    public function update(AppointmentSlotRequest $request, AppointmentSlot $appointmentSlot): RedirectResponse
    {
        $this->authorize('update', $appointmentSlot);
        $this->service->update($appointmentSlot->id, $request->validated());
        return Redirect::route('appointment_slots.show', $appointmentSlot)->with('status', 'Slot actualizado');
    }

    public function destroy(AppointmentSlot $appointmentSlot): RedirectResponse
    {
        $this->authorize('delete', $appointmentSlot);
        $this->service->delete($appointmentSlot->id);
        return Redirect::route('appointment_slots.index')->with('status', 'Slot eliminado');
    }

    // Endpoint JSON
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', AppointmentSlot::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}