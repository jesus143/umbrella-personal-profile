<?php

if(!function_exists(' getBusinessProfilePic')) {
    function getBusinessProfilePic()
    {
        $featured = '';
        $host = "db640728737.db.1and1.com";
        $database = "db640728737";
        $user = "dbo640728737";
        $password = "1qazxsw2!QAZXSW@";

        $businessID = 77514;
        try {
            $WP_CON = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';', $user, $password);
            $WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ERR) {
            echo $ERR->getMessage();
            exit();
        }
        try {
            $QUESTRING_GETNAIMG = "SELECT * FROM wp_user_imguploads";
            $GETNAIMG_RESULT = $WP_CON->query($QUESTRING_GETNAIMG);
            $GETNAIMG_LISTS = $GETNAIMG_RESULT->fetch();
        } catch (PDOException $ERR) {
            echo $ERR->getMessage();
            exit();
        }

        global $featured_image, $status;
        $sql = $WP_CON->prepare('SELECT ui_URL AS url,ui_STATUS AS status FROM wp_user_imguploads WHERE uid_PartnerID = :parnerID');
        $sql->execute(array(':parnerID' => $businessID));
        $result = $sql->fetchObject();
        $status = $result->status;
        if (!empty($result->url)) {
            $featured_image = $result->url;
        }
        switch ($status) {
            case 0  :
                $class_watermark = 'class="water-wrapper water-mark"';
                $featured = $featured_image;

                break;
            case 1  :
                $class_watermark = 'class="water-wrapper"';
                $featured = $featured_image;
                break;
            default :
                $class_watermark = 'class="water-wrapper"';
                //$featured   = get_stylesheet_directory_uri().'/images/default-logo.jpg';
                break;
        }
        //        print "<br>featured image " . $featured;
        //        print "<br>class watermark " . $class_watermark;
        return (!empty($featured)) ? $featured : null;
    }
}