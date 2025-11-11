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
                <input type="search" class="input" name="search" placeholder="Cari visi..." value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Visi</button>
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
                @if ($studentOrganizationVisions->count() == 0)
                    <td colspan="2">Data visi tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationVisions as $studentOrganizationVision)
                        <tr>
                            <td>{{ $studentOrganizationVision->name }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationVision->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationVision->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationVision->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationVisions->links() }}
        </div>
    </div>
    @include('modal.student-organization-vision')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);

        function fetchVision(modal, visionId) {
            fetch('/student-organization/' + studentOrganizationId + '/vision/' + visionId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_vision.name;
                    } else {
                        console.log('Data student organization vision not found!');
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
                document.getElementById('buttonCreateStudentOrganizationVision').setAttribute('action', '/student-organization/' + studentOrganizationId + '/vision')
            } else if (modalTarget.includes('detail')) {
                await fetchVision(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchVision(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationVision').setAttribute('action', '/student-organization/' + studentOrganizationId + '/vision/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationVision').setAttribute('action', '/student-organization/' + studentOrganizationId + '/vision/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
