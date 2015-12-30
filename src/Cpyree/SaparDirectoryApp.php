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

        $this->get('/dirs', 'Cpyree\\Controller\\DirectoryController::getDirectories');
        $this->get('/dir-content', 'Cpyree\\Controller\\DirectoryController::getContentDir');
        $this->get('/dir-genre', 'Cpyree\\Controller\\DirectoryController::getGenreDir');
        $this->get('/set-matadata', 'Cpyree\\Controller\\DirectoryController::setMetadata');
        $this->get('/move', 'Cpyree\\Controller\\DirectoryController::move');
        $this->get('/delete', 'Cpyree\\Controller\\DirectoryController::delete');

        //Security issue, disalow stream on all
        $this->get('/stream', function(Request $request) {
            if ($request->query->has('file')) {
                $stream = new Streamer();
                return $stream->start($request->query->get('file'));
            }
            die();
        });

    }

    private function getBasePath()
    {
        return "/Volumes/Extend/_DanceHall/";
    }

}