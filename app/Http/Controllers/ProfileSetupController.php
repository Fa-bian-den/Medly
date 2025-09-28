<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Profile;
use App\Models\DoctorProfile;

class ProfileSetupController extends Controller
{
    /**
     * Mostrar el formulario inicial de perfil según rol.
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->getRoleNames()->first() ?? 'paciente';

        // Renderizar vistas por rol (crear las blade correspondientes)
        if ($role === 'doctor') {
            return view('profile.setup-doctor', [
                'user' => $user,
            ]);
        }

        // por defecto paciente
        return view('profile.setup-patient', [
            'user' => $user,
        ]);
    }

    /**
     * Guardar/actualizar el perfil inicial (primer llenado obligatorio).
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->getRoleNames()->first() ?? 'paciente';

        // Reglas comunes
        $commonRules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'birthdate'  => ['nullable', 'date'],
            'avatar'     => ['nullable', 'image', 'max:2048'], // 2MB
        ];

        // Reglas por rol
        if ($role === 'doctor') {
            $roleRules = [
                'carnet_minsa'        => ['nullable', 'string', 'max:100'],
                'center_id_proposed'  => ['nullable', 'integer', 'exists:centers,id'],
                'professional_details'=> ['nullable','string','max:2000'],
                'specialties'         => ['nullable','array'],
                'specialties.*'       => ['integer','exists:specialities,id'],
                'documents.*'         => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120'],
            ];
        } else {
            // paciente
            $roleRules = [
                'idcard'                 => ['nullable','string','max:100'],
                'emergency_contact_name' => ['nullable','string','max:150'],
                'emergency_contact_phone'=> ['nullable','string','max:30'],
                // si quieres campos extra del profile los agregas aquí
            ];
        }

        $rules = array_merge($commonRules, $roleRules);

        $data = $request->validate($rules);

        // Guardar datos básicos en users (si aplica)
        $user->fill([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
        ]);
        $user->save();

        // Avatar handling (evitar ->update en instancia para que el analizador no marque error)
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        // Guardar/actualizar profile (tabla profiles)
        $profile = Profile::firstOrNew(['user_id' => $user->id]);
        // Campos comunes o del paciente
        $profile->birthdate = $data['birthdate'] ?? $profile->birthdate;
        $profile->address = $request->input('address', $profile->address);
        $profile->idcard = $data['idcard'] ?? $profile->idcard;
        $profile->phone = $data['phone'] ?? $profile->phone;
        $profile->gender = $request->input('gender', $profile->gender);
        $profile->documents_metadata = $profile->documents_metadata; // no sobrescribir aquí
        $profile->professional_details = $profile->professional_details; // mantiene si no doctor
        $profile->save();

        // Si es doctor, sync con doctor_profiles
        if ($role === 'doctor') {
            $doctor = DoctorProfile::firstOrNew(['user_id' => $user->id]);
            $doctor->center_id_proposed = $data['center_id_proposed'] ?? $doctor->center_id_proposed;
            $doctor->carnet_minsa = $data['carnet_minsa'] ?? $doctor->carnet_minsa;
            $doctor->professional_details = $data['professional_details'] ?? $doctor->professional_details;
            // estado inicial de validación
            if (empty($doctor->status_validation)) {
                $doctor->status_validation = 'pending';
            }
            $doctor->save();

            // documentos (si subidos)
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $filePath = $file->store('doctor_documents', 'public');
                    // almacenar referencia en doctor->documents (json) o en otra tabla según tu diseño
                    $docs = $doctor->documents ? json_decode($doctor->documents, true) : [];
                    $docs[] = ['path' => $filePath, 'uploaded_by' => $user->id, 'uploaded_at' => now()];
                    $doctor->documents = json_encode($docs);
                }
                $doctor->save();
            }

            // specialties: sync pivot (si vienen ids)
            if (!empty($data['specialties']) && is_array($data['specialties'])) {
                $doctor->specialities()->sync($data['specialties']);
            }
        }

        // Registrar quien hizo la última modificación si hace falta
        // $profile->updated_by = auth()->id(); $profile->save();

        // Marcar que el usuario completó perfil (usar save en lugar de update y docblock para IDE)
        if (Schema::hasColumn('users', 'profile_completed') && empty($user->profile_completed)) {
            $user->profile_completed = true;
            $user->save();
        }

        // Redirigir al dashboard
        return redirect()->intended(route('dashboard'))->with('status', 'profile-completed');
    }
}