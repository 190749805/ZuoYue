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
	/**
	 * 显示对上传文件的处理
	 */
	public function upLoadFile(){
		$host = 'admin';
		$filename = $_POST['filename'];  
		$user = 'admin';
		$pass = 'wxqwsqhcwrnxrbb';
		echo $filename."<br/>";
		echo $_FILES["file"]["type"]."<br/>";
		if(($_FILES["file"]["type"] == "image/jpeg")){
			if ($_FILES["file"]["error"] > 0){
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}else{
				echo "Upload: " . $_FILES["file"]["name"] . "<br />";
				echo "Type: " . $_FILES["file"]["type"] . "<br />";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
				if (file_exists("UploadFile/" . $_FILES["file"]["name"])){
					echo $_FILES["file"]["name"] . " already exists. ";
				}else{
					move_uploaded_file($_FILES["file"]["tmp_name"],
					"UploadFile/" . $_FILES["file"]["name"]);
				}
			}
		}else{
			echo "Invalid file";
		}
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
	/**
	 * 实现修改文件的属性
	 */
	public function change_type(){
		$file["name"] = $_GET["name"];
		$file["type"] = $_GET["type"];
		dump($file);
	}
	/**
	 * 实现下载功能
	 */
	public function down(){
		$path = './Files/';
		$filename = $path.'theone.pdf';
		$downloadname = 'PHP与MySQL动态网站开发（第4版）.pdf';
		if(file_exists($filename)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$downloadname);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			flush();
			readfile($filename);
			exit;
		}else{
			echo $filename;
		}
	}
	/**
	 * 显示需要删除的文件
	 */
	public function delete(){
		$this->display();
	}
}