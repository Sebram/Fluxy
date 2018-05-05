<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'fos_rest.view_handler' shared service.

include_once $this->targetDirs[3].'/vendor/friendsofsymfony/rest-bundle/View/ViewHandlerInterface.php';
include_once $this->targetDirs[3].'/vendor/friendsofsymfony/rest-bundle/View/ConfigurableViewHandlerInterface.php';
include_once $this->targetDirs[3].'/vendor/friendsofsymfony/rest-bundle/View/ViewHandler.php';

$this->services['fos_rest.view_handler'] = $instance = new \FOS\RestBundle\View\ViewHandler(($this->services['router'] ?? $this->getRouterService()), ($this->privates['fos_rest.serializer.symfony'] ?? $this->load('getFosRest_Serializer_SymfonyService.php')), NULL, ($this->services['request_stack'] ?? $this->services['request_stack'] = new \Symfony\Component\HttpFoundation\RequestStack()), array('json' => false, 'xml' => false, 'html' => true), 400, 204, false, array('html' => 302), 'twig');

$instance->setSerializeNullStrategy(false);

return $instance;