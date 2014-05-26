<?php
/**
	时间:2014-04-29
	描述:显示首页信息
*/
class IndexAction extends PublicAction {
	/**
		实现一个构造方法
	*/
	public function __construct(){
		parent::__construct();
	}
	/**
		描述:显示index页面
	*/
    public function index(){
		$this->select_zx();
		$this->select_discuss();
		$this->display();
	}
	//显示咨询
	public function select_zx(){
		$m=M('Zx_information');
		$arr=$m->order('id desc')->limit(0,10)->select();
		//dump($arr);
		if($arr>0){
			$this->assign('list',$arr);
		}
	}
	//显示讨论
	public function select_discuss(){
		$m=M('Title');
		$arr=$m->order('id desc')->limit(0,5)->select();
		if($arr>0){
			$this->assign('va',$arr);
		}
	}
}