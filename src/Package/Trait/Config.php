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
use Raxon\Node\Module\Node;

trait Config {
    const COST = 5;

    /**
     * @throws Exception
     */
    public function create(object $flags, object $options): array
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
            !property_exists($options->is, 'default')
        ){
            if(!property_exists($options, 'is')){
                $options->is = (object) [];
            }
            $options->is->default = false;
        }
        if(
            !property_exists($options, 'is') &&
            !property_exists($options->is, 'system')
        ){
            if(!property_exists($options, 'is')){
                $options->is = (object) [];
            }
            $options->is->system = false;
        }
        $options->is->created = microtime(true);
        $email_dir = $object->config('project.dir.data') . 'Email/';
        if(!Dir::is($email_dir)){
            Dir::create($email_dir, Dir::CHMOD);
        }
        $crypt_dir = $email_dir . 'Crypt/';
        if(!Dir::is($crypt_dir)){
            Dir::create($crypt_dir, Dir::CHMOD);
        }
        $crypt_base_url = $crypt_dir . 'base.key';
        $crypt_url = $crypt_dir . Core::uuid() . '.key';
        $record = (object) [
            'user' => $options->user,
            'username' => $options->username,
            'password' => $this->encrypt($crypt_url, $options->password, self::COST),
            'crypt_url' => $this->encrypt($crypt_base_url, $crypt_url, self::COST),
            'host' => $options->host,
            'port' => $options->port,
            'is' => $options->is,
        ];
        $node = new Node($object);
        $class = 'System.Email.Config';
        $response = $node->create($class, $node->role_system(), $record);
        return $response;
    }

    public function encrypt($crypt_url, $string, $cost=1): ?string
    {
        if($crypt_url !== null) {
            $key = Core::key($crypt_url);
            for ($i = 0; $i < $cost; $i++) {
                $string = Crypto::encrypt($string, $key);
            }
            $string = gzencode($string, 9);
            $string = base64_encode($string);
            return $string;
        }
        return null;
    }
}

