<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Email Konfirmasi</title>

        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="antialiased">
        <div class="container w-full mx-auto m-4">
            <div class="w-full mx-auto p-4">
                <span class="text-lg md:text-2xl col-span-6 text-center mb-2 md:mb-8 font-bold">
                  Formulir Pengajuan FBK 2021
                </span>
                <div class="grid grid-cols-6 gap-2 w-full divide-x divide-gray-400 mt-0 md:mt-8 p-2">
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Kategori Pengusul
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->kategori_pengusul }}
                  </div>
                  <div class="col-span-2 md:col-span-2 px-2 text-gray-600 font-bold">
                    Nama Pengusul/Lembaga/Komunitas
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->nama_pengusul }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Telp
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->telp }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Email
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->email }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Alamat
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->alamat }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Kota/Kabupaten
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->kota }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Provinsi
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->provinsi }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Kategori Kegiatan
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->kategori_kegiatan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Judul Kegiatan
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->judul_kegiatan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Deskripsi
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {!! $data->deskripsi_kegiatan !!}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Durasi Pelaksanaan
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->durasi_pelaksanaan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Hasil Kegiatan
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->hasil_kegiatan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Peneriman Manfaat
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    <span v-html="$data->penerima_manfaat" />
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Biaya Diajukan
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    Rp {{ $data->biaya_diajukan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Kenapa kami harus memilih proposal Anda?
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->pertanyaan }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    RAB
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-blue-600 font-bold">
                    {{ $data->rab }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    Tautan Video (Jika ada)
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800">
                    {{ $data->video }}
                  </div>
                  <div class="col-span-6 md:col-span-2 px-2 text-gray-600 font-bold">
                    <span class="font-bold text-red-600">Status</span>
                  </div>
                  <div class="col-span-6 md:col-span-4 px-2 text-gray-800 text-xl font-bold">
                    {{ $data->status }}
                  </div>
                </div>
            </div>
        </div>
    </body>
</html>
