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
                <input type="search" class="input" name="search" placeholder="Cari tugas divisi..."
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
                @if ($studentOrganizationDivisionTasks->count() == 0)
                    <td colspan="2">Data tugas divisi tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationDivisionTasks as $studentOrganizationDivisionTask)
                        <tr>
                            <td>{{ $studentOrganizationDivisionTask->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal"
                                            data-id="{{ $studentOrganizationDivisionTask->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal"
                                            data-id="{{ $studentOrganizationDivisionTask->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal"
                                            data-id="{{ $studentOrganizationDivisionTask->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationDivisionTasks->links() }}
        </div>
    </div>
    @include('modal.student-organization-division-task')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);
        const studentOrganizationDivisionId = @json($studentOrganizationDivision->id);

        function fetchDivisionTask(modal, studentOrganizationDivisionTaskId) {
            fetch('/student-organization/' + studentOrganizationId + '/division/' + studentOrganizationDivisionId + '/task/' + studentOrganizationDivisionTaskId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_division_task.name;
                    } else {
                        console.log('Data division task not found!');
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
                document.getElementById('buttonCreateDivisionTask').setAttribute('action', '/student-organization/' + studentOrganizationId + '/division/' + studentOrganizationDivisionId + '/task')
            } else if (modalTarget.includes('detail')) {
                await fetchDivisionTask(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivisionTask(modal, modalId)
                document.getElementById('buttonEditDivisionTask').setAttribute('action', '/student-organization/' + studentOrganizationId + '/division/' + studentOrganizationDivisionId + '/task/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteDivisionTask').setAttribute('action', '/student-organization/' + studentOrganizationId + '/division/' + studentOrganizationDivisionId + '/task/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
