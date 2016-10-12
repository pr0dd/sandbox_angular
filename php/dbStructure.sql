-- CREATE DATABASE db_name CHARACTER SET latin1 COLLATE latin1_swedish_ci;

-- CREATE DATABASE bikeride CHARACTER SET latin1 COLLATE latin1_swedish_ci;

ALTER TABLE products ADD approved VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'no';
ALTER TABLE configs ADD deliveryRu VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;



-- ====== available start page images ======
CREATE TABLE availspi (
	id INT NOT NULL AUTO_INCREMENT,
    src VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO availspi (`src`) 
	VALUES('
		assets/img/spi/1.jpg,
		assets/img/spi/2.jpg,
		assets/img/spi/3.jpg,
		assets/img/spi/4.jpg,
		assets/img/spi/5.jpg,
		assets/img/spi/6.jpg
	');


-- ====== startpageimages ======
CREATE TABLE startpageimages (
	id INT NOT NULL AUTO_INCREMENT,
    src VARCHAR(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    catId INT NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO startpageimages (`src`,`catId`) 
	VALUES 
	(	'assets/img/spi/1.jpg', 1	),
	(	'assets/img/spi/2.jpg', 2	),
	(	'assets/img/spi/3.jpg', 3	),
	(	'assets/img/spi/4.jpg', 4	),
	(	'assets/img/spi/5.jpg', 5	),
	(	'assets/img/spi/6.jpg', 6	);



-- ====== available category images ======
CREATE TABLE availcti (
	id INT NOT NULL AUTO_INCREMENT,
    src VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO availcti (`src`) 
	VALUES
	('assets/img/cti/1.jpg,
		assets/img/cti/2.jpg,
		assets/img/cti/3.jpg,
		assets/img/cti/4.jpg,
		assets/img/cti/5.jpg,
		assets/img/cti/6.jpg');



-- ====== available promotion images ======
CREATE TABLE availpromi (
	id INT NOT NULL AUTO_INCREMENT,
    src VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO availpromi (`src`) 
	VALUES
	('assets/img/promi/lpa-1.jpg,assets/img/promi/lpa-2.jpg,assets/img/promi/lpa-3.jpg');


-- ====== available category images ======
CREATE TABLE availpri (
	id INT NOT NULL AUTO_INCREMENT,
	productId INT NOT NULL,
    src VARCHAR(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO availpri (`productId`,`src`) 
	VALUES
	(1,'assets/img/pri/1/vl-1-1.jpg, assets/img/pri/1/vl-1-2.jpg, assets/img/pri/1/vl-1-3.jpg'),
	(2,'assets/img/pri/2/vl-2-1.jpg, assets/img/pri/2/vl-2-2.jpg, assets/img/pri/2/vl-2-3.jpg'),
	(3,'assets/img/pri/3/vl-3-1.jpg, assets/img/pri/3/vl-3-2.jpg, assets/img/pri/3/vl-3-3.jpg'),
	(4,'assets/img/pri/4/vl-4-1.jpg, assets/img/pri/4/vl-4-2.jpg, assets/img/pri/4/vl-4-3.jpg'),
	(5,'assets/img/pri/5/ac-1-1.jpg, assets/img/pri/5/ac-1-2.jpg, assets/img/pri/5/ac-1-3.jpg'),
	(6,'assets/img/pri/6/gb-1-1.jpg, assets/img/pri/6/gb-1-2.jpg, assets/img/pri/6/gb-1-3.jpg'),
	(7,'assets/img/pri/7/kc-1-1.jpg, assets/img/pri/7/kc-1-2.jpg, assets/img/pri/7/kc-1-3.jpg'),
	(8,'assets/img/pri/8/sk-1-1.jpg, assets/img/pri/8/sk-1-2.jpg, assets/img/pri/8/sk-1-3.jpg'),
	(9,'assets/img/pri/9/pc-1-1.jpg, assets/img/pri/9/pc-1-2.jpg, assets/img/pri/9/pc-1-3.jpg');


-- ====== products ======
CREATE TABLE products (
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ukrName VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    price VARCHAR(20),
    catId INT NOT NULL,
    catRu VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    catUkr VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    catEn VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    brand VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    descrRu VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    descrUkr VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    color VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    approved VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'no',
    views INT NOT NULL DEFAULT 1,
    inStock INT NOT NULL DEFAULT 0,
    date BIGINT,
    images VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    videos VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    tech TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    techUkr TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    parts TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    partsUkr TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    
    PRIMARY KEY(id)
);

INSERT INTO 
	products 
	(	`name`,
		`ukrName`,
		`price`, 
		`catId`,
		`catRu`,
		`catUkr`,
		`catEn`,
		`brand`,
		`descrRu`,
		`descrUkr`,
		`color`,	
		`views`,	
		`inStock`,	
		`date`,	
		`images`,	
		`videos`,	
		`tech`,	
		`techUkr`,	
		`parts`,	
		`partsUkr`	
	) 
	VALUES 
	(	
		'Велосипед #1',
		'Велосипед #1',
		'30345.45', 
		2,
		'Электровелосипеды',
		'Електровелосипеди',
		'bikes',
		'Mersedes',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'black',	
		11,	
		5,	
		1466515053000,	
		'assets/img/pri/1/vl-1-1.jpg,assets/img/pri/1/vl-1-2.jpg,assets/img/pri/1/vl-1-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY,vNoKguSdy4Y',	
		'detail#1,detail#2,detail#3,detail#4',	
		'detail#1,detail#2,detail#3,detail#4',	
		'part#1,part#2,part#3,part#4',
		'part#1,part#2,part#3,part#4'
	),
	(	
		'Велосипед #2',
		'Велосипед #2',
		'30456.54', 
		2,
		'Электровелосипеды',
		'Електровелосипеди',
		'bikes',
		'Ferrari',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'blue',	
		15,	
		2,	
		1466515123000,	
		'assets/img/pri/2/vl-2-1.jpg,assets/img/pri/2/vl-2-2.jpg,assets/img/pri/2/vl-2-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Велосипед #3',
		'Велосипед #3',
		'41546.54', 
		2,
		'Электровелосипеды',
		'Електровелосипеди',
		'bikes',
		'Porsche',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'white',	
		7,	
		3,	
		1466515253000,	
		'assets/img/pri/3/vl-3-1.jpg,assets/img/pri/3/vl-3-2.jpg,assets/img/pri/3/vl-3-3.jpg',	
		'vNoKguSdy4Y',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Велосипед #4',
		'Велосипед #4',
		'37688.94', 
		2,
		'Электровелосипеды',
		'Електровелосипеди',
		'bikes',
		'Ferrari',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'red',	
		14,	
		1,	
		1466515223000,	
		'assets/img/pri/4/vl-4-1.jpg,assets/img/pri/4/vl-4-2.jpg,assets/img/pri/4/vl-4-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Сумка #1',
		'Сумка #1',
		'1785.54', 
		5,
		'Аксессуары',
		'Аксесуари',
		'accessories',
		'Bagland',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'silver',	
		10,	
		6,	
		1466515323000,	
		'assets/img/pri/5/ac-1-1.jpg,assets/img/pri/5/ac-1-2.jpg,assets/img/pri/5/ac-1-3.jpg',	
		'',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Гироборд #1',
		'Гіроборд #1',
		'18918.94', 
		6,
		'Гироборды',
		'Гіроборди',
		'gyroboards',
		'Heroland',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'blue',	
		19,	
		3,	
		1466515423000,	
		'assets/img/pri/6/gb-1-1.jpg,assets/img/pri/6/gb-1-2.jpg,assets/img/pri/6/gb-1-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Детский кар #1',
		'Дитяча машина #1',
		'28515.94', 
		3,
		'Детские электромобили',
		'Дитячі електромобілі',
		'kidcars',
		'Audi',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'blue',	
		23,	
		4,	
		1466515523000,	
		'assets/img/pri/7/kc-1-1.jpg,assets/img/pri/7/kc-1-2.jpg,assets/img/pri/7/kc-1-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Скутер #1',
		'Скутер #1',
		'34445.14', 
		4,
		'Электроскутеры',
		'Електроскутери',
		'scooters',
		'BMW',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'blue',	
		17,	
		0,	
		1466515623000,	
		'assets/img/pri/8/sk-1-1.jpg,assets/img/pri/8/sk-1-2.jpg,assets/img/pri/8/sk-1-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	),
	(	
		'Самокат #1',
		'Самокат #1',
		'25715.64', 
		1,
		'Электросамокаты',
		'Електросамокати',
		'pushcycles',
		'Legend',
		'Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum в 60-х годах и, в более недавнее время, программы электронной вёрстки типа Aldus PageMaker, в шаблонах которых используется Lorem Ipsum',
		'Вже давно відомо, що читабельний зміст буде заважати зосередитись людині, яка оцінює композицію сторінки. Сенс використання Lorem Ipsum полягає в тому, що цей текст має більш-менш нормальне розподілення літер на відміну від, наприклад',
		'blue',	
		9,	
		0,	
		1466515723000,	
		'assets/img/pri/9/pc-1-1.jpg,assets/img/pri/9/pc-1-2.jpg,assets/img/pri/9/pc-1-3.jpg',	
		'-CmadmM5cOk,m0Xb9BhfVjY',	
		'detail#1,detail#2,detail#3',	
		'detail#1,detail#2,detail#3',	
		'part#1,part#2,part#3',
		'part#1,part#2,part#3'
	);


-- ====== configs ======
CREATE TABLE configs (
	id INT NOT NULL AUTO_INCREMENT,
    phone1 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    phone2 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    phone3 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    addressRu1 VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    addressRu2 VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    addressUkr1 VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    addressUkr2 VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialIcon1 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialIcon2 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialIcon3 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialIcon4 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialIcon5 VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialLink1 VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialLink2 VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialLink3 VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialLink4 VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    socialLink5 VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    deliveryRu VARCHAR(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    deliveryUkr VARCHAR(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	configs (
		`phone1`,
		`phone2`,
		`phone3`, 
		`addressRu1`, 
		`addressRu2`, 
		`socialIcon1`, 
		`socialIcon2`, 
		`socialLink1`,
		`socialLink2`
		) 
	VALUES 
	(	'050-495-46-78',
		'067-512-03-86',
		'099-416-98-91',
		'Украина, г.Киев',
		'ул. Харьковское шоссе, 56',
		'fa-youtube',
		'fa-facebook-official',
		'https://www.youtube.com',
		'https://www.facebook.com'
	);


-- ====== todos ======
CREATE TABLE todos (
	id INT NOT NULL AUTO_INCREMENT,
    userId INT NOT NULL,
    done Boolean DEFAULT FALSE,
    task VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	todos (`userId`,`task`) 
	VALUES 
	(	1,
		'packages and web page editors now use Lorem Ipsum as their default model text'
	),
	(	1,
		'and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident'
	);


-- ====== promotions ======
CREATE TABLE promotions (
	id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    titleUkr VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    src VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    onsite VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'no',
    text VARCHAR(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    textUkr VARCHAR(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	promotions (`title`,`titleUkr`,`src`, `onsite`, `text`, `textUkr`) 
	VALUES 
	(	'АКЦИЯ №1',
		'АКЦИЯ №1',
		'assets/img/promi/lpa-1.jpg',
		'yes',
		'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации',
		'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации'
	),
	(	'АКЦИЯ №2',
		'АКЦИЯ №2',
		'assets/img/promi/lpa-2.jpg',
		'yes',
		'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации',
		'Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается при простой дубликации'
	);	

-- ====== users ======
CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
    login VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    name VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    pass VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    status VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	users (`login`,`name`,`pass`, `user`, `admin`, `super`) 
	VALUES 
	(	'Jonik',
		'Евгений',
		'',
		'super'
	),
	(	'Bobzzz',
		'Сергей',
		'',
		'user'
	),
	(	'Nickolas',
		'Коля',
		'',
		'admin'
	);


-- ====== customers ======
CREATE TABLE customers (
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    phone VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    email VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    address VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    orders VARCHAR(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    notes VARCHAR(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    uniq VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	customers (`name`,`phone`) 
	VALUES ('Дмитрий Петренко','789-456-123');


-- ====== orders ======
CREATE TABLE orders (
	id INT NOT NULL AUTO_INCREMENT,
    customerId INT NOT NULL,
    productId INT NOT NULL,
    quantity INT NOT NULL,
    orderCost VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    deliveryCost VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
    status VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'received',
    date BIGINT,
    notes VARCHAR(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);


-- ====== feeds ======
CREATE TABLE feeds (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    city VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    text VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    approved VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'no',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- ====== categories ======
CREATE TABLE cats (
    id INT NOT NULL AUTO_INCREMENT,
    views INT NOT NULL DEFAULT 1,
    src VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ruName VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ukrName VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    enName VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, 
    PRIMARY KEY(id)
);

INSERT INTO 
	cats (`views`,`src`,`ruName`,`ukrName`,`enName`) 
	VALUES 
	(	10,
		'assets/img/cti/1.jpg',
		'Электросамокаты',
		'Електросамокати',
		'pushcycles'
	),
	(	8,
		'assets/img/cti/2.jpg',
		'Электровелосипеды',
		'Електровелосипеди',
		'bikes'
	),
	(	7,
		'assets/img/cti/3.jpg',
		'Детские электромобили',
		'Дитячі електромобілі',
		'kidcars'
	),
	(	5,
		'assets/img/cti/4.jpg',
		'Электроскутеры',
		'Електроскутери',
		'scooters'
	),
	(	6,
		'assets/img/cti/5.jpg',
		'Аксессуары',
		'Аксесуари',
		'accessories'
	),
	(	3,
		'assets/img/cti/6.jpg',
		'Гироборды',
		'Гіроборди',
		'gyroboards'
	);


-- ====== trackviews ======
CREATE TABLE trackviews (
	id INT NOT NULL AUTO_INCREMENT,
	productId INT NOT NULL,
	date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);









-- ====== comments ======
CREATE TABLE comments (
	id INT NOT NULL AUTO_INCREMENT,
    postId INT,
    userId INT,
    reply Boolean DEFAULT FALSE,
    replyToUserId INT DEFAULT NULL,
    replyToCommentId INT DEFAULT NULL,
    date BIGINT,
    likes INT,
    dislikes INT,
    ratedBy VARCHAR(3500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    msg VARCHAR(2500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(id)
);

INSERT INTO 
	comments (`postId`,`userId`,`reply`,`date`,`likes`,`dislikes`,`ratedBy`,`msg`) 
	VALUES 
	(	7,
		2,
		FALSE,
		1469193453000,
		7,
		2,
		'2,3,5',
		'Чудова стаття! Так тримати Нюта!'
	),
	(	7,
		1,
		TRUE,
		1469254653000,
		12,
		3,
		'1,3,5',
		'Дякую, дуже приємно!'
	);





-- ====== customers ======
CREATE TABLE customers (
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30),
    phone VARCHAR(30),
    PRIMARY KEY(id)
);

INSERT INTO 
	customers (`name`,`phone`) 
	VALUES ('Дмитрий Петренко','789-456-123');

-- ====== orders ======
CREATE TABLE orders (
	orderId INT NOT NULL AUTO_INCREMENT,
    customerId INT NOT NULL,
    pink INT,
    yellow INT,
    blue INT,
    done Boolean DEFAULT FALSE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(orderId)
);

-- ====== orders ======
CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30),
    pass VARCHAR(255),
    PRIMARY KEY(id)
);

-- ====== dirtyfeeds ======
CREATE TABLE dirtyfeeds (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30),
    city VARCHAR(30),
    text VARCHAR(250),
    PRIMARY KEY(id)
);

