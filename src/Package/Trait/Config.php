<?php
namespace Package\Raxon\Email\Trait;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;
use Exception;
use Raxon\Module\Dir;
use Raxon\Module\Core;

trait Config {

    /**
     * @throws Exception
     */
    public function create(object $flags, object $options): void
    {
        Core::interactive();
        $object = $this->object();
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
            if(!property_exists($options, 'is')){
                $options->is = (object) [];
            }
            $options->is->default = false;
        }
        $options->is->created = microtime(true);
        $email_dir = $object->config('project.dir.data') . 'Email/';
        if(!Dir::is($email_dir)){
            Dir::create($email_dir, Dir::CHMOD);
        }
        $crypt_dir = $email_dir . '/Crypt/';
        if(!Dir::is($crypt_dir)){
            Dir::create($crypt_dir, Dir::CHMOD);
        }
        $crypt_url = $crypt_dir . Core::uuid() . '.key';
        $string = $options->password;
        if($crypt_url !== null) {
            $key = Core::key($crypt_url);
            for ($i = 0; $i < 5; $i++) {
                $string = Crypto::encrypt($string, $key);
            }
            $string = gzencode($string, 9);
            $string = base64_encode($string);
        }
        $record = (object) [
            'username' => $options->username,
            'password' => $string,
            'crypt_url' => $crypt_url,
            'host' => $options->host,
            'port' => $options->port,
            'is' => $options->is,
        ];
        d($record);
    }
}

