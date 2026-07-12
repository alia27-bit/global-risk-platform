<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Services\Api\NegaraService;

class NegaraController extends Controller
{
    protected $service;

    public function __construct(NegaraService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $countries = Negara::paginate(20);

        return view('negara.index', compact('countries'));
    }

    public function sync()
    {
        $this->service->sync();

        return redirect()
            ->route('negara.index')
            ->with('success', 'Data negara berhasil disinkronkan.');
    }

    public function show($id)
    {
        $country = Negara::findOrFail($id);

        return view('negara.show', compact('country'));
    }
}