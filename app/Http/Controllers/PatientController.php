<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\User;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request as BaseRequest;

class PatientController extends Controller
{
    protected PatientService $service;

    public function __construct(PatientService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('patients.index', ['patients' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('patients.create');
    }

    public function store(PatientRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        if (! $httpRequest->hasFile('avatar')) {
            $avatar = null;
        } else {
            /** @var UploadedFile|null $maybeAvatar */
            $maybeAvatar = $httpRequest->file('avatar');
            $avatar = $maybeAvatar instanceof UploadedFile ? $maybeAvatar : null;
        }

        $patient = $this->service->create($data, $avatar);

        return Redirect::route('patients.show', $patient)->with('status', 'Paciente creado');
    }


    public function show(User $patient): View
    {
        $this->authorize('view', $patient);
        $patient = $this->service->find($patient->id);
        return view('patients.show', ['patient' => $patient]);
    }

    public function edit(User $patient): View
    {
        $this->authorize('update', $patient);
        $patient = $this->service->find($patient->id);
        return view('patients.edit', ['patient' => $patient]);
    }

    public function update(PatientRequest $request, User $patient): RedirectResponse
    {
        $this->authorize('update', $patient);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        if (! $httpRequest->hasFile('avatar')) {
            $avatar = null;
        } else {
            /** @var UploadedFile|null $maybeAvatar */
            $maybeAvatar = $httpRequest->file('avatar');
            $avatar = $maybeAvatar instanceof UploadedFile ? $maybeAvatar : null;
        }

        $this->service->update($patient->id, $data, $avatar);

        return Redirect::route('patients.show', $patient)->with('status', 'Paciente actualizado');
    }


    public function destroy(User $patient): RedirectResponse
    {
        $this->authorize('delete', $patient);
        $this->service->delete($patient->id);
        return Redirect::route('patients.index')->with('status', 'Paciente eliminado');
    }

    // API endpoints
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }

    public function apiShow(User $patient)
    {
        $this->authorize('view', $patient);
        $patient = $this->service->find($patient->id);
        return response()->json($patient);
    }
}