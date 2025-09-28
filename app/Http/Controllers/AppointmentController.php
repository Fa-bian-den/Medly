<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AppointmentController extends Controller
{
    protected AppointmentService $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Appointment::class);

        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));

        return view('appointments.index', ['appointments' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', Appointment::class);
        return view('appointments.create');
    }

    public function store(AppointmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Appointment::class);

        $data = $request->validated();
        if (! isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        $appointment = $this->service->create($data);

        return Redirect::route('appointments.show', $appointment)->with('status', 'Cita creada');
    }

    public function show(Appointment $appointment): View
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', ['appointment' => $appointment]);
    }

    public function edit(Appointment $appointment): View
    {
        $this->authorize('update', $appointment);
        return view('appointments.edit', ['appointment' => $appointment]);
    }

    public function update(AppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $this->service->update($appointment->id, $request->validated());

        return Redirect::route('appointments.show', $appointment)->with('status', 'Cita actualizada');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $this->authorize('delete', $appointment);

        $this->service->delete($appointment->id);

        return Redirect::route('appointments.index')->with('status', 'Cita eliminada');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}