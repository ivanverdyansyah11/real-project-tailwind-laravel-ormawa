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
            @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Program</button>
            @endif
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
                @if ($studentActivityUnitPrograms->count() == 0)
                    <td colspan="3">Data program tidak ditemukan!</td>
                @else
                    @foreach ($studentActivityUnitPrograms as $studentActivityUnitProgram)
                        <tr>
                            <td>{{ $studentActivityUnitProgram->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentActivityUnitProgram->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentActivityUnitProgram->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                                        <button class="button icon-edit" data-target="editModal" data-id="{{ $studentActivityUnitProgram->id }}" onclick="openModal(this)">
                                            <span class="bg-edit-warning"></span>
                                        </button>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentActivityUnitProgram->id }}" onclick="openModal(this)">
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
            {{ $studentActivityUnitPrograms->links() }}
        </div>
    </div>
    @include('modal.student-activity-unit-program')

    <script>
        const studentActivityUnitId = @json($studentActivityUnit->id);
        const imagePreviewCreate = document.querySelector('.image-preview-create');
        const imageInputCreate = document.querySelector('.image-input-hidden-create');
        const imagePreviewEdit = document.querySelector('.image-preview-edit');
        const imageInputEdit = document.querySelector('.image-input-hidden-edit');

        function fetchProgram(modal, programId) {
            fetch('/student-activity-unit/' + studentActivityUnitId + '/program/' + programId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_activity_unit_program.name;
                        modal.querySelector('textarea[name="description"]').value = data.student_activity_unit_program.description;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('img.image-preview-detail').setAttribute('src', '/assets/image/program/' + data.student_activity_unit_program.image_path);
                        } else if(modal.getAttribute('id').includes('edit')) {
                            modal.querySelector('img.image-preview-edit').setAttribute('src', '/assets/image/program/' + data.student_activity_unit_program.image_path);
                        }
                    } else {
                        console.log('Data student activity unit program not found!');
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
                document.getElementById('buttonCreateStudentActivityUnitProgram').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/program')
            } else if (modalTarget.includes('detail')) {
                await fetchProgram(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchProgram(modal, modalId)
                document.getElementById('buttonEditStudentActivityUnitProgram').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/program/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentActivityUnitProgram').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/program/' + modalId)
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
