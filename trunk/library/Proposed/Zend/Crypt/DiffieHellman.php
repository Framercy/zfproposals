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

require_once 'Zend/Crypt/Math/BigInteger.php';

/**
 * PHP implementation of the Diffie-Hellman public key encryption algorithm.
 * Allows two unassociated parties to establish a joint shared secret key
 * to be used in encrypting subsequent communications.
 *
 * @category   Zend
 * @package    Zend_Crypt
 * @copyright  Copyright (c) 2007 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Crypt_DiffieHellman
{

    /**
     * Default large prime number; required by the algorithm. 
     *
     * @var string
     */
    private $_prime = '155172898181473697471232257763715539915724801966915404479707795314057629378541917580651227423698188993727816152646631438561595825688188889951272158842675419950341258706556549803580104870537681476726513255747040765857479291291572334510643245094715007229621094194349783925984760375594985848253359305585439638443';

    /**
     * The default generator number. This number must be greater than 0 but
     * less than the prime number set.
     * @var string
     */
    private $_generator = '2';

    /**
     * A private number set by the local user. It's optional and will
     * be generated if not set.
     * @var string
     */
    private $_privateKey = null;

    /**
     * BigInteger support object courtesy of Zend_Math
     * @var Zend_Math_BigInteger
     */
    private $_math = null;

    /**
     * The public key generated by this instance after calling generateKeys().
     * @var string
     */
    private $_publicKey = null;

    /**
     * Constructor; if set construct the object using the parameter array to
     * set values for Prime, Generator and Private.
     * Otherwise the defaults are used, and for Private a random number will
     * be generated between 1 and Prime-1.
     *
     * @param array $params
     * @return void
     */
    public function __construct(array $params = null)
    {
        if (!is_null($params)) {
            if (array_key_exists('prime', $params)) {
                $this->setPrimeNumber($params['prime']);
            }
            if (array_key_exists('generator', $params)) {
                $this->setGeneratorNumber($params['generator']);
            }
            if (array_key_exists('private', $params)) {
                $this->setPrivateNumber($params['private']);
            }
        }
        $this->setBigIntegerMath();
    }

    /**
     * Generate own public key. If a private number has not already been
     * set, one will be generated at this stage.
     *
     * @return void
     */
    public function generateKeys()
    {
        $this->_verifyPrivateKeyExistsOrGenerate();
        $this->_publicKey = $this->_math->powmod($this->_generator, $this->_privateKey, $this->_prime);
    }

    /**
     * Returns own public key for communication to the second party to this
     * transaction.
     *
     * @return string
     */
    public function getPublicKey()
    {
        if (is_null($this->_publicKey)) {
            require_once 'Zend/Crypt/DiffieHellman/Exception.php';
            throw new Zend_Crypt_DiffieHellman_Exception('invalid operation; a public key has not yet been generated using generateKeys()');
        }
        return $this->_publicKey;
    }

    /**
     * Compute the shared secret key based on the public key received from the
     * the second party to this transaction. This should agree to the secret
     * key the second party computes on our own public key.
     * Once in agreement, the key is known to only to both parties.
     *
     * @param string $publicKey
     * @return void
     */
    public function computeSecretKey($publicKey)
    {
        if (!preg_match("/^\d+$/", $publicKey)) {
            require_once('Zend/Crypt/DiffieHellman/Exception.php');
            throw new Zend_Crypt_DiffieHellman_Exception('invalid parameter; not a positive natural number');
        }
        $secretKey = $this->_math->powmod($publicKey, $this->_privateKey, $this->_prime);
        return $secretKey;
    }
    
    /**
     * Setter for the value of the prime number
     *
     * @param string $number
     * @return void
     */
    public function setPrimeNumber($number)
    {
        if (!preg_match("/^\d+$/", $number)) {
            require_once('Zend/Crypt/DiffieHellman/Exception.php');
            throw new Zend_Crypt_DiffieHellman_Exception('invalid parameter; not a positive natural number');
        }
        $this->_prime = (string) $number;
    }

    /**
     * Getter for the value of the prime number
     *
     * @return string
     */
    public function getPrimeNumber()
    {
        return $this->_prime;
    }
     
    
    /**
     * Setter for the value of the generator number
     *
     * @param string $number
     * @return void
     */
    public function setGeneratorNumber($number)
    {
        if (!preg_match("/^\d+$/", $number)) {
            require_once('Zend/Crypt/DiffieHellman/Exception.php');
            throw new Zend_Crypt_DiffieHellman_Exception('invalid parameter; not a positive natural number');
        }
        $this->_generator = (string) $number;
    }

    /**
     * Getter for the value of the generator number
     *
     * @return string
     */
    public function getGeneratorNumber()
    {
        return $this->_generator;
    }

    /**
     * Setter for the value of the private number
     *
     * @param string $number
     * @return void
     */
    public function setPrivateNumber($number)
    {
        if (!preg_match("/^\d+$/", $number)) {
            require_once('Zend/Crypt/DiffieHellman/Exception.php');
            throw new Zend_Crypt_DiffieHellman_Exception('invalid parameter; not a positive natural number');
        }
        $this->_privateKey = (string) $number;
    }

    /**
     * Getter for the value of the private number
     *
     * @return string
     */
    public function getPrivateNumber()
    {
        if (!isset($this->_privateKey)) {
            require_once('Zend/Crypt/DiffieHellman/Exception.php');
            throw new Zend_Crypt_DiffieHellman_Exception('invalid parameter; not a positive natural number');
        }
        return $this->_private;
    }

    /**
     * Setter to impose a specific instance of Zend_Math_BigInteger_Interface.
     *
     * @param Zend_Math_BigInteger_Interface
     * @return void
     */
    public function setBigIntegerMath(Zend_Math_BigInteger_Interface $math = null)
    {
        if (is_null($math)) {
            $this->_math = new Zend_Crypt_Math_BigInteger;
            return;
        }
        $this->_math = $math;
    }

    /**
     * In the event a private number/key has not been set by the user,
     * generate one at random.
     *
     * @return string
     */
    private function _verifyPrivateKeyExistsOrGenerate()
    {
        if (isset($this->_privateKey)) {
            return;
        }
        // need to complete auto generation! 
    }

    /**
     * Validate a given public key from a second party
     *
     * @param string $publicKey
     * @return bool
     */
    private function _validatePublicKey($publicKey)
    {
        
    }

}