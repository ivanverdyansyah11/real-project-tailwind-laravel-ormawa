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
            @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Tugas</button>
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
                @if ($studentActivityUnitDivisionTasks->count() == 0)
                    <td colspan="2">Data tugas divisi tidak ditemukan!</td>
                @else
                    @foreach ($studentActivityUnitDivisionTasks as $studentActivityUnitDivisionTask)
                        <tr>
                            <td>{{ $studentActivityUnitDivisionTask->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal"
                                            data-id="{{ $studentActivityUnitDivisionTask->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    @if(auth()->user()->admin || auth()->user()->student_activity_unit)
                                        <button class="button icon-edit" data-target="editModal"
                                                data-id="{{ $studentActivityUnitDivisionTask->id }}" onclick="openModal(this)">
                                            <span class="bg-edit-warning"></span>
                                        </button>
                                        <button class="button icon-delete" data-target="deleteModal"
                                                data-id="{{ $studentActivityUnitDivisionTask->id }}" onclick="openModal(this)">
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
            {{ $studentActivityUnitDivisionTasks->links() }}
        </div>
    </div>
    @include('modal.student-activity-unit-division-task')

    <script>
        const studentActivityUnitId = @json($studentActivityUnit->id);
        const studentActivityUnitDivisionId = @json($studentActivityUnitDivision->id);

        function fetchDivisionTask(modal, studentActivityUnitDivisionTaskId) {
            fetch('/student-activity-unit/' + studentActivityUnitId + '/division/' + studentActivityUnitDivisionId + '/task/' + studentActivityUnitDivisionTaskId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_activity_unit_division_task.name;
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
                document.getElementById('buttonCreateDivisionTask').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/division/' + studentActivityUnitDivisionId + '/task')
            } else if (modalTarget.includes('detail')) {
                await fetchDivisionTask(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchDivisionTask(modal, modalId)
                document.getElementById('buttonEditDivisionTask').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/division/' + studentActivityUnitDivisionId + '/task/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteDivisionTask').setAttribute('action', '/student-activity-unit/' + studentActivityUnitId + '/division/' + studentActivityUnitDivisionId + '/task/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
