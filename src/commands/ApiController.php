<?php
namespace mikehaertl\yii2\apidoc\vim\commands;

use yii\apidoc\commands\ApiController as BaseController;
use mikehaertl\yii2\apidoc\vim\templates\ApiRenderer;

/**
 * Generate class API documentation as Vim help files.
 *
 * @author Michael Härtl <haertl.mike@gmail.com'>
 */
class ApiController extends BaseController
{
    /**
     * @inheritdoc
     * @return ApiRenderer
     */
    protected function findRenderer($template)
    {
        return new ApiRenderer;
    }
}
