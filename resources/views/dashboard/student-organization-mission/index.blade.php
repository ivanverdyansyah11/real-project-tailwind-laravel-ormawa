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
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Misi</button>
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
                @if ($studentOrganizationMissions->count() == 0)
                    <td colspan="2">Data misi tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationMissions as $studentOrganizationMission)
                        <tr>
                            <td>{{ $studentOrganizationMission->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationMission->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationMission->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationMission->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationMissions->links() }}
        </div>
    </div>
    @include('modal.student-organization-mission')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);

        function fetchMission(modal, missionId) {
            fetch('/student-organization/' + studentOrganizationId + '/mission/' + missionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_mission.name;
                    } else {
                        console.log('Data student organization mission not found!');
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
                document.getElementById('buttonCreateStudentOrganizationMission').setAttribute('action', '/student-organization/' + studentOrganizationId + '/mission')
            } else if (modalTarget.includes('detail')) {
                await fetchMission(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchMission(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationMission').setAttribute('action', '/student-organization/' + studentOrganizationId + '/mission/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationMission').setAttribute('action', '/student-organization/' + studentOrganizationId + '/mission/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
