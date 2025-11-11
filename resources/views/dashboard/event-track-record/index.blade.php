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
                <input type="search" class="input" name="search" placeholder="Cari rekam jejak event..." value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Rekam Jejak</button>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tahun</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($eventTrackRecords->count() == 0)
                    <td colspan="4">Data rekam jejak event tidak ditemukan!</td>
                @else
                    @foreach ($eventTrackRecords as $eventTrackRecord)
                        <tr>
                            <td>{{ $eventTrackRecord->title }}</td>
                            <td>{{ $eventTrackRecord->year }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($eventTrackRecord->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $eventTrackRecord->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $eventTrackRecord->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $eventTrackRecord->id }}" onclick="openModal(this)">
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
            {{ $eventTrackRecords->links() }}
        </div>
    </div>
    @include('modal.event-track-record')

    <script>
        const eventId = @json($event->id);
        const imagePreviewCreate = document.querySelector('.image-preview-create');
        const imageInputCreate = document.querySelector('.image-input-hidden-create');
        const imagePreviewEdit = document.querySelector('.image-preview-edit');
        const imageInputEdit = document.querySelector('.image-input-hidden-edit');

        function fetchTrackRecord(modal, trackRecordId) {
            fetch('/event/' + eventId + '/track-record/' + trackRecordId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="title"]').value = data.event_track_record.title;
                        modal.querySelector('input[name="year"]').value = data.event_track_record.year;
                        modal.querySelector('textarea[name="description"]').value = data.event_track_record.description;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('img.image-preview-detail').setAttribute('src', '/assets/image/track-record/' + data.event_track_record.image_path);
                        } else if(modal.getAttribute('id').includes('edit')) {
                            modal.querySelector('img.image-preview-edit').setAttribute('src', '/assets/image/track-record/' + data.event_track_record.image_path);
                        }
                    } else {
                        console.log('Data event track record not found!');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        async function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('create')) {
                document.getElementById('buttonCreateEventTrackRecord').setAttribute('action', '/event/' + eventId + '/track-record')
            } else if (modalTarget.includes('detail')) {
                await fetchTrackRecord(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchTrackRecord(modal, modalId)
                document.getElementById('buttonEditEventTrackRecord').setAttribute('action', '/event/' + eventId + '/track-record/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteEventTrackRecord').setAttribute('action', '/event/' + eventId + '/track-record/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }

        imageInputCreate.addEventListener('change', function() {
            imagePreviewCreate.src = URL.createObjectURL(imageInputCreate.files[0]);
        });

        imageInputEdit.addEventListener('change', function() {
            imagePreviewEdit.src = URL.createObjectURL(imageInputEdit.files[0]);
        });
    </script>
@endsection
