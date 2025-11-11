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
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Divisi</button>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Urutan</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($eventDivisions->count() == 0)
                    <td colspan="2">Data divisi tidak ditemukan!</td>
                @else
                    @foreach ($eventDivisions as $eventDivision)
                        <tr>
                            <td>{{ $eventDivision->name }}</td>
                            <td>{{ $eventDivision->sort }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $eventDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $eventDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $eventDivision->id }}" onclick="openModal(this)">
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
            {{ $eventDivisions->links() }}
        </div>
    </div>
    @include('modal.event-division')

    <script>
        const eventId = @json($event->id);

        function fetchDivision(modal, divisionId) {
            fetch('/event/' + eventId + '/division/' + divisionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.event_division.name;
                        modal.querySelector('input[name="sort"]').value = data.event_division.sort;
                    } else {
                        console.log('Data event division not found!');
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
                document.getElementById('buttonCreateEventDivision').setAttribute('action', '/event/' + eventId + '/division')
            } else if (modalTarget.includes('detail')) {
                await fetchDivision(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivision(modal, modalId)
                document.getElementById('buttonEditEventDivision').setAttribute('action', '/event/' + eventId + '/division/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteEventDivision').setAttribute('action', '/event/' + eventId + '/division/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
