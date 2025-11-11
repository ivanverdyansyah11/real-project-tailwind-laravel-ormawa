@extends('template.homepage')

@section('content')
    <section class="detail-ormawa container mt-[44px] lg:mt-[56px] flex flex-col md:flex-row gap-[20px] lg:gap-[32px]">
        <img
            src="{{ asset('assets/image/student-organization/' . $studentOrganization->image_path) }}"
             alt="Ormawa Image"
             class="w-[60%] md:w-[200px] md:h-[200px] lg:w-[240px] lg:h-[240px] xl:w-[300px] xl:h-[300px] rounded-[4px] aspect-square object-cover border border-light/[0.12]"
        >
        <div class="ormawa-detail w-full">
            <h6 class="subtitle">Profile Ormawa</h6>
            <h2 class="title">Tentang {{ $studentOrganization->name }}</h2>
            <div class="data-detail flex items-center gap-[8px] mb-[16px] lg:mb-[24px]">
                <p class="text-[0.875rem] text-primary">Dari {{ $studentOrganization->name }}</p>
                <span class="w-[6px] h-[6px] aspect-square rounded-full bg-light/[0.42]"></span>
                <p class="text-light/[0.42] text-[0.875rem]">{{ \Carbon\Carbon::parse($studentOrganization->created_at)->translatedFormat('j F Y, g.i A') }}</p>
            </div>
            <p class="description">{{ $studentOrganization->description }}</p>
        </div>
    </section>
    @if($studentOrganization->student_organization_structures->count() > 0 || $studentOrganization->student_organization_achievements->count() > 0)
        <div class="gap-[100px] md:gap-[110px] lg:gap-[120px] flex flex-col py-[100px] md:py-[110px] lg:py-[120px] bg-dark-900">
            @if($studentOrganization->student_organization_structures->count() > 0)
                <section class="structure-ormawa container">
                    <div class="section-header">
                        <h2 class="title">Struktur Organisasi Ormawa</h2>
                    </div>
                    <div class="section-content content-gap grid grid-cols-2 lg:grid-cols-4 gap-[16px] lg:gap-[20px]">
                        @foreach($studentOrganization->student_organization_structures as $StudentOrganizationStructure)
                            <div class="card-structure bg-dark-700 rounded-[3px] overflow-hidden flex flex-col">
                                <img src="{{ $StudentOrganizationStructure->profile_path ? asset('assets/image/structure/' . $StudentOrganizationStructure->profile_path) : 'https://placehold.co/48x48?text=Image+Not+Found' }}" alt="Image Ormawa" class="structure-image w-full aspect-video object-cover">
                                <div class="structure-content p-[16px] lg:p-[20px]">
                                    <h4 class="content-title text-[0.913rem] lg:text-[1rem] font-xd-prime-medium leading-[112%] mb-[4px]">{{ $StudentOrganizationStructure->student_name }}</h4>
                                    <p class="content-description text-[0.813rem] text-light/[0.62]">{{ $StudentOrganizationStructure->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
            @if($studentOrganization->student_organization_achievements->count() > 0)
                <section class="structure-achievement container">
                    <div class="section-header">
                        <h2 class="title">Prestasi Organisasi Mahasiswa</h2>
                    </div>
                    <div class="section-content content-gap grid grid-cols-2 lg:grid-cols-4 gap-[16px] lg:gap-[20px]">
                        @foreach($studentOrganization->student_organization_achievements as $StudentOrganizationAchievement)
                            <div class="structure-content bg-dark-700 rounded-[3px] overflow-hidden p-[16px] lg:p-[20px]">
                                <h4 class="content-title text-[0.913rem] lg:text-[1rem] font-xd-prime-medium leading-[112%] mb-[6px] lg:mb-[8px]">{{ $StudentOrganizationAchievement->name }}</h4>
                                <p class="content-description text-[0.875rem] text-light/[0.62]">{{ $StudentOrganizationAchievement->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    @endif
    @if($studentOrganization->student_organization_visions->count() > 0 || $studentOrganization->student_organization_missions->count() > 0)
        <section class="ormawa container" id="ormawa ukm">
            <div class="section-header">
                <h2 class="title">Visi & Misi Organisasi Mahasiswa</h2>
            </div>
            <div class="section-content content-gap !grid-cols-1">
                @if($studentOrganization->student_organization_visions->count() > 0)
                    <div class="content-card">
                        <h3 class="card-title">VISI</h3>
                        @foreach($studentOrganization->student_organization_visions as $i => $studentOrganizationVision)
                            <p class="card-list">
                                <span class="list-wrapper">
                                    <span class="list-number">{{ $i + 1 }}</span>
                                    <span class="list-value">{{ $studentOrganizationVision->name }}</span>
                                </span>
                            </p>
                        @endforeach
                    </div>
                @endif
                @if($studentOrganization->student_organization_missions->count() > 0)
                    <div class="content-card">
                        <h3 class="card-title">MISI</h3>
                        @foreach($studentOrganization->student_organization_missions as $i => $studentOrganizationMission)
                            <p class="card-list">
                                <span class="list-wrapper">
                                    <span class="list-number">{{ $i + 1 }}</span>
                                    <span class="list-value">{{ $studentOrganizationMission->name }}</span>
                                </span>
                            </p>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
    @if($studentOrganization->student_organization_divisions->count() > 0)
        <section class="accordion container" id="ormawa ukm">
            <div class="section-header">
                <h2 class="title">Divisi Beserta Tugas</h2>
            </div>
            <div class="section-content content-gap flex flex-col gap-[12px]">
                @foreach($studentOrganization->student_organization_divisions as $i => $studentOrganizationDivision)
                    <div class="accordion-item flex flex-col rounded-[2px] overflow-hidden">
                        <button type="button" class="item-header flex items-center gap-[8px] lg:gap-[12px] bg-primary py-[6px] px-[6px] pe-[20px] lg:pe-[24px] justify-between group">
                            <span class="header-number w-[42px] h-[42px] aspect-square rounded-[2px] bg-dark-800 leading-[42px] text-center text-[1.125rem] font-xd-prime-medium text-primary">{{ $i + 1 }}</span>
                            <span class="text-[1rem] !font-xd-prime-medium text-dark-800 uppercase w-full text-start">{{ $studentOrganizationDivision->name }}</span>
                            <span class="w-[14px] h-[12px] bg-cover bg-center bg-no-repeat bg-arrow-right-dark group-hover:translate-x-[4px]"></span>
                        </button>
                        <div class="item-body p-[20px] lg:p-[24px] bg-dark-700 hidden flex-col gap-[20px]">
                            <div class="wrapper flex flex-col gap-[4px]">
                                <p class="body-definition text-[0.913rem] text-light">Pengertian:</p>
                                <p class="body-definition text-[0.913rem] text-light/[0.62]">{{ $studentOrganizationDivision->definition }}</p>
                            </div>
                            <div class="wrapper flex flex-col gap-[4px]">
                                <p class="body-definition text-[0.913rem] text-light">Tugas:</p>
                                @foreach($studentOrganizationDivision->student_organization_division_tasks as $studentOrganizationDivisionTask)
                                    <p class="body-definition text-[0.913rem] text-light/[0.62] flex items-center gap-[4px]">
                                        <span class="w-[5px] h-[5px] rounded-full aspect-square bg-light/[0.24]"></span>
                                        {{ $studentOrganizationDivisionTask->name }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    <section class="program container" id="program">
        <div class="section-header">
            <h2 class="title">Program Kerja ORMAWA</h2>
        </div>
        <div class="section-content content-gap swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach($studentOrganization->student_organization_programs as $studentOrganizationProgram)
                    <div class="swiper-slide">
                        <div class="card-program">
                            <img src="{{ $studentOrganizationProgram->image_path ? asset('assets/image/program/' . $studentOrganizationProgram->image_path) : 'https://placehold.co/48x48?text=Image+Not+Found' }}" alt="Image Ormawa" class="program-image">
                            <div class="program-content">
                                <h4 class="content-title">{{ $studentOrganizationProgram->name }}</h4>
                                <h6 class="content-author">{{ $studentOrganizationProgram->student_organization->abbreviation }}</h6>
                                <p class="content-description">{{ $studentOrganizationProgram->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @if($eventTrackRecords->count() > 0)
        <section class="program container" id="program">
            <div class="section-header">
                <h2 class="title">Rekam Jejak ORMAWA</h2>
            </div>
            <div class="section-content content-gap swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($eventTrackRecords as $eventTrackRecord)
                        <div class="swiper-slide">
                            <div class="card-program">
                                <img src="{{ $eventTrackRecord->image_path ? asset('assets/image/track-record/' . $eventTrackRecord->image_path) : 'https://placehold.co/48x48?text=Image+Not+Found' }}" alt="Image Ormawa" class="program-image">
                                <div class="program-content">
                                    <h4 class="content-title">{{ $eventTrackRecord->title }}</h4>
                                    <h6 class="content-author">{{ $eventTrackRecord->year }}</h6>
                                    <p class="content-description">{{ $eventTrackRecord->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const accordionItems = document.querySelectorAll('.accordion-item .item-header');

        accordionItems.forEach(accordionItem => {
            accordionItem.addEventListener('click', function () {
                const parent = accordionItem.parentElement;
                const itemBody = parent.querySelector('.item-body');
                itemBody.classList.toggle('hidden');
                itemBody.classList.toggle('flex');
            });
        });

        let swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
            },
        });
    </script>
@endsection
