<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Http\Requests\MedicalHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MedicalHistoryController extends Controller
{
    /**
     * Mostrar lista paginada de historiales médicos (web).
     */
    public function index(Request $request): View
    {
        // Autorizar acceso (ajusta policy si la tienes)
        $this->authorize('viewAny', MedicalHistory::class);

        $q = $request->input('q');
        $query = MedicalHistory::query();

        if ($q) {
            $query->where('notes', 'like', "%{$q}%");
        }

        $items = $query->orderByDesc('created_at')->paginate(20);

        return view('medical_histories.index', [
            'medicalHistories' => $items,
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo historial (web).
     */
    public function create(): View
    {
        $this->authorize('create', MedicalHistory::class);

        return view('medical_histories.create');
    }

    /**
     * Guardar historial médico nuevo.
     */
    public function store(MedicalHistoryRequest $request): RedirectResponse
    {
        $this->authorize('create', MedicalHistory::class);

        $data = $request->validated();

        // Si el modelo tiene user_id y quieres asignarlo al actual:
        if (! isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $medical = MedicalHistory::create($data);

        return Redirect::route('medical_histories.show', $medical)->with('status', 'Historial creado');
    }

    /**
     * Mostrar registro específico.
     */
    public function show(MedicalHistory $medicalHistory): View
    {
        $this->authorize('view', $medicalHistory);

        return view('medical_histories.show', [
            'medicalHistory' => $medicalHistory,
        ]);
    }

    /**
     * Formulario edición.
     */
    public function edit(MedicalHistory $medicalHistory): View
    {
        $this->authorize('update', $medicalHistory);

        return view('medical_histories.edit', [
            'medicalHistory' => $medicalHistory,
        ]);
    }

    /**
     * Actualizar registro.
     */
    public function update(MedicalHistoryRequest $request, MedicalHistory $medicalHistory): RedirectResponse
    {
        $this->authorize('update', $medicalHistory);

        $medicalHistory->fill($request->validated());
        $medicalHistory->save();

        return Redirect::route('medical_histories.show', $medicalHistory)->with('status', 'Historial actualizado');
    }

    /**
     * Eliminar registro.
     */
    public function destroy(MedicalHistory $medicalHistory): RedirectResponse
    {
        $this->authorize('delete', $medicalHistory);

        $medicalHistory->delete();

        return Redirect::route('medical_histories.index')->with('status', 'Historial eliminado');
    }

    /**
     * Endpoint API JSON paginado (para SPA o mobile).
     */
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', MedicalHistory::class);

        $perPage = (int) $request->input('per_page', 25);
        $items = MedicalHistory::orderByDesc('created_at')->paginate($perPage);

        return response()->json($items);
    }
}