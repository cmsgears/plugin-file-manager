--
-- Dumping data for table `cmg_core_model_meta`
--

INSERT INTO `cmg_core_model_meta` (`parentId`,`parentType`,`name`,`value`,`type`,`fieldType`,`fieldMeta`) VALUES
	(1,'site','Image Extensions','png,jpg,jpeg,gif','file','input','{\"title\":\"Allowed extensions for images using comma seperated values.\"}'),
	(1,'site','Video Extensions','mp4,flv,ogv,avi','file','input','{\"title\":\"Allowed extensions for videos using comma seperated values.\"}'),
	(1,'site','Doc Extensions','pdf','file','input','{\"title\":\"Allowed extensions for documents using comma seperated values.\"}'),
	(1,'site','Zip Extensions','rar,zip','file','input','{\"title\":\"Allowed extensions for compressed documents using comma seperated values.\"}'),
	(1,'site','Generate Name','1','file','checkbox','{\"title\":\"It allows file manager to generate names randomly and override Pretty Names.\"}'),
	(1,'site','Pretty Names','0','file','checkbox','{\"title\":\"It allow file manager to generate pretty names and add appropriate counter at end in case of duplicate names.It does not work if Generate Name is set.\"}'),
	(1,'site','Max Size','5','file','input','{\"title\":\"Maximum allowed file size.\"}'),
	(1,'site','Generate Thumb','1','file','input','{\"title\":\"Allows file manager to generate thumb for images.\"}'),
	(1,'site','Thumb Width','120','file','input','{\"title\":\"Default thumb Width to generate thumb. It can be overriden by File Uploader Widgets configuration.\"}'),
	(1,'site','Thumb Height','120','file','input','{\"title\":\"Default thumb Height to generate thumb. It can be overriden by File Uploader Widgets configuration.\"}'),
	(1,'site','Uploads Directory',NULL,'file','input','{\"title\":\"Default uploads directory.\"}'),
	(1,'site','Uploads URL','http://localhost/test/uploads','file','input','{\"title\":\"Default uploads url.\"}');