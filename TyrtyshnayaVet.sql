-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Апр 17 2026 г., 20:27
-- Версия сервера: 8.4.6
-- Версия PHP: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `TyrtyshnayaVet`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(2, 'admin', '5f1ac8204ba9896d7c8ac031fea1dddf'),
(3, 'admin_4', '$2y$12$rCH.wmw2L.YVflGXLD7HGexeFnYpYdJSmNBtXXUFncanmEcS/2sq6'),
(4, 'admin_5', '$2y$12$i8MW3VpK/SdHiU6nYXyAr.kQfTcMcVEErM2vlS8RxTWtgV8bv/Weq'),
(5, 'admin_6', '$2y$12$dpVuwVDJEWfIx0GLuCGm/.ETcc/dM2ylcHRQz8FE2y8f1uurd0/au');

-- --------------------------------------------------------

--
-- Структура таблицы `callback_requests`
--

CREATE TABLE `callback_requests` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'новый',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `callback_requests`
--

INSERT INTO `callback_requests` (`id`, `user_id`, `name`, `phone`, `message`, `status`, `note`, `created_at`) VALUES
(1, 1, 'Иванов Иван', '+79657247598', '', 'обработан', 'Обработан', '2026-03-17 14:07:08'),
(2, 2, 'Миронова Алиса Викторовна', '+79652135476', '', 'в работе', '', '2026-03-21 12:09:49'),
(3, 2, 'Миронова Алиса Викторовна', '+79652135476', '', 'новый', NULL, '2026-03-21 12:50:29'),
(4, 2, 'Миронова Алиса Викторовна', '+79652135476', 'Не звонить после 21.00', 'в работе', 'противная', '2026-03-27 15:03:29');

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(31, 2, 1, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `value`) VALUES
(1, 'Товары для кошек', 'cat'),
(2, 'Товары для собак', 'dog'),
(5, 'Товары для птиц', 'bird');

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`) VALUES
(6, 2, 3),
(15, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `index`
--

CREATE TABLE `index` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `index`
--

INSERT INTO `index` (`id`, `name`, `content`, `image`, `sort_order`) VALUES
(1, 'about', '<h1>Ветеринарный центр \"Ветеринар и К\"</h1>\r\n<p>Современная диагностика, европейские стандарты лечения и индивидуальный подход к каждому пациенту. Мы оснастили клинику оборудованием экспертного класса, чтобы точно определять причины заболеваний и назначать эффективное лечение.</p>\r\n<p>В нашей команде — сертифицированные специалисты с опытом работы от 10 лет. Хирург Марина Анатольевна Точилкина регулярно повышает квалификацию в ведущих ветеринарных центрах России и Европы.</p>\r\n<p>Мы работаем с 8 утра до 20 вечера без выходных. В экстренных случаях звоните — примем вне очереди.</p>', 'about.jpg', 1),
(3, 'gallery', 'Наша клиника', 'photo1.jpg', 3),
(4, 'gallery', 'Наши пациенты', 'photo2.jpg', 4),
(5, 'contacts', '<p><strong>Адрес:</strong> г. Орёл, ул. Московская, 104</p><p><strong>Телефон:</strong> +7 (4862) 78-34-35</p><p><strong>Часы работы:</strong> Пн-Пт 9:00-20:00, Сб-Вс 9:00-18:00</p>', 'map.jpg', 5),
(6, 'gallery', 'Наши пациенты', 'photo3.jpg', 6),
(7, 'gallery', 'Наши пациенты', 'photo4.jpg', 7),
(8, 'gallery', 'Наши пациенты', 'photo5.jpg', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_desk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_desk` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `short_desk`, `long_desk`, `img`) VALUES
(1, 'Скидка на стерилизацию в марте', 'Весь март действует скидка 20% на стерилизацию кошек и собак.', 'Дорогие клиенты! Весь март мы дарим вам 20% скидку на стерилизацию кошек и собак. Акция распространяется на все породы. Обязательна предварительная запись по телефону. Количество мест ограничено. Успейте записаться!', 'news1.jpg'),
(2, 'Новый врач-офтальмолог', 'В нашей клинике начал приём врач-офтальмолог с 15-летним стажем.', 'Мы рады сообщить, что к нашей команде присоединилась врач-офтальмолог Петрова Анна Сергеевна. Теперь в нашей клинике можно провести полную диагностику зрения у животных, лечение катаракты, глаукомы и других заболеваний глаз. Приём ведётся по предварительной записи.', 'news2.jpg'),
(3, 'График работы в праздники', 'Обратите внимание на изменения в графике работы в праздничные дни.', 'Уважаемые клиенты! В праздничные дни наша клиника работает по следующему графику: 8 марта - с 10:00 до 18:00. В остальные дни работаем в обычном режиме: пн-пт 9:00-20:00, сб-вс 9:00-18:00. Экстренные вызовы принимаем круглосуточно по телефону +7 (4862) 78-34-35.', 'news3.jpg'),
(4, 'Бесплатная консультация по питанию', 'В феврале все желающие могут получить бесплатную консультацию по питанию животных.', 'Весь февраль в нашей клинике проходит акция: бесплатная консультация ветеринарного диетолога. Наш специалист поможет подобрать правильный рацион для вашего питомца, расскажет о кормах и добавках. Консультация проводится по предварительной записи. Количество мест ограничено.', 'news4.jpg'),
(5, 'Вакцинация со скидкой', 'При комплексной вакцинации – чипирование в подарок!', 'Только до конца месяца при оплате комплексной вакцинации вашего питомца вы получаете чипирование абсолютно бесплатно! Вакцинация включает защиту от основных вирусных заболеваний и бешенства. Успейте воспользоваться предложением!', 'news5.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `total_amount` int DEFAULT '0',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Новый',
  `cancel_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `fullname`, `phone`, `email`, `address`, `comment`, `total_amount`, `status`, `cancel_reason`, `date`) VALUES
(6, 'ЗАКАЗ №20260407-382', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', '', 17670, 'Подтвержден', '', '2026-04-07 13:19:06'),
(7, 'ЗАКАЗ №20260410-724', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', '', 1250, 'Новый', NULL, '2026-04-10 17:18:59'),
(8, 'ЗАКАЗ №20260410-194', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', '', 1250, 'Отменен', 'не указаны товары\r\n', '2026-04-10 17:20:04'),
(9, 'ЗАКАЗ №20260410-367', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', '', 4750, 'Новый', NULL, '2026-04-10 17:25:04'),
(10, 'ЗАКАЗ №20260411-855', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', 'leplr', 24806, 'Новый', NULL, '2026-04-11 10:36:06'),
(11, 'ЗАКАЗ №20260411-487', 2, 'Миронова Алиса Викторовна', '+79652135476', 'alis@yandex.ru', 'Московская ул., 104, Орёл', '', 6127, 'Новый', NULL, '2026-04-11 15:41:17');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_price` int DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_number`, `product_title`, `product_price`, `quantity`) VALUES
(7, 'ЗАКАЗ №20260407-382', 'Бравекто жев. таблетка для собак от 20 до 40 кг, 1000 мг (1 таб/уп) ', 6200, 3),
(8, 'ЗАКАЗ №20260410-724', 'Elanco Мильбемакс Таблетки от гельминтов для крупных кошек весом более 2 кг, со вкусом говядины, 2 таблетки', 1250, 1),
(9, 'ЗАКАЗ №20260410-367', 'Мультикан 6 вакцина для собак, 1 доза', 5000, 1),
(10, 'ЗАКАЗ №20260411-855', 'Бравекто жев. таблетка для собак от 20 до 40 кг, 1000 мг (1 таб/уп) ', 6200, 2),
(11, 'ЗАКАЗ №20260411-855', 'Мультикан 6 вакцина для собак, 1 доза', 5000, 2),
(12, 'ЗАКАЗ №20260411-855', 'Pro Plan сухой корм для кошек с чувствительным пищеварением, ягненок.(400г)', 606, 2),
(13, 'ЗАКАЗ №20260411-855', 'Elanco Мильбемакс Таблетки от гельминтов для крупных кошек весом более 2 кг, со вкусом говядины, 2 таблетки', 1250, 2),
(14, 'ЗАКАЗ №20260411-487', 'Бравекто жев. таблетка для собак от 20 до 40 кг, 1000 мг (1 таб/уп) ', 6200, 1),
(15, 'ЗАКАЗ №20260411-487', 'РИО Корм для волнистых попугаев, основной рацион', 250, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id`, `name`, `content`, `price`) VALUES
(1, 'Первичный приём', '', 500),
(2, 'Терапия', 'Постановка диагноза, определение причин заболевания, назначение и принятие лечения, предупреждение развития болезни в будущем, соблюдение мер профилактики.', 500),
(3, 'Акушерство и гинекология', 'Родовспоможение, реанимация новорождённых котят и щенков, удаление новообразований на половых органах, лечение различных гинекологических заболеваний.', 1000),
(4, 'Офтальмология', 'Лечение различных инфекционных заболеваний, поражающих органы зрения, лечение различных видов травм глазного аппарата, блефаропластика, энуклеация глаз при травме.', 1200),
(5, 'Дерматология', 'Диагностика любых кожных заболеваний, экспресс-анализы, соскобы, трихоскопия, лечение кожных заболеваний.', 500),
(6, 'Стоматология', 'Удаление зубов, чистка зубов ультразвуком.', 1000),
(7, 'Пластическая хирургия', '', 4000),
(8, 'Онкохирургия', '', 4000),
(9, 'Стерилизация кошки', '', 4000),
(10, 'Кастрация кота', '', 2200),
(11, 'Хирургия', 'Все виды хирургических манипуляций. Все операции кроме экстренных проводятся строго по предварительной записи. Напоминаем, что указанная здесь цена является ориентировочной и зависит от индивидуальных особенностей конкретного пациента.', 2200),
(12, 'Процедурные услуги', 'Инъекции, инфузии, капельницы, местные обработки.\r\nРазвернутая консультация', 100),
(13, 'Лабораторные исследования', 'Биохимия крови, клиника крови, моча, кал, инфекции, мазки на готовность к вязке.', 700),
(14, 'Вакцинация', 'Получение ветпаспорта и постановка на ветеринарный учёт.', 2000),
(15, 'Стрижка когтей кошкам, собакам, грызунам', '', 300),
(16, 'Обрезка клюва птицам\r\n', '', 300),
(17, 'Обрезка зубов кроликам\r\n', '', 300);

-- --------------------------------------------------------

--
-- Структура таблицы `shop`
--

CREATE TABLE `shop` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `img` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `category` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `shop`
--

INSERT INTO `shop` (`id`, `title`, `price`, `img`, `description`, `stock`, `category_id`, `category`) VALUES
(1, 'Бравекто жев. таблетка для собак от 20 до 40 кг, 1000 мг (1 таб/уп) ', 6200, 'prod1.jpg', '«Бравекто» — жевательные таблетки для собак весом от 20 до 40 кг (содержат 1000 мг флураланера) для защиты от блох и клещей. Обеспечивает защиту на 12 недель (85 дней). Действует через 4 часа: гибель блох наступает через 8 часов, клещей — через 12 часов. Применяется перорально во время или после кормления (обычно охотно поедается благодаря ароматизатору; разламывать таблетку нельзя). Подходит щенкам с 8‑недельного возраста и весом от 2 кг, может использоваться для беременных и кормящих собак под контролем ветеринара. Противопоказан при индивидуальной непереносимости компонентов. В редких случаях возможны побочные эффекты: рвота, диарея, снижение аппетита, слюнотечение. Перед применением необходима консультация ветеринара. Хранят в сухом, защищённом от света месте. Срок годности — 2 года.', 4, 2, 'dog'),
(2, 'Мультикан 6 вакцина для собак, 1 доза', 5000, 'prod2.jpg', '«Мультикан‑6» — вакцина для собак, предназначенная для профилактики чумы, аденовирусных инфекций, парвовирусного и коронавирусного энтеритов, а также лептоспироза. Препарат состоит из двух компонентов: сухого (лиофилизированного) — живой вакцины с ослабленными штаммами вирусов чумы собак, аденовируса типа 2, парвовируса и коронавируса собак, и жидкого — инактивированной вакцины с убитыми штаммами лептоспир трёх серогрупп (Icterohaemorrhagiae, Canicola и Grippotyphosa) с адъювантом, который одновременно служит растворителем для сухого компонента. Сухой компонент расфасован по 1,0 см \r\n3\r\n  (1 доза), жидкий — по 2,0 мл (1 доза) во флаконы или ампулы вместимостью 3 мл. Вакцина вводится внутримышечно в область бедра: перед применением жидкий компонент подогревают до 36–38  \r\n∘\r\n C, им растворяют сухой компонент, затем тщательно взбалтывают до образования однородной суспензии и используют в течение 15 минут после приготовления. Щенкам вакцину вводят двукратно в возрасте 8–10 недель с интервалом 21–28 суток, ревакцинацию проводят в 10–12 месяцев; взрослым собакам — один раз в год, а если животное ранее не вакцинировалось, то требуется двукратная вакцинация с интервалом 21–28 суток; собакам мелких и декоративных пород (включая щенков) прививают по той же схеме, но в дозе 1 мл. Иммунный ответ формируется через 2–3 недели после иммунизации: у молодняка иммунитет сохраняется 6–8 месяцев, у взрослых собак — 12–15 месяцев. Вакцина противопоказана клинически больным и ослабленным животным, собакам в последний месяц беременности и в первый месяц после родов, а также щенкам младше 8 недель. Как правило, побочные явления не отмечаются, но возможна быстро исчезающая припухлость на месте инъекции; в случае аллергических реакций применяют антигистаминные препараты. Среди особых указаний: вакцину нельзя смешивать в одном шприце с другими биопрепаратами и лекарственными средствами, не следует вакцинировать животных в течение 7 дней после дегельминтизации и 14 дней после обработки хлор- и фосфорсодержащими препаратами, а сыворотку крови вакцинированных собак не исследуют в реакции микроагглютинации (РМА) на наличие антител к лептоспирам в течение 2 месяцев после введения вакцины. Срок годности препарата составляет 18 месяцев при условии хранения в сухом тёмном месте при температуре 2–8  \r\n∘\r\n C. Перед применением необходимо внимательно ознакомиться с полной инструкцией по использованию вакцины.', 12, 2, 'dog'),
(3, 'Pro Plan сухой корм для кошек с чувствительным пищеварением, ягненок.(400г)', 606, 'prod3.jpg', 'Pro Plan Delicate Digestion — сухой полнорационный корм супер‑премиум‑класса для взрослых кошек с чувствительным пищеварением (в т. ч. при нестабильном стуле, рвоте, привередливости в еде). Содержит индейку (18 %), рис, кукурузный и гороховый белок, яичный порошок, рыбий жир, высушенный корень цикория (2 % — натуральный пребиотик), витамины, минералы и антиоксиданты. Гарантированные показатели: белок — 40 %, жир — 18 %, сырая зола — 7,5 %, клетчатка — 1,5 %, Омега‑3 — 0,5 %. Легко усваивается, поддерживает здоровье пищеварительной системы и почек, подходит для повседневного кормления. Можно комбинировать с влажными кормами Pro Plan. Индивидуальную норму кормления рассчитывают по QR‑коду на упаковке — она зависит от возраста, веса и активности кошки. Хранить в сухом прохладном месте. Перед переходом на корм рекомендуется проконсультироваться с ветеринаром.', 3, 1, 'cat'),
(5, 'Elanco Мильбемакс Таблетки от гельминтов для крупных кошек весом более 2 кг, со вкусом говядины, 2 таблетки', 1250, 'prod4.jpg', 'Мильбемакс — антигельминтный препарат от компании Elanco для крупных кошек весом более 2 кг. Выпускается в форме таблеток со вкусом говядины, покрытых оболочкой для маскировки горького вкуса. В упаковке 2 таблетки.', 9, 1, 'cat'),
(6, 'РИО Корм для волнистых попугаев, основной рацион', 250, '1775893378_447.jpg', 'Основной рацион - тщательно сбалансированная зерновая смесьдля ежедневного кормления волнистых попугайчиков, в состав которой отобраны самые полезные и любимые попугайчиками зерна и семена. Их разнообразие обеспечит птицу всеми необходимыми питательными веществами. Содержит редкие семена абиссинского нуга идругие любимые попугайчиками зерна исемена.', 4, 5, 'bird');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `temp_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `phone`, `fullname`, `password`, `temp_password`, `role`) VALUES
(1, 'Игнатий ', 'werdt@yandex.ru', '+79657247598', 'Иванов Иван', 'ded9d440ad7e94c7231d555cbe35e10a', NULL, 'user'),
(2, 'Alis_245', 'alis@yandex.ru', '+79652135476', 'Миронова Алиса Викторовна', '1673f08a613ade3fb07d2cbbcfc92824', NULL, 'user'),
(10, 'admin', NULL, NULL, NULL, '5f1ac8204ba9896d7c8ac031fea1dddf', NULL, 'admin'),
(11, 'Василиса', 'Vasilisa@yandex.ru', '+79657247598', 'Выдрова Василиса Валерьевна', 'f7a4e6a8dc79f33354c5716a9963b515', NULL, 'user'),
(12, 'wer', 'werc@e.tr', '+78456214756', 'qwerfgfdxsz', '$2y$12$.wpvplnhHTxToB3NE6WbNOWVGr52vrw0xY6yF7cv2GkxkbGYHie9a', NULL, 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `callback_requests`
--
ALTER TABLE `callback_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `index`
--
ALTER TABLE `index`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `callback_requests`
--
ALTER TABLE `callback_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `callback_requests`
--
ALTER TABLE `callback_requests`
  ADD CONSTRAINT `callback_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `shop` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
