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
                <input type="search" class="input" name="search" placeholder="Cari event..." value="{{ $search }}">
            </form>
            <a href="{{ route('event.create') }}" class="button-primary">Tambah Event</a>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Pembuat</th>
                    <th>Nama Event</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($events->count() == 0)
                    <td colspan="4">Data event tidak ditemukan!</td>
                @else
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->student_organization ? 'Ormawa: ' . $event->student_organization->name : 'UKM: ' . $event->student_activity_unit->name }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($event->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('event.show', $event) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    <a href="{{ route('event.edit', $event) }}" class="button icon-edit">
                                        <span class="bg-edit-warning"></span>
                                    </a>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $event->id }}" onclick="openModal(this)">
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
            {{ $events->links() }}
        </div>
    </div>
    @include('modal.event')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            document.getElementById(`${modalTarget}`).classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteEvent').setAttribute('action', '/event/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
