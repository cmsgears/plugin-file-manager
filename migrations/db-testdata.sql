--
-- Dumping data for table `cmg_core_form`
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(1,NULL,1,1,'Config File','config-file','Facebook configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

--
-- Dumping data for table `cmg_core_form_field`
--

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(8,'image-extensions','Image Extensions',0,0,'required','{\"title\":\"Image Extensions.\",\"placeholder\":\"Image Extensions\"}',NULL,0),
	(8,'video-extensions','Video Extensions',0,0,'required','{\"title\":\"Video Extensions.\",\"placeholder\":\"Video Extensions\"}',NULL,0),
	(8,'doc-extensions','Doc Extensions',0,0,'required','{\"title\":\"Doc Extensions.\",\"placeholder\":\"Doc Extensions\"}',NULL,0),
	(8,'zip-extensions','Zip Extensions',0,0,'required','{\"title\":\"Zip Extensions.\",\"placeholder\":\"Zip Extensions\"}',NULL,0),
	(8,'generate-name','Generate Name',20,0,'required','{\"title\":\"Generate Name.\"}',NULL,0),
	(8,'pretty-name','Pretty Name',20,0,'required','{\"title\":\"Pretty Name.\"}',NULL,0),
	(8,'max-size','Max Size',0,0,'required','{\"title\":\"Max Size.\",\"placeholder\":\"Max Size\"}',NULL,0),
	(8,'generate-thumb','Generate Thumb',20,0,'required','{\"title\":\"Generate Thumb.\"}',NULL,0),
	(8,'thumb-width','Thumb Width',0,0,'required','{\"title\":\"Thumb Width.\",\"placeholder\":\"Thumb Width\"}',NULL,0),
	(8,'thumb-height','Thumb Height',0,0,'required','{\"title\":\"Thumb Height.\",\"placeholder\":\"Thumb Height\"}',NULL,0),
	(8,'uploads-directory','Uploads Directory',0,0,'required','{\"title\":\"Uploads Directory.\",\"placeholder\":\"Uploads Directory\"}',NULL,0),
	(8,'uploads-url','Uploads URL',0,0,'required','{\"title\":\"Uploads URL.\",\"placeholder\":\"Uploads URL\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`value`,`type`) VALUES
	(1,'site','image-extensions','png,jpg,jpeg,gif','file'),
	(1,'site','video-extensions','mp4,flv,ogv,avi','file'),
	(1,'site','doc-extensions','pdf','file'),
	(1,'site','zip-extensions','rar,zip','file'),
	(1,'site','generate-name','1','file'),
	(1,'site','pretty-name','0','file'),
	(1,'site','max-size','5','file'),
	(1,'site','generate-thumb','1','file'),
	(1,'site','thumb-width','120','file'),
	(1,'site','thumb-height','120','file'),
	(1,'site','uploads-directory',NULL,'file'),
	(1,'site','uploads-url','http://localhost/test/uploads','file');