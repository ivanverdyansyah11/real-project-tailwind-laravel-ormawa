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
                <input type="search" class="input" name="search" placeholder="Cari arsip administrasi..." value="{{ $search }}">
            </form>
            @if(auth()->user()->student_organization || auth()->user()->student_activity_unit)
                <a href="{{ route('activity-report.create') }}" class="button-primary">Tambah Arsip Administrasi</a>
            @endif
        </div>
        <div class="table-group">
            <table>
                <thead>
                <tr>
                    <th>Pembuat</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
{{--                    <th>Status</th>--}}
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($activityReports->count() == 0)
                    <td colspan="5">Data arsip administrasi tidak ditemukan!</td>
                @else
                    @foreach ($activityReports as $activityReport)
                        <tr>
                            <td>{{ $activityReport->student_organization ? 'Ormawa: ' . $activityReport->student_organization->name : 'UKM: ' . $activityReport->student_activity_unit->name }}</td>
                            <td>{{ $activityReport->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($activityReport->description, 40) }}</td>
{{--                            <td>--}}
{{--                                @if($activityReport->status === 'pending')--}}
{{--                                    <p class="status-pending">Tertunda</p>--}}
{{--                                @elseif($activityReport->status === 'accepted')--}}
{{--                                    <p class="status-accepted">Diterima</p>--}}
{{--                                @elseif($activityReport->status === 'rejected')--}}
{{--                                    <p class="status-rejected">Tidak Diterima</p>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            <td>
                                <div class="action-button">
                                    <a href="{{ route('activity-report.download', $activityReport) }}" class="button icon-download">
                                        <span class="bg-download-primary"></span>
                                    </a>
                                    <a href="{{ route('activity-report.show', $activityReport) }}" class="button icon-detail">
                                        <span class="bg-detail-primary"></span>
                                    </a>
                                    @if(auth()->user()->student_organization || auth()->user()->student_activity_unit)
                                        <a href="{{ route('activity-report.edit', $activityReport) }}" class="button icon-edit">
                                            <span class="bg-edit-warning"></span>
                                        </a>
                                        <button class="button icon-delete" data-target="deleteModal" data-id="{{ $activityReport->id }}" onclick="openModal(this)">
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
            {{ $activityReports->links() }}
        </div>
    </div>
    @include('modal.activity-report')

    <script>
        function openModal(element) {
            const modalTarget = element.getAttribute('data-target')
            const modalId = element.getAttribute('data-id')
            const modal = document.getElementById(`${modalTarget}`)
            modal.classList.add('show')
            if (modalTarget.includes('delete')) {
                document.getElementById('buttonDeleteActivityReport').setAttribute('action', '/activity-report/' + modalId)
            }
        }

        function closeModal(modalTarget) {
            document.getElementById(`${modalTarget}`).classList.remove('show')
        }
    </script>
@endsection
