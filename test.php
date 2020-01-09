<?php
	     ob_start();
		include("connection.php");
        $sql = "SELECT * from transactions";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			$arr = array();
			$i = 0;
			while ($data = mysqli_fetch_assoc($result)) {
				while (list ($key, $value) = each($data))
					$arr[$i][$key] = $value;
				$i ++;
			}
		}
		
		ob_clean();
		
		
		set_time_limit(10);
		
		require_once "php_writeexcel/class.writeexcel_workbook.inc.php";
		require_once "php_writeexcel/class.writeexcel_worksheet.inc.php";
		
		$fname = tempnam("/tmp", "simple.xls");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		
		# The general syntax is write($row, $column, $token). Note that row and
		# column are zero indexed
		#
		
		# Write some text
		 $worksheet->write(0, 0,  "transaction_no");
		 $worksheet->write(0, 1,  "voucher_no");
		 $worksheet->write(0, 2,  "head_id");
		 $worksheet->write(0, 3,  "debit");
		 $worksheet->write(0, 4,  "credit");
		
		 for($i=0;$i<count($arr);$i++)
		 {
			 $k = $i+1;
			 $worksheet->write($k, 0,  $arr[$k]['transaction_no']);
			 $worksheet->write($k, 1,  $arr[$k]['voucher_no']);
			 $worksheet->write($k, 2,  $arr[$k]['head_id']);
			 $worksheet->write($k, 3,  $arr[$k]['debit']);
			 $worksheet->write($k, 4,  $arr[$k]['credit']);
			 
		}	
		
		$workbook->close();
		
		header("Content-Type: application/x-msexcel; name=\"example-simple.xls\"");
		header("Content-Disposition: inline; filename=\"example-simple.xls\"");
		$fh=fopen($fname, "rb");
		fpassthru($fh);
		unlink($fname);
?>