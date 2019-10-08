<?php

namespace ccheng\eventmanager\common\libs;

require_once('cos/include.php');

use ccheng\eventmanager\common\libs\cos\Api;
use ccheng\eventmanager\common\libs\cos\Auth;
use Qcloud\Cos\Client;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * 简单封装了一下cos的sdk，在这里进行require 以及初始化配置信息
 * Class QCloudSdk
 *
 * @package common\libs
 */
class QCloudSdk
{
    /**
     * 文件完整路径(bucket, app_id, path, filename, sign)
     */
    const FILE_URL = "http://%s-%s.cos.myqcloud.com/%s/%s?sign=%s";

    /**
     * @param $bucket
     * @param $path
     *
     * @return array|mixed
     */
    public static function stat($bucket, $path)
    {
        return self::getApi()->stat($bucket, $path);
    }

    /**
     * @param      $bucket
     * @param      $srcPath
     * @param      $dstPath
     * @param null $bizAttr
     * @param null $sliceSize
     * @param null $insertOnly
     *
     * @return array|mixed
     */
    public static function upload($bucket, $srcPath, $dstPath, $bizAttr = null, $sliceSize = null, $insertOnly = null)
    {
        return self::getApi()->upload($bucket, $srcPath, $dstPath, $bizAttr, $sliceSize, $insertOnly);
    }

    public static function deleteFile($bucket, $path)
    {
        return self::getApi()->delFile($bucket, $path);
    }

    public static function listFile($bucket, $srcPath)
    {
        return self::getApi()->listFolder($bucket, $srcPath, 100);
    }

    public static function createFolder($bucket, $folder, $bizAttr = null)
    {
        return self::getApi()->createFolder($bucket, $folder, $bizAttr = null);
    }

    public static function deleteFolder($bucket, $folder)
    {
        return self::getApi()->delFolder($bucket, $folder);
    }

    /**
     * 获取签名
     *
     * @param $expired
     * @param $bucketName
     *
     * @return string
     */
    public static function getSign($expired, $bucketName)
    {
        return self::getAuth()->createReusableSignature($expired, $bucketName);
    }

    /**
     * @return mixed|null|string
     */
    public static function getKeyValue()
    {

        return self::getQcloudConfig()['q_cloud_config'];
    }

    public static function getQcloudConfig()
    {
        return call_user_func(\Yii::$app->params['qcloud_config']);
    }

    public static function getExternalAccessConfig()
    {
        return self::getQcloudConfig()['q_cloud_config_for_external'];
    }

    /**
     * @return \QCloud\Cos\Api
     */
    public static function getApi()
    {
        $config = self::getKeyValue();

        return new Api([
            'app_id'     => $config->app_id,
            'secret_id'  => $config->secret_id,
            'secret_key' => $config->secret_key,
            'region'     => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
            'timeout'    => 60,
        ]);
    }

    /**
     * @return \QCloud\Cos\Auth
     */
    public static function getAuth()
    {
        $config = self::getKeyValue();

        return new Auth($config->app_id, $config->secret_id, $config->secret_key);
    }

    public static function getSignUrl($url, $expired)
    {
        if (empty($url)) {
            return $url;
        }

        $url_array = parse_url($url, PHP_URL_HOST);
        if (empty($url_array)) {
            return $url;
        }

        $host = ArrayHelper::getValue($url_array, 'host', '');
        if (empty($host)) {
            return $url;
        }

        $config = self::getKeyValue();
        if (empty($config->bucket_rules)) {
            $config->bucket_rules = ['\b(?<bucketName>[a-zA-Z0-9-_]+)-\d{5,20}\..*myqcloud\.com'];
        }

        $bucketName = null;

        foreach ($config->bucket_rules as $bucket_rule) {
            $res = preg_match($bucket_rule, $host, $match);
            var_dump($match);
            if ($res) {
                $bucketName = $match['bucketName'];
            }
        }

        if (empty($bucketName)) {
            return $url;
        }

        $sign = self::getSign($expired, $bucketName);

        return sprintf("%s://%s%s?sign=%s", $url_array['scheme'], $url_array['host'], $url_array['path'], $sign);

    }

    /**
     * qcloud/cos-sdk-v5
     *
     * @param string $url
     * @param mixed  $expire default 10 minute
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function signAccessUrl($url, $expire = '+10 minute')
    {

        $client = new Client(self::getExternalAccessConfig());

        $result = self::parseUrl($url);
        if (!$result) {
            return $url;
        }

        list($bucket, $path) = $result;

        return $client->getObjectUrl($bucket, $path, $expire);
    }

    /**
     * @param string $url
     *
     * @return bool|array [$bucket, $path]
     */
    protected static function parseUrl($url)
    {
        $pattern = '/^https?:\/\/([a-zA-Z0-9\-]+)\..*\.myqcloud\.com(.*)$/';
        if (preg_match($pattern, $url, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return false;
    }
}
