<?php

require 'koneksi.php' ;
if (isset($_GET['id'])){
    $p = $_GET['id'];

    $cart = $_SESSION['cart'] ;

    foreach($cart as $i => $val){
        if ($val['proid'] == $p){
            unset($cart[$i]);
        }
    }

    $_SESSION['cart'] = $cart ;

    header("Location:?page=penjualan");

} else {
    header("Location:?page=penjualan");
}

?>