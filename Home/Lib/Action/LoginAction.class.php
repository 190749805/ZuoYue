<?php
/**
 *	时间:2014-04-29
 *	描述:登录相关
 */
class LoginAction extends PublicAction {
	private $pjhm;
	private $pass;
	/**
	 *	实现一个构造方法
	 */
	public function __construct(){
		parent::__construct();
	}
	/**
	 *	描述:显示登录页面
	 */
    public function index(){
		$this->display();
	}
	/**
	 * 显示忘记密码的界面
	 */
	public function forget_pass(){
		$this->display();
	}
	/**
	 * 找回密码
	 */
	public function search_pass(){
		$data["username"] = $_POST["username"];
		if($data["username"] === ""){
			$this->error('邮箱不能为空');
		}else{
			$user = M('User');
			$temp = $user->where($data)->select();
			if($temp === NULL){
				$this->error_info = "账号".$data["username"]."不存在";
				$this->display('error');
			}else{
				$time = date('Y-m-d H:i:s');
				$this->pjhm = md5($time.$temp["0"]["jhm"]);
				$user_temp = M('Temp');
				$user_data["username"] = $temp["0"]["username"];
				$user_data["pjhm"] = $this->pjhm;
				$user_data["time"] = $time;
				$result = $user_temp->add($user_data);
				if($result){
				
				}else{
					$this->error('系统发生错误');
				}
				$this->pass = $temp["0"]["pass"];
				$ip = $this->readIp();
				$email = $temp["0"]["username"];
				$email_temp = explode('@',$email);
				$email_temp_url = explode('.',$email_temp["1"]);
				$email_url = '';
				switch($email_temp_url["0"]){
					case "outlook":
						$email_url = 'https://login.live.com/login.srf?wa=wsignin1.0&ct=1400943881&rver=6.1.6206.0&sa=1&ntprob=-1&wp=MBI_SSL_SHARED&wreply=https:%2F%2Fmail.live.com%2F%3Fowa%3D1%26owasuffix%3Dowa%252f&id=64855&snsc=1&cbcxt=mail';
						break;
					case "qq":
						$email_url = 'https://mail.qq.com/';
						break;
					case "gmail":
						$email_url = 'https://gmail.com/';
						break;
					default:
						$email_url = 'https://www.google.com.hk/';
						break;
				}
				$this->emailurl = $email_url; 
				$this->success_info = "账号".$data["username"]."存在";
				$this->display('success');
				$status = $this->mailer($temp["0"]["username"],$this->pjhm,$ip,$this->pass);
			}
		}
	}
	/**
	 *	实现登录功能
	 */
	public function login(){
		$data["username"] = '';
		$data["password"] = '';
		$data["username"] = $_POST["username"];
		$data["password"] = $_POST["password"];
	//	var_dump($data);
		if($data["username"] === '' || $data["password"] === ''){
			$this->error("用户名，密码不能为空");
		}else{
			$user = M("User");
			$list = $user->where($data)->select();
			if(count($list) < 1){
				$this->error("用户名或密码错误");
			}elseif($list[0]["zt"] == "noaction"){
				$this->error("账户未被激活");
			}elseif($list[0]["online"] === "yes"){
				$this->error("账户已登录");
			}else{
				if(md5($list[0]["zcrq"].$data["password"]) == $list[0]["pass"]){
					session_start();
					$time = date("Y-m-d H:i:s");
					$list[0]["dlsj"] = $time;
					$list[0]["online"] = "yes";
					$result = $user->where('id = '.$list[0]["id"])->save($list[0]); 
					$uid = $list[0]["jhm"];
					$_SESSION["user_id"] = $list[0]["id"];
					$_SESSION["id"] = $uid;
					$_SESSION["$uid"] = $list[0]["username"];
					$this->display();
				}else{
					$this->error("用户名或密码错误");
				}
			}
		}
	}
	/**
		注销session
	*/
	public function destroy(){
		$data["id"] = $_SESSION["user_id"];
		$uid = $_SESSION["id"];
		$data["username"] = $_SESSION["$uid"];
		$user = M("User");
		$list = $user->where($data)->select();
		$list[0]["online"] = "no";
		$result = $user->where('id = '.$list[0]["id"])->save($list[0]); 
		session_destroy();
		$this->display();
	}
	/**
	 * 发送邮件，找回密码
	 */
	public function mailer($email,$pjhm,$ip,$pass){
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
		$mail->Subject    = '用户密码修改邮件';                     // 设置邮件标题
		$mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 
													// 可选项，向下兼容考虑
		$mail->MsgHTML("<a href='http://$ip/zuoyue/index.php/Login/change_pass/user/$email/jhm/$pjhm/pass/$pass'>修改密码(此邮件24小时内有效)</a>");                         // 设置邮件内容
		$mail->AddAddress($email, "尊敬的用户");
		if(!$mail->Send()) {
			$status["status"] = '失败';
			
		} else {
			$status["status"] = '成功';
			
		}
		return $status;
	}
	/**
	 * 读取ip;
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
	/**
	 *
	 */
	public function change_pass(){
		$data["username"] = $_GET["user"];
		$data["pass"] = $_GET["pass"];
		$data["pjhm"] = $_GET["jhm"];
		$time1 = strtotime(date('Y-m-d H:i:s'));
		$user_temp = M('Temp');
		$result = $user_temp->where($data)->select();
		if($result === NULL){
		
		}else{
			$data["time"] = $result["0"]["time"];
			$time2 = strtotime($data["time"]);
		}
		if($result["0"]["change"] == 1){
			$this->error('验证以过期，请重新验证','__URL__/forget_pass');
		}else{
			$cha = ((float)($time1 - $time2))/(60)/(60);
			if($cha > 24){
				$this->display('change_pass_error');
			}else{
				$this->user_name = $result["0"]["username"];
				$this->display('change_pass_success');
			}
		}
	}
	/**
	 * 实现修改密码
	 */
	public function change_pass_action(){
		$temp_data["username"] = $_POST["username"];
		$data["pass"] = $_POST["password1"];
		$temp_pass = $_POST["password2"];
		if($data["pass"] == '' || $temp_pass == '' || $temp_pass != $data["pass"]){
			$this->error('输入错误');
		}else{
		//	dump($data);
			$data["username"] = $temp_data["username"];
			$user = M('User');
			$user_temp = $user->where($temp_data)->select();
			if($user_temp){
				$data["pass"] = md5($user_temp["0"]["zcrq"].$data["pass"]);
			}else{
				dump($user_temp);
			//	$this->error("系统发生错误");
			}
			$result = $user->where($temp_data)->save($data);
			if($result){
				$temp = M('Temp');
				$temp_change["change"] = 1;
				$temp_change["username"] = $temp_data["username"];
				$temp->where($temp_data)->save($temp_change);
				$this->success("密码修改成功",'__URL__/index');
			}else{
				$this->error("密码不能与原密码一致");
			}
		}
	}
	//显示修改密码界面
	public function view_passwd(){
		if($_SESSION['user_id']!=null){
			$this->display();
		}else{
			$this->error('请先登录');
		}
	}
	//修改密码
	public function update_passwd(){
		$m=M('User');
		$id=$_SESSION['user_id'];
		$ary=$m->where('id='.$id)->field('pass,zcrq')->find();
		 /* dump($ary);
		dump(md5($ary['zcrq'].$_POST['password']));
		exit;  */
		if(md5($ary['zcrq'].$_POST['password'])==$ary['pass']){
			if($_POST['newpassword']==$_POST['confirmpassword']){
				$data['pass']=md5($ary['zcrq'].$_POST['newpassword']);
				$data['id']=$id;
				$arr=$m->save($data);
				if($arr>0){
					$this->success('修改密码成功','__APP__/Index/index');
				}else{
					$this->error('修改密码失败');
				}
			}else{
				$this->error('确认密码不正确');
			}
		}else{
			$this->error('亲，你输入的原密码不正确');
		}
	}
}