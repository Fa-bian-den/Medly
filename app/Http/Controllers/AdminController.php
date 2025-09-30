<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request as BaseRequest;

class AdminController extends Controller
{
    protected AdminService $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    // Listado web
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('admin.index', ['admins' => $items]);
    }

    // Formulario crear
    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('admin.create');
    }

    // Guardar
    public function store(AdminRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        $avatar = null;
        if ($httpRequest->hasFile('avatar')) {
            $maybe = $httpRequest->file('avatar');
            $avatar = $maybe instanceof UploadedFile ? $maybe : null;
        }

        $admin = $this->service->create($data, $avatar);

        return Redirect::route('admin.show', $admin)->with('status', 'Administrador creado');
    }

    // Mostrar
    public function show(User $admin): View
    {
        $this->authorize('view', $admin);
        $item = $this->service->find($admin->id);
        return view('admin.show', ['admin' => $item]);
    }

    // Editar
    public function edit(User $admin): View
    {
        $this->authorize('update', $admin);
        $item = $this->service->find($admin->id);
        return view('admin.edit', ['admin' => $item]);
    }

    // Actualizar
    public function update(AdminRequest $request, User $admin): RedirectResponse
    {
        $this->authorize('update', $admin);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        $avatar = null;
        if ($httpRequest->hasFile('avatar')) {
            $maybe = $httpRequest->file('avatar');
            $avatar = $maybe instanceof UploadedFile ? $maybe : null;
        }

        $this->service->update($admin->id, $data, $avatar);

        return Redirect::route('admin.show', $admin)->with('status', 'Administrador actualizado');
    }

    // Eliminar
    public function destroy(User $admin): RedirectResponse
    {
        $this->authorize('delete', $admin);
        $this->service->delete($admin->id);
        return Redirect::route('admin.index')->with('status', 'Administrador eliminado');
    }

    // API: listado JSON
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }

    // API: detalle JSON
    public function apiShow(User $admin)
    {
        $this->authorize('view', $admin);
        $item = $this->service->find($admin->id);
        return response()->json($item);
    }
}