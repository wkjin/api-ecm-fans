<?php
namespace Home\Controller;
//header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Origin: http://www.ecm-fans.com");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie");
class UserController extends CommonController {
    //发送邮件主体
    private function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
        $config = C('THINK_EMAIL');
        Vendor('PHPMailer.PHPMailerAutoload'); //从PHPMailer目录导class.phpmailer.php类文件
        $mail = new \PHPMailer(); //PHPMailer对象
        $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP(); // 设定使用SMTP服务
        $mail->SMTPDebug = 0; // 关闭SMTP调试功能
        $mail->SMTPAuth = true; // 启用 SMTP 验证功能
        $mail->SMTPSecure = 'ssl'; // 使用安全协议
        $mail->Host = $config['SMTP_HOST']; // SMTP 服务器
        $mail->Port = $config['SMTP_PORT']; // SMTP服务器的端口号
        $mail->Username = $config['SMTP_USER']; // SMTP服务器用户名
        $mail->Password = $config['SMTP_PASS']; // SMTP服务器密码
        $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
        $replyEmail = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
        $replyName = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
        $mail->AddReplyTo($replyEmail, $replyName);
        $mail->Subject = $subject;
        $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
        $mail->MsgHTML($body);
        $mail->AddAddress($to, $name);
        if(is_array($attachment)){ // 添加附件
            foreach ($attachment as $file){
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    //调用发送邮件
    private function  sendEmail($body){
        return $this->think_send_mail(
            '573807412@qq.com',
            '浩沅官网在线留言提醒',
            '浩沅官网在线留言',
             $body,
            null
        );
    }

    //生成验证码
    public function createCheckCode(){
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

    //验证验证码
    private function checkCheckcode($code){
        if(empty($code)){
            return false;
        }else{
            $code = I('code');
            $verify = new \Think\Verify();
            if($verify->check($code)){
                return true;
            }else{
                return false;
            }
        }
    }

    //接收数据
    public function onlineMessage(){
        $data = I('post.');
        if(empty($data) || empty($data['code'])){
            $this->ajaxError(null, '验证码为空');
            return;
        }
        if(!$this->checkCheckcode($data['code'])){
            $this->ajaxError(null,'验证码错误');
            return;
        }else{
            unset($data['code']);
        }
        $bodyHtml= '用户名：' . $data['username'] . '<br>' .
            '手机号：' . $data['telphone'] . '<br>' .
            '邮箱：' . $data['email'] . '<br>' .
            '公司名称：' . $data['companyName'] . '<br>';
        $res = $this->sendEmail($bodyHtml);
        if(is_bool($res) && $res === true){
            $this->ajaxSuccess(null, '留言成功');
        }else{
            $this->ajaxError($res, '留言失败');
        }
    }
}