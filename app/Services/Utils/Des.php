<?php
/**
 * Created by PhpStorm.
 * User: Administrator des加密算法
 * Date: 2019/4/2
 * Time: 11:30
 */
namespace App\Services\Utils;
class Des
{
    private static $_instance = NULL;
    var $key;//秘钥向量
    var $iv;//混淆向量 ->偏移量

    function __construct()
    {
        $this->key = 'tyxs9sx';
        $this->iv = '';
    }

    /**
     *
     * @User yaokai
     * @return Des|null
     */
    public static function share()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Des();
        }
        return self::$_instance;
    }

    /**
     * 加密算法
     * @User yaokai
     * @param $input 要加密的数据
     * @return string 返回加密后的字符串
     */
    public function encrypt($input)
    {
        //获得加密算法的分组大小  8
        @$size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        //ascii 填充
        $input = $this->pkcs5_pad($input, $size); //如果采用PaddingPKCS7，请更换成PaddingPKCS7方法。
        //用0填充秘钥为指定长度8
        $key = str_pad($this->key, 8, '0'); //3DES加密将8改为24
        //打开算法和模式对应的模块
        @$td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, '');
        //判断混淆向量是否为空
        if ($this->iv == '') {
            //从算法源随机生成混淆向量
            @$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);//类似b"¨ß^\f\x1EÅ╩5"
        } else {
            //反之取设置的混淆向量
            $iv = $this->iv;
        }
        //初始化加密所需的缓冲区
        @mcrypt_generic_init($td, $key, $iv);
        //加密数据  $td为算法对象模块  $input为处理过后的值
        @$data = mcrypt_generic($td, $input);// 类似b"ýyP\x7FN\x00èiÝd>À?s\x18Î"
        //对加密模块进行清理工作
        @mcrypt_generic_deinit($td);
        //关闭加密模块
        @mcrypt_module_close($td);
        //使用 MIME base64 对数据进行编码
        $data = base64_encode($data);//如需转换二进制可改成 bin2hex 转换
        //如果设置了混淆向量 则加密的值是固定的   如果没设置混淆向量 则加密的值是随机的
        return $data;
    }


    /**
     * 解密算法
     * @User yaokai
     * @param $encrypted 加密后的字符串
     * @return bool|string
     */
    function decrypt($encrypted)
    {
        //对使用 MIME base64 编码的数据进行解码
        $encrypted = base64_decode($encrypted); //如需转换二进制可改成 bin2hex 转换
        //使用另一个字符串填充字符串为指定长度 获取秘钥
        $key = str_pad($this->key, 8, '0'); //3DES加密将8改为24
        //打开算法和模式对应的模块
        @$td = mcrypt_module_open(MCRYPT_DES, '', MCRYPT_MODE_CBC, '');//3DES加密将MCRYPT_DES改为MCRYPT_3DES
        //判断混淆向量是否为空
        if ($this->iv == '') {
            //从算法源随机生成混淆向量
            @$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        } else {
            //反之取设置的混淆向量
            $iv = $this->iv;
        }
        //返回打开的模式所能支持的最长密钥  没用上
        @$ks = mcrypt_enc_get_key_size($td);//DES 8   3DES 24
        //初始化加密所需的缓冲区
        @mcrypt_generic_init($td, $key, $iv);
        //解密数据  $td为算法对象模块  $encrypted为需要解密的数据
        $decrypted = @mdecrypt_generic($td, $encrypted);//类似于 "15549070665\x05\x05\x05\x05\x05" 之前加密的数据
        //对加密模块进行清理工作
        @mcrypt_generic_deinit($td);
        //关闭加密模块
        @mcrypt_module_close($td);
        //返回取出解密数据
        $data = $this->pkcs5_unpad($decrypted);

        return $data;
    }

    /**
     * 填补需加密的字符串
     * PKCS7Padding VS PKCS5Padding
     * 区别，PKCS5Padding的blocksize为8字节，而PKCS7Padding的blocksize可以为1到255字节
     * @User yaokai
     * @param $text
     * @param $blocksize
     * @return string
     */
    function pkcs5_pad($text, $blocksize)
    {
        //$pad=5  blocksize=11  $test=8  %取余
        $pad = $blocksize - (strlen($text) % $blocksize);//5
        //返回ascii填补后的字符串， 类似 "15549070665\x05\x05\x05\x05\x05"
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 去除加密填补的字符串
     * PKCS7Padding VS PKCS5Padding
     * 区别，PKCS5Padding的blocksize为8字节，而PKCS7Padding的blocksize可以为1到255字节
     * @User yaokai
     * @param $text
     * @return bool|string
     */
    function pkcs5_unpad($text)
    {
        //取出最后一个字符串 {15}   ord返回字符的 ASCII 码值
        $pad = ord($text{strlen($text) - 1});//5
        //判断$pad的值是否大于本身字符串
        if ($pad > strlen($text)) {
            //如果大于  则多余
            return false;
        }
        //计算ASCII 码值中全部字符都存在于$text字符集合中的第一段子串的长度是否等于取出的$pad
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            //如果不相等  则缺失
            return false;
        }
        //返回字符串的子串
        return substr($text, 0, -1 * $pad);
    }


    /**
     * 填补需加密的字符串
     * PKCS7Padding VS PKCS5Padding
     * 区别，PKCS5Padding的blocksize为8字节，而PKCS7Padding的blocksize可以为1到255字节
     * @User yaokai
     * @param $text
     * @param $blocksize
     * @return string
     */
    function PaddingPKCS7($data)
    {
        @$block_size = mcrypt_get_block_size(MCRYPTDES, MCRYPT_MODE_CBC);//3DES加密将MCRYPT_DES改为MCRYPT_3DES
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }


    /**
     * 去除加密填补的字符串
     * PKCS7Padding VS PKCS5Padding
     * 区别，PKCS5Padding的blocksize为8字节，而PKCS7Padding的blocksize可以为1到255字节
     * @User yaokai
     * @param $text
     * @return bool|string
     */
    private function UnPaddingPKCS7($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

}


/**
 * DES加密解密
 */
//class Des
//{
//    private static $_instance = NULL;
//    var $key;//秘钥向量
//    var $iv;//混淆向量 ->偏移量
//
//    function __construct()
//    {
//        $this->key = 'tyxs9sx';
//        $this->iv = '';
//    }
//
//
//    function getSKey($msg)
//    {
//        if (!$msg) {
//            die('请输入参数值');
//        }
//        /* 打开加密算法和模式 */
//        $td = mcrypt_module_open('des', '', 'ecb', '');
//        /* 创建初始向量，并且检测密钥长度。 Windows 平台请使用 MCRYPT_RAND。 */
//        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
//        $ks = mcrypt_enc_get_key_size($td);
//        /* 创建密钥 */
//        $key = substr(md5($msg), 0, $ks);
//        /* 并且关闭模块 */
//        mcrypt_module_close($td);
//        return $key;
//    }
//
//    /**
//     *
//     * 加密函数
//     * 算法：des
//     * 加密模式：ecb
//     * 补齐方法：PKCS5
//     *
//     * @param unknown_type $input
//     */
//    public function encryptDesEcbPKCS5($input, $key='tyxs9sx')
//    {
//        @$size = mcrypt_get_block_size('des', 'ecb');
//        @$input = $this->pkcs5_pad($input, $size);
//        @$td = mcrypt_module_open('des', '', 'ecb', '');
//        //获取密钥的最大长度
//        @$ks = mcrypt_enc_get_key_size($td);
//        @$key = substr($key, 0, $ks);
//        //加密向量值
//        @$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
//        //$iv =0;
//        @$tmp = mcrypt_generic_init($td, $key, $iv);
//        @$data = mcrypt_generic($td, $input);
//        @mcrypt_generic_deinit($td);
//        @mcrypt_module_close($td);
//        return $data;
//    }
//
//    /**
//     * 解密函数
//     * 算法：des
//     * 加密模式：ecb
//     * 补齐方法：PKCS5
//     * @param unknown_type $input
//     */
//    public function decryptDesEcbPKCS5($input, $key)
//    {
//        @$size = mcrypt_get_block_size('des', 'ecb');
//        @$td = mcrypt_module_open('des', '', 'ecb', '');
//        /*获取密钥的最大长度*/
//        @$ks = mcrypt_enc_get_key_size($td);
//        @$key = substr($key, 0, $ks);
//        @$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
//        @mcrypt_generic_init($td, $key, $iv);
//        @$data = mdecrypt_generic($td, $input);
//        @mcrypt_generic_deinit($td);
//        @mcrypt_module_close($td);
//        $data = $this->pkcs5_unpad($data, $size);
//        return $data;
//    }
//
//    private function pkcs5_pad($text, $blocksize)
//    {
//        $pad = $blocksize - (strlen($text) % $blocksize);
//        return $text . str_repeat(chr($pad), $pad);
//    }
//
//    private function pkcs5_unpad($text)
//    {
//        $pad = ord($text{strlen($text) - 1});
//        if ($pad > strlen($text))
//            return false;
//        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
//            return false;
//        return substr($text, 0, -1 * $pad);
//    }
//
//}