<?php
namespace Package\Raxon\Email\Trait;

use Exception;
use Raxon\App;
use Raxon\Module\Core;

trait Setup {

    /**
     * @throws Exception
     */
    public function email_install(): void
    {
        Core::interactive();
//        $command = 'curl -fsSL https://deno.land/install.sh | sh';
//        exec($command, $output);
//        echo implode(PHP_EOL, $output) . PHP_EOL;
        $object = $this->object();
        $options = App::options($object);
        ddd($options);
    }
}

