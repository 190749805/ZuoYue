<?php 
class DiscussAction extends PublicAction{
	/**
		实现一个构造方法
	*/
	public function __construct(){
		parent::__construct();
	}
	//显示讨论的页面
	public function index(){
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
			$info = '你还没有发表任何主题';
			$this->info = $info;
		}
		$this->session_id = $_SESSION['user_id'];
		$this->display();
	}
	//向数据库里插入主题
	public function commit_title(){
		if($_SESSION['user_id']!=null){
			$data['uid']=$_SESSION['user_id'];
			$data['tcontent']=$_POST['tcontent'];
			$data['time']=date('Y:m:d:H:i:s');
			$data['ttitle']=$_POST['ttitle'];
			//dump($time);
			$title=M('title');
			$arr=$title->add($data);
			if($arr>0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}
	}
	//显示评论页面
	public function view_comment(){
		import('ORG.Util.Page');
		$tid=$_GET['id'];
		$title=M('Title');
		$comment=D('Comment');
		$arr1=$title->where("id=".$tid)->select();
		$count=$comment->where("tid=".$tid)->count();
		$page=new Page($count,5);
		$show=$page->show();
		$arr2=$comment->relation(true)->where("tid=".$tid)->limit($page->firstRow.','.$page->listRows)->select();
		//$this->assign('title',$arr1);
		/* $comment=D('Comment');
		$arr2=$comment->relation(true)->where("tid=".$tid)->select(); */
		//dump($arr1);
		//dump($arr2);
		$arr=array();
		foreach($arr2 as $value){
			$value['title']=null;
			$arr[]=$value;
			//dump($value);
		}
		$arr3=array();
		foreach($arr1 as $value){
			$value['comment']=$arr;
			$arr3[]=$value;
		}
		//dump($arr3);
		$this->assign('list',$arr3);
		$this->assign('page',$show);
		$temp_uid["id"] = $arr3["0"]["uid"];
		$user = M('User');
		$username = $user->where($temp_uid)->select();
	//	dump($username);
		$this->name = $username["0"]["username"];
		$this->display();
	}
	//储存提交过来的回复
	public function commit_reply(){
		$data['ccontent']=$_POST['ccontent'];
		$data['ctime']=date('Y:m:d:H:i:s');
		$data['cid']=$_POST['cid'];
		$uid=$_SESSION["id"];
		$data['cusername']=$_SESSION[$uid];
		if($data['cusername']==null){
			echo -1;
		}else{
			$comment=M('Comment');
			$arr=$comment->add($data);
			//dump($arr);
			if($arr>0){
				$arr1=$comment->where("id=".$arr)->select();
				if($arr1>0){
					$data['id']=$arr1[0]['id'];
					$data['cid']=$arr1[0]['cid'];
					echo json_encode($data);
				}else{
					echo "";
				}
			}else{
				echo "";
			}
		}
	}
	//接收提交过来的评论
	public function commit_comment(){
		if($_SESSION['user_id']!=null){
			$data['ccontent']=$_POST['ccontent'];
			$data['tid']=$_POST['tid'];
			$data['ctime']=date('Y:m:d:H:i:s');
			$uid=$_SESSION["id"];
			$data['cusername']=$_SESSION[$uid];
			$comment=M('Comment');
			$arr=$comment->add($data);
			if($arr>0){
				$title=M('Title');
				$data1['plcs']=$title->where("id=".$data['tid'])->getField('plcs')+1;
				$data1['id']=$data['tid'];
				$arr1=$title->save($data1);
				if($arr1>0){
					echo 1;
				}else{
					echo 2;
					//dump($title->getField('plcs'));
				}
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}
	} 
	//记录浏览次数
	public function title_cs(){
		$id=$_POST['id'];
		$title=M('Title');
		$data['llcs']=$title->where("id=".$id)->getField('llcs')+1;
		$data['id']=$id;
		$arr=$title->save($data);
		if($arr>0){
			echo 1;
		}else{
			echo 0;
		}
	}
	/**
	 * 显示错误信息
	 */
	public function error(){
		$this->display();
	}
}