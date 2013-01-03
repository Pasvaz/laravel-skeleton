<?php

// UTF8 representation of various characters
define('CH_UTF8_BOM',    "\xEF\xBB\xBF"); // U+FEFF
// UTF8 control codes affecting the BiDirectional algorithm (see http://www.unicode.org/reports/tr9/)
define('CH_UTF8_LRM',    "\xE2\x80\x8E"); // U+200E  (Left to Right mark:  zero-width character with LTR directionality)
define('CH_UTF8_RLM',    "\xE2\x80\x8F"); // U+200F  (Right to Left mark:  zero-width character with RTL directionality)
define('CH_UTF8_LRO',    "\xE2\x80\xAD"); // U+202D  (Left to Right override: force everything following to LTR mode)
define('CH_UTF8_RLO',    "\xE2\x80\xAE"); // U+202E  (Right to Left override: force everything following to RTL mode)
define('CH_UTF8_LRE',    "\xE2\x80\xAA"); // U+202A  (Left to Right embedding: treat everything following as LTR text)
define('CH_UTF8_RLE',    "\xE2\x80\xAB"); // U+202B  (Right to Left embedding: treat everything following as RTL text)
define('CH_UTF8_PDF',    "\xE2\x80\xAC"); // U+202C  (Pop directional formatting: restore state prior to last LRO, RLO, LRE, RLE)
 
class Localized_Date {

	const PRESENTED_DATE_FORMAT='m-d-Y';
	const STORED_DATE_FORMAT='Y-m-d';

	/**
	 * This function strips &lrm; and &rlm; from the input string.  It should be used for all
	 * text that has been passed through the PrintReady() function before that text is stored
	 * in the database.  The database should NEVER contain these characters.
	 *
	 * @param  string The string from which the &lrm; and &rlm; characters should be stripped
	 * @return string The input string, with &lrm; and &rlm; stripped
	 */
	static function stripLRMRLM($inputText) {
		$notvalid=array(CH_UTF8_LRM, CH_UTF8_RLM, CH_UTF8_LRO, CH_UTF8_RLO, CH_UTF8_LRE, CH_UTF8_RLE, CH_UTF8_PDF, "&lrm;", "&rlm;", "&LRM;", "&RLM;");
		return str_replace($notvalid, "", $inputText);
	}

	/**
	 * 
	 */
	public $date;

	/**
	 * 
	 */
	public $formatter;

	/**
	 * 
	 */
	public $datepicker_format;

	/**
	 * Create a new Localized_Date instance.
	 *
	 * @param  \DateTime  $date
	 * @param  \IntlDateFormatter $format
	 * @return void
	 */
	//public function __construct(\DateTime $date = new \DateTime(), $locale=Locale::getDefault(), int $datetype=IntlDateFormatter::SHORT, int $timetype=IntlDateFormatter::NONE)
	public function __construct(\DateTime $date, $locale=null, $datetype = IntlDateFormatter::SHORT, $timetype = IntlDateFormatter::NONE)
	{
		if (is_null($date)) $date = new \DateTime();
		if (is_null($locale)) {
			$browserlang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			if (isset($browserlang))
	            $locale = substr($browserlang, 0, 2);
			else
				$locale = Locale::getDefault();
		}

		$this->formatter = new IntlDateFormatter($locale, $datetype, $timetype);
		$this->date = $date;
		$this->datepicker_format = $this->getDatepickerPattern();
		//$this->date = DateTime::createFromFormat($format, $date);
	}

    /**
     * Writes the localized Date
     *
     * @return string
     */
    public function __toString()
    {
        return $this->formatter->format($this->date);
    }

	/**
	* Create pattern Date Javascript
	*
	* @return string pattern date of Javascript
	*/
	function getDatepickerDate()
	{
		return $this->date->format($this->getDatePattern());
	}

	/**
	* Create pattern Date Javascript
	*
	* @return string pattern date of Javascript
	*/
	function getDatepickerPattern()
	{
	    $pattern = static::stripLRMRLM($this->formatter->getPattern());
	    $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $pattern);
	    $exits = array();

	    // Transform pattern for JQuery ui datepicker
	    foreach ($patterns as $index => $val) {
	        switch ($val) {
	            case 'Y':
	            case 'y':
	            case 'yy':
	            case 'yyyy':
	                $exits[$val] = 'yyyy';
	                break;

	            case 'M':
	            case 'MM':
	            case 'L':
	            case 'LL':
	                $exits[$val] = 'm';
	                break;

	            case 'MMM':
	            case 'MMMMM':
	            case 'LLL':
	            case 'LLLLL':
	                $exits[$val] = 'M';
	                break;

	            case 'MMMM':
	            case 'LLLL':
	                $exits[$val] = 'MM';
	                break;

	            case 'd':
	            case 'dd':
	            case 'e':
	            case 'ee':
	            case 'c':
	            case 'cc':
	                $exits[$val] = 'd';
	                break;

	            case 'E':
	            case 'EE':
	            case 'EEE':
	            case 'EEEEE':
	            case 'eee':
	            case 'eeeee':
	            case 'ccc':
	            case 'ccccc':
	                $exits[$val] = 'D';
	                break;

	            case 'EEEE':
	            case 'eeee':
	            case 'cccc':
	                $exits[$val] = 'DD';
	                break;

	            default:
	                $exits[$val] = '';
	                break;
	        }
	    }
	    return str_replace(array_keys($exits), array_values($exits), $pattern);
	}

	/**
	* Create pattern Date Javascript
	*
	* @return string pattern date of Javascript
	*/
	function getDatePattern()
	{
	    $pattern = static::stripLRMRLM($this->formatter->getPattern());
	    $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $pattern);
	    $exits = array();

	    // Transform pattern for date format
	    foreach ($patterns as $index => $val) {
	        switch ($val) {
	            case 'Y':
	            case 'y':
	            case 'yy':
	            case 'yyyy':
	                $exits[$val] = 'Y';
	                break;

	            case 'M':
	            case 'MM':
	            case 'L':
	            case 'LL':
	                $exits[$val] = 'n';
	                break;

	            case 'MMM':
	            case 'MMMMM':
	            case 'LLL':
	            case 'LLLLL':
	                $exits[$val] = 'M';
	                break;

	            case 'MMMM':
	            case 'LLLL':
	                $exits[$val] = 'F';
	                break;

	            case 'd':
	            case 'dd':
	            case 'e':
	            case 'ee':
	            case 'c':
	            case 'cc':
	                $exits[$val] = 'j';
	                break;

	            case 'E':
	            case 'EE':
	            case 'EEE':
	            case 'EEEEE':
	            case 'eee':
	            case 'eeeee':
	            case 'ccc':
	            case 'ccccc':
	                $exits[$val] = 'D';
	                break;

	            case 'EEEE':
	            case 'eeee':
	            case 'cccc':
	                $exits[$val] = 'l';
	                break;

	            default:
	                $exits[$val] = '';
	                break;
	        }
	    }
	    return str_replace(array_keys($exits), array_values($exits), $pattern);
	}
}