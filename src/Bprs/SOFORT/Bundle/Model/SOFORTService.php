<?php

namespace Bprs\SOFORT\Bundle\Model;

use Sofort\SofortLib\Sofortueberweisung;

class SOFORTService
{
    private $configkey;
    private $currencyCode;
    private $successUrl;
    private $abortUrl;
    private $notificationUrl;


    public function __construct($configkey, $currencyCode, $successUrl, $abortUrl, $notificationUrl)
    {
        $this->configkey = $configkey;
        $this->currencyCode = $currencyCode;
        $this->successUrl = $successUrl;
        $this->abortUrl = $abortUrl;
        $this->notificationUrl = $notificationUrl;
    }

    public function getSofortueberweisung($amount = "0.0")
    {
        $Sofortueberweisung = new Sofortueberweisung($this->configkey);
        $Sofortueberweisung->setAmount($amount);
        $Sofortueberweisung->setCurrencyCode($this->currencyCode);

        return $Sofortueberweisung;
    }

    public function startTransaction(Sofortueberweisung $Sofortueberweisung)
    {
        $Sofortueberweisung->sendRequest();

        // SOFORT Api didn't accept the data
        if ($Sofortueberweisung->getError()) {
            return false;
        } else {
            return $Sofortueberweisung;
        }
    }
}
