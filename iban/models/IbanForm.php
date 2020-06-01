<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * IbanForm is the model behind the iban form.
 */
class IbanForm extends Model
{
    public $name;
    public $iban;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, iban are required
            [['name', 'iban'], 'required'],
            ['iban','validateIban'],
            
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Nom et prenom',
            'iban' => 'Iban',
        ];
    }

    public function validateIban($attribute, $params){
        $ibanToVerify = new IbanNumber($this->iban);
        $wrongIbanError = '';

        if(!$ibanToVerify->Verify()){
            $wrongIbanError .= 'Iban invalide !';
            $suggestions = $ibanToVerify->MistranscriptionSuggestions();
            if(is_array($suggestions)){
                if(count($suggestions) == 1){
                    $wrongIbanError .= ' Voulez-vous dire : ' . $suggestions[0] . '?';
                }
            }
            $this->addError($attribute, $wrongIbanError);
        }
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function iban($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setSubject("Envoi Iban")
                ->setTextBody($this->name . ' : ' . $this->iban)
                ->send();

            return true;
        }
        return false;
    }
}
