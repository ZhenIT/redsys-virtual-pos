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

namespace nkm\RedsysVirtualPos\Field;

/**
 * Holds the value of a request/response parameter
 *
 * @package    Redsys Virtual POS
 * @author     Javier Zapata <javierzapata82@gmail.com>
 * @copyright  2014 Javier Zapata <javierzapata82@gmail.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/nkm/redsys-virtual-pos
 */
class Response extends AbstractField implements FieldInterface
{
    protected $name         = 'Response';
    protected $responseName = 'Ds_Response';

    const TYPE_APPROVED         = '0';
    const TYPE_REJECTED         = '1';
    const TYPE_CANCEL_OR_REFUND = '2';
    const TYPE_RECON_OR_PREAUTH = '3';
    const TYPE_ERROR            = '4';

    /**
     * Holds every type of response
     * @var array
     */
    private static $types = [
        self::TYPE_APPROVED         => 'Approved transaction',
        self::TYPE_REJECTED         => 'Rejected transaction. Either by generic reasons or by possible fraud.',
        self::TYPE_CANCEL_OR_REFUND => 'Cancellation or refund',
        self::TYPE_RECON_OR_PREAUTH => 'Reconciliation of Pre-authorisation or pre-authentication',
        self::TYPE_ERROR            => 'Error sent by payment gateway of bank',
    ];

    /**
     * @var array
     */
    private static $responses = [
        '0000' => [
            'type'        => self::TYPE_APPROVED,
            'title'       => "TRANSACCION APROBADA",
            'description' => "Transacción autorizada por el banco emisor de la tarjeta",
        ],

        '0001' => [
            'type'        => self::TYPE_APPROVED,
            'title'       => "TRANSACCION APROBADA PREVIA IDENTIFICACION DE TITULAR",
            'description' => "Código exclusivo para transacciones Verified by Visa o MasterCard SecureCode.\nLa transacción ha sido autorizada y, además, el banco emisor nos informa que ha autenticado correctamente la identidad del titular de la tarjeta.",
        ],

        '0002-0099' => [
            'type'        => self::TYPE_APPROVED,
            'title'       => "TRANSACCION APROBADA",
            'description' => "Transacción autorizada por el banco emisor.",
        ],

        '0101' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA CADUCADA",
            'description' => "Transacción denegada porque la fecha de caducidad de la tarjeta que se ha informado en el pago, es anterior a la actualmente vigente.",
        ],

        '0102' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA BLOQUEDA TRANSITORIAMENTE O BAJO SOSPECHA DE FRAUDE",
            'description' => "Tarjeta bloqueada transitoriamente por el banco emisor o bajo sospecha de fraude.",
        ],

        '0104' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "OPERACIÓN NO PERMITIDA",
            'description' => "Operación no permitida para ese tipo de tarjeta.",
        ],

        '0106' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "NUM. INTENTOS EXCEDIDO",
            'description' => "Excedido el número de intentos con PIN erróneo.",
        ],

        '0107' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "CONTACTAR CON EL EMISOR",
            'description' => "El banco emisor no permite una autorización automática. Es necesario contactar telefónicamente con su centro autorizador para obtener una aprobación manual.",
        ],

        '0109' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "IDENTIFICACIÓN INVALIDA DEL COMERCIO O TERMINAL",
            'description' => "Denegada porque el comercio no está correctamente dado de alta en los sistemas internacionales de tarjetas.",
        ],

        '0110' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "IMPORTE INVALIDO",
            'description' => "El importe de la transacción es inusual para el tipo de comercio que solicita la autorización de pago.",
        ],

        '0114' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA NO SOPORTA EL TIPO DE OPERACIÓN SOLICITADO",
            'description' => "Operación no permitida para ese tipo de tarjeta.",
        ],

        '0116' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "DISPONIBLE INSUFICIENTE",
            'description' => "El titular de la tarjeta no dispone de suficiente crédito para atender el pago.",
        ],

        '0118' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA NO REGISTRADA",
            'description' => "Tarjeta inexistente o no dada de alta por banco emisor.",
        ],

        '0125' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA NO EFECTIVA",
            'description' => "Tarjeta inexistente o no dada de alta por banco emisor.",
        ],

        '0129' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "ERROR CVV2/CVC2",
            'description' => "El código CVV2/CVC2 (los tres dígitos del reverso de la tarjeta) informado por el comprador es erróneo.",
        ],

        '0167' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "CONTACTAR CON EL EMISOR: SOSPECHA DE FRAUDE",
            'description' => "Debido a una sospecha de que la transacción es fraudulenta el banco emisor no permite una autorización automática. Es necesario contactar telefónicamente con su centro autorizador para obtener una aprobación manual.",
        ],

        '0180' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA AJENA AL SERVICIO",
            'description' => "Operación no permitida para ese tipo de tarjeta.",
        ],

        '0181-0182' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA CON RESTRICCIONES DE DEBITO O CREDITO",
            'description' => "Tarjeta bloqueada transitoriamente por el banco emisor.",
        ],

        '0184' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "ERROR EN AUTENTICACION",
            'description' => "Código exclusivo para transacciones Verified by Visa o MasterCard SecureCode.\nLa transacción ha sido denegada porque el banco emisor no pudo autenticar debidamente al titular de la tarjeta.",
        ],

        '0190' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "DENEGACION SIN ESPECIFICAR EL MOTIVO",
            'description' => "Transacción denegada por el banco emisor pero sin que este dé detalles acerca del motivo.",
        ],

        '0191' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "FECHA DE CADUCIDAD ERRONEA",
            'description' => "Transacción denegada porque la fecha de caducidad de la tarjeta que se ha informado en el pago, no se corresponde con la actualmente vigente.",
        ],

        '0201' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA CADUCADA",
            'description' => "Transacción denegada porque la fecha de caducidad de la tarjeta que se ha informado en el pago, es anterior a la actualmente vigente.\nAdemás, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0202' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA BLOQUEDA TRANSITORIAMENTE O BAJO SOSPECHA DE FRAUDE",
            'description' => "Tarjeta bloqueada transitoriamente por el banco emisor o bajo sospecha de fraude.\nAdemás, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0204' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "OPERACION NO PERMITIDA",
            'description' => "Operación no permitida para ese tipo de tarjeta. Además, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0207' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "CONTACTAR CON EL EMISOR",
            'description' => "El banco emisor no permite una autorización automática.\nEs necesario contactar telefónicamente con su centro autorizador para obtener una aprobación manual. Además, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0208-0209' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "TARJETA PERDIDA O ROBADA",
            'description' => "Tarjeta bloqueada por el banco emisor debido a que el titular le ha manifestado que le ha sido robada o perdida.\nAdemás, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0280' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "ERROR CVV2/CVC2",
            'description' => "Código exclusivo para transacciones en las que se solicita el código de 3 dígitos CVV2 (tarj.Visa) o CVC2 (tarj.MasterCard) del reverso de la tarjeta.\nEl código CVV2/CVC2 informado por el comprador es erróneo. Además, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0290' => [
            'type'        => self::TYPE_REJECTED,
            'title'       => "DENEGACION SIN ESPECIFICAR EL MOTIVO",
            'description' => "Transacción denegada por el banco emisor pero sin que este dé detalles acerca del motivo.\nAdemás, el banco emisor considera que la tarjeta está en una situación de posible fraude.",
        ],

        '0400' => [
            'type'        => self::TYPE_CANCEL_OR_REFUND,
            'title'       => "ANULACION ACEPTADA",
            'description' => "Transacción de anulación o retrocesión parcial aceptada por el banco emisor.",
        ],

        '0480' => [
            'type'        => self::TYPE_CANCEL_OR_REFUND,
            'title'       => "NO SE ENCUENTRA LA OPERACIÓN ORIGINAL O TIME-OUT EXCEDIDO",
            'description' => "La anulación o retrocesión parcial no ha sido aceptada porque no se ha localizado la operación original, o bien, porque el banco emisor no ha dado respuesta dentro del time-out predefinido.",
        ],

        '0481' => [
            'type'        => self::TYPE_CANCEL_OR_REFUND,
            'title'       => "ANULACION ACEPTADA",
            'description' => "Transacción de anulación o retrocesión parcial aceptada por el banco emisor. No obstante, la respuesta del banco emisor se ha recibido con mucha demora, fuera del time-out predefinido.",
        ],

        '0500' => [
            'type'        => self::TYPE_RECON_OR_PREAUTH,
            'title'       => "CONCILIACION ACEPTADA",
            'description' => "La transacción de conciliación ha sido aceptada por el banco emisor.",
        ],

        '0501-0503' => [
            'type'        => self::TYPE_RECON_OR_PREAUTH,
            'title'       => "NO ENCONTRADA LA OPERACION ORIGINAL O TIME-OUT EXCEDIDO",
            'description' => "La conciliación no ha sido aceptada porque no se ha localizado la operación original, o bien, porque el banco emisor no ha dado respuesta dentro del timeout predefinido.",
        ],

        '9928' => [
            'type'        => self::TYPE_RECON_OR_PREAUTH,
            'title'       => "ANULACIÓN DE PREAUTORITZACIÓN REALIZADA POR EL SISTEMA",
            'description' => "El sistema ha anulado la preautorización diferida al haber pasado más de 72 horas.",
        ],

        '9929' => [
            'type'        => self::TYPE_RECON_OR_PREAUTH,
            'title'       => "ANULACIÓN DE PREAUTORITZACIÓN REALIZADA POR EL COMERCIO",
            'description' => "La anulación de la preautorización ha sido aceptada",
        ],

        '0904' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "COMERCIO NO REGISTRADO EN EL FUC",
            'description' => "Hay un problema en la configuración del código de comercio. Contactar con Banco Sabadell para solucionarlo.",
        ],

        '0909' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "ERROR DE SISTEMA",
            'description' => "Error en la estabilidad de la plataforma de pagos de Banco Sabadell o en la de los sistemas de intercambio de Visa o MasterCard.",
        ],

        '0912' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "EMISOR NO DISPONIBLE",
            'description' => "El centro autorizador del banco emisor no está operativo en estos momentos.",
        ],

        '0913' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSMISION DUPLICADA",
            'description' => "Se ha procesado recientemente una transacción con el mismo número de pedido (Ds_Merchant_Order).",
        ],

        '0916' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "IMPORTE DEMASIADO PEQUEÑO",
            'description' => "No es posible operar con este importe.",
        ],

        '0928' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TIME-OUT EXCEDIDO",
            'description' => "El banco emisor no da respuesta a la petición de autorización dentro del time-out predefinido.",
        ],

        '0940' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSACCION ANULADA ANTERIORMENTE",
            'description' => "Se está solicitando una anulación o retrocesión parcial de una transacción que con anterioridad ya fue anulada.",
        ],

        '0941' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSACCION DE AUTORIZACION YA ANULADA POR UNA ANULACION ANTERIOR",
            'description' => "Se está solicitando la confirmación de una transacción con un número de pedido (Ds_Merchant_Order) que se corresponde a una operación anulada anteriormente.",
        ],

        '0942' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSACCION DE AUTORIZACION ORIGINAL DENEGADA",
            'description' => "Se está solicitando la confirmación de una transacción con un número de pedido (Ds_Merchant_Order) que se corresponde a una operación denegada.",
        ],

        '0943' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "DATOS DE LA TRANSACCION ORIGINAL DISTINTOS",
            'description' => "Se está solicitando una confirmación errónea.",
        ],

        '0944' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "SESION ERRONEA",
            'description' => "Se está solicitando la apertura de una tercera sesión. En el proceso de pago solo está permitido tener abiertas dos sesiones (la actual y la anterior pendiente de cierre).",
        ],

        '0945' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSMISION DUPLICADA",
            'description' => "Se ha procesado recientemente una transacción con el mismo número de pedido (Ds_Merchant_Order).",
        ],

        '0946' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "OPERACION A ANULAR EN PROCESO",
            'description' => "Se ha solicitada la anulación o retrocesión parcial de una transacción original que todavía está en proceso y pendiente de respuesta.",
        ],

        '0947' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSMISION DUPLICADA EN PROCESO",
            'description' => "Se está intentando procesar una transacción con el mismo número de pedido (Ds_Merchant_Order) de otra que todavía está pendiente de respuesta.",
        ],

        '0949' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TERMINAL INOPERATIVO",
            'description' => "El número de comercio (Ds_Merchant_MerchantCode) o el de terminal (Ds_Merchant_Terminal) no están dados de alta o no son operativos.",
        ],

        '0950' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "DEVOLUCION NO PERMITIDA",
            'description' => "La devolución no está permitida por regulación.",
        ],

        '0965' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "VIOLACIÓN NORMATIVA",
            'description' => "Violación de la Normativa de Visa o Mastercard",
        ],

        '9064' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "LONGITUD TARJETA INCORRECTA",
            'description' => "No posiciones de la tarjeta incorrecta",
        ],

        '9078' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "NO EXISTE METODO DE PAGO",
            'description' => "Los tipos de pago definidos para el terminal (Ds_Merchant_Terminal) por el que se procesa la transacción, no permiten pagar con el tipo de tarjeta informado.",
        ],

        '9093' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TARJETA NO EXISTE",
            'description' => "Tarjeta inexistente.",
        ],

        '9094' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "DENEGACION DE LOS EMISORES",
            'description' => "Operación denegada por parte de los emisoras internacionales",
        ],

        '9104' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "OPER. SEGURA NO ES POSIBLE",
            'description' => "Comercio con autenticación obligatoria y titular sin clave de compra segura",
        ],

        '9142' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TIEMPO LÍMITE DE PAGO SUPERADO",
            'description' => "El titular de la tarjeta no se ha autenticado durante el tiempo máximo permitido.",
        ],

        '9218' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "NO SE PUEDEN HACER OPERACIONES SEGURAS",
            'description' => "La entrada Operaciones no permite operaciones Seguras",
        ],

        '9253' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "CHECK-DIGIT ERRONEO",
            'description' => "Tarjeta no cumple con el check-digit (posición 16 del número de tarjeta calculada según algoritmo de Luhn).",
        ],

        '9256' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "PREAUTORITZACIONES NO HABILITADAS",
            'description' => "La tarjeta no puede hacer Preautorizaciones",
        ],

        '9261' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "LÍMITE OPERATIVO EXCEDIDO",
            'description' => "La transacción excede el límite operativo establecido por Banco Sabadell",
        ],

        '9283' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "SUPERA ALERTAS BLOQUANTES",
            'description' => "La operación excede las alertas bloqueantes, no se puede procesar",
        ],

        '9281' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "SUPERA ALERTAS BLOQUEANTES",
            'description' => "La operación excede las alertas bloqueantes, no se puede procesar",
        ],

        '9912' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "EMISOR NO DISPONIBLE",
            'description' => "El centro autorizador del banco emisor no está operativo en estos momentos.",
        ],

        '9913' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "ERROR EN CONFIRMACION",
            'description' => "Error en la confirmación que el comercio envía al TPV Virtual (solo aplicable en la opción de sincronización SOAP)",
        ],

        '9914' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "CONFIRMACION “KO”",
            'description' => "Confirmación “KO” del comercio (solo aplicable en la opción de sincronización SOAP)",
        ],

        '9915' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "PAGO CANCELADO",
            'description' => "El usuario ha cancelado el pago",
        ],

        '9928' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "AUTORIZACIÓN EN DIFERIDO ANULADA",
            'description' => "Anulación de autorización en diferido realizada por el SIS (proceso batch)",
        ],

        '9929' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "AUTORIZACIÓN EN DIFERIDO ANULADA",
            'description' => "Anulación de autorización en diferido realizada por el comercio",
        ],

        '9997' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "TRANSACCIÓN SIMULTÁNEA",
            'description' => "En el TPV Virtual se está procesando de forma simultánea otra operación con la misma tarjeta.",
        ],

        '9998' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "ESTADO OPERACIÓN: SOLICITADA",
            'description' => "Estado temporal mientras la operación se procesa. Cuando la operación termine este código cambiará.",
        ],

        '9999' => [
            'type'        => self::TYPE_ERROR,
            'title'       => "ESTADO OPERACIÓN: AUTENTICANDO",
            'description' => "Estado temporal mientras el TPV realiza la autenticación del titular. Una vez finalizado este proceso el TPV asignará un nuevo código a la operación.",
        ],
    ];

    private $responseKey;

    /**
     * @param mixed $value The response code
     * @return Response
     */
    public function setValue($value)
    {
        $this->value       = self::pad($value);
        $this->responseKey = $this->getResponseKey($this->value);

        return $this;
    }

    /**
     * @return array
     */
    private static function getResponses()
    {
        return (array) self::$responses;
    }

    /**
     * @param  string  $code The response code
     * @return boolean
     */
    private static function hasResponse($code)
    {
        return (bool) self::getResponseKey($code);
    }

    /**
     * @param  string $code
     * @return array|null The response info or null if not found
     */
    private static function getResponse($code)
    {
        if (self::hasResponse($code)) {
            $responses = self::getResponses();

            return $responses[$code];
        }

        return null;
    }

    /**
     * @return array
     */
    private static function getTypes()
    {
        return (array) self::$types;
    }

    /**
     * @return string
     */
    public function getType()
    {
        $this->ensureValidResponse($this->value);
        $responses = self::getResponses();

        $typeKey = $responses[$this->responseKey]['type'];
        $types   = self::getTypes();

        return $types[$typeKey];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        $this->ensureValidResponse($this->value);
        $responses = self::getResponses();

        return $responses[$this->responseKey]['title'];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $this->ensureValidResponse($this->value);
        $responses = self::getResponses();

        return $responses[$this->responseKey]['description'];
    }

    /**
     * @return boolean
     */
    public function getIsApproved()
    {
        $response = self::getResponse($this->responseKey);

        return $response['type'] === self::TYPE_APPROVED;
    }

    /**
     * @return boolean
     */
    public function getIsRejected()
    {
        $response = self::getResponse($this->responseKey);

        return $response['type'] === self::TYPE_REJECTED;
    }

    /**
     * @return boolean
     */
    public function getIsCancelOrRefund()
    {
        $response = self::getResponse($this->responseKey);

        return $response['type'] === self::TYPE_CANCEL_OR_REFUND;
    }

    /**
     * @return boolean
     */
    public function getIsReconOrPreauth()
    {
        $response = self::getResponse($this->responseKey);

        return $response['type'] === self::TYPE_RECON_OR_PREAUTH;
    }

    /**
     * @return boolean
     */
    public function getIsError()
    {
        $response = self::getResponse($this->responseKey);

        return $response['type'] === self::TYPE_ERROR;
    }

    /**
     * @param  string $code The response code
     * @throws Exception If the code is not valid
     */
    private static function ensureValidResponse($code)
    {
        if (is_null($code)) {
            throw new FieldException("Response code is not defined.");
        }

        if (is_null(self::getResponseKey($code))) {
            throw new FieldException("Invalid Response code.");
        }
    }

    /**
     * Get the corresponding key of the set of responses from a given response code
     * @param  string $code
     * @return string|null The key of the responses array or null if not found
     */
    private static function getResponseKey($code)
    {
        $codeInt      = intval($code);
        $codePad      = self::pad($code);
        $responseKeys = array_keys(self::getResponses());

        $found = array_search($codePad, $responseKeys);
        if ($found !== false) {
            return $codePad;
        }

        $filtered = array_filter($responseKeys, function($k) use ($codeInt)
        {
            $range = explode('-', $k, 2);

            if (!isset($range[1])) {
                return false; // Not a range
            }

            if (intval($range[0]) <= $codeInt && $codeInt <= intval($range[1])) {
                return true; // Within range :)
            }

            return false;
        });

        $found = null;
        if (count($filtered) === 1) {
            list($found) = array_values($filtered);
        }

        return $found;
    }

    /**
     * Pads a given number to match the response code format
     * @param  string $str
     * @return string
     */
    private static function pad($str)
    {
        return str_pad(intval($str), 4, '0', STR_PAD_LEFT);
    }
}