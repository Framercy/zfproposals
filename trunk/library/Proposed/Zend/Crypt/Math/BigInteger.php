<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Crypt
 * @copyright  Copyright (c) 2007 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Validate.php 4974 2007-05-25 21:11:56Z bkarwin $
 */

require_once 'Zend/Math/BigInteger.php';

class Zend_Crypt_Math_BigInteger extends Zend_Math_BigInteger
{

    /**
     * Generate a pseudorandom number within the given range.
     *
     * @param string|int $min
     * @param string|int $max
     * @return string
     * @todo Even more pseudorandomness would be nice...
     */
    public function rand($minimum, $maximum)
    {
        if (strlen($maximum) < 4) {
            return mt_rand($minimum, $maximum - 1);
        }
        $rand = '';
        $i2 = strlen($maximum) - 1;
        for ($i = 1;$i < $i2;$i++) {
            $rand .= mt_rand(0,9);
        }
        $rand .= mt_rand(0,9);
        return $rand;
    }

}