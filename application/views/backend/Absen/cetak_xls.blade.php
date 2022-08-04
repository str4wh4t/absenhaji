@php
use Orm\Absen;
use Carbon\Carbon;
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=". $nama_file . $date_start->format('Y-m-d') . $date_end->format('Y-m-d') .".xls");
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
        $found = false; 
        $terlambat = true; 
        $absen_at = ''; 
        $user_absen_id = null;
        @endphp
        @foreach($period AS $date)
            @foreach($user->absen AS $absen)
                @php 
                $array_user_absen_date_checking = []; 
                $found = false; 
                $terlambat = true; 
                $absen_at = ''; 
                $expired_at = Carbon::createFromFormat('Y-m-d H:i:s',  $absen->expired_at);
                @endphp
                @if($date->isSameDay($expired_at))
                    @php 
                    if(!isset($array_user_absen_date_checking[$user->id])){
                        $array_user_absen_date_checking[$user->id] = [];
                    } 

                    if(in_array($date->format('Y-m-d'),$array_user_absen_date_checking[$user->id])){
                        continue;
                    }

                    $array_user_absen_date_checking[$user->id][] = $date->format('Y-m-d');
                    $found = true; 
                    $userAbsen = $absen->userAbsen()->where('user_id', $user->id)->first();
                    $terlambat = $userAbsen->stts ? false : true;
                    $absen_at = $userAbsen->created_at->format('H:i:s');
                    $user_absen_id = $userAbsen->id;
                    break;
                    @endphp
                @endif
            @endforeach

            @if($found)
                @if($terlambat)
                    <td style="background-color: #ffcccc ;" >{{ $absen_at }}</td>
                @else
                    <td >{{ $absen_at }}</td>
                @endif
            @else
                @php
                $selected_absen = Absen::whereDate('expired_at', $date)->first();
                @endphp
                @if(!empty($selected_absen))
                <td >-</td>
                @else
                <td >-</td>
                @endif
            @endif

        @endforeach
    </tr>
    @php $no++; @endphp
    @endforeach
</table>