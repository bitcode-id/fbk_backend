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
use App\Exceptions\Handler;
use Exception;
use App\Exports\BiodataPengajuanExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\DB;

class BiodataPengajuanController extends Controller
{
    public function index ()
    {
        $data = DB::table('biodata_pengajuan')
                ->join('users', 'biodata_pengajuan.user_id', '=', 'users.id')
                ->select('biodata_pengajuan.id', 'users.name', 'biodata_pengajuan.kategori_pengusul', 'biodata_pengajuan.nama_pengusul', 'biodata_pengajuan.telp', 'biodata_pengajuan.email', 'biodata_pengajuan.alamat', 'biodata_pengajuan.kota', 'biodata_pengajuan.provinsi', 'biodata_pengajuan.kategori_kegiatan', 'biodata_pengajuan.judul_kegiatan', 'biodata_pengajuan.deskripsi_kegiatan', 'biodata_pengajuan.durasi_pelaksanaan', 'biodata_pengajuan.hasil_kegiatan', 'biodata_pengajuan.penerima_manfaat', 'biodata_pengajuan.biaya_diajukan', 'biodata_pengajuan.pertanyaan', 'biodata_pengajuan.rab', 'biodata_pengajuan.status')
                ->orderBy('biodata_pengajuan.updated_at', 'desc')
                ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function show ()
    {
        $data = DB::table('biodata_pengajuan')
                ->join('users', 'biodata_pengajuan.user_id', '=', 'users.id')
                ->where('user_id', auth()->user()->id)
                ->select('biodata_pengajuan.id', 'users.name', 'biodata_pengajuan.kategori_pengusul', 'biodata_pengajuan.nama_pengusul', 'biodata_pengajuan.telp', 'biodata_pengajuan.email', 'biodata_pengajuan.alamat', 'biodata_pengajuan.kota', 'biodata_pengajuan.provinsi', 'biodata_pengajuan.kategori_kegiatan', 'biodata_pengajuan.judul_kegiatan', 'biodata_pengajuan.deskripsi_kegiatan', 'biodata_pengajuan.durasi_pelaksanaan', 'biodata_pengajuan.hasil_kegiatan', 'biodata_pengajuan.penerima_manfaat', 'biodata_pengajuan.biaya_diajukan', 'biodata_pengajuan.pertanyaan', 'biodata_pengajuan.rab', 'biodata_pengajuan.status')
                ->orderBy('biodata_pengajuan.updated_at', 'desc')
                ->first();
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

        $deskripsi_kegiatan = $request->deskripsi_kegiatan;
        $hari = $request->hari ? $request->hari : 0;
        $minggu = $request->minggu ? $request->minggu : 0;
        $bulan = $request->bulan ? $request->bulan: 0;
        $durasi_pelaksanaan = $hari.' hari, '.$minggu.' minggu, '.$bulan.' bulan';
    	$hasil_kegiatan_lainnya = $request->hasil_kegiatan_lainnya ? $request->hasil_kegiatan_lainnya : null;
    	$hasil_kegiatan_array = $request->hasil_kegiatan;
        $hasil_kegiatan = implode(",", $hasil_kegiatan_array).','.$hasil_kegiatan_lainnya;

        $status = $request->status;
    	if ($status == 'draft') {
    		$pesan = 'Sukses menyimpan draft pengajuan';
    	} else {
    		$pesan = 'Sukses menyimpan pengajuan';
    	}
        $biodata_pengajuan = BiodataPengajuan::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'user_id' => $user_id,
                'kategori_pengusul' => $request->kategori_pengusul,
                'nama_pengusul' => $request->nama_pengusul,
                'telp' => $request->telp,
                'email' => $request->email,
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

    	if ($biodata_pengajuan) {
    		return response()->json($pesan);
    	} else {
    		return response()->json('Gagal menyimpan data pengajuan');
    	}
    }

    public function store_video (Request $request)
    {
        $id = $request->id;
        $video = $request->video;
        $biodata_pengajuan = BiodataPengajuan::find($id);
        $biodata_pengajuan->video = $video;
        if ($biodata_pengajuan->save()) {
            return response()->json('Sukses update video');
        } else {
            return response()->json('Gagal menyimpan data video');
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

    public function bulk_update(Request $request)
    {
        $biodata_pengajuan_lulus = explode(',', $request->id_proposal);
        $biodata_pengajuan = DB::table('biodata_pengajuan')->select('id')->get();
        // $hasil_lulus = [];
        // $hasil_tidak_lulus = [];
        $status = $request->status;
        // $update_lulus = BiodataPengajuan::whereIn('id', $biodata_pengajuan_lulus)->update(['status' => 'lulus']);
        // $update_tidak_lulus = BiodataPengajuan::whereNotIn('id', $biodata_pengajuan_lulus)->update(['status' => 'tidak lulus']);

        foreach ($biodata_pengajuan_lulus as $lulus) {
            $update_lulus = BiodataPengajuan::find(intval($lulus));
            $update_lulus->status = $status;
            $update_lulus->save();
        }

        return response()->json([
            'data' => 'Berhasil update status '.$status
        ]);
    }

    public function upload(Request $request)
    {
        $file = $request->file;
        $file_ori_name = $file->getClientOriginalName();
        $file_path = realpath($file);
        $file_name = explode('.',$file_ori_name)[0];
        $file_extension = $file->getClientOriginalExtension();
        $file_slug = Str::slug($file_name, '_').".".$file_extension;

        if ($file_extension == 'xls' || $file_extension == 'xlsx') {
            $name = auth()->user()->name;
            $name_slug = Str::slug($name, '_');
            $role = auth()->user()->role;
            $path = '';
            if($role == "superadmin"){
              $path =  'storage/files/superadmin';
            }else{
              $path =  'storage/files/superadmin/'.$name_slug;
            }

            if ($file->move($path, $file_slug)) {
                $url = $path.'/'.$file_slug;
                return response()->json($url);
            }
        } else {
            return response()->json('Jenis file tidak diizinkan, pastikan file anda xls, xlsx, doc, docx, pdf, jpg, jpeg, png.', 302);
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

    public function export ()
    {
        return Excel::download(new BiodataPengajuanExport, 'data_fbk.xlsx');
    }

    public function pdf ()
    {
        $data_seleksi = BiodataPengajuan::limit(5)->get();
        foreach ($data_seleksi as $data) {
            $pdf = PDF::loadHTML('
                <h1 style="text:center">Formulir FBK</h1>
                <h2>'.$data->kategori_pengusul.'</h2>'.
                '<br><p>'.$data->id.'</p>'
            );
            // return $pdf->save(public_path().'/storage/files/seleksi/'.$data->id);
            // echo "pdf ".$data->id."<br>";
            $pdf->save(public_path().'/storage/files/seleksi'.$data->id);
            // return $pdf->stream();
        }
    }
}
