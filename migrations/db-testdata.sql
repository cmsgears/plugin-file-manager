--
-- Main Site
--

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Facebook Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`options`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config File','config-file','system','Facebook configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-file';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`options`,`data`,`order`) VALUES 
	(@form,'image-extensions','Image Extensions',0,0,'required','{\"title\":\"Image Extensions.\",\"placeholder\":\"Image Extensions\"}',NULL,0),
	(@form,'video-extensions','Video Extensions',0,0,'required','{\"title\":\"Video Extensions.\",\"placeholder\":\"Video Extensions\"}',NULL,0),
	(@form,'doc-extensions','Doc Extensions',0,0,'required','{\"title\":\"Doc Extensions.\",\"placeholder\":\"Doc Extensions\"}',NULL,0),
	(@form,'zip-extensions','Zip Extensions',0,0,'required','{\"title\":\"Zip Extensions.\",\"placeholder\":\"Zip Extensions\"}',NULL,0),
	(@form,'generate-name','Generate Name',20,0,'required','{\"title\":\"Generate Name.\"}',NULL,0),
	(@form,'pretty-name','Pretty Name',20,0,'required','{\"title\":\"Pretty Name.\"}',NULL,0),
	(@form,'max-size','Max Size',0,0,'required','{\"title\":\"Max Size.\",\"placeholder\":\"Max Size\"}',NULL,0),
	(@form,'generate-thumb','Generate Thumb',20,0,'required','{\"title\":\"Generate Thumb.\"}',NULL,0),
	(@form,'thumb-width','Thumb Width',0,0,'required','{\"title\":\"Thumb Width.\",\"placeholder\":\"Thumb Width\"}',NULL,0),
	(@form,'thumb-height','Thumb Height',0,0,'required','{\"title\":\"Thumb Height.\",\"placeholder\":\"Thumb Height\"}',NULL,0),
	(@form,'uploads-directory','Uploads Directory',0,0,'required','{\"title\":\"Uploads Directory.\",\"placeholder\":\"Uploads Directory\"}',NULL,0),
	(@form,'uploads-url','Uploads URL',0,0,'required','{\"title\":\"Uploads URL.\",\"placeholder\":\"Uploads URL\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`value`,`type`) VALUES
	(@site,'site','image-extensions','png,jpg,jpeg,gif','file'),
	(@site,'site','video-extensions','mp4,flv,ogv,avi','file'),
	(@site,'site','doc-extensions','pdf','file'),
	(@site,'site','zip-extensions','rar,zip','file'),
	(@site,'site','generate-name','1','file'),
	(@site,'site','pretty-name','0','file'),
	(@site,'site','max-size','5','file'),
	(@site,'site','generate-thumb','1','file'),
	(@site,'site','thumb-width','120','file'),
	(@site,'site','thumb-height','120','file'),
	(@site,'site','uploads-directory',NULL,'file'),
	(@site,'site','uploads-url','http://localhost/test/uploads/','file');