<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCenterRequest;
use App\Http\Requests\UpdateCenterRequest;
use App\Models\Center;
use App\Services\CenterService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request as BaseRequest;

class CenterController extends Controller
{
    protected CenterService $service;

    public function __construct(CenterService $service)
    {
        $this->service = $service;
        // Ajustar middleware/permissions según tu política (Spatie/Policies)
        $this->middleware('auth')->except(['index', 'show', 'apiIndex', 'apiShow']);
    }

    // Listado HTML paginado
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Center::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return view('centers.index', ['centers' => $items]);
    }

    // Formulario de creación
    public function create(): View
    {
        $this->authorize('create', Center::class);
        return view('centers.create');
    }

    // Guardar nuevo centro (manejo seguro de archivo logo)
    public function store(StoreCenterRequest $request): RedirectResponse
    {
        $this->authorize('create', Center::class);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        if (! $httpRequest->hasFile('logo')) {
            $logo = null;
        } else {
            /** @var UploadedFile|null $maybeLogo */
            $maybeLogo = $httpRequest->file('logo');
            $logo = ($maybeLogo instanceof UploadedFile && $maybeLogo->isValid()) ? $maybeLogo : null;
        }

        $center = $this->service->create($data, $logo);

        return Redirect::route('centers.show', $center)->with('status', 'Centro creado');
    }

    // Mostrar detalle HTML
    public function show(Center $center): View
    {
        $this->authorize('view', $center);
        $center = $this->service->find($center->id);
        return view('centers.show', ['center' => $center]);
    }

    // Formulario edición
    public function edit(Center $center): View
    {
        $this->authorize('update', $center);
        $center = $this->service->find($center->id);
        return view('centers.edit', ['center' => $center]);
    }

    // Actualizar (manejo seguro de logo)
    public function update(UpdateCenterRequest $request, Center $center): RedirectResponse
    {
        $this->authorize('update', $center);
        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request;

        if (! $httpRequest->hasFile('logo')) {
            $logo = null;
        } else {
            /** @var UploadedFile|null $maybeLogo */
            $maybeLogo = $httpRequest->file('logo');
            $logo = ($maybeLogo instanceof UploadedFile && $maybeLogo->isValid()) ? $maybeLogo : null;
        }

        $this->service->update($center->id, $data, $logo);

        return Redirect::route('centers.show', $center)->with('status', 'Centro actualizado');
    }

    // Eliminar
    public function destroy(Center $center): RedirectResponse
    {
        $this->authorize('delete', $center);
        $this->service->delete($center->id);
        return Redirect::route('centers.index')->with('status', 'Centro eliminado');
    }

    // API: listado JSON
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', Center::class);
        $items = $this->service->list($request->all(), (int)$request->input('per_page', 25));
        return response()->json($items);
    }

    // API: detalle JSON
    public function apiShow(Center $center)
    {
        $this->authorize('view', $center);
        $center = $this->service->find($center->id);
        return response()->json($center);
    }
}