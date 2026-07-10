@extends('layouts.admin')
@section('title', 'Kelola Barang - CampLens')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Kelola Barang</h1>
        <p class="text-sm text-gray-500">Manajemen inventaris kamera dan alat camping</p>
    </div>
    <div class="flex gap-3">
        <button id="bulk-delete-btn" onclick="submitBulkDelete()" class="hidden bg-red-100 text-red-600 px-6 py-2 rounded-xl font-bold hover:bg-red-200 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Hapus yang Dipilih (<span id="selected-count">0</span>)
        </button>
        <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="bg-teal-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-teal-700 transition">Tambah Barang Baru</button>
    </div>
</div>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

<form id="bulk-delete-form" method="POST" action="{{ route('admin.bulkDeleteItems') }}">
    @csrf
    <div class="dashboard-card overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" id="select-all" class="rounded text-teal-600 focus:ring-teal-500">
                    </th>
                    <th class="px-6 py-4 font-semibold text-sm">Gambar</th>
                    <th class="px-6 py-4 font-semibold text-sm">Nama</th>
                    <th class="px-6 py-4 font-semibold text-sm">Kategori</th>
                    <th class="px-6 py-4 font-semibold text-sm">Harga/Hari</th>
                    <th class="px-6 py-4 font-semibold text-sm text-center">Stok</th>
                    <th class="px-6 py-4 font-semibold text-sm text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="item-checkbox rounded text-teal-600 focus:ring-teal-500">
                    </td>
                    <td class="px-6 py-4">
                        <img src="{{ $item->image ? asset('storage/' . $item->image) : $item->image_url }}" alt="{{ $item->name }}" class="w-16 h-12 object-cover rounded-lg shadow-sm">
                    </td>
                    <td class="px-6 py-4 font-medium text-sm">{{ $item->name }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 px-3 py-1 rounded-full text-[10px] font-bold uppercase text-gray-600">{{ $item->category->name }}</span>
                    </td>
                    <td class="px-6 py-4 font-bold text-sm text-teal-600">Rp{{ number_format($item->daily_rate, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-center">{{ $item->stock }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick='openEditModal({!! json_encode($item) !!})' class="bg-yellow-400 text-white p-2 rounded-lg hover:bg-yellow-500 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button type="button" onclick="deleteItem({{ $item->id }})" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>

<!-- Single Delete Form (Hidden) -->
<form id="single-delete-form" method="POST" action="{{ route('admin.deleteItem') }}" class="hidden">
    @csrf
    <input type="hidden" name="id" id="delete-id">
</form>

<!-- Modal Tambah & Edit remains same ... -->
<!-- [MODAL CODE START] -->
<div id="add-item-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Tambah Barang Baru</h3>
            <button onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.addItem') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Barang</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga Harian (Rp)</label>
                        <input type="number" name="daily_rate" required min="0" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Stok</label>
                        <input type="number" name="stock" required min="1" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500" placeholder="Masukkan deskripsi barang..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Gambar</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-bold hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-item-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Edit Barang</h3>
            <button onclick="document.getElementById('edit-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.updateItem') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Barang</label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="category_id" id="edit-category" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga Harian (Rp)</label>
                        <input type="number" name="daily_rate" id="edit-rate" required min="0" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Stok</label>
                        <input type="number" name="stock" id="edit-stock" required min="1" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Deskripsi</label>
                    <textarea name="description" id="edit-description" rows="3" class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500" placeholder="Masukkan deskripsi barang..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Ganti Gambar (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-bold hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- [CRUD Kelola Kategori ADMIN] -->
<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCount = document.getElementById('selected-count');

    function updateBulkButton() {
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkDeleteBtn.classList.remove('hidden');
        } else {
            bulkDeleteBtn.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkButton();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });

    function submitBulkDelete() {
        if (confirm('Yakin ingin menghapus semua barang yang dipilih?')) {
            document.getElementById('bulk-delete-form').submit();
        }
    }

    function deleteItem(id) {
        if (confirm('Yakin ingin menghapus barang ini?')) {
            document.getElementById('delete-id').value = id;
            document.getElementById('single-delete-form').submit();
        }
    }

    function openEditModal(item) {
        document.getElementById('edit-id').value = item.id;
        document.getElementById('edit-name').value = item.name;
        document.getElementById('edit-category').value = item.category_id;
        document.getElementById('edit-rate').value = item.daily_rate;
        document.getElementById('edit-stock').value = item.stock;
        document.getElementById('edit-description').value = item.description || '';
        document.getElementById('edit-item-modal').classList.remove('hidden');
    }
</script>
@endsection
