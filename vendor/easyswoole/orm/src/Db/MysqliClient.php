<?php


namespace EasySwoole\ORM\Db;


use EasySwoole\EasySwoole\Logger;
use EasySwoole\Mysqli\Client;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\Exception\Exception;
use EasySwoole\Pool\ObjectInterface;

class MysqliClient extends Client implements ClientInterface,ObjectInterface
{

    protected $lastQuery;
    protected $lastQueryResult;

    public function query(QueryBuilder $builder, bool $rawQuery = false): Result
    {
        $result = new Result();
        $ret = null;
        $errno = 0;
        $error = '';
        $stmt = null;
        try{
            debug(function()use($builder){
                Logger::getInstance()->console($builder->getLastQuery());
            });
            if($rawQuery){
                $ret = $this->rawQuery($builder->getLastQuery(),$this->config->getTimeout());
            }else{
                $stmt = $this->mysqlClient()->prepare($builder->getLastPrepareQuery(),$this->config->getTimeout());
                if($stmt){
                    $ret = $stmt->execute($builder->getLastBindParams(),$this->config->getTimeout());
                }else{
                    $ret = false;
                }
            }

            $errno = $this->mysqlClient()->errno;
            $error = $this->mysqlClient()->error;
            $insert_id     = $this->mysqlClient()->insert_id;
            $affected_rows = $this->mysqlClient()->affected_rows;
            /*
             * 重置mysqli客户端成员属性，避免下次使用
             */
            $this->mysqlClient()->errno = 0;
            $this->mysqlClient()->error = '';
            $this->mysqlClient()->insert_id     = 0;
            $this->mysqlClient()->affected_rows = 0;
            //结果设置
            if (!$rawQuery && $ret && $this->config->isFetchMode()){
                $result->setResult(new Cursor($stmt));
            } else {
                $result->setResult($ret);
            }
            $result->setLastError($error);
            $result->setLastErrorNo($errno);
            $result->setLastInsertId($insert_id);
            $result->setAffectedRows($affected_rows);

            $this->lastQueryResult = $result;
            $this->lastQuery       = $builder;
        }catch (\Throwable $throwable){
            throw $throwable;
        }finally{
            if($errno){
                /*
                    * 断线的时候回收链接
                */
                if(in_array($errno,[2006,2013])){
                    $this->close();
                }
                throw new Exception($error);
            }

        }
        return $result;
    }

    function gc()
    {
        $this->close();
    }

    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }

    function beforeUse(): ?bool
    {
        return $this->connect();
    }

    /**
     * 最后的sql构造
     * @return mixed
     */
    public function lastQuery():? QueryBuilder
    {
        return $this->lastQuery;
    }

    /**
     * 最后的查询结果
     * @return mixed
     */
    public function lastQueryResult():? Result
    {
        return $this->lastQueryResult;
    }

}