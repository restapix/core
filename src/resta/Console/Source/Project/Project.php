<?php

namespace Resta\Console\Source\Project;

use Resta\Foundation\PathManager\StaticPathList;
use Resta\Support\Utils;
use Resta\Console\ConsoleOutputter;
use Resta\Console\ConsoleListAccessor;
use Resta\Foundation\PathManager\StaticPathModel;

class Project extends ConsoleOutputter
{
    use ConsoleListAccessor;

    /**
     * @var $type
     */
    public $type='project';

    /**
     * @var array
     */
    protected $runnableMethods = [
        'create'=>'Creates Application Skeleton'
    ];

    /**
     * @var bool
     */
    protected $projectStatus = true;

    /**
     * @var $commandRule
     */
    public $commandRule=[];

    /**
     * @method create
     * @return mixed
     */
    public function create()
    {
        $this->argument['projectName']          = strtolower($this->projectName());
        $this->argument['kernelDir']            = Utils::getNamespace($this->kernel());
        $this->argument['kernelProviderDir']    = Utils::getNamespace($this->provider());
        $this->argument['factoryDir']           = app()->namespace()->factory();
        $this->directory['projectDir']          = $this->projectPath();
        $this->argument['exceptionNamespace']   = app()->namespace()->exception();
        $this->argument['resourcePath']         = app()->path()->appResourche();
        $this->argument['commandNamespace']           = app()->namespace()->command();
        $this->argument['testNamespace']             = app()->namespace()->tests();

        $recursiveDefaultDirectory = explode("\\",$this->argument['project']);
        $this->argument['applicationName'] = pos($recursiveDefaultDirectory);
        $recursiveDefaultDirectory[] = 'V1';
        $recursiveDefaultDirectoryList = [];

        foreach (array_slice($recursiveDefaultDirectory,1) as $defaultDirectory){

            $recursiveDefaultDirectoryList[]=$defaultDirectory;
            $this->directory[$defaultDirectory.'Path'] = $this->projectPath().''.implode("/",$recursiveDefaultDirectoryList);
        }

        //$this->directory['optionalDir'] = $this->optional();

        //get project directory all path
        $this->directory['kernelDir']                           = $this->kernel();
        $this->directory['middleWareDir']                       = $this->middleware();
        $this->directory['nodeDir']                             = $this->node();
        $this->directory['webservice']                          = $this->webservice();
        $this->directory['stubDir']                             = $this->stub();
        $this->directory['stubControllerDir']                   = $this->stub().''.DIRECTORY_SEPARATOR.'Controller';
        $this->directory['stubControllerCreateDir']             = $this->directory['stubControllerDir'].''.DIRECTORY_SEPARATOR.'Create';
        $this->directory['stubControllerCreateCrudFileDir']     = $this->directory['stubControllerCreateDir'].''.DIRECTORY_SEPARATOR.'Crudfile';
        $this->directory['stubDir']                             = $this->stub();
        $this->directory['providerDir']                         = $this->provider();
        $this->directory['storageDir']                          = $this->storage();
        $this->directory['logDir']                              = $this->log();
        $this->directory['resourceDir']                         = $this->resource();
        $this->directory['resourceCacheDir']                    = $this->resource().'/'.StaticPathModel::$cache;
        $this->directory['languageDir']                         = $this->language();
        $this->directory['languageEnDir']                       = $this->language().'/en';
        $this->directory['configDir']                           = $this->config();
        $this->directory['testDir']                             = $this->test();
        $this->directory['exceptionDir']                        = app()->path()->exception();
        $this->directory['helperDir']                           = app()->path()->helpers();
        $this->directory['commandDir']                          = app()->path()->command();

        //set project directory
        $this->file->makeDirectory($this);

        //get project file all path
        //$this->touch['publish']                     = $this->project.'/publish.php';
        $this->touch['kernel/kernel']               = $this->kernel().'/Kernel.php';
        $this->touch['kernel/servicejson']          = $this->kernel().'/service.json';
        $this->touch['kernel/helper']               = $this->provider().'/HelperServiceProvider.php';
        //$this->touch['helpers/general']             = app()->path()->helpers().'/General.php';
        $this->touch['kernel/version']              = $this->kernel().'/Version.php';
        $this->touch['kernel/app']                  = $this->provider().'/AppServiceProvider.php';
        $this->touch['kernel/worker']               = $this->provider().'/WorkerServiceProvider.php';
        $this->touch['kernel/exception']            = $this->provider().'/ExceptionServiceProvider.php';
        $this->touch['kernel/response']             = $this->provider().'/ResponseServiceProvider.php';
        $this->touch['kernel/entity']               = $this->provider().'/EntityServiceProvider.php';
        $this->touch['kernel/cache']               = $this->provider().'/CacheServiceProvider.php';
        $this->touch['kernel/authenticate']         = $this->provider().'/AuthenticateServiceProvider.php';
        $this->touch['kernel/role']                 = $this->provider().'/RoleServiceProvider.php';
        $this->touch['kernel/track']                = $this->provider().'/TrackServiceProvider.php';
        $this->touch['test/testcase']              = $this->test().'/TestCase.php';
        $this->touch['kernel/consoleevent']         = $this->provider().'/ConsoleEventServiceProvider.php';
        $this->touch['middleware/authenticate']     = $this->middleware().'/Authenticate.php';
        $this->touch['middleware/ratelimit']        = $this->middleware().'/RateLimit.php';
        $this->touch['middleware/clientToken']      = $this->middleware().'/ClientApiToken.php';
        $this->touch['middleware/settimezone']      = $this->middleware().'/SetClientTimezone.php';
        $this->touch['middleware/trustedproxies']   = $this->middleware().'/TrustedProxies.php';
        //$this->touch['factory/factory']             = $this->factory().'/Factory.php';
        //$this->touch['factory/factorymanager']      = $this->factory().'/FactoryManager.php';
        $this->touch['node/index']                  = $this->node().'/index.html';
        $this->touch['webservice/index']            = $this->webservice().'/index.html';
        $this->touch['language/index']              = $this->language().'/index.html';
        $this->touch['language/exception']          = $this->language().'/en/exception.yaml';
        $this->touch['language/default']            = $this->language().'/en/default.yaml';
        $this->touch['log/index']                   = $this->log().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/index.html';
        $this->touch['resource/index']              = $this->resource().'/'.StaticPathModel::$cache.'/index.html';
        $this->touch['stub/index']                  = $this->stub().'/index.html';
        $this->touch['stub/cccrudapp']              = $this->directory['stubControllerCreateCrudFileDir'] .'/app.stub';
        $this->touch['stub/cccrudconf']              = $this->directory['stubControllerCreateCrudFileDir'] .'/conf.stub';
        $this->touch['stub/cccrudcontrollerfilecrud']              = $this->directory['stubControllerCreateCrudFileDir'] .'/controllerfilecrud.stub';
        $this->touch['stub/cccruddeveloper']              = $this->directory['stubControllerCreateCrudFileDir'] .'/developer.stub';
        $this->touch['stub/cccruddoc']              = $this->directory['stubControllerCreateCrudFileDir'] .'/doc.stub';
        $this->touch['stub/cccruddummy']              = $this->directory['stubControllerCreateCrudFileDir'] .'/dummy.stub';
        $this->touch['stub/cccrudroute']              = $this->directory['stubControllerCreateCrudFileDir'] .'/routecrud.stub';
        $this->touch['stub/cccrudpolicy']              = $this->directory['stubControllerCreateCrudFileDir'] .'/policy.stub';
        $this->touch['stub/cccrudreadme']              = $this->directory['stubControllerCreateCrudFileDir'] .'/readme.stub';
        $this->touch['stub/cccrudresourceindex']              = $this->directory['stubControllerCreateCrudFileDir'] .'/resourceIndex.stub';
        $this->touch['config/hateoas']              = $this->config().'/Hateoas.php';
        //$this->touch['config/response']             = $this->config().'/Response.php';
        $this->touch['command/clientEntity']          = $this->directory['commandDir'].'/ClientEntity.php';
        $this->touch['config/redis']                = $this->config().'/Redis.php';
        $this->touch['config/app']                  = $this->config().'/App.php';
        $this->touch['config/autoservice']          = $this->config().'/AutoServices.php';
        $this->touch['config/cache']                = $this->config().'/Cache.php';
        $this->touch['supervisor/supervisor']       = $this->config().'/Supervisor.php';
        $this->touch['config/cors']                 = $this->config().'/Cors.php';
        $this->touch['config/database']             = $this->config().'/Database.php';
        $this->touch['config/authenticate']         = $this->config().'/Authenticate.php';
        $this->touch['config/slack']                = $this->config().'/Slack.php';
        $this->touch['config/search']               = $this->config().'/Search.php';
        $this->touch['version/annotations']         = $this->version().'/ServiceAnnotationsManager.php';
        $this->touch['version/servicedispatcher']   = $this->version().'/ServiceEventDispatcherManager.php';
        $this->touch['version/servicemiddleware']   = $this->version().'/ServiceMiddlewareManager.php';
        $this->touch['version/clientmanager']       = $this->version().'/ClientManager.php';
        //$this->touch['version/base']                = $this->version().'/ServiceBaseController.php';
        $this->touch['version/log']                 = $this->version().'/ServiceLogManager.php';
        //$this->touch['source/apitokentrait']        = $this->sourceSupportDir().'/Traits/ClientApiTokenTrait.php';
        $this->touch['app/readme']                  = $this->projectPath().'/README.md';
        $this->touch['app/gitignore']               = $this->projectPath().'/.gitignore';
        $this->touch['app/composer']                = $this->projectPath().'/composer.json';
        $this->touch['test/index']                  = $this->storage().'/index.html';
        $this->touch['exception/authenticate']      = $this->directory['exceptionDir'] .'/AuthenticateException.php';
        $this->touch['exception/noinput']           = $this->directory['exceptionDir'] .'/NoInputException.php';
        $this->touch['helpers/general']              = $this->directory['helperDir'] .'/General.php';

        //set project touch
        $this->file->touch($this);

        echo $this->classical(' > Application called as "'.$this->projectName().'" has been successfully created in the '.$this->projectPath().'');
    }
}