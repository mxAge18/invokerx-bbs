<?php
/**
 * Created by PhpStorm.
 * User: invokerx
 * Date: 2020/9/10
 * Time: 11:29 AM
 */

namespace App\Handlers;

use Illuminate\Support\Str;
class ImageUploadHandler
{
    // 设置允许上传的类型
    protected $allowed_ext = ['jpg', 'png', 'gif', 'jpeg'];

    // 保存图片功能
    public function save($file, $folder, $file_prefix)
    {

        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        $upload_path = public_path() . "/" . $folder_name;

        $extension = strtolower($file->getClientOriginalExtension()) ? : "png";

        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }

}