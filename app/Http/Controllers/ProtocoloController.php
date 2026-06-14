<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ProtocoloController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Ventanilla/Protocolo');
    }
}
