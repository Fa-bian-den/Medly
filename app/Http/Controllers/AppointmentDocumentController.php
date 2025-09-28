<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentDocumentRequest;
use App\Models\AppointmentDocument;
use App\Services\AppointmentDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request as BaseRequest; // para la anotación al IDE

class AppointmentDocumentController extends Controller
{
    protected AppointmentDocumentService $service;

    public function __construct(AppointmentDocumentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', AppointmentDocument::class);

        $items = $this->service->list($request->all(), (int) $request->input('per_page', 25));
        return view('appointment_documents.index', ['documents' => $items]);
    }

    public function create(): View
    {
        $this->authorize('create', AppointmentDocument::class);
        return view('appointment_documents.create');
    }  

        public function store(AppointmentDocumentRequest $request): RedirectResponse
    {
        $this->authorize('create', AppointmentDocument::class);

        $data = $request->validated();

        /** @var BaseRequest $httpRequest */
        $httpRequest = $request; // anotar al IDE que es Illuminate\Http\Request

        // Comprobar explícita y tempranamente que se envió un archivo
        if (! $httpRequest->hasFile('document')) {
            return Redirect::back()->withErrors(['document' => 'Archivo inválido o no enviado'])->withInput();
        }

        /** @var UploadedFile|null $maybeFile */
        $maybeFile = $httpRequest->file('document');

        if (! $maybeFile instanceof UploadedFile) {
            return Redirect::back()->withErrors(['document' => 'Archivo inválido'])->withInput();
        }

        $file = $maybeFile;

        $data['uploaded_by'] = $data['uploaded_by'] ?? Auth::id();

        $doc = $this->service->create($data, $file);

        return Redirect::route('appointment_documents.show', $doc)->with('status', 'Documento subido');
    }



    public function show(AppointmentDocument $appointmentDocument): View
    {
        $this->authorize('view', $appointmentDocument);
        return view('appointment_documents.show', ['document' => $appointmentDocument]);
    }

    public function destroy(AppointmentDocument $appointmentDocument): RedirectResponse
    {
        $this->authorize('delete', $appointmentDocument);
        $this->service->delete($appointmentDocument->id);
        return Redirect::route('appointment_documents.index')->with('status', 'Documento eliminado');
    }

    // Endpoint JSON
    public function apiIndex(Request $request)
    {
        $this->authorize('viewAny', AppointmentDocument::class);
        $items = $this->service->list($request->all(), (int) $request->input('per_page', 25));
        return response()->json($items);
    }
}