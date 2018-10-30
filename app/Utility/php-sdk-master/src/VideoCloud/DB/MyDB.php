<?php

namespace VideoCloud\DB;
use SQLite3;

/**
 * 数据库查询类，主要负责数据库操作
 */
final class MyDB extends SQLite3
{
    
    public function __construct()
    {
        $this->open('vcloud.db');
    }


    /**
     * 新建表
     * @param {dbhandle} $db 数据库
     */
    public static function createTable($db)
    {
        $sql =<<<EOF
            CREATE TABLE IF NOT EXISTS UploadFile
            (filepath TEXT,
            mtime INTEGER,
            filesize INTEGER,
            nos_context VARCHAR(256),
            nos_bucket VARCHAR(64),
            nos_object VARCHAR(256),
            nos_token TEXT,
            created INTEGER
            );
EOF;

        $ret = $db->exec($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }

    }

   /**
     * 判断文件是否已存在（是否需要续传）
     * @param {array} $fileInfo 文件信息
     * @param {dbhandle} $db 数据库     
     */

    public static function checkExist($fileInfo,$db)
     {
        $where = 'WHERE filepath = "' . $fileInfo['filepath'] . '" and mtime = ' .$fileInfo['mtime'] . ' and filesize = ' . $fileInfo['filesize'];
        $sql   = 'SELECT COUNT(*) count FROM UploadFile ' . $where;

        $ret = $db->query($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }else{
            $row = $ret->fetchArray(SQLITE3_ASSOC);
            if($row['count']>0){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 保存文件
     * @param {array} $fileData 文件信息
     * @param {dbhandle} $db 数据库     
     */

    public static function saveFile($fileData,$db)
     {
       
        $values = 'VALUES ("' . $fileData['filepath'] . '","' . $fileData['mtime'] .'","'. $fileData['filesize'] .'","' . $fileData['created'] .'","'. $fileData['nos_token'] .'","'. $fileData['nos_object'] .'","'. $fileData['nos_bucket'] .'", "")';
        $sql    = 'INSERT INTO UploadFile (filepath, mtime, filesize, created, nos_token, nos_object, nos_bucket, nos_context) '. $values;

        $ret = $db->query($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }
    }

    /**
     * 获取文件
     * @param {array} $fileInfo 文件信息
     * @param {dbhandle} $db 数据库     
     */
    public static function getFile($fileInfo,$db)
     {
       
        $where = 'WHERE filepath = "' . $fileInfo['filepath'] . '" and mtime = ' . $fileInfo['mtime'] . ' and filesize = ' . $fileInfo['filesize'];
        $sql   = 'SELECT * FROM UploadFile '. $where;

        $ret = $db->query($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }else{
            $row = $ret->fetchArray(SQLITE3_ASSOC);
            return $row;
        }
    }

    /**
     * 保存Context
     * @param {array} $fileData 文件信息
     * @param {dbhandle} $db 数据库     
     */
    public static function saveContext($fileData,$db)
     {
       
        $where = 'WHERE filepath = "' . $fileData['filepath'] . '" and mtime = ' . $fileData['mtime'] . ' and filesize = ' . $fileData['filesize'];
        $sql   = 'UPDATE UploadFile set nos_context = "'. $fileData['nos_context'] . '"' . $where;

        $ret = $db->exec($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }
    }

    /**
     * 上传成功后删除文件
     * @param {array} $fileData 文件信息
     * @param {dbhandle} $db 数据库     
     */
    public static function removeFile($fileData,$db)
     {
       
        $where = 'WHERE filepath = "' . $fileData['filepath'] . '" and mtime = ' . $fileData['mtime'] . ' and filesize = ' . $fileData['filesize'];
        $sql   = 'DELETE FROM UploadFile '. $where;

        $ret = $db->query($sql);
        if(!$ret){
            die($db->lastErrorMsg());
        }
        $db->close();
    }
    
}
