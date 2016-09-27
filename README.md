# grid
Yii2 widget - is a default grid with features.
This widget support changing page size from outbox, grouping features from Dosamigos\grid\GroupGridView and export to pdf.

For export to pdf you must set pdfAction (default - site/pdf).

Example action code (not ideal):

```php
public function actionPdf($format = 'A4')
{    
    $html = Yii::$app->request->post('html','no data');
    $pdf = new \mPDF;
    $pdf->setFooter('page.{PAGENO} of {nb}');
    $pdf->AddPageByArray(['newformat' => $format]);
    $css = '<link rel="stylesheet" type="text/css" href="css/print.css"/>'; //example css
    $pdf->writeHtml($css.$html);
    $pdf->Output("output.pdf", "I");
}

```


