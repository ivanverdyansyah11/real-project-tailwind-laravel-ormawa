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
                @if ($studentOrganizationDivisions->count() == 0)
                    <td colspan="3">Data divisi tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationDivisions as $studentOrganizationDivision)
                        <tr>
                            <td>{{ $studentOrganizationDivision->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentOrganizationDivision->definition, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationDivision->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationDivisions->links() }}
        </div>
    </div>
    @include('modal.student-organization-division')

    <script>
        const studentOrganizationDivisionId = @json($studentOrganization->id);

        function fetchDivision(modal, divisionId) {
            fetch('/student-organization/' + studentOrganizationDivisionId + '/division/' + divisionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_division.name;
                        modal.querySelector('textarea[name="definition"]').value = data.student_organization_division.definition;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('input[name="task"]').value = data.student_organization_division_tasks;
                            modal.querySelector('a.button-redirect').setAttribute('href', '/student-organization/' + data.student_organization.id + '/division/' + data.student_organization_division.id + '/task');
                        }
                    } else {
                        console.log('Data division not found!');
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
                document.getElementById('buttonCreateStudentOrganizationDivision').setAttribute('action', '/student-organization/' + studentOrganizationDivisionId + '/division')
            } else if (modalTarget.includes('detail')) {
                await fetchDivision(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivision(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationDivision').setAttribute('action', '/student-organization/' + studentOrganizationDivisionId + '/division/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationDivision').setAttribute('action', '/student-organization/' + studentOrganizationDivisionId + '/division/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
