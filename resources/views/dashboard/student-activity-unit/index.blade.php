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
                <input type="search" class="input" name="search" placeholder="Cari ukm..." value="{{ $search }}">
            </form>
            @if(auth()->user()->admin)
                <a href="{{ route('student-activity-unit.create') }}" class="button-primary">Tambah UKM</a>
            @endif
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Singkatan</th>
                    <th>Deskripsi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($studentActivityUnits->count() == 0)
                    <td colspan="4">Data ukm tidak ditemukan!</td>
                @else
                    @foreach ($studentActivityUnits as $studentActivityUnit)
                        <tr>
                            <td>{{ $studentActivityUnit->name }}</td>
                            <td>{{ $studentActivityUnit->abbreviation }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentActivityUnit->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('student-activity-unit.show', $studentActivityUnit) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    @if(auth()->user()->admin)
                                        <a href="{{ route('student-activity-unit.edit', $studentActivityUnit) }}" class="button icon-edit">
                                            <span class="bg-edit-warning"></span>
                                        </a>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentActivityUnit->id }}" onclick="openModal(this)">
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
            {{ $studentActivityUnits->links() }}
        </div>
    </div>
    @include('modal.student-activity-unit')

    <script>
        async function openModal(element) {
            console.log(element)
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            document.getElementById(`${modalTarget}`).classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentActivityUnit').setAttribute('action', '/student-activity-unit/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
