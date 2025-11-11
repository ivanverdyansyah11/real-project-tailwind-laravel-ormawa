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
                <input type="search" class="input" name="search" placeholder="Cari tugas divisi panitia..."
                       value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Tugas</button>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($infoCommitteeDivisionTasks->count() == 0)
                    <td colspan="2">Data tugas divisi panitia tidak ditemukan!</td>
                @else
                    @foreach ($infoCommitteeDivisionTasks as $infoCommitteeDivisionTask)
                        <tr>
                            <td>{{ $infoCommitteeDivisionTask->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal"
                                            data-id="{{ $infoCommitteeDivisionTask->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal"
                                            data-id="{{ $infoCommitteeDivisionTask->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal"
                                            data-id="{{ $infoCommitteeDivisionTask->id }}" onclick="openModal(this)">
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
            {{ $infoCommitteeDivisionTasks->links() }}
        </div>
    </div>
    @include('modal.info-committee-division-task')

    <script>
        const infoCommitteeId = @json($infoCommittee->id);
        const infoCommitteeDivisionId = @json($infoCommitteeDivision->id);

        function fetchInfoCommitteeDivisionTask(modal, infoCommitteeDivisionTaskId) {
            fetch('/info-committee/' + infoCommitteeId + '/division/' + infoCommitteeDivisionId + '/task/' + infoCommitteeDivisionTaskId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.info_committee_division_task.name;
                    } else {
                        console.log('Data info committee division task not found!');
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
                document.getElementById('buttonCreateInfoCommitteeDivisionTask').setAttribute('action', '/info-committee/' + infoCommitteeId + '/division/' + infoCommitteeDivisionId + '/task')
            } else if (modalTarget.includes('detail')) {
                await fetchInfoCommitteeDivisionTask(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchInfoCommitteeDivisionTask(modal, modalId)
                document.getElementById('buttonEditInfoCommitteeDivisionTask').setAttribute('action', '/info-committee/' + infoCommitteeId + '/division/' + infoCommitteeDivisionId + '/task/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteInfoCommitteeDivisionTask').setAttribute('action', '/info-committee/' + infoCommitteeId + '/division/' + infoCommitteeDivisionId + '/task/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
