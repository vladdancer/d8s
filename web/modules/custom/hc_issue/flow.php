<?php

class DrupalApplicationFlow {

  protected $entryPoint;

  public function __construct($enty_point) {
    $this->entyPoint = $enty_point;
  }

  public static function getFlow($enty_point) {
    return new self($enty_point);
  }


  public function step($step_name = '') {
    return $this;
  }

}

$array = [
  'tets' => [
    'a' => [

    ],
    'a',
    'a',
    'a',
    'a',
  ],
  'b' => [
    'c',
    'c',
    'c',
    'c',
    'c',
  ]
];

$flow = DrupalApplicationFlow::getFlow('index.php')
  ->step('loadComposerClassLoader')
  ->step('loadDrupalKernel')
  ->step('createRequestObject')
  ->step('createResponse')
    ->step('createDrupalKernelAndHandleResponse')
      ->step('prepareEnv')
        ->step('setDefaultPhpSettings')
        ->step('registerDrupalErrorHandlers')
      ->step('initializeSettings')
      ->step('initialiseContainer') // aka boot
        ->step('compileContainer')
          ->step('initializeServiceProviders')
            ->step('discoverServiceProviders')
          ->step('CreateContainerBuilder')
          ->step('registerServiceProviders')
            ->step('CoreServiceProvider')
              ->step('register')
                ->step('BackendCompilerPass')
                ->step('StackedKernelPass')
                ->step('MainContentRenderersPass')
                ->step('TaggedHandlersPass')
                ->step('RegisterStreamWrappersPass')
                ->step('TwigExtensionPass')
                ->step('RegisterEventSubscribersPass')
                ->step('PluginManagerPass')
                ->step('RegisterServicesForDestructionPass')
                  ->step('RouteBuilder')->rebuild // ROUTING: Collect routes & cache
                    ->step('collectStatic')
                    ->step('collectDynamic')
                    ->step('alterRoutes')
                    ->step('finish')
            ->step('NodeServiceProvider')
            ->step('UserServiceProvider')
            ->step('...')
          ->step('compile')
            ->step('processAllCompilerPasses')
              ->step('__')('...')
              ->step('StackedKernelPass')
                ->step('registerMiddlewares')([ // <- using decorator pattern
                  'http_middleware.negotiation',
                  'http_middleware.reverse_proxy',
                  'http_middleware.page_cache',
                  'http_middleware.kernel_pre_handle',
                  // -> drupalKernel->preHandle later call below steps :)
                  // --> loadLegacyIncludes: common, database, theme, menu, form...
                  // --> loadAllModules
                  // --> ...
                  'http_middleware.session',
                  'http_kernel.basic', // <- ROUTING COMES HERE
                  // Request is filled up using event dispatcher system.
                  // Also the whole routing process goes here
                ])
      ->step('runMiddlewares')
      ->step('fixVariousHeaders')

  ->step('sendResponse')
    ->step('sendHeaders')
    ->step('sendContent')
  ->step('shutDownDrupalKernel')
    ->step('runTerminateStepInAllMiddlewares')
      ->step('__')(...)
      ->'http_kernel.basic' // <- ED -> fire Terminate event.

function loadClassloader {
  loadDrupalKernel;
  createRequestObject;
  $createResponse = function {
    handleResponseByDrupal
  }
}
/hc/{node}

/about-page
/node/1