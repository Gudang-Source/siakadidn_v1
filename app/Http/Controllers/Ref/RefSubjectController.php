<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Model\Ref\RefSubject;

use Illuminate\Http\Request;

class RefSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = RefSubject::all();

        return view('ref.subject.index', compact('subjects'));
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
         // validasi     
         $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
        ]);
        RefSubject::create($request->only(['name', 'description']));
        
        $request->session()->flash('success', 'Tambah Materi Sukses');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RefSubject $subject)
    {
        return view('ref.subject.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefSubject $subject)
    {
        // validasi
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
        ]);
        $subject->update($validateData);

        return redirect()->route('ref.subject.index')->with('success', 'Ubah Mata Pelajaran berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RefSubject $subject)
    {
        $subject->delete();
        return redirect()->route('ref.subject.index')->with('success', 'Hapus Mata Pelajaran berhasil!');
    }

    public function showJson($id)
    {
        $subject = RefSubject::findOrFail($id);
        return $subject->toJson();
    }
}
