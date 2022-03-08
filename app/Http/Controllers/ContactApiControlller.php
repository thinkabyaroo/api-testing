<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts=Contact::all();
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'phone'=>'required',
            'photo'=>'nullable|file|mimes:jpeg,png|max:2000'

        ]);

//        return $request;
        $contact=new Contact();
        $contact->name=$request->name;
        $contact->phone=$request->phone;
        if ($request->hasFile('photo')){
            $newName='photo_'.uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photo',$newName);
            $contact->photo=$newName;
        }
        $contact->save();
        return response()->json([
            'message'=>'success',
            'data'=>$contact,
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact=Contact::find($id);
        if(is_null($contact)){
            return response()->json(['message'=>'not found'],404);
        }
        return $contact;
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
        $contact=Contact::find($id);
        if (is_null($contact)){
            return response()->json(['message'=>'not found'],404);
        }
        if (isset($request->name)){
            $contact->name=$request->name;
        }
        if (isset($request->phone)){
            $contact->phone=$request->phone;
        }
        $contact->update();
        return response()->json($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact=Contact::find($id);
        if(is_null($contact)){
            return response()->json(['message'=>'not found'],404);
        }
        $contact->delete();
        return response()->json($contact);
    }
}
