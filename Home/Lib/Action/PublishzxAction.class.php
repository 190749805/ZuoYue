<?PHP 
class PublishzxAction extends PublicAction{
	//显示咨询这个页面
	public function index(){
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
		$this->display();
	}
	//接收图片
	public function upload_photo(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 5242880 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath ='./Uploads/Image/';
		//$upload->thumbRemoveOrigin = true;
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			$data['uid']=$_SESSION['user_id'];
			$data['title']=$_POST['title'];
			$data['desc']=$_POST['content'];
			$data['photo']=$info[0]['savepath'].$info[0]['savename'];
			$this->add_zx($data);
		}
	}
	//接收的数据插入数据库
	public function add_zx($data){
		$data['pub_date']=date('Y-m-d H:i:s');
		$m=M('zx_information');
		$arr=$m->add($data);
		if($arr>0){
			$this->success('发表成功');
		}else{
			$this->error('发表失败');
		}
	}
	//显示咨询评论页面
	public function view_zx(){
		$m=M('Zx_information');
		$id=$_GET['id'];
		$arr=$m->where('id='.$id)->find();
		//dump($arr);
		if($arr>0){
			$this->assign('va',$arr);
			$comment=M('Zx_comment');
			import('ORG.Util.Page');
			$count=$comment->count();
			if($count>0){
				$page=new Page($count,10);
				$show=$page->show();
				$arr=$comment->join('zy_zx_information on zy_zx_comment.zx_id=zy_zx_information.id')->where('zx_id='.$arr['id'])->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('list',$arr);
				$this->assign('page',$show);
			}
		}
		$this->display();
	}
	//接收评论并保存在数据库
	public function add_comment(){
		if($_SESSION['user_id']!=null){
			$data['zx_id']=$_POST['zx_id'];
			$data['comment']=$_POST['comment'];
			$data['time']=date('Y-m-d H:i:s');
			$comment=M('Zx_comment');
			$arr=$comment->add($data);
			//dump($data);
			if($arr>0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}
	}
	/**
	 * 显示错误信息
	 */
	public function error(){
		$this->display();
	}
}