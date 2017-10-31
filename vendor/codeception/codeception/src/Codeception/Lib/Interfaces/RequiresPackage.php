<?php
namespace Codeception\Lib\Interfaces;

interface RequiresPackage
{

    /**
     * Returns list of model and corresponding packages required for this module
     */
    public function _requires();
}
