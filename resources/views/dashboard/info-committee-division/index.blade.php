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
                    <th>Nama Divisi</th>
                    <th>Definisi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($infoCommitteeDivisions->count() == 0)
                    <td colspan="3">Data divisi tidak ditemukan!</td>
                @else
                    @foreach ($infoCommitteeDivisions as $infoCommitteeDivision)
                        <tr>
                            <td>{{ $infoCommitteeDivision->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($infoCommitteeDivision->definition, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $infoCommitteeDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $infoCommitteeDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $infoCommitteeDivision->id }}" onclick="openModal(this)">
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
            {{ $infoCommitteeDivisions->links() }}
        </div>
    </div>
    @include('modal.info-committee-division')

    <script>
        const infoCommitteeDivisionId = @json($infoCommittee->id);

        function fetchDivision(modal, missionId) {
            fetch('/info-committee/' + infoCommitteeDivisionId + '/division/' + missionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.info_committee_division.name;
                        modal.querySelector('textarea[name="definition"]').value = data.info_committee_division.definition;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('input[name="task"]').value = data.info_committee_division_tasks;
                            modal.querySelector('a.button-redirect').setAttribute('href', '/info-committee/' + data.info_committee.id + '/division/' + data.info_committee_division.id + '/task');
                        }
                    } else {
                        console.log('Data info committee division not found!');
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
                document.getElementById('buttonCreateInfoCommitteeDivision').setAttribute('action', '/info-committee/' + infoCommitteeDivisionId + '/division')
            } else if (modalTarget.includes('detail')) {
                await fetchDivision(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivision(modal, modalId)
                document.getElementById('buttonEditInfoCommitteeDivision').setAttribute('action', '/info-committee/' + infoCommitteeDivisionId + '/division/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteInfoCommitteeDivision').setAttribute('action', '/info-committee/' + infoCommitteeDivisionId + '/division/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
