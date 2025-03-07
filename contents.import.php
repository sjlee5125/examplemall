<?php
require_once("inc/db.php");

// 각 카테고리 데이터를 하나의 $result 배열에 통합
$result = [
    'OUTER' => db_select("SELECT * FROM contents WHERE category = 'OUTER'"),
    'TOP' => db_select("SELECT * FROM contents WHERE category = 'TOP'"),
    'KNIT' => db_select("SELECT * FROM contents WHERE category = 'KNIT'"),
    'SHIRTS' => db_select("SELECT * FROM contents WHERE category = 'SHIRTS'"),
    'PANTS' => db_select("SELECT * FROM contents WHERE category = 'PANTS'"),
    'NEW' => db_select("SELECT * FROM contents WHERE category = 'NEW'"),
    'MAIN' => db_select("SELECT * FROM contents WHERE category = 'MAIN'")
];

// 높은 가격순으로 상품 데이터 가져오기
$result_price_H = db_select("SELECT * FROM contents ORDER BY content_price DESC");

// 낮은 가격순으로 상품 데이터 가져오기
$result_price_L = db_select("SELECT * FROM contents ORDER BY content_price ASC");
?>

                    