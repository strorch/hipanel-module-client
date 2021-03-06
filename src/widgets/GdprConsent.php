<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\client\widgets;

use hipanel\modules\client\models\Contact;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Class GdprConsent.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class GdprConsent extends Widget
{
    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var Contact
     */
    public $model;

    public function run()
    {
        if ((string) $this->model->id !== (string) Yii::$app->user->getId() || $this->model->gdpr_consent && $this->model->policy_consent) {
            return $this->renderHiddenInputs();
        }

        $this->registerClientScript();

        return $this->render('gdpr-consent', [
            'form' => $this->form,
            'model' => $this->model,
        ]);
    }

    public function registerClientScript()
    {
        $this->view->registerJs(<<<JS
(function () {
    var modal = $('#gdpr-consent-modal'),
        form = modal.closest('form'),
        acceptingButton = $('.gdpr-acception');
    
    var validToCloseModal = {
        policy_consent: false,
        gdpr_consent: false
    };
    var isGdprAccepted = function () {
        var gdprIsAccepted = true;
        for (var attr in validToCloseModal) {
            gdprIsAccepted = gdprIsAccepted && validToCloseModal[attr];
        }
        return gdprIsAccepted;
    }
    form.on('afterValidateAttribute', function (event, attribute, error) {
        if (validToCloseModal[attribute.name] === undefined) {
            return;
        }
        validToCloseModal[attribute.name] = error.length === 0;
        if (isGdprAccepted() && acceptingButton.data('clicked')) {
            modal.modal('hide');
        }
    });
    
    acceptingButton.click(function (event) {
        $(this).data('clicked', true);
        if (isGdprAccepted()) {
            modal.modal('hide');
        } else {
            form.yiiActiveForm('validate', true);
        }
    });
    
    modal.modal('show');
})()
JS
        );
    }

    private function renderHiddenInputs()
    {
        return implode('', [
            Html::activeHiddenInput($this->model, 'gdpr_consent'),
            Html::activeHiddenInput($this->model, 'policy_consent'),
        ]);
    }
}
