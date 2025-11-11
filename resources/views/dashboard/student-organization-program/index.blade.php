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
                <input type="search" class="input" name="search" placeholder="Cari program..." value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Program</button>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($studentOrganizationPrograms->count() == 0)
                    <td colspan="3">Data program tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationPrograms as $studentOrganizationProgram)
                        <tr>
                            <td>{{ $studentOrganizationProgram->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentOrganizationProgram->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationProgram->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationProgram->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationProgram->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationPrograms->links() }}
        </div>
    </div>
    @include('modal.student-organization-program')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);
        const imagePreviewCreate = document.querySelector('.image-preview-create');
        const imageInputCreate = document.querySelector('.image-input-hidden-create');
        const imagePreviewEdit = document.querySelector('.image-preview-edit');
        const imageInputEdit = document.querySelector('.image-input-hidden-edit');

        function fetchProgram(modal, programId) {
            fetch('/student-organization/' + studentOrganizationId + '/program/' + programId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_program.name;
                        modal.querySelector('textarea[name="description"]').value = data.student_organization_program.description;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('img.image-preview-detail').setAttribute('src', '/assets/image/program/' + data.student_organization_program.image_path);
                        } else if(modal.getAttribute('id').includes('edit')) {
                            modal.querySelector('img.image-preview-edit').setAttribute('src', '/assets/image/program/' + data.student_organization_program.image_path);
                        }
                    } else {
                        console.log('Data student organization program not found!');
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
                document.getElementById('buttonCreateStudentOrganizationProgram').setAttribute('action', '/student-organization/' + studentOrganizationId + '/program')
            } else if (modalTarget.includes('detail')) {
                await fetchProgram(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchProgram(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationProgram').setAttribute('action', '/student-organization/' + studentOrganizationId + '/program/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationProgram').setAttribute('action', '/student-organization/' + studentOrganizationId + '/program/' + modalId)
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
