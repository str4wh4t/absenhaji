<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=". $nama_file .".xls");

// $mulai = indo_date(strftime('%A, %d %B %Y %H:%M:%S', strtotime($ujian->tgl_mulai)));
// $selesai = empty($ujian->terlambat) ? '-' : indo_date(strftime('%A, %d %B %Y %H:%M:%S', strtotime($ujian->terlambat)));

// $t = [];

// $ujian_topik = $ujian->topik()->groupBy('id')->get();
// foreach($ujian_topik AS $topik){
//     $t[] = $topik->nama_topik;
//     $t = array_unique($t);
// }
// $str_topik = implode(',', $t);

// $min_nilai = number_format($nilai->min_nilai,2,'.', '') ;
// $max_nilai = number_format($nilai->max_nilai,2,'.', '') ;
// $rata_rata_ujian = number_format($nilai->avg_nilai,2,'.', '') ;

?>
<table border="1">
    <tr>
        <td rowspan="2" ><b>No</b></td>
        <td rowspan="2" ><b>Nama</b></td>
        <td rowspan="2" ><b>Bidang</b></td>
        <td rowspan="2" ><b>Instansi</b></td>
        <td rowspan="2" ><b>Jabatan</b></td>
        <td rowspan="2" ><b>Struktural</b></td>
        <td colspan="<?= $period->count() ?>" ><b>Absensi</b></td>
    </tr>
    <tr>
        <?php foreach($period AS $date): ?>
            <td ><?= $date->format('Y-m-d') ?></td>
        <?php endforeach; ?>
    </tr>
    <?php
    $no = 1;
    foreach($user_list as $user) {
        // $nilai_bobot_benar = number_format($row['nilai_bobot_benar'] / 3,2,'.', '') ;
        // $nilai_bobot_benar = number_format($row['nilai_bobot_benar'],2,'.', '') ;
        // $hasil = number_format($row['nilai'],2,'.', '') ;
    ?>

    <tr>
        <td ><?= $no ?></td>
        <td ><?= $user->fullname ?></td>
        <td ><?= $user->bidang->bidangname ?? '-' ?></td>
        <td ><?= $user->instansi->instansiname ?? '-' ?></td>
        <td ><?= $user->jabatan->jabatanname ?? '-' ?></td>
        <td ><?= $user->struktural->jabatanname ?? '-' ?></td>
        <?php $array_user_absen_date_checking = [];  ?>
        <?php foreach($period AS $date): ?>
            <?php $found = false; $terlambat = false; foreach($user->absen AS $user_absen): ?>
                <?php // if($date->format('Y-m-d') == $user_absen->created_at->format('Y-m-d')): ?>
                <?php if($date->isSameAs('Y-m-d', $user_absen->created_at)): ?>
                    <?php 
                    if(!isset($array_user_absen_date_checking[$user->id])){
                        $array_user_absen_date_checking[$user->id] = [];
                    } 

                    if(in_array($date->format('Y-m-d'),$array_user_absen_date_checking[$user->id])){
                        continue;
                    }

                    ?>
                    <?php $array_user_absen_date_checking[$user->id][] = $date->format('Y-m-d'); ?>
                    <?php $found = true; $terlambat = $user_absen->stts; ?>
                    <?php else: ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if($found): ?>
                <?php if($terlambat): ?>
                    <td ><?= 'terlambat' ?></td>
                <?php else: ?>
                    <td ><?= 'ok' ?></td>
                <?php endif; ?>
            <?php else: ?>
                <td ><?= '' ?></td>
            <?php endif; ?>

        <?php endforeach; ?>
    </tr>
    <?php $no++; } ?>
</table>