<?php
    namespace app\models;
    

    use \IBAN;
    use \SoapClient;

    class IbanNumber extends IBAN{

        public function ibanToBban(){
            $url = "https://www.ibanbic.be/IBANBIC.asmx?WSDL";
            $client = new SoapClient($url);
            $bban = $client->getBelgianBBAN(array('Value'=>$this->iban))->getBelgianBBANResult;
            return $bban;
        }

        public function getIban(){
            return $this->iban;
        }
    }