<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServicePackage;

class ServiceController extends Controller
{
    /**
     * Menampilkan halaman layanan Staff.
     */
public function index()
{
    $services = Service::with([
        'servicePackages' => function ($query) {
            $query->orderByDesc('is_active');
        }
    ])
    ->orderByDesc('is_active')
    ->get();

    return view('staff.services.index', compact('services'));
}


/**
 * Mengecek apakah layanan sudah pernah digunakan
 * dalam transaksi.
 */
private function serviceHasOrderHistory(Service $service): bool
{
    return $service->servicePackages()
        ->withTrashed()
        ->whereHas('orderItems')
        ->exists();
}

/**
 * Mengecek apakah paket sudah pernah digunakan
 * dalam transaksi.
 */
private function packageHasOrderHistory(ServicePackage $servicePackage): bool
{
    return $servicePackage->orderItems()->exists();
}

/**
 * Memperbarui data paket layanan.
 *
 * Method ini digunakan ketika Staff
 * mengedit nama, harga, atau status paket.
 */
public function updatePackage(Request $request, ServicePackage $servicePackage)
{
    /**
     * Validasi data yang dikirim dari modal.
     */
    $validated = $request->validate([

        // Nama paket wajib diisi.
        'package_name' => 'required|string|max:255',

        // Harga wajib berupa angka dan minimal 0.
        'price' => 'required|numeric|min:0',

        // Status paket hanya boleh aktif atau nonaktif.
        'is_active' => 'required|boolean',

    ]);

    /**
     * Memperbarui data paket yang dipilih.
     */
    $servicePackage->update($validated);

    /**
     * Kembali ke halaman layanan
     * setelah data berhasil diperbarui.
     */
    return redirect()
        ->route('staff.services.index')
        ->with('success', 'Paket layanan berhasil diperbarui.');
}

public function deactivatePackage(ServicePackage $servicePackage)
{
    $servicePackage->update([
        'is_active' => false,
    ]);

    return redirect()
        ->route('staff.services.index')
        ->with(
            'success',
            'Paket layanan berhasil dinonaktifkan.'
        );
}

public function destroyPackage(ServicePackage $servicePackage)
{
    if ($this->packageHasOrderHistory($servicePackage)) {
        $servicePackage->delete();

        return redirect()
            ->route('staff.services.index')
            ->with(
                'success',
                'Paket layanan berhasil dihapus.'
            );
    }

    $servicePackage->forceDelete();

    return redirect()
        ->route('staff.services.index')
        ->with(
            'success',
            'Paket layanan berhasil dihapus.'
        );
}

public function destroy(Service $service)
{
    // Jika layanan sudah pernah digunakan dalam transaksi,
    // gunakan Soft Delete agar histori tetap aman.
    if ($this->serviceHasOrderHistory($service)) {

        $service->delete();

        return redirect()
            ->route('staff.services.index')
            ->with(
                'success',
                'Layanan berhasil dihapus.'
            );
    }

    // Jika belum pernah digunakan dalam transaksi,
    // hapus permanen beserta paket-paketnya.
    DB::transaction(function () use ($service) {

        $service->servicePackages()
            ->withTrashed()
            ->forceDelete();

        $service->forceDelete();
    });

    return redirect()
        ->route('staff.services.index')
        ->with(
            'success',
            'Layanan berhasil dihapus.'
        );
}

public function deactivate(Service $service)
{
    $service->update([
        'is_active' => false,
    ]);

    return redirect()
        ->route('staff.services.index')
        ->with(
            'success',
            'Layanan berhasil dinonaktifkan.'
        );
}

public function reactivate($service)
{
    $service = Service::withTrashed()->findOrFail($service);

    $service->restore();

    $service->update([
        'is_active' => true,
    ]);

    return redirect()
        ->route('staff.services.index')
        ->with(
            'success',
            'Layanan berhasil diaktifkan kembali.'
        );
}
    /**
     * Menampilkan form untuk menambahkan layanan baru.
     */
    public function create()
    {
        /**
         * Method ini hanya bertugas menampilkan halaman form.
         *
         * Belum ada data yang disimpan ke database
         * pada tahap ini.
         */
        return view('staff.services.create');
    }



/**
 * Menyimpan layanan baru beserta paket-paketnya.
 */
public function store(Request $request)
{
    /**
     * Memvalidasi data yang dikirim dari modal.
     *
     * service_name = nama layanan.
     * image       = gambar layanan, tidak wajib.
     * packages    = daftar paket layanan.
     */
    $validated = $request->validate([
        'service_name' => 'required|string|max:100',

        'image' => [
            'nullable',
            'file',
            'mimes:jpg,jpeg,png,svg',
            'max:5120',
        ],

        'packages' => 'required|array|min:1',
        'packages.*.package_name' => 'required|string|max:100',
        'packages.*.price' => 'required|numeric|min:0',
    ]);

    /**
     * Menyimpan file gambar jika Staff mengupload gambar.
     *
     * Jika tidak ada gambar:
     * $imageName akan tetap NULL.
     */
    $imageName = null;

    if ($request->hasFile('image')) {

        $image = $request->file('image');

        $imageName = 'service-' . Str::uuid() . '.' . $image->getClientOriginalExtension();

        $image->move(
            public_path('images'),
            $imageName
        );
    }

    /**
     * Transaction digunakan agar proses penyimpanan
     * layanan dan paket dianggap sebagai satu proses.
     */
    try {

        DB::transaction(function () use ($validated, $imageName) {

            /**
             * Membuat layanan baru.
             */
            $service = Service::create([
                'service_name' => $validated['service_name'],
                'image' => $imageName,
                'is_active' => true,
            ]);

            /**
             * Membuat seluruh paket layanan.
             */
            foreach ($validated['packages'] as $package) {

                $service->servicePackages()->create([
                    'package_name' => $package['package_name'],
                    'price' => $package['price'],
                    'is_active' => true,
                ]);
            }
        });

    } catch (\Throwable $e) {

        /**
         * Jika penyimpanan database gagal setelah
         * gambar berhasil dipindahkan, hapus file
         * agar tidak meninggalkan file yang tidak terpakai.
         */
        if ($imageName) {

            $imagePath = public_path('images/' . $imageName);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        throw $e;
    }

    /**
     * Setelah berhasil disimpan,
     * kembali ke halaman layanan.
     */
    return redirect()
        ->route('staff.services.index')
        ->with('success', 'Layanan dan paket berhasil ditambahkan.');
}

public function update(Request $request, Service $service)
{
    /**
     * Memvalidasi data yang dikirim dari modal edit.
     *
     * service_name = nama layanan.
     * image       = gambar baru, tidak wajib.
     * packages    = daftar paket layanan.
     */
    $validated = $request->validate([
        'service_name' => [
            'required',
            'string',
            'max:100',
        ],

        'image' => [
            'nullable',
            'file',
            'mimes:jpg,jpeg,png,svg',
            'max:5120',
        ],

        'packages' => [
            'nullable',
            'array',
        ],

        'packages.*.id' => [
            'nullable',
            'integer',
        ],

        'packages.*.package_name' => [
            'required',
            'string',
            'max:60',
        ],

        'packages.*.price' => [
            'required',
            'numeric',
            'min:0',
        ],

        'packages.*.is_active' => [
            'nullable',
            'boolean',
        ],
    ]);

    /**
     * Menyimpan nama file gambar lama.
     *
     * Nilai ini diperlukan jika Staff mengupload
     * gambar baru, sehingga gambar lama dapat
     * dihapus setelah proses update berhasil.
     */
    $oldImageName = $service->image;

    /**
     * Secara default tidak ada gambar baru.
     */
    $newImageName = null;

    /**
     * Jika Staff memilih gambar baru,
     * simpan file tersebut ke public/images.
     */
    if ($request->hasFile('image')) {

        $image = $request->file('image');

        $newImageName =
            'service-' .
            Str::uuid() .
            '.' .
            $image->getClientOriginalExtension();

        $image->move(
            public_path('images'),
            $newImageName
        );
    }

    try {

        DB::transaction(function () use (
            $validated,
            $service,
            $newImageName
        ) {

            /*
            |--------------------------------------------------------------------------
            | 1. Update nama layanan dan gambar
            |--------------------------------------------------------------------------
            */

            $serviceData = [
                'service_name' => $validated['service_name'],
            ];

            /**
             * Jika ada gambar baru,
             * gunakan gambar baru.
             *
             * Jika tidak ada gambar baru,
             * kolom image tidak diubah sehingga
             * gambar lama tetap digunakan.
             */
            if ($newImageName) {
                $serviceData['image'] = $newImageName;
            }

            $service->update($serviceData);


            /*
            |--------------------------------------------------------------------------
            | 2. Update / tambah paket
            |--------------------------------------------------------------------------
            */

            foreach ($validated['packages'] ?? [] as $packageData) {

                /*
                |--------------------------------------------------------------------------
                | Paket lama
                |--------------------------------------------------------------------------
                */

                if (!empty($packageData['id'])) {

                    $package = $service->servicePackages()
                        ->where('id', $packageData['id'])
                        ->firstOrFail();

                    $package->update([
                        'package_name' => $packageData['package_name'],
                        'price' => $packageData['price'],
                        'is_active' =>
                            $packageData['is_active']
                            ?? $package->is_active,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Paket baru
                |--------------------------------------------------------------------------
                */

                else {

                    $service->servicePackages()->create([
                        'package_name' => $packageData['package_name'],
                        'price' => $packageData['price'],
                        'is_active' => true,
                    ]);
                }
            }
        });

    } catch (\Throwable $e) {

        /**
         * Jika database gagal setelah gambar baru
         * berhasil dipindahkan, hapus gambar baru
         * agar tidak menjadi file yang tidak terpakai.
         */
        if ($newImageName) {

            $newImagePath =
                public_path('images/' . $newImageName);

            if (file_exists($newImagePath)) {
                unlink($newImagePath);
            }
        }

        throw $e;
    }

    /**
     * Jika update berhasil dan Staff mengganti gambar,
     * hapus gambar lama dari public/images.
     */
    if ($newImageName && $oldImageName) {

        $oldImagePath =
            public_path('images/' . $oldImageName);

        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    /**
     * Kembali ke halaman layanan
     * setelah proses update berhasil.
     */
    return redirect()
        ->route('staff.services.index')
        ->with('success', 'Layanan berhasil diperbarui.');
}


}