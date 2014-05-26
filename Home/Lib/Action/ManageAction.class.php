<?php
/**
	时间:2014-04-29
	描述:显示首页信息
*/
class ShareAction extends PublicAction {
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
		$this->display();
	}
	public function upload(){
		$this->display();
	}
	public function showall(){
		$this->display();
	}
	public function download(){
		$this->display();
	}
	public function change(){
		$this->display();
	}
}