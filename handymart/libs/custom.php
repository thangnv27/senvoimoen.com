<?php
if(!function_exists('CurrentDate')){
    function CurrentDate($format = 'Y-m-d H:i:s', $strtotime = null) {
        if ($strtotime) {
            return date($format, strtotime("{$strtotime} day"));
        } else {
            return date($format);
        }
    }
}
/**
 * Get current request url
 * @return tring
 */
function getCurrentRquestUrl(){
    $prefix = "http://";
    if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on"){
        $prefix = "https://";
    }
    return $prefix . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}
/*----------------------------------------------------------------------------*/
# Cities  in Vietnamese
/*----------------------------------------------------------------------------*/
if(!function_exists('vn_city_list')){
    function vn_city_list(){
        return array(
            "An Giang", "Bà Rịa - Vũng Tàu", "Bạc Liêu", "Bắc Kạn", "Bắc Giang", "Bắc Ninh", "Bến Tre", "Bình Dương",
            "Bình Định", "Bình Phước", "Bình Thuận", "Cà Mau", "Cao Bằng", "Cần Thơ", "Đà Nẵng", "Đắk Lắk", "Đắk Nông",
            "Đồng Nai", "Đồng Tháp", "Điện Biên", "﻿Gia Lai", "Hà Giang", "Hà Nam", "Hà Nội", "Hà Tĩnh", "Hải Dương", 
            "Hải Phòng", "Hòa Bình", "Hậu Giang", "Hưng Yên", "TP. Hồ Chí Minh", "Khánh Hòa", "Kiên Giang", "Kon Tum", 
            "Lai Châu", "Lào Cai", "Lạng Sơn", "Lâm Đồng", "Long An", "Nam Định", "Nghệ An", "Ninh Bình", "Ninh Thuận", 
            "Phú Thọ", "Phú Yên", "Quảng Bình", "Quảng Nam", "Quảng Ngãi", "Quảng Ninh", "Quảng Trị", "Sóc Trăng", 
            "Sơn La", "Tây Ninh", "Thái Bình", "Thái Nguyên", "Thanh Hóa", "Thừa Thiên - Huế", "Tiền Giang", 
            "Trà Vinh", "Tuyên Quang", "Vĩnh Long", "Vĩnh Phúc", "Yên Bái", "Nơi khác", 
        );
    }
}

if(!function_exists('ppo_convert_object_to_array')){
    /**
     * Convert an object to array
     * @param Object $object
     * @return array
     */
    function ppo_convert_object_to_array($object){
        $array = array();
        foreach ($object as $member => $data){
            $array[$member] = $data;
        }
        return $array;
    }
}
if(!function_exists('convert_number_to_words')){
    /**
     * Convert number to words
     * @param Integer $number
     * @return String
     */
    function convert_number_to_words($number, $show = false){
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'không',
            1 => 'một',
            2 => 'hai',
            3 => 'ba',
            4 => 'bốn',
            5 => 'năm',
            6 => 'sáu',
            7 => 'bảy',
            8 => 'tám',
            9 => 'chín',
            10 => 'mười',
            11 => 'mười một',
            12 => 'mười hai',
            13 => 'mười ba',
            14 => 'mười bốn',
            15 => 'mười năm',
            16 => 'mười sáu',
            17 => 'mười bảy',
            18 => 'mười tám',
            19 => 'mười chín',
            20 => 'hai mươi',
            30 => 'ba mươi',
            40 => 'bốn mươi',
            50 => 'năm mươi',
            60 => 'sáu mươi',
            70 => 'bảy mươi',
            80 => 'tám mươi',
            90 => 'chín mươi',
            100 => 'trăm',
            1000 => 'nghìn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'nghìn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }
        
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        if($show){
            echo $string;
        }
        return $string;
    }
}
/**
 * Remove special char
 * 
 * @param string $string
 * @return string
 */
function removeSpecialChar($string){
    $specialChar = array("!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "-", "+", "=", ";", ":", "'", "\"", ",", ".", "/", "<", ">", "?", );
    foreach ($specialChar as $key => $value) {
        $pos = strpos($string, $value);
        if($pos){
            $string = str_replace(substr($string, $pos, 2), ucwords(substr($string, $pos+1, 1)), $string);
        }
    }
    return $string;
}
/**
 * Generate random string 
 * 
 * @param integer $length default length = 32
 * @return string
 */
function random_string($length = 32){
    $key = '';
    $rand = str_split(strtolower(md5(time() * microtime())));
    $keys = array_merge(range(0, 9), range('a', 'z'));
    $keys = array_merge($keys, $rand);

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    
    return $key;
}
/**
 * Replaces url entities with -
 *
 * @param string $fragment
 * @return string
 */
function clean_entities($fragment){
    $translite_simbols = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    );
    $fragment = preg_replace($translite_simbols, $replace, $fragment);
    $fragment = preg_replace('/(-)+/', '-', $fragment);

    return $fragment;
}

/**
 * Read properties file
 * 
 * @param type $filename path to properties file
 * @return array key=>value
 */
function readProperties($filename){
    $list = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $language = array();
    foreach ($list as $lang) {
        $arr = explode('=', $lang);
        if (count($arr) == 2) {
            $language[trim($arr[0])] = trim($arr[1]);
        }
    }
    return $language;
}
/**
 * Write text to file
 *
 * @param string $filename
 * @param string $text
 * @param string $mode example: w+
 */
function write_to_file($filename, $text, $mode) {
    $fp = fopen($filename, $mode);
    fputs($fp, "$text\n");
    fclose($fp);
}

/**
 * Remove BBCODE from text document
 * @param string $code text document
 * @return string text document
 */
function removeBBCode($code){
    $code = preg_replace("/(\[)(.*?)(\])/i", '', $code);
    $code = preg_replace("/(\[\/)(.*?)(\])/i", '', $code);
    $code = preg_replace("/http(.*?).(.*)/i", '', $code);
    $code = preg_replace("/\<a href(.*?)\>/", '', $code);
    $code = preg_replace("/:(.*?):/", '', $code);
    $code = str_replace("\n", '', $code);
    return $code;
}
/**
 * Get short content from full contents
 * 
 * @param integer $length 
 * @return string
 */
function get_short_content($contents, $length){
    $short = "";
    $contents = strip_tags($contents);
    if (strlen($contents) >= $length) {
        $text = explode(" ", substr($contents, 0, $length));
        for ($i = 0; $i < count($text) - 1; $i++) {
            if($i == count($text) - 2){
                $short .= $text[$i];
            }else{
                $short .= $text[$i] . " ";
            }
        }
        $short .= "...";
    } else {
        $short = $contents;
    }
    return $short;
}
/**
 * Video Youtube
 */
function shortcode_youtube($content = NULL, $width = 300, $height = 300){
    if ("" === $content)
        return 'No YouTube Video ID Set';
    $id = $text = $content;
    return '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/' . $id . '"></param><embed src="http://www.youtube.com/v/' . $id . '" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed></object>';
}
/**
 * Tests a string to see if it's a valid email address
 *
 * @param	string	Email address
 *
 * @return	boolean
 */
function is_valid_email($email) {
//    return filter_var($email, FILTER_VALIDATE_EMAIL);
//    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$^", $email);
    return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>@,;]+\.+[a-z]{2,6}))$#si', $email);
}
/**
 * Tests a string to see if it's a valid ID Card
 *
 * @param	string	ID Card
 *
 * @return	boolean
 */
function is_ID_Card($id_card) {
    return preg_match('#^[0-9]{9,12}$#si', $id_card);
}

/**
 * Tests a string to see if it's a valid phone number
 *
 * @param	string	$phone Phone number
 *
 * @return	boolean
 */
function is_valid_phone_number($phone) {
    return preg_match("#^[0-9[:space:]\+\-\.\(\)]+$#si", $phone);
}
/**
 * Display with <pre> tag on browser
 * @param All format $value
 */
function preTag($value) {
    if (is_string($value)) {
        echo "<pre>";
        echo($value);
        echo "</pre>";
    } else {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }
}
/**
 * Init display error messages
 */
function myDebug(){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);
}