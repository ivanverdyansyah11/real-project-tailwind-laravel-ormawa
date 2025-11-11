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
                <input type="search" class="input" name="search" placeholder="Cari panitia..." value="{{ $search }}">
            </form>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Event</th>
                    <th>Divisi</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Program Studi</th>
                    <th>Penilaian</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($evaluations->count() == 0)
                    <td colspan="7">Data panitia tidak ditemukan!</td>
                @else
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->event_recruitment->event->name }}</td>
                            <td>{{ $evaluation->event_recruitment->event_division->name }}</td>
                            <td>{{ $evaluation->event_recruitment->student_name }}</td>
                            <td>{{ $evaluation->event_recruitment->student_code }}</td>
                            <td>{{ $evaluation->event_recruitment->study_program }}</td>
                            <td>
                                @if($evaluation->assessment === 'active')
                                    <p class="status-accepted">Aktif</p>
                                @elseif($evaluation->assessment === 'less active')
                                    <p class="status-accepted">Kurang Aktif</p>
                                @elseif($evaluation->assessment === 'inactive')
                                    <p class="status-accepted">Tidak Aktif</p>
                                @endif
                            </td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('evaluation.show', $evaluation) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    <a href="{{ route('evaluation.edit', $evaluation) }}" class="button icon-edit">
                                        <span class="bg-edit-warning"></span>
                                    </a>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $evaluation->id }}" onclick="openModal(this)">
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
            {{ $evaluations->links() }}
        </div>
    </div>
    @include('modal.evaluation')

    <script>
        async function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteEvaluation').setAttribute('action', '/evaluation/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
