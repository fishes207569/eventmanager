<?php
namespace ccheng\eventmanager\helpers;

class ImageHelper
{
    public static function img_base64($file)
    {
        $type         = getimagesize($file);
        $fp           = fopen($file, "r");
        $file_content = chunk_split(base64_encode(fread($fp, filesize($file))));
        switch ($type[2]) {
            case 1:
                $img_type = "gif";
                break;
            case 2:
                $img_type = "jpg";
                break;
            case 3:
                $img_type = "png";
                break;
        }
        $img = 'data:image/' . $img_type . ';base64,' . $file_content;
        fclose($fp);

        return $img;
    }

    public static function myGetImageSize($url, $type = 'curl', $isGetFilesize = true)
    {
        // 若需要获取图片体积大小则默认使用fread 方式
        $type = $isGetFilesize ? 'fread' : $type;
        if ($type == 'fread') {
            // 或者使用 socket 二进制方式读取, 需要获取图片体积大小最好使用此方法
            $handle = fopen($url, 'rb');
            if (!$handle) {
                return false;
            }
            // 只取头部固定长度168字节数据
            $dataBlock = fread($handle, 168);
        } else {
            // 据说 CURL 能缓存DNS 效率比 socket 高
            $ch = curl_init($url);// 超时设置
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // 取前面 168 个字符通过四张测试图读取宽高结果都没有问题,若获取不到数据可适当加大数值
            curl_setopt($ch, CURLOPT_RANGE, '0-167');// 跟踪301跳转
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// 返回结果
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $dataBlock = curl_exec($ch);
            curl_close($ch);
            if (!$dataBlock) {
                return false;
            }
        }
        // 将读取的图片信息转化为图片路径并获取图片信息,经测试,这里的转化设置 jpeg 对获取png,gif的信息没有影响,无须分别设置
        // 有些图片虽然可以在浏览器查看但实际已被损坏可能无法解析信息
        $size = getimagesize('data://image/jpeg;base64,' . base64_encode($dataBlock));
        if (empty($size)) {
            return false;
        }
        $result['width']  = $size[0];
        $result['height'] = $size[1];
        // 是否获取图片体积大小
        if ($isGetFilesize) {
            // 获取文件数据流信息
            $meta = stream_get_meta_data($handle);
            // nginx 的信息保存在 headers 里，apache 则直接在 wrapper_data
            $dataInfo = isset($meta['wrapper_data']['headers']) ? $meta['wrapper_data']['headers'] :
                $meta['wrapper_data'];
            foreach ($dataInfo as $va) {
                if (preg_match('/length/iU', $va)) {
                    $ts             = explode(':', $va);
                    $result['size'] = trim(array_pop($ts));
                    break;
                }
            }
        }
        if ($type == 'fread') {
            fclose($handle);
        }

        return $result;
    }

    public static function getImageScale($srcWidth, $srcHeigth, $maxWidth = 300, $maxHeigth = 300)
    {
        if($srcWidth>$maxWidth && $srcHeigth>$maxHeigth){
            $Scale            = ($srcWidth / $maxWidth) > ($srcHeigth / $maxHeigth) ? ($srcWidth / $maxWidth) :
                ($srcHeigth / $maxHeigth);
            $result['width']  = floor($srcWidth / $Scale);
            $result['height'] = floor($srcHeigth / $Scale);
        }else{
            $result['width']  = $srcWidth;
            $result['height'] = $srcHeigth;
        }


        return $result;
    }
}
