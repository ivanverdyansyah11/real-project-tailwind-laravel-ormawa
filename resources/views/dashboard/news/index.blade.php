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
                <input type="search" class="input" name="search" placeholder="Cari berita..." value="{{ $search }}">
            </form>
            <a href="{{ route('news.create') }}" class="button-primary">Tambah Berita</a>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Pembuat</th>
                    <th>Nama Berita</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($newses->count() == 0)
                    <td colspan="4">Data berita tidak ditemukan!</td>
                @else
                    @foreach ($newses as $news)
                        <tr>
                            <td>{{ $news->student_organization ? 'Ormawa: ' . $news->student_organization->name : 'UKM: ' . $news->student_activity_unit->name }}</td>
                            <td>{{ $news->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($news->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('news.show', $news) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    <a href="{{ route('news.edit', $news) }}" class="button icon-edit">
                                        <span class="bg-edit-warning"></span>
                                    </a>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $news->id }}" onclick="openModal(this)">
                                        <span class="bg-delete-danger"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="table-paginate">
            {{ $newses->links() }}
        </div>
    </div>
    @include('modal.news')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            document.getElementById(`${modalTarget}`).classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteNews').setAttribute('action', '/news/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
