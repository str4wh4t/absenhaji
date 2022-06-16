@php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=". $nama_file .".xls");
@endphp
<table border="1">
    <tr>
        <td rowspan="2" ><b>No</b></td>
        <td rowspan="2" ><b>Nama</b></td>
        <td rowspan="2" ><b>Bidang</b></td>
        <td rowspan="2" ><b>Instansi</b></td>
        <td rowspan="2" ><b>Jabatan</b></td>
        <td rowspan="2" ><b>Struktural</b></td>
        <td colspan="{{ $period->count() }}" ><b>Absensi</b></td>
    </tr>
    <tr>
        @foreach($period AS $date)
            <td >{{ $date->format('Y-m-d') }}</td>
        @endforeach
    </tr>
    @php $no = 1; @endphp
    @foreach($user_list as $user)
    <tr>
        <td >{{ $no }}</td>
        <td >{{ $user->fullname }}</td>
        <td >{{ $user->bidang->bidangname ?? '-' }}</td>
        <td >{{ $user->instansi->instansiname ?? '-' }}</td>
        <td >{{ $user->jabatan->jabatanname ?? '-' }}</td>
        <td >{{ $user->struktural->jabatanname ?? '-' }}</td>
        @php 
        $array_user_absen_date_checking = []; 
        $found = false; $terlambat = true; $absen_at = ''; 
        @endphp
        @foreach($period AS $date)
            @foreach($user->absen AS $absen)
                @php 
                $array_user_absen_date_checking = []; 
                $found = false; $terlambat = true; $absen_at = ''; 
                @endphp
                @if($date->isSameAs('Y-m-d', $absen->created_at->format('Y-m-d')))
                    @php 
                    if(!isset($array_user_absen_date_checking[$user->id])){
                        $array_user_absen_date_checking[$user->id] = [];
                    } 

                    if(in_array($date->format('Y-m-d'),$array_user_absen_date_checking[$user->id])){
                        continue;
                    }

                    $array_user_absen_date_checking[$user->id][] = $date->format('Y-m-d');
                    $found = true; 
                    $terlambat = $absen->userAbsen()->where('user_id', $user->id)->first()->stts;
                    $absen_at = $absen->userAbsen()->where('user_id', $user->id)->first()->created_at->format('H:i:s');
                    break;
                    @endphp
                @endif
            @endforeach

            @if($found)
                @if($terlambat)
                    <td >{{ $absen_at }}</td>
                @else
                    <td >{{ $absen_at }}</td>
                @endif
            @else
                <td >{{ '-' }}</td>
            @endif

        @endforeach
    </tr>
    @php $no++; @endphp
    @endforeach
</table>