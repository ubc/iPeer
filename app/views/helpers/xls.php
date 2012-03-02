<?php
/**
 * This xls helper is based on the one at
 * http://bakery.cakephp.org/articles/view/excel-xls-helper
 *
 * The difference compared with the original one is this helper
 * actually creates an xml which is openable in Microsoft Excel.
 *
 * Written by Yuen Ying Kit @ ykyuen.wordpress.com
 *
 */
class XlsHelper extends AppHelper
{
    /**
     * set the header of the http response.
     *
     * @param unknown_type $filename
     */
    function setHeader($filename)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Type: application/force-download");
        header("Content-Type: application/download");;
        header("Content-Disposition: inline; filename=\"".$filename.".xls\"");
    }

    /**
     * add the xml header for the .xls file.
     *
     */
    function addXmlHeader()
    {
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        echo "          xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
        echo "          xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        echo "          xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n";
        return;
    }

    /**
     * add the worksheet name for the .xls.
     * it has to be added otherwise the xml format is incomplete.
     *
     * @param unknown_type $workSheetName
     */
    function setWorkSheetName($workSheetName)
    {
        echo "\t<Worksheet ss:Name=\"".$workSheetName."\">\n";
        echo "\t\t<Table>\n";
        return;
    }

    /**
     * add the footer to the end of xml.
     * it has to be added otherwise the xml format is incomplete.
     *
     */
    function addXmlFooter()
    {
        echo "\t\t</Table>\n";
        echo "\t</Worksheet>\n";
        echo "</Workbook>\n";
        return;
    }

    /**
     * move to the next row in the .xls.
     * must be used with closeRow() in pair.
     *
     */
    function openRow()
    {
        echo "\t\t\t<Row>\n";
        return;
    }

    /**
     * end the row in the .xls.
     * must be used with openRow() in pair.
     *
     */
    function closeRow()
    {
        echo "\t\t\t</Row>\n";
        return;
    }

    /**
     * Write the content of a cell in number format
     *
     * @param unknown_type $Value
     */
    function writeNumber($Value)
    {
        if (is_null($Value)) {
            echo "\t\t\t\t<Cell><Data ss:Type=\"String\"> </Data></Cell>\n";
        } else {
            echo "\t\t\t\t<Cell><Data ss:Type=\"Number\">".$Value."</Data></Cell>\n";
        }
        return;
    }

    /**
     * Write the content of a cell in string format
     *
     * @param unknown_type $Value
     */
    function writeString($Value)
    {
        echo "\t\t\t\t<Cell><Data ss:Type=\"String\">".$Value."</Data></Cell>\n";
        return;
    }
}
