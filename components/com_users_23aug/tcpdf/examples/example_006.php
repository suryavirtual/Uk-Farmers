<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '<h1>HTML Example</h1>
Some special characters: &pound; 
<ul>
	<li>Intervet, Merial, Novartis and Schering-Plough products:</li>
	<ul>
		<li>pricing at trade less 13.75%</li>
		<li>see new rebate arrangement below</li>
	</ul>
	</li>
</ul>
<ul>
	<li>Retrospective Rebates, payable at end of terms period:
	<ul>
		<li>0.25% for all Intervet, Schering-Plough, Merial and Novartis products</li>
		<li>5% for all Trilanco products</li>
		<li>1.75% for all other products (i.e. except those listed above)</li>
		<li>Carriage paid deliveries at &pound;385.00</li>
	</ul>
	</li>
</ul>
<ul>
	<li>Vaccine deliveries will incur additional charges for cool boxes and polar packs (when NOT delivered by Trilanco vehicles):
	<ul>
		<li>Large container &pound;15.00</li>
		<li>Small container &pound;8.00</li>
		<li>If returned to Trilanco in good condition they will credit back less &pound;2.00 handling charge</li>
		<li>No charges apply if vaccines delivered by Trilanco vehicles</li>
		<li>Prices will be circulated directly</li>
		<li>Payment is United Farmers normal term</li>
	</ul>
	</li>
</ul>

<h3>Please see below prices for Flexothane Clothing:</h3>
<table border="1">
<tbody>
<tr>
	<th valign="TOP" width="372">Product</th>
	<th valign="TOP" bgcolor="#ffff99" width="101" style="color: #ff0000; text-align:right;">Invoice Price</th>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;9.73</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;9.73</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;9.73</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;9.73</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;10.51</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Rotterdam Trousers Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;11.29</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;16.30</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;16.30</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;16.30</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;16.30</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;17.60</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Dortmund Coat Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;18.90</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;11.72</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;11.72</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;11.72</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;11.72</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;12.66</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montana Fleece Olive Green</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;13.60
	</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montreal Coverall Navy Blue</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;25.32</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montreal Coverall Navy Blue</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;25.32</td>
</tr>
<tr>
	<td valign="TOP">Flexothane Classic Montreal Coverall Navy Blue</td>
	<td valign="BOTTOM" bgcolor="#ffff99" style="color: #ff0000; text-align:right;">&pound;25.32</td>
</tr>

</tbody>
</table>
<p><span style="font-size: xx-small;">1. Agreed on 12 month agreement with annual review</span></p>
<p><span style="font-size: xx-small;">2. Standard UF Payment Terms apply123</span></p>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
