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
                <input type="search" class="input" name="search" placeholder="Cari prestasi..." value="{{ $search }}">
            </form>
            <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Prestasi</button>
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
                @if ($studentOrganizationAchievements->count() == 0)
                    <td colspan="3">Data prestasi tidak ditemukan!</td>
                @else
                    @foreach ($studentOrganizationAchievements as $studentOrganizationAchievement)
                        <tr>
                            <td>{{ $studentOrganizationAchievement->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($studentOrganizationAchievement->description, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button class="button icon-detail" data-target="detailModal" data-id="{{ $studentOrganizationAchievement->id }}" onclick="openModal(this)">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button class="button icon-edit" data-target="editModal" data-id="{{ $studentOrganizationAchievement->id }}" onclick="openModal(this)">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $studentOrganizationAchievement->id }}" onclick="openModal(this)">
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
            {{ $studentOrganizationAchievements->links() }}
        </div>
    </div>
    @include('modal.student-organization-achievement')

    <script>
        const studentOrganizationId = @json($studentOrganization->id);

        function fetchAchievement(modal, achievementId) {
            fetch('/student-organization/' + studentOrganizationId + '/achievement/' + achievementId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('input[name="name"]').value = data.student_organization_achievement.name;
                        modal.querySelector('textarea[name="description"]').value = data.student_organization_achievement.description;
                    } else {
                        console.log('Data student organization achievement not found!');
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
                document.getElementById('buttonCreateStudentOrganizationAchievement').setAttribute('action', '/student-organization/' + studentOrganizationId + '/achievement')
            } else if (modalTarget.includes('detail')) {
                await fetchAchievement(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchAchievement(modal, modalId)
                document.getElementById('buttonEditStudentOrganizationAchievement').setAttribute('action', '/student-organization/' + studentOrganizationId + '/achievement/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteStudentOrganizationAchievement').setAttribute('action', '/student-organization/' + studentOrganizationId + '/achievement/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
