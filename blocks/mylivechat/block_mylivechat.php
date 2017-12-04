<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Mylivechat block definition
 *
 * @package    contrib
 * @subpackage block_mylivechat
 * @copyright  mylivechat.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

/**
 * Mylivechat block class
 *
 * @copyright mylivechat.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mylivechat extends block_base {

    /**
     * Sets the block title
     *
     * @return none
     */
    function init() {
		$this->title = get_string('pluginname', 'block_mylivechat');
    }

    /**
     * Defines where the block can be added
     *
     * @return array
     */
    function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Constrols global configurability of block
     *
     * @return bool
     */
    function instance_allow_config() {
        return false;
    }

    /**
     * Constrols global configurability of block
     *
     * @return bool
     */
    function has_config() {
        return false;
    }

    /**
     * Constrols if a block header is shown based on instance configuration
     *
     * @return bool
     */
    function hide_header() {
        return isset($this->config->show_header) && $this->config->show_header==0;
    }

    /**
     * Constrols the block title based on instance configuration
     *
     * @return bool
     */
    function specialization() {
        $this->title = "MyLiveChat";
    }

    /**
     * Creates the blocks main content
     *
     * @return string
     */
    function get_content() {

        // Access to settings needed
        global $USER, $COURSE, $OUTPUT, $CFG;

        if (isset($this->content)) {
            return $this->content;
        }

        // Settings variables based on config
        $mylivechat_id = "";
		if(isset($this->config->mylivechat_id))
		{
            $mylivechat_id = $this->config->mylivechat_id;
		}
        $mylivechat_displaytype = "button";
		if(isset($this->config->mylivechat_displaytype))
		{
             $mylivechat_displaytype = $this->config->mylivechat_displaytype;
        }
        $mylivechat_membership = "no";
		if(isset($this->config->mylivechat_membership))
		{
			$mylivechat_membership = $this->config->mylivechat_membership;
		}
        $mylivechat_encrymode = "none";
		if(isset($this->config->mylivechat_encrymode))
		{
			$mylivechat_encrymode = $this->config->mylivechat_encrymode;
		}
        $mylivechat_encrykey = "";
		if(isset($this->config->mylivechat_encrykey))
		{
			$mylivechat_encrykey = $this->config->mylivechat_encrykey;
		}

		$isIntegrateUser = false;
		if($mylivechat_membership == "yes")
		{
			$isIntegrateUser = true;
		}

        $this->content = new stdClass;
        $this->content->text = "";

		$chat_button = "<div class=\"mod_mylivechat\">";    

	  if($mylivechat_displaytype=="embedded")
		{
			$chat_button .= "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://mylivechat.com/chatinline.aspx?hccid=".$mylivechat_id."\"></script>";
		}
    else if($mylivechat_displaytype=="widget")
		{
			$chat_button .= "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://mylivechat.com/chatwidget.aspx?hccid=".$mylivechat_id."\"></script>";
		}
		else if($mylivechat_displaytype=="button")
		{
			$chat_button .= "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatbutton.aspx?hccid=".$mylivechat_id."\"></script>";
		}
		else if($mylivechat_displaytype=="box")
		{
			$chat_button .= "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatbox.aspx?hccid=".$mylivechat_id."\"></script>";
		}
    else if($mylivechat_displaytype=="link")
		{
			$chat_button .= "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatlink.aspx?hccid=".$mylivechat_id."\"></script>";
		}
		else
		{
			$chat_button .= "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://mylivechat.com/chatapi.aspx?hccid=".$mylivechat_id."\"></script>";
		}

		if ($USER!=null && $USER->id!=null && !isguestuser($USER) && $isIntegrateUser==true) 
		{
			if($mylivechat_encrykey==null || strlen($mylivechat_encrykey) == 0)
			{
				$chat_button .=  "<script type=\"text/javascript\">MyLiveChat_SetUserName('".$this->EncodeJScript($USER->username)."');</script>";
			}
			else
			{
				$chat_button .=  "<script type=\"text/javascript\">MyLiveChat_SetUserName('".$this->EncodeJScript($USER->username)."','".$this->GetEncrypt($USER->id."",$mylivechat_encrymode,$mylivechat_encrykey)."');</script>";
			}
		}

		$chat_button .= "</div>";

		$this->content->text = $chat_button;

        //$this->page->requires->js_init_call('M.block_simple_clock.initSimpleClock', $arguments, false, $jsmodule);

        $this->content->footer = '';
        return $this->content;
    }

	public function GetEncrypt($data, $encrymode,$encrykey)
	{
		if($encrymode=="basic")
			return $this->BasicEncrypt($data,$encrykey);
		return $data;
	}

	public function BasicEncrypt($data, $encryptkey)
	{
		$EncryptLoopCount = 4;

		$vals = $this->MakeArray($data, true);
		$keys = $this->MakeArray($encryptkey, false);

		$len = sizeof($vals);
		$len2 = sizeof($keys);

		for ($t = 0; $t < $EncryptLoopCount; $t++)
		{
			for ($i = 0; $i < $len; $i++)
			{
				$v = $vals[$i];
				$im = ($v + $i) % 5;

				for ($x = 0; $x < $len; $x++)
				{
					if ($x == $i)
						continue;
					if ($x % 5 != $im)
						continue;

					for ($y = 0; $y <$len2; $y++)
					{
						$k = $keys[$y];
						if ($k == 0)
							continue;

						$vals[$x] += $v % $k;
					}
				}
			}
		}
		return implode('-', $vals);
	}

	public function MakeArray($str, $random)
	{
		$len = pow(2, floor(log(strlen($str), 2)) + 1) + 8;
		if ($len < 32) $len = 32;

		$arr = Array();
		$strarr = str_split($str);
		if ($random==true)
		{
			for ($i = 0; $i < $len; $i++)
				$arr[] = ord($strarr[rand() % strlen($str)]);

			$start = 1 + rand() % ($len - strlen($str) - 2);

			for ($i = 0; $i < strlen($str); $i++)
				$arr[$start + $i] = ord($strarr[$i]);

			$arr[$start - 1] = 0;
			$arr[$start + strlen($str)] = 0;
		}
		else
		{
			for ($i = 0; $i < $len; $i++)
				$arr[] = ord($strarr[$i % strlen($str)]);
		}

		return $arr;
	}

	public function EncodeJScript($str)
	{
		$chars="0123456789ABCDEF";
		$chars = str_split($chars);

		$sb = "";
		$l = strlen($str);
		$strarr = str_split($str);
		for ($i = 0; $i < $l; $i++)
		{
			$c = $strarr[$i];
			if ($c == '\\' || $c == '"' || $c == '\'' || $c == '>' || $c == '<' || $c == '&' || $c == '\r' || $c == '\n')
			{
				if ($sb == "")
				{
					if ($i > 0)
					{
						$sb .= substr($str, 0, $i);
					}
				}
				if ($c == '\\')
				{
					$sb.="\\x5C";
				}
				else if ($c == '"')
				{
					$sb.="\\x22";
				}
				else if ($c == '\'')
				{
					$sb.="\\x27";
				}
				else if ($c == '\r')
				{
					$sb.="\\x0D";
				}
				else if ($c == '\n')
				{
					$sb.="\\x0A";
				}
				else if ($c == '<')
				{
					$sb.="\\x3C";
				}
				else if ($c == '>')
				{
					$sb.="\\x3E";
				}
				else if ($c == '&')
				{
					$sb.="\\x26";
				}
				else
				{
					$code = $c;
					$a1 = $code & 0xF;
					$a2 = ($code & 0xF0) / 0x10;
					$sb.="\\x";
					$sb.=$chars[$a2];
					$sb.=$chars[$a1];
				}
			}
			else if ($sb != "")
			{
				$sb .= $c;
			}
		}
		if ($sb != "")
			return $sb;
		return $str;
	}
}
?>