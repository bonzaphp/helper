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

}