<?php
include './wp-config.php';


//$dt = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
//$timeCurrent = $dt->format('d-m-Y');
//$time_start = strtotime($timeCurrent);
//$time_end = $time_start + 86399;
//
////    print_r($time_start);die;
//$args1 = array(
//    'post_type' => 'dau_gia_cong_thong_t',
//    'post_status' => 'publish',
//    'posts_per_page' => -1,
//);
//$my_query1 = new WP_Query($args1);
//$posts1 = $my_query1->posts;
//
//foreach ($posts1 as $key => $value){
//    $time_post1 = get_field('time_start',$value->ID);
//    $str_post1 = strtotime($time_post1);
////    print_r($time_post1);
////    print_r($str_post1);die();
//
//    if($str_post1 >= $time_start && $str_post1 <= $time_end){
//        wp_set_post_terms($value->ID,28,'danh_muc_cong_thong_tin'); //dang dien ra
//    }
//    elseif ($str_post1 > $time_end){
//        wp_set_post_terms($value->ID,27,'danh_muc_cong_thong_tin'); //da ket thuc
//    }
//    elseif ($str_post1 < $time_start) {
//        wp_set_post_terms($value->ID,29,'danh_muc_cong_thong_tin'); //sap dien ra
//    }
//
//}die;

//UPDATE DANH MUC
//wp_set_post_terms(147,25,'danh_muc_dau_gia');
$dt = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
$timeCurrent = $dt->format('d-m-Y');
$time_start = strtotime($timeCurrent);
$time_end = $time_start + 86399;
$args = array(
    'post_type' => 'dau_gia_hoang_gia',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);
$my_query = new WP_Query($args);
$posts = $my_query->posts;
//print_r($posts);die();
foreach ($posts as $key => $value){
    $time_post = get_field('time_start',$value->ID);
    $str_post = strtotime($time_post);

    if($str_post >= $time_start && $str_post <= $time_end){
        wp_set_post_terms($value->ID,24,'danh_muc_dau_gia');
    }
    elseif ($str_post > $time_end){
        wp_set_post_terms($value->ID,25,'danh_muc_dau_gia');
    }
    elseif ($str_post < $time_start) {
        wp_set_post_terms($value->ID,26,'danh_muc_dau_gia');
    }

}

//CRAWLER DATABASE

function bangCongkhai($id_post)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/portal/propertyInfo?auctionInfoId=' . $id_post,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);
}

function fileCongkhai($id_post)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/portal/viewDetailAuctionInfo?auctionInfoId=' . $id_post,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);
}


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://dgts.moj.gov.vn/portal/search/auction-notice?numberPerPage=50&p=1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response1 = curl_exec($curl);

curl_close($curl);
$decode1 = json_decode($response1);
//print_r($decode1); die;
foreach ($decode1->items as $key => $item) {
    $id_post = $item->id;
    $title_post = $item->propertyName;
    $title_post_sanitize = sanitize_title($title_post);

    $board_post = bangCongkhai($id_post)->items;
    $propertyName = $board_post[0]->propertyName;
    $propertyPlace = $board_post[0]->propertyPlace;
    $strPropertyStartPrice = $board_post[0]->strPropertyStartPrice;
    $strDeposit = $board_post[0]->strDeposit;
    $number_asset = $board_post[0]->propertyAmount;

    $filecongkhai = fileCongkhai($id_post)->listFile;
    $filename1 = $filecongkhai[0]->fileName;
    $filelink1 = $filecongkhai[0]->linkFile;
    $filename2 = $filecongkhai[1]->fileName;
    $filelink2 = $filecongkhai[1]->linkFile;

//    print_r($filecongkhai);die;
    $linkEdit = 'https://dgts.moj.gov.vn/thong-bao-cong-khai-viec-dau-gia/' . $title_post_sanitize . '-' . $id_post . '.html';
    $content = file_get_html($linkEdit);
    $data = $content->find('#generate-content', 0);
//    echo $data; die;
    $get_field = get_field('id_cttdg', 'option');
    $array_id = explode(',', $get_field);
    if (in_array($id_post, $array_id)) {

    } else {
        $args = new WP_Query(array(
            'post_type' => 'dau_gia_cong_thong_t',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'slug_title',
                    'type' => 'CHAR',
                    'value' => $title_post_sanitize,
                    'compare' => '='
                ),
            ),
        ));
        $args_post = $args->posts;
//            print_r($args_post);die;
        if (empty($args_post)) {
            $str_id = $get_field . ',' . $id_post;
            update_field('field_62bac1a1c5214', $str_id, 'option');
            $post_array = array(
                'post_type' => 'dau_gia_cong_thong_t',
                'post_title' => $title_post,
                'post_content' => $data,
                'post_status' => 'publish',
            );
            $post_insert = wp_insert_post($post_array);

            update_field("field_62b28c19ea217", $propertyName, $post_insert); //update ten tai san
            update_field("field_62c63ef39ca16", $propertyPlace, $post_insert); //update đia chi
            update_field("field_62b2c65375bb5", $strPropertyStartPrice, $post_insert); //update gia khoi diem
            update_field("field_62c63ea29ca15", $strDeposit, $post_insert); //update tien dat truoc
            update_field("field_62b2c60f75bb3", $number_asset, $post_insert); //update so luong

            update_field("field_62b28ed3ea222", $filename1, $post_insert); //update ten file
            update_field("field_62b587e24e90c", $filelink1, $post_insert); //update link file
            update_field("field_62ba81e15a297", $title_post_sanitize, $post_insert); //update slug title

            update_field("field_62b678fa051f4", $filename2, $post_insert); //update ten file 1
            update_field("field_62c316dc668d7", $filelink2, $post_insert); //update link file 1
        }

    }
}

//die;


function bangthongtin($id)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/ThongTin/getDetailPropertyInfo?noticeID=' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

function noitiepnhanhoso($id)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/ThongTin/getDetailSelectOrgAuction?id=' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

function tieuchi($id)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/ThongTin/getInfoEditNotice?id=' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

//thông tin bài viết thông báo công khai việc đấu giá
for ($i = 50; $i > 0; $i--) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dgts.moj.gov.vn/ThongTin/getInfoSelectAuctionOrg?district=&endPublishDate=&fromDate=&noticeSub=&numberPerPage=20&ownerFullname=&p=' . $i . '&province=&startPublishDate=&toDate=',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $decode = json_decode($response);


    foreach ($decode->items as $key => $value) {
        $id = $value->id;
        $title = $value->propertyName; //tên bài viết
        $start_time = $value->receiveTimeStart; //thời gia bắt đầu
        $end_time = $value->receiveTimeEnd; //thời gia kết thúc
        $sanitize_title = sanitize_title($title);
        $get_field = get_field('id_post', 'option');
        $array_id = explode(',', $get_field);
        if (in_array($id, $array_id)) {

        } else {
            $args = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'category_name' => 'tin_tuc',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'slug_title',
                        'type' => 'CHAR',
                        'value' => $sanitize_title,
                        'compare' => '='
                    ),
                ),
            ));
            $args_post = $args->posts;
//            print_r($args_post);die;
            if (empty($args_post)) {
                $str_id = $get_field . ',' . $id;
                update_field('field_62b4212113886', $str_id, 'option');
                //thông tin chủ tài sản
                $hoso = noitiepnhanhoso($value->id);
                $hoso_item = $hoso->items[0];

                $ownerFullname = $hoso_item->ownerFullname; //tên chủ sở hữu
                $ownerTel = $hoso_item->ownerTel; //sđt chủ sở hữu
                $ownerAddress = $hoso_item->ownerAddress; //địa chỉ chủ sở hữu
                $addressReceive = $hoso_item->addressReceive; //địa chỉ tiếp nhận hồ sơ
                $fromDate = ($hoso_item->fromDate) / 1000; //thời gian bắt đầu tiếp nhận hồ sơ
                $toDate = ($hoso_item->toDate) / 1000; //thời gian kết thúc tiếp nhận
                $time = $toDate + 86399;

//            print_r($time);die;
                //thông tin bang
                $board = bangthongtin($value->id);

                $name_asset = $board[0]->propertyName; //tên tài sản
                $number = $board[0]->propertyAmount; //số lượng tài sản
                $quality = $board[0]->propertyQuality; //chất lượng tài sản
                $start_price = $board[0]->propertyStartPrice; //giá khởi điểm


                //thong tin tiêu chí lựa chọn tổ chức đấu giá
                $tieuchi = tieuchi($value->id);
                $listFileNotice = $tieuchi->listFileNotice;
                $fileName = $listFileNotice[0]->fileName; //name file 1
                $linkFile = $listFileNotice[0]->linkFile; //link file 1

                $fileName1 = $listFileNotice[1]->fileName; //name file 1
                $linkFile1 = $listFileNotice[1]->linkFile; //link file 1

//            print_r($linkFile);die;

                $post_array = array(
                    'post_type' => 'post',
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_category' => array(5)
                );
                $post_insert = wp_insert_post($post_array);

                update_field("field_62b284d4ea214", $ownerFullname, $post_insert); //update ten chu so huu
                update_field("field_62b96e7cdc32c", $id, $post_insert); //update ID
                update_field("field_62b2c5d475bb2", $ownerAddress, $post_insert); //update dia chi chu so huu
                update_field("field_62b28c19ea217", $name_asset, $post_insert); //update ten tai san
                update_field("field_62b2c60f75bb3", $number, $post_insert); //update so luong tai san
                update_field("field_62b2c62c75bb4", $quality, $post_insert); //update chat luong tai san
                update_field("field_62b2c65375bb5", $start_price, $post_insert);//update gia khoi diem
                update_field("field_62b28d1cea21d", $fromDate, $post_insert); //update thoi gian bat dau tiep nhan
                update_field("field_62b2c6d475bb6", $toDate, $post_insert); //update thoi gian ket thuc tiep nhan
                update_field("field_62b2c71c75bb7", $addressReceive, $post_insert); //update dia diem tiep nhan
                update_field("field_62b2c78375bb8", $ownerTel, $post_insert); //update lien he
                update_field("field_62b56ce25e976", $time, $post_insert); //update thoi gian dien ra dau gia
                update_field("field_62b28ed3ea222", $fileName, $post_insert); //update ten file
                update_field("field_62b587e24e90c", $linkFile, $post_insert); //update link file
                update_field("field_62b678fa051f4", $fileName1, $post_insert); //update ten file 1
                update_field("field_62c316dc668d7", $linkFile1, $post_insert); //update link file 1
                update_field("field_62ba81e15a297", $sanitize_title, $post_insert); //update slug title
            } else {

            }
        }
    }
}


?>