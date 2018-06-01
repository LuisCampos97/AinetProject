<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests\DocumentRequest;
use App\Movement;
use Illuminate\Support\Facades\Storage;

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

        $file = $request->file('document_file');

        if ($movement->document_id == null) {
            $document = Document::create([
                'original_name' => $file->getClientOriginalName(),
                'description' => $request->input('document_description'),
                //'type' => $file->getClientOriginalExtension()
            ]);

            $movement->document_id = $document->id;
            $movement->save();

            if ($file->isValid()) {
                $name = $document->id.'.'.$file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->id, $file, $name);
            }

        } else {
            $document = Document::findOrFail($movement->document_id);

            $document->original_name = $file->getClientOriginalName();
            $document->description = $request->input('document_description');
            //$document->type = $file->getClientOriginalExtension();
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

        Storage::disk('local')->delete('documents/'.$movement->id.'/'.$document->id.'.'.$document->type);
        
        $document->delete();

        return redirect()->action('AccountController@showMovementsForAccount', $movement->account_id);
    }

    public function viewDocument($id)
    {
        $document = Document::findOrFail($id);
        $movement = Movement::where('document_id', '=', $document->id)->first();

        $path = Storage::disk('local')->get('documents/'.$movement->id.'/'.$document->id.'.'.$document->type);

        return Storage::url($path);
    }
}
