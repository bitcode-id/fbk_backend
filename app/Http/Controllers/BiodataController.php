<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\BiodataRequest;
use App\Models\Biodata;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;
use Illuminate\Support\Facades\Hash;

class BiodataController extends Controller
{
    // public function biodata()
    // {
    //   $data = Biodata::with('user')->get();
    //   return response()->json([
    //     'data' => $data
    //   ]);
    // }

    public function user_show($id)
    {
      $this->cek_admin();
      $data = User::where('id', intval($id))->first();

      return Inertia::render('Form/User', [
        'data' => $data
      ]);
    }

    public function create_akun()
    {
      $this->cek_admin();
      return Inertia::render('Form/User', [
        'data' => null
      ]);
    }

    public function user_update(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|max:50',
        'email' => 'required|email|max:50',
        'password' => 'required||confirmed|min:6',
        'role' => 'required|max:50',
        'kegiatan' => 'required|max:50',
      ]);

      $user = User::updateOrCreate(
        [
          'id' => $request->id
        ],
        [
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'role' => $request->role,
          'kegiatan' => $request->kegiatan
        ]);

      if ($user) {
        return redirect()->route('admin_user')->with('status', 'Sukses menyimpan akun');
      }
    }

    public function index()
    {
      $this->cek_admin();
      $data = Biodata::all();
      return Inertia::render('Admin/Biodata', [
        'data' => $data
      ]);
    }

    public function detail()
    {
      // superadmin
    }

    public function create()
    {
      $data = Biodata::where('user_id', Auth::user()->id)->latest('id')->first();
      return Inertia::render('Form/Biodata', [
          'data' => $data
      ]);
    }

    public function post(BiodataRequest $request)
    {
        $validated = $request->validated();

        $biodata = Biodata::updateOrCreate(
          [
            'user_id' => Auth::user()->id
          ],
          [
            'kategori' => $request->kategori,
            'nama_pengusul' => $request->nama_pengusul,
            'nama_penanggungjawab' => $request->nama_penanggungjawab,
            'ktp' => $request->ktp,
            'kemenkumham' => $request->kemenkumham,
            'akta' => $request->akta,
            'npwp' => $request->npwp,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'telp' => $request->telp,
            'email' => $request->email
          ]);

        if ($biodata) {
          return response()->json('Sukses menyimpan biodata akun FBK');
        }
    }

    public function biodata()
    {
      $data = Biodata::where('user_id', auth()->user()->id)->first();

      return response()->json([
          'data' => $data
      ]);
    }

    public function delete(Request $request)
    {
      $this->cek_admin();

      $id = $request->deleteId;
      if ($id) {
        $biodata = Biodata::find($id);
        $biodata->delete();
        return redirect()->route('admin_biodata')->with('status', 'Sukses hapus data');
      } else {
        return abort(404);
      }
    }
    
    public function user_delete(Request $request)
    {
      $this->cek_admin();

      $id = $request->deleteId;
      if ($id <= 2) {
        return abort(401);
      } else if ($id > 2) {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin_user')->with('status', 'Sukses hapus data');
      } else {
        return abort(404);
      }

    }
}
