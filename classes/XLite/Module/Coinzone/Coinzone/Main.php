<?php
namespace XLite\Module\Coinzone\Coinzone;

abstract class Main extends \XLite\Module\AModule {
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Coinzone B.V.';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Coinzone';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '5.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '10';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Bitcoin Payments powered by Coinzone';
    }

    /**
     * The module is defined as the payment module
     *
     * @return integer|null
     */
    public static function getModuleType()
    {
        return static::MODULE_TYPE_PAYMENT;
    }
}
