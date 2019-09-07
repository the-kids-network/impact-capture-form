<?php

namespace App\Utils;

class FileIcons {

    public static function getIcon($fileExtension) {
        switch(strtolower($fileExtension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
                return 'fa-file-image';
            case 'doc':
            case 'docx':
                return 'fa-file-word';
            case 'ppt':
            case 'pptx':
            case 'pps':
                return 'fa-file-powerpoint';
            case 'xls':
            case 'xlsx':
            case 'xlr':
                return 'fa-file-excel';
            case 'pdf':
                return 'fa-file-pdf';
            case 'mp3':
            case 'wav':
            case 'wma':
                return 'fa-file-audio';
            case 'mp4':
            case 'mpg':
            case 'mpeg':
            case 'avi':
            case 'h264':
            case 'm4v':
            case 'mkv':
            case 'mov':
            case 'wmv':
                return 'fa-file-video';
            case 'zip':
            case 'gz':
            case 'tar.gz':
            case 'rar':
            case 'jar':
                return 'fa-file-archive';
            default:
                return 'fa-file-alt';
        }
    }
}