<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\TempEmail;
use App\Entity\TempPhone;
use App\Models\M3Email;
use App\Tool\UUID;
use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\Member;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    //注册
    public function register(Request $request)
    {
        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $password = $request->input('password', '');
        $confirm = $request->input('confirm', '');
        $phone_code = $request->input('phone_code', '');
        $validate_code = $request->input('validate_code', '');

        $m3_result = new M3Result;

        if ($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }
        if ($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = '密码不少于6位';
            return $m3_result->toJson();
        }
        if ($confirm == '' || strlen($confirm) < 6) {
            $m3_result->status = 3;
            $m3_result->message = '确认密码不少于6位';
            return $m3_result->toJson();
        }
        if ($password != $confirm) {
            $m3_result->status = 4;
            $m3_result->message = '两次密码不相同';
            return $m3_result->toJson();
        }

        // 手机号注册
        if ($phone != '') {
            $isExist = Member::query()->where('phone',$phone)->first();
            if(!empty($isExist)){
                $m3_result->status = 10;
                $m3_result->message = '手机号已经被注册';
                return $m3_result->toJson();
            }
            if ($phone_code == '' || strlen($phone_code) != 6) {
                $m3_result->status = 5;
                $m3_result->message = '手机验证码为6位';
                return $m3_result->toJson();
            }
            $tempPhone = TempPhone::query()->where('phone', $phone)->first();
            if ($tempPhone->code == $phone_code) {
                if (time() > strtotime($tempPhone->deadline)) {
                    $m3_result->status = 6;
                    $m3_result->message = '手机验证码不正确';
                    return $m3_result->toJson();
                }

                $member = new Member();
                $member->phone = $phone;
                $member->password = md5('book' . $password);
                $member->created_at = date('Y-m-d H:i:s', time());
                $member->save();

                $m3_result->status = 0;
                $m3_result->message = '注册成功';
                return $m3_result->toJson();
            } else {
                $m3_result->status = 6;
                $m3_result->message = '手机验证码不正确';
                return $m3_result->toJson();
            }
        }else{
            $isExist = Member::query()->where('email',$email)->first();
            if(!empty($isExist)){
                $m3_result->status = 11;
                $m3_result->message = '邮箱已经被注册';
                return $m3_result->toJson();
            }
            if($validate_code == '' || strlen($validate_code) != 4){
                $m3_result->status = 7;
                $m3_result->message = '验证码为4位';
                return $m3_result->toJson();
            }
            $validate_code_session = $request->session()->get('validate_code');
            if($validate_code_session != $validate_code){
                $m3_result->status = 8;
                $m3_result->message = '验证码不正确';
                return $m3_result->toJson();
            }

            $member = new Member();
            $member->email = $email;
            $member->password = md5('book'.$password);
            $member->save();

            //发送邮件
            $uuid = UUID::create();
            $m3_email = new M3Email();
            $m3_email->to = $email;
            $m3_email->cc = '1842484949@qq.com';
            $m3_email->subject = '晓琳书店验证，来自哥哥的测试';
            $m3_email->content = '请于24小时点击该链接完成验证. http://www.book.com/service/validate_email'
                        . '?member_id=' . $member->id . '&code=' . $uuid;
            //保存邮件信息
            $tempEmail = new TempEmail();
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24*60*60);
            $tempEmail->save();

            //发送邮件
            Mail::send('email_register',['m3_email' => $m3_email], function ($m) use ($m3_email){
                $m->to($m3_email->to)
//                    ->cc($m3_email->cc)
                    ->subject($m3_email->subject);
            });
            $m3_result->status = 12;
            $m3_result->message = '激活邮件已经发送至您的邮箱,请立即激活';
            return $m3_result->toJson();
        }
    }

    //登录
    public function login(Request $request)
    {
        $username = $request->input('username','');
        $password = $request->input('password', '');
        $validate_code = $request->input('validate_code', '');

        //校验
        $m3_result = new M3Result();
        if($validate_code == '' || strlen($validate_code) != 4){
            $m3_result->status = 1;
            $m3_result->message = '验证码为4位';
            return $m3_result->toJson();
        }
        $validate_code_session = $request->session()->get('validate_code');
        if($validate_code_session != $validate_code){
            $m3_result->status = 2;
            $m3_result->message = '验证码不正确';
            return $m3_result->toJson();
        }
        if(strpos($username,'@') !== false){
            $member = Member::query()->where('email',$username)->first();
        }else{
            $member = Member::query()->where('phone',$username)->first();
        }
        if(empty($member)){
            $m3_result->status = 3;
            $m3_result->message = '该用户不存在';
            return $m3_result->toJson();
        }else{
            if(md5('book'.$password) != $member->password){
                $m3_result->status = 4;
                $m3_result->message = '密码错误';
                return $m3_result->toJson();
            }
        }

        $request->session()->put('member',$member);
        $m3_result->status = 0;
        $m3_result->message = '登录成功';
        return $m3_result->toJson();
    }

}
