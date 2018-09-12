<?php

namespace App\Http\Controllers\Service;

use App\Entity\Member;
use App\Entity\TempEmail;
use App\Entity\TempPhone;
use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use Illuminate\Http\Request;
use App\Models\M3Result;

class ValidateController extends Controller
{
    //生成验证码
    public function create(Request $request)
    {
        $validateCode = new ValidateCode();
        $request->session()->put('validate_code', $validateCode->getCode());
        return $validateCode->doimg();
    }
    //发送手机验证码
    public function sendSms(Request $request)
    {
        $m3_result = new M3Result();
        $phone = $request->input('phone','');
        if($phone == ''){
            $m3_result->status = 1;
            $m3_result->message = '手机号码不能为空';
            return $m3_result->toJson();
        }
        if(strlen($phone) != 11 || $phone[0] != '1'){
            $m3_result->status = 2;
            $m3_result->message = '手机号码格式不正确';
            return $m3_result->toJson();
        }
        $code = '';
        $charset = '1234567890';
        $_len = strlen($charset) - 1;
        for ($i = 0;$i < 6;++$i) {
            $code .= $charset[mt_rand(0, $_len)];
        }
        $sendTemplateSMS = new SendTemplateSMS();
        $m3_result = $sendTemplateSMS->sendTemplateSMS($phone, array($code, 60), 1);
        if($m3_result->status == 0){
            $tempPhone = TempPhone::query()->where('phone', $phone)->first();
            if($tempPhone == null) {
                $tempPhone = new TempPhone;
            }
            $tempPhone->phone = $phone;
            $tempPhone->code = $code;
            $tempPhone->deadline = date('Y-m-d H:i:s', time()+60*60);
            $tempPhone->save();
        }
        return $m3_result->toJson();
    }

    //激活邮箱
    public function validateEmail(Request $request)
    {
        $member_id = $request->input('member_id','');
        $code = $request->input('code','');
        $tempEmail = TempEmail::query()->where('member_id',$member_id)->first();
        if(empty($tempEmail)){
            $url= '/register';
            $content = '验证异常';
            $success = false;
            return view('email_register_confirm',compact('url','content','success'));
        }
        if($tempEmail->code != $code || time()>strtotime($tempEmail->deadline)) {
            $url= '/register';
            $content = '链接已失效';
            $success = false;
            return view('email_register_confirm',compact('url','content','success'));
        }

        $member = Member::query()->find($member_id);
        $member->active = 1;
        $member->save();
        $url= '/login';
        $success = true;
        $content = '注册成功,正在跳转...';
        return view('email_register_confirm',compact('url','content','success'));
    }

}
