<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movement;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadDocumentView($id) {

        $movement = Movement::findOrFail($id);

        $pagetitle = "Upload Document";

        return view('documents.upload', compact('pagetitle', 'movement'));
    }

    public function uploadDocument(Request $request, $id)
    {
        Movement::findOrFail($id);

        /*$movement = $request->validate([
            'document_file' => 'required',
            'document_description' => 'nullable'
        ]);*/

        dd($request);
        
    }
}
