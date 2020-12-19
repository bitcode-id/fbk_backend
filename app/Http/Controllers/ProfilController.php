<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use Inertia\Inertia;
use DB;
use Excel;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use App\User;
use Illuminate\Http\UploadedFile;

class ProfilController extends Controller
{
    public function index()
    {
        $data = Profil::all();

        return Inertia::render('Admin/Profil', [
            'data' => $data
        ]);
    }

    public function show ($id)
    {
        $data = Profil::where('id', $id)->first();
        return Inertia::render('Show/Profil', [
          'data' => $data,
      ]);
    }

    public function create($id = null)
    {
      $data = $id ? Profil::where('id', $id)->first() : null;

      return Inertia::render('Form/Profil', [
        'data' => $data
    ]);
    }

    public function store(Request $request)
    {
        // $validated = $request->validated();

        $profil = Profil::updateOrCreate(
          [
            'id' => $request->id
          ],
          [
            'user_id' => auth()->user()->id,
            'kategori' => $request->kategori,
            'foto' => $request->foto,
            'nama_project' => $request->nama_project,
            'waktu' => $request->waktu,
            'nominal_diajukan' => $request->nominal_diajukan,
            'nominal_disetujui' => $request->nominal_disetujui,
            'deskripsi' => $request->deskripsi,
            'kata_kunci' => $request->kata_kunci,
            'foto_penerima' => $request->foto_penerima,
            'nama_penerima' => $request->nama_penerima,
            'profil_penerima' => $request->profil_penerima,
            'kontak' => $request->kontak,
            'media_sosial' => $request->media_sosial,
          ]);

        if ($profil) {
          return redirect()->route('admin_profil')->with('status', 'Sukses menyimpan data profil');
        } else {
          // return redirect()->route('dashboard')->with('status', 'Gagal menyimpan akun akun FBK');
          // Session::flash('status', 'Gagal menyimpan data'); 
          return Session::get('status');
        }
    }

    public function importExcel (Request $request)
    {
      if ($request->file('importFile')->isValid()) {
        // dd($request->file('importFile'));
        $collection = (new FastExcel)->import($request->file('importFile'));

        $profil = (new FastExcel)->import($request->file('importFile'), function ($row) {
          return Profil::create([
            'user_id' => 1,
            'kategori' => $row['kategori'],
            'foto' => $row['foto'],
            'nama_project' => $row['nama_project'],
            'waktu' => $row['waktu'],
            'nominal_diajukan' => $row['nominal_diajukan'],
            'nominal_disetujui' => $row['nominal_disetujui'],
            'deskripsi' => $row['deskripsi'],
            'kata_kunci' => $row['kata_kunci'],
            'foto_penerima' => $row['foto_penerima'],
            'nama_penerima' => $row['nama_penerima'],
            'profil_penerima' => $row['profil_penerima'],
            'kontak' => $row['kontak'],
            'media_sosial' => $row['media_sosial'],
          ]);
        });
      }
        // $file = $request->file('file');
        // $file_ori_name = $file->getClientOriginalName();
        // $file_path = realpath($file);  // $file->getPath();
        // $file_name = explode('.',$file_ori_name)[0];
        // $file_extension = $file->getClientOriginalExtension();
        // $file_slug = Str::slug($file_name, '_').".".$file_extension;

        
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $profil = Profil::find($id);
        $profil->delete();
        return redirect()->route('admin_profil')->with('status', 'Sukses hapus data');
    }

    // function import(Request $request)
    // {
    //  $this->validate($request, [
    //   'file'  => 'required|mimes:xls,xlsx'
    //  ]);

    //  $path = $request->file('file')->getRealPath();

    //  $data = Excel::load($path)->get();

    //  if($data->count() > 0)
    //  {
    //   foreach($data->toArray() as $key => $value)
    //   {
    //    foreach($value as $row)
    //    {
    //     $insert_data[] = array(
    //       'user_id' => 1,
    //       'kategori' => $row['kategori'],
    //       'foto' => $row['foto'],
    //       'nama_project' => $row['nama_project'],
    //       'waktu' => $row['waktu'],
    //       'nominal_diajukan' => $row['nominal_diajukan'],
    //       'nominal_disetujui' => $row['nominal_disetujui'],
    //       'deskripsi' => $row['deskripsi'],
    //       'kata_kunci' => $row['kata_kunci'],
    //       'foto_penerima' => $row['foto_penerima'],
    //       'nama_penerima' => $row['nama_penerima'],
    //       'profil_penerima' => $row['profil_penerima'],
    //       'kontak' => $row['kontak'],
    //       'media_sosial' => $row['media_sosial'],
    //     );
    //    }
    //   }

    //   if(!empty($insert_data))
    //   {
    //    DB::table('profil')->insert($insert_data);
    //   }
    //  }
    //  return redirect()->route('admin_profil')->with('status', 'Sukses import data profil');
    // }
}