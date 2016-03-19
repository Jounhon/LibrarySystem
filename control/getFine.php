<?php
	require_once '../control/conn.php';
	$account=$_POST['account'];
	$data=array();
	$fine_query=mysqli_query($link, 'SELECT COUNT(*) AS count, SUM(f.fine) as total
		FROM `fine` f 
			INNER JOIN `log` l
				ON l.id=f.logid
			INNER JOIN `member` m
				ON m.account=l.account
		WHERE f.payment=0 AND m.account="'.$account.'"  GROUP BY l.account');
	while($res=mysqli_fetch_row($fine_query)){
		$fine=mysqli_query($link, 'SELECT b.title,l.check_in,l.check_out,l.due,f.days,f.fine
			FROM `fine` f
				INNER JOIN `log` l
					ON l.account="'.$account.'" 
				INNER JOIN book_copy bc
					ON l.code=bc.code
				INNER JOIN book b
					ON b.isbn=bc.isbn
			WHERE f.payment=0 AND l.id=f.logid');
		$fine_list=array();
		while($list=mysqli_fetch_row($fine)){
			$ci = date_create($list[1]);
			$co = date_create($list[2]);
			$fine_list[]=array('title'=>$list[0],'checkin'=>date_format($ci, 'Y-m-d'),'checkout'=>date_format($co, 'Y-m-d'),'due'=>$list[3],'days'=>$list[4],'fine'=>$list[5]);
		}
		$data[]=$fine_list;
		$data[]=array("count"=>$res[0],"total"=>$res[1]);
	}
	echo json_encode($data);
?>