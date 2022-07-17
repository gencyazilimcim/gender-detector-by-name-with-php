<?php
$control = md5(1458); // form kontrol

if(isset($_POST['gendersubmit']) && md5($_POST['gendersubmit']) == $control  ){
    
    $isim = filter_input(INPUT_POST, "GF_isim", FILTER_SANITIZE_STRING) ;
    $nameList = trim(str_replace(' ', '', $isim)); //boşlukları kaldır
    $nameList = explode(",",$nameList); // diziye dönüştürme

    $multiNameControl = count($nameList); // isim adetleri değişkene aktarıyoruz

    foreach ($nameList as &$onlyNameList) {
        $onlyNameList = preg_replace("/[^a-zA-Z]+/", "", $onlyNameList);  //harf dışındaki bütün karakterleri kaldırır.  
    }

    $nameList = implode("&name=",$nameList); // dizilerin arasına "&name=" ekler

    
    $json = file_get_contents('https://api.genderize.io/?name='.$nameList); //belirtilen URL'deki json alıyoruz.

    $data = json_decode($json,true); // json kodlarını diziye aktarıyoruz.

    echo "<br>";
    if ($multiNameControl != 1) { // değişkene aktarılan isim adetlerini burada kontrol ediyoruz.
        foreach ($data as $value) {
            printf("isim: %s — cinsiyet: &percnt;%s  %s — Sıra: %s <br>", $value['name'] , ($value['probability'] * 100) , (($value['gender'] == "male") ? "Erkek" : "Kadın" ) , $value['count'] );
        }
    }else{
        printf("İsim: %s — Cinsiyet: &percnt;%s  %s — Sıra: %s <br>", $data['name'] , ($data['probability'] * 100) , (($data['gender'] == "male") ? "Erkek" : "Kadın" ) , $data['count'] );
    }

}
?>

<!DOCTYPE html>
<html lang="TR-tr">
<head>
    <meta charset="UTF-8">
    <title>PHP ile isimden cinsiyet bulma</title>
</head>
<body>
    <h1>İsimden cinsiyet bulma</h1>
    <form action="" method="post">
        <input type="text" name="GF_isim">
        <input type="hidden" name="gendersubmit" value="1458">
        <input type="submit" value="Gönder">
    </form>
</body>
</html>
