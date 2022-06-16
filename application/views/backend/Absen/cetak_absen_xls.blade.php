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
        @php $array_user_absen_date_checking = []; @endphp
        @foreach($period AS $date)
            @php $found = false; $terlambat = false; @endphp
            @foreach($user->absen AS $user_absen)
                @if($date->isSameAs('Y-m-d', $user_absen->created_at))
                    @php 
                    if(!isset($array_user_absen_date_checking[$user->id])){
                        $array_user_absen_date_checking[$user->id] = [];
                    } 

                    if(in_array($date->format('Y-m-d'),$array_user_absen_date_checking[$user->id])){
                        continue;
                    }

                    
                    $array_user_absen_date_checking[$user->id][] = $date->format('Y-m-d');
                    $found = true; 
                    $terlambat = $user_absen->stts;
                    @endphp
                @endif
            @endforeach

            @if($found)
                @if($terlambat)
                    <td >{{ 'terlambat' }}</td>
                @else
                    <td >{{ 'ok' }}</td>
                @endif
            @else
                <td >{{ '' }}</td>
            @endif

        @endforeach
    </tr>
    @php $no++; @endphp
    @endforeach
</table>