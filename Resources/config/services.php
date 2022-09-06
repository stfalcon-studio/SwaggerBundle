<?php
/*
 * This file is part of the SwaggerBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$configFolder', '%swagger.config_folder%')
        ->bind('$template', '%swagger.template%')
        ->bind('$docsFolder', '%kernel.project_dir%/public/api/')
    ;

    $services->load('StfalconStudio\SwaggerBundle\\', __DIR__.'/../../{Command,Config,Generator}');
};
