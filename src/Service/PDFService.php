<?php

namespace App\Service;

use Qipsius\TCPDFBundle\Controller\TCPDFController;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Pdf Service
 */
class PDFService
{
    protected TCPDFController $tcpdf;

    /** KernelInterface $appKernel */
    private KernelInterface $appKernel;

    public function __construct(TCPDFController $tcpdf, KernelInterface $appKernel)
    {
        $this->tcpdf = $tcpdf;
        $this->appKernel = $appKernel;
    }

    public function generate($html, $filename)
    {
        $pdf = $this->tcpdf->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Mohamed Rami Aouinti');
        $pdf->SetTitle('Our Code World Title');
        $pdf->SetSubject('List of Tasks');
        // set default header data
        $pdf->SetHeaderData($this->appKernel->getProjectDir() . '/public/logo.jpg', 700, 'Tasks'.' 048', 'Task');

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        $pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'D'); // This will output the PDF as a response directly
    }
}
