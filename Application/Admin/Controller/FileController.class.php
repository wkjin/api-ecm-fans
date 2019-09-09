<?php
//文件处理控制器
namespace Admin\Controller;
class FileController extends CommonController {

    protected  $uploadPath = '';//上传的目录
    protected  $rootUrl = '';//根url
    protected  $uploadUrlPath = '';//文件上传的url的前缀

    public function __construct(){
        parent::__construct();
        $filePath = 'Uploads/website/';
        $this->uploadPath = ROOT_PATH . '/' . $filePath;
        $this->rootUrl  = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'] .':' . $_SERVER['SERVER_PORT'];
        $this->uploadUrlPath =  $this->rootUrl . '/' . $filePath;
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
        $this->_upload('wang_', '', null);
    }

    //一般文件上传
    public function fileUpload(){
        $this->_upload('file_', '', null);
    }

    //pdf上传
    public function pdfUpload(){
        $this->_upload('pdf_', 'PDF', function ($fileName){
            return urlencode(preg_replace("/(.*)\.\w+$/" , "\\1" ,$fileName )) . bin2hex(openssl_random_pseudo_bytes(4))  . preg_replace("/.*(\.\w+)$/" , "\\1" ,$fileName );
        });
    }

    //获取一个文件夹中所有的一般文件（不是富文本编辑器文件）
    public function getPDFFils(){
        $basePath = $this->uploadPath . 'PDF/';
        $baseUrl = $this->uploadUrlPath . 'PDF/';
        $fileData = array();
        foreach (scandir($basePath) as $file){
            if($file !== '.' && $file !== '..') {
                $fileNameDecode = urldecode($file);
                array_push($fileData,  [
                    'url' => $this->rootUrl . '/Admin/File/readPDF/' .substr($fileNameDecode, 0 , -12) . '.pdf?hash=' . substr($fileNameDecode, strlen($fileNameDecode)-12,8) ,
                    'name' => $fileNameDecode,
                    'downloadUrl'=> $this->rootUrl . '/Admin/File/downloadPDF?fileName=' . $fileNameDecode,
                    'deleteUrl' => $this->rootUrl. '/Admin/File/removePdfFile?fileName=' . $fileNameDecode,
                ]);
            }
        }
        $this->ajaxSuccess($fileData, '获取pdf列表成功');
    }
    //读取pdf文件
    public function readPDF(){
        if(preg_match("/^.*?\/([^\/]+\.pdf)$/", $_SERVER['PATH_INFO'], $match)){
            $fileName = $match[1];
            $hash = $_GET['hash'];
            $fp = fopen($this->uploadPath . 'PDF/' . urlencode($fileName.substr(0, -4)) . $hash. '.pdf', "r");
            header('Content-type: application/pdf');
            fpassthru($fp);
            fclose($fp);
        }else{
            $this->error('请求的pdf不存在！！！');
        }
    }
    //移除pdf文件
    public  function removePdfFile(){
        $fileName = I('fileName', '', 'string');
        if(empty($fileName)){
            $this->ajaxError(null, '文件名为空：字段名：fileName');
        }else{
            unlink($this->uploadPath . 'PDF/' . urlencode($fileName));
            $this->ajaxSuccess(null, '删除PDF文件成功！！！');
        }
    }
    //pdf文件下载
    public function downloadPDF(){
        $fileName = I('fileName', '', 'string');
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        echo file_get_contents($this->uploadPath . 'PDF/' . urlencode($fileName));
    }
}