-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 05 mei 2019 om 19:37
-- Serverversie: 10.1.26-MariaDB
-- PHP-versie: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kachungli_bioscoop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoop`
--

CREATE TABLE `bioscoop` (
  `id` int(11) NOT NULL,
  `bioscoopLocatie` int(11) NOT NULL,
  `bioscoopZaal` int(11) NOT NULL,
  `bioscoopAantalPersoneel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoop`
--

INSERT INTO `bioscoop` (`id`, `bioscoopLocatie`, `bioscoopZaal`, `bioscoopAantalPersoneel`) VALUES
(1, 5, 2, 2),
(3, 4, 1, 5),
(4, 4, 1, 7);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopapikey`
--

CREATE TABLE `bioscoopapikey` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `api_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopapikey`
--

INSERT INTO `bioscoopapikey` (`id`, `api_key`, `api_level`) VALUES
(4, '8be57fac93ab53fe21f3c87b83816144', 4),
(5, 'fc9a305cf370b10e802985607d623445', 4),
(6, 'f4e4fa0fb1f071d84338bd92cfe83d38', 2),
(7, 'adb95e64df508fb78602d779f5bcf47c', 2),
(8, '35bcf4e870e59cec4d991f8017c077bc', 2),
(9, '11f3bfbb38d832dcfadbeb1268447e5e', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopdomeinen`
--

CREATE TABLE `bioscoopdomeinen` (
  `id` int(11) NOT NULL,
  `domeinNaam` varchar(150) NOT NULL,
  `api_key` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopdomeinen`
--

INSERT INTO `bioscoopdomeinen` (`id`, `domeinNaam`, `api_key`) VALUES
(1, 'http://example.org', 4),
(2, 'http://test.com', 8);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopfilm`
--

CREATE TABLE `bioscoopfilm` (
  `id` int(10) NOT NULL,
  `filmNaam` varchar(20) NOT NULL,
  `filmTijd` varchar(20) NOT NULL,
  `filmPrijs` decimal(10,2) NOT NULL,
  `filmType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopfilm`
--

INSERT INTO `bioscoopfilm` (`id`, `filmNaam`, `filmTijd`, `filmPrijs`, `filmType`) VALUES
(4, 'Marvel Avengers', '1:30:00', '19.95', 'Actie, Superkrachten'),
(5, 'Your name', '1:30:00', '32.95', 'Romantiek, Bovennatu');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscooplocatie`
--

CREATE TABLE `bioscooplocatie` (
  `id` int(10) NOT NULL,
  `locatieNaam` varchar(100) NOT NULL,
  `locatieStraat` varchar(100) NOT NULL,
  `locatiePostcode` varchar(10) NOT NULL,
  `locatieProvincie` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscooplocatie`
--

INSERT INTO `bioscooplocatie` (`id`, `locatieNaam`, `locatieStraat`, `locatiePostcode`, `locatieProvincie`) VALUES
(4, 'Arnhem', 'Arnhemsestraat 19', '5913OF', 'Gelderland'),
(5, 'Amsterdam', 'Amsterdamsestraat 26', '4351GF', 'Noord-Holland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopticket`
--

CREATE TABLE `bioscoopticket` (
  `id` int(10) NOT NULL,
  `ticketNummer` int(10) NOT NULL,
  `ticketKlant` varchar(20) NOT NULL,
  `ticketDatum` varchar(20) NOT NULL,
  `ticketTijd` varchar(20) NOT NULL,
  `ticketZaal` varchar(20) NOT NULL,
  `ticketPrijs` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopticket`
--

INSERT INTO `bioscoopticket` (`id`, `ticketNummer`, `ticketKlant`, `ticketDatum`, `ticketTijd`, `ticketZaal`, `ticketPrijs`) VALUES
(4, 1, 'Piet Jansen', '14-04-2019', '15:00', '1', '30.00'),
(12, 1, 'Piet Jansen', '14-04-2019', '15:00', '1', '30.00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopvoorstelling`
--

CREATE TABLE `bioscoopvoorstelling` (
  `id` int(10) NOT NULL,
  `voorstellingNummer` int(20) NOT NULL,
  `voorstellingTicket` int(10) NOT NULL,
  `voorstellingZaal` int(10) NOT NULL,
  `voorstellingFilm` int(10) NOT NULL,
  `voorstellingDuur` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopvoorstelling`
--

INSERT INTO `bioscoopvoorstelling` (`id`, `voorstellingNummer`, `voorstellingTicket`, `voorstellingZaal`, `voorstellingFilm`, `voorstellingDuur`) VALUES
(3, 1, 4, 1, 5, 1),
(8, 1, 12, 1, 5, 1),
(12, 53, 12, 1, 4, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bioscoopzalen`
--

CREATE TABLE `bioscoopzalen` (
  `id` int(10) NOT NULL,
  `zaalNummer` int(10) NOT NULL,
  `zaalAantalStoelen` int(10) NOT NULL,
  `zaalAantalRijen` int(10) NOT NULL,
  `zaalBeeld` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bioscoopzalen`
--

INSERT INTO `bioscoopzalen` (`id`, `zaalNummer`, `zaalAantalStoelen`, `zaalAantalRijen`, `zaalBeeld`) VALUES
(1, 1, 50, 10, 'IMAX'),
(2, 2, 30, 6, '3D');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bioscoop`
--
ALTER TABLE `bioscoop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bioscoopLocatie` (`bioscoopLocatie`),
  ADD KEY `bioscoopZaal` (`bioscoopZaal`);

--
-- Indexen voor tabel `bioscoopapikey`
--
ALTER TABLE `bioscoopapikey`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bioscoopdomeinen`
--
ALTER TABLE `bioscoopdomeinen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_key` (`api_key`);

--
-- Indexen voor tabel `bioscoopfilm`
--
ALTER TABLE `bioscoopfilm`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bioscooplocatie`
--
ALTER TABLE `bioscooplocatie`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bioscoopticket`
--
ALTER TABLE `bioscoopticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bioscoopvoorstelling`
--
ALTER TABLE `bioscoopvoorstelling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voorstellingTicket` (`voorstellingTicket`),
  ADD KEY `voorstellingZaal` (`voorstellingZaal`),
  ADD KEY `voorstellingFilm` (`voorstellingFilm`);

--
-- Indexen voor tabel `bioscoopzalen`
--
ALTER TABLE `bioscoopzalen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bioscoop`
--
ALTER TABLE `bioscoop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT voor een tabel `bioscoopapikey`
--
ALTER TABLE `bioscoopapikey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT voor een tabel `bioscoopdomeinen`
--
ALTER TABLE `bioscoopdomeinen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `bioscoopfilm`
--
ALTER TABLE `bioscoopfilm`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `bioscooplocatie`
--
ALTER TABLE `bioscooplocatie`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `bioscoopticket`
--
ALTER TABLE `bioscoopticket`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT voor een tabel `bioscoopvoorstelling`
--
ALTER TABLE `bioscoopvoorstelling`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT voor een tabel `bioscoopzalen`
--
ALTER TABLE `bioscoopzalen`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `bioscoop`
--
ALTER TABLE `bioscoop`
  ADD CONSTRAINT `bioscoop_ibfk_1` FOREIGN KEY (`bioscoopLocatie`) REFERENCES `bioscooplocatie` (`id`),
  ADD CONSTRAINT `bioscoop_ibfk_2` FOREIGN KEY (`bioscoopZaal`) REFERENCES `bioscoopzalen` (`id`);

--
-- Beperkingen voor tabel `bioscoopdomeinen`
--
ALTER TABLE `bioscoopdomeinen`
  ADD CONSTRAINT `bioscoopdomeinen_ibfk_1` FOREIGN KEY (`api_key`) REFERENCES `bioscoopapikey` (`id`);

--
-- Beperkingen voor tabel `bioscoopvoorstelling`
--
ALTER TABLE `bioscoopvoorstelling`
  ADD CONSTRAINT `bioscoopvoorstelling_ibfk_1` FOREIGN KEY (`voorstellingTicket`) REFERENCES `bioscoopticket` (`id`),
  ADD CONSTRAINT `bioscoopvoorstelling_ibfk_2` FOREIGN KEY (`voorstellingZaal`) REFERENCES `bioscoopzalen` (`id`),
  ADD CONSTRAINT `bioscoopvoorstelling_ibfk_3` FOREIGN KEY (`voorstellingFilm`) REFERENCES `bioscoopfilm` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
