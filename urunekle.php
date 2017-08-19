<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>N11 Entegrasyon Paneli</title>
</head>
<style>
    table, td, th {
        border: 1px solid #ddd;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    textarea {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<body>
<form id="urun1" name="urun1" method="post" action="ekle.php">
<?php
/**
 * Created by PhpStorm.
 * User: BanadaPC
 * Date: 18.08.2017
 * Time: 14:52
 */

$gosterimSayisi = $_POST['urunsayi'];

$XMLVeri = simplexml_load_file("http://banadakitap.com/xml/sitemap_product_1.xml");

$linkarr = array();
$linkler = array();

foreach ($XMLVeri as $xlmparse) $linkarr[] = $xlmparse;

$linkarr = array_reverse($linkarr);
$key = "loc";

foreach ($linkarr as $anahtar) {
    array_push($linkler, (string)$anahtar->$key);
}

$urunbilgileri = array();
$urunler = array();

if ($XMLVeri == false)
{
    echo "XML Yüklenirken Hata Oluştu: ";
    foreach (libxml_get_errors() as $error)
    {
        echo "<br>", $error->message;
    }
}else
{
    for($i = 0; $i < $gosterimSayisi; $i++)
    {
        $url = file_get_contents($linkler[$i]);

        $baslik = explode("<div style='float:left;'>", $url);
        $baslik = explode("</div>", $baslik[1]);
        $baslik = $baslik[0];

        $resim = explode("<meta property='og:image' content='", $url);
        $resim = explode("?revision=", $resim[1]);
        $resim = $resim[0];

        $stokKodu = explode("<tr id=\"stok_kodu\" class=\"rowspan row9\">", $url);
        $stokKodu = explode("</tr>", $stokKodu[1]);
        $stokKodu = explode("<td class=\"col3\">", $stokKodu[0]);
        $stokKodu = explode("</td>", $stokKodu[1]);
        $stokKodu = $stokKodu[0];

        $aciklama = explode("<div class=\"ProductDetail\">", $url);
        $aciklama = explode("<div style=\"display:none;\" id=\"contents_2\">", $aciklama[1]);
        $aciklama = "<div>".$aciklama[0];

        $kategori = explode("<span itemprop='category' content='", $url);
        $kategori = explode("'>", $kategori[1]);
        $kategori = $kategori[0];

        $fiyat = explode("id=\"kdv_dahil_cevrilmis_fiyat\">", $url);
        $fiyat = explode(",00 TL", $fiyat[1]);
        $fiyat = trim($fiyat[0]);
        $urunbilgileri =
            [
                'urunBaslik' => $baslik,
                'urunResim' => $resim,
                'urunStokNo' => $stokKodu,
                'urunAciklama' => htmlentities($aciklama),
                'urunKategori' => $kategori,
                'urunFiyat' => $fiyat
            ];

        array_push($urunler, $urunbilgileri);
    }
}

//echo $urunler[0]['urunBaslik'];
$kontrol = 1;
foreach ($urunler as $item) {
    echo
    '
    <table align="center">
    <tr>
        <td><input type="checkbox" name="eklenecek[]" value="'.$kontrol.'" checked /></td>
        <td style="height: 200px; width: 150px">
            <img src="'.$item['urunResim'].'" height="150" width="200"/>
        </td>
        <td style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
            '.$item['urunBaslik'].'
        </td>
        <td style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
            '.$item['urunStokNo'].'
        </td>
        <td style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;;">
            '.$item['urunKategori'].'
        </td>
        <td style="white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
            '.$item['urunFiyat'].'
        </td>
    </tr>
</table>

<table align="center">    <tr>
    <td style="width: 800px"><textarea style="height: 200px; width: 700px">
        '.$item['urunAciklama'].'
        </textarea>
    </td>
</tr></table>
    ';
    // $item['urunBaslik']."<br>";
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunResim'].'" />';
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunBaslik'].'" />';
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunStokNo'].'" />';
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunKategori'].'" />';
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunFiyat'].'" />';
    echo '<input type="hidden" name="urun'.$kontrol.'[]" value="'.$item['urunAciklama'].'" />';
    $kontrol++;

}
?>

    <div style="text-align:center">
        <input style="width: 300px; margin: 0 auto;" type="submit" name="gonder" value="Gönder" />
    </div>
</body>
</html>
