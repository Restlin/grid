<?php

/**
 * @copyright Copyright (c) 2016 Ilya Shumilov
 * @version 1.0.0
 * @link https://github.com/restlin/grid
 */


namespace restlin\grid;

use yii;
use yii\helpers\Html;

/**
 * This is widget to control grid page size. It's fork from nterms/yii2-pagesize-widget with some code fixes.
 * @author restlinru@yandex.ru
 */
class PageSize extends \yii\base\Widget
{

    /**
     * Field label
     * @var string
     */
    public $label = 'Page Size';

    /**
     * Default value
     * @var integer
     */
    public $default = 20;

    /**
     * GET parameter name (default name for Pagination)
     * @var string
     */
    public $paramName = 'per-page';

    /**
     * @var array the list of page sizes
     */
    public $sizes = [20 => 20, 50 => 50, 100 => 100];

    /**
     * Widget render template
     * @var string
     */
    public $template = '{label}: {list}';

    /**
     * DropDownList options
     * @var array
     */
    public $options;

    /**
     * Label options
     * @var array
     */
    public $labelOptions;

    /**
     * Label encoding
     * @var boolean
     */
    public $encodeLabel = true;

    /**
     * Runs the widget
     */
    public function run()
    {
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->id;
        }

        if ($this->encodeLabel) {
            $this->label = Html::encode($this->label);
        }

        $size = Yii::$app->request->get($this->paramName,$this->default);

        return str_replace(['{list}', '{label}'], [
            Html::dropDownList($this->paramName, $size, $this->sizes, $this->options),
            Html::label(Yii::t('app',$this->label), $this->options['id'], $this->labelOptions)
        ], $this->template);
    }

}
