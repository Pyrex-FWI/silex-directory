<?php

namespace Cpyree;


use Silex\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class SaparDirectoryApp extends Application
{
    /**
     * Instantiate a new Application.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this['debug'] = true;

        $this->get('/', function() {
            return 'Welcome at home';
        });

        $this->get('/welcome/{name}', function($name) {
            return sprintf('Welcome %s at home', $name);
        });

        $this->get('/dir', 'Cpyree\\Controller\\DirectoryController::listing');

    }

    private function getBasePath()
    {
        return "/Volumes/Extend/_DanceHall/";
    }

}