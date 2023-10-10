<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Contact::latest()->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'hobi' => 'required',
            'foto' => 'image|mimes:png,jpg,jpeg|max:512'
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Validasi Gagal!'
            ]);

        } else {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $file->store('public/foto');

                $data = Contact::create([
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'hobi' => $request->hobi,
                    'foto' => $file->hashName()
                ]);
            } else {
                $data = Contact::create([
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'hobi' => $request->hobi
                ]);
            }

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'data' => $data
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Contact::find($id);

        if($data){
            return response()->json([
                'status' => Response::HTTP_FOUND,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data TIdak Ditemukkan!'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Contact::find($id);

        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'hobi' => 'required',
            'foto' => 'image|mimes:png,jpg|max:512'
        ]);

        if($data){

            if($validation->fails()){
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Validasi Gagal!'
                ]);

            } else {
                if($request->hasFile('foto')){
    
                    Storage::delete('public/foto/' . $data->foto);
    
                    $file = $request->file('foto');
                    $file->store('public/foto');
    
                    $data->update([
                        'nama' => $request->nama,
                        'username' => $request->username,
                        'hobi' => $request->hobi,
                        'foto' => $file->hashName()
                    ]);
    
                } else {
                    $data->update([
                        'nama' => $request->nama,
                        'username' => $request->username,
                        'hobi' => $request->hobi,
                    ]);
    
                }
            }
            
            return response()->json([
                'status' => Response::HTTP_CREATED,
                'data' => $data
            ]);

        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data TIdak Ditemukkan!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Contact::find($id);

        if($data){
            Storage::delete('public/foto/' . $data->foto);

            $data->delete();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Data Dihapus!',
            ]);

        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Data TIdak Ditemukkan!'
            ]);
        }
    }
}
