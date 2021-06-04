<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $notes = Note::all();
    
        return response()->json([
            'note' => $notes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => 'invalid field',
            ], 422);
        }

        Note::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'status' => true,
            'messages' => 'create note success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $note = Note::findOrFail($id);
        return response()->json([
            'status' => true,
            'messages' => 'show note success',
            'data' => $note
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $note = Note::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => 'invalid field',
            ], 422);
        }

        $note->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'status' => true,
            'messages' => 'edit note success',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $note = Note::findOrFail($id);
        $note->delete();
        return response()->json([
            'status' => true,
            'messages' => 'delete note success',
        ], 200);
    }
}
