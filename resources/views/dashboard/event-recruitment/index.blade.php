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
                <input type="search" class="input" name="search" placeholder="Cari divisi..." value="{{ $search }}">
            </form>
            <a href="{{ route('event-recruitment.generate-sk', $event) }}" class="button-secondary">Cetak SK Panitia</a>
            <a href="{{ route('event-recruitment.create', $event) }}" class="button-primary" onclick="openModal(this)">Tambah Perekrut</a>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Divisi</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Total Mendaftar Event</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($eventRecruitments->count() == 0)
                    <td colspan="7">Data perekrut tidak ditemukan!</td>
                @else
                    @foreach ($eventRecruitments as $eventRecruitment)
                        <tr>
                            <td>{{ $eventRecruitment->event_division->name }}</td>
                            <td>{{ $eventRecruitment->student_name }}</td>
                            <td>{{ $eventRecruitment->student_code }}</td>
                            <td>{{ $eventRecruitment->email }}</td>
                            <td>{{ $eventRecruitment->number_phone }}</td>
                            <td>{{ $eventRecruitment->total_event }}</td>
                            <td>
                                @if($eventRecruitment->status === 'pending')
                                    <p class="status-pending">Tertunda</p>
                                @elseif($eventRecruitment->status === 'accepted')
                                    <p class="status-accepted">Diterima</p>
                                @elseif($eventRecruitment->status === 'rejected')
                                    <p class="status-rejected">Tidak Diterima</p>
                                @endif
                            </td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('evaluation.index', ['search' => $eventRecruitment->student_code]) }}" class="button icon-evaluation">
                                        <i class="fa-regular fa-folder-open text-yellow"></i>
                                    </a>
                                    <a href="{{ route('event-recruitment.show', ['event' => $event, 'eventRecruitment' => $eventRecruitment->id]) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    <a href="{{ route('event-recruitment.edit', ['event' => $event, 'eventRecruitment' => $eventRecruitment->id]) }}" class="button icon-edit">
                                        <span class="bg-edit-warning"></span>
                                    </a>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $eventRecruitment->id }}" onclick="openModal(this)">
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
            {{ $eventRecruitments->links() }}
        </div>
    </div>
    @include('modal.event-recruitment')

    <script>
        const eventId = @json($event->id);

        async function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteEventRecruitment').setAttribute('action', '/event/' + eventId + '/recruitment/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
