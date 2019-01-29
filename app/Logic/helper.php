<?php
function front_url($route)
{
    return url('front/' . $route);
}

function get_current_locale()
{
    return \Illuminate\Support\Facades\App::getLocale();
}

function locales()
{
    $arr = [];
    foreach (\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $key => $value) {
        $arr[$key] = $value['native'];
    }
    return $arr;
}

function words_count($str)
{
    return count(explode(' ', $str));
}

function is_auth()
{
    return auth()->check();
}

function settings_object()
{
    return new \App\Setting();
}

function sub_words($phrase, $max_words, $index = 0)
{
    $phrase_array = explode(' ', $phrase);
    if (count($phrase_array) > $max_words && $max_words > 0)
        $phrase = implode(' ', array_slice($phrase_array, $index, $max_words)) . ' ';
    return $phrase;
}

function replace_space_str($str)
{
    return str_replace(' ', '-', $str);
}

function get_total_cart()
{
    return \Darryldecode\Cart\Facades\CartFacade::session(request()->cookie('customer_id'))->getTotal();
}

function get_diff_between_dates($start, $end)
{
    $startDate = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $start);
    $endDate = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $end);
    return $endDate->diff($startDate);
}

function get_about_info($type, $is_icon = true)
{
    switch ($type) {
        case 1 :
            return ($is_icon) ? 'icon flaticon-target' : 'OUR MISSION';
        case 2 :
            return ($is_icon) ? 'icon flaticon-eye' : 'OUR VISION';
        case 3 :
            return ($is_icon) ? 'icon flaticon-envelope' : 'OUR MESSAGE';
    }
    return '';
}

function get_all_album_cats()
{
    return \App\AlbumCategory::all();
}

function ajax_render_view($view, $data)
{
    try {
        return view($view, $data)->render();
    } catch (\Throwable $e) {
    }
    return [''];
}

function get_constant_value($key)
{
    $constant = new \App\Setting();
    return $constant->valueOf($key);
}

function panel_url($route)
{
    return url('panel/' . $route);
}

function image_url($img, $size = '')
{
    return (!empty($size)) ? url('image/' . $size . '/' . $img) : url('image/' . $img);
}


function file_url($file)
{
    return url('file/' . $file);
}

function get_date_from_timestamp($timestamp)
{
    return format_timestamp_date($timestamp, 'Y-m-d');
}

function get_time_from_timestamp($timestamp)
{
    return format_timestamp_date($timestamp, 'H:i');
}

function format_timestamp_date($timestamp, $format)
{
    return (isset($timestamp) && isset($format)) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format($format) : '';
}


function format_12_to_24($input)
{
    return date("H:i", strtotime($input));
}

function format_24_to_12($input)
{
    return date("g:i a", strtotime($input));
}


function get_social_icon($social)
{
    switch ($social) {
        case 'facebook' :
            return 'icon-facebook';
        case 'twitter' :
            return 'icon-twitter';
        case 'linkedin' :
            return 'icon-linkedin-logo';
        case 'google' :
            return 'icon-google-plus';
        case 'instagram' :
            return 'icon-instagram-symbol';
        case 'youtube' :
            return 'icon-youtube-logo';
    }
    return '';
}

function get_social_icon_footer($social)
{
    switch ($social) {
        case 'facebook' :
            return 'fa fa-facebook';
        case 'twitter' :
            return 'fa fa-twitter';
        case 'linkedin' :
            return 'fa fa-linkedin';
        case 'google' :
            return 'fa fa-google';
        case 'instagram' :
            return 'fa fa-instagram';
        case 'youtube' :
            return 'fa fa-youtube-play';
    }
    return '';
}

function get_social_color_footer($social)
{
    switch ($social) {
        case 'facebook' :
            return '#3B5998';
        case 'twitter' :
            return '#02B0E8';
        case 'linkedin' :
            return '#0077B5';
        case 'google-plus' :
            return '#A11312';
        case 'instagram' :
            return '#8a3ab9';
        case 'youtube' :
            return '#C22E2A';
    }
    return '';
}


function get_socials()
{
    return \App\SocialMedia::all();
}


function string_limit($str, $char_num)
{
    return str_limit(strip_tags($str), $limit = $char_num, $end = ' ...');
}

function is_free($value)
{
    return (isset($value) && $value > 0) ? $value . '$' : 'مجاناً';
}

function array_map_int($input)
{
    if (isset($input) && is_string($input)) {
        return array_map('intval', explode(',', $input));
    }
    return [];
}

function convert_array_map_int($input)
{
    if (isset($input) && is_array($input)) {
        return array_map('intval', $input);
    }
    return [];
}

function social_media()
{
    return \App\SocialMedia::all();
}

function get_request_status($request)
{
    switch ($request->status) {
        case 'pending_teacher' :
            return __("بإنتظار موافقة المدرس");
        case 'pending_student' :
            return __('بإنتظار تأكيد الطالب');
        case 'progress' :
            return (isset($request->is_confirmed) && (int)$request->is_confirmed == 0) ? __('قيد التنفيذ') : __('بإنتظار تأكيد التسليم');
        case 'completed' :
            return __('مكتملة');
        case 'canceled' :
            return __('ملغية');
        case 'rejected' :
            return __('مرفوضة');
        default:
            return '';
    }
}


function get_crumb_array($array)
{
    $crumbArray = [];
    foreach ($array as $i => $item) {
        $crumbArray[$i]['name'] = $item[0];
        $crumbArray[$i]['link'] = $item[1];
    }
    return $crumbArray;
}

function isset_value($object, $attribute, $default = '')
{
    return isset($object) ? $object->$attribute : $default;
}

function get_most_teacher_request()
{
    $teachers = new \App\User();
    return $teachers->active()->teachers()->orderBy('rating', 'DESC')->take(8)->get();
}

function get_all_teachers()
{
    $teachers = new \App\User();
    return $teachers->active()->teachers()->orderBy('rating', 'DESC')->get();
}

function is_type_checked($array, $input)
{
    if (isset($array) && isset($input)) {
        foreach ($array as $item) {
            if ($input == $item) {
                return 'checked';
            }
        }
    }
    return '';
}


function no_data()
{
    return 'لا يوجد بيانات';
}


function get_unread_notifications_count()
{
    return (isset(auth()->user()->unreadNotifications) && auth()->user()->unreadNotifications->count() > 0) ? auth()->user()->unreadNotifications->count() : 0;
}


function admin_url($uri)
{
    return url('admin/' . $uri);
}

function is_menu_element_active($uri)
{
    if (preg_match($uri, url()->current())) {
        return 'active';
    }
    return '';
}

function get_all_services()
{
    return \App\Service::orderBy('created_at', 'DESC')->get();
}

function get_all_project()
{
    return \App\Project::orderBy('created_at', 'DESC')->get();
}

function diff_for_humans($timestamp)
{
    \Carbon\Carbon::setLocale('en');
    return (is_string($timestamp)) ? \Carbon\Carbon::createFromTimestampUTC(strtotime($timestamp))->diffForHumans() : $timestamp->diffForHumans();
}

function get_months(){
    return [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec',];
}
function is_nav_active($array)
{
    foreach ($array as $item) {
        if (strpos(url()->current(), $item) !== false) {
            return 'm-menu__item--submenu m-menu__item--open m-menu__item--expanded';
        }
    }
    return '';
}

function is_active($uri = null)
{

    if (admin_url(isset($uri) ? $uri : 'dashboard') == url()->current()) {
        return 'm-menu__item--active';
    }
    return '';
}

function is_active_preg_match($uri = null)
{
    return !preg_match($uri, url()->current()) ?: 'm-menu__item--active';
}

function is_element_active($uri, $uri2 = null)
{
    return (isset($uri2)) ? ((preg_match($uri, url()->current()) || (preg_match($uri2, url()->current())) ? 'active menu-open' : '')) : ((preg_match($uri, url()->current())) ? 'active nav-active' : '');
}

function is_parent_active($uri)
{
    return (preg_match('/' . $uri . '/i', url()->current())) ? 'active' : '';
}

function get_unread_message_count()
{
    $messages = new \App\VisitorMessage();
    return $messages->unReadMessages()->count();
}

function is_menu_active($uri)
{
    if (preg_match($uri, url()->current())) {
        return 'active';
    }
    return '';
}

function get_locale_changer_URL($locale)
{
    $uri = request()->segments();
    $uri[0] = $locale;
    return url(implode($uri, '/'));
}

function get_text_locale($obj, $text)
{
    if (isset($obj)) {
        $val = $text . (get_current_locale() == 'en' ? '_en' : '_ar');
        return $obj->$val;
    }
    return no_data();
}


function is_selected($var1, $var2)
{
    return ($var1 == $var2) ? 'selected' : '';
}


function YoutubeID($url)
{
    if (strlen($url) > 11) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        } else
            return false;
    }
    return $url;
}

function get_video_embed($link)
{
    $error_msg = '';
    $full_link = '';
    $thumbnail_url = null;
    $code_pattern = '<div><div style="width: 100%; height: 0px; position: relative; padding-bottom: 56.2493%;"><iframe src="{URL}" frameborder="0" allowfullscreen style="width: 100%; height: 100%; position: absolute;"></iframe></div></div>';
    $url_parts = parse_url(strtolower($link));
    if (array_key_exists('host', $url_parts)) {
        if (isset($url_parts['query'])) {
            parse_url(parse_str(parse_url($link, PHP_URL_QUERY), $query_parts));
            if (isset($query_parts['v']) && @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $query_parts['v'])) {
                $full_link = 'https://www.youtube.com/embed/' . $query_parts['v'] . '?wmode=transparent&rel=0&autohide=1&showinfo=0&enablejsapi=1';
                $code = str_replace('{URL}', $full_link, $code_pattern);
                $thumbnail_url = '"https://img.youtube.com/vi/' . $query_parts['v'] . '/mqdefault.jpg';
            } else {
                $error_msg = trans('phrases.invalid_video_link');
            }
        } else {
            $error_msg = trans('phrases.invalid_video_link');
        }
    } else {
        $error_msg = "عذرا الرابط خطأ";
    }

    return $full_link;
}

function user()
{
    return auth()->user();
}

function locale_value($value_ar, $value_en)
{
    return get_current_locale() == 'ar' ? $value_ar : $value_en;
}

function createDate($year, $month, $day)
{
    $date = new DateTime();
    $date->setDate($year, $month, $day);
    return $date->format('Y-m-d');
}