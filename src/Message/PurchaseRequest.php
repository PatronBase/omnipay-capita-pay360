<?php

namespace Omnipay\CapitaPay360\Message;

use SoapClient;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\CapitaPay360\ItemBag;

/**
 * CapitaPay360 Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    /** @var string */
    protected $liveWSDL = 'https://sbs.e-paycapita.com/scp/scpws/scpSimpleClient.wsdl';
    /** @var string */
    protected $testWSDL = 'https://sbsctest.e-paycapita.com/scp/scpws/scpSimpleClient.wsdl';
    /** @var mixed Soap client, added as property for unit testing and error checking */
    public $soapClient = null;

    public function getHmacKey()
    {
        return $this->getParameter('hmacKey');
    }

    public function setHmacKey($value)
    {
        return $this->setParameter('hmacKey', $value);
    }

    public function getHmacKeyID()
    {
        return $this->getParameter('hmacKeyID');
    }

    public function setHmacKeyID($value)
    {
        return $this->setParameter('hmacKeyID', $value);
    }

    public function getScpId()
    {
        return $this->getParameter('scpId');
    }

    public function setScpId($value)
    {
        return $this->setParameter('scpId', $value);
    }

    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    /**
     * Override {@see AbstractRequest::setItems()} to provide additional attributes
     */
    public function setItems($items)
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    public function getData()
    {
        $this->validate('scpId', 'siteId', 'hmacKeyID', 'hmacKey', 'amount', 'currency');
        $timeStamp = gmdate("YmdHis");
        $uniqueReference = uniqid('PB');
        $subjectType = 'CapitaPortal';
        $algorithm = 'Original';
        $credentialsToHash = implode('!', array(
            $subjectType,
            $this->getScpId(),
            $uniqueReference,
            $timeStamp,
            $algorithm,
            $this->getHmacKeyId()
        ));
        $key = base64_decode($this->getHmacKey());
        $hash = hash_hmac('sha256', $credentialsToHash, $key, true);
        $digest = base64_encode($hash);

        $data = array(
            'credentials' => array(
                'subject' => array(
                    'subjectType' => $subjectType,
                    'identifier' => $this->getScpId(),
                    'systemCode' => 'SCP'
                ),
                'requestIdentification' => array(
                    'uniqueReference' => $uniqueReference,
                    'timeStamp' => $timeStamp
                ),
                'signature' => array(
                    'algorithm' => $algorithm,
                    'hmacKeyID' => $this->getHmacKeyId(),
                    'digest' => $digest
                )
            ),
            'requestType' => 'payOnly',
            'requestId' => $this->getTransactionId(),
            'routing' => array(
                'returnUrl' => $this->getReturnUrl(),
                'backUrl' => $this->getCancelUrl(),
                'siteId' => $this->getSiteId(),
                'scpId' => $this->getScpId()
            ),
            'panEntryMethod' => 'ECOM',
            'sale' => array(
                'saleSummary' => array(
                    'description' => substr($this->getDescription(), 0, 100),
                    'amountInMinorUnits' => $this->getAmountInteger()
                )
            )
        );

        // add card holder details if available
        $card = $this->getCard();
        if ($card) {
            $address = array_filter(array(
                'address1' => substr($card->getAddress1(), 0, 50),
                'address2' => substr($card->getAddress2(), 0, 50),
                'address3' => substr($card->getAddress3(), 0, 50),
                'address4' => substr($card->getCity(), 0, 50),
                'county' => substr($card->getState(), 0, 50),
                'country' => substr($card->getCountry(), 0, 50),
                'postcode' => substr($card->getPostcode(), 0, 10),
            ));
            $cardHolderDetails = array_filter(array(
                'cardHolderName' => $card->getName(),
                'address' => $address,
            ));
            if ($cardHolderDetails) {
                $data['billing'] = array('cardHolderDetails' => $cardHolderDetails);
            }
        }

        // Create items array to return
        $items = $this->getItems();
        if ($items) {
            $saleItems = array();
            $itemContactDetails = array();
            // assumption: contact details for each item are the same as the purchaser, not set individually
            if ($card) {
                $accountName = array_filter(array(
                    'title' => $card->getTitle(),
                    'forename' => $card->getFirstName(),
                    'surname' => $card->getLastName(),
                ));
                $contact = array_filter(array(
                    'email' => $card->getEmail(),
                ));
                $itemContactDetails += array_filter(array(
                    'accountName' => $accountName,
                    'accountAddress' => $address,
                    'contact' => $contact,
                ));
            }
            foreach ($items as $itemIndex => $item) {
                $reference = $item->getReference();
                $lgItemDetails = array_filter($itemContactDetails + array(
                    'additionalReference' => $item->getAdditionalReference(),
                    'fundCode' => $item->getFundCode(),
                    'narrative' => $item->getNarrative(),
                ));
                $saleItems[] = array(
                    'itemSummary' => array(
                        'description' => substr($item->getName(), 0, 100),
                        'amountInMinorUnits' => (int) round(
                            $item->getQuantity() * $item->getPrice() * pow(10, $this->getCurrencyDecimalPlaces())
                        ),
                    ) + ($reference ? array('reference' => $reference) : array()),
                    'quantity' => $item->getQuantity(),
                    'lineId' => $itemIndex + 1
                ) + ($lgItemDetails ? array('lgItemDetails' => $lgItemDetails) : array());
            }
            // only supply item detail if there are any items
            $data['sale']['items'] = $saleItems;
        }

        return $data;
    }

    public function sendData($data)
    {
        // workaround so that SoapClient doesn't add 'id' attributes and references on identical nodes (e.g. address)
        $data = unserialize(serialize($data));
        $responseData = $this->getSoapClient()->scpSimpleInvoke($data);

        return $this->response = new PurchaseResponse($this, $responseData);
    }

    protected function getSoapClient()
    {
        return $this->soapClient === null ? new SoapClient($this->getEndpoint(), array()) : $this->soapClient;
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testWSDL : $this->liveWSDL;
    }
}
