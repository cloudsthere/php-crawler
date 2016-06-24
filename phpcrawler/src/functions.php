<?php

namespace phpcrawler;

function deldir($dir) {
    //先删除目录下的文件：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
      if($file!="." && $file!="..") {
        $fullpath=$dir."/".$file;
        if(!is_dir($fullpath)) {
            unlink($fullpath);
        } else {
            deldir($fullpath);
        }
      }
    }
   
    closedir($dh);
    //删除当前文件夹：
    if(rmdir($dir)) {
      return true;
    } else {
      return false;
    }
}


function createAbsolutePath($source, $referer, $prefix = ''){
    if(empty($referer))
        return $source;
    // 绝对路径
    if($source[0] == '/')
        return $prefix.$source;
    if($source[0] != '.')
        $source = './'.$source;

    $referer_info = parse_url($referer);
    $source_info = parse_url($source);

    $referer_path_stack = explode('/', $referer_info['path']);
    $source_path_stack = explode('/', $source_info['path']);

    array_pop($referer_path_stack);
    foreach($source_path_stack as  $node){
        $source_path_collector = [];
        if($node == '.'){
            // array_pop($referer_path_stack);
        }
        elseif($node == '..'){
            // var_export($referer_path_stack);
            // array_splice($referer_path_stack, -2, 2);
            array_pop($referer_path_stack);
            // var_export($referer_path_stack);
            if(empty($referer_path_stack)){
                return false;
            }
        }
        else{
            array_push($source_path_collector, $node);
        }
        $referer_path_stack = array_merge($referer_path_stack, $source_path_collector);
    }
    return $prefix.implode('/', $referer_path_stack);

}


function fileToDir($filename){
    $content = file_get_contents($filename);
    unlink($filename);
    mkdir($filename, 0755);
    file_put_contents($filename.'/index.html', $content);
}

function mkdir_recursive($dirname, $chain = []){
    if(empty($dirname) && empty($chain))
        return;
    if(empty($chain))
        $chain[] = $dirname;

    $res = @mkdir($dirname, 0755, true);
    if(!$res){
        $parent = dirname($dirname);
        // 避免死循环
        if($parent == $dirname)
            return false;
        if(file_exists($dirname) && !is_dir($dirname)){
            fileToDir($dirname);
            array_shift($chain);
            return mkdir_recursive(current($chain), $chain);
        }else{
            array_unshift($chain, $parent);
            return mkdir_recursive($parent, $chain);
        }
    }else{
        array_shift($chain);
        return mkdir_recursive(current($chain), $chain);
    }
}

