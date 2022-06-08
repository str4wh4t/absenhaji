<?php 

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Orm\Absen;
use Carbon\Carbon;

function qrgenerate($kode_absen = null)
{
    if (empty($kode_absen)) {
        $kode_absen = rand(10000,99999);
        $absen = Absen::where('kode_absen', $kode_absen)
                        ->whereDate('expired_at', '>', Carbon::now()->toDateTimeString())
                        ->get();
        if ($absen->isNotEmpty()) {
            // DIULANG SAMPAI TIDAK DITEMUKAN LAGI KODE YANG SAMA YANG MASIH AKTIF
            qrgenerate($kode_absen);
        }
    }
    // CREATE QRCODE
    $writer = new PngWriter();

    // Create QR code
    $qrCode = QrCode::create($kode_absen)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(450)
            ->setMargin(5)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

    // Create generic logo
    //     $logo = Logo::create(__DIR__ . '/assets/symfony.png')
    // ->setResizeToWidth(50);

    //     // Create generic label
    //     $label = Label::create('Label')
    // ->setTextColor(new Color(255, 0, 0));

    $qrcode = $writer->write($qrCode);
    $src = $qrcode->getDataUri();

    return compact('kode_absen', 'src');
}