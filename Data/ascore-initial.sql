-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-03-2013 a las 14:22:08
-- Versión del servidor: 5.5.28
-- Versión de PHP: 5.4.4-13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ascore`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_bilo_object`
--

CREATE TABLE IF NOT EXISTS `coreg2_bilo_object` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` int(11) DEFAULT NULL,
  `entrada` int(11) DEFAULT NULL,
  `salida` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coreg2_bilo_object`
--

INSERT INTO `coreg2_bilo_object` (`ID`, `fecha`, `entrada`, `salida`, `usuario`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_bookmark`
--

CREATE TABLE IF NOT EXISTS `coreg2_bookmark` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coreg2_bookmark`
--

INSERT INTO `coreg2_bookmark` (`ID`, `user_id`, `nombre`, `url`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, NULL, '', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_category`
--

CREATE TABLE IF NOT EXISTS `coreg2_category` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(64) NOT NULL,
  `active` enum('Si','No') NOT NULL DEFAULT 'Si',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `coreg2_category`
--

INSERT INTO `coreg2_category` (`ID`, `label`, `active`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', 'Si', NULL, NULL, NULL, NULL),
(2, 'Category #1', 'Si', 2, 0, 1363267179, -2147483648);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_data_object`
--

CREATE TABLE IF NOT EXISTS `coreg2_data_object` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('archive','folder') NOT NULL DEFAULT 'archive',
  `fileh` int(11) DEFAULT NULL,
  `uid` varchar(30) NOT NULL DEFAULT '',
  `gid` varchar(30) NOT NULL DEFAULT '',
  `p_owner` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL DEFAULT '...',
  `p_group` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL DEFAULT '...',
  `p_other` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL DEFAULT '...',
  `inode` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL DEFAULT '',
  `mime` varchar(50) NOT NULL DEFAULT '',
  `fecha` int(11) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `coreg2_data_object`
--

INSERT INTO `coreg2_data_object` (`ID`, `type`, `fileh`, `uid`, `gid`, `p_owner`, `p_group`, `p_other`, `inode`, `nombre`, `mime`, `fecha`, `descripcion`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, 'archive', NULL, '', '', '...', '...', '...', NULL, '', '', NULL, '', NULL, NULL, NULL, NULL),
(2, 'folder', 0, '2', '2', 'rwx', 'r.x', '...', 0, 'Documentacion', '', 1159290518, 'Documentacion', 2, 0, 1159290518, 0),
(4, 'folder', 0, '2', '2', 'rwx', 'rwx', '...', 2, 'Informes', '', 1165509889, '', 2, 2, 1161950730, 1165509889),
(13, 'folder', 0, '2', '2', 'rwx', 'rwx', '...', 2, 'Documentos', '', 1161956397, '', 2, 0, 1161956397, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_fileh`
--

CREATE TABLE IF NOT EXISTS `coreg2_fileh` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(40) NOT NULL DEFAULT '',
  `ext` varchar(5) NOT NULL DEFAULT '',
  `mime` varchar(25) NOT NULL DEFAULT '',
  `len` int(11) DEFAULT NULL,
  `fecha` int(11) DEFAULT NULL,
  `stats` int(11) DEFAULT NULL,
  `desc` varchar(150) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `familia_id` int(11) DEFAULT NULL,
  `capturefile` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coreg2_fileh`
--

INSERT INTO `coreg2_fileh` (`ID`, `md5`, `ext`, `mime`, `len`, `fecha`, `stats`, `desc`, `nombre`, `familia_id`, `capturefile`, `owner_id`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', '', '', NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_group`
--

CREATE TABLE IF NOT EXISTS `coreg2_group` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(50) NOT NULL,
  `code` int(11) DEFAULT NULL,
  `active` enum('Si','No') NOT NULL DEFAULT 'No',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `coreg2_group`
--

INSERT INTO `coreg2_group` (`ID`, `groupname`, `code`, `active`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', NULL, 'No', NULL, NULL, NULL, NULL),
(2, 'Administradores', 2, 'Si', 0, 2, 0, 1161949224),
(3, 'Operadores', 4, 'Si', 0, 2, 0, 1161949224),
(4, '', 8, 'No', 2, 2, 1140165675, 1161949224),
(5, '', 16, 'No', 2, 2, 1140165675, 1161949224),
(6, '', 32, 'No', 2, 2, 1140165675, 1161949224),
(7, '', 64, 'No', 2, 2, 1140165675, 1161949224),
(8, '', 128, 'No', 2, 2, 1140165675, 1161949224),
(9, '', 256, 'No', 2, 2, 1140165675, 1161949224),
(10, '', 512, 'No', 2, 2, 1140165675, 1161949224),
(11, '', 1024, 'No', 2, 2, 1140165675, 1161949224),
(12, '', 2048, 'No', 2, 2, 1140165675, 1161949224),
(13, '', 4096, 'No', 2, 2, 1140165675, 1161949224),
(14, '', 8192, 'No', 2, 2, 1140165675, 1161949224),
(15, '', 16384, 'No', 2, 2, 1140165675, 1161949224),
(16, '', 32768, 'No', 2, 2, 1140165675, 1161949224),
(17, '', 65536, 'No', 2, 2, 1140165675, 1161949224),
(18, '', 131072, 'No', 2, 2, 1140165675, 1161949224),
(19, '', 262144, 'No', 2, 2, 1140165675, 1161949224),
(20, '', 524288, 'No', 2, 2, 1140165675, 1161949224),
(21, '', 1048576, 'No', 2, 2, 1140165675, 1161949224),
(22, '', 2097152, 'No', 2, 2, 1140165675, 1161949224),
(23, '', 4194304, 'No', 2, 2, 1140165675, 1161949224),
(24, '', 8388608, 'No', 2, 2, 1140165675, 1161949224),
(25, '', 16777216, 'No', 2, 2, 1140165675, 1161949224),
(26, '', 33554432, 'No', 2, 2, 1140165675, 1161949224),
(27, '', 67108864, 'No', 2, 2, 1140165675, 1161949224),
(28, '', 134217728, 'No', 2, 2, 1140165675, 1161949224),
(29, '', 268435456, 'No', 2, 2, 1140165675, 1161949224),
(30, '', 536870912, 'No', 2, 2, 1140165675, 1161949224),
(31, 'Anyone', 1073741824, 'No', 2, 2, 1140165675, 1161949224);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_item`
--

CREATE TABLE IF NOT EXISTS `coreg2_item` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `extension` varchar(64) NOT NULL,
  `defaultuser` varchar(128) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `active` enum('Si','No') NOT NULL DEFAULT 'Si',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `coreg2_item`
--

INSERT INTO `coreg2_item` (`ID`, `extension`, `defaultuser`, `category_id`, `people_id`, `active`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', '', NULL, NULL, 'Si', NULL, NULL, NULL, NULL),
(2, 'Item #1', '', 2, 33, 'Si', 2, 0, 1363267196, -2147483648);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_people`
--

CREATE TABLE IF NOT EXISTS `coreg2_people` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `active` enum('Si','No') NOT NULL DEFAULT 'Si',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Volcado de datos para la tabla `coreg2_people`
--

INSERT INTO `coreg2_people` (`ID`, `name`, `surname`, `active`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', '', 'Si', NULL, NULL, NULL, NULL),
(2, 'María Carmen', 'Giral', 'Si', NULL, NULL, NULL, NULL),
(3, 'Raquel', 'Fernández', 'Si', NULL, NULL, NULL, NULL),
(4, 'Pablo', 'Bernal', 'Si', NULL, NULL, NULL, NULL),
(5, 'Marc', 'Sánchez', 'Si', NULL, NULL, NULL, NULL),
(6, 'Óscar', 'Díaz', 'Si', NULL, NULL, NULL, NULL),
(7, 'José', 'Gómez', 'Si', NULL, NULL, NULL, NULL),
(8, 'Ana', 'Jiménez', 'Si', NULL, NULL, NULL, NULL),
(9, 'Antonia', 'Márquez', 'Si', NULL, NULL, NULL, NULL),
(10, 'Antonia', 'Rodríguez', 'Si', NULL, NULL, NULL, NULL),
(11, 'Rosario', 'Laporta', 'Si', NULL, NULL, NULL, NULL),
(12, 'Manuel', 'Blázquez', 'Si', NULL, NULL, NULL, NULL),
(13, 'Elena', 'Martínez', 'Si', NULL, NULL, NULL, NULL),
(14, 'José', 'Abellán', 'Si', NULL, NULL, NULL, NULL),
(15, 'Mercedes', 'López', 'Si', NULL, NULL, NULL, NULL),
(16, 'Fernando', 'González', 'Si', NULL, NULL, NULL, NULL),
(17, 'Miguel', 'Esteban', 'Si', NULL, NULL, NULL, NULL),
(18, 'José Luis', 'Martínez', 'Si', NULL, NULL, NULL, NULL),
(19, 'José', 'Parra', 'Si', NULL, NULL, NULL, NULL),
(20, 'Carmen', 'Rolo', 'Si', NULL, NULL, NULL, NULL),
(21, 'Andrés', 'Olmo', 'Si', NULL, NULL, NULL, NULL),
(22, 'María Carmen', 'Martínez', 'Si', NULL, NULL, NULL, NULL),
(23, 'Elena', 'Amorós', 'Si', NULL, NULL, NULL, NULL),
(24, 'María Dolores', 'González', 'Si', NULL, NULL, NULL, NULL),
(25, 'Isabel', 'Prats', 'Si', NULL, NULL, NULL, NULL),
(26, 'Amparo', 'González', 'Si', NULL, NULL, NULL, NULL),
(27, 'Andrés', 'Gascón', 'Si', NULL, NULL, NULL, NULL),
(28, 'Juan María', 'Zarza', 'Si', NULL, NULL, NULL, NULL),
(29, 'Ángela', 'Lorenzo', 'Si', NULL, NULL, NULL, NULL),
(30, 'María Pilar', 'Alemany', 'Si', NULL, NULL, NULL, NULL),
(31, 'Ángela', 'Román', 'Si', NULL, NULL, NULL, NULL),
(32, 'Miguel', 'González', 'Si', NULL, NULL, NULL, NULL),
(33, 'Alex', 'Ballesta', 'Si', NULL, NULL, NULL, NULL),
(34, 'José María', 'López', 'Si', NULL, NULL, NULL, NULL),
(35, 'María Dolores', 'Cruz', 'Si', NULL, NULL, NULL, NULL),
(36, 'Salvador', 'Stern', 'Si', NULL, NULL, NULL, NULL),
(37, 'Jorge', 'González', 'Si', NULL, NULL, NULL, NULL),
(38, 'Francisca', 'Martínez', 'Si', NULL, NULL, NULL, NULL),
(39, 'Daniel', 'Balteanu', 'Si', NULL, NULL, NULL, NULL),
(40, 'Fernando', 'Benamar', 'Si', NULL, NULL, NULL, NULL),
(41, 'Mónica', 'Padilla', 'Si', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_queryb`
--

CREATE TABLE IF NOT EXISTS `coreg2_queryb` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `queryb` blob NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `coreg2_queryb`
--

INSERT INTO `coreg2_queryb` (`ID`, `queryb`, `nombre`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', '', NULL, NULL, NULL, NULL),
(3, 0x53454c45435420636f726567325f757365722e757365726e616d6520415320275573756172696f7c737472696e673a3530272c636f726567325f757365722e6e6f6d62726520415320274e6f6d6272657c737472696e673a3530272c636f726567325f757365722e6170656c6c69646f7320415320274170656c6c69646f737c737472696e673a3530272046524f4d20636f726567325f757365722057484552452049443e31, 'Usuarios', 2, 2, 1171132544, 1182781404);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_registro`
--

CREATE TABLE IF NOT EXISTS `coreg2_registro` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `entrada_m` int(11) DEFAULT NULL,
  `salida_m` int(11) DEFAULT NULL,
  `entrada_t` int(11) DEFAULT NULL,
  `salida_t` int(11) DEFAULT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `coreg2_registro`
--

INSERT INTO `coreg2_registro` (`ID`, `user_id`, `dia`, `entrada_m`, `salida_m`, `entrada_t`, `salida_t`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`, `ip`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(2, 2, 1363215601, 1363267040, 0, 0, 0, 0, 0, 1363267040, -2147483648, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_report`
--

CREATE TABLE IF NOT EXISTS `coreg2_report` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(50) NOT NULL DEFAULT '',
  `query_id` int(11) DEFAULT NULL,
  `tipo` enum('HardCoded','SoftCoded') NOT NULL DEFAULT 'HardCoded',
  `activo` enum('Si','No') NOT NULL DEFAULT 'No',
  `printable` enum('Si','No') NOT NULL DEFAULT 'No',
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `coreg2_report`
--

INSERT INTO `coreg2_report` (`ID`, `reportname`, `url`, `query_id`, `tipo`, `activo`, `printable`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, '', '', NULL, 'HardCoded', 'No', 'No', NULL, NULL, NULL, NULL),
(3, 'Listado de Usuarios', '', 3, 'SoftCoded', 'Si', 'Si', 2, 0, 1171132562, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_soft`
--

CREATE TABLE IF NOT EXISTS `coreg2_soft` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `desc` varchar(150) NOT NULL DEFAULT '',
  `version` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `adjunto` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  `paquete` varchar(100) NOT NULL DEFAULT '',
  `fecha_pub` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coreg2_soft`
--

INSERT INTO `coreg2_soft` (`ID`, `nombre`, `desc`, `version`, `url`, `adjunto`, `cat_id`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`, `paquete`, `fecha_pub`) VALUES
(1, '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_user`
--

CREATE TABLE IF NOT EXISTS `coreg2_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `grupos` int(11) DEFAULT NULL,
  `activo` enum('Si','No') NOT NULL DEFAULT 'No',
  `email` varchar(75) NOT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

--
-- Volcado de datos para la tabla `coreg2_user`
--

INSERT INTO `coreg2_user` (`ID`, `username`, `password`, `nombre`, `apellidos`, `grupos`, `activo`, `email`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`) VALUES
(1, 'user', '', '', '', 65536, 'No', '', NULL, NULL, NULL, NULL),
(2, 'admin', '098f6bcd4621d373cade4e832627b4f6', 'Administrador', 'Sistema', 6, 'Si', 'info@activasistemas.com', 0, 2, 0, 1182781222),
(74, 'operador', '098f6bcd4621d373cade4e832627b4f6', 'Operador', 'Sistema', 4, 'Si', '', 2, 0, 1162224261, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_user_pref`
--

CREATE TABLE IF NOT EXISTS `coreg2_user_pref` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sys_app_mode` enum('Si','No') NOT NULL,
  `zoom` enum('popup','iframe') NOT NULL,
  `rlimit` int(11) DEFAULT NULL,
  `spooler` enum('Interno','ASPooler') NOT NULL,
  `ip_spooler` varchar(15) NOT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  `curtain_effect` enum('Si','No') NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coreg2_user_pref`
--

INSERT INTO `coreg2_user_pref` (`ID`, `user_id`, `sys_app_mode`, `zoom`, `rlimit`, `spooler`, `ip_spooler`, `S_UserID_CB`, `S_UserID_MB`, `S_Date_C`, `S_Date_M`, `curtain_effect`) VALUES
(1, NULL, 'Si', 'popup', NULL, 'Interno', '', NULL, NULL, NULL, NULL, 'Si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coreg2_void`
--

CREATE TABLE IF NOT EXISTS `coreg2_void` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `none` int(11) DEFAULT NULL,
  `S_UserID_CB` int(11) DEFAULT NULL,
  `S_UserID_MB` int(11) DEFAULT NULL,
  `S_Date_C` int(11) DEFAULT NULL,
  `S_Date_M` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
