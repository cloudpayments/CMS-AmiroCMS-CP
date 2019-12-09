<?php

/**
 * Cloudpayments payment driver.
 *
 * @package Driver_PaymentSystem
 */

class Cloudpayments_PaymentSystemDriver extends AMI_PaymentSystemDriver {
    protected $driverName = 'cloudpayments';

    const CLOUDPAYMENTS_RESULT_SUCCESS = 0;
    const CLOUDPAYMENTS_RESULT_ERROR_INVALID_ORDER = 10;
    const CLOUDPAYMENTS_RESULT_ERROR_INVALID_COST = 11;
    const CLOUDPAYMENTS_RESULT_ERROR_NOT_ACCEPTED = 13;
    const CLOUDPAYMENTS_RESULT_ERROR_EXPIRED = 20;

    /**
     * Get checkout button HTML form.
     *
     * @param  array &$aRes         Will contain "error" (error description, 'Success by default') and "errno" (error code, 0 by default). "forms" will contain a created form
     * @param  array $aData         The data list for button generation
     * @param  bool  $bAutoRedirect If form autosubmit required (directly from checkout page)
     * @return bool TRUE if form is generated, FALSE otherwise
     */
    public function getPayButton(array &$aRes, array $aData, $bAutoRedirect = false) {
        $aRes['errno'] = 0;
        $aRes['error'] = 'Success';

        foreach (array('return', 'description', 'button_name') as $fldName) {
            $aData[$fldName] = htmlspecialchars($aData[$fldName]);
        }

        $aHiddenData = $aData;
        foreach (array(
                     'process_url',
                     'return',
                     'callback',
                     'cancel',
                     'merchant_id',
                     'amount',
                     'description',
                     'order',
                     'button_name',
                     'button',
                     'secret_key',
                     'public_id',
                     'skin',
                     'cp_lang',
                     'calculationPlace'
                 ) as $var
        ) {
            unset($aHiddenData[$var]);
        }

        if ($aData['currency'] == 'AZM') {
            $aData['currency'] = 'AZN';
        }
        elseif ($aData['currency'] == 'BGN') {
            $aData['currency'] = 'BGL';
        }
        elseif ($aData['currency'] == 'BYR') {
            $aData['currency'] = 'BYN';
        }
        elseif ($aData['currency'] == 'RUR') {
            $aData['currency'] = 'RUR';
        }
        elseif ($aData['currency'] == 'TRL') {
            $aData['currency'] = 'TRY';
        };

        foreach ($aHiddenData as $key => $value) {
            $aData['hiddens'] .= "<input type=\"hidden\" name=\"$key\" value=\"$value\">\r\n";
        }

        return parent::getPayButton($aRes, $aData, $bAutoRedirect);
    }

    /**
     * Get the form that will be autosubmitted to payment system.
     * This step is required for some shooping cart actions.
     *
     * @param  array $aData  The data list for button generation
     * @param  array &$aRes  Will contain "error" (error description,
     *                       'Success by default') and "errno"
     *                       (error code, 0 by default). "forms" will contain
     *                       a created form
     * @return bool  True if form is generated, false otherwise
     */
    public function getPayButtonParams(array $aData, array &$aRes) {
        $aRes['errno']    = 0;
        $aRes['error']    = 'Success';
        $aData['contact'] = preg_replace('/[^+\d]/', '', $aData['contact']);

        $currency = $aData['currency'];
        if ('RUR' === $currency) {
            $currency = 'RUB';
        };
        if ('AZM' === $currency) {
            $currency = 'AZN';
        }
        elseif ('BGN' === $currency) {
            $currency = 'BGL';
        }
        elseif ('BYR' === $currency) {
            $currency = 'BYN';
        }
        elseif ('TRL' === $currency) {
            $currency = 'TRY';
        };
        
        $widget_params = array(
            "publicId"    => $aData["public_id"],  //id из личного кабинета
            "description" => $aData["description"], //назначение
            "amount"      => floatval($aData["amount"]), //сумма
            "currency"    => $currency, //валюта
            "invoiceId"   => $aData["order_id"], //номер заказа  (необязательно)
            "accountId"   => $aData["email"], //идентификатор плательщика (необязательно)
            "email"       => $aData["email"],
            "skin"        => $aData["skin"], //дизайн виджета
            "data"        => array(
                "name"          => $aData["firstname"],
                "phone"         => $aData["contact"],
                "cloudPayments" => array(),
            )
        );

        if ($aData['receipt']) {
            $widget_params['data']['cloudPayments']['customerReceipt'] = $this->getReceiptData($aData);
        }
        $aData['widget_params']   = json_encode($widget_params);
        //$aData['widget_language'] = $this->mapLanguage($aData['language']);
        $aData['widget_language'] = $aData["cp_lang"];

        return parent::getPayButtonParams($aData, $aRes);
    }

    /**
     * Prepare data for receipt
     *
     * @param $aData
     * @return array
     */
    private function getReceiptData($aData) {
        $receipt = array(
            'Items'            => array(),
            "calculationPlace" => $aData['calculationPlace'], //место осуществления расчёта
            'taxationSystem'   => $aData['taxation_system'],
            'email'            => $aData['email'],
            'phone'            => $aData['contact']
        );

        $items             = array();
        $orderId           = intval($aData['order_id']);
        $oOrder            = AMI::getResourceModel('eshop_order/table')->find($orderId);
        $oOrderProductList =
            AMI::getResourceModel('eshop_order_item/table', array(array('doRemapListItems' => true)))
               ->getList()
               ->addColumn('*')
               ->addSearchCondition(array('id_order' => $orderId))
               ->load();
        foreach ($oOrderProductList as $oProduct) {
            $aProduct = $oProduct->data;
            $aProduct = $aProduct['item_info'];
            $items[]  = array(
                "label"    => $aProduct['name'],
                "quantity" => floatval($oProduct->qty),
                "price"    => floatval($oProduct->price),
                "amount"   => floatval($oProduct->price) * floatval($oProduct->qty),
                "vat"      => $aData['vat'],
            );
        }
        if ($oOrder->shipping) {
            $name    = isset($oOrder->custom_info['get_type_name']) ? $oOrder->custom_info['get_type_name'] : 'Доставка';
            $items[] = array(
                "label"    => $name,
                "quantity" => 1,
                "price"    => floatval($oOrder->shipping),
                "amount"   => floatval($oOrder->shipping),
                "vat"      => $aData['vat_delivery'],
            );
        }
        $receipt['Items'] = $items;

        return $receipt;

    }

    /**
     * Verify the order from user back link. In success case 'accepted' status will be setup for order.
     *
     * @param  array $aGet       HTTP GET variables
     * @param  array $aPost      HTTP POST variables
     * @param  array &$aRes      Reserved array reference
     * @param  array $aCheckData Data that provided in driver configuration
     * @param  array $aOrderData Order data that contains such fields as id, total, order_date, status
     * @return bool  TRUE if order is correct, FALSE otherwise
     */
    public function payProcess(array $aGet, array $aPost, array &$aRes, array $aCheckData, array $aOrderData) {
        $aRes['errno'] = 0;
        $aRes['error'] = 'Success';

        return parent::payProcess($aGet, $aPost, $aRes, $aCheckData, $aOrderData);
    }

    /**
     * Verify the order by payment system background responce.
     * In success case 'confirmed' status will be setup for order.
     *
     * <lang:ru>
     * Метод, проверяющий валидность данных о подтверждении платежа
     * (номер заказа, сумма, подпись), переданные из сркипта
     * ato_onpay_callback.php.
     * </lang:ru>
     *
     * @param  array $aGet       HTTP-GET data
     * @param  array $aPost      HTTP-POST data
     * @param  array &$aRes      Reserved array reference
     * @param  array $aCheckData Data that provided in driver configuration
     * @param  array $aOrderData Order data that contains such fields as id, total, order_date, status
     * @see AMI_PaymentSystemDriver::payCallback()
     * @return int
     */
    public function payCallback(array $aGet, array $aPost, &$aRes, array $aCheckData, array $aOrderData) {
        $action = isset($aGet['cp_action']) ? $aGet['cp_action'] : '';
        if (!in_array($action, array('check', 'pay', 'сonfirm', 'fail', 'refund', 'cancel'))) {
            $this->exitWithCallbackResponse(self::CLOUDPAYMENTS_RESULT_ERROR_NOT_ACCEPTED, 'Invalid action');
        }

        // Check sign
        $postData    = file_get_contents('php://input');
        $checkSign   = base64_encode(hash_hmac('SHA256', $postData, $aCheckData['secret_key'], true));
        $requestSign = isset($_SERVER['HTTP_CONTENT_HMAC']) ? $_SERVER['HTTP_CONTENT_HMAC'] : '';

        if ($checkSign !== $requestSign) {
            $this->exitWithCallbackResponse(self::CLOUDPAYMENTS_RESULT_ERROR_NOT_ACCEPTED, 'Invalid sign');
        };

        if (in_array($action, array('check', 'pay'))) {
            $customData = unserialize($aOrderData[0]);
            $totalOrderAmount  = floatval($aOrderData['order_amount'] + $aOrderData['shipping']);
            $feePercent = floatval($customData['fee_percent']);
            if ($feePercent) {
                $totalOrderAmount *= (1 + $feePercent / 100);
            }
            $feeConst = floatval($customData['fee_const']);
            if ($feeConst) {
                $totalOrderAmount += $feeConst;
            }
            if ($totalOrderAmount != floatval($_POST['Amount'])) {
                $aRes['error'] = 'Invalid order cost';
                $this->exitWithCallbackResponse(self::CLOUDPAYMENTS_RESULT_ERROR_INVALID_COST, 'Invalid order cost');
            }
        }

        $status = '';
        switch ($action) {
            case 'pay':
            case 'сonfirm':
                if ($aPost['Status'] == 'Completed') {
                    $status = 'confirmed';
                }
                break;
            case 'fail':
            case 'refund':
            case 'cancel':
                $status = 'rejected';
                break;
        }

        $orderId = $aPost['InvoiceId'];

        $this->processOrderCallback($orderId, $status);
        $this->exitWithCallbackResponse(self::CLOUDPAYMENTS_RESULT_SUCCESS);
      
        return -1;
    }

    /**
     * @param $value
     * @return mixed
     */
 //   private function mapLanguage($value) {
//        $map = array(
   //         'en' => 'en-US',
 //           'ru' => 'ru-RU'
//        );

  //      return isset($map[$value]) ? $map[$value] : $value;
 //   }
//
    /**
     * @param $orderId
     * @param $status
     */
    private function processOrderCallback($orderId, $status) {
        global $conn, $CONNECT_OPTIONS;
        $CONNECT_OPTIONS['disable_cache_warn'] = true;
        if (in_array($status, array('confirmed', 'rejected'))) {
            global $cms, $oEshop, $oOrder;
            $oEshop->initByOwnerName('eshop');
            $oOrder->updateStatus($cms, $orderId, 'auto', $status);
            if ('confirmed' === $status) {
                $this->onPaymentConfirmed($orderId);
            }
        }
        $conn->Out();
    }

    /**
     * @param        $code
     * @param string $logErrorMessage
     */
    private function sendCallbackResponse($code, $logErrorMessage = '') {
        header('Content-Type: application/json');
        echo json_encode(array('code' => $code, 'message' => $logErrorMessage));
    }

    /**
     * @param        $code
     * @param string $logErrorMessage
     */
    private function exitWithCallbackResponse($code, $logErrorMessage = '') {
        if (!empty($logErrorMessage)) {
            $this->log($logErrorMessage, E_USER_WARNING);
        }
        $this->sendCallbackResponse($code, $logErrorMessage);
        die();
    }

    /**
     * Return real system order id from data that provided by payment system.
     *
     * @param  array $aGet              HTTP GET variables
     * @param  array $aPost             HTTP POST variables
     * @param  array &$aRes             Reserved array reference
     * @param  array $aAdditionalParams Reserved array
     * @return int  Order Id
     */
    public function getProcessOrder(array $aGet, array $aPost, array &$aRes, array $aAdditionalParams) {
        $orderId = 0;

        if (!empty($aPost['InvoiceId'])) {
            $orderId = $aPost['InvoiceId'];
        }

        return (int)$orderId;
    }

    public function getOrderIdVarName() {
        return 'InvoiceId';
    }

}
