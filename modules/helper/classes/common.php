<?php

/**
 * Default common
 *
 * @package    Kohana/common
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class common {
    /*
     * 星期几换算
     * @author akirametero
     */

    public static function getWeek($number) {
        switch ($number) {
            case 1:
                return "一";
                break;
            case 2:
                return "二";
                break;
            case 3:
                return "三";
                break;
            case 4:
                return "四";
                break;
            case 5:
                return "五";
                break;
            case 6:
                return "六";
                break;
            case 0:
                return "日";
                break;
        }
    }

    //end function

    /*
     * 除去html各种东西
     * @author hl
     */
    public static function clearHtml ( $content ) {
        $content = preg_replace ( "/<a[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<\/a>/i" , "" , $content );
        $content = preg_replace ( "/<div[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<font[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<strong[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<\/font[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<\/strong[^>]*>/i" , "" , $content );
        $content = preg_replace ( "/<\/div>/i" , "" , $content );
        $content = preg_replace ( "/<p>/i" , "" , $content );
        $content = preg_replace ( "/<\/p>/i" , "" , $content );
        $content = preg_replace ( "/<!--[^>]*-->/i" , "" , $content ); // 注释内容
        $content = preg_replace ( "/style=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/class=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/id=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/lang=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/width=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/height=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/border=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/face=.+?['|\"]/i" , '' , $content ); // 去除样式
        $content = preg_replace ( "/where/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/drop/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/delete/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/update/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/create/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/insert/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/select/i" , '' , $content ); // 去除sql关键字
        $content = preg_replace ( "/truncate/i" , '' , $content ); // 去除sql关键字
        $qian = array(" ","　","\t","\n","\r",",");
        $hou = array("","","","","","，") ;
        $content = str_replace($qian,$hou,$content); 
        return $content;
    }

    /**
     * 时间换算
     * @param
     * @return string
     */
    public static function gmstrftimeA($seconds) {
        if ($seconds > 3600) {
            return intval($seconds / 3600) . '小时' . gmstrftime('%M分钟', $seconds);
        } else {
            return gmstrftime('%H小时%M分钟', $seconds);
        }
    }

    /**
     * 微信支付链接跳转
     * @param unknown $ordersn
     * @param unknown $subject
     * @param unknown $price
     * @param unknown $paytype
     * @param string $showurl
     * @param string $extra_para
     * @param string $widbody
     * @return string
     */
    public static function payOnlineWx($ordersn, $subject, $price) {
        $payurl = "http://www.51syx.com/pc/wxpay/pay?order_number=$ordersn";
        //$payurl = urlencode($payurl);
        //记录数据库
        $orm = ORM::factory("Tmpwx");
        $orm->order_number = $ordersn;
        $orm->total_fee = $price;
        $orm->subject = $subject;
        $orm->create();
        $html = '<script>window.location.href="' . $payurl . '"</script>';
        return $html;
    }

    //end function
    //在线支付公共接口
    /* -
      $ordersn:订单编号
      $subject:商品名称
      $price:总价
      $showurl:商品url
      - */

    public static function payOnline($ordersn, $subject, $price, $paytype, $showurl = '', $extra_para = '', $widbody = '') {



        if ($paytype == 1) { //支付宝
            $payurl = '/shouji/thirdpay/alipay';
            $html = "<form method='post' action='{$payurl}' name='alipayfrm'>";
            $html.='<input type="hidden" name="ordersn" value="' . $ordersn . '">';
            $html.='<input type="hidden" name="subject" value="' . $subject . '">';
            $html.='<input type="hidden" name="price" value="' . $price . '">';
            $html.='<input type="hidden" name="widbody" value="' . $widbody . '">';
            $html.='<input type="hidden" name="showurl" value="' . $showurl . '">';
            $html.='<input type="hidden" name="extra_common_param" value="' . $extra_para . '">';

            $html.='</form>';
            $html.="<script>document.forms['alipayfrm'].submit();</script>";
            return $html;
        } else if ($paytype == 2) {  //快钱支付
            $payurl = $GLOBALS['cfg_cmspath'] . '/thirdpay/bill';

            $html = "<form method='post' action='{$payurl}' name='billfrm'>";
            $html.='<input type="hidden" name="ordersn" value="' . $ordersn . '">';
            $html.='<input type="hidden" name="subject" value="' . $subject . '">';
            $html.='<input type="hidden" name="price" value="' . $price . '">';
            $html.='<input type="hidden" name="showurl" value="' . $showurl . '">';
            $html.='</form>';
            $html.="<script>document.forms['billfrm'].submit();</script>";
            return $html;
        }
    }

    //end function

    /**
     * 易宝支付
     * @author 郁政
     */
    public function payOnlineYee($reqURL_onLine, $p0_Cmd, $p1_MerId, $p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $p9_SAF, $pa_MP, $pd_FrpId, $pm_Period, $pn_Unit, $pr_NeedResponse, $hmac) {
        $html = '<html>';
        $html .= '<head>';
        $html .= '<title>To YeePay Page</title>';
        $html .= '</head>';
        $html .= '<body onLoad="document.yeepay.submit();">';
        $html .= '<form name="yeepay" action="' . $reqURL_onLine . '" method="post">';
        $html .= '<input type="hidden" name="p0_Cmd" value="' . $p0_Cmd . '"/>';
        $html .= '<input type="hidden" name="p1_MerId" value="' . $p1_MerId . '"/>';
        $html .= '<input type="hidden" name="p2_Order" value="' . $p2_Order . '"/>';
        $html .= '<input type="hidden" name="p3_Amt" value="' . $p3_Amt . '"/>';
        $html .= '<input type="hidden" name="p4_Cur" value="' . $p4_Cur . '"/>';
        $html .= '<input type="hidden" name="p5_Pid" value="' . $p5_Pid . '"/>';
        $html .= '<input type="hidden" name="p6_Pcat" value="' . $p6_Pcat . '"/>';
        $html .= '<input type="hidden" name="p7_Pdesc" value="' . $p7_Pdesc . '"/>';
        $html .= '<input type="hidden" name="p8_Url" value="' . $p8_Url . '"/>';
        $html .= '<input type="hidden" name="p9_SAF" value="' . $p9_SAF . '"/>';
        $html .= '<input type="hidden" name="pa_MP" value="' . $pa_MP . '"/>';
        $html .= '<input type="hidden" name="pd_FrpId" value="' . $pd_FrpId . '"/>';
        $html .= '<input type="hidden" name="pm_Period" value="' . $pm_Period . '"/>';
        $html .= '<input type="hidden" name="pn_Unit" value="' . $pn_Unit . '"/>';
        $html .= '<input type="hidden" name="pr_NeedResponse" value="' . $pr_NeedResponse . '"/>';
        $html .= '<input type="hidden" name="hmac" value="' . $hmac . '"/>';
        $html .= '</form>';
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }

    /**
     * 快钱链接跳转
     * @author huanglei@51syx.com
     */
    public static function payOnline99Bill($orderId, $productName, $orderAmount, $payerContact) {
        //人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
        $merchantAcctId = "1007845075601";
        //编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
        $inputCharset = "1";
        //接收支付结果的页面地址，该参数一般置为空即可。
        $pageUrl = "http://www.51syx.com/pc/order/index";
        //服务器接收支付结果的后台地址，该参数务必填写，不能为空。
        $bgUrl = "http://www.51syx.com/pc/pay/kqnotifyurl";
        //网关版本，固定值：v2.0,该参数必填。
        $version = "v2.0";
        //语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
        $language = "1";
        //签名类型,该值为4，代表PKI加密方式,该参数必填。
        $signType = "4";
        //支付人姓名,可以为空。
        $payerName = "";
        //支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
        $payerContactType = "2";
        //支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
//        $payerContact = "18217504148";
        //商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
//        $orderId = date("YmdHis");
        //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
        $orderAmount = $orderAmount * 100;
        //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
        $orderTime = date("YmdHis");
        //商品名称，可以为空。
//        $productName = "苹果";
        //商品数量，可以为空。
        $productNum = "";
        //商品代码，可以为空。
        $productId = "";
        //商品描述，可以为空。
        $productDesc = "";
        //扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
        $ext1 = "";
        //扩展自段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空。
        $ext2 = "";
        //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10，必填。
        $payType = "00";
        //银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
        $bankId = "";
        //同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
        $redoFlag = "";
        //快钱合作伙伴的帐户号，即商户编号，可为空。
        $pid = "";
        // signMsg 签名字符串 不可空，生成加密签名串
        $kq_all_para = self::kq_ck_null($inputCharset, 'inputCharset');
        $kq_all_para.=self::kq_ck_null($pageUrl, "pageUrl");
        $kq_all_para.=self::kq_ck_null($bgUrl, 'bgUrl');
        $kq_all_para.=self::kq_ck_null($version, 'version');
        $kq_all_para.=self::kq_ck_null($language, 'language');
        $kq_all_para.=self::kq_ck_null($signType, 'signType');
        $kq_all_para.=self::kq_ck_null($merchantAcctId, 'merchantAcctId');
        $kq_all_para.=self::kq_ck_null($payerName, 'payerName');
        $kq_all_para.=self::kq_ck_null($payerContactType, 'payerContactType');
        $kq_all_para.=self::kq_ck_null($payerContact, 'payerContact');
        $kq_all_para.=self::kq_ck_null($orderId, 'orderId');
        $kq_all_para.=self::kq_ck_null($orderAmount, 'orderAmount');
        $kq_all_para.=self::kq_ck_null($orderTime, 'orderTime');
        $kq_all_para.=self::kq_ck_null($productName, 'productName');
        $kq_all_para.=self::kq_ck_null($productNum, 'productNum');
        $kq_all_para.=self::kq_ck_null($productId, 'productId');
        $kq_all_para.=self::kq_ck_null($productDesc, 'productDesc');
        $kq_all_para.=self::kq_ck_null($ext1, 'ext1');
        $kq_all_para.=self::kq_ck_null($ext2, 'ext2');
        $kq_all_para.=self::kq_ck_null($payType, 'payType');
        $kq_all_para.=self::kq_ck_null($bankId, 'bankId');
        $kq_all_para.=self::kq_ck_null($redoFlag, 'redoFlag');
        $kq_all_para.=self::kq_ck_null($pid, 'pid');
        $kq_all_para = substr($kq_all_para, 0, strlen($kq_all_para) - 1);
        /////////////  RSA 签名计算 ///////// 开始 //
        $fp = fopen(DOCROOT . "modules/99bill/classes/cert/99bill-rsa.pem", "r");
        $priv_key = fread($fp, 123456);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);
        // compute signature
        openssl_sign($kq_all_para, $signMsg, $pkeyid, OPENSSL_ALGO_SHA1);
        // free the key from memory
        openssl_free_key($pkeyid);
        $signMsg = base64_encode($signMsg);
        $html = '<html>';
        $html .= '<head>';
        $html .= '<title>99bill</title>';
        $html .= '</head>';
        $html .= '<body onLoad="document.kqPay.submit();">';
        $html .= '<form name="kqPay" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" method="post">';
        $html .= '<input type="hidden" name="inputCharset" value="' . $inputCharset . '" />';
        $html .= '<input type="hidden" name="pageUrl" value="' . $pageUrl . '" />';
        $html .= '<input type="hidden" name="bgUrl" value="' . $bgUrl . '" />';
        $html .= '<input type="hidden" name="version" value="' . $version . '" />';
        $html .= '<input type="hidden" name="language" value="' . $language . '" />';
        $html .= '<input type="hidden" name="signType" value="' . $signType . '" />';
        $html .= '<input type="hidden" name="signMsg" value="' . $signMsg . '" />';
        $html .= '<input type="hidden" name="merchantAcctId" value="' . $merchantAcctId . '" />';
        $html .= '<input type="hidden" name="payerName" value="' . $payerName . '" />';
        $html .= '<input type="hidden" name="payerContactType" value="' . $payerContactType . '" />';
        $html .= '<input type="hidden" name="payerContact" value="' . $payerContact . '" />';
        $html .= '<input type="hidden" name="orderId" value="' . $orderId . '" />';
        $html .= '<input type="hidden" name="orderAmount" value="' . $orderAmount . '" />';
        $html .= '<input type="hidden" name="orderTime" value="' . $orderTime . '" />';
        $html .= '<input type="hidden" name="productName" value="' . $productName . '" />';
        $html .= '<input type="hidden" name="productNum" value="' . $productNum . '" />';
        $html .= '<input type="hidden" name="productId" value="' . $productId . '" />';
        $html .= '<input type="hidden" name="productDesc" value="' . $productDesc . '" />';
        $html .= '<input type="hidden" name="ext1" value="' . $ext1 . '" />';
        $html .= '<input type="hidden" name="ext2" value="' . $ext2 . '" />';
        $html .= '<input type="hidden" name="payType" value="' . $payType . '" />';
        $html .= '<input type="hidden" name="bankId" value="' . $bankId . '" />';
        $html .= '<input type="hidden" name="redoFlag" value="' . $redoFlag . '" />';
        $html .= '<input type="hidden" name="pid" value="' . $pid . '" />';
        $html .= '</form>';
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }

    /*
     * 发送短信
     * */

    public static function send_message_bak($mobile, $content) {

        $msg = Kohana::$config->load('message')->short_message;
        $url = $msg['url'];
        $method = 'POST';
        $post_data = array();
        $post_data['userid'] = $msg['userid'];
        $post_data['account'] = $msg['account'];
        $post_data['password'] = $msg['password'];
        $post_data['content'] = $content;
        $post_data['mobile'] = $mobile;


        $o = '';
        foreach ($post_data as $k => $v) {
            $o.="$k=" . urlencode($v) . '&';
        }
        $post_data = substr($o, 0, -1);
        $result = self::http($url, $method, $post_data);
        return $result;
    }

    /*
     * curl http访问
     * */

    public static function http($url, $method = 'get', $postfields = '') {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (substr($url, 0, 7) == 'http://') {
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_URL, Kohana::config('core.nocdn_domain') . $url);
        }
        if ($method == "POST")
            curl_setopt($ch, CURLOPT_POST, 1);
        if (is_string($postfields)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        } else {
            $tempArr = array();
            foreach ($postfields as $k => $v) {
                $tempArr[] = $k . '=' . urlencode($v);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $tempArr));
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        ob_start();
        curl_exec($ch);
        $contents = ob_get_contents();
        ob_end_clean();
        curl_close($ch);
        return $contents;
    }

    /*
     * 提示相关信息（错误或者返回的信息） 
     * @author hl
     */

    public static function showMsg($msg, $gourl) {
        header("Content-type:text/html;charset=utf-8");
        if (!empty($msg))
            echo "<script>alert('$msg');</script>";
        if ($gourl == -1) {
            echo "<script>window.history.go(-1);</script>";
        } else {
            echo "<script>self.location.href='$gourl';</script>";
        }
        exit;
    }

    /*
     * 除去html各种东西
     * @author hl
     */

    // public static function clearHtml($content) {
    //     $content = preg_replace("/<a[^>]*>/i", "", $content);
    //     $content = preg_replace("/<\/a>/i", "", $content);
    //     $content = preg_replace("/<div[^>]*>/i", "", $content);
    //     $content = preg_replace("/<font[^>]*>/i", "", $content);
    //     $content = preg_replace("/<strong[^>]*>/i", "", $content);
    //     $content = preg_replace("/<\/font[^>]*>/i", "", $content);
    //     $content = preg_replace("/<\/strong[^>]*>/i", "", $content);
    //     $content = preg_replace("/<\/div>/i", "", $content);
    //     $content = preg_replace("/<p>/i", "", $content);
    //     $content = preg_replace("/<\/p>/i", "", $content);
    //     $content = preg_replace("/<!--[^>]*-->/i", "", $content); //注释内容
    //     $content = preg_replace("/style=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/class=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/id=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/lang=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/width=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/height=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/border=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/face=.+?['|\"]/i", '', $content); //去除样式
    //     $content = preg_replace("/face=.+?['|\"]/", '', $content); //去除样式 只允许小写 正则匹配没有带 i 参数

    //     return $content;
    // }

    /**
     * 传入城市名称，判断是火车站捏，还是汽车站捏，或者都是--首页智能出行用 
     * @akirametero
     * @return int   
     */
    public static function getCityType($city) {
        $cache = Cache::instance("memcache");
        $key = "common_getCityType_" . $city;
        $tt = $cache->get($key);
        if (empty($tt)) {
            $orm = ORM::factory("Hccity");
            $orm->where("name", "=", $city);
            $count = $orm->count_all();
            if ($count > 1) {
                //是火车站也是汽车站
                $tt = 2;
            } else {
                $orm = ORM::factory("Hccity");
                $orm->where("name", "=", $city);
                $result = $orm->find()->as_array();
                $city_type = Arr::get($result, "city_type");
                $tt = $city_type;
            }
            $cache->set($key, $tt, 86400 * 30);
        }
        return $tt;
    }

    //end function

    /**
     * 同程取消订单原因代码
     * @author 郁政
     */
    public static function getTcCancelReasonCode($id = '') {
        $arr = array(
            '1' => '行程变更',
            '2' => '通过其他更优惠的渠道预订了酒店',
            '3' => '不满意同程的服务',
            '4' => '其他原因',
            '5' => '信息错误重新预订',
            '6' => '预订流程不方便',
            '7' => '变更流程不方便'
        );
        return $id ? (isset($arr[$id]) ? $arr[$id] : '') : $arr;
    }

    /**
     * 捷旅酒店星级
     * @author 郁政
     */
    public static function getJlHotelStar($id = '') {
        $arr = array(
            '20' => '3星以下',
            '30' => '准3星',
            '35' => '3星',
            '40' => '准4星',
            '45' => '4星',
            '50' => '准5星',
            '55' => '5星'
        );
        return $id ? (isset($arr[$id]) ? $arr[$id] : '') : $arr;
    }

    /**
     * 捷旅酒店设施
     * @author 郁政
     */
    public static function getJlHotelFacilities($id = '') {
        $arr = array(
            '11' => '停车场',
            '12' => '会议室',
            '13' => '游泳池',
            '14' => '健身房',
            '15' => '洗衣服务',
            '16' => '中餐厅',
            '17' => '西餐厅',
            '18' => '宴会厅',
            '19' => '租车服务',
            '20' => '外币兑换',
            '21' => '咖啡厅',
            '22' => 'ATM机',
            '23' => '酒吧',
            '24' => '叫醒服务',
            '25' => '网球场',
            '26' => '歌舞厅',
            '27' => '美容美发',
            '30' => '前台贵重物品保险柜',
            '31' => '送餐服务',
            '32' => '礼宾司服务',
            '33' => '商务中心',
            '34' => '旅游服务'
        );
        return $id ? (isset($arr[$id]) ? $arr[$id] : '') : $arr;
    }

    /**
     * 计算范围
     * @param unknown $lat
     * @param unknown $lon
     * @param unknown $raidus
     * @return multitype:number
     */
    public static function getAround($lat, $lon, $raidus) {
        $PI = 3.14159265;

        $latitude = $lat;
        $longitude = $lon;

        $degree = (24901 * 1609) / 360.0;
        $raidusMile = $raidus;

        $dpmLat = 1 / $degree;
        $radiusLat = $dpmLat * $raidusMile;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;

        $mpdLng = $degree * cos($latitude * ($PI / 180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng * $raidusMile;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;

        return array("minLat" => $minLat, "maxLat" => $maxLat, "minLng" => $minLng, "maxLng" => $maxLng);
    }

    /**
     * 计算两个坐标之间的距离(米)
     * @param float $fP1Lat 起点(纬度)
     * @param float $fP1Lon 起点(经度)
     * @param float $fP2Lat 终点(纬度)
     * @param float $fP2Lon 终点(经度)
     * @return int
     */
    public static function distanceBetween($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon) {
        $fEARTH_RADIUS = 6378137;
        $fP1Lon = (float) $fP1Lon;
        $fP2Lon = (float) $fP2Lon;
        $fP1Lat = (float) $fP1Lat;
        $fP2Lat = (float) $fP2Lat;
        //角度换算成弧度
        $fRadLon1 = deg2rad($fP1Lon);
        $fRadLon2 = deg2rad($fP2Lon);
        $fRadLat1 = deg2rad($fP1Lat);
        $fRadLat2 = deg2rad($fP2Lat);
        //计算经纬度的差值
        $fD1 = abs($fRadLat1 - $fRadLat2);
        $fD2 = abs($fRadLon1 - $fRadLon2);
        //距离计算
        $fP = pow(sin($fD1 / 2), 2) + cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2 / 2), 2);
        return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
    }

    /**
     * 酒店星级配置
     * @author 郁政
     */
    public static function getStarRatedConfig() {
        return array(
            '1,76' => '五星/豪华',
            '2,77' => '四星/高档',
            '3,78' => '三星/舒适',
            '152,63' => '二星/经济',
            '1535' => '其他'
        );
    }

    /**
     * 酒店价格配置
     * @author 郁政
     */
    public static function getPriceRangeConfig() {
        return array(
            '0,200' => '&yen;200以下',
            '200,300' => '&yen;200-&yen;300',
            '300,450' => '&yen;300-&yen;450',
            '450,600' => '&yen;450-&yen;600',
            '600,0' => '&yen;600以上'
        );
    }

    /**
     * 酒店到达时间配置
     * @author 郁政
     */
    public static function getArriveTime($id) {
        $arr = array(
            '1' => '1900-01-01 11:00',
            '2' => '1900-01-01 12:00',
            '3' => '1900-01-01 13:00',
            '4' => '1900-01-01 14:00',
            '5' => '1900-01-01 15:00',
            '6' => '1900-01-01 16:00',
            '7' => '1900-01-01 17:00',
            '8' => '1900-01-01 18:00',
            '9' => '1900-01-01 19:00',
            '10' => '1900-01-01 20:00',
            '11' => '1900-01-01 21:00',
            '12' => '1900-01-01 22:00',
            '13' => '1900-01-01 23:00',
            '14' => '1900-01-01 24:00',
            '15' => '1900-01-02 1:00',
            '16' => '1900-01-02 2:00',
            '17' => '1900-01-02 3:00',
            '18' => '1900-01-02 4:00',
            '19' => '1900-01-02 5:00',
        );
        return isset($arr[$id]) ? $arr[$id] : '';
    }

    /**
     * 获取19e火车票座位类型
     * @author 郁政
     */
    public static function getSeatTypeBy19e($id) {
        $arr = array(
            '0' => '商务座',
            '1' => '特等座',
            '2' => '一等座',
            '3' => '二等座',
            '4' => '高级软卧',
            '5' => '软卧',
            '6' => '硬卧',
            '7' => '软座',
            '8' => '硬座',
            '9' => '无座',
            '10' => '其他'
        );
        return isset($arr[$id]) ? $arr[$id] : '';
    }

    /**
     * 二维数组去重
     * @author 郁政
     */
    public static function unique_arr($array2D, $stkeep = false, $ndformat = true) {
        // 判断是否保留一级数组键 (一级数组键可以为非数字)
        if ($stkeep)
            $stArr = array_keys($array2D);

        // 判断是否保留二级数组键 (所有二级数组键必须相同)
        if ($ndformat)
            $ndArr = array_keys(end($array2D));

        //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        foreach ($array2D as $v) {
            $v = join(",", $v);
            $temp[] = $v;
        }

        //去掉重复的字符串,也就是重复的一维数组
        $temp = array_unique($temp);

        //再将拆开的数组重新组装
        foreach ($temp as $k => $v) {
            if ($stkeep)
                $k = $stArr[$k];
            if ($ndformat) {
                $tempArr = explode(",", $v);
                foreach ($tempArr as $ndkey => $ndval)
                    $output[$k][$ndArr[$ndkey]] = $ndval;
            }
            else
                $output[$k] = explode(",", $v);
        }

        return $output;
    }

    /**
     * 拼音字符转换图
     * @var array
     */
    private static $_aMaps = array(
        'a' => -20319, 'ai' => -20317, 'an' => -20304, 'ang' => -20295, 'ao' => -20292,
        'ba' => -20283, 'bai' => -20265, 'ban' => -20257, 'bang' => -20242, 'bao' => -20230, 'bei' => -20051, 'ben' => -20036, 'beng' => -20032, 'bi' => -20026, 'bian' => -20002, 'biao' => -19990, 'bie' => -19986, 'bin' => -19982, 'bing' => -19976, 'bo' => -19805, 'bu' => -19784,
        'ca' => -19775, 'cai' => -19774, 'can' => -19763, 'cang' => -19756, 'cao' => -19751, 'ce' => -19746, 'ceng' => -19741, 'cha' => -19739, 'chai' => -19728, 'chan' => -19725, 'chang' => -19715, 'chao' => -19540, 'che' => -19531, 'chen' => -19525, 'cheng' => -19515, 'chi' => -19500, 'chong' => -19484, 'chou' => -19479, 'chu' => -19467, 'chuai' => -19289, 'chuan' => -19288, 'chuang' => -19281, 'chui' => -19275, 'chun' => -19270, 'chuo' => -19263, 'ci' => -19261, 'cong' => -19249, 'cou' => -19243, 'cu' => -19242, 'cuan' => -19238, 'cui' => -19235, 'cun' => -19227, 'cuo' => -19224,
        'da' => -19218, 'dai' => -19212, 'dan' => -19038, 'dang' => -19023, 'dao' => -19018, 'de' => -19006, 'deng' => -19003, 'di' => -18996, 'dian' => -18977, 'diao' => -18961, 'die' => -18952, 'ding' => -18783, 'diu' => -18774, 'dong' => -18773, 'dou' => -18763, 'du' => -18756, 'duan' => -18741, 'dui' => -18735, 'dun' => -18731, 'duo' => -18722,
        'e' => -18710, 'en' => -18697, 'er' => -18696,
        'fa' => -18526, 'fan' => -18518, 'fang' => -18501, 'fei' => -18490, 'fen' => -18478, 'feng' => -18463, 'fo' => -18448, 'fou' => -18447, 'fu' => -18446,
        'ga' => -18239, 'gai' => -18237, 'gan' => -18231, 'gang' => -18220, 'gao' => -18211, 'ge' => -18201, 'gei' => -18184, 'gen' => -18183, 'geng' => -18181, 'gong' => -18012, 'gou' => -17997, 'gu' => -17988, 'gua' => -17970, 'guai' => -17964, 'guan' => -17961, 'guang' => -17950, 'gui' => -17947, 'gun' => -17931, 'guo' => -17928,
        'ha' => -17922, 'hai' => -17759, 'han' => -17752, 'hang' => -17733, 'hao' => -17730, 'he' => -17721, 'hei' => -17703, 'hen' => -17701, 'heng' => -17697, 'hong' => -17692, 'hou' => -17683, 'hu' => -17676, 'hua' => -17496, 'huai' => -17487, 'huan' => -17482, 'huang' => -17468, 'hui' => -17454, 'hun' => -17433, 'huo' => -17427,
        'ji' => -17417, 'jia' => -17202, 'jian' => -17185, 'jiang' => -16983, 'jiao' => -16970, 'jie' => -16942, 'jin' => -16915, 'jing' => -16733, 'jiong' => -16708, 'jiu' => -16706, 'ju' => -16689, 'juan' => -16664, 'jue' => -16657, 'jun' => -16647,
        'ka' => -16474, 'kai' => -16470, 'kan' => -16465, 'kang' => -16459, 'kao' => -16452, 'ke' => -16448, 'ken' => -16433, 'keng' => -16429, 'kong' => -16427, 'kou' => -16423, 'ku' => -16419, 'kua' => -16412, 'kuai' => -16407, 'kuan' => -16403, 'kuang' => -16401, 'kui' => -16393, 'kun' => -16220, 'kuo' => -16216,
        'la' => -16212, 'lai' => -16205, 'lan' => -16202, 'lang' => -16187, 'lao' => -16180, 'le' => -16171, 'lei' => -16169, 'leng' => -16158, 'li' => -16155, 'lia' => -15959, 'lian' => -15958, 'liang' => -15944, 'liao' => -15933, 'lie' => -15920, 'lin' => -15915, 'ling' => -15903, 'liu' => -15889, 'long' => -15878, 'lou' => -15707, 'lu' => -15701, 'lv' => -15681, 'luan' => -15667, 'lue' => -15661, 'lun' => -15659, 'luo' => -15652,
        'ma' => -15640, 'mai' => -15631, 'man' => -15625, 'mang' => -15454, 'mao' => -15448, 'me' => -15436, 'mei' => -15435, 'men' => -15419, 'meng' => -15416, 'mi' => -15408, 'mian' => -15394, 'miao' => -15385, 'mie' => -15377, 'min' => -15375, 'ming' => -15369, 'miu' => -15363, 'mo' => -15362, 'mou' => -15183, 'mu' => -15180,
        'na' => -15165, 'nai' => -15158, 'nan' => -15153, 'nang' => -15150, 'nao' => -15149, 'ne' => -15144, 'nei' => -15143, 'nen' => -15141, 'neng' => -15140, 'ni' => -15139, 'nian' => -15128, 'niang' => -15121, 'niao' => -15119, 'nie' => -15117, 'nin' => -15110, 'ning' => -15109, 'niu' => -14941, 'nong' => -14937, 'nu' => -14933, 'nv' => -14930, 'nuan' => -14929, 'nue' => -14928, 'nuo' => -14926,
        'o' => -14922, 'ou' => -14921,
        'pa' => -14914, 'pai' => -14908, 'pan' => -14902, 'pang' => -14894, 'pao' => -14889, 'pei' => -14882, 'pen' => -14873, 'peng' => -14871, 'pi' => -14857, 'pian' => -14678, 'piao' => -14674, 'pie' => -14670, 'pin' => -14668, 'ping' => -14663, 'po' => -14654, 'pu' => -14645,
        'qi' => -14630, 'qia' => -14594, 'qian' => -14429, 'qiang' => -14407, 'qiao' => -14399, 'qie' => -14384, 'qin' => -14379, 'qing' => -14368, 'qiong' => -14355, 'qiu' => -14353, 'qu' => -14345, 'quan' => -14170, 'que' => -14159, 'qun' => -14151,
        'ran' => -14149, 'rang' => -14145, 'rao' => -14140, 're' => -14137, 'ren' => -14135, 'reng' => -14125, 'ri' => -14123, 'rong' => -14122, 'rou' => -14112, 'ru' => -14109, 'ruan' => -14099, 'rui' => -14097, 'run' => -14094, 'ruo' => -14092,
        'sa' => -14090, 'sai' => -14087, 'san' => -14083, 'sang' => -13917, 'sao' => -13914, 'se' => -13910, 'sen' => -13907, 'seng' => -13906, 'sha' => -13905, 'shai' => -13896, 'shan' => -13894, 'shang' => -13878, 'shao' => -13870, 'she' => -13859, 'shen' => -13847, 'sheng' => -13831, 'shi' => -13658, 'shou' => -13611, 'shu' => -13601, 'shua' => -13406, 'shuai' => -13404, 'shuan' => -13400, 'shuang' => -13398, 'shui' => -13395, 'shun' => -13391, 'shuo' => -13387, 'si' => -13383, 'song' => -13367, 'sou' => -13359, 'su' => -13356, 'suan' => -13343, 'sui' => -13340, 'sun' => -13329, 'suo' => -13326,
        'ta' => -13318, 'tai' => -13147, 'tan' => -13138, 'tang' => -13120, 'tao' => -13107, 'te' => -13096, 'teng' => -13095, 'ti' => -13091, 'tian' => -13076, 'tiao' => -13068, 'tie' => -13063, 'ting' => -13060, 'tong' => -12888, 'tou' => -12875, 'tu' => -12871, 'tuan' => -12860, 'tui' => -12858, 'tun' => -12852, 'tuo' => -12849,
        'wa' => -12838, 'wai' => -12831, 'wan' => -12829, 'wang' => -12812, 'wei' => -12802, 'wen' => -12607, 'weng' => -12597, 'wo' => -12594, 'wu' => -12585,
        'xi' => -12556, 'xia' => -12359, 'xian' => -12346, 'xiang' => -12320, 'xiao' => -12300, 'xie' => -12120, 'xin' => -12099, 'xing' => -12089, 'xiong' => -12074, 'xiu' => -12067, 'xu' => -12058, 'xuan' => -12039, 'xue' => -11867, 'xun' => -11861,
        'ya' => -11847, 'yan' => -11831, 'yang' => -11798, 'yao' => -11781, 'ye' => -11604, 'yi' => -11589, 'yin' => -11536, 'ying' => -11358, 'yo' => -11340, 'yong' => -11339, 'you' => -11324, 'yu' => -11303, 'yuan' => -11097, 'yue' => -11077, 'yun' => -11067,
        'za' => -11055, 'zai' => -11052, 'zan' => -11045, 'zang' => -11041, 'zao' => -11038, 'ze' => -11024, 'zei' => -11020, 'zen' => -11019, 'zeng' => -11018, 'zha' => -11014, 'zhai' => -10838, 'zhan' => -10832, 'zhang' => -10815, 'zhao' => -10800, 'zhe' => -10790, 'zhen' => -10780, 'zheng' => -10764, 'zhi' => -10587, 'zhong' => -10544, 'zhou' => -10533, 'zhu' => -10519, 'zhua' => -10331, 'zhuai' => -10329, 'zhuan' => -10328, 'zhuang' => -10322, 'zhui' => -10315, 'zhun' => -10309, 'zhuo' => -10307, 'zi' => -10296, 'zong' => -10281, 'zou' => -10274, 'zu' => -10270, 'zuan' => -10262, 'zui' => -10260, 'zun' => -10256, 'zuo' => -10254
    );

    /**
     * 将中文编码成拼音
     * @param string $utf8Data utf8字符集数据
     * @param string $sRetFormat 返回格式 [head:首字母|all:全拼音]
     * @return string
     */
    public static function encode($utf8Data, $sRetFormat = 'head') {
        $arr = ['重庆','婺源','杜浔','邛崃','泸州','深圳','泸沽湖','东莞','澧县','偃师','溧阳','湟中','浏阳','沅陵','漯河','芷江','歙县'];  //如果还有解析不出来的往后面加
        if (in_array($utf8Data,$arr)) {
            foreach ($arr as $key => $val) {
                if ($utf8Data == $val) {
                    $arrp = ['chong qing','wu yuan','du xun','qiong lai','lu zhou','shen zhen','gu lu hu','dong guan','li xian','yan shi','li yang','huang zhong','liu yang','yuan ling','luo he','zhi jiang','she xian'];
                    $arrh = ['cq','wy','dx','ql','lz','sz','glh','dg','lx','ys','ly','hz','ly','yl','lh','zj','sx'];
                    if ('head' === $sRetFormat)
                        return $arrh[$key];
                    else
                        return $arrp[$key];
                }
            }
        }
        $sGBK = iconv('UTF-8', 'GBK', $utf8Data);

        $aBuf = array();
        for ($i = 0, $iLoop = strlen($sGBK); $i < $iLoop; $i++) {
            $iChr = ord($sGBK{$i});
            if ($iChr > 160)
                $iChr = ($iChr << 8) + ord($sGBK{++$i}) - 65536;
            if ('head' === $sRetFormat)
                $aBuf[] = substr(self::zh2py($iChr), 0, 1);
            else
                $aBuf[] = self::zh2py($iChr);
        }
        if ('head' === $sRetFormat)
            return implode('', $aBuf);
        else
            return implode(' ', $aBuf);
    }

    /**
     * 中文转换到拼音(每次处理一个字符)
     * @param number $iWORD 待处理字符双字节
     * @return string 拼音
     */
    private static function zh2py($iWORD) {
        if ($iWORD > 0 && $iWORD < 160) {
            return chr($iWORD);
        } elseif ($iWORD < -20319 || $iWORD > -10247) {
            return '';
        } else {
            foreach (self::$_aMaps as $py => $code) {
                if ($code > $iWORD)
                    break;
                $result = $py;
            }
            return $result;
        }
    }

    /**
     * 发送短信
     * @param $mobile
     * @param $content
     * @return string
     * @author akirametero
     */
    public static function send_message($mobile, $content) {
        $content = str_replace("【51随意行网】", "", $content);
        $msg = Kohana::$config->load('message')->message2;
        $url = $msg['url'];
        $method = 'POST';
        $post_data = array();
        $post_data['account'] = $msg['account'];
        $post_data['pswd'] = $msg['password'];
        $post_data['mobile'] = $mobile;
        $post_data['msg'] = $content;
        $post_data['needstatus'] = true;
        $post_data['product'] = '';
        $post_data['extno'] = '';
        $result = self::curlPost($url, $post_data);
        $res = preg_split("/[,\r\n]/", $result);
    }

    //end function

    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    private function curlPost($url, $postFields) {
        $postFields = http_build_query($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //end function

    /**
     * 处理html元素
     * @param $table
     * @return array
     * @author akirametero
     */
    public function get_html_array($startcity, $endcity, $datetime, $startstaion = "") {
        $url = "http://www.12308.com/index/searchModel.html";
        $post = array("endCityName" => $endcity, "order" => "", "pageIndex" => "1", "startCityName" => $startcity, "startDate" => $datetime, "stationName" => $startstaion, "timeInterval" => "");
        $html = self::curlPost($url, $post);
        $xml = new DOMDocument();
        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $xml->loadHTML($meta . $html);
        $dom = $xml->getElementsByTagName("li");
        $arr = array();
        $n = 0;
        $i = 0;
        $rep = array(" ", "　", "\t", "\n", "\r");
        foreach ($dom as $vs) {
            if ($n > 5) {
                $val = trim($vs->nodeValue);
                $val = str_replace($rep, '+', $val);
                $arr[$i] = $val;
                $i++;
            }
            $n++;
        }
        $new_arr = array();
        $f = 0;
        foreach ($arr as $k => $vss) {
            if ($k % 6 == 0) {
                $train_no_arr = explode("+++++++++", $arr[$k + 2]);
                if ($train_no_arr[1] != "" && $arr[$k + 4] != "") {
                    if ($arr[$k + 3] != "查看票价") {
                        $new_arr[$f]["train_no"] = $train_no_arr[1];
                        $new_arr[$f]["ticket_num"] = $arr[$k + 4];
                        $new_arr[$f]["ticket_price"] = str_replace("¥", "", $arr[$k + 3]);
                        $f++;
                    }
                }
            }
        }
        return $new_arr;
    }

    //end function

    /*
     * 不停的&
     * @author hl
     */
    function kq_ck_null($kq_va, $kq_na) {
        if ($kq_va == "") {
            return $kq_va = "";
        } else {
            return $kq_va = $kq_na . '=' . $kq_va . '&';
        }
    }

    /**
     * 验证请求的参数，是否存在缺失
     * @param unknown_type $check_param：必要的参数名称
     * @param unknown_type $get_param： 现传入的参数列表
     * return $buffer： 若有参数名称不存在，则返回缺失的参数名称
     */
    public static function check_params($check_param, $get_param) {
        $buffer = array();
        if (empty($get_param))
            return $check_param;
        for ($i = 0; $i < count($check_param); $i++) {
            if (!in_array($check_param[$i], $get_param)) {
                $buffer[] = $check_param[$i];
            }
        }
        return $buffer;
    }

    /**
     * 根据错误编号获取错误信息
     * @author 郁政
     *
     */
    public static function getErrMsgByErrCode($errorCode) {
        $arr = array();
        $cache = Cache::instance('memcache');
        $arr = $cache->get('error_code_config');
        if (!$arr) {
            $arr = Kohana::$config->load('error_code');
            $cache->set('error_code_config', $arr, 86400 * 7);
        }
        $er_arr = array();
        if ($arr) {
            foreach ($arr as $k => $v) {
                if (isset($v['code']) && isset($v['msg'])) {
                    $er_arr[$v['code']] = $v['msg'];
                }
            }
        }
        return isset($er_arr[$errorCode]) ? $er_arr[$errorCode] : '';
    }

    /**
     * 获取毫秒数
     * @author 郁政
     *
     */
    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 发送邮件
     * @author akirametero
     * @param string $subject 邮件主题
     * @param string $from_eamil
     * @param string $to_eamil
     * @param string $content
     * @param string $from_eamil
     * @param string $bodytype default text/html
     */
    public static function sendemail($subject, $to_email, $content, $bodytype = 'text/html') {
        $config_group = "default";
        $config = Kohana::$config->load('swift')->get($config_group);
        if (!$config) {
            return false;
        }
        $from_email = $config['transport']['options']['username'];
        $result = FALSE;
        try {
            Email::factory($config_group)->send(Email::message($subject, $from_email, $to_email)->setBody($content, $bodytype));
            $result = 1;
        } catch (Swift_TransportException $e) {

            if (isset($_GET['debug']) and $_GET['debug'] === '1') {
                throw $e;
            }
            $result = FALSE;
        }
        return $result;
    }

    /*
     * 获取某个配置值
     * */

    public static function getSysConf($field, $varname, $webid = 0) {
        $result = DB::query(Database::SELECT, "select $field from sline_sysconfig where varname='$varname' and webid=$webid")->execute()->as_array();
        return $result[0][$field];
    }

    public static function getSysPara($varname) {
        return self::getSysConf('value', $varname, 0);
    }

    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;

        $key = md5($key ? $key : 'stourweb');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /*
     * @author huanglei@51syx.com
     * opid的用户 查询登录
     */

    public static function checkLoginOpid($opid) {
        $model = ORM::factory('member')->where("connectid", "=", $opid)->find();
        if (isset($model->mid))
            return $model->as_array();
        else
            return 0;
    }

    /**
     *   发起一个HTTP(S)请求，并返回json格式的响应数据
     *   @param array 错误信息  array($errorCode, $errorMessage)
     *   @param string 请求Url
     *   @param array 请求参数
     *   @param string 请求类型(GET|POST)
     *   @param int 超时时间
     *   @param array 额外配置
     *   
     *   @return array
     */
    public function curl_request_json(&$error, $url, $param = array(), $method = 'GET', $timeout = 10, $exOptions = null) {
        $error = false;
        $responseText = self:: curl_request_text($error, $url, $param, $method, $timeout, $exOptions);
        $response = null;
        if ($error == false && $responseText) {
            $response = json_decode($responseText, true);
            if ($response == null) {
                $error = array('errorCode' => -1, 'errorMessage' => 'json decode fail', 'responseText' => $responseText);
                //将错误信息记录日志文件里
                $logText = "json decode fail : $url";
                if (!empty($param)) {
                    $logText .= ", param=" . json_encode($param);
                }
                $logText .= ", responseText=$responseText";
                file_put_contents("/data/error.log", $logText);
            }
        }
        return $response;
    }

    /**
     *  发起一个HTTP(S)请求，并返回响应文本
     *   @param array 错误信息  array($errorCode, $errorMessage)
     *   @param string 请求Url
     *   @param array 请求参数
     *   @param string 请求类型(GET|POST)
     *   @param int 超时时间
     *   @param array 额外配置
     *   
     *   @return string
     */
    public function curl_request_text(&$error, $url, $param = array(), $method = 'GET', $timeout = 15, $exOptions = NULL) {
        //判断是否开启了curl扩展
        if (!function_exists('curl_init'))
            exit('please open this curl extension');
        //将请求方法变大写
        $method = strtoupper($method);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (isset($_SERVER['HTTP_USER_AGENT']))
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        if (isset($_SERVER['HTTP_REFERER']))
            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($param)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, (is_array($param)) ? http_build_query($param) : $param);
                }
                break;
            case 'GET':
            case 'DELETE':
                if ($method == 'DELETE') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                }
                if (!empty($param)) {
                    $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($param) ? http_build_query($param) : $param);
                }
                break;
        }
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置额外配置
        if (!empty($exOptions)) {
            foreach ($exOptions as $k => $v) {
                curl_setopt($ch, $k, $v);
            }
        }
        $response = curl_exec($ch);
        $error = false;
        //看是否有报错
        $errorCode = curl_errno($ch);
        if ($errorCode) {
            $errorMessage = curl_error($ch);
            $error = array('errorCode' => $errorCode, 'errorMessage' => $errorMessage);
            //将报错写入日志文件里
            $logText = "$method $url: [$errorCode]$errorMessage";
            if (!empty($param))
                $logText .= ",$param" . json_encode($param);
            file_put_contents('/data/error.log', $logText);
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 过滤空格，换行
     * @author 郁政
     */
    public static function myTrim($str) {
        $search = array(" ", "　", "\n", "\r", "\t");
        $replace = array("", "", "", "", "");
        return str_replace($search, $replace, $str);
    }

    /**
     * @title : 判断数组是几维数据
     * @author: jiye
     * @time  : 2016年12月20日/下午2:36:02
     */
    public static function getmaxdim($arr) {
        if (!is_array($arr)) {
            return 0;
        } else {
            $dimension = 0;
            foreach ($arr as $item1) {
                $t1 = common::getmaxdim($item1);
                if ($t1 > $dimension) {
                    $dimension = $t1;
                }
            }
            return $dimension + 1;
        }
    }

    /**
     * 数字转汉字
     * @author 朱旭
     */
    public static function ch_num($num,$mode=true)
    {  
        $char = array("零","一","二","三","四","五","六","七","八","九");  
        $dw = array("","十","百","千","","万","亿","兆");  
        $dec = "点";  
        $retval = "";  
        if($mode)  
            preg_match_all("/^0*(\d*)\.?(\d*)/",$num, $ar);  
        else  
            preg_match_all("/(\d*)\.?(\d*)/",$num, $ar);  
        if($ar[2][0] != "")  
            $retval = $dec . $this->ch_num($ar[2][0],false); //如果有小数，先递归处理小数  
        if($ar[1][0] != "") {  
            $str = strrev($ar[1][0]);  
            for($i=0;$i<strlen($str);$i++) {  
                $out[$i] = $char[$str[$i]];  
                if($mode) {  
                    $out[$i] .= $str[$i] != "0"? $dw[$i%4] : "";  
                    if($str[$i]+$str[$i-1] == 0)  
                        $out[$i] = "";  
                    if($i%4 == 0)  
                        $out[$i] .= $dw[4+floor($i/4)];  
                }  
            }  
            $retval = join("",array_reverse($out)) . $retval;  
        }  
        return $retval;  
    }

    /**
     *  二维数组排序操作
     * @author zx
     */
    public static function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){  
        if(is_array($arrays)){  
            foreach ($arrays as $array){  
                if(is_array($array)){  
                    $key_arrays[] = $array[$sort_key];  
                }else{  
                    return false;  
                }  
            }  
        }else{
            return false;  
        } 
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);  
        return $arrays;  
    }

    /**
     * 开启CURL会话
     */
    public static function curl($url,$type,$data='')
    {
        $c = curl_init(); 
        curl_setopt($c, CURLOPT_URL, $url); //请求地址
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); //不输出数据
        //curl_setopt($c, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:200.98.182.163', 'CLIENT-IP:203.98.182.163')); //构造IP 
        //curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com/ "); //构造来路 
        //curl_setopt($c, CURLOPT_HEADER, 0);//如果你想把一个头包含在输出中，设置这个选项为一个非零值。
        if($type == 1){
            curl_setopt($c, CURLOPT_POST, $type);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用。
            curl_setopt($c, CURLOPT_POSTFIELDS, $data);//传递一个作为HTTP “POST”操作的所有数据的字符串。['name'=>'张三']也行
        }
        $out = curl_exec($c); //执行 cURL 会话
        curl_close($c); //关闭 cURL 会话
        
        return $out;
    }

    /**
     * 返回结果
     */
    public static function returnJson($code=200, $message='操作成功', $data='', $json=FALSE)
    {
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        if ($json) {
            return json_encode($return);
        } else {
            return $return;
        }
    }

    /**
     * 调节BUG专用,写入日志文件
     * @param array data 要写入的数据,key为名,value为值
     * @author 朱旭
     */
    public static function writeLog($data)
    {
        if (!$data || !is_array($data)) {
            return fasle;
        }
        $txt = '';
        foreach ($data as $key => $val) {
            $txt .= $key.":".$val."\r\n";
        }
        $txt .= "---------------------------------------------------------------------------\r\n"; //分割一下
        
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', $txt, FILE_APPEND);
    }
}

?>