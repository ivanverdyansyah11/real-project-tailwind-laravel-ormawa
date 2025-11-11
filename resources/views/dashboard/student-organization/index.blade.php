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
                <input type="search" class="input" name="search" placeholder="Cari organisasi mahasiswa..." value="{{ $search }}">
            </form>
            @if(auth()->user()->admin)
                <a href="{{ route('student-organization.create') }}" class="button-primary">Tambah Ormawa</a>
            @endif
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Singkatan</th>
                    <th>Total Program</th>
                    <th>Total Prestasi</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($studentOrganizations->count() == 0)
                    <td colspan="5">Data organisasi mahasiswa tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizations as $studentOrganization)
                        <tr>
                            <td>{{ $studentOrganization->name }}</td>
                            <td>{{ $studentOrganization->abbreviation }}</td>
                            <td>{{ count($studentOrganization->student_organization_programs) }}</td>
                            <td>{{ count($studentOrganization->student_organization_achievements) }}</td>
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('student-organization.show', $studentOrganization) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    @if(auth()->user()->admin)
                                        <a href="{{ route('student-organization.edit', $studentOrganization) }}" class="button icon-edit">
                                            <span class="bg-edit-warning"></span>
                                        </a>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganization->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizations->links() }}
        </div>
    </div>
    @include('modal.student-organization')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            document.getElementById(`${modalTarget}`).classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganization').setAttribute('action', '/student-organization/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
