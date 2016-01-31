--
-- Main Site
--

SELECT @site := `id` FROM cmg_core_site WHERE slug = 'main';

--
-- Facebook Config Form
--

INSERT INTO `cmg_core_form` (`siteId`,`templateId`,`createdBy`,`modifiedBy`,`name`,`slug`,`type`,`description`,`successMessage`,`captcha`,`visibility`,`active`,`userMail`,`adminMail`,`createdAt`,`modifiedAt`,`htmlOptions`,`data`) VALUES
	(@site,NULL,1,1,'Config File','config-file','system','Facebook configuration form.','All configurations saved successfully.',0,10,1,0,0,'2014-10-11 14:22:54','2014-10-11 14:22:54',NULL,NULL);

SELECT @form := `id` FROM cmg_core_form WHERE slug = 'config-file';

INSERT INTO `cmg_core_form_field` (`formId`,`name`,`label`,`type`,`compress`,`validators`,`order`,`htmlOptions`,`data`) VALUES 
	(@form,'image_extensions','Image Extensions',0,0,'required',0,'{\"title\":\"Image Extensions.\",\"placeholder\":\"Image Extensions\"}',NULL),
	(@form,'video_extensions','Video Extensions',0,0,'required',0,'{\"title\":\"Video Extensions.\",\"placeholder\":\"Video Extensions\"}',NULL),
	(@form,'audio_extensions','Audio Extensions',0,0,'required',0,'{\"title\":\"Audio Extensions.\",\"placeholder\":\"Audio Extensions\"}',NULL),
	(@form,'document_extensions','Document Extensions',0,0,'required',0,'{\"title\":\"Document Extensions.\",\"placeholder\":\"Document Extensions\"}',NULL),
	(@form,'compressed_extensions','Compressed Extensions',0,0,'required',0,'{\"title\":\"Compressed Extensions.\",\"placeholder\":\"Compressed Extensions\"}',NULL),
	(@form,'generate_name','Generate Name',40,0,'required',0,'{\"title\":\"Generate Name.\"}',NULL),
	(@form,'pretty_name','Pretty Name',40,0,'required',0,'{\"title\":\"Pretty Name.\"}',NULL),
	(@form,'max_size','Max Size',0,0,'required',0,'{\"title\":\"Max Size.\",\"placeholder\":\"Max Size\"}',NULL),
	(@form,'generate_thumb','Generate Thumb',40,0,'required',0,'{\"title\":\"Generate Thumb.\"}',NULL),
	(@form,'thumb_width','Thumb Width',0,0,'required',0,'{\"title\":\"Thumb Width.\",\"placeholder\":\"Thumb Width\"}',NULL),
	(@form,'thumb_height','Thumb Height',0,0,'required',0,'{\"title\":\"Thumb Height.\",\"placeholder\":\"Thumb Height\"}',NULL),
	(@form,'uploads_directory','Uploads Directory',0,0,NULL,0,'{\"title\":\"Uploads Directory.\",\"placeholder\":\"Uploads Directory\"}',NULL),
	(@form,'uploads_url','Uploads URL',0,0,'required',0,'{\"title\":\"Uploads URL.\",\"placeholder\":\"Uploads URL\"}',NULL);

--
-- Dumping data for table `cmg_core_model_attribute`
--

INSERT INTO `cmg_core_model_attribute` (`parentId`,`parentType`,`name`,`type`,`valueType`,`value`) VALUES
	(@site,'site','image_extensions','file','text','png,jpg,jpeg,gif'),
	(@site,'site','video_extensions','file','text','mp4,flv,ogv,avi'),
	(@site,'site','audio_extensions','file','text','mp3,m4a,wav'),
	(@site,'site','document_extensions','file','text','pdf,doc,docx,xls,xlsx,txt'),
	(@site,'site','compressed_extensions','file','text','rar,zip'),
	(@site,'site','generate_name','file','flag','1'),
	(@site,'site','pretty_name','file','flag','0'),
	(@site,'site','max_size','file','text','5'),
	(@site,'site','generate_thumb','file','flag','1'),
	(@site,'site','thumb_width','file','text','120'),
	(@site,'site','thumb_height','file','text','120'),
	(@site,'site','uploads_directory','file','text',NULL),
	(@site,'site','uploads_url','file','text','http://localhost/test/uploads/');