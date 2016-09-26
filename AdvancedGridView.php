<?php
/**
 * @copyright Copyright (c) 2016 Ilya Shumilov
 * @version 1.0.0
 * @link https://github.com/restlin/grid
 */
namespace restlin\grid;

use restlin\grid\PageSize;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;

/**
 * AdvancedGridView - gridview with changed pageSize, grouping columns and export to PDF
 * It support all group features from Dosamigos\grid\GroupGridView.
 * Also this widget has integrated page size and export to pdf (use Mpdf). 
 * @version 1.0.0
 * @author restlinru@yandex.ru
 */
class AdvancedGridView extends \dosamigos\grid\GroupGridView
{    
    /**
     * @var array the list of page sizes
     */
    public $pageSizes = [20 => 20, 50 => 50, 100 => 100];
    /**
     * Default value
     * @var integer
     */
    public $pageSizeDefault = 20;

    /**
     * GET parameter name (default name for Pagination)
     * @var string
     */
    public $pageSizeparamName = 'per-page';
    /**
     * ID for pageSize select tag
     * @var string
     */
    public $pageSizeId = 'ps-id';
    /**
     * Page format from Mpdf (A4, A4-L and etc)
     * @var string
     */
    public $pdfFormat = 'A4';
    
    /**
     * Action id, that print your html from grid to mpdf
     * @todo Add example with action code!
     * @var string
     */
    public $pdfAction = 'site/pdf';
    /**
     * Runs the widget.
     */
    public function run()
    {        
        $this->filterSelector = '#'.$this->pageSizeId;
        $view = $this->getView();
        if($this->pdfFormat!==false) {
            $url = Yii::$app->urlManager->createUrl([$this->pdfFormat,'format'=>$this->pdfFormat]);
            $js=' $(".btn-grid-pdf").on("click",gridToPdf);
            function gridToPdf()
            {
               var html="<div class=\'grid-view\'>"+$(this).parents(\'.grid-view\').html()+"</div>";               
               $(\'#print-grid-content\').val(html);
               $(\'#print-grid-form\').get(0).submit();
            }';
            ActiveForm::begin(['action' => $url,'id'=>'print-grid-form']);
            echo Html::hiddenInput('html','',['id'=>'print-grid-content']);
            ActiveForm::end();
            $view->registerJs($js);
        }
        parent::run();
    }
    /**
     * Renders the summary text.
     * @return string
     */
    public function renderSummary()
    {
        $btnPrint = $this->pdfFormat ? '&nbsp;'.Html::button('PDF',[
            'class'=>'btn btn-xs btn-info btn-grid-pdf',
            'title' => Yii::t('app','Export to PDF'),
            'style' => 'margin-bottom: 3px;'
        ]) : '';
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return $btnPrint;
        }
        $summaryOptions = $this->summaryOptions;
        $tag = ArrayHelper::remove($summaryOptions, 'tag', 'div');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            $widget = PageSize::widget([
                'id'=>$this->pageSizeId,
                'default'=>$this->pageSizeDefault,
                'sizes'=>$this->pageSizes,
                'paramName'=>$this->pageSizeparamName,
            ]);
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}.', [
                        'begin' => $begin,
                        'end' => $end,
                        'count' => $count,
                        'totalCount' => $totalCount,
                        'page' => $page,
                        'pageCount' => $pageCount,
                    ]).' '.$widget.$btnPrint
                , $summaryOptions);
            }
        }
        return parent::renderSummary().$btnPrint;
    }
}
