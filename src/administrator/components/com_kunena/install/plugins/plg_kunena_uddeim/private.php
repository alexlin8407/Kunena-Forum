<?php
/**
 * Kunena Plugin
 *
 * @package         Kunena.Plugins
 * @subpackage      UddeIM
 *
 * @copyright       Copyright (C) 2008 - 2018 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

/**
 * Class KunenaPrivateUddeIM
 * @since Kunena
 */
class KunenaPrivateUddeIM extends KunenaPrivate
{
	/**
	 * @var null|uddeIMAPI
	 * @since Kunena
	 */
	protected $uddeim = null;

	/**
	 * @var null
	 * @since Kunena
	 */
	protected $params = null;

	/**
	 * @param $params
	 *
	 * @since Kunena
	 */
	public function __construct($params)
	{
		$this->params = $params;

		if (!class_exists('uddeIMAPI'))
		{
			return;
		}

		$this->uddeim = new uddeIMAPI;

		if ($this->uddeim->version() < 1)
		{
			return;
		}
	}

	/**
	 * @param $userid
	 *
	 * @return mixed
	 * @since Kunena
	 */
	public function getUnreadCount($userid)
	{
		return $this->uddeim->getInboxUnreadMessages($userid);
	}

	/**
	 * @param $text
	 *
	 * @return string
	 * @since Kunena
	 */
	public function getInboxLink($text)
	{
		if (!$text)
		{
			$text = JText::_('COM_KUNENA_PMS_INBOX');
		}

		return '<a href="' . JRoute::_($this->uddeim->getLinkToBox('inbox', false)) . '" rel="follow">' . $text . '</a>';
	}

	/**
	 * @return string
	 * @since Kunena
	 */
	public function getInboxURL()
	{
		return JRoute::_($this->uddeim->getLinkToBox('inbox', false));
	}

	/**
	 * @param $userid
	 *
	 * @return string
	 * @since Kunena
	 */
	protected function getURL($userid)
	{
		static $itemid = false;

		if ($itemid === false)
		{
			$itemid = 0;

			if (method_exists($this->uddeim, 'getItemid'))
			{
				$itemid = $this->uddeim->getItemid();
			}

			if ($itemid)
			{
				$itemid = '&Itemid=' . (int) $itemid;
			}
			else
			{
				$itemid = '';
			}
		}

		return JRoute::_('index.php?option=com_uddeim&task=new&recip=' . (int) $userid . $itemid);
	}
}
