<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests\DocumentRequest;
use App\Movement;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function uploadDocumentView($id)
    {
        $pagetitle = "Upload Document";
        $document = new Document;

        $movement = Movement::findOrFail($id);

        if ($movement->document_id != null) {
            $document = Document::findOrFail($movement->document_id);
        }

        return view('documents.upload', compact('pagetitle', 'movement', 'document'));
    }

    public function uploadDocument(DocumentRequest $request, $id)
    {
        $movement = Movement::findOrFail($id);

        $request->validated();

        if ($movement->document_id == null) {
            $document = Document::create([
                'original_name' => $request->file('document_file')->getClientOriginalName(),
                'description' => $request->input('document_description'),
            ]);

            $movement->document_id = $document->id;
            $movement->save();

        } else {
            $document = Document::findOrFail($movement->document_id);

            $document->original_name = $request->file('document_file')->getClientOriginalName();
            $document->description = $request->input('document_description');
            $document->save();
        }

        return redirect()->action('AccountController@showMovementsForAccount', $movement->account_id);
    }

    public function removeDocument($id)
    {
        $document = Document::findOrFail($id);
        $movement = Movement::where('document_id', '=', $document->id)->first();
        $movement->document_id = null;
        $movement->save();
        $document->delete();

        return redirect()->action('AccountController@showMovementsForAccount', $movement->account_id);
    }

    public function viewDocument($id) {
        
    }
}
