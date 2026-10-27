<?php
namespace Package\Raxon\Email\Trait;

use Exception;
use Raxon\App;
use Raxon\Module\Core;

trait Config {

    /**
     * @throws Exception
     */
    public function create(object $flags, object $options): void
    {
        Core::interactive();
        $object = $this->object();
        $options = App::options($object);
        if(!property_exists($options, 'username')){
            throw new Exception('No -option password found');
        }

        if(!property_exists($options, 'password')){
            throw new Exception('No -option password found');
        }
        if(!property_exists($options, 'host')){
            throw new Exception('No -option host found');
        }
        if(!property_exists($options, 'port')){
            throw new Exception('No -option port found');
        }
        if(
            !property_exists($options, 'is') &&
            !property_exists($options, 'default')
        ){
            $options->is->default = false;
        }
        $options->is->created = microtime(true);
        $record = (object) [
            'username' => $options->username,
            'password' => $options->password,
            'host' => $options->host,
            'port' => $options->port,
            'is' => $options->is,
        ];
        d($record);
    }
}

