<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 15-1-28
 * Time: 上午10:37
 */

/**
 * Class SFTPConnection
 * SFTP上传
 */
class SFTPConnection
{
    private $connection;
    private $sftp;

    public function __construct($host, $port = 22)
    {
        $this->connection = ssh2_connect($host, $port);
        if (!$this->connection)
            throw new Exception("Could not connect to $host on port $port.");
    }

    /**
     * @param $username
     * @param $password
     * @throws Exception
     * sftp验证用户名和密码
     */
    public function login($username, $password)
    {
        if (!@ssh2_auth_password($this->connection, $username, $password))
            throw new Exception("Could not authenticate with username $username " .
                "and password $password.");

        $this->sftp = @ssh2_sftp($this->connection);
        if (!$this->sftp)
            throw new Exception("Could not initialize SFTP subsystem.");
    }

    /**
     * @param $local_file
     * @param $remote_file
     * @throws Exception
     * 通过ssh2_scp_send上传
     */
    public function uploadFile($local_file, $remote_file)
    {
        $connection = $this->connection;
        $sendbol = ssh2_scp_send($connection, $local_file, $remote_file); //上传文件
        if (!$sendbol) {
            throw new Exception("Could not send file");
        }
//        else {
//            echo "upload success";
//        }

    }

    /**
     * @param $local_file
     * @param $remote_file
     * @throws Exception
     */
    public function  uploadFile1($local_file, $remote_file)
    {
        $sftp = $this->sftp;
        $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

        if (!$stream)
            throw new Exception("Could not open file: $remote_file");

        $data_to_send = @file_get_contents($local_file);
        if ($data_to_send === false)
            throw new Exception("Could not open local file: $local_file.");

        if (@fwrite($stream, $data_to_send) === false)
            throw new Exception("Could not send data from file: $local_file.");

        @fclose($stream);
    }

}