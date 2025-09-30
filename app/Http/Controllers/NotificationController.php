<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    // Listado web (lista del usuario actual por defecto)
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Notification::class);

        $filters = $request->all();
        if (!isset($filters['user_id'])) {
            $filters['user_id'] = $request->user()->id;
        }

        $items = $this->service->list($filters, (int)$request->input('per_page', 25));

        return view('notifications.index', ['notifications' => $items]);
    }

    // Formulario crear
    public function create(): View
    {
        $this->authorize('create', Notification::class);
        return view('notifications.create');
    }

    // Guardar
    public function store(NotificationRequest $request): RedirectResponse
    {
        $this->authorize('create', Notification::class);

        $data = $request->validated();

        $notification = $this->service->create($data);

        return Redirect::route('notifications.show', $notification)->with('status', 'Notificación creada');
    }

    // Mostrar detalle
    public function show(Notification $notification): View
    {
        $this->authorize('view', $notification);
        $item = $this->service->find($notification->id);
        return view('notifications.show', ['notification' => $item]);
    }

    // Editar
    public function edit(Notification $notification): View
    {
        $this->authorize('update', $notification);
        $item = $this->service->find($notification->id);
        return view('notifications.edit', ['notification' => $item]);
    }

    // Actualizar (por ejemplo marcar read_at)
    public function update(NotificationRequest $request, Notification $notification): RedirectResponse
    {
        $this->authorize('update', $notification);

        $data = $request->validated();

        $this->service->update($notification->id, $data);

        return Redirect::route('notifications.show', $notification)->with('status', 'Notificación actualizada');
    }

    // Eliminar
    public function destroy(Notification $notification): RedirectResponse
    {
        $this->authorize('delete', $notification);
        $this->service->delete($notification->id);
        return Redirect::route('notifications.index')->with('status', 'Notificación eliminada');
    }

    // API: listado JSON (por usuario actual o admin)
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', Notification::class);

        $filters = $request->all();
        if (!isset($filters['user_id'])) {
            $filters['user_id'] = $request->user()->id;
        }

        $items = $this->service->list($filters, (int)$request->input('per_page', 25));
        return response()->json($items);
    }

    // API: detalle JSON
    public function apiShow(Notification $notification)
    {
        $this->authorize('view', $notification);
        $item = $this->service->find($notification->id);
        return response()->json($item);
    }

    // API helper: marcar todas leídas para usuario actual
    public function apiMarkAllRead(Request $request)
    {
        $this->authorize('update', Notification::class);
        $count = $this->service->markAllReadForUser($request->user()->id);
        return response()->json(['updated' => $count]);
    }
}