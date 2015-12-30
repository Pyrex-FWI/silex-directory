<?php

namespace Cpyree\Controller;

use Cpyree\Id3\Metadata\Id3Metadata;
use Cpyree\Id3\Wrapper\BinWrapper\Eyed3Wrapper;
use Cpyree\Id3\Wrapper\BinWrapper\MediainfoWrapper;
use Cpyree\SaparDirectoryApp;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class DirectoryController
{

    const mediainfo = '/usr/local/bin/mediainfo';
    const eyed3 = '/usr/local/bin/eyeD3';
    const organize = '/usr/local/bin/php /Users/yemistikris/Documents/audio_api/app/console organize:temp  -vvv';
    const archive = '/Volumes/archives/';
    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function getDirectories(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $dirs = iterator_to_array(Finder::create()
            ->directories()
            ->sortByName()
            ->in($wkd)->depth(0)->getIterator());
        $dirs = array_map(function(\SplFileInfo $item) {
            return [
                'name'          => $item->getFilename(),
                'pathName'      => $item->getRealPath(),
                'expanded'      => false,
                'childLoaded'   => false,
                'isDir'         => true,
            ];
        }, $dirs);

        $dirs = array_values($dirs);

        return $saparDirectoryApp->json($dirs);
    }

    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function getContentDir(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $files = iterator_to_array(Finder::create()
            ->files()
            ->name('/(mp3|flac)$/')
            ->sortByName()
            ->in($wkd)->depth(0)->getIterator());
        $files = array_map(function(\SplFileInfo $item) {
            return [
                'name'          => $item->getFilename(),
                'pathName'      => $item->getRealPath(),
                'isDir'         => false,
            ];
        }, $files);

        $files = array_values($files);

        return $saparDirectoryApp->json($files);
    }

    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function getGenreDir(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $files = iterator_to_array(Finder::create()
            ->files()
            ->name('/(mp3|flac)$/')
            ->in($wkd)->depth(0)->getIterator());

        $mediaInfo = new MediainfoWrapper();
        $mediaInfo->setBinPath('/usr/local/bin/mediainfo');

        $files = array_map(function(\SplFileInfo $item) use ($mediaInfo) {
            $id3m = new Id3Metadata($item->getRealPath());
            if($mediaInfo->read($id3m)) {

                return $id3m;
            }
        }, $files);


        $files = array_values($files);

        return $saparDirectoryApp->json($files);
    }
    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function setMetadata(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $genre = $request->query->get('g');
        $year = $request->query->get('y');
        $files = iterator_to_array(Finder::create()
            ->files()
            ->name('/mp3|flac/')
            ->in($wkd)->depth(0)->getIterator());

        $eyd3 = new Eyed3Wrapper();
        $eyd3->setBinPath('/usr/local/bin/eyeD3');

        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            $id3 = new Id3Metadata($file->getRealPath());
            if ( $genre) {
                $id3->setGenre($genre);
            }
            if ($year) {
                $id3->setYear($year);
            }
            $eyd3->write($id3);
        }

        return $saparDirectoryApp->json([]);
    }

    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function move(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $cmd = sprintf(self::organize. '  %s %s ', escapeshellarg($wkd), escapeshellarg(self::archive));
        exec($cmd, $output, $return_var);
        if ($request->query->has('debug')) {
            dump($cmd);
            dump($output);
            dump(!(boolval($return_var)));
        }

        return $saparDirectoryApp->json(!(boolval($return_var)));
    }

    /**
     * @param Request $request
     * @param SaparDirectoryApp $saparDirectoryApp
     * @return mixed
     */
    public function delete(Request $request, SaparDirectoryApp $saparDirectoryApp) {

        $wkd = $this->safeWorkingDir($request->query->has('path') ? $request->query->get('path') : null);
        $cmd = sprintf('rm -fr  %s', escapeshellarg($wkd));
        exec($cmd, $output, $return_var);
        if ($request->query->has('debug')) {
            dump($cmd);
            dump($output);
            dump(!(boolval($return_var)));
        }

        return $saparDirectoryApp->json(!(boolval($return_var)));
    }



    private function safeWorkingDir($path = null)
    {
        //$wkd = '/Volumes/Extend/_DanceHall/';
        $wkd = '/Volumes/temp/';

        if ($path) {
            $path = realpath($path);
            if (FALSE !== strstr($path, $wkd)) {
                $wkd = $path;
            }
        }

        return $wkd;
    }
}