@extends('template.dashboard')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session()->has('failed'))
        <div class="alert alert-danger" role="alert">
            {{ session('failed') }}
        </div>
    @endif
    <div class="content-menu content-table">
        <div class="table-header">
            <form method="GET" class="form">
                <input type="search" class="input" name="search" placeholder="Cari admin..." value="{{ $search }}">
            </form>
            @if(auth()->user()->admin->is_super_admin)
                <a href="{{ route('admin.create') }}" class="button-primary">Tambah Admin</a>
            @endif
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIP</th>
                    <th>Nomor Telepon</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($admins->count() == 0)
                    <td colspan="6">Data admin tidak ditemukan!</td>
                @else
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->full_name }}</td>
                            <td>{{ $admin->lecturer_code }}</td>
                            <td>{{ $admin->phone_number }}</td>
                            <td>{{ $admin->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            <td>{{ $admin->status ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td>
                                <div class="action-button">
                                    @if(auth()->user()->admin->id !== $admin->id)
                                        <a href="{{ route('admin.show', $admin) }}" class="button icon-detail">
                                            <span class="bg-detail-primary"></span>
                                        </a>
                                        @if(auth()->user()->admin->is_super_admin)
                                            <a href="{{ route('admin.edit', $admin) }}" class="button icon-edit">
                                                <span class="bg-edit-warning"></span>
                                            </a>
                                            <button class="button icon-delete" data-target="deleteModal" data-id="{{ $admin->id }}" onclick="openModal(this)">
                                                <span class="bg-delete-danger"></span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="table-paginate">
            {{ $admins->links() }}
        </div>
    </div>
    @include('modal.admin')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteAdmin').setAttribute('action', '/admin/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
