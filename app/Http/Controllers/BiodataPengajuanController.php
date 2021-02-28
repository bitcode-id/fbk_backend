<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BiodataPengajuanValidation;
use App\Models\BiodataPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use File;
use Image;

class BiodataPengajuanController extends Controller
{
    public function user ()
    {
    	$data = BiodataPengajuan::with('user')->where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->first();
    	return response()->json([
    		'data' => $data
    	]);
    }

    public function admin ()
    {
    	$data = BiodataPengajuan::with('user')->orderBy('created_at', 'desc')->get();
    	return response()->json([
    		'data' => $data
    	]);
    }

    public function store (BiodataPengajuanValidation $request)
    {
        $validated = $request->validated();

    	$user_id = null;
    	if (auth()->user()->role == 'admin') {
    		$user_id = $request->user_id;
    	} else {
    		$user_id = auth()->user()->id;
    	}

    	// $durasi_pelaksanaan_array = $request->durasi_pelaksanaan;
    	// $durasi_pelaksanaan = implode(",", $durasi_pelaksanaan_array);
        $durasi_pelaksanaan = $request->durasi_pelaksanaan;

    	$hasil_kegiatan_lainnya = $request->hasil_kegiatan_lainnya ? $request->hasil_kegiatan_lainnya : null;
    	$hasil_kegiatan_array = $request->hasil_kegiatan;
        $hasil_kegiatan = implode(",", $hasil_kegiatan_array).','.$hasil_kegiatan_lainnya;

        $status = $request->status;
    	if ($status == 'draft') {
    		$pesan = 'Sukses menyimpan draft pengajuan';
    	} else {
    		$pesan = 'Sukses menyimpan pengajuan';
    	}
        $email = $request->email;
        $cek_email = BiodataPengajuan::where('user_id', auth()->user()->id)->first() ? BiodataPengajuan::where('user_id', auth()->user()->id)->first()->email : null;
        if ($email == $cek_email) { //tidak perlu update email
            $biodata_pengajuan = BiodataPengajuan::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'user_id' => $user_id,
                    'kategori_pengusul' => $request->kategori_pengusul,
                    'nama_pengusul' => $request->nama_pengusul,
                    'telp' => $request->telp,
                    // 'email' => $request->email,
                    'alamat' => $request->alamat,
                    'kota' => $request->kota,
                    'provinsi' => $request->provinsi,
                    'kategori_kegiatan' => $request->kategori_kegiatan,
                    'judul_kegiatan' => $request->judul_kegiatan,
                    'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                    'durasi_pelaksanaan' => $durasi_pelaksanaan,
                    'hasil_kegiatan' => $hasil_kegiatan,
                    'penerima_manfaat' => $request->penerima_manfaat,
                    'biaya_diajukan' => $request->biaya_diajukan,
                    'pertanyaan' => $request->pertanyaan,
                    'rab' => $request->rab,
                    'status' => $status
                ]
            );
        } else { // update email
            $biodata_pengajuan = BiodataPengajuan::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'user_id' => $user_id,
                    'kategori_pengusul' => $request->kategori_pengusul,
                    'nama_pengusul' => $request->nama_pengusul,
                    'telp' => $request->telp,
                    'email' => $email,
                    'alamat' => $request->alamat,
                    'kota' => $request->kota,
                    'provinsi' => $request->provinsi,
                    'kategori_kegiatan' => $request->kategori_kegiatan,
                    'judul_kegiatan' => $request->judul_kegiatan,
                    'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                    'durasi_pelaksanaan' => $durasi_pelaksanaan,
                    'hasil_kegiatan' => $hasil_kegiatan,
                    'penerima_manfaat' => $request->penerima_manfaat,
                    'biaya_diajukan' => $request->biaya_diajukan,
                    'pertanyaan' => $request->pertanyaan,
                    'rab' => $request->rab,
                    'status' => $status
                ]
            );
        }

    	if ($biodata_pengajuan) {
    		return response()->json($pesan);
    	} else {
    		return response()->json('Gagal menyimpan data pengajuan');
    	}
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $biodata_pengajuan = BiodataPengajuan::find($id);
        $biodata_pengajuan->status = $request->status;
        if ($biodata_pengajuan->save()) {
            return response()->json('Sukses perbarui data pengajuan');
        } else {
            return response()->json('Gagal perbarui data pengajuan');
        }
    }

    public function upload(Request $request)
    {
        $file = $request->file;
        $file_ori_name = $file->getClientOriginalName();
        $file_path = realpath($file);
        $file_name = explode('.',$file_ori_name)[0];
        $file_extension = $file->getClientOriginalExtension();
        $file_slug = Str::slug($file_name, '_').".".$file_extension;

        $name = auth()->user()->name;
        $name_slug = Str::slug($name, '_');
        $role = auth()->user()->role;
        $path = '';
        if($role == "superadmin"){
          $path =  'storage/files/superadmin';
        }else{
          $path =  'storage/files/superadmin/'.$name_slug;
        }

        // $upload_dir = public_path('storage/files/upload');

        if ($file->move($path, $file_slug)) {
            $url = $path.'/'.$file_slug;
            return response()->json($url);
        }
    }

    public function destroy ($id)
    {
    	$biodata_pengajuan = BiodataPengajuan::find($id);
    	if ($biodata_pengajuan->delete()) {
    		return response()->json('Sukses menghapus data pengajuan');
    	} else {
    		return response()->json('Gagal menghapus data pengajuan');
    	}
    }
}
