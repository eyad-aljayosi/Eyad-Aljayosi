
CREATE TABLE `user_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  -- تعيين AUTO_INCREMENT لزيادة الرقم تلقائيًا
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)  -- تعيين العمود id كمفتاح رئيسي
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `tbl_otp_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  -- تعيين AUTO_INCREMENT لزيادة الرقم تلقائيًا
  `otp` int(11) NOT NULL,
  `is_expired` tinyint(4) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)  -- تعيين العمود id كمفتاح رئيسي
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `logging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,       -- رقم معرف فريد يتم زيادته تلقائيًا
  `email` varchar(255) NOT NULL,              -- البريد الإلكتروني المرتبط بالعملية
  `my_datetime` datetime NOT NULL,            -- التاريخ والوقت للحدث
  `operation` varchar(255) NOT NULL,          -- وصف العملية
  PRIMARY KEY (`id`)                          -- تعيين `id` كمفتاح أساسي
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `admin_form` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,   -- معرف النشاط (سيزداد تلقائيًا)
  `user_id` int(11) NOT NULL,             -- معرف المستخدم (يرتبط بـ user_form)
  `message` text NOT NULL,                -- الرسالة الأصلية
  `shift` int(11) NOT NULL,               -- مقدار التحويل
  `encrypted_message` text NOT NULL,      -- الرسالة المشفرة
  `operation_type` enum('encrypt','decrypt') NOT NULL, -- نوع العملية (تشفير أو فك تشفير)
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(), -- وقت النشاط
  PRIMARY KEY (`id`),                     -- تعيين العمود id كمفتاح رئيسي
  KEY `user_id` (`user_id`),              -- إضافة فهرس على user_id لتحسين الأداء
  CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`id`) -- المفتاح الخارجي
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `points` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `difficulty` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;
