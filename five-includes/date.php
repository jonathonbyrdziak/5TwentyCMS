<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Five Twenty CMS
 * @SubPackage PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * 
 * @author byrd
 *
 */
class FiveDate extends Object
{
	/**
	 * Unix timestamp
	 *
	 * @var     int|boolean
	 * @access  protected
	 */
	var $_date = false;

	/**
	 * Time offset (in seconds)
	 *
	 * @var     string
	 * @access  protected
	 */
	var $_offset = 0;

	/**
	 * Creates a new instance of Date representing a given date.
	 *
	 * Accepts RFC 822, ISO 8601 date formats as well as unix time stamps.
	 * If not specified, the current date and time is used.
	 *
	 * @param mixed $date optional the date this Date will represent.
	 * @param int $tzOffset optional the timezone $date is from
	 */
	function __construct($date = 'now', $tzOffset = 0)
	{
		if ($date == 'now' || empty($date))
		{
			$this->_date = strtotime(gmdate("M d Y H:i:s", time()));
			return;
		}
		
		$tzOffset *= 3600;
		if (is_numeric($date))
		{
			$this->_date = $date - $tzOffset;
			return;
		}

		if (preg_match('~(?:(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun),\\s+)?(\\d{1,2})\\s+([a-zA-Z]{3})\\s+(\\d{4})\\s+(\\d{2}):(\\d{2}):(\\d{2})\\s+(.*)~i',$date,$matches))
		{
			$months = Array(
				'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
				'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
				'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
			);
			$matches[2] = strtolower($matches[2]);
			if (! isset($months[$matches[2]])) {
				return;
			}
			$this->_date = mktime(
				$matches[4], $matches[5], $matches[6],
				$months[$matches[2]], $matches[1], $matches[3]
			);
			if ($this->_date === false) {
				return;
			}

			if ($matches[7][0] == '+') {
				$tzOffset = 3600 * substr($matches[7], 1, 2)
					+ 60 * substr($matches[7], -2);
			} elseif ($matches[7][0] == '-') {
				$tzOffset = -3600 * substr($matches[7], 1, 2)
					- 60 * substr($matches[7], -2);
			} else {
				if (strlen($matches[7]) == 1) {
					$oneHour = 3600;
					$ord = ord($matches[7]);
					if ($ord < ord('M')) {
						$tzOffset = (ord('A') - $ord - 1) * $oneHour;
					} elseif ($ord >= ord('M') && $matches[7] != 'Z') {
						$tzOffset = ($ord - ord('M')) * $oneHour;
					} elseif ($matches[7] == 'Z') {
						$tzOffset = 0;
					}
				}
				switch ($matches[7]) {
					case 'UT':
					case 'GMT': $tzOffset = 0;
				}
			}
			$this->_date -= $tzOffset;
			return;
		}
		if (preg_match('~(\\d{4})-(\\d{2})-(\\d{2})[T\s](\\d{2}):(\\d{2}):(\\d{2})(.*)~', $date, $matches))
		{
			$this->_date = mktime(
				$matches[4], $matches[5], $matches[6],
				$matches[2], $matches[3], $matches[1]
			);
			if ($this->_date == false) {
				return;
			}
			if (isset($matches[7][0])) {
				if ($matches[7][0] == '+' || $matches[7][0] == '-') {
					$tzOffset = 60 * (
						substr($matches[7], 0, 3) * 60 + substr($matches[7], -2)
					);
				} elseif ($matches[7] == 'Z') {
					$tzOffset = 0;
				}
			}
			$this->_date -= $tzOffset;
			return;
		}
        $this->_date = (strtotime($date) == -1) ? false : strtotime($date);
		if ($this->_date) {
			$this->_date -= $tzOffset;
		}
	}

	/**
	 * Will reset the date to the current time
	 * 
	 * @return null
	 */
	function reset()
	{
		$this->_date = strtotime(date("M d Y H:i:s", time()));
		return;
	}
	
	/**
	 * Set the date offset (in hours)
	 *
	 * @access public
	 * @param float The offset in hours
	 */
	function setOffset($offset) {
		$this->_offset = 3600 * $offset;
	}

	/**
	 * Get the date offset (in hours)
	 *
	 * @access public
	 * @return integer
	 */
	function getOffset() {
		return ((float) $this->_offset) / 3600.0;
	}

	/**
	 * Get the Difference
	 * 
	 * Method is used to determine the amount of time difference between the object date
	 * and the given date
	 * 
	 * @param int $date
	 */
	function getDifference( $date = null, $type = '', $roundup = true )
	{
		//preparing the function
		if (is_null($date)) return 0;
		
		//initializing variables
		$date = $this->isTime($date);
		$roundup = ($roundup)? 'floor':'ceil' ;
		
		$difference = $this->_date - $date;
		
		//getting the absolute value
		if ($difference < 0) $difference = 0 - $difference;
		
		switch (strtolower($type))
		{
			case 'miliseconds': default: $return = $difference;break;
			case 'second': case 'seconds': $return = $roundup($difference /60);break;
			case 'minute': case 'minutes': $return = $roundup($difference /60/60);break;
			case 'day':case 'days': $return = $roundup($difference /60/60/24);break;
			case 'month':case 'months': $return = $roundup($difference /60/60/24/30.42);break;
			case 'year':case 'years': $return = $roundup($difference /60/60/24/30.42/12);break;
			
		}
		
		return $return;
	}

	/**
	 * Will substract the desired value from the date
	 * 
	 * @param string $type
	 * @return bool
	 */
	function minus( $number, $type )
	{
		//building the date array
		$date = date_parse( date('Y-m-d H:i:s', $this->_date) );
		
		// building the array to avoid errors
		$t = array('hour' =>0,'minute' =>0,'second' =>0,'month' =>0,'day' =>0,'year' =>0);
		$t[$type] = $number;
		
		//recalculates the time value
		$this->_date = mktime(
			$date['hour'] - $t['hour'],
			$date['minute'] - $t['minute'],
			$date['second'] - $t['second'],
			$date['month'] - $t['month'],
			$date['day'] - $t['day'],
			$date['year'] - $t['year']
			);
		
		
		return true;
	}
	
	/**
	 * Will add the desired value to the date
	 * 
	 * @param string $type
	 * @return bool
	 */
	function add( $number, $type )
	{
		//building the date array
		$date = date_parse( date('Y-m-d H:i:s', $this->_date) );
		
		// building the array to avoid errors
		$t = array('hour' =>0,'minute' =>0,'second' =>0,'month' =>0,'day' =>0,'year' =>0);
		$t[$type] = $number;
		
		//recalculates the time value
		$this->_date = mktime(
			$date['hour'] + $t['hour'],
			$date['minute'] + $t['minute'],
			$date['second'] + $t['second'],
			$date['month'] + $t['month'],
			$date['day'] + $t['day'],
			$date['year'] + $t['year']
			);
		
		
		return true;
	}
	
	/**
	 * Add Net Days
	 * 
	 * While there are days to add, loop to the next day to add
	 * 
	 * 
	 * @param string $days
	 * @return bool
	 */
	function addNet( $days, $type = 'day' )
	{
		while($days != 0)
		{
			//add a new day with every loop
			$this->add(1, $type);
			
			//if this new date is a working day then we can subtract a net day
			if ($this->isWorkingDay())
			{
				$days--;
				continue;
			}
		}
		
		return true;
	}
	
	/**
	 * Will determine if the current time is after the given time
	 * 
	 * @param $date
	 */
	function isAfter( $date )
	{	
		//adjust this time
		$date = $this->isTime($date);
		
		if ($this->_date > $date)
			return true;
		return false;
	}
	
	/**
	 * Will determine if the current time is before the given time
	 * 
	 * @param $date
	 */
	function isBefore( $date )
	{
		//adjust this time
		$date = $this->isTime($date);
		
		if ($this->_date < $date)
			return true;
		return false;
	}
	
	/**
	 * Is this date seven years old or older?
	 * 
	 * Method is responsible for comparing the given date against the 
	 * current date in order to determine if the deference is seven
	 * years or not.
	 * 
	 * @return boolean
	 */
	public function isBeforeSevenYears()
	{
		//initializing variables
		$time = strtotime("-7 Years");
		
		if ($this->isAfter( $time ))
			return true;
		return false;
	}
	
	/**
	 * Is this a Time
	 * 
	 * Method indicates whether the param is a time value or a date value
	 * 
	 * NOTE : If strtotime gets a 0 then it will faile
	 * 
	 * @param $date
	 * @return boolean
	 */
	function isTime( $date = null )
	{
		if (is_null($date)) return $this->_date;
		
		//If this is a true time value as integer, then return it
		if (($timestamp = strtotime($date)) !== false) return strtotime($date);
		
		//Checking to see if the $date is all digits
		$str = preg_replace('/\D/', '', $date); //echo $str;
		if (trim($str) === trim($date))
		{
			return (int)trim($date);
		}
		
		return false;
	}
	
	/**
	 * Is this date Valid?
	 * 
	 * Method will determine if this is a legitamite date or not
	 * 
	 * @return boolean
	 */
	function isValid()
	{
		if ( strlen(trim($this->_date))>1 ) 
			return true;
		return false;
	}
	
	/**
	 * Is This a Weekend?
	 * 
	 * Method is designed to determine if the date given is a weekend day or not
	 * 
	 * @return boolean
	 */
	function isWeekendDay()
	{
		if( date( 'N', $this->_date ) > 5 ) 
			return true;
		return false;
	}
	
	/**
	 * Is This a Working Day?
	 * 
	 * Method is designed to determine if the date given is a working day or not
	 * 
	 * @param $date
	 */
	function isWorkingDay()
	{
		//weekends are not working days
		if ($this->isWeekendDay()) 
			return false;
			
		//have the possibility to add a holiday or other checker here
		
		return true;
	}
	
	/**
	 * Translates needed strings in for Date::toFormat (see {@link PHP_MANUAL#strftime})
	 *
	 * @access protected
	 * @param string $format The date format specification string (see {@link PHP_MANUAL#strftime})
	 * @param int $time Unix timestamp
	 * @return string a date in the specified format
	 */
	function _strftime($format, $time)
	{
		if(strpos($format, '%a') !== false)
			$format = str_replace('%a', $this->_dayToString(date('w', $time), true), $format);
		if(strpos($format, '%A') !== false)
			$format = str_replace('%A', $this->_dayToString(date('w', $time)), $format);
		if(strpos($format, '%b') !== false)
			$format = str_replace('%b', $this->_monthToString(date('n', $time), true), $format);
		if(strpos($format, '%B') !== false)
			$format = str_replace('%B', $this->_monthToString(date('n', $time)), $format);
		$date = strftime($format, $time);
		return $date;
	}

	/**
	 * Translates month number to string
	 *
	 * @access protected
	 * @param int $month The numeric month of the year
	 * @param bool $abbr Return the abreviated month string?
	 * @return string month string
	 */
	function _monthToString($month, $abbr = false)
	{
		switch ($month)
		{
			case 1:  return $abbr ? 'JAN'  : 'JANUARY';
			case 2:  return $abbr ? 'FEB'  : 'FEBRUARY';
			case 3:  return $abbr ? 'MAR'  : 'MARCH';
			case 4:  return $abbr ? 'APR'  : 'APRIL';
			case 5:  return $abbr ? 'MAY'  : 'MAY';
			case 6:  return $abbr ? 'JUN'  : 'JUNE';
			case 7:  return $abbr ? 'JUL'  : 'JULY';
			case 8:  return $abbr ? 'AUG'  : 'AUGUST';
			case 9:  return $abbr ? 'SEP'  : 'SEPTEMBER';
			case 10: return $abbr ? 'OCT'  : 'OCTOBER';
			case 11: return $abbr ? 'NOV'  : 'NOVEMBER';
			case 12: return $abbr ? 'DEC'  : 'DECEMBER';
		}
	}

	/**
	 * Translates day of week number to string
	 *
	 * @access protected
	 * @param int $day The numeric day of the week
	 * @param bool $abbr Return the abreviated day string?
	 * @return string day string
	 */
	function _dayToString($day, $abbr = false)
	{
		switch ($day)
		{
			case 0: return $abbr ? 'SUN' : 'SUNDAY';
			case 1: return $abbr ? 'MON' : 'MONDAY';
			case 2: return $abbr ? 'TUE' : 'TUESDAY';
			case 3: return $abbr ? 'WED' : 'WEDNESDAY';
			case 4: return $abbr ? 'THU' : 'THURSDAY';
			case 5: return $abbr ? 'FRI' : 'FRIDAY';
			case 6: return $abbr ? 'SAT' : 'SATURDAY';
		}
	}
	
	/**
	 * Gets the month of the date
	 * 
	 * @param string $format
	 */
	function toMonth( $format = 'm' )
	{
		$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		$date = ($this->_date !== false) ? date($format, $this->_date) : null;
		return $date;
	}

	/**
	 * Gets the month of the date
	 * 
	 * @param string $format
	 */
	function toDay( $format = 'd' )
	{
		$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		$date = ($this->_date !== false) ? date($format, $this->_date) : null;
		return $date;
	}

	/**
	 * Gets the month of the date
	 * 
	 * @param string $format
	 */
	function toYear( $format = 'Y' )
	{
		$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		$date = ($this->_date !== false) ? date($format, $this->_date) : null;
		return $date;
	}

	/**
	 * Gets the date as an RFC 822 date.
	 *
	 * @return a date in RFC 822 format
	 * @link http://www.ietf.org/rfc/rfc2822.txt?number=2822 IETF RFC 2822
	 * (replaces RFC 822)
	 */
	function toRFC822($local = false)
	{
		$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		$date = ($this->_date !== false) ? date('D, d M Y H:i:s', $date).' +0000' : null;
		return $date;
	}

	/**
	 * Gets the date as an ISO 8601 date.
	 *
	 * @return a date in ISO 8601 (RFC 3339) format
	 * @link http://www.ietf.org/rfc/rfc3339.txt?number=3339 IETF RFC 3339
	 */
	function toISO8601($local = false)
	{
		$date   = ($local) ? $this->_date + $this->_offset : $this->_date;
		$offset = $this->getOffset();
        $offset = ($local && $this->_offset) ? sprintf("%+03d:%02d", $offset, abs(($offset-intval($offset))*60) ) : 'Z';
        $date   = ($this->_date !== false) ? date('Y-m-d\TH:i:s', $date).$offset : null;
		return $date;
	}

	/**
	 * Gets the date as in MySQL datetime format
	 *
	 * @return a date in MySQL datetime format
	 * @link http://dev.mysql.com/doc/refman/4.1/en/datetime.html MySQL DATETIME
	 * format
	 */
	function toMySQL($local = false)
	{
		$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		$date = ($this->_date !== false) ? date('Y-m-d H:i:s', $date) : null;
		return $date;
	}

	/**
	 * Gets the date as UNIX time stamp.
	 *
	 * @return a date as a unix time stamp
	 */
	function toUnix($local = false)
	{
		$date = null;
		if ($this->_date !== false) {
			$date = ($local) ? $this->_date + $this->_offset : $this->_date;
		}
		return $date;
	}

	/**
	 * Gets the date in a specific format
	 *
	 * Returns a string formatted according to the given format. Month and weekday names and
	 * other language dependent strings respect the current locale
	 *
	 * @param string $format  The date format specification string (see {@link PHP_MANUAL#strftime})
	 * @return a date in a specific format
	 */
	function toFormat($format = '%Y-%m-%d %H:%M:%S')
	{
		$date = ($this->_date !== false) ? $this->_strftime($format, $this->_date + $this->_offset) : null;

		return $date;
	}
	
	/**
	 * function returns the date formatted
	 * 
	 * @return string
	 */
	function __toString()
	{
		return (string)$this->_date;
	}

}