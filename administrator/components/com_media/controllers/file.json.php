<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * File Media Controller
 *
 * @since  1.6
 */
class MediaControllerFile extends JControllerLegacy
{
	/**
	 * Upload a file
	 *
	 * @return  void
	 *
	 * @since   1.5
	 */
	public function upload()
	{
		$params = JComponentHelper::getParams('com_media');

		// Check for request forgeries
		if (!JSession::checkToken('request'))
		{
			$response = array(
				'status' => '0',
				'error' => JText::_('JINVALID_TOKEN')
			);
			echo json_encode($response);

			return;
		}

		// Get the user
		$user  = JFactory::getUser();
		JLog::addLogger(array('text_file' => 'upload.error.php'), JLog::ALL, array('upload'));

		// Get some data from the request
		$file   = $this->input->files->get('Filedata', '', 'array');
		$folder = $this->input->get('folder', '', 'path');

		// Instantiate the media helper
		$mediaHelper = new JHelperMedia;

		if ($_SERVER['CONTENT_LENGTH'] > ($params->get('upload_maxsize', 0) * 1024 * 1024)
			|| $_SERVER['CONTENT_LENGTH'] > $mediaHelper->toBytes(ini_get('upload_max_filesize'))
			|| $_SERVER['CONTENT_LENGTH'] > $mediaHelper->toBytes(ini_get('post_max_size'))
			|| $_SERVER['CONTENT_LENGTH'] > $mediaHelper->toBytes(ini_get('memory_limit')))
		{
			$response = array(
				'status' => '0',
				'error' => JText::_('COM_MEDIA_ERROR_WARNFILETOOLARGE')
			);
			echo json_encode($response);

			return;
		}

		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		$file['name'] = JFile::makeSafe($file['name']);

		if (!isset($file['name']))
		{
			$response = array(
				'status' => '0',
				'error' => JText::_('COM_MEDIA_ERROR_BAD_REQUEST')
			);

			echo json_encode($response);

			return;
		}

		// The request is valid
		$err = null;

		$filepath = JPath::clean(COM_MEDIA_BASE . '/' . $folder . '/' . strtolower($file['name']));

		if (!MediaHelper::canUpload($file, $err))
		{
			JLog::add('Invalid: ' . $filepath . ': ' . $err, JLog::INFO, 'upload');

			$response = array(
				'status' => '0',
				'error' => JText::_($err)
			);

			echo json_encode($response);

			return;
		}

		// Trigger the onContentBeforeSave event.
		JPluginHelper::importPlugin('content');
		$dispatcher  = JEventDispatcher::getInstance();
		$object_file = new JObject($file);
		$object_file->filepath = $filepath;
		$result = $dispatcher->trigger('onContentBeforeSave', array('com_media.file', &$object_file, true));

		if (in_array(false, $result, true))
		{
			// There are some errors in the plugins
			JLog::add('Errors before save: ' . $object_file->filepath . ' : ' . implode(', ', $object_file->getErrors()), JLog::INFO, 'upload');

			$response = array(
				'status' => '0',
				'error' => JText::plural('COM_MEDIA_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors))
			);

			echo json_encode($response);

			return;
		}

		if (JFile::exists($object_file->filepath))
		{
			// File exists
			JLog::add('File exists: ' . $object_file->filepath . ' by user_id ' . $user->id, JLog::INFO, 'upload');

			$response = array(
				'status' => '0',
				'error' => JText::_('COM_MEDIA_ERROR_FILE_EXISTS')
			);

			echo json_encode($response);

			return;
		}

		if (!$user->authorise('core.create', 'com_media'))
		{
			// File does not exist and user is not authorised to create
			JLog::add('Create not permitted: ' . $object_file->filepath . ' by user_id ' . $user->id, JLog::INFO, 'upload');

			$response = array(
				'status' => '0',
				'error' => JText::_('COM_MEDIA_ERROR_CREATE_NOT_PERMITTED')
			);

			echo json_encode($response);

			return;
		}

		if (!JFile::upload($object_file->tmp_name, $object_file->filepath))
		{
		
			JLog::add('Error on upload: ' . $object_file->filepath, JLog::INFO, 'upload');

			$response = array(
				'status' => '0',
				'error' => JText::_('COM_MEDIA_ERROR_UNABLE_TO_UPLOAD_FILE')
			);

			echo json_encode($response);

			return;
		}

		// Trigger the onContentAfterSave event.
		$dispatcher->trigger('onContentAfterSave', array('com_media.file', &$object_file, true));
		JLog::add($folder, JLog::INFO, 'upload');

		$response = array(
			'status' => '1',
			'error' => JText::sprintf('COM_MEDIA_UPLOAD_COMPLETE', substr($object_file->filepath, strlen(COM_MEDIA_BASE)))
		);

		echo json_encode($response);

		return;
	}
}
