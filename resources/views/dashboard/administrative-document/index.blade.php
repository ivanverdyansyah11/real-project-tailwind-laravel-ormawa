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
                <input type="search" class="input" name="search" placeholder="Cari dokumen administrasi..." value="{{ $search }}">
            </form>
            @if(auth()->user()->admin)
                <a href="{{ route('administrative-document.create') }}" class="button-primary">Tambah Dokumen Administrasi</a>
            @endif
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($administrativeDocuments->count() == 0)
                    <td colspan="3">Data dokumen administrasi tidak ditemukan!</td>
                @else
                    @foreach ($administrativeDocuments as $administrativeDocument)
                        <tr>
                            <td>{{ $administrativeDocument->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($administrativeDocument->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('administrative-document.download', $administrativeDocument) }}" class="button icon-download">
                                        <span class="bg-download-primary"></span>
                                    </a>
                                    <a href="{{ route('administrative-document.show', $administrativeDocument) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    @if(auth()->user()->admin)
                                        <a href="{{ route('administrative-document.edit', $administrativeDocument) }}" class="button icon-edit">
                                            <span class="bg-edit-warning"></span>
                                        </a>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $administrativeDocument->id }}" onclick="openModal(this)">
                                            <span class="bg-delete-danger"></span>
                                        </button>
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
            {{ $administrativeDocuments->links() }}
        </div>
    </div>
    @include('modal.administrative-document')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteAdministrativeDocument').setAttribute('action', '/administrative-document/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
