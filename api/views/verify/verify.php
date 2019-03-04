<?php


//创建空白画板
$img=imagecreate(50, 24);
imagecolorallocate($img, 200, 200, 204);//创建（背景）颜色

//随机获取4为验证码
$code="QWERTYUIPMNBVCXADFGHJKLabcdefghjmnqrtycx9875432";
$verify='';//验证码

for($i=0;$i<4;$i++){
	$index=rand(0,strlen($code)-1);
	$tmp=$code[$index];
	$verify.=$tmp;
	$color=imagecolorallocate($img, 0, 0, 0);
	imagestring($img,5, 13*$i+2, rand(2,8), $tmp, $color);
	//imagettftext($img, 20, rand(-30,30), $i*20+10, rand(25,35), $color, $tmp);
}



//随机干扰点
for($i=0;$i<250;$i++){
	$color=imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
	imagesetpixel($img, rand(0,100), rand(0,40), $color);
}

// //画干扰直线
// $lnum=rand(2,4);
// for($i=0;$i<$lnum;$i++){
// 	$color=imagecolorallocate($img, rand(0,160), rand(0,100), rand(0,100));
// 	imageline($img, rand(6,20), rand(4,10), rand(10,70),rand(10,30), $color);

// }
		
    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数)
     *
     *      高中的数学公式咋都忘了涅，写出来
     *        正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     *
     */
        $px = $py = 0;
        // 曲线前部分
        $A = mt_rand(1, 12);                  // 振幅
        $b = mt_rand(-6, 6);   // Y轴方向偏移量
        $f = mt_rand(-6, 6);   // X轴方向偏移量
        $T = mt_rand(24, 25);  // 周期
        $w = (2 * M_PI) / $T;

        $px1 = 0;  // 曲线横坐标起始位置
        $px2 = mt_rand(25, 40);  // 曲线横坐标结束位置

        $color = imagecolorallocate($img, mt_rand(0,255), mt_rand(0, 255), mt_rand(0, 255));
        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if ($w != 0) {
                $py = $A * sin($w * $px + $f) + $b + 12;  // y = Asin(ωx+φ) + b
                $i = 1;
                while ($i > 0) {
                    imagesetpixel($img, $px + $i, $py + $i, $color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }

        // 曲线后部分
        $A = mt_rand(1, 12);                  // 振幅
        $f = mt_rand(-6,6);   // X轴方向偏移量
        $T = mt_rand(24,25);  // 周期
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - 12;
        $px1 = $px2;
        $px2 = 25;

        $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if ($w != 0) {
                $py = $A * sin($w * $px + $f) + $b + 12;  // y = Asin(ωx+φ) + b
                $i = 3;
                while ($i > 0) {
                    imagesetpixel($img, $px + $i, $py + $i, $color);
                    $i--;
                }
            }
        }
    


//将文件按照图像输出到浏览器
header("content-type:image/png");

//输出画板
imagepng($img);

//清除图像资源
imagedestroy($img);


//将验证码存入cookie供登录验证,5分钟内有效
setcookie("verify",$verify,time()+300);

  
    