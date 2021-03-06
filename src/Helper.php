<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019-06-27
 * Time: 11:43
 */

namespace bonza\helper;


use bonza\helper\exception\RuntimeException;
use Exception;

class Helper
{
    /**
     * 随机字符串生成
     * @param  int  $length  长度
     * @param  bool  $is_numeric  字符组成模式
     * @return string
     */
    public static function random(int $length ,bool $is_numeric = false ): string
    {
        try {
            //$is_numeric=false表示字母加数字，$length表示长度
            if ($is_numeric) {
                $hash = sprintf("%0{$length}d", random_int(0, (10 ** $length) - 1));
            } else {
                $hash = '';
                $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
                $max = strlen($chars) - 1;
                for ($i = 0; $i < $length; $i++) {
                    $hash .= $chars[random_int(0, $max)];
                }
            }
            return $hash;
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * 获取毫秒级别的时间戳
     * @return float
     */
    public static function getMillisecond(): float
    {
        list($micro_sec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', ((float) $micro_sec + (float) $sec) * 1000);
    }

    /**
     * 数字金额转换成中文大写金额的函数
     * String Int  $num  要转换的小写数字或小写字符串
     * return 大写字母
     * 小数位为两位
     * @param $num
     * @return string
     */
    public static function numToRmb(float $num): string
    {
        $c1 = '零壹贰叁肆伍陆柒捌玖';
        $c2 = '分角元拾佰仟万拾佰仟亿';
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num *= 100;
        if (strlen($num) > 10) {
            return '金额太大，请检查';
        }
        $i = 0;
        $c = '';
        while (1) {
            if ($i === 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = (string)($num % 10);
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n !== '0' || ($n === '0' && ($p2 === '亿' || $p2 === '万' || $p2 === '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            ++$i;
            //去掉数字最后一位了
            $num /= 10;
            //这里一定要转换成整形，否则将导致无法结束循环，因为浮点数无法直接和零比较
            $num = (int)$num;
            //结束循环
            if ($num === 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m === '零元' || $m === '零万' || $m === '零亿' || $m === '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j -= 3;
                $slen -= 3;
            }
            $j += 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c) - 3, 3) === '零') {
            $c = substr($c, 0, -3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return '零元整';
        }
        return $c . '整';
    }


}