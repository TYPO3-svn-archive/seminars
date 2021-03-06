<?php
/***************************************************************
 * Copyright notice
*
* (c) 2012 Niels Pardon (mail@niels-pardon.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This class represents a view helper for rendering date ranges.
 *
 * @package TYPO3
 * @subpackage tx_seminars
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
class tx_seminars_ViewHelper_DateRange {
	/**
	 * @var tx_oelib_Configuration
	 */
	protected $configuration = NULL;

	/**
	 * @var tx_oelib_Translator
	 */
	protected $translator = NULL;

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->configuration = tx_oelib_ConfigurationRegistry::getInstance()->get('plugin.tx_seminars');
		$this->translator = tx_oelib_TranslatorRegistry::getInstance()->get('seminars');
	}

	/**
	 * Frees as much memory that has been used by this object as possible.
	 */
	public function __destruct() {
		unset($this->configuration, $this->translator);
	}

	/**
	 * Gets the date.
	 * Returns a localized string "will be announced" if there's no date set.
	 *
	 * Returns just one day if the timespan takes place on only one day.
	 * Returns a date range if the timespan takes several days.
	 *
	 * @param tx_seminars_Model_AbstractTimeSpan $timeSpan the timespan to get the date for
	 * @param string $dash the character or HTML entity used to separate start date and end date
	 *
	 * @return string the timespan date
	 */
	public function render(tx_seminars_Model_AbstractTimeSpan $timeSpan, $dash = '&#8211;') {
		if (!$timeSpan->hasBeginDate()) {
			return $this->translator->translate('message_willBeAnnounced');
		}

		$beginDate = $timeSpan->getBeginDateAsUnixTimeStamp();
		$endDate = $timeSpan->getEndDateAsUnixTimeStamp();

		// Is the timespan open-ended or does it span one day only?
		if (!$timeSpan->hasEndDate() || $this->isSameDay($beginDate, $endDate)) {
			return $this->getAsDateFormatYmd($beginDate);
		}

		if ($this->configuration->getAsBoolean('abbreviateDateRanges')) {
			$formattedBeginDate = $this->getAsAbbreviatedDateRange($beginDate, $endDate, $dash);
		} else {
			$formattedBeginDate = $this->getAsDateFormatYmd($beginDate);
		}

		return $formattedBeginDate . $dash . $this->getAsDateFormatYmd($endDate);
	}

	/**
	 * Renders the UNIX timestamps in $beginDate and $endDate as an abbreviated date range.
	 *
	 * @param integer $beginDate
	 * @param integer $endDate
	 * @param string $dash
	 *
	 * @return string the abbreviated date range
	 */
	protected function getAsAbbreviatedDateRange($beginDate, $endDate, $dash = '&#8211;') {
		// Are the years different? Then include the complete begin date.
		if (!$this->isSameYear($beginDate, $endDate)) {
			return $this->getAsDateFormatYmd($beginDate);
		}

		// Are the months different? Then include day and month.
		if (!$this->isSameMonth($beginDate, $endDate)) {
			return $this->getAsDateFormatMd($beginDate);
		}

		return $this->getAsDateFormatD($beginDate);
	}

	/**
	 * Returns whether the UNIX timestamps in $beginDate and $endDate are on the same day.
	 *
	 * @param integer $beginDate
	 * @param integer $endDate
	 *
	 * @return boolean TRUE if $beginDate and $endDate are on the same day, otherwise FALSE
	 */
	protected function isSameDay($beginDate, $endDate) {
		return ($this->getAsDateFormatYmd($beginDate) === $this->getAsDateFormatYmd($endDate));
	}

	/**
	 * Returns whether the UNIX timestamps in $beginDate and $endDate are in the same month.
	 *
	 * @param integer $beginDate
	 * @param integer $endDate
	 *
	 * @return boolean TRUE if $beginDate and $endDate are in the same month, otherwise FALSE
	 */
	protected function isSameMonth($beginDate, $endDate) {
		return ($this->getAsDateFormatM($beginDate) === $this->getAsDateFormatM($endDate));
	}

	/**
	 * Returns whether the UNIX timestamps in $beginDate and $endDate are in the same year.
	 *
	 * @param integer $beginDate
	 * @param integer $endDate
	 *
	 * @return boolean TRUE if $beginDate and $endDate are in the same year, otherwise FALSE
	 */
	protected function isSameYear($beginDate, $endDate) {
		return ($this->getAsDateFormatY($beginDate) === $this->getAsDateFormatY($endDate));
	}

	/**
	 * Renders a UNIX timestamp in the strftime format specified in plugin.tx_seminars_seminars.dateFormatYMD.
	 *
	 * @param integer $timestamp the UNIX timestamp to render
	 *
	 * @return string the UNIX timestamp rendered using the strftime format in plugin.tx_seminars_seminars.dateFormatYMD
	 */
	protected function getAsDateFormatYmd($timestamp) {
		return strftime($this->configuration->getAsString('dateFormatYMD'), $timestamp);
	}

	/**
	 * Renders a UNIX timestamp in the strftime format specified in plugin.tx_seminars_seminars.dateFormatY.
	 *
	 * @param integer $timestamp the UNIX timestamp to render
	 *
	 * @return string the UNIX timestamp rendered using the strftime format in plugin.tx_seminars_seminars.dateFormatY
	 */
	protected function getAsDateFormatY($timestamp) {
		return strftime($this->configuration->getAsString('dateFormatY'), $timestamp);
	}

	/**
	 * Renders a UNIX timestamp in the strftime format specified in plugin.tx_seminars_seminars.dateFormatM.
	 *
	 * @param integer $timestamp the UNIX timestamp to render
	 *
	 * @return string the UNIX timestamp rendered using the strftime format in plugin.tx_seminars_seminars.dateFormatM
	 */
	protected function getAsDateFormatM($timestamp) {
		return strftime($this->configuration->getAsString('dateFormatM'), $timestamp);
	}

	/**
	 * Renders a UNIX timestamp in the strftime format specified in plugin.tx_seminars_seminars.dateFormatMD.
	 *
	 * @param integer $timestamp the UNIX timestamp to render
	 *
	 * @return string the UNIX timestamp rendered using the strftime format in plugin.tx_seminars_seminars.dateFormatMD
	 */
	protected function getAsDateFormatMd($timestamp) {
		return strftime($this->configuration->getAsString('dateFormatMD'), $timestamp);
	}

	/**
	 * Renders a UNIX timestamp in the strftime format specified in plugin.tx_seminars_seminars.dateFormatD.
	 *
	 * @param integer $timestamp the UNIX timestamp to render
	 *
	 * @return string the UNIX timestamp rendered using the strftime format in plugin.tx_seminars_seminars.dateFormatD
	 */
	protected function getAsDateFormatD($timestamp) {
		return strftime($this->configuration->getAsString('dateFormatD'), $timestamp);
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/seminars/ViewHelper/DateRange.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/seminars/ViewHelper/DateRange.php']);
}