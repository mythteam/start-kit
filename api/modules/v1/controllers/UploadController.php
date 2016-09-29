<?php

namespace api\modules\v1\controllers;

use api\rest\Controller;
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Yaconf;
use Yii;
use yii\validators\RangeValidator;
use yii\web\UploadedFile;

class UploadController extends Controller
{
    protected function excepts()
    {
        return [
            'token',
            'callback',
            'test-upload',
            'file-delete',
            'files',
        ];
    }

    /**
     * Generate qiniu upload token.
     *
     * @SWG\Get(
     *    path = "/upload/token",
     *    tags = {"upload"},
     *    operationId = "getUploadToken",
     *    summary = "获取七牛上传token",
     *    description = "此接口不需要传入参数, token过期时间是*3600s*, `data`值为生成的*token*",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\Parameter(
     *        in = "query",
     *        name = "source",
     *        description = "上传文件类型: `img` 或者 `voice`, 默认可为空,那么则为 `img` 类型, 当上传声音文件时需要指定为 `voice`",
     *        required = true,
     *        type = "string",
     *        default = "img",
     *        enum = {"img", "voice"}
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     * @param string $source
     * @param Auth $auth
     *
     * @return string
     */
    public function actionToken(
        $source = 'img',
        Auth $auth
    ) {
        $validator = new RangeValidator(['range' => ['img', 'voice']]);

        $validator->validate($source, $err);
        if ($err) {
            return $this->sendFaildValidation($err);
        }

        $policy = [
            'callbackUrl' => url(['/v1/upload/callback'], true),
            'callbackBody' => json_encode([
                'fname' => '$(fname)',
                'fkey' => '$(key)',
                'desc' => '$(x:desc)',
                'source' => $source,
            ]),
        ];
        $buckets = [
            'img' => Yaconf::get('kit.qn.bucket_img'),
            'voice' => Yaconf::get('kit.qn.bucket_voice'),
        ];
        return [
            'token' => $auth->uploadToken($buckets[$source], null, 3600, $policy),
        ];
    }

    /**
     * 七牛异步回调处理.
     *
     * @return string
     */
    public function actionCallback()
    {
        $_data = file_get_contents('php://input');
        $_data = json_decode($_data, true);

        $prefix = [
            'img' => Yaconf::get('kit.qn.img_bucket_prefix'),
            'voice' => Yaconf::get('kit.qn.voice_bucket_prefix'),
        ];

        $prefix = $prefix[$_data['source']];
        $prefix = rtrim($prefix, '/');

        return [
            'avatar' => urldecode($prefix . '/' . $_data['fkey']),
        ];
    }

    /**
     * Test upload via swagger
     *
     * @SWG\Post(
     *    path = "/upload/test",
     *    tags = {"upload"},
     *    operationId = "test-upload",
     *    summary = "测试上传文件",
     *    description = "测试上传文件",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "token",
     *        description = "上传Token",
     *        required = true,
     *        type = "string",
     *    ),
     *    @SWG\parameter(
     *        in = "formData",
     *        name = "file",
     *        description = "file",
     *        required = true,
     *        type = "file"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     * @param FileNamingResolver $resolver
     * @param UploadManager $upload
     *
     * @return array
     * @throws \Exception
     */
    public function actionTestUpload(
        FileNamingResolver $resolver,
        UploadManager $upload
    ) {
        $file = UploadedFile::getInstanceByName('file');

        $fileInfo = new FileInfo($file->name);
        $key = $resolver->resolveName($fileInfo);
        $key = trim($key, '/');

        list($ret, $err) = $upload->putFile(app()->request->post('token'), $key, $file->tempName);

        if ($err) {
            return [
                'errcode' => 1,
                'errmsg' => $err->message(),
            ];
        }
        return [
            'avatar' => $ret['data'],
        ];
    }

    /**
     * 列出Bucket的中的所有文件
     *
     * @SWG\Get(
     *    path = "/upload/files",
     *    tags = {"upload"},
     *    operationId = "ListUploadFiles",
     *    summary = "列出Bucket中的所有文件",
     *    description = "description",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\Parameter(
     *        in = "query",
     *        name = "bucket",
     *        description = "指定空间。",
     *        required = true,
     *        type = "string",
     *        default = "yuban",
     *        enum = {"yuban"}
     *    ),
     *    @SWG\Parameter(
     *        in = "query",
     *        name = "prefix",
     *        description = "指定前缀，只有资源名匹配该前缀的资源会被列出。缺省值为空字符串。",
     *        required = false,
     *        type = "string"
     *    ),
     *    @SWG\Parameter(
     *        in = "query",
     *        name = "marker",
     *        description = "上一次列举返回的位置标记，作为本次列举的起点信息。缺省值为空字符串。",
     *        required = false,
     *        type = "string"
     *    ),
     *    @SWG\Parameter(
     *        in = "query",
     *        name = "limit",
     *        description = "本次列举的条目数，范围为1-1000。缺省值为1000。",
     *        required = false,
     *        type = "string",
     *        default = "10"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     * @param BucketManager $bucketManager
     * @param string $bucket
     * @param string $prefix
     * @param string $marker
     * @param int $limit
     *
     * @return mixed
     */
    public function actionFiles(
        BucketManager $bucketManager,
        $bucket = 'yuban',
        $prefix = '',
        $marker = '',
        $limit = 10
    ) {

        list($items, $marker, $err) = $bucketManager->listFiles($bucket, $prefix, $marker, $limit);

        return [
            'items' => $items,
            'marker' => $marker,
        ];
    }

    /**
     * Delete file by key
     *
     * @SWG\Post(
     *    path = "/upload/file-delete",
     *    tags = {"upload"},
     *    operationId = "DeleteFile",
     *    summary = "删除文件",
     *    description = "根据Key删除文件",
     *    produces = {"application/json"},
     *    consumes = {"application/json"},
     *    @SWG\Parameter(
     *        in = "formData",
     *        name = "key",
     *        description = "文件对应的KEY",
     *        required = true,
     *        type = "string"
     *    ),
     *    @SWG\Response(response = 200, description = "success")
     * )
     *
     * @param  BucketManager $bucketManager
     *
     * @return mixed
     */
    public function actionFileDelete(
        BucketManager $bucketManager
    ) {
        $bucket = Yaconf::get('kit.qn.bucket_img');
        $result = $bucketManager->delete($bucket, request()->post('key'));
        if ($result) {
            return [
                'errcode' => 1,
                'errmsg' => $result->message(),
            ];
        }
        return;
    }
}
