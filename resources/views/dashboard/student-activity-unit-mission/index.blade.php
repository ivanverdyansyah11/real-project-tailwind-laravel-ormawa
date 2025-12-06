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
                <input type="search" class="input" name="search" placeholder="Cari misi..." value="{{ $search }}">
            </form>
            @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Misi</button>
            @endif
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
                @if ($studentActivityUnitMissions->count() == 0)
                    <td colspan="2">Data misi tidak ditemukan!</td>
                @else
                    @foreach ($studentActivityUnitMissions as $studentActivityUnitMission)
                        <tr>
                            <td>{{ $studentActivityUnitMission->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentActivityUnitMission->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                                        <button class="button icon-edit" data-target="editModal" data-id="{{ $studentActivityUnitMission->id }}" onclick="openModal(this)">
                                            <span class="bg-edit-warning"></span>
                                        </button>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentActivityUnitMission->id }}" onclick="openModal(this)">
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
            {{ $studentActivityUnitMissions->links() }}
        </div>
    </div>
    @include('modal.student-activity-unit-mission')

    <script>
        const studentActivityUnitId = @json($studentActivityUnit->id);

        function fetchMission(modal, missionId) {
            fetch('/student-activity-unit/' + studentActivityUnitId + '/mission/' + missionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_activity_unit_mission.name;
                    } else {
                        console.log('Data student activity unit mission not found!');
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
                document.getElementById('buttonCreateStudentActivityUnitMission').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/mission')
            } else if (modalTarget.includes('detail')) {
                await fetchMission(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchMission(modal, modalId)
                document.getElementById('buttonEditStudentActivityUnitMission').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/mission/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentActivityUnitMission').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/mission/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
