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
        ddd($options);
    }
}

