--
-- Main Site
--

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Facebook Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`htmlOptions`,`createdAt`,`modifiedAt`) VALUES
	(@site,NULL,1,1,'Config File','config-file','system','Facebook configuration form.','All configurations saved successfully.',0,10,1,0,0,NULL,'2014-10-11 14:22:54','2014-10-11 14:22:54');

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-file';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`htmlOptions`,`data`,`order`) VALUES 
	(@form,'image_extensions','Image Extensions',0,0,'required','{\"title\":\"Image Extensions.\",\"placeholder\":\"Image Extensions\"}',NULL,0),
	(@form,'video_extensions','Video Extensions',0,0,'required','{\"title\":\"Video Extensions.\",\"placeholder\":\"Video Extensions\"}',NULL,0),
	(@form,'doc_extensions','Doc Extensions',0,0,'required','{\"title\":\"Doc Extensions.\",\"placeholder\":\"Doc Extensions\"}',NULL,0),
	(@form,'zip_extensions','Zip Extensions',0,0,'required','{\"title\":\"Zip Extensions.\",\"placeholder\":\"Zip Extensions\"}',NULL,0),
	(@form,'generate_name','Generate Name',20,0,'required','{\"title\":\"Generate Name.\"}',NULL,0),
	(@form,'pretty_name','Pretty Name',20,0,'required','{\"title\":\"Pretty Name.\"}',NULL,0),
	(@form,'max_size','Max Size',0,0,'required','{\"title\":\"Max Size.\",\"placeholder\":\"Max Size\"}',NULL,0),
	(@form,'generate_thumb','Generate Thumb',20,0,'required','{\"title\":\"Generate Thumb.\"}',NULL,0),
	(@form,'thumb_width','Thumb Width',0,0,'required','{\"title\":\"Thumb Width.\",\"placeholder\":\"Thumb Width\"}',NULL,0),
	(@form,'thumb_height','Thumb Height',0,0,'required','{\"title\":\"Thumb Height.\",\"placeholder\":\"Thumb Height\"}',NULL,0),
	(@form,'uploads_directory','Uploads Directory',0,0,NULL,'{\"title\":\"Uploads Directory.\",\"placeholder\":\"Uploads Directory\"}',NULL,0),
	(@form,'uploads_url','Uploads URL',0,0,'required','{\"title\":\"Uploads URL.\",\"placeholder\":\"Uploads URL\"}',NULL,0);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`type`,`valueType`,`value`) VALUES
	(@site,'site','image_extensions','file','text','png,jpg,jpeg,gif'),
	(@site,'site','video_extensions','file','text','mp4,flv,ogv,avi'),
	(@site,'site','doc_extensions','file','text','pdf'),
	(@site,'site','zip_extensions','file','text','rar,zip'),
	(@site,'site','generate_name','file','flag','1'),
	(@site,'site','pretty_name','file','flag','0'),
	(@site,'site','max_size','file','text','5'),
	(@site,'site','generate_thumb','file','flag','1'),
	(@site,'site','thumb_width','file','text','120'),
	(@site,'site','thumb_height','file','text','120'),
	(@site,'site','uploads_directory','file','text',NULL),
	(@site,'site','uploads_url','file','text','http://localhost/test/uploads/');