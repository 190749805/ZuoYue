<?php
/**
 *	时间:2014-04-29
 *	描述:显示首页信息
 */
class RegistreAction extends PublicAction {
	private $email;
	private $password;
	private $passwordtoo;
	/**
		实现一个构造方法
	*/
	public function __construct(){
		parent::__construct();
	}
	/**
		描述:显示注册页面
	*/
    public function index(){
		$this->display();
	}
	/**
		实现注册方法
	*/
	public function registre(){
		$ip = '';
		$status["status"] = '失败';
		$status["info1"] = '/已有账号，点击<a href="__APP__/Login/index">登录</a>';
		$status["info2"] = '<br/><a href="__URL__/index">返回注册页面</a>';
		$status["info3"] = '';
		$status["info4"] = '系统检测到您填写的信息有误！请重新填写<br/><br/><br/>';
		$this->email = '';
		$this->password = '';
		$this->passwordtoo = '';
		$this->email = $_POST["email"];
		$this->password = $_POST["password"];
		$this->passwordtoo = $_POST["passwordtoo"];
		if($this->email === '' || $this->password === '' ||$this->passwordtoo === '' ||$this->password !== $this->passwordtoo){
			$this->error('必填信息不能为空');
		}else if(ereg("/^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i",$this->email)){
			$this->error('填写不符约定');
		}else{
			$user = M("User");
			$time = date("Y-m-d H:i:s");
			$data["username"] = $this->email;
			$data["pass"] = md5($time.$this->password);
			$data["zcrq"] = $time;
			$data["jhm"] = md5($time.$date["username"]);  
			$data["zt"] = 'noaction';
			$data["jf"] = 0;
			$data["dj"] = 1;
			$data["grkjm"] = $this->email;
			$data["kjsydx"] = 0;
			if($lastInsId = $user->add($data)){
				$ip = $this->readIp();
				$status = $this->mailer($this->email,$data["jhm"],$ip);
			} else {
				$this->error('数据写入错误！');
			}	
		}
	//	echo $this->email;
	//	var_dump($status);
		$this->statu = $status;
		$this->display();
	}
	/**
		发送邮件的函数
	*/
	public function mailer($email,$jhm,$ip){
		require_once('./Plus/mail/class.phpmailer.php');
		require_once("./Plus/mail/class.smtp.php"); 
		$mail  = new PHPMailer(); 

		$mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
		$mail->IsSMTP();                            // 设定使用SMTP服务
		$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
		$mail->SMTPSecure = "ssl";                  // SMTP 安全协议
		$mail->Host       = "smtp.gmail.com";       // SMTP 服务器
		$mail->Port       = 465;                    // SMTP服务器的端口号
		$mail->Username   = "yijianlingchen@gmail.com";  // SMTP服务器用户名
		$mail->Password   = "Wxqwsqh2011";        // SMTP服务器密码
		$mail->SetFrom('yijianlingchen@gmail', 'ZuoYue管理员');    // 设置发件人地址和名称
		$mail->AddReplyTo($email,"尊敬的用户"); 
													// 设置邮件回复人地址和名称
		$mail->Subject    = '用户激活邮件';                     // 设置邮件标题
		$mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 
													// 可选项，向下兼容考虑
		$mail->MsgHTML("<a href='http://$ip/zuoyue/index.php/Registre/action/user/$email/jhm/$jhm'>点击激活账户</a>");                         // 设置邮件内容
		$mail->AddAddress($email, "尊敬的用户");
		//$mail->AddAttachment("images/phpmailer.gif"); // 附件 
		if(!$mail->Send()) {
			$status["status"] = '失败';
			$status["info1"] = '/已有账号，点击<a href="__APP__/Login/index">登录</a>';
			$status["info2"] = '<br/><br/>系统发送邮件失败<br/>';
			$status["info3"] = '<br/>';
			$status["info4"] = '注：如果未在您的收件箱中找到，请查看垃圾邮件箱。此邮件为系统自动发送，请勿回复！';
		} else {
			$status["status"] = '成功';
			$status["info1"] = '/已有账号，点击<a href="__APP__/Login/index">登录</a>';
			$status["info2"] = '系统已将激活邮件发送至您填写的邮箱中，请激活以后才能使用该账户。';
			$status["info3"] = '没有收到邮件，<a href="__URL__/remailer">点击重新发送！</a>';
			$status["info4"] = '注：如果未在您的收件箱中找到，请查看垃圾邮件箱。此邮件为系统自动发送，请勿回复！';
		}
		return $status;
	}
	/**
		重新发送邮件
	*/
	public function remailer(){
		$status = $this->mailer($this->email);
		$this->statu = $status;
		$this->display(registre);
	}
	/**
		激活邮箱的界面
	*/
	public function action(){
		$status["status"] = '失败';
		$status["info1"] = '/已有账号，点击<a href="__APP__/Login/index">登录</a>';
		$status["info2"] = '激活账户失败。';
		$status["info3"] = '<a href="__APP__/Login/index">点击登录！</a>';
		$status["info4"] = '对不起，激活失败了！';
		$data["username"] = $_GET["user"];
		$data["jhm"] = $_GET["jhm"];
	//	var_dump($data);
		$user = M("User");
		$list = $user->where($data)->select();
	//	var_dump($list[0]);
		if(count($list) < 1){
			;
		}else{
			$list[0]["zt"] = "action";
		//	var_dump($list[0]);
			$result = $user->save($list[0]);
		//	var_dump($result);
			if(count($result) === false){
				;
			}else{
				$status["status"] = '成功';
				$status["info1"] = '/已有账号，点击<a href="__APP__/Login/index">登录</a></br/></br/>';
				$status["info2"] = '</br/>激活账户成功。';
				$status["info3"] = '<a href="__APP__/Login/index">点击登录！</a>';
				$status["info4"] = '现在您可以使用该账户了！';
			}	
		}
		$this->statu = $status;
		$this->display();
	}
	/**
		获取ip，将其写入到mailer函数中
	*/
	public function readIp(){
		$ip = '';
		$filename = "./Home/Conf/ip.txt";
		$file = fopen($filename, "r") or exit("Unable to open file!");
		while(!feof($file)){
			$ip = fgets($file);
		}
		fclose($file);
		return $ip;
	}
}