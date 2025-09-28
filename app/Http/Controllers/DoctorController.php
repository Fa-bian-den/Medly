<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\User;
use App\Services\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    protected DoctorService $service;

    public function __construct(DoctorService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('doctors.index', ['doctors' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('doctors.create');
    }

        public function store(DoctorRequest $request): RedirectResponse
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

        $doctor = $this->service->create($data, $avatar);

        return Redirect::route('doctors.show', $doctor)->with('status', 'Médico creado');
    }


    public function show(User $doctor): View
    {
        $this->authorize('view', $doctor);
        $doctor = $this->service->find($doctor->id);
        return view('doctors.show', ['doctor' => $doctor]);
    }

    public function edit(User $doctor): View
    {
        $this->authorize('update', $doctor);
        $doctor = $this->service->find($doctor->id);
        return view('doctors.edit', ['doctor' => $doctor]);
    }

        public function update(DoctorRequest $request, User $doctor): RedirectResponse
    {
        $this->authorize('update', $doctor);
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

        $this->service->update($doctor->id, $data, $avatar);

        return Redirect::route('doctors.show', $doctor)->with('status', 'Médico actualizado');
    }

    public function destroy(User $doctor): RedirectResponse
    {
        $this->authorize('delete', $doctor);
        $this->service->delete($doctor->id);
        return Redirect::route('doctors.index')->with('status', 'Médico eliminado');
    }

    // Acción para revisar solicitud del médico (aprobación/rechazo)
    public function review(Request $request, User $doctor): RedirectResponse
    {
        $this->authorize('review', User::class);

        $action = $request->input('action'); // approve / reject
        $comments = $request->input('comments');

        // lógica simple: delega a service o actualiza doctorProfile
        $profile = $doctor->doctorProfile;
        if (! $profile) {
            return Redirect::back()->withErrors(['profile' => 'Perfil de doctor no encontrado']);
        }

        if ($action === 'approve') {
            $profile->status_validation = 'aprobado';
            $profile->reviewed_by = Auth::id();
            $profile->validated_at = now();
            $profile->comments = $comments;
            $profile->save();
        } else {
            $profile->status_validation = 'rechazado';
            $profile->reviewed_by = Auth::id();
            $profile->validated_at = now();
            $profile->comments = $comments;
            $profile->save();
        }

        return Redirect::route('doctors.show', $doctor)->with('status', 'Revisión aplicada');
    }

    // API
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }
}