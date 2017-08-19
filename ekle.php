<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ekle</title>
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: BanadaPC
 * Date: 18.08.2017
 * Time: 11:54
 */

include "N11.php";

$n11parametre =
    [
        'appKey' => '',
        'appSecret' => ''
    ];

$n11 = new N11($n11parametre);


foreach ($_POST['eklenecek'] as $urunNo) {


//    $kategoriler = $n11->GetTopLevelCategories();
//print_r($kategoriler);

//    $urunler = $n11->GetProductList(10,0);
//var_dump($urunler);

//    $altkategori = $n11->GetSubCategories(1002200);
//    print_r($altkategori);

    // KATEGORİ
// kategori bilgisi içinde belirli kelimeleri aratarak kategori bulmaya çalışır. eğer kategori bilgisi içinde eşleşme yoksa ürün açıklamasında aynı kelimeleri arar.
    if (strstr(strtolower($_POST['urun' . $urunNo][3]), "lys") || strstr(strtolower($_POST['urun' . $urunNo][3]), "ygs")) {
        $urunKategori = 1002200;
    } else if (strstr(strtolower($_POST['urun' . $urunNo][3]), "kpss")) {
        $urunKategori = 1042100;
    } else if (strstr(strtolower($_POST['urun' . $urunNo][3]), "teog")) {
        $urunKategori = 1041111;
    } else if (strstr(strtolower($_POST['urun' . $urunNo][3]), "yds")) {
        $urunKategori = 1041112;
    } else {
        if (strstr(strtolower($_POST['urun' . $urunNo][5]), "lys") || strstr(strtolower($_POST['urun' . $urunNo][3]), "ygs")) {
            $urunKategori = 1002200;
        } else if (strstr(strtolower($_POST['urun' . $urunNo][5]), "kpss")) {
            $urunKategori = 1042100;
        } else if (strstr(strtolower($_POST['urun' . $urunNo][5]), "teog")) {
            $urunKategori = '1041111';
        } else if (strstr(strtolower($_POST['urun' . $urunNo][5]), "yds")) {
            $urunKategori = 1041112;
        } else {
            $urunKategori = 1002197;
        }
        $urunKategori = 1002197;
    }


    //ÜRÜN ÖZELLİKLERİ

    $urunKodu = $_POST['urun' . $urunNo][2];
    $urunBaslik = $_POST['urun' . $urunNo][1];
    $urunAltBaslik = "Hızlı Kargo || Güncel Baskı || Orjinal Ürün Garantisi";
    $urunAciklama = $_POST['urun' . $urunNo][5];
    $urunFiyat = $_POST['urun' . $urunNo][4];
    $urunFiyat = ($urunFiyat * 0.1) + $urunFiyat; //%10 Komisyon
    if ($urunFiyat > 87 && $urunFiyat < 90) {
        $urunFiyat = 90;
    }
    $urunResimURL = $_POST['urun' . $urunNo][0];
    $urunHazirlanmaSuresi = "2";
    $urunIndirimOrani = 0;
    $urunKargoSablon = "Yurtiçi Kargo";



        $saveProduct = $n11->SaveProduct(
            [
                'productSellerCode' => $urunKodu,
                'title' => $urunBaslik,
                'subtitle' => $urunAltBaslik,
                'description' => $urunAciklama,
                'attributes' => '',
                'category' =>
                    [
                        'id' => $urunKategori
                    ],
                'price' => $urunFiyat,
                'currencyType' => 'TL',
                'images' =>
                    [
                        'image' =>
                            [
                                'url' => $urunResimURL,
                                'order' => 1
                            ]
                    ],
                'saleStartDate' => '19/08/2017',
                'saleEndDate' => '19/08/2018',
                'productionDate' => '',
                'expirationDate' => '',
                'productCondition' => '1',
                'preparingDay' => $urunHazirlanmaSuresi,
                'discount' => '',
                'shipmentTemplate' => $urunKargoSablon,
                'stockItems' =>
                    [
                        'stockItem' =>
                            [
                                'quantity' => 15,
                                'sellerStockCode' => $urunKodu,
                                'attributes' =>
                                [
                                        'attribute' => array()
                                ],
                                'optionPrice' => null,
                                'bundle' => null,
                                'mpn' => null,
                                'gtin' => null
                            ]
                    ]
            ]
        );

    }
    var_dump($saveProduct);

?>

</body>
</html>
