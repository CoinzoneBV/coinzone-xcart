<?php
namespace XLite\Module\Coinzone\Coinzone\Model\Payment\Processor;

class Coinzone extends \XLite\Model\Payment\Base\WebBased
{
    protected $checkoutURL = null;

    protected function getFormUrl()
    {
        if (!isset($this->checkoutURL)) {

            require_once LC_DIR_MODULES . 'Coinzone' . LC_DS . 'Coinzone' . LC_DS . 'lib' . LC_DS . 'CoinzoneLib.php';

            $coinzoneSettings = $this->getCoinzoneSettings();
            $coinzone = new \CoinzoneLib($coinzoneSettings['clientCode'], $coinzoneSettings['apiKey']);

            $order = $this->transaction->getOrder();

            /* create payload array */
            $payload = array(
                'amount' => $order->getTotal(),
                'currency' => $order->getCurrency()->getCode(),
                'merchantReference' => $this->transaction->getTransactionId(),
                'email' => $this->getProfile()->getLogin(),
                'redirectUrl' => $this->getReturnURL(null, true),
                'notificationUrl' => $this->getCallbackURL(null, true),
            );
            $response = $coinzone->callApi('transaction', $payload);
            if ($response->status->code === 201) {
                $this->checkoutURL = $response->response->url;
            }
        }
        return $this->checkoutURL;
    }


    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);
    }

    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processCallback($transaction);
        $request = \XLite\Core\Request::getInstance();

        $headers = getallheaders();

        $schema = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        $currentUrl = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $content = file_get_contents("php://input");
        $input = json_decode($content);

        $coinzoneSettings = $this->getCoinzoneSettings();

        /** check signature */
        $stringToSign = $content . $currentUrl . $headers['timestamp'];
        $signature = hash_hmac('sha256', $stringToSign, $coinzoneSettings['apiKey']);
        if ($signature !== $headers['signature']) {
            header("HTTP/1.0 400 Bad Request");
            exit("Invalid callback");
        }
        $successArray = array('PAID', 'COMPLETE');
        if (in_array($input->status, $successArray)) {
            $this->transaction->setStatus($transaction::STATUS_SUCCESS);
        } else {
            header("HTTP/1.0 400 Bad Request");
            exit("Invalid callback");
        }
    }

    protected function getCoinzoneSettings()
    {
        return array(
            'clientCode' => $this->getSetting('clientCode'),
            'apiKey' => $this->getSetting('apiKey')
        );
    }

    protected function getFormFields()
    {
        return array();
    }
    protected function assembleFormBody()
    {
        return true;
    }
    protected function getFormMethod()
    {
        return self::FORM_METHOD_GET;
    }

    public function getSettingsWidget()
    {
        return 'modules/Coinzone/Coinzone/config.tpl';
    }

    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
        && $method->getSetting('clientCode')
        && $method->getSetting('apiKey');
    }

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return true;
    }
}
