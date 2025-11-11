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
                <input type="search" class="input" name="search" placeholder="Cari struktur..." value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Struktur</button>
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>Role</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($studentOrganizationStructures->count() == 0)
                    <td colspan="4">Data struktur tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationStructures as $studentOrganizationStructure)
                        <tr>
                            <td>{{ $studentOrganizationStructure->student_name }}</td>
                            <td>{{ $studentOrganizationStructure->student_code }}</td>
                            <td>{{ $studentOrganizationStructure->role }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationStructure->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationStructure->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationStructure->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationStructures->links() }}
        </div>
    </div>
    @include('modal.student-organization-structure')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);
        const imagePreviewCreate = document.querySelector('.image-preview-create');
        const imageInputCreate = document.querySelector('.image-input-hidden-create');
        const imagePreviewEdit = document.querySelector('.image-preview-edit');
        const imageInputEdit = document.querySelector('.image-input-hidden-edit');

        function fetchStructure(modal, structureId) {
            fetch('/student-organization/' + studentOrganizationId + '/structure/' + structureId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="student_name"]').value = data.student_organization_structure.student_name;
                        modal.querySelector('input[name="student_code"]').value = data.student_organization_structure.student_code;
                        modal.querySelector('input[name="role"]').value = data.student_organization_structure.role;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('img.image-preview-detail').setAttribute('src', '/assets/image/structure/' + data.student_organization_structure.profile_path);
                        } else if(modal.getAttribute('id').includes('edit')) {
                            modal.querySelector('img.image-preview-edit').setAttribute('src', '/assets/image/structure/' + data.student_organization_structure.profile_path);
                        }
                    } else {
                        console.log('Data student organization structure not found!');
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
                document.getElementById('buttonCreateStudentOrganizationStructure').setAttribute('action', '/student-organization/' + studentOrganizationId + '/structure')
            } else if (modalTarget.includes('detail')) {
                await fetchStructure(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchStructure(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationStructure').setAttribute('action', '/student-organization/' + studentOrganizationId + '/structure/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationStructure').setAttribute('action', '/student-organization/' + studentOrganizationId + '/structure/' + modalId)
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
