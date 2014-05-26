<?php
/**
	时间:2014-04-29
	描述:公共的类
*/
class PublicAction extends Action {
	/**
		实现一个构造方法
	*/
	public function __construct(){
		parent::__construct();
		$uid = $_SESSION["id"];
		if($_SESSION[$uid] === '' ||  $_SESSION[$uid] == null){
			$show["info1"] ="<a href='__APP__/Login/index'>登录</a>";
			$show["info2"] ="<a href='__APP__/Registre/index'>注册</a>";
		}else{
			
			$user = M('User');
			$id = $_SESSION["user_id"];
			$result = $user->where('id = '.$id)->select(); 
		
			if($result[0]["online"] === "no"){
				
				$show["info1"] ="<a href='__APP__/Login/index'>登录</a>";
				$show["info2"] ="<a href='__APP__/Registre/index'>注册</a>";
				
			}else{
				
				$time = strtotime(date("Y-m-d H:i:s"));
			
				$time2 = strtotime($result[0]["dlsj"]);
			
				$cha = ((float)($time - $time2))/(60);
			
				if($cha < 27 ){
					
					$show["info1"] =$_SESSION[$uid];
					$show["info2"] ="<a href='__APP__/Login/destroy'>注销</a>";
				
				}else{
					
					$result[0]["online"] = "no";
					$result = $user->where('id = '.$result[0]["id"])->save($result[0]); 
					session_destroy();
					$show["info1"] ="<a href='__APP__/Login/index'>登录</a>";
					$show["info2"] ="<a href='__APP__/Registre/index'>注册</a>";
				
				}
			}
		}
		$this->shows = $show;
	}
}