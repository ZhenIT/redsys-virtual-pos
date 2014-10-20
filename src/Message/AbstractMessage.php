<?php
/**
 * Redsys Virtual POS
 *
 * Copyright (c) 2014, Javier Zapata <javierzapata82@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Javier Zapata nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Redsys Virtual POS
 * @author     Javier Zapata <javierzapata82@gmail.com>
 * @copyright  2014 Javier Zapata <javierzapata82@gmail.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/nkm/redsys-virtual-pos
 */

namespace nkm\RedsysVirtualPos\Message;

/**
 * Part of a communication for a monetary operation (a request or a reponse)
 *
 * @package    Redsys Virtual POS
 * @author     Javier Zapata <javierzapata82@gmail.com>
 * @copyright  2014 Javier Zapata <javierzapata82@gmail.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/nkm/redsys-virtual-pos
 */
abstract class AbstractMessage implements MessageInterface
{
    const FIELD_BASE_NAMESPACE = '\nkm\RedsysVirtualPos\Field\\';

    /**
     * Environment object
     * @var object
     */
    protected $environment;

    /**
     * All the fields that can go in an action
     * @var array
     */
    protected $fields;

    /**
     * Fields that comprise the signature
     * @var array
     */
    protected $signatureFields;

    /**
     * Holds all the field objects
     * @var array
     */
    protected $params = [];

    /**
     * @var boolean
     */
    protected $isValid;

    /**
     * @var array
     */
    protected $ValidationErrors;

    /**
     * @param EnvironmentInterface  $environment    The environment to set
     */
    public function __construct(\nkm\RedsysVirtualPos\Environment\EnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array All the fields that can go in an action
     */
    protected function getFields()
    {
        return (array) $this->fields;
    }

    /**
     * @return array All the fields that comprise the signature
     */
    protected function getSignatureFields()
    {
        return (array) $this->signatureFields;
    }

    /**
     * @param array $params     Params and values
     * @return MessageInterface
     */
    public function setParams(Array $params)
    {
        foreach ($params as $paramName => $paramValue) {
            $this->setParam($paramName, $paramValue);
        }

        return $this;
    }

    /**
     * @return array The actions's parameter objects
     */
    public function getParams()
    {
        $params = [];
        $fields = $this->getFields();
        foreach ($fields as $fieldName => $fieldClass) {
            $params[$fieldName] = $this->getParam($fieldName);
        }

        return $params;
    }

    /**
     * @param  string   $fieldName  The field's name
     * @param  mixed    $value      The field's value
     * @return MessageInterface
     */
    protected function setParam($fieldName, $value)
    {
        $this->isValid = null;
        $this->validationErrors = null;

        $fieldClass = $this->getFieldClassName($fieldName);

        try {
            $rc = new \ReflectionClass($fieldClass);
            $this->params[$fieldName] = $rc->newInstanceArgs([$value]);
        } catch (Exception $e) {
            throw new \RuntimeException("Class `{$fieldClass}` not found.");
        }

        return $this;
    }

    /**
     * @param string $fieldName The field's name
     * @return FieldInterface
     */
    protected function getParam($fieldName)
    {
        $fieldClass = $this->getFieldClassName($fieldName);

        if (!isset($this->params[$fieldName]) || !is_object($this->params[$fieldName])) {
            $rc = new \ReflectionClass($fieldClass);
            $this->params[$fieldName] = $rc->newInstance();
        }

        return $this->params[$fieldName];
    }

    /**
     * Returns the result of the field's validation
     * performing it if necessary
     * @return boolean
     */
    public function getIsValid()
    {
        if (is_null($this->isValid)) {
            $this->validate();
        }

        return $this->isValid;
    }

    /**
     * Returns all the errors found on the field's validation
     * performing it if necessary
     * @return array List of validation error messages
     */
    public function getValidationErrors()
    {
        if (is_null($this->validationErrors)) {
            $this->validate();
        }

        return $this->validationErrors;
    }

    /**
     * Validates all the params of the action
     * @return MessageInterface
     */
    abstract protected function validate();

    /**
     * @return string The encrypted signature
     */
    protected function generateSignature()
    {
        try {
            $secret = $this->environment->getSecret();
        } catch (Exception $e) {
            throw new MessageException("Cannot generate the signature. Merchant secret is not set.");
        }

        $fields = $this->getSignatureFields();
        ksort($fields, SORT_NUMERIC);

        $signaturePlain = '';
        foreach($fields as $fieldName) {
            $fieldObj        = $this->getParam($fieldName);
            $signaturePlain .= $fieldObj->getValue();
        }
        $signaturePlain .= $secret;

        $signatureCrypt = strtoupper(sha1($signaturePlain));

        return $signatureCrypt;
    }

    /**
     * Get the fully qualified name of a field's class
     * @param  string $fieldName The field name
     * @return string            The field class name
     */
    protected function getFieldClassName($fieldName)
    {
        $fields = array_merge($this->getFields(), ['signature' => 'Signature']);

        if (!isset($fields[$fieldName])) {
            throw new MessageException("Field ´{$fieldName}´ is not within the list of valid fields.");
        }
        $className = $fields[$fieldName];

        // If is not a fully qualified class name
        // append our own base namespace
        if ($className[0] !== '\\') {
            $className = self::FIELD_BASE_NAMESPACE.$className;
        }

        return $className;
    }
}