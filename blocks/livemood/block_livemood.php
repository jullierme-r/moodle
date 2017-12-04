<?php 

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation,either version 3 of the License,or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not,see <http://www.gnu.org/licenses/>.


/**
 * livemood block definition
 *
 * @package    contrib
 * @subpackage block_livemood
 * @copyright  live-school.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
class block_livemood extends block_base {

	function init() {
	    $this->title = get_string('pluginname','block_livemood');
	}
	
    /**
     * Constrols the block title based on instance configuration
     *
     * @return bool
     */
    function specialization() {
        $this->title = "Live-Mood";
    }
	function get_content() {
	    global $CFG,$USER,$COURSE;
	    if ($this->content !== NULL) {
	        return $this->content;
	    }
		// default role is Student so show Student content
		$html_code_head = '<div style="width:100%; text-align:center; font-size: 0.9em; font-weight:bold">';		
	    $this->content = new stdClass;
		// check first if the user is logged
		if($USER->id){
			// check if the Admin secret key is existing in global block config
			if($CFG->block_livemood_skey){
				/* 
				Standard Role ID Description for moodle 2.8.X
				You are free to adapt the ID to your custom settings
				1 = manager = live-school organization button
				2 = coursecreator = live-school teacher button
				3 = editingteacher = live-school teacher button 	
				4 = teacher = live-school teacher button
				5 = student = live-school student button
				6 = guest = live-school student button
				7 = user = live-school student button
				8 = frontpage = live-school student button
				*/
				$current_user_role_tab = $this->get_user_role($COURSE->id);
				$current_user_role = ($current_user_role_tab[0] == 0) ? $current_user_role_tab[1] : $current_user_role_tab[0];
				$html_code_body = '<script language="JavaScript" type="text/javascript">
									<!--
										var block_livemood_liveWin;
										function block_livemood_go(f,u,n,w,h){
											try{
												if(w == null && h == null){
													//
												}else{
													h = (navigator.appVersion.indexOf("Safari") > -1 && navigator.appVersion.indexOf("Chrome") == -1) ? (h - 80) : h;
													block_livemood_liveWin = window.open(\'\',n,\'toolbar=no,locationbar=no,location=no,menubar=no,scrollbars=no,status=no,statusbar=no,resizable=yes,titlebar=no,width=\'+w+\',height=\'+h);
												}
												if(n == "_blank"){
													switch(u){
														case "a":
															f.log_statut.value = "Administrator";
															f.action = "https://secure.live-school.net/boss/index.lol";
														break;
														case "t":
															f.log_statut.value = "Coach";
															f.action = "https://secure.live-school.net/boss/index.lol";
														break;
													}
													f.target = n;
													f.submit();
												}else{
													if(block_livemood_liveWin){
														if(block_livemood_liveWin.location == "about:blank"){
															if(u == "s"){
																f.action = "https://secure.live-school.net/module.lol";
																f.target = n;
																f.submit();
															}
														}
														block_livemood_liveWin.focus();
													}
												}
											}catch(e){
												if(block_livemood_liveWin){
													block_livemood_liveWin.focus();
												}
											}
										}
									//-->
									</script>';
				if($current_user_role){
					switch($current_user_role){
						case 1:
							// Admin so it's the Live-Mood Organization button
							$html_code_body = '<form name="block_livemood_form" id="block_livemood_form" action="https://secure.live-school.net/boss/index.lol" method="post" target="_blank">'.
										 '<input type="hidden" name="log_statut" value="Administrator">'.
										 '<input type="hidden" name="login" value="'.$USER->email.'">'.
										 '<input type="hidden" name="log_moodle_req" value="'.$CFG->block_livemood_skey.'">'.
										 '<input type="submit" class="adminbut" name="Submit" value="Admin Live" onclick="javascript:this.blur()" onmouseout="javascript:this.blur()">'.
										 '</form>';
						break;
						case 2: case 3: case 4:
							// Consider all those roles as Live-Mood teacher content
							$html_code_body = '<form name="block_livemood_form" id="block_livemood_form" action="https://secure.live-school.net/boss/index.lol" method="post" target="_blank">'.
										 '<input type="hidden" name="log_statut" value="Coach">'.
										 '<input type="hidden" name="login" value="'.$USER->email.'">'.
										 '<input type="hidden" name="log_moodle_req" value="'.$CFG->block_livemood_skey.'">'.
										 '<input type="submit" class="teacbut" name="Submit" value="Teacher Live" onclick="javascript:this.blur()" onmouseout="javascript:this.blur()">'.
										 '</form>';
						break;
						case 5: case 6: case 7: case 8:
							// all these role id should be Students
							$html_code_body .= '<form name="block_livemood_form" id="block_livemood_form" action="" method="post" target="liveroom" onsubmit="javascript:this.target=\'liveroom\'">'.
						 				'<input type="hidden" name="log_moodle_req" value="'.$CFG->block_livemood_skey.'">'.
										'<input type="hidden" name="login" value="'.$USER->email.'">'.
						 				'<input type="button" class="studbut" name="goStudent" value="Student Live" onclick="javascript:block_livemood_go(this.form,\'s\',\'liveroom\',1024,768);this.blur();" onmouseout="javascript:this.blur()">'.
						 				'</form>';
						break;
					}
				}else{
					// Moodle standard ID maybe changed?
					// show all the three buttons. (Moodle users you can change all the code above and below to change your needs)
					$html_code_body .= '<form name="block_livemood_form" id="block_livemood_form" action="" method="post" target="liveroom" onsubmit="javascript:this.target=\'liveroom\'">'.
									'<input type="hidden" name="log_statut" value="">'.
									'<input type="hidden" name="login" value="'.$USER->email.'">'.
									'<input type="hidden" name="log_moodle_req" value="'.$CFG->block_livemood_skey.'">'.
									'<input type="button" class="adminbut" name="goAdmin" value="Admin Live" style="width:60px;padding:6px;line-height:120%" onclick="javascript:block_livemood_go(this.form,\'a\',\'_blank\',null,null);this.blur()" onmouseout="javascript:this.blur()">'.
									'<input type="button" class="teacbut" name="goTeacher" value="Teacher Live" style="width:60px;padding:6px;line-height:120%" onclick="javascript:block_livemood_go(this.form,\'t\',\'_blank\',null,null);this.blur()" onmouseout="javascript:this.blur()">'.
									'<input type="button" class="studbut" name="goStudent" value="Student Live" style="width:60px;padding:6px;line-height:120%" onclick="javascript:block_livemood_go(this.form,\'s\',\'liveroom\',1024,768);this.blur();" onmouseout="javascript:this.blur()">'.
									'</form>';
				}
			}else{
				$html_code_body = '<form name="block_livemood_get_key" id="block_livemood_get_key" method="post" action="https://secure.live-school.net/indexOrg.lol" target="_blank">'.
									'<input type="hidden" name="email" value="'.$USER->email.'">'.
									'</form>'.
									'<span style="color:#FF0000">Manager secret key not found</span><br/>'.
									'<a href="#" onclick="javascript:document.block_livemood_get_key.submit()">Get your secret key here</a>';
			}
		}else{
			// user not logged
			// check if the Admin secret key is existing in global block config
			if($CFG->block_livemood_skey){
				$html_code_body = '<script language="JavaScript" type="text/javascript">
									<!--
										var block_livemood_liveWin;
										function block_livemood_go(f,u,n,w,h){
											try{
												h = (navigator.appVersion.indexOf("Safari") > -1 && navigator.appVersion.indexOf("Chrome") == -1) ? (h - 80) : h;
												block_livemood_liveWin = window.open(\'\',n,\'toolbar=no,locationbar=no,location=no,menubar=no,scrollbars=no,status=no,statusbar=no,resizable=yes,titlebar=no,width=\'+w+\',height=\'+h);
												if(block_livemood_liveWin.location == "about:blank"){
													f.action = "https://secure.live-school.net/module.lol";
													f.target = n;
													f.submit();
												}
												block_livemood_liveWin.focus();
											}catch(e){
												if(typeof(block_livemood_liveWin) == "object"){
													block_livemood_liveWin.focus();
												}
											}
										}
									//-->
									</script>';
				// public side so show Student button
				$html_code_body .= '<form name="block_livemood_form" id="block_livemood_form" action="" method="post" target="liveroom" onsubmit="javascript:this.target=\'liveroom\'">'.
									'<input type="hidden" name="log_moodle_req" value="'.$CFG->block_livemood_skey.'">'.
									'<input type="button" name="goStudent" value="Student Go Live" onclick="javascript:block_livemood_go(this.form,\'s\',\'liveroom\',1024,768);this.blur();" onmouseout="javascript:this.blur()">'.
									'</form>';
			}else{
				$html_code_body = '<div style="width:100%; text-align:center; font-size: 0.9em;"> - </div>';
			}
		}
	    $this->content->text = $html_code_head.$html_code_body.'</div>';
	    $this->content->footer = '<noscript><p style="font-size: 0.9em;">you dont have Javascript enabled which is required to run Live-Mood (live-school) plugin</p></noscript>';
			
	    return $this->content;
	}
    function instance_allow_config(){
        return false;
    }
    function has_config(){
        return true;
    }
	function instance_allow_multiple(){
	  return false;
	}
	function get_user_role($courseid){
		global $CFG,$USER,$DB;
		$roleTab = array();
		$sql_string = "select ra.roleid from ".$CFG->prefix."context,".$CFG->prefix."role_assignments ra where ".$CFG->prefix."context.id=ra.contextid and ra.userid=".$USER->id;
		$tab_sql = $DB->get_records_sql($sql_string);
		$sql_string_course = "select ra.enrolid from ".$CFG->prefix."context,".$CFG->prefix."user_enrolments ra where (".$CFG->prefix."context.instanceid=".$courseid." or ".$CFG->prefix."context.instanceid=0) and ra.id=".$courseid." and ra.userid=".$USER->id;
		$tab_sql_course = $DB->get_records_sql($sql_string_course);
		if(empty($tab_sql)){
			// current user has no any system role
			$roleTab[0] = 0;
		}else{
			sort($tab_sql);
			$sqlArray = $tab_sql[0];
			$roleTab[0] = $sqlArray->roleid;
		}
		if(empty($tab_sql_course)){
			// current user has no any system role
			$roleTab[1]= 0;
		}else{
			sort($tab_sql_course);
			$sqlArray = $tab_sql_course[0];
			$roleTab[1] = $sqlArray->enrolid;
		}
		return $roleTab;
	}
}
