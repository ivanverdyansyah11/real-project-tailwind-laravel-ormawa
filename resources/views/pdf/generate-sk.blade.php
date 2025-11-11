<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SK Panitia {{$event->name}}</title>
    <style>
        @font-face {
            font-family: 'xd-prime-regular';
            src: url("{{ public_path('assets/font/xd-prime/XDPrime-Regular.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'xd-prime-bold';
            src: url("{{ public_path('assets/font/xd-prime/XDPrime-Bold.ttf') }}") format('truetype');
        }

        * {
            font-family: 'xd-prime-regular' !important;
        }
    </style>
</head>
<body>
<img src="{{public_path('assets/image/event/' . $event->image_path)}}" alt="Logo Path" style="width: 120px !important; height: 120px !important;">
<p style="text-transform: uppercase; text-align: center; font-family: 'xd-prime-bold' !important;">STRUKTUR KEPANITIAAN <br> {{$event->name}}</p>

<table>
    <tr>
        <td style="font-size: 0.875rem; padding: 4px;">PELINDUNG</td>
        <td style="font-size: 0.875rem; padding: 4px;">: {{$event->protector}}</td>
    </tr>
    <tr>
        <td style="font-size: 0.875rem; padding: 4px;">PENANGGUNG JAWAB</td>
        <td style="font-size: 0.875rem; padding: 4px;">: {{$event->responsible_person}}</td>
    </tr>
    <tr>
        <td style="font-size: 0.875rem; padding: 4px;">KETUA STEERING COMMITTEE</td>
        <td style="font-size: 0.875rem; padding: 4px;">: {{$event->steering_committee_chair}}</td>
    </tr>
</table>

<table style="width: 100%; margin-top: 24px;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black; text-align: center; background-color: green; color: white;">Jabatan</td>
        <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black; text-align: center; background-color: green; color: white;">Nama Lengkap</td>
        <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black; text-align: center; background-color: green; color: white;">NIM</td>
    </tr>
    @foreach($eventRecruitments as $eventRecruitment)
        <tr>
            <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black;">{{$eventRecruitment->event_division->name}}</td>
            <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black;">{{$eventRecruitment->student_name}}</td>
            <td style="font-size: 0.875rem; padding: 6px; border: 1px solid black;">{{$eventRecruitment->student_code}}</td>
        </tr>
    @endforeach
</table>

<table style="width: 100%; margin-top: 120px;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 80%;"></td>
        <td style="width: 80%;"></td>
        <td style="width: 100%;">
            <div style="position: relative;">
                <p style="position: relative; font-size: 0.913rem; line-height: 100%; margin: 0 !important; padding: 0; width: fit-content !important;">Ketua Steering Committee,</p>
                <p style="position: relative; font-size: 0.913rem; line-height: 100%; margin: 0 !important; padding: 0; width: fit-content !important;">Kepala Program Studi Sistem Informasi</p>
                <p style="position: relative; font-size: 0.913rem; line-height: 100%; margin: 0 !important; margin-top: 120px !important; padding: 0; width: fit-content !important;">{{$event->steering_committee_chair}}</p>
{{--                <img src="{{public_path('assets/image/sample-ttd.png')}}" alt="Sample TTD" style="width: 240px !important; position: absolute; bottom: 88%; left: -10%;">--}}
            </div>
        </td>
    </tr>
</table>
</body>
</html>
