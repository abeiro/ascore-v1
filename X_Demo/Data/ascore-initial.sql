# MySQL dump by phpMyDump
# Host: localhost Database: worldspace
# ----------------------------
# Server version: 5.0.32-Debian_7etch3



DROP TABLE IF EXISTS `coreg2_bilo_object`;
# ----------------------------------------
# table structure for table 'coreg2_bilo_object' 
CREATE TABLE coreg2_bilo_object (
  `ID` int(11) NOT NULL auto_increment,
  `fecha` int(11) default NULL,
  `entrada` int(11) default NULL,
  `salida` int(11) default NULL,
  `usuario` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_bilo_object VALUES (1,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL);
DROP TABLE IF EXISTS `coreg2_bookmark`;
# ----------------------------------------
# table structure for table 'coreg2_bookmark' 
CREATE TABLE coreg2_bookmark (
  `ID` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `nombre` varchar(50) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_bookmark VALUES (1,  NULL, '', '',  NULL,  NULL,  NULL,  NULL);
DROP TABLE IF EXISTS `coreg2_cat_act`;
# ----------------------------------------
# table structure for table 'coreg2_cat_act' 
CREATE TABLE coreg2_cat_act (
  `ID` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `descripcion` varchar(150) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_cat_act VALUES (1, '', '',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_cat_act VALUES (2, 'Juegos', 'Actualizaciones de juegos que hay disponibles',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_cat_act VALUES (3, 'Deportes', 'Actualizaciones de los deportes',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_cat_act VALUES (4, 'ASLinux', 'Actualizaciones de ASLinux', 0, 0, 0, 1159290338);
INSERT INTO coreg2_cat_act VALUES (9, 'Ciencia', 'Pos eso, que se habla de ciencias', 2, 0, 1146825287, 0);
DROP TABLE IF EXISTS `coreg2_cat_not`;
# ----------------------------------------
# table structure for table 'coreg2_cat_not' 
CREATE TABLE coreg2_cat_not (
  `ID` int(11) NOT NULL auto_increment,
  `nombre_cat` varchar(50) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_cat_not VALUES (1, '',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_cat_not VALUES (2, 'Empresa', 0, 2, 0, 1145526861);
INSERT INTO coreg2_cat_not VALUES (3, 'Distribuciones', 2, 0, 1140166657, 0);
INSERT INTO coreg2_cat_not VALUES (4, 'Ocio', 2, 0, 1140166668, 0);
INSERT INTO coreg2_cat_not VALUES (5, 'N?cleo', 2, 0, 1140166678, 0);
INSERT INTO coreg2_cat_not VALUES (8, 'Software', 0, 2, 0, 1140176058);
INSERT INTO coreg2_cat_not VALUES (7, 'Seguridad', 0, 2, 0, 1140169135);
DROP TABLE IF EXISTS `coreg2_cat_soft`;
# ----------------------------------------
# table structure for table 'coreg2_cat_soft' 
CREATE TABLE coreg2_cat_soft (
  `ID` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `descripcion` varchar(150) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_cat_soft VALUES (1, '', '',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_cat_soft VALUES (3, 'Drivers y controladores', 'Drivers y controladores', 0, 58, 0, 1151427522);
DROP TABLE IF EXISTS `coreg2_categoria`;
# ----------------------------------------
# table structure for table 'coreg2_categoria' 
CREATE TABLE coreg2_categoria (
  `ID` int(11) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL default '',
  `cat_id` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `cat_pr` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_categoria VALUES (1, '',  NULL,  NULL,  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_categoria VALUES (26, 'Example', 0, 2, 2, 1161945559, 1161945559, 26);
INSERT INTO coreg2_categoria VALUES (27, 'SubExample', 26, 2, 0, 1161945592, 0, 26);
INSERT INTO coreg2_categoria VALUES (28, 'SubSubExample', 27, 2, 0, 1161962890, 0, 26);
INSERT INTO coreg2_categoria VALUES (25, 'prueba3', 24, 8, 0, 1149678822, 0, 19);
DROP TABLE IF EXISTS `coreg2_data_object`;
# ----------------------------------------
# table structure for table 'coreg2_data_object' 
CREATE TABLE coreg2_data_object (
  `ID` int(11) NOT NULL auto_increment,
  `type` enum('archive','folder') NOT NULL default 'archive',
  `fileh` int(11) default NULL,
  `uid` varchar(30) NOT NULL default '',
  `gid` varchar(30) NOT NULL default '',
  `p_owner` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL default '...',
  `p_group` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL default '...',
  `p_other` enum('...','r..','.w.','..x','rw.','r.x','.w.','.wx','..x','rwx') NOT NULL default '...',
  `inode` int(11) default NULL,
  `nombre` varchar(255) NOT NULL default '',
  `mime` varchar(50) NOT NULL default '',
  `fecha` int(11) default NULL,
  `descripcion` varchar(255) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_data_object VALUES (1, 'archive',  NULL, '', '', '...', '...', '...',  NULL, '', '',  NULL, '',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_data_object VALUES (2, 'folder', 0, '2', '2', 'rwx', 'r.x', '...', 0, 'Documentacion', '', 1159290518, 'Documentacion', 2, 0, 1159290518, 0);
INSERT INTO coreg2_data_object VALUES (4, 'folder', 0, '2', '2', 'rwx', 'rwx', '...', 2, 'Informes', '', 1165509889, '', 2, 2, 1161950730, 1165509889);
INSERT INTO coreg2_data_object VALUES (13, 'folder', 0, '2', '2', 'rwx', 'rwx', '...', 2, 'Documentos', '', 1161956397, '', 2, 0, 1161956397, 0);
DROP TABLE IF EXISTS `coreg2_documento`;
# ----------------------------------------
# table structure for table 'coreg2_documento' 
CREATE TABLE coreg2_documento (
  `ID` int(11) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `cuerpo` blob NOT NULL,
  `autor` varchar(50) NOT NULL default '',
  `fecha_cre` int(11) default NULL,
  `fecha_mod` int(11) default NULL,
  `cat_id` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `cat_pr` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_documento VALUES (1, '', '', '',  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_documento VALUES (21, 'Example', '<p><strong>This is a test example</strong></p><p>&nbsp;</p>', 'admin', 1161967943, 1182780950, 26, 2, 2, 1161967943, 1182780950, 26);
DROP TABLE IF EXISTS `coreg2_fileh`;
# ----------------------------------------
# table structure for table 'coreg2_fileh' 
CREATE TABLE coreg2_fileh (
  `ID` int(11) NOT NULL auto_increment,
  `md5` varchar(40) NOT NULL default '',
  `ext` varchar(5) NOT NULL default '',
  `mime` varchar(25) NOT NULL default '',
  `len` int(11) default NULL,
  `fecha` int(11) default NULL,
  `stats` int(11) default NULL,
  `desc` varchar(150) NOT NULL default '',
  `nombre` varchar(50) NOT NULL default '',
  `familia_id` int(11) default NULL,
  `capturefile` int(11) default NULL,
  `owner_id` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_fileh VALUES (1, '', '', '',  NULL,  NULL,  NULL, '', '',  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL);
DROP TABLE IF EXISTS `coreg2_foro`;
# ----------------------------------------
# table structure for table 'coreg2_foro' 
CREATE TABLE coreg2_foro (
  `ID` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `descripcion` varchar(150) NOT NULL default '',
  `msg` int(11) default NULL,
  `fecha` int(11) default NULL,
  `autormsg` varchar(50) NOT NULL default '',
  `visitas` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_foro VALUES (1, 'Foro asCore', 'Cuestiones del desarrollo de asCore', 0, 1159290258, '', 0, 0, 0, 1159290258, 0);
INSERT INTO coreg2_foro VALUES (2, 'Foro asCore', 'Developer Issues', 0, 1182780968, '', 0, 0, 2, 1159290265, 1182780968);
DROP TABLE IF EXISTS `coreg2_foto`;
# ----------------------------------------
# table structure for table 'coreg2_foto' 
CREATE TABLE coreg2_foto (
  `ID` int(11) NOT NULL auto_increment,
  `id_foto` int(11) default NULL,
  `id_thumb` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `desc` varchar(100) NOT NULL default '',
  `id_old` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_foto VALUES (1,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL, '',  NULL);
INSERT INTO coreg2_foto VALUES (18, 4, 5, 2, 0, 1148310985, 0, '', 0);
INSERT INTO coreg2_foto VALUES (19, 6, 7, 2, 0, 1148310985, 0, '', 0);
INSERT INTO coreg2_foto VALUES (20, 8, 9, 2, 0, 1148311089, 0, '', 0);
INSERT INTO coreg2_foto VALUES (21, 10, 11, 2, 0, 1148311089, 0, '', 0);
INSERT INTO coreg2_foto VALUES (22, 12, 13, 2, 0, 1148311182, 0, '', 0);
INSERT INTO coreg2_foto VALUES (23, 14, 15, 2, 0, 1148311182, 0, '', 0);
INSERT INTO coreg2_foto VALUES (24, 16, 17, 2, 0, 1148311327, 0, '', 0);
INSERT INTO coreg2_foto VALUES (25, 18, 19, 2, 0, 1148311327, 0, '', 0);
INSERT INTO coreg2_foto VALUES (26, 20, 21, 2, 0, 1148311833, 0, '', 0);
INSERT INTO coreg2_foto VALUES (27, 22, 23, 2, 0, 1148312369, 0, '', 0);
INSERT INTO coreg2_foto VALUES (28, 24, 25, 2, 0, 1148312369, 0, '', 0);
INSERT INTO coreg2_foto VALUES (29, 26, 27, 2, 0, 1148312388, 0, '', 0);
INSERT INTO coreg2_foto VALUES (30, 28, 29, 2, 0, 1148312388, 0, '', 0);
INSERT INTO coreg2_foto VALUES (31, 30, 31, 6, 0, 1148313144, 0, '', 0);
INSERT INTO coreg2_foto VALUES (32, 32, 33, 6, 0, 1148313144, 0, '', 0);
INSERT INTO coreg2_foto VALUES (33, 34, 35, 2, 0, 1148551557, 0, '', 0);
INSERT INTO coreg2_foto VALUES (34, 36, 37, 2, 0, 1148551557, 0, '', 0);
INSERT INTO coreg2_foto VALUES (35, 38, 39, 2, 0, 1148552569, 0, '', 0);
INSERT INTO coreg2_foto VALUES (88, 144, 145, 58, 0, 1151484699, 0, '', 0);
INSERT INTO coreg2_foto VALUES (37, 42, 43, 2, 0, 1148552580, 0, '', 0);
INSERT INTO coreg2_foto VALUES (62, 92, 93, 8, 0, 1149847876, 0, '', 0);
INSERT INTO coreg2_foto VALUES (64, 96, 97, 8, 0, 1149847919, 0, '', 0);
INSERT INTO coreg2_foto VALUES (81, 130, 131, 58, 0, 1151312398, 0, '', 0);
INSERT INTO coreg2_foto VALUES (41, 50, 51, 2, 0, 1149495533, 0, '', 0);
INSERT INTO coreg2_foto VALUES (42, 52, 53, 2, 0, 1149495677, 0, '', 0);
INSERT INTO coreg2_foto VALUES (43, 54, 55, 2, 0, 1149495768, 0, '', 0);
INSERT INTO coreg2_foto VALUES (44, 56, 57, 2, 0, 1149495801, 0, '', 0);
INSERT INTO coreg2_foto VALUES (45, 58, 59, 2, 0, 1149495856, 0, '', 0);
INSERT INTO coreg2_foto VALUES (46, 60, 61, 2, 0, 1149496007, 0, '', 0);
INSERT INTO coreg2_foto VALUES (47, 62, 63, 2, 0, 1149496009, 0, '', 0);
INSERT INTO coreg2_foto VALUES (48, 64, 65, 2, 0, 1149496069, 0, '', 0);
INSERT INTO coreg2_foto VALUES (49, 66, 67, 2, 0, 1149496126, 0, '', 0);
INSERT INTO coreg2_foto VALUES (50, 68, 69, 2, 0, 1149496129, 0, '', 0);
INSERT INTO coreg2_foto VALUES (51, 70, 71, 8, 0, 1149842804, 0, '', 0);
INSERT INTO coreg2_foto VALUES (83, 134, 135, 58, 0, 1151426704, 0, '', 0);
INSERT INTO coreg2_foto VALUES (80, 128, 129, 58, 0, 1150974244, 0, '', 0);
INSERT INTO coreg2_foto VALUES (54, 76, 77, 8, 0, 1149842858, 0, '', 0);
INSERT INTO coreg2_foto VALUES (55, 78, 79, 8, 0, 1149845813, 0, '', 0);
INSERT INTO coreg2_foto VALUES (56, 80, 81, 8, 0, 1149845859, 0, '', 0);
INSERT INTO coreg2_foto VALUES (57, 82, 83, 8, 0, 1149845877, 0, '', 0);
INSERT INTO coreg2_foto VALUES (58, 84, 85, 8, 0, 1149845887, 0, '', 0);
INSERT INTO coreg2_foto VALUES (59, 86, 87, 8, 0, 1149845896, 0, '', 0);
INSERT INTO coreg2_foto VALUES (60, 88, 89, 8, 0, 1149846075, 0, '', 0);
INSERT INTO coreg2_foto VALUES (61, 90, 91, 8, 0, 1149846626, 0, '', 0);
INSERT INTO coreg2_foto VALUES (85, 138, 139, 58, 0, 1151427066, 0, '', 0);
INSERT INTO coreg2_foto VALUES (86, 140, 141, 58, 0, 1151427104, 0, '', 0);
INSERT INTO coreg2_foto VALUES (66, 100, 101, 8, 0, 1149848031, 0, '', 0);
INSERT INTO coreg2_foto VALUES (67, 102, 103, 8, 0, 1149848101, 0, '', 0);
INSERT INTO coreg2_foto VALUES (68, 104, 105, 8, 0, 1149848115, 0, '', 0);
INSERT INTO coreg2_foto VALUES (69, 106, 107, 8, 0, 1149849321, 0, '', 0);
INSERT INTO coreg2_foto VALUES (70, 108, 109, 8, 0, 1149865912, 0, '', 0);
INSERT INTO coreg2_foto VALUES (71, 110, 111, 8, 0, 1149865925, 0, '', 0);
INSERT INTO coreg2_foto VALUES (72, 112, 113, 8, 0, 1149866954, 0, '', 0);
INSERT INTO coreg2_foto VALUES (73, 114, 115, 8, 0, 1149867047, 0, '', 0);
INSERT INTO coreg2_foto VALUES (74, 116, 117, 8, 0, 1149867263, 0, '', 0);
INSERT INTO coreg2_foto VALUES (75, 118, 119, 8, 0, 1149867335, 0, '', 0);
INSERT INTO coreg2_foto VALUES (76, 120, 121, 8, 0, 1149867358, 0, '', 0);
INSERT INTO coreg2_foto VALUES (78, 124, 125, 8, 0, 1149868930, 0, '', 0);
INSERT INTO coreg2_foto VALUES (87, 142, 143, 58, 0, 1151427145, 0, '', 0);
INSERT INTO coreg2_foto VALUES (84, 136, 137, 58, 0, 1151426862, 0, '', 0);
DROP TABLE IF EXISTS `coreg2_group`;
# ----------------------------------------
# table structure for table 'coreg2_group' 
CREATE TABLE coreg2_group (
  `ID` int(11) NOT NULL auto_increment,
  `groupname` varchar(50) NOT NULL default '',
  `code` int(11) default NULL,
  `active` enum('Si','No') NOT NULL default 'No',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_group VALUES (1, '',  NULL, 'No',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_group VALUES (2, 'Administradores', 2, 'Si', 0, 2, 0, 1161949224);
INSERT INTO coreg2_group VALUES (3, 'Operadores', 4, 'Si', 0, 2, 0, 1161949224);
INSERT INTO coreg2_group VALUES (4, '', 8, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (5, '', 16, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (6, '', 32, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (7, '', 64, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (8, '', 128, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (9, '', 256, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (10, '', 512, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (11, '', 1024, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (12, '', 2048, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (13, '', 4096, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (14, '', 8192, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (15, '', 16384, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (16, '', 32768, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (17, '', 65536, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (18, '', 131072, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (19, '', 262144, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (20, '', 524288, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (21, '', 1048576, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (22, '', 2097152, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (23, '', 4194304, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (24, '', 8388608, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (25, '', 16777216, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (26, '', 33554432, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (27, '', 67108864, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (28, '', 134217728, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (29, '', 268435456, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (30, '', 536870912, 'No', 2, 2, 1140165675, 1161949224);
INSERT INTO coreg2_group VALUES (31, 'Anyone', 1073741824, 'No', 2, 2, 1140165675, 1161949224);
DROP TABLE IF EXISTS `coreg2_notice`;
# ----------------------------------------
# table structure for table 'coreg2_notice' 
CREATE TABLE coreg2_notice (
  `ID` int(11) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `body` blob NOT NULL,
  `fecha_pub` int(11) default NULL,
  `id_cat` int(11) default NULL,
  `resumen` blob NOT NULL,
  `adjunto` int(11) default NULL,
  `keyword` varchar(100) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `visita` int(11) default NULL,
  `fech_ult_consulta` int(11) default NULL,
  `notas` varchar(100) NOT NULL default '',
  `fuente` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_notice VALUES (1, 'Introduzca titulo', 'Prueba', 0, 2, '', 0, '', 2, 0, 1180947539, 0, 0, 0, '', '');
INSERT INTO coreg2_notice VALUES (10, 'prueba', 'Prueba', 0, 2, '', 0, 'prueba', 2, 2, 1180975291, 1180976093, 16, 1180976093, '', 'prueba');
DROP TABLE IF EXISTS `coreg2_post`;
# ----------------------------------------
# table structure for table 'coreg2_post' 
CREATE TABLE coreg2_post (
  `ID` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `autor` varchar(50) NOT NULL default '',
  `msg` blob NOT NULL,
  `fecha` int(11) default NULL,
  `p_id` int(11) default NULL,
  `respuestas` int(11) default NULL,
  `foro_id` int(11) default NULL,
  `autormsg` varchar(50) NOT NULL default '',
  `visitas` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `urespuesta` int(11) default NULL,
  `foto` int(11) default NULL,
  `fecha_hoy` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `coreg2_queryb`;
# ----------------------------------------
# table structure for table 'coreg2_queryb' 
CREATE TABLE coreg2_queryb (
  `ID` int(11) NOT NULL auto_increment,
  `queryb` blob NOT NULL,
  `nombre` varchar(50) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_queryb VALUES (1, '', '',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_queryb VALUES (3, 'SELECT coreg2_user.username AS \'Usuario|string:50\',coreg2_user.nombre AS \'Nombre|string:50\',coreg2_user.apellidos AS \'Apellidos|string:50\' FROM coreg2_user WHERE ID>1', 'Usuarios', 2, 2, 1171132544, 1182781404);
DROP TABLE IF EXISTS `coreg2_registro`;
# ----------------------------------------
# table structure for table 'coreg2_registro' 
CREATE TABLE coreg2_registro (
  `ID` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `dia` int(11) default NULL,
  `entrada_m` int(11) default NULL,
  `salida_m` int(11) default NULL,
  `entrada_t` int(11) default NULL,
  `salida_t` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_registro VALUES (1,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL,  NULL);
DROP TABLE IF EXISTS `coreg2_report`;
# ----------------------------------------
# table structure for table 'coreg2_report' 
CREATE TABLE coreg2_report (
  `ID` int(11) NOT NULL auto_increment,
  `reportname` varchar(50) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `query_id` int(11) default NULL,
  `tipo` enum('HardCoded','SoftCoded') NOT NULL default 'HardCoded',
  `activo` enum('Si','No') NOT NULL default 'No',
  `printable` enum('Si','No') NOT NULL default 'No',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_report VALUES (1, '', '',  NULL, 'HardCoded', 'No', 'No',  NULL,  NULL,  NULL,  NULL);
INSERT INTO coreg2_report VALUES (3, 'Listado de Usuarios', '', 3, 'SoftCoded', 'Si', 'Si', 2, 0, 1171132562, 0);
DROP TABLE IF EXISTS `coreg2_soft`;
# ----------------------------------------
# table structure for table 'coreg2_soft' 
CREATE TABLE coreg2_soft (
  `ID` int(11) NOT NULL auto_increment,
  `nombre` varchar(100) NOT NULL default '',
  `desc` varchar(150) NOT NULL default '',
  `version` varchar(50) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `adjunto` int(11) default NULL,
  `cat_id` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `paquete` varchar(100) NOT NULL default '',
  `fecha_pub` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_soft VALUES (1, '', '', '', '',  NULL,  NULL,  NULL,  NULL,  NULL,  NULL, '',  NULL);
DROP TABLE IF EXISTS `coreg2_user`;
# ----------------------------------------
# table structure for table 'coreg2_user' 
CREATE TABLE coreg2_user (
  `ID` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `nombre` varchar(50) NOT NULL default '',
  `apellidos` varchar(50) NOT NULL default '',
  `grupos` int(11) default NULL,
  `activo` enum('Si','No') NOT NULL default 'No',
  `telefono` varchar(15) NOT NULL default '',
  `email` varchar(75) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  `fecha_hoy` int(11) default NULL,
  `direccion` varchar(75) NOT NULL default '',
  `localidad` varchar(50) NOT NULL default '',
  `p_state` varchar(50) NOT NULL default '',
  `zip` varchar(5) NOT NULL default '',
  `pais` varchar(50) NOT NULL default '',
  `mojo` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_user VALUES (1, 'user', '', '', '', 65536, 'No', '', '',  NULL,  NULL,  NULL,  NULL,  NULL, '', '', '', '', '', '');
INSERT INTO coreg2_user VALUES (2, 'admin', '098f6bcd4621d373cade4e832627b4f6', 'Administrador', 'Sistema', 6, 'Si', '954426632', 'info@activasistemas.com', 0, 2, 0, 1182781222, 1182781222, 'Mi calle', 'Sevilla', 'Sevilla', '41003', 'Espa?a', '');
INSERT INTO coreg2_user VALUES (74, 'operador', '098f6bcd4621d373cade4e832627b4f6', 'Operador', 'Sistema', 4, 'Si', '', '', 2, 0, 1162224261, 0, 0, '', '', '', '', '', '');
DROP TABLE IF EXISTS `coreg2_user_pref`;
# ----------------------------------------
# table structure for table 'coreg2_user_pref' 
CREATE TABLE coreg2_user_pref (
  `ID` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `sys_app_mode` enum('Si','No') NOT NULL default 'Si',
  `zoom` enum('popup','iframe') NOT NULL default 'popup',
  `rlimit` int(11) default NULL,
  `spooler` enum('Interno','ASPooler') NOT NULL default 'Interno',
  `ip_spooler` varchar(15) NOT NULL default '',
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO coreg2_user_pref VALUES (1,  NULL, 'Si', 'popup',  NULL, 'Interno', '',  NULL,  NULL,  NULL,  NULL);
DROP TABLE IF EXISTS `coreg2_void`;
# ----------------------------------------
# table structure for table 'coreg2_void' 
CREATE TABLE coreg2_void (
  `ID` int(11) NOT NULL auto_increment,
  `none` int(11) default NULL,
  `S_UserID_CB` int(11) default NULL,
  `S_UserID_MB` int(11) default NULL,
  `S_Date_C` int(11) default NULL,
  `S_Date_M` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

