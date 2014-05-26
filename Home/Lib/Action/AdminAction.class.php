<?php
/**
 *	时间:2014-04-29
 *	描述:显示管理信息
 */
class AdminAction extends PublicAction {
	/**
	*	实现一个构造方法
	*/
	public function __construct(){
		parent::__construct();
		$this->writeIp();
	}
	/**
		描述:显示index页面
	*/
    public function index(){
		$this->display();
	}
	/**
		获取本机ip
	*/
	public function getIp(){
		if (isset($_ENV["HOSTNAME"])){
			$MachineName = $_ENV["HOSTNAME"]; 
		}else if  (isset($_ENV["COMPUTERNAME"])){
			$MachineName = $_ENV["COMPUTERNAME"]; 
		}else{
			 $MachineName = "";
		} 
		return gethostbyname($MachineName);
	}
	/**
		将获取的ip地址写入文件，以便方式邮件是调用
	*/
	public function writeIp(){
		$filename = "./Home/Conf/ip.txt";
		$ip = $this->getIp();
		if (!$handle = fopen($filename, 'w+')) {
		//	echo "不能打开文件 $filename";
			exit;
		}else{
			if(fwrite($handle, $ip) === FALSE){
			//	echo "不能写入文件 $filename";
				exit;
			}else{
			//	echo "写入文件 $filename";
			}
		}
	}
	/**
		系统表
	*/
	public function system(){
		$this->display();
	}
	/**
		获取系统信息
	*/
	public function getcolumninfo(){
		$user = M("User"); // 实例化User对象
		$data = $user->select();
		$j = 0;
		for($i = 0;$i < count($data);$i ++){
			if($data["$i"]["zt"] == "action"){
				$j ++;
			}
		}
		$system["zcyhs"] = count($data);
		$system["sjyhs"] = $j;
		$j = 0;
		$yxyhs = 0;
		$exyhs = 0;
		$sxyhs = 0;
		for($i = 0;$i < count($data);$i ++){
			if($data["$i"]["online"] == "yes"){
				$j ++;
				if($data["$i"]["dj"] == 1){
					$yxyhs ++;
				}
				if($data["$i"]["dj"] == 2){
					$exyhs ++;
				}
				if($data["$i"]["dj"] == 3){
					$sxyhs ++;
				}
			}
		}
		$system["zxyhs"] = $j;
		$system["qtyhs"] = ($system["zcyhs"] - $system["sjyhs"]);
		$system["yxyhs"] = $yxyhs;
		$system["exyhs"] = $exyhs;
		$system["sxyhs"] = $sxyhs;
	//	var_dump($system);
		$admin = M('Admin');
		$list = $admin->where('id = 1')->save($system);
	//	var_dump($list);
		$this->ajaxReturn($system,'JSON');
	}
	/**
		定时清除session
	*/
	public function cleanSession(){
		$time = strtotime(date("Y-m-d H:i:s"));
		$user = M('User');
		$condition["zt"] = "action";
		$list = $user->where($condition)->select();
		for($i = 0; $i<count($list) ;$i++){
			$time2 = $list[$i]["dlsj"];
			$cha = ((float)($time - strtotime($time2)))/(60);
		//	echo $cha;
			if($cha > 27 ){
				$list[$i]["online"] = "no";
				$user->where('id = '.$list[$i]["id"])->save($list[$i]);
			//	echo "$i";
			}
		}
		$condition["zt"] = "action";
		$condition["online"] = "yes";
		$result = $user->where($condition)->select();
		$num = count($result);
		$this->ajaxReturn($num,'JSON');
	}
	//查询咨询
	public function select_zx(){
		$m=M('zx_information');
		import('ORG.Util.Page');
		$count=$m->count();
		if($count>0){
			$page=new Page($count,10);
			$show=$page->show();
			$arr=$m->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('list',$arr);
			$this->assign('page',$show);
		}
		$this->session = $_SESSION["user_id"];
		//dump($arr);
	}
	//删除咨询
	public function delete_zx(){
		$id=$_GET['id'];
		$m=M('zx_information');
		$arr=$m->where('id='.$id)->delete();
		if($arr>0){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	//查询讨论
	public function select_discuss(){
		import('ORG.Util.Page');
		$title=M('title');
		//$uid=$_SESSION['user_id'];
		$count=$title->count();
		$Page=new Page($count,7);
		$show=$Page->show();
		$arr=$title->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		if($arr>0){
			$this->assign('list',$arr);
			$this->assign('page',$show);
		}else{
			$info = '没有发表任何主题';
			$this->info = $info;
		}
		$this->session_id = $_SESSION['user_id'];
	}
	//删除讨论
	public function delete_discuss(){
		$id=$_GET['id'];
		$m=M('Title');
		$arr=$m->where('id='.$id)->delete();
		if($arr>0){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}