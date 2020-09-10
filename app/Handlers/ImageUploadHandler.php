<?php
/**
 * Created by PhpStorm.
 * User: invokerx
 * Date: 2020/9/10
 * Time: 11:29 AM
 */

namespace App\Handlers;

use Illuminate\Support\Str;
use Image;

class ImageUploadHandler
{
    // 设置允许上传的类型
    protected $allowed_ext = ['jpg', 'png', 'gif', 'jpeg'];

    // 保存图片功能
    public function save($file, $folder, $file_prefix, $max_width = false)
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
        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }

    public function reduceSize($file_path, $max_width)
    {
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();

        });

        // 对图片修改后进行保存
        $image->save();
    }

}