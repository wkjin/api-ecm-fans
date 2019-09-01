<?php
//文件处理控制器
namespace Admin\Controller;
class FileController extends CommonController {

    protected  $uploadPath = '';//上传的目录
    protected  $uploadUrlPath = '';//文件上传的url的前缀

    public function __construct(){
        parent::__construct();
        $filePath = 'Uploads/website/';
        $this->uploadPath = ROOT_PATH . '/' . $filePath;
        $this->uploadUrlPath = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'] .':' . $_SERVER['SERVER_PORT'] . '/' . $filePath;
    }

    //移动文件
    protected function moveFile($tempFile, $uploadFile){
        if (move_uploaded_file($tempFile, $uploadFile)) {
            return true;
        } else {
            return false;
        }
    }

    //定义wangEditor返回
    private function _wangEditorReturn($errno, $fileUrl, $message){
        $data = array();
        if(is_string($fileUrl)){
            array_push($data, $fileUrl);
        }else if(is_array($fileUrl)){
            $data = array_merge($data, $fileUrl);
        }
        $this->ajaxReturn(array(
            'errno' => $errno,
            'data' => $data,
            'message' => $message
        ),'JSON');
    }
    //wangEditor成功返回
    protected function wangSuccess($fileUrl){
        $this->_wangEditorReturn(0, $fileUrl, '文件上传成功');
    }
    //wangEditor错误返回
    protected function wangError($message){
        $this->_wangEditorReturn(1, null, $message);
    }

    //上传
    private function _upload($filePrefix, $saveDirName='', $fileNameDealFunc){
        if(empty($_FILES)){
            $this->wangError('上传文件为空');
        }else{
            $fileData = array();
            $saveDirName = $saveDirName.trim('/');
            if(is_string($saveDirName) && $saveDirName !== ''){
                $saveDirName .= '/';
            }
            foreach ($_FILES as $file){
                if(!is_callable($fileNameDealFunc)){
                    $fileName = uniqid($filePrefix, true) . preg_replace("/.*(\.\w+)$/" , "\\1" ,$file['name'] );
                }else{
                    $fileName = $fileNameDealFunc($file['name']);
                }
                if($this->moveFile($file['tmp_name'], $this->uploadPath . $saveDirName . $fileName)){
                    array_push($fileData, $this->uploadUrlPath . $fileName);
                }
            }
            if(count($fileData) > 0){
                $this->wangSuccess($fileData);
            }else{
                $this->wangError('上传不成功');
            }
        }
    }

    //wangEdtor上传使用
    public function wangUpload(){
        $this->_upload('wang_');
    }

    //一般文件上传
    public function fileUpload(){
        $this->_upload('file_');
    }

    //pdf上传
    public function pdfUpload(){
        $this->_upload('pdf_', 'PDF', function ($fileName){
            return urlencode(preg_replace("/(.*)\.\w+$/" , "\\1" ,$fileName )) . preg_replace("/.*(\.\w+)$/" , "\\1" ,$fileName );
        });
    }

    public function test($func){
        $a = $func();
        var_dump($a);
    }
    //获取一个文件夹中所有的一般文件（不是富文本编辑器文件）
    public function getPDFFils(){
        $basePath = $this->uploadPath . 'PDF/';
        $baseUrl = $this->uploadUrlPath . 'PDF/';
        $fileData = array();
        foreach (scandir($basePath) as $file){
            if($file !== '.' && $file !== '..') {
                array_push($fileData,  ['url' => $baseUrl .$file, 'name' => urldecode($file)]);
            }
        }
        $this->_wangEditorReturn(0, $fileData, '获取pdf列表成功');
    }
    //移除pdf文件
    public  function removePdfFile(){
        $fileName = I('post.fileName', '', 'string').trim('/');
        if(!empty($fileName)){
            $this->wangError('文件名为空：字段名：fileName');
        }else{
            unlink($this->uploadPath . 'PDF/' + $fileName);
            $this->_wangEditorReturn(0, null, '删除PDF文件成功！！！');
        }
    }

}