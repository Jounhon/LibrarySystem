<?php
	session_start();
	require_once '../control/conn.php';
	$array=array();
	if($_POST['action']=='new'){
		$query=mysqli_query($link, 'SELECT `isbn`,`title` FROM `book` ORDER BY `time` DESC LIMIT 6');
	}
	else if($_POST['action']=='class'){
		if(!$_POST['cid'] || $_POST['cid']=='all'){
			$query=mysqli_query($link, 'SELECT `isbn`,`title` FROM `book` ORDER BY `time` DESC');
		}
		else{
			$query=mysqli_query($link, 'SELECT `isbn`,`title` FROM `book` WHERE `main_class` LIKE "%'.$_POST['cid'].'%" OR `sub_class` LIKE "%'.$_POST['cid'].'%" ORDER BY `time` DESC');
		}
	}
	else if($_POST['action']=='search'){
		$query_author=mysqli_query($link, 'SELECT `id` FROM `author` WHERE `name` LIKE "%'.$_POST['search'].'%"');
		$row=mysqli_num_rows($query_author);
		if($row>0) $author=mysqli_fetch_assoc($query_author);
		else $author='#';
		$query_publisher=mysqli_query($link, 'SELECT `id` FROM `publisher` WHERE `name` LIKE "%'.$_POST['search'].'%"');
		$row=mysqli_num_rows($query_publisher);
		if($row>0) $publisher=mysqli_fetch_assoc($query_publisher);
		else $publisher='#';
		$query=mysqli_query($link, 'SELECT DISTINCT book.isbn,book.title
					FROM `book` INNER JOIN `book_author` ba
						ON ba.isbn=book.isbn
					WHERE book.isbn like "%'.$_POST['search'].'%" 
						or book.title like "%'.$_POST['search'].'%" 
						or ba.author_id like "%'.$author['id'].'%" 
						or book.publisher = "'.$publisher['id'].'"
					ORDER BY book.time DESC');
	}
	elseif($_POST['action']=='getDetail'){
		$isbn=$_POST['isbn'];
		$query_book=mysqli_query($link, 'SELECT book.title,book.isbn,book.date,p.name as publisher,mc.name as mcName,sc.name as scName FROM book
					INNER JOIN publisher p
						ON book.publisher=p.id
					INNER JOIN classification mc
						ON book.main_class=mc.id
					INNER JOIN classification sc
						ON book.sub_class=sc.id
				WHERE book.isbn="'.$isbn.'"');
		$fetch_book=mysqli_fetch_assoc($query_book);
		$rate_query=mysqli_query($link, 'SELECT ROUND(AVG(`rate`),2) as rate FROM `comment` WHERE `isbn`="'.$isbn.'"');
		$fetch_rate=mysqli_fetch_assoc($rate_query);
		if($fetch_rate['rate']=='') $fetch_rate['rate']=0;
		$array[]=array('title'=>$fetch_book['title'], 
			'isbn'=>$fetch_book['isbn'], 
			'publisher'=>$fetch_book['publisher'], 
			'mc'=>$fetch_book['mcName'],
			'sc'=>$fetch_book['scName'],
			'date'=>$fetch_book['date'],
			'rate'=>$fetch_rate['rate']);
		/* ↑ get book detail*/
		$query_author=mysqli_query($link, 'SELECT a.name FROM book_author ba
					INNER JOIN author a
						ON ba.author_id=a.id
				WHERE ba.isbn="'.$isbn.'"');
		$author=array();
		while ($res=mysqli_fetch_row($query_author)){
			array_push($author,$res[0]);
		}
		$array[]=$author;
		/* ↑ get authors*/
		$copy=array();
		$query_copy=mysqli_query($link, 'SELECT `code`,`number`,`status` FROM `book_copy` WHERE `isbn`="'.$isbn.'" ORDER BY `number`');
		while($res=mysqli_fetch_row($query_copy)){
			switch($res[2]){
				case 'on-shelf':
					$color='#5cb85c';
				break;
				case 'on-hold':
					$color='#428bca';
				break;
				case 'on-loan':
					$color='#d9534f';
				break;
				case 'on-loan&on-hold':
					$color='#f0ad4e';
				break;
			}
			if($_POST['account']=='null'){
				$disabled='disabled';
			}
			else{

				$hold_query=mysqli_query($link, 'SELECT * FROM `log` WHERE `account`="'.$_POST['account'].'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
				$hold_count=mysqli_num_rows($hold_query);
				if($hold_count==3) $disabled='disabled';

				$check_query=mysqli_query($link, 'SELECT * FROM `log` WHERE `code`="'.$res[0].'" AND `account`="'.$_POST['account'].'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
				$check_count=mysqli_num_rows($check_query);
				if(($res[2]=='on-hold'||$res[2]=='on-loan&on-hold')&&$check_count==1) $disabled='';
				else if(($res[2]=='on-shelf'||$res[2]=='on-loan')&&$hold_count==3) $disabled='disabled';
				else if(($res[2]=='on-shelf'||$res[2]=='on-loan')&&$hold_count!=3) $disabled='';
				else $disabled='disabled';

				$account_query=mysqli_query($link, 'SELECT `active` FROM `member` WHERE `account`="'.$_POST['account'].'"');
				$fetch_account=mysqli_fetch_assoc($account_query);
				if($fetch_account['active']==0) $disabled='disabled';
			}
			$copy[]=array('code'=>$res[0], 'number'=>$res[1], 'status_color'=>$color,'disabled'=>$disabled,'checkCount'=>$check_count);
		}
		$array[]=$copy;
		/* ↑ get copy*/
		$comment=array();
		$comment_query=mysqli_query($link, 'SELECT c.rate,c.comment,
				IF(c.anonymous=1,"匿名",m.name) AS name 
				FROM `comment` c 
					INNER JOIN `member` m 
						ON m.account=c.account 
				WHERE c.isbn="'.$isbn.'" ORDER BY c.id DESC');
		$comment_rows=mysqli_num_rows($comment_query);
		if($comment_rows>0){
			while($res=mysqli_fetch_row($comment_query)){
				$comment[]=array('rate'=>$res[0], 'comment'=>$res[1], 'name'=>$res[2]);
			}
			$array[]=$comment;
		}
	}
	else if($_POST['action']=='getCopy'){
		$code=$_POST['code'];
		$query_copy=mysqli_query($link, 'SELECT bc.code,bc.number,bc.status FROM `book_copy` bc
				INNER JOIN `book_copy` bbc
				ON bbc.code="'.$code.'"
				WHERE bc.isbn=bbc.isbn ORDER BY bc.number');
		while($res=mysqli_fetch_row($query_copy)){
			switch($res[2]){
				case 'on-shelf':
					$color='#5cb85c';
				break;
				case 'on-hold':
					$color='#428bca';
				break;
				case 'on-loan':
					$color='#d9534f';
				break;
				case 'on-loan&on-hold':
					$color='#f0ad4e';
				break;
			}
			if($_POST['account']=='null'){
				$disabled='disabled';
			}
			else{

				$hold_query=mysqli_query($link, 'SELECT * FROM `log` WHERE `account`="'.$_POST['account'].'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
				$hold_count=mysqli_num_rows($hold_query);
				if($hold_count==3) $disabled='disabled';

				$check_query=mysqli_query($link, 'SELECT * FROM `log` WHERE `code`="'.$res[0].'" AND `account`="'.$_POST['account'].'" AND `hold` IS NOT NULL AND `hold_cancel` IS NULL');
				$check_count=mysqli_num_rows($check_query);
				if(($res[2]=='on-hold'||$res[2]=='on-loan&on-hold')&&$check_count==1) $disabled='';
				else if(($res[2]=='on-shelf'||$res[2]=='on-loan')&&$hold_count==3) $disabled='disabled';
				else if(($res[2]=='on-shelf'||$res[2]=='on-loan')&&$hold_count!=3) $disabled='';
				else $disabled='disabled';

				$account_query=mysqli_query($link, 'SELECT `active` FROM `member` WHERE `account`="'.$_POST['account'].'"');
				$fetch_account=mysqli_fetch_assoc($account_query);
				if($fetch_account['active']==0) $disabled='disabled';
			}
			$array[]=array('code'=>$res[0], 'number'=>$res[1], 'status_color'=>$color,'disabled'=>$disabled);
		}
	}

	if($_POST['action']!='getDetail'){
		while ($res=mysqli_fetch_row($query)){
			$array[]=array('isbn' => $res[0], 'title' => $res[1]);
		}

	}
	echo json_encode($array);
?>
