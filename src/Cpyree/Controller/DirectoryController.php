<?php

namespace Cpyree\Controller;

use Cpyree\SaparDirectoryApp;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class DirectoryController
{
    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function listing(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $dirs = iterator_to_array(Finder::create()
            ->directories()
            ->in($wkd)->depth(0)->getIterator());
        $dirs = array_map(function(\SplFileInfo $item) {
            return [
                'name'      => $item->getFilename(),
                'pathName'  => $item->getRealPath(),
                'expanded'  => false,
                'childLoaded'=> false,

            ];
        }, $dirs);

        $dirs = array_values($dirs);

        return $saparDirectoryApp->json($dirs);
    }

    private function safeWorkingDir($path = null)
    {
        $wkd = '/Volumes/Extend/_DanceHall/';

        if ($path) {
            $path = realpath($path);
            if (FALSE !== strstr($path, $wkd)) {
                $wkd = $path;
            }
        }

        return $wkd;
    }
}