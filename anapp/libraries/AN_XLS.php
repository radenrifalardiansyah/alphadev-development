<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "libraries/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;

/**
 * XLS Class
 */
class AN_XLS
{
	var $CI;
	var $qualified;

	// simple export
	var $companyName;
	var $simpleExcel;
	var $objPHPExcel;
	var $objReader;
	var $objWriter;
	var $worksheet;
	var $tempFile;
	var $filename;
	var $title;
	var $subTitle;
	var $heading;
	var $exportDate;
	var $data;
	
	/**
	 * All styles settings
	 * @author	Yudha
	 */
	var $styleBorderThin = array(
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		),
	);

	var $styleBorderThick = array(
		'borders' => array(
			'outline' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
			),
		),
	);

	var $styleHeading = array(
		'font'	=> array(
			'bold' => true
		),
		'alignment' => array(
	        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	    ),
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			),
		),
		'fill' => array(
	        'fillType' 		=> \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
	        'rotation' 		=> 90,
	        'startColor' 	=> array('argb' => 'FFEFEFEF'),
	        'endColor' 		=> array('argb' => 'FFEFEFEF'),
	    ),
	);

	// var $styleLineBreak = \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder() );

	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
	{
		$this->CI =& get_instance();
		$this->companyName = COMPANY_NAME;
	}

	function setHeader($content_type, $filename = null) {
		//ob_end_clean();
		if (ob_get_contents()) ob_end_clean();

		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header('Content-Type: ' . $content_type);
		header('Content-Disposition: attachment;filename="' . $this->filename . '.xlsx"');

	}
	
	/**
	 * Set file properties
	 */
	function setProperties() {
		// Set document properties
        $this->objPHPExcel->getProperties()
            ->setCreator($this->companyName)
            ->setLastModifiedBy($this->companyName)
            ->setTitle($this->title)
            ->setSubject($this->title)
            ->setDescription($this->title)
            ->setKeywords($this->title)
            ->setCategory($this->title);
	}
	
	/**
	 * Init simple exporter
	 * 
	 */
	function simpleInit() {
		$this->objPHPExcel 	= new Spreadsheet();
		
		// setup properties
		$this->setProperties();

		$currentTime 		= time();
		$this->exportDate 	= date('d M, Y', $currentTime);
		$this->filename 	= $this->title . ' ' . date('d-m-Y_His');
		
		// set table header
		if ( is_array( $this->heading[0] ) ) {
			$_heading = array_reverse( $this->heading );
			foreach( $_heading as $heading ) {
				array_unshift( $this->data, $heading );
			}
		} else {
			array_unshift( $this->data, $this->heading );
		}
		// set export date
		array_unshift($this->data, array('Tanggal Export: ' . $this->exportDate));
		// set subtitle
		array_unshift($this->data, array($this->subTitle));
		// set main title
        array_unshift($this->data, array($this->title . ' - ' . $this->companyName));

        $set_cell 	= array();
    	$max_column = 0;
        foreach ($this->data as $row => $column) {
        	if ( $column ) {
	        	$alpha 	= 'A';
	        	foreach ($column as $col => $val) {
		        	$set_cell[($row+1)][$alpha] = $val;
		        	$alpha++;
			    	$max_column = $col;
	        	}
        	} else {
        		if ( $max_column > 0 ) {
		        	$alpha 	= 'A';
        			for ($i=0; $i <= $max_column; $i++) { 
			        	$set_cell[($row+1)][$alpha] = '';
			        	$alpha++;
        			}
        		}
        	}
        }

        if ( $set_cell ) {
        	foreach ($set_cell as $row => $column) {
	        	foreach ($column as $col => $val) {
	        		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$row, $val);
	        		if ( $row == 4 ) {
	        			$this->objPHPExcel->getActiveSheet()->getStyle($col.$row)->applyFromArray($this->styleHeading);
	        		}
	        		if ( $row >= 5 ) {
	        			$this->objPHPExcel->getActiveSheet()->getStyle($col.$row)->applyFromArray($this->styleBorderThin);
	        		}
	        	}
        	}
        }
	}

	/**
	 * Export Withdraw
	 *
	 */
	function withdraw( $export_data=array() ) {

		$this->title 		= 'Laporan Withdraw';
		$this->heading 		= array( 'No', 'Tanggal', 'Username', 'Nama', 'Bank', 'No. Rekening', 'Pemilik Rekening', 'Nominal WD', 'Biaya Admin', 'Nominal Transfer', 'Status', 'Tanggal Konfirmasi' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {

                $bill 			= ( $row->bill ) ? $row->bill : '-';
                $bill_name 		= ( $row->bill_name ) ? strtoupper($row->bill_name) : '-';
                $bank_name 		= '-';
                if ( $row->bank && $bank = an_banks($row->bank) ) {
	                if ( ! empty( $bank->kode ) && ! empty( $bank->nama ) ){
	                    $bank_name 	= strtoupper($bank->kode .' - '. $bank->nama);
	                }
                }

				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					strtoupper($row->username),
					strtoupper($row->name),
					$bank_name,
					$bill,
					$bill_name,
					$row->nominal,
					$row->admin_fund,
					$row->nominal_receipt,
					($row->status == 0 ? 'PENDING' : 'TRANSFERED'),
					( $row->status == 0 ? '' : date('Y-m-d H:i', strtotime($row->datemodified)) )
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $rowNumber, '=SUM(H5:H' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $rowNumber, '=SUM(I5:I' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $rowNumber, '=SUM(J5:J' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $rowNumber, '=SUM(K5:K' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $rowNumber, '=SUM(L5:L' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('H'.$rowNumber.':L'.$rowNumber)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('M5:N'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:L'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Deposite List
	 *
	 */
	function depositelist( $export_data=array() ) {

		$this->title 		= 'Laporan Deposite';
		$this->heading 		= array( 'No', 'Username', 'Nama', 'Jumlah');
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {

                $bill 			= ( $row->bill ) ? $row->bill : '-';
                $bill_name 		= ( $row->bill_name ) ? strtoupper($row->bill_name) : '-';
                $bank_name 		= '-';
                if ( $row->bank && $bank = an_banks($row->bank) ) {
	                if ( ! empty( $bank->kode ) && ! empty( $bank->nama ) ){
	                    $bank_name 	= strtoupper($bank->kode .' - '. $bank->nama);
	                }
                }

				$this->data[] = array(
					$no++ . '.',
					strtoupper($row->username),
					strtoupper($row->name),
					$row->total_deposite,
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('H'.$rowNumber.':L'.$rowNumber)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('M5:N'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:L'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Deposite List
	 *
	 */
	function memberdepositelist( $export_data=array() ) {
		$this->title 		= 'Laporan Member Deposite';
		$this->heading 		= array( 'No', 'Tanggal', 'Tipe', 'Status', 'Nominal', 'Keterangan');
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					strtoupper($row->source),
					$row->type,
					$row->amount,
					$row->description
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('H'.$rowNumber.':L'.$rowNumber)->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}
    
    /**
	 * Export Sales
	 *
	 */
	function sales( $export_data=array(), $status_order = '' ) {
		$this->title 		= 'Laporan Penjualan ('.strtoupper($status_order).')';
		$this->heading 		= array( 'No', 'Invoice', 'Username', 'Nama', 'Status Pembeli', 'Produk', 'Total QTY', 'Total WD', 'Total Pembayaran', 'Metode Pengiriman', 'Tanggal', 'Tanggal Expired', 'Konfirmasi', 'Dikonfirmasi Oleh' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
            $datenow        = date('Y-m-d H:i:s');
			foreach($export_data as $row) {
				$status         = '';
                if ( $row->status == 0 ) { $status = 'PENDING'; }
                if ( $row->status == 1 ) { $status = 'CONFIRMED'; }
                if ( $row->status == 2 ) { $status = 'DONE'; }
                if ( $row->status == 4 ) { $status = 'CANCELLED'; }

				$courier = "";
				if ( strtolower($row->shipping_method) == 'pickup' ) {
                    $courier    = 'PICKUP';
                    if ( $row->status == 2 ) {
                        $courier .= '\n Nama Pengambil : '. ( $row->resi ? strtoupper($row->resi) : '-' ) .'';
                    }
                } else {
                    $courier    = 'EKSPEDISI';
                    if ( $row->courier ) {
                        $courier .= ' \n '. lang('courier') .' : '. ( (strtolower($row->courier) == 'ekspedisi') ? '-' : strtoupper($row->courier) );
                        $courier .= ' \n Layanan : '. ( (strtolower($row->courier) == 'ekspedisi') ? '-' : strtoupper($row->service) );
                    }
                    if ( $row->status == 2 ) {
                        $courier .= ' \n Resi : '. ( $row->resi ? strtoupper($row->resi) : '-' ) .'';
                    }
                }

				$datemodified   = date('d M y H:i', strtotime($row->datemodified));
                $dateconfirm    = '-';
                $confirmed_by   = '-';
                if ( $row->status > 0 ) {
                    $confirmed_by   = $row->modified_by;
                }
                if ( $row->dateconfirmed != '0000-00-00 00:00:00' && $row->status == 1 ) {
                    $dateconfirm    = date('d M y H:i', strtotime($row->dateconfirmed));
                    $confirmed_by   = $row->confirmed_by;
                }

                $dateexpired    = '-';
                if ( $row->dateexpired && $row->dateexpired != '0000-00-00 00:00:00' ) {
                    $dateexpired    = date('d M y H:i', strtotime($row->dateexpired));
                    if ( $row->status == 0 && strtotime($datenow) > strtotime($row->dateexpired) ) {
                        $row->status = 4;
                        $datemodified = $dateexpired;
                        $confirmed_by = 'expired';
                    }
                }

				$confirmation   = 'PENDING';
                if ( $row->confirm == 'manual' ){ $confirmation = 'MANUAL'; }
                if ( $row->confirm == 'auto' )  { $confirmation = 'AUTO'; }

				$this->data[] = array(
					$no++ . '.',
					$row->invoice,
					strtoupper($row->username),
					strtoupper($row->name),
					$status,
					'', //product
					$row->total_qty,
					$row->total_bv,
					$row->total_payment,
					$courier,
					date("d-m-Y", strtotime($row->datecreated)),
					$dateexpired,
					$confirmation,
					$confirmed_by
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $rowNumber, '=SUM(H5:HG' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $rowNumber, '=SUM(I5:IG' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:N'.$rowNumber)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

		$this->objPHPExcel->getActiveSheet()->getStyle('A5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('F5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('G5:G'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:I'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('J5:J'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:I'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');
		$this->objPHPExcel->getActiveSheet()->getStyle('K5:N'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('J5:J'.$rowNumber)->getAlignment()->setWrapText(true);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export RO List
	 *
	 */
	function rolist( $export_data=array() ) {

		$this->title 		= 'Laporan RO';
		$this->heading 		= array( 'No', 'Tanggal', 'Invoice', 'Username', 'Nama', 'Keterangan');
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					$row->invoice,
					strtoupper($row->username),
					strtoupper($row->name),
					$row->desc
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export History Bonus List
	 *
	 */
	function historybonuslist( $export_data=array() ) {

		$this->title 		= 'Laporan Bonus Member';
		$this->heading 		= array( 'No', 'Tanggal', 'Username', 'Nama', 'Nominal', 'Tipe', 'Keterangan' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$type = "";
				if($row->type == 1){
					$type = "PENJUALAN";
				}elseif($row->type == 2){
					$type = "REFERRAL";
				}elseif($row->type == 3){
					$type = "PASS UP";
				}elseif($row->type == 4){
					$type = "KOMISI GROUP";
				}elseif($row->type == 5){
					$type = "KOMISI BREAK";
				}

				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					strtoupper($row->username),
					strtoupper($row->name),
					$row->amount,
					$type,
					$row->desc
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');
		$this->objPHPExcel->getActiveSheet()->getStyle('F5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('G5:G'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(100);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Total Bonus List
	 *
	 */
	function totalbonuslist( $export_data=array() ) {

		$this->title 		= 'Laporan Total Bonus';
		$this->heading 		= array( 'No', 'Username', 'Nama', 'Jumlah' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					strtoupper($row->username),
					strtoupper($row->name),
					$row->total,
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Member Bonus List
	 *
	 */
	function memberbonuslist( $export_data=array() ) {

		$this->title 		= 'Laporan Member Bonus Komisi';
		$this->heading 		= array( 'No', 'Tanggal', 'Nominal', 'Tipe', 'Keterangan' );
		$this->data			= array();
		
		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				
				$type = "";
				if($row->type == 1){
					$type = "PENJUALAN";
				}elseif($row->type == 2){
					$type = "REFERRAL";
				}elseif($row->type == 3){
					$type = "PASS UP";
				}elseif($row->type == 4){
					$type = "KOMISI GROUP";
				}elseif($row->type == 5){
					$type = "KOMISI BREAK";
				}

				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->datecreated)),
					$row->amount,
					$type,
					$row->desc
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $rowNumber, '=SUM(C5:C' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:C'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(100);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Register List
	 *
	 */
	function registerlist( $export_data=array() ) {

		$this->title 		= 'Laporan Pendaftaran Member';
		$this->heading 		= array( 'No', 'Pendaftar', 'Sponsor', 'Username', 'Nama', 'WA', 'Email', 'Status', 'Akses Pendaftaran', 'Tanggal Daftar', 'Tanggal Konfirmasi' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
                $status = '';
                if ($row->status == 0) {
                    $status = 'PENDING';
                } elseif ($row->status == 1) {
                    $status = 'CONFIRMED';
                } elseif ($row->status == 2) {
                    $status = 'CANCELLED';
                }

				$this->data[] = array(
					$no++ . '.',
					strtoupper($row->member),
					strtoupper($row->sponsor),
					strtoupper($row->downline),
					strtoupper($row->name),
					$row->phone,
					$row->email,
					$status,
					$row->access,
					date('Y-m-d H:i', strtotime($row->datecreated)),
					( $row->status == 0 ? '' : date('Y-m-d H:i', strtotime($row->datemodified)) )
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('F5:F'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('G5:K'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Omset List (Monthly)
	 *
	 */
	function omzetmonthlylist( $export_data=array() ) {

		$this->title 		= 'Laporan Omzet Bulanan';
		$this->heading 		= array( 'No', 'Tanggal', 'Pendaftaran', 'RO', 'Total Omzet', 'Total Omzet BV', 'Total Bonus', 'Persentase (%)' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$percent = $row->percent ? $row->percent : 0;
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->month_omzet)),
                    $row->total_omzet_register,
                    $row->total_omzet_ro,
                    $row->total_omzet,
                    $row->total_omzet_bv,
                    $row->total_bonus,
                    $percent
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $rowNumber, '=SUM(C5:C' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $rowNumber, '=SUM(F5:F' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $rowNumber, '=SUM(G5:G' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $rowNumber, '=SUM(H5:H' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:G'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:H'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:G'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Omset List (Daily)
	 *
	 */
	function omzetdailylist( $export_data=array() ) {

		$this->title 		= 'Laporan Omzet Harian';
		$this->heading 		= array( 'No', 'Tanggal', 'Pendaftaran', 'RO', 'Total Omzet', 'Total Omzet BV', 'Total Bonus', 'Persentase (%)' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$percent = $row->percent ? $row->percent : 0;
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->date_omzet)),
                    $row->total_omzet_register,
                    $row->total_omzet_ro,
                    $row->total_omzet,
                    $row->total_omzet_bv,
                    $row->total_bonus,
                    $percent
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $rowNumber, '=SUM(C5:C' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $rowNumber, '=SUM(F5:F' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $rowNumber, '=SUM(G5:G' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $rowNumber, '=SUM(H5:H' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:G'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('H5:H'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:G'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Omset Oerder List (Daily)
	 *
	 */
	function omzetorderdailylist( $export_data=array() ) {

		$this->title 		= 'Laporan Omzet Order Harian';
		$this->heading 		= array( 'No', 'Tanggal', 'Admin Generate', 'Stockist Order', 'Total Omzet' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->date_omzet)),
                    $row->omzet_generate,
                    $row->omzet_order,
                    $row->total_omzet,
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $rowNumber, '=SUM(C5:C' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:E'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Omset Oerder List (Montly)
	 *
	 */
	function omzetordermontlylist( $export_data=array() ) {

		$this->title 		= 'Laporan Omzet Order Bulanan';
		$this->heading 		= array( 'No', 'Tanggal', 'Admin Generate', 'Stockist Order', 'Total Omzet' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					date("d-m-Y", strtotime($row->date_omzet)),
                    $row->omzet_generate,
                    $row->omzet_order,
                    $row->total_omzet,
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $rowNumber, '=SUM(C5:C' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:B'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:E'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export Deposite Pin List
	 *
	 */
	function depositepinlist( $export_data=array() ) {

		$this->title 		= 'Product Member';
		$this->heading 		= array( 'No', 'Username', 'Nama', 'Jumlah', 'Jumlah Aktif' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				$this->data[] = array(
					$no++ . '.',
					strtoupper($row->username),
					strtoupper($row->name),
                    $row->total,
                    $row->total_active,
					date("d-m-Y", strtotime($row->datecreated))
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $rowNumber, '=SUM(D5:D' . ($rowNumber-1) . ')');
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $rowNumber, '=SUM(E5:E' . ($rowNumber-1) . ')');

		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('C5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:E'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:E'.$rowNumber)->getNumberFormat()->setFormatCode('#,##0');

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	/**
	 * Export PIN Member List
	 *
	 */
	function pinmemberlist( $export_data=array() ) {

		$this->title 		= 'Product Member Detail';
		$this->heading 		= array( 'No', 'ID Produk', 'Pengirim', 'Produk', 'Status', 'Tanggal', 'Tanggal Transfer' );
		$this->data			= array();

		// set data
		if ( $export_data ) {
			$no=1;
			foreach($export_data as $row) {
				if($row->status == 0)       { $status = 'PENDING'; }
                elseif($row->status == 1)   { $status = 'ACTIVE'; }
                elseif($row->status == 2)   { $status = 'USED'; }

				$this->data[] = array(
					$no++ . '.',
					$row->id_pin,
                    strtoupper($row->username_sender),
                    $row->product_name,
                    $status,
                    date('Y-m-d H:i', strtotime($row->datecreated)),
					( $row->status == 0 ? '' : date('Y-m-d H:i', strtotime($row->datemodified)) )
				);
			}
		}
		
		// add 3 new rows
		$this->data[] = array();
		$this->data[] = array();
		$this->data[] = array();

		// init simple export
		$this->simpleInit();
		
		$rowNumber = count($this->data);
		
		// write formula
		$this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->objPHPExcel->getActiveSheet()->getStyle('A5:C'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$this->objPHPExcel->getActiveSheet()->getStyle('D5:D'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$this->objPHPExcel->getActiveSheet()->getStyle('E5:G'.$rowNumber)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($this->title);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->setHeader('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$writer = IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
		$writer->save('php://output');
		exit;
	}


    // ---------------------------------------------------------------------------
}
// END Excel Class