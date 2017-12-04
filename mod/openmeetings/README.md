#OpenMetings Video Conference for Moodle#

This Moodle plugin uses Apache OpenMeetings.
You need your own Apache OpenMeetings instance running.

[![Build Status](https://travis-ci.org/moodlebeuth/moodle-mod_openmeetings.svg?branch=master)](https://travis-ci.org/moodlebeuth/moodle-mod_openmeetings)

##Requirements##

PHP 5.6 or 7.0, OpenMeetings 3.2.x or later and Moodle 2.9 or later.

##tested Versions##

* OpenMeetings: 3.2.x
* Moodle: 2.9, 3.0, 3.1 & 3.2
* Databases: mysql & postgresql
* PHP: 5.6 & 7.0
* OS: Debian Jessie & Linux Mint 18

##Building/Developing

* Checkout necessary branch `git checkout <branch_name>`
* Perform necessary edits
* update `<property name="project.version" value="2.0.2.5" />` in build.xml
* run `ant` command

As a result `version.php` packed will have correct `$plugin->release` and `$plugin->version` set

##Check out:##

http://openmeetings.apache.org

##Mailinglists##

* http://mail-archives.apache.org/mod_mbox/openmeetings-user/
* http://mail-archives.apache.org/mod_mbox/openmeetings-dev/

##Tutorials for installing OpenMeetings and Tools##

* https://cwiki.apache.org/confluence/display/OPENMEETINGS/Tutorials+for+installing+OpenMeetings+and+Tools

##Development: Apache Build Server & JIRA Issue Navigator ##

* https://builds.apache.org/view/M-R/view/OpenMeetings/
* https://issues.apache.org/jira/browse/OPENMEETINGS/?selectedTab=com.atlassian.jira.jira-projects-plugin:summary-panel

##Commercial Support links##

* http://openmeetings.apache.org/commercial-support.html
* mailto:om.unipro@gmail.com
