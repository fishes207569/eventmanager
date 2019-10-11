<?php
namespace ccheng\eventmanager\common\UEditor;
use common\helpers\StringHelper;
use ccheng\eventmanager\common\libs\QCloudSdk;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class UEditorAction extends \kucha\ueditor\UEditorAction
{
    /**
     * 处理action
     */
    protected function handleAction()
    {
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'config':
                $result = $this->config;
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
            $result = $this->uploadFileToCos();
                break;
            /* 列出图片 */
            case 'listimage':
                /* 列出文件 */
            case 'listfile':
                $result = $this->actionList();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->actionCrawler();
                break;

            default:
                $result = [
                    'state' => '请求地址出错',
                ];
                break;
        }

        /* 输出结果 */

        return $result;

    }

    /**
     * 上传
     *
     * @return array
     */
    protected function actionUpload()
    {
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $config    = [
                    "pathRoot"   => ArrayHelper::getValue($this->config, "imageRoot", $_SERVER['DOCUMENT_ROOT']),
                    "pathFormat" => $this->config['imagePathFormat'],
                    "maxSize"    => $this->config['imageMaxSize'],
                    "allowFiles" => $this->config['imageAllowFiles'],
                ];
                $fieldName = $this->config['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config    = [
                    "pathRoot"   => ArrayHelper::getValue($this->config, "scrawlRoot", $_SERVER['DOCUMENT_ROOT']),
                    "pathFormat" => $this->config['scrawlPathFormat'],
                    "maxSize"    => $this->config['scrawlMaxSize'],
                    "allowFiles" => $this->config['scrawlAllowFiles'],
                    "oriName"    => "scrawl.png",
                ];
                $fieldName = $this->config['scrawlFieldName'];
                $base64    = "base64";
                break;
            case 'uploadvideo':
                $config    = [
                    "pathRoot"   => ArrayHelper::getValue($this->config, "videoRoot", $_SERVER['DOCUMENT_ROOT']),
                    "pathFormat" => $this->config['videoPathFormat'],
                    "maxSize"    => $this->config['videoMaxSize'],
                    "allowFiles" => $this->config['videoAllowFiles'],
                ];
                $fieldName = $this->config['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config    = [
                    "pathRoot"   => ArrayHelper::getValue($this->config, "fileRoot", $_SERVER['DOCUMENT_ROOT']),
                    "pathFormat" => $this->config['filePathFormat'],
                    "maxSize"    => $this->config['fileMaxSize'],
                    "allowFiles" => $this->config['fileAllowFiles'],
                ];
                $fieldName = $this->config['fileFieldName'];
                break;
        }
        /* 生成上传实例对象并完成上传 */

        $up = new Uploader($fieldName, $config, $base64);
        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */

        return $up;
    }

    protected function uploadFileToCos()
    {
        /** @var $up Uploader */
        $up = $this->actionUpload();
        $file_path=$up->getFilePath();
        $file_info=$up->getFileInfo();
        $qcloud_config=QCloudSdk::getKeyValue();
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        $ret = QCloudSdk::upload($qcloud_config->bucket, $file_path, '/event_images/' . StringHelper::genUniqueString() . $extension);
        if (isset($ret['code'])) {
            if ($ret['code'] == 0) {
                $file_info['url']=QCloudSdk::signAccessUrl($ret['data']['access_url'],'+2 year');
            } else {
                throw new Exception(sprintf("上传到到腾讯云失败 - %s", $ret['message']));
            }
        } else {
            throw new Exception(sprintf("上传到腾讯云失败,返回结果异常 - %s", json_encode($ret)));
        }
        return $file_info;
    }
}