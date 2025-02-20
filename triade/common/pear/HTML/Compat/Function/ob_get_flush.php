<?php
// $Id: ob_get_flush.php,v 1.8 2007/04/17 10:09:56 arpad Exp $


/**
 * Replace ob_get_flush()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.ob_get_flush
 * @author      Aidan Lister <aidan@php.net>
 * @author      Thiemo Mättig (http://maettig.com/)
 * @version     $Revision: 1.8 $
 * @since       PHP 4.3.0
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_ob_get_flush()
{
    $contents = ob_get_contents();

    if ($contents !== false) {
        ob_end_flush();
    }

    return $contents;
}


// Define
if (!function_exists('ob_get_flush')) {
    function ob_get_flush()
    {
        return php_compat_ob_get_flush();
    }
}
