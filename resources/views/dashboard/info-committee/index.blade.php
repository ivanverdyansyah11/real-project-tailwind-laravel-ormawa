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
        @if(count($infoCommittees) === 0)
            <div class="table-header mb-4">
                <button class="button-primary" data-target="createModal" onclick="openModal(this)">Tambah Info Panitia</button>
            </div>
        @endif
        <div class="table-group !mt-0">
            <table>
                <thead>
                <tr>
                    <th>Total Divisi</th>
                    <th>Definisi Panitia</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($infoCommittees->count() == 0)
                    <td colspan="3">Data info panitia tidak ditemukan!</td>
                @else
                    @foreach ($infoCommittees as $infoCommittee)
                        <tr>
                            <td>{{ count($infoCommittee->info_committee_divisions) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($infoCommittee->committee_definition, 40) }}</td>
                            <td>
                                <div class="action-button">
                                    <button data-target="detailModal" data-id="{{ $infoCommittee->id }}" onclick="openModal(this)" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </button>
                                    <button data-target="editModal" data-id="{{ $infoCommittee->id }}" onclick="openModal(this)" class="button icon-edit">
                                        <span class="bg-edit-warning"></span>
                                    </button>
                                    <button class="button icon-delete" data-target="deleteModal" data-id="{{ $infoCommittee->id }}" onclick="openModal(this)">
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
            {{ $infoCommittees->links() }}
        </div>
    </div>
    @include('modal.info-committee')

    <script>
        function fetchInfoCommittee(modal, infoCommitteeId) {
            fetch('/info-committee/' + infoCommitteeId, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status_code === 200) {
                        modal.querySelector('textarea[name="committee_definition"]').value = data.info_committee.committee_definition;
                        if (modal.getAttribute('id').includes('detail')) {
                            modal.querySelector('input[name="total_division"]').value = data.total_division;
                            modal.querySelector('a.button-redirect').setAttribute('href', '/info-committee/' + data.info_committee.id + '/division');
                        }
                    } else {
                        console.log('Data info committee not found!');
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
                document.getElementById('buttonCreateInfoCommittee').setAttribute('action', '/info-committee/')
            } else if (modalTarget.includes('detail')) {
                await fetchInfoCommittee(modal, modalId)
            } else if (modalTarget.includes('edit')) {
                await fetchInfoCommittee(modal, modalId)
                document.getElementById('buttonEditInfoCommittee').setAttribute('action', '/info-committee/' + modalId)
            } else if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteInfoCommittee').setAttribute('action', '/info-committee/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
