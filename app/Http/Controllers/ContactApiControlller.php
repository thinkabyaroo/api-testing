<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactApiControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $contacts=Contact::get()->each(function ($contact){
            if ($contact->photo === null){
                $contact->photo =asset('user-default.jpg');

            }else{
                $contact->photo=asset('storage/photo/'.$contact->photo);
            }
            $contact->makeHidden(['created_at','updated_at']);
            $contact->date=$contact->created_at->format("d M Y");
            $contact->time=$contact->created_at->format("h:i s");

        });
        return response()->json([
            "message"=>"success",
            'data'=>$contacts
        ],200);
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
            return response()->json([
                'message'=>'not found'
            ],404);
        }
        return response()->json([
            'message'=>'success',
            'data'=>$contact,
        ],200);

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
        $request->validate([
            'name'=>'nullable|min:3',
            'phone'=>'nullable|min:8|max:20',
            'photo'=>'nullable|file|mimes:jpeg,png|max:2000'

        ]);
        $contact=Contact::find($id);
        if (is_null($contact)){
            return response()->json([
                'message'=>'not found'
            ],404);
        }
        if (isset($request->name)){
            $contact->name=$request->name;
        }
        if (isset($request->phone)){
            $contact->phone=$request->phone;
        }
        if ($request->hasFile('photo')){
            Storage::delete("public/photo/".$contact->photo);
            $newName='photo_'.uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photo',$newName);
            $contact->photo=$newName;
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
            return response()->json([
                'message'=>'not found'
            ],404);
        }
        $contact->delete();
        return response()->json([
            'message'=>'deleted',
        ],204);
    }
}
