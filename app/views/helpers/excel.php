<?php
App::import('Vendor', 'PHPExcel', array('file' => 'excel/PHPExcel.php'));
App::import('Vendor', 'PHPExcelWriter', array('file' => 'excel/PHPExcel/Writer/Excel5.php'));

/**
 * ExcelHelper
 *
 * @uses AppHelper
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class ExcelHelper extends AppHelper
{
    public $xls;
    public $sheet;
    public $data;
    public $blacklist = array();

    /**
     * excelHelper
     *
     *
     * @access public
     * @return void
     */
    function excelHelper()
    {
        $this->xls = new PHPExcel();
        $this->sheet = $this->xls->getActiveSheet();
        $this->sheet->getDefaultStyle()->getFont()->setName('Verdana');
    }

    /**
     * generate
     *
     * @param mixed &$data data
     * @param bool  $title title
     *
     * @access public
     * @return void
     */
    function generate(&$data, $title = 'Report')
    {
        $this->data =& $data;
        $this->_title($title);
        $this->_headers();
        $this->_rows();
        $this->_output($title);
        return true;
    }


    /**
     * _title
     *
     * @param mixed $title
     *
     * @access protected
     * @return void
     */
    function _title($title)
    {
        $this->sheet->setCellValue('A2', $title);
        $this->sheet->getStyle('A2')->getFont()->setSize(23);
        $this->sheet->getRowDimension('2')->setRowHeight(333);
    }


    /**
     * _headers
     *
     *
     * @access protected
     * @return void
     */
    function _headers()
    {
        $i=0;
        $data = array_keys($this->data[0]);
        foreach ($data as $field) {
            if (!in_array($field, $this->blacklist)) {
                $columnName = Inflector::humanize($field);
                $this->sheet->setCellValueByColumnAndRow($i++, 4, $columnName);
            }
        }
        $this->sheet->getStyle('A4')->getFont()->setBold(true);
        $this->sheet->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->sheet->getStyle('A4')->getFill()->getStartColor()->setRGB('969696');
        $this->sheet->duplicateStyle($this->sheet->getStyle('A4'), 'B4:'.$this->sheet->getHighestColumn().'4');
        for ($j=1; $j<$i; $j++) {
            $this->sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($j))->setAutoSize(true);
        }
    }


    /**
     * _rows
     *
     *
     * @access protected
     * @return void
     */
    function _rows()
    {
        $i=5;
        foreach ($this->data as $row) {
            $j=0;
            foreach ($row as $field => $value) {
                if (!in_array($field, $this->blacklist)) {
                    $this->sheet->setCellValueByColumnAndRow($j++, $i, $value);
                }
            }
            $i++;
        }
    }


    /**
     * _output
     *
     * @param mixed $title
     *
     * @access protected
     * @return void
     */
    function _output($title)
    {
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="'.$title.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = new PHPExcel_Writer_Excel5($this->xls);
        $objWriter->setTempDir(TMP);
        $objWriter->save('php://output');
    }
}
