@extends('layouts.admin')
@section('title', 'Kelola Kategori - CampLens')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold">Kelola Kategori</h1>
        <p class="text-sm text-gray-500">Manajemen kategori barang kamera dan camping</p>
    </div>
    <button id="bulk-delete-btn" onclick="submitBulkDelete()" class="hidden bg-red-100 text-red-600 px-6 py-2 rounded-xl font-bold hover:bg-red-200 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Hapus yang Dipilih (<span id="selected-count">0</span>)
    </button>
</div>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex justify-between items-center">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah -->
    <div class="lg:col-span-1">
        <div class="dashboard-card">
            <h3 class="text-lg font-bold mb-4">Tambah Kategori Baru</h3>
            <form method="POST" action="{{ route('admin.addCategory') }}">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Nama Kategori" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                    <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-xl font-bold hover:bg-teal-700 transition">Tambah Kategori</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabel Kategori -->
    <div class="lg:col-span-2">
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.bulkDeleteCategories') }}">
            @csrf
            <div class="dashboard-card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 w-10 text-center">
                                <input type="checkbox" id="select-all" class="rounded text-teal-600 focus:ring-teal-500">
                            </th>
                            <th class="px-6 py-4 font-semibold">Nama</th>
                            <th class="px-6 py-4 font-semibold">Total Barang</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $cat->id }}" class="item-checkbox rounded text-teal-600 focus:ring-teal-500">
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $cat->name }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-xs font-bold">{{ $cat->items_count }} Barang</span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button type="button" onclick="openEditModal({{ $cat->id }}, '{{ $cat->name }}')" class="bg-yellow-400 text-white p-2 rounded-lg hover:bg-yellow-500 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" onclick="deleteCategory({{ $cat->id }})" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- Single Delete Form (Hidden) -->
<form id="single-delete-form" method="POST" action="{{ route('admin.deleteCategory') }}" class="hidden">
    @csrf
    <input type="hidden" name="id" id="delete-id">
</form>

<!-- Modal Edit -->
<div id="edit-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-md w-full shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Edit Kategori</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.updateCategory') }}">
            @csrf
            <input type="hidden" name="id" id="edit-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-bold hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

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
        if (confirm('Yakin ingin menghapus semua kategori yang dipilih?')) {
            document.getElementById('bulk-delete-form').submit();
        }
    }

    function deleteCategory(id) {
        if (confirm('Yakin ingin menghapus kategori ini?')) {
            document.getElementById('delete-id').value = id;
            document.getElementById('single-delete-form').submit();
        }
    }

    function openEditModal(id, name) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endsection
