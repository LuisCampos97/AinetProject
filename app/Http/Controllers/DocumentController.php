<?php

namespace App\Http\Controllers;

use App\Document;
use App\Http\Requests\DocumentRequest;
use App\Movement;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

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

        //If the movement does not have a document.
        if ($movement->document_id == null) {
            $document = Document::create([
                'original_name' => $file->getClientOriginalName(),
                'description' => $request->input('document_description'),
                'type' => $file->getClientOriginalExtension(),
            ]);

            $movement->document_id = $document->id;
            $movement->save();

            if ($file->isValid()) {
                $name = $movement->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
            }

        } else {
            //If the movement has a document and the user wants to change it.
            $document = Document::findOrFail($movement->document_id);

            $unique_id = $movement->id . '.' . $document->type;
            
            Storage::disk('local')->delete('documents/'.$movement->account_id.'/'.$unique_id);

            $document->original_name = $file->getClientOriginalName();
            $document->description = $request->input('document_description');
            $document->type = $file->getClientOriginalExtension();
            $document->save();

            if ($file->isValid()) {
                $name = $movement->id . '.' . $file->getClientOriginalExtension();
                Storage::disk('local')->putFileAs('documents/' . $movement->account_id, $file, $name);
            }
        }

        return redirect()->action('AccountController@showMovementsForAccount', $movement->account_id);
    }

    public function removeDocument($id)
    {
        $document = Document::findOrFail($id);
        $movement = Movement::where('document_id', '=', $document->id)->first();
        $movement->document_id = null;
        $movement->save();

        $unique_id = $movement->id . '.' . $document->type;

        Storage::disk('local')->delete('documents/'.$movement->account_id.'/'.$unique_id);

        $document->delete();

        return redirect()->action('AccountController@showMovementsForAccount', $movement->account_id);
    }

    public function viewDocument($id)
    {
        $document = Document::findOrFail($id);
        $movement = Movement::where('document_id', '=', $document->id)->first();

        $unique_id = $movement->id . '.' . $document->type;

        $file = new File(storage_path('app/' . 'documents/' . $movement->account_id . '/' . $unique_id));
        dd($file);

        return response()->download(
            storage_path('app/' . 'documents/' . $movement->account_id . '/' . $unique_id),
            $document->original_name);
    }
}
