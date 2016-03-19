<?php
    /*$dbhost = 'localhost';
    $dbuser = ‘root’;
    $dbpass = ‘1234’;
    $dbname = 'library_system';
    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');*/
    $host="127.0.0.1";
    $port=3306;
    $socket="";
    $user="admin";
    $password="1234";
    $dbname="library_system";

    $link = mysqli_connect($host, $user, $password, $dbname);
    //$con = new mysqli($host, $user, $password, $dbname, $port, $socket) or die ('Could not connect to the database server' . mysqli_connect_error());

    mysqli_query($link, "SET NAMES 'utf8'");
    // select db
    //$db=mysql_select_db($dbname);

    if(!$link) {
    	// create db
    	mysqli_query($link, 'CREATE DATABASE IF NOT EXISTS `library_system` CHARACTER SET utf8 COLLATE utf8_general_ci');
    	// select db
    	mysqli_select_db($dbname);
    	// create table
	    mysqli_query($link, 'create table IF NOT EXISTS `member`(
		    `name` varchar(45) not null,
		    `account` char(9) primary key not null,
		    `password` varchar(30) not null,
		    `email` varchar(45) not null,
		    `authority` int not null DEFAULT 1,
		    `address` varchar(60),
		    `token` char(16),
    		`active` bit default b"0"
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `classification`(
			`id` int primary key auto_increment not null,
		    `class_id` int,
		    `name` varchar(45) not null,
		    `sub_class` varchar(150),
		    foreign key(`class_id`) references classification(`id`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `author`(
			`id` int primary key auto_increment not null,
		    `name` varchar(45) not null,
		    `organization` varchar(45)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `publisher`(
			`id` int primary key auto_increment not null,
		    `name` varchar(45) not null,
		    `address` varchar(60)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `book`(
		    `isbn` char(17) primary key not null,
		    `title` varchar(64) not null,
		    `main_class` int not null,
		    `sub_class` int not null,
		    `publisher` int not null,
		    `date` date not null default "0000-00-00",
		    `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		    foreign key(`main_class`) references classification(`id`),
		    foreign key(`sub_class`) references classification(`id`),
		    foreign key(`publisher`) references publisher(`id`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `book_author`(
			`id` int primary key auto_increment not null,
		    `author_id` int not null,
		    `isbn` char(17) not null,
		    foreign key(`isbn`) references book(`isbn`),
		    foreign key(`author_id`) references author(`id`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `book_copy` (
			`id` int primary key auto_increment not null,
		    `isbn` char(17) not null ,
		    `code` char(10) not null ,
		    `number` int(3) not null,
		    `status` varchar(30) not null,
		    foreign key(`isbn`) references book(`isbn`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `log`(
			`id` int primary key auto_increment not null,
		    `account` char(9) not null,
		    `copy_id` int not null,
		    `check_in` datetime null,
		    `check_out` datetime null,
		    `hold` datetime null ,
		    `hold_cancel` datetime null,
		    `due` date null,
		    foreign key(`account`) references member(`account`),
		    foreign key(`copy_id`) references book_copy(`id`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `fine`(
			`id` int primary key auto_increment not null,
		    `logid` int not null unique,
		    `days` int not null,
		    `fine` int not null,
		    `payment` bit default b"0",
		    foreign key(`logid`) references log(`id`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `message`(
			`id` int primary key auto_increment not null,
		    `account` char(9) not null,
		    `title` varchar(50) not null,
		    `content` varchar(1000) not null,
		    `send` datetime null,
		    `read` datetime null,
		    `delete` datetime null,
		    `action` varchar(45) not null,
		    foreign key(`account`) references member(`account`)
		)');
		mysqli_query($link, 'create table IF NOT EXISTS `comment`(
			`id` int primary key auto_increment not null,
		    `isbn` char(17) not null,
		    `account` char(9) not null,
		    `rate` FLOAT not null,
		    `comment` varchar(500),
		    `date` datetime null,
		    `anonymous` bit default b"0",
		    foreign key(`account`) references member(`account`),
		    foreign key(`isbn`) references book(`isbn`)
		)');
		mysqli_query($link, "INSERT INTO `member`(`name`,`account`,`password`,`email`,`authority`) VALUES('Admin','102590018','1234','h6g2682@gmail.com','3')");
		mysqli_query($link, "INSERT INTO `member`(`name`,`account`,`password`,`email`,`authority`) VALUES('Neo','103590018','1234','h6g2682@hotmail.com','2')");
		mysqli_query($link, "INSERT INTO `member`(`name`,`account`,`password`,`email`,`authority`) VALUES('Justin','104590018','1234','h6g2682@yahoo.com.tw','1')");
		mysqli_query($link, "INSERT INTO `member`(`name`,`account`,`password`,`email`,`authority`) VALUES('Adam','105590018','1234','h6g2682@gmail.com','1')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('Khaled Hosseini  卡勒德‧胡賽尼')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('李靜宜')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('Rhonda Byrne 朗達．拜恩')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('謝明憲')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('Mitch Albom 米奇‧艾爾邦')");
		mysqli_query($link, "INSERT INTO `author`(`name`) VALUES('吳品儒')");

		mysqli_query($link, "INSERT INTO `publisher`(`name`) VALUES('木馬文化')");
		mysqli_query($link, "INSERT INTO `publisher`(`name`) VALUES('方智')");
		mysqli_query($link, "INSERT INTO `publisher`(`name`) VALUES('大塊文化')");

		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('總類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('哲學類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('宗教類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('科學類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('應用科學類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('社會科學類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('中國史地類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('世界史地類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('語言文學類')");
		mysqli_query($link, "INSERT INTO `classification`(`name`) VALUES('藝術類')");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('特藏','1','善本; 稿本; 精鈔本, 舊鈔本; 紀念文庫; 中山文庫; 指定文庫; 鄉土文庫; 學位論文, 送審論文; 禁書')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('目錄學總論','1','圖書學; 總目錄; 國家書目; 特種書目; 其他特種目錄; 專科目錄; 個人目錄; 藏書目錄; 讀書法')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('圖書資訊學總論','1','圖書館建築, 圖書館設備; 圖書館管理; 圖書館業務; 各類圖書館, 特殊圖書館; 專門圖書館; 普通圖書館, 檔案學, 檔案館; 資訊處理, 資訊事業; 私家藏書')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('國學總論','1','古籍源流; 古籍讀法及研究; 各國漢學研究; 漢學會議 ; 漢學家傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('類書總論, 百科全書總論','1','分類類書; 摘錦類書; 韻目類書; 歲時類書 ; 常識手冊; 青少年百科全書. 兒童百科全書; 各國百科全書')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('連續性出版品, 普通期刊','1','學術期刊; 調查研究報告; 機關雜誌; 娛樂雜誌; 婦女雜誌, 家庭雜誌; 青少年雜誌, 兒童雜誌; 普通畫報; 普通年鑑; 普通報紙')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('普通會社總論','1','國際性普通會社; 中國普通會社; 各國普通會社; 基金會; 博物館學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('普通論叢','1','雜考; 雜說; 雜品; 雜纂; 西學雜論; 現代論叢; 各國論叢')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('普通叢書','1','明以前叢書; 清代叢書, 近代叢書; 民國叢書, 現代叢書; 輯逸叢書; 各國叢書; 郡邑叢書; 翻譯叢書,西學叢書; 族姓叢書; 自著叢書')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('經學總論','1','易經; 書經; 詩經; 禮; 春秋; 孝經; 四書; 群經總義; 小學及樂經')");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('哲學總論','2','哲學理論; 哲學教育及研究; 哲學辭典; 哲學期刊; 哲學團體; 哲學論文集; 哲學叢書; 哲學史')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('思想、學術概說','2','中國思想、學術; 東方思想、學術; 西洋思想、學術; 知識的區分; 人文學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國哲學總論','2','先秦哲學; 漢代哲學; 魏晉六朝哲學; 唐代哲學; 宋元哲學; 明代哲學; 清代哲學; 現代哲學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('東方哲學總論','2','日本哲學; 韓國哲學; 猶太哲學; 阿拉伯哲學; 波斯哲學; 中東各國哲學; 印度哲學; 東南亞各國哲學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('西洋哲學總論','2','古代哲學; 中世哲學; 近世哲學; 英國哲學; 美國哲學; 法國哲學; 德奧哲學; 義大利哲學; 其他西洋各國哲學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('邏輯總論','2','演繹邏輯; 歸納邏輯; 科學方法論; 辯證邏輯; 模態邏輯; 數理邏輯; 機率; 專業邏輯; 邏輯各論')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('形上學總論','2','知識論; 方法論; 宇宙論; 本體論; 價值論; 真理論; 宇宙論問題各論; 存有論問題各論')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('心理學總論','2','心理學研究方法; 生理心理學; 一般心理; 比較心理學; 離常心理學, 超心理學; 心理學各論; 應用心理學; 臨床心理學; 心理計量, 心理測量')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('美學總論','2','美意識; 美與感覺; 美之形式; 美之內容; 審美情感; 審美判斷; 各派美學')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('倫理學總論','2','倫理學理論; 個人倫理, 修身; 家庭倫理; 性倫理, 婚姻; 社會倫理; 國家倫理; 生命倫理學; 職業倫理; 道德各論')");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('宗教總論','3','宗教政策; 宗教教育及研究; 宗教辭典; 宗教期刊; 宗教團體; 宗教論文集; 宗教叢書; 宗教史')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('宗教學總論','3','宗教經驗; 宗教倫理道德; 宗教行為及組織; 宗教與文化; 原始宗教; 宗教觀念與思想; 宗教交流; 各宗教比較論, 比較宗教學; 宗教思想史；宗教學史')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('佛教總論','3','經及其釋; 論及其釋; 律及其釋; 佛教儀制, 佛教文藝; 佛教布教及信仰生活; 佛教宗派; 寺院; 佛教史地; 佛教傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('道教總論','3','道藏; 道教規律; 道教儀式; 道教修鍊; 道教宗派; 道觀及道教組織; 道教史; 道教傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('基督教總論','3','聖經; 神學, 教義學; 教義文獻; 實際神學, 儀式; 教牧學; 宗派; 教會, 社會神學; 基督教史; 基督教傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('伊斯蘭教總論','3','伊斯蘭教經典; 伊斯蘭教論疏; 伊斯蘭教規律; 伊斯蘭教儀式; 伊斯蘭教布教; 伊斯蘭教宗派; 伊斯蘭教教會及組織; 伊斯蘭教史; 伊斯蘭教傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('猶太教總論','3','猶太教經典; 猶太教教義; 猶太教規律; 猶太教儀式; 猶太教布教; 猶太教宗派; 猶太教教會及組織; 猶太教史; 猶太教傳記')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('其他宗教','3','國其他宗教; 祠祀; 日本神道; 婆羅門教, 印度教; 祆教; 其他東方諸宗教; 希臘羅馬之宗教; 條頓系及北歐之宗教; 其他')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('神話總論','3','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('術數總論','3','')");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('科學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('數學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('天文學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('物理學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('化學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('地球科學總論, 地質學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('生物科學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('植物學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('動物學總論','4','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('人類學總論','4','')");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('應用科學總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('醫藥總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('家政總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('農業總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('工程學總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('礦冶總論','5','')");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('化學工程','5',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('製造總論','5',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('商業總論','5',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('商學總論','5',''");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('社會科學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('統計學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('教育總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('禮俗總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('社會學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('經濟學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('財政學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('政治學總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('法律總論','6',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('軍事總論','6',''");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('史地總論','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國通史','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國斷代史','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國文化史','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國外交史','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國史料','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國地理總志','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國地方志總論','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國地理類志','7',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國遊記','7',''");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('世界史地','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('海洋志總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('亞洲史地總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('歐洲史地總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('美洲史地總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('非洲史地總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('大洋洲總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('傳記總論','8',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('文物考古總論','8',''");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('語言學總論','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('文學總論','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國文學總論','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國文學總集','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('中國文學別集','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('各地方文學, 各民族文學, 各體文學','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('東方文學總論','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('西洋文學總論','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('俄國文學','9',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('新聞學總論','9',''");

		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('藝術總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('音樂總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('建築藝術總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('雕塑總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('繪畫總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('攝影總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('應用美術總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('技藝總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('戲劇總論','10',''");
		mysqli_query($link, "INSERT INTO `classification`(`name`,`class_id`,`sub_class`) VALUES('遊藝及休閒活動總論','10',''");
		
    }
    ?>