# imageSlicer 图片切割 长图切割

需要开启gd库
php5.5

#用法
require '/Slicer.php';
$obj = new Slicer('2.jpg');
$list = $obj->make();

#返回值 arr
array(6) {
  [0]=>
  string(8) "2_0.jpeg"
  [1]=>
  string(8) "2_1.jpeg"
  [2]=>
  string(8) "2_2.jpeg"
  [3]=>
  string(8) "2_3.jpeg"
  [4]=>
  string(8) "2_4.jpeg"
  [5]=>
  string(8) "2_5.jpeg"
}
