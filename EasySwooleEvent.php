<?php
namespace EasySwoole\EasySwoole;


use App\Sync\Driver\DemoDriver;
use App\Sync\Invoker\DemoInvoker;
use EasySwoole\EasySwoole\Command\Utility;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\WordsMatch\WordsMatchServer;
use Symfony\Component\Finder\Finder;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        echo Utility::displayItem('initializing','EasySwooleEvent initializing'.PHP_EOL);
        self::optimumConfig();
        self::loadConfigFile();
        self::registerService();
    }

    public static function mainServerCreate(EventRegister $register)
    {
        echo Utility::displayItem('mainServerCreating','EasySwooleEvent mainServerCreating'.PHP_EOL);
        self::attachInvokerServer();
        self::attachWordsMatchServer();
        self::addCrontabTask();
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
    }

    static function addCrontabTask(){
        $tasks = require EASYSWOOLE_ROOT . '/App/Crontab/config.php';
        foreach ($tasks as $taskClass){
            Crontab::getInstance()->addTask($taskClass);
        }
        return true;
    }

    static function attachInvokerServer(){
        DemoInvoker::getInstance(new DemoDriver())->setWorkerNum(2)->attachServer(ServerManager::getInstance()->getSwooleServer());
    }

    static function attachWordsMatchServer(){
        WordsMatchServer::getInstance()
            ->setMaxMem('512M') // 每个进程最大内存
            ->setProcessNum(5) // 设置进程数量
            ->setServerName(config('SERVER_NAME').'-WordsMatch')// 服务名称
            ->setTempDir(EASYSWOOLE_TEMP_DIR)// temp地址
            ->setWordsMatchPath(EASYSWOOLE_ROOT.'/WordsMatch/')
            ->setDefaultWordBank('SensitiveWords.txt')// 服务启动时默认导入的词库文件路径
            ->setSeparator(',')// 词和其它信息分隔符
            ->attachToServer(ServerManager::getInstance()->getSwooleServer());
    }

    static function registerService()
    {
        self::registerMysql();
        self::registerRedis();
    }

    static function registerRedis(){
        try{
            $configs = config('redis');
            foreach ($configs as $connection => $config){
                $redisConfig = new \EasySwoole\Redis\Config\RedisConfig();
                $redisConfig->setHost($config['host']);
                $redisConfig->setPort($config['port']);
                $redisConfig->setAuth($config['auth']);
                $redisPoolConfig = \EasySwoole\RedisPool\Redis::getInstance()->register('master',$redisConfig);
                $redisClusterPoolConfig = \EasySwoole\RedisPool\Redis::getInstance()->register('cluster',new \EasySwoole\Redis\Config\RedisClusterConfig([
                    ['redis', 6380],
                ],[
                    'auth' => '',
                    'serialize' => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_PHP
                ]));
                //配置连接池连接数
                $redisPoolConfig->setMinObjectNum(5);
                $redisPoolConfig->setMaxObjectNum(20);
            }
        }catch (\Throwable $e){
            Logger::getInstance()->error($e->getTraceAsString());
        }
    }

    static function registerMysql()
    {
        try{
            $configs = config('database');
            foreach ($configs as $connection => $mysqlConfig){
                $config = new \EasySwoole\ORM\Db\Config();
                $config->setHost($mysqlConfig['host']);
                $config->setPort($mysqlConfig['port']);
                $config->setUser($mysqlConfig['user']);
                $config->setPassword($mysqlConfig['password']);
                $config->setDatabase($mysqlConfig['database']);
                $config->setTimeout($mysqlConfig['timeout']);
                $config->setCharset($mysqlConfig['charset']);
                //连接池配置
                $config->setGetObjectTimeout(3.0); //设置获取连接池对象超时时间
                $config->setIntervalCheckTime(30*1000); //设置检测连接存活执行回收和创建的周期
                $config->setMaxIdleTime(15); //连接池对象最大闲置时间(秒)
                $config->setMaxObjectNum($mysqlConfig['max']); //设置最大连接池存在连接对象数量
                $config->setMinObjectNum($mysqlConfig['min']); //设置最小连接池存在连接对象数量
                $config->setAutoPing(5); //设置自动ping客户端链接的间隔

                DbManager::getInstance()->addConnection(new Connection($config));
            }
        }catch (\Throwable $e){
            Logger::getInstance()->error($e->getTraceAsString());
        }
    }

    static function loadConfigFile(){
        $configs = [];
        $commonPaths = EASYSWOOLE_ROOT.'/config/common';
        $envPaths = EASYSWOOLE_ROOT.'/config/'.config('ENV');
        $finder = new Finder();
        $finder->files()->in([
            $commonPaths,
            $envPaths
        ])->name('*.php');
        foreach ($finder ?? [] as $file) {
            $configs[$file->getBasename('.php')] =  require $file->getRealPath();
        }
        Config::getInstance()->merge($configs);
    }

    static function optimumConfig()
    {
        $out = intval(shell_exec('cat /proc/cpuinfo |grep processor|wc -l'));
        $config = Config::getInstance();
        $keys = [
            'MAIN_SERVER.SETTING.reactor_num',
            'MAIN_SERVER.SETTING.worker_num',
            'MAIN_SERVER.TASK.workerNum',
        ];
        foreach ($keys as $key){
            empty($config->getConf($key)) && $config->setConf($key,$out);
        }
        $config->setConf('MAIN_SERVER.SETTING.buffer_output_size',32 * 1024 *1024);
    }
}