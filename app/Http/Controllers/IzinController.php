<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        //get posts
        $student = Izin::latest()->paginate(5);

        //render view with posts
        return view('siswa.index', compact('student'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'nama'     => 'required',
            'kelas'   => 'required',
            'absen'   => 'required',
            'alasan'   => 'required',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('siswa', $image->hashName());

        //create stude$student
        Izin::create([
            'nama'     => $request->nama,
            'kelas'   => $request->kelas,
            'absen'   => $request->absen,
            'alasan'   => $request->alasan,
            'image'     => $image->hashName(),
        ]);

        //redirect to index
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit(Izin $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Izin $siswa)
    {
        //validate form
        $this->validate($request, [
            'nama'     => 'required',
            'kelas'   => 'required',
            'absen'   => 'required',
            'alasan'   => 'required',
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('siswa', $image->hashName());

            //delete old image
            Storage::delete('siswa'.$siswa->image);

            //update siswa with new image
            $siswa->update([
                'nama'     => $request->nama,
                'kelas'   => $request->kelas,
                'absen'   => $request->absen,
                'alasan'   => $request->alasan,
                'image'     => $image->hashName(),
            ]);

        } else {

            //update post without image
            $siswa->update([
                'nama'     => $request->nama,
                'kelas'   => $request->kelas,
                'absen'   => $request->absen,
                'alasan'   => $request->alasan,
                'image'   => $image->hasName(),
            ]);
        }

        //redirect to index
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    public function destroy(Izin $siswa)
    {
        //delete image
        Storage::delete('siswa'. $siswa->image);

        //delete post
        $siswa->delete();

        //redirect to index
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

}
