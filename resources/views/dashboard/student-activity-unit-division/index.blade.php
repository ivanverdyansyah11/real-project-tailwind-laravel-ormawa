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
            @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Divisi</button>
            @endif
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
                @if ($studentActivityUnitDivisions->count() == 0)
                    <td colspan="3">Data divisi tidak ditemukan!</td>
                @else
                    @foreach ($studentActivityUnitDivisions as $studentActivityUnitDivision)
                        <tr>
                            <td>{{ $studentActivityUnitDivision->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentActivityUnitDivision->definition, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentActivityUnitDivision->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                                        <button class="button icon-edit" data-target="editModal" data-id="{{ $studentActivityUnitDivision->id }}" onclick="openModal(this)">
                                            <span class="bg-edit-warning"></span>
                                        </button>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentActivityUnitDivision->id }}" onclick="openModal(this)">
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
            {{ $studentActivityUnitDivisions->links() }}
        </div>
    </div>
    @include('modal.student-activity-unit-division')

    <script>
        const studentActivityUnitDivisionId = @json($studentActivityUnit->id);

        function fetchDivision(modal, divisionId) {
            fetch('/student-activity-unit/' + studentActivityUnitDivisionId + '/division/' + divisionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_activity_unit_division.name;
                        modal.querySelector('textarea[name="definition"]').value = data.student_activity_unit_division.definition;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('input[name="task"]').value = data.student_activity_unit_division_tasks;
                            modal.querySelector('a.button-redirect').setAttribute('href', '/student-activity-unit/' + data.student_activity_unit.id + '/division/' + data.student_activity_unit_division.id + '/task');
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
                document.getElementById('buttonCreateStudentActivityUnitDivision').setAttribute('action', '/student-activity-unit/' + studentActivityUnitDivisionId + '/division')
            } else if (modalTarget.includes('detail')) {
                await fetchDivision(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivision(modal, modalId)
                document.getElementById('buttonEditStudentActivityUnitDivision').setAttribute('action', '/student-activity-unit/' + studentActivityUnitDivisionId + '/division/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentActivityUnitDivision').setAttribute('action', '/student-activity-unit/' + studentActivityUnitDivisionId + '/division/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
