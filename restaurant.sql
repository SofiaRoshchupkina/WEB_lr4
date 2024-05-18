-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 13 2024 г., 21:30
-- Версия сервера: 8.0.24
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `restaurant`
--

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `News_Id` int NOT NULL,
  `Ntext` varchar(350) NOT NULL,
  `Ntitle` varchar(100) NOT NULL,
  `Nurl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`News_Id`, `Ntext`, `Ntitle`, `Nurl`) VALUES
(1, 'Попробуйте новое блюдо в нашем ресторане \"ItalianTaste\"', 'Сливочная паста с креветками', 'https://polzavred-edi.ru/wp-content/uploads/2022/02/sous-dlja-spagetti-s-krevetkami.jpg'),
(2, 'Получите скидку 20% в свой день рождения, показав паспорт официанту', 'День рождения в WorldTaste', 'https://lookimg.com/images/2017/12/21/kFFzv.jpg'),
(3, 'Каждую пятницу вечер живой музыки во всех наших заведениях! \r\nСпешите забронировать столики, количество мест ограничено!', 'Вечер живой музыки!', 'https://www.sheron.ru/upload/iblock/08b/08b9788cf6113a32be0c46f1ebcfe6d4.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `меню`
--

CREATE TABLE `меню` (
  `Код_блюда` int NOT NULL,
  `Название_блюда` varchar(255) DEFAULT NULL,
  `Цена` decimal(10,2) DEFAULT NULL,
  `Ингредиенты` text,
  `Категория_блюда` varchar(50) DEFAULT NULL,
  `Murl` varchar(255) DEFAULT NULL,
  `Код_филиала` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `меню`
--

INSERT INTO `меню` (`Код_блюда`, `Название_блюда`, `Цена`, `Ингредиенты`, `Категория_блюда`, `Murl`, `Код_филиала`) VALUES
(1, 'Уха с семгой', '570.00', 'Картофель, морковь, сыр, семга', 'Суп', 'https://www.chefmarket.ru/blog/wp-content/uploads/2019/06/creamy-soup-300x200.jpg', 1),
(2, 'Стейк', '850.00', 'Мясо, овощи на гриле', 'Горячее блюдо', 'https://www.exquis.ro/wp-content/uploads/2020/01/friptura-de-vita-300x200.jpg', 3),
(3, 'Пеперони', '659.00', 'Тесто, пеперони, соус, сыр', 'Пицца', 'https://uaimages.com/assets/files/2021/09/12_2_002-300x200.jpg', 3),
(4, 'Шаверма MAX', '285.00', 'Лаваш, свежие овощи, курица, соус на кефире, сыр', 'Горячее блюдо', 'https://sh-dvorik.ru/wa-data/public/shop/products/69/09/969/images/193/193.300.jpg', 2),
(5, 'Борщ', '219.00', 'Бульон, свекла, капуста, картошка, говядина, сметана', 'Суп', 'https://готовим-мясо.рф/wp-content/uploads/2023/10/1663686606_11-mykaleidoscope-ru-p-borshch-so-smetanoi-oboi-15-300x200.jpg', 2),
(6, 'Морс', '120.00', 'Ягоды, вода, сахар', 'Напиток', 'https://wheyprotein.tv/wp-content/uploads/2019/06/arandano-rojo-americano-pastillas.jpg', 2),
(7, 'Сэндвич с курицей', '189.00', 'Тосты, курица варенная, соус, листья салата', 'Закуска', 'https://www.povarenok.ru/data/cache/2014dec/07/36/962423_21675-300x0.jpg', 2),
(9, 'Семга на гриле', '950.00', 'Семга, салат из сезонных овощей', 'Горячее блюдо', 'https://www.belmarrahealth.com/wp-content/uploads/2016/10/alzheimers-disease-risk-and-memory-loss-prpoblems-risk-lowered-by-eating-broiled-fish-300x200.jpg', 1),
(10, 'Брускетты с форелью', '410.00', 'Хлеб, творожный сыр, форель', 'Закуска', 'https://cdn.100sp.ru/cache_pictures/059158176/thumb300', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `капчи`
--

CREATE TABLE `капчи` (
  `IDcap` int NOT NULL,
  `URLcap` varchar(350) NOT NULL,
  `значение` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `капчи`
--

INSERT INTO `капчи` (`IDcap`, `URLcap`, `значение`) VALUES
(1, 'http://dmtsoft.ru/img/captcha/dmt-Simple-Captcha-1.png', 'neu4'),
(2, 'http://dmtsoft.ru/img/captcha/dmt-Simple-Captcha-2.png', 'bwjn'),
(3, 'http://dmtsoft.ru/img/captcha/dmt-Simple-Captcha-3.png', 'gazf'),
(4, 'http://dmtsoft.ru/img/captcha/dmt-Flow-captcha-1.jpeg', '774'),
(5, 'http://dmtsoft.ru/img/captcha/dmt-Flow-captcha-2.jpeg', '6643'),
(6, 'http://dmtsoft.ru/img/captcha/dmt-Flow-captcha-3.jpeg', '6411');

-- --------------------------------------------------------

--
-- Структура таблицы `поставщики`
--

CREATE TABLE `поставщики` (
  `Код_поставщика` int NOT NULL,
  `Название_поставщика` varchar(255) NOT NULL,
  `Адрес` varchar(255) DEFAULT NULL,
  `Телефон` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Код_филиала` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `поставщики`
--

INSERT INTO `поставщики` (`Код_поставщика`, `Название_поставщика`, `Адрес`, `Телефон`, `Email`, `Код_филиала`) VALUES
(1, 'Хлеба', 'ул. Ворошилова, д.7/1', '+88005553535', 'supplier1@example.com', 3),
(2, 'Мясной отдел', 'ул. Мясорубов, д.2', '+79006002211', 'pochtaeee@example.com', 3),
(3, 'Овощи', 'б-р. Пионеров, д.5', '+79009008080', 'ovoshiii@mail.ru', 1),
(4, 'Мясо и рыба', 'Фермерское пос., ул. Длинная, д.2', '+79098007744', 'meatandfishi@mail.ru', 1),
(5, 'Булочная', 'Пекарский пер., д.89', '+79523334411', 'pochtahleb@mail.ru', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `администраторы`
--

CREATE TABLE `администраторы` (
  `Код_администратора` int NOT NULL,
  `ФИО` varchar(255) DEFAULT NULL,
  `Логин` varchar(50) DEFAULT NULL,
  `Пароль` varchar(50) DEFAULT NULL,
  `Код_филиала` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `администраторы`
--

INSERT INTO `администраторы` (`Код_администратора`, `ФИО`, `Логин`, `Пароль`, `Код_филиала`) VALUES
(1, 'Алексеев Алексей Алексеевич', 'admin18', 'password12', 1),
(2, 'Дмитриев Дмитрий Дмитриевич', 'admin2', 'password2', 2),
(3, 'Сергеев Сергей Сергеевич', 'admin3', 'password3', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `бронирования`
--

CREATE TABLE `бронирования` (
  `Код_бронирования` int NOT NULL,
  `Дата_бронирования` date DEFAULT NULL,
  `Время_бронирования` time DEFAULT NULL,
  `Количество_персон` int DEFAULT NULL,
  `Код_филиала` int DEFAULT NULL,
  `Код_гостя` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `бронирования`
--

INSERT INTO `бронирования` (`Код_бронирования`, `Дата_бронирования`, `Время_бронирования`, `Количество_персон`, `Код_филиала`, `Код_гостя`) VALUES
(38, '2024-04-19', '12:30:00', 4, 3, 1),
(40, '2024-04-28', '10:00:00', 1, 3, 1),
(41, '2024-04-07', '10:00:00', 1, 3, 1),
(42, '2024-04-18', '10:00:00', 1, 1, 3),
(44, '2024-04-13', '10:00:00', 1, 3, 1),
(45, '2024-04-21', '10:00:00', 1, 3, 1),
(46, '2024-04-13', '10:00:00', 1, 3, 1),
(47, '2024-04-26', '10:00:00', 1, 1, 5),
(49, '2024-04-19', '10:00:00', 1, 3, 1),
(56, '2024-04-20', '10:00:00', 1, 3, 1),
(57, '2024-05-12', '10:00:00', 1, 1, 1),
(58, '2024-04-20', '13:00:00', 3, 2, 1),
(59, '2024-05-25', '15:30:00', 10, 3, 1),
(60, '2024-05-25', '13:30:00', 3, 1, 2),
(61, '2024-05-22', '18:00:00', 8, 1, 12),
(62, '2024-06-01', '16:00:00', 4, 1, 2),
(63, '2024-06-22', '17:00:00', 5, 1, 1),
(70, '2024-05-26', '16:30:00', 7, 1, 1),
(73, '2024-05-24', '17:00:00', 8, 1, 3),
(74, '2024-05-17', '13:00:00', 1, 1, 1),
(77, '2024-05-18', '15:00:00', 7, 1, 1),
(80, '2024-05-23', '18:30:00', 15, 1, 1),
(85, '2024-05-26', '21:00:00', 2, 1, 3),
(88, '2024-05-22', '17:00:00', 5, 1, 12),
(93, '2024-05-26', '13:00:00', 4, 1, 3),
(98, '2024-05-18', '14:30:00', 6, 1, 5),
(100, '2024-05-26', '15:30:00', 10, 1, 12),
(101, '2024-05-13', '21:00:00', 2, 1, 1),
(102, '2024-07-11', '19:00:00', 5, 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `гости`
--

CREATE TABLE `гости` (
  `Код_гостя` int NOT NULL,
  `ФИО` varchar(255) DEFAULT NULL,
  `Телефон` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Логин` varchar(50) DEFAULT NULL,
  `Пароль` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `гости`
--

INSERT INTO `гости` (`Код_гостя`, `ФИО`, `Телефон`, `Email`, `Логин`, `Пароль`) VALUES
(1, 'Иванов Иван Иванович', '+79101234566', 'ivanov@example.com', 'guest1', 'password1'),
(2, 'Петров Петр Петрович', '+79102345678', 'petrov@example.com', 'guest2', 'password2'),
(3, 'Сидоров Сидор Сидорович', '+79103456789', 'sidorov@example.com', 'guest3', 'password3'),
(5, 'Рощупкина София Евгеньевна', '+79658524141', 'pochtaaa@mail.ru', 'sofiya', '123'),
(12, 'Гончарова Нина', '+79639639663', 'pochtamy@mail.ru', 'yyy', '111');

-- --------------------------------------------------------

--
-- Структура таблицы `заказы`
--

CREATE TABLE `заказы` (
  `Код_блюда` int NOT NULL,
  `Код_бронирования` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `заказы`
--

INSERT INTO `заказы` (`Код_блюда`, `Код_бронирования`) VALUES
(2, 56),
(3, 56),
(1, 57),
(4, 58),
(5, 58),
(3, 59),
(1, 60);

-- --------------------------------------------------------

--
-- Структура таблицы `филиалы`
--

CREATE TABLE `филиалы` (
  `Код_филиала` int NOT NULL,
  `Адрес` varchar(255) DEFAULT NULL,
  `Телефон` varchar(20) DEFAULT NULL,
  `Название` varchar(255) DEFAULT NULL,
  `Время_работы` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `филиалы`
--

INSERT INTO `филиалы` (`Код_филиала`, `Адрес`, `Телефон`, `Название`, `Время_работы`) VALUES
(1, 'ул. Ленина, д. 1', '+79101112233', 'Морской', '11:00 - 23:00'),
(2, 'ул. Космонавтов, д. 2', '+79102223344', 'Бистро', '6:00 - 00:00'),
(3, 'ул. Пушкина, д. 3', '+79103334455', 'ItalianTaste', '12:00 - 1:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`News_Id`);

--
-- Индексы таблицы `меню`
--
ALTER TABLE `меню`
  ADD PRIMARY KEY (`Код_блюда`),
  ADD KEY `fk_filial_menu` (`Код_филиала`);

--
-- Индексы таблицы `капчи`
--
ALTER TABLE `капчи`
  ADD PRIMARY KEY (`IDcap`);

--
-- Индексы таблицы `поставщики`
--
ALTER TABLE `поставщики`
  ADD PRIMARY KEY (`Код_поставщика`),
  ADD KEY `fk_filial_supplier` (`Код_филиала`);

--
-- Индексы таблицы `администраторы`
--
ALTER TABLE `администраторы`
  ADD PRIMARY KEY (`Код_администратора`),
  ADD KEY `fk_filial_admin` (`Код_филиала`);

--
-- Индексы таблицы `бронирования`
--
ALTER TABLE `бронирования`
  ADD PRIMARY KEY (`Код_бронирования`),
  ADD KEY `fk_filial_reservation` (`Код_филиала`),
  ADD KEY `fk_guest_reservation` (`Код_гостя`);

--
-- Индексы таблицы `гости`
--
ALTER TABLE `гости`
  ADD PRIMARY KEY (`Код_гостя`);

--
-- Индексы таблицы `заказы`
--
ALTER TABLE `заказы`
  ADD PRIMARY KEY (`Код_блюда`,`Код_бронирования`),
  ADD KEY `Код_бронирования` (`Код_бронирования`);

--
-- Индексы таблицы `филиалы`
--
ALTER TABLE `филиалы`
  ADD PRIMARY KEY (`Код_филиала`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `News_Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `меню`
--
ALTER TABLE `меню`
  MODIFY `Код_блюда` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `капчи`
--
ALTER TABLE `капчи`
  MODIFY `IDcap` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `поставщики`
--
ALTER TABLE `поставщики`
  MODIFY `Код_поставщика` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `администраторы`
--
ALTER TABLE `администраторы`
  MODIFY `Код_администратора` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `бронирования`
--
ALTER TABLE `бронирования`
  MODIFY `Код_бронирования` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT для таблицы `гости`
--
ALTER TABLE `гости`
  MODIFY `Код_гостя` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `филиалы`
--
ALTER TABLE `филиалы`
  MODIFY `Код_филиала` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `меню`
--
ALTER TABLE `меню`
  ADD CONSTRAINT `fk_filial_menu` FOREIGN KEY (`Код_филиала`) REFERENCES `филиалы` (`Код_филиала`);

--
-- Ограничения внешнего ключа таблицы `поставщики`
--
ALTER TABLE `поставщики`
  ADD CONSTRAINT `fk_filial_supplier` FOREIGN KEY (`Код_филиала`) REFERENCES `филиалы` (`Код_филиала`);

--
-- Ограничения внешнего ключа таблицы `администраторы`
--
ALTER TABLE `администраторы`
  ADD CONSTRAINT `fk_filial_admin` FOREIGN KEY (`Код_филиала`) REFERENCES `филиалы` (`Код_филиала`);

--
-- Ограничения внешнего ключа таблицы `бронирования`
--
ALTER TABLE `бронирования`
  ADD CONSTRAINT `fk_filial_reservation` FOREIGN KEY (`Код_филиала`) REFERENCES `филиалы` (`Код_филиала`),
  ADD CONSTRAINT `fk_guest_reservation` FOREIGN KEY (`Код_гостя`) REFERENCES `гости` (`Код_гостя`);

--
-- Ограничения внешнего ключа таблицы `заказы`
--
ALTER TABLE `заказы`
  ADD CONSTRAINT `заказы_ibfk_1` FOREIGN KEY (`Код_блюда`) REFERENCES `меню` (`Код_блюда`),
  ADD CONSTRAINT `заказы_ibfk_2` FOREIGN KEY (`Код_бронирования`) REFERENCES `бронирования` (`Код_бронирования`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
