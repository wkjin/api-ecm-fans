<?php
namespace Common\Service;
class ProductService  extends  CommonService {
    public function __construct(){
        parent::__construct('product');
    }

    //做联合查询用
    private function tj(){
        $this->tableObj = $this->tableObj
            ->alias('p')
            ->join('left join __ARTICLE__ as a on p.article_id = a.id')
            ->join('left join __CATEGORY__ as c on c.id = a.category_id')
            ->field('a.*, p.*, c.name_cn as c_name_cn, c.name_en as c_name_en');
        return $this;
    }

    //需要与文章表联合查询获取产品信息
    public function getProducts($where = [], $pageNow = 1, $pageSize = 999){
        return $this->tj()->getList($where, $pageNow, $pageSize, 'sort asc, p.id desc');
    }

    //需要与文章表联合查询获取产品信息
    public function getProductDetail($where = []){
        return $this->tj()->findItem($where);
    }

    //更新产品
    public function updateProduct($productId, $data){
        //查询产品信息，补充数据
        $productData = $this->findItem(['id' => intval($productId)]);
        if(is_array($productData)){
            $classifyData = $this->classifyData($data);
            $articleService = new ArticleService();
            $articleId = $productData['article_id'];
            $res = $articleService->updateArticle(['id' => $articleId], $classifyData['article']);
            //更新文章信息
            if(is_bool($res)){
                return $res;
            }else if($res > 0 ){//不管什么时候都应该成功（更新时间改变）
                $this->update(['id' => $productData['id']], $classifyData['product']);
                return $res;
            }else{
                return $res;
            }
        }else{
            return false;
        }
    }

    //添加产品
    public function addProduct($data){
        if(!is_array($data)){
            return false;
        }else{
            $classifyData = $this->classifyData($data);
            $articleService = new ArticleService();
            $insertId = $articleService->addArticle($classifyData['article']);
            if(is_int($insertId)){
                if($insertId > 0){
                    return $this->insert(array_merge($classifyData['product'], ['article_id' => $insertId]));
                }else{
                    return $insertId;
                }
            }else{
                return false;
            }
        }
    }

    //把数据分类['product'=> null, 'article'=> null]
    protected function classifyData($data){
        if(!is_array($data)){
            return null;
        }
        $articleColumn = [
            'thumb_en',
            'thumb_cn',
            'category_id',
            'title_cn',
            'title_en',
            'sort',
            'status',
            'abstract_cn',
            'abstract_en',
            'content_cn',
            'content_en'
        ];
        $productColumn = [
            'impeller_diameter',
            'air_volume',
            'power_category',
            'voltage',
            'frequency',
            'atmospheric_pressure',
            'speed',
            'phase_number',
            'specifications'
        ];
        $productData = [];
        $articleData = [];
        foreach ($data as $key => $value) {
            if(in_array($key, $productColumn)){
                $productData[$key] = $value;
            }else if(in_array($key, $articleColumn)){
                $articleData[$key] = $value;
            }
        }
        $articleData['is_article'] = 0;//标注为非文章
        return [
            'product' => $productData,
            'article' => $articleData
        ];
    }

    //删除产品
    public function  removeProduct(){
        $productId = I('productId');
        $productData = $this->findItem(['id' => intval($productId)]);
        if(is_array($productData) && isset($productData['article_id'])){
            //删除产品信息（如果删除附表的信息成功，才删除产品信息）
            $res = $this->remove(['id' => $productData['id']]);
            if($res){
                $articleService = new ArticleService();
                $articleService->removeArticle(['id' => $productData['article_id'], 'is_article' => 0]);
                //删除文章信息
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}