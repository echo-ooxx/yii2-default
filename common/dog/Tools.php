<?php
/**
 * Created by PhpStorm.
 * User: leezhang
 * Date: 2017/11/21
 * Time: 下午12:24
 */

namespace common\dog;

class Tools
{
    const FLAG_NUMERIC = 1;
    const FLAG_NO_NUMERIC = 2;
    const FLAG_ALPHANUMERIC = 3;

    public static function randomstring($length = 8, $flag = self::FLAG_NO_NUMERIC)
    {
        switch ($flag) {
            case self::FLAG_NUMERIC:
                $str = '0123456789';
                break;
            case self::FLAG_NO_NUMERIC:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case self::FLAG_ALPHANUMERIC:
            default:
                $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        for ($i = 0, $passwd = ''; $i < $length; $i++) {
            $passwd .= substr($str, mt_rand(0, strlen($str) - 1), 1);
        }

        return $passwd;
    }

    public static function generateOrderId()
    {
        mt_srand((double)microtime(true) * 1000000);

        return date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public static function getHttpHost($http = false, $entities = false)
    {
        $host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);
        if ($entities) {
            $host = htmlspecialchars($host, ENT_COMPAT, 'UTF-8');
        }
        if ($http) {
            $https = false;
            if (isset($_SERVER['HTTPS'])) {
                $https = ($_SERVER['HTTPS'] == 1 || strtolower($_SERVER['HTTPS']) == 'on');
            }
            if (isset($_SERVER['SSL'])) {
                $https = ($_SERVER['SSL'] == 1 || strtolower($_SERVER['SSL']) == 'on');
            }
            $host = ($https ? 'https://' : 'http://') . $host;
        }

        return $host;
    }

    public static function getDomain()
    {
        if (preg_match("#[\w-]+\.(com|net|org|gov|cc|biz|info|cn|co)\b(\.(cn|hk|uk|jp|tw))*#", $_SERVER['HTTP_HOST'], $match)) {
            return $match[0];
        }

        return "";
    }

    public static function getServerName()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_SERVER']) && $_SERVER['HTTP_X_FORWARDED_SERVER']) {
            return $_SERVER['HTTP_X_FORWARDED_SERVER'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    public static function getRemoteAddress($onlyremote = false)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!$onlyremote) {
            if (isset($_SERVER['HTTP_CDN_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CDN_REAL_IP'])) {
                $ip = $_SERVER['HTTP_CDN_REAL_IP'];
            } elseif (isset($_SERVER['HTTP_CDN_SRC_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CDN_SRC_IP'])) {
                $ip = $_SERVER['HTTP_CDN_SRC_IP'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
                foreach ($matches[0] AS $xip) {
                    if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                        $ip = $xip;
                        break;
                    }
                }
            }
        }

        return $ip;
    }

    public static function getRemotePort()
    {
        return intval($_SERVER['REMOTE_PORT']);
    }

    public static function getReferer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return null;
        }
    }

    public static function getUA()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }

        return "";
    }

    public static function usingSecureMode()
    {
        if (isset($_SERVER['HTTPS'])) {
            return ($_SERVER['HTTPS'] == 1 || strtolower($_SERVER['HTTPS']) == 'on');
        }
        if (isset($_SERVER['SSL'])) {
            return ($_SERVER['SSL'] == 1 || strtolower($_SERVER['SSL']) == 'on');
        }

        return false;
    }

    public static function getCurrentUrlProtocolPrefix()
    {
        if (Tools::usingSecureMode()) {
            return 'https://';
        } else {
            return 'http://';
        }
    }

    public static function truncate($str, $max_length, $suffix = '...')
    {
        if (mb_strlen($str, 'utf-8') <= $max_length) {
            return $str;
        }

        return mb_substr($str, 0, $max_length, 'utf-8') . $suffix;
    }

    public static function substr($string, $length, $havedot = 0, $charset = '')
    {
        if (empty($charset)) {
            $charset = 'utf8';
        }
        if (strtolower($charset) == 'gbk') {
            $charset = 'gbk';
        } else {
            $charset = 'utf8';
        }
        if (self::strlen($string, $charset) <= $length) {
            return $string;
        }
        if (function_exists('mb_strcut')) {
            $string = mb_substr($string, 0, $length, $charset);
        } else {
            $pre = '{%';
            $end = '%}';
            $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
            $strcut = '';
            $strlen = strlen($string);
            if ($charset == 'utf8') {
                $n = $tn = $noc = 0;
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $tn = 1;
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $tn = 3;
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $tn = 4;
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $tn = 5;
                        $n += 5;
                        $noc++;
                    } elseif ($t == 252 || $t == 253) {
                        $tn = 6;
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            } else {
                $n = $tn = $noc = 0;
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } else {
                        $tn = 1;
                        $n++;
                        $noc++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            }
            $string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        }
        if ($havedot) {
            $string = $string . "...";
        }

        return $string;
    }

    public static function strlen($string, $charset = 'utf8')
    {
        if (strtolower($charset) == 'gbk') {
            $charset = 'gbk';
        } else {
            $charset = 'utf8';
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, $charset);
        } else {
            $n = $noc = 0;
            $strlen = strlen($string);
            if ($charset == 'utf8') {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $n += 5;
                        $noc++;
                    } elseif ($t == 252 || $t == 253) {
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                }
            } else {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $n += 2;
                        $noc++;
                    } else {
                        $n++;
                        $noc++;
                    }
                }
            }

            return $noc;
        }
    }

    public static function simplearray($array, $key)
    {
        if (!empty($array) && is_array($array)) {
            $result = array();
            foreach ($array as $k => $item) {
                $result[$k] = $item[$key];
            }

            return $result;
        }

        return null;
    }

    public static function simpleerrors($errors)
    {
        $result = array();
        if (is_array($errors)) {
            foreach ($errors as $key => $error) {
                if (is_array($error)) {
                    $result = array_merge($result, self::simpleerrors($error));
                } else {
                    $result[] = $error;
                }
            }
        } else {
            $result[] = $errors;
        }

        return $result;
    }

    public static function obj2array($object)
    {
        return json_decode(json_encode($object), true);
    }

    public static function arrayUnique($array)
    {
        if (version_compare(phpversion(), '5.2.9', '<')) {
            return array_unique($array);
        } else {
            return array_unique($array, SORT_REGULAR);
        }
    }

    public static function arrayUnique2d($array, $keepkeys = true)
    {
        $output = array();
        if (!empty($array) && is_array($array)) {
            $stArr = array_keys($array);
            $ndArr = array_keys(end($array));
            $tmp = array();
            foreach ($array as $i) {
                $i = join("¤", $i);
                $tmp[] = $i;
            }
            $tmp = array_unique($tmp);
            foreach ($tmp as $k => $v) {
                if ($keepkeys) {
                    $k = $stArr[$k];
                }
                if ($keepkeys) {
                    $tmpArr = explode("¤", $v);
                    foreach ($tmpArr as $ndk => $ndv) {
                        $output[$k][$ndArr[$ndk]] = $ndv;
                    }
                } else {
                    $output[$k] = explode("¤", $v);
                }
            }
        }

        return $output;
    }

    public static function friendlyDate($sTime, $type = 'normal')
    {
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = intval(date("Ymd", $cTime)) - intval(date("Ymd", $sTime));
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if ($type == 'full') {
            return date("Y-m-d , H:i:s", $sTime);
        } else {
            if ($dTime < 60 && $dTime > 0) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600 && $dTime > 0) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dDay == 1) {
                return date("昨天 H:i", $sTime);
            } elseif ($dDay == 2) {
                return date("前天 H:i", $sTime);
            } else {
                return date("Y-m-d", $sTime);
            }
        }
    }

    public static function generateYear()
    {
        $tab = array();
        for ($i = date('Y') - 10; $i >= 1900; $i--) {
            $tab[] = $i;
        }

        return $tab;
    }

    public static function generateMonth()
    {
        $tab = array();
        for ($i = 1; $i != 13; $i++) {
            $tab[$i] = date('F', mktime(0, 0, 0, $i, date('m'), date('Y')));
        }

        return $tab;
    }

    public static function dateadd($interval, $step, $date)
    {
        list($year, $month, $day) = explode('-', $date);
        if (strtolower($interval) == 'y') {
            return date('Y-m-d', mktime(0, 0, 0, $month, $day, intval($year) + intval($step)));
        } elseif (strtolower($interval) == 'm') {
            return date('Y-m-d', mktime(0, 0, 0, intval($month) + intval($step), $day, $year));
        } elseif (strtolower($interval) == 'd') {
            return date('Y-m-d', mktime(0, 0, 0, $month, intval($day) + intval($step), $year));
        }

        return date('Y-m-d');
    }

    public static function strexists($string, $find)
    {
        return !(strpos($string, $find) === FALSE);
    }

    public static function utf8substr($string, $beginIndex, $length)
    {
        if (strlen($string) < $length) {
            return substr($string, $beginIndex);
        }
        $char = ord($string[$beginIndex + $length - 1]);
        if ($char >= 224 && $char <= 239) {
            $str = substr($string, $beginIndex, $length - 1);

            return $str;
        }
        $char = ord($string[$beginIndex + $length - 2]);
        if ($char >= 224 && $char <= 239) {
            $str = substr($string, $beginIndex, $length - 2);

            return $str;
        }

        return substr($string, $beginIndex, $length);
    }

    public static function implode($glue, $array)
    {
        $return = '';
        if (!empty($array)) {
            foreach ($array as $item) {
                if (is_array($item)) {
                    $return .= $glue . self::implode($glue, $item);
                } elseif (!empty($item)) {
                    $return .= $item;
                }
            }
        }

        return trim($return, ';');
    }

    public static function isX86_64arch()
    {
        return (PHP_INT_MAX == '9223372036854775807');
    }

    public static function getMaxUploadSize($max_size = 0)
    {
        $post_max_size = self::strtobyte(ini_get('post_max_size'));
        $upload_max_filesize = self::strtobyte(ini_get('upload_max_filesize'));
        if ($max_size > 0) {
            $result = min($post_max_size, $upload_max_filesize, $max_size);
        } else {
            $result = min($post_max_size, $upload_max_filesize);
        }

        return $result;
    }

    public static function getMemoryLimit()
    {
        $memory_limit = @ini_get('memory_limit');

        return self::strtobyte($memory_limit);
    }

    public static function strtobyte($value)
    {
        if (is_numeric($value)) {
            return $value;
        } else {
            $value_length = strlen($value);
            $qty = (int)substr($value, 0, $value_length - 1);
            $unit = strtolower(substr($value, $value_length - 1));
            switch ($unit) {
                case 'k':
                    $qty *= 1024;
                    break;
                case 'm':
                    $qty *= 1048576;
                    break;
                case 'g':
                    $qty *= 1073741824;
                    break;
            }

            return $qty;
        }
    }

    public static function bytetostr($value)
    {
        if ($value >= 1073741824) {
            $value = round($value / 1073741824 * 100) / 100 . ' GB';
        } elseif ($value >= 1048576) {
            $value = round($value / 1048576 * 100) / 100 . ' MB';
        } elseif ($value >= 1024) {
            $value = round($value / 1024 * 100) / 100 . ' KB';
        } else {
            $value = $value . ' Bytes';
        }

        return $value;
    }

    public static function cleanXSS($data, $trim = true)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return $trim ? trim($data) : $data;
    }

    public static function fulltohalf($str)
    {
        $arr = array(
            '０' => '0',
            '１' => '1',
            '２' => '2',
            '３' => '3',
            '４' => '4',
            '５' => '5',
            '６' => '6',
            '７' => '7',
            '８' => '8',
            '９' => '9',
            'Ａ' => 'A',
            'Ｂ' => 'B',
            'Ｃ' => 'C',
            'Ｄ' => 'D',
            'Ｅ' => 'E',
            'Ｆ' => 'F',
            'Ｇ' => 'G',
            'Ｈ' => 'H',
            'Ｉ' => 'I',
            'Ｊ' => 'J',
            'Ｋ' => 'K',
            'Ｌ' => 'L',
            'Ｍ' => 'M',
            'Ｎ' => 'N',
            'Ｏ' => 'O',
            'Ｐ' => 'P',
            'Ｑ' => 'Q',
            'Ｒ' => 'R',
            'Ｓ' => 'S',
            'Ｔ' => 'T',
            'Ｕ' => 'U',
            'Ｖ' => 'V',
            'Ｗ' => 'W',
            'Ｘ' => 'X',
            'Ｙ' => 'Y',
            'Ｚ' => 'Z',
            'ａ' => 'a',
            'ｂ' => 'b',
            'ｃ' => 'c',
            'ｄ' => 'd',
            'ｅ' => 'e',
            'ｆ' => 'f',
            'ｇ' => 'g',
            'ｈ' => 'h',
            'ｉ' => 'i',
            'ｊ' => 'j',
            'ｋ' => 'k',
            'ｌ' => 'l',
            'ｍ' => 'm',
            'ｎ' => 'n',
            'ｏ' => 'o',
            'ｐ' => 'p',
            'ｑ' => 'q',
            'ｒ' => 'r',
            'ｓ' => 's',
            'ｔ' => 't',
            'ｕ' => 'u',
            'ｖ' => 'v',
            'ｗ' => 'w',
            'ｘ' => 'x',
            'ｙ' => 'y',
            'ｚ' => 'z',
            '（' => '(',
            '）' => ')',
            '〔' => '[',
            '〕' => ']',
            '【' => '[',
            '】' => ']',
            '〖' => '[',
            '〗' => ']',
            '“' => '"',
            '”' => '"',
            '‘' => '\'',
            '’' => '\'',
            '｛' => '{',
            '｝' => '}',
            '《' => '<',
            '》' => '>',
            '％' => '%',
            '＋' => '+',
            '—' => '-',
            '－' => '-',
            '～' => '-',
            '：' => ':',
            '。' => '.',
            '、' => ',',
            '，' => '.',
            '；' => ',',
            '？' => '?',
            '！' => '!',
            '…' => '-',
            '‖' => '|',
            '｜' => '|',
            '〃' => '"',
            '　' => ' ',
            '＄' => '$',
            '＠' => '@',
            '＃' => '#',
            '＾' => '^',
            '＆' => '&',
            '＊' => '*',
            '＂' => '"',
        );

        return strtr($str, $arr);
    }

    private static function http_build_query($formdata, $separator, $key = '', $prefix = '')
    {
        $rlt = '';
        foreach ($formdata as $k => $v) {
            if (is_array($v)) {
                if ($key) {
                    $rlt .= self::http_build_query($v, $separator, $key . '[' . $k . ']', $prefix);
                } else {
                    $rlt .= self::http_build_query($v, $separator, $k, $prefix);
                }
            } else {
                if ($key) {
                    $rlt .= $prefix . $key . '[' . urlencode($k) . ']=' . urldecode($v) . '&';
                } else {
                    $rlt .= $prefix . urldecode($k) . '=' . urldecode($v) . '&';
                }
            }
        }

        return $rlt;
    }

    public static function set_status($position, $value, $baseon = null)
    {
        $t = pow(2, $position - 1);
        if ($value) {
            $t = $baseon | $t;
        } elseif ($baseon !== null) {
            $t = $baseon & ~$t;
        } else {
            $t = ~$t;
        }

        return $t & 0xFFFF;
    }

    public static function get_status($status, $position)
    {
        $t = $status & pow(2, $position - 1) ? 1 : 0;

        return $t;
    }

    public static function tree($items, $idfield = 'id', $pidfield = 'pid', $childfield = 'children')
    {
        $tree = array();
        foreach ($items as $key => $item) {
            if (isset($items[$item[$pidfield]])) {
                $items[$item[$pidfield]][$childfield][$item[$idfield]] = &$items[$item[$idfield]];
            } else {
                $tree[$key] = &$items[$item[$idfield]];
            }
        }

        return $tree;
    }

    public static function build_url($url,$params){
        $str = strpos($url,'?');
        $url_params = '';
        if(is_array($params)){
            foreach($params as $key => $value){
                $url_params .= $key.'='.$value.'&';
            }
            $url_params = trim($url_params,'&');
        }else{
            $url_params = $params;
        }
        if($str === false){
            return $url.'?'.$url_params;
        }else{
            return $url.'&'.$url_params;
        }
    }

    public static function map_by_id($data){
        $arr = [];
        if(is_array($data)){
            foreach($data as $key => $value){
                if(isset($value['id'])){
                    $arr[$value['id']] = $value;
                }else{
                    $arr[] = $value;
                }
            }
        }
        return $arr;
    }

    public static function getOrderId(){
        list($msec, $sec) = explode(' ', microtime());
        return self::generateOrderId().floor($msec*10000);
    }

}

?>