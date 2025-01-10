<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function index()
    {
        // load user's notes
        $id = session('user.id');
        $notes = User::find($id)->notes()->get()->toArray();

        // show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        // show new note view
        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
        // validate request
        $request->validate(
            // rules
            [
                'text_title' => 'required | min:3 | max:200',
                'text_note' => 'required | min:3 | max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'Title is required',
                'text_title.min' => 'The title must have at least :min characters.',
                'text_title.max' => 'The title must have a maximum of :max characters.',

                'text_note.required' => 'Annotation is required',
                'text_note.min' => 'The annotation must have at least :min characters.',
                'text_note.max' => 'The annotation must have a maximum of :max characters.',
            ]
        );

        // get user id
        $id = session('user.id');

        // create new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function editNote($id) 
    {
        $id = Operations::decryptId($id);
        

        // load note 
        $note = Note::find($id);

        // show edit note view
        return view('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(request $request)
    {
        // validate request
        $request->validate(
            // rules
            [
                'text_title' => 'required | min:3 | max:200',
                'text_note' => 'required | min:3 | max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'Title is required',
                'text_title.min' => 'The title must have at least :min characters.',
                'text_title.max' => 'The title must have a maximum of :max characters.',

                'text_note.required' => 'Annotation is required',
                'text_note.min' => 'The annotation must have at least :min characters.',
                'text_note.max' => 'The annotation must have a maximum of :max characters.',
            ]
        );

        // chack if note_id exists
        if($request->note_id == null) {
            return redirect()->to('home');
        }

        // decrypt note_id
        $id = Operations::decryptId($request->note_id);

        // load note
        $note = Note::find($id);

        // update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // redirect to home
        return redirect()->route('home');
    }

    public function deleteNote($id) 
    {
        $id = Operations::decryptId($id);
        echo "I'm deleting note with id = $id";
    }

}
