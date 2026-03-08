<?php

namespace App\Controllers\Debug;

class Rerouter extends BaseController
{
    /**
     * Reroutes the request to a specified controller and method dynamically.
     *
     * @param string      $controller The name of the controller to reroute to.
     *                                This should correspond to a class name within the
     *                                `App\Controllers\Debug` namespace.
     * @param string|bool $method     (Optional) The name of the method to call on the controller.
     *                                If not provided, the `index` method of the controller will be called.
     *
     * @return mixed The result of the called controller method.
     *
     * @throws \Error If the specified controller class does not exist.
     * @throws \Error If the specified method does not exist on the controller.
     */
    public function reroute($controller, $method = false)
    {
        $class = ucwords($controller);
        $class = 'App\\Controllers\\Debug\\' . $class;

        $controller = new $class();
        if (! $method) {
            return $controller->index();
        }

        return $controller->{$method}();
    }
}
