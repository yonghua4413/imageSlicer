<?php
/**
 * Created by PhpStorm.
 * User: yonghua
 * Date: 2017/10/18
 * Time: 9:46
 */
class Slicer
{
    private $imgUrl;
    private $imgName;
    private $thumbImgArr = [];
    private $imgHeight;
    private $imgWidth;
    private $thumbNum;
    private $per=200;
    private $minPX = 500;
    private $lastHeight;
    private $typeIndex;
    private $typeArr = [1 => 'gif',2 => 'jpeg',3 => 'png'];

    /**
     * Slicer constructor.
     * @param string $url 图片地址
     */
    public function __construct($url='')
    {
        if(!file_exists($url)){
            exit('文件不存在！');
        }
        $this->imgUrl = $url;
        //获取图片信息
        $imgInfo = getimagesize($this->imgUrl);
        $this->imgWidth = $imgInfo[0];
        $this->imgHeight = $imgInfo[1];
        $this->typeIndex = $imgInfo[2];
        $this->thumbNum = $this->getThumbNum();
        $this->lastHeight = $this->getLastHeight();
        $this->imgName = $this->getImageName();
    }

    /**
     * 计算应该切割的张数
     * @return int
     */
    private function getThumbNum(){
        if($this->imgHeight <= $this->minPX){
            return 1;
        }
        return  floor($this->imgHeight/$this->per);
    }

    /**
     * 获取最后一张的高度
     * @return int
     */
    private function getLastHeight(){
        if($this->imgHeight <= $this->minPX){
            return 0;
        }
        return  $this->imgHeight - ($this->per)*$this->thumbNum;
    }

    private function getImageName(){
        $name = basename($this->imgUrl);
        return explode('.', $name)[0];
    }

    public function make(){
        //如果不满足切割条件
        if($this->thumbNum == 1){
            $this->thumbImgArr[0] = $this->imgUrl;
            return $this->thumbImgArr;
        }else{
            //创建一张原类型的图片
            $funName = 'imagecreatefrom'.$this->typeArr[$this->typeIndex];
            $img = $funName($this->imgUrl);
            for($i = 0; $i < $this->thumbNum; $i++){
                $this->thumbImgArr[$i] = $this->mkThumbImg($i,$img);
            }
            $this->thumbImgArr[$this->thumbNum]=$this->mkLastThumbImg($img);
            return $this->thumbImgArr;
        }
    }

    /**
     * 返回图片名称
     * @return string
     */
    private function mkThumbImg($i, $img){
        $croped = imagecreatetruecolor($this->imgWidth, $this->per);
        imagecopy($croped, $img, 0, 0, 0, $this->per*($i), $this->imgWidth, $this->per);
        $fileName = "{$this->imgName}_$i.{$this->typeArr[$this->typeIndex]}";
        $typeName = 'image'.$this->typeArr[$this->typeIndex];
        $typeName($croped, $fileName);
        imagedestroy($croped);
        return $fileName;
    }

    /**
     * 制作最后一张
     * @param $img
     * @return string
     */
    private function mkLastThumbImg($img){
        $croped = imagecreatetruecolor($this->imgWidth, $this->lastHeight);
        imagecopy($croped, $img, 0, 0, 0, $this->per*($this->thumbNum), $this->imgWidth, $this->lastHeight);
        $fileName = "{$this->imgName}_$this->thumbNum.{$this->typeArr[$this->typeIndex]}";
        $typeName = 'image'.$this->typeArr[$this->typeIndex];
        $typeName($croped, $fileName);
        imagedestroy($croped);
        return $fileName;
    }

}
