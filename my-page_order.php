<?php require_once("pay.import.php");?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>EX mall</title>
</head>

<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper my-page">
        <section class="menus">
            <div class="my-page_title"><span>MY PAGE</span></div>
            <ul class="navs">
                <a href="my-page_member_pwd.php"><li class="nav" style="width: 165px;"><span>회원정보 수정</span></li></a>
                <a href="my-page_order.php"><li class="nav" style="background-color: rgb(255, 139, 139); width: 165px;"><span>주문배송</span></li></a>
                <li class="nav"><span>쿠폰/적립금</span></li>
                <li class="nav"><span>교환/반품</span></li>
                <li class="nav"><span>좋아요</span></li>
                <li class="nav"><span>최근 본 상품</span></li>
                <li class="nav"><span>회원탈퇴</span></li>
            </ul>
        </section>
        <section class="view">
            <div class="order_title"><span>주문/배송 조회</span></div>
            <section class="table pay">
                <table>
                <?php foreach($result as $key1=>$value){?>
                    <?php $total_price=0;?>
                    <?php $order_contents = json_decode($value["order_contents"]);?>
                    <tr class="order_info_header">
                        <td class="order_date"><span>2022.09.29</span></td>
                        <td class="order_id"><span>주문번호 : <?php echo $result[$key1]['order_id']?></span></td>
                    </tr>
                    <?php foreach($order_contents as $key2=>$order_content){?>
                        <?php $order_info =db_select("select * from contents where content_code= ?", array($order_contents[$key2]->content_code)); ?>
                        <form action="review_write.php" method="post">
                            <tr class="order_content" >
                                <td>
                                    <input type="hidden" name="order_id" value="<?php echo $result[$key1]['order_id']?>"/>
                                    <div class="order_status"><span>주문완료</span></div>
                                    <?php if( $result[$key1]['review'] == "N"){?>
                                        <button class="review_write"> 리뷰 작성하기</button>
                                    <?php }else {?>
                                        <button disabled="disabled" class="review_complete"> 리뷰 작성완료</button>
                                        <?php } ?>
                                </td>
                                <td>
                                    <div class="img_wrapper"><img src="<?php echo $order_info[0]['content_img']?>" alt="" /></div>
                                    <input type="hidden" name="content_img" value="<?php echo $order_info[0]['content_img']?>">
                                </td>
                                <td class="content_info">
                                    <span class="content_options"><?php echo $order_contents[$key2]->content_options;?></span>
                                    <span class="content_name"><?php echo $order_info[0]['content_name']?></span>
                                    <input type="hidden" name="content_options" value="<?php echo $order_contents[$key2]->content_options;?>">
                                    <input type="hidden" name="content_name" value="<?php echo $order_info[0]['content_name']?>">
                                    <input type="hidden" name="content_code" value="<?php echo $order_contents[$key2]->content_code?>">
                                </td>
                                <td>
                                    <?php $price=$order_info[0]['content_price']?>
                                    <?php echo number_format($price)?>원
                                </td>
                                <td>
                                    <?php $amount=$order_contents[$key2]->content_amount;?>
                                    <?php echo number_format($amount)?>개
                                </td>
                                <td>무료배송</td>
                                <td>
                                    <?php $total=$price*$amount ?>
                                    <?php echo number_format($total)?>원
                                </td>
                                <?php $total_price=$total_price+$total?>
                            </tr>
                        </form>
                        
                        
                    <?php } ?>
                    <tr class="order_receipt">
                        <td class="title font_weight">상품금액</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <span class="total_price">
                                <?php echo number_format($total_price)?>원
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            </section>
            
        </section>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>

    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/hot_issue.js"></script>
    <script src="js/member.js"></script>

</body>

</html>