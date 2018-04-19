<?php

use app\models\forms\CarModelsForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $carModelsForm CarModelsForm */
/** @var $data array */

$js = <<<JS
$('#carmodelsform-country').on("select2:select", function(e) { 
   
   $.ajax({
        url: '/site/get-languages',
        method: 'POST',
        dataType: 'json',
        data: {
            countryName: e.params.data.text
        },
        success: function(response) {
            
            $('#carmodelsform-lang').html('');
            $('#carmodelsform-lang').removeAttr('disabled');
            $('#button_send_request').removeAttr('disabled');
                        
            if(response.id != undefined) {
                $('#carmodelsform-lang').append('<option value='+response.code+'>'+response.name+'</option');
            } else {
                $.each(response, function(index, value) {
                    $('#carmodelsform-lang').append('<option value='+value.code+'>'+value.name+'</option');  
                });
            }
            
            
        }
    });
   
});
JS;
$this->registerJs($js);


$form = ActiveForm::begin();

echo $form->field($carModelsForm, 'country')
    ->widget(Select2::class, [
        'data' => $data,
        'options' => [
            'placeholder' => 'Выберите страну',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]);

echo $form->field($carModelsForm, 'lang')
    ->dropDownList(
        [],
        [
            'placeholder' => 'Выберите язык',
            'disabled'    => true
        ]
    );

echo Html::submitButton('Обновить данные', [
    'title'    => 'Обновить данные',
    'id'       => 'button_send_request',
    'class'    => 'btn btn-success',
    'disabled' => true
]);

ActiveForm::end();



