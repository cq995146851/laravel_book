<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\Order;
use Illuminate\Http\Request;
use App\Models\M3Result;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    public function aliPay(Request $request) {

        require_once(app_path() . "/Tool/Alipay/alipay.config.php");
        require_once(app_path() . "/Tool/Alipay/lib/alipay_submit.class.php");

        //返回格式
        $format = "xml";
        //必填，不需要修改

        //返回格式
        $v = "2.0";
        //必填，不需要修改

        //请求号
        $req_id = date('Ymdhis');
        //必填，须保证每次请求都是唯一

        //**req_data详细信息**

        //服务器异步通知页面路径
        $notify_url = "http://" . $_SERVER['HTTP_HOST'] . '/service/pay/ali_notify';
        //需http://格式的完整路径，不允许加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $call_back_url = "http://" . $_SERVER['HTTP_HOST'] . '/service/pay/ali_result';
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        //http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/call_back_url.php

        //操作中断返回地址
        $merchant_url = "http://" . $_SERVER['HTTP_HOST'] . '/service/pay/ali_merchant';
        //用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

        //卖家支付宝帐户
        $seller_email = 'william@speakez.cn';
        //必填

        //商户订单号
        $out_trade_no = $_POST['order_no'];
        //商户网站订单系统中唯一订单号，必填
        Log::info('out_trade_no:' . $out_trade_no);

        //订单名称
        $subject = $_POST['name'];
        //必填

        //付款金额
        $total_fee = $_POST['total_price'];
        //必填

        //请求业务参数详细
        $req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
        //必填

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $para_token = array(
            "service" => "alipay.wap.trade.create.direct",
            "partner" => trim($alipay_config['partner']),
            "sec_id" => trim($alipay_config['sign_type']),
            "format"	=> $format,
            "v"	=> $v,
            "req_id"	=> $req_id,
            "req_data"	=> $req_data,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($para_token);
        //URLDECODE返回的信息
        $html_text = urldecode($html_text);
//        return $html_text;

        //解析远程模拟提交后返回的信息
        $para_html_text = $alipaySubmit->parseResponse($html_text);

        //获取request_token
        $request_token = $para_html_text['request_token'];


        /**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        //必填

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "alipay.wap.auth.authAndExecute",
            "partner" => trim($alipay_config['partner']),
            "sec_id" => trim($alipay_config['sign_type']),
            "format"	=> $format,
            "v"	=> $v,
            "req_id"	=> $req_id,
            "req_data"	=> $req_data,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');

        return $html_text;
    }
}
